<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="{{ asset('css/Tienda.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{ asset('css/TiendaMenu.css') }}" rel="stylesheet">
</head>
<body style="">
    @if(Auth::guard('cliente')->check())
        @include('Tienda/subcarpetas/navbarIC')
    @else
        @include('Tienda/subcarpetas/navbarSC')
    @endif


    <header>
        <div class="container">
            <!-- <div class="logo-container">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo de la empresa" style="width:200px" class="logo">
            </div> -->
            <nav>

                <ul>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Definir una variable global para indicar si el usuario está autenticado
        var isLoggedIn = @json(Auth::guard('cliente')->check()); // Esto se evaluará como true o false en JavaScript
        const agregarCarritoUrl = '{{ route("agregarCarrito") }}';
        const agregarCarritoOfertaUrl = '{{ route("agregarCarritoOferta") }}';
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    </script>
