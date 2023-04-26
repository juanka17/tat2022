<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<?php include 'componentes/componentes_basicos.php'; ?>
<link rel="stylesheet" href="dev_x/lib/css/dx.light.css">



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
        border: 2px solid #31e802;
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

    .options {
        padding: 20px;
        margin-top: 20px;
        background-color: rgba(191, 191, 191, 0.15);
    }

    .caption {
        font-size: 18px;
        font-weight: 500;
    }

    .option {
        margin-top: 10px;
    }

    .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        border-top: 3px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
        overflow: auto;
    }

    .box-body {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding: 10px;
        height: auto;
    }

    #cuotas_recomendadas {
        display: flex;
        justify-items: stretch;
        flex-direction: row;
        justify-content: space-around;
    }

    .cuota {
        border: 2px solid black;
        border-radius: 20px;
        padding: 15px;
        margin: 10px;
        width: 100%;
    }

    #overlay {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2;
        cursor: pointer;
    }

    #text {
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 50px;
        color: white;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
    }

    @media (max-width: 800px) {
        .tabla_supevisor {
            display: grid;
            overflow: auto;
        }
    }
</style>
</head>

<body ng-app="almacenesApp" ng-controller="almacenesController" class="layout-top-nav" style="height: auto; min-height: 100%;">
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
                    <li><a href="almacenes.php"><i class="fa fa-home"></i> Distribudora</a></li>
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
                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 1; CargarEcuEmpleadosAlmacen();">
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
                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 15;BuscarSupervisor()">
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
                            <div class="col-md-12 col-sm-6 col-xs-12 hide" ng-click="seccion = 2;CargarTemporadasVentasAlmacen()">
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
                            <div class="col-md-12 col-sm-6 col-xs-12 hide" ng-click="seccion = 3;CargarTemporadasVentasAlmacen()">
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
                            <div class="col-md-12 col-sm-6 col-xs-12 hide" ng-click="seccion = 4;CargaRedencionesAlmacen();">
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

                            <div class="col-md-12 col-sm-6 col-xs-12 hide" ng-show="datos_usuario.es_administrador == 1" ng-click="seccion = 7;CargarCuposAlmacenes();">
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

                            <div class="col-md-12 col-sm-6 col-xs-12" ng-click="seccion = 11;ObtenerDocumentoHabeasData()">
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
                    </div>
                    <div id="overlay">
                        <div id="text">Cargando...</div>
                    </div>
                    <div class="col-sm-12 col-md-9" id="volver">
                        <div ng-show="seccion == 1" class="col sm-12 text-left elementos">
                            <br />
                            <h2 class="text-center">Estado Cuenta</h2>
                            <p>¡Para ver el detalle por mes de la venta reportada, por favor de clic en el nombre del participante que desea consultar.!</p>
                            <input type="text" class="form-control" ng-model="filtros.nombre" placeholder="Nombre Vendedor" ng-change="SeleccionarListadoEmpleados()" />
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
                                    <tr ng-repeat="puntos in empleados" ng-click="VerDetalleEstadoCuenta(puntos.id_vendedor)">
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
                            <button class="btn btn-primary btn-tiny" ng-click="seccion = 14;BuscarSupervisor()">
                                Crear nuevo vendedor
                            </button>
                            <p>¡Hola Equipo!
                                <br>
                                <spam ng-show="mes_cuota_seleccionado >= 20">
                                    La cuota sugerida que genera el sistema,
                                    se calcula a partir del promedio de venta reportado de los ultimos 3 meses
                                    Antes de empezar a diligenciar las cuotas,
                                    válida si tu equipo de ventas está completo.
                                    De lo contrario, por favor en módulo "Crear Vendedor"
                                    Completa tu fuerza de ventas para comenzar a revisar y
                                    ajustar las cuotas de tu distribuidora.
                                </spam>
                            </p>
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <label for="cuota_ventas">Seleccione mes a cargar</label>
                                    <select class="form-control" name="cuota_mes_distribuidora" ng-model="mes_cuota_seleccionado" ng-change="CargarCuotasAlmacen(mes_cuota_seleccionado)" id="cuota_mes_distribuidora">
                                        <option value="14">Febrero</option>
                                        <option value="15">Marzo</option>
                                        <option value="16">Abril</option>
                                        <option value="17">Mayo</option>
                                        <option value="18">Junio</option>
                                        <option value="19">Julio</option>
                                        <option value="20">Agosto</option>
                                        <option value="21">Septiembre</option>
                                        <option value="22">Octubre</option>
                                        <option value="23">Noviembre</option>
                                        <option value="24">Diciembre</option>
                                        <option value="26">Febrero 2023</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" ng-if="crear_nueva_cuota == 0">
                                <div class="col-sm-12">
                                    <small>*No se encontraron cuotas registradas para este periodo</small>
                                </div>
                                <div class="col-sm-12 col-md-3 hide">
                                    <label for="cuota_ventas">Crear Nueva Cuota de Venta</label>
                                    <input class="form-control" type="text" id="cuota_ventas" ng-blur="MostrarCuotaAumentada()" name="cuota_ventas" ng-model="nueva_cuota_distribuidora" onkeyup="format(this)" onchange="format(this)">
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <label for="cuota_impactos">Crear Nueva Cuota de Impactos</label>
                                    <input class="form-control" type="text" id="cuota_impactos" name="cuota_impctos" ng-model="nueva_cuota_distribuidora_impactos" onkeyup="format(this)" onchange="format(this)">
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <small>Margen {{almacen.margen}}%</small><br>
                                    <spam>{{cuota_aumentada | number:0}}</spam>
                                </div>
                                <div class="col-sm-12 col-md-3 hide">
                                    <br>
                                    <button class="btn btn-primary" ng-click="GuardarCuotasDistribuidora(nueva_cuota_distribuidora,nueva_cuota_distribuidora_impactos,mes_cuota_seleccionado)">
                                        <i class="fa fa-plus"></i>
                                        Crear Nueva Cuota
                                    </button>
                                </div>
                            </div>
                            <div class="row" ng-if="crear_nueva_cuota == 1">
                                <div class="col-sm-12 col-md-3">
                                    <label for="cuota_ventas">Editar Cuota Ventas</label>
                                    <input class="form-control" type="text" ng-model="cuotas_distribuidora[0].cuota" id="cuota_ventas" ng-blur="MostrarCuotaAumentada()" name="cuota_ventas" ng-model="nueva_cuota_distribuidora" name="cuota_ventas" onkeyup="format(this)" onchange="format(this)">
                                </div>
                                <div class="col-sm-12 col-md-3 hide">
                                    <label for="cuota_impactos">Editar Cuota Impactos</label>
                                    <input class="form-control" type="text" ng-model="cuotas_distribuidora[0].impactos" id="cuota_impactos" name="cuota_impactos" onkeyup="format(this)" onchange="format(this)">
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <small>Margen {{almacen.margen}}%</small><br>
                                    <spam>{{cuota_aumentada | number:0}}</spam>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <br>
                                    <button class="btn btn-primary" ng-disabled="mes_cuota_seleccionado <= 19" ng-click="GuardarCuotasDistribuidora(nueva_cuota_distribuidora,nueva_cuota_distribuidora_impactos,mes_cuota_seleccionado)">
                                        <i class="fa fa-plus"></i>
                                        Actualizar Cuota
                                    </button>
                                </div>
                                <div class="col-sm-12">
                                    <br>

                                    <h3 ng-show="mes_cuota_seleccionado <= 19">Cuota distribuidora ${{cuota_total | number:0}}</h3>
                                    <div ng-if="mes_cuota_seleccionado >= 20" id="cuotas_recomendadas">

                                        <div class="cuota">
                                            <h3>Cuota Recomendada</h3>
                                            <h5>Cuota: ${{datos_vendedores[0].cuota_almacen | number:0}}</h5>
                                            <h5>Cuota costo: ${{datos_vendedores[0].cuota_costo | number:0}}</h5>
                                            <h5>Impactos: {{cuota_impactos | number:0}}</h5>
                                        </div>
                                        <div class="cuota">
                                            <h3>Cuota Modificada</h3>
                                            <h5>Cuota: ${{datos_vendedores[0].cuota_modificada | number:0}}</h5>
                                            <h5>Cuota costo: ${{datos_vendedores[0].cuota_costo_modificada | number:0}}</h5>
                                            <h5>Impactos: {{total_impactos | number:0}}</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <button type="button" ng-click="ExportarExcel()">Exportar</button>
                                        </div>
                                        <div class="col-sm-12 col-md-6" ng-show="mes_cuota_seleccionado >= 21">
                                            <input type="file" id="ngexcelfile" ng-blur="ArchivoSeleccionado()" ng-show="imput_subir_archivo" />
                                            <button type="button" class="btn btn-primary" ng-disabled="boton_ver_archivo" ng-click="LeerExcel()"><i class="fa fa-eye"></i> Mostrar Información</button>
                                        </div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="box box-solid">
                                            <div class="box-group" id="accordion">
                                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                                <div class="panel box box-primary" ng-repeat="supervisor in estado_cuenta track by $index" ng-if="$index >0">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" ng-click="CargarImpactosSupervisor(supervisor.id_supervisor)" data-parent="#accordion" href="#collapse_{{supervisor.id_supervisor}}" aria-expanded="false" class="collapsed">
                                                                Supervisor: {{supervisor.supervisor}} | Numero de vendedores ({{supervisor.registros.length}})
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_{{supervisor.id_supervisor}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                        <div class="box-body">
                                                            <span ng-repeat="r in resultados_suma" ng-if="r.id_supervisor == supervisor.id_supervisor">
                                                                Cuota Ventas Supervisor:
                                                                <b style="color: red">
                                                                    ${{r.cuota | number}}
                                                                </b>
                                                            </span>
                                                            <br>
                                                            <span ng-repeat="r in resultados_suma" ng-if="r.id_supervisor == supervisor.id_supervisor">
                                                                Cuota Impactos Supervisor:
                                                                <b style="color: red" id="cuota_impactos_supervisor_{{$index}}">

                                                                    {{r.cuota_impactos | number}}
                                                                </b>
                                                                <br>

                                                                <a  ng-show="mes_cuota_seleccionado >= 21" href="" ng-click="HabilitarModificacionImpactos(supervisor.id_supervisor,$index)"><small> Modificar Cuota Impactos </small><a>
                                                            </span>
                                                            <br>

                                                            <div ng-if="modificar_impactos == 1" ng-show="mes_cuota_seleccionado >= 21">
                                                                <label for="cuota_impactos">Modificar Cuota Impactos</label>
                                                                <input type="text" id="cuota_impactos_supervisor_{{$index}}">
                                                                <button style="cursor:pointer" ng-click="GuardarCuotasImpactos(supervisor.id_supervisor,$index)" class="btn btn-primary"><i class="fa fa-save fa-x2"></i></button>
                                                            </div>

                                                            <!--<div ng-if="modificar_impactos == 1" ng-show="mes_cuota_seleccionado >= 20">
                                                                Cuota Impactos : 
                                                               <b style="color: red">
                                                                {{impactos_supervisor[0].impactos}}
                                                                </b>
                                                            </div>-->


                                                            <table class="table table2excel">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Vendedor</th>
                                                                        <th>Venta Ultimo Q</th>
                                                                        <th>Venta Promedio</th>
                                                                        <th>% Participacion</th>
                                                                        <th>Cuota Vendedor</th>
                                                                        <th>Editar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody ng-init="total = 0">
                                                                    <tr ng-repeat="registro in supervisor.registros track by $index">
                                                                        <td>
                                                                            <spam style="color:blue;cursor:pointer" ng-click="DireccionarVendedor(registro.id_vendedor)">{{registro.vendedor}}
                                                                                <spam>
                                                                        </td>
                                                                        <td>{{registro.venta_ultimo_q_vendedor | number}}</td>
                                                                        <td>{{registro.venta_vendedor | number}}</td>
                                                                        <td>{{registro.porcentaje_participacion}}</td>
                                                                        <td ng-init="$parent.total = total + (registro.cuota_vendedor - 0)">
                                                                            ${{registro.cuota_vendedor | number}}
                                                                        </td>
                                                                        <td ng-show="mes_cuota_seleccionado >= 21">
                                                                            <button class="btn btn-primary btn-tiny" ng-click="ModificarCuotasVendedor(registro)">Editar</button>
                                                                        </td>
                                                                        <td ng-show="mes_cuota_seleccionado >= 21">
                                                                            <button type="button" class="btn btn-danger btn-tiny" ng-click="RazonEliminacionCuotasVendedor(registro)" data-toggle="modal">Inactivar</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-show="seccion == 2" class="col md-12 text-left elementos" ng-show="redenciones_empleado.length > 0">
                            <br />
                            <h2 class="text-center">Ranking Actual <small>Seleccione la temporada que desea
                                    ver</small>
                            </h2>
                            <div ng-repeat="temporada in temporadas_ranking track by $index" ng-init="id_temporada_ranking_activa = 0;">
                                <button class="btn btn-primary btn-block text-left" ng-click="id_temporada_ranking_activa = id_temporada_ranking_activa == 0 ? temporada.id : 0">{{temporada.nombre}}</button>
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

                        <div ng-show="seccion == 3" class="col md-12 text-left elementos" ng-show="redenciones.length >= 0">
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
                                    <tr ng-repeat="ganador in temporadas_ranking_diamante.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada.cupos_diamante && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center " ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-ms" ng-show="redenciones_temporada < cupos_temporada.cupos_diamante">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <button class="btn btn-danger btn-sm" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_diamante_0.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada_4.cupos_diamante && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center " ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-ms" ng-show="redenciones_temporada < cupos_temporada_4.cupos_diamante">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_diamante && datos_usuario.es_administrador != 4">
                                            <button ng-show="redenciones_temporada < cupos_temporada_4.cupos_diamante" class="btn btn-danger btn-sm" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_oro.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada.cupos_oro && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center " ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_oro && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-ms" ng-show="redenciones_temporada < cupos_temporada.cupos_oro">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_oro && datos_usuario.es_administrador != 4">
                                            <button class="btn btn-danger btn-sm" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_oro_0.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada_4.cupos_oro && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center " ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_oro && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-ms" ng-show="redenciones_temporada < cupos_temporada_4.cupos_oro">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_oro && datos_usuario.es_administrador != 4">
                                            <button ng-show="redenciones_temporada < cupos_temporada_4.cupos_oro" class="btn btn-danger btn-sm" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_plata.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada.cupos_plata && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center " ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_plata && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-ms" ng-show="redenciones_temporada < cupos_temporada.cupos_plata">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada.cupos_plata && datos_usuario.es_administrador != 4">
                                            <button class="btn btn-danger btn-sm" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="ganador in temporadas_ranking_plata_0.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < cupos_temporada_4.cupos_plata && ganador.puntos > 0">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center " ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_plata && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=0&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-ms" ng-show="redenciones_temporada < cupos_temporada_4.cupos_plata">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center " ng-show="ganador.entregas_solicitadas == 1">
                                            Premio Solicitado
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < cupos_temporada_4.cupos_plata && datos_usuario.es_administrador != 4">
                                            <button ng-show="redenciones_temporada < cupos_temporada_4.cupos_plata" class="btn btn-danger btn-sm" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad > 0" colspan="2">
                                            {{ganador.comentario}}
                                        </td>
                                    </tr>
                                    <tr class="hide" ng-repeat="ganador in temporada_1.ranking track by $index" ng-show="redencion.puede_redimir != 0 && $index < almacen.encuestas_periodo">
                                        <td class="text-uppercase">{{ganador.vendedor}}</td>
                                        <td class="text-uppercase">{{ganador.categoria}}</td>
                                        <td class="text-center text-uppercase">{{ganador.bimestre}}</td>
                                        <td class="text-center">{{ganador.puntos| number}}</td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < almacen.encuestas_periodo && datos_usuario.es_administrador != 4">
                                            <a ng-href="redenciones.php?id_afiliado={{ganador.id_vendedor}}&id_almacen={{almacen.id_drogueria}}&id_temporada={{ganador.id_temporada}}&catalogo_perfecto=1&id_categoria={{ganador.id_categoria}}" class="btn btn-primary btn-sm" ng-show="redenciones_temporada < almacen.encuestas_periodo">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </td>
                                        <td class="text-center" ng-show="ganador.novedad == 0 && ganador.entregas_solicitadas == 0 && $index < almacen.encuestas_periodo && datos_usuario.es_administrador != 4">

                                            <button ng-show="redenciones_temporada < almacen.encuestas_periodo" class="btn btn-danger btn-tiny" ng-click="IniciarDenegacionRedencion(ganador.id_ecu)" data-toggle="modal" data-target="#modalDenegarRedencion">
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

                        <div ng-show="seccion == 4" class="col md-12 text-left elementos" ng-show="redenciones.length > 0">
                            <br />
                            <div ng-show="datos_usuario.es_administrador != 4">
                                <h2 class="text-center">Entregas</h2>
                                <h3>Legalizar por temporada</h3>
                                <br />
                                <div class="row">
                                    <div class="col-sm-12 col-md-12" ng-repeat="temp in temporadas_activas track by $index">
                                        <button ng-click="SeleccionarTemporadaEntregas(temp.id)" class="btn btn-primary btn-block">
                                            <i class="fa fa-eye"></i>
                                            Ver {{temp.nombre}}
                                        </button>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <br>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoTutorial">
                                            Video Tutorial
                                        </button>
                                    </div>
                                </div>
                                <br />
                                <div class="button-group" role="group" aria-label="">
                                    <a class="btn btn-danger" ng-repeat="temporada in temporadas_por_legalizar track by $index" ng-href="legalizacion_masiva.php?id_almacen={{almacen.id_drogueria}}&id_temporada={{temporada.id}}" ng-show="redenciones_procesadas > 0 && (temporada.id == temporada_seleccionada)">
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
                                    <tr ng-repeat="redencion in redenciones track by $index" ng-show="redencion.id_temporada == temporada_seleccionada">
                                        <td class="text-left">{{redencion.id_redencion}}</td>
                                        <td class="text-left">{{redencion.empleado}}</td>
                                        <td class="text-left">{{redencion.clasificacion}}</td>
                                        <td class="text-left">{{redencion.categoria}}</td>
                                        <td class="text-left">{{redencion.premio}}</td>
                                        <td class="text-left">{{redencion.fecha_redencion}}</td>
                                        <td class="text-left">{{redencion.estado}}</td>
                                        <td class="text-left">{{redencion.temporada}}</td>
                                        <td class="text-left" ng-show="redencion.id_operacion == 5">
                                            <a class="btn btn-primary" target="_blank" ng-href="documento_legalizacion.php?folio={{redencion.id_redencion}}">
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
                                                <th style="border-bottom: 3px solid orange; text-align: center;" colspan="3">{{temporada.periodo_1}}</th>
                                                <th style="border-bottom: 3px solid yellow; text-align: center;" colspan="3">{{temporada.periodo_2}}</th>
                                                <th style="border-bottom: 3px solid greenyellow; text-align: center;" colspan="3">General</th>
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
                                            <tr ng-repeat="cuota in empleados_cuotas  track by $index" ng-show="cuota.id_temporada == temporada.id_temporada">
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
                                                    <a ng-show="cuota.ranking <= almacen.supervisores && cuota.cumplimiento_bimestre >= 95 && cuota.redenciones_temporada == 0 && cuota.puede_redimir == 1 && cuota.id_temporada == 4" ng-href="redenciones.php?id_afiliado={{cuota.id_supervisor}}&id_almacen={{cuota.id_almacen}}&id_temporada={{cuota.id_temporada}}&catalogo_perfecto=0&id_categoria=1" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <p ng-show="cuota.cumplimiento_bimestre >= 95 && cuota.redenciones_temporada == 1 && cuota.puede_redimir == 1">
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
                                        <input class="form-control" type='text' ng-model="almacen.drogueria" ng-disabled="true" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5">
                                    <div class="form-group">
                                        <label for="nombre">Visitador:</label>
                                        <input class="form-control" type='text' ng-model="almacen.visitador" ng-disabled="true" />
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
                                    <button class="btn btn-primary" ng-click="ObtenerCategoriasLlamadas()" data-open="modalLlamadas">
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
                                                    <iframe ng-src="{{habeas_data[0].firma}}" frameborder="0" width="100%" height="550" marginheight="0" marginwidth="0" id="pdf">
                                                        Ver Habeas Data
                                                    </iframe>
                                                </div>
                                                <div ng-show="habeas_data.length == 0 || (habeas_data.length == 1 && habeas_data[0].tipo_acta == 0)">
                                                    <iframe src="terminoscondiciones_programa_sociosamigos_canal_tat_2022.pdf" frameborder="0" width="100%" height="550" marginheight="0" marginwidth="0" id="pdf">
                                                        Ver Habeas Data
                                                    </iframe>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-offset-1 col-md-10" ng-show="habeas_data.length == 0">
                                                <div class="col-sm-12 col-md-12">
                                                    <button class="btn btn-primary btn-block" ng-click="tipo = 1">Aceptar Términos y Condiciones con
                                                        Firma</button>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <br>
                                                    <button class="btn btn-primary btn-block" ng-click="tipo = 2">Aceptar Términos y Condiciones con
                                                        Documento</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" ng-show="habeas_data.length == 0 && tipo == 1" ng-init="boton = 1">
                                            <div class="col-sm-12 col-md-offset-1 col-md-4">
                                                <p>
                                                    Cliente
                                                    <input type="text" class="form-control" ng-model="almacen.drogueria" ng-disabled="true" />
                                                </p>
                                                <p>
                                                    <b>Apreciado Cliente:</b>
                                                    <input class="form-control" type='text' ng-model="legalizacion.nombre" />
                                                </p>
                                                <p>
                                                    <b>Documento Cliente:</b>
                                                    <input class="form-control" type='number' ng-model="legalizacion.documento" />
                                                </p>
                                                <p>
                                                    Fecha
                                                    <input class="form-control" type='text' ng-disabled="true" ng-model="legalizacion.fecha" />
                                                </p>
                                            </div>
                                            <div class="col-sm-12 col-md-offset-1 col-md-10">
                                                <div id="img_perfil">
                                                    <img id="sig-image-confirmed" src="" alt="Firma no registrada!" ng-show="legalizacion.firma != ''" />
                                                </div>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#singModal" ng-click="AbrirModalFirmaVendedor()">Firma
                                                    Cliente</button>
                                            </div>
                                            <div class="col-sm-12 col-md-offset-1 col-md-10">
                                                <br />
                                                <br />
                                                <button class="btn btn-success" id="sig-clearBtn" ng-show="boton == 1" ng-click="LegalizarRedencion()">Guardar</button>
                                            </div>
                                        </div>
                                        <div class="row" ng-show="habeas_data.length == 0 && tipo == 2 " ng-init="boton = 1">
                                            <div class="col-sm-12 col-md-offset-1 col-md-4">
                                                <h3>Selecione el documento.</h3>
                                                <form method="POST" action="clases/cargararchivos.php" enctype="multipart/form-data">
                                                    <input type="file" name="archivo" accept="application/pdf" required>
                                                    <input hidden type="text" name="id_almacen" ng-model="almacen.id_drogueria" value="{{almacen.id_drogueria}}">
                                                    <button type="submit" class="btn btn-primary btn-block" value="cargar Archivos"><i class="fa fa-upload"></i> Subir
                                                        Archivo</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-offset-1 col-md-4" ng-show="habeas_data.length == 1">
                                                <a ng-if="habeas_data[0].tipo_acta == 0" class="btn btn-primary btn-block" href="https://sociosyamigos.com.co/tat2022/ver_actas.php?id_almacen={{habeas_data[0].id_almacen}}">
                                                    Descargar Documento</a>

                                                <a ng-if="habeas_data[0].tipo_acta == 1" class="btn btn-primary btn-block" target="_blank" href="https://sociosyamigos.com.co/tat2022/{{habeas_data[0].firma}}">
                                                    Descargar Documento</a>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row" ng-show="false">
                                            <div class="col-md-12">
                                                <textarea id="sig-dataUrl" class="form-control" rows="5" ng-value="legalizacion.firma_vendedor">Data URL for your signature will go here!</textarea>
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
                                <div ng-repeat="temporada in cuotas_vendedores track by $index" ng-show="temporada.id_temporada == 5">
                                    <table class="table">
                                        <thead>
                                            <tr ng-hide="datos_usuario.es_administrador == 2">
                                                <th>{{temporada.temporada}}</th>
                                                <th style="border-bottom: 3px solid orange; text-align: center;" colspan="4">{{temporada.periodo_1}}</th>
                                                <th style="border-bottom: 3px solid yellow; text-align: center;" colspan="4">{{temporada.periodo_2}}</th>
                                                <th style="border-bottom: 3px solid greenyellow; text-align: center;" colspan="4">General</th>
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
                                            <tr ng-repeat="cuota in empleados_cuotas track by $index" ng-show="cuota.id_temporada == 5">
                                                <td>{{cuota.supervisor}}</td>
                                                <td>
                                                    <a href="simulador_ventas.php?id_usuario={{cuota.id_afiliado}}&id_temporada={{cuota.id_temporada}}" class="btn btn-primary">
                                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.cuota_1| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.venta_1| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    {{cuota.cumplimiento_1| number:1}}%
                                                </td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.cuota_2| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.venta_2| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    {{cuota.cumplimiento_2| number:1}}%
                                                </td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.cuota_bimestre| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    ${{cuota.venta_bimestre| number}}</td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    {{cuota.cumplimiento_bimestre| number}}%
                                                </td>
                                                <td>
                                                    <a ng-show="cuota.cumplimiento_bimestre >= 100 && cuota.redenciones_temporada == 0 && cuota.puede_redimir == 1 && datos_usuario.es_administrador != 4 && cuota.id_almacen != 56" ng-href="redenciones.php?id_afiliado={{cuota.id_supervisor}}&id_almacen={{cuota.id_almacen}}&id_temporada={{cuota.id_temporada}}&catalogo_perfecto=0" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </td>
                                                <td ng-hide="datos_usuario.es_administrador == 2">
                                                    <p ng-show="cuota.cumplimiento_bimestre >= 100 && cuota.redenciones_temporada == 1 && cuota.puede_redimir == 1 && datos_usuario.es_administrador != 4 && cuota.id_almacen != 56">
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


                                    </div>
                                </div>
                                <!-- /.box-body -->

                            </div>
                        </div>

                        <div ng-show="seccion == 14" class="col md-12 text-center elementos">
                            <br />
                            <h2 class="text-center">Crear Vendedor</h2>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <label>Seleccione Supervisor</label>
                                    <select class="form-control" ng-model="nuevo_afiliado.id_supervisor">
                                        <option ng-repeat="supervisores in supervisores_afiliados" value="{{supervisores.id}}">{{supervisores.nombre}}</option>
                                        <option value="0">Sin Supervisor</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label>Ingrese Cedula</label>
                                    <input class="form-control" type="text" ng-model="nuevo_afiliado.cedula" />
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label>Ingrese Nombre</label>
                                    <input class="form-control" type="text" ng-model="nuevo_afiliado.nombre" />
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label>Ingrese Telefono</label>
                                    <input class="form-control" type="number" ng-model="nuevo_afiliado.telefono" />
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label>Seleccione Rol</label>
                                    <select class="form-control" ng-model="nuevo_afiliado.rol">
                                        <option value="6">Supervisor</option>
                                        <option value="4">Vendedor</option>
                                    </select>
                                    <label>Seleccione Periodo</label>
                                    <select class="form-control" ng-model="nuevo_afiliado.id_periodo">
                                        <option value="20">Agosto</option>
                                        <option value="21">Septiembre</option>
                                        <option value="22">Octubre</option>
                                        <option value="23">Noviembre</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="col-sm-12 col-md-12">
                                        <label>Ingrese Cuota</label>
                                        <input class="form-control" type="number" ng-model="nuevo_afiliado.cuota" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5 offset-md-1" ng-init="reemplazar_vendedor = 0">
                                    <br />
                                    <br />
                                    <h5>¿Reemplazar Vendedor?</h5>
                                    <input type="radio" id="reemplazar_vendedor_si" name="reemplazar" value="1" ng-model="reemplazar_vendedor" ng-change="ReemplazarVendedor()">
                                    <label for="si">Si</label>
                                    <input type="radio" id="reemplazar_vendedor_no" name="reemplazar" value="0" ng-model="reemplazar_vendedor" ng-change="ReemplazarVendedor()">
                                    <label for="no">No</label>
                                    <div ng-if="reemplazar_vendedor == 1">
                                        <label for="vendedores_reemplazo">Seleccionar Vendedor</label>
                                        <select class="form-control" name="vendedores_reemplazo" ng-model="reemplazo_seleccionado" ng-change="ValidarReemplazo(reemplazo_seleccionado)" id="vendedores_reemplazo">
                                            <option value="0">SELECCIONAR</option>
                                            <option ng-repeat="l in listado_vendedores_reemplazo" value="{{l.id_vendedor}}">{{l.vendedor}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5" ng-init="btn_crear_usuario = 0">
                                    <br />
                                    <br />
                                    <button class="btn btn-primary btn-block" ng-if="btn_crear_usuario == 0" ng-click="CrearNuevoUsuario();">
                                        Crear Vendedor
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade " id="detalleEstadoCuenta" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 14">
                                        Febrero 2022
                                    </button>
                                    <br>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 15">
                                        Marzo 2022
                                    </button>
                                    <br>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 16">
                                        Abril 2022
                                    </button>
                                    <br>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 17">
                                        Mayo 2022
                                    </button>
                                    <br>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 18">
                                        Junio 2022
                                    </button>
                                    <br>
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary btn-block" ng-click="temporada_estado_cuenta = 19">
                                        Julio 2022
                                    </button>
                                    <br>
                                </div>
                            </div>
                            <div class=" text-center caja_bimestres_estado_cuenta" ng-repeat="puntos in puntos_empleado_detallado" ng-show="puntos.id_periodo == temporada_estado_cuenta">
                                <br>
                                <h3>{{puntos.categoria}}</h3>
                                <table class="table table-bordered table-hover text-center tabla_supevisor">
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
            <div class="modal fade" id="singModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <button class="btn btn-primary" id="sig-submitBtn" ng-click="GuardarFirmaVendedor()" ng-show="legalizacion.firma == ''" data-dismiss="modal">
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

            <!-- Modal -->
            <div class="modal fade" id="confirmacion_edicion_cuotas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Actualizar Cuotas</h5>
                            <br>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form ng-submit="ConfirmarActualizacionCuotas()">
                                <div class="row">
                                    <div class="col-sm-12 col-md-5 text-center">
                                        <h3>Cuota Actual</h3>
                                        <br>
                                        <h4>${{RegistroSeleccionado.cuota_vendedor | number}}</h4>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <label for="cuota_nueva">Nueva Cuota</label>
                                        <input type="text" required class="form-control" name="" onkeyup="format(this)" onchange="format(this)" id="cuota_nueva" ng-model="nueva_cuota">
                                        <div class="col-sm-12 col-md-4 offset-md-4 ">
                                            <br>
                                            <button class="btn btn-primary btn-block" type="submit">Actualizar Cuota</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-sm-12 col-md-5 offset-md-1" ng-init="actualizar_vendedor = 0">
                                    <br />
                                    <br />
                                    <h5>¿Reemplazar Supervisor?</h5>
                                    <input type="radio" id="actualizar_vendedor_si" name="reemplazar" value="1" ng-model="actualizar_vendedor">
                                    <label for="si">Si</label>
                                    <input type="radio" id="actualizar_vendedor_no" name="reemplazar" value="0" ng-model="actualizar_vendedor">
                                    <label for="no">No</label>
                                    <div ng-if="actualizar_vendedor == 1">
                                        <label for="actualizar_supervisor">Seleccionar Supervisor</label>
                                        <select class="form-control" name="actualizar_supervisor" ng-model="nuevo_supervisor" id="supervisor_reemplazo">
                                            <option value="0">SELECCIONAR</option>
                                            <option ng-repeat="l in supervisores_afiliados" value="{{l.id}}">{{l.nombre}}</option>
                                        </select>
                                        <div class="col-sm-12  ">
                                            <br>
                                            <button class="btn btn-primary btn-block" type="button" ng-click="ActualizarSupervisores(nuevo_supervisor)">Actualizar Supervisor</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->

            <!-- Modal -->
            <div class="modal fade" id="cuotasMasivas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Detalle Actualización Cuotas</h5>
                            <br>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    Cuota distribuidora actual
                                    <br>
                                    <span id="cuota_total_almacen">${{cuota_total | number}}</span>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    Cuota distribuidora con cambios
                                    <br>
                                    <span id="cuota_total_actualizada"></span>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <button class="btn btn-primary" ng-click="ActualizarDatosCargueMasivo();">Actualizar</button>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>IDvendedor</th>
                                        <th>Vendedor</th>
                                        <th>Cuota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="d in datos_cargados">
                                        <td>{{d.IDvendedor}}</td>
                                        <td>{{d.Vendedor}}</td>
                                        <td>${{d.Cuota | number}}</td>
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
                        <div class="row" ng-click="periodo_seleccionado = periodo_seleccionado == -1 ? periodo.id_periodo : -1">
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
                    <h3>Inactivar Vendedor </h3>
                    <br>
                    <br>
                    Indique la razón por la cual se inactiva el vendedor
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <select class="form-control" ng-model="razon_denegacion">
                        <option value="1">Retiro</option>
                        <option value="2">Licencia de maternidad</option>
                        <option value="3">Incapacidad</option>
                        <option value="4">Informacion incorrecta</option>
                        <option value="5">Otro</option>
                    </select>
                    <div class="row">
                        <div class="col-md-8 text-right">
                            <br>
                            <button class="btn btn-primary" ng-click="EliminarCuotasVendedor()">Confirmar
                                Eliminación</button>
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
                            <input type="text" class="form-control" id="cuota_1" onkeyup="format(this)" onchange="format(this)" placeholder="{{ActualizarCuotaCuota1}}" />
                        </div>
                        <div class="col-sm-12 col-md-6 form-group">
                            <label>
                                Actualizar Cuota Mes 2.
                            </label>
                            <input type="text" class="form-control" id="cuota_2" onkeyup="format(this)" onchange="format(this)" placeholder="{{ActualizarCuotaCuota2}}" />
                        </div>
                        <div class="col-sm-12  form-group" ng-init="botonactualizarcuota = true">
                            <br />
                            <br />
                            <br />
                            <button class="btn btn-primary" ng-show="botonactualizarcuota" ng-click="ActualizarCuotasCargadas()">Actualizar Cuotas</button>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="inactivar_usuarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inactivar Vendedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" ng-model="filtros.nombre" placeholder="Filtrar Nombre Vendedor" ng-change="SeleccionarListadoVendedoresInactivar()" />
                    <br />
                    <table class="table">
                        <th>Inactivar</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoria</th>
                        <tbody>
                            <tr ng-repeat="inactivar in inactivar_empleado">
                                <td><button class="btn btn-danger btn-tiny" ng-click="InactivarVendedor(inactivar.id)"><i class="fa fa-trash"></i></button>
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
                    <object width="470" height="415" data="https://www.youtube.com/embed/NjjaY5f-7HQ?rel=0&amp;showinfo=0">
                    </object>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    <script src="js/almacenes.js?ver=61" type="text/javascript"></script>
    <script src="js/signature.js?reload=true"></script>
    <!-- DevExtreme library -->
    <script type="text/javascript" src="dev_x/lib/js/jszip.js"></script>
    <script type="text/javascript" src="dev_x/lib/js/dx.all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs@3.4.0/dist/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.6/xls.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/danialfarid-angular-file-upload/12.2.13/ng-file-upload.min.js"></script>
</body>

</html>