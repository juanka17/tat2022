<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="es" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>

    <script src="js/encuestas_redencion.js" type="text/javascript"></script>
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
</head>

<body ng-app="encuestasRedencionApp" ng-controller="encuestasRedencionController" class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper" style="min-height: 556px;">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>

        <div class="container">

            <section class="content-header">
                <h1>
                    Encuesta Redencion
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Encuesta</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-sm-5">
                        <b>Premio</b>
                        <br />
                        {{redencion.premio}}
                    </div>
                    <div class="col-sm-3">
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
                        <table class="table" id="pnlEncuesta" ng-show="encuesta_redencion.length == 0">
                            <thead>
                                <tr>
                                    <th>Pregunta</th>
                                    <th>Opciones</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1. ¿Usted recibió el premio?</td>
                                    <td>
                                        <select class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>2. ¿El premio lleno sus expectativas?</td>
                                    <td>
                                        <select class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>3. ¿Cómo le pareció la calidad del premio? </td>
                                    <td>
                                        <select class="form-control">
                                            <option value="4">Excelente</option>
                                            <option value="3">Buena</option>
                                            <option value="2">Regular</option>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="text" /></td>
                                </tr>
                                <tr>
                                    <td>4. ¿El tiempo de entrega le parecio? </td>
                                    <td>
                                        <select class="form-control">
                                            <option value="4">Excelente</option>
                                            <option value="3">Buena</option>
                                            <option value="2">Regular</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>5. ¿Le cumplieron con la fecha estipulada de entrega? </td>
                                    <td>
                                        <select class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>6. ¿Cómo le pareció la atención de la empresa que le entrego el premio? </td>
                                    <td>
                                        <select class="form-control">
                                            <option value="4">Excelente</option>
                                            <option value="3">Buena</option>
                                            <option value="2">Regular</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>7. ¿Los premios del catalogo le parecen alcanzables? </td>
                                    <td>
                                        <select class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>8. ¿Le gustaría quitar/cambiar algún tipo de premio del catalogo? </td>
                                    <td>
                                        <select class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>9. ¿Qué premio le gustaría agregar al catalogo?</td>
                                    <td>
                                        <select class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <button class="btn btn-primary" ng-click="RegistrarEncuestaRedencion()">
                                            Registrar encuesta
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="small-12 cell">
                        <table class="table" ng-show="encuesta_redencion.length > 0">
                            <thead>
                                <tr>
                                    <th>Pregunta</th>
                                    <th>Opciones</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="pregunta in encuesta_redencion track by $index">
                                    <td>
                                        <span ng-show="pregunta.numero_pregunta == 1">¿Usted recibió el premio? </span>
                                        <span ng-show="pregunta.numero_pregunta == 2">¿El premio lleno sus expectativas? </span>
                                        <span ng-show="pregunta.numero_pregunta == 3">¿Cómo le pareció la calidad del premio? </span>
                                        <span ng-show="pregunta.numero_pregunta == 4">¿El tiempo de entrega le parecio? </span>
                                        <span ng-show="pregunta.numero_pregunta == 5">¿Le cumplieron con la fecha estipulada de entrega? </span>
                                        <span ng-show="pregunta.numero_pregunta == 6">¿Cómo le pareció la atención de la empresa que le entrego el premio? </span>
                                        <span ng-show="pregunta.numero_pregunta == 7">¿Los premios del catalogo le parecen alcanzables? </span>
                                        <span ng-show="pregunta.numero_pregunta == 8">¿Le gustaría quitar/cambiar algún tipo de premio del catalogo? </span>
                                        <span ng-show="pregunta.numero_pregunta == 9">¿Qué premio le gustaría agregar al catalogo?</span>
                                    </td>
                                    <td>
                                        <span ng-show="pregunta.respuesta == 0">No</span>
                                        <span ng-show="pregunta.respuesta == 1">Si</span>
                                        <span ng-show="pregunta.respuesta == 2">Regular</span>
                                        <span ng-show="pregunta.respuesta == 3">Buena</span>
                                        <span ng-show="pregunta.respuesta == 4">Excelente</span>
                                    </td>
                                    <td>
                                        {{pregunta.comentario}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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