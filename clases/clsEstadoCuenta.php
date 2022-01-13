<?php

include_once('clsDDBBOperations.php');
include_once('FECrypt.php');
include_once('consultas.php');
//include_once('clsMailHelper.php');

class clsEstadoCuenta
{
    public static function ObtenerEstadoCuenta($parametros)
    {
        $results = array();
        
        $transactions = clsDDBBOperations::ExecuteSelect(Consultas::$consulta_estado_cuenta, $parametros->id_afiliado);
        $periodoActual = "";
        $conceptoActual = "";
        
        foreach ($transactions as $transaccion) 
        {
            if($periodoActual != $transaccion["id_periodo"])
            {
                $periodoActual = $transaccion["id_periodo"];
                $results[$periodoActual] = array();
                $results[$periodoActual]["total"] = 0;
                $results[$periodoActual]["nombre"] = $transaccion["periodo"];
            }
            
            if($conceptoActual != $transaccion["concepto"])
            {
                $conceptoActual = $transaccion["concepto"];
                $results[$periodoActual][$conceptoActual] = array();
                $results[$periodoActual][$conceptoActual]["total"] = 0;
            }
            
            if($transaccion["id_concepto"] == 2)
            {
                $transaccionArray = explode("|", $transaccion["descripcion"]);
                $transaccion["marca"] = $transaccionArray[0];
                $transaccion["articulo"] = $transaccionArray[1];
                $transaccion["venta"] = $transaccionArray[2];
                $transaccion["fecha"] = $transaccionArray[5];
            }
            
            if($transaccion["suma"] == 1)
            {
                $results[$periodoActual]["total"] += $transaccion["puntos"];
                $results[$periodoActual][$conceptoActual]["total"] += $transaccion["puntos"];
            }
            else
            {
                $results[$periodoActual]["total"] -= $transaccion["puntos"];
                $results[$periodoActual][$conceptoActual]["total"] -= $transaccion["puntos"];
            }
            
            $results[$periodoActual][$conceptoActual][$transaccion["id"]] = $transaccion;
        }
        
        return $results;
    }
    
    public static function RegistrarTransaccion($id_venta, $id_afiliado, $id_concepto, $puntos, $descripcion)
    {
        $inserts = array();
        $inserts["id_periodo"] = clsEstadoCuenta::ObtenerPeriodoActual();
        $inserts["id_venta"] = $id_venta;
        $inserts["id_afiliado"] = $id_afiliado;
        $inserts["id_concepto"] = $id_concepto;
        $inserts["puntos"] = $puntos;
        $inserts["descripcion"] = $descripcion;
        $inserts["fecha"] = date('Y-m-d');
        clsDDBBOperations::ExecuteInsert($inserts, "estado_cuenta");
        
        return clsDDBBOperations::GetLastInsertedId();
    }
    
    public static function ObtenerPeriodoActual()
    {
        $query = str_replace("_date_", date('Y/m/d'),Consultas::$obtener_periodo_por_fecha);
        $id_periodo = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        return $id_periodo["id_periodo"];
    }
}
    
?>