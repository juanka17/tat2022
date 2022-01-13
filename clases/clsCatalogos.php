<?php

include_once('clsDDBBOperations.php');
include_once('consultas.php');

class clsCatalogos {

    public static function EjecutarOperacion($operacion, $parametros) {
        switch ($operacion) {
            case "CargaCatalogo": return clsCatalogos::EjecutarConsulta($parametros);
                break;
            case "RegistraCatalogoSimple": return clsCatalogos::EjecutarInsercion($parametros);
                break;
            case "RegistraCatalogoMixto": return clsCatalogos::EjecutarInsercionMixta($parametros);
                break;
            case "RegistraCatalogoMixtoMasivo": return clsCatalogos::EjecutarInsercionMixtaMasiva($parametros);
                break;
            case "RegistraCatalogoDesdeArrayJSON": return clsCatalogos::EjecutarInsercionDesdeArrayJSON($parametros);
                break;
            case "ModificaCatalogoSimple": return clsCatalogos::EjecutarModificacion($parametros);
                break;
            case "ModificaCatalogoMixto": return clsCatalogos::EjecutarModificacionMixta($parametros);
                break;
            case "EliminaCatalogoSimple": return clsCatalogos::EjecutarEliminacion($parametros);
                break;
        }
    }

    private static function EjecutarConsulta($parametros) {
        $query = "SELECT * FROM " . $parametros->catalogo;
        $order = " ORDER BY 2";
        switch ($parametros->catalogo) {
            case "premios": {
                    $query = Consultas::$consulta_premios;
                    $order = " ORDER BY cap.id ";
                };
                break;
            case "afiliados": {
                    $query = Consultas::$datos_afiliado . " where afi.id = " . $parametros->id;
                };
                break;
            case "grafica_usuario_ecu": {
                    $query = str_replace("_id_usuario_", $parametros->id_usuario, Consultas::$grafica_usuario_ecu);
                    $order = "";
                };
                break;
            case "estado_cuenta_afiliado": {
                    $query = Consultas::$estado_cuenta_afiliado . " where e.id_usuario = " . $parametros->id_usuario;
                    $order = " ";
                };
                break;
            case "ventas_usuario": {
                    $query = "call sp_ventas_usuario_totales(" . $parametros->id_usuario . ",0);";
                    $order = "";
                };
                break;
            case "ciudad": {
                    $query = $query . " where id_departamento = " . $parametros->departamento;
                };
                break;
            case "familiares_afiliado": {
                    $query = Consultas::$consulta_familiares . " where id_afiliado = " . $parametros->id_afiliado;
                };
                break;
            case "intereses_afiliado": {
                    $query = Consultas::$consulta_intereses . " where id_afiliado = " . $parametros->id_afiliado;
                };
                break;
            case "llamadas_afiliado": {
                    $query = Consultas::$consulta_llamadas_afiliado . " where id_afiliado = " . $parametros->id_afiliado;
                    $order = " ORDER BY 2 DESC";
                };
                break;
            case "llamadas_almacen": {
                    $query = Consultas::$consulta_llamadas_almacen . " where la.id_almacen = " . $parametros->id_almacen;
                    $order = " ORDER BY 2 DESC";
                };
                break;
            case "almacen": {
                    $query = $query . " where visible = 1";
                };
                break;
            case "redenciones_almacen": {
                    $query = " call sp_informacion_redenciones_almacen(" . $parametros->id_almacen . "); ";
                    $order = " ";
                };
                break;
            case "puntos_empleados_almacen": {
                    $query = Consultas::$puntos_empleados_almacen . " where alm.id = " . $parametros->id_almacen;
                    $order = " order by afi.nombre, per.id ";
                };
                break;
            case "redenciones_empleados_almacen": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$redenciones_empleados_almacen);
                    $order = " order by afi.nombre ";
                };
                break;
            case "almacen_informacion": {
                    $query = Consultas::$consulta_droguerias . " where alm.id = " . $parametros->id_almacen . " and alm.estado = 1";
                    $order = " order by alm.nombre ";
                };
                break;
            case "almacenes_visitador": {
                    $query = Consultas::$consulta_droguerias . " where vis.id  = " . $parametros->id_afiliado . " and alm.estado = 1";
                    $order = " ";
                };
                break;
            case "almacenes_gerente": {
                    $query = Consultas::$consulta_droguerias . " where ger.id = " . $parametros->id_afiliado . " and alm.estado = 1";
                    $order = " order by drogueria ";
                };
                break;
            case "almacenes_asistente": {
                    $query = Consultas::$consulta_droguerias . " where ter.id = " . $parametros->id_territorio . " and alm.estado = 1";
                    $order = " order by drogueria ";
                };
                break;
            case "almacenes_global": {
                    $query = Consultas::$consulta_droguerias . " where vis.id <> 0 and alm.estado = 1";
                    $order = " order by drogueria ";
                };
                break;
            case "ventas_vendedor": {
                    $query = Consultas::$consulta_ventas . " where afi.id = " . $parametros->id_afiliado . " ";
                    $order = " order by per.id desc, cat.nombre ";
                };
                break;
            case "ventas_vendedor_periodo": {
                    $query = Consultas::$consulta_ventas . " where afi.id = " . $parametros->id_afiliado . " and ven.id_periodo = " . $parametros->id_periodo . " ";
                    $order = " order by cat.nombre ";
                };
                break;
            case "estado_cuenta_vendedores_almacen": {
                    $query = Consultas::$consulta_estado_cuenta;
                    $query = $query . " where est.id_almacen = " . $parametros->id_almacen;
                    $order = " GROUP BY est.id_vendedor ORDER BY venta desc";
                };
                break;
            case "estado_cuenta_vendedor_detallado": {
                    $query = Consultas::$consulta_estado_cuenta__detallado;
                    $query = $query . " where est.id_vendedor = " . $parametros->id_vendedor;
                    $order = " group by per.id";
                };
                break;
            case "datos_redencion": {
                    $query = " call sp_datos_redencion(" . $parametros->id_redencion . ")";
                    $order = " ";
                };
                break;
            case "consulta_ganadores_ciclo1": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$consulta_ganadores_ciclo1);
                    $order = "  ";
                };
                break;
            case "consulta_ganadores_ciclo2": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$consulta_ganadores_ciclo2);
                    $order = "  ";
                };
                break;
            case "consulta_ganadores_ciclo3": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$consulta_ganadores_ciclo3);
                    $order = "  ";
                };
                break;
            case "consulta_ganadores_ciclo4": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$consulta_ganadores_ciclo4);
                    $order = "  ";
                };
                break;
            case "consulta_ganadores_ciclo5": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$consulta_ganadores_ciclo5);
                    $order = "  ";
                };
                break;
            case "consulta_cuotas_supervisor": {
                    $query = " call sp_cuotas_supervisores(" . $parametros->id_almacen . ")";
                    $order = " ";
                };
                break;
            case "consulta_cuotas_vendedor": {
                    $query = " call sp_cuotas_vendedores_supervisor(" . $parametros->id_almacen . ")";
                    $order = " ";
                };
                break;
            case "consulta_cuotas_vendedor_supervisor": {
                    $query = " call sp_cuotas_vendedores(" . $parametros->id_almacen . ")";
                    $order = " ";
                };
                break;
            case "obtener_periodo": {
                    $query = "select * from periodo where now() between inicio and final;";
                    $order = "  ";
                };
                break;
            case "lista_visitadores": {
                    $query = "select id,nombre from afiliados where id_clasificacion = 3";
                    $order = " order by nombre ";
                };
                break;
            case "lista_vendedores": {
                    $query = Consultas::$consulta_vendedores;
                    $order = " order by nombre ";
                };
                break;
            case "lista_promotores": {
                    $query = Consultas::$consulta_promotores;
                    $order = " order by nombre ";
                };
                break;
            case "almacenes_promotor": {
                    $query = Consultas::$consulta_almacenes_promotor . " where pro.id_afiliados = " . $parametros->id_promotor;
                    $order = " order by nombre ";
                };
                break;
            case "datos_redencion_almacen_estado_temporada": {
                    $query = " call sp_redenciones_almacen_estado_temporada(" . $parametros->id_estado . "," . $parametros->id_almacen . "," . $parametros->id_temporada . ")";
                    $order = " ";
                };
                break;
            case "datos_redencion_almacen_estado": {
                    $query = " call sp_redenciones_almacen_estado(" . $parametros->id_estado . "," . $parametros->id_almacen . ")";
                    $order = " ";
                };
                break;
            case "redencion_individual": {
                    $query = " call sp_datos_redencion(" . $parametros->folio . ") ";
                    $order = "";
                };
                break;
            case "documento_legalizacion": {
                    $query = Consultas::$consulta_acta . " where red.id = " . $parametros->id_redencion . "; ";
                    $order = " ";
                };
                break;
            case "redenciones_asistente": {
                    $query = " call sp_redenciones_asistente(" . $parametros->id_afiliado . ") ";
                    $order = "";
                };
                break;
            case "actas_disponibles": {
                    $query1 = str_replace("_id_temporada_", $parametros->id_temporada, Consultas::$consulta_actas);
                    $query = str_replace("_id_ejecutivo_", $parametros->id_ejecutivo, $query1);
                    $order = " order by asi.nombre,eje.nombre,alm.nombre ";
                };
                break;
            case "redenciones_solicitadas": {
                    $query = " call sp_informacion_redenciones_por_estado(1) ";
                    $order = "";
                };
                break;
            case "temporada": {
                    $query = Consultas::$consulta_temporada;
                    $order = "";
                };
                break;
            case "temporada_redenciones": {
                    $query = Consultas::$consulta_temporada_redenciones;
                    $order = "";
                };
                break;
            case "consulta_ganadores_anual": {
                    $query = " call sp_ranking_anual(" . $parametros->id_almacen . "); ";
                    $order = "";
                };
                break;
            case "cupos_ranking_anual": {
                    $query = $query . " where id_almacen = " . $parametros->id_almacen;
                    $order = " order by id ";
                };
                break;
            case "redimir_entrega_anual": {
                    $query = " call sp_redimir_entrega_anual('.$parametros->id_afiliado.','.$parametros->id_registra.','.$parametros->puesto.'); ";
                    $order = " ";
                };
                break;
            case "periodos_ventas_almacen": {
                    $query = str_replace("_id_almacen_", $parametros->id_almacen, Consultas::$periodos_ventas_almacen);
                    $order = " order by tem.id desc ";
                };
                break;
            case "cargar_ranking_vendedores_almacen_temporada": {
                    $query = " call sp_ganadores_ciclo(" . $parametros->id_temporada . "," . $parametros->id_almacen . "); ";
                    $order = " ";
                };
                break;
            case "vendedor_perfecto_otros": {
                    $query = " call sp_vendedor_perfecto_global_otros(" . $parametros->id_almacen . "); ";
                    $order = " ";
                };
                break;
            case "estado_cuenta": {
                    $query = " select * from estado_cuenta where id = " . $parametros->id;
                    $order = " ";
                };
                break;
            case "ejecutivo": {
                    $query = " select * from afiliados where id_clasificacion = 3";
                    $order = " order by 2";
                };
                break;
            case "temporada_total": {
                    $query = " select * from temporada where id IN (19,20,21,22,23,24,25,107,108)";
                    $order = " order by 1";
                };
                break;

            case "sub_reportes": {
                    $query = " select * from sub_reportes where id_reporte =" . $parametros->id_reporte;
                    $order = " order by 1";
                };
                break;

            case "denegar_solicitud_premio": {
                    $query = Consultas::$denegar_solicitud_premio . " where red.id_afiliado = " . $parametros->id_afiliado . " and red.id_almacen = " . $parametros->id_almacen . " and red.temporada = " . $parametros->id_temporada . " and pre.NAVIDAD != 1";
                    $order = " ";
                };
                break;

            case "habeas_data": {
                    $query = "select * from habeas_data where id_almacen = " . $parametros->id_almacen;
                    $order = " ";
                };
                break;

            case "cuotas_simulador": {
                    $query = Consultas::$cuotas_simulador . " where ven.id_supervisor = " . $parametros->id_usuario . " and ven.id_temporada = " . $parametros->id_temporada . " GROUP BY id_temporada";
                    $order = " ";
                };
                break;

            case "cuotas_simulador_vendedor": {
                    $query = Consultas::$cuotas_simulador_vendedor . " where ven.id_supervisor = " . $parametros->id_usuario . " and cuo.id_temporada = " . $parametros->id_temporada;
                    $order = " ";
                };
                break;
            case "supervisores_almacen": {
                    $query = "SELECT id,nombre FROM afiliados WHERE id_almacen = " . $parametros->id_almacen . " AND id_clasificacion = 4 AND acepto_terminos = 0";
                    $order = " ";
                };
                break;
            case "temporada_actual": {
                    $query = "SELECT * FROM periodo where id_temporada IN (SELECT id_temporada from periodo WHERE now() between inicio and final)";
                    $order = " ";
                };
                break;
            case "obtener_cod_formas": {
                    $query = "SELECT MAX(id)cod_formas FROM afiliados WHERE id_clasificacion = 6";
                    $order = " ";
                };
                break;
            case "vendedores_almacen": {
                    $query = "SELECT afi.id,afi.nombre,cat.nombre categoria FROM afiliados afi inner join categorias cat on cat.id = afi.id_categoria WHERE id_almacen = " . $parametros->id_almacen . " AND id_clasificacion = 6 AND id_estatus != 5";
                    $order = " ";
                };
                break;
            case "cupos_almacenes_temporada": {
                    $query = "SELECT * FROM cupos_almacenes WHERE id_almacen = " . $parametros->id_almacen . " AND id_temporada = 5";
                    $order = " ";
                };
                break;

            case "cupos_almacenes": {
                    $query = consultas::$consulta_cupos_almacenes_temporadas . "where id_almacen = " . $parametros->id_almacen;
                    $order = " ";
                };
                break;

            case "clasificacion_afiliados_temporada": {
                    $query = consultas::$clasificacion_afiliados_temporada . "where id_afiliado = " . $parametros->id_afiliado;
                    $order = " ";
                };
                break;

            case "visitadores_almacen": {
                    $query = "SELECT id,nombre FROM afiliados WHERE id IN (SELECT id_visitador FROM almacenes)";
                    $order = " ";
                };
                break;

            case "categorias": {
                    $query = "SELECT * FROM categorias";
                    $order = " order by 1 ";
                };
                break;

            case "temporadas_creacion_usuario": {
                    $query = "SELECT * FROM temporada";
                    $order = " order by 1 ";
                };
                break;

            case "periodo_actual": {
                    $query = "SELECT * FROM periodo where now() between inicio and final";
                    $order = " order by 1 ";
                };
                break;
            
            case "representantes": {
                $query = "SELECT id,nombre FROM afiliados WHERE id IN (SELECT id_visitador FROM almacenes)";
                $order = " ";
                }; break;
        }
        $query = $query . $order;

        return clsDDBBOperations::ExecuteSelectNoParams($query);
    }

    private static function EjecutarInsercion($parametros) {
        $result = clsDDBBOperations::ExecuteInsert((array) $parametros->datos, $parametros->catalogo);
        return clsCatalogos::EjecutarConsulta($parametros);
    }

    private static function EjecutarInsercionDesdeArrayJSON($parametros) {

        $valores = [];
        foreach ($parametros->datos as $filtro) {
            $temp_filtro = [];
            foreach ($filtro as $key => $valor) {
                $temp_filtro[$key] = $valor;
            }
            $valores[] = $temp_filtro;
        }

        $result = clsDDBBOperations::ExecuteInsert($valores, $parametros->catalogo);
        return clsCatalogos::EjecutarConsulta($parametros);
    }

    private static function EjecutarInsercionMixta($parametros) {
        $result = clsDDBBOperations::ExecuteInsert((array) $parametros->datos, $parametros->catalogo_real);
        return clsCatalogos::EjecutarConsulta($parametros);
    }

    private static function EjecutarInsercionMixtaMasiva($parametros) {
        foreach ($parametros->lista_datos as $datos) {
            $result = clsDDBBOperations::ExecuteInsert((array) $datos, $parametros->catalogo_real);
        }

        return clsCatalogos::EjecutarConsulta($parametros);
    }

    private static function EjecutarModificacion($parametros) {
        clsDDBBOperations::ExecuteUpdate((array) $parametros->datos, $parametros->catalogo, $parametros->id);
        return clsCatalogos::EjecutarConsulta($parametros);
    }

    private static function EjecutarModificacionMixta($parametros) {
        clsDDBBOperations::ExecuteUpdate((array) $parametros->datos, $parametros->catalogo_real, $parametros->id);
        return clsCatalogos::EjecutarConsulta($parametros);
    }

    private static function EjecutarEliminacion($parametros) {
        clsDDBBOperations::ExecuteDelete($parametros->catalogo, $parametros->identificador);
        return clsCatalogos::EjecutarConsulta($parametros);
    }

}
