<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="es" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/redenciones.js?reload=7" type="text/javascript"></script>
    <script type="text/javascript">
    var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    var afiliado_seleccionado = <?php echo json_encode($_SESSION["afiliadoSeleccionado"]); ?>;
    var categoriaSeleccionada = -1;

    var id_almacen = 0;
    var id_afiliado = 0;
    var id_temporada = 0;
    var id_categoria = 0;
    var catalogo_perfecto = 0;
    if (
        (typeof getParameterByName("id_almacen") !== 'undefined' && getParameterByName("id_almacen") != "") &&
        (typeof getParameterByName("id_afiliado") !== 'undefined' && getParameterByName("id_afiliado") != "") &&
        (typeof getParameterByName("id_temporada") !== 'undefined' && getParameterByName("id_temporada") != "") &&
        (typeof getParameterByName("id_categoria") !== 'undefined' && getParameterByName("id_categoria") != "") &&
        (typeof getParameterByName("catalogo_perfecto") !== 'undefined' && getParameterByName("catalogo_perfecto") !=
            "")
    ) {
        id_almacen = getParameterByName("id_almacen");
        id_afiliado = getParameterByName("id_afiliado");
        id_temporada = getParameterByName("id_temporada");
        catalogo_perfecto = getParameterByName("catalogo_perfecto");
        id_categoria = getParameterByName("id_categoria");
    }

    $(document).ready(function() {
        AnimarCalle();
    });

    var carros = [{
            imagen: "transmilenio.png",
            direccion: "izquierda",
            size: "20"
        },
        {
            imagen: "camion.png",
            direccion: "derecha",
            size: "20"
        },
        {
            imagen: "bus.png",
            direccion: "izquierda",
            size: "15"
        },
        {
            imagen: "carro1.png",
            direccion: "izquierda",
            size: "10"
        },
        {
            imagen: "taxi.png",
            direccion: "derecha",
            size: "10"
        },
        {
            imagen: "carro2.png",
            direccion: "derecha",
            size: "10"
        }
    ];

    function AnimarCalle() {
        var conteo = 0;

        setInterval(function() {
            if (conteo >= carros.length) {
                conteo = 0;
            }

            $("#animacion .carros").empty();
            var src = "images/carros/" + carros[conteo].imagen;
            var clase = "carro " + carros[conteo].direccion;
            var size = carros[conteo].size + "%";
            var htmlImagen = "<img src='" + src + "' class='" + clase + "' style='width: " + size + ";' />";
            $("#animacion .carros").html(htmlImagen);

            conteo++;
        }, 5000);
    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    </script>
    <style>
    .premio {
        margin-bottom: 20px;
        background-color: #ffffff
    }

    .premio .nombrePremio {
        color: white;
        height: 25px;
        overflow: hidden;
        border: 3px solid #367fa9;
        border-radius: 20px;
        background: #ff7e06;
    }

    .premio .imagen {
        height: 125px;
        overflow: hidden;
    }

    .premio .imagen img {
        height: 100%;
    }

    .premio .btn-agregar {
        background: #ff7e06;
        border: 3px solid #367fa9;
        border-radius: 20px;
        width: 100%;
    }
    </style>
</head>

<body ng-app="redencionesApp" ng-controller="redencionesController" class="layout-top-nav"
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
                    Entregas a Solicitar
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li onclick="javascript:document.location.href = document.referrer"><i class="fa fa-home"></i>
                        Almacen</li>
                    <li class="active">Entregas</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class='row text-center'>
                    <div class="col-md-12">
                        <img src="images/quinto_bimestre_2021.png" class="img-thumbnail" />
                        <br />
                    </div>
                    <div class="col-sm-12 text-right">
                        <button ng-show="categoria_seleccionada == 0" onclick="history.back()"
                            class="btn btn-danger">Volver</button>
                        <button ng-show="categoria_seleccionada > 0" ng-click="categoria_seleccionada = 0"
                            class="btn btn-danger">Volver</button>
                        <br />
                        <br />
                    </div>
                    <div class="col-sm-12 text-right">
                        <div ng-show="catalogo_perfecto == 0">
                            <div ng-show="id_temporada == 1 && catalogo_perfecto == 0">
                                <div ng-init="categoria_seleccionada = 0" ng-show="categoria_seleccionada == 0">
                                    <div class="btn btn-primary btn-block" ng-click="categoria_seleccionada = 1">
                                        <i class="fa fa-gift"></i>
                                        <h4>Primer Bimestre</h4>
                                    </div>
                                </div>
                            </div>


                            <div ng-show="id_temporada == 2 && catalogo_perfecto == 0">
                                <div ng-init="categoria_seleccionada = 0" ng-show="categoria_seleccionada == 0">
                                    <div class="btn btn-primary btn-block" ng-click="categoria_seleccionada = 2">
                                        <i class="fa fa-gift"></i>
                                        <h4>Segundo Bimestre</h4>
                                    </div>
                                </div>
                            </div>

                            <div ng-show="id_temporada == 3 && catalogo_perfecto == 0">
                                <div ng-init="categoria_seleccionada = 0" ng-show="categoria_seleccionada == 0">
                                    <div class="btn btn-primary btn-block" ng-click="categoria_seleccionada = 3">
                                        <i class="fa fa-gift"></i>
                                        <h4>Tercer Bimestre</h4>
                                    </div>
                                </div>
                            </div>

                            <div ng-show="id_temporada == 4 && catalogo_perfecto == 0">
                                <div ng-init="categoria_seleccionada = 0" ng-show="categoria_seleccionada == 0">
                                    <div class="btn btn-primary btn-block" ng-click="categoria_seleccionada = 3">
                                        <i class="fa fa-gift"></i>
                                        <h4>Cuarto Bimestre</h4>
                                    </div>
                                </div>
                            </div>

                            <div ng-show="id_temporada == 5 && catalogo_perfecto == 0">
                                <div ng-init="categoria_seleccionada = 0" ng-show="categoria_seleccionada == 0">
                                    <div class="btn btn-primary btn-block" ng-click="categoria_seleccionada = 2">
                                        <i class="fa fa-gift"></i>
                                        <h4>Quinto Bimestre</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3 text-center" ng-repeat="premio in premios| orderBy: ORDER"
                        ng-show='(categoria_seleccionada == premio.ID_CATEGORIA && id_temporada == premio.CATALOGO) && id_categoria == premio.CATEGORIA_AFILIADO'>

                        <div class="premio">
                            <div class='nombrePremio'>
                                {{premio.NOMBRE}}
                            </div>
                            <div class="descripcion"
                                ng-show='filtros.premio_descripcion == -1 || filtros.premio_descripcion != $index'>
                                <div class="imagen">
                                    <img ng-src='http://formasestrategicas.com.co/premios/{{premio.ID}}.jpg'
                                        height='100%' alt="No disponible"
                                        onError="this.src='images/premios/replace.png'" />
                                </div>
                            </div>
                            <span class='desc' ng-show='filtros.premio_descripcion == $index'>
                                <strong>Marca: </strong>{{premio.MARCA}}
                                <br />
                                {{premio.DESCRIPCION}}
                            </span>
                            <button class='btn btn-primary btn-block btn-agregar' ng-disabled="id_almacen == 0"
                                ng-click="AgregarAlCarrito(premio.ID)" data-toggle="modal"
                                data-target="#modalConfirmaRedencion" data-backdrop="static" data-keyboard="false">
                                <span class='fa fa-shopping-basket'></span> Seleccionar
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
        </div>


    </div>
    <div class="modal fade" id="modalConfirmaRedencion" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">

        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <h3>Entregas a solicitar</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="col-md-12 text-center" ng-show="!redimiendo && !redimido">
                        <h3>
                            {{carrito.elementos[0].NOMBRE}}
                            <br />
                            <small>{{carrito.elementos[0].MARCA}}</small>
                        </h3>
                        <img ng-src='http://formasestrategicas.com.co/premios/{{carrito.elementos[0].ID}}.jpg'
                            height='100%' alt="No disponible" onError="this.src='images/premios/replace.png'" />
                        <br />
                        <h4 class="text-left">{{carrito.elementos[0].DESCRIPCION}}</h4>
                        <br />
                        <br />
                    </div>
                    <div class="text-center" ng-show="redimiendo">
                        <h2>Procesando</h2>
                        <br />
                        <img src="images/loader.gif" />
                    </div>
                    <div ng-show="redimido">
                        <h3>Solicitud realizada</h3>
                        <br />
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Entrega</th>
                                    <th class="text-right">Folio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="redencion in Redenciones track by $index">
                                    <td>{{redencion.premio}}</td>
                                    <td class="text-right">{{redencion.folio}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-6">
                        <button class="btn btn-danger" data-dismiss="modal" ng-click="LimpiarDatos()"
                            ng-disabled="redimiendo" ng-show="!redimido">
                            Cerrar
                        </button>
                        <a ng-href="modificar_almacen.php?id_almacen={{id_almacen}}" class="btn btn-danger"
                            ng-show="redimido">Cerrar</a>
                    </div>
                    <div class="col-md-6 text-right" ng-show="denegar.length == 0 || catalogo_perfecto == 2">
                        <button class="btn btn-success" ng-click="RegistrarRedencion()"
                            ng-show="!redimiendo && !redimido" ng-disabled="puntosCarrito <= 0">
                            Solicitar ahora
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>