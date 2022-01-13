<?php

include_once('clsDDBBOperations.php');
include_once('consultas.php');

class clsRedenciones
{
    public static function EjecutarOperacion($operacion, $parametros)
    {
        switch ($operacion)
        {
            case "Redimir": return clsRedenciones::Redimir($parametros);break;
            case "ObtenerRedenciones": return clsRedenciones::ObtenerRedenciones($parametros);break;
            case "ObtenerRedencionesEntregadas": return clsRedenciones::ObtenerRedencionesEntregadas($parametros);break;
            case "RegistrarBonoEntregado": return clsRedenciones::RegistrarBonoEntregado($parametros);break;
            case "CancelaRedencion": return clsRedenciones::CancelaRedencion($parametros);break;
            case "ModificarEstadoRedencionMasivo": return clsRedenciones::ModificarEstadoRedencionMasivo($parametros);break;
        }
    }
    
    private static function Redimir($parametros)
    {
        $indexFolio = 0;
        $folios = array();
        foreach ($parametros->redenciones as $redencion) {
            $folios[$indexFolio] = clsRedenciones::RegistrarRedencion(
                $parametros->id_afiliado,
                $parametros->id_almacen,
                $parametros->temporada,
                $redencion,
                $parametros->direccion
            );
            $indexFolio++;
        }

        $query = Consultas::$consulta_redenciones." where red.id IN %li";
        $redimidos = clsDDBBOperations::ExecuteSelect($query, $folios);

        return array('ok' => true, 'redenciones' => $redimidos);
    }
    
    private static function RegistrarRedencion($id_afiliado, $id_almacen, $temporada, $redencion, $direccion)
    {
        $inserts = array();
        $inserts["id_afiliado"] = $id_afiliado;
        $inserts["id_premio"] = $redencion->ID;
        $inserts["id_almacen"] = $id_almacen;
        $inserts["puntos"] = $redencion->PUNTOS;
        $inserts["direccion_envio"] = $direccion;
        $inserts["fecha_redencion"] = date('Y-m-d');
        $inserts["fecha_provista_entrega"] = date('Y-m-d', strtotime("+15 days"));
        $inserts["comentarios"] = $redencion->COMENTARIO;
        $inserts["id_registra"] = $id_afiliado;
        $inserts["temporada"] = $temporada;

        $resultado_redencion = clsDDBBOperations::ExecuteInsert($inserts, "redenciones");
        
        if(is_array($resultado_redencion))
        {
            $folio = clsDDBBOperations::GetLastInsertedId();
            $resultado_operacion = clsRedenciones::RegistrarOperacionRedencion($folio, 1, intval($_SESSION["usuario"]["id"]), $redencion->COMENTARIO, 0, 0);
        }

        return $folio;
    }
    
    private static function RegistrarEstadoDeCuenta($redencion)
    {
        $id_afiliado = intval($_SESSION["afiliadoSeleccionado"]["id"]);
        $descripcion = "Redencion ".$redencion->NOMBRE;
        
        return clsEstadoCuenta::RegistrarTransaccion(
                0,
                $id_afiliado, 
                2, 
                $redencion->PUNTOS, 
                $descripcion);
    }
    
    public static function RegistrarOperacionRedencion($folio,$id_operacion,$id_registra,$comentario,$finaliza,$retorna)
    {
        $query = Consultas::$consulta_redencion." where id = %i";
        $result = "";
        $redencion = clsDDBBOperations::ExecuteUniqueRow($query, $folio);
        
        $hoy = date_create(date('Y-m-d'));
        $to=date_create($redencion["fecha_redencion"]);
        $diff=date_diff($to,$hoy);
        
        if($id_operacion == 5 && $diff->days > 1)
        {
            return "Premio no puede ser cancelado despues de 24 horas";
        }
        
        if($redencion["finalizada"] == 0)
        {
            $inserts = array();
            $inserts["id_redencion"] = $folio;
            $inserts["id_operacion"] = $id_operacion;
            $inserts["fecha_operacion"] = date('Y-m-d');
            $inserts["id_usuario"] = $id_registra;
            $inserts["comentario"] = $comentario;
            
            clsDDBBOperations::ExecuteInsert($inserts, "seguimiento_redencion");

            if($finaliza == 1)
            {
                clsRedenciones::FinalizaRedencion($folio);
            }

            if($retorna == 1)
            {
                clsRedenciones::FinalizaRedencion($folio);
                clsRedenciones::CancelaRedencion($folio, $id_registra, $redencion);
            }
        }
        else
        {
            $result = "El premio se encuentra finalizado";
        }
        
        return $result;
    }
    
    public static function FinalizaRedencion($id_redencion)
    {
        $updates = array();
        $updates["finalizada"] = 1;
        DB::update('redenciones', $updates , "id = %i", $id_redencion);
    }
    
