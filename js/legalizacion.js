var demo = "test";
var waterMark = "";

angular.module('legalizacionApp', []).controller('legalizacionController', function($scope, $http) {

    $scope.legalizacion = {
        nombre: "",
        documento: "",
        almacen: "",
        premio: "",
        fecha_entrega: "",
        firma_vendedor: "",
        firma_visitador: "",
        foto: ""
    };
    $scope.legalizacion_imagen = {
        foto_vendedor: "",
        foto_acta: ""
    };

    // <editor-fold defaultstate="collapsed" desc="Datos Redención">

    $scope.CargaDatosRedencion = function() {
        var parametros = { catalogo: "datos_redencion", id_redencion: id_redencion };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDatosRedencion);
    };

    $scope.MostrarDatosRedencion = function(data) {
        $scope.redencion = data[0];
        $scope.VerDetalleAlmacen();
    };

    $scope.CargaDatosRedenciones = function() {
        var parametros = { catalogo: "datos_redencion_almacen_estado", id_estado: 4, id_almacen: id_almacen };

        if (id_temporada != 0) {
            parametros = { catalogo: "datos_redencion_almacen_estado_temporada", id_estado: 4, id_almacen: id_almacen, id_temporada: id_temporada };
        }
        console.log(parametros);
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDatosRedenciones);
    };

    $scope.MostrarDatosRedenciones = function(data) {
        if (!legalizacion_individual) {
            $scope.redenciones = data;
        } else {
            angular.forEach(data, function(item) {
                if (item.id_redencion == id_redencion) {
                    $scope.redenciones = Array();
                    $scope.redenciones.push(item);
                }
            });
        }
        $scope.temporada_actual = $scope.redenciones[0].temporada_otros;



        $scope.VerDetalleAlmacen();
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Datos del almacén">

    $scope.VerDetalleAlmacen = function() {
        var parametros = { catalogo: "almacen_informacion", id_almacen: id_almacen };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDetalleAlmacen);
    };

    $scope.MostrarDetalleAlmacen = function(data) {
        $scope.almacen = data[0];
        console.log($scope.almacen);
        $scope.AsignarCamposExistentes();
    };

    $scope.AsignarCamposExistentes = function() {
        $scope.legalizacion.almacen = $scope.almacen.drogueria;
        $scope.legalizacion.fecha_entrega = moment().format("YYYY-MM-DD HH:mm:ss");

        $scope.legalizacion.premio = "-";
        $scope.legalizacion.premios = $scope.redenciones;

        console.log($scope.legalizacion.premios);
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Confirmar firma">

    $scope.AbrirModalFirmaVendedor = function() {
        clearCanvasVendedor();
        $scope.legalizacion.firma_vendedor = "";
    };

    $scope.GuardarFirmaVendedor = function() {
        $scope.legalizacion.firma_vendedor = $("#sig-dataUrl").val();
        $("#sig-image-confirmed").attr("src", $("#sig-image").attr("src"));
        console.log($scope.legalizacion);
    };

    $scope.LimpiarFirmaVendedor = function() {
        $scope.legalizacion.firma_vendedor = "";
    };

    $scope.AbrirModalFirmaVisitador = function() {
        clearCanvasVisitador();
        $scope.legalizacion.firma_visitador = "";
    };

    $scope.GuardarFirmaVisitador = function() {
        $scope.legalizacion.firma_visitador = $("#sig-dataUrl1").val();
        $("#sig-image-confirmed1").attr("src", $("#sig-image1").attr("src"));
        console.log($scope.legalizacion);
    };

    $scope.LimpiarFirmaVisitador = function() {
        $scope.legalizacion.firma_visitador = "";
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Legalizar redención">

    $scope.LegalizarRedencion = function() {
        $scope.legalizacion.foto = $("#image").val();
        var legalizacion_completa = true;
        angular.forEach($scope.legalizacion, function(dato) {
            if (dato == "") {
                legalizacion_completa = false;
            }
        });

        if (legalizacion_completa) {
            $scope.ConstruirDocumento();
        } else {
            alert("Debe completar toda la información, firmar y subir la imagen");
        }
    };

    $scope.LegalizarRedencionImagen = function() {
        $scope.legalizacion_imagen.foto_vendedor = $("#image_vendedor").val();
        $scope.legalizacion_imagen.foto_acta = $("#image_acta").val();
        var legalizacion_completa_modal = true;
        angular.forEach($scope.legalizacion_imagen, function(dato) {
            if (dato == "") {
                legalizacion_completa_modal = false;
            }
        });

        if (legalizacion_completa_modal) {
            $scope.ConstruirDocumentoImagen();
        } else {
            alert("Debe Seleccionar y subir las 2 imagenes");
        }
    };

    $scope.ConstruirDocumentoImagen = function() {
        $scope.boton_modal = 0;
        var index = 0;
        var html_documento = Array();

        html_documento.push("<img src='../images/logos/tat.png' style='width: 100px;' />");
        html_documento.push("<img src='../images/logos/gsk_logo.png' style='width: 100px; margin-left: 78%;' />");
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<b>Señor(es): </b>");
        html_documento.push("<br/>");
        html_documento.push($scope.legalizacion.almacen);
        html_documento.push("<br/>");
        html_documento.push("Ciudad: ");
        html_documento.push($scope.almacen.ciudad);
        html_documento.push("<p>");
        html_documento.push("<b>Referencia:</b> Acta de Entrega - Programa Socios & Amigos (TAT)");
        html_documento.push("</p>");
        html_documento.push("<br/>");
        html_documento.push("Apreciados señores");
        html_documento.push("<br/>");
        html_documento.push("<p>");
        html_documento.push("Por medio de la presente legalizamos la entrega de los productos identificados a continuación , en razón a su participación en el Programa Socios & Amigos (TAT) de GlaxoSmithKline Consumer Healthcare Colombia S.A.S. (GSK), identificada con NIT 900.809.229-8, de conformidad con los Términos y Condiciones del mismo");
        html_documento.push("</p>");
        html_documento.push("<p>");
        html_documento.push("<b>Periodo a legalizar:</b>");
        html_documento.push("<br/>");
        html_documento.push("<br/>");

        var temporada_html = Array();

        $scope.temporada_actual = $scope.redenciones[0].temporada_otros;
        temporada_html.push($scope.temporada_actual);
        temporada_html.push("<br/>");
        html_documento.push(temporada_html.join(""));

        html_documento.push("<b>");
        html_documento.push("Entregables:");
        html_documento.push("</b>");

        html_documento.push("<br/>");
        html_documento.push("<b>");

        angular.forEach($scope.legalizacion.premios, function(premio) {
            var premio_html = Array();
            $scope.temporada_actual = $scope.redenciones[0].temporada_otros

            premio_html.push(premio.id_redencion);
            premio_html.push(" - ");
            premio_html.push(premio.afiliado);
            premio_html.push(" - ");
            premio_html.push(premio.premio);
            premio_html.push("<br/>");
            html_documento.push(premio_html.join(""));
        });

        html_documento.push("</b>");
        html_documento.push("<br/>");
        html_documento.push("<p>");
        html_documento.push("El recibo de los Entregables no representa incentivo ni recompensa por la compra, prescripción o recomendación pasada, presente o futura de productos GSK y los Entregables no pueden ser transferido a terceros, consumidores o personal que expenda medicamentos o productos fitoterapéuticos al consumidor. Aplican los Términos y Condiciones del Programa.");
        html_documento.push("</p>");
        html_documento.push("<br/>");
        html_documento.push("En señal de aceptación y recibo,  suscribo el presente documento:");
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<b>Fecha de entrega:</b> ");
        html_documento.push($scope.legalizacion.fecha_entrega);
        html_documento.push("<br/>");
        html_documento.push("<b>Imagen Vendedor:</b> ");
        html_documento.push("<br/>");
        var foto_legalizacion_vendedor = "<img style='width: 50%; object-fit: scale-down' src='" + waterMarkVendedor + "' />";
        html_documento.push(foto_legalizacion_vendedor);
        html_documento.push("<br/>");
        html_documento.push("<b>Imagen Acta:</b> ");
        html_documento.push("<br/>");
        var foto_legalizacion_acta = "<img style='width: 50%; object-fit: scale-down' src='" + waterMarkActa + "' />";
        html_documento.push(foto_legalizacion_acta);

        $scope.documento_html = html_documento.join("");

        $scope.CambiarEstadoPremioVarios();
    };

    $scope.ConstruirDocumento = function() {
        $scope.boton = 0;
        var index = 0;
        var html_documento = Array();

        html_documento.push("<img src='../images/logos/tat.png' style='width: 100px;' />");
        html_documento.push("<img src='../images/logos/gsk_logo.png' style='width: 100px; margin-left: 78%;' />");
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<b>Señor(es): </b>");
        html_documento.push("<br/>");
        html_documento.push($scope.legalizacion.almacen);
        html_documento.push("<br/>");
        html_documento.push("Ciudad: ");
        html_documento.push($scope.almacen.ciudad);
        html_documento.push("<p>");
        html_documento.push("<b>Referencia:</b> Acta de Entrega - Programa Socios & Amigos (TAT)");
        html_documento.push("</p>");
        html_documento.push("<br/>");
        html_documento.push("Apreciados señores");
        html_documento.push("<br/>");
        html_documento.push("<p>");
        html_documento.push("Por medio de la presente legalizamos la entrega de los productos identificados a continuación , en razón a su participación en el Programa Socios & Amigos (TAT) de GlaxoSmithKline Consumer Healthcare Colombia S.A.S. (GSK), identificada con NIT 900.809.229-8, de conformidad con los Términos y Condiciones del mismo");
        html_documento.push("</p>");
        html_documento.push("<p>");
        html_documento.push("<b>Periodo a legalizar:</b>");
        html_documento.push("<br/>");

        var temporada_html = Array();

        $scope.temporada_actual = $scope.redenciones[0].temporada_otros;
        temporada_html.push($scope.temporada_actual);
        temporada_html.push("<br/>");
        html_documento.push(temporada_html.join(""));


        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<b>");
        html_documento.push("Entregables:");
        html_documento.push("</b>");

        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<b>");


        angular.forEach($scope.legalizacion.premios, function(premio) {
            var premio_html = Array();
            $scope.temporada_actual = $scope.redenciones[0].temporada_otros

            premio_html.push(premio.id_redencion);
            premio_html.push(" - ");
            premio_html.push(premio.afiliado);
            premio_html.push(" - ");
            premio_html.push(premio.premio);
            premio_html.push("<br/>");
            premio_html.push("<br/>");
            html_documento.push(premio_html.join(""));
        });

        html_documento.push("</b>");
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<p>");
        html_documento.push("El recibo de los Entregables no representa incentivo ni recompensa por la compra, prescripción o recomendación pasada, presente o futura de productos GSK y los Entregables no pueden ser transferido a terceros, consumidores o personal que expenda medicamentos o productos fitoterapéuticos al consumidor. Aplican los Términos y Condiciones del Programa.");
        html_documento.push("</p>");
        html_documento.push("<br/>");
        html_documento.push("En señal de aceptación y recibo,  suscribo el presente documento:");
        html_documento.push("<p>");
        html_documento.push("<b>" + $scope.almacen.visitador + "</b>");
        html_documento.push("</p>");
        html_documento.push("<br/>");
        html_documento.push("<b>Fecha de entrega:</b> ");
        html_documento.push($scope.legalizacion.fecha_entrega);
        html_documento.push("<br/>");
        html_documento.push("<b>Nombre Cliente:</b> ");
        html_documento.push("<br/>");
        html_documento.push($scope.legalizacion.nombre);
        html_documento.push("<br/>");
        html_documento.push("<b>Documento Cliente:</b> ");
        html_documento.push("<br/>");
        html_documento.push($scope.legalizacion.documento);
        html_documento.push("<br/>");
        html_documento.push("<b>Firma Cliente:</b> ");

        var firma_vendedor = "<img style='width: 50%; object-fit: scale-down' src='" + $scope.legalizacion.firma_vendedor + "' />";

        html_documento.push("<br/>");
        html_documento.push(firma_vendedor);
        html_documento.push("<br/>");
        html_documento.push("<b>Nombre Ejecutivo que legaliza<:</b> ");
        html_documento.push("<br/>");
        html_documento.push($scope.datos_usuario.nombre);
        html_documento.push("<br/>");
        html_documento.push("<b>Firma Ejecutivo:</b> ");

        var firma_visitador = "<img style='width: 50%; object-fit: scale-down' src='" + $scope.legalizacion.firma_visitador + "' />";

        html_documento.push("<br/>");
        html_documento.push(firma_visitador);
        html_documento.push("<br/>");
        html_documento.push("<br/>");
        html_documento.push("<b>Imagen:</b> ");
        html_documento.push("<br/>");
        var foto_legalizacion = "<img style='width: 50%; object-fit: scale-down' src='" + waterMark + "' />";
        html_documento.push(foto_legalizacion);

        $scope.documento_html = html_documento.join("");

        $scope.CambiarEstadoPremioVarios();
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Modificar estados de las redenciones">

    var conteo_auxiliar_legalizacion = 0;
    $scope.CambiarEstadoPremioVarios = function() {
        if (conteo_auxiliar_legalizacion < $scope.legalizacion.premios.length) {
            console.log(conteo_auxiliar_legalizacion);
            console.log($scope.legalizacion.premios.length);
            console.log($scope.legalizacion.premios[conteo_auxiliar_legalizacion].id_redencion);

            var id_redencion_esp = $scope.legalizacion.premios[conteo_auxiliar_legalizacion].id_redencion;
            $scope.CambiarEstadoPremio(id_redencion_esp, false);
            conteo_auxiliar_legalizacion++;
        } else {
            $scope.FinalizacionLegalizacion();
        }
    };

    $scope.CambiarEstadoPremio = function(id_redencion_conf, unico) {
        var estado_nuevo = {
            id_redencion: id_redencion_conf,
            id_operacion: 5,
            fecha_operacion: moment().format("YYYY-MM-DD HH:mm:ss"),
            id_usuario: datos_usuario.id,
            comentario: $scope.documento_html
        };

        var parametros = {
            catalogo: "redencion_individual",
            catalogo_real: "seguimiento_redencion",
            datos: estado_nuevo,
            folio: id_redencion_conf
        };

        if (unico) {
            $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixto", parametros, $scope.FinalizacionLegalizacion);
        } else {
            $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixto", parametros, $scope.CambiarEstadoPremioVarios);
        }
    };

    $scope.FinalizacionLegalizacion = function() {
        $("#btn_atras").click();
    };

    // </editor-fold>

    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack) {
        $http({
            method: "POST",
            url: "clases/jarvis.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: { modelo: modelo, operacion: operacion, parametros: parametros }
        }).success(function(data) {
            if (data.error == "") {
                CallBack(data.data);
            } else {
                $scope.errorGeneral = data.error;
            }
        });
    };

    $scope.datos_usuario = datos_usuario;
    $scope.legalizacion_individual = legalizacion_individual;

    $scope.CargaDatosRedenciones();

});

