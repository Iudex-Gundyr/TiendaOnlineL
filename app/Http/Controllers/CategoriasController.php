<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Categorias;

class CategoriasController extends Controller
{
    public function categorias()
    {
        $categorias = Categorias::where('fk_id_estadoel', 1)
        ->orderBy('id', 'desc')->take(30)
        ->get();
        return view('Intranet/Configuracion/Categorias/Categorias',compact('categorias'));
    }
    public function CrearCategoria(Request $request)
    {
    // Validar la entrada
    $request->validate([
        'nombrecat' => 'required|max:255',
    ]);

    // Buscar la categoría por nombre
    $categoria = Categorias::where('nombrecat', $request->input('nombrecat'))->first();

    if ($categoria) {
        // Si la categoría ya existe, actualizar fk_id_estadoel solo si es necesario
        if ($categoria->fk_id_estadoel != 1) {
            $categoria->fk_id_estadoel = 1;
            $categoria->save();
            $message = 'Categoría creada con éxito';
        } else {
            $message = 'La categoría ya existe y está activa';
            return redirect()->back()->with('error', $message);
        }
    } else {
        // Si la categoría no existe, crear una nueva
        $categoria = new Categorias;
        $categoria->nombrecat = $request->input('nombrecat');
        $categoria->fk_id_estadoel = 1;
        $categoria->save();
        $message = 'Categoría creada con éxito';
    }
    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', $message);
    }

    public function eliminarCategoria($id){
        try {
            Categorias::where('id', $id)->update(['FK_ID_ESTADOEL' => 2]);

            // Redirecciona de vuelta con un mensaje de éxito
            return redirect()->back()->with('success', 'Categoria eliminado exitosamente.');
        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir, por ejemplo, si no se encuentra el usuario
            return redirect()->back()->with('error', 'Error al eliminar la categoria: ' . $e->getMessage());
        }
    }

    public function categoriasfiltrar(Request $request)
    {
        $nombreCat = $request->input('nombrecat');
        $categorias = Categorias::where('nombrecat', 'like', '%' . $nombreCat . '%')->where('FK_ID_ESTADOEL', 1)->take(30)->get();
        return view('Intranet/Configuracion/Categorias/Categorias',compact('categorias'));
    }

}
