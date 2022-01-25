<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<?php include 'componentes/componentes_basicos.php'; ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
function format(input) {
    var num = input.value.replace(/\./g, '');
    if (!isNaN(num)) {
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/, '');
        input.value = num;
    } else {
        alert('Solo se permiten numeros');
        input.value = input.value.replace(/[^\d\.]*/g, '');
    }
}
</script>
<script type="text/javascript">
var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
var id_temporada_en_redencion = 9;
var seleccionar_almacen = false;
var id_almacen = 0;
if (typeof getParameterByName("id_almacen") !== 'undefined' && getParameterByName("id_almacen") != "") {
    var seleccionar_almacen = true;
    id_almacen = getParameterByName("id_almacen");
} else {
    document.location.href = "almacenes.php";
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
</script>
<style>
hr {
    margin-top: 9px;
    margin-bottom: 20px;
    border: 0;
    border-top: 9px solid #333;
}

#hr1 {
    margin-top: 9px;
    margin-bottom: 20px;
    border: 0;
    border-top: 9px solid #d193c4;
}

#hr2 {
    margin-top: 9px;
    margin-bottom: 20px;
    border: 0;
    border-top: 9px solid #00c0ef38;
}

#sig-canvas {
    border: 2px dotted #CCCCCC;
    border-radius: 5px;
    cursor: crosshair;
}

#sig-dataUrl {
    width: 100%;
}

#singModal {
    top: 0px !important;
    touch-action: none;
    overflow: hidden;
}

#sig-canvas {
    touch-action: none;
}

/*.modal-dialog {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }

    .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
    }*/

#box-body-cuotas {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    padding: 10px;
    height: auto;

}

.cuotas-scroll {
    overflow: scroll;
}

.mini_modal {
    width: 70%;
    height: auto;
    margin: 10% 0% 0% 20%;
    -webkit-transition: -webkit-transform .3s ease-out;
    -o-transition: -o-transform .3s ease-out;
    transition: transform .3s ease-out;
    -webkit-transform: translate(0, -25%);
    -ms-transform: translate(0, -25%);
    -o-transform: translate(0, -25%);
    transform: translate(0, -25%);
}

.elementos {
    border: 2px solid #ff751d;
    border-radius: 20px;
    padding: 20px;
    overflow: auto;
}

.table-bordered {
    border: 2px solid #ff751d5c;
    border-radius: 20px;
}

.table-bordered>thead>tr>th,
.table-bordered>tbody>tr>th,
.table-bordered>tfoot>tr>th,
.table-bordered>thead>tr>td,
.table-bordered>tbody>tr>td,
.table-bordered>tfoot>tr>td {
    border: 1px solid #ff751d5c;
    border-radius: 20px;
}

.hoverTable {
    width: 100%;
    border-collapse: collapse;
}

.hoverTable td {
    padding: 10px;
    border: #f4f4f4 2px solid;
}

/* Define the default color for all the table rows */
.hoverTable tr {
    background: #ecf0f5;
}

/* Define the hover highlight color for the table row */
.hoverTable tr:hover {
    background-color: #ffff99;
    cursor: pointer;
}
</style>
</head>

