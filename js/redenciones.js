angular.module('redencionesApp', []).controller('redencionesController', function($scope, $http, $location) {

    $scope.premios = Array();
    $scope.filtros = { id_categoria: -1, nombre: "", solo_redimibles: false, premio_descripcion: -1 };
    $scope.carrito = { elementos: [], puntos: 0 };
    $scope.ORDER = "PUNTOS";

    $scope.CargarCategorias = function() {
        var parametros = { catalogo: "categoria_premio" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CargarCategoria);
    };

    $scope.CargarCategoria = function(data) {
        $scope.categorias = data;
        $scope.filtros.id_categoria = categoriaSeleccionada;
        console.log($scope.categorias);
        console.log($scope.filtros.id_categoria);
        $scope.CargarPremios();
    };

    $scope.CargarPremios = function() {
        var parametros = { catalogo: "premios" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremios);
    };

    $scope.MostrarPremios = function(data) {
        $scope.premios = data;
        console.log($scope.premios);
        $scope.DenegarSolicitudPremio();
    };

    $scope.DenegarSolicitudPremio = function() {
        var parametros = {
            catalogo: "denegar_solicitud_premio",
            id_afiliado: id_afiliado,
            id_almacen: id_almacen,
            id_temporada: id_temporada
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarPremiosDenegados);
    };

    $scope.MostrarPremiosDenegados = function(data) {
        $scope.denegar = data;
        $scope.CargarDepartamentos();
    };

    $scope.CargarDepartamentos = function() {
        var parametros = { catalogo: "departamento" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.ListarDepartamentos);
    };

    $scope.ListarDepartamentos = function(data) {
        $scope.departamento = data;
        $scope.EjecutarLlamado("afiliados", "BuscarAfiliados", { documento: $scope.afiliado.cedula, nombre: "" }, $scope.CargaDatosAfiliado);
    };

    $scope.CargaDatosAfiliado = function(data) {
        $scope.datos_afiliado = data[0];
        $scope.direccionEnvio = 0;
        $scope.CambiaDireccionEnvio();
        $scope.ProcesaAfiliadoSeleccionado();
    };

    $scope.ProcesaAfiliadoSeleccionado = function() {
        $scope.CargaCiudadesDepartamento();
    };

    $scope.CargaCiudadesDepartamento = function(cargar) {
        if ($scope.datos_afiliado.ID_DEPARTAMENTO != null) {
            var parametros = { catalogo: "ciudad", departamento: $scope.datos_afiliado.ID_DEPARTAMENTO };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.SeleccionarCiudadAfiliado);
        }
    };

    $scope.SeleccionarCiudadAfiliado = function(data) {
        $scope.ciudadesPorDepartamento = data;
    };

    $scope.CambiaDireccionEnvio = function() {
        if ($scope.direccionEnvio == 0) {
            $scope.direccionSeleccionada = $scope.datos_afiliado.DIRECCION;
            $scope.direccionDeEnvioExistente = true;
        }

        if ($scope.direccionEnvio == 1) {
            $scope.direccionSeleccionada = "";
            $scope.direccionDeEnvioExistente = false;
        }
        $scope.errorRedencion = "";
    };

    $scope.CambiarCategoria = function(id_categoria) {
        $scope.id_categoria_seleccionada = id_categoria;
    };

    $scope.MostrarDescripcion = function(index) {
        if (index == $scope.filtros.premio_descripcion)
            $scope.filtros.premio_descripcion = -1;
        else
            $scope.filtros.premio_descripcion = index;
    };

    $scope.AgregarAlCarrito = function(premioID) {
        var premio = { ID: 0 };
        angular.forEach($scope.premios, function(premio_Cat) {
            if (premio_Cat.ID == premioID) {
                premio = angular.copy(premio_Cat);
            }
        });

        if (premio.ID != 0) {
            premio.COMENTARIO = "";
            premio.ID_PREMIO_REGALO = 0;
            $scope.carrito.elementos.push(premio);
            $scope.SumarioPuntosCarrito();
        } else {
            $scope.errorRedencion = "Intente denuevo";
        }

    };

    $scope.LimpiarDatos = function() {
        $scope.iniciaRedencion = false;
        $scope.RemoverDelCarrito(0);
    };

    $scope.RemoverDelCarrito = function(premioIndex) {
        $scope.carrito.elementos.splice(premioIndex, 1);
        $scope.SumarioPuntosCarrito();
    };

    $scope.SumarioPuntosCarrito = function() {
        $scope.carrito.puntos = 0;
        angular.forEach($scope.carrito.elementos, function(premio) {
            $scope.carrito.puntos += parseInt(premio.PUNTOS);
        });
    };

    $scope.RegistrarRedencion = function() {
        var parametros = {
            id_afiliado: id_afiliado,
            id_almacen: id_almacen,
            temporada: id_temporada,
            redenciones: $scope.carrito.elementos,
            direccion: "",
            id_registra: datos_usuario.id,
            id_ciudad: 0
        };

        $scope.EjecutarLlamado("redenciones", "Redimir", parametros, $scope.MostrarResultadoRedencion);

        $scope.redimiendo = true;
    };

    $scope.MostrarResultadoRedencion = function(data) {
        if (data.ok) {
            $scope.redimiendo = false;
            $scope.redimido = true;
            $scope.Redenciones = data.redenciones;
            $scope.afiliado.puntos = data.puntosNuevos;
        } else {
            $scope.redimiendo = false;
            $scope.redimido = false;
            $scope.errorRedencion = data.error;
        }
    };

    $scope.ActualizarPantalla = function() {
        location.reload();
    };

    $scope.ordenar = function(criterio) {
        if ($scope.ORDER == criterio) {
            $scope.ORDER = "-" + $scope.ORDER;
        } else {
            $scope.ORDER = criterio;
        }
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

    $scope.afiliado = afiliado_seleccionado;
    $scope.usuario = datos_usuario;
    $scope.id_almacen = id_almacen;
    $scope.catalogo_perfecto = catalogo_perfecto;
    $scope.id_temporada = id_temporada;
    $scope.id_categoria = id_categoria;

    $scope.CargarCategorias();
});