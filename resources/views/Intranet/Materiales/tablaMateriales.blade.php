<h2>materiales creados.</h2>
<form action="{{ route('materialesfiltrar') }}">
    <label for="filtroNombre">Filtrar por Nombre de material o por codigo de barra (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombrem">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Codigo de barra</th>
            <th>Nombre del material</th>
            <th>Cantidad actual</th>
            <th>Valor</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($materiales->isEmpty())
        <tr>
            <td colspan="2">No existen materiales con estas características.</td>
        </tr>
    @else
        @foreach ($materiales as $material)
            <tr>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $material->codigob }}</td>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $material->nombrem }}</td>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $material->cantidad_restante }}</td>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${{ number_format($material->valorm, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('modificarMaterial', ['id' => $material->id]) }}" class="action-link">Modificar</a>
                    <br>                    <br>
                    <a href="{{ route('FotosDescripcion', ['id' => $material->id]) }}" class="action-link-green">Fotos / Descripcion </a>
                    <br>                    <br>
                    <a href="{{ route('cantidad', ['id' => $material->id]) }}" class="action-link-yellow">Cantidad</a>
                    <br>                    <br>
                    <!-- Enlace para eliminar -->
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $material->id }})">Eliminar</a>

                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>