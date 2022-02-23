<?php
if (count($_SESSION) > 0) {
    if ($_SESSION["usuario"]["ID_ROL"] == 6) { //supervisor
        include 'componentes/menu_supervisor.php';
    } else if ($_SESSION["usuario"]["ID_ROL"] == 7) {//informatico
        include 'componentes/menu_vendedor.php';
    } else if ($_SESSION["usuario"]["ID_ROL"] == 4) {//vendedor
        include 'componentes/menu_vendedor.php';
    }
    else if ($_SESSION["usuario"]["ID_ROL"] == 2) {//admin
        include 'componentes/menu_admin.php';
    }
    else if ($_SESSION["usuario"]["ID_ROL"] == 1) {//representante o visitador
        include 'componentes/menu_visitador.php';
    }
}
?>