
@include('Tienda/Tienda')

<div class="container">
    <div class="row">
    <div class="table-container">
    <h1 style="text-align:center">Mis compras</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Fecha</th>
                <th scope="col">Estado de la compra</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($compras as $compra)
                <tr>
                    <td>{{ $compra->id }}</td>
                    <td>{{ $compra->created_at }}</td>
                    <td>{{ $compra->nombreest }}</td>
                    <td><a href="/DetallesMisCompras/{{$compra->id}}">Ver detalles</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="carrito-vacio">No compraste ningun producto</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

    </div>
</div>
@include('Tienda/footer')