angular.module('encuestasRedencionApp', []).controller('encuestasRedencionController', function($scope, $http) {

    $scope.datos_usuario = { nombre: "", cedula: "" };

    // <editor-fold defaultstate="collapsed" desc="Información Redención">

    $scope.CargarRedencion = function() {
        var parametros = { catalogo: "redencion", id: id_redencion };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRedencion);
    };

    $scope.MostrarRedencion = function(data) {
        $scope.redencion = data[0];
        $scope.CargarEncuestaRedencion();
    };

    $scope.CargarEncuestaRedencion = function() {
        var parametros = { catalogo: "encuesta_redencion", id_redencion: id_redencion };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEncuestaRedencion);
    };

    $scope.encuesta_redencion = Array();
    $scope.MostrarEncuestaRedencion = function(data) {
        if (data.length > 0) {
            $scope.encuesta_redencion = data;
        }
    };

    $scope.RegistrarEncuestaRedencion = function() {
        var preguntas_encuesta = Array();
        $("#pnlEncuesta tbody tr").each(function(index, row) {
            if (index < 9) {
                var pregunta = {
                    id_redencion: id_redencion,
                    numero_pregunta: (index + 1),
                    respuesta: $(row).find("select").first().val(),
                    comentario: $(row).find("input").first().val()
                };
                preguntas_encuesta.push(pregunta);
            }
        });

        var parametros = {
            catalogo: "encuesta_redencion",
            catalogo_real: "encuesta_redencion",
            lista_datos: preguntas_encuesta,
            id_redencion: id_redencion
        };

        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixtoMasivo", parametros, $scope.CargarEncuestaRedencion);
    };

    // </editor-fold>

    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack) {
        $http({
            method: "POST",
            url: "clases/jarvis.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: { modelo: modelo, operacion: operacion, parametros: parametros }
        }).success(function(data) {
            if (data.error == "") {
                CallBack(data.data);
            } else {
                console.log(data);
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.usuario_en_sesion = usuario_en_sesion;
    $scope.CargarRedencion();
});