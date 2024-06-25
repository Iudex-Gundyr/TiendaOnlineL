<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <link href="{{ asset('css/Tienda.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @if(Auth::guard('cliente')->check())
        @include('Tienda/subcarpetas/navbarIC')
    @else
        @include('Tienda/subcarpetas/navbarSC')
    @endif


    <header>
        <div class="container">
            <div class="logo-container">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo de la empresa" class="logo">
            </div>
            <nav>

                <ul>
                    <li><a href="/productos">Productos</a></li>
                    <li><a href="/verCarrito" id="carritoBtn">Carrito (<span id="carritoCantidad">0</span>)</a></li>
                </ul>
            </nav>
        </div>
    </header>
    @if (session('success_message'))
        <div class="alert alert-aprobed">
            {{ session('success_message') }}
            <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
    @endif
    <div class="container">

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/Tienda.js') }}"></script>
    <script src="{{ asset('js/carritoCompra.js') }}"></script>
    <script>
        // Definir una variable global para indicar si el usuario está autenticado
        var isLoggedIn = @json(Auth::guard('cliente')->check()); // Esto se evaluará como true o false en JavaScript
        const agregarCarritoUrl = '{{ route("agregarCarrito") }}';
        const agregarCarritoOfertaUrl = '{{ route("agregarCarritoOferta") }}';
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    </script>
</body>
</html>
