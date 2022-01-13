<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="./js/reporte_graficasv3.js?reload=36" type="text/javascript"></script>
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

        .filter_panel {
            background: white;
            -webkit-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.75);
            -moz-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.75);
            box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.75);
            height: 125px;
        }

        .filter_panel label {
            border-bottom: 1px solid;
            display: block;
            padding-left: 5px;
        }

        .filter_list {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            overflow-y: auto;
            overflow-x: hidden;
            height: 100px;
            width: 100%;
        }

        .filter_panel .filter_list ul {
            list-style: none;
            margin: 0px;
            padding: 0px 5px;
        }

        .filter_panel .filter_list input {
            margin-right: 5px;
        }

        .change_selection_children {
            float: right;
            margin-right: 5px !important;
        }

        h3 {}

        h4 {
            margin: 0px;
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
                <div class="row">
                    <div class="col-sm-12 col-md-2">
                        <div class="filter_panel">
                            <label>Periodo <input type="checkbox" class="change_selection_children" /></label>
                            <div class="filter_list">
                                <ul ng-repeat="periodos in periodos_ventas track by $index">
                                    <li><input class='filtro_periodo' type="checkbox" value="{{periodos.id}}" checked="checked">{{periodos.nombre}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Portafolio</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_portafolio" ng-change="categoriaProductos();loadMarcasProductos();loadSubMarcas();productos();">
                            <option ng-selected="true" ng-value='0'>Todos</option>
                            <option value='1'>PFE</option>
                            <option value='2'>GSK</option>
                            <option value='3'>Sin SKU</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Categoria Producto</label>
                        <select class="form-control" id="categoria_productos" ng-model="FiltrosGraficas.id_categoria_producto" ng-change="loadMarcasProductos();loadSubMarcas(); productos()">
                            <option ng-selected="true" ng-value='0'>Todos</option>
                            <option ng-repeat="categoria_producto in categoria_productos track by $index" value='{{categoria_producto.id_categoria_producto}}'>{{categoria_producto.categoria_producto}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Marca</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_marca_nueva_grafica" ng-change="loadSubMarcas();productos()">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-repeat="marca in marcas track by $index" value='{{marca.id}}'>{{marca.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>SubMarca</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_sub_marca_nueva_grafica" ng-change="productos()">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-repeat="s_marca in sub_marcas track by $index" value='{{s_marca.id}}'>{{s_marca.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Producto</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_producto_nueva_grafica">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-repeat="productos in productoss track by $index" value='{{productos.id}}'>{{productos.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Territorios</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_territorio" ng-change="loadRepresentantes();loadDistribuidorasMadre();almacenesRepresentante()">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-value='1'>Norte</option>
                            <option ng-value='2'>Centro</option>
                            <option ng-value='3'>Sur</option>
                            <option ng-value='5'>Santanderes</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Representante</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_representante" ng-change="loadDistribuidorasMadre(); almacenesRepresentante()">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-repeat="representante in representantes track by $index" value='{{representante.id}}'>{{representante.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Distribuidora Madre</label>
                        <select class="form-control" ng-model="FiltrosGraficas.id_distribuidora_madre" ng-change="almacenesRepresentante()">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-repeat="distri in distribuidora_madre track by $index" value='{{distri.id}}'>{{distri.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <label>Distribuidora</label>
                        <select class="form-control" ng-model="FiltrosGraficas.almacenes_grafica_ventas">
                            <option ng-selected="true" value='0'>Todos</option>
                            <option ng-repeat="almacen_representante in almacen_representantes track by $index" value='{{almacen_representante.id}}'>{{almacen_representante.nombre}}</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-2">
                        <br />
                        <button ng-click="CrearFiltros();" class="btn btn-primary btn-block"><i class="fa fa-bar-chart"> Mostrar Gráficas</i></button>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <br />
                        <button type="reset" class="btn btn-danger btn-block" ng-click="reset();"><i class="fa fa-trash"></i> Limpiar Filtros</button>
                    </div>
                    <div class="col-sm-12 col-md-2" ng-init="panel_indicador = 0; show_all_panels = false;">
                        <br />
                        <button ng-click="panel_indicador = 1" class="btn btn-primary btn-block">Portafolio</button>
                        <button ng-click="panel_indicador = 2" class="btn btn-primary btn-block">Territorios</button>
                        <button ng-click="panel_indicador = 3" class="btn btn-primary btn-block">Representantes</button>
                        <button ng-click="panel_indicador = 4" class="btn btn-primary btn-block">Ranking productos</button>
                        <button ng-click="panel_indicador = 5" class="btn btn-primary btn-block">Crecimiento Anual</button>
                        <button ng-click="panel_indicador = 6" class="btn btn-primary btn-block">Cumplimiento</button>
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <div ng-show="show_all_panels || panel_indicador == 1">
                            <div class="col-sm-12">
                                <h3>Portafolio</h3>
                            </div>
                            <div class="col-sm-12">
                                <div id="GraficaPortafolio" style="height: 500px;"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 2">
                            <div class="col-sm-12">
                                <h3>Gráficas Territorios</h3>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-primary btn-cambiar-fuente-grafica-territorios" data-fuente="ventas">Ventas</button>
                                <button class="btn btn-primary btn-cambiar-fuente-grafica-territorios" data-fuente="impactos">Impactos</button>
                                <button class="btn btn-primary btn-cambiar-fuente-grafica-territorios" data-fuente="dropsize">Dropsize</button>
                                <div id="GraficaTerritorios"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 3">
                            <div class="col-sm-12">
                                <h3>Gráficas Representantes</h3>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <h3>Representantes</h3>
                                    <div id="graficaRepresentantes_representantes"></div>
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <h3>Distribuidoras</h3>
                                    <div id="graficaRepresentantes_distribuidoras"></div>
                                </div>
                            </div>

                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 4">
                            <div class="col-sm-12">
                                <h3>Gráficas Ranking Productos</h3>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div id="grafica_marcas"></div>
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <div id="grafica_submarcas"></div>
                                </div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 5">
                            <div class="col-sm-12">
                                <h3>Gráficas Crecimiento Anual</h3>
                            </div>
                            <div class="col-sm-12">
                                <div id="NuevaGraficaCrecimientoAnualVentas"></div>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Impactos</h3>
                                <div id="NuevaGraficaCrecimientoAnualImpactos"></div>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Dropsize</h3>
                                <div id="NuevaGraficaCrecimientoAnualDropsize"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 6">
                            <div class="col-sm-12">
                                <h3>Cumplimiento</h3>
                            </div>
                            <div class="col-sm-12">
                                <h4>Ventas</h4>
                                <div id="GraficaCumplimientoVentas" style="height: 250px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <h4>Impactos</h4>
                                <div id="GraficaCumplimientoImpactos" style="height: 250px;"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
        </div>
    </div>
    <?php include 'componentes/coponentes_js.php'; ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js" crossorigin="anonymous"></script>
    <script src="https://pierresh.github.io/morris.js/js/regression.js" crossorigin="anonymous"></script>
    <script src="plugins/morris.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="plugins/morris.css">
    </style>
    <script>
        window.Promise || document.write('<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>')
        window.Promise || document.write('<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>')
        window.Promise || document.write('<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>')
    </script>
    <script src="js/apexcharts.min.js"></script>
    <script>
        var dgproducts = null;
        var data_graficas = [];
        var dgcumplimiento = null;
        $(function() {
            $(".change_selection_children").attr("checked", "checked");
            $(".change_selection_children").on("change", function() {
                if ($(this).is(':checked')) {
                    $(this).parent().siblings(".filter_list").find("input:checkbox").attr("checked", "checked");
                } else {
                    $(this).parent().siblings(".filter_list").find("input:checkbox").removeAttr("checked");
                }
            });
        })
    </script>
</body>

</html>