drop procedure if exists sp_registrar_operacion_redencion_2022;

DELIMITER //

create procedure sp_registrar_operacion_redencion_2022(id_redencion_p int, id_operacion_p int, comentario_p varchar(50),id_registra_p int)
begin

	set @ejecuta_cambio = 1;
	SET @diferencia_dias = 0;
	
	set @diferencia_dias = (select DATEDIFF(now(),fecha) diferencia from redenciones where id = id_redencion_p);
	
	if (id_operacion_p = 5 and @diferencia_dias > 1) and id_registra_p in (12) then
		
		set @id_periodo = (SELECT max(id) FROM periodo WHERE now() between fecha_inicio and fecha_final);
		set @id_usuario = (SELECT max(id_usuario) FROM redenciones WHERE id = id_redencion_p);
		set @puntos = (SELECT max(puntos) FROM redenciones WHERE id = id_redencion_p);
		set @id_premio = (SELECT max(id_premio) FROM redenciones WHERE id = id_redencion_p);
		set @descripcion_ecu = (SELECT concat('Cancelación redención ',nombre) FROM premios WHERE id = @id_premio);
		
		insert into estado_cuenta(id_periodo,id_concepto,id_usuario,puntos,descripcion,fecha)
		values (@id_periodo,5,@id_usuario,@puntos ,@descripcion_ecu,now());
	
	elseif id_operacion_p = 5 and @diferencia_dias > 1 then
		set @ejecuta_cambio = 0;
	elseif id_operacion_p = 5 and @diferencia_dias <= 1 then
		
		set @id_periodo = (SELECT max(id) FROM periodo WHERE now() between fecha_inicio and fecha_final);
		set @id_usuario = (SELECT max(id_usuario) FROM redenciones WHERE id = id_redencion_p);
		set @puntos = (SELECT max(puntos) FROM redenciones WHERE id = id_redencion_p);
		set @id_premio = (SELECT max(id_premio) FROM redenciones WHERE id = id_redencion_p);
		set @descripcion_ecu = (SELECT concat('Cancelación redención ',nombre) FROM premios WHERE id = @id_premio);
		
		insert into estado_cuenta(id_periodo,id_concepto,id_usuario,puntos,descripcion,fecha)
		values (@id_periodo,5,@id_usuario,@puntos ,@descripcion_ecu,now());
		
		set @ejecuta_cambio = 1;
	else
		set @ejecuta_cambio = 1;
	end if;

	if @ejecuta_cambio = 1 then
		INSERT INTO seguimiento_redencion (id_redencion, id_operacion, comentario, fecha_operacion, id_usuario) 
		VALUES (id_redencion_p, id_operacion_p, comentario_p, now(), id_registra_p);

		call sp_obtener_redencion_2022(id_redencion_p);
	else
		
		select 'No se puede cancelar el premio pasadas 24 horas' error;
		
	end if;
		
end //

delimiter ;


call sp_registrar_operacion_redencion_2022(51,2,'hecho',12);
