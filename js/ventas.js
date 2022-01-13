angular.module('ventasApp', []).controller('ventasController', function ($scope, $http) {
    
    $scope.calculando = false;
    $scope.RegistrarVentas = function()
    {
        $scope.resultadoCarga = "";
        $scope.errorCarga = "";
        if(archivo !== "")
        {
            var parametros = { archivo: archivo, ventas: ventas };
            $scope.calculando = true;
            $scope.EjecutarLlamado("ventas", "RegistrarVentas", parametros, $scope.ResultadoRegistroVentas);
        }
        else
        {
            $scope.errorCarga = "Debe cargar un archivo";
        }
    };
    
    $scope.ResultadoRegistroVentas = function(data)
    {
        if(data.ok)
        {
            $scope.calculando = false;
            $scope.resultadoCarga = "Archivo procesado correctamente";
        }
        else
        {
            $scope.errorCarga = data.error;
        }
    };
    
    $scope.LimpiarMensajes = function()
    {
        $scope.calculando = false;
        $scope.resultadoCarga = "";
        $scope.errorCarga = "";
    };
    
    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack)
    {
        $http({ 
            method: "POST", url: "../clases/jarvis.php", headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
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
});