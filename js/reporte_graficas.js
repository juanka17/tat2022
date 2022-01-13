angular.module('reporteGraficasApp', ['chart.js']).controller('reporteGraficasController', function($scope, $http) {

    $scope.labels = [];
    $scope.data = [];
    $scope.labels_supervisor_lider = [];
    $scope.data_supervisor_lider = [];

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Categoria Productos">
    $scope.categoriaProductos = function(data) {

        if (data == 0) {
            var url = "php/modulos/reportes/ventas.php?search=categoria_productos";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=categoria_productos_portafolio&id_portafolio=" + data;
        }

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.categoria_productos = response.data;
        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Marcas">
    $scope.loadMarcasProductos = function(id_categoria, id_portafolio) {
        let id_categoria_producto = 0;
        let id_portafolio_producto = 0;
        typeof id_categoria == "undefined" ? id_categoria_producto = 0 : id_categoria_producto = id_categoria;
        typeof id_portafolio == "undefined" ? id_portafolio_producto = 0 : id_portafolio_producto = id_portafolio;

        if (id_categoria == 0 && id_portafolio == 0) {
            var url = "php/modulos/reportes/ventas.php?search=marcas";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=categoria_marcas&id_categoria_productos_marca=" + id_categoria_producto + "&id_portafolio=" + id_portafolio_producto;
        }

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.marcas = response.data;

        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Sub_Marcas">
    $scope.loadSubMarcas = function(id_categoria, id_portafolio, id_marca) {
        let id_categoria_producto = 0;
        let id_portafolio_producto = 0;
        let id_marca_producto = 0;
        typeof id_categoria == "undefined" ? id_categoria_producto = 0 : id_categoria_producto = id_categoria;
        typeof id_portafolio == "undefined" ? id_portafolio_producto = 0 : id_portafolio_producto = id_portafolio;
        typeof id_marca == "undefined" ? id_marca_producto = 0 : id_marca_producto = id_marca;

        if (id_categoria == 0 && id_portafolio == 0 && id_marca == 0) {
            var url = "php/modulos/reportes/ventas.php?search=sub_marcas";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=sub_marcas_filtros&id_categoria_productos_marca=" + id_categoria_producto + "&id_portafolio=" + id_portafolio_producto + "&id_marca=" + id_marca_producto;
        }

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.sub_marcas = response.data;

        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Productos">
    $scope.productos = function(portafolio, categoria, marca, sub_marca) {
        let filtro_portafolio_producto = 0;
        let filtro_categoria_producto = 0;
        let filtro_marca_producto = 0;
        let filtro_sub_marca_producto = 0;
        let filtro_producto_producto = 0;

        typeof portafolio == "undefined" ? filtro_portafolio_producto = 0 : filtro_portafolio_producto = portafolio;
        typeof categoria == "undefined" ? filtro_categoria_producto = 0 : filtro_categoria_producto = categoria;
        typeof marca == "undefined" ? filtro_marca_producto = 0 : filtro_marca_producto = marca;
        typeof sub_marca == "undefined" ? filtro_sub_marca_producto = 0 : filtro_sub_marca_producto = sub_marca;


        if (filtro_portafolio_producto == 0 && filtro_categoria_producto == 0 && filtro_marca_producto == 0 && filtro_sub_marca_producto == 0) {
            var url = "php/modulos/reportes/ventas.php?search=productos_total";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=productos&id_portafolio=" + filtro_portafolio_producto + "&id_categoria=" + filtro_categoria_producto + "&id_marca=" + filtro_marca_producto + "&id_sub_marca=" + filtro_sub_marca_producto;
        }

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {
            $scope.productoss = response.data;
        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Temporadas">
    $scope.loadTemporadas = function() {
        $http({
            method: "GET",
            url: "php/modulos/reportes/ventas.php?search=temporadas",

        }).success(function(response) {

            $scope.temporadas = response.data;
            $scope.categoriaProductos(0);

        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Representantes">
    $scope.loadRepresentantes = function(data) {
        if (data == 0) {
            var url = "php/modulos/reportes/ventas.php?search=representantes";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=representantes_territorio&id_territorio=" + data;
        }

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.representantes = response.data;
        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Distribuidoras Madre">
    $scope.loadDistribuidorasMadre = function(id_territorio, id_representante) {
        let id_territorio_almacen = 0;
        let id_representante_almacen = 0;
        typeof id_territorio == "undefined" ? id_territorio_almacen = 0 : id_territorio_almacen = id_territorio;
        typeof id_representante == "undefined" ? id_representante_almacen = 0 : id_representante_almacen = id_representante;

        if (id_territorio_almacen == 0 && id_representante_almacen == 0) {
            var url = "php/modulos/reportes/ventas.php?search=distribuidora_madre";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=distribuidora_madre_representante&id_territorio=" + id_territorio_almacen + "&id_representante=" + id_representante_almacen;
        }

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.distribuidora_madre = response.data;

        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Distribuidoras">

    $scope.almacenesRepresentante = function(id_territorio, id_representante, id_madre) {
        let id_territorio_almacen = 0;
        let id_representante_almacen = 0;
        let id_madre_almacen = 0;
        typeof id_territorio == "undefined" ? id_territorio_almacen = 0 : id_territorio_almacen = id_territorio;
        typeof id_representante == "undefined" ? id_representante_almacen = 0 : id_representante_almacen = id_representante;
        typeof id_madre == "undefined" ? id_madre_almacen = 0 : id_madre_almacen = id_madre;

        if (id_territorio_almacen == 0 && id_representante_almacen == 0 && id_madre_almacen == 0) {
            var url = "php/modulos/reportes/ventas.php?search=distribuidoras_activas";
        } else {
            var url = "php/modulos/reportes/ventas.php?search=almacen_representantes&id_territorio=" + id_territorio_almacen + "&id_visitador=" + id_representante_almacen + "&id_madre=" + id_madre_almacen;
        }
        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.almacen_representantes = response.data;
            $scope.PeriodosVentas();
        });
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Select Periodos">
    $scope.PeriodosVentas = function(data) {
        var url = "php/modulos/reportes/ventas.php?search=periodos_ventas";

        $http({
            method: "GET",
            url: url,

        }).success(function(response) {

            $scope.periodos_ventas = response.data;

        });
    };

    // </editor-fold>

    $scope.reset = function() {
        $scope.id_portafolio = 0;
        $scope.id_categoria_producto = 0;
        $scope.id_marca_nueva_grafica = 0;
        $scope.id_sub_marca_nueva_grafica = 0;
        $scope.id_producto_nueva_grafica = 0;
        $scope.id_territorio_grafica_cupos = 0;
        $scope.id_representante = 0;
        $scope.id_distribuidora_madre = 0;
        $scope.almacenes_grafica_ventas = 0;
        $scope.id_periodo_nueva_grafica = 0;

        $scope.productos(0, 0, 0, 0);
        $scope.loadRepresentantes(0);
        $scope.almacenesRepresentante(0, 0, 0);
        $scope.loadMarcasProductos(0);
        $scope.loadSubMarcas(0, 0, 0);
        $scope.loadDistribuidorasMadre(0);

    };

    // <editor-fold defaultstate="collapsed" desc="Indicadores Ventas">
    $scope.ObtenerIndicadoresVentas = function(id_portafolio, id_categoria_producto, id_marca_nueva_grafica, id_sub_marca_nueva_grafica, id_producto_nueva_grafica, id_territorio, id_representante, id_distribuidora_madre, almacenes_grafica_ventas, id_periodo_nueva_grafica) {
        $scope.show_all_panels = true;

        typeof id_portafolio == "undefined" ? $scope.filtro_id_portafolio = 0 : $scope.filtro_id_portafolio = id_portafolio;

        typeof id_categoria_producto == "undefined" ? $scope.filtro_id_categoria_producto = 0 : $scope.filtro_id_categoria_producto = id_categoria_producto;

        typeof id_marca_nueva_grafica == "undefined" ? $scope.filtro_id_marca_producto = 0 : $scope.filtro_id_marca_producto = id_marca_nueva_grafica;

        typeof id_sub_marca_nueva_grafica == "undefined" ? $scope.filtro_id_sub_marca_producto = 0 : $scope.filtro_id_sub_marca_producto = id_sub_marca_nueva_grafica;

        typeof id_producto_nueva_grafica == "undefined" ? $scope.filtro_id_producto_nueva_grafica = 0 : $scope.filtro_id_producto_nueva_grafica = id_producto_nueva_grafica;

        typeof id_territorio == "undefined" ? $scope.filtro_id_territorio_nueva_grafica = 0 : $scope.filtro_id_territorio_nueva_grafica = id_territorio;

        typeof id_representante == "undefined" ? $scope.filtro_id_representante = 0 : $scope.filtro_id_representante = id_representante;

        typeof id_distribuidora_madre == "undefined" ? $scope.filtro_id_madre_distribuidora = 0 : $scope.filtro_id_madre_distribuidora = id_distribuidora_madre;

        typeof almacenes_grafica_ventas == "undefined" ? $scope.filtro_almacenes_grafica_ventas = 0 : $scope.filtro_almacenes_grafica_ventas = almacenes_grafica_ventas;

        typeof id_periodo_nueva_grafica == "undefined" ? $scope.filtro_id_periodo_nueva_grafica = 0 : $scope.filtro_id_periodo_nueva_grafica = id_periodo_nueva_grafica;

        $scope.parametros = {
            id_portafolio: $scope.filtro_id_portafolio,
            id_categoria: $scope.filtro_id_categoria_producto,
            id_marca: $scope.filtro_id_marca_producto,
            id_sub_marca: $scope.filtro_id_sub_marca_producto,
            id_producto: $scope.filtro_id_producto_nueva_grafica,
            id_territorio: $scope.filtro_id_territorio_nueva_grafica,
            id_representante: $scope.filtro_id_representante,
            id_madre: $scope.filtro_id_madre_distribuidora,
            id_distribuidora: $scope.filtro_almacenes_grafica_ventas,
            id_periodo: $scope.filtro_id_periodo_nueva_grafica
        };

        $scope.EjecutarLlamado("reportes", "obtener_indicadores_ventas", $scope.parametros, $scope.MostrarDatosGrafica);

    };

    $scope.MostrarDatosGrafica = function(data) {
        $("#NuevaGraficaVentas").empty();
        $("#NuevaGraficaImpactos").empty();
        $("#NuevaGraficaDropsize").empty();

        dg = data;

        if (dg.length == 0) {
            $("#NuevaGraficaVentas").empty();
            $("#NuevaGraficaImpactos").empty();
            $("#NuevaGraficaDropsize").empty();
            alert("No Existe Información Con Los Filtros Seleccionados");
        } else {
            let graficas = ["ventas", "impactos", "dropsize"]
            let totales_ventas = [];
            let totales_impactos = [];
            let ventas_totales = null;
            let impactos_totales = null;
            let dropsize_total = {};

            for (ig in graficas) {
                let x_axis = 'periodo';
                let y_axis = ['Total'];
                let chart_data = [];
                let temp_obj = { periodo: '', Total: 0 };

                for (i in dg.data) {
                    if (y_axis.indexOf(dg.data[i].territorio) == -1) {
                        y_axis.push(dg.data[i].territorio);
                    }

                    if (temp_obj.periodo != dg.data[i].periodo) {
                        if (temp_obj.periodo != '') {
                            if (ig == 0) {
                                totales_ventas.push(temp_obj.Total);
                            }

                            if (ig == 1) {
                                totales_impactos.push(temp_obj.Total);
                            }

                            if (ig == 2) {
                                let cur_index = chart_data.length;
                                temp_obj.Total = totales_ventas[cur_index] / totales_impactos[cur_index];
                                temp_obj.Total = temp_obj.Total.toFixed(2);
                            }

                            chart_data.push(temp_obj);
                            temp_obj = { periodo: '', Total: 0 };
                        }
                        temp_obj.periodo = dg.data[i].periodo;
                    }

                    if (!(dg.data[i].territorio in temp_obj)) {
                        temp_obj[dg.data[i].territorio] = dg.data[i][graficas[ig]];
                        if (ig == 2) {
                            temp_obj[dg.data[i].territorio] = temp_obj[dg.data[i].territorio].toFixed(2);
                        }
                        temp_obj.Total += temp_obj[dg.data[i].territorio];
                    }
                }
                if (ig == 0) {
                    totales_ventas.push(temp_obj.Total);
                }

                if (ig == 1) {
                    totales_impactos.push(temp_obj.Total);
                }

                if (ig == 2) {
                    let cur_index = chart_data.length;
                    temp_obj.Total = Math.round(totales_ventas[cur_index] / totales_impactos[cur_index]);
                    temp_obj.Total = temp_obj.Total;
                }
                chart_data.push(temp_obj);

                if (ig == 0 || ig == 1) {
                    temp_obj = { periodo: 'Total' };
                    chart_data.forEach(month_data => {
                        y_axis.forEach(key => {
                            //console.log(key);
                            //console.log( month_data[key] );

                            if (!(key in temp_obj)) {
                                temp_obj[key] = month_data[key];
                            } else {
                                temp_obj[key] += month_data[key];
                            }
                        });
                    });
                    //console.log( temp_obj );
                    temp_obj.periodo = "Total";

                    if (ig == 0) {
                        ventas_totales = temp_obj;
                    } else {
                        impactos_totales = temp_obj;
                    }

                    chart_data.push(temp_obj);
                } else {
                    //console.table(ventas_totales);
                    Object.keys(ventas_totales).forEach(key => {
                        if (key != "periodo") {
                            dropsize_total[key] = Math.round(ventas_totales[key] / impactos_totales[key]);
                        } else {
                            dropsize_total[key] = "Total";
                        }
                    });

                    chart_data.push(dropsize_total);
                }

                let preUnits = ig == 0 ? "$" : "";
                let nombre_grafica = graficas[ig];
                nombre_grafica = nombre_grafica.charAt(0).toUpperCase() + nombre_grafica.slice(1);
                let element = 'NuevaGrafica' + nombre_grafica;
                new Morris.Bar({
                    element: element,
                    data: chart_data,
                    xkey: x_axis,
                    ykeys: y_axis,
                    labels: y_axis,
                    preUnits: preUnits,
                    barColors: ['#ff0700', '#41C26D', '#1222e4', '#7248B7', '#399efe']
                });
            }
        }
        $scope.ObtenerIndicadoresRepresentantes();
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Indicadores Representantes">
    $scope.ObtenerIndicadoresRepresentantes = function() {
        $scope.EjecutarLlamado("reportes", "obtener_indicadores_representantes", $scope.parametros, $scope.MostrarDatosGraficaRepresentantes);
    };

    $scope.MostrarDatosGraficaRepresentantes = function(data) {
        $("#graficaRepresentantes_representantes").empty();
        $("#graficaRepresentantes_distribuidoras").empty();

        //console.log(data);

        dg = data;

        if (dg.length == 0) {
            $("#graficaRepresentantes_representantes").empty();
            $("#graficaRepresentantes_distribuidoras").empty();
            alert("No Existe Información Con Los Filtros Seleccionados");
        } else {
            let x_axis = "distribuidora";
            let y_axis = ["ventas"];
            let chart_data = [];
            let temp_obj = { distribuidora: '' };
            let data_representantes = [];
            let ids_reps = [];

            let total_ventas = 0;
            for (i in dg.data) {
                total_ventas += dg.data[i].ventas;
            }

            for (i in dg.data) {
                if (ids_reps.indexOf(dg.data[i].id_representante) == -1) {
                    data_representantes.push({ "label": dg.data[i].representante, "value": 0 });
                    ids_reps.push(dg.data[i].id_representante);
                    index_rep = ids_reps.indexOf(dg.data[i].id_representante);
                }
                data_representantes[index_rep].value += dg.data[i].ventas;

                chart_data.push({ "distribuidora": (dg.data[i].distribuidora + " - " + dg.data[i].representante), "ventas": dg.data[i].ventas });
            }

            for (i in data_representantes) {
                data_representantes[i]["formatted"] = Math.round((data_representantes[i].value * 100) / total_ventas);
            }

            data_representantes.sort((a, b) => (a.value > b.value) ? -1 : 1);
            console.log(data_representantes);
            new Morris.Donut({
                element: 'graficaRepresentantes_representantes',
                data: data_representantes,
                formatter: function(x, data) {
                    let val = x.toLocaleString(undefined, { minimumFractionDigits: 2 });
                    return "\n" + "$" + val + "\n" + data.formatted + "%";
                },
                colors: ["#d92027", "#ff9234", "#ffcd3c", "#35d0ba", "#1b6ca8", "#79d70f", "#ffd31d", "#649d66", "#ff5f40", "#e11d74"]
            });

            chart_data.sort((a, b) => (a.ventas > b.ventas) ? -1 : 1)
            let axes = false;
            console.log(chart_data);
            new Morris.Bar({
                element: "graficaRepresentantes_distribuidoras",
                data: chart_data,
                xkey: x_axis,
                ykeys: y_axis,
                labels: y_axis,
                xLabelAngle: 90,
                axes: axes,
                preUnits: "$"
            });
        }
        $scope.ObtenerIndicadoresRankingProductos();
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Indicadores Ranking Productos">    

    $scope.ObtenerIndicadoresRankingProductos = function() {
        $scope.EjecutarLlamado("reportes", "sp_indicadores_ranking_productos", $scope.parametros, $scope.MostrarIndicadoresRankingProductos);
    };

    $scope.MostrarIndicadoresRankingProductos = function(data) {
        $scope.RankingProductos = data;

        dg = $scope.RankingProductos;
        dgproducts = dg;

        $("#grafica_marcas").empty();
        $("#grafica_submarcas").empty();

        dgproducts.data.sort((a, b) => (b.marca > a.marca) ? -1 : 1);
        let ranking_marcas = [];
        for (i in dgproducts.data) {
            let marca = dgproducts.data[i].marca;
            if (!(marca in ranking_marcas)) {
                ranking_marcas[marca] = dgproducts.data[i].ventas;
            } else {
                ranking_marcas[marca] += dgproducts.data[i].ventas;
            }
        }

        dgproducts.data.sort((a, b) => (b.sub_marca > a.sub_marca) ? -1 : 1);
        let ranking_sub_marcas = [];
        for (i in dgproducts.data) {
            let sub_marca = dgproducts.data[i].sub_marca;
            if (!(sub_marca in ranking_sub_marcas)) {
                ranking_sub_marcas[sub_marca] = dgproducts.data[i].ventas;
            } else {
                ranking_sub_marcas[sub_marca] += dgproducts.data[i].ventas;
            }
        }

        let total_ventas = 0;
        for (i in ranking_marcas) {
            total_ventas += ranking_marcas[i];
        }

        let datos_marcas = [];
        for (var key in ranking_marcas) {
            datos_marcas.push({ "label": key.trim(), "value": ranking_marcas[key] });
        }

        for (i in datos_marcas) {
            let total = (datos_marcas[i].value * 100) / total_ventas;
            total = total.toLocaleString(undefined, { minimumFractionDigits: 2 })
            datos_marcas[i]["formatted"] = total;
        }

        console.log(datos_marcas);
        datos_marcas.sort((a, b) => (a.value > b.value) ? -1 : 1);
        new Morris.Donut({
            element: 'grafica_marcas',
            data: datos_marcas,
            formatter: function(x, data) {
                console.log(x);
                console.log(data);
                let val = x.toLocaleString(undefined, { minimumFractionDigits: 2 });
                return "\n" + "$" + val + "\n" + data.formatted + "%";
                return "$" + val;
            },
            colors: ["#d92027", "#ff9234", "#ffcd3c", "#35d0ba", "#1b6ca8", "#79d70f", "#ffd31d", "#649d66", "#ff5f40", "#e11d74"]
        });

        let datos_sub_marcas = [];
        for (var key in ranking_sub_marcas) {
            datos_sub_marcas.push({ "submarca": key.trim(), "value": ranking_sub_marcas[key] });
        }
        console.log(datos_sub_marcas);
        datos_sub_marcas.sort((a, b) => (a.value > b.value) ? -1 : 1);
        new Morris.Bar({
            element: "grafica_submarcas",
            data: datos_sub_marcas,
            xkey: "submarca",
            ykeys: ["value"],
            labels: ["Ventas"],
            xLabelAngle: 90,
            axes: false,
            preUnits: "$"
        });

        $scope.ObtenerIndicadoresCrecimientoAnual();
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Crecimiento Anual">

    $scope.ObtenerIndicadoresCrecimientoAnual = function() {
        $scope.EjecutarLlamado("reportes", "obtener_ndicadores_crecimiento", $scope.parametros, $scope.MostrarDatosGraficaCrecimientoAnual);
    };

    $scope.MostrarDatosGraficaCrecimientoAnual = function(data) {
        $("#NuevaGraficaCrecimientoAnualVentas").empty();
        $("#NuevaGraficaCrecimientoAnualImpactos").empty();
        $("#NuevaGraficaCrecimientoAnualDropsize").empty();


        dg = data;

        if (dg.length == 0) {
            alert("No Existe Información Con Los Filtros Seleccionados");
        } else {
            let x_axis = 'periodo';
            let y_axis = [];
            let chart_data_ventas = [];
            let chart_data_impactos = [];
            let chart_data_dropsize = [];
            let temp_obj_ventas = { periodo: '', ventas_2019: 0, ventas_2020: 0, crecimiento: 0 };
            let temp_obj_impactos = { periodo: '', impactos_2019: 0, impactos_2020: 0, crecimiento: 0 };
            let temp_obj_dropsize = { periodo: '', dropsize_2019: 0, dropsize_2020: 0, crecimiento: 0 };
            for (i in dg.data) {

                if (temp_obj_ventas.periodo != dg.data[i].periodo) {
                    if (temp_obj_ventas.periodo != '') {
                        chart_data_ventas.push(temp_obj_ventas);
                        temp_obj_ventas = { periodo: '', ventas_2019: 0, ventas_2020: 0, crecimiento: 0 };
                    }
                    temp_obj_ventas.periodo = dg.data[i].periodo;

                    temp_obj_ventas.ventas_2019 = dg.data[i].ventas_2019;
                    temp_obj_ventas.ventas_2020 = dg.data[i].ventas_2020;
                    temp_obj_ventas.crecimiento = dg.data[i].crecimiento_ventas;

                }
                if (temp_obj_impactos.periodo != dg.data[i].periodo) {
                    if (temp_obj_impactos.periodo != '') {
                        chart_data_impactos.push(temp_obj_impactos);
                        temp_obj_impactos = { periodo: '', impactos_2019: 0, impactos_2020: 0, crecimineto: 0 };
                    }
                    temp_obj_impactos.periodo = dg.data[i].periodo;

                    temp_obj_impactos.impactos_2019 = dg.data[i].impactos_2019;
                    temp_obj_impactos.impactos_2020 = dg.data[i].impactos_2020;
                    temp_obj_impactos.crecimiento = dg.data[i].crecimiento_impactos;

                }
                if (temp_obj_dropsize.periodo != dg.data[i].periodo) {
                    if (temp_obj_dropsize.periodo != '') {
                        chart_data_dropsize.push(temp_obj_dropsize);
                        temp_obj_dropsize = { periodo: '', dropsize_2019: 0, dropsize_2020: 0, crecimiento: 0 };
                    }
                    temp_obj_dropsize.periodo = dg.data[i].periodo;

                    temp_obj_dropsize.dropsize_2019 = dg.data[i].dropsize_2019;
                    temp_obj_dropsize.dropsize_2020 = dg.data[i].dropsize_2020;
                    temp_obj_dropsize.crecimiento = dg.data[i].crecimiento_dropsize;

                }

            }
            chart_data_ventas.push(temp_obj_ventas);
            chart_data_impactos.push(temp_obj_impactos);
            chart_data_dropsize.push(temp_obj_dropsize);
            //console.log( chart_data );


            new Morris.Bar({
                element: NuevaGraficaCrecimientoAnualVentas,
                data: chart_data_ventas,
                xkey: 'periodo',
                ykeys: ['ventas_2019', 'ventas_2020', 'crecimiento'],
                labels: ['2019', '2020', 'Crecimiento'],
                preUnits: "$",
                barColors: ['#ff0700', '#41C26D', '#1222e4', '#7248B7'],
                hoverCallback: function(index, options, content, row) {
                    var indexAmount = 3;
                    var txtToReplace = $(content)[indexAmount].textContent;
                    return content.replace(txtToReplace, txtToReplace.replace(options.preUnits, "%"));
                }
            });
            new Morris.Bar({
                element: NuevaGraficaCrecimientoAnualImpactos,
                data: chart_data_impactos,
                xkey: 'periodo',
                ykeys: ['impactos_2019', 'impactos_2020', 'crecimiento'],
                labels: ['2019', '2020', 'Crecimiento'],
                preUnits: ".",
                barColors: ['#ff0700', '#41C26D', '#1222e4', '#7248B7'],
                hoverCallback: function(index, options, content, row) {
                    var indexAmount = 3;
                    var txtToReplace = $(content)[indexAmount].textContent;
                    return content.replace(txtToReplace, txtToReplace.replace(options.preUnits, "%"));
                }
            });
            new Morris.Bar({
                element: NuevaGraficaCrecimientoAnualDropsize,
                data: chart_data_dropsize,
                xkey: 'periodo',
                ykeys: ['dropsize_2019', 'dropsize_2020', 'crecimiento'],
                labels: ['2019', '2020', 'Crecimiento'],
                preUnits: ".",
                barColors: ['#ff0700', '#41C26D', '#1222e4', '#7248B7'],
                hoverCallback: function(index, options, content, row) {
                    var indexAmount = 3;
                    var txtToReplace = $(content)[indexAmount].textContent;
                    return content.replace(txtToReplace, txtToReplace.replace(options.preUnits, "%"));
                }
            });

        }

        $scope.ObtenerIndicadoresCumplimiento();
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Cumplimiento">

    $scope.ObtenerIndicadoresCumplimiento = function() {
        $scope.EjecutarLlamado("reportes", "obtener_indicadores_cumplimiento", $scope.parametros, $scope.MostrarIndicadoresCumplimiento);
    };

    $scope.MostrarIndicadoresCumplimiento = function(data) {
        $("#GraficaCumplimientoVentas").empty();
        $("#GraficaCumplimientoImpactos").empty();
        dg = data;
        dgcumplimiento = dg;
        if (dg.length == 0) {
            alert("No Existe Información Con Los Filtros Seleccionados");
        } else {
            let temp_obj_ventas = { periodo: '', cuota: 0, ventas: 0, cumplimiento: 0 };
            let temp_obj_impactos = { periodo: '', cuota: 0, impactos: 0, cumplimiento: 0 };

            let chart_data_ventas = [];
            let chart_data_impactos = [];

            for (i in dgcumplimiento.data) {
                if (temp_obj_ventas.periodo != dg.data[i].periodo) {
                    if (temp_obj_ventas.periodo != '') {
                        chart_data_ventas.push(temp_obj_ventas);
                        chart_data_impactos.push(temp_obj_impactos);
                        temp_obj_ventas = { periodo: '', cuota: 0, ventas: 0, cumplimiento: 0 };
                        temp_obj_impactos = { periodo: '', cuota: 0, impactos: 0, cumplimiento: 0 };
                    }

                    if (dg.data[i].cuotas != null) {
                        temp_obj_ventas.periodo = dg.data[i].periodo;
                        temp_obj_ventas.cuota = dg.data[i].cuotas;
                        temp_obj_ventas.ventas = dg.data[i].ventas;
                        temp_obj_ventas.cumplimiento = dg.data[i].cumplimiento_ventas;
                    }

                    if (dg.data[i].cuota_impactos != null) {
                        temp_obj_impactos.periodo = dg.data[i].periodo;
                        temp_obj_impactos.cuota = dg.data[i].cuota_impactos;
                        temp_obj_impactos.impactos = dg.data[i].impactos;
                        temp_obj_impactos.cumplimiento = dg.data[i].cumplimiento_impactos;
                    }
                }
            }
            chart_data_ventas.push(temp_obj_ventas);
            chart_data_impactos.push(temp_obj_impactos);

            console.log(chart_data_ventas);
            console.log(chart_data_impactos);

            new Morris.Bar({
                element: "GraficaCumplimientoVentas",
                data: chart_data_ventas,
                xkey: 'periodo',
                ykeys: ['cuota', 'ventas', 'cumplimiento'],
                labels: ['Cuota', 'Ventas', 'Cumplimiento'],
                preUnits: "$",
                barColors: ['#ff0700', '#41C26D', '#1222e4', '#7248B7'],
                hoverCallback: function(index, options, content, row) {
                    var indexAmount = 3;
                    var txtToReplace = $(content)[indexAmount].textContent;
                    return content.replace(txtToReplace, txtToReplace.replace(options.preUnits, "%"));
                }
            });

            new Morris.Bar({
                element: "GraficaCumplimientoImpactos",
                data: chart_data_impactos,
                xkey: 'periodo',
                ykeys: ['cuota', 'impactos', 'cumplimiento'],
                labels: ['Cuota', 'Impactos', 'Cumplimiento'],
                preUnits: ".",
                barColors: ['#ff0700', '#41C26D', '#1222e4', '#7248B7'],
                hoverCallback: function(index, options, content, row) {
                    var indexAmount = 3;
                    var txtToReplace = $(content)[indexAmount].textContent;
                    return content.replace(txtToReplace, txtToReplace.replace(options.preUnits, "%"));
                }
            });
        }

        $scope.panel_indicador = 1;
        $scope.show_all_panels = false;
    };

    $scope.ObtenerIndicadoresVentasPortafolio = function() {
        $scope.EjecutarLlamado("reportes", "obtener_indicadores_ventas_portafolio", $scope.parametros, $scope.MostrarIndicadoresPortafolio);
    };

    $scope.MostrarIndicadoresPortafolio = function(data) {
        $("#GraficaCumplimientoVentas").empty();
        $("#GraficaCumplimientoImpactos").empty();
        dg = data;
        if (dg.length == 0) {
            alert("No Existe Información Con Los Filtros Seleccionados");
        } else {

            console.log("Gráfica Portafolio");
            console.table(dg.data);

            dg.data;
            let portafolios = [];
            let objeto_portafolio = {
                "Portafolio": "",
                "Analgesicos": 0,
                "Cuidado_Personal": 0,
                "Respiratorios": 0,
                "Promoción": 0,
                "Expectorante": 0,
                "Multivitamínicos": 0,
                "Antigripales": 0,
                "N_A": 0,
                "Analgesicos_Ninos": 0,
                "Antiemeticos": 0,
                "Cuidado_Oral": 0,
                "Total": 0
            };
            for (i in dg.data) {
                if (dg.data[i]["portafolio"] == "Sin SKU") {
                    objeto_portafolio = {
                        "Portafolio": "Sin SKU",
                        "Analgesicos": 0,
                        "Cuidado_Personal": 0,
                        "Respiratorios": 0,
                        "Promoción": 0,
                        "Expectorante": 0,
                        "Multivitamínicos": 0,
                        "Antigripales": 0,
                        "Analgesicos_Ninos": 0,
                        "Antiemeticos": 0,
                        "Cuidado_Oral": 0,
                        "NA": dg.data[i]["N_A"],
                        "Total": dg.data[i]["ventas"],
                    };
                } else {
                    objeto_portafolio = {
                        "Portafolio": dg.data[i]["portafolio"],
                        "Analgesicos": dg.data[i]["Analgesicos"],
                        "Cuidado_Personal": dg.data[i]["Cuidado_Personal"],
                        "Respiratorios": dg.data[i]["Respiratorios"],
                        "Promoción": dg.data[i]["Promoción"],
                        "Expectorante": dg.data[i]["Expectorante"],
                        "Multivitamínicos": dg.data[i]["Multivitamínicos"],
                        "Antigripales": dg.data[i]["Antigripales"],
                        "Analgesicos_Ninos": dg.data[i]["Analgesicos_Ninos"],
                        "Antiemeticos": dg.data[i]["Antiemeticos"],
                        "Cuidado_Oral": dg.data[i]["Cuidado_Oral"],
                        "NA": 0,
                        "Total": dg.data[i]["ventas"]
                    };
                }


                console.table(objeto_portafolio);
                portafolios.push(objeto_portafolio);
            }

            console.log(portafolios);

            $("#GraficaPortafolio").html("");
            new Morris.Bar({
                element: "GraficaPortafolio",
                data: portafolios,
                xkey: 'Portafolio',
                ykeys: ['Analgesicos', 'Cuidado_Personal', 'Respiratorios', 'Promoción', 'Expectorante', 'Multivitamínicos', 'Antigripales', 'Analgesicos_Ninos', 'Antiemeticos', 'Cuidado_Oral', "NA", 'Total'],
                labels: ['Analgesicos', 'Cuidado_Personal', 'Respiratorios', 'Promoción', 'Expectorante', 'Multivitamínicos', 'Antigripales', 'Analgesicos_Ninos', 'Antiemeticos', 'Cuidado_Oral', "NA", 'Total'],
                preUnits: "$",
                stacked: true
            });
        }


    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Cupos">

    $scope.ObtenerIndicadoresCupos = function(id_territorio, id_representante, id_almacen, id_temporada) {
        if (typeof id_territorio == "undefined") {
            $scope.filtro_id_territorio = 0;
        } else {
            $scope.filtro_id_territorio = id_territorio;
        }
        if (typeof id_representante == "undefined") {
            $scope.filtro_id_representante = 0;
        } else {
            $scope.filtro_id_representante = id_representante;
        }
        if (typeof id_almacen == "undefined") {
            $scope.filtro_id_almacen = 0;
        } else {
            $scope.filtro_id_almacen = id_almacen;
        }
        if (typeof id_temporada == "undefined") {
            $scope.filtro_id_temporada = 0;
        } else {
            $scope.filtro_id_temporada = id_temporada;
        }



        $scope.parametros_cupos = {
            id_territorio: $scope.filtro_id_territorio,
            id_representante: $scope.filtro_id_representante,
            id_almacen: $scope.filtro_id_almacen,
            id_temporada: $scope.filtro_id_temporada

        };
        $scope.EjecutarLlamado("reportes", "sp_indicadores_cupos", $scope.parametros_cupos, $scope.MostrarIndicadoresCupos);
    };

    $scope.MostrarIndicadoresCupos = function(data) {
        $("#graficaDeCupos").empty();

        if (data.length == 0) {
            alert("no hay datos para mostrar")
        } else {
            $scope.ecu_temporada_general = data;
            console.log($scope.ecu_temporada_general);
            let chartData = [];
            let chartResponse = $scope.ecu_temporada_general.data[0];

            console.log(chartResponse);
            chartData.push({
                despachado: parseInt(chartResponse.despachado),
                despachado_x_100: parseInt(chartResponse.despachado_x_100),
                legalizado: parseInt(chartResponse.legalizado),
                solicitado: parseInt(chartResponse.solicitado),
                solicitado_x_100: parseInt(chartResponse.solicitado_x_100),
                legalizado_x_100: parseInt(chartResponse.legalizado_x_100)
            });


            //console.log(chartData)


            new Morris.Donut({
                // ID of the element in which to draw the chart.
                element: 'graficaDeCupos',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.                              
                data: [
                    { value: chartData[0].despachado, label: 'Despachado', formatted: chartData[0].despachado_x_100 + '%' },
                    { value: chartData[0].legalizado, label: 'Legalizado', formatted: chartData[0].legalizado_x_100 + '%' },
                    { value: chartData[0].solicitado, label: 'Solicitado', formatted: chartData[0].solicitado_x_100 + '%' }
                ],
                backgroundColor: '#ccc',
                labelColor: '#060',
                colors: [
                    '#fbf000',
                    '#00ff00',
                    '#367fa9'
                ],
                formatter: function(x, data) {
                    return data.formatted;
                }
            }).on('click', function(i, row) {
                //console.log(i, row);
            });
        }
        $scope.ObtenerIndicadoresCuposTerritorio();
    };

    $scope.ObtenerIndicadoresCuposTerritorio = function() {
        $scope.EjecutarLlamado("reportes", "sp_indicadores_cupos_territorio", $scope.parametros_cupos, $scope.MostrarIndicadoresCuposTerritorios);
    };

    $scope.MostrarIndicadoresCuposTerritorios = function(data) {
        $("#graficaDeCupos0").empty();
        $("#graficaDeCupos1").empty();
        $("#graficaDeCupos2").empty();
        $("#graficaDeCupos3").empty();

        if (data.length == 0) {
            alert("no hay datos para mostrar")
        } else {
            $scope.ecu_temporada = data.data;
            let graficas = ["graficaDeCupos0", "graficaDeCupos1", "graficaDeCupos2", "graficaDeCupos3"];

            setTimeout(function() {
                console.log($scope.ecu_temporada);
                let chartData = [];
                for (ig in $scope.ecu_temporada) {
                    let chartResponse = ig;

                    console.log(chartResponse);
                    chartData.push({
                        despachado: parseInt($scope.ecu_temporada[chartResponse].despachado),
                        despachado_x_100: parseInt($scope.ecu_temporada[chartResponse].despachado_x_100),
                        legalizado: parseInt($scope.ecu_temporada[chartResponse].legalizado),
                        solicitado: parseInt($scope.ecu_temporada[chartResponse].solicitado),
                        solicitado_x_100: parseInt($scope.ecu_temporada[chartResponse].solicitado_x_100),
                        legalizado_x_100: parseInt($scope.ecu_temporada[chartResponse].legalizado_x_100)
                    });

                    console.log(chartData)

                    let nombre_grafica = graficas[ig];
                    console.log(nombre_grafica);
                    new Morris.Donut({
                        // ID of the element in which to draw the chart.
                        element: nombre_grafica,
                        // Chart data records -- each entry in this array corresponds to a point on
                        // the chart.                              
                        data: [
                            { value: chartData[chartResponse].despachado, label: 'Despachado', formatted: chartData[chartResponse].despachado_x_100 + '%' },
                            { value: chartData[chartResponse].legalizado, label: 'Legalizado', formatted: chartData[chartResponse].legalizado_x_100 + '%' },
                            { value: chartData[chartResponse].solicitado, label: 'Solicitado', formatted: chartData[chartResponse].solicitado_x_100 + '%' }
                        ],
                        backgroundColor: '#ccc',
                        labelColor: '#060',
                        colors: [
                            '#fbf000',
                            '#00ff00',
                            '#367fa9'
                        ],
                        formatter: function(x, data) {
                            return data.formatted;
                        }
                    }).on('click', function(i, row) {
                        console.log(i, row);
                    });
                }
            }, 2000);



        }

    };
    // </editor-fold>

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
    $scope.loadTemporadas();
    $scope.productos(0, 0, 0, 0);
    $scope.loadRepresentantes(0);
    $scope.almacenesRepresentante(0, 0, 0);
    $scope.loadMarcasProductos(0);
    $scope.loadSubMarcas(0, 0, 0);
    $scope.loadDistribuidorasMadre(0);
});