angular.module('adminApp', []).controller('adminController', function ($scope, $http) {
    
    $scope.filtros = {nombre: ""};
    
    $scope.CargarVisitadores = function()
    {
        var parametros = {catalogo: "lista_visitadores"};
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVisitadores);
    };
    
    $scope.MostrarVisitadores = function(data)
    {
        $scope.lista_visitadores = data;
        $scope.CargarAlmacenes();
    };
    
    $scope.CargarAlmacenes = function()
    {
        var parametros = {catalogo: "almacenes_global"};rametros = {catalogo: "almacenes_visitador", id_afiliado: $scope.datos_usuario.id};        
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenes);
    };
    
    $scope.MostrarAlmacenes = function(data)
    {
        $scope.almacenes = data;
        $scope.SeleccionarListadoAlmacenes();
    };

    $scope.lista_almacenes = Array();
    $scope.SeleccionarListadoAlmacenes = function()
    {
        $scope.lista_almacenes = Array();
        $scope.cantidad_paginas = 0;
        angular.forEach($scope.almacenes, function(almacen){
            if($scope.filtros.nombre.length == 0 || almacen.drogueria.toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1)
            {
                $scope.lista_almacenes.push(almacen);
            }
        });
    };
    
    $scope.CrearNuevoAlmacen = function()
    {
        $scope.almacen_seleccionado = {
            nombre: "",
            id_visitador: 0,
            encuestas_periodo: 0,
            incauca: 0,
            vendedor_perfecto: 0,
            supervisor_lider: 0,
            id: 0
        };
        
        $('#modalEditarAlmacen').modal('show',{
            backdrop: 'static',
            keyboard: false
        });
    };
    
    $scope.EditarAlmacen = function(index)
    {
        $scope.almacen_seleccionado = {
            nombre: $scope.lista_almacenes[index].drogueria,
            id_visitador: $scope.lista_almacenes[index].id_visitador,
            encuestas_periodo: $scope.lista_almacenes[index].encuestas_periodo,
            incauca: $scope.lista_almacenes[index].incauca,
            vendedor_perfecto: $scope.lista_almacenes[index].vendedor_perfecto,
            supervisor_lider: $scope.lista_almacenes[index].supervisor_lider,
            id: $scope.lista_almacenes[index].id_drogueria
        };
        
        $('#modalEditarAlmacen').modal('show',{
            backdrop: 'static',
            keyboard: false
        });
    };
    
    $scope.ModificarAlmacen = function()
    {   
        var parametros = { 
            catalogo: "almacenes",
            datos: $scope.almacen_seleccionado,
            id: $scope.almacen_seleccionado.id
        };
        
        $scope.EjecutarLlamado("catalogos", "ModificaCatalogoSimple", parametros, $scope.ResultadoEdicionAlmacen);
    };
    
    $scope.CrearAlmacen = function()
    {
        var parametros = {
            catalogo: "almacenes_global",
            catalogo_real: "almacenes",
            datos: $scope.almacen_seleccionado
        };
        
        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixto", parametros, $scope.ResultadoEdicionAlmacen);
    };
    
    $scope.ResultadoEdicionAlmacen = function(data)
    {
        $('#modalEditarAlmacen').modal('hide');
        $scope.CargarAlmacenes();
    };
    
    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack)
    {
        $http({ 
            method: "POST", url: "clases/jarvis.php", headers: {'Content-Type': 'application/x-www-form-urlen ed'}, 
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
    
    $scope.datos_usuario = datos_usuario;
    $scope.CargarVisitadores();
});