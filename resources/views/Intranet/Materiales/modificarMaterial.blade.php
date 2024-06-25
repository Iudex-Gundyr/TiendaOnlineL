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
                <label for="username1">Valor del material:</label>
                <input id="username1" name="valorm" type="number" value="{{ $material->valorm }}" required>
                @error('valorm')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                <label for="username1">Codigo de barra:</label>
                <input id="username1" name="codigob" type="number" value="{{ $material->codigob }}"  required>
                @error('codigob')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                <label for="username1">Categoria del material:</label>
                @if($categorias->isEmpty())
                <p>No existen categorías (Crea una categoría en la sección categorías materiales)</p>
                @else
                    <select name="categoria" required>
                        <option value="{{$material->categorias->id}}" selected>{{$material->categorias->nombrecat}} (Seleccionado actualmente)</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombrecat }}</option>
                        @endforeach
                    </select>
                @endif
                
                @error('categoria')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
                <label for="username1">Marca del material:</label>
                @if($marcas->isEmpty())
                    <p>No existen categorías (Crea una nueva marca en la sección marcas de materiales)</p>
                @else
                    <select name="marca" required>
                        <option value="{{$material->marcas->id}}" selected>{{$material->marcas->nombremar}} (Seleccionado actualmente)</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombremar }}</option>
                        @endforeach
                    </select>
                @endif                        
                <input type="submit" value="Modificar Material">
                <p>Este material fue actualizado el: {{ \Carbon\Carbon::parse($material->fechaupd)->format('d/m/Y') }} por el usuario: {{ $material->nombre_usuario_actualizador }}</p>
                <p>Este material fue creado el: {{ \Carbon\Carbon::parse($material->fechac)->format('d/m/Y') }} por el usuario: {{ $material->nombre_usuario_creador }}</p>
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            @include('Intranet/Materiales/tablaMateriales')
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