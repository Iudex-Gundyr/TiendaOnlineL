<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis datos</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('Tienda/Tienda')

<div class="modal-content">
    <form action="{{ route('actualizarCliente') }}" method="POST" class="register-form">
        @csrf
        <h2>Tus datos actuales</h2>
        <div class="input-group">
            <label for="register-nombrec">Nombre de usuario:</label>
            <input type="text" id="register-nombrec" name="nombrec" value="{{ $datos->nombrec }}" required>
            @error('nombrec')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-email">Correo electrónico (No se puede cambiar):</label>
            <label for="register-email">{{ $datos->correo }}</label>
        </div>
        <div class="input-group">
            <label for="register-documentacion">RUT (Escribir sin puntos ni guion):</label>
            <input type="text" id="register-documentacion" name="documentacion" value="{{ $datos->documentacion }}" required>
            @error('documentacion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-password">Nueva Contraseña:</label>
            <input type="password" id="register-password" name="password">
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-password2">Repetir Nueva contraseña:</label>
            <input type="password" id="register-password2" name="password_confirmation">
            @error('password_confirmation')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-country">País:</label>
            <select id="register-country" name="pais">
                <option value="{{ $datos->pais->id }}" selected>{{ $datos->pais->nombrepa }}</option>
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
            <select id="register-region" name="region">
                <option value="{{ $datos->region->id }}" selected>{{ $datos->region->nombrere }}</option>
                @foreach ($regiones as $region)
                    <option value="{{ $region->id }}" {{ old('region') == $region->id ? 'selected' : '' }}>{{ $region->nombrere }}</option>
                @endforeach
            </select>
            @error('region')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">

            <label for="register-ciudad">Ciudad/Localidad (Actual: {{$datos->ciudad->nombreci}} )</label>
            <select id="register-ciudad" name="ciudad" >
                <option value="{{ $datos->ciudad->id }}" selected>{{ $datos->ciudad->nombreci }}</option>
                @foreach ($ciudades as $ciudad)
                    <option value="{{ $ciudad->id }}" {{ old('ciudad') == $ciudad->id ? 'selected' : '' }}>{{ $ciudad->nombreci }}</option>
                @endforeach

            </select>
            <label for="register-ciudad">No editar si no quiere cambiar la ciudad</label>
            @error('ciudad')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-direccion">Dirección:</label>
            <input type="text" id="register-direccion" name="direccion" value="{{ $datos->direccion }}" required>
            @error('direccion')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-numero">Número:</label>
            <input type="text" id="register-numero" name="numero" value="{{ $datos->numerod }}" required>
            @error('numero')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-block">Block (Opcional):</label>
            <input type="text" id="register-block" name="block" value="{{ $datos->blockd }}">
            @error('block')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-telefono">Teléfono:</label>
            <input type="text" id="register-telefono" name="telefono" value="{{ $datos->telefono }}" required>
            @error('telefono')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group">
            <label for="register-telefonof">Teléfonof (Opcional):</label>
            <input type="text" id="register-telefonof" name="telefonof" value="{{ $datos->telefonof }}">
            @error('telefonof')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn">Actualizar</button>
    </form>
</div>

<!-- Definir la URL de la ruta como un atributo de datos -->
<div id="ruta-tomar-regiones" data-url="{{ route('tomarRegiones', ['id' => ':id']) }}"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/registrar.js') }}"></script>

</body>
</html>
