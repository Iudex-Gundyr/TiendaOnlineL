<h2>categorias creados.</h2>
<form action="{{ route('categoriasfiltrar') }}">
    <label for="filtroNombre">Filtrar por Nombre de categoria (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombrecat">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre de categoria</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($categorias->isEmpty())
        <tr>
            <td colspan="2">No existen categorias con estas características.</td>
        </tr>
    @else
        @foreach ($categorias as $categoria)
            <tr>
                <td>{{ $categoria->nombrecat }}</td>
                <td>
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $categoria->id }})">Eliminar</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>