<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/catalogo.js?reload=1" type="text/javascript"></script>
    <script type="text/javascript">
    var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>

    <style>
    .descripcion {
        overflow: hidden;
        height: auto;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .box-body {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding: 10px;
        height: 420px;
    }

    .profile-user-img {
        margin: 0 auto;
        width: 100px;
        padding: 3px;
        border: 3px solid #d2d6de;
    }

    .img-circle {
        border-radius: 50%;
    }
    </style>
</head>

<body ng-app="catalogoApp" ng-controller="catalogoController" class="wrapper layout-top-nav"
    style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <div class="container-fluid">
            <section class="content-header">
                <h1>
                    Catálogo<small>puntos disponibles: {{datos_usuario.saldo_actual | number}}</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
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
                                <option class="btn btn-warning btn-block"
                                    ng-repeat="categoria in categoria_premios track by $index" ng-value="categoria.id">
                                    {{categoria.NOMBRE}}
                                </option>
                            </select>
                        </div>
                        <!--<div class="col-sm-12 col-md-12 form-group">
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
                        </div>-->
                    </div>
                    <div class="col-sm-12 col-md-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <br />
                                <button class="btn btn-block" style="background-color: black; color: white;"
                                    data-toggle="modal" data-target="#modal_carrito">
                                    <i class="fa fa-shopping-basket"></i> Ver Carrito ({{carrito.elementos.length}})
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4 " ng-repeat="premio in premios_visibles" ng-show="true">
                                <div class="box box-danger">
                                    <div class="box-body box-profile">
                                        <br />
                                        <div style="text-align: center;">
                                            <img class="profile-user-img img-responsive img-circle" style="align:center"
                                                ng-src='https://formasestrategicas.com.co/premios/{{premio.id}}.jpg'
                                                alt="No disponible"
                                                onError="this.src='../../images/premios/replace.png'" />
                                        </div>
                                        <br />
                                        <h6 class="text-center">{{premio.nombre}}</h6>
                                        <h3 class="text-center">
                                            {{ premio.puntos == premio.puntos_actuales ? premio.puntos : premio.puntos_actuales | number}}
                                            <span ng-show="true">({{premio.puntos}})</span>
                                            Puntos
                                        </h3>
                                        <div class="btn-group-vertical btn-block">
                                            <br />
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal_detalle_premio" ng-click="SeleccionarPremio($index)"
                                                ng-disabled=" premio.puntos_actuales > saldo_disponible"
                                                ng-show="usuario_en_sesion.id_rol == 3">
                                                <i class="fa fa-star"></i> Seleccionar
                                            </button>
                                            <button class="btn btn-primary"
                                                ng-disabled=" premio.puntos_actuales > saldo_disponible"
                                                ng-show="usuario_en_sesion.id_rol != 3 && premio.id != 2646">
                                                <i class="fa fa-phone"></i> Llamar
                                            </button>

                                            <a class="btn btn-primary"
                                                ng-disabled=" premio.puntos_actuales > saldo_disponible"
                                                ng-show="premio.id == 2646" download="images/bono_digital.png"
                                                href="images/bono_digital.png"><i class="fa fa-download"></i>
                                                Descargar Bono</a>
                                        </div>
                                        <p class="descripcion">
                                            <b>Marca:</b> {{premio.marca}}
                                            <br />
                                            {{premio.descripcion}}
                                        </p>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
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
            </section>
        </div>
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
            <?php include 'componentes/coponentes_js.php'; ?>
        </footer>
    </div>

    <div id="modal_detalle_premio" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Agregar al carrito</h2>
                </div>
                <div class="modal-body text-center">
                    <img ng-src='https://formasestrategicas.com.co/premios/{{premio_seleccionado.id_premio}}.jpg'
                        alt="No disponible" onError="this.src='images/premios/replace.png'" />
                    <h5>
                        {{premio_seleccionado.premio}}
                        <br />
                        <small>Marca: {{premio_seleccionado.marca}}</small>
                    </h5>
                    <h6>{{premio_seleccionado.puntos_actuales}} Puntos</h6>
                    <p ng-show="premio_seleccionado.solo_call == 1">
                        estos premios sólo se pueden redimir por nuestro call center
                        en la línea 018000 413580
                    </p>
                    <p ng-show="premio_seleccionado.solo_call == 0">
                        {{premio_seleccionado.descripcion}}
                    </p>
                    <button class="btn btn-warning" data-dismiss="modal" data-toggle="modal"
                        data-target="#modal_carrito"
                        ng-show=" premio_seleccionado.solo_call == 0 || usuario_en_sesion.id_rol == 2"
                        ng-click="AgregarAlCarrito()">
                        <i class="fa fa-shopping-basket"></i> Agregar al Carrito
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_carrito" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Carrito de premios</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-warning btn-block" data-dismiss="modal">
                                <i class="fa fa-eye"></i> Seleccionar más
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-warning btn-block" data-dismiss="modal" data-toggle="modal"
                                data-target="#modal_confirmar">
                                <i class="fa fa-shopping-bag"></i> Confirmar Redencion
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <h3>
                                Premios Seleccionados
                            </h3>
                        </div>
                        <div class="col-sm-12 col-md-8 text-center">
                            <h5>
                                Si desea especificar una dirección de envio diferente,
                                en el siguiente paso una vez confirme la redención, lo podrá hacer.
                            </h5>
                        </div>
                        <div class="col-sm-12 col-md-4 text-right">
                            <h3>
                                {{saldo_disponible| number}}
                                <small>
                                    Puntos restantes
                                </small>
                            </h3>
                        </div>
                        <div class="col-sm-12" style="max-height: 300px; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Premio</th>
                                        <th>Puntos</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in carrito.elementos track by $index">
                                        <td>
                                            <button class="btn btn-danger btn-sm" ng-click="QuitarDelCarrito($index)">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                        </td>
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.puntos_actuales}}</td>
                                        <td>
                                            <input type="text" ng-model="premio.comentario" maxlength="500"
                                                placeholder="Comentarios" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h2 class="modal-title">Carrito de premios</h2>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_confirmar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Confirmar Redenciones</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-warning btn-block" data-dismiss="modal">
                                <i class="fa fa-eye"></i> Seleccionar más
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <button class="btn btn-warning btn-block"
                                ng-disabled="datos_usuario.direccion == '' || datos_usuario.telefono == '' || datos_usuario.ciudad == ''"
                                data-toggle="modal" data-target="#modal_resultado_registro"
                                ng-click="GuardarRedenciones()">
                                <i class="fa fa-shopping-bag"></i> Registrar redenciones
                            </button>
                        </div>
                        <div class="col-sm-12"
                            ng-show="datos_usuario.direccion == '' || datos_usuario.telefono == '' || datos_usuario.ciudad == ''">
                            Debe actualizar sus datos para proceder con la redención
                        </div>
                        <div class="col-sm-12">
                            <h3>
                                Información Envio
                                <br />
                                <small>
                                    Si desea especificar una dirección de envio diferente,
                                    hacer click en el botón de editar para cambiar dirección y ciudad.
                                </small>
                            </h3>
                            <table class="table" ng-init="editar_direccion = false">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Ciudad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{datos_usuario.nombre}}</td>
                                        <td>{{datos_usuario.telefono}}</td>
                                        <td>
                                            <span ng-show="!editar_direccion">{{direccion}}</span>
                                            <label ng-show="editar_direccion">
                                                <input type="text" maxlength="50" ng-model="datos_usuario.direccion" />
                                            </label>
                                        </td>
                                        <td ng-show="!editar_direccion">
                                            {{datos_usuario.ciudad_departamento}}
                                        </td>
                                        <td ng-show="editar_direccion">
                                            <label ng-show="ciudades.length == 0">
                                                <input type="text" placeholder="Nombre de la ciudad"
                                                    ng-blur="BuscarCiudad()" ng-model="nombre_ciudad" />
                                            </label>
                                            <label ng-show="ciudades.length > 0">
                                                <select id="dd_ciudad_usuario" ng-model='nombre_ciudad'">
                                                    <option ng-repeat=" ciudad in ciudades track by $index"
                                                    value='{{ciudad.nombre}}'>{{ciudad.nombre}}</option>
                                                </select>
                                            </label>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" ng-show="!editar_direccion"
                                                ng-click="editar_direccion = true;"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" style="max-height: 200px; overflow-y: auto;">
                            <h5>Lista de premios</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Premio</th>
                                        <th>Puntos</th>
                                        <th>Comentario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in carrito.elementos track by $index">
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.puntos_actuales}}</td>
                                        <td>{{premio.comentario}}</td>
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
    <div id="modal_resultado_registro" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Resultado Redenciones</h2>
                    <h4 class="modal-title">Tu redención ha sido exitosa!!</h4>
                    <h5 class="modal-title"> Cuentas con 20 días hábiles para recibir tu premio.</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" ng-show="redenciones_registrada.length == null">
                            <img src="../../images/loader.gif" style="width: 100%;" />
                        </div>
                        <div class="col-sm-12" style="max-height: 400px; overflow-y: auto;"
                            ng-show="redenciones_registrada.length > 0">
                            <h5>Lista de premios</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Premio</th>
                                        <th>Puntos</th>
                                        <th>Comentario</th>
                                        <th>Folio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in redenciones_registrada track by $index"
                                        ng-show="premio.error == ''">
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.puntos}}</td>
                                        <td>{{premio.comentario}}</td>
                                        <td>{{premio.folio}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!--<h5>Errores</h5>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Premio</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="premio in redenciones_registrada track by $index"
                                        ng-show="premio.error != ''">
                                        <td>{{premio.premio}}</td>
                                        <td>{{premio.error}}</td>
                                    </tr>
                                </tbody>
                            </table>-->
                            <button class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-sm-12 text-center" ng-show="redenciones_registrada.length == 0">
                            <p>
                                Ocurrió un error en el registro de las redenciones
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>