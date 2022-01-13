
angular.module('promotoresApp', []).controller('promotoresController', function ($scope, $http) {

    $scope.datos_usuario = datos_usuario;
   

    $scope.loadPromotor = function () {
        $http({
            method: "GET",
            url: "php/modulos/promotores/select.php?search=all",
            
        }).success(function (response) {

            $scope.lista_promotores = response.data;

            console.log(data);
        });
    };
    
    $scope.loadPromotorDetalle = function (id_usuario) {
        $('#modalDetalleAlmacenesPromotor').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
        $http({
            method: "GET",
            url: "php/modulos/promotores/select.php?search=detalle&id_afiliado="+id_usuario,
            
        }).success(function (response) {

            $scope.lista_promotores_detalle = response.data;

            console.log(data);
        });
    };
    
    $scope.loadPromotor();



});