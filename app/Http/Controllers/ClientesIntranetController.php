<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class ClientesIntranetController extends Controller
{
    public function ClienteIntranet()
    {
    $clientes = DB::table('cliente as cl')
        ->join('ciudad as ci', 'ci.id', '=', 'cl.fk_id_ciudad')
        ->join('region as r', 'r.id', '=', 'ci.fk_id_region')
        ->join('pais as pa', 'pa.id', '=', 'r.fk_id_pais')
        ->select(
            'cl.id',
            'cl.nombrec', 
            'cl.correo', 
            'cl.direccion', 
            'cl.blockd', 
            'cl.numerod', 
            'cl.documentacion', 
            'cl.telefono', 
            'cl.telefonof', 
            'ci.nombreci', 
            'r.nombrere', 
            'pa.nombrepa'
        )->limit(30)
        ->get();
        return view('Intranet/Clientes/Cliente', compact('clientes'));
    }

    public function ComprasCliente($id)
    {
        $clientes = DB::table('cliente as cl')
        ->join('ciudad as ci', 'ci.id', '=', 'cl.fk_id_ciudad')
        ->join('region as r', 'r.id', '=', 'ci.fk_id_region')
        ->join('pais as pa', 'pa.id', '=', 'r.fk_id_pais')
        ->select(
            'cl.id',
            'cl.nombrec', 
            'cl.correo', 
            'cl.direccion', 
            'cl.blockd', 
            'cl.numerod', 
            'cl.documentacion', 
            'cl.telefono', 
            'cl.telefonof', 
            'ci.nombreci', 
            'r.nombrere', 
            'pa.nombrepa'
        )->where('cl.id',$id)
        ->first();
        $compras = DB::table('compra as c')
        ->join('estadoc as e', 'c.fk_id_estadoc', '=', 'e.id')
        ->select('c.id', 'c.created_at', 'e.nombreest')
        ->where('fk_id_cliente',$id)
        ->orderBy('id', 'desc')
        ->get();
        return view('Intranet/Clientes/Compras/ComprasCliente',compact('clientes','compras'));
    }
}
