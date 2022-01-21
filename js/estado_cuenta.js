angular.module('estadoCuentaApp', []).controller('estadoCuentaController', function($scope, $http, $document) {

    $scope.filtros_ventas = {
        temporada: ""
    };
    $scope.filtros_tickets = {
        tickets: ""
    };

    $scope.CargarDatosUsuario = function() {
        var parametros = {
            catalogo: "afiliados",
            id: $scope.usuario_en_sesion.id
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDatosUsuario);
    };

    $scope.MostrarDatosUsuario = function(data) {
        $scope.datos_usuario = data[0];
        $scope.ObtenerEstadoCuenta();
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Estado de Cuenta">

    $scope.filtros = {
        cedula: "",
        nombre: ""
    };

    $scope.ObtenerEstadoCuenta = function() {
        var parametros = {
            catalogo: "estado_cuenta_afiliado",
            id_usuario: $scope.usuario_en_sesion.id
        };
        console.log(parametros);
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEstadoCuenta);
    };

    $scope.MostrarEstadoCuenta = function(data) {
        var periodos = Array();
        var periodo_actual = {
            id_periodo: 0,
            periodo: "",
            registros: []
        };

        angular.forEach(data, function(registro) {
            if (periodo_actual.id_periodo != registro.id_periodo) {
                if (periodo_actual.id_periodo > 0) {
                    console.log(periodo_actual);
                    periodos.push(periodo_actual);
                    periodo_actual = {
                        id_periodo: 0,
                        periodo: "",
                        registros: []
                    };
                }

                periodo_actual.id_periodo = registro.id_periodo;
                periodo_actual.periodo = registro.periodo;
                periodo_actual.registros = [];
            }
            periodo_actual.registros.push(registro);
        });

        periodos.push(periodo_actual);
        console.log(periodo_actual);
        $scope.estado_cuenta = periodos;
        $scope.ObtenerVentasUsuario();
    };

    $scope.ObtenerGrafica = function() {
        var parametros = {
            catalogo: "grafica_usuario_ecu",
            id_usuario: id_usuario
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGrafica);
    };

    $scope.MostrarGrafica = function(data) {
        $scope.grafica = data;
        grafica = $scope.grafica;
        $scope.ObtenerPuntosUsuario();
    };

    $scope.ObtenerPuntosUsuario = function() {
        var parametros = { catalogo: "puntos_usuario", id_usuario: id_usuario };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPuntosUsuario);
    };

    $scope.MostrarPuntosUsuario = function(data) {
        $scope.puntos_usuario = data;
    };

    $scope.ObtenerVentasUsuario = function() {
        var parametros = { catalogo: "ventas_usuario", id_usuario: id_usuario };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVentasUsuario);
    };

    $scope.ventas_totales = [];
    $scope.ventas = [];
    $scope.MostrarVentasUsuario = function(data) {
        $scope.ventas_totales = data
        $scope.ventas = $scope.ventas_totales;
        //$scope.ObtenerVentasUsuarioLS();
    };

    $scope.ventas_visibles = [];
    $scope.VerDetalles = function(id_temporada) {
        $scope.ventas_visibles = [];
        var ventas_arr = $scope.ventas_totales;
        console.log(ventas_arr)
        $scope.titulo_detalle = "Detalle ventas generales";

        angular.forEach(ventas_arr, function(venta) {
            if ($scope.filtros_ventas.temporada.length == 0 || venta.temporada.toLowerCase().indexOf($scope.filtros_ventas.temporada) > -1) {
                if (venta.id_temporada == id_temporada || id_temporada == 0) {
                    $scope.ventas_visibles.push(venta);
                }
            }
        });

        setTimeout(function() {
            $("#tdetalle tr").each(function(index, row) {
                if ($(row).first().children().eq(1).html().toLowerCase().indexOf("total") >= 0) {
                    $(row).css("font-weight", "bold");
                }
            });
        }, 100);

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