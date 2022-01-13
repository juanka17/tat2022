angular.module('ventasApp', []).controller('ventasController', function ($scope, $http) {
    
    $scope.ObtenerVentasSinVendedor = function()
    {
        $scope.EjecutarLlamado("ventas", "ObtenerVentasSinVendedor", {}, $scope.MostrarVentasSinVendedor);
    };
    
    $scope.MostrarVentasSinVendedor = function(data)
    {
        console.log(data);
        if(data.ok)
        {
            $scope.ventas_sin_vendedor = data.datos;
        }
    };
    
    $scope.ReclamarVenta = function(id_venta)
    {
        var parametros = {id_afiliado: afiliado_seleccionado.id, id_venta: id_venta};
        $scope.EjecutarLlamado("ventas", "ReclamarVenta", parametros, $scope.ObtenerVentasSinVendedor);
    };
    
    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack)
    {
        $http({ 
            method: "POST", url: "clases/jarvis.php", headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
            data: { modelo: modelo, operacion: operacion, parametros: parametros }
        }).success(function(data){
            if(data.error == "")
            {
                CallBack(data.data);
            }
            else
            {
                $scope.errorCarga = data.error;
            }
        });
    };
    
    if(typeof ventas_no_procesadas !== 'undefined')
    {
        $scope.ObtenerVentasSinVendedor();
    }
});