var canvas = null;
var ctx = null;
$(function() {
    console.log(1);
    $('#singModal').on('shown.bs.modal', function() {
        if (canvas == null) {
            canvas = document.getElementById("sig-canvas");
        }
        canvas.width = $("#singModal").width();
        var height = $("#singModal").height();
        height = height - ((height * 0.2));
        canvas.height = height;

        ctx = canvas.getContext("2d");
        ctx.strokeStyle = "#222222";
        ctx.lineWith = 2;

        console.log(2);
        var sigText = document.getElementById("sig-dataUrl");
        var sigImage = document.getElementById("sig-image");
        //var clearBtn = document.getElementById("singModal");
        var submitBtn = document.getElementById("sig-submitBtn");
        submitBtn.addEventListener("click", function(e) {
            console.log(20);
            var dataUrl = canvas.toDataURL();
            sigText.innerHTML = dataUrl;
            sigImage.setAttribute("src", dataUrl);
        }, false);
    });
});

var canvas1 = null;
var ctx1 = null;
$(function() {
    console.log(1);
    $('#singModal1').on('shown.bs.modal', function() {
        if (canvas1 == null) {
            canvas1 = document.getElementById("sig-canvas1");
        }
        canvas1.width = $("#singModal1").width();
        var height = $("#singModal1").height();
        height = height - ((height * 0.2));
        canvas1.height = height;

        ctx1 = canvas1.getContext("2d");
        ctx1.strokeStyle = "#222222";
        ctx1.lineWith = 2;

        console.log(2);
        var sigText = document.getElementById("sig-dataUrl1");
        var sigImage = document.getElementById("sig-image1");
        //var clearBtn = document.getElementById("singModal");
        var submitBtn = document.getElementById("sig-submitBtn1");
        submitBtn.addEventListener("click", function(e) {
            console.log(20);
            var dataUrl = canvas1.toDataURL();
            sigText.innerHTML = dataUrl;
            sigImage.setAttribute("src", dataUrl);
        }, false);
    });
});

