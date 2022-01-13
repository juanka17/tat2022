<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/reportes.js?version=1" type="text/javascript"></script>
    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
        $(document).ready(function() {
            $("#pnlPreview").show();
            $("#pnlLoad").hide();
            $("#btnExportar").on("click", function() {
                var csv = JSON2CSV(dataReporte);
                var downloadLink = document.createElement("a");
                var blob = new Blob(["\ufeff", csv]);
                var url = URL.createObjectURL(blob);
                var today = new Date();
                var fecha = today.getFullYear() + "" + (today.getMonth() + 1) + "" + today.getDate();
                downloadLink.href = url;
                downloadLink.download = "Reporte" + reporteNombre + "_" + fecha + ".csv";
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            });
        });

        function JSON2CSV(objArray) {
            console.log(array);
            var array = objArray;
            var str = '';
            var line = '';
            if (true) {
                var head = array[0];
                $.each(array.header, function(index, cell) {
                    line += cell + ';';
                });
                line = line.slice(0, -1);
                str += line + '\r\n';
            }

            for (var i = 0; i < array.data.length; i++) {
                var line = '';
                for (var index in array.data[i]) {
                    line += array.data[i][index] + ';';
                }

                line = line.replace(/\r?\n/g, "");
                line = line.slice(0, -1);
                str += line + '\r\n';
            }
            return str;
        }
    </script>
</head>

<body ng-app="reportesApp" ng-controller="reportesController" class="layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Reportes
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Reportes</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-sm-12 form-group">
                        <label>Reporte:</label>
                        <select class="form-control" ng-model='reportes.id' ng-change="BuscarReporte(reportes.id)">
                            <option ng-repeat="reporte in reportes track by $index" value='{{reporte.id}}'>{{reporte.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12 form-group">
                        <label>Sub Reporte</label>
                        <select class="form-control" id="ddReportes">
                            <option ng-repeat="subreporte in subreportes track by $index" value='{{subreporte.valor}}'>{{subreporte.nombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row" ng-show="(datos_usuario.id_clasificacion == 2 || datos_usuario.id_clasificacion == 5) && reporte_cargado">
                    <div class="col-md-6 col-sm-12 form-group">
                        <label>Ejecutivo</label>
                        <select class="form-control" ng-model="index_ejecutivo_seleccionado" ng-disabled="index_ejecutivo_seleccionado >= 0" ng-change="FiltrarEjecutivo()">
                            <option value="-1">Filtrar por ejecutivo</option>
                            <option ng-repeat="ejecutivo in ejecutivos track by $index" ng-value='$index'>{{ejecutivo.ejecutivo}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12 form-group" ng-show="index_ejecutivo_seleccionado >= 0">
                        <label>Distribuidora</label>
                        <select class="form-control" ng-model="index_distribuidora_seleccionado" ng-change="FiltrarDistribuidora()">
                            <option value="-1">Filtrar por distribuidora</option>
                            <option ng-repeat="distribuidora in ejecutivo_seleccionado.distribuidoras track by $index" ng-value='$index'>{{distribuidora.distribuidora}}</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-12 form-group" ng-show="index_ejecutivo_seleccionado >= 0">
                        <br />
                        <button class="btn btn-primary" ng-click="CambiarEjecutivo()">Cambiar Ejecutivo</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <br />
                    <button class="btn btn-primary" id="btnPrevisualizar" ng-click="CargarReporte()">Previsualizar</button>
                    <a class="btn btn-success" id="btnExportar">Exportar a excel</a>
                </div>
                <div class="col-md-12" style="overflow: auto; height: 300px;" id='pnlLoad'>
                    <img src="images/loader.gif" />
                </div>
                <div class="col-sm-12 table table-scroll">
                    <br />
                    <div id="contenedorTabla" style="overflow: auto; height: 400px; width: 1150px"></div>
                </div>

        </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <?php include 'componentes/footer.php'; ?>
    </footer>
    <?php include 'componentes/coponentes_js.php'; ?>

    </div>
</body>

</html>