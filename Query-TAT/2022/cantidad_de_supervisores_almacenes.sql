



SELECT id_supervisor,
id_almacen,
COUNT(*)
FROM ( 
SELECT 
id_supervisor,
id_almacen
FROM 
	vendedores_supervisor v
	INNER JOIN afiliados a ON a.id = v.id_supervisor
WHERE id_periodo = 18
GROUP BY id_almacen,id_supervisor
ORDER BY id_almacen,id_supervisor)a
GROUP BY 
id_almacen