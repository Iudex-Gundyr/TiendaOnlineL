<h2>Usuarios creados.</h2>
<form action="{{ route('usuariosfiltrar') }}">
    <label for="filtroNombre">Filtrar por Nombre de Usuario (Dejar vacio para mostrar todos):</label>
    <input type="text" id="filtroNombre" name="nombreus">
    <input type="submit" value="Filtrar">
</form>
<table>
    <thead>
        <tr>
            <th>Nombre de usuario</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
    @if ($usuarios->isEmpty())
        <tr>
            <td colspan="2">No existen usuarios con estas características.</td>
        </tr>
    @else
        @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->nombreus }}</td>
                <td>
                    <a href="{{ route('modificarUsuario', ['id' => $usuario->id]) }}" class="action-link">Editar</a>
                    <!-- Enlace para eliminar -->
                    <a href="#" class="action-link-red" onclick="confirmEliminarUsuario({{ $usuario->id }})">Eliminar</a>
                    <a href="{{route('privilegios', ['id' => $usuario->id])}}" class="action-link-green">Privilegios</a>
                </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->

    </tbody>
</table>