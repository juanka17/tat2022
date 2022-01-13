<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?>    
        <script src="./js/reporte_graficasV2.js?reload=34" type="text/javascript"></script>
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
            .indicador
            {
                border-radius: 100%;
                display: inline-block;
                height: 15px;
                margin-right: 10px;
                width: 15px;
            }

            .filter_panel 
            {
                background: white;
                -webkit-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.75);
                -moz-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.75);
                box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.75);
                height: 125px;
            }

            .filter_panel label
            {
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

            .filter_panel .filter_list ul
            {
                list-style: none;
                margin: 0px;
                padding: 0px 5px;
            }

            .filter_panel .filter_list input
            {
                margin-right: 5px;
            }

            .change_selection_children
            {
                float: right;
                margin-right: 5px !important;
            }

            h3
            {

            }

            h4
            {
                margin: 0px;
            }

        </style>
    </head> 
    <body ng-app="reporteGraficasApp" ng-controller="reporteGraficasController" class="skin-blue layout-top-nav" style="height: auto; min-height: 100%;">
        <?php include 'componentes/mostrar_imagen.php'; ?>
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <?php include 'componentes/menu.php'; ?>
                        <!-- /.navbar-collapse -->
                        <!-- Navbar Right Menu -->
                        <div class="navbar-custom-menu">
                            <?php include 'componentes/controles_superiores.php'; ?>
                        </div>
                        <!-- /.navbar-custom-menu -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </header>

            <div class="content-wrapper">

                <section class="content">
                    <div class="row">

                        <div class="col-sm-12 col-md-12">
                            <div class="row" >
                                <div class="col-sm-12 col-md-2">
                                    <div class="filter_panel">
                                        <label>Portafolio <input type="checkbox" class="change_selection_children" /></label>   
                                        <div class="filter_list">
                                            <ul>
                                                <li><input class='filtro_portafolio' type="checkbox" value="1" checked="checked"> GSK</li>
                                                <li><input class='filtro_portafolio' type="checkbox" value="2" checked="checked"> PFE</li>
                                                <li><input class='filtro_portafolio' type="checkbox" value="3" checked="checked"> Sin SKU</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="filter_panel">
                                        <label>Categoria Producto <input type="checkbox" class="change_selection_children" /></label>    
                                        <div class="filter_list">
                                            <ul ng-repeat="categoria_producto in categoria_productos track by $index">
                                                <li><input class='filtro_categoria_producto' type="checkbox" value="{{categoria_producto.id_categoria_producto}}" checked="checked">{{categoria_producto.categoria_producto}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="filter_panel">
                                        <label>Marca <input type="checkbox" class="change_selection_children" /></label>   
                                        <div class="filter_list">
                                            <ul ng-repeat="marca in marcas track by $index">
                                                <li><input class='filtro_marcas' type="checkbox" value="{{marca.id}}" checked="checked">{{marca.nombre}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="filter_panel">
                                        <label>SubMarca <input type="checkbox" class="change_selection_children" /></label>   
                                        <div class="filter_list">
                                            <ul ng-repeat="s_marca in sub_marcas track by $index">
                                                <li><input class='filtro_sub_marcas' type="checkbox" value="{{s_marca.id}}" checked="checked">{{s_marca.nombre}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="filter_panel">
                                        <label>Producto <input type="checkbox" class="change_selection_children" /></label>  
                                        <div class="filter_list">
                                            <ul ng-repeat="productos in productoss track by $index">
                                                <li><input class='filtro_productos' type="checkbox" value="{{productos.id}}" checked="checked">{{productos.nombre}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    </br>
                                </div>
                            </div>
                            <div class="row" >
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
                                    <div class="filter_panel">
                                        <label>Territorios <input type="checkbox" class="change_selection_children" /></label>     
                                        <div class="filter_list">
                                            <ul>
                                                <li><input class='filtro_territorios' type="checkbox" value="1" checked="checked">Norte</li>
                                                <li><input class='filtro_territorios' type="checkbox" value="2" checked="checked">Centro</li>
                                                <li><input class='filtro_territorios' type="checkbox" value="3" checked="checked">Sur</li>
                                                <li><input class='filtro_territorios' type="checkbox" value="5" checked="checked">Santanderes</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="filter_panel">
                                        <label>Representante <input type="checkbox" class="change_selection_children" /></label>  
                                        <div class="filter_list">
                                            <ul ng-repeat="representante in representantes track by $index">
                                                <li><input class='filtro_representantes' type="checkbox" value="{{representante.id}}" checked="checked">{{representante.nombre}}</li>
                                            </ul>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="filter_panel">
                                        <label>Distribuidora Madre <input type="checkbox" class="change_selection_children" /></label>  
                                        <div class="filter_list">
                                            <ul ng-repeat="distri in distribuidora_madre track by $index">
                                                <li><input class='filtro_distribuidora_madre' type="checkbox" value="{{distri.id}}" checked="checked">{{distri.nombre}}</li>
                                            </ul>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="filter_panel">
                                        <label>Distribuidora <input type="checkbox" class="change_selection_children" /></label>     
                                        <div class="filter_list">
                                            <ul ng-repeat="almacen_representante in almacen_representantes track by $index">
                                                <li><input class='filtro_distribuidora' type="checkbox" value="{{almacen_representante.id}}" checked="checked">{{almacen_representante.nombre}}</li>
                                            </ul>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-offset-8 col-md-2">
                                    <br/>
                                    <button ng-click="CrearFiltros(id_portafolio, id_categoria_producto, id_marca_nueva_grafica, id_sub_marca_nueva_grafica, id_producto_nueva_grafica, id_territorio, id_representante, id_distribuidora_madre, almacenes_grafica_ventas, id_periodo_nueva_grafica);" class="btn btn-primary btn-block"><i class="fa fa-bar-chart"> Mostrar Gráficas</i></button>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <br/>
                                    <button type="reset" class="btn btn-danger btn-block" ng-click="reset();"><i class="fa fa-trash"></i> Limpiar Filtros</button>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2" ng-init="panel_indicador = 0; show_all_panels = false;" >
                            <br/>
                            <button ng-click="panel_indicador = 1" class="btn btn-primary btn-block">Territorios</button>
                            <button ng-click="panel_indicador = 2" class="btn btn-primary btn-block">Representantes</button>
                            <button ng-click="panel_indicador = 3" class="btn btn-primary btn-block">Ranking productos</button>
                            <button ng-click="panel_indicador = 4" class="btn btn-primary btn-block">Crecimiento Anual</button>
                            <button ng-click="panel_indicador = 5" class="btn btn-primary btn-block">Cumplimiento</button>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <div ng-show="show_all_panels || panel_indicador == 1">
                                <div class="col-sm-12"> 
                                    <h3>Representantes</h3>
                                </div>
                                <div class="col-sm-12">
                                    <h4>Ventas</h4> 
                                    <div id="NuevaGraficaVentas" style="height: 250px;"></div>
                                </div>
                                <div class="col-sm-12">
                                    <h4>Impactos</h4> 
                                    <div id="NuevaGraficaImpactos" style="height: 250px;"></div>
                                </div>
                                <div class="col-sm-12">
                                    <h4>Dropsize</h4> 
                                    <div id="NuevaGraficaDropsize" style="height: 250px;"></div>
                                </div>
                            </div>
                            <div ng-show="show_all_panels || panel_indicador == 2">
                                <div class="col-sm-12"> 
                                    <h3>Representantes</h3>
                                </div>
                                <div class="col-sm-3">
                                    <h4>Representantes</h4> 
                                    <div id="graficaRepresentantes_representantes" ></div>
                                </div>
                                <div class="col-sm-9">
                                    <h4>Distribuidoras</h4> 
                                    <div id="graficaRepresentantes_distribuidoras" ></div>
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
                                    <h3>Crecimiento Anual</h3>
                                </div>
                                <div class="col-sm-12">
                                    <h4>Ventas</h4> 
                                    <div id="NuevaGraficaCrecimientoAnualVentas" style="height: 250px;"></div>
                                </div>
                                <div class="col-sm-12">
                                    <h4>Impactos</h4> 
                                    <div id="NuevaGraficaCrecimientoAnualImpactos" style="height: 250px;"></div>
                                </div>
                                <div class="col-sm-12">
                                    <h4>Dropsize</h4> 
                                    <div id="NuevaGraficaCrecimientoAnualDropsize" style="height: 250px;"></div>
                                </div>
                            </div>
                            <div ng-show="show_all_panels || panel_indicador == 5">
                                <div class="col-sm-12"> 
                                    <h3>Crecimiento Anual</h3>
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
        <div id="modalCargando" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cargando Graficas</h4>
                    </div>
                    <div class="modal-body">
                        <img style="height: 100%; width: 100%;" src="images/loader.gif"/>
                    </div>
                </div>

            </div>
        </div>
        <!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js" crossorigin="anonymous"></script>
        <script src="https://pierresh.github.io/morris.js/js/regression.js" crossorigin="anonymous"></script>
        <script src="plugins/morris.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="plugins/morris.css">
        <script>
        var dgproducts = null;
        var dgcumplimiento = null;
        $(function(){
            $(".change_selection_children").attr("checked", "checked");
            $(".change_selection_children").on("change", function(){
                if( $(this).is(':checked') )
                {
                    $(this).parent().siblings(".filter_list").find("input:checkbox").attr("checked", "checked");
                }
                else
                {
                    $(this).parent().siblings(".filter_list").find("input:checkbox").removeAttr("checked");
                }
            });
        })
        </script>

    </body>
</html>