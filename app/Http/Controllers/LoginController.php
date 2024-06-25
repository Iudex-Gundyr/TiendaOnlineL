<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    public function Login()
    {
        return view('Login'); // Asegúrate de que tienes una vista 'login.blade.php'
    }

    public function iniciarSesion(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombreus' => 'required',
            'password' => 'required',
        ]);

        // Obtener las credenciales del formulario
        $credenciales = $request->only('nombreus', 'password');

        // Buscar el usuario por nombre de usuario
        $usuario = Usuarios::where('nombreus', $credenciales['nombreus'])->first();

        // Verificar si el usuario existe
        if (!$usuario) {
            return back()->withErrors(['mensaje' => 'Nombre de usuario o contraseña incorrectos.'])->withInput();
        }

        // Verificar si el usuario está activo
        if ($usuario->fk_id_estadoel == 2) {
            return back()->withErrors(['mensaje' => 'Su cuenta ha sido desactivada.'])->withInput();
        }

        // Intentar iniciar sesión con las credenciales proporcionadas
        if (Auth::guard('usuarios')->attempt($credenciales)) {
            return redirect()->intended('/dashboard');
        } else {
            return back()->withErrors(['mensaje' => 'Nombre de usuario o contraseña incorrectos.'])->withInput();
        }
    }

    public function cerrarSesion(Request $request)
    {
        Auth::guard('usuarios')->logout(); // Cerrar sesión del guardia 'usuarios'

        $request->session()->invalidate(); // Invalidar la sesión

        $request->session()->regenerateToken(); // Regenerar el token de sesión

        return redirect('/login'); // Redirigir a la página de inicio u otra página deseada
    }
    
    
    
}