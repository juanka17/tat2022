SET @cod_formas_permanece = 'SP1022';
SET @cod_formas_eliminar = 'SP7787';

SELECT @id_permanece := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT @id_elimina := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_eliminar;

SELECT * FROM ventas WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM impactos WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM t_estado_cuenta WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM vendedores_supervisor WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM llamadas_usuarios WHERE id_usuario IN (@id_permanece,@id_elimina);
SELECT * FROM redenciones WHERE id_usuario IN (@id_permanece,@id_elimina);


UPDATE ventas SET id_vendedor = @id_permanece WHERE id_vendedor = @id_elimina;
UPDATE impactos SET id_afiliado = @id_permanece WHERE id_afiliado = @id_elimina;
UPDATE t_estado_cuenta SET id_vendedor = @id_permanece WHERE id_vendedor = @id_elimina;
UPDATE vendedores_supervisor SET id_vendedor = @id_permanece WHERE id_vendedor = @id_elimina;
UPDATE llamadas_usuarios SET id_usuario = @id_permanece WHERE id_usuario = @id_elimina;
UPDATE redenciones SET id_usuario = @id_permanece WHERE id_usuario = @id_elimina;

SELECT * FROM ventas WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM impactos WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM t_estado_cuenta WHERE id_vendedor IN (@id_permanece,@id_elimina);

DELETE FROM afiliados WHERE id = @id_elimina;

SELECT @id_permanece := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT @id_elimina := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_eliminar;