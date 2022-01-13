angular.module('catalogoApp', []).controller('catalogoController', function($scope, $http, $document) {



    // <editor-fold defaultstate="collapsed" desc="Listado Premios">

    $scope.ObtenerCategoriaPremios = function() {
        var parametros = { catalogo: "categoria_premios" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostarCategoriaPremios);
    };

    $scope.MostarCategoriaPremios = function(data) {
        $scope.categoria_premios = data;
        $scope.ObtenerPremios(0);
    };

    $scope.ObtenerPremios = function(data) {
        $scope.inicio = data;
        if ($scope.inicio == 0) {
            var parametros = { catalogo: "premios" };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
        }
        if ($scope.inicio == 1) {
            var parametros = { catalogo: "premios_menor_mayor" };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
        }
        if ($scope.inicio == 2) {
            var parametros = { catalogo: "premios_mas_redimidos" };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
        }
        if ($scope.inicio == 3) {
            var parametros = { catalogo: "redimir_hoy", saldo_actual: usuario_en_sesion.saldo_actual };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
        }
        if ($scope.inicio == 4) {
            var esfuerzate = usuario_en_sesion.saldo_actual * 3;
            var parametros = { catalogo: "si_te_esfuerzas", saldo_actual: esfuerzate };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
        }
    };

    $scope.MostrarPremios = function(data) {
        $scope.premios_total = data;
        $scope.MostrarPremioDeLaSemana();
    };

    $scope.MostrarPremioDeLaSemana = function() {
        if ($scope.id_premio != 0) {
            $scope.premios = Array();
            $scope.lista_almacenes = Array();
            angular.forEach($scope.premios_total, function(premio) {
                if (premio.id_premio == $scope.id_premio) {
                    $scope.premios.push(premio);
                }
            });
            $scope.cantidad_paginas = Math.ceil($scope.premios.length / $scope.items_por_pagina) - 1;
            $scope.SeleccionarPaginaListaVisible(0)
        } else {
            $scope.SeleccionarPremiosVisibles();
        }
    };

    $scope.items_por_pagina = 6;
    $scope.filtros = { nombre: "", id_categoria: "0" };
    $scope.SeleccionarPremiosVisibles = function() {
        console.log($scope.filtros);
        $scope.premios = Array();
        $scope.lista_almacenes = Array();
        angular.forEach($scope.premios_total, function(premio) {
            if (
                ($scope.filtros.nombre.length == 0 || premio.premio.toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) &&
                ($scope.filtros.id_categoria == 0 || premio.id_categoria == $scope.filtros.id_categoria)
            ) {
                $scope.premios.push(premio);
            }
        });

        $scope.cantidad_paginas = Math.ceil($scope.premios.length / $scope.items_por_pagina) - 1;
        $scope.SeleccionarPaginaListaVisible(0);
    };

    $scope.range = function(min, max, step) {
        step = step || 1;
        var input = [];
        for (var i = min; i <= max; i += step)
            input.push(i);
        return input;
    };

    $scope.SeleccionarPaginaListaVisible = function(index) {
        $scope.pagina_actual = index;
        var inicio = $scope.items_por_pagina * index;
        var final = ($scope.items_por_pagina * index) + $scope.items_por_pagina;
        $scope.premios_visibles = $scope.premios.slice(inicio, final);
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Lista Carrito">

    $scope.SeleccionarPremio = function(index) {
        $scope.premio_seleccionado = $scope.premios_visibles[index];
    };

    $scope.carrito = { puntos: 0, elementos: [] };
    $scope.AgregarAlCarrito = function() {
        var premio_temp = angular.copy($scope.premio_seleccionado);
        console.log($scope.saldo_disponible + ">=" + ($scope.carrito.puntos + parseInt(premio_temp.puntos_actuales)));

        if ($scope.datos_usuario.saldo_actual >= ($scope.carrito.puntos + parseInt(premio_temp.puntos_actuales))) {
            $scope.premio_seleccionado.comentario = "";
            $scope.premio_seleccionado.puntos = $scope.premio_seleccionado.puntos_actuales;
            $scope.carrito.elementos.push($scope.premio_seleccionado);

            $scope.carrito.puntos += $scope.premio_seleccionado.puntos_actuales;
            $scope.saldo_disponible -= $scope.premio_seleccionado.puntos_actuales;
        } else {
            CallToast("No tienes saldo suficiente para redimir este premio");
        }
    };

    $scope.QuitarDelCarrito = function(index) {
        $scope.carrito.elementos.splice(index, 1);
        $scope.carrito.puntos -= parseInt($scope.premio_seleccionado.puntos_actuales);
        $scope.saldo_disponible += parseInt($scope.premio_seleccionado.puntos_actuales);
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Ciudad">

    $scope.nombre_ciudad = "";
    $scope.ciudades = [];
    $scope.BuscarCiudad = function() {
        var parametros = { catalogo: "ciudad", nombre_ciudad: $scope.nombre_ciudad };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCiudades);
    };

    $scope.MostrarCiudades = function(data) {
        $scope.ciudades = data;
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Redencion">

    $scope.direccion = "";
    $scope.redenciones_registrada = null;
    $scope.GuardarRedenciones = function() {
        var parametros = {
            premios: $scope.carrito.elementos,
            id_usuario: $scope.datos_usuario.id,
            direccion_envio: $scope.datos_usuario.direccion,
            id_registra: $scope.usuario_en_sesion.id,
            ciudad: $scope.nombre_ciudad
        };

        $scope.EjecutarLlamado("especiales", "registrar_redenciones", parametros, $scope.ResultadoRegistroRedenciones);
    };

    $scope.ResultadoRegistroRedenciones = function(data) {
        if (data.ok) {
            $scope.redenciones_registrada = data.redenciones;
            console.log($scope.redenciones_registrada);
        } else {
            $scope.redenciones_registrada = 0;
            CallToast(data.error);
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
                console.log(data);
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.usuario_en_sesion = usuario_en_sesion;
    $scope.id_premio = id_premio;
    $scope.id_usuario = id_usuario;
    $scope.CargarDatosUsuario();

    $(function() {
        $('#modal_resultado_registro').on('hidden.bs.modal', function(e) {
            if ($(e.target).attr("id") == "modal_resultado_registro") {
                document.location.reload();
            }
        })
    });

});