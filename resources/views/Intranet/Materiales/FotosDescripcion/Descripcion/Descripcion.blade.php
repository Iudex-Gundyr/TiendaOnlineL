<h2>Agregar o quitar descripciones del material {{$material->nombrem}}.</h2>
<form action="{{ route('agregarDescripcion', $material->id) }}" method="POST">
    @csrf
    <label for="descripcion">descripcion:</label>
    <input type="text" id="descripcion" name="descripcion" required>
    <input type="submit" value="Realizar acción">
</form>
<table>
    <thead>
        <tr>
            <th>descripcion</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($descripciones->isEmpty())
        <tr>
            <td colspan="2">No existen descripciones.</td>
        </tr>
    @else
        @foreach ($descripciones as $descripcion)
            <tr>

                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $descripcion->nombredes }} </td>

                <td>
                    <!-- Enlace para eliminar -->
                    <a href="#" class="action-link-red" onclick="confirmEliminarD({{ $descripcion->id }})">Eliminar</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->
    </tbody>
</table>