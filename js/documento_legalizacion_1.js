angular.module('documentoLegaliacionApp', []).controller('documentoLegaliacionController', function ($scope, $http) {
    
    $scope.ObtenerDocumentoLegalizacion = function()
    {  
        var parametros = {
            catalogo: "documento_legalizacion",
            id_redencion: id_redencion
        };
        
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDocumentoLegalizacion);
    };
    
    $scope.MostrarDocumentoLegalizacion = function(data)
    {
        var html_acta = data[0].comentario;
        html_acta = html_acta.replace("png", "jpg");
        $("#documento_legalizacion").html(html_acta);
        console.log(html_acta);
        var nombre_archivo = data[0].temporada + "_" + data[0].distribuidora + ".pdf";
        nombre_archivo = nombre_archivo.toLowerCase().replace(/ /g, '_');
        
        setTimeout(function(){
            GenerarPDF(nombre_archivo);
        }, 500);
        
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
    
    if(id_redencion > 0)
    {
        $scope.ObtenerDocumentoLegalizacion();
    }
    
});

function GenerarPDF(nombre_archivo)
{
    document.body.style.width = "700px";
    var doc_height = parseInt($("body").css("height").replace("px","")) + 100;
    
    var pdf = new jsPDF('p', 'pt', [800, doc_height]);
    var canvas = pdf.canvas;
    
    //console.log(doc_height);
    
    html2canvas(document.body, {
        canvas:canvas,
        height: 2000,
        width: 700,
        onrendered: function(canvas) {
            pdf.save(nombre_archivo);
            
            setTimeout(function(){
                close();
            }, 250);
        }
    });
}