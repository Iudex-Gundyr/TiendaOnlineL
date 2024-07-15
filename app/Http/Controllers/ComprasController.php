<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompraMateriales;
use App\Models\CompraOferta;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function MisCompras()
    {
        $clienteId = Auth::guard('cliente')->id();
        if (!$clienteId) {
            return back()->with('error', 'Debes iniciar sesión para armar y ver tu carrito ♥');
        }
        $compras = DB::table('compra as c')
        ->join('estadoc as e', 'c.fk_id_estadoc', '=', 'e.id')
        ->select('c.id', 'c.created_at', 'e.nombreest')
        ->where('fk_id_cliente', $clienteId)
        ->orderBy('c.created_at', 'desc')  // Ordenar por fecha de creación ascendente
        ->get();

        return view('Tienda/Cliente/MisCompras', compact('compras'));
    }

    public function DetallesMisCompras($id)
    {
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


        return view('Tienda.Cliente.DetallesMisCompras', compact('detallesCompra','detallesCompraOferta'));
    }
}
