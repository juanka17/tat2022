<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>

    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/llamadas_masivo.js?cant=tell&if=is_true&ver=1" type="text/javascript"></script>
    <script src="js/app.js"></script>
    <script>
    var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
    <script>
    $(function() {
        $("#fecha_llamada").datepicker();
        $("#fecha_llamada").datepicker("option", "dateFormat", 'yy-mm-dd');
    });
    </script>
    <style>
    .btn-primary {
        background-color: #e00900;
        border-color: #e00900;
        color: black;
    }
    </style>
</head>

<body ng-app="llamadasmasivoApp" ng-controller="llamadasmasivoController" class="layout-top-nav"
    style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h4>
                    LLamadas Masivo
                </h4>

            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <form ng-submit="SumarioCedulasMasivos()">

                            <div class="col-lg-12 col-md-12 ">
                                <label>
                                    <a ng-repeat="item in anteriores track by $index">{{item}} - </a>
                                    <span class="fa fa-remove" ng-show="anteriores.length > 0" aria-hidden="true"
                                        ng-click="ObtenerSubcategorias(-1)"></span>
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-2">
                                    <label class="form-group" ng-show='subCategorias.length > 0'>
                                        Categoria llamada
                                        <select class="form-control " id='categoriaLlamada' ng-model='subCategoria'
                                            type="text" ng-change='ObtenerSubcategorias(subCategoria)' required>
                                            <option value='0' ng-selected="true">Seleccionar</option>
                                            <option ng-repeat="item in subCategorias track by item.ID"
                                                value='{{item.ID}}'>
                                                {{item.NOMBRE}}
                                            </option>
                                        </select>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-5">
                                    <label> Comentario </label>
                                    <input class="form-control" type="text" placeholder="Ingrese comentario"
                                        ng-model="comentario" required>
                                </div>
                                <div class="col-sm-12 col-md-5">
                                    <div class="row">
                                        <label> Fecha llamada</label>
                                        <input class="form-control" type="text" ng-model="fecha_llamada"
                                            id="fecha_llamada" name="" ng-change="FechaCargue()" placeholder="MM-DD-AAAA" required>
                                           
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12  col-md-2">
                                    <small>
                                        <b>Cantidad de Usuarios:</b> {{informacion_usuarios.cantidad}}
                                        <br />
                                        <b>Usuarios correctos:</b> {{informacion_usuarios.cedulas_correctas}}
                                        <br />
                                        <b>Usuarios incorrectos:</b>
                                        {{informacion_usuarios.cantidad - informacion_usuarios.cedulas_correctas}}
                                        <br />
                                    </small>
                                </div>
                                <div class="col-sm-12 col-md-5">
                                    <button class="btn btn-success btn-sm" type="button" ng-click="LimpiarTodo()"
                                        style="margin-top:20px; width: 496px;"> Limpiar</button>
                                </div>
                                <div class="col-sm-12 col-md-5">
                                    <button class="btn btn-success btn-sm" style="margin-top: 20px; width: 503px;" type="submit">
                                        Cargar LLamadas
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br />
                                <textarea ng-model="informacion_usuarios.listado" rows="10" style="width: 100%;"
                                    required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
        </footer>
        <?php include 'componentes/coponentes_js.php'; ?>
    </div>
</body>

</html>