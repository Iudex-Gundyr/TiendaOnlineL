<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Materiales;
use App\Models\Ofertas;
use App\Models\MaterialOferta;
use App\Models\Descripcion;
use App\Models\Fotos;
use App\Models\Marcas;
use App\Models\Categorias;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
class TiendaController extends Controller
{
    public function Tienda()
    {
        return Redirect::route('productos', 1);
    }
    

    public function tablaMateriales($page)
    {
        $perPage = 12; // Número de productos por página
        $offset = ($page - 1) * $perPage; // Calcular el offset para la paginación
    
        // Ajustar la longitud máxima permitida para GROUP_CONCAT
        DB::statement('SET SESSION group_concat_max_len = 1000000');
    
        $materiales = Materiales::select(
                'materiales.id',
                'materiales.codigob',
                'materiales.nombrem',
                'materiales.valorm',
                DB::raw('(SELECT GROUP_CONCAT(f.fotografia SEPARATOR ", ") 
                          FROM fotos AS f 
                          WHERE f.fk_id_material = materiales.id) AS fotos'),
                DB::raw('(SELECT GROUP_CONCAT(d.nombredes SEPARATOR ", ") 
                          FROM descripcion AS d 
                          WHERE d.fk_id_material = materiales.id) AS descripciones'),
                DB::raw('SUM(
                            COALESCE(c.total_cantidad, 0)
                            - COALESCE(co.total_cantidad, 0)
                            - COALESCE(cm.total_cantidad, 0)
                        ) AS cantidad_restante')
            )
            ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(cantidad) AS total_cantidad 
                                FROM cantidadmat 
                                GROUP BY fk_id_materiales) AS c'), 'materiales.id', '=', 'c.fk_id_materiales')
            ->leftJoin(DB::raw('(SELECT fk_id_material, SUM(cantidad) AS total_cantidad 
                                FROM material_oferta 
                                WHERE fk_id_estadoel = 1 
                                GROUP BY fk_id_material) AS co'), 'materiales.id', '=', 'co.fk_id_material')
            ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(
                                    CASE
                                        WHEN (created_at >= NOW() - INTERVAL 1 HOUR OR fk_id_estadoel = 1)
                                        THEN cantidad
                                        ELSE 0
                                    END
                                ) AS total_cantidad 
                                FROM compra_materiales 
                                GROUP BY fk_id_materiales) AS cm'), 'materiales.id', '=', 'cm.fk_id_materiales')
            ->where('materiales.fk_id_estadoel', 1)
            ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
            ->havingRaw('cantidad_restante > 0') // Filtrar por cantidad_restante mayor que 0
            ->orderByDesc('materiales.id')
            ->offset($offset)
            ->limit($perPage)
            ->get();
    
