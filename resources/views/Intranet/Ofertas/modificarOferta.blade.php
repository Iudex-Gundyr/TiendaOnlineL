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
            <button onclick="window.location.href='{{ url('/dashboard') }}'" class="btn btn-secondary">Volver</button>
            <h2>modificando la Oferta {{$oferta->nombreof}}.</h2>
            <form action="{{ route('updateOferta', $oferta->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <label for="username1">Nombre de la oferta:</label>
                <input id="username1" name="nombreof" type="text" value="{{ $oferta->nombreof }}" required>
                @error('nombreof')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                
                <label for="fotografia">Fotografía de la oferta (No agregar una foto si no quiere reemplazar la actual)(JPG o PNG):</label>
                <input type="file" id="fotografia" name="fotografia" accept="image/png, image/jpeg">
                @error('fotografia')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                
                <label for="porcentajeof">Porcentaje de la oferta</label>
                <input id="porcentajeof" name="porcentajeof" type="number" value="{{ $oferta->porcentajeof }}" min="0" max="100" required>
                @error('porcentajeof')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            
                <label for="fechaexp">Fecha de expiración de la oferta</label>
                <input id="fechaexp" name="fechaexp" type="date" value="{{ \Carbon\Carbon::parse($oferta->fechaexp)->format('Y-m-d') }}" required>
                @error('fechaexp')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                
                <input type="submit" value="Modificar oferta">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Ofertas/tablaOfertas')
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarOferta/" + ID;
    }
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('fechaexp').setAttribute('min', today);
    });
</script>