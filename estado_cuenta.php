<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/estado_cuenta.js?ver=1" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
    svg>g>g:last-child {
        pointer-events: none
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

    <!--<script type="text/javascript">
    setTimeout(function() {
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            //console.log(grafica);
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Temporada');
            data.addColumn('number', 'Cuotas');
            data.addColumn('number', 'Ventas');
            var array1 = grafica;
            var index = 0;
            array1.forEach(function() {
                console.log(index);
                var cuota = parseFloat($.trim(grafica[index].cuota));
                var venta = parseFloat($.trim(grafica[index].venta));
                data.addRows([
                    [grafica[index].nombre, cuota, venta]
                ]);
                index++;
            });
            var options = {
                chart: {
                    title: 'Grafica de ventas y cuotas por trimestre'
                },
                bars: 'horizontal', // Required for Material Bar Charts.,
            };
            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    }, 3000);
    </script>

    <script type="text/javascript">
    setTimeout(function() {
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            //console.log(grafica);
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Temporada');
            data.addColumn('number', 'Ventas');
            data.addColumn('number', 'Cuotas');
            var array1 = grafica;
            var index = 0;
            array1.forEach(function() {
                var venta = parseFloat($.trim(grafica[index].venta));
                var cuota = parseFloat($.trim(grafica[index].cuota));
                data.addRows([
                    [grafica[index].nombre, venta, cuota]
                ]);
                index++;
            });
            var options = {

                title: 'Grafica De Ventas y Cuotas Por Trimestre',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                },
                // Allow multiple
                // simultaneous selections.
                selectionMode: 'multiple',
                // Trigger tooltips
                // on selections.
                tooltip: {
                    trigger: 'selection'
                },
                // Group selections
                // by x-value.
                aggregationTarget: 'category',

            };
            var chart = new google.visualization.LineChart(document.getElementById('linechart_material'));
            chart.draw(data, options);
        }
    }, 3000);
    </script>-->
</head>

<body ng-app="estadoCuentaApp" ng-controller="estadoCuentaController" class="layout-top-nav"
    style="height: auto; min-height: 100%;">
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
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-sm-12 col-md-offset-11 col-md-1">
                            <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Categoria</th>
                                        <th>Almacén</th>
                                        <th>Ventas generales</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{datos_usuario.nombre}}</td>
                                        <td>{{datos_usuario.clasificacion}}</td>
                                        <td>{{datos_usuario.almacen}}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modal_ventas" ng-click="VerDetalles(0, 3)">
                                                Ver ventas generales</button>
                                        </td>
                                        <td>{{datos_usuario.saldo_actual| number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <h4>Resumen total de puntos</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Puntos Ganados</th>
                                        <th>Puntos Redimidos</th>
                                        <th>Puntos Disponibles </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat=" puntos_usu in puntos_usuario track by $index">
                                        <td>{{puntos_usu.puntos_ganados| number}}</td>
                                        <td>{{puntos_usu.puntos_redimidos| number}}</td>
                                        <td>{{puntos_usu.puntos_disponibles| number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="col-sm-12 col-md-12">
                                        <div id="columnchart_material"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <br />
                                        <br />
                                        <div id="linechart_material"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="col-ms-12 col-md-12">
                                        <h3 class="text-center">Transacciones</h3>
                                        <div ng-repeat="periodo in estado_cuenta track by $index">
                                            <button type="button" class="btn btn-primary btn-block"
                                                data-toggle="collapse"
                                                data-target="#demo{{$index}}">{{periodo.periodo}}</button>
                                            <div id="demo{{$index}}" class="collapse">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Cuota</th>
                                                            <th>Venta</th>
                                                            <th>Puntos Venta</th>
                                                            <th>Puntos Cumplimiento</th>
                                                            <th>Puntos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="registro in periodo.registros track by $index">
                                                            <td>{{registro.cuota| number}}</td>
                                                            <td>{{registro.venta| number}}</td>
                                                            <td>{{registro.puntos_venta| number}}</td>
                                                            <td>{{registro.puntos_cumplimiento| number}}</td>
                                                            <td>{{registro.puntos_venta| number}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div ng-repeat="periodo in estado_cuenta_tickets track by $index"
                                            ng-hide="estado_cuenta_tickets[0].registros.length == 0"
                                            class="col-ms-12 col-md-6">
                                            <h3 class="text-center">Tickets</h3>
                                            <button type="button" class="btn btn-primary btn-block"
                                                data-toggle="collapse"
                                                data-target="#demo1{{$index}}">{{periodo.periodo}}</button>
                                            <div id="demo1{{$index}}" class="collapse">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Concepto</th>
                                                            <th>Descripcion</th>
                                                            <th>Puntos Sorteo</th>
                                                            <th>Cantidad Tickets</th>
                                                            <th>Tickets</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="registro in periodo.registros track by $index">
                                                            <td>{{registro.concepto}}</td>
                                                            <td>{{registro.descripcion}}</td>
                                                            <td>{{registro.puntos| number}}</td>
                                                            <td>{{registro.cantidad| number}}</td>
                                                            <td>
                                                                <a ng-show="registro.id_concepto == 3"
                                                                    data-toggle="modal" data-target="#tickets_usuario"
                                                                    href="#" ng-click="ObtenerTicketsUsuario()"><i
                                                                        class="fa fa-eye"></i> Ver Tickets</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <div id="modal_ventas" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <h2 class="modal-title">{{titulo_detalle}}</h2>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <input class="form-control" type='text' placeholder="Buscar por Temporada"
                            ng-model="filtros_ventas.temporada" ng-change="VerDetalles(0)" />
                    </div>
                    <table class="table" id="tdetalle">
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>Cuota Almacen</th>
                                <th>Venta Almacen</th>
                                <th>Cuota Vendedor</th>
                                <th>Venta Vendedor</th>
                                <th>Procentaje Prticipacion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="registro in ventas_visibles track by $index" ng-show="registro.id_vendedor==datos_usuario.id">
                                <td>{{registro.id_periodo}}</td>
                                <td>${{registro.cuota_almacen| number}}</td>
                                <td>${{registro.venta_almacen| number}}</td>
                                <td>${{registro.cuota_vendedor| number}}</td>
                                <td>${{registro.venta_vendedor| number}}</td>
                                <td>{{registro.porcentaje_participacion | number:'2'}} % </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="tickets_usuario" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">{{titulo_detalle}}</h2>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <input class="form-control" type='text' placeholder="Buscar Ticked"
                            ng-model="filtros_tickets.tickets" ng-change="VerTicketsVisibles()" />
                    </div>
                    <table class="table" id="tdetalle">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="registro in tickets_visibles track by $index">
                                <td>{{$index + 1}}</td>
                                <td>{{registro.tickets}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</body>

</html>