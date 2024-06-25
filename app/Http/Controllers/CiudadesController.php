<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ciudad;

class CiudadesController extends Controller
{
    public function ciudades($id)
    {
        $ciudades = Ciudad::where('fk_id_estadoel',1)->where('fk_id_region',$id)->orderBy('id', 'desc')->take(30)->get();
        return view('Intranet/Configuracion/Paises/Regiones/Ciudades/Ciudades', compact('ciudades','id'));
    }

    public function crearCiudad(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombreci' => 'required|string|max:255|unique:ciudad,nombreci,NULL,id,fk_id_estadoel,1,fk_id_region,' . $id,
        ], [
            'nombreci.unique' => 'El nombre de la ciudad ya está en uso en esta región.',
        ]);
    
        // Verificar si la ciudad ya existe con fk_id_estadoel = 2 en la misma región
        $ciudadExistente = Ciudad::where('nombreci', $request->input('nombreci'))
                                 ->where('fk_id_estadoel', 2)
                                 ->where('fk_id_region', $id)
                                 ->first();
    
        if ($ciudadExistente) {
            // Si la ciudad existe con estadoel = 2, se reactiva cambiando fk_id_estadoel a 1
            $ciudadExistente->fk_id_estadoel = 1;
            $ciudadExistente->save();
            return redirect()->back()->with('success', 'Ciudad creada exitosamente.');
        }
    
        // Crear una nueva ciudad
        $ciudad = new Ciudad();
        $ciudad->nombreci = $request->input('nombreci');
        $ciudad->fk_id_region = $id; // Asignar el ID de la región a la que pertenece esta ciudad
        $ciudad->fk_id_estadoel = 1; // Ajustar según tu lógica de negocio
        $ciudad->save();
    
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Ciudad creada exitosamente.');
    }
    public function eliminarCiudad($id)
    {
        // Buscar la ciudad por ID
        $ciudad = Ciudad::find($id);

        // Verificar si la ciudad existe
        if (!$ciudad) {
            return redirect()->back()->with('error', 'Ciudad no encontrada.');
        }

        // Actualizar el estado de la ciudad a eliminado
        $ciudad->fk_id_estadoel = 2;
        $ciudad->save();

        return redirect()->back()->with('success', 'Ciudad eliminada correctamente.');
    }
    public function ciudadesfiltrar(Request $request,$id)
    {
        $nombreCi = $request->input('nombreci');
        $ciudades = Ciudad::where('nombreci', 'like', '%' . $nombreCi . '%')->where('FK_ID_ESTADOEL', 1)->where('fk_id_region',$id)->take(30)->get();
        return view('Intranet/Configuracion/Paises/Regiones/Ciudades/Ciudades', compact('ciudades','id'));
    }
}
