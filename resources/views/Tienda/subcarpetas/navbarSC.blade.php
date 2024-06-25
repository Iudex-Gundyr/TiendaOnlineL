<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Responsivo Flotante</title>
    <link href="{{ asset('css/TiendaMenu.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <form action="{{ route('IniciarSesionCliente') }}" method="POST" class="login-form" id="loginForm">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="correo" name="correo" placeholder="Correo" value="{{ old('correo') }}" required>
                        <br>
                        <a style="color: white" href="/Registrar">Registrarse</a>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>
                        <br>
                        <a id="forgotPassword" style="color: white" href="#">Olvide mi contraseña</a>
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
    </nav>

    <div class="content">

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/recuperarPass.js') }}"></script>
    <script>
        var recuperarPassUrl = '{{ route('recuperarPass') }}';
    </script>
</body>
</html>