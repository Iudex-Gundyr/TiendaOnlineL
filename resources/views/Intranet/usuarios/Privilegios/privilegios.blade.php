<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">
    <script src="{{ asset('js/confirmarEliminar.js') }}"></script>
    <title>Usuarios</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">

        <div class="box">
            <button onclick="window.history.back()" class="btn btn-secondary">Volver</button>
            <h2>Agregar nuevo privilegio al usuario {{$usuario->nombreus}}.</h2>
            <form action="{{ route('agregarPrivilegio', $usuario->id) }}" method="POST">
                @csrf
                <select name="privilegio" required>
                    @foreach($privilegios as $privilegio)
                        <option value="{{ $privilegio->id }}">{{ $privilegio->nombrepri }}</option>
                    @endforeach
                </select>
                @error('privilegio')
                    <p class="text-danger" style="color: red">{{ $message }}</p>
                @enderror
            
            
                <input type="submit" value="Agregar privilegio">
            </form>
        </div>
        <div class="divider"></div>
        <div class="box">
            <h2>Privilegios del usuario {{$usuario->nombreus}}.</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre del privilegio</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($privilegioUsuario as $privilegioU)
                        <tr>
                            <th>{{$privilegioU->nombrepri}}</th>
                            <th><a href="#" class="action-link-red" onclick="confirmEliminar({{ $privilegioU->privilegio_usuario_id }})">Eliminar</a></th>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<script>
function confirmEliminar(ID) {
    if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
        window.location.href = "/eliminarPrivilegio/" + ID;
    }
}
</script>