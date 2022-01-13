<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

    <head>
        <?php include 'componentes/componentes_basicos.php'; ?>
        <script src="js/legalizacion.js?ver=18" type="text/javascript"></script>
        <link rel="stylesheet" href="./css/legalizar.css?ver=5">
        <script type="text/javascript">
            var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
            var id_redencion = 0;
            var id_almacen = 0;
            var legalizacion_individual = true;
            var id_temporada = 0;

            if (typeof getParameterByName("id_redencion") !== 'undefined' && getParameterByName("id_redencion") != "") {
                var seleccionar_almacen = true;
                id_redencion = getParameterByName("id_redencion");
                id_almacen = getParameterByName("id_almacen");
            } else {
                document.location.href = document.referrer;
            }

            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                        results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }
        </script>
        <style>
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

            #sig-canvas1 {
                border: 2px dotted #CCCCCC;
                border-radius: 5px;
                cursor: crosshair;
            }

            #sig-dataUrl1 {
                width: 100%;
            }

            #singModal1 {
                top: 0px !important;
                touch-action: none;
                overflow: hidden;
            }

            #sig-canvas1 {
                touch-action: none;
            }

            .modal-dialog {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .modal-content {
                height: auto;
                min-height: 100%;
                border-radius: 0;
            }

        </style>
    </head>

    <body ng-app="legalizacionApp" ng-controller="legalizacionController" class="wrapper skin-blue layout-top-nav" style="height: auto; min-height: 100%;">
        <?php include 'componentes/mostrar_imagen.php'; ?>  
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-custom-menu">
                        <?php include 'componentes/controles_superiores.php'; ?>
                    </div>
                </div>
            </nav>
        </header>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Legalizar Actas
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li onclick="javascript:document.location.href = document.referrer"><i class="fa fa-home"></i>
                        Almacen</li>
                    <li class="active">Legalizar Actas</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class='row text-justify'>

                    <div class="col-md-offset-1 col-md-8">

                    </div>
                    <div class="col-md-offset-1 col-md-2">
                        <a class="btn btn-danger" id="btn_atras"
                           onclick="javascript:document.location.href = document.referrer">Volver</a>
                    </div>

                    <div class="col-md-offset-1 col-md-12">
                        <br />
                        <img src='../images/logos/tat.png' style='width: 150px;' />
                        <img src='../images/logos/gsk_logo.png' style='width: 150px;' />
                        <br />
                        <br />
                    </div>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-4">
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Legalizar con Acta Física</button>
                            <p>
                                Bogota
                                <br />
                                <br />
                            </p>
                            <p>
                                Cliente
                                <input type="text" class="form-control" ng-model="legalizacion.almacen"
                                       ng-disabled="true" />
                            </p>
                            <p>
                                <b>Referencia:</b> Plan Socios & Amigos TAT 2020
                            </p>
                            <p>
                                <b>Periodo a legalizar:</b> {{temporada_actual}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <br />
                            <br />
                            <p>
                                Apreciados Señores:
                            </p>
                            <p>
                                Por su participación en el Plan Socios y Amigos TAT,
                                de GlaxoSmithKline Consumer Healthcare Colombia S.A.S.
                                (Nit 900.809.229-8) y Pfizer PFE Colombia S.A.S. (Nit 900.786.357-1) hacemos Entrega de:
                            </p>
                            <div ng-repeat="premio in legalizacion.premios track by $index">
                                <b>{{premio.id_redencion}} - {{premio.afiliado}} - {{premio.premio}}</b>
                            </div>
                            <br />
                            <br />
                            <p>
                                La entrega mencionada no representa incentivo ni
                                recompensa por la compra, prescripción o recomendación 
                                pasada, presente o futura de productos de Pfizer PFE y GSK Consumer.
                            </p>
                            <p>
                                Esta entrega no puede ser transferida a terceros ,
                                a consumidores o personal que expenda medicamentos
                                o fitoterapeuticos al consumidor.
                            </p>
                            <br />
                            <br />
                            <p>
                                Cordial saludo
                            </p>
                            <br />
                            <br />
                            <p>
                                <b>{{datos_usuario.nombre}}</b>
                            </p>
                            <br />
                            <b>Pfizer SAS</b>
                            <br />
                            <br />
                            <br />
                            <p>
                                En señal de aceptación y recibo, suscribo el presente documento:
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-4">
                            <b>Fecha de entrega </b><input class="form-control" type='text'
                                                           ng-model="legalizacion.fecha_entrega" /><br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-4">
                            <b>Nombre Cliente </b>
                            <input class="form-control" type='text' ng-model="legalizacion.nombre" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-4">
                            <b>Documento Cliente </b>
                            <input class="form-control" type='text' ng-model="legalizacion.documento" />
                        </div>
                        <div class="col-md-offset-1 col-md-10">
                            <img id="sig-image-confirmed" src="" alt="Firma no registrada!"
                                 ng-show="legalizacion.firma_vendedor != ''" />
                            <br />
                            <button class="btn btn-primary" data-toggle="modal" data-target="#singModal"
                                    ng-click="AbrirModalFirmaVendedor()">Firma Cliente</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-4">
                            <br />
                            <br />
                            <b>Nombre Ejecutivo</b>
                            <input class="form-control" type='text' ng-model="datos_usuario.nombre"
                                   ng-disabled="true" />
                        </div>
                        <div class="col-md-offset-1 col-md-10">
                            <img id="sig-image-confirmed1" src="" alt="Firma no registrada!"
                                 ng-show="legalizacion.firma_visitador != ''" />
                            <br />
                            <button class="btn btn-primary" data-toggle="modal" data-target="#singModal1"
                                    ng-click="AbrirModalFirmaVisitador()">Firmar Ejecutivo</button>
                        </div>
                    </div>    
                    <div class="row" ng-init="boton = 1">
                        <div class="col-md-offset-1 col-md-10">
                            <br />
                            <br />
                            <button class="btn btn-success" id="sig-clearBtn" ng-show="boton == 1" ng-click="LegalizarRedencion()">Legalizar redención</button>
                        </div>
                    </div>
                    <div class="row">
                        <br/>
                        <br/>
                        <br/>
                        <div class="form-group">
                            <input type="file" id="image" ng-model="legalizacion.foto" name="image" accept="image/*">
                            <label for="image">
                                <span class="image" mat-stroked-button color="primary"><i class="fa fa-upload"></i> Subir Imagen </span>
                            </label>
                            <img id='output'>
                        </div>
                    </div>
                    <br />
                    <div class="row" ng-show="false">
                        <div class="col-md-12">
                            <textarea id="sig-dataUrl" class="form-control" rows="5"
                                      ng-value="legalizacion.firma_vendedor">Data URL for your signature will go here!</textarea>
                        </div>
                    </div>
                    <div class="row" ng-show="false">
                        <div class="col-md-12">
                            <textarea id="sig-dataUrl1" class="form-control" rows="5"
                                      ng-value="legalizacion.firma_visitador">Data URL for your signature will go here!</textarea>
                        </div>
                    </div>
                    <br />
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


        <div class="modal fade" id="singModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Firma Vendedor</h4> 
                        <button class="btn btn-primary" id="sig-submitBtn"
                                ng-click="GuardarFirmaVendedor()" ng-show="legalizacion.firma_vendedor == ''"
                                data-dismiss="modal">Confirmar Firma</button>
                    </div>
                    <div class="modal-body">
                        <canvas id="sig-canvas" ng-show="legalizacion.firma_vendedor == ''">
                            El navegador no es compatible con esta funcionalidad
                        </canvas>
                        <img id="sig-image" src="" alt="Firma no registrada!"
                             ng-show="legalizacion.firma_vendedor != ''" />
                        <br />

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="singModal1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Firma Ejecutivo</h4> 
                        <button class="btn btn-primary" id="sig-submitBtn1"
                                ng-click="GuardarFirmaVisitador()" ng-show="legalizacion.firma_visitador == ''"
                                data-dismiss="modal">Confirmar Firma</button>
                    </div>
                    <div class="modal-body">
                        <canvas id="sig-canvas1" ng-show="legalizacion.firma_visitador == ''">
                            El navegador no es compatible con esta funcionalidad
                        </canvas>
                        <img id="sig-image1" src="" alt="Firma no registrada!"
                             ng-show="legalizacion.firma_visitador != ''" />
                        <br />
                    </div>
                </div>
            </div>
        </div>

        <script src="js/signature.js?reload=true"></script>
        <script>
                                         (function () {

                                             // Get a regular interval for drawing to the screen
                                             window.requestAnimFrame = (function (callback) {
                                                 return window.requestAnimationFrame ||
                                                         window.webkitRequestAnimationFrame ||
                                                         window.mozRequestAnimationFrame ||
                                                         window.oRequestAnimationFrame ||
                                                         window.msRequestAnimaitonFrame ||
                                                         function (callback) {
                                                             window.setTimeout(callback, 1000 / 60);
                                                         };
                                             })();

                                             // Set up the canvas
                                             var canvas1 = document.getElementById("sig-canvas1");
                                             var ctx = canvas1.getContext("2d");
                                             ctx.strokeStyle = "#222222";
                                             ctx.lineWith = 2;

                                             // Set up the UI
                                             var sigText = document.getElementById("sig-dataUrl1");
                                             var sigImage = document.getElementById("sig-image1");
                                             var clearBtn = document.getElementById("sig-clearBtn");
                                             var submitBtn = document.getElementById("sig-submitBtn1");
                                             clearBtn.addEventListener("click", function (e) {
                                                 clearCanvas();
                                                 sigText.innerHTML = "Data URL for your signature will go here!";
                                                 sigImage.setAttribute("src", "");
                                             }, false);
                                             submitBtn.addEventListener("click", function (e) {
                                                 var dataUrl = canvas1.toDataURL();
                                                 sigText.innerHTML = dataUrl;
                                                 sigImage.setAttribute("src", dataUrl);
                                             }, false);

                                             // Set up mouse events for drawing
                                             var drawing = false;
                                             var mousePos = {
                                                 x: 0,
                                                 y: 0
                                             };
                                             var lastPos = mousePos;
                                             canvas1.addEventListener("mousedown", function (e) {
                                                 drawing = true;
                                                 lastPos = getMousePos(canvas1, e);
                                             }, false);
                                             canvas1.addEventListener("mouseup", function (e) {
                                                 drawing = false;
                                             }, false);
                                             canvas1.addEventListener("mousemove", function (e) {
                                                 mousePos = getMousePos(canvas1, e);
                                             }, false);

                                             // Set up touch events for mobile, etc
                                             canvas1.addEventListener("touchstart", function (e) {
                                                 mousePos = getTouchPos(canvas1, e);
                                                 var touch = e.touches[0];
                                                 var mouseEvent = new MouseEvent("mousedown", {
                                                     clientX: touch.clientX,
                                                     clientY: touch.clientY
                                                 });
                                                 canvas1.dispatchEvent(mouseEvent);
                                             }, false);
                                             canvas1.addEventListener("touchend", function (e) {
                                                 var mouseEvent = new MouseEvent("mouseup", {});
                                                 canvas1.dispatchEvent(mouseEvent);
                                             }, false);
                                             canvas1.addEventListener("touchmove", function (e) {
                                                 var touch = e.touches[0];
                                                 var mouseEvent = new MouseEvent("mousemove", {
                                                     clientX: touch.clientX,
                                                     clientY: touch.clientY
                                                 });
                                                 canvas1.dispatchEvent(mouseEvent);
                                             }, false);

                                             // Prevent scrolling when touching the canvas
                                             document.body.addEventListener("touchstart", function (e) {
                                                 if (e.target == canvas1) {
                                                     e.preventDefault();
                                                 }
                                             }, false);
                                             document.body.addEventListener("touchend", function (e) {
                                                 if (e.target == canvas1) {
                                                     e.preventDefault();
                                                 }
                                             }, false);
                                             document.body.addEventListener("touchmove", function (e) {
                                                 if (e.target == canvas1) {
                                                     e.preventDefault();
                                                 }
                                             }, false);

                                             // Get the position of the mouse relative to the canvas
                                             function getMousePos(canvasDom, mouseEvent) {
                                                 var rect = canvasDom.getBoundingClientRect();
                                                 return {
                                                     x: mouseEvent.clientX - rect.left,
                                                     y: mouseEvent.clientY - rect.top
                                                 };
                                             }

                                             // Get the position of a touch relative to the canvas
                                             function getTouchPos(canvasDom, touchEvent) {
                                                 var rect = canvasDom.getBoundingClientRect();
                                                 return {
                                                     x: touchEvent.touches[0].clientX - rect.left,
                                                     y: touchEvent.touches[0].clientY - rect.top
                                                 };
                                             }

                                             // Draw to the canvas
                                             function renderCanvas() {
                                                 if (drawing) {
                                                     ctx.moveTo(lastPos.x, lastPos.y);
                                                     ctx.lineTo(mousePos.x, mousePos.y);
                                                     ctx.stroke();
                                                     lastPos = mousePos;
                                                 }
                                             }

                                             function clearCanvas() {
                                                 canvas1.width = canvas1.width;
                                             }

                                             // Allow for animation
                                             (function drawLoop() {
                                                 requestAnimFrame(drawLoop);
                                                 renderCanvas();
                                             })();

                                         })();
        </script>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Legalizar con imagen</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">                            
                            <div class="col-md-6 text-center">
                                <div class="form-group">
                                    <input type="file" id="image_vendedor" ng-model="legalizacion_imagen.foto_vendedor" name="image" accept="image/*">
                                    <label for="image_vendedor">
                                        <span class="image_vendedor" mat-stroked-button color="primary"><i class="fa fa-upload"></i> Subir Imágen Vendedor</span>
                                    </label>
                                    <img id='outputmodalvendedor'>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="form-group">
                                    <input type="file" id="image_acta" ng-model="legalizacion_imagen.foto_acta" name="image" accept="image/*">
                                    <label for="image_acta">
                                        <span class="image_acta" mat-stroked-button color="primary"><i class="fa fa-upload"></i> Subir Imágen Acta</span>
                                    </label>
                                    <img id='outputmodalacta'>
                                </div>
                            </div>
                            <div ng-init="boton_modal = 1">
                                <div class="col-md-12">
                                    <br />
                                    <br />
                                    <button class="btn btn-success btn-block" id="sig-clearBtn" ng-show="boton_modal == 1" ng-click="LegalizarRedencionImagen()">Legalizar redención</button>
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
    </body>

</html>
