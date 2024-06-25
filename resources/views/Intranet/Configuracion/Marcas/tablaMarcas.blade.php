<h2>marcas creadas.</h2>
<form action="{{ route('marcasfiltrar') }}">
    <label for="filtroNombre">Filtrar por Nombre de la marca (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombremar">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre de la marca</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($marcas->isEmpty())
        <tr>
            <td colspan="2">No existen marcas con estas características.</td>
        </tr>
    @else
        @foreach ($marcas as $marca)
            <tr>
                <td>{{ $marca->nombremar }}</td>
                <td>
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $marca->id }})">Eliminar</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>