<h2>Ofertas creadas.</h2>
<form action="{{ route('ofertasFiltrar') }}" method="GET">
    <label for="filtroNombre">Filtrar por Nombre de oferta o por código de barra (Dejar vacío para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombrem">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Oferta</th>
            <th>Cantidad</th>
            <th>Codigo de barra</th>

            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @if ($ofertas->isEmpty())
            <tr>
                <td colspan="4">No existen Ofertas con estas características.</td>
            </tr>
        @else
            @foreach ($ofertas as $oferta)
                <tr>
                    <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $oferta->material->nombrem }}
                    </td>
                    <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $oferta->cantidad_en_compra }}
                    </td>
                    <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $oferta->material->codigob }}
                    </td>
                    <td>
                        <a href="#" class="action-link-red" onclick="confirmEliminar({{ $oferta->idmatof }})">Eliminar</a>
                        <br><br>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>