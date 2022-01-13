<?php

include_once('clsAfiliados.php');
include_once('clsCatalogos.php');
include_once('clsRedenciones.php');
include_once('clsVentas.php');
include_once('clsReportes.php');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$result = array();
$result["data"] = array();
$result["error"] = "";
$operationResult = "";

switch($request->modelo)
{
    case "afiliados":
        $operationResult = clsAfiliados::EjecutarOperacion($request->operacion, $request->parametros); break;
    case "catalogos":
        $operationResult = clsCatalogos::EjecutarOperacion($request->operacion, $request->parametros); break;
    case "redenciones":
        $operationResult = clsRedenciones::EjecutarOperacion($request->operacion, $request->parametros); break;
    case "ventas":
        $operationResult = clsVentas::EjecutarOperacion($request->operacion, $request->parametros); break;
    case "reportes":
        $operationResult = clsReportes::EjecutarOperacion($request->operacion, $request->parametros); break;
}

if(is_array($operationResult))
{
    $result["data"] = $operationResult;
}
else
{
    $result["error"] = $operationResult;
}

echo json_encode($result, JSON_NUMERIC_CHECK);

?>