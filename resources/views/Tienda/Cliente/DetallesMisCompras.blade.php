@include('Tienda/Tienda')

    <div class="container">
        <div class="row">
            <h1 style="text-align:center">Productos comprados</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Valor del producto</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Producto</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detallesCompra as $compra)
                    <tr>
                        <td>{{ $compra->valorcm }}</td>
                        <td>{{ $compra->cantidad }}</td>
                        <td>{{ $compra->nombrem }}</td>
                        <td><a href="{{ route('verDetalleMaterial', $compra->id) }}">Ver detalles</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="carrito-vacio">No hay productos en el carrito de ofertas.</td>
                    </tr>
                    @endforelse
                    @forelse ($detallesCompraOferta as $compra)
                    <tr>
                        <td>{{ $compra->valor }}</td>
                        <td>{{ $compra->cantidad }}</td>
                        <td>{{ $compra->nombrem }}</td>
                        <td><a href="{{ route('verDetalleMaterial', $compra->id) }}">Ver detalles</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="carrito-vacio">No hay productos en el carrito de ofertas.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    @include('Tienda/footer')
