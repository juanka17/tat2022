select
	alm.id id_distribuidora,
	alm.nombre distribuidora, 
	eje.id id_ejecutivo,
	eje.nombre ejecutivo,
	ven.id id_vendedor,
	ven.nombre vendedor,
	ven.COD_PFIZER,
	ven.COD_FORMAS
from
	almacenes alm
	left join afiliados ven on ven.id_almacen = alm.id
	left join afiliados eje on eje.id = alm.id_visitador