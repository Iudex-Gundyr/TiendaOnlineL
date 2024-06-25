<h2>Ofertas creadas.</h2>
<form action="{{ route('ofertasFiltrar') }}">
    <label for="filtroNombre">Filtrar por Nombre de oferta o por codigo de barra (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombrem">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre de la oferta</th>
            <th>Porcentaje</th>
            <th>Fecha de expiración</th>
            <th>Imagen</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($ofertas->isEmpty())
        <tr>
            <td colspan="2">No existen Ofertas con estas características.</td>
        </tr>
    @else
        @foreach ($ofertas as $oferta)
            <tr>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $oferta->nombreof }}
                </td>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $oferta->porcentajeof }}%
                </td>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ \Carbon\Carbon::parse($oferta->fechaexp)->format('d/m/Y') }}
                </td>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <img src="data:image/jpeg;base64,{{ base64_encode($oferta->fotografia) }}" alt="Fotografía de la oferta" style="max-width: 100px;">
                </td>
                <td>
                    <a href="{{ route('modificarOferta', ['id' => $oferta->id]) }}" class="action-link">Modificar</a>
                    <br>                    <br>
                    <a href="#" class="action-link-red" onclick="confirmEliminar({{ $oferta->id }})">Eliminar</a>
                    <br>                    <br>
                    <a href="{{ route('materialOferta', ['id' => $oferta->id]) }}" class="action-link-green">Asignar materiales</a>
                    <br>                    <br>

                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>