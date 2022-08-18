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
				round(if(cuota_sin_aumento=0,cuota_vendedor,cuota_sin_aumento)) cuota_def,
				par.cuota_impactos
			FROM 
				t_participacion_vendedores par
				left JOIN vendedores_supervisor ven ON ven.id_vendedor = par.id_vendedor AND ven.id_periodo = @id_periodo
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
			sum(ven.valor) ventas,
			SUM(ven.impactos) impactos
		from 
			ventas ven
			inner join afiliados afi on afi.id = ven.id_vendedor
		where
			ven.id_periodo = @id_periodo
		group by 
			ven.id_vendedor	
			
			
	);

DROP TEMPORARY TABLE IF EXISTS t_puntos_venta;
CREATE TEMPORARY TABLE IF NOT EXISTS t_puntos_venta AS (

	select
		per.id id_periodo,
		per.nombre periodo,
		ven.id_almacen,
		alma.nombre distribuidora,
		cuo.id_supervisor,
		afi.nombre supervisor,
		ifnull(SUM(cuo.cuota_impactos),0) cuota_impactos,
		SUM(ven.impactos) impactos,
		ifnull(ROUND((SUM(ven.impactos)/SUM(cuo.cuota_impactos))*100),0) cump_impactos,
		sum(cuo.cuota_vendedor) cuota_venta,
		SUM(ven.ventas) ventas,
		ifnull(ROUND((SUM(ven.ventas)/SUM(cuo.cuota_vendedor))*100),0) cump_ventas
	from
		t_ventas ven 		
		left JOIN t_cuotas cuo ON ven.id_vendedor = cuo.id_vendedor 
		inner join periodo per on per.id = ven.id_periodo
		LEFT JOIN cuotas_almacen alm ON alm.id_almacen = ven.id_almacen AND alm.id_periodo = per.id
		INNER JOIN almacenes alma ON alma.id = ven.id_almacen
		left JOIN afiliados afi ON afi.ID = cuo.id_supervisor
	where
		per.id = @id_periodo
	GROUP BY
		cuo.id_supervisor,
		ven.id_almacen
		
);


DROP TEMPORARY TABLE IF EXISTS t_consolidado;
CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (

	SELECT
		*,
		round(if(cump_impactos >= 100,impactos,0)) puntos_impactos,
		round(if(cump_ventas >= 100, ventas/200000,0)) puntos_ventas
	from 
		t_puntos_venta ven	
	WHERE
		ven.id_supervisor NOT IN (SELECT id_vendedor FROM t_estado_cuenta WHERE id_periodo = @id_periodo AND id_concepto = 4)
);


insert into t_estado_cuenta(id_periodo,id_almacen,id_concepto,id_vendedor,venta,cuota,cumplimiento,puntos_venta,cuota_impactos,impactos,cumplimiento_impactos,puntos_impactos,total_puntos)
select
	id_periodo,
	id_almacen,
	4,
	id_supervisor,
	ventas,	
	cuota_venta,
	cump_ventas,
	puntos_ventas,
	cuota_impactos,
	impactos,
	cump_impactos,
	puntos_impactos,	
	(puntos_impactos+puntos_ventas)
from
	t_consolidado
where
	@cargar = 1
AND id_supervisor IS NOT null;

SELECT *,(puntos_impactos+puntos_ventas) total_puntos from t_consolidado;

DROP TEMPORARY TABLE t_cuotas;
DROP TEMPORARY TABLE t_ventas;
DROP TEMPORARY TABLE t_puntos_venta;