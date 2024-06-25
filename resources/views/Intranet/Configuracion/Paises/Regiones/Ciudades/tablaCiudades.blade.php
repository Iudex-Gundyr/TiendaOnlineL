<h2>Ciudades creados.</h2>
<form action="{{ route('ciudadesfiltrar', ['id' => $id]) }}">
    <label for="filtroNombre">Filtrar por Nombre de ciudad/localidad (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombreci">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre de la ciudad/localidad</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($ciudades->isEmpty())
        <tr>
            <td colspan="2">No existen categorias con estas características.</td>
        </tr>
    @else
        @foreach ($ciudades as $ciudad)
            <tr>
                <td>{{ $ciudad->nombreci }}</td>
                <td>
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $ciudad->id }})">Eliminar</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>