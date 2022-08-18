<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/admin_vendedores.js?ver=11" type="text/javascript"></script>
    <script type="text/javascript">
    var idAfiliado = 0;
    var idAfiliadoSeleccionado = 0;
    var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
</head>

<body ng-app="adminApp" ng-controller="adminController" class=" layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Usuarios
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li><a href="menu_administracion.php"><i class="fa fa-user"></i> Administracion</a></li>
                    <li class="active">Usuarios</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <input class="form-control" type='text' placeholder="Buscar por nombre"
                            ng-model="filtros.nombre" ng-change="SeleccionarListadoVendedores()" />
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <input class="form-control" type='text' placeholder="Buscar por Cod_formas"
                            ng-model="filtros.cod_formas" ng-change="SeleccionarListadoVendedores()" />
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <input class="form-control" type='text' placeholder="Buscar por Distribuidora"
                            ng-model="filtros.distribuidora" ng-change="SeleccionarListadoVendedores()" />
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <input class="form-control" type='text' placeholder="Buscar por Cedula"
                            ng-model="filtros.cedula" ng-change="SeleccionarListadoVendedores()" />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <br>
                        <button class="btn btn-primary btn-block" ng-click="CrearNuevoAfiliado()">
                            Crear nuevo
                        </button>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <br>
                        <a class="btn btn-danger btn-block" href="menu_administracion.php">
                            Volver
                        </a>
                    </div>
                    <div class="col-sm-12 table-responsive">
                        <br />
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Vendedor</th>
                                    <th>Id</th>
                                    <th>Cod Formas</th>
                                    <th>Cedula</th>
                                    <th>Distribuidora</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th class="text-center">Modificar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="vendedor in lista_vendedores track by $index">
                                    <td class="text-left">{{vendedor.nombre}}</td>
                                    <td class="text-left">{{vendedor.id}}</td>
                                    <td class="text-left">{{vendedor.cod_formas}}</td>
                                    <td class="text-left">{{vendedor.cedula}}</td>
                                    <td class="text-left">{{vendedor.distribuidora}}</td>
                                    <td class="text-left">{{vendedor.rol}}</td>
                                    <td class="text-left">{{vendedor.estatus}}</td>
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

    </div>
    <div class="modal fade" id="modalEditarVendedor" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear/Modificar Usuarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input class="form-control" type='text' ng-model="vendedor_seleccionado.nombre" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="cedula">Cedula:</label>
                                <input class="form-control" type='text' ng-model="vendedor_seleccionado.cedula" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="nombre">Codigo Formas:</label>
                                <input class="form-control" type='text' ng-model="vendedor_seleccionado.cod_formas" />
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="nombre">Visitador:</label>
                                <select class="form-control" ng-model="vendedor_seleccionado.id_visitador"
                                    ng-change="CargarAlmacenes(vendedor_seleccionado.id_visitador)">
                                    <option ng-repeat="visitador in lista_visitadores" value="{{visitador.id}}">
                                        {{visitador.nombre}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="nombre">Distribuidora:</label>
                                <select class="form-control" ng-model="vendedor_seleccionado.id_almacen"
                                    ng-change="CargarSupervisores(vendedor_seleccionado.id_almacen)">
                                    <option ng-repeat="almacen in lista_almacenes" value="{{almacen.id_drogueria}}">
                                        {{almacen.drogueria}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6" ng-hide="vendedor_seleccionado.id > 0">
                            <div class="form-group">
                                <label for="nombre">Supervisor:</label>
                                <select class="form-control" ng-model="vendedor_seleccionado.id_supervisor">
                                    <option value="1">No aplica</option>
                                    <option ng-repeat="supervisor in lista_supervisores" value="{{supervisor.id}}">
                                        {{supervisor.nombre}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="nombre">Estado:</label>
                                <select class="form-control" ng-model="vendedor_seleccionado.id_estatus">
                                    <option value="1" ng-value="1">Activo</option>
                                    <option value="2" ng-value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6" ng-hide="vendedor_seleccionado.id > 0">
                            <div class="form-group">
                                <label for="nombre">Rol:</label>
                                <select class="form-control" ng-model="vendedor_seleccionado.id_rol">
                                    <option ng-repeat="rol in roles" value="{{rol.ID}}">
                                        {{rol.NOMBRE}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 text-left">
                        </div>
                        <div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-3 text-right">
                            <button type="button" ng-show="vendedor_seleccionado.id > 0"
                                class="btn btn-success btn-block" ng-click="ModificarAlmacen()">Actualizar</button>
                            <button type="button" ng-show="vendedor_seleccionado.id == 0"
                                class="btn btn-success btn-block" ng-click="CrearAfiliado()">Crear</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>    
</body>

</html>