<div class="row">
    <div class="col-sm-12 offset-md-1 col-md-10 ">
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
                        <a class="nav-link" href="mis_datos_vendedor.php?id_usuario={{datos_usuario.id}}">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            Mis Datos
                        </a>
                    </li>
                    <?php
                    if ($_SESSION["usuario"]["id_clasificacion"] == 2) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i>
                            Admin
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="menu_administracion.php">
                                <i class="fa fa-user"></i> Admin
                            </a>
                            <a class="dropdown-item" href="autorizacion_entregas.php">
                                <i class="fa fa-file-excel-o"></i>
                                Autorizar Entregas
                            </a>
                            <a class="dropdown-item" href="listado_usuarios.php">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                Buscar Usuario
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="almacenes.php">
                            <i class="fa fa-users"></i>
                            Clientes
                        </a>
                    </li>

                    <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="reportes.php">
                            <i class="fa fa-file-archive-o"></i>
                            Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="catalogo.php?id_usuario={{datos_usuario.id}}">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            Catalogo
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-industry"></i>
                            Indicadores
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="reporte_graficasv3.php">
                                <i class="fa fa-bar-chart "></i>
                                Indicadores ventas
                            </a>
                            <a class="dropdown-item" href="reporte_graficas_cupos.php">
                                <i class="fa fa-pie-chart "></i>
                                Indicadores cupos
                            </a>

                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mecanica.php">
                            <i class="fa fa-plus-square"></i>
                            Mecánica
                        </a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="https://sociosyamigos.com.co/tat_2020/bienvenida.php">
                            <i class="fa fa-plus-square"></i>
                            Tat 2020
                        </a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="https://sociosyamigos.com.co/tat/bienvenida.php">
                            <i class="fa fa-plus-square"></i>
                            Tat 2021
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