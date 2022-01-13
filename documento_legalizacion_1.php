<?php
include_once('clases/clsDDBBOperations.php');
include_once('clases/consultas.php');
include_once('../vendor/autoload.php');
require_once '../vendor/dompdf/autoload.inc.php';

require_once '../vendor/dompdf/lib/html5lib/Parser.php';
require_once '../vendor/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../vendor/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../vendor/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();


// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$nombre_documento = $_GET["nombre"];

$query = Consultas::$consulta_acta." where red.id = ".$_GET["folio"];
$results =  clsDDBBOperations::ExecuteSelectNoParams($query);

$dompdf = new Dompdf();
$html = $results[0]["comentario"];
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($nombre_documento);

/*
$nombre_documento = $_GET["nombre"];

$query = Consultas::$consulta_acta." where red.id = ".$_GET["folio"];
$results =  clsDDBBOperations::ExecuteSelectNoParams($query);

$mpdf = new mPDF();

$mpdf->Bookmark('Start of the document');

$html = $results[0]["comentario"];
$mpdf->WriteHTML($html);

$mpdf->Output($nombre_documento, 'D');*/
?>
<html>
    <head>
        <title>title</title>
        <script>
            window.close();
        </script>
    </head>
    <body>

    </body>
</html>
