<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Marcas</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Crear Nueva Marca Para Los Materiales</h2>
            <form action="{{ route('CrearMarca') }}" method="POST">
                @csrf
            
                <label for="username1">Nombre de la marca:</label>
                <input id="username1" name="nombremar" type="text" value="{{ old('nombremar') }}" required>
                @error('nombremar')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
        
            
                <input type="submit" value="Crear Marca">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Configuracion/Marcas/tablaMarcas')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarMarca/" + ID;
    }
}
</script>