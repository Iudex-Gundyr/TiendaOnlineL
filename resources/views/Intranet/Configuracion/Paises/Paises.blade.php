<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Paises</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.location.href='{{ url('/dashboard') }}'" class="btn btn-secondary">Volver</button>
            <h2>Agregar nuevo pais.</h2>
            <form action="{{ route('crearPais') }}" method="POST">
                @csrf
            
                <label for="username1">Nombre del país:</label>
                <input id="username1" name="nombrepa" type="text" value="{{ old('nombrepa') }}" required>
                @error('nombrepa')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            
                <input type="submit" value="Crear Pais">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Configuracion/Paises/tablaPaises')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarPais/" + ID;
    }
}
</script>