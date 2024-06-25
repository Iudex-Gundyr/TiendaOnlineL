@include('Tienda/Tienda')

<form action="{{ route('filtrarProductos') }}" method="POST" class="custom-form">
    @csrf
    <input type="text" name="nombreProducto" placeholder="Ingrese su texto">
    <div class="select-container">
        <select name="categoria" id="categoria">
            <option value="">Seleccione una categoría</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombrecat }}</option>
            @endforeach
        </select>
    </div>
    <div class="select-container">
        <select name="marca" id="marca">
            <option value="">Seleccione una marca</option>
            @foreach($marcas as $marca)
                <option value="{{ $marca->id }}">{{ $marca->nombremar }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit">Filtrar</button>
    <a href="/productos">Borrar Filtro</a>
</form>


<div class="container-Oferta">
    @foreach ($ofertas as $oferta) 
        <div class="oferta-card">
            {{-- Imagen del producto --}}
            @if ($oferta->fotografia)
                <div class="image-container">
                    <img src="data:image/jpeg;base64,{{ base64_encode($oferta->fotografia) }}" alt="Foto">
                </div>
            @else
                <p>No hay foto disponible</p>
            @endif  
            {{-- Detalles del producto --}}
            <div class="product-details">
                <h2>{{ $oferta->nombreof }} valido hasta {{ \Carbon\Carbon::parse($oferta->fechaexp)->format('d M Y') }}</h2> {{-- Nombre del material --}}
                <a href="{{ route('verDetalle', $oferta->id) }}">Ver detalle</a>
            </div>
        </div>
    @endforeach   
</div>

 
<div class="container">
    @if ($materiales->isEmpty())
        <p>No existen productos con tus preferencias</p>
    @else
        @foreach ($materiales as $material) 
            <div class="product-card">
                {{-- Imagen del producto --}}
                @if ($material->fotos)
                    <img src="data:image/jpeg;base64,{{ base64_encode($material->fotos) }}" alt="Foto">
                @else
                    <p>No hay fotos disponibles</p>
                @endif  
                {{-- Detalles del producto --}}
                <div class="product-details">
                    <h2>{{ $material->nombrem }}</h2> {{-- Nombre del material --}}
                    <p class="price">${{ number_format($material->valorm) }} CLP</p> {{-- Valor del material --}}
                    @if ($material->descripciones)
                        <p>{{ $material->descripciones }}</p>
                    @else
                        <p>No hay descripciones disponibles</p>
                    @endif
                    <p>Cantidad restante: {{ $material->cantidad_restante }}</p> {{-- Cantidad restante --}}
                    <select name="cantidad" id="cantidad{{$material->id}}">
                        @for ($i = 1; $i <= $material->cantidad_restante; $i++)
                            <option value="{{ $i }}">{{ $i }} unidad/es</option>
                        @endfor
                    </select>
                    
                    <button id="agregar-carrito-{{ $material->id }}" class="agregar-carrito" data-id="{{ $material->id }}">Agregar al carrito</button>
                    <a href="{{ route('verDetalleMaterial', $material->id) }}">ver detalle</a>
                </div>
            </div>
        @endforeach
    @endif
        <div>
            @if ($n > 1)
                <a href="{{ route('productos', ['n' => $n - 1]) }}" class="btn btn-primary">Anterior</a>
            @endif
        
            Página actual: {{ $n }}
        
            @if ($n * 12 < $totalProductos)
                <a href="{{ route('productos', ['n' => $n + 1]) }}" class="btn btn-primary">Siguiente</a>
            @endif
        </div>
        
        <div>
            <!-- Aquí va tu código para mostrar los productos -->
            @foreach ($materiales as $material)
                <!-- Aquí la estructura HTML para mostrar cada producto -->
            @endforeach
        </div>
</div>