drop procedure if exists sp_obtener_redencion_2022;

DELIMITER //

create procedure sp_obtener_redencion_2022(id_redencion_p int)
begin

	select
		red.id folio,
		pre.id id_premio,
		pre.nombre premio,
		red.fecha fecha_redencion,
		seg.id id_seguimiento,
		ope.nombre operacion,
		seg.fecha_operacion,
		red.puntos,
		red.id_estado_cuenta,
		'' error
	from 
		redenciones red
		inner join premios pre on pre.id = red.id_premio
		inner join seguimiento_redencion seg on seg.id = red.id_ultima_operacion
		inner join operaciones_redencion ope on ope.id = seg.id_operacion
	where
		red.id = id_redencion_p;
		
end //

delimiter ;

call sp_obtener_redencion_2022(51);