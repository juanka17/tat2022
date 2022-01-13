<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?> 
        <script src="js/simulador.js?reload=3" type="text/javascript"></script>

        <script type="text/javascript">
            var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
            $(document).ready(function () {
                 $('.money').mask('000,000,000,000.00', {reverse: true});
            });
        </script>
        <style>
            .simulador
            {
                border: 3px solid #c490be;
                border-radius: 20px;
                box-shadow: 10px 10px 10px 5px #c490be;
                margin-top: 20px;
            }
        </style>
    </head>

    <body ng-app="simuladorApp" ng-controller="simuladorController" class="wrapper skin-blue layout-top-nav" style="height: auto; min-height: 100%;">
        <?php include 'componentes/mostrar_imagen.php'; ?>        
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <?php include 'componentes/menu.php'; ?>
                    <div class="navbar-custom-menu">
                        <?php include 'componentes/controles_superiores.php'; ?>
                    </div>
                </div>
            </nav>
        </header>
        <div class="content-wrapper" style="min-height: 556px;">
            <div class="container">
                <section class="content-header">
                    <h1>
                        Simulador
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Simulador</a></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-ms-12 text-left">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label>Venta:</label>
                                    <input class="form-control" id="money"  placeholder="Ingrese la venta" type='number' ng-model="simulador.venta" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label>Cuota:</label>
                                    <input class="form-control" placeholder="Ingrese la cuota" type='number' ng-model="simulador.cuota" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label>Impactos:</label>
                                    <input class="form-control" placeholder="Ingrese los impactos" type='number' ng-model="simulador.impactos" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-primary btn-block" ng-click="Simular();">Realizar simulación</button>
                            </div>
                            <div ng-init="mostrar = 0">
                                <div class="text-center" ng-show="mostrar == 1">
                                    <div class="col-sm-12 col-md-5 simulador">
                                        <h2>Puntos otorgados por venta</h2>
                                        <h3>{{puntos_venta| number:0}}</h3>
                                    </div>                                    
                                    <div class="col-sm-12 col-md-offset-2 col-md-5 simulador">
                                        <h2>Puntos otorgados por impactos</h2>
                                        <h3>{{puntos_impactos| number:0}}</h3>
                                    </div>
                                    <div class="col-sm-12 simulador">
                                        <h2>Clasificación</h2>
                                        <h3>{{mensaje}}</h3>
                                    </div>
                                    <div class="col-sm-12 simulador">
                                        <h2>Puntos totales</h2>
                                        <h3>{{puntos_totales| number:0}}</h3>
                                    </div>
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
    </body>
</html>