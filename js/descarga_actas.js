angular.module('actasApp', []).controller('actasController', function ($scope, $http) {
    $scope.actas ={id_temporada:"",id_ejecutivo:""}
    var catalogos = ["operaciones_redencion"];
    var indexAsist = 0;
    
    $scope.CargarCatalogosIniciales = function()
    {
        
        $("#pnlLoad").show();
        if(indexAsist < catalogos.length)
        {
            var parametros = {catalogo: catalogos[indexAsist]};
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearCatalogo);
        }
        else
        {
            indexAsist = 0;
            $scope.CargarEjecutivo();
        }
    };
    
    $scope.CrearCatalogo = function(data)
    {           
        data.push({id: 0, nombre: "Todos"});
        $scope[catalogos[indexAsist]] = data;
        indexAsist++;
        $scope.CargarCatalogosIniciales();
    };
    
    $scope.CargarEjecutivo = function()
    {
        var parametros = {catalogo: "ejecutivo"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEjecutivo);              
    };
    
     $scope.MostrarEjecutivo = function(data)
    {
        $scope.ejecutivo = data;
        $scope.CargarTemporada();
    };
    
    $scope.CargarTemporada= function()
    {
        var parametros = {catalogo: "temporada_total"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarTemporada);              
    };
    
     $scope.MostrarTemporada = function(data)
    {
        $scope.temporada = data;
    };
    
    $scope.CargaActasDisponibles = function(temporada,ejecutivo)
    {
        $scope.id_temporada = temporada;
        $scope.id_ejecutivo = ejecutivo;
        var parametros = {catalogo: "actas_disponibles",id_temporada:$scope.id_temporada,id_ejecutivo:$scope.id_ejecutivo};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarActasDisponibles);
    };
    
    $scope.MostrarActasDisponibles = function(data)
    {
        $scope.redenciones = data;
        $scope.SeleccionarListadoActas();
    };
    
    $scope.filtros = {temporada: "", ejecutivo: "" };
    $scope.SeleccionarListadoActas = function()
    {
        $scope.lista_actas = Array();
        angular.forEach($scope.redenciones, function(redencion){
            
            
                var nombre_documento = redencion.temporada + "_" + redencion.distribuidora.replace('.', '') + ".pdf";
                nombre_documento = nombre_documento.toLowerCase().replace(/ /g, '_');
                redencion.documento = "documento_legalizacion_1.php?folio=" + redencion.folio_mayor + "&nombre=" + nombre_documento;
                $scope.lista_actas.push(redencion);
            
        });        
        $("#pnlLoad").hide();
    };
    
    $scope.GenerarPDFMasivo = function()
    {
        $scope.ProcesarPDFMasivo();
    };
    
    $scope.index_registros = 0;
    $scope.descargando_actas = false;
    $scope.ProcesarPDFMasivo = function()
    {
        $scope.descargando_actas = true;
        if($scope.index_registros < $scope.lista_actas.length)
        {
            var popup = window.open($scope.lista_actas[$scope.index_registros].documento);
            $scope.index_registros++;
            
            var log = $scope.index_registros + " de " + $scope.lista_actas.length;
            console.log(log);
            
            var popupTick = setInterval(function() {
                if (popup.closed) {
                    clearInterval(popupTick);
                    $scope.ProcesarPDFMasivo();
                }
            }, 500);
        }
        else
        {
            $scope.descargando_actas = false;
            alert("Proceso de descarga finalizado");
        }
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