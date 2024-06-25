<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Ofertas;

class OfertasController extends Controller
{
    public function Ofertas()
    {
        $ofertas = Ofertas::where('fk_id_estadoel',1)->orderBy('id', 'desc')->take(5)->get();
        return view('Intranet/Ofertas/Ofertas', compact('ofertas'));
    }
    public function modificarOferta($id)
    {
        $ofertas = Ofertas::where('fk_id_estadoel',1)->orderBy('id', 'desc')->take(5)->get();
        $oferta = Ofertas::where('fk_id_estadoel',1)->where('id',$id)->orderBy('id', 'desc')->take(5)->first();
        return view('Intranet/Ofertas/modificarOferta', compact('ofertas','oferta'));
    }

    public function crearOferta(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombreof' => 'required|string|max:255',
            'fotografia' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'porcentajeof' => 'required|numeric|min:0|max:100',
            'fechaexp' => 'required|date|after:today',
        ]);
    
        // Manejar la subida de la imagen
        if ($request->hasFile('fotografia')) {
            $file = $request->file('fotografia');
            $fileData = file_get_contents($file);  // Convertir la imagen a formato binario
        }
    
        // Crear una nueva oferta (suponiendo que tienes un modelo Oferta)
        $oferta = new Ofertas();
        $oferta->nombreof = $validatedData['nombreof'];
        $oferta->fotografia = $fileData;  // Guardar el binario en la base de datos
        $oferta->porcentajeof = $validatedData['porcentajeof'];
        $oferta->fechaexp = $validatedData['fechaexp'];
        $oferta->fk_id_estadoel = 1;
        $oferta->save();
    
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Oferta creada exitosamente.');
    }

    public function updateOferta($id, Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombreof' => 'required|string|max:255',
            'fotografia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Hacer que la fotografía sea opcional
            'porcentajeof' => 'required|numeric|min:0|max:100', // Validación de porcentaje
            'fechaexp' => 'required|date|after:today', // Validación de fecha
        ]);
    
        // Encontrar la oferta existente
        $oferta = Ofertas::findOrFail($id);
    
        
        // Actualizar los datos de la oferta
        $oferta->nombreof = $validatedData['nombreof'];
        $oferta->porcentajeof = $validatedData['porcentajeof'];
        $oferta->fechaexp = $validatedData['fechaexp'];
        // Manejar la subida de la imagen
        if ($request->hasFile('fotografia')) {
            $file = $request->file('fotografia');
            $fileData = file_get_contents($file);  // Convertir la imagen a formato binario
            $oferta->fotografia = $fileData;
        }
        
    
        // Guardar la oferta actualizada
        $oferta->save();
    
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Oferta actualizada exitosamente.');
    }
    public function eliminarOferta($id)
    {
        // Encontrar la oferta por ID
        $oferta = Ofertas::findOrFail($id);
        
        // Cambiar el estado a 2
        $oferta->fk_id_estadoel = 2;
        
        // Guardar los cambios
        $oferta->save();
        
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Oferta eliminada exitosamente.');
    }
}