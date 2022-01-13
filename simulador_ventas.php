<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/simulador_ventas.js?reload=23" type="text/javascript"></script>

    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
        var id_usuario = 0;
        var id_temporada = 0;
        if (typeof getParameterByName("id_usuario") !== 'undefined' && getParameterByName("id_usuario") != "") {

            id_usuario = getParameterByName("id_usuario");
        } else {
            alert("No selecciono vendedor");
        }
        if (typeof getParameterByName("id_temporada") !== 'undefined' && getParameterByName("id_temporada") != "") {

            id_temporada = getParameterByName("id_temporada");
        } else {
            alert("No selecciono vendedor");
        }

        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    </script>
    <style>
        .simulador {
            border: 3px solid #c490be;
            border-radius: 20px;
            box-shadow: 10px 10px 10px 5px #c490be;
            margin-top: 20px;
        }

        .modal-lg {
            width: 1159px;
        }

        #tabla_supevisor {
            border: 10px solid #e4dede;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: 8px solid #f4f4f4;
        }
    </style>
</head>

<body ng-app="simuladorApp" ng-controller="simuladorController" class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper" style="min-height: 556px;">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <div class="container">
            <section class="content-header">
                <h1>
                    Simulador de ventas
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Bienvenida</a></li>
                    <li onclick="javascript:document.location.href = document.referrer"><i class="fa fa-home"></i> Almacen</li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-2 offset-md-10">
                        <a class="btn btn-danger " onclick="javascript:document.location.href = document.referrer" /><i class="fa fa-home"></i> Volver</a>
                        <br />
                        <br />
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Venta:</label>
                            <input class="form-control" id="money" placeholder="Ingrese la venta" type='number' ng-model="simulador.venta" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Impactos:</label>
                            <input class="form-control" placeholder="Ingrese los impactos" type='number' ng-model="simulador.impactos" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Fecha:</label>
                            <input class="form-control" type='date' id="fecha_simulados" />
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="Simular();">Realizar simulación</button>
                    </div>
                    <div ng-init="mostrar = 0">
                        <div class="text-center" ng-show="mostrar == 1">
                            <div class="col-sm-12 col-md-6 offset-md-5">
                                <table id="tabla_supevisor" class="table table-bordered table-hover">
                                    <thead>
                                        <th colspan="5"></th>
                                        <th>
                                            <h3>Ventas</h3>
                                        </th>
                                        <th colspan="2"></th>
                                        <th>
                                            <h3>Impactos</h3>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Días
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>{{dia}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{dia}}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Cuota
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>$ {{cuota[0].cuota_2| number}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{cuota[0].impactos}}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Venta
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>$ {{venta| number}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{impactos}}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Cumplimiento hoy
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>{{cumplimiento_hoy_venta| number :0}}%</h5>
                                            </td>
                                            <td>
                                                <h5>{{cumplimiento_hoy_impactos| number :0}}%</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Venta Diaria
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>${{venta_diaria_ventas| number :0}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{venta_diaria_impactos| number :0}}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Cierre proyectado venta hoy
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>$ {{cierre_proyectado_venta_hoy_ventas| number:0}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{cierre_proyectado_venta_hoy_impactos| number:0}}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Cumplimiento cierre venta hoy
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>{{cumplimiento_cierre_venta| number:0}}%</h5>
                                            </td>
                                            <td>
                                                <h5>{{cumplimiento_cierre_impactos| number:0}}%</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Venta diaria minima
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>$ {{meta_diaria_para_cumplir_venta| number:0}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{meta_diaria_para_cumplir_impactos| number:0}}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4>
                                                    Vender para cumplir diaria 100%
                                                </h4>
                                            </td>
                                            <td colspan="4">
                                                <h5>$ {{vender_para_cumplir_diaria_venta| number:0}}</h5>
                                            </td>
                                            <td>
                                                <h5>{{vender_para_cumplir_diaria_impactos| number:0}}</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-success btn-block" ng-click="DescargarExcelGeneral();"><i class="fa fa-download"></i> Descargar</button>
                                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#simuladorvendedor" ng-click="CargarCuotasVendedor();">Vendedores Supervisor</button>

                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
            <?php include 'componentes/coponentes_js.php'; ?>
        </footer>
        <script type="text/javascript" src="js/jquery.mask.js"></script>
        <script type="text/javascript" src="js/jquery.mask.min.js"></script>
        <scritp type="text/javascript">

        </scritp>
    </div>
    <!-- Modal -->

    <div id="simuladorvendedor" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Vendedores Supervisor</h4>
                    <button class="btn btn-success btn-block" ng-click="DescargarExcelDetallado();"><i class="fa fa-download"></i> Descargar</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="simulador_detallado">
                        <thead>
                            <th colspan="3">

                            </th>
                            <th>
                                Dias
                            </th>
                            <th>
                                Cuota
                            </th>
                            <th>
                                Valor Ingresado
                            </th>
                            <th>
                                Cumplimiento hoy
                            </th>
                            <th>
                                Venta Diaria
                            </th>
                            <th>
                                Cierre proyectado venta hoy
                            </th>
                            <th>
                                Cumplimiento cierre venta hoy
                            </th>
                            <th>
                                Meta diaria para cumplir
                            </th>
                            <th>
                                Vender para cumplir diaria 100%
                            </th>
                        </thead>
                        <tbody ng-repeat="cuotas in cuota_vendedor track by $index">
                            <tr>
                                <td ROWSPAN="3" class="simulador_nombre_venta">
                                    {{cuotas.nombre}}
                                </td>
                                <td>
                                    <input size="4" type="number" placeholder="Ingrese Venta" id="valor_vendedor_{{$index}}" />
                                </td>
                                <td>
                                    Ventas
                                </td>
                                <td class="simulador_dia_venta">
                                    {{dia}}
                                </td>
                                <td class="simulador_cuota_venta">
                                    ${{cuotas.cuota_2| number}}
                                </td>
                                <td class="simulador_valor_ingresado_venta">
                                    <p id="valor_ingresado_{{$index}}"></p>
                                </td>
                                <td class="simulador_cumplimiento_hoy_venta">
                                    <p id="cumplimiento_hoy_venta_{{$index}}"></p>
                                </td>
                                <td class="simulador_venta_diaria">
                                    <p id="venta_diaria_{{$index}}"></p>
                                </td>
                                <td class="simulador_cierre_proyectado_venta_hoy">
                                    <p id="cierre_proyectado_venta_hoy{{$index}}"></p>
                                </td>
                                <td class="simulador_cumplimiento_venta_cierre">
                                    <p id="cumplimiento_venta_cierre{{$index}}"></p>
                                </td>
                                <td class="simulador_meta_para_cumplir_venta">
                                    <p id="meta_para_cumplir_venta{{$index}}"></p>
                                </td>
                                <td class="simulador_vender_para_cumplir_venta">
                                    <p id="vender_para_cumplir_venta{{$index}}"></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input size="4" type="number" placeholder="Ingrese Impactos" id="impactos_vendedor_{{$index}}" />
                                </td>
                                <td>
                                    Impactos
                                </td>
                                <td class="simulador_dia_imp">
                                    {{dia}}
                                </td>
                                <td class="simulador_cuota_imp">
                                    {{cuotas.impactos}}
                                </td>
                                <td class="simulador_valor_ingresado_imp">
                                    <p id="valor_ingresado_cuota_{{$index}}"></p>
                                </td>
                                <td class="simulador_cumplimiento_hoy_imp">
                                    <p id="cumplimiento_hoy_impactos_{{$index}}"></p>
                                </td>
                                <td class="simulador_venta_diaria_impactos">
                                    <p id="venta_impactos_diaria_{{$index}}"></p>
                                </td>
                                <td class="simulador_cierre_proyectado_imp_hoy">
                                    <p id="cierre_proyectado_impactos_hoy{{$index}}"></p>
                                </td>
                                <td class="simulador_cumplimiento_imp_cierre">
                                    <p id="cumplimiento_impactos_cierre{{$index}}"></p>
                                </td>
                                <td class="simulador_meta_para_cumplir_imp">
                                    <p id="meta_para_cumplir_impactos{{$index}}"></p>
                                </td>
                                <td class="simulador_vender_para_cumplir_impacto">
                                    <p id="vender_para_cumplir_impactos{{$index}}"></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn btn-primary btn-sm" ng-click="SimularVendedor($index)">Calcular</button>
                                </td>
                            </tr>
                        </tbody>
                    </TABLE>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</body>

</html>