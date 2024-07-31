<h2>Cliente.</h2>
<table>
    <thead>
        <tr>
            <th>Nombre del cliente</th>
            <th>Correo</th>
            <th>Numero de telefono</th>
            <th>Numero de telefono fijo</th>
            <th>Ubicación</th>
            <th>Dirección</th>
        </tr>
    </thead>
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
            <td>{{$cliente->nombrepa}} - {{ $cliente->nombrere }} - {{ $cliente->nombreci }}</td>
        @endif
        @if(empty($cliente->direccion))
            <td>No se proporcionó nombre de cliente</td>
        @else
            <td>{{ $cliente->direccion }} @if(!empty($cliente->numerod)) - {{$cliente->numerod}} @endif @if(!empty($cliente->blockd)) - {{$cliente->blockd}} @endif </td>
        @endif
    </tbody>
</table>
<h2>Productos a entregar.</h2>
<table>
    <thead>
        <tr>
            <th>Valor</th>
            <th>Cantidad</th>
            <th>Producto</th>
        </tr>
    </thead>
    <tbody>
    @if($detallesCompra->isEmpty())
        <tr>
            <td colspan="4">No se realizaron compras sin oferta.</td>
        </tr>
    @else
        @foreach ($detallesCompra as $compra)
            <tr>
                <td>${{ number_format($compra->valorcm, 0, ',', '.') }} CLP</td>
                <td>{{ $compra->cantidad }}</td>
                <td>{{ $compra->nombrem }}</td>
            </tr>
        @endforeach
    @endif
    @if($detallesCompraOferta->isEmpty())
    <tr>
        <td colspan="4">No se realizando compras con oferta.</td>
    </tr>
    @else
        @foreach ($detallesCompraOferta as $compra)
            <tr>
                <td>${{ number_format($compra->valor, 0, ',', '.') }} CLP</td>
                <td>{{ $compra->cantidad }}</td>
                <td>{{ $compra->nombrem }}</td>
            </tr>
        @endforeach
    @endif
    
    </tbody>

</table>
<script>
    function formatRUT(input) {
        let value = input.value.replace(/\D/g, ''); // Elimina cualquier caracter que no sea número
        if (value.length > 1) {
            value = value.slice(0, -1) + '-' + value.slice(-1); // Añade el guion antes del último dígito
        }
        if (value.length > 4) {
            value = value.slice(0, -5) + '.' + value.slice(-5); // Añade el punto en la posición adecuada
        }
        if (value.length > 8) {
            value = value.slice(0, -9) + '.' + value.slice(-9); // Añade otro punto en la posición adecuada
        }
        input.value = value;
    }

    function validateForm(event) {
        event.preventDefault(); // Previene el envío del formulario
        
        const input = document.getElementById('RUT');
        const inputValue = input.value.trim(); // Obtener el valor del RUT y eliminar espacios en blanco al inicio y al final
        const documentacion = input.getAttribute('data-documentacion');
        const permitido = "20.381.209-4"; // Texto permitido para enviar el formulario

        if (inputValue !== documentacion && inputValue !== permitido) {
            alert('El RUT ingresado no coincide con la documentación del cliente ni es el texto permitido.');
        } else {
            event.target.submit(); // Envía el formulario si la validación es correcta
        }
    }
</script>
</head>
<form action="{{ route('realizarEntrega', ['id' => $id]) }}" onsubmit="validateForm(event)">
    <h1>
        <label for="filtroNombre">Ingresar el rut del cliente, para cambiar el estado a entregado:</label>
    </h1>
    <input type="text" id="RUT" name="RUT" oninput="formatRUT(this)" data-documentacion="{{ $cliente->documentacion }}">
    <input type="submit" value="Entregar">
</form>
