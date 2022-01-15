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

<body ng-app="mecanicaApp" ng-controller="mecanicaController" class="layout-top-nav"
    style="height: auto; min-height: 100%;">
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
                    <div class="col-md-4 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 1;">
                            Mecánica Vendedores
                        </button>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 2;">
                            Mecánica Supervisores
                        </button>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <button class="btn btn-primary btn-block" ng-click="seleccion = 3;">
                            Mecánica Informatico
                        </button>
                    </div>

                    <div class="col-sm-12 col-md-8 offset-md-2 " ng-show="seleccion == 1">
                        <br />
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h4>¿Cómo Ganas?</h4>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%"
                                    src="images/mecanica/como-participan-vendedores.jpg" class="img-responsive" />
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
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img style="height: 100%; width: 100%"
                                    src="images/mecanica/como-participan-supervisores.jpg" class="img-responsive" />
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center" ng-show="seleccion == 3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4>Cronograma</h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <img style="height: 100%; width: 100%" src="images/mecanica/como-participan-informatico.jpg"
                                class="img-responsive" />
                        </div>
                        <!-- /.box-body -->
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