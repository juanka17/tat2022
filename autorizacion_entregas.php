<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/autorizacion_entregas.js?reload=2" type="text/javascript"></script>
    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
</head>

<body ng-app="reportesApp" ng-controller="reportesController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Autorizar Entregas
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Autorizar Entregas</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class='row text-center'>
                    <div class="col-md-3 text-left">
                        <div class="form-group">
                            <label for="nombre">Distribuidora:</label>
                            <input class="form-control" type='text' ng-model="filtros.drogeria" ng-change="SeleccionarListadoRedenciones()" />
                        </div>
                    </div>
                    <div class="col-md-3 text-left">
                        <div class="form-group">
                            <label for="nombre">Temporada:</label>
                            <select class="form-control" ng-model="filtros.id_temporada" ng-change="SeleccionarListadoRedenciones()" ng-options="c.id as c.nombre_full for c in temporada_redenciones">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <br />
                        <button class="btn btn-primary" ng-click="AutorizarEntregas()">
                            <i class="fa fa-check-circle"></i>
                            Autorizar Entregas
                        </button>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Autorizar Despacho</th>
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
                                    <td class="text-left">
                                        <input type="checkbox" ng-model="redencion.agregar" data-folio="{{redencion.id_redencion}}" class="check_confirmacion" />
                                    </td>
                                    <td class="text-left">{{redencion.id_redencion}}</td>
                                    <td class="text-left">
                                        {{redencion.almacen}}
                                        <a ng-href="modificar_almacen.php?id_almacen={{redencion.id_almacen}}">
                                            <i class="link_view fa fa-share"></i>
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
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
        </div>
    </div>

    <div class="modal fade" id="folioSeleccionado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Redenciones a Autorizar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Confirmación de folios a Despachar</h4>
                    <table class="table">
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
                            <tr ng-repeat="redencion in lista_redenciones_seleccionadas track by $index">
                                <td class="text-left">{{redencion.id_redencion}}</td>
                                <td class="text-left">
                                    {{redencion.almacen}}
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" ng-click="GuardarEntregasAutorizadas();">Guardar</button>

                </div>
            </div>
        </div>
    </div>
</body>

</html>