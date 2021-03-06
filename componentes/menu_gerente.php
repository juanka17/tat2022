<div class="row">
    <div class="col-sm-12 col-md-8 offset-md-2">
        <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="bienvenida.php">
                            <i class="fa fa-home"></i>
                            Inicio
                            <span class="sr-only">
                                (current)
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="almacenes.php">
                            <i class="fa fa-users"></i>
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="catalogo.php?id_afiliado={{datos_usuario.id}}">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            Catalogo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estado_cuenta.php?id_afiliado={{datos_usuario.id}}">
                            <i class="fa fa-address-card" aria-hidden="true"></i>
                            Estado de cuenta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?logout">
                            <i class="fa fa-sign-out"></i>
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>