<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?> 
        <script src="js/promotores.js?ver=3" type="text/javascript"></script>
        <script type="text/javascript">
            var mostrar_promotores = true;
            var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
        </script>
    </head>

    <body ng-app="promotoresApp" ng-controller="promotoresController" class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $_SESSION["usuario"]["nombre"]; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?php include 'componentes/menu.php'; ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        promotores
                        <small>Promotor</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                        <li class="active">promotores</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
             
                        <div class="col-sm-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>id</th>          
                                        <th>Nombre</th>
                                        <th>Venta</th>
                                        <th>Cuota</th>
                                        <th>Cumplimiento</th>
                                        <th>Solicitar</th>
                                        <th>Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="data in lista_promotores track by $index">            
                                        <td class="text-left hide-for-medium-only hide-for-small-only">{{data.id_afiliados}}</td>
                                        <td class="text-left">{{data.nombre}}</td>
                                        <td class="text-left hide-for-small-only">${{data.venta | number}}</td>
                                        <td class="text-left hide-for-small-only">${{data.cuota | number}}</td>
                                        <td class="text-left hide-for-small-only">{{data.cumplimiento}}</td>
                                        <td>
                                            <button ng-show="data.validacion == 1" class="btn btn-success"><i class="fa fa-check"></i></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" ng-click="loadPromotorDetalle(data.id_afiliados)"><i class="fa fa-diamond"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane" id="control-sidebar-home-tab">

                    </div>
                </div>
            </aside>
        </div>
        <div class="modal" id="modalDetalleAlmacenesPromotor">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Droguerias Promotor</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">    
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>ID Almacen</th>
                                    <th>Drogueria</th>
                                    <th>Venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="droguerias in lista_promotores_detalle track by $index">
                                    <td class="text-left">{{droguerias.id}}</td>
                                    <td class="text-left">{{droguerias.almacen}}</td>
                                    <td class="text-left">${{droguerias.venta |number}}</td>
                                </tr>
                            </tbody>
                        </table>
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