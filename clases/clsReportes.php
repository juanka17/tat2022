<?php

ini_set('memory_limit', '-1');

include_once('clsDDBBOperations.php');
include_once('consultas.php');

class clsReportes {

    public static function EjecutarOperacion($operacion, $parametros) {
        switch ($operacion) {
            case "estado_cuenta": $datos = clsReportes::ReporteEstadoCuenta($parametros);
                break;
            case "estructura_almacenes": $datos = clsReportes::ReporteEstructuraAlmacenes($parametros);
                break;

            case "Redenciones": $datos = clsReportes::ReporteRedenciones($parametros);
                break;
            case "RankingSupervisores": $datos = clsReportes::ReporteRankingSupervisores($parametros);
                break;
            case "RankingVendedores1": $datos = clsReportes::ReporteRankingVendedoresPrimerBimestre($parametros);
                break;
            case "RankingVendedores2": $datos = clsReportes::ReporteRankingVendedoresSegundoBimestre($parametros);
                break;

            case "VendedorPerfectoIncauca": $datos = clsReportes::VendedorPerfectoIncauca($parametros);
                break;
            case "VendedorPerfectoIncaucaNuevo": $datos = clsReportes::VendedorPerfectoIncaucaNuevo($parametros);
                break;
            case "VendedorPerfectoOtros": $datos = clsReportes::VendedorPerfectoOtros($parametros);
                break;
            case "VendedorPerfectoOtrosNuevo": $datos = clsReportes::VendedorPerfectoOtrosNuevo($parametros);
                break;

            case "AvanceEntregas1": $datos = clsReportes::AvanceEntregas1($parametros);
                break;
            case "AvanceEntregas2": $datos = clsReportes::AvanceEntregas2($parametros);
                break;
            case "AvanceEntregas3": $datos = clsReportes::AvanceEntregas3($parametros);
                break;
            case "AvanceEntregas4": $datos = clsReportes::AvanceEntregas4($parametros);
                break;
            case "AvanceEntregas5": $datos = clsReportes::AvanceEntregas5($parametros);
                break;
            case "AvanceEntregas6": $datos = clsReportes::AvanceEntregas6($parametros);
                break;

            case "ConsolidadoEntregas": $datos = clsReportes::ConsolidadoEntregas($parametros);
                break;
            case "RankingFinal": $datos = clsReportes::RankingFinal($parametros);
                break;

            case "supervisor_lider": $datos = clsReportes::SupervisorLider($parametros);
                break;

            case "Cupos_almacen": $datos = clsReportes::CuposAlmacen($parametros);
                break;
            case "ventas_distribuidora": $datos = clsReportes::VentasDistribuidora($parametros);
                break;

            case "ventas_primer_bimestre_2021": $datos = clsReportes::VentasPrimerBimestre2021($parametros);
            break;
            case "ventas_segundo_bimestre_2021": $datos = clsReportes::VentasSegundoBimestre2021($parametros);
            break;
            case "ventas_tercer_bimestre_2021": $datos = clsReportes::VentasTercerBimestre2021($parametros);
            break;      
            case "ventas_cuarto_bimestre_2021": $datos = clsReportes::VentasCuartoBimestre2021($parametros);
            break;
            case "ventas_quinto_bimentre_2021": $datos = clsReportes::VentasQuintoBimestre2021($parametros);
            break;

            case "ventas_primer_bimestre_sku": $datos = clsReportes::ventas_primer_bimestre_sku($parametros);
            break;
            case "ventas_segundo_bimestre_sku": $datos = clsReportes::ventas_segundo_bimestre_sku($parametros);
            break;
            case "ventas_tercer_bimestre_sku": $datos = clsReportes::ventas_tercer_bimestre_sku($parametros);
            break;           
            case "ventas_cuarto_bimestre_sku": $datos = clsReportes::ventas_cuarto_bimestre_sku($parametros);
            break;
            case "ventas_quinto_bimestre_sku": $datos = clsReportes::ventas_quinto_bimestre_sku($parametros);
            break;


            case "cuotas_supervisores1": $datos = clsReportes::CuotasSupervisor1($parametros);
                break;
            case "cuotas_supervisores2": $datos = clsReportes::CuotasSupervisor2($parametros);
                break;
            case "cuotas_supervisores3": $datos = clsReportes::CuotasSupervisor3($parametros);
                break;
            case "cuotas_supervisores4": $datos = clsReportes::CuotasSupervisor4($parametros);
                break;
            case "cuotas_supervisores5": $datos = clsReportes::CuotasSupervisor5($parametros);
                break;
            case "cuotas_supervisores6": $datos = clsReportes::CuotasSupervisor6($parametros);
                break;

            case "estructura_supervisores": $datos = clsReportes::ReporteEstructuraSupervisores($parametros);
                break;

            case "obtener_indicadores_territorio": $datos = clsReportes::ObtenerIndicadoresTerritorio($parametros);
                break;
            case "obtener_indicadores_distribuidora": $datos = clsReportes::ObtenerIndicadoresDistribuidora($parametros);
                break;


            case "obtener_indicadores_representantes": $datos = clsReportes::ObtenerIndicadoresRepresentantes($parametros);
                break;
            case "obtener_indicadores_ventas": $datos = clsReportes::ObtenerIndicadoresVentas($parametros);
                break;
            case "sp_indicadores_ranking_productos": $datos = clsReportes::ObtenerIndicadoresRankingProductos($parametros);
                break;
            case "obtener_ndicadores_crecimiento": $datos = clsReportes::ObtenerIndicadoresCrecimiento($parametros);
                break;
            case "obtener_indicadores_cumplimiento": $datos = clsReportes::ObtenerIndicadoresCumplimiento($parametros);
                break;



            case "obtener_indicadores_representantes_checkbox": $datos = clsReportes::ObtenerIndicadoresRepresentantesCheckbox($parametros);
                break;
            case "obtener_indicadores_ventas_checkbox": $datos = clsReportes::ObtenerIndicadoresVentasCheckbox($parametros);
                break;
            case "sp_indicadores_ranking_productos_checkbox": $datos = clsReportes::ObtenerIndicadoresRankingProductosCheckbox($parametros);
                break;
            case "obtener_ndicadores_crecimiento_checkbox": $datos = clsReportes::ObtenerIndicadoresCrecimientoCheckbox($parametros);
                break;
            case "obtener_indicadores_cumplimiento_checkbox": $datos = clsReportes::ObtenerIndicadoresCumplimientoCheckbox($parametros);
                break;


            case "sp_indicadores_cupos": $datos = clsReportes::ObtenerIndicadoresCupos($parametros);
                break;
            case "sp_indicadores_cupos_territorio": $datos = clsReportes::ObtenerIndicadoresCuposTerritorio($parametros);
                break;

            case "obtener_indicadores_ventas_portafolio_nuevo": $datos = clsReportes::ObtenerIndicadoresVentasPortafolio($parametros);
                break;
            case "obtener_indicadores_ventas_nuevo": $datos = clsReportes::ObtenerIndicadoresVentasNuevo($parametros);
                break;
            case "obtener_indicadores_representantes_nuevo": $datos = clsReportes::ObtenerIndicadoresRepresentanteNuevo($parametros);
                break;
            case "indicadores_ranking_productos_nuevo": $datos = clsReportes::ObtenerIndicadoresRankingProductosNuevo($parametros);
                break;
            case "obtener_ndicadores_crecimiento_nuevo": $datos = clsReportes::ObtenerIndicadoresCrecimientosNuevo($parametros);
                break;
            case "obtener_indicadores_cumplimiento_nuevo": $datos = clsReportes::ObtenerIndicadoresCumplimientoNuevo($parametros);
                break;


            case "cuotas_actualizadas": $datos = clsReportes::ObtenerReportecuotasActualizadas($parametros);
                break;
            case "reporte_ventas_vendedor": $datos = clsReportes::ObtenerReporteCuotasVendedor($parametros);
                break;
 

            case "reporte_habeas_data": $datos = clsReportes::ReporteHabeasData($parametros);
                break;

            case "reporte_distribuidora_sin_habeas_data": $datos = clsReportes::ReporteDistribuidoraSinHabeasData($parametros);
                break;

            case "log_cambios": $datos = clsReportes::ReporteLogCambios($parametros);
                break;
        }
        return clsReportes::ProcesarDatos($datos);
    }

