<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarritoCompraController;
use App\Http\Controllers\TransbankController;
use App\Models\Compra;
use App\Models\CompraMateriales;
use App\Models\CompraOferta;
use App\Models\CarritoMaterial;
use App\Models\CarritoOferta;
use Carbon\Carbon;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;

use Transbank\Webpay\Webpay;


class pagarController extends Controller
{
    public function pagar()
    {

            $clienteId = Auth::guard('cliente')->id();
            if (!$clienteId) {
                return back()->with('error', 'Debes iniciar sesión para armar y ver tu carrito ♥');
            }
            // Resolver el controlador CarritoCompraController
            $carritoCompraController = app(CarritoCompraController::class);
            // Llamar a la función tablaCarritoOferta y obtener el resultado
            $comprasofertas = $carritoCompraController->tablaCarritoOferta($clienteId);
            $compras = $carritoCompraController->tablaCarrito($clienteId);
            $totalPagar = $carritoCompraController->pagarCarrito($clienteId);
            $totalPagarOferta = $carritoCompraController->pagarCarritoOferta($clienteId);

            //Validar existencias sin registrar
            $validacionOfertas = $this->validarComprasOferta($comprasofertas);
            if ($validacionOfertas['error']) {
                return back()->with('error', $validacionOfertas['mensaje']);
            }
            $validacionCompras = $this->validarCompras($compras);
            if ($validacionCompras['error']) {
                return back()->with('error', $validacionCompras['mensaje']);
            }
            //Crear compra N° 
            $compra = new Compra();
            $compra->fk_id_cliente = $clienteId;
            $compra->fk_id_estadoc = 1;
            $compra->fk_id_estadoel = 1;
            $compra->created_at = now();
            $compra->save();
            $compraId = $compra->id;
            //Validar existencias pero registrarlos registrando
            $validacionComprasRegistro = $this->validarComprasRegistro($compras,$compraId);
            if ($validacionCompras['error']) {
                return back()->with('error', $validacionCompras['mensaje']);
            }
            $validacionComprasRegistro = $this->validarComprasOfertaRegistro($comprasofertas,$compraId);
            if ($validacionCompras['error']) {
                return back()->with('error', $validacionCompras['mensaje']);
            }

            $totalPagar = $carritoCompraController->pagarCarrito($clienteId);
            $totalPagarOferta = $carritoCompraController->pagarCarritoOferta($clienteId);
                    // Verificar si la suma es cero
            if ($totalPagar + $totalPagarOferta == 0) {
                return back()->with('error', 'No tienes productos en el carrito'); // Redirigir con mensaje de error
            }
            // Calcula el total y redondea a 0 decimales
            $Total = round($totalPagar + $totalPagarOferta + ($totalPagar + $totalPagarOferta)*0.02, 0);
            $transbankController = app(TransbankController::class);
            $url_to_pay = $transbankController->star_web_pay_plus_transaction($compraId, $clienteId, $Total);

            return $url_to_pay;
    }
    
