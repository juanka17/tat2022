<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/estado_cuenta.js?ver=10" type="text/javascript"></script>
    <link rel="stylesheet" href="dev_x/lib/css/dx.light.css">
    <script src="js/app.js"></script>
    <style>
        svg>g>g:last-child {
            pointer-events: none
        }

        .hoverTable {
            width: 100%;
            border-collapse: collapse;
        }

        .hoverTable td {
            padding: 10px;
            border: #f4f4f4 2px solid;
        }

        /* Define the default color for all the table rows */
        .hoverTable tr {
            background: #ecf0f5;
        }

        /* Define the hover highlight color for the table row */
        .hoverTable tr:hover {
            background-color: #ffff99;
            cursor: pointer;
        }
        .alerta{
            color:red
        }
        .movil{
            display: block;
            overflow: auto;
        }
    </style>
    <script>
        var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
        var id_usuario = 0;
        if (typeof getParameterByName("id_usuario") !== 'undefined' && getParameterByName("id_usuario") != "") {
            id_usuario = getParameterByName("id_usuario");
        } else {
            alert("No hay usuario seleccionado.");
        }
    </script>
</head>

<body ng-app="estadoCuentaApp" ng-controller="estadoCuentaController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Estado de Cuenta
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                        <li><a onclick="javascript:history.back();"><i class="fa fa-dashboard"></i> Usuario</a></li>
                        <li class="active">Estado cuenta</li>
                    </ol>
                </section>
                <!-- Estado Cuenta Vendedores -->
                <section class="content" ng-if="datos_usuario.id_rol == 4">
                    <div class="row text-center">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>
                        </div>
                        <div class="col-sm-12">
                            <br />
                            <h4>Resumen estado de cuenta</h4>
                            <div>
                                <button class="btn btn-primary" ng-click="ObtenerCuotasVentasEstadoCuenta()">Cuotas/Ventas</button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan=" 1">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Puntos Ganados</th>
                                        <th>Puntos Gastados</th>
                                        <th>Puntos Disponibles</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--ng-click="VerDetalleEstadoCuenta(puntos.id_vendedor)-->
                                    <tr ng-repeat="puntos in puntos_empleados">
                                        <td>{{puntos.total_puntos_ganados | number}}</td>
                                        <td>{{puntos.total_puntos_gastados | number}}</td>
                                        <td>{{puntos.puntos_restantes | number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <h3 class="text-center">Transacciones</h3>
                            <div ng-repeat="periodo in estado_cuenta_premio track by $index">
                                <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo1{{$index}}">{{periodo.periodo}}</button>
                                <div id="demo1{{$index}}" class="collapse" style="overflow:auto">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Venta</th>
                                                <th>Cuota</th>
                                                <th>Cumplimiento</th>
                                                <th>Puntos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="registro in periodo.registros track by $index">
                                                <td>{{registro.concepto}}</td>
                                                <td>
                                                    <spam ng-if="registro.id_concepto !=2">${{registro.venta | number}}</spam>
                                                </td>
                                                <td>
                                                    <spam ng-if="registro.id_concepto !=2">${{registro.cuota| number}}</spam>
                                                </td>
                                                <td>{{registro.cumplimiento| number}}</td>
                                                <td>{{registro.total_puntos| number}}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="col-sm-12 ">
                                <div class="demo-container">
                                    <div id="chart"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 ">
                                <div class="demo-container">
                                    <div id="pie"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Estado Cuenta Supervisores -->
                <section class="content" ng-if="datos_usuario.id_rol == 6">
                    <div class="row text-center">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>
                        </div>
                        <div class="col-sm-12 ">
                            <br />
                            <h2>Estado Cuenta</h2>
                            <table class="table movil">
                                <thead>
                                    <tr>
                                        <th>Venta</th>
                                        <th>Cuota</th>
                                        <th>Impactos</th>
                                        <th>Cuota Imp</th>
                                        <th>Puntos Ganados</th>
                                        <th>Puntos Gastados</th>
                                        <th>Puntos Disponibles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="puntos in puntos_supervisores">
                                        <td>${{puntos.venta | number}}</td>
                                        <td>${{puntos.cuota | number}}</td>
                                        <td>{{puntos.impactos | number}}</td>
                                        <td>{{puntos.cuota_impactos | number}}</td>
                                        <td>{{puntos.total_puntos_ganados | number}}</td>
                                        <td>{{puntos.total_puntos_gastados | number}}</td>
                                        <td>{{puntos.puntos_restantes | number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 col-md-10 offset-md-1">
                            <h3 class="text-center">Transacciones</h3>
                            <div ng-repeat="periodo in estado_cuenta_premio track by $index">
                                <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo1{{$index}}">{{periodo.periodo}}</button>
                                <div id="demo1{{$index}}" class="collapse">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Venta</th>
                                                <th>Cuota</th>
                                                <th>Cumplimiento</th>
                                                <th class="alerta">Puntos Venta</th>   
                                                <th>Impactos</th>
                                                <th>Cuota Imp</th>
                                                <th>Cumplimiento Imp</th>
                                                <th class="alerta">Puntos Impactos</th>
                                                <th class="alerta">Total Puntos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="registro in periodo.registros track by $index">
                                                <td>{{registro.concepto}}</td>
                                                <td>
                                                    <spam ng-if="registro.id_concepto !=2">${{registro.venta | number}}</spam>
                                                </td>
                                                <td>
                                                    <spam ng-if="registro.id_concepto !=2">${{registro.cuota| number}}</spam>
                                                </td>
                                                <td>{{registro.cumplimiento| number}}</td>
                                                <td class="alerta">{{registro.puntos_venta| number}}</td>
                                                <td>{{registro.impactos| number}}</td>
                                                <td>{{registro.cuota_impactos| number}}</td>
                                                <td>{{registro.cumplimiento_impactos| number}}</td>
                                                <td class="alerta">{{registro.puntos_impactos| number}}</td>
                                                <td class="alerta">{{registro.total_puntos| number}}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content" ng-if="datos_usuario.id_rol == 7">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>
                        </div>
                        <div class="col-sm-12 text-center">
                            <div class="col-sm-12 text-center">
                                <br />
                                <h2>Estado Cuenta</h2>
                                <table class="table table-bordered hoverTable">
                                    <thead>
                                        <tr>
                                            <th>Vendedor</th>
                                            <th>Periodo</th>
                                            <th>Concepto</th>
                                            <th>Total Puntos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="puntos in puntos_informaticos">
                                            <td>{{puntos.vendedor}}</td>
                                            <td>{{puntos.periodo}}</td>
                                            <td>{{puntos.concepto}}</td>
                                            <td>{{puntos.total_puntos | number}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.content -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
        </div>
    </div>

    <div class="modal fade " id="detalleEstadoCuenta" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Cuotas Estado Cuenta
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: auto;" ng-init="temporada_estado_cuenta = 0">
                    <div class="row">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Periodo</th>
                                    <th>Cuota</th>
                                    <th>Venta</th>
                                    <th>Cumplimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="m in cuotas_ventas">
                                    <td>{{m.periodo}}</td>
                                    <td>${{m.cuota | number}}</td>
                                    <td>${{m.venta | number}}</td>
                                    <td>{{m.cumplimiento | number}}%</td>

                                </tr>
                            </tbody>
                        </table>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="VerDetalleEstadoCuentaSupervisor" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Detalle Estado Cuenta {{puntos_empleado_detallado[0].vendedor}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" ng-init="temporada_estado_cuenta = 0">
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 14">
                                Febrero </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 15">
                                Marzo </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 16">
                                Abril </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 17">
                                Mayo </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 18">
                                Junio </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 19">
                                Julio
                            </button>
                        </div>
                    </div>

                    <div class=" text-center caja_bimestres_estado_cuenta" ng-repeat="puntos in puntos_empleado_detallado_supervisor" ng-show="puntos.id_periodo == temporada_estado_cuenta">
                        <br>
                        <table id="tabla_supevisor" class="table table-bordered table-hover text-center">
                            <thead>
                                <tr>
                                    <th colspan="5">{{puntos.periodo}}</th>
                                </tr>
                                <tr>

                                    <th colspan="1"> </th>
                                    <th> Cuota </th>
                                    <th> Venta </th>
                                    <th> Cumplimiento </th>
                                    <th> Puntos </th>
                                </tr>
                                <tr>
                                    <th>Venta</th>
                                    <td>${{puntos.cuota | number}}</td>
                                    <td>${{puntos.venta | number}}</td>
                                    <td>{{puntos.cumplimiento_venta}}%</td>
                                    <td>{{puntos.puntos_venta | number}}</td>
                                </tr>
                                <tr>
                                    <th>Impactos</th>
                                    <td>${{puntos.cuota_impactos | number}}</td>
                                    <td>${{puntos.impactos | number}}</td>
                                    <td>{{puntos.cumplimiento_impactos}}%</td>
                                    <td>{{puntos.puntos_impactos | number}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"> Total Puntos</td>
                                    <td>{{puntos.total_puntos | number}}</td>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- DevExtreme library -->
    <script type="text/javascript" src="dev_x/lib/js/jszip.js"></script>
    <script type="text/javascript" src="dev_x/lib/js/dx.all.js"></script>
</body>

</html>