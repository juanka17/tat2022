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
    <script src="js/afiliados.js?reload=2" type="text/javascript"></script>
    <script type="text/javascript">
    var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    var mis_datos = true;
    </script>
</head>

<body ng-app="afiliadosApp" ng-controller="afiliadosController" class="wrapper layout-top-nav"
    style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
        <div class="container-fluid">
            <section class="content-header">
                <h1>
                    Aceptar TYC
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Aceptar TYC</a></li>
                </ol>
            </section>
            <section class="content">
                <div class="col-sm-12 col-md-12 col-lg-10 offset-lg-1 text-center">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Cedula:</label>
                                <input class="form-control" type='text' ng-model="seleccionado.CEDULA"
                                    ng-disabled="true" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Nombre:</label>
                                <input class="form-control" type='text' ng-model="seleccionado.NOMBRE" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Email:</label>
                                <input class="form-control" type='text' ng-model="seleccionado.EMAIL"
                                    id="email" data-toggle="tooltip" data-placement="top" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Departamento:</label>
                                <select class="form-control" ng-model="seleccionado.ID_DEPARTAMENTO"
                                    ng-options="c.ID as c.NOMBRE for c in departamento"
                                    ng-change="CargaCiudadesDepartamento()">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Ciudad:</label>
                                <select class="form-control" ng-model="seleccionado.ID_CIUDAD"
                                    ng-options="c.ID as c.NOMBRE for c in ciudad">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Celular:</label>
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
                            <div class="col-sm-12 text-justify">
                                <h4>Apreciado usuario:</h4>
                                <p>1. Una vez se haya generado su registro tendrá acceso a una cuenta individual en la
                                    cual podrá consultar el estado de los clientes (distribuidores) a su cargo. El
                                    registro implica el cambio de contraseña para acceder a la plataforma, aceptación
                                    para recibir información de los clientes a su cargo a través de mensajes de texto,
                                    llamada telefónica, página web, e-mail y comunicación escrita.</p>
                                <p>2. La plataforma Socios & Amigos garantiza que sus datos serán utilizados para
                                    efectos del presente programa y que tendrá derecho a consultarlos, actualizarlos de
                                    manera gratuita y conforme al decreto 1377 de 2013.</p>
                                <h4>Confidencialidad:</h4>
                                <p>3. Formas Estrategicas SAS, operador de la plataforma de reconocimientos de Socios &
                                    Amigos, declara que entiende la importancia respeto, al acatamiento y salvaguarda de
                                    la información personal de sus respectivos titulares (usuarios) y que tiene el
                                    conocimiento y ha adoptado las medidas necesarias para dar cumplimiento a la
                                    normatividad vigente en materia de protección de datos personales, artículos 15 y 20
                                    de la Constitución Política de Colombia, la Ley 1581 de 2012 “Ley de Protección de
                                    Datos Personales" (Habeas Data), el Decreto 1377 del 27 de junio de 2013 del
                                    Ministerio de Comercio, Industria y Turismo, así como los capítulos 25 y 26 del
                                    Decreto 1074 de 2015 y demás disposiciones concordantes o complementarias, que las
                                    modifiquen, adicionen, reglamenten o sustituyan.</p>
                                <p>4. Formas Estrategicas SAS., entiende y acepta que se encuentra autorizada para hacer
                                    uso de las bases de datos personales que le sean entregadas en cumplimiento de las
                                    autorizaciones que los usuarios deseen otorgar y para las finalidades que así se
                                    manifiesten.</p>
                                <p>
                            </div>
                            <div class="col-sm-2"></div>

                            <div class="col-md-offset-1 col-md-10 text-center text-info lead" style="color: #004f8d;">
                                <br />
                                <input type="checkbox" ng-model="acepto_terminos_y_condiciones" /> Haz click acá para
                                actualizar tu clave y aceptar los terminos de uso de socios y amigos.
                                <br />
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="interes">Clave Actual</label>
                                    <input type='password' class="form-control" placeholder='Clave actual'
                                        ng-model='claveActual' />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="interes">Clave Nueva</label>
                                    <input type='password' class="form-control" placeholder='Clave nueva'
                                        ng-model='claveNueva' />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="interes">Confirmar Clave Nueva</label>
                                    <input type='password' class="form-control" placeholder='Confirmar clave nueva'
                                        ng-model='confirmaClaveNueva' />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button class='btn btn-primary' ng-click='AceptarTerminos()'
                                    ng-disabled="!acepto_terminos_y_condiciones">
                                    Acepto terminos del programa
                                </button>
                                <br /><br />
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
        </div>
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
            <?php include 'componentes/coponentes_js.php'; ?>
        </footer>
    </div>
</body>

</html>