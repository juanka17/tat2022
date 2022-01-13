angular.module('terminosApp', []).controller('terminosController', function ($scope, $http) {

    $scope.CambiaClave = function()
    {
        console.log($scope.claveNueva);
        console.log(datos_usuario.cedula);
        if($scope.claveNueva == datos_usuario.cedula)
        {
            $scope.cambioClaveError = "La clave debe ser distinta de la cedula";
        }
        else if($scope.claveNueva.length < 6 || $scope.claveNueva.length > 12)
        {
            $scope.cambioClaveError = "La clave debe tener entre 6 y 12 caracteres.";
        }
        else if($scope.claveNueva != $scope.confirmaClaveNueva)
        {
            $scope.cambioClaveError = "La clave y la confirmación no coinciden";
        }
        else
        {
            $scope.claves = { id_afiliado: datos_usuario.id, actual: $scope.claveActual, nueva: $scope.claveNueva };
            $scope.EjecutarLlamado("afiliados", "AceptarTerminos", $scope.claves, $scope.ConfirmaAceptaTerminos);
        }
    };
    
    $scope.ConfirmaAceptaTerminos = function(data)
    {
        if(data.ok)
        {
            document.location.href = "bienvenida.php";
        }
        else
        {
            $scope.cambioClaveError = data.error;
        }
    };
    
    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack)
    {
        $http({ 
            method: "POST", url: "clases/jarvis.php", headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
            data: { modelo: modelo, operacion: operacion, parametros: parametros }
        }).success(function(data){
            //console.log(data);
            if(data.error == "")
            {
                CallBack(data.data);
            }
            else
            {
                $scope.errorGeneral = data.error;
            }
        });
    };
    
});