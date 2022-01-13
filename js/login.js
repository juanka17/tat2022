angular.module('loginApp', []).controller('loginController', function ($scope, $http) {

    $scope.acepta_terminos = false;
    $scope.usuario = {documento: "", clave: ""};
    $scope.recordar = {email: ""};
    $scope.nuevo = {CEDULA: "", NOMBRE: "", DIRECCION: "", TELEFONO: "", CELULAR: "", EMAIL: "", ID_MARCA: 0, ID_ALMACEN: 0, CLAVE: "", CONFIRMA: "", MARCA_NUEVA: "Nueva marca"};
    
    $scope.CargarAlmacenes = function()
    {
        var parametros = {catalogo: "almacen"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearListaAlmacenes);
    };
    
    $scope.CrearListaAlmacenes = function(data)
    {
        $scope.almacen = data;
        $scope.CargarMarcas();
    };
    
    $scope.CargarMarcas = function()
    {
        var parametros = {catalogo: "marcas"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearListaMarcas);
    };
    
    $scope.CrearListaMarcas = function(data)
    {
        $scope.marcas = data;
    };
    
    $scope.Login = function()
    {
        if($scope.usuario.documento != "" && $scope.usuario.clave != "")
        {
            $scope.EjecutarLlamado("afiliados", "Login", $scope.usuario, $scope.ConfirmaLogin);
        }
        else
        {
            $scope.errorGeneral = "Debe completar todos los campos";
        }
    };
    
    $scope.ConfirmaLogin = function(data)
    {
        console.log(data);
        if(data.login == 1)
        {
            document.location.href = "bienvenida.php";
        }
        else if(data.login == 2)
        {
            $('#modalRegistrarse').modal('show');
        }
    };
    
    $scope.CrearAfiliado = function()
    {
        var correcto = true;
        angular.forEach($scope.nuevo, function(value, key) {
            if(angular.isNumber(value) && value == 0)
            {
                correcto = false;
            }
            else if(!angular.isNumber(value) && value == "")
            {
                correcto = false;
            }
        });
        
        if(correcto)
        {
            if($scope.nuevo.CONFIRMA == "")
            {
                $scope.errorCreacion = "Debes ingresar una clave.";
            }
            else if($scope.nuevo.CLAVE == $scope.nuevo.CEDULA)
            {
                $scope.errorCreacion = "La clave debe ser distinta de la cedula";
            }
            else if($scope.nuevo.CLAVE.length < 6 || $scope.nuevo.CLAVE.length > 12)
            {
                $scope.errorCreacion = "La clave debe tener entre 6 y 12 caracteres.";
            }
            else if($scope.nuevo.CLAVE != $scope.nuevo.CONFIRMA)
            {
                $scope.errorCreacion = "La clave y la confirmación no coinciden";
            }
            else
            {
                $scope.EjecutarLlamado("afiliados", "CreaAfiliado", $scope.nuevo, $scope.AfiliadoCreado);
            }
        }
        else
        {
            $scope.errorCreacion = "Debes completar todos los campos.";
        }
    };
    
    $scope.AfiliadoCreado = function(data)
    {
        if(data.ok)
        {
            document.location.href = "bienvenida.php";
        }
        else
        {
            $scope.errorCreacion = data.error;
        }
    };
    
    $scope.RestaurarClave = function()
    {
        if($scope.recordar.email != "")
        {
            $scope.EjecutarLlamado("afiliados", "RestauraPassword", $scope.recordar, $scope.ConfirmaRestaruracion);
        }
        else
        {
            $scope.errorRecordar = "Debe ingresar un correo electrónico";
        }
    };
    
    $scope.ConfirmaRestaruracion = function(data)
    {
        if(data.ok)
        {
            console.log("Se envio un correo electrónico con la nueva contraseña para poder ingresar.");
        }else{
            console.log("Hubo un problema al enviar el correo elctrónico por favor comunicarse con la linea de atención");
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
    
    $scope.CargarAlmacenes();
});