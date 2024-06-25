<h2>Agregar o quitar insumos del material {{$material->nombrem}}. </h2>
<form action="{{ route('agregarCantidad', $material->id) }}" method="POST" id="formulario-cantidad">
    @csrf
    <label for="accion">Acción:</label>
    <select id="accion" name="accion" required>
        <option value="" disabled selected>Seleccione alguna opción</option>
        <option value="agregar">Agregar Material</option>
        <option value="quitar">Quitar Material</option>
    </select>
    <br><br>
    @if ($material)
        <label for="cantidad">Cantidad (Solo números enteros): La cantidad actual es {{ $material->cantidad_restante }}</label>
        <input type="number" id="cantidad" name="cantidad" required>
    @endif
    <br><br>
    <input type="submit" value="Realizar acción">
</form>
<table>
    <thead>
        <tr>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
    @if ($cantidades->isEmpty())
        <tr>
            <td colspan="2">No existen cantidades.</td>
        </tr>
    @else
        @foreach ($cantidades as $cantidad)
            <tr>
                <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">@if($cantidad->cantidad >= 0)Agregaron: @else Quitaron: @endif {{ $cantidad->cantidad }} </td>
            </tr>
        @endforeach
    @endif
        <!-- Aquí se pueden añadir las filas dinámicamente -->
    </tbody>
</table>


<script>
    // Obtener referencia a los elementos del formulario
    const accionSelect = document.getElementById('accion');
    const cantidadInput = document.getElementById('cantidad');
    const cantidadRestante = {{ $material->cantidad_restante ?? 0 }}; // Valor de cantidad_restante desde PHP
    
    // Escuchar cambios en el select 'accion'
    accionSelect.addEventListener('change', function() {
        if (this.value === 'quitar') {
            // Si se selecciona 'Quitar Material', establecer el atributo 'max' en cantidadRestante
            cantidadInput.setAttribute('max', cantidadRestante);
        } else {
            // Si se selecciona 'Agregar Material', eliminar el atributo 'max' o establecerlo a un valor alto si se permite cualquier número
            cantidadInput.removeAttribute('max');
        }
    });
</script>