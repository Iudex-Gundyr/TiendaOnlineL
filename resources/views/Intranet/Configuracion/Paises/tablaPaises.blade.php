<h2>paises creados.</h2>
<form action="{{ route('paisesFiltrar') }}">
    <label for="filtroNombre">Filtrar por Nombre del país (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombrepa">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre del país</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($paises->isEmpty())
        <tr>
            <td colspan="2">No existen paises con estas características.</td>
        </tr>
    @else
        @foreach ($paises as $pais)
            <tr>
                <td>{{ $pais->nombrepa }}</td>
                <td>
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $pais->id }})">Eliminar</a>
                    <a href="{{ route('Regiones', ['id' => $pais->id]) }}" class="action-link">Regiones</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>