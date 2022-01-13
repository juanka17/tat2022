<?php
session_start();

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: ../index.php");
    die();
}

$url = explode("/", $_SERVER['PHP_SELF']);
if ($url[(count($url) - 1)] != "index.php") {
    if (count($_SESSION) == 0) {
        session_destroy();
        header("Location: ../index.php");
        die();
    } else {
        if ($_SESSION["usuario"]["acepto_terminos"] == 0 && $url[(count($url) - 1)] != "mis_datos.php") {
            header("Location: mis_datos.php");
        }
    }
} else {
    $_SESSION["usuario"] = null;
    $_SESSION["afiliadoSeleccionado"] = null;
    session_destroy();
}
?>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <?php include 'componentes/componentes_basicos.php'; ?> 
        <script src="js/afiliados.js?reload=1" type="text/javascript"></script>
        <script type="text/javascript">
            var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
            var mis_datos = true;
        </script>
    </head>

    <body ng-app="afiliadosApp" ng-controller="afiliadosController" class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini" style="color: black"><b>TAT</span>
                    <!-- logo for regular state and mobile devices -->
                    <div><img  id="fixed_logo" src="images/logos/tat.jpg" /></div>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-right">
                                            <a href="?logout" class="btn btn-default btn-flat">Salir</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
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
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->

                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">

                </section>

                <!-- Main content -->
                <section class="content">
                    <div ng-show="buscando"></div>
                    <div ng-show="editando">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h3 ng-show="datos_usuario.acepto_terminos != 0">Mis datos</h3>
                                <h3 ng-show="datos_usuario.acepto_terminos == 0">
                                    Registre sus datos para acceder:
                                </h3>
                                <br/>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-offset-1 col-md-3">
                                <div class="form-group">
                                    <label>Codigo:</label>
                                    <input class="form-control" type='text' ng-model="seleccionado.CEDULA" ng-disabled="true" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <input class="form-control" type='text' ng-model="seleccionado.NOMBRE" />
                                </div>
                            </div>                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Email:</label>
                                    <input class="form-control" type='text' ng-disabled="true" ng-model="seleccionado.EMAIL" id="email"
                                           data-toggle="tooltip" data-placement="top"   />
                                </div>
                            </div>
                            <div class="col-md-offset-1 col-md-3">
                                <div class="form-group">
                                    <label >Departamento:</label>
                                    <select class="form-control" ng-model="seleccionado.ID_DEPARTAMENTO" 
                                            ng-options="c.ID as c.NOMBRE for c in departamento" ng-change="CargaCiudadesDepartamento()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Ciudad:</label>
                                    <select class="form-control" ng-model="seleccionado.ID_CIUDAD" 
                                            ng-options="c.ID as c.NOMBRE for c in ciudad">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" ng-hide="datos_usuario.es_administrador == 2">
                                <div class="form-group" >
                                    <label >Celular:</label>
                                    <input class="form-control" type='text' ng-model="seleccionado.CELULAR" />
                                </div>
                            </div>

                            <div class="col-md-12 text-center" ng-show="datos_usuario.acepto_terminos != 0">
                                <a class="btn btn-success" href="bienvenida.php"> Continuar</a>
                            </div>
                            <div ng-show="datos_usuario.acepto_terminos == 0">                                
                                <div class="col-sm-12 text-center">
                                    Terminos de uso
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8 text-justify">
                                    <h4>Apreciado usuario:</h4>
                                    <p>1. Una vez se haya generado su registro tendrá acceso a una cuenta individual en la cual podrá consultar el estado de los clientes (distribuidores) a su cargo. El registro implica el cambio de contraseña para acceder a la plataforma, aceptación para recibir información de los clientes a su cargo a través de mensajes de texto, llamada telefónica, página web, e-mail y comunicación escrita.</p>
                                    <p>2. La plataforma Socios & Amigos garantiza que sus datos serán utilizados para efectos del presente programa y que tendrá derecho a consultarlos, actualizarlos de manera gratuita y conforme al decreto 1377 de 2013.</p>
                                    <h4>Confidencialidad:</h4>
                                    <p>3. Formas Estrategicas SAS, operador de la plataforma de reconocimientos de Socios & Amigos, declara que entiende la importancia respeto, al acatamiento y salvaguarda de la información personal de sus respectivos titulares (usuarios) y que tiene el conocimiento y ha adoptado las medidas necesarias para dar cumplimiento a la normatividad vigente en materia de protección de datos personales, artículos 15 y 20 de la Constitución Política de Colombia, la Ley 1581 de 2012 “Ley de Protección de Datos Personales" (Habeas Data), el Decreto 1377 del 27 de junio de 2013 del Ministerio de Comercio, Industria y Turismo, así como los capítulos 25 y 26 del Decreto 1074 de 2015 y demás disposiciones concordantes o complementarias, que las modifiquen, adicionen, reglamenten o sustituyan.</p>
                                    <p>4. Formas Estrategicas SAS., entiende y acepta que se encuentra autorizada para hacer uso de las bases de datos personales que le sean entregadas en cumplimiento de las autorizaciones que los usuarios deseen otorgar y para las finalidades que así se manifiesten.</p>
                                    <p>
                                </div>                                    
                                <div class="col-sm-2" ></div>                                   

                                <div class="col-md-offset-1 col-md-10 text-center text-info lead" style="color: #004f8d;">
                                    <br/>
                                    <input type="checkbox" ng-model="acepto_terminos_y_condiciones" /> Haz click acá para actualizar tu clave y aceptar los terminos de uso de socios y amigos.
                                    <br/>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="interes">Clave Actual</label>
                                        <input type='password' class="form-control" placeholder='Clave actual' ng-model='claveActual'/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="interes">Clave Nueva</label>
                                        <input type='password' class="form-control" placeholder='Clave nueva' ng-model='claveNueva' />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="interes">Confirmar Clave Nueva</label>
                                        <input type='password' class="form-control" placeholder='Confirmar clave nueva' ng-model='confirmaClaveNueva' />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button class='btn btn-primary' ng-click='AceptarTerminos()' ng-disabled="!acepto_terminos_y_condiciones">
                                        Acepto terminos del programa
                                    </button>
                                    <br/><br/>
                                </div>

                            </div>
                            <div class="col-md-offset-2 col-md-8 bg-success text-center">
                                {{operacionCorrectaMensaje}}
                            </div>
                            <div class="col-md-offset-2 col-md-8 bg-danger text-center">
                                {{mensajeError}}
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
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane" id="control-sidebar-home-tab">

                    </div>
                </div>
            </aside>
        </div>
    </body>
</html>