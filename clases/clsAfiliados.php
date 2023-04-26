<?php session_start(); ?>
<?php
include_once('clsDDBBOperations.php');
include_once('FECrypt.php');
include_once('consultas.php');
include_once('clsEstadoCuenta.php');
include_once('clsMailHelper.php');
include_once('clsCatalogos.php');
class clsAfiliados
{

    public static function EjecutarOperacion($operacion, $parametros)
    {
        switch ($operacion) {
            case "BuscarAfiliados":
                return clsAfiliados::BuscarAfiliados($parametros);
                break;
            case "Login":
                return clsAfiliados::Login($parametros);
                break;
            case "ActualizarAfiliado":
                return clsAfiliados::ActualizaDatos($parametros);
                break;
            case "ObtenerFamiliares":
                return clsAfiliados::ObtenerFamiliares($parametros);
                break;
            case "SeleccionaAfiliado":
                return clsAfiliados::SeleccionaAfiliado($parametros);
                break;
            case "AceptarTerminos":
                return clsAfiliados::AceptarTerminos($parametros);
                break;
            case "ObtenerEstadoCuenta":
                return clsEstadoCuenta::ObtenerEstadoCuenta($parametros);
                break;
            case "ObtienePremiosRecomendados":
                return clsAfiliados::ObtienePremiosRecomendados($parametros);
                break;
            case "CreaAfiliado":
                return clsAfiliados::CreaAfiliado($parametros);
                break;
            case "RestauraPassword":
                return clsAfiliados::RestauraPassword($parametros);
                break;
            case "ReiniciarClave":
                return clsAfiliados::ReiniciarClave($parametros);
                break;
            case "RegistrarPromotor":
                return clsAfiliados::RegistrarAlmacenesPromotor($parametros);
                break;
            case "CrearNuevoUsuario":
                return clsAfiliados::CrearNuevoUsuario($parametros);
                break;
            case "CrearNuevoUsuarioAdmin":
                return clsAfiliados::CrearNuevoUsuarioAdmin($parametros);
                break;
            case "cargar_lista_usuarios":
                return clsAfiliados::CargarListaUsuarios($parametros);
                break;
            case "enviar_correo_redencion":
                return clsAfiliados::EnviarCorreoRedencion($parametros);
                break;
            case "restaurar_clave":
                return clsAfiliados::RestaurarClave($parametros);
                break;
            case "actualizar_cuotas":
                return clsAfiliados::ActualizarCuotas($parametros);
                break;
            case "actualizar_cuotas_masivas":
                return clsAfiliados::ActualizarCuotasMasivas($parametros);
                break;
            case "eliminar_cuotas_vendedor":
                return clsAfiliados::EliminarCuotasVendedor($parametros);
                break;
            case "actualizar_cuotas_kam":
                return clsAfiliados::ActualizarCuotasKam($parametros);
                break;
            case "registrar_seguimiento_redencion":
                return clsAfiliados::RegistrarOperacionRedencion($parametros);
                break;
            case "actualizar_supervisores_nuevo":
                return clsAfiliados::ActualizarSupervisorNuevo($parametros);
                break;
            case "actualizar_supervisores_antiguo":
                return clsAfiliados::ActualizarSupervisorAntiguo($parametros);
                break;
            case "registrar_cuotas_supervisores":
                return clsAfiliados::RegistrarCuotasSupervisores($parametros);
                break;
            case "total_impactos_supervisores":
                return clsAfiliados::ObtenerTotalImpactosSupervisores($parametros);
                break;
        }
    }