function clearCanvasVendedor() {
    console.log(canvas);

    if (canvas != null) {
        canvas.width = canvas.width;
        var sigText = document.getElementById("sig-dataUrl");
        var sigImage = document.getElementById("sig-image");
        sigText.innerHTML = "Data URL for your signature will go here!";
        sigImage.setAttribute("src", "");
    }
}

function clearCanvasVisitador() {
    console.log(canvas1);

    if (canvas1 != null) {
        canvas1.width = canvas1.width;
        var sigText = document.getElementById("sig-dataUrl1");
        var sigImage = document.getElementById("sig-image1");
        sigText.innerHTML = "Data URL for your signature will go here!";
        sigImage.setAttribute("src", "");
    }
}


// <editor-fold defaultstate="collapsed" desc="Funcion imagen vendedor">
$("document").ready(function() {

    $("#image").change(function(event) {
        console.log("Event", event);

        var input = event.target;

        var reader = new FileReader();
        reader.onload = () => {
            var dataURL = reader.result;
            var output = document.getElementById('output');
            output.src = dataURL;
            console.log("reader", reader);
            console.log("reader.result", reader.result);

            waterMark = reader.result;
        };

        reader.readAsDataURL(input.files[0]);
        jQuery('span.image').html(input.files[0].name);

        /*     let file = event.target.files[0];
         
         console.log("TCL: AppConfigComponent -> reader.onload -> file.name", file.name)
         console.log("TCL: AppConfigComponent -> reader.onload -> file.type", file.type) */
    });
});

