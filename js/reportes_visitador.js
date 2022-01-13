angular.module('reporteVisitadorApp', []).controller('reporteVisitadorController', function ($scope, $http) {
    
    var catalogos = ["temporada"];    
    var indexAsist = 0;
    
    $scope.editando = false;
    $scope.CargarCatalogosIniciales = function()
    {
        if(indexAsist < catalogos.length)
        {
            var parametros = {catalogo: catalogos[indexAsist]};
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearCatalogo);
        }
        else
        {
            indexAsist = 0;
        }        
    };
    $scope.CrearCatalogo = function(data)
    {
        $scope[catalogos[indexAsist]] = data;
        indexAsist++;
        $scope.CargarCatalogosIniciales();
    };
    
    $scope.CargarReporte = function()
    {
        $("#pnlPreview").hide();
        $("#pnlLoad").show();        
        var parametros = {id_afiliado: datos_usuario.id};
        $scope.EjecutarLlamado("reportes", $("#ddReportes").val(), parametros, $scope.MostrarReporte);
        
    };
    
    $scope.MostrarReporte = function(data)
    {      
        dataReporte = data;
        
        $scope.reporte = {
            header: data.header,
            filas: []
        };
        angular.forEach(data.data, function(fila_data) {
            if (typeof fila_data.id_ejecutivo !== "undefined") 
            {
                if($scope.datos_usuario.es_administrador > 0 || fila_data.id_ejecutivo == $scope.datos_usuario.id && fila_data.id_temporada == $scope.filtros.id_temporada)
                {
                    var fila = [];
                    angular.forEach(fila_data, function(value, key) {
                        if($scope.columnas.indexOf(key) >= 0)
                        {
                            var column = { 
                                value: value, 
                                order: $scope.columnas.indexOf(key)
                            };
                            fila.push(column);
                        }
                    });
                    $scope.reporte.filas.push(fila);
                }
            }
        });
        $("#pnlPreview").show();
        $("#pnlLoad").hide();
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
                $scope.errorGeneral = data.error;
            }
        });
    };
    
    $scope.datos_usuario = datos_usuario;
    $scope.columnas = ['id_temporada','temporada','periodo','distribuidora','vendedor','ciudad','impactos','venta','puntos_impacto','puntos_venta','puntos_periodo'];
    $scope.CargarCatalogosIniciales();
});
