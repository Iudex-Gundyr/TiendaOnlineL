@include('Tienda/Tienda')
<div class="container">

    <div class="card mt-2 mb-4 shadow-sm">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 bg-pr">
                        <div id="carouselExample" class="carousel slide">
                            <div class="carousel-inner">
                                @if ($fotos->isEmpty())
                                    <div class="carousel-item active">
                                        <p class="d-block w-100 px-2">No hay fotos disponibles</p>
                                    </div>
                                @else
                                    @foreach ($fotos as $index => $foto)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="data:image/jpeg;base64,{{ base64_encode($foto->fotografia) }}"
                                                class="d-block w-100  img-product object-fit-contain zoom "
                                                alt="Foto">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <!---FIN COL -->
                    <div class="col col-lg-6">
                        <div class="mt-2">

                            <div class="card-title"><strong>Nombre:</strong> {{ $material->nombrem }}</div>
                            <div class="card-text"><strong>Valor:</strong>
                                ${{ number_format($material->valorm, 0, ',', '.') }} CLP
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="card-text"><strong>Disponibles:</strong> {{ $material->cantidad_restante }}
                            </div>
                            <div class="card-text"><strong>Código de barras:</strong> {{ $material->codigob }}</div>
                        </div>

                        <div class="mt-2">
                            <div class="card-text"><strong>Descripción:</strong></div>
                            @if ($descripciones->isEmpty())
                                <p>No hay descripción disponible</p>
                            @else
                                <div class="card-text">
                                    <ul>
                                        @foreach ($descripciones as $descripcion)
                                            <li>{{ $descripcion->nombredes }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="mt-2">
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="{{ asset('js/verdetalle.js') }}"></script>
                            <select name="cantidad" id="cantidad{{ $material->id }}" class="form-select">
                                @for ($i = 1; $i <= min(50, $material->cantidad_restante); $i++)
                                    <option value="{{ $i }}">{{ $i }} unidad/es</option>
                                @endfor
                            </select>
                            

                            <div class="mx-2 mt-2 d-grid gap-2 mx-auto mt-2 mb-2">
                                <button id="agregar-carrito-{{ $material->id }}"
                                    class="agregar-carrito btn btn-primary btn-lg"
                                    data-id="{{ $material->id }}">Agregar
                                    al carrito</button>
                            </div>
                        </div>

                    </div>
                    <!---FIN COL -->
                </div>
                <!---FIN ROW  -->
            </div>
            <!---FIN CONTAINER hijo-->
        </div>
        <!---FIN CARD-BODY -->


    </div>
    <!-- <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div> -->

</div>
@include('Tienda/footer')
