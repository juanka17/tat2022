<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?> 
        <script src="js/reportes_asistentes.js?reload=1" type="text/javascript"></script>
        <script type="text/javascript">
            var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
        </script>
    </head>

    <body ng-app="reportesApp" ng-controller="reportesController" class="skin-blue layout-top-nav" style="height: auto; min-height: 100%;">
        <?php include 'componentes/mostrar_imagen.php'; ?>
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <?php include 'componentes/menu.php'; ?>
                        <!-- /.navbar-collapse -->
                        <!-- Navbar Right Menu -->
                        <div class="navbar-custom-menu">
                            <?php include 'componentes/controles_superiores.php'; ?>
                        </div>
                        <!-- /.navbar-custom-menu -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </header>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Reporte Asistente
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                        <li class="active">Reporte</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content" ng-init="nombre_reporte = 'Entregas'">
                    <div class="row" ng-show="nombre_reporte == 'Entregas'">
                        <div class="col-md-3 text-left">
                            <div class="form-group">
                                <label for="nombre">Distribuidora:</label>
                                <input class="form-control" type='text' ng-model="filtros.drogeria" ng-change="SeleccionarListadoRedenciones()" />
                            </div>
                        </div>
                        <div class="col-md-3 text-left">
                            <div class="form-group">
                                <label for="nombre">Estado:</label>
                                <select class="form-control" ng-model="filtros.id_operacion" ng-change="SeleccionarListadoRedenciones()"
                                        ng-options="c.id as c.nombre for c in operaciones_redencion">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-lg" ng-click="GenerarReporte('entregas')">
                                <i class="fa fa-file-excel-o"></i>
                                Generar reporte
                            </button>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Distribuidora</th>
                                        <th>Entrega</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Actualización</th>
                                        <th>Temporada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="redencion in lista_redenciones track by $index">
                                        <td class="text-left">{{redencion.id_redencion}}</td>
                                        <td class="text-left">
                                            {{redencion.almacen}} 
                                            <a ng-href="modificar_almacen.php?id_almacen={{redencion.id_almacen}}">
                                                <i class="link_view fa fa-share" ></i>
                                            </a>
                                        </td>   
                                        <td class="text-left">{{redencion.premio}}</td>
                                        <td class="text-left">{{redencion.fecha_redencion}}</td>
                                        <td class="text-left">{{redencion.estado}}</td>
                                        <td class="text-left">{{redencion.ultimo_cambio}}</td>
                                        <td class="text-left">{{redencion.temporada}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <!-- /.content-wrapper -->
                <footer class="main-footer">
                    <?php include 'componentes/footer.php'; ?>
                </footer>
                <?php include 'componentes/coponentes_js.php'; ?>
            </div>


        </div>
    </body>
</html>