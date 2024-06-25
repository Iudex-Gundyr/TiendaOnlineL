<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('Tienda/Tienda')

<div class="modal-content">
    <form action="{{ route('registrarCliente') }}" method="POST" class="register-form">
        @csrf
        <h2>Registrarse</h2>
        <div class="input-group">
            <label for="register-nombrec">Nombre de usuario:</label>
            <input type="text" id="register-nombrec" name="nombrec" value="{{ old('nombrec') }}" required>
            @error('nombrec')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-email">Correo electrónico:</label>
            <input type="email" id="register-email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-documentacion">RUT (Escribir sin puntos ni guion):</label>
            <input type="text" id="register-documentacion" name="documentacion" value="{{ old('documentacion') }}" required>
            @error('documentacion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-password">Contraseña:</label>
            <input type="password" id="register-password" name="password" required>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-password2">Repetir contraseña:</label>
            <input type="password" id="register-password2" name="password_confirmation" required>
            @error('password_confirmation')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-country">País:</label>
            <select id="register-country" name="pais" required>
                <option value="" disabled selected>Seleccione su país</option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais->id }}" {{ old('pais') == $pais->id ? 'selected' : '' }}>{{ $pais->nombrepa }}</option>
                @endforeach
            </select>
            @error('pais')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-region">Región/Estado:</label>
            <select id="register-region" name="region" required>
                <option value="" disabled selected>Seleccione su región</option>
            </select>
            @error('region')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-ciudad">Ciudad/Localidad:</label>
            <select id="register-ciudad" name="ciudad" required>
                <option value="" disabled selected>Seleccione su ciudad/localidad</option>
            </select>
            @error('ciudad')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-direccion">Dirección:</label>
            <input type="text" id="register-direccion" name="direccion" value="{{ old('direccion') }}" required>
            @error('direccion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-numero">Número:</label>
            <input type="text" id="register-numero" name="numero" value="{{ old('numero') }}" required>
            @error('numero')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-block">Block (Opcional):</label>
            <input type="text" id="register-block" name="block" value="{{ old('block') }}">
            @error('block')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-telefono">Teléfono:</label>
            <input type="text" id="register-telefono" name="telefono" value="{{ old('telefono') }}" required>
            @error('telefono')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-telefonof">Teléfonof (Opcional):</label>
            <input type="text" id="register-telefonof" name="telefonof" value="{{ old('telefonof') }}">
            @error('telefonof')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn">Registrarse</button>
    </form>
</div>

<!-- Definir la URL de la ruta como un atributo de datos -->
<div id="ruta-tomar-regiones" data-url="{{ route('tomarRegiones', ['id' => ':id']) }}"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/registrar.js') }}"></script>

</body>
</html>
