angular.module('adminApp', []).controller('adminController', function($scope, $http) {

    $scope.filtros = {
        nombre: "",
        cod_formas: "",
        distribuidora: ""
    };

    $scope.vendedor_seleccionado = {
        id: 0,
        nombre: "",
        cod_formas: 0,
        id_almacen: 0,
        id_clasificacion: 6,
        id_categoria: 0,
        id_visitador: 0,
        id_supervisor: 0,
        cuota_minima: 0,
        imp_minimos: 0,
        id_registra: datos_usuario.id,
        id_temporada: 0,
    };

    $scope.CargarVendedores = function() {
        var parametros = {
            catalogo: "lista_vendedores"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVendedores);
    };

    $scope.MostrarVendedores = function(data) {
        $scope.vendedores = data;
        $scope.SeleccionarListadoVendedores();
        $scope.CargarVisitadores();
    };

    $scope.lista_vendedores = Array();

    $scope.SeleccionarListadoVendedores = function() {
        $scope.lista_vendedores = Array();
        angular.forEach($scope.vendedores, function(vendedor) {
            if (vendedor.nombre != null && vendedor.cod_formas != null && vendedor.distribuidora != null) {

                if (
                    ($scope.filtros.nombre.length == 0 || vendedor.nombre.toString().toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) &&
                    ($scope.filtros.cod_formas.length == 0 || vendedor.cod_formas.toString().indexOf($scope.filtros.cod_formas.toLowerCase()) > -1) &&
                    ($scope.filtros.distribuidora.length == 0 || vendedor.distribuidora.toString().toLowerCase().indexOf($scope.filtros.distribuidora.toString().toLowerCase()) > -1)
                ) {

                    if ($scope.lista_vendedores.length < 50) {
                        $scope.lista_vendedores.push(vendedor);
                    }
                }
            }

        });

    };

    $scope.CargarVisitadores = function() {
        var parametros = {
            catalogo: "visitadores_almacen"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVisitadores);
    };

    $scope.MostrarVisitadores = function(data) {
        $scope.lista_visitadores = data;
        $scope.ObtenerRangosCategorias();
    };

    $scope.CargarAlmacenes = function(data) {
        var parametros = {
            catalogo: "almacenes_visitador",
            id_afiliado: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenes);
    };

    $scope.MostrarAlmacenes = function(data) {
        $scope.lista_almacenes = data;
    };

    $scope.CargarSupervisores = function(data) {
        console.log(data)
        var parametros = {
            catalogo: "supervisores_almacen",
            id_almacen: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarSupervisores);
    };

    $scope.MostrarSupervisores = function(data) {
        $scope.lista_supervisores = data;
    };

    $scope.ObtenerRangosCategorias = function() {
        var parametros = {
            catalogo: "categorias"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRangosCategorias);
    };

    $scope.MostrarRangosCategorias = function(data) {
        $scope.categorias_rangos = data;
        $scope.ObtenerTemporadas();
    };

    $scope.ObtenerTemporadas = function() {
        var parametros = {
            catalogo: "temporadas_creacion_usuario"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarTemporadas);
    };

    $scope.MostrarTemporadas = function(data) {
        $scope.temporadas = data;
    };

    $scope.MostrarCuotaMinima = function(data) {
        if (data == 1) {
            $scope.vendedor_seleccionado.cuota_minima = $scope.categorias_rangos[0].cuota_minima;
            $scope.vendedor_seleccionado.imp_minimos = $scope.categorias_rangos[0].impactos_minimos;
        } else if (data == 2) {
            $scope.vendedor_seleccionado.cuota_minima = $scope.categorias_rangos[1].cuota_minima;
            $scope.vendedor_seleccionado.imp_minimos = $scope.categorias_rangos[1].impactos_minimos;
        } else if (data == 3) {
            $scope.vendedor_seleccionado.cuota_minima = $scope.categorias_rangos[2].cuota_minima;
            $scope.vendedor_seleccionado.imp_minimos = $scope.categorias_rangos[2].impactos_minimos;
        } else if (data == 4) {
            $scope.vendedor_seleccionado.cuota_minima = $scope.categorias_rangos[3].cuota_minima;
            $scope.vendedor_seleccionado.imp_minimos = $scope.categorias_rangos[3].impactos_minimos;
        } else {
            $scope.vendedor_seleccionado.cuota_minima = $scope.categorias_rangos[4].cuota_minima;
            $scope.vendedor_seleccionado.imp_minimos = $scope.categorias_rangos[4].impactos_minimos;
        }
    }



    $scope.ObtenerCategorias = function(data) {
        var parametros = {
            catalogo: "clasificacion_afiliados_temporada",
            id_afiliado: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCategorias);
    };

    $scope.MostrarCategorias = function(data) {
        $scope.categorias = data;
        $('#modalCategoriasVendedor').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
    };



    $scope.CrearNuevoAfiliado = function() {

        $('#modalEditarVendedor').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
    };


    $scope.CrearAfiliado = function() {

        var parametros = {
            catalogo: "lista_vendedores",
            catalogo_real: "afiliados",
            datos: $scope.vendedor_seleccionado
        };

        $scope.EjecutarLlamado("afiliados", "CrearNuevoUsuarioAdmin", parametros, $scope.ResultadoEdicionVendedor);

    };


    $scope.EditarAlmacen = function(index) {
        $scope.vendedor_seleccionado = {
            id: $scope.lista_vendedores[index].id,
            nombre: $scope.lista_vendedores[index].nombre,
            cod_formas: $scope.lista_vendedores[index].cod_formas,
            id_almacen: $scope.lista_vendedores[index].id_almacen,
            id_almacen_old: $scope.lista_vendedores[index].id_almacen,
            id_clasificacion: $scope.lista_vendedores[index].id_clasificacion,
            id_estatus: $scope.lista_vendedores[index].id_estatus,
            id_categoria: $scope.lista_vendedores[index].id_categoria,
        };

        $('#modalEditarVendedor').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
    };

    $scope.ModificarAlmacen = function() {
        $http({
            method: "POST",
            url: "./php/modulos/admin_vendedor/update.php",
            data: $scope.vendedor_seleccionado
        }).success(function(response) {
            console.log(response);
            $scope.CargarAlmacenes();
            if (response.success) {
                $('#modalEditarVendedor').modal('hide');

            }
        });

    };

    $scope.ResultadoEdicionVendedor = function(data) {
        $('#modalEditarVendedor').modal('hide');
        $scope.CargarVendedores();
    };

    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack) {
        $http({
            method: "POST",
            url: "clases/jarvis.php",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: {
                modelo: modelo,
                operacion: operacion,
                parametros: parametros
            }
        }).success(function(data) {
            if (data.error == "") {
                CallBack(data.data);
            } else {
                console.log(data);
                $scope.errorGeneral = data.error;
            }
        });
    };

    /**
     * Init Var
     */

    $scope.datos_usuario = datos_usuario;
    $scope.CargarVendedores();
});