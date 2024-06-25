<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pais;

class PaisController extends Controller
{
    public function paises()
    {
        $paises = Pais::where('fk_id_estadoel', 1)->orderBy('id', 'desc')->take(30)->get();
        return view('Intranet/Configuracion/Paises/Paises',compact('paises'));
    }

    public function crearPais(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombrepa' => 'required|string|max:30',
        ]);

        // Verificar si el país ya existe con fk_id_estadoel diferente de 2
        $paisExistente = Pais::where('nombrepa', $request->input('nombrepa'))
                             ->where('fk_id_estadoel', '!=', 2)
                             ->first();

        if ($paisExistente) {
            return redirect()->back()->withErrors(['nombrepa' => 'El nombre del país ya existe.']);
        }

        // Crear un nuevo país
        $pais = new Pais();
        $pais->nombrepa = $request->input('nombrepa');
        $pais->fk_id_estadoel = 1; // Asegúrate de ajustar esto según tu lógica de negocio
        $pais->save();

        // Redireccionar a alguna vista o mostrar un mensaje de éxito
        return redirect()->back()->with('success', 'País creado exitosamente.');
    }

    public function paisesFiltrar(Request $request)
    {
        // Obtener el nombre del país ingresado en el formulario
        $nombrepa = $request->input('nombrepa');
        
        // Consultar y filtrar los países
        $paises = Pais::where('fk_id_estadoel', 1)
                    ->where('nombrepa', 'like', '%' . $nombrepa . '%')
                    ->orderBy('id', 'desc')
                    ->take(30)
                    ->get();
        // Devolver la vista con los países filtrados
        return view('Intranet/Configuracion/Paises/Paises', compact('paises'));
    }
    

    public function eliminarPais($id)
    {
        // Encontrar el país por su ID
        $pais = Pais::find($id);

        // Verificar si el país existe
        if ($pais) {
            // Actualizar el campo fk_id_estadoel a 2
            $pais->fk_id_estadoel = 2;
            $pais->save();

            // Redireccionar con un mensaje de éxito
            return redirect()->back()->with('success', 'País eliminado exitosamente.');
        } else {
            // Redireccionar con un mensaje de error si el país no se encuentra
            return redirect()->back()->with('error', 'País no encontrado.');
        }
    }
}
