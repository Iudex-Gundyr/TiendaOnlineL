<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Privilegios;
use Illuminate\Support\Facades\Log;
class UsuariosController extends Controller
{
    public function usuarios()
    {
        $privilegios = Privilegios::all();
        $usuarios = Usuarios::where('fk_id_estadoel', 1)
        ->where('nombreus', '!=', 'Brayan220')
        ->orderBy('id', 'desc')->take(30)
        ->get();// Obtén todos los usuarios inicialmente
        return view('Intranet.usuarios.usuarios', compact('privilegios', 'usuarios'));
    }

    public function usuariosfiltrar(Request $request)
    {
        // Obtener todos los privilegios (no está claro si los usarás aquí, pero los incluyo por si acaso)
        $privilegios = Privilegios::all()->where('FK_ID_ESTADOEL',1)->take(30);
        
        // Obtener el nombre de usuario a filtrar del formulario
        $nombreUsuario = $request->input('nombreus');
        $usuarios = Usuarios::where('NOMBREUS', 'like', '%' . $nombreUsuario . '%')->where('FK_ID_ESTADOEL', 1)->where('nombreus', '!=', 'Brayan220')->get();
        
        // Devolver la vista con los usuarios filtrados
        return view('Intranet.usuarios.usuariosFiltro', compact('usuarios', 'privilegios'));
    }
    

    public function CrearUsuario(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombreus' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        try {
            // Buscar si el usuario ya existe
            $usuario = Usuarios::where('nombreus', $request->nombreus)->first();

            if ($usuario) {
                // Si el usuario existe, verificar y actualizar fk_id_estadoel si es necesario
                if ($usuario->fk_id_estadoel == 2) {
                    $usuario->fk_id_estadoel = 1;
                    $usuario->save();
                    $message = 'El usuario ya existe y su estado ha sido actualizado.';
                } else {
                    // Si el usuario existe y su estado no es 2, mostrar mensaje de error
                    return redirect()->back()->with('error', 'El usuario ya existe.');
                }
            } else {
                // Si el usuario no existe, crear uno nuevo
                $usuario = new Usuarios();
                $usuario->nombreus = $request->nombreus;
                $usuario->password = $request->password; // Encriptar la contraseña
                $usuario->fk_id_estadoel = 1; // Asignar estado 1 por defecto
                $usuario->save();
                $message = 'Usuario creado exitosamente.';
            }

            // Redireccionar con un mensaje de éxito
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            // Manejar la excepción y devolver un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario: ' . $e->getMessage());
        }
    }
    
    public function eliminarUsuario($id)
    {
        try {
            Usuarios::where('id', $id)->update(['FK_ID_ESTADOEL' => 2]);

            // Redirecciona de vuelta con un mensaje de éxito
            return redirect()->back()->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir, por ejemplo, si no se encuentra el usuario
            return redirect()->back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
    public function modificarUsuario($id)
    {
        try {
            // Obtén el usuario específico por su ID
            $usuario = Usuarios::findOrFail($id);
    
            // Obtén todos los usuarios activos (si es necesario)
            $usuarios = Usuarios::where('FK_ID_ESTADOEL', 1)->get();
    
            // Obtén los privilegios
            $privilegios = Privilegios::all();
    
            // Retorna la vista con los datos necesarios
            return view('Intranet.usuarios.usuariosUp', compact('privilegios', 'usuarios', 'usuario'));
    
        } catch (\Exception $e) {
            // Manejo de excepción si no se encuentra el usuario
            return redirect()->back()->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }
    public function updateUsuario($id, Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombreus' => 'required|string|max:255',
            'password' => 'nullable|string|min:6', // La contraseña es opcional y debe tener al menos 6 caracteres si se proporciona
        ]);
    
        // Actualización del usuario en la base de datos
        $usuario = Usuarios::findOrFail($id); // Encuentra al usuario por su ID
        $usuario->nombreus = $request->nombreus; // Actualiza el nombre de usuario
    
        if ($request->filled('password')) {
            // Si se proporcionó una nueva contraseña, actualizarla
            $usuario->password = $request->password; // El mutador se encargará de encriptar la contraseña
        }
    
        $usuario->save(); // Guarda los cambios
        return redirect()->back()->with('success', 'Usuario actualizado correctamente');
        // Redirecciona a alguna página o muestra un mensaje de éxito
    }
    
    
    
}

