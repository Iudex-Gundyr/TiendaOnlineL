


document.addEventListener('DOMContentLoaded', function() {
    const botonCarrito = document.getElementById('carritoBtn');
    if (botonCarrito) {
        botonCarrito.addEventListener('click', function() {
            console.log('Carrito botón clicado');
        });
    } else {
        console.error('Elemento con ID "carritoBtn" no encontrado.');
    }
});



document.addEventListener('DOMContentLoaded', function() {
    const carritoBtn = document.getElementById('carritoBtn');

    if (carritoBtn) {
        carritoBtn.addEventListener('click', function() {
            console.log('Carrito botón clicado');
        });
    } else {
        console.error('Elemento con ID "carritoBtn" no encontrado.');
    }

    function updateCarritoCount() {
        $.ajax({
            url: carritoCountUrl, // Asegúrate de definir esta variable en tu Blade template o en tu JS
            method: "GET",
            success: function(response) {
                $('#contador').text(response.total_count);
            },
            error: function() {
                console.error('Error al obtener el conteo del carrito.');
            }
        });
    }

    // Llama a la función al cargar la página
    updateCarritoCount();

    // Actualiza el contador cada 30 segundos (30000 ms)
    setInterval(updateCarritoCount, 850);
})
