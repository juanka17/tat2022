set @id_periodo = 18;
set @cargar =0;

	DROP TEMPORARY TABLE IF EXISTS t_cuotas;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas AS (
	
		SELECT
				par.id_periodo,
				par.id_almacen,
				par.almacen,	
				ven.id_supervisor,			
				par.id_vendedor,
				par.vendedor,
				par.venta_almacen,
				par.venta_ultimo_q_vendedor,
				par.venta_vendedor,
				par.porcentaje_participacion,
				par.cuota_almacen,
				par.cuota_sin_aumento,
				par.cuota_vendedor,
				sup.NOMBRE supervisor
			FROM 
				t_participacion_vendedores par
				left JOIN vendedores_supervisor ven ON ven.id_vendedor = par.id_vendedor AND ven.id_periodo = @id_periodo
				left JOIN afiliados sup ON sup.ID = ven.id_supervisor
			WHERE 
				par.id_periodo = @id_periodo
			ORDER BY 
				porcentaje_participacion desc
			
	);

	DROP TEMPORARY TABLE IF EXISTS t_ventas;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas AS (
	
		select
			ven.id_periodo,
			ven.id_vendedor,
			afi.nombre,
			afi.id_almacen,		
			afi.cod_formas,
			sum(ven.valor) ventas
		from 
			ventas ven
			inner join afiliados afi on afi.id = ven.id_vendedor
		where
			ven.id_periodo = @id_periodo
		group by 
			ven.id_vendedor	
			
			
	);

	DROP TEMPORARY TABLE IF EXISTS t_puntos_venta_advil;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_puntos_venta_advil AS (
	
		select
			per.id id_periodo,
			per.nombre periodo,
			ven.id_vendedor,
			sum(ven.valor) venta_especial,
			pro.id id_producto,
			pro.nombre
		from ventas ven 
			inner join periodo per on per.id = ven.id_periodo
			INNER JOIN productos pro ON pro.id = ven.id_producto
		where
			per.id = @id_periodo
			AND pro.especial = 1
		GROUP BY 
			ven.id_vendedor
	);

DROP TEMPORARY TABLE IF EXISTS t_puntos_venta;
CREATE TEMPORARY TABLE IF NOT EXISTS t_puntos_venta AS (

	select
		per.id id_periodo,
		per.nombre periodo,
		ven.id_vendedor,
		ven.cod_formas,
		ven.nombre vendedor,
		ven.id_almacen,
		cuo.id_vendedor id_usuario,
		cuo.cuota_vendedor cuota,
		ven.ventas		
	from
		t_ventas ven 		
		left JOIN t_cuotas cuo ON ven.id_vendedor = cuo.id_vendedor 
		inner join periodo per on per.id = ven.id_periodo
	where
		per.id = @id_periodo
		
);

DROP TEMPORARY TABLE IF EXISTS t_consolidad_ventas;
CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidad_ventas AS (

	SELECT
		ven.id_periodo,
		ven.periodo,
		ven.id_vendedor,
		ven.cod_formas,
		ven.vendedor,
		ven.id_almacen,
		ven.id_usuario,
		ven.cuota,
		ven.ventas,
		esp.venta_especial,
		(ven.ventas-esp.venta_especial) diferencia,
		ROUND((ven.ventas/ven.cuota)*100) cumplimiento,
		if(ROUND((ven.ventas/ven.cuota)*100)>=100, ROUND((ven.ventas-esp.venta_especial)/25000), 0)puntos_venta,
		if(ROUND((ven.ventas/ven.cuota)*100)>=100, ROUND((esp.venta_especial/25000)*2), 0)puntos_especial
	FROM t_puntos_venta ven
	left JOIN t_puntos_venta_advil esp ON ven.id_usuario = esp.id_vendedor
		
);


		
	/*
DROP TEMPORARY TABLE IF EXISTS t_puntos_impactos;
CREATE TEMPORARY TABLE IF NOT EXISTS t_puntos_impactos AS (
	select
		per.id id_periodo,
		per.nombre periodo,
		imp.id_afiliado,
		cuo.impactos cuota_impactos,	
		imp.impactos,
		ROUND((imp.impactos/cuo.impactos)*100) cumplimiento_impactos,
		if(ROUND((imp.impactos/cuo.impactos)*100)>=100, ROUND((imp.impactos/1)*6), 0)puntos_impactos
	from
		impactos imp
		INNER JOIN t_cuotas cuo ON cuo.id_usuario = imp.id_afiliado
		inner join periodo per on per.id = imp.id_periodo
	where
		imp.id_periodo = @id_periodo
);*/

DROP TEMPORARY TABLE IF EXISTS t_consolidado;
CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (

	select
		ven.id_periodo,
		per.id_temporada,
		ven.periodo,
		alm.nombre almacen,
		alm.id id_almacen,
		ven.cod_formas,
		ven.id_vendedor,
		ven.vendedor,
		ven.ventas venta,
		ifnull(ven.venta_especial,0) venta_especial,
		ven.diferencia,
		ifnull(ven.cuota,0) cuota,
		ifnull(ven.cumplimiento,0) cumplimiento,
		ifnull(ven.puntos_venta,0) puntos_venta,
		ifnull(ven.puntos_especial,0) puntos_especial,
		(puntos_venta+ifnull(ven.puntos_especial,0)) total_puntos_venta,
		(puntos_venta+ifnull(ven.puntos_especial,0)) total_puntos
	from 
		t_consolidad_ventas ven	
		left JOIN almacenes alm on alm.id = ven.id_almacen
		left JOIN periodo per ON per.id = ven.id_periodo
	WHERE
	ven.id_vendedor NOT IN (SELECT id_vendedor FROM t_estado_cuenta WHERE id_periodo = @id_periodo AND id_concepto = 1)
);



insert into t_estado_cuenta(id_periodo,id_almacen,id_concepto,id_vendedor,venta,venta_especial,cuota,cumplimiento,puntos_venta,puntos_especial,total_puntos_venta,total_puntos)
select
	id_periodo,
	id_almacen,
	1,
	id_vendedor,
	venta,
	venta_especial,
	cuota,
	cumplimiento,
	puntos_venta,
	puntos_especial,
	total_puntos_venta,
	total_puntos
from
	t_consolidado
where
	@cargar = 1 ;

select * from t_consolidado;

DROP TEMPORARY TABLE t_cuotas;
DROP TEMPORARY TABLE t_ventas;
DROP TEMPORARY TABLE t_puntos_venta_advil;
DROP TEMPORARY TABLE t_puntos_venta;
DROP TEMPORARY TABLE t_consolidad_ventas;