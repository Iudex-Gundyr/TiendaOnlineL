
    $(document).ready(function() {
        $('#marca').change(function() {
            var marcaId = $(this).val();
            if (marcaId) {
                $.ajax({
                    url: '/tomarCategorias/' + marcaId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#categoria').empty();
                        $('#categoria').append('<option value="" disabled selected>Seleccione una categoría</option>');
                        $.each(data, function(key, value) {
                            $('#categoria').append('<option value="' + value.id + '">' + value.nombrecat + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar categorías:', error);
                    }
                });
            } else {
                $('#categoria').empty();
                $('#categoria').append('<option value="" disabled selected>Seleccione una categoría</option>');
                $('#categoria').append('<option value="" disabled selected>Seleccione una categoría</option>');
            }
        });
    });


    $(document).ready(function() {
        $('#categoria').change(function() {
            var categoriaId = $(this).val();
            var marcaId = $('#marca').val();
            
            if (categoriaId && marcaId) {
                $.ajax({
                    url: '/materialesPorCategoriaMarca/' + categoriaId + '/' + marcaId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data); // Verifica qué datos recibes aquí
                        
                        $('#material').empty();
                        $('#material').append('<option value="" disabled selected>Seleccione un material</option>');
                        
                        $.each(data, function(key, value) {
                            console.log(value); // Verifica qué contiene cada valor (id, nombremat)
                            $('#material').append('<option value="' + value.id + '">' + ' Nombre: ' + value.nombrem + ' Codigo de barra: ' + value.codigob + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Muestra el mensaje de error si hay algún problema
                    }
                });
            } else {
                $('#material').empty();
                $('#material').append('<option value="" disabled selected>Seleccione un material</option>');
            }
        });
    });


$(document).ready(function() {
    $('#material').change(function() {
        var materialId = $(this).val();

        if (materialId) {
            $.ajax({
                url: '/materiales/' + materialId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Datos recibidos:', data); // Verifica qué datos recibes aquí

                    if (data && data.nombrem) {
                        $('#informacion').html(
                            'Nombre: ' + data.nombrem + '<br>' +
                            'Código de barra: ' + data.codigob + '<br>' +
                            'Cantidad actual: ' + data.cantidad_restante
                        );
                        $('#cantidad').attr('max', data.cantidad_restante);
                    } else {
                        console.log('Nombre del material no disponible en los datos recibidos.');
                        $('#informacion').text('Información no disponible');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud:', xhr.responseText); // Muestra el mensaje de error si hay algún problema
                    $('#informacion').text('Error al cargar la información');
                }
            });
        } else {
            $('#informacion').text('Seleccione un material');
        }
    });
});


