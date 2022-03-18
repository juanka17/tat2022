angular.module('afiliadosApp', []).controller('afiliadosController', function($scope, $http) {

    var catalogos = ["departamento"];
    var indexAsist = 0;
    var tempSeleccionado = null;

    $scope.buscando = true;
    $scope.editando = false;
    $scope.busqueda = { documento: "", nombre: "" };
    $scope.afiliadosEncontrados = [];
    $scope.acepto_terminos_y_condiciones = false;

    $scope.CargarCatalogosIniciales = function() {
        if (indexAsist < catalogos.length) {
            var parametros = { catalogo: catalogos[indexAsist] };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearCatalogo);
        } else {
            indexAsist = 0;
            $scope.ValidaUsuarioEsAfiliado();
        }
    };

    $scope.CrearCatalogo = function(data) {
        $scope[catalogos[indexAsist]] = data;
        indexAsist++;
        $scope.CargarCatalogosIniciales();
    };

    $scope.BuscarNuevoAfiliado = function() {
        $scope.busqueda = { documento: "", nombre: "" };
        $scope.afiliadosEncontrados = [];
        $scope.seleccionado = {};
        $scope.ToggleMode();
    };

    $scope.BuscarAfiliados = function(especifico) {
        if ($scope.busqueda.documento != "" || $scope.busqueda.nombre != "") {
            if (especifico) {
                $scope.EjecutarLlamado("afiliados", "BuscarAfiliados", $scope.busqueda, $scope.CargaDatosAfiliado);
            } else {
                $scope.EjecutarLlamado("afiliados", "BuscarAfiliados", $scope.busqueda, $scope.ListaAfiliadosEncontrados);
            }
        } else {
            $scope.errorGeneral = "Debe registrar nombre o documento";
        }
    };

    $scope.ListaAfiliadosEncontrados = function(data) {
        $scope.afiliadosEncontrados = data;
    };

    $scope.SeleccionaAfiliado = function(index) {
        $scope.seleccionado = $scope.afiliadosEncontrados[index];
        $scope.ProcesaAfiliadoSeleccionado();
    };

    $scope.CargaDatosAfiliado = function(data) {
        $scope.seleccionado = data[0];
        $scope.ProcesaAfiliadoSeleccionado();
    };

    $scope.ProcesaAfiliadoSeleccionado = function() {
        $scope.EjecutarLlamado("afiliados", "SeleccionaAfiliado", { id: $scope.seleccionado.ID }, $scope.SeleccionarCiudadAfiliado);
        tempSeleccionado = angular.copy($scope.seleccionado);
        $scope.CargaCiudadesDepartamento();
        $scope.ToggleMode();
    };

    $scope.CargaCiudadesDepartamento = function() {
        if ($scope.seleccionado.ID_DEPARTAMENTO != null) {
            var parametros = { catalogo: "ciudad", departamento: $scope.seleccionado.ID_DEPARTAMENTO };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.SeleccionarCiudadAfiliado);
        }
    };

    $scope.SeleccionarCiudadAfiliado = function(data) {
        $scope.ciudad = data;
    };

    $scope.ActualizarAfiliado = function() {
        var diferences = Array();

        if ($scope.seleccionado.ID_MARCA == 1000 && $scope.seleccionado.MARCA_NUEVA == "") {
            $scope.errorGeneral = "Se debe asignar un nombre a la marca nueva";
            return;
        }

        if (JSON.stringify($scope.seleccionado) != JSON.stringify(tempSeleccionado)) {
            for (var propertyName in $scope.seleccionado) {
                if (propertyName != "$$hashKey" && $scope.seleccionado[propertyName] != tempSeleccionado[propertyName])
                    if (!diferences.hasOwnProperty("ID_" + propertyName))
                        diferences.push({ property: propertyName, value: $scope.seleccionado[propertyName] });
            }
        }

        if (diferences.length > 0) {
            var parametros = { actualizados: diferences, id_afiliado: $scope.seleccionado.ID };
            if ($scope.seleccionado.ID_MARCA == 1000) {
                parametros.nuevo_almacen = $scope.MARCA_NUEVA;
            }

            $scope.EjecutarLlamado("afiliados", "ActualizarAfiliado", parametros, $scope.RespuestaActualizacion);
        } else if (datos_usuario.acepto_terminos == 0) {
            location.reload();
        }
    };

    $scope.RespuestaActualizacion = function(data) {
        $scope.operacionCorrectaMensaje = data.mensaje;
        if (datos_usuario.acepto_terminos == 0 || $scope.seleccionado.ID_MARCA == 1000) {
            location.reload();
        }
    };

    // <editor-fold defaultstate="collapsed" desc="Aceptar terminos">

    $scope.claveNueva = "";
    $scope.AceptarTerminos = function() {
        var valid = Array();
        if ($scope.seleccionado.CELULAR == "" || $scope.seleccionado.CELULAR == null) valid.push("Celular");
        if ($scope.seleccionado.EMAIL == "" || $scope.seleccionado.EMAIL == null) valid.push("Email");
        if ($scope.seleccionado.ID_DEPARTAMENTO == "" || $scope.seleccionado.ID_DEPARTAMENTO == null) valid.push("Departamento");
        if ($scope.seleccionado.ID_CIUDAD == "" || $scope.seleccionado.ID_CIUDAD == null) valid.push("Ciudad");

        if (valid.length == 0) {
            if ($scope.claveNueva == datos_usuario.cedula && $scope.claveNueva != null && $scope.claveNueva != "") {
                $scope.mensajeError = "La clave debe ser distinta de la cedula";
            } else if ($scope.claveNueva.length < 6 || $scope.claveNueva.length > 12) {
                $scope.mensajeError = "La clave debe tener entre 6 y 12 caracteres.";
            } else if ($scope.claveNueva != $scope.confirmaClaveNueva) {
                $scope.mensajeError = "La clave y la confirmación no coinciden";
            } else {
                $scope.claves = { id_afiliado: datos_usuario.id, actual: $scope.claveActual, nueva: $scope.claveNueva };
                $scope.EjecutarLlamado("afiliados", "AceptarTerminos", $scope.claves, $scope.ConfirmaAceptaTerminos);
            }
        } else {
            $scope.mensajeError = "Debes completar los siguientes campos para continuar: " + valid.join(",");
        }
    };

    $scope.ConfirmaAceptaTerminos = function(data) {
        if (data.ok) {
            $scope.ActualizarAfiliado();
        } else {
            $scope.mensajeError = data.error;
        }
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Premios sugeridos">

    $scope.ObtenerPremiosRecomendados = function() {
        $scope.datos = { id_afiliado: afiliado_seleccionado.id };
        $scope.EjecutarLlamado("afiliados", "ObtienePremiosRecomendados", $scope.datos, $scope.MuestraPremiosRecomendados);
    };

    $scope.MuestraPremiosRecomendados = function(data) {
        $scope.premiosRecomendados = data[0];
    };

    // </editor-fold>

    $scope.ToggleMode = function() {
        $scope.buscando = !$scope.buscando;
        $scope.editando = !$scope.editando;
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
    $scope.CargarCatalogosIniciales();

    $scope.ValidaUsuarioEsAfiliado = function() {

        if (typeof datos_usuario !== 'undefined' && typeof mis_datos !== 'undefined' && mis_datos) {
            $scope.datos_usuario = datos_usuario;
            $scope.busqueda.documento = datos_usuario.cedula;
            $scope.BuscarAfiliados(true);
        } else {
            if (typeof afiliado_seleccionado !== 'undefined' && afiliado_seleccionado != null) {
                if (afiliado_seleccionado.id > 0) {
                    $scope.afiliado_seleccionado_sesion = afiliado_seleccionado;
                    $scope.busqueda.documento = afiliado_seleccionado.cedula;
                    $scope.BuscarAfiliados(true);
                }
            }
        }

        if (typeof carga_estado_cuenta !== 'undefined') {
            $scope.ObtenerEstadoCuenta();
        }
    };


});