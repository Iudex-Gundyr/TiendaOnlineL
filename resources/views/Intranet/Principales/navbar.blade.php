<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/Intranet.css') }}" rel="stylesheet">
</head>
<body>
    <div class="navbar">
  
        <ul>
            <img src="{{ asset('img/Logo.png') }}" alt="Logo de la empresa" class="logo">
            <li class="open"><a href="/dashboard"><i class="fa fa-building" aria-hidden="true"></i>Dashboard</a></li>
            <li class="open"><a href="/dashboard"><i class="fa fa-user" aria-hidden="true"></i>Clientes</a></li>
            <li class="open"><a href="/materiales"><i class="fa fa-boxes" aria-hidden="true"></i>Materiales</a></li>
            <li class="open"><a href="/Ofertas"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i>Ofertas</a></li>
            <li class="parent"><a href="#"><i class="fa fa-chart-line" aria-hidden="true"></i>Analisis de datos<i class="fa fa-arrow-down arrow-icon" aria-hidden="true"></i></a>
                <ul class="sub-menu">
                </ul>
            </li>
            <li class="parent"><a href="#"><i class="fas fa-cog"></i>Configuración<i class="fa fa-arrow-down arrow-icon" aria-hidden="true"></i></a>
                <ul class="sub-menu">
                    <li><a href="/usuarios"><i class="fa fa-user" aria-hidden="true"></i>Usuarios del sistema</a></li>   
                    <li><a href="/categorias"><i class="fas fa-list-ol"></i>Categorias Materiales</a></li>
                    <li><a href="/marcas"><i class="fas fa-star"></i>Marcas de materiales</a></li>
                    <li><a href="/paises"><i class="fas fa-globe"></i>Paises disponibles</a></li>

                </ul>
            </li>
            <li><a href="{{ route('cerrarSesion') }}">    <i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
        </ul>
    </div>
</body>
</html>
<script src="{{ asset('js/Intranet.js') }}"></script>