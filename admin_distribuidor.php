<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/admin_distribuidores.js?ver=1" type="text/javascript"></script>
    <script type="text/javascript">
        var idAfiliado = 0;
        var idAfiliadoSeleccionado = 0;
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
</head>

<body ng-app="adminApp" ng-controller="adminController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Distribuidores
            </h1>
            <ol class="breadcrumb">
                <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="menu_administracion.php"><i class="fa fa-user"></i> Administracion</a></li>
                <li class="active">Distribuidores</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <input class="form-control" type='text' placeholder="Buscar por nombre" ng-model="filtros.nombre" ng-change="SeleccionarListadoAlmacenes()" />
                </div>
                <div class="col-sm-12 col-md-4">
                    <button class="btn btn-primary btn-block" ng-click="CrearNuevoAlmacen()">
                        Crear nuevo
                    </button>
                </div>
                <div class="col-sm-12 col-md-4">
                    <a class="btn btn-danger btn-block" href="menu_administracion.php">
                        Volver
                    </a>
                </div>
                <div class="col-sm-12">
                    <br />
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Ejecutivo</th>
                                <th>Nombre</th>
                                <th class="text-center">Cupos</th>
                                <th class="text-center">Supervisores</th>
                                <th class="text-center">Modificar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="almacen in lista_almacenes track by $index">
                                <td class="text-left">{{almacen.drogueria}}</td>
                                <td class="text-left" ng-show="datos_usuario.es_administrador == 1">{{almacen.visitador}}</td>
                                <td class="text-center">{{almacen.encuestas_periodo}}</td>
                                <td class="text-center">{{almacen.supervisores}}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-block" ng-click="EditarAlmacen($index)">
                                        <span class="fa fa-edit"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- /.content -->
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
        </footer>
        <?php include 'componentes/coponentes_js.php'; ?>
        <!-- Control Sidebar -->
    </div>
    <div class="modal" id="modalEditarAlmacen">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modificar distribuidor</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input class="form-control" type='text' ng-model="almacen_seleccionado.nombre" />
                    </div>
                    <div class="form-group">
                        <label for="nombre">Visitador:</label>
                        <select class="form-control" ng-model="almacen_seleccionado.id_visitador" ng-options="c.id as c.nombre for c in lista_visitadores">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Cupos:</label>
                        <input class="form-control" type='text' ng-model="almacen_seleccionado.encuestas_periodo" />
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 text-left">
                            <button type="button" data-close aria-label="Close modal" class="btn btn-danger btn-block">Cerrar</button>
                        </div>
                        <div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-3 text-right">
                            <button type="button" ng-show="almacen_seleccionado.id > 0" class="btn btn-primary btn-block" ng-click="ModificarAlmacen()">Actualizar</button>
                            <button type="button" ng-show="almacen_seleccionado.id == 0" class="btn btn-primary btn-block" ng-click="CrearAlmacen()">Crear</button>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

</body>

</html>