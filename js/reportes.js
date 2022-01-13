angular.module('reportesApp', []).controller('reportesController', function($scope, $http) {

    $scope.usuario = { documento: "", clave: "" };

    $scope.ObtenerReportes = function() {
        var parametros = { catalogo: "reportes" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarReportes);
    };

    $scope.MostrarReportes = function(data) {
        $scope.reportes = data;
    };

    $scope.BuscarReporte = function(data) {
        $scope.id_reporte = data;
        var parametros = {
            catalogo: "sub_reportes",
            id_reporte: $scope.id_reporte
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarSubReportes);
    };

    $scope.MostrarSubReportes = function(data) {
        $scope.subreportes = data;
    };

    $scope.CargarReporte = function() {
        $scope.reporte_cargado = false;
        $scope.ejecutivos = null;
        var parametros = {

            id_afiliado: datos_usuario.id

        };
        $("#pnlPreview").hide();
        $("#pnlLoad").show();
        $scope.EjecutarLlamado("reportes", $("#ddReportes").val(), parametros, $scope.MostrarReporte);
    };

    $scope.reporte_cargado = false;
    $scope.ejecutivos = null;
    $scope.datos_reporte = null;
    $scope.MostrarReporte = function(data) {
        $scope.ejecutivo_seleccionado = null;
        $scope.distribuidora_seleccionada = null;
        $scope.index_distribuidora_seleccionado = -1;
        $scope.index_ejecutivo_seleccionado = -1;

        reporteNombre = $("#ddReportes").val();
        console.log(reporteNombre);
        $scope.datos_reporte = { data: [], header: [] };
        $scope.datos_visibles = { data: [], header: [] };

        $scope.datos_reporte.data = data.data;
        $scope.datos_reporte.header = data.header;

        if ((datos_usuario.id_clasificacion == 2 || datos_usuario.id_clasificacion == 5)) {
            $scope.ejecutivos = [];
        }

        $.each($scope.datos_reporte.data, function(index, row) {
            if ($scope.ejecutivos != null) {
                if (!$scope.EjecutivoExiste(row.id_ejecutivo)) {
                    var ejecutivo = {
                        id_ejecutivo: row.id_ejecutivo,
                        ejecutivo: row.ejecutivo,
                        distribuidoras: []
                    };
                    var distribuidora = {
                        id_distribuidora: row.id_distribuidora,
                        distribuidora: row.distribuidora
                    };
                    $scope.ejecutivos.push(ejecutivo);
                    $scope.ejecutivos[$scope.ejecutivos.length - 1].distribuidoras.push(distribuidora);
                } else {
                    var comprobacion_distribuidora = $scope.EjecutivoTieneDistribuidora(row.id_ejecutivo, row.id_distribuidora);
                    if (!comprobacion_distribuidora.tiene_distribuidora) {
                        var distribuidora = {
                            id_distribuidora: row.id_distribuidora,
                            distribuidora: row.distribuidora
                        };
                        $scope.ejecutivos[comprobacion_distribuidora.index_ejecutivo].distribuidoras.push(distribuidora);
                    }
                }
            }

        });

        $scope.MostrarDatosFiltrados();
    };

    $scope.MostrarDatosFiltrados = function() {
        $("#pnlPreview").hide();
        $("#pnlLoad").show();

        $scope.datos_visibles = { data: [], header: [] };
        $scope.datos_visibles.header = $scope.datos_reporte.header;

        $.each($scope.datos_reporte.data, function(index, row) {
            if ((datos_usuario.id_clasificacion == 2 || datos_usuario.id_clasificacion == 5)) {
                var agregar_fila = true;
                if ($scope.distribuidora_seleccionada != null) {
                    if (row.id_distribuidora != $scope.distribuidora_seleccionada.id_distribuidora) {
                        agregar_fila = false;
                    }
                } else if ($scope.ejecutivo_seleccionado != null) {
                    if (row.id_ejecutivo != $scope.ejecutivo_seleccionado.id_ejecutivo) {
                        agregar_fila = false;
                    }
                }

                if (agregar_fila) {
                    $scope.datos_visibles.data.push(row);
                }
            } else if ((datos_usuario.id_clasificacion == 3 && row.id_ejecutivo == datos_usuario.id)) {
                $scope.datos_visibles.data.push(row);
            }
        });

        $scope.MostrarTabla();
    };

    $scope.EjecutivoExiste = function(id_ejecutivo) {
        var existe = false;
        $.each($scope.ejecutivos, function(index, ejecutivo) {
            if (ejecutivo.id_ejecutivo == id_ejecutivo) {
                existe = true;
            }
        });
        return existe;
    };

    $scope.EjecutivoTieneDistribuidora = function(id_ejecutivo, id_distribuidora) {
        var index_ejecutivo_tiene = -1;
        var tiene_distribuidora = false;
        $.each($scope.ejecutivos, function(index_ejecutivo, ejecutivo) {
            if (ejecutivo.id_ejecutivo == id_ejecutivo) {
                index_ejecutivo_tiene = index_ejecutivo;
                $.each(ejecutivo.distribuidoras, function(index_distribuidora, distribuidora) {
                    if (distribuidora.id_distribuidora == id_distribuidora) {
                        tiene_distribuidora = true;
                    }
                });
            }
        });
        return { index_ejecutivo: index_ejecutivo_tiene, tiene_distribuidora: tiene_distribuidora };
    };

    $scope.index_ejecutivo_seleccionado = -1;
    $scope.ejecutivo_seleccionado = null;
    $scope.FiltrarEjecutivo = function() {
        $scope.index_distribuidora_seleccionado = -1;
        $scope.distribuidora_seleccionada = null;
        $scope.ejecutivo_seleccionado = { distribuidoras: [] };
        if ($scope.index_ejecutivo_seleccionado >= 0) {
            $scope.ejecutivo_seleccionado = $scope.ejecutivos[$scope.index_ejecutivo_seleccionado];
        } else {
            $scope.ejecutivo_seleccionado = null;
        }
        $scope.MostrarDatosFiltrados();
    };

    $scope.index_distribuidora_seleccionado = -1;
    $scope.distribuidora_seleccionada = null;
    $scope.FiltrarDistribuidora = function() {
        if ($scope.index_distribuidora_seleccionado >= 0) {
            $scope.distribuidora_seleccionada = $scope.ejecutivos[$scope.index_ejecutivo_seleccionado].distribuidoras[$scope.index_distribuidora_seleccionado];
        } else {
            $scope.distribuidora_seleccionada = null;
        }
        $scope.MostrarDatosFiltrados();
    };

    $scope.CambiarEjecutivo = function() {
        $scope.index_ejecutivo_seleccionado = -1;
        $scope.ejecutivo_seleccionado = null;
        $scope.index_distribuidora_seleccionado = -1;
        $scope.distribuidora_seleccionada = null;
        $scope.MostrarDatosFiltrados();
    };

    $scope.MostrarTabla = function() {
        var htmlTable = Array();
        htmlTable.push("<table class='table table-striped'>");
        htmlTable.push("<thead><tr>");
        $.each($scope.datos_visibles.header, function(index, column) {
            htmlTable.push("<th>" + column + "</th>");
        });
        htmlTable.push("<tr></thead>");

        htmlTable.push("<tbody>");
        $.each($scope.datos_visibles.data, function(index, row) {
            if (index < 50) {
                htmlTable.push("<tr>");
                $.each(row, function(rowIndex, cell) {
                    htmlTable.push("<td>" + cell + "</td>");
                });
                htmlTable.push("</tr>");
            } else {
                return false;
            }
        });
        htmlTable.push("</tbody>");
        htmlTable.push("</table>");

        $("#contenedorTabla").html(htmlTable.join(""));
        $("#pnlPreview").show();
        $("#pnlLoad").hide();

        dataReporte = $scope.datos_visibles;

        $scope.reporte_cargado = true;
    };

    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack) {
        $http({
            method: "POST",
            url: "clases/jarvis.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: { modelo: modelo, operacion: operacion, parametros: parametros }
        }).success(function(data) {
            if (data.error == "") {
                CallBack(data.data);
            } else {
                $scope.errorGeneral = data.error;
                $("#pnlPreview").hide();
                $("#pnlLoad").show();
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
    $scope.ObtenerReportes();
});