<body ng-app="almacenesApp" ng-controller="almacenesController" class="layout-top-nav"
    style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    {{almacen.drogueria}}<br />
                    <small>Haga clic sobre los cuadros o iconos para acceder a la información</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li><a href="almacenes.php"><i class="fa fa-home"></i> Almacenes</a></li>
                    <li class="active">Administración</li>
                </ol>
            </section>

            <!-- Main content programa 2021 -->
            <section class="container-flex">
                <div class="row" ng-init="seccion = 0">
                    <div class="col-sm-12 col-md-2 offset-md-10">
                        <a class="btn btn-danger btn-block" href="almacenes.php">
                            <i class="fa fa-home"></i>
                            Volver
                        </a>
                        <br>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div ng-hide="datos_usuario.es_administrador == 2">
                            <div class="col-md-12 col-sm-6 col-xs-12"
                                ng-click="seccion = 1; CargarEcuEmpleadosAlmacen();">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-pie-chart"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Estado De Cuenta</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 15;">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Cuotas Distribuidora</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12"
                                ng-click="seccion = 2;CargarTemporadasVentasAlmacen()">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green">
                                        <i class="fa fa-trophy"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Ranking Actual</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12"
                                ng-click="seccion = 3;CargarTemporadasVentasAlmacen()">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow">
                                        <i class="fa fa-child"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Ganadores Bimestre</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 4;CargaRedencionesAlmacen();">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red">
                                        <i class="fa fa-truck"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Entregas</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 5;CargarCuotasSupervisor()">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-handshake-o"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Supervisor</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>

                            <div class="col-md-12 col-sm-6 col-xs-12" ng-show="datos_usuario.es_administrador == 1"
                                ng-click="seccion = 7;CargarCuposAlmacenes();">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Información Almacén</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>

                            <div class="col-md-12 col-sm-6 col-xs-12"
                                ng-click="seccion = 11;ObtenerDocumentoHabeasData()">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow">
                                        <i class="fa fa-file"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Terminos y Condiciones</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12"
                                ng-click="seccion = 13;CargarCuotasVendedorSupervisor()">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red">
                                        <i class="fa fa-users"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Cuotas Vendedor</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 14;BuscarSupervisor();">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green">
                                        <i class="fa fa-plus"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <br />
                                        <span class="info-box-text">Crear Vendedor</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 12;CargarCuotasSupervisor()">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fa fa-user"></i>
                                </span>

                                <div class="info-box-content">
                                    <br />
                                    <span class="info-box-text">Simulador Ventas</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-9" id="volver">
                        <div ng-show="seccion == 1" class="col sm-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Estado Cuenta</h2>
                            <input type="text" class="form-control" ng-model="filtros.nombre"
                                placeholder="Nombre Vendedor" ng-change="SeleccionarListadoEmpleados()" />
                            <br />
                            <table class="table hoverTable">
                                <thead>
                                    <tr class="text-center"">
                                        <th colspan=" 1">
                                        </th>
                                        <th colspan="3">Venta</th>
                                        <th colspan="4">Puntos</th>
                                        <th colspan="1"></th>
                                    </tr>
                                    <tr>
                                        <th>Vendedor</th>
                                        <th>General</th>
                                        <th>Advil / Dolex</th>
                                        <th>Impactos</th>
                                        <th>General</th>
                                        <th>Advil / Dolex</th>
                                        <th>Impactos</th>
                                        <th>Total Puntos</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="puntos in empleados"
                                        ng-click="VerDetalleEstadoCuenta(puntos.id_vendedor)">
                                        <td>{{puntos.vendedor}}</td>
                                        <td>${{puntos.venta | number}}</td>
                                        <td>${{puntos.venta_especial | number}}</td>
                                        <td>{{puntos.impactos | number}}</td>
                                        <td>{{puntos.puntos_venta | number}}</td>
                                        <td>{{puntos.puntos_especial | number}}</td>
                                        <td>{{puntos.puntos_impactos | number}}</td>
                                        <td>{{puntos.total_puntos | number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div ng-show="seccion == 15" class="col sm-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Cuotas Distribuidoras</h2>
                            <p>¡Hola!
                                <br>
                                En este modulo podras ingresar las cuotas de tu distribuidora.
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <label for="cuota_ventas">Seleccione mes a cargar</label>
                                    <select class="form-control" name="cuota_mes_distribuidora"
                                        ng-model="mes_cuota_seleccionado" id="cuota_mes_distribuidora">
                                        <option value="13">Enero</option>
                                        <option value="14">Febrero</option>
                                        <option value="15">Marzo</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <br>
                                    <button type="button" class="btn btn-primary"
                                        ng-click="CargarCuotasAlmacen(mes_cuota_seleccionado)">Consultar Cuota</button>
                                </div>
                            </div>
                            <div class="row" ng-if="crear_nueva_cuota == 0">
                                <div class="col-sm-12 col-md-6">
                                    <small>*No se encontraron cuotas registradas para este periodo</small>
                                    <br>
                                    <label for="cuota_ventas">Crear Nueva Cuota de Venta</label>
                                    <input class="form-control" type="text" id="cuota_ventas" name="cuota_ventas"
                                        ng-model="nueva_cuota_distribuidora" onkeyup="format(this)"
                                        onchange="format(this)">
                                    <label for="cuota_impactos">Crear Nueva Cuota de Impactos</label>
                                    <input class="form-control" type="text" id="cuota_impactos" name="cuota_impctos"
                                        ng-model="nueva_cuota_distribuidora_impactos" onkeyup="format(this)"
                                        onchange="format(this)">
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <br>
                                    <button class="btn btn-primary"
                                        ng-click="GuardarCuotasDistribuidora(nueva_cuota_distribuidora,nueva_cuota_distribuidora_impactos,mes_cuota_seleccionado)">
                                        <i class="fa fa-plus"></i>
                                        Crear Nueva Cuota
                                    </button>
                                </div>
                            </div>
                            <div class="row" ng-if="crear_nueva_cuota == 1">
                                <div class="col-sm-12 col-md-10">
                                    <label for="cuota_ventas">Editar Cuota Ventas</label>
                                    <input class="form-control" type="text" ng-model="cuotas_distribuidora[0].cuota"
                                        id="cuota_ventas" name="cuota_ventas" onkeyup="format(this)"
                                        onchange="format(this)">
                                    <label for="cuota_impactos">Editar Cuota Impactos</label>
                                    <input class="form-control" type="text" ng-model="cuotas_distribuidora[0].impactos"
                                        id="cuota_impactos" name="cuota_impactos" onkeyup="format(this)"
                                        onchange="format(this)">
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <br>
                                    <button class="btn btn-primary"
                                        ng-click="GuardarCuotasDistribuidora(nueva_cuota_distribuidora,nueva_cuota_distribuidora_impactos,mes_cuota_seleccionado)">
                                        <i class="fa fa-plus"></i>
                                        Actualizar Cuota
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div ng-show="seccion == 2" class="col md-12 text-left elementos"
                            ng-show="redenciones_empleado.length > 0">
                            <br />
                            <h2 class="text-center">Ranking Actual <small>Seleccione la temporada que desea
                                    ver</small>
                            </h2>
                            <div ng-repeat="temporada in temporadas_ranking track by $index"
                                ng-init="id_temporada_ranking_activa = 0;">
                                <button class="btn btn-primary btn-block text-left"
                                    ng-click="id_temporada_ranking_activa = id_temporada_ranking_activa == 0 ? temporada.id : 0">{{temporada.nombre}}</button>
                                <table class="table" ng-show="id_temporada_ranking_activa == temporada.id">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th class="text-center hidden">Solicitadas</th>
                                            <th class="text-center">Puntos</th>
                                            <th class="text-center">Ranking</th>
                                            <th class="text-center">Novedad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="ganador in temporada.ranking track by $index">
                                            <td class="text-uppercase">{{ganador.vendedor}}</td>
                                            <td class="text-uppercase">{{ganador.categoria}}</td>
                                            <td class="text-center hidden">{{ganador.entregas_solicitadas}}</td>
                                            <td class="text-center">{{ganador.puntos| number}}</td>
                                            <td class="text-center">{{ganador.puesto}}</td>
                                            <td class="text-center">{{ganador.novedad}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div ng-show="seccion == 3" class="col md-12 text-left elementos"
                            ng-show="redenciones.length >= 0">
                            <br />
                            <h2 class="text-center">Ganadores</h2>
                            <table class="table" ng-hide="almacen.telefono == 0">
                                <thead>
                                    <tr>
                                        <th>Nombre vendedor</th>
                                        <th>Categoría</th>
                                        <th class="text-center">Bimestre</th>
                                        <th class="text-center">Puntos</th>
                                        <th class="text-center">Solicitar</th>
                                        <th class="text-center">Denegar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="ganador in temporadas_ranking_diamante.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada.cupos_diamante && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center "
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-ms"
                                                ng-show="redenciones_temporada < cupos_temporada.cupos_diamante">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <button class="btn btn-danger btn-sm"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_diamante_0.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada_4.cupos_diamante && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center "
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-ms"
                                                ng-show="redenciones_temporada < cupos_temporada_4.cupos_diamante">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <button ng-show="redenciones_temporada < cupos_temporada_4.cupos_diamante"
                                                class="btn btn-danger btn-sm"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_oro.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada.cupos_oro && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center "
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_oro && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-ms"
                                                ng-show="redenciones_temporada < cupos_temporada.cupos_oro">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_oro && datos_usuario.es_administrador != 4">
                                            <button class="btn btn-danger btn-sm"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_oro_0.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada_4.cupos_oro && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center "
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_oro && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-ms"
                                                ng-show="redenciones_temporada < cupos_temporada_4.cupos_oro">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_oro && datos_usuario.es_administrador != 4">
                                            <button ng-show="redenciones_temporada < cupos_temporada_4.cupos_oro"
                                                class="btn btn-danger btn-sm"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_plata.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada.cupos_plata && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center "
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_plata && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-ms"
                                                ng-show="redenciones_temporada < cupos_temporada.cupos_plata">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_plata && datos_usuario.es_administrador != 4">
                                            <button class="btn btn-danger btn-sm"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_plata_0.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada_4.cupos_plata && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center "
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_plata && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-ms"
                                                ng-show="redenciones_temporada < cupos_temporada_4.cupos_plata">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_plata && datos_usuario.es_administrador != 4">
                                            <button ng-show="redenciones_temporada < cupos_temporada_4.cupos_plata"
                                                class="btn btn-danger btn-sm"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr class="hide" ng-repeat="ganador in temporada_1.ranking track by $index"
                                        ng-show="redencion.puede_redimir != 0 && $index < almacen.encuestas_periodo">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < almacen.encuestas_periodo && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=1&id_categoria={{ganador.id_categoria}}"
                                                class="btn btn-primary btn-sm"
                                                ng-show="redenciones_temporada < almacen.encuestas_periodo">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center"
                                            ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < almacen.encuestas_periodo && datos_usuario.es_administrador != 4">

                                            <button ng-show="redenciones_temporada < almacen.encuestas_periodo"
                                                class="btn btn-danger btn-tiny"
                                                ng-click="IniciarDenegacionRedencion(ganador.id_ecu)"
                                                data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div ng-show="seccion == 4" class="col md-12 text-left elementos"
                            ng-show="redenciones.length > 0">
                            <br />
                            <div ng-show="datos_usuario.es_administrador != 4">
                                <h2 class="text-center">Entregas</h2>
                                <h3>Legalizar por temporada</h3>
                                <br />
                                <div class="row">
                                    <div class="col-sm-12 col-md-12"
                                        ng-repeat="temp in temporadas_activas track by $index">
                                        <button ng-click="SeleccionarTemporadaEntregas(temp.id)"
                                            class="btn btn-primary btn-block">
                                            <i class="fa fa-eye"></i>
                                            Ver {{temp.nombre}}
                                        </button>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <br>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#videoTutorial">
                                            Video Tutorial
                                        </button>
                                    </div>
                                </div>
                                <br />
                                <div class="button-group" role="group" aria-label="">
                                    <a class="btn btn-danger"
                                        ng-repeat="temporada in temporadas_por_legalizar track by $index"
                                        ng-href="legalizacion_masiva.php?id_almacen={{almacen.id_drogueria}}&id_temporada={{temporada.id}}"
                                        ng-show="redenciones_procesadas > 0 && (temporada.id == temporada_seleccionada)">
                                        <i class="fa fa-pencil"></i> Legalizar {{temporada.nombre}}
                                    </a>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Nombre Vendedor</th>
                                        <th>Clasificación</th>
                                        <th>Categoría</th>
                                        <th>Entrega</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Temporada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="redencion in redenciones track by $index"
                                        ng-show="redencion.id_temporada == temporada_seleccionada">
                                        <td class="text-left">{{redencion.id_redencion}}</td>
                                        <td class="text-left">{{redencion.empleado}}</td>
                                        <td class="text-left">{{redencion.clasificacion}}</td>
                                        <td class="text-left">{{redencion.categoria}}</td>
                                        <td class="text-left">{{redencion.premio}}</td>
                                        <td class="text-left">{{redencion.fecha_redencion}}</td>
                                        <td class="text-left">{{redencion.estado}}</td>
                                        <td class="text-left">{{redencion.temporada}}</td>
                                        <td class="text-left" ng-show="redencion.id_operacion == 5">
                                            <a class="btn btn-primary" target="_blank"
                                                ng-href="documento_legalizacion.php?folio={{redencion.id_redencion}}">
                                                Ver documento
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div ng-show="seccion == 5" class="col md-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Cuotas Supervisores</h2>
                            <div class="col-sm-12">
                                <div ng-repeat="temporada in cuotas_supervisor">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{temporada.temporada}}</th>
                                                <th style="border-bottom: 3px solid orange; text-align: center;"
                                                    colspan="3">{{temporada.periodo_1}}</th>
                                                <th style="border-bottom: 3px solid yellow; text-align: center;"
                                                    colspan="3">{{temporada.periodo_2}}</th>
                                                <th style="border-bottom: 3px solid greenyellow; text-align: center;"
                                                    colspan="3">General</th>
                                            </tr>
                                            <tr>
                                                <th>Supervisor</th>
                                                <th class="text-center">Cuota</th>
                                                <th class="text-center">Venta</th>
                                                <th class="text-center">Cum</th>
                                                <th class="text-center">Cuota</th>
                                                <th class="text-center">Venta</th>
                                                <th class="text-center">Cum</th>
                                                <th class="text-center">Cuota</th>
                                                <th class="text-center">Venta</th>
                                                <th class="text-center">Cum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="cuota in empleados_cuotas  track by $index"
                                                ng-show="cuota.id_temporada == temporada.id_temporada">
                                                <td>{{cuota.supervisor}}</td>
                                                <td>${{cuota.cuota_1| number}}</td>
                                                <td>${{cuota.venta_1| number}}</td>
                                                <td>{{cuota.cumplimiento_1| number:1}}%</td>
                                                <td>${{cuota.cuota_2| number}}</td>
                                                <td>${{cuota.venta_2| number}}</td>
                                                <td>{{cuota.cumplimiento_2| number:1}}%</td>
                                                <td>${{cuota.cuota_bimestre| number}}</td>
                                                <td>${{cuota.venta_bimestre| number}}</td>
                                                <td>{{cuota.cumplimiento_bimestre| number}}%</td>
                                                <td>
                                                    <a ng-show="cuota.ranking <= almacen.supervisores && cuota.cumplimiento_bimestre >= 95 && cuota.redenciones_temporada == 0 && cuota.puede_redimir == 1 && cuota.id_temporada == 4"
                                                        ng-href="redenciones.php?id_afiliado={{cuota.id_supervisor}}&id_almacen={{cuota.id_almacen}}&id_temporada={{cuota.id_temporada}}&catalogo_perfecto=0&id_categoria=1"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <p
                                                        ng-show="cuota.cumplimiento_bimestre >= 95 && cuota.redenciones_temporada == 1 && cuota.puede_redimir == 1">
                                                        Ya Solicitó
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div ng-show="seccion == 7" class="col md-12 text-left elementos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2 class="text-center">Información de la distribuidora</h2>
                                </div>

                                <div class="col-sm-12 col-md-offset-1 col-md-5">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input class="form-control" type='text' ng-model="almacen.drogueria"
                                            ng-disabled="true" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5">
                                    <div class="form-group">
                                        <label for="nombre">Visitador:</label>
                                        <input class="form-control" type='text' ng-model="almacen.visitador"
                                            ng-disabled="true" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-offset-1 col-md-10">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Temporada</th>
                                                <th>Cupos Supervisor</th>
                                                <th>Cupos Diamante</th>
                                                <th>Cupos Oro</th>
                                                <th>Cupos Plata</th>
                                                <th>Total Cupos</th>
                                            </tr>
                                        <tbody>
                                            <tr ng-repeat="cupos in cupos_almacenes">
                                                <td>{{cupos.temporada}}</td>
                                                <td>{{cupos.supervisores}}</td>
                                                <td>{{cupos.cupos_diamante}}</td>
                                                <td>{{cupos.cupos_oro}}</td>
                                                <td>{{cupos.cupos_plata}}</td>
                                                <td>{{cupos.total_premiados}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div ng-show="false" class="col-sm-4">
                                    <button class="btn btn-primary" ng-click="ObtenerCategoriasLlamadas()"
                                        data-open="modalLlamadas">
                                        Capturar Llamada
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div ng-show="seccion == 11" class="col md-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Términos y Condiciones</h2>

                            <section class="content">
                                <div class='row text-justify'>
                                    <div class="col-sm-12 col-md-offset-1 col-md-10">
                                        <div class="col-sm-12 col-md-offset-1 col-md-10">
                                            <br />
                                            <img src='../images/logos/tat.png' style='width: 150px;' />
                                            <img src='../images/logos/gsk_logo.png' style='width: 150px;' />
                                            <br />
                                            <br />
                                        </div>

                                        <div class="row" ng-init="tipo = 0">
                                            <div class="col-sm-12 col-md-offset-1 col-md-10">
                                                <div ng-show="habeas_data.length == 1 && habeas_data[0].tipo_acta == 1">
                                                    <iframe ng-src="{{habeas_data[0].firma}}" frameborder="0"
                                                        width="100%" height="550" marginheight="0" marginwidth="0"
                                                        id="pdf">
                                                        Ver Habeas Data
                                                    </iframe>
                                                </div>
                                                <div
                                                    ng-show="habeas_data.length == 0 || (habeas_data.length == 1 && habeas_data[0].tipo_acta == 0)">
                                                    <iframe
                                                        src="terminoscondiciones_programa_sociosamigos_canal_tat.pdf"
                                                        frameborder="0" width="100%" height="550" marginheight="0"
                                                        marginwidth="0" id="pdf">
                                                        Ver Habeas Data
                                                    </iframe>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-offset-1 col-md-10"
                                                ng-show="habeas_data.length == 0">
                                                <div class="col-sm-12 col-md-12">
                                                    <button class="btn btn-primary btn-block"
                                                        ng-click="tipo = 1">Aceptar Términos y Condiciones con
                                                        Firma</button>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <br>
                                                    <button class="btn btn-primary btn-block"
                                                        ng-click="tipo = 2">Aceptar Términos y Condiciones con
                                                        Documento</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" ng-show="habeas_data.length == 0 && tipo == 1"
                                            ng-init="boton = 1">
                                            <div class="col-sm-12 col-md-offset-1 col-md-4">
                                                <p>
                                                    Cliente
                                                    <input type="text" class="form-control" ng-model="almacen.drogueria"
                                                        ng-disabled="true" />
                                                </p>
                                                <p>
                                                    <b>Apreciado Cliente:</b>
                                                    <input class="form-control" type='text'
                                                        ng-model="legalizacion.nombre" />
                                                </p>
                                                <p>
                                                    <b>Documento Cliente:</b>
                                                    <input class="form-control" type='number'
                                                        ng-model="legalizacion.documento" />
                                                </p>
                                                <p>
                                                    Fecha
                                                    <input class="form-control" type='text' ng-disabled="true"
                                                        ng-model="legalizacion.fecha" />
                                                </p>
                                            </div>
                                            <div class="col-sm-12 col-md-offset-1 col-md-10">
                                                <div id="img_perfil">
                                                    <img id="sig-image-confirmed" src="" alt="Firma no registrada!"
                                                        ng-show="legalizacion.firma != ''" />
                                                </div>
                                                <button class="btn btn-primary" data-toggle="modal"
                                                    data-target="#singModal" ng-click="AbrirModalFirmaVendedor()">Firma
                                                    Cliente</button>
                                            </div>
                                            <div class="col-sm-12 col-md-offset-1 col-md-10">
                                                <br />
                                                <br />
                                                <button class="btn btn-success" id="sig-clearBtn" ng-show="boton == 1"
                                                    ng-click="LegalizarRedencion()">Guardar</button>
                                            </div>
                                        </div>
                                        <div class="row" ng-show="habeas_data.length == 0 && tipo == 2 "
                                            ng-init="boton = 1">
                                            <div class="col-sm-12 col-md-offset-1 col-md-4">
                                                <h3>Selecione el documento.</h3>
                                                <form method="POST" action="clases/cargararchivos.php"
                                                    enctype="multipart/form-data">
                                                    <input type="file" name="archivo" accept="application/pdf" required>
                                                    <input hidden type="text" name="id_almacen"
                                                        ng-model="almacen.id_drogueria"
                                                        value="{{almacen.id_drogueria}}">
                                                    <button type="submit" class="btn btn-primary btn-block"
                                                        value="cargar Archivos"><i class="fa fa-upload"></i> Subir
                                                        Archivo</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-offset-1 col-md-4"
                                                ng-show="habeas_data.length == 1">
                                                <a ng-if="habeas_data[0].tipo_acta == 0"
                                                    class="btn btn-primary btn-block"
                                                    href="https://sociosyamigos.com.co/tat/ver_actas.php?id_almacen={{habeas_data[0].id_almacen}}">
                                                    Descargar Documento</a>

                                                <a ng-if="habeas_data[0].tipo_acta == 1"
                                                    class="btn btn-primary btn-block" target="_blank"
                                                    href="https://sociosyamigos.com.co/tat/{{habeas_data[0].firma}}">
                                                    Descargar Documento</a>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row" ng-show="false">
                                            <div class="col-md-12">
                                                <textarea id="sig-dataUrl" class="form-control" rows="5"
                                                    ng-value="legalizacion.firma_vendedor">Data URL for your signature will go here!</textarea>
                                            </div>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                            </section>

                        </div>

                        <div ng-show="seccion == 12" class="col md-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Simulador Ventas</h2>
                            <div class="col-sm-12">
                                <div ng-repeat="temporada in cuotas_vendedores track by $index"
                                    ng-show="temporada.id_temporada == 5">
                                    <table class="table">
                                        <thead>
                                            <tr ng-hide="datos_usuario.es_administrador == 2">
                                                <th>{{temporada.temporada}}</th>
                                                <th style="border-bottom: 3px solid orange; text-align: center;"
                                                    colspan="4">{{temporada.periodo_1}}</th>
                                                <th style="border-bottom: 3px solid yellow; text-align: center;"
                                                    colspan="4">{{temporada.periodo_2}}</th>
                                                <th style="border-bottom: 3px solid greenyellow; text-align: center;"
                                                    colspan="4">General</th>
                                            </tr>
                                            <tr>
                                                <th>Supervisor</th>
                                                <th>Simulador de Cierre</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Cuota</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Venta</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Cum</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Cuota</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Venta</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Cum</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Cuota</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Venta</th>
                                                <th ng-hide="datos_usuario.es_administrador == 2" class="text-center">
                                                    Cum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="cuota in empleados_cuotas track by $index"
                                                ng-show="cuota.id_temporada == 5">
                                                <td>{{cuota.supervisor}}</td>
                                                <td>
                                                    <a href="simulador_ventas.php?id_usuario={{cuota.id_afiliado}}&id_temporada={{cuota.id_temporada}}"
                                                        class="btn btn-primary">
                                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.cuota_1| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.venta_1| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    {{cuota.cumplimiento_1| number:1}}%</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.cuota_2| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.venta_2| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    {{cuota.cumplimiento_2| number:1}}%</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.cuota_bimestre| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.venta_bimestre| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    {{cuota.cumplimiento_bimestre| number}}%</td>
                                                <td>
                                                    <a ng-show="cuota.cumplimiento_bimestre >= 100 && cuota.redenciones_temporada == 0 && cuota.puede_redimir == 1 && datos_usuario.es_administrador != 4 && cuota.id_almacen != 56"
                                                        ng-href="redenciones.php?id_afiliado={{cuota.id_supervisor}}&id_almacen={{cuota.id_almacen}}&id_temporada={{cuota.id_temporada}}&catalogo_perfecto=0"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    <p
                                                        ng-show="cuota.cumplimiento_bimestre >= 100 && cuota.redenciones_temporada == 1 && cuota.puede_redimir == 1 && datos_usuario.es_administrador != 4 && cuota.id_almacen != 56">
                                                        Ya Solicitó
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div ng-show="seccion == 13" class="col md-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Cuotas Vendedores</h2>
                            <div class="col-sm-12 text-center">
                                <div class="box-body-cuotas">
                                    <div class="box-group" id="accordion">
                                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                        <div class="panel box box-primary"
                                            ng-repeat="temporada in cuotas_vendedores_supervisor track by $index">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion"
                                                        href="#collapseOne_{{$index}}" aria-expanded="false"
                                                        class="collapsed">
                                                        Clic para ver el {{temporada.temporada}}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_{{$index}}" class="panel-collapse collapse"
                                                aria-expanded="false" style="height: 0px;">
                                                <div class="cuotas-scroll">
                                                    <input type="text" class="form-control" ng-model="filtros.nombre"
                                                        placeholder="Filtrar Nombre Vendedor"
                                                        ng-change="SeleccionarListadoEmpleadosCuotasSupervisor()" />
                                                    <br />
                                                    <table class="table text-left">
                                                        <thead>
                                                            <tr ng-hide="datos_usuario.es_administrador == 2">
                                                                <th>{{temporada.temporada}}</th>
                                                                <th></th>
                                                                <th style="border-bottom: 3px solid orange; text-align: center;"
                                                                    colspan="4">{{temporada.periodo_1}}</th>
                                                                <th style="border-bottom: 3px solid yellow; text-align: center;"
                                                                    colspan="4">{{temporada.periodo_2}}</th>
                                                                <th style="border-bottom: 3px solid greenyellow; text-align: center;"
                                                                    colspan="4">General</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Vendedor</th>
                                                                <th>Actualizar Cuota</th>
                                                                <th>Categoría</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Cuota</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Venta</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Cum</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Cuota</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Venta</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Cum</th>
                                                                <th></th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Cuota</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Venta</th>
                                                                <th ng-hide="datos_usuario.es_administrador == 2"
                                                                    class="text-center">Cum</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="cuota in empleados_cuotas_supervisor track by $index"
                                                                ng-show="cuota.id_temporada == temporada.id_temporada">
                                                                <td>{{cuota.supervisor}}</td>
                                                                <td><button class="btn btn-primary"
                                                                        ng-show="cuota.id_temporada == 8"
                                                                        ng-click="ActualizarCuotasVendedores(cuota.id_cuota, cuota.id_categoria, cuota.cuota_1, cuota.cuota_2);"><i
                                                                            class="fa fa-pencil"></i></button></td>
                                                                <td>{{cuota.categoria}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    ${{cuota.cuota_1| number}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    ${{cuota.venta_1| number}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    {{cuota.cumplimiento_1| number:1}}%</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    ${{cuota.cuota_2| number}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    ${{cuota.venta_2| number}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    {{cuota.cumplimiento_2| number:1}}%</td>
                                                                <td></td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    ${{cuota.cuota_bimestre| number}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    ${{cuota.venta_bimestre| number}}</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    {{cuota.cumplimiento_bimestre| number}}%</td>
                                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                                    <a ng-show="false && cuota.cumplimiento_bimestre >= 100 && cuota.redenciones_temporada == 0 && cuota.puede_redimir == 1 && datos_usuario.es_administrador != 4 && cuota.id_almacen != 56"
                                                                        ng-href="redenciones.php?id_afiliado={{cuota.id_supervisor}}&id_almacen={{cuota.id_almacen}}&id_temporada={{cuota.id_temporada}}&catalogo_perfecto=0"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="fa fa-shopping-cart"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.box-body -->

                            </div>
                        </div>

                        <div ng-show="seccion == 14" class="col md-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Crear Vendedor</h2>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <label>Seleccione Supervisor</label>
                                    <select class="form-control" ng-model="nuevo_afiliado.id_supervisor">
                                        <option ng-repeat="supervisores in supervisores_afiliados"
                                            value="{{supervisores.id}}">{{supervisores.nombre}}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label>Ingrese Nombre</label>
                                    <input class="form-control" type="text" ng-model="nuevo_afiliado.nombre" />
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label>Seleccione Categoria</label>
                                    <select class="form-control" ng-model="nuevo_afiliado.id_categoria">
                                        <option value="1">Diamante</option>
                                        <option value="2">Oro</option>
                                        <option value="3">Plata</option>
                                        <option value="4">Bronce</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="col-sm-12 col-md-12">
                                        <label>Ingrese Cuota Mes Uno</label>
                                        <input class="form-control" type="text" ng-model="nuevo_afiliado.cuota_1" />
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <label>Ingrese Cuota Mes Dos</label>
                                        <input class="form-control" type="text" ng-model="nuevo_afiliado.cuota_2" />
                                    </div>

                                </div>
                                <div class="col-sm-12 col-md-offset-2 col-md-8">
                                    <br />
                                    <br />
                                    <button class="btn btn-primary btn-block" ng-click="CrearNuevoUsuario();">Crear
                                        Vendedor</button>
                                    <button class="btn btn-danger btn-block" data-toggle="modal"
                                        data-target="#inactivar_usuarios"
                                        ng-click="BuscarVendedoresAlmacen();">Inactivar Vendedor</button>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </section>
            <div class="modal fade " id="detalleEstadoCuenta" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                Detalle Estado Cuenta {{puntos_empleado_detallado[0].vendedor}}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" ng-init="temporada_estado_cuenta = 0">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 1">
                                        Primer bimestre 2021
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 2">
                                        Segundo bimestre 2021
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 3">
                                        Tercer bimestre 2021
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 4">
                                        Cuarto bimestre 2021
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 5">
                                        Quinto bimestre 2021
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 6">
                                        Sexto bimestre 2021s
                                    </button>
                                </div>
                            </div>
                            <div class=" text-center caja_bimestres_estado_cuenta"
                                ng-repeat="puntos in puntos_empleado_detallado"
                                ng-show="puntos.id_temporada == temporada_estado_cuenta">
                                <br>
                                <h3>{{puntos.categoria}}</h3>
                                <table id="tabla_supevisor" class="table table-bordered table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th colspan="5">{{puntos.periodo}}</th>
                                        </tr>
                                        <tr>

                                            <th colspan="1"> </th>
                                            <th> Cuota </th>
                                            <th> Venta </th>
                                            <th> Cumplimiento </th>
                                            <th> Puntos </th>
                                        </tr>
                                        <tr>
                                            <th>Venta</th>
                                            <td>${{puntos.cuota | number}}</td>
                                            <td>${{puntos.venta | number}}</td>
                                            <td>{{puntos.cumplimiento}}</td>
                                            <td>{{puntos.puntos_venta | number}}</td>
                                        </tr>
                                        <tr>
                                            <th>Impactos</th>
                                            <td>{{puntos.cuota_impactos}}</td>
                                            <td>{{puntos.impactos | number}}</td>
                                            <td>{{puntos.cumplimiento_impactos}}</td>
                                            <td>{{puntos.puntos_impactos | number}}</td>
                                        </tr>
                                        <tr>
                                            <th>Advil / Dolex</th>
                                            <td colspan="1"></td>
                                            <td>${{puntos.venta_especial | number}}</td>
                                            <td colspan="1"></td>
                                            <td>{{puntos.puntos_especial | number}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"> Total Puntos</td>
                                            <td>{{puntos.total_puntos | number}}</td>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="singModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Firma Cliente</h5>
                            <br>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <button class="btn btn-primary" id="sig-submitBtn" ng-click="GuardarFirmaVendedor()"
                                ng-show="legalizacion.firma == ''" data-dismiss="modal">
                                Confirmar Firma
                            </button>
                            <canvas id="sig-canvas" ng-show="legalizacion.firma == ''">
                                El navegador no es compatible con esta funcionalidad
                            </canvas>
                            <img id="sig-image" src="" alt="Firma no registrada!" ng-show="legalizacion.firma != ''" />
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
        </div>


    </div>


    <div class="modal fade" id="modalDetallePuntos" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1>Ventas Detalladas</h1>
                </div>
                <div class="modal-body">
                    <div ng-repeat="periodo in ventas_periodo track by $index" ng-init="detalle_activo = -1;
                                        periodo_seleccionado = -1">
                        <div class="row"
                            ng-click="periodo_seleccionado = periodo_seleccionado == -1 ? periodo.id_periodo : -1">
                            <div class="col-md-8 text-left">
                                <b>{{periodo.periodo}}</b>
                                <i class="fa fa-plus-circle" ng-show="periodo_seleccionado == -1"></i>
                                <i class="fa fa-minus-circle" ng-show="periodo_seleccionado != -1"></i>
                            </div>
                            <div class="col-md-4 text-right"><b><u>${{periodo.ventas| number}}</u></b></div>
                        </div>
                        <div ng-show="periodo_seleccionado == periodo.id_periodo">
                            <div class="row">
                                <div class="col-md-7"><b>Categoria</b></div>
                                <div class="col-md-2 text-center"><b>Cantidad</b></div>
                                <div class="col-md-3 text-right"><b>Venta</b></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div ng-repeat="ventas in periodo.detalle track by $index">
                                        <div class="row" ng-click="detalle_activo = detalle_activo == -1 ? $index : -1">
                                            <div class="col-md-7">
                                                {{ventas.categoria}}
                                                <i class="fa fa-plus-circle" ng-show="detalle_activo == -1"></i>
                                                <i class="fa fa-minus-circle" ng-show="detalle_activo != -1"></i>
                                            </div>
                                            <div class="col-md-2 text-center"><b>{{ventas.unidades}}</b></div>
                                            <div class="col-md-3 text-right"><b>${{ventas.venta_total| number}}</b>
                                            </div>
                                        </div>
                                        <div ng-show="detalle_activo == $index">
                                            <div class="row">
                                                <div class="col-md-3"><b>Producto</b></div>
                                                <div class="col-md-3"><b>Portafolio</b></div>
                                                <div class="col-md-2"><b>SKU</b></div>
                                                <div class="col-md-1 text-center"><b>Cantidad</b></div>
                                                <div class="col-md-3 text-right"><b>Venta</b></div>
                                            </div>
                                            <div ng-repeat="detalle in ventas.detalle">
                                                <div class="row">
                                                    <div class="col-md-3">{{detalle.producto}}</div>
                                                    <div class="col-md-3">{{detalle.portafolio}}</div>
                                                    <div class="col-md-2">{{detalle.sku}}</div>
                                                    <div class="col-md-1 text-center">{{detalle.unidades}}</div>
                                                    <div class="col-md-3 text-right">${{detalle.venta_total| number}}
                                                    </div>
                                                </div>
                                            </div>
                                            <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDenegarRedencion" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Denegar Entrega</h3>
                </div>
                <div class="modal-body">
                    <p>Inidique la razón por la cual se denegará esta entrega</p>
                    <br />
                    <select class="form-control" ng-model="razon_denegacion">
                        <option value="Retiro">Retiro</option>
                        <option value="Licencia de maternidad">Licencia de maternidad</option>
                        <option value="Incapacidad">Incapacidad</option>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Informacion incorrecta">Informacion incorrecta</option>
                        <option value="Gana siguiente en ranking">Gana siguiente en ranking</option>
                        <option value="Cambio de zona">Cambio de zona</option>
                        <option value="Supera Cupo de Entregas Distribuidora">Supera Cupo de Entregas Distribuidora
                        </option>
                    </select>
                    <div class="row">
                        <br />
                        <br />
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-danger" ng-click="LimpiarDenegacion()">Cerrar</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary" ng-click="DenegarRedencion()">Confirmar Denegación</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDenegarRankingAnual" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Denegar Vendedor Ranking Anual</h3>
                </div>
                <div class="modal-body">
                    <p>Inidique la razón por la cual se denegará esta entrega
                    <p>
                        <br />
                        <select class="form-control" ng-model="ranking_anual_seleccionado.razon">
                            <option value="Diferencia de ventas – devolver para revisar">Diferencia de ventas –
                                devolver para revisar</option>
                            <option value="Vendedor retirado">Vendedor retirado</option>
                            <option value="Vendedor Fallecido">Vendedor Fallecido</option>
                        </select>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary" ng-click="DenegarRankingAnual($index)">Confirmar
                                Denegación</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDenegarVendedorPerfecto" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1>Denegar Vendedor Perfecto</h1>
                    Inidique la razón por la cual se denegará esta entrega
                    <br />
                </div>
                <div class="modal-body">
                    <select class="form-control" ng-model="razon_denegacion_vendedor_perfecto">
                        <option value="Retiro">Retiro</option>
                        <option value="Licencia de maternidad">Licencia de maternidad</option>
                        <option value="Incapacidad">Incapacidad</option>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Informacion incorrecta">Informacion incorrecta</option>
                        <option value="Gana siguiente en ranking">Gana siguiente en ranking</option>
                        <option value="Cambio de zona">Cambio de zona</option>
                        <option value="Supera Cupo de Entregas Distribuidora">Supera Cupo de Entregas Distribuidora
                        </option>
                    </select>
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-danger" ng-click="LimpiarDenegacion()">Cerrar</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary" ng-click="DenegarVendedorPerfecto()">Confirmar
                                Denegación</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div id="actualizar_cuota" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 600px; height: 300px; margin: 10% 0% 0% 25%;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Actualizar Cuotas</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 form-group">
                            <label>
                                Actualizar Cuota Mes 1.
                            </label>
                            <input type="text" class="form-control" id="cuota_1" onkeyup="format(this)"
                                onchange="format(this)" placeholder="{{ActualizarCuotaCuota1}}" />
                        </div>
                        <div class="col-sm-12 col-md-6 form-group">
                            <label>
                                Actualizar Cuota Mes 2.
                            </label>
                            <input type="text" class="form-control" id="cuota_2" onkeyup="format(this)"
                                onchange="format(this)" placeholder="{{ActualizarCuotaCuota2}}" />
                        </div>
                        <div class="col-sm-12  form-group" ng-init="botonactualizarcuota = true">
                            <br />
                            <br />
                            <br />
                            <button class="btn btn-primary" ng-show="botonactualizarcuota"
                                ng-click="ActualizarCuotasCargadas()">Actualizar Cuotas</button>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="inactivar_usuarios" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inactivar Vendedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" ng-model="filtros.nombre"
                        placeholder="Filtrar Nombre Vendedor" ng-change="SeleccionarListadoVendedoresInactivar()" />
                    <br />
                    <table class="table">
                        <th>Inactivar</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoria</th>
                        <tbody>
                            <tr ng-repeat="inactivar in inactivar_empleado">
                                <td><button class="btn btn-danger btn-tiny"
                                        ng-click="InactivarVendedor(inactivar.id)"><i class="fa fa-trash"></i></button>
                                </td>
                                <td>{{inactivar.id}}</td>
                                <td>{{inactivar.nombre}}</td>
                                <td>{{inactivar.categoria}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="videoTutorial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tutorial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <object width="470" height="415"
                        data="https://www.youtube.com/embed/NjjaY5f-7HQ?rel=0&amp;showinfo=0">
                    </object>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    <script src="js/almacenes.js?ver=44" type="text/javascript"></script>
    <script src="js/signature.js?reload=true"></script>

</body>

</html>