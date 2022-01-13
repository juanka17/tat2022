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

<body ng-app="afiliadosApp" ng-controller="afiliadosController" class="wrapper layout-top-nav"
    style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper" style="min-height: 556px;">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <div class="container-fluid">
            <section class="content-header">
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-10 offset-lg-1 text-center">
                        <div class="col-sm-12 col-md-3">
                            <div class="col-sm-12 col-md-12 form-group">
                                <label>Nombre del premio</label>
                                <input class="form-control" ng-model="filtros.nombre" type="text"
                                    placeholder="Nombre premio" ng-change="SeleccionarPremiosVisibles()">
                            </div>
                            <div class="col-sm-12 col-md-12 form-group">
                                <label> Categoria</label>
                                <select class="form-control" size="12" ng-model="filtros.id_categoria"
                                    ng-change="SeleccionarPremiosVisibles()">
                                    <option class="btn btn-warning btn-block" value="0">TODOS LOS PREMIOS</option>
                                    <option class="btn btn-warning btn-block" value="10">NAVIDAD</option>
                                    <option class="btn btn-warning btn-block"
                                        ng-repeat="categoria in categoria_premios track by $index"
                                        ng-value="categoria.id" ng-if=" categoria.id!=10 && categoria.id != 14"
                                        ng-show="usuario_en_sesion.id_rol==10">
                                        {{categoria.nombre}}
                                    </option>
                                    <option class="btn btn-warning btn-block"
                                        ng-repeat="categoria in categoria_premios track by $index"
                                        ng-value="categoria.id" ng-if=" categoria.id!=10"
                                        ng-show="usuario_en_sesion.id_rol!=10">
                                        {{categoria.nombre}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-12 form-group">
                                <br />
                                <button class="btn btn-warning btn-block" ng-click="ObtenerPremios(1);"><i
                                        class="fa fa-arrow-up"></i> Ordenar Menor a Mayor</button>
                            </div>
                            <div class="col-sm-12 col-md-12 form-group">

                                <button class="btn btn-warning btn-block" ng-click="ObtenerPremios(2);"><i
                                        class="fa fa-star"></i> Más Redimidos</button>
                            </div>
                            <div class="col-sm-12 col-md-12 form-group">

                                <button class="btn btn-warning btn-block" ng-click="ObtenerPremios(3);"><i
                                        class="fa fa-shopping-cart"></i> Puedes Redimir Hoy</button>
                            </div>
                            <div class="col-sm-12 col-md-12 form-group">

                                <button class="btn btn-warning btn-block" ng-click="ObtenerPremios(4);"><i
                                        class="fa fa-child"></i> Si Te Esfuerzas</button>
                            </div>
                        </div>
                        <br />
                        <div class="col-sm-12 col-md-9">
                            <div class="col-sm-12">
                                <br />
                                <button class="btn btn-block" style="background-color: black; color: white;"
                                    data-toggle="modal" data-target="#modal_carrito">
                                    <i class="fa fa-shopping-basket"></i> Ver Carrito ({{carrito.elementos.length}})
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-4 " ng-repeat="premio in premios_visibles track by $index"
                                ng-show="usuario_en_sesion.id_rol == 2 || (usuario_en_sesion.id_rol == 10 && premio.puntos > 0) ">
                                <br />
                                <div class="box box-primary">
                                    <div class="box-body box-profile">
                                        <br />
                                        <img class="profile-user-img img-responsive img-circle"
                                            ng-src='https://formasestrategicas.com.co/premios/{{premio.id_premio}}.jpg'
                                            alt="No disponible" onError="this.src='../../images/premios/replace.png'" />
                                        <br />
                                        <h6 class="text-center">{{premio.premio}}</h6>
                                        <h3 class="text-center">
                                            {{ premio.puntos == premio.puntos_actuales ? premio.puntos : premio.puntos_actuales | number}}
                                            <span ng-show="premio.puntos != premio.puntos_actuales"
                                                style="color: red; text-decoration: line-through;">({{premio.puntos}})</span>
                                            Puntos
                                        </h3>
                                        <div class="btn-group-vertical btn-block"
                                            ng-show="usuario_en_sesion.id_rol != 3">
                                            <br />
                                            <button class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal_detalle_premio" ng-click="SeleccionarPremio($index)"
                                                ng-disabled=" premio.puntos_actuales > saldo_disponible"
                                                ng-show="premio.solo_call == 0 || usuario_en_sesion.id_rol == 2">
                                                <i class="fa fa-star"></i> Seleccionar
                                            </button>
                                            <button class="btn btn-warning" ng-disabled="true"
                                                ng-show="premio.solo_call == 1 && usuario_en_sesion.id_rol != 2">
                                                <i class="fa fa-phone"></i> Llamar
                                            </button>
                                        </div>
                                        <div class="descripcion">
                                            <p>
                                                <b>Marca:</b> {{premio.marca}}
                                                <br />
                                                {{premio.descripcion}}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 ">

                            <div class="btn-group btn-block">
                                <div class="col-sm-12 col-md-2">
                                    <button class="btn btn-warning btn-block" ng-disabled="pagina_actual == 0"
                                        ng-click="SeleccionarPaginaListaVisible(pagina_actual - pagina_actual)">
                                        INICIO
                                    </button>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <button class="btn btn-warning btn-block" ng-disabled="pagina_actual == 0"
                                        ng-click="SeleccionarPaginaListaVisible(pagina_actual - 1)">
                                        <i class="fa fa-backward"></i>
                                    </button>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <button class="btn btn-warning btn-block"> {{pagina_actual + 1}} de
                                        {{cantidad_paginas + 1}} </button>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <button class="btn btn-warning btn-block"
                                        ng-disabled="pagina_actual >= cantidad_paginas"
                                        ng-click="SeleccionarPaginaListaVisible(pagina_actual + 1)">
                                        <i class="fa fa-forward"></i>
                                    </button>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <button class="btn btn-warning btn-block"
                                        ng-disabled="pagina_actual >= cantidad_paginas"
                                        ng-click="SeleccionarPaginaListaVisible(pagina_actual + (cantidad_paginas - pagina_actual))">
                                        FIN
                                    </button>
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