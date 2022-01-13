<?php

include_once('clsDDBBOperations.php');
include_once('FECrypt.php');
include_once('consultas.php');
//include_once('clsMailHelper.php');
include_once('clsEstadoCuenta.php');

class clsVentas
{
    public static function EjecutarOperacion($operacion, $parametros)
    {
        switch ($operacion)
        {
            case "RegistrarVentas": return clsVentas::RegistrarVentas($parametros);break;
            case "ObtenerVentasSinVendedor": return clsVentas::VentasSinVendedor($parametros);break;
            case "ReclamarVenta": return clsVentas::ReclamarVenta($parametros);break;
        }
    }
    
    private static function RegistrarVentas($parametros)
    {
        $query = Consultas::$verificar_archivo_ventas." where ArchivoDeCarga = %s";
        $ventasExistentes = clsDDBBOperations::ExecuteUniqueRow($query, $parametros->archivo);
        
        if(intval($ventasExistentes["ventas"]) == 0)
        {
            foreach ($parametros->ventas as $venta) 
            {
                clsVentas::RegistraVenta($venta, $parametros->archivo);
            }
            
            clsVentas::RegistraEstadoDeCuenta($parametros->archivo);
            return array('ok' => true);
        }
        else
        {
            $mensaje = "Existen ".intval($ventasExistentes["ventas"])." ventas registradas para el archivo ".$parametros->archivo;
            return array('ok' => false, 'error' => $mensaje);
        }
    }
    
    private static function RegistraVenta($venta, $archivo)
    {
        $inserts = array();
        $inserts["tipo_operacion"] = $venta->tipo;
        $inserts["tienda"] = $venta->tienda;
        $inserts["recaudo"] = $venta->recaudo;
        $inserts["fecha"] =  $venta->fecha;
        $inserts["marca"] = $venta->marca;
        $inserts["articulo"] = $venta->articulo;
        $inserts["precio"] = $venta->precio;
        $inserts["cedula_vendedor"] = $venta->codigoVendedor;
        $inserts["venta"] = $venta->montoGE;
        $inserts["puntos_x_venta"] = $venta->puntos;
        $inserts["ArchivoDeCarga"] = $archivo;
        $inserts["fecha_registro"] = date('Y-m-d');

        $result = clsDDBBOperations::ExecuteInsert($inserts, "ventas");
        //print_r($result);
        
        return clsDDBBOperations::GetLastInsertedId();
    }
    
    private static function RegistraEstadoDeCuenta($archivo)
    {
        $query = Consultas::$obtener_ventas_afiliados." where ven.ArchivoDeCarga = %s";
        $ventas_afiliados = clsDDBBOperations::ExecuteSelect($query, $archivo);
        
        if(count($ventas_afiliados) > 0)
        {
            foreach ($ventas_afiliados as $venta) 
            {
                $comentario = $venta["marca"]."|".$venta["articulo"]."|".$venta["venta"]."|".$venta["archivo"]."|".$venta["recaudo"]."|".$venta["fecha"];
                $result = clsEstadoCuenta::RegistrarTransaccion($venta["id"], $venta["id_afiliado"], 2, $venta["puntos_x_venta"], $comentario);
            }
        }
    }
    
    private static function VentasSinVendedor($parametros)
    {
        $ventas_sin_vendedor = clsDDBBOperations::ExecuteSelectNoParams(Consultas::$ventas_sin_vendedor);
        return array('ok' => true, 'datos' => $ventas_sin_vendedor);
    }
    
    private static function ReclamarVenta($parametros)
    {
        $updates = array();
        $updates["id_afiliado_novedad"] = $parametros->id_afiliado;
        
        clsDDBBOperations::ExecuteUpdate($updates, "ventas", $parametros->id_venta);
        
        return array('ok' => true);
    }
}
    
?>