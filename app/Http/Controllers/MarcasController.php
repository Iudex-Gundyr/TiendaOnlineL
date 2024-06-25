<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Marcas;

class MarcasController extends Controller
{
    public function marcas()
    {
        $marcas = Marcas::where('fk_id_estadoel', 1)
        ->orderBy('id', 'desc') 
        ->take(30)
        ->get();
        return view('Intranet/Configuracion/Marcas/Marcas', compact('marcas'));
    
    }

    public function CrearMarca(Request $request)
    {
                $request->validate([
                    'nombremar' => 'required|string|max:255|unique:marcas,nombremar,NULL,id,fk_id_estadoel,1',
                ], [
                    'nombremar.unique' => 'El nombre de la marca ya está en uso.',
                ]);
        
                $MarcaExistente = Marcas::where('nombremar', $request->input('nombremar'))
                                        ->where('fk_id_estadoel', 2)
                                        ->first();
        
                if ($MarcaExistente) {
                    $MarcaExistente->fk_id_estadoel = 1;
                    $MarcaExistente->save();
                } else {
                    $marca = new Marcas();
                    $marca->nombremar = $request->input('nombremar');
                    $marca->fk_id_estadoel = 1;
                    $marca->save();
                }
                return redirect()->back()->with('success', 'Marca creada exitosamente');
    }
    public function marcasfiltrar(Request $request)
    {
        $nombreMar = $request->input('nombremar');
        $marcas = Marcas::where('nombremar', 'like', '%' . $nombreMar . '%')->where('FK_ID_ESTADOEL', 1)->take(30)->get();
        return view('Intranet/Configuracion/Marcas/Marcas', compact('marcas'));
    }
    public function eliminarMarca($id)
    {
        // Buscar la ciudad por ID
        $marca = Marcas::find($id);

        // Verificar si la ciudad existe
        if (!$marca) {
            return redirect()->back()->with('error', 'Marca no encontrada.');
        }

        // Actualizar el estado de la ciudad a eliminado
        $marca->fk_id_estadoel = 2;
        $marca->save();

        return redirect()->back()->with('success', 'Marca eliminada correctamente.');
    }
}
