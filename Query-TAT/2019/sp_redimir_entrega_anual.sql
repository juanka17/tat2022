DROP PROCEDURE IF EXISTS sp_redimir_entrega_anual;
DELIMITER //
CREATE PROCEDURE sp_redimir_entrega_anual(id_afiliado_p int, id_registra_p int, puesto_p int)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	/*
	set @id_afiliado = 3619;
	set @id_registra = 3900;
	set @puesto = 2;
	*/
	
	set @id_afiliado = id_afiliado_p;
	set @id_registra = id_registra_p;
	set @puesto = puesto_p;
	
	set @id_almacen = (select max(id_almacen) from afiliados where id = @id_afiliado);
	
	insert into redenciones (id_afiliado,id_premio,id_almacen,temporada,puntos,fecha_redencion,id_registra)
	select
		afi.id id_afiliado,
		pre.id id_premio,
		alm.id id_almacen,
		109 temporada,
		0 puntos,
		now() fecha_redencion,
		@id_registra id_registra
	from
		cupos_ranking_anual cra
		inner join almacenes alm on alm.id = cra.id_almacen
		inner join afiliados afi on afi.id_almacen = alm.id
		inner join premios pre on 
			(@puesto = 1 and find_in_set(pre.id, cast(cra.primero as char))) or
			(@puesto = 2 and find_in_set(pre.id, cast(cra.segundo as char))) or
			(@puesto = 3 and find_in_set(pre.id, cast(cra.tercero as char))) or
			(@puesto = 4 and find_in_set(pre.id, cast(cra.cuarto as char)))
	where
		afi.id = @id_afiliado;
	
	insert into seguimiento_redencion (id_redencion,id_operacion,fecha_operacion,id_usuario)
	select id id_redencion,1 id_operacion,now() fecha_operacion,@id_registra id_usuario
	from redenciones where temporada = 109 and id_afiliado = @id_afiliado;
	
	call sp_ranking_anual(@id_almacen);
	
	/*
	select * from redenciones where temporada = 106;
	select * from seguimiento_redencion where id_redencion in (
		select id from redenciones where temporada = 106
	);
	
	delete from seguimiento_redencion where id_redencion in (
		select id from redenciones where temporada = 106
	);
	delete from redenciones where temporada = 106;
	*/

END//
DELIMITER ;

/*
call sp_redimir_entrega_anual(2529,3900,1);
*/