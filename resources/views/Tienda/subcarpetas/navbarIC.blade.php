
    <nav class="navbar navbar-expand-lg sticky-top bg-primary" >
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo de la empresa" style="width:100px" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav fs-5">
                    <li class="nav-item p-3"><a href="/productos" class="nav-link text-white"><i class="bi bi-camera-video"></i>Productos</a></li>
                    <li class="nav-item p-3"><a href="/verCarrito" class="nav-link text-white" id="carritoBtn">Carrito</a>
                    </li>
                    <li class="nav-item p-3"><a href="/misDatos" class="nav-link text-white"></i> Mis datos</a></li>
                    <li class="nav-item p-3"><a href="/MisCompras" class="nav-link text-white"><i
                                class="fas fa-shopping-bag"></i> Mis
                            compras</a></li>
                    <li class="nav-item p-3"><a href="/cerrarSesion" class="nav-link text-white"><i
                                class="fas fa-sign-out-alt"></i>
                            Cerrar sesión</a></li>

                </ul>
              
            </div>
        </div>
    </nav>


{{-- 
    <div class="content">

    </div>


</body>

</html> --}}