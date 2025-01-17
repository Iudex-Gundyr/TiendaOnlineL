<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Categorias</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Crear Nueva Categoria.</h2>
            <form action="{{ route('CrearCategoria') }}" method="POST">
                @csrf
            
                <label for="username1">Nombre de la categoria:</label>
                <input id="username1" name="nombrecat" type="text" value="{{ old('nombrecat') }}" required>
                @error('nombrecat')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror          
                <input type="submit" value="Crear categoria">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Configuracion/Categorias/tablaCategorias')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarCategoria/" + ID;
    }
}
</script>