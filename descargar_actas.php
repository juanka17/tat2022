<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
    <script src="js/html2canvas.js" type="text/javascript"></script>
    <script src="js/addhtml.js" type="text/javascript"></script>
    <script src="js/descarga_actas.js?version=3" type="text/javascript"></script>
    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
</head>

<body ng-app="actasApp" ng-controller="actasController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Descargar Actas
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li><a href="menu_administracion.php"><i class="fa fa-user"></i> Administración</a></li>
                    <li class="active">Actas</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-sm-12 col-md-4 form-group">
                        Temporada
                        <select class="form-control" ng-model="actas.id_temporada">
                            <option value="">Filtrar por Temporada</option>
                            <option value="16">agosto por Temporada</option>
                            <option ng-repeat="operacion in temporada track by $index" value='{{operacion.id}}'>{{operacion.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 form-group">
                        Ejecutivos
                        <select class="form-control" ng-model="actas.id_ejecutivo">
                            <option value="">Filtrar por Ejecutivo</option>
                            <option ng-repeat="operacion in ejecutivo track by $index" value='{{operacion.ID}}'>{{operacion.NOMBRE}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 form-group">
                        <br />
                        <button class="btn btn-primary btn-block" ng-click="CargaActasDisponibles(actas.id_temporada, actas.id_ejecutivo);"><i class="fa fa-eye"></i>Ver Actas</button>

                    </div>
                    <div class="col-sm-12">
                        <br />
                        <button class="btn btn-primary btn-block" ng-click="GenerarPDFMasivo()" ng-show="!descargando_actas">
                            <i class="fas fa-file-pdf"></i> Generar PDF Masivo ({{lista_actas.length}} Documentos)
                        </button>
                        <h3 ng-show="descargando_actas">
                            Procesando {{lista_actas.length}} actas
                        </h3>
                    </div>
                    <div class="col-sm-12">
                        <br />
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Temporada</th>
                                    <th>Ejecutivo</th>
                                    <th>Asistente</th>
                                    <th>Distirbuidora</th>
                                    <th>Entregas</th>
                                    <th>Acta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="actas in redenciones track by $index">
                                    <td class="text-left">{{actas.temporada}}</td>
                                    <td class="text-left">{{actas.ejecutivo}}</td>
                                    <td class="text-left">{{actas.asistente}}</td>
                                    <td class="text-left">{{actas.distribuidora}}</td>
                                    <td class="text-left">{{actas.entregas}}</td>
                                    <td class="text-left">
                                        <a class="btn btn-primary btn-block" target="_blank" ng-href="{{actas.documento}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr ng-show="lista_actas.length == 0">
                                    <td colspan="6" class="text-center">
                                        <h3>No hay datos para esta temporada.</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <!-- /.content -->
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
            <!-- Control Sidebar -->
        </div>

    </div>
</body>

</html>