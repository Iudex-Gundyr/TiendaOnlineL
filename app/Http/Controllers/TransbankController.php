<?php

namespace App\Http\Controllers;

use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;
use Illuminate\Http\Request;

class TransbankController extends Controller
{
    public function __construct()
    {
        if(app()->environment('production'))
        {
            WebpayPlus::configureForProduction(
                env('webpay_plus_cc'),
                env('webpay_plus_api_key')
            );
        } else {
            WebpayPlus::configureForTesting();
        }
    }

    public function star_web_pay_plus_transaction($compraId, $clienteId, $Total)
    {

        $transaccion = (new Transaction)->create(
            $compraId, //buy order
            $clienteId,
            $Total,
            route('confirmar_pago')
        );

        $url = $transaccion->getUrl();
        $token = $transaccion->getToken();

        return view('redirect_to_transbank', compact('url', 'token'));
    }


}
