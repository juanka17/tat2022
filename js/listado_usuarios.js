angular.module('listadoUsuariosApp', []).controller('listadoUsuariosController', function($scope, $http) {

    $scope.filtros = { cedula: "", nombre: "", almacen: "", cod_formas: "" };

    $scope.Almacenes = function() {
        var parametros = { catalogo: "almacenes_usuarios" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenes);
    };

    $scope.CargarAlmacenes = function(data) {
        var parametros = {
            catalogo: "almacenes_visitador",
            id_afiliado: $scope.datos_usuario.id
        };
        console.log(parametros);
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenes);
    };

    $scope.MostrarAlmacenes = function(data) {
        $scope.almacen = data;
        //console.log($scope.almacen);
    };

    $scope.BuscarUsuarios = function() {
        var parametros = {
            cedula: $scope.filtros.cedula,
            nombre: $scope.filtros.nombre,
            almacen: $scope.filtros.almacen,
            cod_formas: $scope.filtros.cod_formas,
            id_rol: $scope.datos_usuario.ID_ROL
        };
        console.log(parametros);
        $scope.EjecutarLlamado("afiliados", "cargar_lista_usuarios", parametros, $scope.MostrarUsuarios);
    };

    $scope.MostrarUsuarios = function(data) {
        if (data.ok) {
            $scope.listado_usuarios = data.listado;
            console.log($scope.listado_usuarios);
        } else {
            alert(data.error);
        }
    };

    $scope.RedireccionarUsuario = function(id_usuario) {
        window.location.href = "mis_datos_vendedor.php?id_usuario=" + id_usuario;
    }

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
                $("#pnlPreview").hide();
                $("#pnlLoad").show();
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
    if ($scope.datos_usuario.ID_ROL == 2) {
        $scope.Almacenes();
    } else {
        $scope.CargarAlmacenes();
    }

});