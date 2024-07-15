{{-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Responsivo Flotante</title>
    <link href="{{ asset('css/TiendaMenu.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body> --}}


    <nav class="navbar navbar-expand-lg sticky-top bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo de la empresa" style="width:100px" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                <form action="{{ route('IniciarSesionCliente') }}" method="POST"
                    class="login-form row g-3 align-items-center" id="loginForm">
                    @csrf
                    <div class="col-auto">
                        <input type="text" id="correo" name="correo" class="form-control " placeholder="Correo"
                            value="{{ old('correo') }}" required>
                    </div>
                    <div class="col-auto">
                        <span class="mx-2"><a style="color: white" href="/Registrar">Registrarse</a></span>
                    </div>
                    <div class="col-auto">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="Contraseña" required>

                    </div>
                    <div class="col-auto">
                        <span class="mx-2"><a id="forgotPassword" style="color: white" href="#">Olvide mi
                                contraseña</a></span>
                    </div>
                    @error('pass')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                    <div class="col-auto">
                        <button type="submit" class="btn btn-info hover:bg-primary hover:text-white">Iniciar Sesión</button>

                    </div>
                </form>


                @if ($errors->has('error'))
                    <span style="color: red;">{{ $errors->first('error') }}</span>
                @endif

                </ul>

            </div>
        </div>
    </nav>
    <!-- <nav class="navbar">
        <div class="navbar-container">
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <ul class="nav-menu">
                <form action="{{ route('IniciarSesionCliente') }}" method="POST" class="login-form" id="loginForm">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo" value="{{ old('correo') }}" required>
                        <br>
                        <a style="color: white"  href="/Registrar">Registrarse</a>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                        <br>
                        <a id="forgotPassword" style="color: white"  href="#">Olvide mi contraseña</a>
                    </div>
                    @error('pass')
    <span style="color: red;">{{ $message }}</span>
@enderror
                    <button type="submit" class="btn">Iniciar Sesión</button>
                </form>
                
                
                @if ($errors->has('error'))
<span style="color: red;">{{ $errors->first('error') }}</span>
@endif

            </ul>
            <div class="login-container">
            </div>
        </div>
    </nav> -->

    <div class="content">

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/recuperarPass.js') }}"></script>
    <script>
        var recuperarPassUrl = '{{ route('recuperarPass') }}';
    </script>
{{-- </body>

</html> --}}
