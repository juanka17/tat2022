<?php
if (count($_SESSION) > 0) {
    if ($_SESSION["usuario"]["es_administrador"] == 1) {
        include 'componentes/menu_admin.php';
    } else if ($_SESSION["usuario"]["es_administrador"] == 2) {
        include 'componentes/menu_gerente.php';
    } else if ($_SESSION["usuario"]["es_administrador"] == 3) {
        include 'componentes/menu_vendedor.php';
    } else if (isset($_SESSION["usuario"])) {
        include 'componentes/menu_visitador.php';
    }
}
?>
