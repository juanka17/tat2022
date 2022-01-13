set @id_periodo = 11;
set @cargar = 0;

DROP TEMPORARY TABLE IF EXISTS t_cuotas;
CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas AS (

	select
		cuo.id_temporada,
		per.id id_periodo,
		cuo.id_usuario,
		afi.nombre,
		afi.cod_formas,
		cuota_1,
		cuota_2,
		cuota_minima,
		impactos
	from 
		cuotas cuo
		inner join afiliados afi on afi.id = cuo.id_usuario
		INNER JOIN periodo per ON per.id_temporada = cuo.id_temporada		
	where
		per.id = @id_periodo
		
);

DROP TEMPORARY TABLE IF EXISTS t_ventas;
CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas AS (

	select
		ven.id_periodo,
		ven.id_vendedor,
		afi.nombre,
		afia.id_almacen,		
		afi.cod_formas,
		sum(ven.valor) ventas
	from 
		ventas ven
		INNER JOIN afiliado_almacen afia ON afia.id_afiliado = ven.id_vendedor
		inner join afiliados afi on afi.id = afia.id_afiliado
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
		ven.nombre vendedor,
		ven.id_almacen,
		cuo.id_usuario,
		cuota_2 cuota,
		cuo.cuota_minima,
		ven.ventas		
	from
		t_ventas ven 		
		left JOIN t_cuotas cuo ON ven.id_vendedor = cuo.id_usuario 
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
		ven.vendedor,
		ven.id_almacen,
		ven.id_usuario,
		ven.cuota,
		ven.cuota_minima,
		ven.ventas,
		esp.venta_especial,
		ROUND((ven.ventas/ven.cuota)*100) cumplimiento,
		if(ROUND((ven.ventas/ven.cuota)*100)>=100, ROUND(ven.ventas/25000), 0)puntos_venta,
		if(ROUND((ven.ventas/ven.cuota)*100)>=100, ROUND((esp.venta_especial/25000)*2), 0)puntos_especial
	FROM t_puntos_venta ven
	left JOIN t_puntos_venta_advil esp ON ven.id_usuario = esp.id_vendedor
		
);


		
	
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
);

DROP TEMPORARY TABLE IF EXISTS t_consolidado;
CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (

	select
		ven.id_periodo,
		per.id_temporada,
		ven.periodo,
		alm.nombre almacen,
		alm.id id_almacen,
		ven.id_vendedor,
		ven.vendedor,
		cla.id id_categoria,
		cla.nombre categoria,
		ven.ventas venta,
		ifnull(ven.venta_especial,0) venta_especial,
		ifnull(ven.cuota,0) cuota,
		ifnull(ven.cuota_minima,0) cuota_minima,
		ifnull(ven.cumplimiento,0) cumplimiento,
		ifnull(ven.puntos_venta,0) puntos_venta,
		ifnull(ven.puntos_especial,0) puntos_especial,
		ifnull(imp.cuota_impactos,0) cuota_impactos,
		ifnull(imp.impactos,0) impactos,
		ifnull(imp.cumplimiento_impactos,0) cumplimiento_impactos,
		ifnull(imp.puntos_impactos,0) puntos_impactos,
		(puntos_venta+ifnull(ven.puntos_especial,0)) total_puntos_venta,
		(puntos_venta+ifnull(ven.puntos_especial,0)+ifnull(imp.puntos_impactos,0)) total_puntos
	from 
		t_consolidad_ventas ven	
		INNER JOIN almacenes alm on alm.id = ven.id_almacen
		left join t_puntos_impactos imp on imp.id_periodo = ven.id_periodo and imp.id_afiliado = ven.id_usuario
		INNER JOIN periodo per ON per.id = ven.id_periodo
		left JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = ven.id_vendedor AND per.id_temporada = nue.id_temporada
		left JOIN categorias cla ON cla.id = nue.id_categoria
	WHERE
	ven.id_vendedor NOT IN (SELECT id_vendedor FROM t_estado_cuenta WHERE id_periodo = @id_periodo)
);

insert into t_estado_cuenta(id_periodo,id_almacen,id_vendedor,id_categoria,venta,venta_especial,cuota,cumplimiento,puntos_venta,puntos_especial,cuota_impactos,impactos,cumplimiento_impactos,puntos_impactos,total_puntos_venta,total_puntos)
select
	id_periodo,
	id_almacen,
	id_vendedor,
	id_categoria,
	venta,
	venta_especial,
	cuota,
	cumplimiento,
	puntos_venta,
	puntos_especial,
	cuota_impactos,
	impactos,
	cumplimiento_impactos,
	puntos_impactos,
	total_puntos_venta,
	total_puntos
from
	t_consolidado
where
	@cargar = 1
	GROUP BY id_vendedor;

select * from t_consolidado;

DROP TEMPORARY TABLE t_cuotas;
DROP TEMPORARY TABLE t_ventas;
DROP TEMPORARY TABLE t_puntos_venta_advil;
DROP TEMPORARY TABLE t_puntos_venta;
DROP TEMPORARY TABLE t_consolidad_ventas;
DROP TEMPORARY TABLE t_puntos_impactos;