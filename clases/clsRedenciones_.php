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
        }
    }
    
    private static function Redimir($parametros)
    {
        $puntosActuales = intval($_SESSION["afiliadoSeleccionado"]["puntos"]);
        
        if($puntosActuales >= $parametros->puntosTotalRedencion)
        {
            $indexFolio = 0;
            $folios = array();
            foreach ($parametros->redenciones as $redencion) {
                $folios[$indexFolio] = clsRedenciones::RegistrarRedencion($redencion,$parametros->id_ciudad,$parametros->direccion);
                $indexFolio++;
            }

            $query = Consultas::$consulta_redenciones." where red.id IN %li";
            $redimidos = clsDDBBOperations::ExecuteSelect($query, $folios);
            
            $query = Consultas::$consulta_afiliados." where afi.id = %i";
            $afiliado = clsDDBBOperations::ExecuteUniqueRow($query, intval($_SESSION["afiliadoSeleccionado"]["id"]));

            clsMailHelper::EnviarMailRedencion($afiliado["EMAIL"], $afiliado["NOMBRE"], $redimidos);
            
            $nuevosPuntos = clsRedenciones::ActualizarPuntos();

            return array('ok' => true, 'redenciones' => $redimidos, 'puntosNuevos' => $nuevosPuntos);
        }
        else
        {
            return array('ok' => false, 'error' => "Los puntos registrados en el servidor no son suficientes para esta redención.");
        }
    }
    
    private static function ActualizarPuntos()
    {
        $query = Consultas::$consulta_login." where afiliados.id = %i";
        $results = clsDDBBOperations::ExecuteUniqueRow($query, intval($_SESSION["afiliadoSeleccionado"]["id"]));
        $_SESSION["afiliadoSeleccionado"]["puntos"] = $results["puntos"];
        return $results["puntos"];
    }
    
    private static function RegistrarRedencion($redencion, $id_ciudad, $direccion)
    {
        $inserts = array();
        $inserts["id_afiliado"] = intval($_SESSION["afiliadoSeleccionado"]["id"]);
        $inserts["id_premio"] = $redencion->ID;
        $inserts["puntos"] = $redencion->PUNTOS;
        $inserts["direccion_envio"] = $direccion;
        $inserts["fecha_redencion"] = date('Y-m-d');
        $inserts["fecha_provista_entrega"] = date('Y-m-d', strtotime("+15 days"));
        $inserts["comentarios"] = $redencion->COMENTARIO;
        $inserts["id_registra"] = intval($_SESSION["usuario"]["id"]);
        $inserts["id_ciudad"] = $id_ciudad;

        clsDDBBOperations::ExecuteInsert($inserts, "redenciones");
        $folio = clsDDBBOperations::GetLastInsertedId();
        
        clsRedenciones::RegistrarOperacionRedencion($folio, 1, intval($_SESSION["usuario"]["id"]), $redencion->COMENTARIO, 0, 0);
        clsRedenciones::RegistrarEstadoDeCuenta($redencion);
        
        return $folio;
    }
    
    private static function RegistrarEstadoDeCuenta($redencion)
    {
        $id_afiliado = intval($_SESSION["afiliadoSeleccionado"]["id"]);
        
        return clsEstadoCuenta::RegistrarTransaccion(
                $id_afiliado, 
                3, 
                $redencion->PUNTOS, 
                "Redencion ".$redencion->NOMBRE);
    }
    
    private static function RegistrarOperacionRedencion($folio,$id_operacion,$id_registra,$comentario,$finaliza,$retorna)
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
            
            DB::insert('seguimiento_redencion', $inserts);

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
}
    
?>