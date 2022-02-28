angular.module('misdatosVendedorApp', []).controller('misdatosVendedorController', function($scope, $http) {

    $scope.datos_vendedor = {
        cedula: "",
        cod_formas: "",
        nombre: "",
        telefono: "",
        celular: "",
        fecha_nacimiento: "",
        direccion: "",
        email: "",
        id_almacen: "",
        representante: "",
        id_genero: "",
        id_departamento: "",
        id_ciudad: "",
        id_estatus: ""
    };

    var categorias_llamada = null;
    $scope.subCategoria = 0;
    $scope.subcategorias = Array();
    $scope.anteriores = Array();
    $scope.categorias_anteriores = Array();
    $scope.subCategoriaSeleccionada = 0;
    $scope.comentarioLlamadas = '';
    $scope.llamadas_usuario = null;
    $scope.llamada = { COMENTARIO: "", id_subcategoria: 0 };

    $scope.CargarRepresentantes = function() {
        var parametros = {
            catalogo: "representantes"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRepresentantes);
    };

    $scope.MostrarRepresentantes = function(data) {
        $scope.representante = data;
        $scope.CargarDepartamentos();
    };

    $scope.CargarDepartamentos = function() {
        var parametros = {
            catalogo: "departamento"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDepartamento);
    };

    $scope.MostrarDepartamento = function(data) {
        $scope.departamento = data;
        $scope.CargarVendedor();
    };

    $scope.CargarVendedor = function() {
        var parametros = {
            catalogo: "afiliados",
            id: $scope.id_usuario
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVendedor);
    };

    $scope.MostrarVendedor = function(data) {
        $scope.datos_vendedor = data[0];
        $scope.CargarAlmacenesRepresentante($scope.datos_vendedor.id_representante)
        $scope.CargarCiudades(0, $scope.datos_vendedor.id_ciudad)
    };

    $scope.CargarAlmacenesRepresentante = function(data) {
        var parametros = {
            catalogo: "almacenes",
            id_visitador: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenesRepresentante);
    };

    $scope.MostrarAlmacenesRepresentante = function(data) {
        $scope.almacenes = data;
    };

    $scope.CargarCiudades = function(carga, data) {
        if (carga == 1) {
            var parametros = {
                catalogo: "ciudad",
                departamento: data
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCiudades);
        } else {
            var parametros = {
                catalogo: "ciudad_guardada",
                ciudad: data
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCiudades);
        }
    };

    $scope.MostrarCiudades = function(data) {
        $scope.ciudades = data;
    };

    $scope.ActualizarDatosVendedores = function() {

        let data = {
            cedula: $scope.datos_vendedor.cedula,
            cod_formas: $scope.datos_vendedor.cod_formas,
            nombre: $scope.datos_vendedor.nombre,
            telefono: $scope.datos_vendedor.telefono,
            celular: $scope.datos_vendedor.celular,
            nacimiento: $("#fecha_nacimiento").val(),
            direccion: $scope.datos_vendedor.direccion,
            email: $scope.datos_vendedor.email,
            id_almacen: $scope.datos_vendedor.id_almacen,
            representante: $scope.datos_vendedor.representante,
            id_genero: $scope.datos_vendedor.id_genero,
            id_estatus: $scope.datos_vendedor.id_estatus,
            id_actualiza: $scope.datos_usuario.id
        }
        var parametros = {
            catalogo: "afiliados",
            datos: data,
            id: $scope.id_usuario
        };
        console.log(parametros);
        $scope.EjecutarLlamado("catalogos", "ModificaCatalogoSimple", parametros, $scope.ResultadoEdicionVendedor);
    };

    $scope.ResultadoEdicionVendedor = function() {
        alert("Datos modificados");
    };

    $scope.ObtenerCategoriasLlamada = function() {
        var parametros = {
            catalogo: "categorias_llamada"
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CargarSubCategorias);
        $scope.ObtenerLlamadas();
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

    $scope.ObtenerLlamadas = function() {
        var parametros = {
            catalogo: "llamadas_usuarios",
            id_usuario: $scope.id_usuario
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarLlamadas);
    };

    $scope.MostrarLlamadas = function(data) {
        $scope.subCategoria = 0;
        $scope.subcategorias = Array();
        $scope.anteriores = Array();
        $scope.categorias_anteriores = Array();
        $scope.subCategoriaSeleccionada = 0;
        $scope.comentarioLlamadas = '';
        $scope.llamadas_usuarios = null;
        $scope.llamada = { comentario: "", id_subcategoria: 0 };
        categorias_llamada = null;

        $scope.llamadas_usuarios = data;
    };

    $scope.RegistraLlamada = function(data) {
        $scope.llamada.id_usuario = id_usuario;
        $scope.llamada.fecha = moment().format("YYYY-MM-DD HH:mm:ss");
        $scope.llamada.id_usuario_registra = $scope.datos_usuario.id;
        var parametros = {
            catalogo: "llamadas_usuarios",
            id_usuario: $scope.id_usuario,
            datos: $scope.llamada
        };
        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoSimple", parametros, $scope.MostrarLlamadas);
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
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
    $scope.id_usuario = id_usuario;
    $scope.CargarRepresentantes();
});