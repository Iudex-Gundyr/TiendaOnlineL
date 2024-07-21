<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Fotos;
use App\Models\Descripcion;
use App\Models\Materiales;

class FotosDescripcionController extends Controller
{
    public function FotosDescripcion($id)
    {
        $material = Materiales::select('materiales.*', 'marcas.nombremar', 'categorias.nombrecat', 'creador.nombreus as nombre_usuario_creador', 'actualizador.nombreus as nombre_usuario_actualizador')
        ->where('materiales.id', $id)
        ->join('marcas', 'materiales.fk_id_marcas', '=', 'marcas.id')
        ->join('categorias', 'materiales.fk_id_categorias', '=', 'categorias.id')
        ->join('usuarios as creador', 'materiales.fk_id_usuariocre', '=', 'creador.id')
        ->join('usuarios as actualizador', 'materiales.fk_id_usuarioupd', '=', 'actualizador.id')
        ->first();
        $fotos = Fotos::where('fk_id_material',$id)->get();
        $descripciones = Descripcion::where('fk_id_material',$id)->get();
        return view('Intranet/Materiales/FotosDescripcion/FotosDescripcion', compact('fotos','material','descripciones'));

    }

    public function agregarFoto($id, Request $request)
    {
        // Validar que el archivo sea una imagen y de tipo jpeg o png, y que no exceda 2MB
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png|max:2048',
        ]);
    
        // Obtener el archivo de la solicitud
        $file = $request->file('foto');
        $extension = $file->getClientOriginalExtension();
        
        // Crear una imagen desde el archivo
        if ($extension === 'jpeg' || $extension === 'jpg') {
            $image = imagecreatefromjpeg($file->getRealPath());
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($file->getRealPath());
        } else {
            return redirect()->back()->with('error', 'Formato de imagen no soportado');
        }
    
        // Definir las nuevas dimensiones y la calidad
        $newWidth = 800;
        $newHeight = 600;
    
        // Obtener las dimensiones originales de la imagen
        list($width, $height) = getimagesize($file->getRealPath());
    
        // Crear una nueva imagen con las dimensiones definidas
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
        // Redimensionar la imagen
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
        // Guardar la imagen comprimida en un archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'img');
        if ($extension === 'jpeg' || $extension === 'jpg') {
            imagejpeg($resizedImage, $tempFile, 75); // Calidad 75
        } elseif ($extension === 'png') {
            imagepng($resizedImage, $tempFile, 6); // Calidad 6
        }
    
        // Obtener el contenido binario del archivo temporal
        $fileContents = file_get_contents($tempFile);
    
        // Crear una nueva instancia de Fotos y asignar los valores
        $fotos = new Fotos();
        $fotos->fotografia = $fileContents;
        $fotos->fk_id_material = $id;
        $fotos->save();
    
        // Limpiar recursos
        imagedestroy($image);
        imagedestroy($resizedImage);
        unlink($tempFile);
    
        // Obtener todas las fotos y el material actual
        $fotos = Fotos::where('fk_id_material', $id)->get();
    
        // Redirigir a la vista 'FotosDescripcion' con los datos necesarios
        return redirect()->back()->with('success', 'Imagen subida correctamente');
    }
    
    public function eliminarFoto($id)
    {
        // Encontrar la foto por su ID
        $foto = Fotos::findOrFail($id);

        // Eliminar la foto
        $foto->delete();

        // Redirigir de vuelta con un mensaje de éxito
        return back()->with('success', 'Foto eliminada correctamente.');
    }


    public function agregarDescripcion($id, Request $request)
    {
        // Validar que el campo 'descripcion' esté presente y sea requerido
        $request->validate([
            'descripcion' => 'required',
        ]);
    
        // Crear una nueva instancia de Descripcion y asignar los valores
        $descripcion = new Descripcion();
        $descripcion->nombredes = $request->input('descripcion'); // Corregir el acceso al campo 'descripcion'
        $descripcion->fk_id_material = $id;
        $descripcion->save();
    
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Descripción subida correctamente');
    }

    public function eliminarDescripcion($id)
    {
        $Descripcion = Descripcion::findOrFail($id);

        // Eliminar la foto
        $Descripcion->delete();

        return redirect()->back()->with('success', 'Descripción eliminada correctamente');
    }
}
