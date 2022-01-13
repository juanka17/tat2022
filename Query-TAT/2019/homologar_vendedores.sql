SET @cod_formas_permanece = 10215  ;
SET @cod_formas_eliminar = 9569  ;

SELECT @id_permanece := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT @id_elimina := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_eliminar;

SELECT * FROM ventas WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM cuotas WHERE id_usuario IN (@id_permanece,@id_elimina);
SELECT * FROM impactos WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM estado_cuenta WHERE id_afiliado IN (@id_permanece,@id_elimina);

UPDATE ventas SET id_vendedor = @id_permanece WHERE id_vendedor = @id_elimina;
UPDATE cuotas SET id_usuario = @id_permanece WHERE id_usuario = @id_elimina;
UPDATE impactos SET id_afiliado = @id_permanece WHERE id_afiliado = @id_elimina;
UPDATE estado_cuenta SET id_afiliado = @id_permanece WHERE id_afiliado = @id_elimina;

SELECT * FROM ventas WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM cuotas WHERE id_usuario IN (@id_permanece,@id_elimina);
SELECT * FROM impactos WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM estado_cuenta WHERE id_afiliado IN (@id_permanece,@id_elimina);

DELETE FROM afiliado_almacen WHERE id_afiliado = @id_elimina;
DELETE FROM afiliados WHERE id = @id_elimina;

SELECT @id_permanece := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT @id_elimina := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_eliminar;