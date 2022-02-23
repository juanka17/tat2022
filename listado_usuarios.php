<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/listado_usuarios.js?ver=1" type="text/javascript"></script>
    <script type="text/javascript">
    var id_usuario = 0;
    var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
    if (typeof getParameterByName("id_usuario") !== 'undefined' && getParameterByName("id_usuario") != "") {
        id_usuario = getParameterByName("id_usuario");
    } else {
        alert("No hay usuario seleccionado.");
        document.location.href = "listado_usuarios.php";
    }
    </script>
</head>

<body ng-app="listadoUsuariosApp" ng-controller="listadoUsuariosController" class="layout-top-nav"
    style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Buscar Usuario
                    <small>Buscar el vendedor por nombre o por identificacion</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Buscar usuario</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row" ng-show="datos_usuario.ID_ROL==2">
                    <div class="col-ms-12 col-md-3 form-group">
                        <label>Cédula</label>
                        <input class="form-control" type="text" placeholder="Cédula" ng-model="filtros.cedula" />
                    </div>
                    <div class="col-ms-12 col-md-3 form-group">
                        <label>Codigo Formas</label>
                        <input class="form-control" type="text" placeholder="Codigo Formas" ng-model="filtros.cod_formas" />
                    </div>
                    <div class="col-ms-12 col-md-3 form-group">
                        <label>Nombre</label>
                        <input class="form-control" type="text" placeholder="Nombre" ng-model="filtros.nombre" />
                    </div>
                    <div class="col-ms-12 col-md-3 form-group">
                        <label>Almacen</label>
                        <select class="form-control" ng-model='filtros.almacen'>
                            <option value="">Almacenes</option>
                            <option ng-repeat="operacion in almacen track by $index"
                                value='{{operacion.id}}'>{{operacion.nombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row" ng-show="datos_usuario.ID_ROL==1">
                    <div class="col-ms-12 col-md-4 form-group">
                        <label>Cédula</label>
                        <input class="form-control" type="text" placeholder="Cédula" ng-model="filtros.cedula" />
                    </div>
                    <div class="col-ms-12 col-md-4 form-group">
                        <label>Nombre</label>
                        <input class="form-control" type="text" placeholder="Nombre" ng-model="filtros.nombre" />
                    </div>
                    <div class="col-ms-12 col-md-4 form-group">
                        <label>Almacen</label>
                        <select class="form-control" ng-model='filtros.almacen'>
                            <option value="">Almacenes</option>
                            <option ng-repeat="operacion in almacen track by $index"
                                value='{{operacion.id_drogueria}}'>{{operacion.drogueria}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <button class="btn btn-success btn-block" ng-click="BuscarUsuarios()"><i
                                class="fa fa-search"></i>
                            Buscar Usuarios</button>
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary btn-block" href="../../modulos/crear_usuario/crear_usuario.php"><i
                                class="fa fa-user-plus"></i> Crear Usuario</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="hide-for-small-only">Cédula</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th class="hide-for-small-only">Almacen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="usuario in listado_usuarios track by $index">
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                            ng-click="RedireccionarUsuario(usuario.ID)">
                                            <i class="fa fa-search"></i>{{usuario.ID}}
                                        </button>
                                    </td>
                                    <td class="hide-for-small-only">{{usuario.CEDULA}}</td>
                                    <td>{{usuario.NOMBRE}}</td>
                                    <td>{{usuario.estatus}}</td>
                                    <td class="hide-for-small-only">{{usuario.ALMACEN}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
        </footer>
        <?php include 'componentes/coponentes_js.php'; ?>

    </div>
</body>

</html>