    public function validarComprasOferta($comprasofertas)
    {
        foreach ($comprasofertas as $oferta) {
            if ($oferta->cantidad_en_compra < $oferta->cantof) {

                return [
                    'error' => true,
                    'mensaje' => 'No hay suficientes existencias disponibles para ' . $oferta->nombrem . '. Por favor, cambie la cantidad de compras. ♥'
                ];
            }
            if ($oferta->fechaexp > now()) {
                return [
                    'error' => true,
                    'mensaje' => 'La oferta para ' . $oferta->nombrem . ' ha expirado. Por favor, eliminela de su carrito de compra.'
                ];
            }
        }
        return [
            'error' => false,
        ];
    }
    public function validarCompras($compras)
    {
        foreach ($compras as $compra) {
            // Asumiendo que `$cantidad_restante` y `$cantidad` son columnas de tu modelo
            if ($compra->cantidad_restante < $compra->cantidad) {
                return [
                    'error' => true,
                    'mensaje' => 'No hay suficientes existencias disponibles para ' . $compra->nombrem . '. Por favor, cambie la cantidad de compras. ♥'
                ];
            }
        }
        return [
            'error' => false,
        ];
    }
    public function validarComprasOfertaRegistro($comprasofertas,$id)
    {
        foreach ($comprasofertas as $oferta) {
            if ($oferta->cantidad_en_compra < $oferta->cantof) {
                // Retornar un array con el mensaje de error
                return [
                    'error' => true,
                    'mensaje' => 'No hay suficientes existencias disponibles para ' . $oferta->nombrem . '. Por favor, cambie la cantidad de compras. ♥'
                ];


            }
            $compraOferta = new CompraOferta();
            $compraOferta->cantidad = $oferta->cantof;
            $compraOferta->valor = $oferta->totalPagar;
            $compraOferta->created_at = Carbon::now()->setTimezone('America/Santiago');
            $compraOferta->fk_id_moferta = $oferta->idmatof; // Suponiendo que esto es correcto
            $compraOferta->fk_id_compra = $id; // Guardar el ID de la compra
            $compraOferta->fk_id_estadoel = 2;
            $compraOferta->save();
        }
        return [
            'error' => false,
        ];
    } 
    public function validarComprasRegistro($compras, $id)
    {
        foreach ($compras as $compra) {
            // Asumiendo que `$cantidad_restante` y `$cantidad` son columnas de tu modelo
            if ($compra->cantidad_restante < $compra->cantidad) {
                return [
                    'error' => true,
                    'mensaje' => 'No hay suficientes existencias disponibles para ' . $compra->nombrem . '. Por favor, cambie la cantidad de compras. ♥'
                ];
            }
            // Crear y guardar CompraMateriales
            $compraMaterial = new CompraMateriales();
            $compraMaterial->cantidad = $compra->cantidad;
            $compraMaterial->valorcm = $compra->valor_total;
            $compraMaterial->created_at = Carbon::now()->setTimezone('America/Santiago');
            $compraMaterial->fk_id_materiales = $compra->fk_id_material; // Suponiendo que esto es correcto
            $compraMaterial->fk_id_compra = $id; // Guardar el ID de la compra
            $compraMaterial->fk_id_estadoel = 2;
            $compraMaterial->save();
        }
        
        return [
            'error' => false,
        ];
    }

    public function confirmar_pago(Request $request)
    {
        $token = $request->input('token_ws');
        $confirmacion = (new Transaction)->commit($token);
        $clienteId = Auth::guard('cliente')->id();
        if (!$clienteId) {
            return back()->with('error', 'Debes iniciar sesión para pagar y ver tu carrito ♥');
        }
    
        if ($confirmacion->isApproved()) {
            // Transacción aprobada
            $compra = Compra::find($confirmacion->buyOrder);
    
            if ($compra) {
                $compra->fk_id_estadoc = 2; // Estado de la compra a "aprobada"
                $compra->save();
    
                // Actualizar estado de las compras de oferta asociadas
                CompraOferta::where('fk_id_compra', $confirmacion->buyOrder)
                    ->update(['fk_id_estadoel' => 1]); // Estado de la compra de oferta a "aprobada"
    
                // Actualizar estado de los materiales comprados asociados
                CompraMateriales::where('fk_id_compra', $confirmacion->buyOrder)
                    ->update(['fk_id_estadoel' => 1]); // Estado del material comprado a "aprobado"
                CarritoMaterial::where('fk_id_cliente', $clienteId)->delete();
                CarritoOferta::where('fk_id_cliente', $clienteId)->delete();

                return redirect()->route('verCarrito')
                    ->with('success_message', '¡Gracias por comprar con nosotros! Recibirás una confirmación pronto.');
            } else {
                return redirect()->route('verCarrito')
                    ->with('error_message', 'Compra no encontrada. Por favor, inténtalo de nuevo o contacta con soporte.');
            }
        } else {
            // Transacción no aprobada
    
            // Eliminar registros de CompraMateriales y CompraOferta no aprobados
            CompraMateriales::where('fk_id_compra', $confirmacion->buyOrder)->delete();
            CompraOferta::where('fk_id_compra', $confirmacion->buyOrder)->delete();

            // Actualizar estado de la compra a "fallida" (estado 4 en la base de datos)
            $compra = Compra::find($confirmacion->buyOrder);
            if ($compra) {
                $compra->fk_id_estadoc = 4; // Estado de la compra a "fallida"
                $compra->save();
            }
    
            return redirect()->route('verCarrito')
                ->with('error', 'Pago no aprobado. Por favor, inténtalo de nuevo o contacta con nuestro soporte. :(');
        }
    }
    
    
    

}
