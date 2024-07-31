@include('Tienda/Tienda')

<h1 class="text-center">{{ $noferta->nombreof }}</h1>
<div class="container">

    @if ($ofertas->isEmpty())
        <p>No quedan productos para esta oferta.</p>
    @else
        @foreach ($ofertas as $oferta) 
            <div class="card mt-3" >
                {{-- Imagen del producto --}}
                @if ($oferta->fotos)
                    <img src="data:image/jpeg;base64,{{ base64_encode($oferta->fotos) }}" class="card-img-top img-product object-fit-contain " alt="Foto">
                @else
                    <p>No hay fotos disponibles</p>
                @endif  
                {{-- Detalles del producto --}}
                <div class="card-body">
                    <h2>{{ $oferta->nombrem }}</h2> {{-- Nombre del oferta --}}
                    <p class="card-text">${{ number_format($oferta->valorm - ($oferta->valorm * $oferta->porcentajeof / 100)) }} CLP</p>
                    <p class="card-text" style="text-decoration: line-through; color: #888; font-size: 0.7em;">${{ number_format($oferta->valorm) }} CLP</p>

                    @if ($oferta->descripciones)
                        <p class="card-text">{{ $oferta->descripciones }}</p>
                    @else
                        <p class="card-text">No hay descripciones disponibles</p>
                    @endif
                    <p>Cantidad restante: {{ $oferta->cantidad_en_compra }}</p> {{-- Cantidad restante --}}
                    <select name="cantidad" id="cantidad{{ $oferta->id }}" class="form-select form-select-sm">
                        @for ($i = 1; $i <= min(50, $oferta->cantidad_en_compra); $i++)
                            <option value="{{ $i }}">{{ $i }} unidad/es</option>
                        @endfor
                    </select>                    
                    
                    <button id="agregar-carrito-oferta-{{ $oferta->id }}" class="agregar-carritoOferta btn btn-lg btn-primary mt-2" data-id="{{ $oferta->id }}">Agregar al carrito</button>
                    
                    
                    
                    <a href="{{ route('verDetalleMaterial', ['id' => $oferta->fk_id_material]) }}">ver detalle</a>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
$(document).ready(function() {
    $('.agregar-carritoOferta').on('click', function() {
        var ofertaId = $(this).data('id');
        var cantidad = $('#cantidad' + ofertaId).val();

        // Verificar si hay un usuario autenticado como cliente
        if (!checkClienteAutenticado()) {
            alert('Debes iniciar sesión como cliente para agregar productos al carrito');
            // Aquí podrías mostrar un mensaje de error al usuario o redirigir a la página de inicio de sesión
            return;
        }

        // Crear el objeto de datos que se enviará al servidor
        var data = {
            fk_id_Moferta: ofertaId,
            cantidad: cantidad
            // Puedes añadir más datos si es necesario
        };

        // Realizar la petición AJAX
        $.ajax({
            type: 'POST',
            url: '{{ route('agregarCarritoOferta') }}', // Ruta de Laravel definida en tus rutas
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agregar el token CSRF
            },
            data: data,
            success: function(response) {
                // Manejar la respuesta exitosa aquí
                console.log('Producto agregado al carrito correctamente');
                alert('Producto agregado al carrito correctamente'); // Mostrar mensaje de éxito
                // Por ejemplo, mostrar un mensaje de éxito o actualizar la UI
            },
            error: function(error) {
                // Manejar el error aquí
                console.error('Error al agregar producto al carrito', error);
                alert('Error al agregar producto al carrito. Por favor, inténtalo de nuevo más tarde.'); // Mostrar mensaje de error
                // Por ejemplo, mostrar un mensaje de error o revertir cambios en la UI
            }
        });
    });

    // Función para verificar si el cliente está autenticado
    function checkClienteAutenticado() {
        // Aquí puedes implementar la lógica para verificar si el cliente está autenticado
        // Por ejemplo, podrías verificar si existe una cookie de sesión o algún otro indicador
        // que indique que el cliente está autenticado
        return {{ Auth::guard('cliente')->check() ? 'true' : 'false' }};
    }
});

</script>
@include('Tienda/footer')