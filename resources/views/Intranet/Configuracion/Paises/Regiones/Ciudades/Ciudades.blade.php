<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Ciudades</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Agregar nueva localidad.</h2>
            <form action="{{ route('crearCiudad', ['id' => $id]) }}" method="POST">
                @csrf
            
                <label for="username1">Nombre de la ciudad/localidad:</label>
                <input id="username1" name="nombreci" type="text" value="{{ old('nombreci') }}" required>
                @error('nombreci')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            
                <input type="submit" value="Crear ciudad/localidad">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Configuracion/Paises/Regiones/Ciudades/tablaCiudades')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarCiudad/" + ID;
    }
}
</script>