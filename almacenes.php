<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/almacenes.js?ver=5" type="text/javascript"></script>
    <script type="text/javascript">
        var mostrar_almacenes = true;
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
</head>

<body ng-app="almacenesApp" ng-controller="almacenesController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Almacenes
                    <small>Buscar la distribuidora por nombre</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Almacenes</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <input type="text" class="form-control" ng-model="filtros.nombre" placeholder="Nombre Distribuidora" ng-change="SeleccionarListadoAlmacenes()" />
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <input type="text" class="form-control" ng-model="filtros.visitador" placeholder="Nombre Ejecutivo" ng-change="SeleccionarListadoAlmacenes()" />
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <input type="text" class="form-control" ng-model="filtros.territorio" placeholder="Territorio" ng-change="SeleccionarListadoAlmacenes()" />
                    </div>
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="hide-for-small-only" ng-show="datos_usuario.es_administrador == 1 || datos_usuario.es_administrador == 4">Ejecutivo</th>
                                    <th class="hide-for-medium-only hide-for-small-only">Ciudad</th>
                                    <th>Nombre</th>
                                    <th>Territorio</th>
                                    <th class="hide-for-small-only">Cupos Diamante</th>
                                    <th class="hide-for-small-only">Cupos Oro</th>
                                    <th class="hide-for-small-only">Cupos Plata</th>
                                    <th class="hide-for-small-only">Cupos Super</th>
                                    <th class="hide-for-small-only">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="almacen in lista_almacenes track by $index">
                                    <td>
                                        <button class="btn btn-warning btn-sm" ng-click="SeleccionaAlmacen($index)">
                                            <span class="fa fa-edit"></span>
                                        </button>
                                    </td>
                                    <td class="text-left hide-for-small-only" ng-show="datos_usuario.es_administrador == 1 || datos_usuario.es_administrador == 4">{{almacen.visitador}}</td>
                                    <td class="text-left hide-for-medium-only hide-for-small-only">{{almacen.ciudad}}</td>
                                    <td class="text-left">{{almacen.drogueria}}</td>
                                    <td class="text-left">{{almacen.territorio}}</td>
                                    <td class="text-left hide-for-small-only">{{almacen.cupos_diamante}}</td>
                                    <td class="text-left hide-for-small-only">{{almacen.cupos_oro}}</td>
                                    <td class="text-left hide-for-small-only">{{almacen.cupos_plata}}</td>
                                    <td class="text-left hide-for-small-only">{{almacen.supervisores}}</td>
                                    <td class="text-left hide-for-small-only">{{almacen.total_premiados}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <!-- /.content -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
        </div>
    </div>
</body>

</html>