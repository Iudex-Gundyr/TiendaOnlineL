{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body> --}}

@include('Tienda/Tienda')

<div class="container-sm">
    <div class="row">
    <div class="modal-content">
    <form action="{{ route('registrarCliente') }}" method="POST" class="register-form">
        @csrf
        <h2>Registrarse</h2>
        <div class="mb-3">
            <label for="register-nombrec" class="form-label">Nombre de usuario:</label>
            <input type="text" id="register-nombrec" class="form-control" name="nombrec" value="{{ old('nombrec') }}" required>
            @error('nombrec')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-email" class="form-label">Correo electrónico:</label>
            <input type="email" id="register-email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-documentacion" class="form-label">RUT (Escribir sin puntos ni guion):</label>
            <input type="text" id="register-documentacion" class="form-control" name="documentacion" value="{{ old('documentacion') }}" required>
            @error('documentacion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-password" class="form-label">Contraseña:</label>
            <input type="password" id="register-password" class="form-control" name="password" required>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-password2" class="form-label">Repetir contraseña:</label>
            <input type="password" id="register-password2" class="form-control" name="password_confirmation" required>
            @error('password_confirmation')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-country">País:</label>
            <select class="form-select" id="register-country" name="pais" required>
                <option value="" disabled selected>Seleccione su país</option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais->id }}" {{ old('pais') == $pais->id ? 'selected' : '' }}>{{ $pais->nombrepa }}</option>
                @endforeach
            </select>

            @error('pais')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-region" class="form-label">Región/Estado:</label>
            <select id="register-region" class="form-select" name="region" required>
                <option value="" disabled selected>Seleccione su región</option>
            </select>
            @error('region')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-ciudad" class="form-label">Ciudad/Localidad:</label>
            <select id="register-ciudad" class="form-select" name="ciudad" required>
                <option value="" disabled selected>Seleccione su ciudad/localidad</option>
            </select>
            @error('ciudad')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-direccion" class="form-label">Dirección:</label>
            <input type="text" id="register-direccion" class="form-control" name="direccion" value="{{ old('direccion') }}" required>
            @error('direccion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-numero" class="form-label">Número:</label>
            <input type="text" id="register-numero" class="form-control" name="numero" value="{{ old('numero') }}" required>
            @error('numero')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-block" class="form-label">Block (Opcional):</label>
            <input type="text" id="register-block" class="form-control" name="block" value="{{ old('block') }}">
            @error('block')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-telefono" class="form-label">Teléfono Movil:</label>
            <input type="text" id="register-telefono" class="form-control" name="telefono" value="{{ old('telefono') }}" required>
            @error('telefono')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="register-telefonof" class="form-label">Teléfono de respaldo(Opcional):</label>
            <input type="text" id="register-telefonof" class="form-control" name="telefonof" value="{{ old('telefonof') }}">
            @error('telefonof')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg mb-3">Registrarse</button>
    </form>
</div>
    </div>
</div>


<!-- Definir la URL de la ruta como un atributo de datos -->
<div id="ruta-tomar-regiones" data-url="{{ route('tomarRegiones', ['id' => ':id']) }}"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/registrar.js') }}"></script>

@include('Tienda/footer')
