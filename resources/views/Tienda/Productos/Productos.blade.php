@include('Tienda/Tienda')



<div class="container m-auto">
    <form action="{{ route('filtrarProductos') }}" method="POST" class="row">
        @csrf
        <div class="col-12 col-sm-3 mt-2">
            <input type="text" name="nombreProducto" class="form-control" placeholder="Ingrese su texto">
        </div>
        <div class="col-12 col-sm-3 mt-2">
            <select name="categoria" id="categoria" class="form-select">
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombrecat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-3 mt-2">
            <select name="marca" id="marca" class="form-select">
                <option value="">Seleccione una marca</option>
                @foreach($marcas as $marca)
                <option value="{{ $marca->id }}">{{ $marca->nombremar }}</option>
                @endforeach
            </select>
        </div>
        <div class="col col-sm-3 d-flex mt-2 ">
            <button type="submit" class="btn btn-primary mx-2">Filtrar</button>
            <a href="/productos" class="mx-2">Borrar Filtro</a>
        </div>
    </form>

</div>


<!-- oferta -->
<div class="container">
    <div class="row mb-2">

        @foreach ($ofertas as $oferta)
        <div class="mt-4 col-sm-12 col-lg-12">
            <div class="card" style="width: 100%;">
                <div class="header ">
                    {{-- Imagen del producto --}}
                    @if ($oferta->fotografia)
                    <a href="{{ route('verDetalle', $oferta->id) }}" class="btn btn-primary">
                        <img src="data:image/jpeg;base64,{{ base64_encode($oferta->fotografia) }}"
                            class="card-img-top img-product-banner object-fit-contain " alt="Foto"></a>


                    @else
                    <p class="px-2">No hay foto disponible</p>
                    @endif
                    {{-- Detalles del producto --}}
                </div>

                <div class="card-body">
                    <h2 class="card-text ">{{ $oferta->nombreof }} valido hasta
                        {{ \Carbon\Carbon::parse($oferta->fechaexp)->format('d M Y') }}</h2>
                    {{-- Nombre del material --}}

                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>



<div class="container">

    <div class="row mb-2">
        @if ($materiales->isEmpty())
        <p>No existen productos con tus preferencias</p>
        @else
        @foreach ($materiales as $material)
        <div class="mt-4  col col-lg-4 col-md-6 col-sm-6">
            <div class="card card-zoom m-auto h-100 shadow-sm" style="width: 20rem;">
                <div class="img-custom">
                    {{-- Imagen del producto --}}
                    @if ($material->fotos)
                    <a href="{{ route('verDetalleMaterial', $material->id) }}">
                        <img src="data:image/jpeg;base64,{{ base64_encode($material->fotos) }}"
                            class="card-img-top img-product object-fit-contain " alt="Foto">
                    </a>
                    @else
                    <p class="px-2">No hay fotos disponibles</p>
                    @endif
                </div>
                {{-- Detalles del producto --}}
                <div class="card-body">
                    <h2>{{ $material->nombrem }}</h2> {{-- Nombre del material --}}
                    <p class="card-title">${{ number_format($material->valorm) }} CLP</p> {{-- Valor del material --}}
                    {{-- @if ($material->descripciones)
                    <p class="card-text">{{ $material->descripciones }}</p>
                    @else
                    <p class="card-text">No hay descripciones disponibles</p>
                    @endif --}}
                    <p class="card-text">Cantidad restante: {{ $material->cantidad_restante }}</p>
                    {{-- Cantidad restante --}}
                    <select name="cantidad" class="form-select" id="cantidad{{$material->id}}">
                        @for ($i = 1; $i <= min(50, $material->cantidad_restante); $i++)
                            <option value="{{ $i }}">{{ $i }} unidad/es</option>
                        @endfor
                    </select>
                    <div class="mx-2 mt-2 d-grid gap-2 mx-auto">
                        <button id="agregar-carrito-{{ $material->id }}" class="btn btn-primary agregar-carrito"
                            data-id="{{ $material->id }}">Agregar al carrito</button>
                    </div>
                </div>
            </div>

        </div>
        @endforeach

        @endif
    </div>

</div>





<div class="container mt-3">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">

                @if ($n > 1)
                <a href="{{ route('productos', ['n' => $n - 1]) }}" class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span></a>
                @endif
            </li>
            <li class="page-item"><a class="page-link" href="#"> Página actual: {{ $n }}</a></li>

            <li class="page-item">

                @if ($n * 12 < $totalProductos) <a href="{{ route('productos', ['n' => $n + 1]) }}" class="page-link"
                    href="#" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
                    @endif

            </li>
        </ul>
    </nav>
</div>
@include('Tienda/footer')
