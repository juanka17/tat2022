SET @cod_formas_permanece = '80757917';
SET @cod_formas_eliminar = '6953';

SELECT @id_permanece := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT @id_elimina := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_eliminar;

SELECT * FROM ventas WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM impactos WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM t_estado_cuenta WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM cuotas_especiales_2022 WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM vendedores_supervisor WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM llamadas_usuarios WHERE id_usuario IN (@id_permanece,@id_elimina);
SELECT * FROM redenciones WHERE id_usuario IN (@id_permanece,@id_elimina);

SELECT * FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT * FROM afiliados WHERE cod_formas = @cod_formas_eliminar;

