angular.module('misdatosVendedorApp', []).controller('misdatosVendedorController', function($scope, $http) {

    $scope.datos_vendedor = {
        cedula: "",
        cod_formas: "",
        nombre: "",
        telefono: "",
        celular: "",
        fecha_nacimiento: "",
        direccion: "",
        email: "",
        id_almacen: "",
        representante: "",
        id_genero: "",
        ciudad: "",
        id_estatus: ""
    };

    $scope.CargarRepresentantes = function() {
        var parametros = {
            catalogo: "representantes"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRepresentantes);
    };

    $scope.MostrarRepresentantes = function(data) {
        $scope.representante = data;
        $scope.CargarVendedor();
    };

    $scope.CargarVendedor = function() {
        var parametros = {
            catalogo: "afiliados",
            id: $scope.datos_usuario.id
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVendedor);
    };

    $scope.MostrarVendedor = function(data) {
        $scope.datos_vendedor = data[0];
        $scope.CargarAlmacenesRepresentante($scope.datos_vendedor.id_representante)
    };

    $scope.CargarAlmacenesRepresentante = function(data) {
        console.log(data)
        var parametros = {
            catalogo: "almacenes",
            id_visitador: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenesRepresentante);
    };

    $scope.MostrarAlmacenesRepresentante = function(data) {
        $scope.almacenes = data;
    };

    $scope.ActualizarDatosVendedores = function() {

        let data = {
            cedula: $scope.datos_vendedor.cedula,
            cod_formas: $scope.datos_vendedor.cod_formas,
            nombre: $scope.datos_vendedor.nombre,
            telefono: $scope.datos_vendedor.telefono,
            celular: $scope.datos_vendedor.celular,
            nacimiento: $("#fecha_nacimiento").val(),
            direccion: $scope.datos_vendedor.direccion,
            email: $scope.datos_vendedor.email,
            id_almacen: $scope.datos_vendedor.id_almacen,
            representante: $scope.datos_vendedor.representante,
            id_genero: $scope.datos_vendedor.id_genero,
            id_estatus: $scope.datos_vendedor.id_estatus
        }
        var parametros = {
            catalogo: "afiliados",
            datos: data,
            id: $scope.datos_usuario.id
        };

        $scope.EjecutarLlamado("catalogos", "ModificaCatalogoSimple", parametros, $scope.ResultadoEdicionVendedor);
    };

    $scope.ResultadoEdicionVendedor = function() {
        alert("Datos modificados");
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
    $scope.CargarRepresentantes();
});