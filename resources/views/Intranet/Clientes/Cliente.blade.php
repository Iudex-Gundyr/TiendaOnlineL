<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Clientes</title>
</head>
<body>

    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">
        <div class="divider"></div>
        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            
            @include('Intranet/Clientes/tablaClientes')
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