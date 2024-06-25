<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
@include('Tienda/Tienda')

<div>
    <h1 class="carrito-titulo">Carrito de Compra</h1>

    @auth('cliente')


        <div class="table-container">
            @forelse($materiales as $material)
                @if ($loop->first)
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Productos</th>
                                <th>Valor unitario</th>
                                <th>Cantidad a comprar</th>
                                <th>Valor a pagar</th>
                                <th>Disponibles</th>
                                <th>opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                            <tr>
                                <td>{{ $material->nombrem }}</td>
                                <td>${{ number_format($material->valorm, 0, ',', '.') }} CLP</td>
                                <td>
                                    <select name="cantidad" id="cantidad{{$material->id}}" onchange="actualizarCantidad({{ $material->carrito_id }}, this.value)">
                                        <option value="{{$material->cantidad}}" hidden>{{$material->cantidad}} unidad/es</option>
                                        @for ($i = 1; $i <= $material->cantidad_restante; $i++)
                                            <option value="{{ $i }}">{{ $i }} unidad/es</option>
                                        @endfor
                                    </select>
                                </td>
                                <td>${{ number_format($material->valor_total, 0, ',', '.') }} CLP</td>
                                <td>{{ $material->cantidad_restante }}</td>
                                <td>
                                    <a href="/verDetalleMaterial/{{ $material->id }}">Ver producto</a>
                                    <form id="eliminarForm{{$material->carrito_id}}" method="POST" action="{{ route('eliminarCarrito', ['id' => $material->carrito_id]) }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>

                            </tr>
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @empty

                <p class="carrito-vacio">No hay productos en el carrito normal.</p>
            @endforelse
        </div>
        <h1 class="carrito-titulo">Carrito de ofertas</h1>
        <div class="table-container">
            @forelse($materialesOferta as $material)
                @if ($loop->first)
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Productos</th>
                                <th>descuento</th>
                                <th>Valor unitario (Oferta incluida)</th>
                                <th>Cantidad a comprar</th>

                                <th>Valor a pagar (Oferta incluida)</th>
                                <th>Disponibles</th>
                                <th>opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                            <tr>
                                <td>{{ $material->nombrem }}</td>
                                <td>{{ $material->porcentajeof }}%</td>
                                <td>${{ number_format($material->valor_con_descuento, 0, ',', '.') }} CLP</td>
                                <td>
                                    <select name="cantidad" id="cantidad{{$material->id}}" onchange="actualizarCantidadOferta({{ $material->carrito_id }}, this.value)">
                                        <option value="{{$material->cantof}}" hidden>{{$material->cantof}} unidad/es</option>
                                        @for ($i = 1; $i <= $material->cantidad_en_compra; $i++)
                                            <option value="{{ $i }}">{{ $i }} unidad/es</option>
                                        @endfor
                                    </select>
                                </td>
                                <td>${{ number_format($material->totalPagar, 0, ',', '.') }} CLP</td>
                                <td>{{ $material->cantidad_en_compra }}</td>
                                <td>
                                    <a href="/verDetalleMaterial/{{ $material->id }}">Ver producto</a>
                                    <form id="eliminarForm{{$material->carrito_id}}" method="POST" action="{{ route('eliminarCarritoOferta', ['id' => $material->carrito_id]) }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>

                            </tr>
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @empty

                <p class="carrito-vacio">No hay productos en el carrito de ofertas.</p>
            @endforelse
        </div>



        <div class="table-container">
            <h2>Total a pagar</h2>
            Productos sin oferta
            ${{ number_format($totalPagar, 0, ',', '.') }} CLP<br>
            Productos con oferta
            ${{ number_format($totalPagarOferta, 0, ',', '.') }} CLP<br>
            Servicios y pago en linea (2%)
            ${{ number_format(($totalPagar + $totalPagarOferta)*0.02, 0, ',', '.') }} CLP<br>
            Total a pagar
            ${{ number_format($totalPagar + $totalPagarOferta + ($totalPagar + $totalPagarOferta)*0.02, 0, ',', '.') }} CLP
            <a href="/pagar">pagar</a>
        </div>
    @else
        <p class="carrito-mensaje">Debes iniciar sesión para armar y ver tu carrito.</p>
        <p><a href="{{ route('login') }}" class="carrito-enlace">Iniciar sesión</a></p>
    @endauth

</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function actualizarCantidad(carritoId, nuevaCantidad) {
        // Realizar la petición AJAX
        axios.post('{{ route('actualizarCantidad') }}', {
            carrito_id: carritoId,
            nueva_cantidad: nuevaCantidad
        })
        .then(function (response) {
            // Si la petición fue exitosa, recargar la página
            window.location.reload();
        })
        .catch(function (error) {
            console.error('Error al actualizar la cantidad:', error);
            // Manejar errores según sea necesario
        });
    }
    function actualizarCantidadOferta(carritoId, nuevaCantidad) {
    console.log('Carrito ID:', carritoId);
    console.log('Nueva cantidad:', nuevaCantidad);

    // Realizar la petición AJAX con Axios
    axios.post('{{ route('actualizarCantidadOferta') }}', {
        carrito_id: carritoId,
        nueva_cantidad: nuevaCantidad
    })
    .then(function (response) {
        console.log('Respuesta del servidor:', response.data);
        // Si la petición fue exitosa, recargar la página
        window.location.reload();
    })
    .catch(function (error) {
        console.error('Error al actualizar la cantidad:', error);
        // Manejar errores según sea necesario
    });
}
</script>