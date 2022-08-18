angular.module('catalogoApp', []).controller('catalogoController', function($scope, $http, $timeout) {

    // <editor-fold defaultstate="collapsed" desc="Listado Premios">

    $scope.CargarDatosUsuario = function() {
        var parametros = { catalogo: "afiliados", id: $scope.id_usuario };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDatosUsuario);
    };

    $scope.MostrarDatosUsuario = function(data) {
        $scope.datos_usuario = data[0];
        $scope.saldo_disponible = $scope.datos_usuario.saldo_actual == null ? 0 : parseInt($scope.datos_usuario.saldo_actual);
        $scope.nombre_ciudad = $scope.datos_usuario.ciudad_departamento;
        $scope.direccion = $scope.datos_usuario.DIRECCION;
        $scope.ObtenerCategoriaPremios();

    };

    $scope.ObtenerCategoriaPremios = function() {
        var parametros = { catalogo: "categoria_premio" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostarCategoriaPremios);
    };

    $scope.MostarCategoriaPremios = function(data) {
        $scope.categoria_premios = data;
        $scope.ObtenerPuntosEstadoCuenta();
    };

    $scope.ObtenerPuntosEstadoCuenta = function() {

        var parametros = { catalogo: "total_puntos_estado_cuenta", id_vendedor: $scope.id_usuario };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostarPuntosEstadoCuenta);
    }

    $scope.MostarPuntosEstadoCuenta = function(data) {
        $scope.puntos_estado_cuenta = data;
        $scope.ObtenerPremios(0);
    }

    $scope.ObtenerPremios = function(data) {
        $scope.inicio = data;
        if ($scope.inicio == 0) {
            var parametros = { catalogo: "premios" };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
        }
        /*if ($scope.inicio == 1) {
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
        }*/
    };

    $scope.MostrarPremios = function(data) {
        $scope.premios_total = data;
        $scope.SeleccionarPremiosVisibles(0);
    };

    $scope.MostrarBannerCategora = function(id) {
        $("#banner_categoria").html("");
        $("#banner_categoria").append("<img class='cate_logo_banner' src='images/logos_catalogo/banner_catalogo/" + id + ".jpg' alt='No disponible' />")

    }

    $scope.items_por_pagina = 6;
    $scope.filtros = { nombre: "", id_categoria: "0" };
    $scope.SeleccionarPremiosVisibles = function(id_categoria) {
        $scope.filtros.id_categoria = id_categoria;
        $scope.premios = Array();
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

    $scope.MostrarPremioSeleccionado = function(index) {
        $scope.premio_seleccionado = $scope.premios_visibles[index];
    };

    $scope.CambiarCantidadPremio = function(cantidad) {

        if (cantidad == -1 && $scope.premio_seleccionado.CANTIDAD > 1) {
            $scope.premio_seleccionado.CANTIDAD--;
        }

        if (cantidad == 1) {
            $scope.premio_seleccionado.CANTIDAD++;
        };
    };

    $scope.puntos_totales = [];
    $scope.agregar = 0;
    $scope.ConfirmarPremioSeleccionado = function(index) {
        $scope.premio_seleccionado = $scope.premios_visibles[index];
        $scope.premio_seleccionado.CANTIDAD = 1;
        for (var i = 0; i < $scope.premio_seleccionado.CANTIDAD; i++) {
            $scope.puntos_totales.push($scope.premio_seleccionado.puntos)
            $scope.AgregarAlCarrito($scope.premio_seleccionado.ID);
        }


    };

    $scope.carrito = { puntos: 0, elementos: [] };
    $scope.AgregarAlCarrito = function() {

        $scope.suma_puntos = $scope.puntos_totales.reduce((ant, act) => { return parseInt(ant) + parseInt(act) })

        var premio_temp = angular.copy($scope.premio_seleccionado);

        if (parseInt($scope.datos_usuario.saldo_actual) >= parseInt($scope.suma_puntos)) {
            console.log($scope.puntos_estado_cuenta[0].puntos)
            if ($scope.puntos_estado_cuenta[0].puntos >= 150) {
                premio_temp.comentario = "";
                $scope.carrito.elementos.push(premio_temp);

                $scope.carrito.puntos += premio_temp.puntos_actuales;
                $scope.saldo_disponible -= premio_temp.puntos_actuales;
                $scope.agregar = 1;
                $timeout(function() {
                    $scope.agregar = 0;
                }, 500);
            } else {
                alert("No tienes 150 puntos minimos en el estado de cuenta")
            }

        } else {
            alert("No tienes saldo suficiente para redimir este premio");
        }
    };

    $scope.QuitarDelCarrito = function(index) {
        $scope.puntos_totales.splice(index, 1);
        $scope.carrito.elementos.splice(index, 1);
        $scope.carrito.puntos -= parseInt($scope.premio_seleccionado.puntos_actuales);
        $scope.saldo_disponible += parseInt($scope.premio_seleccionado.puntos_actuales);
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Ciudad">

    $scope.BuscarDepartamentos = function() {
        var parametros = {
            catalogo: "departamento"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDepartamento);
    };

    $scope.MostrarDepartamento = function(data) {
        $scope.departamento = data;
    };
    $scope.CargarCiudades = function(id) {

        var parametros = {
            catalogo: "ciudad",
            departamento: id
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCiudades);
    };

    $scope.MostrarCiudades = function(data) {
        $scope.ciudades = data;
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Redencion">
    $scope.datos_envio = {
        correo: "",
        cambio_correo: "",
        nuevo_correo: "",
        cambio_celular: "",
        nuevo_celular: "",
        operador: "0"
    }
    $scope.direccion = "";
    $scope.redenciones_registrada = null;

    $scope.ConfirmarRedencion = function() {

        $("#modal_confirmar").modal("show");
        $scope.habilitar_exito = false;
        $scope.habilitar_recarga = false;
        $scope.habilitar_bono = false;

        angular.forEach($scope.carrito.elementos, function(premio) {

            if (premio.id_categoria == 1) {
                $scope.habilitar_exito = true;
            } else if (premio.id_categoria == 9) {
                $scope.habilitar_recarga = true;
            } else {
                $scope.habilitar_bono = true;
            }
        });

    };

    $scope.ActualizarCorreo = function() {
        if ($scope.datos_envio.cambio_correo) {
            $("#nuevo_correo").removeClass("hide")
        } else {
            $("#nuevo_correo").addClass("hide")
        }

    };
    $scope.ActualizarCelular = function() {
        if ($scope.datos_envio.cambio_celular) {
            $("#nuevo_celular").removeClass("hide")
        } else {
            $("#nuevo_celular").addClass("hide")
        }
    };

    $scope.GuardarRedenciones = function() {
        var legalizacion_completa = true;
        $scope.mensaje = "";
        if ($scope.datos_envio.cambio_correo) {
            $scope.datos_usuario.EMAIL = $scope.datos_envio.nuevo_correo
        }
        if ($scope.datos_envio.cambio_celular) {
            $scope.datos_usuario.CELULAR = $scope.datos_envio.nuevo_celular
        }

        if (legalizacion_completa) {
            $("#verificacion_registro").modal('show');
        } else {
            alert($scope.mensaje);
        }
    };

    $scope.FinalizarRedencion = function(data) {
        $scope.boton_aceptar = 1;

        var parametros = {
            premios: $scope.carrito.elementos,
            id_usuario: $scope.id_usuario,
            correo_envio: $scope.datos_usuario.EMAIL,
            numero_envio: $scope.datos_usuario.CELULAR,
            operador: $scope.datos_envio.operador,
            id_registra: $scope.usuario_en_sesion.id,
            exito: $scope.habilitar_exito,
            recarga: $scope.habilitar_recarga,
            bono: $scope.habilitar_bono,
            cambio_correo: $scope.datos_envio.cambio_correo,
            cambio_telefono: $scope.datos_envio.cambio_celular
        };

        $scope.EjecutarLlamado("afiliados", "enviar_correo_redencion", parametros, $scope.ResultadoRegistroRedenciones);

    };

    $scope.ResultadoRegistroRedenciones = function(data) {
        console.log(data)
        if (data.ok) {
            /*var settings = {
                "async": true,
                "crossDomain": true,
                "url": "https://api.ultramsg.com/instance5858/messages/chat",
                "method": "POST",
                "headers": {},
                "data": {
                    "token": "34hkinvz25rqkxf3",
                    "to": "+57" + $scope.datos_usuario.CELULAR,
                    "body": "¡Felicitaciones! Tu bono solicitado en Socios y Amigos llegara pronto a tu correo",
                    "priority": "1",
                    "referenceId": ""
                }
            }

            $http(settings).success(function(response) {
                console.log(response);
            });*/
            $("#modal_resultado_registro").modal("show");
        } else {
            $scope.redenciones_registrada = 0;
            alert(data.error);
        }
    }


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
    //$scope.id_premio = id_premio;


    $(function() {
        $('#modal_resultado_registro').on('hidden.bs.modal', function(e) {
            if ($(e.target).attr("id") == "modal_resultado_registro") {
                document.location.reload();
            }
        })
    });

    $scope.CargarDatosUsuario();
    $scope.BuscarDepartamentos();
});