<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\MaterialOferta;
use App\Models\Categorias;
use App\Models\Marcas;
use App\Models\Materiales;
use App\Models\Ofertas;
use Illuminate\Support\Facades\DB;
class matOfertasController extends Controller
{
public function materialOferta($id)
{
    $marcas = Marcas::where('fk_id_estadoel', 1)
                    ->orderBy('nombremar', 'asc')
                    ->get();
    
    $oferta = Ofertas::where('fk_id_estadoel', 1)
                     ->where('id', $id)
                     ->first();

    // Nueva consulta utilizando la query adaptada
    $ofertas = MaterialOferta::select(
        'material_oferta.*',
        'o.*',
        'ma.*',
        'material_oferta.id as idmatof',
        DB::raw('material_oferta.cantidad - COALESCE(sum_co.cantidad, 0) as cantidad_en_compra')
    )
    ->join('ofertas as o', 'o.id', '=', 'material_oferta.fk_id_oferta')
    ->join('materiales as ma', 'ma.id', '=', 'material_oferta.fk_id_material')
    ->leftJoin(DB::raw('(SELECT
                            fk_id_Moferta,
                            SUM(CASE
                                    WHEN fk_id_estadoel = 2 AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 1 THEN cantidad
                                    WHEN fk_id_estadoel = 1 THEN cantidad
                                    ELSE 0
                                END) AS cantidad
                        FROM
                            compra_oferta
                        WHERE
                            fk_id_estadoel IN (1, 2)
                        GROUP BY
                            fk_id_moferta
                    ) AS sum_co'), 'sum_co.fk_id_Moferta', '=', 'material_oferta.id')
    ->where('material_oferta.fk_id_estadoel', 1)->where('o.id', $id)
    ->get();



    return view('Intranet/Ofertas/MaterialOferta/matOferta', compact('marcas', 'oferta', 'ofertas'));
}

    public function eliminarmatOferta($id)
    {
        // Encontrar el registro de MaterialOferta por su ID
        $matOferta = MaterialOferta::findOrFail($id);
    
        // Cambiar el estado a 2 (desactivado)
        $matOferta->fk_id_estadoel = 2;
    
        // Guardar los cambios
        $matOferta->save();
    
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Material de oferta eliminada correctamente.');
    }


    
    public function tomarCategorias($id)
    {
        try {
            // Obtener la marca por su ID
            $marca = Marcas::findOrFail($id);

            // Obtener los materiales asociados a la marca que estén activos
            $materiales = Materiales::where('fk_id_marcas', $id)
                                    ->where('fk_id_estadoel', 1)
                                    ->with('categorias')
                                    ->get();

            // Obtener las categorías únicas de los materiales activos
            $categorias = $materiales->unique('fk_id_categorias')->pluck('categorias');

            // Preparar los datos en el formato requerido para la respuesta JSON
            $categoriasFormatted = $categorias->map(function ($categoria) {
                return [
                    'id' => $categoria->id,
                    'nombrecat' => $categoria->nombrecat,
                ];
            });

            // Retornar la respuesta JSON con las categorías
            return response()->json($categoriasFormatted);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
    public function materialesPorCategoriaMarca($idCategoria, $idMarca)
    {
        // Obtener los materiales activos que pertenecen a la categoría y marca específicas
        $materiales = Materiales::where('fk_id_estadoel', 1)
                                ->where('fk_id_categorias', $idCategoria)
                                ->where('fk_id_marcas', $idMarca)
                                ->get();

        return response()->json($materiales);
    }

    public function materiales($id)
    {
        try {
            // Consulta para obtener el material por su ID con la cantidad restante calculada
            $material = Materiales::select(
                'materiales.id',
                'materiales.codigob',
                'materiales.nombrem',
                'materiales.valorm',
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
            ->where('materiales.id', $id) // Filtrar por el ID del material
            ->where('materiales.fk_id_estadoel', 1)
            ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
            ->orderByDesc('materiales.id')
            ->first(); // Obtener solo un resultado
    
            // Verificar si se encontró el material
            if (!$material) {
                return response()->json(['error' => 'Material no encontrado'], 404);
            }
    
            // Preparar la respuesta JSON con los datos del material
            $response = [
                'id' => $material->id,
                'nombrem' => $material->nombrem,
                'codigob' => $material->codigob,
                'valorm' => $material->valorm,
                'cantidad_restante' => $material->cantidad_restante,
                // Agrega otros campos que desees devolver en la respuesta JSON
            ];
    
            // Retornar la respuesta en formato JSON
            return response()->json($response);
        } catch (\Exception $e) {
            // Manejo de errores si ocurre alguna excepción
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }
    
    

    public function crearMatOf(Request $request, $id)
    {
        $request->validate([
            'marca' => 'required|exists:marcas,id',
            'categoria' => 'required|exists:categorias,id',
            'material' => 'required|exists:materiales,id',
            'cantidad' => 'required|integer|min:1',
        ]);
    
        // Obtener la cantidad restante del material
        $cantidadRestante = $this->tablaMateriales($request->input('material'));
    
        // Validar que la cantidad solicitada no exceda la cantidad restante
        if ($request->input('cantidad') > $cantidadRestante) {
            return redirect()->back()->withErrors(['cantidad' => 'La cantidad solicitada excede la cantidad restante de materiales disponibles.']);
        }
    
        // Crear una nueva instancia del modelo MaterialOferta
        $MatOf = new MaterialOferta;
        $MatOf->fk_id_oferta = $id;
        $MatOf->fk_id_material = $request->input('material');
        $MatOf->fk_id_estadoel = 1; 
        $MatOf->cantidad = $request->input('cantidad');
        
        // Guardar el modelo en la base de datos
        $MatOf->save();
    
        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Material agregado a la oferta correctamente.');
    }
    
    public function tablaMateriales($materialId)
    {
        $result = Materiales::select(
            DB::raw('SUM(
                        COALESCE(c.cantidad, 0)
                        - COALESCE(co.cantidad, 0)
                        - COALESCE(
                            CASE
                                WHEN (cm.created_at >= NOW() - INTERVAL 1 HOUR OR cm.fk_id_estadoel = 1)
                                THEN cm.cantidad
                                ELSE 0
                            END, 0)
                    ) AS cantidad_restante')
        )
        ->leftJoin('material_oferta as co', 'materiales.id', '=', 'co.fk_id_material')
        ->leftJoin('cantidadmat as c', 'materiales.id', '=', 'c.fk_id_materiales')
        ->leftJoin('compra_materiales as cm', 'materiales.id', '=', 'cm.fk_id_materiales')
        ->where('materiales.id', $materialId)
        ->where('materiales.fk_id_estadoel', 1)
        ->groupBy('materiales.id')
        ->first();
    
        return $result ? $result->cantidad_restante : 0;
    }
    
    
}