    private static function ProcesarDatos($datos) {
        if (count($datos)) {
            $headers = array();
            $colCount = 0;
            if (count($datos) > 0) {
                foreach ($datos[0] as $columName => $rowDefiner) {
                    $headers[$colCount] = $columName;
                    $colCount++;
                }
            } else {
                $data = 0;
            }

            $data = array("header" => $headers, "data" => $datos);
        } else {
            $data = 0;
        }

        return $data;
    }

    private static function ReporteEstructuraAlmacenes($parametros) {
        $query = Consultas::$estructira_fdv;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ReporteEstadoCuenta($parametros) {

        $query = Consultas::$reporte_estado_cuenta_total;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }


    private static function ventas_primer_bimestre_sku($parametros) {
        $query = "call sp_reporte_ventas_sku(1) ";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ventas_segundo_bimestre_sku($parametros) {
        $query = "call sp_reporte_ventas_sku(2) ";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ventas_tercer_bimestre_sku($parametros) {
        $query = "call sp_reporte_ventas_sku(3) ";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }    
    
    private static function ventas_cuarto_bimestre_sku($parametros) {
        $query = "call sp_reporte_ventas_sku(4) ";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }    
    
    private static function ventas_quinto_bimestre_sku($parametros) {
        $query = "call sp_reporte_ventas_sku(5) ";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ReporteRedenciones($parametros) {
        $query = "call sp_redenciones()";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ReporteRankingSupervisores($parametros) {
        $query = "call sp_ranking_supervisores()";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ReporteRankingVendedoresPrimerBimestre($parametros) {
        $query = "call sp_ganadores_ciclo(1,-1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ReporteRankingVendedoresSegundoBimestre($parametros) {
        $query = "call sp_ganadores_ciclo(2,-1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VendedorPerfectoIncauca($parametros) {
        $query = "call sp_vendedor_perfecto_global_incauca(-1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VendedorPerfectoIncaucaNuevo($parametros) {
        $query = "call sp_reporte_vendedor_perfecto_global_incauca(-1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VendedorPerfectoOtros($parametros) {
        $query = "call sp_vendedor_perfecto_global_otros(-1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VendedorPerfectoOtrosNuevo($parametros) {
        $query = "call sp_reporte_perfecto_global_otros(-1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function AvanceEntregas1($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function AvanceEntregas2($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(2);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function AvanceEntregas3($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(3);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function AvanceEntregas4($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(4);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function AvanceEntregas5($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(5);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }
    private static function AvanceEntregas6($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(6);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ConsolidadoEntregas($parametros) {
        $query = "call sp_consolidado_entregas();";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function RankingFinal($parametros) {
        $query = "call sp_reporte_ranking_anual();";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function SupervisorLider($parametros) {
        $query = Consultas::$reporte_supervisor_lider;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function CuposAlmacen($parametros) {
        $query = Consultas::$reporte_cupos_almacen;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VentasDistribuidora($parametros) {
        $query = Consultas::$reporte_ventas_distribuidora;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    /* private static function VentasSku($parametros)
      {
      $query = Consultas::$reporte_ventas_sku;
      $results = clsDDBBOperations::ExecuteSelectNoParams($query);
      return $results;
      } */

    private static function CuotasSupervisor1($parametros) {
        $query = Consultas::$reporte_cuotas_supervisor . " where cuo.id_temporada = 1 group by cuo.id_afiliado,cuo.id_temporada";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function CuotasSupervisor2($parametros) {
        $query = Consultas::$reporte_cuotas_supervisor . " where cuo.id_temporada = 2 group by cuo.id_afiliado,cuo.id_temporada";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function CuotasSupervisor3($parametros) {
        $query = Consultas::$reporte_cuotas_supervisor . " where cuo.id_temporada = 3 group by cuo.id_afiliado,cuo.id_temporada";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function CuotasSupervisor4($parametros) {
        $query = Consultas::$reporte_cuotas_supervisor . " where cuo.id_temporada = 4 group by cuo.id_afiliado,cuo.id_temporada";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function CuotasSupervisor5($parametros) {
        $query = Consultas::$reporte_cuotas_supervisor . " where cuo.id_temporada = 5 group by cuo.id_afiliado,cuo.id_temporada";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function CuotasSupervisor6($parametros) {
        $query = Consultas::$reporte_cuotas_supervisor . " where cuo.id_temporada = 6 group by cuo.id_afiliado,cuo.id_temporada";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VentasPrimerBimestre2021($parametros) {
        $query = "call sp_reporte_ventas_2021(1);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VentasSegundoBimestre2021($parametros) {
        $query = "call sp_reporte_ventas_2021(2);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VentasTercerBimestre2021($parametros) {
        $query = "call sp_reporte_ventas_2021(3);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function VentasCuartoBimestre2021($parametros) {
        $query = "call sp_reporte_ventas_2021(4);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }   
    
    private static function VentasQuintoBimestre2021($parametros) {
        $query = "call sp_reporte_ventas_2021(5);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ReporteEstructuraSupervisores($parametros) {
        $query = Consultas::$reporte_estructura_supervisores;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function Indicadores($parametros) {
        $query = "call sp_reporte_avance_entregas_temporada(2);";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $results;
    }

    private static function ObtenerIndicadoresTerritorio($parametros) {
        $query = "call sp_indicadores_territorios( " . $parametros->id_portafolio . " , " . $parametros->id_categoria . " );";
        $territorios = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $territorios;
    }

    private static function ObtenerIndicadoresDistribuidora($parametros) {
        $query = "call sp_indicadores_distribuidoras( " . $parametros->id_portafolio . " , " . $parametros->id_categoria . " );";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

    private static function ObtenerIndicadoresRepresentantes($parametros) {
        $query = "call sp_indicadores_representantes( " . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , " . $parametros->id_periodo . ");";
        $indicadores = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $indicadores;
    }

    private static function ObtenerIndicadoresVentas($parametros) {
        $query = "call sp_indicadores_ventas_general( " . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , " . $parametros->id_periodo . ");";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

    private static function ObtenerIndicadoresRankingProductos($parametros) {
        $query = "call sp_indicadores_ranking_productos( " . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , " . $parametros->id_periodo . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresCrecimiento($parametros) {
        $query = "call sp_indicadores_crecimiento(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , " . $parametros->id_periodo . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresCumplimiento($parametros) {
        $query = "call sp_indicadores_cuotas_general(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , " . $parametros->id_periodo . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresRepresentantesCheckbox($parametros) {
        $query = "call sp_indicadores_representantes_checkbox( " . "'" . $parametros->hash . "'" . ");";
        $indicadores = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $indicadores;
    }

    private static function ObtenerIndicadoresVentasCheckbox($parametros) {
        $query = "call sp_indicadores_ventas_general_checkbox( '" . $parametros->hash . "');";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);
        
        return $distribuidora;
    }

    private static function ObtenerIndicadoresRankingProductosCheckbox($parametros) {
        $query = "call sp_indicadores_ranking_productos_checkbox( " . "'" . $parametros->hash . "'" . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresCrecimientoCheckbox($parametros) {
        $query = "call sp_indicadores_crecimiento_checkbox( " . "'" . $parametros->hash . "'" . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresCumplimientoCheckbox($parametros) {
        $query = "call sp_indicadores_cuotas_general_checkbox('" . $parametros->hash . "');";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresCupos($parametros) {
        $query = "call sp_indicadores_cupos( " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_almacen . " , " . $parametros->id_temporada . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerIndicadoresCuposTerritorio($parametros) {
        $query = "call sp_indicadores_cupos_territorio( " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_almacen . " , " . $parametros->id_temporada . ");";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $result;
    }

    private static function ObtenerReportecuotasActualizadas($parametros) {
        $query = Consultas::$reporte_cuotas_actualizadas;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $results;
    }

    private static function ObtenerReporteCuotasVendedor($parametros) {
        $query = "call sp_reporte_cuotas_vendedores()";
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $results;
    }    

    private static function ObtenerIndicadoresVentasPortafolio($parametros) {
        $query = "call sp_indicadores_ventas_categorias_nuevo(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , '" . $parametros->hash . "');";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);
        return $result;
    }

    private static function ObtenerIndicadoresVentasNuevo($parametros) {
        $query = "call sp_indicadores_ventas_general_nuevo(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , '" . $parametros->hash . "');";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

    private static function ObtenerIndicadoresRepresentanteNuevo($parametros) {
        $query = "call sp_indicadores_representantes_nuevo(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , '" . $parametros->hash . "');";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

    private static function ObtenerIndicadoresRankingProductosNuevo($parametros) {
        $query = "call sp_indicadores_ranking_productos_nuevo(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , '" . $parametros->hash . "');";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

    private static function ObtenerIndicadoresCrecimientosNuevo($parametros) {
        $query = "call sp_indicadores_crecimiento_nuevo(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , '" . $parametros->hash . "');";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

    private static function ObtenerIndicadoresCumplimientoNuevo($parametros) {
        $query = "call sp_indicadores_cuotas_general_nuevo(" . $parametros->id_portafolio . " , " . $parametros->id_categoria . " , " . $parametros->id_marca . " , " . $parametros->id_sub_marca . " , " . $parametros->id_producto . " , " . $parametros->id_territorio . " , " . $parametros->id_representante . " , " . $parametros->id_madre . " , " . $parametros->id_distribuidora . " , '" . $parametros->hash . "');";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }    

    private static function ReporteHabeasData($parametros) {
        $query = Consultas::$reporte_habeas_data;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $results;
    }

    private static function ReporteDistribuidoraSinHabeasData($parametros) {
        $query = Consultas::$reporte_distribuidordas_sin_habeas_data;
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $results;
    }

    private static function ReporteLogCambios($parametros) {
        $query = "CALL sp_log_usuarios(0);";
        $distribuidora = clsDDBBOperations::ExecuteSelectNoParams($query);

        return $distribuidora;
    }

}

?>