<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/estado_redenciones_masivo.js?cant=tell&if=is_true&ver=1" type="text/javascript"></script>
    
    <script>
            var usuario_en_sesion = <?php echo json_encode($_SESSION["usuario"]); ?>;
            var id_redencion = 0;
        </script>

</head>

<body ng-app="estadosRedencionApp" ng-controller="estadosRedencionController"
class="layout-top-nav" style="height: auto; min-height: 100%;">
    
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="wrapper">
  

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
            <?php include 'componentes/menu.php'; ?>
           
            <div class="row" ng-show="redencion.operacion != 'Encuestado'">
                <div class="container" id='conta'>
                     <section class="content-header">
                    <div class="col-md-18">
                        <h4>Actualización masiva</h4>
                    </div>
                     </section>
                    <div class="col-md-10">
                      <small>  Ingrese los folios a ser modificados con la siguiente organización: 
                        <ul>
                            <li>
                                <p>Cada fila debe contener los siguientes datos separados por este carácter |:</p>
                                <p>
                                    <b>
                                        Por ejemplo a continuación se muestra el modelo para indicar que el folio
                                        con número 1234 cambiará a estado Enviado y en su comentario se indicará la
                                        información relevante del cambio de estado en este caso Enviado por
                                        trasportadora
                                        envia, y finalmente el número de referencia será el número de guía que se usará
                                        200009339992.
                                    </b>
                                </p>
                                <p>
                                    <b>
                                        1234|3|Enviado por trasportadora envia|838389202
                                    </b>
                                </p>
                            </li>
                            <li>
                                Los datos estan listados a continuación
                                <ul>
                                    <li>Número de folio a modificar</li>
                                    <li>Estado a modificar</li>
                                    <li>Comentario *</li>
                                    <li>Codigo de referencia (N° de Guia, N° de Folio)</li>
                                </ul>
                            </li>
                        </ul>
                        <i>* El comentario debe ser maximo de 100 carácteres y se recomienda que se ingrese información
                            concreta y relevante como el número de guía.</i>
                    </div>
                    <div class="col-md-8">
                    <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Estados posibles</th>
                                    </tr>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>1</td><td>Redimido</td></tr>
                                    <tr><td>2</td><td>Solicitado</td></tr>
                                    <tr><td>3</td><td>Enviado</td></tr>
                                    <tr><td>4</td><td>Entregado</td></tr>
                                    <tr><td>5</td><td>Cancelado</td></tr>
                                    <tr><td>6</td><td>Novedad</td></tr>
                                    <tr><td>7</td><td>Encuestado</td></tr>
                                    <tr><td>8</td><td>Despachado</td></tr>
                                    <tr><td>9</td><td>Garantia En Proceso</td></tr>
                                </tbody>
                            </table>   
                        <b>Cantidad de folios:</b> {{informacion_folios.cantidad}}
                        <br />
                        <b>Estructura correcta:</b> {{informacion_folios.folios_correctos}}
                        <br />
                        <b>Estructura incorrecta:</b>
                        {{informacion_folios.cantidad - informacion_folios.folios_correctos}}
                        <br />
                        <b>Procesados correctamente:</b> {{informacion_folios.folios_procesados}}
                        <br />
                        
                        <button class="btn btn-success btn-block btn-sm" ng-click="ProcesarFoliosMasivos()"
                            ng-disabled=" !(informacion_folios.cantidad > 0 && informacion_folios.cantidad == informacion_folios.folios_correctos) " style="width: 20%;">
                            Procesar folios
                        </button>
                        </small>
                    </div>
                    <div class="small-12 cell">
                        <br />
                        <textarea ng-model="informacion_folios.listado" ng-change="SumarioFoliosMasivos()" rows="10"
                            style="width: 100%;"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="main-footer">
                <?php include 'componentes/footer.php'; ?>
            </footer>
            <?php include 'componentes/coponentes_js.php'; ?>
</body>

</html>