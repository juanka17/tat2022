<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="./js/reporte_graficas.js?reload=35" type="text/javascript"></script>
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
    </style>
</head>

<body ng-app="reporteGraficasApp" ng-controller="reporteGraficasController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>

        <div class="content-wrapper">

            <section class="content">
                <div class="row">

                    <div class="col-sm-12 col-md-12">
                        <div id="indicadores_territorios" class="row">
                            <div class="col-sm-12 col-md-2">
                                <label>Portafolio</label>
                                <select class="form-control" ng-model="id_portafolio" ng-change="categoriaProductos(id_portafolio);loadMarcasProductos(0, id_portafolio);loadSubMarcas(0, id_portafolio, 0);productos(id_portafolio, 0, 0, 0);">
                                    <option ng-selected="true" ng-value='0'>Todos</option>
                                    <option value='1'>PFE</option>
                                    <option value='2'>GSK</option>
                                    <option value='3'>Sin SKU</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Categoria Producto</label>
                                <select class="form-control" id="categoria_productos" ng-model="id_categoria_producto" ng-change="loadMarcasProductos(id_categoria_producto, id_portafolio);loadSubMarcas(id_categoria_producto, id_portafolio, 0); productos(id_portafolio, id_categoria_producto, 0, 0)">
                                    <option ng-selected="true" ng-value='0'>Todos</option>
                                    <option ng-repeat="categoria_producto in categoria_productos track by $index" value='{{categoria_producto.id_categoria_producto}}'>{{categoria_producto.categoria_producto}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Marca</label>
                                <select class="form-control" ng-model="id_marca_nueva_grafica" ng-change="loadSubMarcas(id_categoria_producto, id_portafolio, id_marca_nueva_grafica);productos(id_portafolio, id_categoria_producto, id_marca_nueva_grafica, 0)">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="marca in marcas track by $index" value='{{marca.id}}'>{{marca.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>SubMarca</label>
                                <select class="form-control" ng-model="id_sub_marca_nueva_grafica" ng-change="productos(id_portafolio, id_categoria_producto, id_marca_nueva_grafica, id_sub_marca_nueva_grafica)">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="s_marca in sub_marcas track by $index" value='{{s_marca.id}}'>{{s_marca.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Producto</label>
                                <select class="form-control" ng-model="id_producto_nueva_grafica">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="productos in productoss track by $index" value='{{productos.id}}'>{{productos.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Territorios</label>
                                <select class="form-control" ng-model="id_territorio" ng-change="loadRepresentantes(id_territorio);loadDistribuidorasMadre(id_territorio, 0);almacenesRepresentante(id_territorio, 0, 0)">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-value='1'>Norte</option>
                                    <option ng-value='2'>Centro</option>
                                    <option ng-value='3'>Sur</option>
                                    <option ng-value='5'>Santanderes</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Representante</label>
                                <select class="form-control" ng-model="id_representante" ng-change="loadDistribuidorasMadre(id_territorio, id_representante); almacenesRepresentante(id_territorio, id_representante, 0)">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="representante in representantes track by $index" value='{{representante.id}}'>{{representante.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Distribuidora Madre</label>
                                <select class="form-control" ng-model="id_distribuidora_madre" ng-change="almacenesRepresentante(id_territorio, id_representante, id_distribuidora_madre)">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="distri in distribuidora_madre track by $index" value='{{distri.id}}'>{{distri.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Distribuidora</label>
                                <select class="form-control" ng-model="almacenes_grafica_ventas">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="almacen_representante in almacen_representantes track by $index" value='{{almacen_representante.id}}'>{{almacen_representante.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label>Periodo</label>
                                <select class="form-control" ng-model="id_periodo_nueva_grafica">
                                    <option ng-selected="true" value='0'>Todos</option>
                                    <option ng-repeat="periodos in periodos_ventas track by $index" value='{{periodos.id}}'>{{periodos.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <br />
                                <button ng-click="ObtenerIndicadoresVentas(id_portafolio, id_categoria_producto, id_marca_nueva_grafica, id_sub_marca_nueva_grafica, id_producto_nueva_grafica, id_territorio, id_representante, id_distribuidora_madre, almacenes_grafica_ventas, id_periodo_nueva_grafica);" class="btn btn-primary btn-block"><i class="fa fa-bar-chart"> Mostrar Gráficas</i></button>
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <br />
                                <button type="reset" class="btn btn-danger btn-block" ng-click="reset();"><i class="fa fa-trash"></i> Limpiar Filtros</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2" ng-init="panel_indicador = 0; show_all_panels = false;">
                        <br />
                        <button ng-click="panel_indicador = 1" class="btn btn-primary btn-block">Territorios</button>
                        <button ng-click="panel_indicador = 2" class="btn btn-primary btn-block">Representantes</button>
                        <button ng-click="panel_indicador = 3" class="btn btn-primary btn-block">Ranking productos</button>
                        <button ng-click="panel_indicador = 4" class="btn btn-primary btn-block">Crecimiento Anual</button>
                        <button ng-click="panel_indicador = 5" class="btn btn-primary btn-block">Cumplimiento</button>
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <div ng-show="show_all_panels || panel_indicador == 1">
                            <div class="col-sm-12">
                                <h3>Gráficas Territorios</h3>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Ventas</h3>
                                <div id="NuevaGraficaVentas" style="height: 250px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Impactos</h3>
                                <div id="NuevaGraficaImpactos" style="height: 250px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Dropsize</h3>
                                <div id="NuevaGraficaDropsize" style="height: 250px;"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 2">
                            <div class="col-sm-12">
                                <h3>Gráficas Representantes</h3>
                            </div>
                            <div class="col-sm-3">
                                <h3>Representantes</h3>
                                <div id="graficaRepresentantes_representantes"></div>
                            </div>
                            <div class="col-sm-9">
                                <h3>Distribuidoras</h3>
                                <div id="graficaRepresentantes_distribuidoras"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 3">
                            <div class="col-sm-12">
                                <h3>Gráficas Ranking Productos</h3>
                            </div>
                            <div class="col-sm-3">
                                <div id="grafica_marcas"></div>
                            </div>
                            <div class="col-sm-9">
                                <div id="grafica_submarcas"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 4">
                            <div class="col-sm-12">
                                <h3>Gráficas Crecimiento Anual</h3>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Ventas</h3>
                                <div id="NuevaGraficaCrecimientoAnualVentas" style="height: 250px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Impactos</h3>
                                <div id="NuevaGraficaCrecimientoAnualImpactos" style="height: 250px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <h3>Gráfica Dropsize</h3>
                                <div id="NuevaGraficaCrecimientoAnualDropsize" style="height: 250px;"></div>
                            </div>
                        </div>
                        <div ng-show="show_all_panels || panel_indicador == 5">
                            <div class="col-sm-12">
                                <h3>Cumplimiento Cuotas</h3>
                            </div>
                            <div class="col-sm-12">
                                <h4>Cumplimiento Ventas</h4>
                                <div id="GraficaCumplimientoVentas" style="height: 250px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <h4>Cumplimiento Impactos</h4>
                                <div id="GraficaCumplimientoImpactos" style="height: 250px;"></div>
                            </div>
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