<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis compras</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('Tienda/Tienda')

<div class="table-container">
    <h1 style="text-align:center">Mis compras</h1>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Valor del producto</th>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Opciones</th>
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
        </tbody>
        
    </table>
</div>


</body>
</html>
