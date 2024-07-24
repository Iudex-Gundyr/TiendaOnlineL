<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Oferta</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.location.href='{{ url('/Ofertas') }}'" class="btn btn-secondary">Volver</button>
            <h2>Añadiendo material a una oferta.</h2>
            <form action="{{ route('crearMatOf', $oferta->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
            
                @include('Intranet/Ofertas/MaterialOferta/FuncionMarcaxCat/MarcasCM')
    

            
                <input type="submit" value="Crear oferta">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Ofertas/MaterialOferta/tablamatOferta')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarmatOferta/" + ID;
    }
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('fechaexp').setAttribute('min', today);
    });
</script>