SET @cod_formas_permanece = 7435        ;
SET @cod_formas_eliminar = 'PA20464'  ;

SELECT @id_permanece := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_permanece;
SELECT @id_elimina := id, cod_formas FROM afiliados WHERE cod_formas = @cod_formas_eliminar;

SELECT * FROM afiliados WHERE id IN (@id_permanece,@id_elimina);
SELECT * FROM afiliado_almacen WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM ventas WHERE id_vendedor IN (@id_permanece,@id_elimina);
SELECT * FROM cuotas WHERE id_usuario IN (@id_permanece,@id_elimina);
SELECT * FROM impactos WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM estado_cuenta WHERE id_afiliado IN (@id_permanece,@id_elimina);
SELECT * FROM nueva_clasificacion_usuario WHERE id_afiliado IN (@id_permanece,@id_elimina);


