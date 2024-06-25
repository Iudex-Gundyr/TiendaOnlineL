<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Materiales</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Modificar el material {{$material->nombrem}}. </h2>
            <form action="{{ route('updateMaterial', $material->id) }}" method="POST">
                @csrf
            
                <label for="username1">Nombre del material:</label>
                <input id="username1" name="nombrem" type="text" value="{{ $material->nombrem }}" required>
                @error('nombrem')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Materiales/Cantidad/Cantidad')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarMateriales/" + ID;
    }
}
</script>