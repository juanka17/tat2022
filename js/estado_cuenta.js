angular.module('estadoCuentaApp', []).controller('estadoCuentaController', function($scope, $http, $document) {

    // <editor-fold defaultstate="collapsed" desc="Datos Usuarios">

    $scope.CargarDatosUsuario = function() {
        var parametros = {
            catalogo: "afiliados",
            id: $scope.id_usuario
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDatosUsuario);
    };

    $scope.MostrarDatosUsuario = function(data) {
        $scope.datos_usuario = data[0];
        if ($scope.datos_usuario.id_rol == 4) {
            $scope.ObtenerEstadoCuenta();
        } else if ($scope.datos_usuario.id_rol == 6) {
            $scope.ObtenerEstadoCuentaSupervisores();
        } else if ($scope.datos_usuario.id_rol == 7) {
            $scope.ObtenerEstadoCuentaInformatico();
        }
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Estado de Cuenta Vendedores">
    $scope.ObtenerEstadoCuenta = function() {
        var parametros = {
            catalogo: "estado_cuenta_vendedores",
            id_vendedor: $scope.id_usuario
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEcuEmpleadosAlmacen);
    };

    $scope.MostrarEcuEmpleadosAlmacen = function(data) {
        $scope.puntos_empleados = data;
        $scope.VerDetalleEstadoCuenta($scope.id_usuario);
    };

    $scope.VerDetalleEstadoCuenta = function(data) {

        var parametros = {
            catalogo: "estado_cuenta_vendedor_detallado",
            id_vendedor: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEstadoCuentaDetalle);
    };

    $scope.MostrarEstadoCuentaDetalle = function(data) {
        var periodos = Array();
        var periodo_actual = { id_periodo: 0, periodo: "", registros: [] };
        angular.forEach(data, function(registro) {

            if (periodo_actual.id_periodo != registro.id_periodo) {
                if (periodo_actual.id_periodo > 0) {
                    periodos.push(periodo_actual);
                    periodo_actual = { id_periodo: 0, periodo: "", registros: [] };
                }

                periodo_actual.id_periodo = registro.id_periodo;
                periodo_actual.periodo = registro.periodo;
                periodo_actual.registros = [];
            }
            periodo_actual.registros.push(registro);

        });
        periodos.push(periodo_actual);
        $scope.estado_cuenta_premio = periodos;
        $scope.ObtenerGraficaEstadoCuenta();
    };

    $scope.ObtenerGraficaEstadoCuenta = function() {
        var parametros = {
            catalogo: "grafica_usuario_ecu",
            id_vendedor: $scope.id_usuario
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGrafica);
    };

    $scope.MostrarGrafica = function(data) {
        const dataSource = data;

        $(() => {
            $('#chart').dxChart({
                dataSource,
                commonSeriesSettings: {
                    argumentField: 'periodo',
                    type: 'bar',
                    hoverMode: 'allArgumentPoints',
                    selectionMode: 'allArgumentPoints',
                    label: {
                        visible: false,
                        format: {
                            type: 'fixedPoint',
                            precision: 0,
                        },
                    },
                },
                series: [
                    { valueField: 'cuota', name: 'Cuota' },
                    { valueField: 'venta', name: 'Venta' },
                ],
                title: 'Gráfica de cuotas/ventas',
                legend: {
                    verticalAlignment: 'bottom',
                    horizontalAlignment: 'center',
                },
                export: {
                    enabled: false,
                },
                onPointClick(e) {
                    e.target.select();
                },
            });
        });
        $scope.ObtenerGraficaEstadoCuentaPie();
    };

    $scope.ObtenerGraficaEstadoCuentaPie = function() {
        var parametros = {
            catalogo: "grafica_usuario_ecu_pie",
            id_vendedor: $scope.id_usuario
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGraficaPie);
    };

    $scope.MostrarGraficaPie = function(data) {
        const dataSource = data;

        $(() => {
            $('#pie').dxPieChart({
                size: {
                    width: 400,
                },
                palette: 'bright',
                dataSource,
                series: [{
                    argumentField: 'portafolio',
                    valueField: 'ventas',
                    label: {
                        format: {
                            type: 'fixedPoint',
                            precision: 0,
                        },
                        visible: true,
                        customizeText: function(pointInfo) {
                            return pointInfo.argument + ': $' + pointInfo.valueText;
                        },
                        connector: {
                            visible: true,
                            width: 1,
                        },
                    },
                }, ],
                title: 'Ventas Advil/Dolex',
                export: {
                    enabled: false,
                },
                onPointClick(e) {
                    const point = e.target;

                    toggleVisibility(point);
                },
                onLegendClick(e) {
                    const arg = e.target;

                    toggleVisibility(this.getAllSeries()[0].getPointsByArg(arg)[0]);
                },
            });

            function toggleVisibility(item) {
                if (item.isVisible()) {
                    item.hide();
                } else {
                    item.show();
                }
            }
        });

    };

    $scope.ObtenerCuotasVentasEstadoCuenta = function() {
        var parametros = {
            catalogo: "estado_cuentas_ventas_estado_cuenta",
            id_vendedor: $scope.id_usuario
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotas);
    };

    $scope.MostrarCuotas = function(data) {
        $scope.cuotas_ventas = data;
        $("#detalleEstadoCuenta").modal("show");
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Estado de Cuenta Supervisores">
    $scope.ObtenerEstadoCuentaSupervisores = function() {

        var parametros = {
            catalogo: "estado_cuenta_supervisores",
            id_vendedor: $scope.id_usuario
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEcuSupervisores);

    };

    $scope.MostrarEcuSupervisores = function(data) {
        $scope.puntos_supervisores = data;
        $scope.VerDetalleEstadoCuentaSupervisor($scope.id_usuario)
    }

    $scope.VerDetalleEstadoCuentaSupervisor = function(data) {

        var parametros = {
            catalogo: "estado_cuenta_vendedor_detallado_supervisor",
            id_vendedor: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEstadoCuentaDetalleSupervisor);
    };

    $scope.MostrarEstadoCuentaDetalleSupervisor = function(data) {
        var periodos = Array();
        var periodo_actual = { id_periodo: 0, periodo: "", registros: [] };
        angular.forEach(data, function(registro) {

            if (periodo_actual.id_periodo != registro.id_periodo) {
                if (periodo_actual.id_periodo > 0) {
                    periodos.push(periodo_actual);
                    periodo_actual = { id_periodo: 0, periodo: "", registros: [] };
                }

                periodo_actual.id_periodo = registro.id_periodo;
                periodo_actual.periodo = registro.periodo;
                periodo_actual.registros = [];
            }
            periodo_actual.registros.push(registro);

        });
        periodos.push(periodo_actual);
        $scope.estado_cuenta_premio = periodos;

    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Estado de Cuenta Informaticos">
    $scope.ObtenerEstadoCuentaInformatico = function() {

        var parametros = {
            catalogo: "estado_cuenta_informatico",
            id_vendedor: $scope.id_usuario
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEcuInformatico);

    };

    $scope.MostrarEcuInformatico = function(data) {
        $scope.puntos_informaticos = data;
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
                console.log(data);
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.usuario_en_sesion = usuario_en_sesion;
    $scope.id_usuario = id_usuario;
    $scope.CargarDatosUsuario();
    //$scope.ObtenerGrafica();
});