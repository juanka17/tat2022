<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="es" dir="ltr">

<head>


    <?php include 'componentes/componentes_basicos.php'; ?>

    <script src="js/estados_redencion.js?cant=tell&if=is_true&ver=2" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="style_estados_redenciones.css?ver=1" />
    <script src="js/app.js"></script>
    <script>
        var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
        var id_redencion = 0;
        if (typeof getParameterByName("id_redencion") !== 'undefined' && getParameterByName("id_redencion") != "") {
            id_redencion = getParameterByName("id_redencion");
        } else {
            alert("Redención no disponible.");
        }
    </script>
    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: 7%;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
    </style>
</head>

<body ng-app="estadosRedencionApp" ng-controller="estadosRedencionController" class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper" style="min-height: 556px;">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>

        <div class="container">
            <section class="content-header">
                <h1>
                    Seguimiento premios
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Redenciones</li>
                </ol>
            </section>

            <section class="">
                <div class="row">
                    <div class="col-sm-4">
                        <b>Premio</b>
                        <br />
                        {{redencion.premio}}
                    </div>
                    <div class="col-sm-2">
                        <b>Folio</b>
                        <br />
                        {{redencion.folio}}
                    </div>
                    <div class="col-sm-2">
                        <b>Fecha Redencion</b>
                        <br />
                        {{redencion.fecha_redencion}}
                    </div>
                    <div class="col-sm-2">
                        <b>Puntos</b>
                        <br />
                        {{redencion.puntos}}
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>
                    </div>
                    <div class="col-sm-12">
                        <br />
                        <h4>Estados de la redencion</h4>
                    </div>
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Operación</th>
                                    <th>Comentario</th>
                                    <th>Correo Envio</th>
                                    <th>Numero Envio</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="seguimiento in seguimiento_redencion track by $index">
                                    <td>{{seguimiento.operacion}}</td>
                                    <td>{{seguimiento.comentario}}</td>
                                    <td>{{seguimiento.correo_envio}}</td>
                                    <td>{{seguimiento.numero_envio}}</td>
                                    <td>{{seguimiento.fecha_operacion}}</td>
                                    <td ng-show="seguimiento.operacion == 'Entregado'">
                                        <a class="btn btn-primary" ng-href="../encuesta_premio/encuesta_premio.php?id_redencion={{redencion.folio}}">
                                            Encuesta
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" ng-show="redencion.operacion != 'Encuestado'">
                    <div class="col-sm-4 form-group">
                        <label>Nuevo estado</label>
                        <select class="form-control" ng-model='nuevo_estado.id_operacion'>
                            <option ng-repeat="operacion in operaciones_redencion track by $index" value='{{operacion.id}}'>{{operacion.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label>Comentario</label>
                        <input class="form-control" type="text" placeholder="Comentario" ng-model="nuevo_estado.comentario">
                    </div>
                    <div class="col-sm-4">
                        <br />
                        <button class="btn btn-primary" ng-click="RegistrarSeguimiento()">Registrar
                            Seguimiento</button>
                    </div>
                </div>
            </section>
        </div>
        <footer class=" main-footer">
            <?php include 'componentes/footer.php'; ?>
            <?php include 'componentes/coponentes_js.php'; ?>
        </footer>
    </div>

</body>

</html>