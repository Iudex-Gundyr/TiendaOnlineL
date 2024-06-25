<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Regiones</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Agregar nueva Region.</h2>
            <form action="{{ route('crearRegion', ['id' => $id]) }}" method="POST">
                @csrf
            
                <label for="username1">Nombre del la region:</label>
                <input id="username1" name="nombrere" type="text" value="{{ old('nombrere') }}" required>
                @error('nombrere')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                <input type="submit" value="Crear Region">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Configuracion/Paises/Regiones/tablaRegiones')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarRegion/" + ID;
    }
}


</script>