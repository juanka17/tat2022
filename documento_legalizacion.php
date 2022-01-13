<!DOCTYPE html>
<html lang="es">

    <head>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/angular.min.js" type="text/javascript"></script>
        <script>
            
            var id_redencion = 0;
            if(typeof getParameterByName("folio") !== 'undefined' && getParameterByName("folio") != "")
            {
                console.log(getParameterByName("folio"));
                id_redencion = getParameterByName("folio");
                console.log(id_redencion);
            }
                
            $(function(){
                if(typeof getParameterByName("folio") !== 'undefined' && getParameterByName("folio") != "")
                {
                    console.log(getParameterByName("folio"));
                }
                else
                {
                    $("#documento_legalizacion").html("Documento no encontrado.");
                }
            });
            
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                        results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }
            
        </script>
        <script src="js/documento_legalizacion.js" type="text/javascript"></script>
    </head>
    <body ng-app="documentoLegaliacionApp" ng-controller="documentoLegaliacionController" class="bodyIndex">
        
        <div id="documento_legalizacion"></div>
    
    </body>
</html>