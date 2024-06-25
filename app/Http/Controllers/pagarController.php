<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarritoCompraController;
use App\Models\Compra;
use App\Models\CompraMateriales;
use App\Models\CompraOferta;
use Carbon\Carbon;
use Transbank\Webpay\Webpay;


class pagarController extends Controller
{
    public function pagar()
    {
        try {
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

            $Total = $totalPagar + $totalPagarOferta + 3500;


            return back()->with('success_message', '¡Todo está bien!');
    
        } catch (\Exception $e) {
            // Registra el error en los logs
            Log::error('Error al llamar a la función tablaCarritoOferta: ' . $e->getMessage());
    
            // Devuelve una respuesta de error
            return response('<p>Error al procesar la solicitud.</p>', 500)->header('Content-Type', 'text/html');
        }
    }
    
    public function validarComprasOferta($comprasofertas)
    {
        foreach ($comprasofertas as $oferta) {
            if ($oferta->cantidad_en_compra < $oferta->cantof) {
                // Retornar un array con el mensaje de error
                return [
                    'error' => true,
                    'mensaje' => 'No hay suficientes existencias disponibles para ' . $oferta->nombrem . '. Por favor, cambie la cantidad de compras. ♥'
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
    

}
