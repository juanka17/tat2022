delimiter //

set @id_usuario = 2;

drop function if exists obtener_saldo_actual//

create function obtener_saldo_actual(id_usuario_par int) returns int
BEGIN

	declare saldo_actual INT;
	
	set saldo_actual = (select sum(case when con.suma = 1 then ecu.total_puntos when con.suma = 0 then ecu.total_puntos * -1 end ) puntos
	from t_estado_cuenta ecu 
	INNER JOIN concepto_estado_cuenta con ON con.id = ecu.id_concepto
	where ecu.id_vendedor = id_usuario_par);
	
	return saldo_actual;
	
end//

delimiter ;