    private static function VerificarRequisito($parametros)
    {
        $query = Consultas::$comprobacion_requisito;
        
        $query = str_replace("_id_premio_", $parametros->id_requisito, $query);
        $query = str_replace("_id_afiliado_", $parametros->id_afiliado, $query);
        $results = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        
        $query = Consultas::$consulta_premios." and pre.ID = ".$parametros->id_premio;
        $premio = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        
        return array('ok' => true, 'requisito' => $results, 'premio' => $premio);
    }
    
    private static function VerificarUnicidad($parametros)
    {
        $query = Consultas::$comprobacion_unicidad;
        
        $query = str_replace("_id_premio_", $parametros->id_premio, $query);
        $query = str_replace("_id_afiliado_", $parametros->id_afiliado, $query);
        $results = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        
        $query = Consultas::$consulta_premios." and pre.ID = ".$parametros->id_premio;
        $premio = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        
        return array('ok' => true, 'unico' => $results, 'premio' => $premio);
    }
    
    private static function VerificarTopes($parametros)
    {
        $query = "select limite_tarjetas_periodo from clasificacion where id = %i";
        $tope_clasificacion = clsDDBBOperations::ExecuteUniqueRow($query, $_SESSION["afiliadoSeleccionado"]["id_clasificacion"]);
        $tope_clasificacion = $tope_clasificacion["limite_tarjetas_periodo"];
        
        $sumatoria_redimidas = clsRedenciones::ObtenerRedencionesRecargasTarjetasPeriodo($parametros);;
        
        $query = Consultas::$consulta_premios." and pre.ID = ".$parametros->id_premio;
        $premio = clsDDBBOperations::ExecuteUniqueRowNoParams($query);

        return array(
            'ok' => true, 
            'tope_clasificacion' => $tope_clasificacion, 
            'sumatoria_redimidas' => $sumatoria_redimidas, 
            'premio' => $premio
        );
    }
    
    private static function ObtenerRedencionesRecargasTarjetasPeriodo($parametros)
    {
        $query = "
            select ifnull(sum(pre.VALOR_MONETARIO),0) sumatoria_redimidos
            from redenciones red inner join premios pre on pre.id = red.id_premio
            where pre.id in (2014,2070,2071) and MONTH(now()) = MONTH(fecha_redencion) 
            and red.id not in (select id_redencion from seguimiento_redencion where id_operacion = 5)
            and id_afiliado = %i
        ";
        
        $result = clsDDBBOperations::ExecuteUniqueRow($query, $parametros->id_afiliado);
        
        return $result["sumatoria_redimidos"];
    }
    
    private static function ObtenerRedenciones($parametros)
    {
        $query = Consultas::$consulta_redenciones." where afi.id = %i";
        $redenciones = clsDDBBOperations::ExecuteSelect($query, $parametros->id_afiliado);
        
        return array('ok' => true, 'redenciones' => $redenciones);
    }
    
    private static function ObtenerRedencionesEntregadas($parametros)
    {
        $query = Consultas::$consulta_redenciones." where opr.id = 4 and afi.id = %i";
        $redenciones = clsDDBBOperations::ExecuteSelect($query, $parametros->id_afiliado);

        return array('ok' => true, 'redenciones' => $redenciones);
    }
    
    private static function RegistrarBonoEntregado($parametros)
    {
        clsRedenciones::RegistrarOperacionRedencion($parametros->folio, 4, $parametros->id_usuario, "Bono entregado en redención", 1, 0);
        return array('ok' => true);
    }
    
    private static function CancelaRedencion($parametros)
    {
        $updates = array();
        $updates["finalizada"] = 1;
        $result = clsDDBBOperations::ExecuteUpdate($updates, "redenciones", $parametros->folio);
        
        $query = Consultas::$consulta_redenciones." where red.id = %i";
        $redencion = clsDDBBOperations::ExecuteUniqueRow($query, $parametros->folio);
        
        $comentario = "Cancelación ".$redencion["premio"];
        clsEstadoCuenta::RegistrarTransaccion(
                        0,
                        $redencion["id_afiliado"],
                        3, 
                        $redencion["puntos"],
                        $comentario);
        
        $redencion = clsDDBBOperations::ExecuteUniqueRow($query, $parametros->folio);
        return array('ok' => true, 'redenciones' => $redencion);
    }
    
    private static function ModificarEstadoRedencionMasivo($parametros)
    {
        foreach (explode(",", $parametros->ids_redenciones) as $id_redencion) {

            clsRedenciones::RegistrarOperacionRedencion(
                $id_redencion,
                $parametros->id_operacion, 
                $parametros->id_usuario, 
                $parametros->comentario, 
                0, 
                0
            );
            
        }
        
        return array('ok' => true, 'parametros' => $parametros);
    }
}
    
?>