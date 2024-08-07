<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pais;
use App\Models\Region;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class ClienteController extends Controller
{
    public function Registrar()
    {
        $paises = Pais::where('fk_id_estadoel', 1)->orderby('nombrepa', 'asc')->get();
        return view('Tienda/Cliente/RegistrarMenu', compact('paises'));
    }

    public function registrarCliente(Request $request)
    {
        // Validación de los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombrec' => 'required|string|max:255',
            'email' => 'required|email|unique:cliente,correo',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6|same:password',
            'pais' => 'required|exists:pais,id',
            'region' => 'required|exists:region,id',
            'ciudad' => 'required|exists:ciudad,id',
            'direccion' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'telefono' => 'required|string|max:20',
            'telefonof' => 'nullable|string|max:20',
            'documentacion' => 'required|string|max:50|regex:/^\d{1,3}(\.\d{3})*-\d{1}K?$/i',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'documentacion.regex' => 'El RUT debe tener un formato válido.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Crear nuevo cliente
        $cliente = new Cliente();
        $cliente->nombrec = $request->nombrec;
        $cliente->correo = $request->email;
        $cliente->password = bcrypt($request->password); // Hasheo de la contraseña
        $cliente->direccion = $request->direccion;
        $cliente->blockd = $request->block ?? null;
        $cliente->numerod = $request->numero;
        $cliente->telefono = $request->telefono;
        $cliente->telefonof = $request->telefonof;
        $cliente->fk_id_identificacion = 1; // Ajustar según tu lógica de identificación
        $cliente->fk_id_ciudad = $request->ciudad;
        $cliente->documentacion = $request->documentacion;
        $cliente->fk_id_estadoel = 1; // Ajustar según tu lógica de estado
        $cliente->save();
    
        // Iniciar sesión automáticamente
        Auth::guard('cliente')->login($cliente);
    
        // Redireccionar con mensaje de éxito
        return redirect()->route('home')->with('success_message', '¡Te has registrado y has iniciado sesión automáticamente!');
    }
    

    public function tomarRegiones($id)
    {
        $regiones = Region::where('fk_id_pais', $id)
                          ->where('fk_id_estadoel', 1)
                          ->orderBy('nombrere', 'asc')
                          ->get();

        return response()->json($regiones);
    }

    public function tomarCiudades($id)
    {
        $ciudades = Ciudad::where('fk_id_region', $id)
                          ->where('fk_id_estadoel', 1)
                          ->orderBy('nombreci', 'asc')
                          ->get();

        return response()->json($ciudades);
    }

    public function iniciarSesionCliente(Request $request)
    {
        // Validación de datos del formulario (opcional si ya se valida en el frontend)
    
        // Intentar autenticar al usuario usando el guard 'cliente'
        try {
            $credentials = $request->only('correo', 'password');
    
            if (Auth::guard('cliente')->attempt($credentials)) {
                // Autenticación exitosa, redirigir según sea necesario
                return redirect()->route('home')->with('success_message', '¡Has iniciado sesión, ahora puedes comprar nuestra gran variedad de productos!');
            } else {
                // Incrementar contador de intentos fallidos en sesión
                $intentosFallidos = session('intentos_fallidos', 0) + 1;
                session(['intentos_fallidos' => $intentosFallidos]);
    
                // Si hay más de 10 intentos fallidos, lanzar excepción para evitar nuevos intentos
                if ($intentosFallidos > 10) {
                    throw ValidationException::withMessages([
                        'mensaje' => 'Demasiados intentos fallidos. Por favor, inténtalo más tarde.',
                    ]);
                }
    
                // Autenticación fallida, redirigir de vuelta al formulario con mensaje de error
                return redirect()->back()->withErrors(['error' => 'Correo o contraseña incorrectos'])->withInput();
            }
        } catch (ValidationException $e) {
            // En caso de excepción de validación (más de 10 intentos fallidos), redirigir con el mensaje de error
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function cerrarSesion()
    {
        Auth::guard('cliente')->logout(); // Cerrar sesión del guard 'cliente'

        return redirect()->route('home')->with('success_message', 'Has cerrado sesión, vuelve pronto :)');; // Redirigir a la ruta de inicio de sesión del cliente
    }


    public function misDatos()
    {
        // Obtener el ID del usuario actualmente autenticado
        $id = Auth::guard('cliente')->id();
    
        // Ejecutar la consulta para obtener los datos del cliente con el ID obtenido
        $datos = Cliente::with(['ciudad.region.pais'])
                        ->where('id', $id)
                        ->firstOrFail();
    
        $paises = Pais::where('fk_id_estadoel', 1)->orderby('nombrepa', 'asc')->get();
        $regiones = Region::where('fk_id_pais', $datos->ciudad->region->fk_id_pais)->where('fk_id_estadoel', 1)->orderby('nombrere', 'asc')->get();
        $ciudades = Ciudad::where('fk_id_region', $datos->ciudad->fk_id_region)->where('fk_id_estadoel', 1)->orderby('nombreci', 'asc')->get();
    
        // Pasar los datos a la vista
        return view('Tienda/Cliente/MisDatos', compact('datos', 'paises', 'regiones', 'ciudades'));
    }

    public function actualizarCliente(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombrec' => 'required|string|max:255',
            'documentacion' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'direccion' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'block' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:255',
            'telefonof' => 'nullable|string|max:255',
        ]);

        // Obtener el cliente actualmente autenticado
        $cliente = Cliente::find(Auth::guard('cliente')->id());

        // Actualizar los campos modificables
        $cliente->nombrec = $request->input('nombrec');
        $cliente->documentacion = $request->input('documentacion');
        $cliente->direccion = $request->input('direccion');
        $cliente->numerod = $request->input('numero');
        $cliente->blockd = $request->input('block');
        $cliente->telefono = $request->input('telefono');
        $cliente->telefonof = $request->input('telefonof');

        // Actualizar la contraseña si se proporcionó una nueva y es válida
        if ($request->filled('password')) {
            if ($request->input('password') === $request->input('password_confirmation')) {
                $cliente->password = $request->input('password');
            } else {
                return redirect()->back()->withErrors(['password' => 'Las contraseñas no coinciden.'])->withInput();
            }
        }

        // Actualizar la ciudad si se proporcionó
        if ($request->filled('ciudad')) {
            $cliente->fk_id_ciudad = $request->input('ciudad');
        }

        // Guardar los cambios
        $cliente->save();

        // Redireccionar con un mensaje de éxito
        return redirect()->route('misDatos')->with('success_message', '¡Tus datos han sido actualizados!');
    }


    public function recuperarPass(Request $request)
    {
        // Validar que el campo 'correo' está presente en la solicitud
        $request->validate([
            'correo' => 'required|email',
        ]);
    
        // Buscar el cliente por correo electrónico
        $cliente = Cliente::where('correo', $request->correo)->first();
    
        // Si el cliente no existe, redirigir con un mensaje de error
        if (!$cliente) {
            return back()->with('error', 'Correo no encontrado');
        }
    
        // Generar una nueva contraseña aleatoria de 8 caracteres
        $nuevaPassword = Str::random(8);
    
        // Actualizar la contraseña del cliente en la base de datos
        $cliente->password = $nuevaPassword;
        $cliente->save();
    
        // Enviar el correo electrónico con la nueva contraseña
        Mail::send('emails.recuperarPass', ['cliente' => $cliente, 'password' => $nuevaPassword], function ($message) use ($cliente) {
            $message->to($cliente->correo);
            $message->subject('Nueva contraseña de recuperación');
        });
    
        // Redirigir con un mensaje de éxito
        return back()->with('success_message', 'Se ha enviado una nueva contraseña a su correo electrónico.');
    }
    
}
