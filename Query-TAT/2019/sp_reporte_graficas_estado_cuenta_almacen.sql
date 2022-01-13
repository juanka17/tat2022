DROP PROCEDURE IF EXISTS sp_reporte_graficas_estado_cuenta_almacen;

DELIMITER //

CREATE PROCEDURE sp_reporte_graficas_estado_cuenta_almacen(IN id_almacen_p INT, IN id_temporada_p INT, IN id_territorio_p INT, IN id_representante_p INT)
BEGIN

	/*
	set @id_almacen = 1768;
	*/
	set @id_almacen = id_almacen_p;
	set @id_temporada = id_temporada_p;
	set @id_territorio = id_territorio_p;
	set @id_representante = id_representante_p;

	
	SELECT tcr.*,
		ROUND(((solicitado*100)/encuestas_periodo))solicitado_x_100,
		ROUND(((confirmado*100)/encuestas_periodo))confirmado_x_100,
		ROUND(((aprobado*100)/encuestas_periodo))aprobado_x_100,
		ROUND(((procesado*100)/encuestas_periodo))procesado_x_100,
		ROUND(((legalizado*100)/encuestas_periodo))legalizado_x_100,
		encuestas_periodo - (legalizado + aprobado + solicitado + confirmado + procesado) pendientes_por_solicitar,
		ROUND(((encuestas_periodo - (legalizado + aprobado + solicitado + confirmado + procesado))*100)/encuestas_periodo) pendientes_por_solicitar_x_100
		FROM (SELECT alm.id id_almacen,
         alm.nombre almacen,
         ter.id id_territorio,
         ter.nombre territorio,
         usu.ID id_usuario,
         usu.nombre visitador,
         tem.id id_temporada,
         tem.nombre temporada,
         (alm.encuestas_periodo + alm.supervisores) encuestas_periodo,
         sum(CASE WHEN opr.id = 1 THEN 1 ELSE 0 END) solicitado,
         sum(CASE WHEN opr.id = 2 THEN 1 ELSE 0 END) confirmado,
         sum(CASE WHEN opr.id = 3 THEN 1 ELSE 0 END) aprobado,
         sum(CASE WHEN opr.id = 4 THEN 1 ELSE 0 END) procesado,
         sum(CASE WHEN opr.id = 5 THEN 1 ELSE 0 END) legalizado
    FROM redenciones red,
         almacenes alm,
         territorios ter,
         afiliados usu,
         temporada tem,
         seguimiento_redencion seg,
         operaciones_redencion opr
   WHERE red.id_almacen = alm.id
         AND ter.id = alm.id_territorio
         AND usu.ID = alm.id_visitador
         AND tem.id = red.temporada
         AND seg.id_redencion = red.id
         AND seg.id_operacion = opr.id
         AND seg.id IN (SELECT max(sr.id) FROM seguimiento_redencion sr WHERE sr.id_redencion = red.id)
GROUP BY alm.id, tem.id) tcr;
	
	
	

END;

call sp_reporte_graficas_estado_cuenta_almacen(1,19,1,1);