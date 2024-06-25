<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Usuarios</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Modificando al usuario. {{$usuario->nombreus}}</h2>
            <form action="{{ route('updateUsuario', $usuario->id) }}" method="POST">
                @csrf
            
                <label for="username1">Nombre de usuario:</label>
                <input id="username1" name="nombreus" type="text" value="{{ old('nombreus', $usuario->nombreus) }}" required>
                @error('nombreus')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            
                <label for="password1">Clave del usuario (Dejar vacío si no quiere cambiar):</label>
                <input id="password1" name="password" type="password">
                @error('password')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            
                <input type="submit" value="Modificar usuario {{ $usuario->NOMBREUS }}">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/usuarios/tablaUsuarios')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminarUsuario(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        window.location.href = "/eliminarUsuario/" + ID;
    }
}
</script>