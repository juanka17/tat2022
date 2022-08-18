<?php


include_once('clases/clsDDBBOperations.php');


require_once __DIR__ . '../../vendor/autoload.php';


$id_almacen = $_GET["id_almacen"];

$query = "select * from habeas_data where id_almacen = " . $id_almacen;
$results =  clsDDBBOperations::ExecuteSelectNoParams($query);

$almacen = "select * from almacenes where id = " . $id_almacen;
$results_almacen =  clsDDBBOperations::ExecuteSelectNoParams($almacen);

$nombre = $results[0]["nombre"];
$documento = $results[0]["documento"];
$firma = $results[0]["firma"];
$fecha = $results[0]["fecha"];
$distribuidora = $results_almacen[0]["nombre"];

$fp = fopen("terminoscondiciones.txt", "a+");

$modelo_documento = "";

while (!feof($fp)) {

    $modelo_documento .= utf8_encode(fgets($fp));
}
fclose($fp);

$modelo_documento = str_replace("_firma_", $firma, $modelo_documento);
$modelo_documento = str_replace("_distribuidora_", $distribuidora, $modelo_documento);
$modelo_documento = str_replace("_nombre_", $nombre, $modelo_documento);
$modelo_documento = str_replace("_documento_", $documento, $modelo_documento);
$modelo_documento = str_replace("_fecha_", $fecha, $modelo_documento);

$nombre_documento = $results_almacen[0]["nombre"].".pdf";


$mpdf = new mPDF();

$mpdf->Bookmark('Start of the document');

$mpdf->WriteHTML($modelo_documento);
$mpdf->Output($nombre_documento, 'D');
