<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Materiales;
use App\Models\Categorias;
use App\Models\Marcas;
use App\Models\CantidadMat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
class MaterialesController extends Controller
{
    public function materiales()
    {
        $materiales = $this->tablaMateriales();  
        $marcas = Marcas::where('fk_id_estadoel', 1)->orderBy('nombremar', 'asc')->get();
        $categorias = Categorias::where('fk_id_estadoel', 1)->orderBy('nombrecat', 'asc')->get();
    
        return view('Intranet/Materiales/Materiales', compact('categorias', 'marcas', 'materiales'));
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
    
    public function filtrartablaMateriales($filtroNombre = null)
    {
        // Consulta base de materiales con cálculo de cantidad restante
        $query = Materiales::select(
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
        ->where('materiales.fk_id_estadoel', 1);

        // Aplicar filtro si se proporciona un nombre o código de barras
        if ($filtroNombre) {
            $query->where(function ($query) use ($filtroNombre) {
                $query->where('materiales.nombrem', 'like', '%' . $filtroNombre . '%')
                      ->orWhere('materiales.codigob', 'like', '%' . $filtroNombre . '%');
            });
        }

        // Continuar con el resto de la consulta
        $materialesFiltrados = $query->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm')
                                    ->orderByDesc('materiales.id')
                                    ->limit(30)
                                    ->get();

        return $materialesFiltrados;
    }
    
    

    public function crearMaterial(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombrem' => 'required|string|max:255',
            'valorm' => 'required|numeric|max:99999999999',
            'codigob' => 'required|numeric',
            'categoria' => 'required|exists:categorias,id',
            'marca' => 'required|exists:marcas,id',
        ]);
        // Buscar un material existente con el mismo código   
        $materialExistente = Materiales::where('codigob', $validatedData['codigob'])->first();

        if ($materialExistente) {
            // Si el material existe y está eliminado (fk_id_estadoel = 2)
            if ($materialExistente->fk_id_estadoel == 2) {
                $material = new Materiales();
                $material->nombrem = $validatedData['nombrem'];
                $material->valorm = $validatedData['valorm'];
                $material->codigob = $validatedData['codigob'];
                $material->fk_id_categorias = $validatedData['categoria'];
                $material->fk_id_marcas = $validatedData['marca'];
                $material->fk_id_estadoel = 1; // Por defecto, activo
                $material->fk_id_usuariocre = Auth::id();
                $material->fk_id_usuarioupd = Auth::id();
                $material->save();
                return redirect()->back()->with(['succes' => 'Material creado exitosamente.'])->withInput();
            } else {
                // Si ya existe un material activo con ese código de barras, mostrar error y redirigir
                return redirect()->back()->with(['error' => 'El código de barras ya está siendo utilizado por otro material activo.'])->withInput();
            }
        }
        // Si no existe o su estado no es 2, crear un nuevo material
        $material = new Materiales();
        $material->nombrem = $validatedData['nombrem'];
        $material->valorm = $validatedData['valorm'];
        $material->codigob = $validatedData['codigob'];
        $material->fk_id_categorias = $validatedData['categoria'];
        $material->fk_id_marcas = $validatedData['marca'];
        $material->fk_id_estadoel = 1; // Por defecto, activo
        $material->fk_id_usuariocre = Auth::id();
        $material->fk_id_usuarioupd = Auth::id();
        $material->save();
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->back()->with('success', 'Material creado exitosamente.');
    }

    public function materialesfiltrar(Request $request)
    {
        // Obtener el valor del campo de búsqueda del formulario
        $filtroNombre = $request->input('nombrem');

        // Obtener los materiales filtrados utilizando la nueva consulta
        $materiales = $this->filtrartablaMateriales($filtroNombre);

        // Obtener marcas y categorías (sin filtrar)
        $marcas = Marcas::where('fk_id_estadoel', 1)->orderBy('nombremar', 'asc')->get();
        $categorias = Categorias::where('fk_id_estadoel', 1)->orderBy('nombrecat', 'asc')->get();

        // Retornar la vista con los datos filtrados y sin filtrar
        return view('Intranet/Materiales/Materiales', compact('materiales', 'marcas', 'categorias'));
    }

    public function modificarMaterial($id)
    {
        $cantidades = CantidadMat::orderByDesc('id')->take(5)->get();
        $materiales = $this->tablaMateriales();  
        $material = Materiales::select('materiales.*', 'marcas.nombremar', 'categorias.nombrecat', 'creador.nombreus as nombre_usuario_creador', 'actualizador.nombreus as nombre_usuario_actualizador')
        ->where('materiales.id', $id)
        ->join('marcas', 'materiales.fk_id_marcas', '=', 'marcas.id')
        ->join('categorias', 'materiales.fk_id_categorias', '=', 'categorias.id')
        ->join('usuarios as creador', 'materiales.fk_id_usuariocre', '=', 'creador.id')
        ->join('usuarios as actualizador', 'materiales.fk_id_usuarioupd', '=', 'actualizador.id')
        ->first();
        $marcas = Marcas::where('fk_id_estadoel',1)->orderBy('nombremar','asc')->get();
        $categorias = Categorias::where('fk_id_estadoel', 1)->orderBy('nombrecat', 'asc')->get();
        return view('Intranet/Materiales/modificarMaterial', compact('categorias','marcas','materiales','material','cantidades'));
    }
    public function updateMaterial(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombrem' => 'required|string|max:255',
            'valorm' => 'required|numeric|max:99999999999',
            'codigob' => 'required|numeric|unique:materiales,codigob,' . $id,
            'categoria' => 'required|exists:categorias,id',
            'marca' => 'required|exists:marcas,id',
        ]);

        try {
            // Obtener el material por su ID
            $material = Materiales::findOrFail($id);

            // Actualizar los datos del material con los nuevos valores
            $material->nombrem = $validatedData['nombrem'];
            $material->valorm = $validatedData['valorm'];
            $material->codigob = $validatedData['codigob'];
            $material->fk_id_categorias = $validatedData['categoria'];
            $material->fk_id_marcas = $validatedData['marca'];
            $material->fk_id_usuarioupd = Auth::id(); // Actualizar el ID del usuario que está realizando la actualización
            $material->save();

            // Redirigir al usuario con un mensaje de éxito
            return redirect()->back()->with('success', 'Material actualizado exitosamente.');
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir
            return redirect()->back()->with('error', 'Error al actualizar el material: ' . $e->getMessage())->withInput();
        }
    }

    public function eliminarMaterial($id)
    {
        try {
            // Buscar el material por su ID
            $material = Materiales::findOrFail($id);

            // Actualizar fk_id_estadoel a 2
            $material->fk_id_estadoel = 2;
            $material->fk_id_usuarioupd = Auth::id(); // Actualizar el ID del usuario que está realizando la acción
            $material->save();

            // Redirigir de vuelta a la lista de materiales con un mensaje de éxito
            return redirect()->back()->with('success', 'Material eliminado exitosamente.');
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir
            return redirect()->back()->with('error', 'Error al eliminar el material: ' . $e->getMessage());
        }
    }
}
