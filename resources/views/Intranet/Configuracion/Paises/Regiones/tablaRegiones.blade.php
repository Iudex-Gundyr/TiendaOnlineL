<h2>Regiones creadas.</h2>
<form action="{{ route('regionesFiltrar', ['id' => $id]) }}">
    <label for="filtroNombre">Filtrar por Nombre del la región (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombrere">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre del la región</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($regiones->isEmpty())
        <tr>
            <td colspan="2">No existen regiones con estas características.</td>
        </tr>
    @else
        @foreach ($regiones as $region)
            <tr>
                <td>{{ $region->nombrere }}</td>
                <td>
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $region->id }})">Eliminar</a>
                    <a href="{{ route('ciudades', ['id' => $region->id]) }}" class="action-link">Ciudades</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>