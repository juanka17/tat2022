angular.module('reporteGraficasApp', ['chart.js']).controller('reporteGraficasController', function ($scope, $http) {


    $scope.labels = [];
    $scope.data = [];
    $scope.labels_supervisor_lider = [];
    $scope.data_supervisor_lider = [];

    // <editor-fold defaultstate="collapsed" desc="Cargar Temporadas">
    $scope.loadTemporadas = function ()
    {
        $http({
            method: "GET",
            url: "php/modulos/reportes/ventas.php?search=temporadas",

        }).success(function (response) {

            $scope.temporadas = response.data;
            $scope.categoriaProductos();

        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Cargar Categoria Productos">
    $scope.categoriaProductos = function ()
    {
        $http({
            method: "GET",
            url: "php/modulos/reportes/ventas.php?search=categoria_productos"

        }).success(function (response) {

            $scope.categoria_productos = response.data;
            $scope.loadTerritorios();
        });
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Cargar Territorios">
    $scope.loadTerritorios = function ()
    {
        $http({
            method: "GET",
            url: "php/modulos/reportes/ventas.php?search=territorios",

        }).success(function (response) {

            $scope.territorios = response.data;
            $scope.loadRepresentantes(4);

        });
    };
    // </editor-fold>
    
    
    // <editor-fold defaultstate="collapsed" desc="Cargar Representantes">
    $scope.loadRepresentantes = function (data)
    {
        console.log(data);
        if (data == 4)
        {
            $scope.id_territorio_representaten = '1,2,3';
        } else
        {
            $scope.id_territorio_representaten = data;
        }
        $http({
            method: "GET",
            url: "php/modulos/reportes/ventas.php?search=representantes&id_territorio=" + $scope.id_territorio_representaten,

        }).success(function (response) {

            $scope.representantes = response.data;
            $scope.almacenesRepresentante(1);
        });
    };
    // </editor-fold>
    
    $scope.almacenesRepresentante = function (data)
    {
        console.log(data);
        if (data == 1)
        {
            var url = "php/modulos/reportes/ventas.php?search=distribuidoras_activas&id_territorio=1,2,3";
        } else
        {
            var url = "php/modulos/reportes/ventas.php?search=almacen_representantes&id_representante= " + data;
        }
        $http({
            method: "GET",
            url: url,

        }).success(function (response) {

            $scope.almacen_representantes = response.data;

        });
    };

    // <editor-fold defaultstate="collapsed" desc="Mostrar Grafica Cupos">

    $scope.FiltroCupos = function (id_territorio, id_representante, id_almacen_representante, id_temporada)
    {
        $scope.xsentencia = "";
        $scope.xsql = "1=1 ";

        if (typeof id_territorio !== 'undefined' && id_territorio != 4)
        {
            $scope.xsql = $scope.xsql + ' and tcr.id_territorio=' + id_territorio;
        }
        if (typeof id_representante !== 'undefined' && id_representante != -1)
        {
            $scope.xsql = $scope.xsql + ' and tcr.id_usuario=' + id_representante;
        }
        if (typeof id_almacen_representante !== 'undefined' && id_almacen_representante != -1)
        {
            $scope.xsql = $scope.xsql + ' and tcr.id_almacen=' + id_almacen_representante;
        }
        if (typeof id_temporada !== 'undefined' && id_temporada != -1)
        {
            $scope.xsql = $scope.xsql + ' and tcr.id_temporada=' + id_temporada;
        }

        $scope.xsentencia = "SELECT " +
                "IFNULL(sum(tcr.total_premiados), 0) encuestas_periodo, " +
                "IFNULL(sum(tcr.solicitado), 0) solicitado, " +
                "IFNULL(sum(tcr.confirmado), 0) confirmado, " +
                "IFNULL(sum(tcr.aprobado), 0) aprobado, " +
                "IFNULL(sum(tcr.procesado), 0) procesado, " +
                "IFNULL(sum(tcr.legalizado), 0) legalizado, " +
                "IFNULL(ROUND(((sum(tcr.solicitado)*100)/sum(tcr.total_premiados))),0)solicitado_x_100, " +
                "IFNULL(ROUND(((sum(tcr.confirmado)*100)/sum(tcr.total_premiados))),0)confirmado_x_100, " +
                "IFNULL(ROUND(((sum(tcr.aprobado)*100)/sum(tcr.total_premiados))),0)aprobado_x_100, " +
                "IFNULL(ROUND(((sum(tcr.procesado)*100)/sum(tcr.total_premiados))),0)procesado_x_100, " +
                "IFNULL(ROUND(((sum(tcr.legalizado)*100)/sum(tcr.total_premiados))),0)legalizado_x_100, " +
                "IFNULL(sum(total_premiados) - (sum(legalizado) + sum(aprobado) + sum(solicitado) + sum(confirmado) + sum(procesado)),0) pendientes_por_solicitar, " +
                "IFNULL(ROUND(((sum(total_premiados) - (sum(legalizado) + sum(aprobado) + sum(solicitado) + sum(confirmado) + sum(procesado)))*100)/sum(total_premiados)),0) pendientes_por_solicitar_x_100 " +
                "FROM (SELECT alm.id id_almacen, " +
                "alm.nombre almacen, " +
                "ter.id id_territorio, " +
                "ter.nombre territorio, " +
                "usu.ID id_usuario, " +
                "usu.nombre visitador, " +
                "tem.id id_temporada, " +
                "tem.nombre temporada, " +
                "(alm.total_premiados + alm.supervisores) total_premiados, " +
                "sum(CASE WHEN opr.id = 1 THEN 1 ELSE 0 END) solicitado, " +
                "sum(CASE WHEN opr.id = 2 THEN 1 ELSE 0 END) confirmado, " +
                "sum(CASE WHEN opr.id = 3 THEN 1 ELSE 0 END) aprobado, " +
                "sum(CASE WHEN opr.id = 4 THEN 1 ELSE 0 END) procesado, " +
                "sum(CASE WHEN opr.id = 5 THEN 1 ELSE 0 END) legalizado " +
                "FROM redenciones red, " +
                "almacenes alm, " +
                "territorios ter, " +
                "afiliados usu, " +
                "temporada tem, " +
                "seguimiento_redencion seg, " +
                "operaciones_redencion opr " +
                "WHERE red.id_almacen = alm.id " +
                "AND ter.id = alm.id_territorio " +
                "AND usu.ID = alm.id_visitador " +
                "AND tem.id = red.temporada " +
                "AND seg.id_redencion = red.id " +
                "AND seg.id_operacion = opr.id " +
                "AND seg.id IN (SELECT max(sr.id) FROM seguimiento_redencion sr WHERE sr.id_redencion = red.id) " +
                "GROUP BY alm.id, tem.id) tcr";

        $scope.xsentencia = $scope.xsentencia + " where " + $scope.xsql;
        return $scope.xsentencia;
    };
    $scope.loadCupos = function (id_territorio_grafica_cupos, id_representante_grafica_cupos, almacenes_grafica_cupos, temporadas_grafica_cupos) {
        $scope.mensajeCupos = "";
        $("#graficaDeCupos").empty();

        $scope.xc1 = $scope.FiltroCupos(id_territorio_grafica_cupos, id_representante_grafica_cupos, almacenes_grafica_cupos, temporadas_grafica_cupos);
        $scope.generarGraficaCupos($scope.xc1);

    };

    $scope.generarGraficaCupos = function (where_validacion)
    {
        var url = "php/modulos/reportes/ventas.php?search=cupos";
        $http({

            method: "POST",
            url: url,
            data: {where_validacion_id_almacen: where_validacion}

        }).success(function (response) {
            if (response.data.length == 0) {
                alert("no hay datos para mostrar")
            } else
            {
                $scope.ecu_temporada = response.data[0];
                console.log($scope.ecu_temporada);
                let chartData = [];
                let chartResponse = response.data;


                for (i in chartResponse) {
                    x = chartResponse[i];
                    chartData.push({
                        year: x.temporada,
                        pendientes_por_solicitar: parseInt(x.pendientes_por_solicitar),
                        pendientes_por_solicitar_x_100: parseInt(x.pendientes_por_solicitar_x_100),
                        procesado: parseInt(x.procesado),
                        procesado_x_100: parseInt(x.procesado_x_100),
                        legalizado: parseInt(x.legalizado),
                        legalizado_x_100: parseInt(x.legalizado_x_100)});

                }
                console.log(chartData)


                new Morris.Donut({
                    // ID of the element in which to draw the chart.
                    element: 'graficaDeCupos',
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.                              
                    data: [
                        {value: chartData[0].pendientes_por_solicitar, label: 'Por Solicitar', formatted: chartData[0].pendientes_por_solicitar_x_100 + '%'},
                        {value: chartData[0].procesado, label: 'Procesado', formatted: chartData[0].procesado_x_100 + '%'},
                        {value: chartData[0].legalizado, label: 'Legalizado', formatted: chartData[0].legalizado_x_100 + '%'}
                    ],
                    backgroundColor: '#ccc',
                    labelColor: '#060',
                    colors: [
                        '#ff0000',
                        '#fbf000',
                        '#00ff00'
                    ],
                    formatter: function (x, data) {
                        return data.formatted;
                    }
                }).on('click', function (i, row) {
                    console.log(i, row);
                });
            }
        });

    };

    $scope.loadCuposPrecargadas = function () {
        $scope.mensajeCupos = "";
        $("#graficaDeCupos").empty();
        var where_validacion_id_almacen = 1;
        var where_validacion_id_temporada = 19;
        var url = "php/modulos/reportes/ventas.php?search=cupos";
        $http({

            method: "POST",
            url: url,
            data: {where_validacion_id_almacen: where_validacion_id_almacen, where_validacion_id_temporada: where_validacion_id_temporada}

        }).success(function (response) {
            if (response.data.length == 0) {
                alert("no hay datos para mostrar")
            } else
            {
                $scope.ecu_temporada = response.data[0];
                console.log($scope.ecu_temporada);
                let chartData = [];
                let chartResponse = response.data;


                for (i in chartResponse) {
                    x = chartResponse[i];
                    chartData.push({
                        year: x.temporada,
                        pendientes_por_solicitar: parseInt(x.pendientes_por_solicitar),
                        pendientes_por_solicitar_x_100: parseInt(x.pendientes_por_solicitar_x_100),
                        procesado: parseInt(x.procesado),
                        procesado_x_100: parseInt(x.procesado_x_100),
                        legalizado: parseInt(x.legalizado),
                        legalizado_x_100: parseInt(x.legalizado_x_100)});

                }
                console.log(chartData)
                new Morris.Donut({
                    // ID of the element in which to draw the chart.
                    element: 'graficaDeCupos',
                    xLabelAngle: 60,
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.                              
                    data: [
                        {value: chartData[0].pendientes_por_solicitar, label: 'Por Solicitar', formatted: chartData[0].pendientes_por_solicitar_x_100 + '%'},
                        {value: chartData[0].procesado, label: 'Procesado', formatted: chartData[0].procesado_x_100 + '%'},
                        {value: chartData[0].legalizado, label: 'Legalizado', formatted: chartData[0].legalizado_x_100 + '%'}
                    ],
                    backgroundColor: '#ccc',
                    labelColor: '#060',
                    colors: [
                        '#ff0000',
                        '#fbf000',
                        '#00ff00'
                    ],
                    formatter: function (x, data) {
                        return data.formatted;
                    }
                }).on('click', function (i, row) {
                    console.log(i, row);
                });
            }
        });

    };
    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Mostrar Grafica Impactos">
    $scope.loadImpactos = function (data) {
        console.log(data);
        $scope.mensajeVentasPrecargadas = " ";

        $("#graficaDeImpactos").empty();
        // <editor-fold defaultstate="collapsed" desc="Territorio">

        if (data.length == 0) {
            alert("no hay datos para mostrar")
        } else
        {
            let chartData = [];
            let chartResponse = data;


            for (i in chartResponse) {
                x = chartResponse[i];
                chartData.push({year: x.periodo, impactos: parseInt(x.impactos), distribuidora: x.distribuidora});

            }
            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'graficaDeImpactos',
                xLabelAngle: 60,
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.                
                data: chartData,
                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['impactos'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['impactos']
            });
            $scope.loadDropsize(data)
        }
        // </editor-fold>
    };


    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Mostrar Grafica Dropsize">
    $scope.loadDropsize = function (data) {
        console.log(data);
        $scope.mensajeDropsizePrecargadas = " ";

        $("#graficaDeDropsize").empty();

        if (data.length == 0) {
            alert("no hay datos para mostrar")
        } else
        {
            let chartData = [];
            let chartResponse = data;


            for (i in chartResponse) {
                x = chartResponse[i];
                chartData.push({year: x.periodo, calculo: parseInt(x.calculo), distribuidora: x.distribuidora});

            }
            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'graficaDeDropsize',
                xLabelAngle: 60,
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.                
                data: chartData,
                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['calculo'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Dropsize']
            });
            $scope.loadCrecimiento();
        }
    };
    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Mostrar Grafica Crecimiento">
    $scope.loadCrecimiento = function () {
        $scope.mensajeCrecimientoPrecargadas = " ";
        var where_validacion = $scope.xsentencia;
        var url = "php/modulos/reportes/ventas.php?search=crecimiento";

        $http({
            method: "POST",
            url: url,
            data: {where_validacion: where_validacion}
        }).success(function (response) {

            $("#graficaDeCrecimiento").empty();

            if (response.data.length == 0) {
                alert("no hay datos para mostrar")
            } else
            {
                let chartData = [];
                let chartResponse = response.data;

                for (i in chartResponse) {
                    x = chartResponse[i];
                    chartData.push({
                        year: x.periodo,
                        ventas: parseInt(x.ventas_descuento),
                        crecimiento: x.crecimiento
                    });

                }
                new Morris.Bar({
                    // ID of the element in which to draw the chart.
                    element: 'graficaDeCrecimiento',
                    xLabelAngle: 60,
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.                
                    data: chartData,
                    // The name of the data record attribute that contains x-values.
                    xkey: 'year',
                    // A list of names of data record attributes that contain y-values.
                    ykeys: ['ventas'],
                    // Labels for the ykeys -- will be displayed when you hover over the
                    // chart.
                    labels: ['Venta'],
                    hoverCallback: function (index, options, content, row) {
                        return "Crecimiento= " + numeral(row.crecimiento).format('0,0') + "% = $" + numeral(row.ventas).format('0,0');
                    }
                });
            }
        });
    };
    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Mostrar Grafica Ventas SKU">

    $scope.filtro = function (id_territorio, id_representante, id_almacen_representante, id_categoria_producto, id_productos, id_temporada_ventas_sku, id_periodo_ventas_sku, id_anio)
    {
        $scope.xsentencia = "";
        $scope.xsql = "1=1 ";

        if (typeof id_territorio !== 'undefined' && id_territorio != 4)
        {
            $scope.xsql = $scope.xsql + ' and ter.id=' + id_territorio;
        }
        if (typeof id_representante !== 'undefined' && id_representante != -1)
        {
            $scope.xsql = $scope.xsql + ' and alm.id_visitador=' + id_representante;
        }
        if (typeof id_almacen_representante !== 'undefined' && id_almacen_representante != -1)
        {
            $scope.xsql = $scope.xsql + ' and alm.id=' + id_almacen_representante;
        }
        if (typeof id_categoria_producto !== 'undefined' && id_categoria_producto != -1)
        {
            $scope.xsql = $scope.xsql + ' and cat.id=' + id_categoria_producto;
        }
        if (typeof id_productos !== 'undefined' && id_productos != -1)
        {
            $scope.xsql = $scope.xsql + ' and pro.id=' + id_productos;
        }
        if (typeof id_temporada_ventas_sku !== 'undefined' && id_temporada_ventas_sku != -1)
        {
            $scope.xsql = $scope.xsql + ' and tem.id=' + id_temporada_ventas_sku;
        }
        if (typeof id_periodo_ventas_sku !== 'undefined' && id_periodo_ventas_sku != -1)
        {
            $scope.xsql = $scope.xsql + ' and per.id=' + id_periodo_ventas_sku;
        }
        if (typeof id_anio !== 'undefined' && id_anio != -1)
        {
            $scope.xsql = $scope.xsql + ' and YEAR(per.inicio)=' + id_anio;
        }

        $scope.xsentencia = "SELECT                  " +
                "    ven.id_periodo,                         " +
                "    per.nombre periodo,                        " +
                "    YEAR(per.inicio) anio,                        " +
                "    MONTH(per.inicio) mes,                          " +
                "    tem.id id_temporada,                 " +
                "    tem.nombre temporada,                 " +
                "    alm.id id_distribuidora,                 " +
                "    alm.nombre distribuidora,                 " +
                "    alm.id_visitador,                 " +
                "    vis.nombre representante,                 " +
                "    ter.id id_territorio,                 " +
                "    ter.nombre territorio,                        " +
                "    sum(ventas)ventas,                        " +
                "    ifnull(des.descuento,0) descuento,                        " +
                "    case                      " +
                "        when des.descuento is null then ROUND( sum(ventas) / 1 )                 " +
                "        when des.descuento <= 0 then ROUND( sum(ventas) / 1 )                 " +
                "        else round(ventas / des.descuento )                    " +
                "    END ventas_descuento,                 " +
                "    case                                  " +
                "        when des.descuento is null then round(( round( sum(ventas) / 1 ) * 0.3 ) / 1000) " +
                "        when des.descuento <= 0 then round(( round( sum(ventas) / 1 ) * 0.3 ) / 1000)                      " +
                "        else round(( round(ventas / des.descuento ) * 0.3 ) / 1000)             " +
                "    end puntos,                              " +
                "    sum(impactos) impactos,                    " +
                "    case                               " +
                "        when des.descuento is null then ROUND(( sum(ventas) / 1 )/sum(imp.impactos))    " +
                "        when des.descuento <= 0 then ROUND(( sum(ventas) / 1)/sum(imp.impactos))                " +
                "        else ROUND(( sum(ventas) / des.descuento )/sum(imp.impactos))                   " +
                "    END calculo,                " +
                "    cat.id id_categoria_producto,                " +
                "    cat.nombre categoria_producto,                " +
                "    ven.id_producto,                " +
                "    pro.nombre producto                  " +
                "FROM     " +
                "    (" +
                "        select                " +
                "            ven.id_periodo,                " +
                "            ven.id_vendedor,                " +
                "            afi.nombre,                " +
                "            afi.cod_formas,                " +
                "            afi.id_categoria,                " +
                "            cat.nombre categoria,                " +
                "            sum(ven.valor) ventas,                " +
                "            ven.id_producto            " +
                "        FROM                   " +
                "            ventas ven                " +
                "            inner join afiliados afi on afi.id = ven.id_vendedor                " +
                "            INNER JOIN categorias cat ON cat.id = afi.id_categoria            " +
                "        group BY" +
                "            ven.id_vendedor,id_periodo" +
                "    ) ven            " +
                "    INNER JOIN (" +
                "        select" +
                "            per.id id_periodo," +
                "            per.nombre periodo,                " +
                "            imp.id_afiliado,               " +
                "            sum(imp.impactos) impactos           " +
                "        from                    " +
                "            impactos imp                " +
                "            inner join periodo per on per.id = imp.id_periodo                " +
                "        GROUP BY per.id,imp.id_afiliado  " +
                "    ) imp ON imp.id_afiliado = ven.id_vendedor AND imp.id_periodo = ven.id_periodo                " +
                "    INNER JOIN afiliado_almacen afia ON afia.id_afiliado = ven.id_vendedor                " +
                "    INNER JOIN almacenes alm ON alm.id = afia.id_almacen                " +
                "    LEFT  JOIN descuentos des on     des.id_almacen = afia.id_almacen AND (1 >= des.id_periodo_inicial AND 12 <= des.id_periodo_final )                " +
                "    INNER JOIN territorios ter ON ter.id = alm.id_territorio                " +
                "    INNER JOIN periodo per ON per.id = ven.id_periodo                " +
                "    INNER JOIN afiliados vis ON vis.id = alm.id_visitador                       " +
                "    INNER JOIN temporada tem ON tem.id = per.id_temporada                " +
                "    INNER JOIN productos pro ON pro.id = ven.id_producto                " +
                "    INNER JOIN categoria_producto cat ON cat.id = pro.id_categoria             ";

        $scope.xsentencia = $scope.xsentencia + " where " + $scope.xsql;
        return $scope.xsentencia;
    };

    $scope.agrupacion = function (data)
    {
        $scope.xagrupacion = "";

        if (data == 1)
        {
            $scope.xagrupacion = 'ter.id';
        }
        if (data == 2)
        {
            $scope.xagrupacion = 'alm.id';
        }
        if (data == 3)
        {
            $scope.xagrupacion = 'alm.id,per.id';
        }
        if (data == 4)
        {
            $scope.xagrupacion = 'tem.id,ter.id';
        }
        if (data == 5)
        {
            $scope.xagrupacion = 'per.id';
        }
        if (data == 6)
        {
            $scope.xagrupacion = 'ven.id_periodo';
        }
        if (data == 7)
        {
            $scope.xagrupacion = 'tem.id';
        }
        if (data == 8)
        {
            $scope.xagrupacion = 'ven.id_periodo,pro.id';
        }
        if (data == 9)
        {
            $scope.xagrupacion = 'ter.id,per.id';
        }
        if (data == 10)
        {
            $scope.xagrupacion = 'alm.id,tem.id';
        }
        if (data == 11)
        {
            $scope.xagrupacion = 'alm.id_visitador';
        }
        if (data == 12)
        {
            $scope.xagrupacion = 'YEAR(per.inicio)';
        }
        if (data == 13)
        {
            $scope.xagrupacion = 'pro.id';
        }
        if (data == 14)
        {
            $scope.xagrupacion = 'cat.id';
        }
        if (data == 15)
        {
            $scope.xagrupacion = 'pro.id,year(per.inicio)';
        }
        return $scope.xagrupacion;
    };

    $scope.generarGrafica = function (where_validacion, group_by_validacion, xkey, xlabel, xagrupacion)
    {
        var url = "php/modulos/reportes/ventas.php?search=ventasSku";

        $http({
            method: "POST",
            url: url,
            data: {where_validacion: where_validacion, group_by_validacion: group_by_validacion}
        }).success(function (response) {
            if (response.data.length == 0) {
                alert("no hay datos para mostrar")
            } else
            {
                let chartData = [];
                let chartResponse = response.data;

                for (i in chartResponse) {
                    x = chartResponse[i];
                    chartData.push({periodo: x.periodo,
                        valor: x.ventas_descuento,
                        distribuidora: x.distribuidora,
                        territorio: x.territorio,
                        representante: x.representante,
                        anio: x.anio,
                        temporada: x.temporada,
                        producto: x.producto,
                        categoria_producto: x.categoria_producto
                    });
                    if (xlabel == 1)
                    {
                        valor_label = x.territorio
                    }
                    if (xlabel == 2)
                    {
                        valor_label = x.periodo
                    }
                    if (xlabel == 3)
                    {
                        valor_label = x.distribuidora
                    }
                    if (xlabel == 4)
                    {
                        valor_label = x.representante
                    }

                }
                new Morris.Bar({
                    // ID of the element in which to draw the chart.
                    element: 'graficaDeVentasSku',
                    xLabelAngle: 60,
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.                
                    data: chartData,
                    // The name of the data record attribute that contains x-values.
                    xkey: xkey,
                    // A list of names of data record attributes that contain y-values.
                    ykeys: ['valor'],
                    // Labels for the ykeys -- will be displayed when you hover over the
                    // chart.
                    labels: [valor_label],
                    preUnits: '$',
                    hoverCallback: function (index, options, content, row) {
                        if (xagrupacion === 1)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 2)
                        {
                            return row.distribuidora + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 3)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 4)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 5)
                        {
                            return row.periodo + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 6)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 7)
                        {
                            return row.temporada + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 8)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 9)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 10)
                        {
                            return row.territorio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 11)
                        {
                            return row.representante + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 12)
                        {
                            return row.anio + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 13)
                        {
                            return row.producto + "= $" + numeral(row.valor).format('0,0');
                        }
                        if (xagrupacion === 14)
                        {
                            return row.categoria_producto + "= $" + numeral(row.valor).format('0,0');
                        }
                    }
                });
                $scope.loadImpactos(response.data);
            }
        });
    };

    $scope.loadVentasSku = function (id_territorio, id_representante, id_almacen_representante, id_categoria_producto, id_productos, id_temporada_ventas_sku, id_periodo_ventas_sku, id_anio) {
        console.log(id_territorio);
        console.log(id_representante);
        console.log(id_almacen_representante);
        console.log(id_categoria_producto);
        console.log(id_productos);
        console.log(id_temporada_ventas_sku);
        console.log(id_periodo_ventas_sku);
        console.log(id_anio);
        $scope.mensajeVentasSKUPrecargadas = " ";
        $("#graficaDeVentasSku").empty();

        $scope.x1 = $scope.filtro(id_territorio, id_representante, id_almacen_representante, id_categoria_producto, id_productos, id_temporada_ventas_sku, id_periodo_ventas_sku, id_anio);

        /*1  00000000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            alert("Debe Selecccionar Algun Filtro")
        }
        /*2  00000001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            if (id_anio == -1)
            {
                $scope.x2 = $scope.agrupacion(12);
                $scope.generarGrafica($scope.x1, $scope.x2, 'anio', 3, 12);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*3  00000010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            if (id_periodo_ventas_sku == -1)
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*4  00000011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*5  00000100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            if (id_temporada_ventas_sku == -1)
            {
                $scope.x2 = $scope.agrupacion(7);
                $scope.generarGrafica($scope.x1, $scope.x2, 'temporada', 3, 7);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*6  00000101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*7  00000110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*8  00000111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*9  00001000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            if (id_productos == -1)
            {
                $scope.x2 = $scope.agrupacion(13);
                $scope.generarGrafica($scope.x1, $scope.x2, 'producto', 3, 13);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*10  00001001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {

            $scope.x2 = $scope.agrupacion(15);
            $scope.generarGrafica($scope.x1, $scope.x2, 'anio', 2, 13);

        }
        /*11  00001010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*12  00001011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*13  00001100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*14  00001101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*15  00001110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*16  00001111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*17  00010000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            if (id_categoria_producto == -1)
            {
                $scope.x2 = $scope.agrupacion(14);
                $scope.generarGrafica($scope.x1, $scope.x2, 'categoria_producto', 3, 14);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*18  00010001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*19  00010010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*20  00010011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*21  00010100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*22  00010101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*23  00010110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*24  00010111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*25  00011000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*26  00011001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*27  00011010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*28  00011011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*29  00011100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*30  00011101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*31  00011110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*32  00011111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*33  00100000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            if (id_almacen_representante == -1)
            {
                $scope.x2 = $scope.agrupacion(2);
                $scope.generarGrafica($scope.x1, $scope.x2, 'distribuidora', 3, 2);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*34  00100001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*35  00100010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*36  00100011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*37  00100100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*38  00100101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*39  00100110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*40  00100111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*41  00101000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*42  00101001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*43  00101010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*44  00101011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*45  00101100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*46  00101101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*47  00101110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*48  00101111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*49  00110000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*50  00110001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*51  00110010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*52  00110011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*53  00110100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*54  00110101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*55  00110110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*56  00110111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*57  00111000  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*58  00111001  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*59  00111010  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*60  00111011  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*61  00111100  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*62  00111101  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*63  00111110  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*64  00111111  */if (typeof id_territorio === 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*65  01000000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            if (id_representante == -1)
            {
                $scope.x2 = $scope.agrupacion(11);
                $scope.generarGrafica($scope.x1, $scope.x2, 'representante', 3, 11);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
            $scope.x2 = $scope.agrupacion(5);
        }
        /*66  01000001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*67  01000010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*68  01000011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*69  01000100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*70  01000101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*71  01000110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*72  01000111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*73  01001000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*74  01001001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*75  01001010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*76  01001011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*77  01001100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*78  01001101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*79  01001110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*80  01001111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*81  01010000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*82  01010001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*83  01010010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*84  01010011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*85  01010100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*86  01010101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*87  01010110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*88  01010111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*89  01011000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*90  01011001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*91  01011010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*92  01011011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*93  01011100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*94  01011101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*95  01011110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*96  01011111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*97  01100000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*98  01100001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*99  01100010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*100  01100011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*101  01100100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*102  01100101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*103  01100110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*104  01100111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*105  01101000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*106  01101001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*107  01101010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*108  01101011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*109  01101100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*110  01101101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*111  01101110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*112  01101111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*113  01110000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*114  01110001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*115  01110010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*116  01110011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*117  01110100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*118  01110101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*119  01110110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*120  01110111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*121  01111000  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*122  01111001  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*123  01111010  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*124  01111011  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*125  01111100  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*126  01111101  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*127  01111110  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*128  01111111  */if (typeof id_territorio === 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*129  10000000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            if (id_territorio == 4)
            {
                $scope.x2 = $scope.agrupacion(1);
                $scope.generarGrafica($scope.x1, $scope.x2, 'territorio', 3, 1);
            } else
            {
                $scope.x2 = $scope.agrupacion(5);
                $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 3, 5);
            }
        }
        /*130  10000001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*131  10000010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*132  10000011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*133  10000100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*134  10000101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*135  10000110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*136  10000111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*137  10001000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*138  10001001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*139  10001010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*140  10001011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*141  10001100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*142  10001101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*143  10001110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*144  10001111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*145  10010000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*146  10010001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*147  10010010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*148  10010011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*149  10010100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*150  10010101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*151  10010110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*152  10010111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*153  10011000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*154  10011001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*155  10011010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*156  10011011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*157  10011100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*158  10011101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*159  10011110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*160  10011111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*161  10100000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*162  10100001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*163  10100010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*164  10100011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*165  10100100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*166  10100101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*167  10100110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*168  10100111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*169  10101000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*170  10101001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*171  10101010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*172  10101011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*173  10101100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*174  10101101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*175  10101110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*176  10101111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*177  10110000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*178  10110001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*179  10110010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*180  10110011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*181  10110100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*182  10110101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*183  10110110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*184  10110111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*185  10111000  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*186  10111001  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*187  10111010  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*188  10111011  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*189  10111100  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*190  10111101  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*191  10111110  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*192  10111111  */if (typeof id_territorio !== 'undefined' && typeof id_representante === 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*193  11000000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*194  11000001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*195  11000010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*196  11000011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*197  11000100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*198  11000101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*199  11000110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*200  11000111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*201  11001000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*202  11001001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*203  11001010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*204  11001011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*205  11001100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*206  11001101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*207  11001110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*208  11001111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*209  11010000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*210  11010001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*211  11010010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*212  11010011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*213  11010100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*214  11010101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*215  11010110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*216  11010111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*217  11011000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*218  11011001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*219  11011010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*220  11011011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*221  11011100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*222  11011101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*223  11011110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*224  11011111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante === 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*225  11100000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*226  11100001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*227  11100010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*228  11100011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*229  11100100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*230  11100101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*231  11100110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*232  11100111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*233  11101000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*234  11101001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*235  11101010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*236  11101011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*237  11101100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*238  11101101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*239  11101110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*240  11101111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto === 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*241  11110000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*242  11110001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*243  11110010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*244  11110011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*245  11110100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*246  11110101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*247  11110110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*248  11110111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos === 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*249  11111000  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*250  11111001  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*251  11111010  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*252  11111011  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku === 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*253  11111100  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*254  11111101  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku === 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*255  11111110  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio === 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }
        /*256  11111111  */if (typeof id_territorio !== 'undefined' && typeof id_representante !== 'undefined' && typeof id_almacen_representante !== 'undefined' && typeof id_categoria_producto !== 'undefined' && typeof id_productos !== 'undefined' && typeof id_temporada_ventas_sku !== 'undefined' && typeof id_periodo_ventas_sku !== 'undefined' && typeof id_anio !== 'undefined') {
            $scope.x2 = $scope.agrupacion(5);
            $scope.generarGrafica($scope.x1, $scope.x2, 'periodo', 2, 5);
        }





    };
    // </editor-fold>

    $scope.ObtenerIndicadoresTerritorio = function (id_categoria_producto, id_portafolio)
    {
        if (typeof id_categoria_producto == "undefined")
        {
            $scope.filtro_id_categoria_producto = 0;
        } else
        {
            console.log("aqui tamben")
            $scope.filtro_id_categoria_producto = id_categoria_producto;
        }
        if (typeof id_portafolio == "undefined")
        {
            $scope.filtro_id_portafolio = 0;
        } else
        {
            $scope.filtro_id_portafolio = id_portafolio;
        }

        var parametros = {
            id_portafolio: $scope.filtro_id_portafolio,
            id_categoria: $scope.filtro_id_categoria_producto
        };
        $scope.EjecutarLlamado("reportes", "obtener_indicadores_territorio", parametros, $scope.MostrarIndicadoresTerritorio);
    };

    $scope.MostrarIndicadoresTerritorio = function (data)
    {
        $scope.DatosGraficasTerritorio = data;
        $scope.ObtenerIndicadoresDistribuidoras();
    };

    $scope.ObtenerIndicadoresDistribuidoras = function ()
    {
        var parametros = {
            id_portafolio: $scope.filtro_id_portafolio,
            id_categoria: $scope.filtro_id_categoria_producto
        };
        $scope.EjecutarLlamado("reportes", "obtener_indicadores_distribuidora", parametros, $scope.MostrarIndicadoresDistribuidoras);
    };

    $scope.MostrarIndicadoresDistribuidoras = function (data)
    {
        $scope.DatosGraficasDistribuidora = data;
        $scope.ObtenerIndicadoresRepresentantes();
    };

    $scope.ObtenerIndicadoresRepresentantes = function ()
    {
        var parametros = {
            id_portafolio: $scope.filtro_id_portafolio,
            id_categoria: $scope.filtro_id_categoria_producto
        };
        $scope.EjecutarLlamado("reportes", "obtener_indicadores_representantes", parametros, $scope.MostrarIndicadoresRepresentantes);
    };

    $scope.MostrarIndicadoresRepresentantes = function (data)
    {
        $scope.DatosGraficasRepresentantes = data;
        $scope.MostrarNuevaGraficaVentasTerritorio();
    };

    // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Territorios">
    $scope.MostrarNuevaGraficaVentasTerritorio = function ()
    {
        $("#NuevaGraficaVentasTerritorio").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasTerritorio.data;


        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({territorio: x.territorio, ventas: parseInt(x.ventas)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaVentasTerritorio',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'territorio',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['ventas'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Ventas'],
            preUnits: '$',
            hoverCallback: function (index, options, content, row) {
                return "$" + numeral(row.ventas).format('0,0');
            }
        });
        $scope.MostrarNuevaGraficaImpactosTerritorio();

    };

    $scope.MostrarNuevaGraficaImpactosTerritorio = function ()
    {
        $("#NuevaGraficaImpactosTerritorio").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasTerritorio.data;


        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({territorio: x.territorio, impactos: parseInt(x.impactos)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaImpactosTerritorio',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'territorio',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['impactos'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['impactos'],
            hoverCallback: function (index, options, content, row) {
                return row.impactos;
            }
        });
        $scope.MostrarNuevaGraficaDropSizeTerritorio();

    };

    $scope.MostrarNuevaGraficaDropSizeTerritorio = function ()
    {
        $("#NuevaGraficaDropsizeTerritorio").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasTerritorio.data;


        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({territorio: x.territorio, dropzise: parseInt(x.dropzise)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaDropsizeTerritorio',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'territorio',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['dropzise'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['dropzise'],
            hoverCallback: function (index, options, content, row) {
                return row.dropzise;
            }
        });
        $scope.MostrarNuevaGraficaVentasDistribuidora();
    };

    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Distribuidoras">
    $scope.MostrarNuevaGraficaVentasDistribuidora = function () {

        $("#NuevaGraficaVentasDistribuidora").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasDistribuidora.data;

        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({distribuidora: x.distribuidora, ventas: parseInt(x.ventas)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaVentasDistribuidora',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'distribuidora',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['ventas'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Ventas'],
            preUnits: '$',
            hoverCallback: function (index, options, content, row) {
                return row.distribuidora + "= $" + numeral(row.ventas).format('0,0');
            },
            horizontal: true,
            gridTextSize: 8
        });
        $scope.MostrarNuevaGraficaImpactosDistribuidora();

    };

    $scope.MostrarNuevaGraficaImpactosDistribuidora = function () {

        $("#NuevaGraficaImpactosDistribuidora").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasDistribuidora.data;

        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({distribuidora: x.distribuidora, impactos: parseInt(x.impactos)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaImpactosDistribuidora',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'distribuidora',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['impactos'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['impactos'],
            hoverCallback: function (index, options, content, row) {
                return row.distribuidora + " =" + numeral(row.impactos).format('0,0');
            }, horizontal: true,
            gridTextSize: 8
        });
        $scope.MostrarNuevaGraficaDropSizeDistribuidora();

    };

    $scope.MostrarNuevaGraficaDropSizeDistribuidora = function () {

        $("#NuevaGraficaDropsizeDistribuidora").empty();


        let chartData = [];
        let chartResponse = $scope.DatosGraficasDistribuidora.data;


        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({distribuidora: x.distribuidora, dropzise: parseInt(x.dropzise)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaDropsizeDistribuidora',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'distribuidora',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['dropzise'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['dropzise'],
            hoverCallback: function (index, options, content, row) {
                return row.distribuidora + " =" + numeral(row.dropzise).format('0,0');
            },
            horizontal: true,
            gridTextSize: 8
        });
        $scope.MostrarNuevaGraficaVentasRepresentante();

    };

    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Mostrar Nueva Grafica Distribuidoras">
    $scope.MostrarNuevaGraficaVentasRepresentante = function () {

        $("#NuevaGraficaVentasRepresentante").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasRepresentantes.data;

        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({representante: x.representante, ventas: parseInt(x.ventas), total: 0});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaVentasRepresentante',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'representante',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['ventas'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Ventas'],
            preUnits: '$',
            horizontal: true,
            gridTextSize: 8

        });
        $scope.MostrarNuevaGraficaImpactosRepresentante();

    };

    $scope.MostrarNuevaGraficaImpactosRepresentante = function () {

        $("#NuevaGraficaImpactosRepresentante").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasRepresentantes.data;

        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({representante: x.representante, impactos: parseInt(x.impactos)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaImpactosRepresentante',
            xLabelAngle: 60,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'representante',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['impactos'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['impactos'],
            horizontal: true,
            gridTextSize: 8
        });
        $scope.MostrarNuevaGraficaDropSizeRepresentante();

    };

    $scope.MostrarNuevaGraficaDropSizeRepresentante = function () {

        $("#NuevaGraficaDropsizeRepresentante").empty();

        let chartData = [];
        let chartResponse = $scope.DatosGraficasRepresentantes.data;

        for (i in chartResponse) {
            x = chartResponse[i];
            chartData.push({representante: x.representante, dropzise: parseInt(x.dropzise)});

        }
        new Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'NuevaGraficaDropsizeRepresentante',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.                
            data: chartData,
            // The name of the data record attribute that contains x-values.
            xkey: 'representante',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['dropzise'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['dropzise'],
            horizontal: true,
            gridTextSize: 8
        });

    };

    // </editor-fold> 

    $scope.EjecutarLlamado = function (modelo, operacion, parametros, CallBack) {
        $http({
            method: "POST",
            url: "clases/jarvis.php",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: {modelo: modelo, operacion: operacion, parametros: parametros}
        }).success(function (data) {
            if (data.error == "") {
                CallBack(data.data);
            } else {
                $scope.errorGeneral = data.error;
            }
        });
    };
    $scope.loadTemporadas();
});
