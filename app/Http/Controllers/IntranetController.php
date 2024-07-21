<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB; 
use App\Models\CompraMateriales;
use App\Models\CompraOferta;
use App\Models\Compra;
use Carbon\Carbon;

class IntranetController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function dashboard()
    {
        $s30d = $this->Compras30Dias();
        $s60d = $this->Compras60Dias();
        $s90d = $this->Compras90Dias();
        $pEntregar = $this->PorEntregar();
        $Entregadas = $this->entregadas();
        $cProceso = $this->Proceso();
        $materialVendido = $this->materialVendido();
        $materialOfertaVendido = $this->materialOfertaVendido();
        return view('Intranet/Principales/Dashboard',compact('s30d','s60d','s90d','pEntregar','Entregadas','cProceso','materialVendido','materialOfertaVendido'));
    }
    public function materialOfertaVendido()
    {
        $resultados = DB::table('materiales as m')
            ->join('material_oferta as mo', 'mo.fk_id_material', '=', 'm.id')
            ->join('ofertas as o', 'o.id', '=', 'mo.fk_id_oferta')
            ->join('compra_oferta as co', 'mo.id', '=', 'co.fk_id_moferta')
            ->selectRaw('SUM(co.cantidad) AS suma, SUM(co.valor) AS valor, m.nombrem, m.codigob')
            ->where('co.created_at', '>=', now()->subDays(60))
            ->groupBy('m.nombrem', 'm.codigob')
            ->orderBy('suma', 'desc')  // Ordenar en orden descendente por la suma
            ->first(); // Obtener solo el primer registro
        
        return $resultados;
    }
    public function materialVendido()
    {
        $resultados = DB::table('compra_materiales as cm')
            ->join('materiales as m', 'm.id', '=', 'cm.fk_id_materiales')
            ->select(DB::raw('SUM(cm.cantidad) as suma, SUM(cm.valorcm) as valor, m.nombrem, m.codigob'))
            ->where('cm.created_at', '>=', now()->subDays(60))
            ->groupBy('m.nombrem', 'm.codigob')
            ->orderBy('suma', 'desc')
            ->first();
        
        return $resultados;
    }

    public function Proceso()
    {
        $Proceso = Compra::where('fk_id_estadoc', 1)
        ->where('fk_id_estadoel', 1)
        ->count();
        return $Proceso;
    }


    public function entregadas()
    {
        $entregas = Compra::where('fk_id_estadoc', 3)
        ->where('fk_id_estadoel', 1)
        ->count();
        return $entregas;
    }

    public function PorEntregar()
    {
        $compraCount = Compra::where('fk_id_estadoc', 2)
        ->where('fk_id_estadoel', 1)
        ->count();
        return $compraCount;
    }


    public function Compras30Dias()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $sumaCompraMateriales = CompraMateriales::where('fk_id_estadoel', 1)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('valorcm');
        $sumaCompraOferta = CompraOferta::where('fk_id_estadoel', 1)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('valor');
        $sumaTotal = $sumaCompraMateriales + $sumaCompraOferta;

        return $sumaTotal;
    }
    public function Compras60Dias()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(60);
        $sumaCompraMateriales = CompraMateriales::where('fk_id_estadoel', 1)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('valorcm');
        $sumaCompraOferta = CompraOferta::where('fk_id_estadoel', 1)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('valor');
        $sumaTotal = $sumaCompraMateriales + $sumaCompraOferta;

        return $sumaTotal;
    }
    public function Compras90Dias()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(90);
        $sumaCompraMateriales = CompraMateriales::where('fk_id_estadoel', 1)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('valorcm');
        $sumaCompraOferta = CompraOferta::where('fk_id_estadoel', 1)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('valor');
        $sumaTotal = $sumaCompraMateriales + $sumaCompraOferta;

        return $sumaTotal;
    }


}


