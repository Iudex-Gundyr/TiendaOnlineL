<h2>Agregar o quitar fotos del material {{$material->nombrem}}.</h2>
<form action="{{ route('agregarFoto', $material->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="foto">Foto (JPG o PNG):</label>
    <input type="file" id="foto" name="foto" accept="image/png, image/jpeg" required>

    <input type="submit" value="Realizar acción">
</form>
<table>
    <thead>
        <tr>
            <th>foto</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @if ($fotos->isEmpty())
            <tr>
                <td colspan="2">No existen fotos para mostrar.</td>
            </tr>
        @else
            @foreach ($fotos as $foto)
                <tr>
                    <!-- Mostrar la imagen -->
                    <td>
                        <img src="data:image/jpeg;base64,{{ base64_encode($foto->fotografia) }}" alt="Foto" style="max-width: 100px;">
                        <br>
                    </td>
        
                    <!-- Enlace para eliminar -->
                    <td>
                        <a href="#" class="action-link-red" onclick="confirmEliminarF({{ $foto->id }})">Eliminar</a>
                    </td>
                </tr>
            @endforeach
        @endif
    
        <!-- Aquí se pueden añadir las filas dinámicamente -->
    </tbody>
</table>