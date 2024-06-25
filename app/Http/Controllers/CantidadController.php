<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CantidadMat;
use App\Models\Materiales;
use Illuminate\Support\Facades\DB;
class CantidadController extends Controller
{
    public function cantidad($id)
    {
        try {
            // Obtener las últimas 5 cantidades para el material específico
            $cantidades = CantidadMat::orderByDesc('id')
                ->where('fk_id_materiales', $id)
                ->take(5)
                ->get();
    
            // Consulta principal para obtener el material específico con cantidad restante calculada
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
            ->where('materiales.fk_id_estadoel', 1)->where('materiales.id',$id)
            ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
            ->orderByDesc('materiales.id')
            ->limit(30)
            ->first();
            
    
            // Verificar si se encontró el material
            if (!$material) {
                return response()->view('errors.404', [], 404);
            }
    
            // Consulta opcional para obtener una lista de materiales para mostrar en la vista
            $materiales = $this->tablamateriales();
    
            // Retornar la vista con los datos del material y otras variables compactadas
            return view('Intranet/Materiales/Cantidad/Cantidad', compact('material', 'cantidades', 'materiales'));
        } catch (\Exception $e) {
            // Manejo de errores si ocurre alguna excepción
            return response()->view('errors.500', [], 500);
        }
    }
    public function tablaMateriales()
    {
        return Materiales::select(
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
        ->where('materiales.fk_id_estadoel', 1)
        ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
        ->orderByDesc('materiales.id')
        ->limit(30)
        ->get();
    }
    
    public function agregarCantidad(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'accion' => 'required|string|in:agregar,quitar',
            'cantidad' => 'required|integer|min:1',
        ]);
    
        // Obtener la cantidad del formulario
        $cantidads = $validatedData['cantidad'];
    
        // Validar que la cantidad no sea negativa
        if ($cantidads < 0) {
            return redirect()->back()->with('error', 'La cantidad no puede ser menor a 0.');
        }
    
        // Si la acción es quitar, multiplicar la cantidad por -1
        if ($validatedData['accion'] == 'quitar') {
            $cantidads *= -1;
        }
    
        // Crear o actualizar el registro en la tabla CantidadMat
        $cantidad = new CantidadMat();
        $cantidad->cantidad = $cantidads;
        $cantidad->fk_id_materiales = $id;
        $cantidad->save();
        if ($validatedData['accion'] == 'quitar') {
            return redirect()->back()->with('success', 'Cantidad retirada exitosamente.');
        }
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Cantidad agregada exitosamente.');
    }
}
