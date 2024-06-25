@if($marcas->isEmpty())
    <p>No existen categorías (Crea una nueva marca en la sección marcas de materiales)</p>
@else
    <label for="marca">Marca</label>
    <select name="marca" id="marca" required>
        <option value="" disabled selected>Seleccione un elemento</option>
        @foreach($marcas as $marca)
            <option value="{{ $marca->id }}">{{ $marca->nombremar }}</option>
        @endforeach
    </select>

    <label for="categoria">Categoría</label>
    <select name="categoria" id="categoria" required>
        <option value="" disabled selected>Seleccione una categoría</option>
    </select>

    <label for="material">Material</label>
    <select name="material" id="material" required>
        <option value="" disabled selected>Seleccione un material</option>
    </select>
    <label for="informacion" id="informacion"></label>
    <label for="cantidad">Cantidad que desea ingresar a la oferta<br>(Esto restara autamitacente de los materiales actuales).</label>
    <input id="cantidad" name="cantidad" type="number" value="{{ old('cantidad') }}" min="0" max="0" required>
    @error('cantidad')
        <p class="text-danger" style="color: red">{{ $message }}</p>
    @enderror
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/scriptsOfertas.js') }}"></script>