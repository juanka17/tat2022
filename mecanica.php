<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/mecanica.js" type="text/javascript"></script>
    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
    <style>
        .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
            height: auto;
        }
    </style>
</head>

<body ng-app="mecanicaApp" ng-controller="mecanicaController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Mecánica
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Mecanica</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row" ng-init="seleccion = 1">
                    <div class="col-md-3 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 1;">
                            Mecánica General
                        </button>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 2">
                            Parámetros
                        </button>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 4">
                            Cronograma
                        </button>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 5">
                            Cronograma Interno
                        </button>
                    </div>
                    <div class="col-sm-12 col-md-8 offset-md-2 " ng-show="seleccion == 1">
                        <br />
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h4>¿Cómo Ganas?</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%" src="images/mecanica/como_ganas_2021.jpg" class="img-responsive" />
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h4>¿Cómo ganan los supervisores?</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%" src="images/mecanica/como_ganan_supervisores_2021.jpg" class="img-responsive" />
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h4>Bonus pack</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%" src="images/mecanica/bonus_pack_2021.jpg" class="img-responsive" />
                            </div>
                            <!-- /.box-body -->
                        </div>

                        <br />
                    </div>

                    <div class="col-sm-12 col-md-8 offset-md-2 text-center" ng-show="seleccion == 2">
                        <br />
                        <div class="box box_primary">
                            <div class="box-header with-border">
                                <h4>Clasificaciones</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%" src="images/mecanica/clasificaciones_2021.jpg" class="img-responsive" />
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="box">
                            <div class="box-header with-border">
                                <h4>Ascensos y Desensos</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%" src="images/mecanica/ascensos_2021.jpg" class="img-responsive" />
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center" ng-show="seleccion == 4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4>Cronograma</h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <img style="height: 100%; width: 100%" src="images/mecanica/cronograma_2021_1.jpg" class="img-responsive" />
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
                <div class="col-sm-12 text-center" ng-show="seleccion == 5">
                    <h3>Cronograma Interno Ejecutivos</h3>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Cronograma</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">

                                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item">
                                                <img src="images/cronograma/portada_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/paso_paso_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/enero_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/febrero_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/marzo_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/abril_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/mayo_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/junio_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/julio_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/agosto_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item active">
                                                <img src="images/cronograma/septiembre_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/octubre_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/noviembre_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/diciembre_1.jpg" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="images/cronograma/enero_22.jpg" class="d-block w-100" alt="...">
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Cronograma</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body text-left">
                                    <img src="images/cronograma/contenido_1.jpg" style="width: 100%; " alt="Third slide">
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
        </div>
    </div>

</body>

</html>