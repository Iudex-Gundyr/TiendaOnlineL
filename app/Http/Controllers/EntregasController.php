<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\CompraOferta;
use App\Models\CompraMateriales;
use Illuminate\Support\Facades\DB;

class EntregasController extends Controller
{
    public function entregas()
    {
        $compras = DB::table('compra as c')
            ->join('estadoc as e', 'c.fk_id_estadoc', '=', 'e.id')
            ->select('c.id', 'c.created_at', 'e.nombreest')
            ->where('fk_id_estadoc', 2)
            ->orderBy('id', 'desc')
            ->get();
        return view('Intranet/Entregas/Entregas',compact('compras'));
    }

    public function verDetallesEntrega($id)
    {
        $cliente =  DB::table('compra as c')
        ->join('cliente as cl', 'cl.id', '=', 'c.fk_id_cliente')
        ->join('ciudad as ci', 'ci.id', '=', 'cl.fk_id_ciudad')
        ->join('region as r', 'r.id', '=', 'ci.fk_id_region')
        ->join('pais as pa', 'pa.id', '=', 'r.fk_id_pais')
        ->select(
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
        )->where('c.id',$id)
        ->first();

        $detallesCompra = DB::table('compra_materiales as cm')
        ->join('materiales as m', 'cm.fk_id_materiales', '=', 'm.id')
        ->select('cm.valorcm', 'cm.cantidad', 'cm.created_at', 'm.nombrem','m.id')
        ->where('cm.fk_id_compra', $id) 
        ->get();

        $detallesCompraOferta = DB::table('compra_oferta as co')
            ->join('material_oferta as mo', 'co.id', '=', 'mo.fk_id_material')
            ->join('materiales as m', 'm.id', '=', 'mo.fk_id_material')
            ->select('co.valor', 'co.cantidad', 'm.nombrem', 'm.id')
            ->where('co.fk_id_compra', $id) // Suponiendo que hay una columna fk_id_compra en compra_oferta
            ->get();

        return view('Intranet/Entregas/DetalleEntregas/DetalleEntregas', compact('detallesCompra','detallesCompraOferta','cliente','id'));
    }
}
