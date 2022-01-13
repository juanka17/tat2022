angular.module('simuladorApp', []).controller('simuladorController', function($scope, $http) {

    $scope.simulador = { venta: "", impactos: "" };

    $scope.CargarCuotas = function() {
        var parametros = {
            catalogo: "cuotas_simulador",
            id_usuario: $scope.id_usuario,
            id_temporada: $scope.id_temporada
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotas);
    };

    $scope.MostrarCuotas = function(data) {
        $scope.cuota = data;
        $scope.CargarDiasHabiles();
    };

    $scope.CargarDiasHabiles = function() {
        var parametros = {
            catalogo: "periodo_actual"
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDiasHabiles);
    };

    $scope.MostrarDiasHabiles = function(data) {
        $scope.dias_habiles = data;
        let calculo = $scope.dias_habiles[0].id % 2;
        if (calculo == 0) {
            $scope.indice = 0;
        } else {
            $scope.indice = 1;
        }

    };

    $scope.Simular = function() {
        $scope.mostrar = 1;
        $scope.fecha = new Date();
        $scope.fecha_simulador = $("#fecha_simulados").val();
        $scope.fecha_simulador_dia = new Date($scope.fecha_simulador).getDate();
        $scope.dia_total = new Date($scope.fecha_simulador).getDate();
        $scope.dia_festivo = new Date($scope.fecha_simulador).getDate() / 7;
        $scope.festivo = Math.round($scope.dia_festivo);
        $scope.dia = Math.round($scope.dia_total - $scope.dia_festivo);
        $scope.dia_faltante = $scope.dias_habiles[0].dias_habiles - $scope.dia;

        $scope.venta = $scope.simulador.venta;
        $scope.impactos = $scope.simulador.impactos;

        $scope.cumplimiento_hoy_venta = ($scope.venta / $scope.cuota[$scope.indice].cuota_2) * 100;
        $scope.cumplimiento_hoy_impactos = ($scope.impactos / $scope.cuota[$scope.indice].impactos) * 100;

        $scope.venta_diaria_ventas = $scope.venta / $scope.dia;
        $scope.cierre_proyectado_venta_hoy_ventas = $scope.venta_diaria_ventas * 26;

        $scope.venta_diaria_impactos = $scope.impactos / $scope.dia;
        $scope.cierre_proyectado_venta_hoy_impactos = $scope.venta_diaria_impactos * 26;

        $scope.cumplimiento_cierre_venta = ($scope.cierre_proyectado_venta_hoy_ventas / $scope.cuota[$scope.indice].cuota_2) * 100;
        $scope.cumplimiento_cierre_impactos = ($scope.cierre_proyectado_venta_hoy_impactos / $scope.cuota[$scope.indice].impactos) * 100;

        $scope.meta_diaria_para_cumplir_venta = $scope.cuota[$scope.indice].cuota_2 / 26;
        $scope.meta_diaria_para_cumplir_impactos = $scope.cuota[$scope.indice].impactos / 26;

        $scope.vender_para_cumplir_diaria_venta = ($scope.cuota[$scope.indice].cuota_2 - $scope.venta) / $scope.dia_faltante;
        $scope.vender_para_cumplir_diaria_impactos = ($scope.cuota[$scope.indice].impactos - $scope.impactos) / $scope.dia_faltante;



    };

    $scope.CargarCuotasVendedor = function() {
        var parametros = {
            catalogo: "cuotas_simulador_vendedor",
            id_usuario: $scope.id_usuario,
            id_temporada: $scope.id_temporada
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotasVendedor);
    };

    $scope.MostrarCuotasVendedor = function(data) {
        $scope.cuota_vendedor = data;
    };

    $scope.SimularVendedor = function(index) {
        $scope.venta_vendedor = $("#valor_vendedor_" + index).val();
        $("#valor_ingresado_" + index).text("$" + new Intl.NumberFormat().format($scope.venta_vendedor));
        $scope.impactos_vendedor = $("#impactos_vendedor_" + index).val();
        $("#valor_ingresado_cuota_" + index).text($scope.impactos_vendedor);


        $scope.cumplimiento_hoy_venta_vendedor = ($scope.venta_vendedor / $scope.cuota_vendedor[index].cuota_2) * 100;
        $("#cumplimiento_hoy_venta_" + index).text(Math.round($scope.cumplimiento_hoy_venta_vendedor) + "%");
        $scope.cumplimiento_hoy_impactos_vendedor = ($scope.impactos_vendedor / $scope.cuota_vendedor[index].impactos) * 100;
        $("#cumplimiento_hoy_impactos_" + index).text(Math.round($scope.cumplimiento_hoy_impactos_vendedor) + "%");

        $scope.venta_diaria_ventas_vendedor = ($scope.venta_vendedor / $scope.dia).toFixed();
        $("#venta_diaria_" + index).text("$" + new Intl.NumberFormat().format($scope.venta_diaria_ventas_vendedor));

        $scope.cierre_proyectado_venta_hoy_ventas = $scope.venta_diaria_ventas_vendedor * 26;
        $("#cierre_proyectado_venta_hoy" + index).text("$" + new Intl.NumberFormat().format($scope.cierre_proyectado_venta_hoy_ventas));

        $scope.venta_diaria_impactos_vendedor = ($scope.impactos_vendedor / $scope.dia).toFixed();
        $("#venta_impactos_diaria_" + index).text(Math.round($scope.venta_diaria_impactos_vendedor));

        $scope.cierre_proyectado_impactos_hoy_impactos = $scope.venta_diaria_impactos_vendedor * 26;
        $("#cierre_proyectado_impactos_hoy" + index).text(Math.round($scope.cierre_proyectado_impactos_hoy_impactos) + "%");

        $scope.cumplimiento_cierre_venta_vendedor = ($scope.cierre_proyectado_venta_hoy_ventas / $scope.cuota_vendedor[index].cuota_2) * 100;
        $("#cumplimiento_venta_cierre" + index).text(Math.round($scope.cumplimiento_cierre_venta_vendedor) + "%");
        $scope.cumplimiento_cierre_impactos_vendedor = ($scope.cierre_proyectado_impactos_hoy_impactos / $scope.cuota_vendedor[index].impactos) * 100;
        $("#cumplimiento_impactos_cierre" + index).text(Math.round($scope.cumplimiento_cierre_impactos_vendedor) + "%");

        $scope.meta_diaria_para_cumplir_venta_vendedor = $scope.cuota_vendedor[index].cuota_2 / 26;
        $("#meta_para_cumplir_venta" + index).text("$" + new Intl.NumberFormat().format(Math.round($scope.meta_diaria_para_cumplir_venta_vendedor)));
        $scope.meta_diaria_para_cumplir_impactos_vendedor = $scope.cuota_vendedor[index].impactos / 26;
        $("#meta_para_cumplir_impactos" + index).text(Math.round($scope.meta_diaria_para_cumplir_impactos_vendedor));

        $scope.vender_para_cumplir_diaria_venta_vendedor = (($scope.cuota_vendedor[index].cuota_2 - $scope.venta_vendedor) / $scope.dia_faltante).toFixed();
        $("#vender_para_cumplir_venta" + index).text("$" + new Intl.NumberFormat().format($scope.vender_para_cumplir_diaria_venta_vendedor));

        $scope.vender_para_cumplir_diaria_impactos = ($scope.cuota_vendedor[index].impactos - $scope.impactos_vendedor) / $scope.dia_faltante;
        $("#vender_para_cumplir_impactos" + index).text(new Intl.NumberFormat().format($scope.vender_para_cumplir_diaria_impactos));

    };

    $scope.DescargarExcelGeneral = function() {
        var data = Array();
        $("#tabla_supevisor tr").each(function(index, row) {
            data[index] = Array();
            $(row).find("td").each(function(index_cell, cell) {
                let cell_value = $(cell).find("h4").length > 0 ? $(cell).find("h4").first().html().trim().replace(/(\r\n|\n|\r)/gm, "") : "";
                if (cell_value == "") {
                    cell_value = $(cell).find("h5").first().html();
                }
                cell_value = cell_value == "Días" ? "Dias" : cell_value;
                data[index][index_cell] = cell_value;
            });
        });

        data[0] = ["", "Ventas", "Impactos"];
        console.table(data);

        let csvContent = "data:text/csv;charset=utf-8," + data.map(e => e.join(";")).join("\n");
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "simulacion.csv");
        document.body.appendChild(link); // Required for FF

        link.click();
    };

    $scope.DescargarExcelDetallado = function() {
        var data = Array();
        $("#simulador_detallado tbody").each(function(index, row) {
            data[index] = Array();

            let nombre_venta = $(".simulador_nombre_venta").eq(index).html().trim().replace(/(\r\n|\n|\r)/gm, "");

            let dia_venta = $(".simulador_dia_venta").eq(index).html().trim().replace(/(\r\n|\n|\r)/gm, "");

            let cuota_venta = $(".simulador_cuota_venta").eq(index).html().trim().replace(/(\r\n|\n|\r)/gm, "");
            let cuota_imp = $(".simulador_cuota_imp").eq(index).html().trim().replace(/(\r\n|\n|\r)/gm, "");

            let dia_valor_ingesado_venta = $(".simulador_valor_ingresado_venta p").eq(index).html();
            let dia_valor_ingesado_imp = $(".simulador_valor_ingresado_imp p").eq(index).html();

            let simulador_cumplimiento_hoy_venta = $(".simulador_cumplimiento_hoy_venta p").eq(index).html();
            let simulador_cumplimiento_hoy_imp = $(".simulador_cumplimiento_hoy_imp p").eq(index).html();

            let simulador_venta_diaria = $(".simulador_venta_diaria p").eq(index).html();
            let simulador_venta_diaria_impactos = $(".simulador_venta_diaria_impactos p").eq(index).html();

            let simulador_cierre_proyectado_venta_hoy = $(".simulador_cierre_proyectado_venta_hoy p").eq(index).html();
            let simulador_cierre_proyectado_imp_hoy = $(".simulador_cierre_proyectado_imp_hoy p").eq(index).html();

            let simulador_cumplimiento_venta_cierre = $(".simulador_cumplimiento_venta_cierre p").eq(index).html();
            let simulador_cumplimiento_imp_cierre = $(".simulador_cumplimiento_imp_cierre p").eq(index).html();

            let simulador_meta_para_cumplir_venta = $(".simulador_meta_para_cumplir_venta p").eq(index).html();
            let simulador_meta_para_cumplir_imp = $(".simulador_meta_para_cumplir_imp p").eq(index).html();

            let simulador_vender_para_cumplir_venta = $(".simulador_vender_para_cumplir_venta p").eq(index).html();
            let simulador_vender_para_cumplir_impacto = $(".simulador_vender_para_cumplir_impacto p").eq(index).html();

            if (typeof nombre_venta !== 'undefined' && nombre_venta != "") {
                data[index] = [
                    nombre_venta,
                    dia_venta,
                    cuota_venta,
                    dia_valor_ingesado_venta,
                    simulador_cumplimiento_hoy_venta,
                    simulador_venta_diaria,
                    simulador_cierre_proyectado_venta_hoy,
                    simulador_cumplimiento_venta_cierre,
                    simulador_meta_para_cumplir_venta,
                    simulador_vender_para_cumplir_venta,
                    cuota_imp,
                    dia_valor_ingesado_imp,
                    simulador_cumplimiento_hoy_imp,
                    simulador_venta_diaria_impactos,
                    simulador_cierre_proyectado_imp_hoy,
                    simulador_cumplimiento_imp_cierre,
                    simulador_meta_para_cumplir_imp,
                    simulador_vender_para_cumplir_impacto
                ];
            }
        });



        var array = [
            "Nombre",
            "Dias",
            "Cuota",
            "Valor Ingresado",
            "Cumplimiento hoy",
            "Venta Diaria",
            "Cierre proyectado venta hoy",
            "Cumplimiento cierre venta hoy",
            "Meta diaria para cumplir",
            "Vender para cumplir diaria 100%",
            "Cuota Imp",
            "Valor Ingresado Imp",
            "Cumplimiento hoy Imp",
            "Venta Diaria Imp",
            "Cierre proyectado venta hoy Imp",
            "Cumplimiento cierre venta hoy Imp",
            "Meta diaria para cumplir Imp",
            "Vender para cumplir diaria 100% Imp"
        ];

        data.unshift(array);

        console.table(data);

        let csvContent = "data:text/csv;charset=utf-8," + data.map(e => e.join(";")).join("\n");
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "simulacion_detallado.csv");
        document.body.appendChild(link); // Required for FF

        link.click();
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
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
    $scope.id_usuario = id_usuario;
    $scope.id_temporada = id_temporada;

    $scope.CargarCuotas();
});