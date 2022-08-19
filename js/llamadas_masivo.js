angular.module('llamadasmasivoApp', []).controller('llamadasmasivoController', function($scope, $http) {

    $scope.datos_usuario = { id: "", cedula: "" };
    $scope.informacion_usuarios = { cantidad: 0, listado: Array(), cedulas_correctas: 0, llamadas_procesadas: 0 };
    $scope.subCategoria = 0;
    $scope.subcategorias = Array();
    $scope.anteriores = Array();
    $scope.categorias_anteriores = Array();
    $scope.subCategoriaSeleccionada = 0;
    $scope.comentarioLlamadas = '';
    $scope.llamadas_usuario = null;
    $scope.llamada = { COMENTARIO: "", id_subcategoria: 0 };
    var categorias_llamada = null;



    $scope.CedulasActivas = function() {
        var parametros = { catalogo: "documentos_activos" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDocumentosActivos);
    };

    $scope.MostrarDocumentosActivos = function(data) {
        $scope.documento = data;
        angular.forEach($scope.documento, function(value) {
            cedulas.push(value.id.toString());
        });
    };

    $scope.ObtenerCategoriasLlamada = function() {
        var parametros = { catalogo: "categorias_llamada" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CargarSubCategorias);

    };

    $scope.CargarSubCategorias = function(data) {
        categoriasLlamada = data;
        $scope.ObtenerSubcategorias(0);
    };

    $scope.ObtenerSubcategorias = function(idParam) {
        $scope.llamada.id_subcategoria = $scope.subCategoria;
        var id = idParam;
        $scope.subCategoriaSeleccionada = id;
        if (idParam == -1) {
            id = $scope.categoriasAnteriores[$scope.categoriasAnteriores.length - 1].ID_PADRE;
            $scope.categoriasAnteriores.pop();
            $scope.anteriores.pop();
            $scope.categoriasAnteriores.pop();
            $scope.anteriores.pop();
        }

        if (idParam == 0) {
            $scope.categoriasAnteriores = Array();
            $scope.anteriores = Array();
        }

        $scope.subCategorias = Array();
        angular.forEach(categoriasLlamada, function(subCategoria) {
            if (id == subCategoria.ID_PADRE)
                $scope.subCategorias.push(subCategoria);

            if (id != 0 && id == subCategoria.ID) {
                $scope.categoriasAnteriores.push(subCategoria);
                $scope.anteriores.push(subCategoria.NOMBRE);
            }
        });
    };

    $scope.SumarioCedulasMasivos = function() {
        $scope.informacion_usuarios.cantidad = $scope.informacion_usuarios.listado == "" ? 0 : $scope.informacion_usuarios.listado.split(/\n/).length;

        listado = $scope.informacion_usuarios.listado.split(/\n/g);
        $scope.datos = [];
        $scope.datos_errados = [];
        $scope.informacion_usuarios.cedulas_correctas = 0;

        angular.forEach(listado, function(value, key) {
            if (cedulas.indexOf(listado[key]) > -1) {
                $scope.datos.push(value);
            } else {
                $scope.datos_errados.push(value);
            };
        });

        $scope.informacion_usuarios.cedulas_correctas = $scope.datos.length;
        console.log($scope.datos);
        console.log($scope.datos_errados);

        if ($scope.datos_errados.length > 0) {
            alert("Cargue Fallo: se muestran los ids que no existen");
            alert($scope.datos_errados);
        } else {
            if ($scope.fecha_llamada == "NaN-NaN-NaN" || $scope.subCategoria == 0 || $scope.comentario == null) {
                alert("debe ingresar todos los datos");
            } else {
                console.log($scope.fecha_llamada);
                $scope.RegistraLlamadas();
            }
        }
    };

    $scope.RegistraLlamadas = function() {
        var actualizar_llamadas = Array();

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            return [year, month, day].join('-');
        }
        FECHA = formatDate($("#fecha_llamada").val());
        $scope.fecha_llamada = FECHA;
        console.log(FECHA);
        console.log($scope.fecha_llamada);

        angular.forEach(listado, function(value) {

            var pregunta = {
                fecha: $scope.fecha_llamada,
                id_usuario: value,
                id_subcategoria: $scope.subCategoria,
                id_usuario_registra: $scope.usuario_en_sesion.id,
                comentario: $scope.comentario
            };
            actualizar_llamadas.push(pregunta);
        });
        console.log(actualizar_llamadas);

        var parametros = {
            catalogo: "llamadas_usuarios",
            catalogo_real: "llamadas_usuarios",
            lista_datos: actualizar_llamadas,
            id_usuario: 16
        };
        console.log(parametros);
        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixtoMasivo", parametros, $scope.TerminarCargue);

    };

    $scope.TerminarCargue = function(data) {
        alert("Cargue Terminado");
        location.reload(true);
    };

    $scope.LimpiarTodo = function(data) {
        location.reload(true);
    };

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
    $scope.CedulasActivas();
    $scope.ObtenerCategoriasLlamada();
});

var cedulas = Array();
var listado = Array();