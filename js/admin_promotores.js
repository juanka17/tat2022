angular.module('adminPromotoresApp', []).controller('adminPromotoresController', function ($scope, $http) {

    $scope.filtros = {nombre: ""};
    $scope.almacenes = [{id_almacen: ""}];


    $scope.add = function () {
        $scope.almacenes.push({
            id_almacen: ""
        });
    };

    $scope.delete = function (index) {
        $scope.almacenes.splice(index, 1);
    };

    $scope.CargarAlmacenes = function ()
    {
        var parametros = {catalogo: "almacenes_global"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenes);
    };

    $scope.MostrarAlmacenes = function (data)
    {
        $scope.lista_almacenes = data;
        $scope.CargarVendedores();
    };

    $scope.CargarVendedores = function ()
    {
        var parametros = {catalogo: "lista_promotores"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVendedores);
    };

    $scope.MostrarVendedores = function (data)
    {
        $scope.promotores = data;
        $scope.SeleccionarListadoVendedores();
    };

    $scope.lista_vendedores = Array();
    $scope.SeleccionarListadoVendedores = function ()
    {
        $scope.lista_vendedores = Array();
        angular.forEach($scope.promotores, function (vendedor) {
            if (vendedor.nombre != null)
            {
                if ($scope.filtros.nombre.length == 0 || vendedor.nombre.toString().toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1)
                {
                    $scope.lista_vendedores.push(vendedor);
                }
            }

        });

    };

    $scope.AgregarAlmacenes = function (index)
    {
        $scope.promotor_seleccionado = index;
        $('#modalEditarVendedor').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
    };

    $scope.GuardarAlmacenesPromotor = function ()
    {
        var datos_promotor = {
            id_promotor: $scope.promotor_seleccionado
        };

        var almacenes = {
            almacenes: $scope.almacenes
        };
        var parametros = {
            catalogo_real: "almacenes_promotor",
            datos: datos_promotor,
            almacenes: almacenes
        };

        console.log(parametros);
        $scope.EjecutarLlamado("afiliados", "RegistrarPromotor", parametros, $scope.ResultadoGuardarAlmacenesPromotor);



    };

    $scope.ResultadoGuardarAlmacenesPromotor = function ()
    {
        $('#modalEditarVendedor').modal('hide');
        $scope.CargarVendedores();
    };

    $scope.AlmacenesPromotor = function (index)
    {
        $scope.promotor_seleccionado = index;

        var parametros = {catalogo: "almacenes_promotor",
            id_promotor: $scope.promotor_seleccionado
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenesPromotor);

        $('#modalAlmacenesPromotor').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
    };

    $scope.MostrarAlmacenesPromotor = function (data)
    {
        $scope.almacenes_promotor = data;
        /*$('#modalEditarVendedor').modal('hide');
         $scope.CargarVendedores();*/
    };

    $scope.EjecutarLlamado = function (modelo, operacion, parametros, CallBack)
    {
        $http({
            method: "POST", url: "clases/jarvis.php", headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: {modelo: modelo, operacion: operacion, parametros: parametros}
        }).success(function (data) {
            if (data.error == "")
            {
                CallBack(data.data);
            } else
            {
                console.log(data);
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
    $scope.CargarAlmacenes();
});