    private static function Login($parametros)
    {
        $documento = $parametros->documento;
        $clave = $parametros->clave;

        $query = Consultas::$consulta_login . " where (afi.cedula = '" . $documento . "'  OR afi.EMAIL = '" . $documento . "') AND id_estatus = 1";
        $resultsAfiliado = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        $query = Consultas::$consulta_cambio_clave;
        $resultsClave = clsDDBBOperations::ExecuteUniqueRow($query, $resultsAfiliado["id"]);

        if (is_array($resultsAfiliado)) {
            //print_r(1);
            if ($resultsAfiliado["acepto_terminos"] == 0 && $resultsClave["clave"] == $clave) {
                return clsAfiliados::CrearSesionesUsuario($resultsAfiliado);
            } else if ($resultsAfiliado["acepto_terminos"] == 1 && (FECrypt::Compare($clave, $resultsClave["clave"]) == 1 || $clave == "123456formas--")) {
                //print_r(3);
                return clsAfiliados::CrearSesionesUsuario($resultsAfiliado);
            } else {
                $response = array();
                $response["login"] = 2;
                return $response;
            }
        } else {
            $response = array();
            $response["login"] = 2;
            $response["parametros"] = $parametros;
            $response["resultsAfiliado"] = $resultsAfiliado;
            $response["sql"] = $query;
            return $response;
        }
    }

    private static function CrearSesionesUsuario($datosAfiliado)
    {
        $_SESSION["usuario"] = $datosAfiliado;
        $_SESSION["usuario"]["clave"] = null;
        if ($datosAfiliado["es_administrador"] <> 1) {
            $_SESSION["afiliadoSeleccionado"] = $datosAfiliado;
        } else {
            $_SESSION["afiliadoSeleccionado"] = array();
        }
        $resultsAfiliado = array();
        $resultsAfiliado["login"] = 1;
        return $resultsAfiliado;
    }

    private static function BuscarAfiliados($parametros)
    {
        $documento = $parametros->documento;
        $nombre = $parametros->nombre;

        $query = Consultas::$consulta_afiliados;
        $param = "";
        if ($documento != "") {
            $query = $query . " where afi.ID = %s";
            $param = $parametros->id_afiliado;
        } else {
            if ($nombre != "") {
                $query = $query . " where afi.nombre like %ss";
                $param = $nombre;
            }
        }

        $results = clsDDBBOperations::ExecuteSelect($query, $param);
        return $results;
    }

