<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Region;
use Illuminate\Validation\Rule;

class RegionController extends Controller
{
    public function Regiones($id)
    {

        $regiones = Region::where('fk_id_estadoel', 1)->where('fk_id_pais',$id)->orderBy('id', 'desc')->take(30)->get();
        return view('Intranet/Configuracion/Paises/Regiones/Regiones', compact('id','regiones'));
    }

    public function crearRegion(Request $request, $id)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombrere' => [
                'required',
                'string',
                'max:30',
                Rule::unique('region')->where(function ($query) use ($id) {
                    // Verificar unicidad del nombre de región para el país con ID $id
                    return $query->where('fk_id_pais', $id)
                                 ->where(function ($query) {
                                     // Asegurar que el nombre de la región no se repita solo si fk_id_estadoel no es 2
                                     $query->where('fk_id_estadoel', '!=', 2)
                                           ->orWhereNull('fk_id_estadoel');
                                 });
                }),
            ],
        ]);
    
        // Crear una nueva región asociada al país con ID $id
        $region = new Region();
        $region->nombrere = $request->input('nombrere');
        $region->fk_id_pais = $id; // Asignar el ID del país al que pertenece esta región
        $region->fk_id_estadoel = 1; // Ajustar según tu lógica de negocio
        $region->save();
    
        // Redireccionar a alguna vista o mostrar un mensaje de éxito
        return redirect()->back()->with('success', 'Región creada exitosamente.');
    }
    public function regionesFiltrar(Request $request, $id)
    {
        // Obtener el nombre de la región desde la solicitud
        $nombrere = $request->input('nombrere');
    
        // Construir la consulta base
        $query = Region::where('fk_id_estadoel', 1)
                       ->where('fk_id_pais', $id);
    
        // Aplicar filtro por nombre de región si se proporciona
        if (!empty($nombrere)) {
            $query->where('nombrere', 'like', '%' . $nombrere . '%');
        }
    
        // Obtener las regiones ordenadas por ID descendente y tomar las primeras 30
        $regiones = $query->orderBy('id', 'desc')->take(30)->get();
    
        return view('Intranet/Configuracion/Paises/Regiones/Regiones', compact('id','regiones'));
    }

    public function eliminarRegion($id)
    {
        // Buscar la región por su ID
        $region = Region::find($id);
    
        if (!$region) {
            return redirect()->back()->with('error', 'La región no existe.');
        }
    
        // Cambiar el estado de la región a inactivo (fk_id_estadoel = 2)
        $region->fk_id_estadoel = 2;
        $region->save();
    
        // Redireccionar de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'La región se ha eliminado correctamente.');
    }
}
