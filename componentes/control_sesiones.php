<?php
session_start();
/*ini_set('session.cookie_httponly', 1);

$currentCookieParams = session_get_cookie_params();  
foreach( $_COOKIE as $cookie_name => $cookie_value )
{
    $value = $cookie_value;
    if( $cookie_name == "PHPSESSID" )
    {
        $value = session_id();
    }

    unset($_COOKIE[ $cookie_name ]); 
    setcookie(  
        $cookie_name,//name  
        $value,//value  
        0,//expires at end of session  
        $currentCookieParams['path'],//path  
        $currentCookieParams['domain'],//domain  
        true, //secure,
        true
    );  
}*/

if(isset($_GET["logout"]))
{
    session_destroy();
    header("Location: ../index.php");
    die();
}

$url = explode("/", $_SERVER['PHP_SELF']);
if($url[(count($url) - 1)] != "index.php")
{
    if(count($_SESSION) == 0)
    {
        session_destroy();
        header("Location: ../index.php");
        die();
    }
    else
    {
        if($_SESSION["usuario"]["acepto_terminos"] == 0 && $url[(count($url) - 1)] != "formulario_usuario.php")
        {
            $url_mis_datos = "Location: mis_datos.php?id_usuario=".$_SESSION["usuario"]["id"];
            header($url_mis_datos);
        }
    }
}
else
{
    $_SESSION["usuario"] = null;
    $_SESSION["afiliadoSeleccionado"] = null;
    session_destroy();
}

?>