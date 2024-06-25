<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('img/Logo.png') }}" alt="Logo de la empresa" class="logo">
        <h2>Iniciar Sesión</h2>
        <form action="{{ route('IniciarSesion') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="nombreus">Nombre de usuario:</label>
                <input type="nombreus" id="nombreus" name="nombreus" value="{{ old('nombreus') }}" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            @if ($errors->has('mensaje'))
                <span style="color: red;">{{ $errors->first('mensaje') }}</span>
            @endif<br>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>