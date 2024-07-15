<h2>Entregas pendientes.</h2>
<table>
    <thead>
        <tr>
            <th>Identificacion</th>
            <th>hecho el ( DD/MM/AAAA | HH::MM::SS )</th>
            <th>estado de la compra</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @if($compras->isEmpty())
        <tr>
            <td colspan="4">No existen entregas que realizar.</td>
        </tr>
    @else
        @foreach ($compras as $compra)
            <tr>
                <td>{{ $compra->id }}</td>
                <td>{{ \Carbon\Carbon::parse($compra->created_at)->format('d-m-Y H:i:s') }}</td>
                <td>{{ $compra->nombreest }}</td>
                <td>
                    <a href="/verDetallesEntrega/{{$compra->id}}" class="action-link">Ver detalles</a>
                </td>
            </tr>
        @endforeach
    @endif
    
    </tbody>
</table>