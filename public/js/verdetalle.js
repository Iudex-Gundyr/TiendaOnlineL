document.addEventListener("DOMContentLoaded", function() {
    // Obtener elementos
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("modalImage");
    var closeBtn = document.getElementsByClassName("close")[0];
    var images = document.getElementsByClassName("clickable-image");

    // Añadir evento click a cada imagen
    for (var i = 0; i < images.length; i++) {
        images[i].onclick = function() {
            modal.style.display = "flex";
            modalImg.src = this.src;
        }
    }

    // Cerrar modal al hacer click en el botón de cerrar
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Cerrar modal al hacer click fuera de la imagen
    modal.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
});