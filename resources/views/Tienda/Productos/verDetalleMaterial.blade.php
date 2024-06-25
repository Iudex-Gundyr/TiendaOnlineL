
@include('Tienda/Tienda')
<div class="container-Oferta">

    <div class="oferta-card">
        @if($fotos->isEmpty())
            <p>No hay fotos disponibles</p>
        @else
            <div class="image2-container">
                @foreach ($fotos as $foto)
                    <div class="image2-detalle">
                        <img src="data:image/jpeg;base64,{{ base64_encode($foto->fotografia) }}" alt="Foto" class="clickable-image">
                    </div>
                @endforeach

            </div>
        @endif
        <div class="material-container">
            <div>
                <div><strong>Nombre:</strong> {{ $material->nombrem }}</div>
                <div><strong>Valor:</strong> ${{ number_format($material->valorm, 0, ',', '.') }} CLP</div>
            </div>
            <div>
                <div><strong>Disponibles:</strong> {{ $material->cantidad_restante }}</div>
                <div><strong>Código de barras:</strong> {{ $material->codigob }}</div>
            </div>
        </div>
        <div>
            <div><strong>Descripción:</strong></div>
            @if ($descripciones->isEmpty())
                <p>No hay descripción disponible</p>
            @else
                <div class="descripcion">
                    <ul>
                        @foreach ($descripciones as $descripcion)
                            <li>{{ $descripcion->nombredes }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/verdetalle.js') }}"></script>
        <select name="cantidad" id="cantidad{{$material->id}}">
            @for ($i = 1; $i <= $material->cantidad_restante; $i++)
                <option value="{{ $i }}">{{ $i }} unidad/es</option>
            @endfor
        </select>
        
        <button id="agregar-carrito-{{ $material->id }}" class="agregar-carrito" data-id="{{ $material->id }}">Agregar al carrito</button>
    </div>
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

</div>
