<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Responsivo Flotante</title>
    <link href="{{ asset('css/TiendaMenu.css') }}" rel="stylesheet">
    
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="/productos"  class="nav-link">Productos</a></li>
                <li class="nav-item"><a href="/verCarrito"  class="nav-link" id="carritoBtn">Carrito</a></li>
                <li class="nav-item"><a href="/misDatos" class="nav-link"></i> Mis datos</a></li>
                <li class="nav-item"><a href="/MisCompras" class="nav-link"><i class="fas fa-shopping-bag"></i> Mis compras</a></li>
                <li class="nav-item"><a href="/cerrarSesion" class="nav-link"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
            </ul>

        </div>
    </nav>
    <div class="content">

    </div>


</body>
</html>