{{-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis datos</title>
    <!-- Asegurar que el token CSRF esté presente -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body> --}}

@include('Tienda/Tienda')

<div class="container mt-3 mb-3 shadow-sm " style="max-width:1000px">
    <div class="row px-3 pt-4 pb-4">
        <div class="modal-content">
            <form action="{{ route('actualizarCliente') }}" method="POST" class="register-form">
                @csrf
                <h2>Tus datos actuales</h2>
                <div class="mb-3">
                    <label class="form-label" for="register-nombrec">Nombre de usuario:</label>
                    <input type="text" id="register-nombrec" name="nombrec" class="form-control"
                        value="{{ $datos->nombrec }}" required>
                    @error('nombrec')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-email">Correo electrónico (No se puede cambiar):</label>
                    <label class="form-label" for="register-email">{{ $datos->correo }}</label>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-documentacion">RUT (Escribir sin puntos ni guion):</label>
                    <input type="text" id="register-documentacion" class="form-control" name="documentacion"
                        value="{{ $datos->documentacion }}" required>
                    @error('documentacion')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-password">Nueva Contraseña:</label>
                    <input type="password" id="register-password" class="form-control" name="password">
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-password2">Repetir Nueva contraseña:</label>
                    <input type="password" id="register-password2" class="form-control" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="register-country">País:</label>
                    <select class="form-select" id="register-country" name="pais">
                        <option value="{{ $datos->pais->id }}" selected>{{ $datos->pais->nombrepa }}</option>
                        @foreach ($paises as $pais)
                            <option value="{{ $pais->id }}" {{ old('pais') == $pais->id ? 'selected' : '' }}>
                                {{ $pais->nombrepa }}</option>
                        @endforeach
                    </select>
                    @error('pais')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-region">Región/Estado:</label>
                    <select class="form-select" id="register-region" name="region">
                        <option value="{{ $datos->region->id }}" select class="form-select" ed>
                            {{ $datos->region->nombrere }}</option>
                        @foreach ($regiones as $region)
                            <option value="{{ $region->id }}"
                                {{ old('region') == $region->id ? 'select class="form-select" ed' : '' }}>
                                {{ $region->nombrere }}</option>
                        @endforeach
                    </select class="form-select">
                    @error('region')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">

                    <label class="form-label" for="register-ciudad">Ciudad/Localidad (Actual:
                        {{ $datos->ciudad->nombreci }} )</label>
                    <select class="form-select" id="register-ciudad" name="ciudad">
                        <option value="{{ $datos->ciudad->id }}" select class="form-select" ed>
                            {{ $datos->ciudad->nombreci }}</option>
                        @foreach ($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}"
                                {{ old('ciudad') == $ciudad->id ? 'select class="form-select" ed' : '' }}>
                                {{ $ciudad->nombreci }}</option>
                        @endforeach

                    </select class="form-select">
                    <label class="form-label" for="register-ciudad">No editar si no quiere cambiar la ciudad</label>
                    @error('ciudad')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-direccion">Dirección:</label>
                    <input type="text" id="register-direccion" class="form-control" name="direccion"
                        value="{{ $datos->direccion }}" required>
                    @error('direccion')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-numero">Número:</label>
                    <input type="text" id="register-numero" class="form-control" name="numero"
                        value="{{ $datos->numerod }}" required>
                    @error('numero')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-block">Block (Opcional):</label>
                    <input type="text" id="register-block" class="form-control" name="block"
                        value="{{ $datos->blockd }}">
                    @error('block')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-telefono">Teléfono Movil:</label>
                    <input type="text" id="register-telefono" class="form-control" name="telefono"
                        value="{{ $datos->telefono }}" required>
                    @error('telefono')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="register-telefonof">Teléfono de respaldo(Opcional):</label>
                    <input type="text" id="register-telefonof" class="form-control" name="telefonof"
                        value="{{ $datos->telefonof }}">
                    @error('telefonof')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
            </form>
        </div>
    </div>
</div>


<!-- Definir la URL de la ruta como un atributo de datos -->
<div id="ruta-tomar-regiones" data-url="{{ route('tomarRegiones', ['id' => ':id']) }}"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/registrar.js') }}"></script>

@include('Tienda/footer')
