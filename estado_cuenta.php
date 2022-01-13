<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?> 
        <script src="js/estado_cuenta.js?ver=54" type="text/javascript"></script> 
        <script src="js/app.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
            var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
            var id_usuario = 0;
            if (typeof getParameterByName("id_usuario") !== 'undefined' && getParameterByName("id_usuario") != "")
            {
                id_usuario = getParameterByName("id_usuario");
            } else
            {
                alert("No hay usuario seleccionado.");
            }

        </script>  
        <script type="text/javascript">
            setTimeout(function () {
                google.charts.load('current', {'packages': ['bar']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    console.log(grafica);
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Temporada');
                    data.addColumn('number', 'Cuotas');
                    data.addColumn('number', 'Ventas');
                    data.addColumn('number', 'Cumplimiento');
                    var array1 = grafica;
                    var index = 0;
                    array1.forEach(function () {
                        console.log(index);
                        data.addRows([
                            [grafica[index].nombre, grafica[index].cuota, grafica[index].venta, grafica[index].cumplimiento]
                        ]);
                        index++;
                    });
                    var options = {
                        chart: {
                            title: 'Grafica de ventas y cuotas por trimestre'
                        }
                    };
                    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                }
            }, 1000);
        </script> 
    </head>

    <body ng-app="estadoCuentaApp" ng-controller="estadoCuentaController" class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php include 'componentes/controles_superiores.php'; ?>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $_SESSION["usuario"]["nombre"]; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?php include 'componentes/menu.php'; ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Estado de Cuenta
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                        <li><a onclick="javascript:history.back();"><i class="fa fa-dashboard"></i> Usuario</a></li>
                        <li class="active">Estado cuent</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">                         
                        <div class="col-sm-12 col-md-offset-11 col-md-1">                        
                            <button class="btn btn-danger" onclick="javascript:history.back();">Volver</button>                            
                        </div>                        
                        <div class="cell small-12">                        
                            <table class="table">                            
                                <thead>                                
                                    <tr>                                    
                                        <th>Nombre</th>                                        
                                        <th>Clasificación</th>                                        
                                        <th>Almacén</th>
                                        <th>Ventas generales</th>                                          
                                        <th>Puntos</th>                                        
                                    </tr>                                    
                                </thead>                                
                                <tbody>                                
                                    <tr>                                    
                                        <td>{{datos_usuario.nombre}}</td>                                        
                                        <td>{{datos_usuario.clasificacion}}</td>                                        
                                        <td>{{datos_usuario.almacen}}</td>                                        
                                        <td ng-show="datos_usuario.id_almacen != 34">                                        
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_ventas" ng-click="VerDetalles(0, 3)">Ver ventas generales</button>                                            
                                        </td>                                         

                                        <td>{{datos_usuario.saldo_actual| number}}</td>                                        
                                    </tr>                                    
                                </tbody>                                
                            </table>                            
                        </div>                        
                        <div class="cell small-12"> 
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div id="columnchart_material"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <h3 class="text-center">Transacciones</h3>
                                    <div ng-repeat="periodo in estado_cuenta track by $index" class="col-ms-12">
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo{{$index}}">{{periodo.periodo}}</button>
                                        <div id="demo{{$index}}" class="collapse">
                                            <table class="table">                                        
                                                <thead>                                            
                                                    <tr>                                                
                                                        <th>Concepto</th>                                                    
                                                        <th>Descripcion</th>                                                    
                                                        <th>Detalles</th>                                                                
                                                        <th>Puntos</th>                                                    
                                                    </tr>                                                
                                                </thead>                                            
                                                <tbody>                       
                                                    <tr ng-repeat="registro in periodo.registros track by $index">                                                
                                                        <td>{{registro.concepto}}</td>                                                    
                                                        <td>{{registro.descripcion}}</td>                                                    
                                                        <td>                                                    
                                                            <a ng-show="registro.id_concepto == 3 || registro.id_concepto == 4" data-toggle="modal" data-target="#modal_ventas" href="#" ng-click="VerDetalles(registro.id_temporada)"><i class="fa fa-eye"></i> Ver detalles</a>                                                        
                                                        </td>                                                                         
                                                        <td>{{registro.puntos| number}}</td>                                                    
                                                    </tr>                                                
                                                </tbody>                                            
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>                        
                    </div> 
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
            <!-- Control Sidebar -->

        </div>

        <div id="modal_ventas" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">{{titulo_detalle}}</h2>
                    </div>
                    <div class="modal-body">
                        <div class="col-sm-12">
                            <input class="form-control" type='text' placeholder="Buscar por Temporada" ng-model="filtros_ventas.temporada" ng-change="VerDetalles(0, 3)" />
                        </div>
                        <table class="table" id="tdetalle">                
                            <thead>                    
                                <tr>                        
                                    <th>Temporada</th>                              
                                    <th>Periodo</th>                              
                                    <th>Cuota</th>                            
                                    <th>Venta</th>                            
                                </tr>                        
                            </thead>                    
                            <tbody>                    
                                <tr ng-repeat="registro in ventas_visibles track by $index">                        
                                    <td>{{registro.temporada}}</td>                                                    
                                    <td>{{registro.periodo}}</td>                                                    
                                    <td>${{registro.cuota| number}}</td>                            
                                    <td>${{registro.venta| number}}</td>                            
                                </tr>                        
                            </tbody>                    
                        </table>      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

    </body>
</html>