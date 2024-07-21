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
            $filePath = $file->getPathname(); // Obtén el path temporal del archivo
    
            // Obtener la información de la imagen
            $imageInfo = getimagesize($filePath);
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];
    
            // Crear una imagen de GD desde el archivo subido
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($filePath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($filePath);
                    break;
                default:
                    throw new \Exception('Tipo de imagen no soportado');
            }
    
            // Comprimir la imagen
            $compressionQuality = 75; // Ajusta la calidad de compresión (0-100)
            ob_start(); // Inicia un buffer de salida
            imagejpeg($image, null, $compressionQuality); // Comprime la imagen a JPEG
            $fileData = ob_get_clean(); // Obtén los datos de la imagen comprimida y limpia el buffer
    
            // Liberar memoria
            imagedestroy($image);
        } else {
            $fileData = null;
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
    
        // Manejar la subida de la imagen si se proporciona
        if ($request->hasFile('fotografia')) {
            $file = $request->file('fotografia');
            $filePath = $file->getPathname(); // Obtén el path temporal del archivo
    
            // Obtener la información de la imagen
            $imageInfo = getimagesize($filePath);
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];
    
            // Crear una imagen de GD desde el archivo subido
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($filePath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($filePath);
                    break;
                default:
                    throw new \Exception('Tipo de imagen no soportado');
            }
    
            // Comprimir la imagen
            $compressionQuality = 75; // Ajusta la calidad de compresión (0-100)
            ob_start(); // Inicia un buffer de salida
            imagejpeg($image, null, $compressionQuality); // Comprime la imagen a JPEG
            $fileData = ob_get_clean(); // Obtén los datos de la imagen comprimida y limpia el buffer
    
            // Liberar memoria
            imagedestroy($image);
    
            // Actualizar el campo de la imagen en la oferta
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