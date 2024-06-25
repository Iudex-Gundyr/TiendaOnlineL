document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los botones de agregar al carrito de materiales
    var botonesAgregarCarrito = document.querySelectorAll('.agregar-carrito');

    botonesAgregarCarrito.forEach(function(boton) {
        boton.addEventListener('click', function(event) {
            event.preventDefault();

            // Verificar si el usuario está autenticado usando la variable global
            if (!isLoggedIn) {
                alert('Debe iniciar sesión');
                return;
            }

            var materialId = boton.getAttribute('data-id');
            var selectCantidad = document.getElementById('cantidad' + materialId);
            var cantidad = selectCantidad.value;

            if (!cantidad || cantidad <= 0) {
                alert('Por favor, seleccione una cantidad válida.');
                return;
            }

            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(agregarCarritoUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    fk_id_material: materialId,
                    cantidad: cantidad
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });    
});


