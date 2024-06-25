document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('forgotPassword').addEventListener('click', function(event) {
        event.preventDefault();
        var correo = document.getElementById('correo').value;
        if (correo) {
            // Crear un formulario temporal
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = recuperarPassUrl;

            // Añadir token CSRF
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Añadir campo de correo
            var emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'correo';
            emailInput.value = correo;
            form.appendChild(emailInput);

            // Añadir el formulario temporal al cuerpo y enviarlo
            document.body.appendChild(form);
            form.submit();
        } else {
            alert('Por favor, ingrese su correo electrónico primero.');
        }
    });
});
