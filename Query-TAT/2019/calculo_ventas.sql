set @id_periodo = 3;
set @cargar = 0;

DROP TEMPORARY TABLE IF EXISTS t_puntos_ventas;
CREATE TEMPORARY TABLE IF NOT EXISTS t_puntos_ventas AS (

	select
		ven.id_periodo,
		ven.id_vendedor,
		afi.nombre,
		afi.cod_formas,
		sum(ven.valor) ventas,
		ifnull(des.descuento,0) descuento,
		case 
			when des.descuento is null then round(sum(ven.valor) / 1 )
			when des.descuento <= 0 then round(sum(ven.valor) / 1 )
			else round(sum(ven.valor) / des.descuento )
		end ventas_descuento,
		case 
			when des.descuento is null then round(( round(sum(ven.valor) / 1 ) * 0.3 ) / 1000)
			when des.descuento <= 0 then round(( round(sum(ven.valor) / 1 ) * 0.3 ) / 1000)
			else round(( round(sum(ven.valor) / des.descuento ) * 0.3 ) / 1000)
		end puntos
	from 
		ventas ven
		inner join afiliados afi on afi.id = ven.id_vendedor
		left join descuentos des on 
			des.id_almacen = afi.id_almacen 
			and (@id_periodo >= des.id_periodo_inicial and @id_periodo <= des.id_periodo_final )
	where
		ven.id_periodo = @id_periodo
	group by 
		ven.id_vendedor
		
);
	
DROP TEMPORARY TABLE IF EXISTS t_puntos_impactos;
CREATE TEMPORARY TABLE IF NOT EXISTS t_puntos_impactos AS (

	select
		per.id id_periodo,
		per.nombre periodo,
		imp.id_afiliado,
		imp.impactos,
		round(((imp.impactos * 0.7) * 10000) / 1000) puntos
	from
		impactos imp
		inner join periodo per on per.id = imp.id_periodo
	where
		imp.id_periodo = @id_periodo
		
);

DROP TEMPORARY TABLE IF EXISTS t_consolidado;
CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (

	select
		per.id id_periodo,
		per.nombre periodo,
		alm.nombre almacen,
		alm.id id_almacen,
		afi.id id_vendedor,
		afi.nombre vendedor,
		ven.ventas venta,
		ven.descuento,
		ven.ventas_descuento ,
		imp.impactos,
		ven.puntos puntos_venta,
		imp.puntos puntos_impacto
	from 
		t_puntos_ventas ven
		inner join afiliados afi on afi.id = ven.id_vendedor
		inner join periodo per on per.id = ven.id_periodo
		INNER JOIN afiliado_almacen afia ON afia.id_afiliado = afi.ID
		inner join almacenes alm on alm.id = afia.id_almacen
		left join t_puntos_impactos imp on imp.id_periodo = ven.id_periodo and imp.id_afiliado = ven.id_vendedor
);

insert into estado_cuenta(id_periodo,id_afiliado,id_concepto,venta,impactos,puntos_venta,puntos_impacto,descripcion,fecha)
select
	id_periodo,
	id_vendedor id_afiliado,
	1 id_concepto,
	ventas_descuento,
	ifnull(impactos,0) impactos,
	puntos_venta,
	puntos_impacto,
	'Puntos periodo' descripcion,
	now() fecha
from
	t_consolidado
where
	@cargar = 1
	GROUP BY id_vendedor;

select * from t_consolidado GROUP BY id_vendedor;
select * from t_puntos_ventas;
select * from t_puntos_impactos;

drop table t_puntos_ventas;
drop table t_puntos_impactos;