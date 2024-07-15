<?php

namespace App\Http\Controllers;

use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;
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


class TransbankController extends Controller
{
    public function __construct()
    {
        if (app()->environment('production')) {
            \Log::info('Configuring for production environment');
            WebpayPlus::configureForProduction(
                env('WEBPAY_PLUS_CC'),
                env('WEBPAY_PLUS_API_KEY')
            );
        } else {
            \Log::info('Configuring for testing environment');
            WebpayPlus::configureForTesting();
        }
    }


    public function star_web_pay_plus_transaction($compraId, $clienteId, $Total)
    {
        try {
            $transaction = (new Transaction)->create(
                $compraId, // buy order
                $clienteId,
                $Total,
                route('confirmar_pago', ['compraId' => $compraId]) // Pasar $compraId como parámetro
            );
    
            \Log::info('Transaction created', [
                'url' => $transaction->getUrl(),
                'token' => $transaction->getToken(),
                'commerce_code' => env('WEBPAY_PLUS_CC'),
                'api_key' => env('WEBPAY_PLUS_API_KEY')
            ]);
    
            $url = $transaction->getUrl();
            $token = $transaction->getToken();
    
            return view('redirect_to_transbank', compact('url', 'token'));
        } catch (\Exception $e) {
            \Log::error('Error creating transaction', [
                'message' => $e->getMessage(),
                'commerce_code' => env('WEBPAY_PLUS_CC'),
                'api_key' => env('WEBPAY_PLUS_API_KEY')
            ]);
            return back()->withErrors(['msg' => 'Ocurrió un error al procesar la transacción: ' . $e->getMessage()]);
        }
    }

    public function confirmar_Pago(Request $request, $compraId)
    {
        \Log::info('Request received', $request->all());
    
        $token = $request->input('token_ws');
        $tokenAnular = $request->input('TBK_TOKEN');
    
        \Log::info('Tokens received', ['token_ws' => $token, 'TBK_TOKEN' => $tokenAnular]);
    
        if (empty($token)) {
            $token = $tokenAnular;
        }
    
        $compra = Compra::find($compraId);
        if ($compra && $compra->tokenpago) {
            $token = $compra->tokenpago;
        }
    
        \Log::info('Token used for transaction', ['token' => $token]);
    
        if ($token) {
            try {
                $transaction = new Transaction();
                $confirmacion = $transaction->commit($token);
                \Log::info('Transaction commit response', ['response' => $confirmacion]);
            } catch (\Exception $e) {
                \Log::error('Transaction commit error', [
                    'message' => $e->getMessage(),
                    'token' => $token,
                    'environment' => config('app.env'),
                    'api_key' => config('transbank.api_key'),
                    'commerce_code' => config('transbank.commerce_code')
                ]);
    
                if ($token == $tokenAnular) {
                    $id = $request->input('TBK_ORDEN_COMPRA');
    
                    if ($compra) {
                        $compra->fk_id_estadoc = 4; // Estado de la compra a "fallida"
                        $compra->tokenpago = $tokenAnular;
                        $compra->save();
    
                        CompraMateriales::where('fk_id_compra', $compra->id)->delete();
                        CompraOferta::where('fk_id_compra', $compra->id)->delete();
                    }
    
                    return redirect()->route('verCarrito')
                        ->with('error', 'La compra ha sido anulada.');
                }
    
                return redirect()->route('verCarrito')
                    ->with('error', 'Ocurrió un error al procesar la transacción: ' . $e->getMessage());
            }
    
            $clienteId = Auth::guard('cliente')->id();
            if (!$clienteId) {
                return back()->with('error', 'Debes iniciar sesión para pagar y ver tu carrito ♥');
            }
    
            if ($confirmacion->isApproved()) {
                if ($compra) {
                    $compra->fk_id_estadoc = 2; // Estado de la compra a "aprobada"
                    $compra->tokenpago = $token; // Guardar el token de pago
                    $compra->save();
    
                    CompraOferta::where('fk_id_compra', $confirmacion->buyOrder)
                        ->update(['fk_id_estadoel' => 1]);
    
                    CompraMateriales::where('fk_id_compra', $confirmacion->buyOrder)
                        ->update(['fk_id_estadoel' => 1]);
    
                    CarritoMaterial::where('fk_id_cliente', $clienteId)->delete();
                    CarritoOferta::where('fk_id_cliente', $clienteId)->delete();
    
                    return redirect()->route('verCarrito')
                        ->with('success_message', '¡Gracias por comprar con nosotros! Recibirás una confirmación pronto.');
                } else {
                    return redirect()->route('verCarrito')
                        ->with('error_message', 'Compra no encontrada. Por favor, inténtalo de nuevo o contacta con soporte.');
                }
            } else {
                if ($compra) {
                    $compra->fk_id_estadoc = 4; // Estado de la compra a "fallida"
                    $compra->tokenpago = $token;
                    $compra->save();
    
                    CompraMateriales::where('fk_id_compra', $confirmacion->buyOrder)->delete();
                    CompraOferta::where('fk_id_compra', $confirmacion->buyOrder)->delete();
                }
    
                return redirect()->route('verCarrito')
                    ->with('error', 'Pago no aprobado. Por favor, inténtalo de nuevo o contacta con nuestro soporte. :(');
            }
        } else {
            \Log::error('No valid token received', ['request' => $request->all()]);
    
            if ($compra) {
                $compra->fk_id_estadoc = 4; // Estado de la compra a "fallida"
                $compra->tokenpago = 'fallida';
                $compra->save();
                CompraMateriales::where('fk_id_compra', $confirmacion->buyOrder)->delete();
                CompraOferta::where('fk_id_compra', $confirmacion->buyOrder)->delete();
            }
            return redirect()->route('verCarrito')
                ->with('error', 'No se recibió un token válido para procesar el pago. Por favor, inténtalo de nuevo o contacta con nuestro soporte.');
        }
    }
    


}
