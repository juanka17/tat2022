<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/catalogo.js?reload=11" type="text/javascript"></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
        var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
        var id_usuario = 0;
        var id_premio = 0;
        if (typeof getParameterByName("id_usuario") !== 'undefined' && getParameterByName("id_usuario") != "") {
            id_usuario = getParameterByName("id_usuario");
        } else {
            alert("No hay usuario seleccionado.");
        }
        if (typeof getParameterByName("id_premio") !== 'undefined' && getParameterByName("id_premio") != "") {
            id_premio = getParameterByName("id_premio");
        }
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

        * {
            box-sizing: border-box;
        }

        .flex-container {
            display: flex;
            text-align: center;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .flex-item-left {
            background-color: #5A6058;
            padding: 10px;
            flex: 50%;
        }

        .flex-item-right {
            background-color: #5A6058;
            padding: 10px;
            flex: 50%;
        }

        .cate_logo {
            
            padding: 10px;
            margin: 0% 0% 10% 0%;
            height: auto;
            cursor: pointer;
            transition: transform .5s;
        }


        .cate_logo:hover {
            transform: scale(1.2);
        }

        .cate_logo_banner {
            border: 2px solid #D6D8D5;
            padding: 10px;
            margin: 2% 0% 5% 0%;
            height: auto;
            width: 100%;
            cursor: pointer;
        }

        @media (max-width: 800px) {
            .flex-container {
                grid-template-columns: repeat(16, 1fr);
                display: grid;
                overflow: auto;
                white-space: nowrap;
                grid-gap: 16px;
            }
            .cate_logo_banner {
            border: 2px solid #D6D8D5;
            padding: 10px;
            margin: 15% 0% 10% 0%;
            height: auto;
            width: 100%;
            cursor: pointer;
        }
        }
    </style>
</head>

<body ng-app="catalogoApp" ng-controller="catalogoController" class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <div class="container-fluid">
            <section class="content-header">
                <h1>
                    <b>puntos disponibles: {{datos_usuario.saldo_actual | number}}</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                </ol>
            </section>


            <section class="content">
                <div class="row" ng-init="valor_categoria = 0">

                    <div class="col-sm-12 col-md-12 text-center">
                        <h2>¡Selecciona una de nuestras categorías destacadas!</h2>
                        <div class="flex-container">
                            <div ng-repeat="categoria in categoria_premios track by $index" ng-click="MostrarBannerCategora(categoria.NOMBRE);SeleccionarPremiosVisibles(categoria.ID)">
                                <img class="cate_logo" ng-src='images/logos_catalogo/{{categoria.NOMBRE}}.png' alt="No disponible" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <div id="banner_categoria">
                        </div>
                    </div>
                    <div class="col-sm-12  col-md-6 offset-md-2 ">
                        <label>Nombre del premio</label>
                        <input class="form-control" ng-model="filtros.nombre" type="text" placeholder="Nombre premio" ng-change="SeleccionarPremiosVisibles(0)">
                    </div>
                    <div class="col-sm-12 col-md-4 text-center">
                        <i class="fa fa-shopping-cart fa-4x" style="cursor: pointer;" data-toggle="modal" data-target="#modal_carrito"></i>
                        <br>
                        Mi Carrito ({{carrito.elementos.length}})

                    </div>
                    <div class="col-sm-12 col-md-1">
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 " ng-repeat="premio in premios_visibles" ng-show="true">
                                <div class="box" style="color: #5A6058;">
                                    <div class="box-body box-profile">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4 text-center">
                                                <img class="img-responsive img-circle" ng-src='https://formasestrategicas.com.co/premios/{{premio.id_premio}}.jpg?ver=1' alt="No disponible" onError="this.src='../../images/premios/replace.png'" />
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <h2 class="text-center">{{premio.premio}}</h2>
                                                <p class="descripcion">
                                                    <b>Marca:</b> {{premio.marca}}
                                                </p>

                                            </div>
                                            <div class="col-sm-12 col-md-4">

                                                <h3 class="text-center" style="color: #5A6058;">
                                                    {{ premio.puntos == premio.puntos_actuales ? premio.puntos : premio.puntos_actuales | number}}
                                                    Puntos
                                                </h3>
                                                <div class="btn-group-vertical btn-block">
                                                    <br />
                                                    <button class="btn btn-outline-success" data-toggle="modal" data-target="#modal_detalle_premio" ng-click="MostrarPremioSeleccionado($index)" ng-disabled=" premio.puntos_actuales > saldo_disponible " ng-show="premio.id != 2646">
                                                        Ver detalle
                                                    </button>
                                                    <br>
                                                    <button ng-show="agregar == 0" class="btn btn-success" ng-click="ConfirmarPremioSeleccionado($index)">
                                                        Agregar al carrito {{$index}}
                                                    </button>
                                                    <p ng-show="agregar == 1">Agregando ...</p>
                                                    <!--<button class="btn btn-primary"
                                                ng-disabled=" premio.puntos_actuales > saldo_disponible"
                                                ng-show="usuario_en_sesion.id_rol != 3 && premio.id != 2646">
                                                <i class="fa fa-phone"></i> Llamar
                                            </button>-->

                                                    <a class="btn btn-primary" ng-disabled=" premio.puntos_actuales > saldo_disponible" ng-show="premio.id == 2646" download="images/bono_digital.png" href="images/bono_digital.png"><i class="fa fa-download"></i>
                                                        Descargar Bono</a>
                                                    </button>
                                                </div>

                                                <!-- /.box-body -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 ">

                                <div class="btn-group btn-block">
                                    <div class="col-sm-12 col-md-2">
                                        <button class="btn btn-success btn-block" ng-disabled="pagina_actual == 0" ng-click="SeleccionarPaginaListaVisible(pagina_actual - pagina_actual)">
                                            INICIO
                                        </button>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <button class="btn btn-success btn-block" ng-disabled="pagina_actual == 0" ng-click="SeleccionarPaginaListaVisible(pagina_actual - 1)">
                                            <i class="fa fa-backward"></i>
                                        </button>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <button class="btn btn-success btn-block"> {{pagina_actual + 1}} de
                                            {{cantidad_paginas + 1}} </button>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <button class="btn btn-success btn-block" ng-disabled="pagina_actual >= cantidad_paginas" ng-click="SeleccionarPaginaListaVisible(pagina_actual + 1)">
                                            <i class="fa fa-forward"></i>
                                        </button>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <button class="btn btn-success btn-block" ng-disabled="pagina_actual >= cantidad_paginas" ng-click="SeleccionarPaginaListaVisible(pagina_actual + (cantidad_paginas - pagina_actual))">
                                            FIN
                                        </button>
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

    <div id="modal_detalle_premio" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Detalle</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body text-center">
                    <img ng-src='https://formasestrategicas.com.co/premios/{{premio_seleccionado.id_premio}}.jpg' alt="No disponible" onError="this.src='images/premios/replace.png'" />
                    <h5>
                        {{premio_seleccionado.premio}}
                        <br />
                        <small>Marca: {{premio_seleccionado.marca}}</small>
                    </h5>
                    <h6>{{premio_seleccionado.puntos_actuales}} <b>Puntos</b></h6>
                    <p ng-show="premio_seleccionado.solo_call == 1">
                        estos premios sólo se pueden redimir por nuestro call center
                        en la línea 018000 413580
                    </p>
                    <p>
                        {{premio_seleccionado.descripcion}}
                    </p>
                    <p>
                        Tu bono será enviado en los próximos 5 días hábiles a la confirmación de la redención"
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_carrito" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Carrito de premios</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-outline-success btn-block" data-dismiss="modal">
                                Seleccionar más
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-success btn-block" data-dismiss="modal" ng-click="ConfirmarRedencion()">
                                Confirmar Solicitud
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <h5>
                                Bonos Seleccionados
                            </h5>
                        </div>
                        <div class="col-sm-12 col-md-4 text-right">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Puntos restantes</th>
                                        <th>Puntos gastados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{saldo_disponible | number}}</td>
                                        <td>{{carrito.puntos | number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" style="max-height: 300px; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Quitar</th>
                                        <th></th>
                                        <th>Premio</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in carrito.elementos track by $index">
                                        <td>
                                            <button class="btn btn-danger btn-sm" ng-click="QuitarDelCarrito($index)">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <img ng-src='https://formasestrategicas.com.co/premios/{{premio.id_premio}}.jpg' style="height:auto; width:30%" alt="No disponible" />
                                        </td>
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.puntos}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_confirmar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Confirmar Redenciones</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>
                                Datos usuario.
                            </h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Departamento</th>
                                        <th>Ciudad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{datos_usuario.NOMBRE}}</td>
                                        <td>{{datos_usuario.CELULAR}}</td>
                                        <td>{{datos_usuario.DEPARTAMENTO}}</td>
                                        <td>{{datos_usuario.CIUDAD}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <h3>
                                Datos envío.
                            </h3>
                            <form ng-submit="GuardarRedenciones()">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12" ng-if="datos_usuario.EMAIL == null || datos_usuario.CELULAR == null">
                                        *Debe realizar la actualización de datos.
                                    </div>
                                    <div class="col-sm-12 col-md-12" ng-if="datos_usuario.EMAIL != null && (habilitar_exito || habilitar_bono)">
                                        El bono será enviado al siguiente correo, <b>{{datos_usuario.EMAIL}}</b>
                                        <br>
                                        <input type="checkbox" name="" id="cambio_correo" ng-change="ActualizarCorreo()" ng-model="datos_envio.cambio_correo">
                                        <label for="cambio_correo">Seleccione si desea enviar el bono a otro correo</label>
                                        <br>
                                        <input type="email" name="" placeholder="Ingrese nuevo correo" class="form-control hide" id="nuevo_correo" ng-model="datos_envio.nuevo_correo">
                                    </div>
                                    <div class="col-sm-12 col-md-12" ng-if="datos_usuario.CELULAR != null && habilitar_recarga">
                                        La recarga se realizará al siguiente número de teléfono , <b>{{datos_usuario.CELULAR}}</b>
                                        <br>
                                        <input type="checkbox" name="" id="cambio_celular" ng-change="ActualizarCelular()" ng-model="datos_envio.cambio_celular">
                                        <label for="cambio_celular">Seleccione si desea enviar la recarga a otro celular</label>
                                        <br>
                                        <input type="number" name="" placeholder="Ingrese nuevo celular" class="form-control hide" id="nuevo_celular" ng-model="datos_envio.nuevo_celular">
                                        <label for="operador">Seleccione el operador</label>
                                        <select class="form-control" required id="operador" ng-model="datos_envio.operador" autocomplete="off">
                                            <option value=''>Seleccionar</option>
                                            <option value='1'>Movistar</option>
                                            <option value='2'>Tigo</option>
                                            <option value='3'>Exito</option>
                                            <option value='4'>Claro</option>
                                            <option value='5'>Virgin</option>
                                            <option value='6'>Uff</option>
                                            <option value='7'>ETB</option>
                                            <option value='8'>Avantel</option>
                                            <option value='9'>Wom</option>
                                            <option value='10'>Flash Mobile</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6" ng-if="datos_usuario.EMAIL != null || datos_usuario.CELULAR != null">
                                        <br>
                                        <button type="submit" class="btn btn-warning btn-block">
                                            <i class="fa fa-shopping-cart"></i> Continuar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-12">
                            <h5>Lista de bonos</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bono</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in carrito.elementos track by $index">
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.puntos_actuales}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="verificacion_registro" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Verificación solicitud</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" ng-if="habilitar_exito || habilitar_bono">
                            Se van a enviar los siguientes bonos al correo:
                            <br>
                            <b>{{datos_usuario.EMAIL}}</b>
                        </div>
                        <div class="col-sm-12" ng-if="habilitar_recarga">
                            Se va a realizar la recarga al siguiente numero:
                            <br>
                            <b>{{datos_usuario.CELULAR}}</b>
                        </div>
                        <div class="col-sm-12" style="max-height: 400px; overflow-y: auto;">
                            <h5>Lista de bonos</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bono</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in carrito.elementos track by $index">
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.puntos}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 col-md-6" ng-init="boton_aceptar = 0">
                            <button class="btn btn-success" ng-if="boton_aceptar == 0" ng-click="FinalizarRedencion()">ACEPTAR</button>
                            <img src="images/img_correos/load.gif" style="width: 100%;" ng-if="boton_aceptar == 1">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_resultado_registro" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Resultado Redenciones</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" ng-if="habilitar_exito">
                            <img src="images/img_correos/pop_up_exito.png" style="width: 100%;" />
                        </div>
                        <div class="col-sm-12" ng-if="habilitar_bono">
                            <img src="images/img_correos/pop_up_bonos.png" style="width: 100%;" />
                        </div>
                        <div class="col-sm-12" ng-if="habilitar_recarga || (habilitar_exito && habilitar_bono)">
                            <img src="images/img_correos/pop_up_varios.png" style="width: 100%;" />
                        </div>
                        <div class="col-sm-12" style="max-height: 400px; overflow-y: auto;" ng-show="redenciones_registrada.length > 0">


                            <button class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>