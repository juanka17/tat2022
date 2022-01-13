<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?> 
        <script src="js/admin_promotores.js?ver=1" type="text/javascript"></script>
        <script type="text/javascript">
            var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
        </script>
        <style>

        </style>
    </head>

    <body ng-app="adminPromotoresApp" ng-controller="adminPromotoresController" class="hold-transition skin-blue sidebar-mini">
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
                        Promotores
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                        <li class="active">Promotores</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <input class="form-control" type='text' placeholder="Buscar por nombre" ng-model="filtros.nombre" ng-change="SeleccionarListadoVendedores()" />
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <a class="btn btn-danger btn-block" href="menu_administracion.php" >
                                Volver
                            </a>
                        </div>
                        <div class="col-sm-12">
                            <br/>
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Promotor</th>
                                        <th>Cod Formas</th>
                                        <th>Clasificacion</th>
                                        <th class="text-center">Agregar Almacen</th>
                                        <th class="text-center">Almacen Promotor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="promotor in lista_vendedores track by $index">
                                        <td class="text-left">{{promotor.nombre}}</td>
                                        <td class="text-left">{{promotor.cod_formas}}</td>
                                        <td class="text-left">{{promotor.clasificacion}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary" ng-click="AgregarAlmacenes(promotor.id)">
                                                <span class="fa fa-plus"></span>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-success" ng-click="AlmacenesPromotor(promotor.id)">
                                                <span class="fa fa-home"></span>
                                            </button>
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
        <div class="modal" id="modalEditarVendedor">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Agregar Almacenes</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <button  type="button" class='btn btn-primary' ng-click="add()">Agregar Almacen</button> 
                        <div ng-repeat="data in almacenes">                                

                            <div class="row" >

                                <div class="col-sm-12 col-md-8 form-group">    
                                    <label>Almacen</label>                                    
                                    <select required="true" class="form-control" ng-model='data.id_almacen'>
                                        <option ng-repeat="almacenes in lista_almacenes track by $index" 
                                                value='{{almacenes.id_drogueria}}'>{{almacenes.drogueria}}</option>
                                    </select> 
                                </div>

                                <div class="col-sm-12 col-md-4 form-group" >
                                    <br/>
                                    <button ng-hide="$first"  type="button" class='btn btn-danger btn-sm' ng-click="delete($index)">Eliminar</button>
                                </div>  

                            </div>         

                        </div>  
                        <button  type="button" class='btn btn-primary btn-block' ng-click="GuardarAlmacenesPromotor();">Guardar Almacen</button> 
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal" id="modalAlmacenesPromotor">
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
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="almacene in almacenes_promotor track by $index">
                                    <td class="text-left">{{almacene.id}}</td>
                                    <td class="text-left">{{almacene.nombre}}</td>
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