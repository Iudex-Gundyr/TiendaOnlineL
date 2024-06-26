<h2>Cliente.</h2>
<table>
    <thead>
        <tr>
            <th>Nombre del cliente</th>
            <th>Correo</th>
            <th>Documentacion</th>
            <th>Numero de telefono</th>
            <th>Numero de telefono fijo</th>
            <th>Ubicación</th>
            <th>Dirección</th>
            <th>Opciones</th>
        </tr>
    </thead>

    @forelse ($clientes as $cliente)
    <tbody>
        @if(empty($cliente->nombrec))
            <td>No se proporcionó nombre de cliente</td>
        @else
            <td>{{ $cliente->nombrec }}</td>
        @endif
        @if(empty($cliente->correo))
            <td>No se proporcionó correo de cliente</td>
        @else
            <td>{{ $cliente->correo }}</td>
        @endif
        @if(empty($cliente->documentacion))
            <td>No se proporcionó documentacion de cliente</td>
        @else
            <td>{{ $cliente->documentacion }}</td>
        @endif
        @if(empty($cliente->telefono))
            <td>No se proporcionó celular de cliente</td>
        @else
            <td>{{ $cliente->telefono }}</td>
        @endif
        @if(empty($cliente->telefonof))
            <td>No se proporcionó telefono fijo</td>
        @else
            <td>{{ $cliente->telefonof }}</td>
        @endif
        @if(empty($cliente->nombreci))
            <td>No se proporcionó ciudad del cliente</td>
        @else
            <td>{{ $cliente->nombrepa }} - {{ $cliente->nombrere }} - {{ $cliente->nombreci }}</td>
        @endif
        @if(empty($cliente->direccion))
            <td>No se proporcionó dirección del cliente</td>
        @else
            <td>{{ $cliente->direccion }} @if(!empty($cliente->numerod)) - {{ $cliente->numerod }} @endif @if(!empty($cliente->blockd)) - {{ $cliente->blockd }} @endif </td>
        @endif
            <td><a href="/ComprasCliente/{{ $cliente->id }}" class="action-link-yellow">Ver Compras</a></td>
    </tbody>
    @empty
        <tbody>
            <tr>
                <td colspan="6">No hay clientes registrados</td>
            </tr>
        </tbody>
    @endforelse

</table>