function onFileChange(event) {

    console.log("Event", event);

    var input = event.target;

    var reader = new FileReader();
    reader.onload = () => {
        var dataURL = reader.result;
        var output = document.getElementById('output');
        output.src = dataURL;
        console.log("reader", reader);
        console.log("mensajes");
        console.log(reader.result)
    };

    //reader.readAsDataURL(input.files[0]);
    jQuery('span.image').html(input.files[0].name);

    /*     let file = event.target.files[0];
     
     console.log("TCL: AppConfigComponent -> reader.onload -> file.name", file.name)
     console.log("TCL: AppConfigComponent -> reader.onload -> file.type", file.type) */
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Funcion imagen vendedor Modal">
$("document").ready(function() {

    $("#image_vendedor").change(function(event) {
        console.log("Event", event);

        var input = event.target;

        var reader = new FileReader();
        reader.onload = () => {
            var dataURL = reader.result;
            var outputmodalvendedor = document.getElementById('outputmodalvendedor');
            outputmodalvendedor.src = dataURL;
            console.log("reader", reader);
            console.log("reader.result", reader.result);

            waterMarkVendedor = reader.result;
        };

        reader.readAsDataURL(input.files[0]);
        jQuery('span.image_vendedor').html(input.files[0].name);

        /*     let file = event.target.files[0];
         
         console.log("TCL: AppConfigComponent -> reader.onload -> file.name", file.name)
         console.log("TCL: AppConfigComponent -> reader.onload -> file.type", file.type) */
    });
});

function onFileChange(event) {

    console.log("Event", event);

    var input = event.target;

    var reader = new FileReader();
    reader.onload = () => {
        var dataURL = reader.result;
        var outputmodalvendedor = document.getElementById('outputmodalvendedor');
        outputmodalvendedor.src = dataURL;
        console.log("reader", reader);
        console.log("mensajes");
        console.log(reader.result)
    };

    //reader.readAsDataURL(input.files[0]);
    jQuery('span.image_vendedor').html(input.files[0].name);

    /*     let file = event.target.files[0];
     
     console.log("TCL: AppConfigComponent -> reader.onload -> file.name", file.name)
     console.log("TCL: AppConfigComponent -> reader.onload -> file.type", file.type) */
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Funcion imagen acta modal">
$("document").ready(function() {

    $("#image_acta").change(function(event) {
        console.log("Event", event);

        var input = event.target;

        var reader = new FileReader();
        reader.onload = () => {
            var dataURL = reader.result;
            var outputmodalacta = document.getElementById('outputmodalacta');
            outputmodalacta.src = dataURL;
            console.log("reader", reader);
            console.log("reader.result", reader.result);

            waterMarkActa = reader.result;
        };

        reader.readAsDataURL(input.files[0]);
        jQuery('span.image_acta').html(input.files[0].name);

        /*     let file = event.target.files[0];
         
         console.log("TCL: AppConfigComponent -> reader.onload -> file.name", file.name)
         console.log("TCL: AppConfigComponent -> reader.onload -> file.type", file.type) */
    });
});

function onFileChange(event) {

    console.log("Event", event);

    var input = event.target;

    var reader = new FileReader();
    reader.onload = () => {
        var dataURL = reader.result;
        var outputmodalacta = document.getElementById('outputmodalacta');
        outputmodalacta.src = dataURL;
        console.log("reader", reader);
        console.log("mensajes");
        console.log(reader.result)
    };

    //reader.readAsDataURL(input.files[0]);
    jQuery('span.image_acta').html(input.files[0].name);

    /*     let file = event.target.files[0];
     
     console.log("TCL: AppConfigComponent -> reader.onload -> file.name", file.name)
     console.log("TCL: AppConfigComponent -> reader.onload -> file.type", file.type) */
}
// </editor-fold>