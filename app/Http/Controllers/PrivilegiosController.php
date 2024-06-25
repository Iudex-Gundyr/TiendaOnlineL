<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Privilegios;
use App\Models\PrivilegioUsuario;
use Illuminate\Support\Facades\DB; 

class PrivilegiosController extends Controller
{
    public function privilegios($id)
    {
        // Obtener los privilegios del usuario con sus detalles incluyendo los IDs de privilegio_usuario
        $privilegioUsuario = DB::table('privilegio_usuario as pu')
            ->select('pu.id as privilegio_usuario_id', 'pu.fk_id_privilegio', 'p.nombrepri')
            ->join('privilegios as p', 'pu.fk_id_privilegio', '=', 'p.id')
            ->where('pu.fk_id_usuario', $id)
            ->get();
    
        // Obtener información del usuario
        $usuario = Usuarios::find($id);
    
        // Obtener todos los privilegios disponibles
        $privilegios = Privilegios::all();
    
        // Devolver la vista con los datos necesarios
        return view('Intranet/usuarios/Privilegios/privilegios', compact('privilegios', 'usuario', 'privilegioUsuario'));
    }
    
    public function agregarPrivilegio(request $request,$id)
    {
        $request->validate([
            'privilegio' => 'required|exists:privilegios,id', // Asegúrate primero que el privilegio existe
            // Validación personalizada para verificar la unicidad de fk_id_privilegio por usuario
            'privilegio' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $exists = PrivilegioUsuario::where('fk_id_usuario', $id)
                                               ->where('fk_id_privilegio', $value)
                                               ->exists();
                    if ($exists) {
                        $fail('Este privilegio ya está asignado a este usuario.');
                    }
                },
            ],
        ]);

        $privilegiosUsuario = new PrivilegioUsuario();
        $privilegiosUsuario->fk_id_usuario = $id;
        $privilegiosUsuario->fk_id_privilegio = $request->privilegio;
        $privilegiosUsuario->save();
        return redirect()->back()->with('success', 'Privilegio asignado correctamente.');
    }

    public function eliminarPrivilegio($id)
    {
        // Buscar el registro por su ID
        $privilegioUsuario = PrivilegioUsuario::find($id);
        
        // Verificar si el registro existe
        if (!$privilegioUsuario) {
            // Si no existe, redireccionar o devolver un mensaje de error
            return redirect()->back()->with('error', 'El privilegio de usuario no existe.');
        }
        
        try {
            // Eliminar el registro
            $privilegioUsuario->delete();
            
            // Redireccionar con un mensaje de éxito
            return redirect()->back()->with('success', 'El privilegio de usuario se eliminó correctamente.');
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la eliminación
            return redirect()->back()->with('error', 'Error al eliminar el privilegio de usuario: ' . $e->getMessage());
        }
    }
}


