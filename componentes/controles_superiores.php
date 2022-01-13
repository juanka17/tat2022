<?php
$file_name = basename($_SERVER["SCRIPT_FILENAME"], '.php');
if ($file_name != "index" && $file_name != "index_especial") {
?>
    <div class="row">
        <div class="col-sm-12 col-md-4 offset-md-4 text-center">
            <br>
            <p>¡Hola <?php echo $_SESSION["usuario"]["nombre"]; ?>!</p>
        </div>
    </div>


<?php
}
?>