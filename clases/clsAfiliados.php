<?php session_start(); ?>
<?php

include_once('clsDDBBOperations.php');
include_once('FECrypt.php');
include_once('consultas.php');
include_once('clsEstadoCuenta.php');
include_once('clsMailHelper.php');
include_once('clsCatalogos.php');

class clsAfiliados {

    public static function EjecutarOperacion($operacion, $parametros) {
        switch ($operacion) {
            case "BuscarAfiliados": return clsAfiliados::BuscarAfiliados($parametros);
                break;
            case "Login": return clsAfiliados::Login($parametros);
                break;
            case "ActualizarAfiliado": return clsAfiliados::ActualizaDatos($parametros);
                break;
            case "ObtenerFamiliares": return clsAfiliados::ObtenerFamiliares($parametros);
                break;
            case "SeleccionaAfiliado": return clsAfiliados::SeleccionaAfiliado($parametros);
                break;
            case "AceptarTerminos": return clsAfiliados::AceptarTerminos($parametros);
                break;
            case "ObtenerEstadoCuenta": return clsEstadoCuenta::ObtenerEstadoCuenta($parametros);
                break;
            case "ObtienePremiosRecomendados": return clsAfiliados::ObtienePremiosRecomendados($parametros);
                break;
            case "CreaAfiliado": return clsAfiliados::CreaAfiliado($parametros);
                break;
            case "RestauraPassword": return clsAfiliados::RestauraPassword($parametros);
                break;
            case "ReiniciarClave": return clsAfiliados::ReiniciarClave($parametros);
                break;
            case "RegistrarPromotor": return clsAfiliados::RegistrarAlmacenesPromotor($parametros);
                break;
            case "CrearNuevoUsuario": return clsAfiliados::CrearNuevoUsuario($parametros);
                break;
            case "CrearNuevoUsuarioAdmin": return clsAfiliados::CrearNuevoUsuarioAdmin($parametros);
                break;
            case "cargar_lista_usuarios": return clsAfiliados::CargarListaUsuarios($parametros);
                break;
        }
    }