        return $materiales;
    }
    
    
    public function productos($n = 1)
    {
        $ofertas = $this->tablaOfertas();
        $materiales = $this->tablaMateriales($n);
        $marcas = Marcas::where('fk_id_estadoel',1)->orderby('nombremar','asc')->get();
        $categorias = Categorias::where('fk_id_estadoel',1)->orderby('nombrecat','asc')->get();
        $totalProductos = Materiales::where('fk_id_estadoel', 1)->count(); // Total de productos disponibles
    
        return view('Tienda/Productos/Productos', compact('materiales', 'n', 'totalProductos','ofertas','marcas','categorias'));
    }
    public function filtrarProductos(Request $request, $n = 1)
    {
        // Obtener los valores del formulario
        $idCategoria = $request->filled('categoria') ? $request->input('categoria') : "";
        $idMarca = $request->filled('marca') ? $request->input('marca') : "";
        $nombreProducto = $request->filled('nombreProducto') ? $request->input('nombreProducto') : "";
        
        // Obtener las ofertas y materiales filtrados
        $ofertas = $this->tablaOfertas();
        $materiales = $this->filtrartablaMateriales($n, $idCategoria, $idMarca, $nombreProducto);
    
        // Obtener todas las marcas y categorías disponibles
        $marcas = Marcas::where('fk_id_estadoel', 1)->orderBy('nombremar', 'asc')->get();
        $categorias = Categorias::where('fk_id_estadoel', 1)->orderBy('nombrecat', 'asc')->get();
        $totalProductos = Materiales::where('fk_id_estadoel', 1)->count();
    
        // Retornar la vista con los datos
        return view('Tienda/Productos/Productos', compact('materiales', 'n', 'totalProductos', 'ofertas', 'marcas', 'categorias'));
    }
    
    
    public function filtrartablaMateriales($n, $idCategoria, $idMarca, $nombreProducto)
    {
        $perPage = 12; // Número de productos por página
        $offset = ($n - 1) * $perPage; // Calcular el offset para la paginación

        // Ajustar la longitud máxima permitida para GROUP_CONCAT
        DB::statement('SET SESSION group_concat_max_len = 1000000');
    
        $materiales = Materiales::select(
                'materiales.id',
                'materiales.codigob',
                'materiales.nombrem',
                'materiales.valorm',
                DB::raw('(SELECT GROUP_CONCAT(f.fotografia SEPARATOR ", ") 
                          FROM fotos AS f 
                          WHERE f.fk_id_material = materiales.id) AS fotos'),
                DB::raw('(SELECT GROUP_CONCAT(d.nombredes SEPARATOR ", ") 
                          FROM descripcion AS d 
                          WHERE d.fk_id_material = materiales.id) AS descripciones'),
                DB::raw('SUM(
                            COALESCE(c.total_cantidad, 0)
                            - COALESCE(co.total_cantidad, 0)
                            - COALESCE(cm.total_cantidad, 0)
                        ) AS cantidad_restante')
            )
            ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(cantidad) AS total_cantidad 
                                FROM cantidadmat 
                                GROUP BY fk_id_materiales) AS c'), 'materiales.id', '=', 'c.fk_id_materiales')
            ->leftJoin(DB::raw('(SELECT fk_id_material, SUM(cantidad) AS total_cantidad 
                                FROM material_oferta 
                                WHERE fk_id_estadoel = 1 
                                GROUP BY fk_id_material) AS co'), 'materiales.id', '=', 'co.fk_id_material')
            ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(
                                    CASE
                                        WHEN (created_at >= NOW() - INTERVAL 1 HOUR OR fk_id_estadoel = 1)
                                        THEN cantidad
                                        ELSE 0
                                    END
                                ) AS total_cantidad 
                                FROM compra_materiales 
                                GROUP BY fk_id_materiales) AS cm'), 'materiales.id', '=', 'cm.fk_id_materiales')
            ->where('materiales.fk_id_estadoel', 1)
            ->when($idCategoria, function ($query) use ($idCategoria) {
                return $query->where('materiales.fk_id_categorias', $idCategoria);
            })
            ->when($idMarca, function ($query) use ($idMarca) {
                return $query->where('materiales.fk_id_marcas', $idMarca);
            })
            ->when($nombreProducto, function ($query) use ($nombreProducto) {
                return $query->where('materiales.nombrem', 'like', '%' . $nombreProducto . '%');
            })
            ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
            ->havingRaw('cantidad_restante > 0') // Filtrar por cantidad_restante mayor que 0
            ->orderByDesc('materiales.id')
            ->offset($offset)
            ->limit($perPage)
            ->get();
    
        return $materiales;
    }
    
    



    public function tablaOfertas()
    {
        $ofertas = Ofertas::where('fk_id_estadoel', 1)
                           ->where('fechaexp', '>', now())
                           ->get();
        return $ofertas;
    }

    public function verDetalle($id)
    {
        $ofertas = $this->materialesOferta($id);
        $noferta = Ofertas::where('id', $id)->first();
        return view('Tienda/Productos/Oferta' ,compact('ofertas','noferta'));
    }
    public function materialesOferta($id)
    {

        // Ajustar la longitud máxima permitida para GROUP_CONCAT
        DB::statement('SET SESSION group_concat_max_len = 1000000');

        $subQuery = DB::table('compra_oferta')
            ->select('fk_id_Moferta', DB::raw('SUM(CASE 
                                                    WHEN fk_id_estadoel = 2 AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 1 THEN cantidad
                                                    WHEN fk_id_estadoel = 1 THEN cantidad
                                                    ELSE 0
                                                 END) AS total_compra'))
            ->groupBy('fk_id_Moferta');
        
        $ofertas = MaterialOferta::select(
            'material_oferta.id',
            'material_oferta.fk_id_oferta',
            'o.id as oferta_id',
            'o.nombreof',
            'material_oferta.fk_id_material as material_id',
            'material_oferta.fk_id_material as fk_id_material',
            'o.porcentajeof',
            'ma.valorm',
            DB::raw('MAX(ma.nombrem) as nombrem'),
            DB::raw('material_oferta.cantidad - COALESCE(suma_cantidad.total_compra, 0) AS cantidad_en_compra'),
            DB::raw('GROUP_CONCAT(DISTINCT fo.fotografia SEPARATOR ", ") AS fotos'),
            DB::raw('GROUP_CONCAT(DISTINCT des.nombredes SEPARATOR ", ") AS descripciones')
        )
        ->join('ofertas as o', 'o.id', '=', 'material_oferta.fk_id_oferta')
        ->join('materiales as ma', 'ma.id', '=', 'material_oferta.fk_id_material')
        ->leftJoinSub($subQuery, 'suma_cantidad', function($join) {
            $join->on('suma_cantidad.fk_id_Moferta', '=', 'material_oferta.id');
        })
        ->leftJoin('fotos as fo', 'fo.fk_id_material', '=', 'ma.id')
        ->leftJoin('descripcion as des', 'des.fk_id_material', '=', 'ma.id')
        ->where('material_oferta.fk_id_estadoel', 1)->where('material_oferta.fk_id_oferta', $id)
        ->groupBy(
            'material_oferta.id',
            'material_oferta.fk_id_oferta',
            'o.id',
            'o.nombreof',
            'material_oferta.fk_id_material',
            'o.porcentajeof',
            'ma.valorm',
            'material_oferta.cantidad',
            'suma_cantidad.total_compra'
        )
        ->get();
        
        


        
        return $ofertas;
    }



    public function verDetalleMaterial($id)
    {
        $material = $this->seleccionarMaterial($id);
        $fotos = Fotos::where('fk_id_material',$id)->get();
        $descripciones = Descripcion::where('fk_id_material',$id)->get();
        return view('Tienda/Productos/verDetalleMaterial',compact('material','fotos','descripciones'));
        
    }

    public function seleccionarMaterial($id)
    {
        $material = Materiales::select(
                'materiales.id',
                'materiales.codigob',
                'materiales.nombrem',
                'materiales.valorm',
                DB::raw('(SELECT GROUP_CONCAT(f.fotografia SEPARATOR ", ") 
                          FROM fotos AS f 
                          WHERE f.fk_id_material = materiales.id) AS fotos'),
                DB::raw('(SELECT GROUP_CONCAT(d.nombredes SEPARATOR ", ") 
                          FROM descripcion AS d 
                          WHERE d.fk_id_material = materiales.id) AS descripciones'),
                DB::raw('SUM(
                            COALESCE(c.total_cantidad, 0)
                            - COALESCE(co.total_cantidad, 0)
                            - COALESCE(cm.total_cantidad, 0)
                        ) AS cantidad_restante')
            )
            ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(cantidad) AS total_cantidad 
                                FROM cantidadmat 
                                GROUP BY fk_id_materiales) AS c'), 'materiales.id', '=', 'c.fk_id_materiales')
            ->leftJoin(DB::raw('(SELECT fk_id_material, SUM(cantidad) AS total_cantidad 
                                FROM material_oferta 
                                WHERE fk_id_estadoel = 1 
                                GROUP BY fk_id_material) AS co'), 'materiales.id', '=', 'co.fk_id_material')
            ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(
                                    CASE
                                        WHEN (created_at >= NOW() - INTERVAL 1 HOUR OR fk_id_estadoel = 1)
                                        THEN cantidad
                                        ELSE 0
                                    END
                                ) AS total_cantidad 
                                FROM compra_materiales 
                                GROUP BY fk_id_materiales) AS cm'), 'materiales.id', '=', 'cm.fk_id_materiales')
            ->where('materiales.fk_id_estadoel', 1)
            ->where('materiales.id', $id)
            ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
            ->havingRaw('cantidad_restante > 0') // Filtrar por cantidad_restante mayor que 0
            ->orderByDesc('materiales.id')
            ->first();
        
        return $material;
    }

    public function CarritoCant()
    {
        // Obtiene el ID del usuario autenticado
        $iduser = Auth::guard('cliente')->id();

        // Obtiene el conteo de items en carrito_material y carrito_oferta
        $countCarritoMaterial = DB::table('carrito_material')
            ->where('fk_id_cliente', $iduser)
            ->count();

        $countCarritoOferta = DB::table('carrito_oferta')
            ->where('fk_id_cliente', $iduser)
            ->count();

        // Suma los conteos
        $totalCount = $countCarritoMaterial + $countCarritoOferta;

        // Retorna la respuesta con el conteo total
        return response()->json([
            'total_count' => $totalCount,
        ]);
    }
    
    
    


}
