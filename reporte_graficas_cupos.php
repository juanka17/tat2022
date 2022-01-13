<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="./js/reporte_graficas.js?reload=32" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script>
        var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
    <script src="./node_modules/chart.js/dist/Chart.min.js"></script>
    <script src="./node_modules/angular-chart.js/dist/angular-chart.min.js"></script>
    <style>
        .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
            height: auto;
        }

        .indicador {
            border-radius: 100%;
            display: inline-block;
            height: 15px;
            margin-right: 10px;
            width: 15px;
        }

        .cuadro_regional {
            border: 3px solid black;
            border-radius: 20px;
            padding: 10px;
        }
    </style>
</head>

<body ng-app="reporteGraficasApp" ng-controller="reporteGraficasController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <section class="content">

                <div id="indicadores_cupos" class="row">
                    <div class="col-sm-12">
                        <h3>
                            Gráfica Cupos por Territorios<br />
                            <small>{{mensajeCupos}}</small>
                        </h3>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <label>Territorios</label>
                        <select class="form-control" ng-change="loadRepresentantes(id_territorio_grafica_cupos)" ng-model="id_territorio_grafica_cupos">
                            <option ng-value='0'>Todos</option>
                            <option ng-value='1'>Norte</option>
                            <option ng-value='2'>Centro</option>
                            <option ng-value='3'>Sur</option>
                            <option ng-value='5'>Santanderes</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <label>Representante</label>
                        <select class="form-control" ng-change="almacenesRepresentante(id_representante_grafica_cupos)" ng-model="id_representante_grafica_cupos">
                            <option value='0'>Todos</option>
                            <option ng-repeat="representante in representantes track by $index" value='{{representante.id}}'>{{representante.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <label>Distribuidora</label>
                        <select class="form-control" ng-model="almacenes_grafica_cupos">
                            <option value='0'>Todos</option>
                            <option ng-repeat="almacen_representante in almacen_representantes track by $index" value='{{almacen_representante.id}}'>{{almacen_representante.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <label>Temporada</label>
                        <select class="form-control" id="dd_almacen" ng-model="temporadas_grafica_cupos">
                            <option ng-value='0'>Todos</option>
                            <option ng-repeat="temporada in temporadas track by $index" ng-show="temporada.id <= 6" value='{{temporada.id}}'>{{temporada.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <br />
                        <button ng-click="ObtenerIndicadoresCupos(id_territorio_grafica_cupos, id_representante_grafica_cupos, almacenes_grafica_cupos, temporadas_grafica_cupos);" class="btn btn-primary btn-block"><i class="fa fa-bar-chart">Ver Grafica</i></button>
                    </div>
                    <div class="col-sm-12 col-md-6 text-left">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cupos reconocimiento </td>
                                    <td>{{ecu_temporada_general.data[0].encuestas_periodo| number}}</td>
                                </tr>
                                <tr>
                                    <td>Vendedores cumplen </td>
                                    <td>{{ecu_temporada_general.data[0].cumplen| number}}</td>
                                </tr>
                                <tr>
                                    <td>Vendedores habilitados </td>
                                    <td>{{ecu_temporada_general.data[0].habilitados| number}}</td>
                                </tr>
                                <tr>
                                    <td>Total Solicitado </td>
                                    <td>{{ecu_temporada_general.data[0].total_solicitado| number}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="indicador" style=" background-color: #ff0000;"></a> Por solicitar
                                    </td>
                                    <td>{{ecu_temporada_general.data[0].pendientes_por_solicitar| number}}</td>
                                </tr>
                                <tr>
                                    <td style="color:red">Estado de entregas </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="indicador" style=" background-color: #367fa9;"></a> Solicitado
                                    </td>
                                    <td>{{ecu_temporada_general.data[0].solicitado| number}}</td>
                                </tr>

                                <tr>
                                    <td>
                                        <a class="indicador warning" style=" background-color: #fbf000;"></a> Despachados
                                    </td>
                                    <td>{{ecu_temporada_general.data[0].despachado| number}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="indicador" style=" background-color: #00ff00;"></a> Legalizados
                                    </td>
                                    <td>{{ecu_temporada_general.data[0].legalizado| number}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-md-6 text-left">
                        <div id="graficaDeCupos"></div>
                    </div>

                    <div class="col-sm-12 col-md-3 text-left" ng-repeat="grafica in ecu_temporada track by $index">
                        <div class="cuadro_regional">
                            <h4>{{grafica.territorio}}</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Cupos reconocimiento </td>
                                        <td>{{grafica.encuestas_periodo| number}}</td>
                                    </tr>
                                    <tr>
                                        <td>Vendedores cumplen </td>
                                        <td>{{grafica.cumplen| number}}</td>
                                    </tr>
                                    <tr>
                                        <td>Vendedores habilitados </td>
                                        <td>{{grafica.habilitados| number}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Solicitado </td>
                                        <td>{{grafica.total_solicitado| number}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a class="indicador" style=" background-color: #ff0000;"></a> Por solicitar
                                        </td>
                                        <td>{{grafica.pendientes_por_solicitar| number}}</td>
                                    </tr>
                                    <tr>
                                        <td style="color:red">Estado de entregas </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a class="indicador" style=" background-color: #367fa9;"></a> Solicitado
                                        </td>
                                        <td>{{grafica.solicitado| number}}</td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a class="indicador warning" style=" background-color: #fbf000;"></a> Despachados
                                        </td>
                                        <td>{{grafica.despachado| number}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a class="indicador" style=" background-color: #00ff00;"></a> Legalizados
                                        </td>
                                        <td>{{grafica.legalizado| number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="graficaDeCupos{{$index}}"></div>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
        </footer>
    </div>
    <?php include 'componentes/coponentes_js.php'; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js" crossorigin="anonymous"></script>
    <script src="https://pierresh.github.io/morris.js/js/regression.js" crossorigin="anonymous"></script>
    <script src="plugins/morris.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="plugins/morris.css">
    <script>
        var dgproducts = null;
    </script>
</body>

</html>