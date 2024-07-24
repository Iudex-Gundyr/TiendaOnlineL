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
            <button onclick="window.location.href='{{ url('/materiales') }}'" class="btn btn-secondary">Volver</button>
            @include('Intranet/Materiales/FotosDescripcion/Fotos/Fotos')
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Materiales/FotosDescripcion/Descripcion/Descripcion')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminarF(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarFoto/" + ID;
    }
}
function confirmEliminarD(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarDescripcion/" + ID;
    }
}
</script>