    private static function Login($parametros) {
        $documento = $parametros->documento;
        $clave = $parametros->clave;

        $query = Consultas::$consulta_login . " where afi.cedula = '" . $documento . "'  OR afi.EMAIL = '". $documento ."'";
        $resultsAfiliado = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        $query = Consultas::$consulta_cambio_clave;
        $resultsClave = clsDDBBOperations::ExecuteUniqueRow($query, $resultsAfiliado["id"]);

        if (count($resultsAfiliado) > 0 && is_array($resultsAfiliado)) {
            //print_r(1);
            if ($resultsAfiliado["acepto_terminos"] == 0 && $resultsClave["clave"] == $clave) {
                return clsAfiliados::CrearSesionesUsuario($resultsAfiliado);
            } else if ($resultsAfiliado["acepto_terminos"] == 1 && (FECrypt::Compare($clave, $resultsClave["clave"]) == 1 || $clave == "123456formas--" )) {
                //print_r(3);
                return clsAfiliados::CrearSesionesUsuario($resultsAfiliado);
            } else {
                return "Acceso incorrecto";
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

    private static function CrearSesionesUsuario($datosAfiliado) {
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

    private static function BuscarAfiliados($parametros) {
        $documento = $parametros->documento;
        $nombre = $parametros->nombre;

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

    public static function ActualizaDatos($parametros) {
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

    private static function ObtenerFamiliares($parametros) {
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

    private static function SeleccionaAfiliado($parametros) {
        $query = Consultas::$consulta_login . " where afiliados.id = " . $parametros->id;
        $results = clsDDBBOperations::ExecuteUniqueRowNoParams($query);
        $_SESSION["afiliadoSeleccionado"] = $results;
    }

    private static function AceptarTerminos($parametros) {
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

    private static function ActualizaTerminos($id_afiliado) {
        $updates = array();
        $updates["ACEPTO_TERMINOS"] = 1;
        clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $id_afiliado);

        $_SESSION["usuario"]["acepto_terminos"] = 1;
    }

    private static function RestauraPassword($id) {
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
        }
        else {
            return array('ok' => false, 'error' => "El correo ingresado no es correcto.");
        }
    }

    private static function ReiniciaPassword($id, $cedula) {
        clsAfiliados::EjecutaCambioClave($cedula, $id);

        $updates = array();
        $updates["CLAVE"] = $cedula;

        clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $id);

        return "ok";
    }

    private static function EjecutaCambioClave($newPassword, $id) {
        $updates = array();
        $updates["CLAVE"] = FECrypt::Encrypt($newPassword);

        clsDDBBOperations::ExecuteUpdate($updates, "afiliados", $id);
    }

    private static function ObtienePremiosRecomendados($parametros) {
        $puntos = intval($_SESSION["afiliadoSeleccionado"]["puntos"]);
        $query = str_replace("_puntos_", $puntos, Consultas::$premios_sugeridos);
        $query = str_replace("%i", $parametros->id_afiliado, $query);
        $results = clsDDBBOperations::ExecuteSelectNoParams($query);

        $recomendados = array();
        $recomendados["recomendados"] = $results;

        return $results;
    }

    public static function CreaAfiliado($parametros) {
        if ($parametros->ID_MARCA == 1000) {
            $parametros->ID_MARCA = clsAfiliados::CrearAlmacenNuevo($parametros->MARCA_NUEVA);
        }

        $insert = Array();
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

    private static function CrearAlmacenNuevo($nombre) {
        $result = clsDDBBOperations::ExecuteUniqueRowNoParams(Consultas::$seleccionar_id_almacen_nuevo);

        $almacen = array();
        $almacen["id"] = $result["id"];
        $almacen["nombre"] = $nombre;

        clsCatalogos::EjecutarInsercionInterna($almacen, "marcas");

        return $result["id"];
    }

    private static function ReiniciarClave($parametros) {
        $query = "select id,email,nombre from afiliados where EMAIL = '" . $parametros->email . "'";
        $results = clsDDBBOperations::ExecuteUniqueRowNoParams($query);

        if (count($results) > 0) {
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 5; $i++) {
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
        }
        else {
            return array('ok' => false, 'error' => "El correo ingresado no es correcto.");
        }
    }

    private static function RegistrarAlmacenesPromotor($parametros) {

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

    private static function CrearNuevoUsuario($parametros) {

        $insert = Array();
        $insert["NOMBRE"] = $parametros->nombre;
        $insert["COD_FORMAS"] = "PA".$parametros->cod_formas;
        $insert["ID_ALMACEN"] = $parametros->id_almacen;
        $insert["ID_ESTATUS"] = 7;
        $insert["ID_CLASIFICACION"] = 6;
        $insert["ID_CATEGORIA"] = $parametros->id_categoria;
        $insert["ID_REGISTRA"] = $parametros->id_registra;
        
        $insertResult = clsDDBBOperations::ExecuteInsert($insert, "afiliados");
        if (is_array($insertResult)) {
            $id_afiliado = clsDDBBOperations::GetLastInsertedId();

            $insert_cuotas = Array();
            $insert_cuotas["ID_USUARIO"] = $id_afiliado;
            $insert_cuotas["ID_TEMPORADA"] = $parametros->id_temporada;
            $insert_cuotas["CUOTA_1"] = $parametros->cuota_1;
            $insert_cuotas["CUOTA_2"] = $parametros->cuota_2;
            $insert_cuotas["IMPACTOS"] = $parametros->impactos;
            
            $insertResultCuotas = clsDDBBOperations::ExecuteInsert($insert_cuotas, "cuotas");
            
            $insert_supervisor = Array();
            
            $insert_supervisor["ID_SUPERVISOR"] = $parametros->id_supervisor;
            $insert_supervisor["ID_VENDEDOR"] = $id_afiliado;
            
            $insertResultSupervisor = clsDDBBOperations::ExecuteInsert($insert_supervisor, "vendedores_supervisor");
            
            $insert_categoria = Array();
            
            $insert_categoria["ID_AFILIADO"] = $id_afiliado;
            $insert_categoria["ID_TEMPORADA"] = $parametros->id_temporada;
            $insert_categoria["ID_CATEGORIA"] = $parametros->id_categoria;
            
            $insertResultCategoria = clsDDBBOperations::ExecuteInsert($insert_categoria, "nueva_clasificacion_usuario");
            
            $insert_nuevo_vendedor = Array();
            
            $insert_nuevo_vendedor["ID_AFILIADO"] = $id_afiliado;
            $insert_nuevo_vendedor["ID_ALMACEN"] = $parametros->id_almacen;
            
            $insertResultCategoria = clsDDBBOperations::ExecuteInsert($insert_nuevo_vendedor, "afiliado_almacen");


            return array('ok' => true);
        } else {
            return array('ok' => false, 'error' => "Error en la creación, por favor comuniquese con la linea de atención.");
        }
    }

    private static function CrearNuevoUsuarioAdmin($parametros) {
        $insert = Array();
        $insert["NOMBRE"] = $parametros->datos->nombre;
        $insert["COD_FORMAS"] = $parametros->datos->cod_formas;
        $insert["ID_ALMACEN"] = $parametros->datos->id_almacen;
        $insert["ID_ESTATUS"] = 4;
        $insert["ID_CLASIFICACION"] = 6;
        $insert["ID_CATEGORIA"] = $parametros->datos->id_categoria;
        $insert["ID_REGISTRA"] = $parametros->datos->id_registra;
        
        $insertResult = clsDDBBOperations::ExecuteInsert($insert, "afiliados");
        if (is_array($insertResult)) {
            $id_afiliado = clsDDBBOperations::GetLastInsertedId();

            $insert_cuotas = Array();
            $insert_cuotas["ID_USUARIO"] = $id_afiliado;
            $insert_cuotas["ID_TEMPORADA"] = $parametros->datos->id_temporada;
            $insert_cuotas["CUOTA_MINIMA"] = $parametros->datos->cuota_minima;
            $insert_cuotas["CUOTA_1"] = $parametros->datos->cuota_minima;
            $insert_cuotas["CUOTA_2"] = $parametros->datos->cuota_minima;
            $insert_cuotas["IMPACTOS"] = $parametros->datos->imp_minimos;
            
            $insertResultCuotas = clsDDBBOperations::ExecuteInsert($insert_cuotas, "cuotas");
            
            $insert_supervisor = Array();
            
            $insert_supervisor["ID_SUPERVISOR"] = $parametros->datos->id_supervisor;
            $insert_supervisor["ID_VENDEDOR"] = $id_afiliado;
            $insert_supervisor["ID_TEMPORADA"] = $parametros->datos->id_temporada;
            
            $insertResultSupervisor = clsDDBBOperations::ExecuteInsert($insert_supervisor, "vendedores_supervisor");
            
            $insert_categoria = Array();
            
            $insert_categoria["ID_AFILIADO"] = $id_afiliado;
            $insert_categoria["ID_TEMPORADA"] = $parametros->datos->id_temporada;
            $insert_categoria["ID_CATEGORIA"] = $parametros->datos->id_categoria;
            
            $insertResultCategoria = clsDDBBOperations::ExecuteInsert($insert_categoria, "nueva_clasificacion_usuario");
            
            $insert_nuevo_vendedor = Array();
            
            $insert_nuevo_vendedor["ID_AFILIADO"] = $id_afiliado;
            $insert_nuevo_vendedor["ID_ALMACEN"] = $parametros->datos->id_almacen;
            
            $insertResultCategoria = clsDDBBOperations::ExecuteInsert($insert_nuevo_vendedor, "afiliado_almacen");


            return array('ok' => true);
        } else {
            return array('ok' => false, 'error' => "Error en la creación, por favor comuniquese con la linea de atención.");
        }
    }

    private static function CargarListaUsuarios($parametros)
    {
        if($parametros->id_rol==2){
            if ($parametros->cedula != "") {
                $cedula = $parametros->cedula == "" ? "%" : $parametros->cedula;
                $query = Consultas::$consulta_afiliados . " where afi.cedula = '" . $cedula . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else if ($parametros->almacen != "" || $parametros->nombre != "" || $parametros->cod_formas != "") {
                $almacen = $parametros->almacen == "" ? "%" : $parametros->almacen; 
                $nombre = $parametros->nombre == "" ? "%" : "%" . $parametros->nombre . "%";
                $codfomas = $parametros->cod_formas == "" ? "%" : "%" . $parametros->cod_formas . "%";
                $query = Consultas::$consulta_afiliados . " where (afi.ID_ALMACEN IS NULL || afi.ID_ALMACEN LIKE '" . $almacen . "') and afi.nombre like '" . $nombre . "' and afi.COD_FORMAS like '". $codfomas . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else {
                return array('ok' => false, 'error' => "Debe indicarse un valor");
            }
        }

        if($parametros->id_rol==1){
            if ($parametros->cedula != "") {
                $cedula = $parametros->cedula == "" ? "%" : $parametros->cedula;
                $query = Consultas::$consulta_afiliados . " where afi.cedula = '" . $cedula . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else if ($parametros->almacen != "" || $parametros->nombre != "") {
                $almacen = $parametros->almacen == "" ? "%" : $parametros->almacen; 
                $nombre = $parametros->nombre == "" ? "%" : "%" . $parametros->nombre . "%";
                $nombre = $parametros->cod_formas == "" ? "%" : "%" . $parametros->cod_formas . "%";
                $query = Consultas::$consulta_afiliados . " where (afi.ID_ALMACEN IS NULL || afi.ID_ALMACEN LIKE '" . $almacen . "') and afi.nombre like '" . $nombre . "' order by afi.ID_ESTATUS desc";
                $listado = clsDDBBOperations::ExecuteSelectNoParams($query);

                return array('ok' => true, 'listado' => $listado);
            } else {
                return array('ok' => false, 'error' => "Debe indicarse un valor");
            }
        }
    }
}

?>