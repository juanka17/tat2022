angular
    .module("reporteGraficasApp", ["chart.js"])
    .controller("reporteGraficasController", function($scope, $http) {
        $scope.labels = [];
        $scope.data = [];
        $scope.labels_supervisor_lider = [];
        $scope.data_supervisor_lider = [];

        $scope.FiltrosGraficas = {
            id_portafolio: 0,
            id_categoria_producto: 0,
            id_marca_nueva_grafica: 0,
            id_sub_marca_nueva_grafica: 0,
            id_producto_nueva_grafica: 0,
            id_territorio: 0,
            id_representante: 0,
            id_distribuidora_madre: 0,
            almacenes_grafica_ventas: 0,
        };

        // <editor-fold defaultstate="collapsed" desc="Cargar Select Categoria Productos">
        $scope.categoriaProductos = function() {
            if ($scope.FiltrosGraficas.id_portafolio == 0) {
                var url = "php/modulos/reportes/ventas.php?search=categoria_productos";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=categoria_productos_portafolio&id_portafolio=" +
                    $scope.FiltrosGraficas.id_portafolio;
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
        $scope.loadMarcasProductos = function() {
            let id_categoria_producto = 0;
            let id_portafolio_producto = 0;
            typeof $scope.FiltrosGraficas.id_categoria_producto == "undefined" ?
                (id_categoria_producto = 0) :
                (id_categoria_producto =
                    $scope.FiltrosGraficas.id_categoria_producto);
            typeof $scope.FiltrosGraficas.id_portafolio == "undefined" ?
                (id_portafolio_producto = 0) :
                (id_portafolio_producto = $scope.FiltrosGraficas.id_portafolio);

            if (id_portafolio_producto == 0 && id_categoria_producto == 0) {
                var url = "php/modulos/reportes/ventas.php?search=marcas";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=categoria_marcas&id_categoria_productos_marca=" +
                    id_categoria_producto +
                    "&id_portafolio=" +
                    id_portafolio_producto;
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
        $scope.loadSubMarcas = function() {
            let id_categoria_producto = 0;
            let id_portafolio_producto = 0;
            let id_marca_producto = 0;
            typeof $scope.FiltrosGraficas.id_categoria_producto == "undefined" ?
                (id_categoria_producto = 0) :
                (id_categoria_producto =
                    $scope.FiltrosGraficas.id_categoria_producto);
            typeof $scope.FiltrosGraficas.id_portafolio == "undefined" ?
                (id_portafolio_producto = 0) :
                (id_portafolio_producto = $scope.FiltrosGraficas.id_portafolio);
            typeof $scope.FiltrosGraficas.id_marca_nueva_grafica == "undefined" ?
                (id_marca_producto = 0) :
                (id_marca_producto = $scope.FiltrosGraficas.id_marca_nueva_grafica);

            if (
                id_categoria_producto == 0 &&
                id_portafolio_producto == 0 &&
                id_marca_producto == 0
            ) {
                var url = "php/modulos/reportes/ventas.php?search=sub_marcas";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=sub_marcas_filtros&id_categoria_productos_marca=" +
                    id_categoria_producto +
                    "&id_portafolio=" +
                    id_portafolio_producto +
                    "&id_marca=" +
                    id_marca_producto;
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
        $scope.productos = function() {
            let filtro_portafolio_producto = 0;
            let filtro_categoria_producto = 0;
            let filtro_marca_producto = 0;
            let filtro_sub_marca_producto = 0;

            typeof $scope.FiltrosGraficas.portafolio == "undefined" ?
                (filtro_portafolio_producto = 0) :
                (filtro_portafolio_producto = $scope.FiltrosGraficas.portafolio);
            typeof $scope.FiltrosGraficas.id_categoria_producto == "undefined" ?
                (filtro_categoria_producto = 0) :
                (filtro_categoria_producto =
                    $scope.FiltrosGraficas.id_categoria_producto);
            typeof $scope.FiltrosGraficas.id_marca_nueva_grafica == "undefined" ?
                (filtro_marca_producto = 0) :
                (filtro_marca_producto =
                    $scope.FiltrosGraficas.id_marca_nueva_grafica);
            typeof $scope.FiltrosGraficas.id_sub_marca_nueva_grafica == "undefined" ?
                (filtro_sub_marca_producto = 0) :
                (filtro_sub_marca_producto =
                    $scope.FiltrosGraficas.id_sub_marca_nueva_grafica);

            if (
                filtro_portafolio_producto == 0 &&
                filtro_categoria_producto == 0 &&
                filtro_marca_producto == 0 &&
                filtro_sub_marca_producto == 0
            ) {
                var url = "php/modulos/reportes/ventas.php?search=productos_total";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=productos&id_portafolio=" +
                    filtro_portafolio_producto +
                    "&id_categoria=" +
                    filtro_categoria_producto +
                    "&id_marca=" +
                    filtro_marca_producto +
                    "&id_sub_marca=" +
                    filtro_sub_marca_producto;
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
        $scope.loadRepresentantes = function() {
            if ($scope.FiltrosGraficas.id_territorio == 0) {
                var url = "php/modulos/reportes/ventas.php?search=representantes";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=representantes_territorio&id_territorio=" +
                    $scope.FiltrosGraficas.id_territorio;
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
        $scope.loadDistribuidorasMadre = function() {
            let id_territorio_almacen = 0;
            let id_representante_almacen = 0;
            typeof $scope.FiltrosGraficas.id_territorio == "undefined" ?
                (id_territorio_almacen = 0) :
                (id_territorio_almacen = $scope.FiltrosGraficas.id_territorio);
            typeof $scope.FiltrosGraficas.id_representante == "undefined" ?
                (id_representante_almacen = 0) :
                (id_representante_almacen = $scope.FiltrosGraficas.id_representante);

            if (id_territorio_almacen == 0 && id_representante_almacen == 0) {
                var url = "php/modulos/reportes/ventas.php?search=distribuidora_madre";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=distribuidora_madre_representante&id_territorio=" +
                    id_territorio_almacen +
                    "&id_representante=" +
                    id_representante_almacen;
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

        $scope.almacenesRepresentante = function() {
            let id_territorio_almacen = 0;
            let id_representante_almacen = 0;
            let id_madre_almacen = 0;
            typeof $scope.FiltrosGraficas.id_territorio == "undefined" ?
                (id_territorio_almacen = 0) :
                (id_territorio_almacen = $scope.FiltrosGraficas.id_territorio);
            typeof $scope.FiltrosGraficas.id_representante == "undefined" ?
                (id_representante_almacen = 0) :
                (id_representante_almacen = $scope.FiltrosGraficas.id_representante);
            typeof $scope.FiltrosGraficas.id_distribuidora_madre == "undefined" ?
                (id_madre_almacen = 0) :
                (id_madre_almacen = $scope.FiltrosGraficas.id_distribuidora_madre);

            if (
                id_territorio_almacen == 0 &&
                id_representante_almacen == 0 &&
                id_madre_almacen == 0
            ) {
                var url =
                    "php/modulos/reportes/ventas.php?search=distribuidoras_activas";
            } else {
                var url =
                    "php/modulos/reportes/ventas.php?search=almacen_representantes&id_territorio=" +
                    id_territorio_almacen +
                    "&id_visitador=" +
                    id_representante_almacen +
                    "&id_madre=" +
                    id_madre_almacen;
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
        $scope.PeriodosVentas = function() {
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
            $scope.FiltrosGraficas.id_portafolio = 0;
            $scope.FiltrosGraficas.id_categoria_producto = 0;
            $scope.FiltrosGraficas.id_marca_nueva_grafica = 0;
            $scope.FiltrosGraficas.id_sub_marca_nueva_grafica = 0;
            $scope.FiltrosGraficas.id_producto_nueva_grafica = 0;
            $scope.FiltrosGraficas.id_territorio_grafica_cupos = 0;
            $scope.FiltrosGraficas.id_representante = 0;
            $scope.FiltrosGraficas.id_distribuidora_madre = 0;
            $scope.FiltrosGraficas.almacenes_grafica_ventas = 0;
            $scope.FiltrosGraficas.id_periodo_nueva_grafica = 0;

            $scope.productos();
            $scope.loadRepresentantes();
            $scope.almacenesRepresentante();
            $scope.loadMarcasProductos();
            $scope.loadSubMarcas();
            $scope.loadDistribuidorasMadre();
        };

        // <editor-fold defaultstate="collapsed" desc="Crear HASH">
        $scope.CrearFiltros = function() {
            $scope.filtros = [];
            $scope.countChecked();

            var lista_filtros = [];
            $scope.filtros.forEach((filtro) => {
                let tmp_filtro = [];
                for (var key in filtro) {
                    tmp_filtro[key] = filtro[key];
                }
                lista_filtros.push(tmp_filtro);
            });

            var parametros = {
                catalogo: "filtros_indicadores",
                datos: $scope.filtros,
            };
            $scope.EjecutarLlamado(
                "catalogos",
                "RegistraCatalogoDesdeArrayJSON",
                parametros,
                $scope.ObtenerFiltrosGraficas
            );
        };

        $scope.countChecked = function() {
            $scope.filtro_periodo = [];

            function broofa() {
                return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
                    /[xy]/g,
                    function(c) {
                        var r = (Math.random() * 16) | 0,
                            v = c == "x" ? r : (r & 0x3) | 0x8;
                        return v.toString(16);
                    }
                );
            }
            let hash_consulta = broofa();

            $(".filtro_periodo:checked").each(function(index, filtro) {
                $scope.filtro_periodo.push($(filtro).attr("value"));
                id_filtro = $(filtro).attr("value");
                $scope.filtros.push({
                    hash_consulta: hash_consulta,
                    tipo_filtro: "periodo",
                    id_filtro: id_filtro,
                });
            });
        };

        // </editor-fold>

        $scope.ObtenerFiltrosGraficas = function() {
            $scope.show_all_panels = true;

            $scope.parametros = {
                id_portafolio: $scope.FiltrosGraficas.id_portafolio,
                id_categoria: $scope.FiltrosGraficas.id_categoria_producto,
                id_marca: $scope.FiltrosGraficas.id_marca_nueva_grafica,
                id_sub_marca: $scope.FiltrosGraficas.id_sub_marca_nueva_grafica,
                id_producto: $scope.FiltrosGraficas.id_producto_nueva_grafica,
                id_territorio: $scope.FiltrosGraficas.id_territorio,
                id_representante: $scope.FiltrosGraficas.id_representante,
                id_madre: $scope.FiltrosGraficas.id_distribuidora_madre,
                id_distribuidora: $scope.FiltrosGraficas.almacenes_grafica_ventas,
                hash: $scope.filtros[0].hash_consulta,
            };

            $scope.EjecutarLlamado(
                "reportes",
                "obtener_indicadores_ventas_portafolio_nuevo",
                $scope.parametros,
                $scope.MostrarIndicadoresPortafolio
            );
        };

        // <editor-fold defaultstate="collapsed" desc="Indicadores Ventas">

        $scope.MostrarIndicadoresPortafolio = function(data) {
            dg = data;
            if (dg.length == 0) {
                alert(
                    "No Existe Información Con Los Filtros Seleccionados - Portafolio"
                );
            } else {
                let datos = [];
                datos["Analgesicos"] = [];
                datos["Cuidado_Personal"] = [];
                datos["Respiratorios"] = [];
                datos["Promoción"] = [];
                datos["Expectorante"] = [];
                datos["Multivitamínicos"] = [];
                datos["Antigripales"] = [];
                datos["N_A"] = [];
                datos["Analgesicos_Ninos"] = [];
                datos["Antiemeticos"] = [];
                datos["Cuidado_Oral"] = [];
                datos["Total"] = [];

                //let categories = [ "PFE" , "GSK" , "Sin SKU" ];
                let categories = [];
                for (i in dg.data) {
                    if (dg.data[i].portafolio != "Sin SKU") {
                        datos["Analgesicos"].push(dg.data[i]["Analgesicos"]);
                        datos["Cuidado_Personal"].push(dg.data[i]["Cuidado_Personal"]);
                        datos["Respiratorios"].push(dg.data[i]["Respiratorios"]);
                        datos["Promoción"].push(dg.data[i]["Promoción"]);
                        datos["Expectorante"].push(dg.data[i]["Expectorante"]);
                        datos["Multivitamínicos"].push(dg.data[i]["Multivitamínicos"]);
                        datos["Antigripales"].push(dg.data[i]["Antigripales"]);
                        datos["N_A"].push(dg.data[i]["N_A"]);
                        datos["Analgesicos_Ninos"].push(dg.data[i]["Analgesicos_Ninos"]);
                        datos["Antiemeticos"].push(dg.data[i]["Antiemeticos"]);
                        datos["Cuidado_Oral"].push(dg.data[i]["Cuidado_Oral"]);

                        categories.push([
                            dg.data[i].portafolio,
                            "$" +
                            dg.data[i]["ventas"].toLocaleString(undefined, {
                                minimumFractionDigits: 0,
                            }) +
                            "M",
                        ]);
                    }
                }

                let series = [];
                for (tipo in datos) {
                    series.push({ name: tipo, data: datos[tipo] });
                }

                let options = buildBarGraphicOptions(
                    "Categorias X Marca",
                    categories,
                    series,
                    true
                );
                drawGraphic("#GraficaPortafolio", options);
            }

            $scope.EjecutarLlamado(
                "reportes",
                "obtener_indicadores_ventas_nuevo",
                $scope.parametros,
                $scope.MostrarGraficaTerritorios
            );
        };

        $scope.MostrarGraficaTerritorios = function(data) {
            $("#NuevaGraficaVentas").empty();
            $("#NuevaGraficaImpactos").empty();
            $("#NuevaGraficaDropsize").empty();

            dg = data;

            if (dg.length == 0) {
                $("#NuevaGraficaVentas").empty();
                $("#NuevaGraficaImpactos").empty();
                $("#NuevaGraficaDropsize").empty();
                alert(
                    "No Existe Información Con Los Filtros Seleccionados - Territorios"
                );
            } else {
                dg = dg;
                let datos_territorios = {
                    ventas: { title: "Ventas", series: [] },
                    impactos: { title: "Impactos", series: [] },
                    dropsize: { title: "Dropsize", series: [] },
                    categories: { ventas: [], impactos: [], dropsize: [] },
                };

                let totales = { ventas: [], impactos: [], dropsize: [] };
                let categories = [];
                let preview_data = [];
                preview_data["Norte"] = { ventas: [], impactos: [], dropsize: [] };
                preview_data["Centro"] = { ventas: [], impactos: [], dropsize: [] };
                preview_data["Sur"] = { ventas: [], impactos: [], dropsize: [] };
                preview_data["Santander"] = { ventas: [], impactos: [], dropsize: [] };

                let total_ventas = 0;
                let total_impactos = 0;
                let temp_periodo = "";
                for (i in dg.data) {
                    if (categories.indexOf(dg.data[i].periodo) == -1) {
                        categories.push(dg.data[i].periodo);
                    }
                    preview_data[dg.data[i].territorio].ventas.push(dg.data[i].ventas);
                    preview_data[dg.data[i].territorio].impactos.push(
                        dg.data[i].impactos
                    );
                    preview_data[dg.data[i].territorio].dropsize.push(
                        (dg.data[i].ventas / dg.data[i].impactos).toFixed(0)
                    );

                    if (temp_periodo != "" && temp_periodo != dg.data[i].periodo) {
                        totales.ventas.push(total_ventas);
                        totales.impactos.push(total_impactos);
                        totales.dropsize.push((total_ventas / total_impactos).toFixed(0));
                        total_ventas = 0;
                        total_impactos = 0;
                    }
                    total_ventas += dg.data[i].ventas;
                    total_impactos += dg.data[i].impactos;
                    temp_periodo = dg.data[i].periodo;
                }
                totales.ventas.push(total_ventas);
                totales.impactos.push(total_impactos);
                totales.dropsize.push((total_ventas / total_impactos).toFixed(0));

                for (territorio in preview_data) {
                    datos_territorios.ventas.series.push({
                        name: territorio,
                        data: preview_data[territorio].ventas,
                    });
                    datos_territorios.impactos.series.push({
                        name: territorio,
                        data: preview_data[territorio].impactos,
                    });
                    datos_territorios.dropsize.series.push({
                        name: territorio,
                        data: preview_data[territorio].dropsize,
                    });
                }

                for (i_periodo in categories) {
                    let total =
                        "$" +
                        totales.ventas[i_periodo].toLocaleString(undefined, {
                            minimumFractionDigits: 0,
                        });
                    datos_territorios.categories.ventas.push([
                        categories[i_periodo],
                        total,
                    ]);

                    total = totales.impactos[i_periodo].toLocaleString(undefined, {
                        minimumFractionDigits: 0,
                    });
                    datos_territorios.categories.impactos.push([
                        categories[i_periodo],
                        total,
                    ]);

                    total = totales.dropsize[i_periodo].toLocaleString(undefined, {
                        minimumFractionDigits: 0,
                    });
                    datos_territorios.categories.dropsize.push([
                        categories[i_periodo],
                        total,
                    ]);
                }

                data_graficas["territorios"] = datos_territorios;
                showGraficaTerritorios("ventas");
            }

            $scope.ObtenerIndicadoresRepresentantes();
        };

        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="Indicadores Representantes">
        $scope.ObtenerIndicadoresRepresentantes = function() {
            $scope.EjecutarLlamado(
                "reportes",
                "obtener_indicadores_representantes_nuevo",
                $scope.parametros,
                $scope.MostrarDatosGraficaRepresentantes
            );
        };

        $scope.MostrarDatosGraficaRepresentantes = function(data) {
            $("#graficaRepresentantes_representantes").empty();
            $("#graficaRepresentantes_distribuidoras").empty();

            dg = data;

            if (dg.length == 0) {
                $("#graficaRepresentantes_representantes").empty();
                $("#graficaRepresentantes_distribuidoras").empty();
                alert("No Existe Información Con Los Filtros Seleccionados");
            } else {
                let x_axis = "distribuidora";
                let y_axis = ["ventas"];
                let chart_data = [];
                let temp_obj = { distribuidora: "" };
                let data_representantes = [];
                let ids_reps = [];

                let total_ventas = 0;
                for (i in dg.data) {
                    total_ventas += dg.data[i].ventas;
                }

                for (i in dg.data) {
                    if (ids_reps.indexOf(dg.data[i].id_representante) == -1) {
                        data_representantes.push({
                            label: dg.data[i].representante,
                            value: 0,
                        });
                        ids_reps.push(dg.data[i].id_representante);
                        index_rep = ids_reps.indexOf(dg.data[i].id_representante);
                    }
                    data_representantes[index_rep].value += dg.data[i].ventas;

                    chart_data.push({
                        distribuidora: dg.data[i].distribuidora + " - " + dg.data[i].representante,
                        ventas: dg.data[i].ventas,
                    });
                }

                for (i in data_representantes) {
                    data_representantes[i]["formatted"] = Math.round(
                        (data_representantes[i].value * 100) / total_ventas
                    );
                }

                data_representantes.sort((a, b) => (a.value > b.value ? -1 : 1));

                new Morris.Donut({
                    element: "graficaRepresentantes_representantes",
                    data: data_representantes,
                    formatter: function(x, data) {
                        let val = x.toLocaleString(undefined, { minimumFractionDigits: 2 });
                        return "\n" + "$" + val + "\n" + data.formatted + "%";
                    },
                    colors: [
                        "#d92027",
                        "#ff9234",
                        "#ffcd3c",
                        "#35d0ba",
                        "#1b6ca8",
                        "#79d70f",
                        "#ffd31d",
                        "#649d66",
                        "#ff5f40",
                        "#e11d74",
                    ],
                });

                chart_data.sort((a, b) => (a.ventas > b.ventas ? -1 : 1));
                let axes = false;
                new Morris.Bar({
                    element: "graficaRepresentantes_distribuidoras",
                    data: chart_data,
                    xkey: x_axis,
                    ykeys: y_axis,
                    labels: y_axis,
                    xLabelAngle: 90,
                    axes: axes,
                    preUnits: "$",
                });
            }
            $scope.ObtenerIndicadoresRankingProductos();
        };

        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="Indicadores Ranking Productos">

        $scope.ObtenerIndicadoresRankingProductos = function() {
            $scope.EjecutarLlamado(
                "reportes",
                "indicadores_ranking_productos_nuevo",
                $scope.parametros,
                $scope.MostrarIndicadoresRankingProductos
            );
        };

        $scope.MostrarIndicadoresRankingProductos = function(data) {
            $scope.RankingProductos = data;

            dg = $scope.RankingProductos;
            dgproducts = dg;

            $("#grafica_marcas").empty();
            $("#grafica_submarcas").empty();

            dgproducts.data.sort((a, b) => (b.marca > a.marca ? -1 : 1));
            let ranking_marcas = [];
            for (i in dgproducts.data) {
                let marca = dgproducts.data[i].marca;
                if (!(marca in ranking_marcas)) {
                    ranking_marcas[marca] = dgproducts.data[i].ventas;
                } else {
                    ranking_marcas[marca] += dgproducts.data[i].ventas;
                }
            }

            dgproducts.data.sort((a, b) => (b.sub_marca > a.sub_marca ? -1 : 1));
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
                datos_marcas.push({ label: key.trim(), value: ranking_marcas[key] });
            }

            for (i in datos_marcas) {
                let total = (datos_marcas[i].value * 100) / total_ventas;
                total = total.toLocaleString(undefined, { minimumFractionDigits: 2 });
                datos_marcas[i]["formatted"] = total;
            }

            datos_marcas.sort((a, b) => (a.value > b.value ? -1 : 1));
            new Morris.Donut({
                element: "grafica_marcas",
                data: datos_marcas,
                formatter: function(x, data) {
                    let val = x.toLocaleString(undefined, { minimumFractionDigits: 2 });
                    return "\n" + "$" + val + "\n" + data.formatted + "%";
                    return "$" + val;
                },
                colors: [
                    "#d92027",
                    "#ff9234",
                    "#ffcd3c",
                    "#35d0ba",
                    "#1b6ca8",
                    "#79d70f",
                    "#ffd31d",
                    "#649d66",
                    "#ff5f40",
                    "#e11d74",
                ],
            });

            let datos_sub_marcas = [];
            for (var key in ranking_sub_marcas) {
                datos_sub_marcas.push({
                    submarca: key.trim(),
                    value: ranking_sub_marcas[key],
                });
            }

            datos_sub_marcas.sort((a, b) => (a.value > b.value ? -1 : 1));
            new Morris.Bar({
                element: "grafica_submarcas",
                data: datos_sub_marcas,
                xkey: "submarca",
                ykeys: ["value"],
                labels: ["Ventas"],
                xLabelAngle: 90,
                axes: false,
                preUnits: "$",
            });

            $scope.ObtenerIndicadoresCrecimientoAnual();
        };

        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Crecimiento Anual">

        $scope.ObtenerIndicadoresCrecimientoAnual = function() {
            $scope.EjecutarLlamado(
                "reportes",
                "obtener_ndicadores_crecimiento_nuevo",
                $scope.parametros,
                $scope.MostrarDatosGraficaCrecimientoAnual
            );
        };

        $scope.MostrarDatosGraficaCrecimientoAnual = function(data) {
            $("#NuevaGraficaCrecimientoAnualVentas").empty();
            $("#NuevaGraficaCrecimientoAnualImpactos").empty();
            $("#NuevaGraficaCrecimientoAnualDropsize").empty();

            dg = data;

            if (dg.length == 0) {
                alert("No Existe Información Con Los Filtros Seleccionados");
            } else {
                let datos_ventas = [];
                datos_ventas["ventas_2020"] = [];
                datos_ventas["ventas_2021"] = [];
                datos_ventas["crecimiento_ventas"] = [];

                let datos_impactos = [];
                datos_impactos["impactos_2020"] = [];
                datos_impactos["impactos_2021"] = [];
                datos_impactos["crecimiento_impactos"] = [];

                let datos_dropsize = [];
                datos_dropsize["dropsize_2020"] = [];
                datos_dropsize["dropsize_2021"] = [];

                let categories = [];
                for (i in dg.data) {
                    datos_ventas["ventas_2020"].push(dg.data[i]["ventas_2020"]);
                    datos_ventas["ventas_2021"].push(dg.data[i]["ventas_2021"]);
                    datos_ventas["crecimiento_ventas"].push(
                        dg.data[i]["crecimiento_ventas"]
                    );

                    datos_impactos["impactos_2020"].push(dg.data[i]["impactos_2020"]);
                    datos_impactos["impactos_2021"].push(dg.data[i]["impactos_2021"]);
                    datos_impactos["crecimiento_impactos"].push(
                        dg.data[i]["crecimiento_impactos"]
                    );

                    datos_dropsize["dropsize_2020"].push(dg.data[i]["dropsize_2020"]);
                    datos_dropsize["dropsize_2021"].push(dg.data[i]["dropsize_2021"]);

                    categories.push(dg.data[i]["periodo"]);
                }

                let series_ventas = [];
                for (tipo in datos_ventas) {
                    series_ventas.push({ name: tipo, data: datos_ventas[tipo] });
                }

                let series_impactos = [];
                for (tipo in datos_impactos) {
                    series_impactos.push({ name: tipo, data: datos_impactos[tipo] });
                }

                let series_dropsize = [];
                for (tipo in datos_dropsize) {
                    series_dropsize.push({ name: tipo, data: datos_dropsize[tipo] });
                }

                var options_ventas = {
                    series: series_ventas,
                    chart: {
                        type: "bar",
                        height: 500,
                        stacked: true,
                    },
                    title: {
                        text: "Crecimiento Ventas",
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            dataLabels: {
                                position: "top",
                            },
                        },
                    },
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function(value) {
                                if (value < 100) {
                                    return (
                                        "%" +
                                        value.toLocaleString(undefined, {
                                            minimumFractionDigits: 0,
                                        })
                                    );
                                } else {
                                    return (
                                        "$" +
                                        value.toLocaleString(undefined, {
                                            minimumFractionDigits: 0,
                                        })
                                    );
                                }
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: 6,
                        style: {
                            fontSize: "12px",
                            colors: ["#fff"],
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        show: false,
                        width: 5,
                        colors: ["#fff"],
                    },
                    xaxis: {
                        categories: categories,
                        labels: {
                            formatter: function(value) {
                                return (
                                    "$" +
                                    value.toLocaleString(undefined, { minimumFractionDigits: 0 })
                                );
                            },
                        },
                    },
                };

                var chart_ventas = new ApexCharts(
                    document.querySelector("#NuevaGraficaCrecimientoAnualVentas"),
                    options_ventas
                );
                chart_ventas.render();

                var options_impactos = {
                    series: series_impactos,
                    chart: {
                        type: "bar",
                        height: 500,
                        stacked: true,
                    },
                    title: {
                        text: "Crecimiento Impactos",
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            dataLabels: {
                                position: "top",
                            },
                        },
                    },
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function(value) {
                                return value.toLocaleString(undefined, {
                                    minimumFractionDigits: 0,
                                });
                            },
                        },
                    },
                    /**/
                    dataLabels: {
                        enabled: true,
                        offsetX: -6,

                        style: {
                            fontSize: "12px",
                            colors: ["#fff"],
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(value) {
                            return value.toLocaleString(undefined, {
                                minimumFractionDigits: 0,
                            });
                        },
                    },
                    stroke: {
                        show: true,
                        width: 5,
                        colors: ["#fff"],
                    },
                    xaxis: {
                        categories: categories,
                        labels: {
                            formatter: function(value) {
                                return value.toLocaleString(undefined, {
                                    minimumFractionDigits: 0,
                                });
                            },
                        },
                    },
                };

                var chart_impactos = new ApexCharts(
                    document.querySelector("#NuevaGraficaCrecimientoAnualImpactos"),
                    options_impactos
                );
                chart_impactos.render();

                var options_dropsize = {
                    series: series_dropsize,
                    chart: {
                        type: "bar",
                        height: 500,
                        stacked: true,
                    },
                    title: {
                        text: "Crecimiento Ventas",
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            dataLabels: {
                                position: "top",
                            },
                        },
                    },
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function(value) {
                                return (
                                    "$" +
                                    value.toLocaleString(undefined, { minimumFractionDigits: 0 })
                                );
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -6,

                        style: {
                            fontSize: "12px",
                            colors: ["#fff"],
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(value) {
                            return value.toLocaleString(undefined, {
                                minimumFractionDigits: 0,
                            });
                        },
                    },
                    stroke: {
                        show: true,
                        width: 5,
                        colors: ["#fff"],
                    },
                    xaxis: {
                        categories: categories,
                        labels: {
                            formatter: function(value) {
                                return value.toLocaleString(undefined, {
                                    minimumFractionDigits: 0,
                                });
                            },
                        },
                    },
                };

                var chart_dropsize = new ApexCharts(
                    document.querySelector("#NuevaGraficaCrecimientoAnualDropsize"),
                    options_dropsize
                );
                chart_dropsize.render();
            }

            $scope.ObtenerIndicadoresCumplimiento();
        };
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Cumplimiento">

        $scope.ObtenerIndicadoresCumplimiento = function() {
            $scope.EjecutarLlamado(
                "reportes",
                "obtener_indicadores_cumplimiento_nuevo",
                $scope.parametros,
                $scope.MostrarIndicadoresCumplimiento
            );
        };

        $scope.MostrarIndicadoresCumplimiento = function(data) {
            $("#GraficaCumplimientoVentas").empty();
            $("#GraficaCumplimientoImpactos").empty();
            dg = data;
            dgcumplimiento = dg;
            if (dg.length == 0) {
                alert("No Existe Información Con Los Filtros Seleccionados");
            } else {
                let datos_ventas = [];
                datos_ventas["Ventas"] = [];
                datos_ventas["Cuotas"] = [];
                datos_ventas["Cumplimiento"] = [];

                let datos_impactos = [];
                datos_impactos["Impactos"] = [];
                datos_impactos["Cuota"] = [];
                datos_impactos["Cumplimiento"] = [];

                let categories = [];

                for (i in dg.data) {
                    datos_ventas["Ventas"].push(dg.data[i]["ventas"]);
                    datos_ventas["Cuotas"].push(dg.data[i]["cuotas"]);
                    datos_ventas["Cumplimiento"].push(dg.data[i]["cumplimiento_ventas"]);

                    datos_impactos["Impactos"].push(dg.data[i]["impactos"]);
                    datos_impactos["Cuota"].push(dg.data[i]["cuota_impactos"]);
                    datos_impactos["Cumplimiento"].push(
                        dg.data[i]["cumplimiento_impactos"]
                    );

                    categories.push(dg.data[i]["periodo"]);
                }

                let series_ventas = [];
                for (tipo in datos_ventas) {
                    series_ventas.push({ name: tipo, data: datos_ventas[tipo] });
                }

                let series_impactos = [];
                for (tipo in datos_impactos) {
                    series_impactos.push({ name: tipo, data: datos_impactos[tipo] });
                }

                var options_ventas = {
                    series: series_ventas,
                    chart: {
                        height: 350,
                        type: "line",
                        dropShadow: {
                            enabled: true,
                            color: "#000",
                            top: 18,
                            left: 7,
                            blur: 10,
                            opacity: 0.2,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    colors: ["#77B6EA", "#545454"],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                    },
                    grid: {
                        borderColor: "#e7e7e7",
                        row: {
                            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                            opacity: 0.5,
                        },
                    },
                    markers: {
                        size: 1,
                    },
                    xaxis: {
                        categories: categories,
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                if (value > 5000) {
                                    return (
                                        "$" +
                                        value.toLocaleString(undefined, {
                                            minimumFractionDigits: 0,
                                        })
                                    );
                                } else {
                                    return (
                                        "%" +
                                        value.toLocaleString(undefined, {
                                            minimumFractionDigits: 0,
                                        })
                                    );
                                }
                            },
                        },
                    },
                    legend: {
                        position: "top",
                        horizontalAlign: "right",
                        floating: true,
                        offsetY: -25,
                        offsetX: -5,
                    },
                };

                var options_impactos = {
                    series: series_impactos,
                    chart: {
                        height: 350,
                        type: "line",
                        dropShadow: {
                            enabled: true,
                            color: "#000",
                            top: 18,
                            left: 7,
                            blur: 10,
                            opacity: 0.2,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    colors: ["#77B6EA", "#545454"],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                    },
                    grid: {
                        borderColor: "#e7e7e7",
                        row: {
                            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                            opacity: 0.5,
                        },
                    },
                    markers: {
                        size: 1,
                    },
                    xaxis: {
                        categories: categories,
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                if (value > 5000) {
                                    return value.toLocaleString(undefined, {
                                        minimumFractionDigits: 0,
                                    });
                                } else {
                                    return (
                                        "%" +
                                        value.toLocaleString(undefined, {
                                            minimumFractionDigits: 0,
                                        })
                                    );
                                }
                            },
                        },
                    },
                    legend: {
                        position: "top",
                        horizontalAlign: "right",
                        floating: true,
                        offsetY: -25,
                        offsetX: -5,
                    },
                };

                var chart_ventas = new ApexCharts(
                    document.querySelector("#GraficaCumplimientoVentas"),
                    options_ventas
                );

                chart_ventas.render();

                var chart_impactos = new ApexCharts(
                    document.querySelector("#GraficaCumplimientoImpactos"),
                    options_impactos
                );
                chart_impactos.render();
                /*
        let temp_obj_ventas = {
          periodo: "",
          cuota: 0,
          ventas: 0,
          cumplimiento: 0,
        };
        let temp_obj_impactos = {
          periodo: "",
          cuota: 0,
          impactos: 0,
          cumplimiento: 0,
        };

        let chart_data_ventas = [];
        let chart_data_impactos = [];

        for (i in dgcumplimiento.data) {
          if (temp_obj_ventas.periodo != dg.data[i].periodo) {
            if (temp_obj_ventas.periodo != "") {
              chart_data_ventas.push(temp_obj_ventas);
              chart_data_impactos.push(temp_obj_impactos);
              temp_obj_ventas = {
                periodo: "",
                cuota: 0,
                ventas: 0,
                cumplimiento: 0,
              };
              temp_obj_impactos = {
                periodo: "",
                cuota: 0,
                impactos: 0,
                cumplimiento: 0,
              };
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

        new Morris.Bar({
          element: "GraficaCumplimientoVentas",
          data: chart_data_ventas,
          xkey: "periodo",
          ykeys: ["cuota", "ventas", "cumplimiento"],
          labels: ["Cuota", "Ventas", "Cumplimiento"],
          preUnits: "$",
          barColors: ["#ff0700", "#41C26D", "#1222e4", "#7248B7"],
          hoverCallback: function (index, options, content, row) {
            var indexAmount = 3;
            var txtToReplace = $(content)[indexAmount].textContent;
            return content.replace(
              txtToReplace,
              txtToReplace.replace(options.preUnits, "%")
            );
          },
        });

        new Morris.Bar({
          element: "GraficaCumplimientoImpactos",
          data: chart_data_impactos,
          xkey: "periodo",
          ykeys: ["cuota", "impactos", "cumplimiento"],
          labels: ["Cuota", "Impactos", "Cumplimiento"],
          preUnits: ".",
          barColors: ["#ff0700", "#41C26D", "#1222e4", "#7248B7"],
          hoverCallback: function (index, options, content, row) {
            var indexAmount = 3;
            var txtToReplace = $(content)[indexAmount].textContent;
            return content.replace(
              txtToReplace,
              txtToReplace.replace(options.preUnits, "%")
            );
          },
        });
      */
            }
            $scope.panel_indicador = 1;
            $scope.show_all_panels = false;
        };

        // </editor-fold>

        $scope.EjecutarLlamado = function(
            modelo,
            operacion,
            parametros,
            CallBack
        ) {
            $http({
                method: "POST",
                url: "clases/jarvis.php",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                data: { modelo: modelo, operacion: operacion, parametros: parametros },
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

function buildBarGraphicOptions(title, categories, series, money) {
    return {
        title: {
            text: title,
        },
        chart: {
            type: "bar",
            height: 500,
            stacked: true,
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                if (money) {
                    value = Math.round(value / 1000000, 0);
                    return (
                        "$" +
                        value.toLocaleString(undefined, { minimumFractionDigits: 0 }) +
                        "M"
                    );
                }
                return value.toLocaleString(undefined, { minimumFractionDigits: 0 });
            },
        },
        series: series,
        xaxis: {
            categories: categories,
            labels: {
                formatter: function(value) {
                    if (money) {
                        return (
                            "$" +
                            value.toLocaleString(undefined, { minimumFractionDigits: 0 })
                        );
                    }
                    return value.toLocaleString(undefined, { minimumFractionDigits: 0 });
                },
            },
        },
        legend: {
            position: "top",
            horizontalAlign: "left",
            offsetX: 40,
        },
        fill: {
            opacity: 1,
        },
        colors: [
            "#003f5c",
            "#2f4b7c",
            "#665191",
            "#a05195",
            "#d45087",
            "#f95d6a",
            "#ff7c43",
            "#ffa600",
        ],
        tooltip: {
            shared: true,
            y: {
                formatter: function(value) {
                    if (money) {
                        return (
                            "$" +
                            value.toLocaleString(undefined, { minimumFractionDigits: 0 })
                        );
                    }
                    return value.toLocaleString(undefined, { minimumFractionDigits: 0 });
                },
            },
        },
    };
}

function drawGraphic(selector, options) {
    $(selector).html("");
    var chart = new ApexCharts(document.querySelector(selector), options);
    chart.render();
}

function showGraficaTerritorios(title) {
    data_graficas.territorios[title].title;
    let options = buildBarGraphicOptions(
        data_graficas.territorios[title].title,
        data_graficas.territorios.categories[title],
        data_graficas.territorios[title].series,
        title == "ventas"
    );
    drawGraphic("#GraficaTerritorios", options);
}

$(function() {
    $(".btn-cambiar-fuente-grafica-territorios").on("click", function() {
        let fuente = $(this).data("fuente");
        showGraficaTerritorios(fuente);
    });
});