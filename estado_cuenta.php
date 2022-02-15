<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/estado_cuenta.js?ver=2" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="js/app.js"></script>
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
                    <div class="row" ng-show="usuario_en_sesion.es_administrador==3">

                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Almacén</th>
                                        <th>Ventas generales</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{datos_usuario.nombre}}</td>
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
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-2">
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <div class="">
                                        <h3 class="text-center">Transacciones</h3>
                                        <div ng-repeat="periodo in estado_cuenta track by $index">
                                            <button type="button" class="btn btn-primary btn-block"
                                                data-toggle="collapse"
                                                data-target="#demo{{$index}}">Febrero</button>
                                            <div id="demo{{$index}}" class="collapse">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Concepto</th>
                                                            <th>Descripcion</th>
                                                            <th>Puntos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Solicitud</td>
                                                            <td>Redencion de bono</td>
                                                            <td>40</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ventas</td>
                                                            <td>Reporte de ventas</td>
                                                            <td>400</td>
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
                                <th>Procentaje Participacion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="registro in ventas_totales track by $index" ng-show="registro.id_vendedor==id_usuario">
                                <td>{{registro.periodo}}</td>
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
</body>

</html>