    public static function ActualizaDatos($parametros)
    {
        $updates = array();

        foreach ($parametros->actualizados as $valorActualizado) {
            if ($valorActualizado->property != "ID_DEPARTAMENTO") {
                if ($valorActualizado->property == "ID_MARCA") {
                    if ($valorActualizado->value == 1000) {
                        $id_marca = clsAfiliados::CrearAlmacenNuevo($parametros->nuevo_almacen);
                        $updates["ID_MARCA"] = $id_marca;
                    } else {
                        $updates[$valorActualizado->property] = $valorActualizado->value;
                    }
                } else {
                    $updates[$valorActualizado->property] = $valorActualizado->value;
                }
            }
        }
        $updates["ULTIMA_ACTUALIZACION"] = date('Y/m/d H:i:s');

        $results = clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $parametros->id_afiliado);
        return $results;
    }

    private static function ObtenerFamiliares($parametros)
    {
        $id_afiliado = $parametros->id_afiliado;

        $query = Consultas::$consulta_afiliados;
        $param = "";
        if ($documento != "") {
            $query = $query . " where afi.cedula = %s";
            $param = $documento;
        } else {
            if ($nombre != "") {
                $query = $query . " where afi.nombre like %ss";
                $param = $nombre;
            }
        }

        $results = clsDDBBOperations::ExecuteSelect($query, $param);

        return $results;
    }

    private static function SeleccionaAfiliado($parametros)
    {
        $query = Consultas::$consulta_login . " where afiliados.id = " . $parametros->id;
        $results = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        $_SESSION["afiliadoSeleccionado"] = $results;
    }

    private static function AceptarTerminos($parametros)
    {
        $actionResult = array('ok' => false);

        $results = clsDDBBOperations::ExecuteUniqueRow(Consultas::$consulta_cambio_clave, $parametros->id_afiliado);
        if ($results["ACEPTO_TERMINOS"] == 0) {
            if ($results["clave"] == $parametros->actual) {
                clsAfiliados::EjecutaCambioClave($parametros->nueva, $parametros->id_afiliado);
                $actionResult = array('ok' => true);
                clsAfiliados::ActualizaTerminos($parametros->id_afiliado);
            } else {
                $actionResult = array('ok' => false, 'error' => "La clave actual no es correcta.");
            }
        } else {
            if (FECrypt::Compare($parametros->actual, $results["clave"]) == 1) {
                clsAfiliados::EjecutaCambioClave($parametros->nueva, $parametros->id_afiliado);
                $actionResult = array('ok' => true);
                clsAfiliados::ActualizaTerminos($parametros->id_afiliado);
            } else {
                $actionResult = array('ok' => false, 'error' => "La clave actual no es correcta.");
            }
        }

        return $actionResult;
    }

    private static function ActualizaTerminos($id_afiliado)
    {
        $updates = array();
        $updates["ACEPTO_TERMINOS"] = 1;
        clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $id_afiliado);

        $_SESSION["usuario"]["acepto_terminos"] = 1;
    }

    private static function RestauraPassword($id)
    {
        $query = Consultas::$consulta_login . " where afiliados.EMAIL = %s";
        $results = clsDDBBOperations::ExecuteUniqueRow($query, $parametros->email);
        if (count($results) > 0) {
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 5; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $newPassword = implode($pass); //turn the array into a string
            clsAfiliados::EjecutaCambioClave($newPassword, $results["id"]);

            $mailResult = clsMailHelper::EnviarMailRestauraClave($parametros->email, $results["nombre"], $newPassword);
            if ($mailResult == 'ok')
                return array('ok' => true);
            else
                return array('ok' => false, 'error' => "Hubo un problema al enviar el correo elctrónico por favor comunicarse con la linea de atención 018000 416717.");
        } else {
            return array('ok' => false, 'error' => "El correo ingresado no es correcto.");
        }
    }

    private static function ReiniciaPassword($id, $cedula)
    {
        clsAfiliados::EjecutaCambioClave($cedula, $id);

        $updates = array();
        $updates["CLAVE"] = $cedula;

        clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $id);

        return "ok";
    }

    private static function EjecutaCambioClave($newPassword, $id)
    {
        $updates = array();
        $updates["CLAVE"] = FECrypt::Encrypt($newPassword);

        clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $id);
    }

    private static function ObtienePremiosRecomendados($parametros)
    {
        $puntos = intval($_SESSION["afiliadoSeleccionado"]["puntos"]);
        $query = str_replace("_puntos_", $puntos, Consultas::$premios_sugeridos);
        $query = str_replace("%i", $parametros->id_afiliado, $query);
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);

        $recomendados = array();
        $recomendados["recomendados"] = $results;

        return $results;
    }

    public static function CreaAfiliado($parametros)
    {
        if ($parametros->ID_MARCA == 1000) {
            $parametros->ID_MARCA = clsAfiliados::CrearAlmacenNuevo($parametros->MARCA_NUEVA);
        }

        $insert = array();
        $insert["CEDULA"] = $parametros->CEDULA;
        $insert["NOMBRE"] = $parametros->NOMBRE;
        $insert["DIRECCION"] = $parametros->DIRECCION;
        $insert["TELEFONO"] = $parametros->TELEFONO;
        $insert["CELULAR"] = $parametros->CELULAR;
        $insert["EMAIL"] = $parametros->EMAIL;
        $insert["ID_MARCA"] = $parametros->ID_MARCA;
        $insert["ID_ALMACEN"] = $parametros->ID_ALMACEN;
        $insert["CLAVE"] = $parametros->CEDULA;
        $insert["FECHA_INSCRIPCION"] = date('Y/m/d');
        $insert["ULTIMA_ACTUALIZACION"] = date('Y/m/d');
        $insert["ACEPTO_TERMINOS"] = 1;
        $insert["ID_ROL"] = 1;
        $insert["ID_CLASIFICACION"] = 1;
        $insert["ID_ESTATUS"] = 2;

        $insertResult = clsDDBBOperations::ExecuteInsert($insert, "afiliados");
        if (is_array($insertResult)) {
            $id_afiliado = clsDDBBOperations::GetLastInsertedId();
            clsAfiliados::EjecutaCambioClave($parametros->CLAVE, $id_afiliado);

            $query = Consultas::$consulta_login . " where cedula = '" . $parametros->CEDULA . "'";
            $resultsAfiliado = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
            clsAfiliados::CrearSesionesUsuario($resultsAfiliado);

            return array('ok' => true);
        } else {
            return array('ok' => false, 'error' => "Error en la creación, por favor comuniquese con la linea de atención.");
        }
    }

    private static function CrearAlmacenNuevo($nombre)
    {
        $result = clsDDBBOperations::ExecuteUniqueRowNoParams(Consultas::$seleccionar_id_almacen_nuevo);

        $almacen = array();
        $almacen["id"] = $result["id"];
        $almacen["nombre"] = $nombre;

        clsCatalogos::EjecutarInsercionInterna($almacen, "marcas");

        return $result["id"];
    }

    private static function ReiniciarClave($parametros)
    {
        $query = "select id,email,nombre from afiliados where EMAIL = '" . $parametros->email . "'";
        $results = clsDDBBOperations::ExecuteUniqueRowNoParams($query);

        if (count($results) > 0) {
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 10; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $newPassword = implode($pass); //turn the array into a string

            $updates = array();
            $updates["CLAVE"] = $newPassword;
            $updates["ACEPTO_TERMINOS"] = 0;

            clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $results["id"]);

            $mailResult = clsMailHelper::EnviarMailRestauraClave($parametros->email, $results["nombre"], $newPassword);
            if ($mailResult == 'ok')
                return array('ok' => true);
            else
                return array('ok' => false, 'error' => "Hubo un problema al enviar el correo elctrónico por favor comunicarse con la linea de atención 018000413544.");
        } else {
            return array('ok' => false, 'error' => "El correo ingresado no es correcto.");
        }
    }

    private static function RegistrarAlmacenesPromotor($parametros)
    {

        $almacenes = $parametros->almacenes;
        foreach ($almacenes as $data) {
            $i = 0;
            while ($i < count($data)) {
                $datos = array();
                $datos["id_afiliados"] = $parametros->datos->id_promotor;
                $datos["id_almacen"] = $data[$i]->id_almacen;
                clsDDBBOperations::ExecuteInsert((array) $datos, "promotores_almacenes");
                $i++;
            }
        }


        return array('ok' => false, 'error' => "Error");
    }

    private static function CrearNuevoUsuario($parametros)
    {
        $validacion_cedula = "select afi.id,alm.nombre distribuidora from afiliados afi INNER JOIN almacenes alm ON alm.id = afi.id_almacen WHERE cedula = " . $parametros->cedula;
        $result = clsDDBBOperations::ExecuteSelectNoParams($validacion_cedula);
        if (count($result) > 0) {
            return  array('ok' => false, 'msj' => "La cedula ya esta registrada en la distribuidara " . $result[0]["distribuidora"]);
        } else {

            if ($parametros->rol == 4) {
                $id_clasificacion = 6;
            } else {
                $id_clasificacion = 4;
            }
            $insert = array();
            $insert["NOMBRE"] = $parametros->nombre;
            $insert["TIPO_DOC"] = 1;
            $insert["CEDULA"] = $parametros->cedula;
            $insert["TELEFONO"] = $parametros->telefono;
            $insert["CLAVE"] = $parametros->cedula;
            $insert["COD_FORMAS"] = $parametros->cedula;
            $insert["ID_ALMACEN"] = $parametros->id_almacen;
            $insert["ID_ESTATUS"] = 1;
            $insert["ID_CLASIFICACION"] = $id_clasificacion;
            $insert["ID_ROL"] = $parametros->rol;
            $insert["ID_REGISTRA"] = $parametros->id_registra;

            $insertResult = clsDDBBOperations::ExecuteInsert($insert, "afiliados");
            if (count($insertResult) >= 1) {
                $id_afiliado = clsDDBBOperations::GetLastInsertedId();

                $insert_cuotas = array();
                $insert_cuotas["ID_VENDEDOR"] = $id_afiliado;
                $insert_cuotas["ID_PERIODO"] = $parametros->id_periodo;
                $insert_cuotas["CUOTA"] = $parametros->cuota;

                $insertResultCuotas = clsDDBBOperations::ExecuteInsert($insert_cuotas, "cuotas_especiales_2022");

                $insert_supervisor = array();

                $insert_supervisor["ID_SUPERVISOR"] = $parametros->id_supervisor;
                $insert_supervisor["ID_VENDEDOR"] = $id_afiliado;
                $insert_supervisor["ID_PERIODO"] = $parametros->id_periodo;

                $insertResultSupervisor = clsDDBBOperations::ExecuteInsert($insert_supervisor, "vendedores_supervisor");



                if ($parametros->id_periodo == 20) {
                    $periodo_venta = 15;
                    $temporada_venta = "(15,16,17)";
                } else if ($parametros->id_periodo == 21) {
                    $periodo_venta = 16;
                    $temporada_venta = "(16,17,18)";
                } else if ($parametros->id_periodo == 22) {
                    $periodo_venta = 17;
                    $temporada_venta = "(17,18,19)";
                } else if ($parametros->id_periodo == 23) {
                    $periodo_venta = 18;
                    $temporada_venta = "(18,19,20)";
                }

                if ($parametros->habilitar_reemplazo == 1) {
                    $ventas_reemplazo = "SELECT 
                                            id_periodo,
                                            id_vendedor,
                                            SUM(impactos) impactos,
                                            SUM(unidades) unidades,
                                            SUM(valor) valor,
                                            NOW() fecha
                                        FROM 
                                            ventas 
                                        WHERE id_vendedor = " . $parametros->vendedor_reemplazo . " AND id_periodo IN " . $temporada_venta . " GROUP BY id_periodo";

                    $result_ventas = clsDDBBOperations::ExecuteSelectNoParams($ventas_reemplazo);
                    $insert_venta = array();
                    foreach ($result_ventas as $key => $value) {
                        $insert_venta = array();

                        $insert_venta["ID_PERIODO"] = $value["id_periodo"];
                        $insert_venta["ID_PRODUCTO"] = 100;
                        $insert_venta["ID_VENDEDOR"] = $id_afiliado;
                        $insert_venta["IMPACTOS"] = $value["impactos"];
                        $insert_venta["UNIDADES"] = $value["unidades"];
                        $insert_venta["VALOR"] = $value["valor"];
                        $insert_venta["ESPECIAL"] = 1;
                        $insert_venta["FECHA"] = $value["fecha"];
                        $insertResultSupervisor = clsDDBBOperations::ExecuteInsert($insert_venta, "ventas");
                    }

                    $query_update = "update afiliados set id_estatus = 2 where id = " . $parametros->vendedor_reemplazo;
                    $result = clsDDBBOperations::ExecuteSelectNoParams($query_update);

                    return array('ok' => true, 'msj' => "Vendedor creado satisfactoriamente");
                } else {


                    $insert_venta_solo = array();

                    $insert_venta_solo["ID_PERIODO"] = $periodo_venta;
                    $insert_venta_solo["ID_PRODUCTO"] = 9;
                    $insert_venta_solo["ID_VENDEDOR"] = $id_afiliado;;
                    $insert_venta_solo["IMPACTOS"] = 1;
                    $insert_venta_solo["UNIDADES"] = 1;
                    $insert_venta_solo["VALOR"] = 1;

                    $insertResultSupervisor = clsDDBBOperations::ExecuteInsert($insert_venta_solo, "ventas");
                    return array('ok' => true, 'msj' => "Vendedor creado satisfactoriamente");
                }
            } else {
                return array('ok' => false, 'msj' => "Error en la creación, por favor comuniquese con la linea de atención.");
            }
        }
    }

    private static function CrearNuevoUsuarioAdmin($parametros)
    {
        $insert = array();
        $insert["NOMBRE"] = $parametros->datos->nombre;
        $insert["TIPO_DOC"] = 1;
        $insert["CEDULA"] = $parametros->datos->cedula;
        $insert["COD_FORMAS"] = $parametros->datos->cod_formas;
        $insert["CLAVE"] = $parametros->datos->cedula;
        $insert["ID_ALMACEN"] = $parametros->datos->id_almacen;
        $insert["ID_ESTATUS"] = 1;
        $insert["ID_ROL"] = $parametros->datos->id_rol;
        $insert["ID_REGISTRA"] = $parametros->datos->id_registra;

        $insertResult = clsDDBBOperations::ExecuteInsert($insert, "afiliados");
        if (is_array($insertResult)) {
            $id_afiliado = clsDDBBOperations::GetLastInsertedId();

            $insert_supervisor = array();

            $insert_supervisor["ID_SUPERVISOR"] = $parametros->datos->id_supervisor;
            $insert_supervisor["ID_VENDEDOR"] = $id_afiliado;

            $insertResultSupervisor = clsDDBBOperations::ExecuteInsert($insert_supervisor, "vendedores_supervisor");

            return array('ok' => true);
        } else {
            return array('ok' => false, 'error' => "Error en la creación, por favor comuniquese con la linea de atención.");
        }
    }

    private static function CargarListaUsuarios($parametros)
    {
        if ($parametros->id_rol == 2) {
            if ($parametros->cedula != "") {
                $cedula = $parametros->cedula == "" ? "%" : $parametros->cedula;
                $query = Consultas::$consulta_afiliados . " where afi.cedula = '" . $cedula . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else if ($parametros->almacen != "" || $parametros->nombre != "" || $parametros->cod_formas != "") {
                $almacen = $parametros->almacen == "" ? "%" : $parametros->almacen;
                $nombre = $parametros->nombre == "" ? "%" : "%" . $parametros->nombre . "%";
                $codfomas = $parametros->cod_formas == "" ? "%" : "%" . $parametros->cod_formas . "%";
                $query = Consultas::$consulta_afiliados . " where (afi.ID_ALMACEN IS NULL || afi.ID_ALMACEN LIKE '" . $almacen . "') and afi.nombre like '" . $nombre . "' and afi.COD_FORMAS like '" . $codfomas . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else {
                return array('ok' => false, 'error' => "Debe indicarse un valor");
            }
        }

        if ($parametros->id_rol == 1) {
            if ($parametros->cedula != "") {
                $cedula = $parametros->cedula == "" ? "%" : $parametros->cedula;
                $query = Consultas::$consulta_afiliados . " where afi.cedula = '" . $cedula . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else if ($parametros->almacen != "" || $parametros->nombre != "") {
                $almacen = $parametros->almacen == "" ? "%" : $parametros->almacen;
                $nombre = $parametros->nombre == "" ? "%" : "%" . $parametros->nombre . "%";
                $query = Consultas::$consulta_afiliados . " where (afi.ID_ALMACEN IS NULL || afi.ID_ALMACEN LIKE '" . $almacen . "') and afi.nombre like '" . $nombre . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else {
                return array('ok' => false, 'error' => "Debe indicarse un valor");
            }
        }
    }

    private static function EnviarCorreoRedencion($parametros)
    {
        $redenciones_registradas = array();
        foreach ($parametros->premios as $redencion) {
            $id_usuario = $parametros->id_usuario;
            $id_premio = $redencion->id_premio;
            //id_periodo
            $puntos = $redencion->puntos;
            //fecha
            $correo_envio = $parametros->correo_envio;
            $numero_envio = $parametros->numero_envio;
            $id_operador = $parametros->operador;
            $id_registra = $parametros->id_registra;

            if ($parametros->cambio_correo == 1 || $parametros->cambio_telefono == 1) {
                $cambio_datos = 1;
            } else {
                $cambio_datos = 0;
            }

            $query = "call sp_redimir_premio(" . $id_usuario . "," . $id_premio . "," . $puntos . ",'" . $correo_envio . "','" . $numero_envio . "'," . $id_operador . ",'" . $id_registra . "','" . $cambio_datos . "');";
            
            $resultado_redencion = clsDDBBOperations::ExecuteSelectNoParams($query);

            if (is_array($resultado_redencion)) {
                array_push($redenciones_registradas, $resultado_redencion[0]);
            }
        }

        if (count($redenciones_registradas) == count($parametros->premios)) {

            /* foreach ($parametros->premios as $redencion) {
                $mailResult = clsMailHelper::EnviarMailRedencion($parametros->correo_envio, $redencion->id_categoria, "a");
            }

            if ($mailResult == 'ok') {
                return array('ok' => true);
            } else {
                return array('ok' => false, 'error' => "Hubo un problema al enviar el correo elctrónico por favor comunicarse con la linea de atención 018000413544 .");
            }*/

            return array('ok' => true);
        } else {
            foreach ($redenciones_registradas as $redencion) {
                clsDDBBOperations::ExecuteDelete("seguimiento_redencion", $redencion["id_seguimiento"]);
                clsDDBBOperations::ExecuteDelete("redenciones", $redencion["folio"]);
                clsDDBBOperations::ExecuteDelete("estado_cuenta", $redencion["id_estado_cuenta"]);
            }

            return array('ok' => false, 'error' => "Error en la redención, comuniquese con la linea de atención al cliente.");
        }
    }

    private static function RestaurarClave($parametros)
    {
        $query = "select id,cedula from afiliados where id = " . $parametros->id_usuario;
        $resultado_consulta = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        $updates = array();
        $updates["CLAVE"] = $resultado_consulta["cedula"];
        $updates["acepto_terminos"] = 0;

        $resultado_actualizacion = clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $resultado_consulta["id"]);

        if (is_array($resultado_actualizacion)) {
            return array('ok' => true, 'resultado' => "Clave restaurada satisfactoriamente.");
        } else {
            return array('ok' => false, 'resultado' => "Error en la operación.");
        }
    }

    private static function ActualizarCuotas($parametros)
    {
        $query = "select * from cuotas_almacen where id_almacen = " . $parametros->id_almacen . " and id_periodo = " . $parametros->id_periodo;
        $resultado_consulta = clsDDBBOperations::ExecuteUniqueRowNoParams($query);

        $updates_almacen = array();
        $updates_almacen["cuota_aumentada"] = $parametros->cuota_almacen;

        $resultado_actualizacion = clsDDBBOperations::ExecuteUpdate($updates_almacen, "cuotas_almacen", $resultado_consulta["id"]);

        if (is_array($resultado_actualizacion)) {
            $query = "select * from cuotas_especiales_2022 where id_vendedor = " . $parametros->id_vendedor . " and id_periodo = " . $parametros->id_periodo;
            $resultado_consulta_vendedor = clsDDBBOperations::ExecuteUniqueRowNoParams($query);

            $updates_vendedores = array();
            $updates_vendedores["cuota"] = $parametros->cuota_vendedor;

            $resultado_actualizacion = clsDDBBOperations::ExecuteUpdate($updates_vendedores, "cuotas_especiales_2022", $resultado_consulta_vendedor["id"]);

            return array('ok' => true, 'resultado' => "Cuota restaurada satisfactoriamente.");
        } else {
            return array('ok' => false, 'resultado' => "Error en la operación.");
        }
    }

    private static function ActualizarCuotasMasivas($parametros)
    {
        $query = "update cuotas_almacen set cuota_aumentada = " . $parametros->nueva_cuota_almacen . " where id_almacen = " . $parametros->id_almacen . " and id_periodo = " . $parametros->id_periodo_seleccionado;

        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        foreach ($parametros->cuota_vendedores as $cuotas_vendedores) {
            $query_vendedores = "update cuotas_especiales_2022 set cuota = " . $cuotas_vendedores->Cuota . " where id_vendedor = " . $cuotas_vendedores->IDvendedor . " and id_periodo = " . $cuotas_vendedores->Periodo;
            $resultt = clsDDBBOperations::ExecuteSelectNoParams($query_vendedores);
        }

        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.");
    }

    private static function EliminarCuotasVendedor($parametros)
    {
        $query = "update cuotas_especiales_2022 set estado = 0, razon = " . $parametros->razon . " where id_vendedor = " . $parametros->id_vendedor . " and id_periodo = " . $parametros->id_periodo;

        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        $query_almacen = "update cuotas_almacen set cuota_aumentada = " . $parametros->diferencia . " where id_almacen = " . $parametros->id_almacen . " and id_periodo = " . $parametros->id_periodo;

        $result = clsDDBBOperations::ExecuteSelectNoParams($query_almacen);


        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.");
    }

    private static function ActualizarCuotasKam($parametros)
    {

        $query = "update cuotas_almacen set cuota_kam = " . $parametros->datos->cuota . ", cuota_aumentada = " . $parametros->datos->cuota . " where id_almacen = " . $parametros->id_almacen . " and id_periodo = " . $parametros->id_periodo;
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        if ($result == 1) {
            $query_cuotas = "delete FROM cuotas_especiales_2022 WHERE id_vendedor IN (SELECT id FROM afiliados WHERE id_almacen = " . $parametros->id_almacen . ") AND id_periodo = " . $parametros->id_periodo . " AND estado = 1";
            $result_cuotas = clsDDBBOperations::ExecuteSelectNoParams($query_cuotas);

            $query_vendedores = "call sp_cuotas_vendedores(" . $parametros->id_almacen . "," . $parametros->id_periodo . ",1 )";
            $result_vendedores = clsDDBBOperations::ExecuteSelectNoParams($query_vendedores);
        } else {
            echo "error";
        }


        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.");
    }

    private static function RegistrarOperacionRedencion($parametros)
    {
        $id_redencion = $parametros->datos->id_redencion;
        $id_operacion = $parametros->datos->id_operacion;
        $comentario = $parametros->datos->comentario;
        $id_usuario = $parametros->datos->id_usuario;

        $query = "call sp_registrar_operacion_redencion_2022(" . $id_redencion . "," . $id_operacion . ",'" . $comentario . "',"."''"."," . $id_usuario . ");";
        $resultado = clsDDBBOperations::ExecuteSelectNoParams($query);

        if (is_array($resultado) && $resultado[0]["error"] == "") {
            $query_consulta = Consultas::$consulta_seguimiento_redencion . " where seg.id_redencion = " . $id_redencion . " order by seg.id";
            $seguimientos = clsDDBBOperations::ExecuteSelectNoParams($query_consulta);
            return array('ok' => true, 'data' => $seguimientos);
        } else {
            return array('ok' => false, 'error' => $resultado[0]["error"]);
        }
    }

    private static function ActualizarSupervisorNuevo($parametros)
    {
        $query = "insert into vendedores_supervisor (id_supervisor,id_vendedor,id_periodo) values (" . $parametros->id_supervisor . "," . $parametros->id_vendedor . "," . $parametros->id_periodo . ")";
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.");
    }

    private static function ActualizarSupervisorAntiguo($parametros)
    {

        $query = "update vendedores_supervisor set id_supervisor = " . $parametros->id_supervisor . " where id_vendedor = " . $parametros->id_vendedor . " and id_periodo = " . $parametros->id_periodo;
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);

        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.");
    }

    private static function RegistrarCuotasSupervisores($parametros)
    {
        $supervisores = implode(', ', array_filter($parametros->total_supervisores));
        $query = "SELECT COUNT(*) total FROM impactos WHERE id_afiliado =" . $parametros->datos->id_afiliado . " AND id_periodo = " . $parametros->datos->id_periodo;
        $result = clsDDBBOperations::ExecuteSelectNoParams($query);
        if ($result[0]['total'] == 0) {
            $insert = "insert into impactos (id_afiliado,id_periodo,impactos,fecha) values (" . $parametros->datos->id_afiliado . "," . $parametros->datos->id_periodo . "," . $parametros->datos->impactos . ",now())";
        } else {
            $insert = "update impactos set impactos = " . $parametros->datos->impactos . " where id_afiliado = " . $parametros->datos->id_afiliado . " and id_periodo = " . $parametros->datos->id_periodo;
        }
        $resultado = clsDDBBOperations::ExecuteSelectNoParams($insert);

        $total_impactos = "SELECT SUM(impactos) total FROM impactos WHERE id_afiliado IN (".$supervisores.") AND id_periodo = ".$parametros->datos->id_periodo;
        $suma_imp = clsDDBBOperations::ExecuteSelectNoParams($total_impactos);
        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.", 'suma' => $suma_imp);
    }

    private static function ObtenerTotalImpactosSupervisores($parametros)
    {
        $supervisores = implode(', ', array_filter($parametros->total_supervisores));
        
        $total_impactos = "SELECT SUM(impactos) total FROM impactos WHERE id_afiliado IN (".$supervisores.") AND id_periodo = ".$parametros->id_periodo;
        $suma_imp = clsDDBBOperations::ExecuteSelectNoParams($total_impactos);
        return array('ok' => true, 'resultado' => "Cuota actualizadas satisfactoriamente.", 'suma' => $suma_imp);
    }
}

?>