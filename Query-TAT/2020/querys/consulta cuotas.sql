SELECT
id_usuario,
cuota_1,
case
	when cuota_1 <= 999999 then 4
	when cuota_1 >= 1000000 AND cuota_1 <= 2499999 then 3
	when cuota_1 >= 2500000 AND cuota_1 <= 4999999 then 2
	when cuota_1 >= 5000000 then 1
END clasificacion
 FROM cuotas ORDER BY 2 descs,3;

SELECT * from cuotas WHERE cuota_1 >= 1000000 AND cuota_1 <= 2499999;

SELECT * FROM cuotas WHERE cuota_1 >= 2500000 AND cuota_1 <= 4999999;

SELECT * FROM cuotas WHERE cuota_1 >= 5000000





/*UPDATE cuotas SET impactos = 75 WHERE cuota_1 >= 2500000 AND cuota_1 <= 4999999*/