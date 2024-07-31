{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body> --}}
@include('Tienda/Tienda')

<div class="container">
    <h1 class="h1  fw-bold">Carrito de Compra</h1>

    @auth('cliente')



        <div class="table-responsive ">
            @forelse($materiales as $material)
                @if ($loop->first)
                    <table class="table border shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                        <thead>
                            <tr>
                                <th  scope="col">Productos</th>
                                <th  scope="col">Valor unitario</th>
                                <th  scope="col">Cantidad a comprar</th>
                                <th  scope="col">Total</th>
                                <th  scope="col">Disponibles</th>
                                <th  scope="col">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                            <tr>
                                <td>{{ $material->nombrem }}</td>
                                <td>${{ number_format($material->valorm, 0, ',', '.') }} CLP</td>
                                <td>
                                    <select name="cantidad" id="cantidad{{$material->id}}" class="form-select" onchange="actualizarCantidad({{ $material->carrito_id }}, this.value)">
                                        <option value="{{$material->cantidad}}" hidden>{{$material->cantidad}} unidad/es</option>
                                        @for ($i = 1; $i <= min(50, $material->cantidad_restante); $i++)
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
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>

                            </tr>
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @empty

                <p class="text-danger">No hay productos en el carrito normal.</p>
            @endforelse
        </div>



        <h1 class="h2 ">Carrito de ofertas</h1>
        <div class="table-responsive">
            @forelse($materialesOferta as $material)
                @if ($loop->first)
                    <table class="table shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                        <thead>
                            <tr>
                                <th scope="col" >Productos</th>
                                <th scope="col">descuento</th>
                                <th scope="col">Valor unitario (Oferta incluida)</th>
                                <th scope="col">Cantidad a comprar</th>

                                <th scope="col">Valor a pagar (Oferta incluida)</th>
                                <th scope="col">Disponibles</th>
                                <th scope="col">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
                            <tr>
                                <td>{{ $material->nombrem }}</td>
                                <td>{{ $material->porcentajeof }}%</td>
                                <td>${{ number_format($material->valor_con_descuento, 0, ',', '.') }} CLP</td>
                                <td>
                                    <select name="cantidad" id="cantidad{{$material->id}}" class="form-select" onchange="actualizarCantidadOferta({{ $material->carrito_id }}, this.value)">
                                        <option value="{{$material->cantof}}" hidden>{{$material->cantof}} unidad/es</option>
                                        @for ($i = 1; $i <= min(50, $material->cantidad_en_compra); $i++)
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
                                        <button type="submit" class="btn btn-primary">Eliminar</button>
                                    </form>
                                </td>

                            </tr>
                @if ($loop->last)
                        </tbody>
                    </table>
                @endif
            @empty

                <p class="text-danger">No hay productos en el carrito de ofertas.</p>
            @endforelse
        </div>



        <div class="card mb-2 shadow-sm p-3 mb-5 bg-body-tertiary rounded">
            <div class="card-body">
            <h2 class="card-title fw-bold">Total a pagar</h2>
            <p class="card-text fw-bold">
           Productos sin oferta
            ${{ number_format($totalPagar, 0, ',', '.') }} CLP <br>
            Productos con oferta
            ${{ number_format($totalPagarOferta, 0, ',', '.') }} CLP<br>
            Servicios y pago en linea (2%)
            ${{ number_format(($totalPagar + $totalPagarOferta)*0.02, 0, ',', '.') }} CLP<br>
            Total a pagar
            ${{ number_format($totalPagar + $totalPagarOferta + ($totalPagar + $totalPagarOferta)*0.02, 0, ',', '.') }} CLP
            </p>
           
            <a href="/pagar" class="btn btn-success btn-lg">Continuar la compra</a>
        </div>
    @else
        <p class="carrito-mensaje">Debes iniciar sesión para armar y ver tu carrito.</p>
        <p><a href="{{ route('login') }}" class="carrito-enlace">Iniciar sesión</a></p>
    @endauth
            </div>
         

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
@include('Tienda/footer')