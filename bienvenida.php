<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/afiliados.js?reload=1" type="text/javascript"></script>
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

        .box-body-1 {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
            height: auto;
            margin-bottom: 18px;
        }

        .banner {
            border: 3px solid #000000;
            border-radius: 10px;
            border-color: #ff5e09;
            background: #ff7e06;
        }

        #cronograma {
            width: 100%;
        }

        #tienda_perfecta {
            height: 87px;
        }

        @media screen and (min-width: 40em) and (max-width: 63.9375em) {
            .box-body {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                border-bottom-right-radius: 3px;
                border-bottom-left-radius: 3px;
                padding: 10px;
                height: auto;
                margin-bottom: 18px;
            }

            .box-body-1 {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                border-bottom-right-radius: 3px;
                border-bottom-left-radius: 3px;
                height: auto;
                margin-bottom: 18px;
                width: 693px;
            }

            #tienda_perfecta {
                height: 90%;
            }
        }

        @media screen and (max-width: 39.9375em) {
            .box-body {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                border-bottom-right-radius: 3px;
                border-bottom-left-radius: 3px;
                padding: 10px;
                height: auto;
                margin-bottom: 18px;
            }

            .box-body-1 {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                border-bottom-right-radius: 3px;
                border-bottom-left-radius: 3px;
                padding: 2% 0% 3% 4%;
                height: auto;
                margin-bottom: 18px;
                width: 100%;
            }

            #tienda_perfecta {
                height: 20px;
            }
        }
    </style>
</head>

<body ng-app="afiliadosApp" ng-controller="afiliadosController" class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper" style="min-height: 556px;">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <div class="container-fluid">
            <section class="content-header">
                <h1>
                    Inicio
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-10 offset-lg-1 text-center">
                        <div class="box-body banner">
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="images/banner/banner.png" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item ">
                                        <img src="images/banner/5q_diamante.png" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item ">
                                        <img src="images/banner/5q_oro.png" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item ">
                                        <img src="images/banner/5q_plata.png" class="d-block w-100" alt="...">
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
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <br>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-6">
                                    <div class="box-body-1 banner text-center">
                                        <a href="almacenes.php">
                                            <img id="cronograma" src="images/icono-distribuidores.png">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6">
                                    <div class="box-body-1 banner text-center">
                                        <a href="mecanica.php">
                                            <img id="cronograma" src="images/icono-mecanica.png">
                                        </a>
                                    </div>
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
    </div>
</body>

</html>