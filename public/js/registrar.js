$(document).ready(function() {
    // Manejar la selección de país y cargar las regiones correspondientes
    $('#register-country').change(function() {
        var paisId = $(this).val();
        $('#register-region').html('<option value="" disabled selected>Cargando...</option>');
        $('#register-ciudad').html('<option value="" disabled selected>Seleccione su ciudad/localidad</option>');

        $.ajax({
            url: '/tomarRegiones/' + paisId, // Ruta con el ID del país
            type: 'GET',
            success: function(data) {
                var options = '<option value="" disabled selected>Seleccione su región</option>';
                $.each(data, function(key, region) {
                    options += '<option value="'+ region.id +'">'+ region.nombrere +'</option>';
                });
                $('#register-region').html(options);
                if(oldRegion) {
                    $('#register-region').val(oldRegion).trigger('change');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
            }
        });
    });

    // Manejar la selección de región y cargar las ciudades correspondientes
    $('#register-region').change(function() {
        var regionId = $(this).val();
        $('#register-ciudad').html('<option value="" disabled selected>Cargando...</option>');

        $.ajax({
            url: '/tomarCiudades/' + regionId, // Ruta con el ID de la región
            type: 'GET',
            success: function(data) {
                var options = '<option value="" disabled selected>Seleccione su ciudad/localidad</option>';
                $.each(data, function(key, ciudad) {
                    options += '<option value="'+ ciudad.id +'">'+ ciudad.nombreci +'</option>';
                });
                $('#register-ciudad').html(options);
                if(oldCiudad) {
                    $('#register-ciudad').val(oldCiudad);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
            }
        });
    });

    // Inicializar valores antiguos si existen
    const oldRegion = "{{ old('region') }}";
    const oldCiudad = "{{ old('ciudad') }}";
    if (oldRegion) {
        $('#register-country').trigger('change');
    }
});

// Función para formatear RUT
document.addEventListener("DOMContentLoaded", function() {
    var rutInput = document.getElementById('register-documentacion');

    rutInput.addEventListener('input', function() {
        var value = rutInput.value.replace(/\./g, '').replace('-', '');

        if (value.length > 1) {
            var body = value.slice(0, -1);
            var dv = value.slice(-1).toUpperCase();

            rutInput.value = body.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + dv;
        }
    });
});
