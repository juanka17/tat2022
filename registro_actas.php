<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/registro_actas.js?ver=8" type="text/javascript"></script>
</head>
<style>
    input:valid,
    textarea:valid {
        border: 2px solid;
        border-color: green;
    }

    input:invalid,
    textarea:invalid {
        border: 2px solid;
        border-color: red;
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

<body ng-app="registroActasApp" ng-controller="registrosActasontroller">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper skin-blue layout-top-nav" style="height: auto; min-height: 100%;">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h3>
                    Registro: Cordial saludo.
                </h3>
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="row">
                    <form ng-submit="LegalizarRedencion();">
                        <div class="col-sm-12 col-md-offset-2 col-md-8">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>
                                        1) Ingrese Documento
                                    </label>
                                    <input class="form-control" type="number" ng-model="registro.documento" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="gerente">Buscar</label>
                                <button type="button" class="btn btn-primary btn-block" ng-click="BuscarPersona();"> Buscar Persona</button>
                            </div>
                        </div>
                        <div ng-show="datos_persona.length > 0">
                            <div class="row">
                                <div class="col-sm-12 col-md-offset-4 col-md-4">
                                    <label for="tipo_documento">Documento a legalizar</label>
                                    <select class="form-control" name="tipo_documento" value="{{registro.tipo_documento}}" id="tipo_documento" ng-model="registro.tipo_documento" ng-change="VerificarActa();">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Aceptar Habeas Data</option>
                                        <option value="2">Aceptar Términos y Condiciones</option>
                                        <option value="3">Aceptar Términos y Condiciones y Habeas Data</option>
                                    </select>
                                </div>

                                <div class="col-sm-12 col-md-offset-2 col-md-8">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="nombre">Nombre</label>
                                        <input class="form-control" ng-disabled="true" name="nombre" type="text" value="{{datos_persona[0].nombre}}" />
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="tipo_persona">Tipo Persona</label>
                                        <select class="form-control" ng-disabled="true" name="tipo_persona" ng-model="datos_persona[0].admin" id="tipo_persona" value="{{datos_persona[0].admin}}">
                                            <option value="1">Propietario</option>
                                            <option value="0">Administrador</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <table class="table text-center">
                                            <thead>
                                                <tr>
                                                    <th>Propietario</th>
                                                    <th>Administrador</th>
                                                    <th>Codigo</th>
                                                    <th>Drogueria</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="drogueria in droguerias">
                                                    <td>{{drogueria.dueno}}</td>
                                                    <td>{{drogueria.admin}}</td>
                                                    <td>{{drogueria.codigo}}</td>
                                                    <td>{{drogueria.drogueria}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-offset-2 col-md-8 text-justify">
                                        <div ng-if="registro.tipo_documento == 1 && acta_verificada.length > 0">
                                            <h3>ya existe un acta registrada para ese tipo de documento</h3>
                                        </div>
                                        <div ng-if="registro.tipo_documento == 1 && acta_verificada.length == 0">
                                            <iframe src="HabeasData2021.pdf" frameborder="0" width="655" height="550" marginheight="0" marginwidth="0" id="pdf">
                                                Ver Habeas Data
                                            </iframe>
                                        </div>
                                        <div ng-if="registro.tipo_documento == 2 && acta_verificada.length > 0">
                                            <h3>ya existe un acta registrada para ese tipo de documento</h3>
                                        </div>
                                        <div ng-if="registro.tipo_documento == 2 && acta_verificada.length == 0">
                                            <iframe src="tyc_acta_2021.pdf" frameborder="0" width="655" height="550" marginheight="0" marginwidth="0" id="pdf">
                                                Ver los términos y condiciones
                                            </iframe>
                                        </div>
                                        <div ng-if="registro.tipo_documento == 3 && acta_verificada.length == 0">
                                            <iframe src="HabeasData2021.pdf" frameborder="0" width="655" height="550" marginheight="0" marginwidth="0" id="pdf">
                                                Ver Habeas Data
                                            </iframe>
                                            <iframe src="tyc_acta_2021.pdf" frameborder="0" width="655" height="550" marginheight="0" marginwidth="0" id="pdf">
                                                Ver los términos y condiciones
                                            </iframe>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div id="img_perfil">
                                                <img id="sig-image-confirmed1" src="" alt="Firma no registrada!" ng-show="legalizacion.firma_visitador != ''" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-offset-4 col-md-4">
                                    <button class="btn btn-primary" data-toggle="modal" type="button" data-target="#singModal1" ng-click="AbrirModalFirmaVisitador()">
                                        Firmar
                                    </button>
                                    <div class="row" ng-if="false">
                                        <div class="col-md-12">
                                            <textarea id="sig-dataUrl1" class="form-control" rows="5" ng-value="legalizacion.firma_visitador">Data URL for your signature will go here!</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-offset-2 col-md-8">
                                    <br />
                                    <br />
                                    <br />
                                    <button type="submit" id="sig-clearBtn" class="btn btn-primary btn-block">
                                        Registrar
                                    </button>
                                    <img id="mostrar_gif" src="images/loader.gif" alt="">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div ng-show="datos_persona.length == 0">
                        <div class="col-sm-12 col-md-12 text-center">
                            <h3>Persona no encontrada</h3>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    </div>
    <div class="modal fade" id="singModal1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Firma</h4>
                    <button class="btn btn-primary" id="sig-submitBtn1" ng-click="GuardarFirmaVisitador()" ng-show="legalizacion.firma_visitador == ''" data-dismiss="modal">Confirmar Firma</button>
                </div>
                <div class="modal-body">
                    <canvas id="sig-canvas1" ng-show="legalizacion.firma_visitador == ''">
                        El navegador no es compatible con esta funcionalidad
                    </canvas>
                    <img id="sig-image1" src="" alt="Firma no registrada!" ng-show="legalizacion.firma_visitador != ''" />
                    <br />
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {

            // Get a regular interval for drawing to the screen
            window.requestAnimFrame = (function(callback) {
                return window.requestAnimationFrame ||
                    window.webkitRequestAnimationFrame ||
                    window.mozRequestAnimationFrame ||
                    window.oRequestAnimationFrame ||
                    window.msRequestAnimaitonFrame ||
                    function(callback) {
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
            clearBtn.addEventListener("click", function(e) {
                clearCanvas();
                sigText.innerHTML = "Data URL for your signature will go here!";
                sigImage.setAttribute("src", "");
            }, false);
            submitBtn.addEventListener("click", function(e) {
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
            canvas1.addEventListener("mousedown", function(e) {
                drawing = true;
                lastPos = getMousePos(canvas1, e);
            }, false);
            canvas1.addEventListener("mouseup", function(e) {
                drawing = false;
            }, false);
            canvas1.addEventListener("mousemove", function(e) {
                mousePos = getMousePos(canvas1, e);
            }, false);

            // Set up touch events for mobile, etc
            canvas1.addEventListener("touchstart", function(e) {
                mousePos = getTouchPos(canvas1, e);
                var touch = e.touches[0];
                var mouseEvent = new MouseEvent("mousedown", {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas1.dispatchEvent(mouseEvent);
            }, false);
            canvas1.addEventListener("touchend", function(e) {
                var mouseEvent = new MouseEvent("mouseup", {});
                canvas1.dispatchEvent(mouseEvent);
            }, false);
            canvas1.addEventListener("touchmove", function(e) {
                var touch = e.touches[0];
                var mouseEvent = new MouseEvent("mousemove", {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas1.dispatchEvent(mouseEvent);
            }, false);

            // Prevent scrolling when touching the canvas
            document.body.addEventListener("touchstart", function(e) {
                if (e.target == canvas1) {
                    e.preventDefault();
                }
            }, false);
            document.body.addEventListener("touchend", function(e) {
                if (e.target == canvas1) {
                    e.preventDefault();
                }
            }, false);
            document.body.addEventListener("touchmove", function(e) {
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
</body>

</html>