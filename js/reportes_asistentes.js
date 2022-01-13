angular.module('reportesApp', []).controller('reportesController', function ($scope, $http) {
    
    var catalogos = ["operaciones_redencion"];
    var indexAsist = 0;
    
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
            $scope.CargaRedencionesVisitador();
        }
    };
    
    $scope.CrearCatalogo = function(data)
    {
        data.push({id: 0, nombre: "Todos"});
        $scope[catalogos[indexAsist]] = data;
        indexAsist++;
        $scope.CargarCatalogosIniciales();
    };
    
    $scope.CargaRedencionesVisitador = function()
    {
        var parametros = {catalogo: "redenciones_asistente", id_afiliado: $scope.datos_usuario.id};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRedenciones);
    };
    
    $scope.MostrarRedenciones = function(data)
    {
        $scope.redenciones = data;
        $scope.SeleccionarListadoRedenciones();
    };
    
    $scope.filtros = {drogeria: "", id_operacion: 0};
    $scope.SeleccionarListadoRedenciones = function()
    {
        $scope.lista_redenciones = Array();
        angular.forEach($scope.redenciones, function(redencion){
            if(
                    ($scope.filtros.drogeria.length == 0 || redencion.almacen.toLowerCase().indexOf($scope.filtros.drogeria) > -1 ) &&
                    ($scope.filtros.id_operacion == 0 || redencion.id_operacion == $scope.filtros.id_operacion)
                    )
            {
                if(redencion.id_operacion == 5)
                {
                    redencion.documento = "https://sociosyamigos.com.co/tat/documento_legalizacion.php?folio=" + redencion.id_redencion;
                }
                
                $scope.lista_redenciones.push(redencion);
            }
        });
    };
    
    $scope.CargarEntrgasLegalizadas = function()
    {
        var parametros = {catalogo: "entregas_legalizadas_visitador", id_afiliado: $scope.datos_usuario.id};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEntregasLegalizadas);
    };
    
    $scope.MostrarEntregasLegalizadas = function(data)
    {
        $scope.legalizados = Array();
        angular.forEach(data, function(redencion){
            redencion.documento = "https://sociosyamigos.com.co/droguerias/documento_legalizacion.php?folio=" + redencion.id_redencion;
            $scope.legalizados.push(redencion);
        });
        console.log($scope.legalizados);
    };
    
    $scope.GenerarReporte = function(reporte)
    {
        data_reporte = Array();
        switch(reporte)
        {
            case "entregas": {
                nombre_reporte = "Entregas";
                data_reporte = $scope.lista_redenciones;
            }; break;
            case "droguerias": {
                nombre_reporte = "Droguerias";
                data_reporte = $scope.drogerias;
            }; break;
            case "actas": {
                nombre_reporte = "Actas";
                data_reporte = $scope.legalizados;
            }; break;
        }
        var str_reporte = JSON2CSV();
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
    $scope.CargarCatalogosIniciales();
});

function JSON2CSV() {
    var array = data_reporte;
    
    var str = '';
    var line = '';
    
    var head = array[0];
    $.each(head, function(key, val){
        if(key.indexOf("id_") == -1)
            line += key + ';';
    });
    
    line = line.slice(0, -1);
    str += line + '\r\n';
    
    for (var i = 0; i < array.length; i++) {
        var line = '';
        
        $.each(array[i], function(key, val){
            if(key.indexOf("id_") == -1)
                line += val + ';';
        });
        
        line = line.slice(0, -1);
        str += line + '\r\n';
    }
    
    ExportExcel(str);
}

function ExportExcel(csv_report)
{
    var downloadLink = document.createElement("a");
    var blob = new Blob(["\ufeff", csv_report]);
    var url = URL.createObjectURL(blob);
    
    var today = new Date();
    var fecha = today.getFullYear()+""+(today.getMonth()+1)+""+today.getDate();
    
    downloadLink.href = url;
    downloadLink.download = "Reporte" + nombre_reporte + "_" + fecha + ".csv";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink); 
}