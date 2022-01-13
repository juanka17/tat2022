angular.module('simuladorApp', []).controller('simuladorController', function ($scope, $http) {

    $scope.simulador = {venta: "", cuota: "", impactos: ""};

    $scope.Simular = function ()
    {
        $scope.mostrar = 1;
        var puntos = 0;
        var puntos_imp = 0;
        if ($scope.simulador.venta >= $scope.simulador.cuota)
        {
            puntos = $scope.simulador.venta / 25000;
            puntos_imp = $scope.simulador.impactos * 6;
        } else
        {
            alert("no puntos")
        }
        console.log($scope.simulador.cuota);
        $scope.guardar = 1;
        if ($scope.simulador.cuota >= 50000000)
        {
            console.log("Diamante");
            $scope.mensaje = "Diamante";
        }
        if ($scope.simulador.cuota >= 2500000 && $scope.simulador.cuota < 4999999)
        {
            console.log("Oro");
            $scope.mensaje = "Oro";
        }
        if ($scope.simulador.cuota >= 1000001 && $scope.simulador.cuota < 2499999)
        {
            console.log("Plata");
            $scope.mensaje = "Plata";
        }
        if ($scope.simulador.cuota < 1000000)
        {
             console.log("Bronce");
            $scope.mensaje = "Bronce";
        }

        $scope.puntos_venta = puntos;
        $scope.puntos_impactos = puntos_imp;
        $scope.puntos_totales = puntos + puntos_imp;



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
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
});