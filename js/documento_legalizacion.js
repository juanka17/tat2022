angular.module('documentoLegaliacionApp', []).controller('documentoLegaliacionController', function ($scope, $http) {
    
    console.log(2);
    $scope.ObtenerDocumentoLegalizacion = function()
    {  
        console.log(id_redencion);
        var parametros = {
            catalogo: "documento_legalizacion",
            id_redencion: id_redencion
        };
        
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDocumentoLegalizacion);
    };
    
    $scope.MostrarDocumentoLegalizacion = function(data)
    {
        console.log(data);
        $("#documento_legalizacion").html(data[0].comentario);
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
                console.log(data);
                $scope.errorGeneral = data.error;
            }
        });
    };
    
    console.log(id_redencion);
    if(id_redencion > 0)
    {
        console.log(3);
        $scope.ObtenerDocumentoLegalizacion();
    }
    
});