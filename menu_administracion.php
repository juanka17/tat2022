<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
    <style>
        .btn {
            margin-top: 99px;
            height: 200px;
            font-size: 30px;
            padding: 50px;
            border-radius: 20px;
        }
    </style>
</head>

<body class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Administración de plataforma
            </h1>
            <ol class="breadcrumb">
                <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Administración</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-sm-4">
                    <a class="btn btn-primary btn-block hide" href="admin_distribuidor.php">
                        <i class="fa fa-industry"></i>
                        <br />
                        Distribuidores
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="btn btn-primary btn-block" href="admin_vendedores.php">
                        <i class="fa fa-users"></i>
                        <br />
                        Vendedores
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="btn btn-primary btn-block hide" href="descargar_actas.php">
                        <i class="fa fa-file-pdf-o"></i>
                        <br />
                        Actas
                    </a>
                </div>
            </div>
        </section>
        <!-- /.content -->
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
        </footer>
        <?php include 'componentes/coponentes_js.php'; ?>
    </div>

</body>

</html>