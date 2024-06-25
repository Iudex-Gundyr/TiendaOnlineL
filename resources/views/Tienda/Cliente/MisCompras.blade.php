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
                <th>Id</th>
                <th>Fecha</th>
                <th>Estado de la compra</th>
                <th>Opciones</th>
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

</body>
</html>
