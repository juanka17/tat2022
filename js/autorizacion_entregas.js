angular.module('reportesApp', []).controller('reportesController', function ($scope, $http) {

    var catalogos = ["temporada_redenciones"];
    var indexAsist = 0;

    $scope.CargarCatalogosIniciales = function ()
    {
        if (indexAsist < catalogos.length)
        {
            var parametros = {catalogo: catalogos[indexAsist]};
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearCatalogo);
        } else
        {
            indexAsist = 0;
            $scope.CargaRedencionesSolicitadas();
        }
    };

    $scope.CrearCatalogo = function (data)
    {
        data.unshift({id: 0, nombre: "Todos", nombre_full: "Todos"});
        $scope[catalogos[indexAsist]] = data;
        indexAsist++;
        $scope.CargarCatalogosIniciales();
    };

    $scope.CargaRedencionesSolicitadas = function ()
    {
        $("#folioSeleccionado").modal("hide");
        var parametros = {catalogo: "redenciones_solicitadas"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRedenciones);
    };

    $scope.MostrarRedenciones = function (data)
    {
        $scope.filtros = {drogeria: "", id_temporada: 0};
        $scope.redenciones = data;
        $scope.SeleccionarListadoRedenciones();
    };

    $scope.filtros = {drogeria: "", id_temporada: 0};
    $scope.SeleccionarListadoRedenciones = function ()
    {
        $scope.lista_redenciones = Array();
        angular.forEach($scope.redenciones, function (redencion) {
            if (
                    ($scope.filtros.drogeria.length == 0 || redencion.almacen.toLowerCase().indexOf($scope.filtros.drogeria) > -1) &&
                    ($scope.filtros.id_temporada == 0 || redencion.id_temporada == $scope.filtros.id_temporada)
                    )
            {
                redencion.agregar = true;
                $scope.lista_redenciones.push(redencion);
            }
        });
    };

    $scope.AutorizarEntregas = function ()
    {
        $("#folioSeleccionado").modal("show");

        $scope.lista_redenciones_seleccionadas = Array();
        $scope.id_lista_seleccionada = Array();
        var index = 0;
        angular.forEach($scope.lista_redenciones, function (redencion) {
            if (redencion.agregar)
            {
                $scope.lista_redenciones_seleccionadas.push(redencion);
                $scope.id_lista_seleccionada.push(redencion.id_redencion);
            }
            index++;
        });
    };

    $scope.GuardarEntregasAutorizadas = function ()
    {
        var parametros = {
            catalogo: "ModificarEstadoRedencionMasivo",
            ids_redenciones: $scope.id_lista_seleccionada.join(","),
            id_operacion: 4,
            id_usuario: $scope.datos_usuario.id,
            comentario: "Actualización Estado Masiva"
        };
        console.log(parametros);
        $scope.EjecutarLlamado("redenciones", "ModificarEstadoRedencionMasivo", parametros, $scope.CargaRedencionesSolicitadas);
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
    $scope.CargarCatalogosIniciales();
});

function JSON2CSV() {
    var array = data_reporte;

    var str = '';
    var line = '';

    var head = array[0];
    $.each(head, function (key, val) {
        if (key.indexOf("id_") == -1)
            line += key + ';';
    });

    line = line.slice(0, -1);
    str += line + '\r\n';

    for (var i = 0; i < array.length; i++) {
        var line = '';

        $.each(array[i], function (key, val) {
            if (key.indexOf("id_") == -1)
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
    var fecha = today.getFullYear() + "" + (today.getMonth() + 1) + "" + today.getDate();

    downloadLink.href = url;
    downloadLink.download = "Reporte" + nombre_reporte + "_" + fecha + ".csv";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}