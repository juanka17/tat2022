<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/mis_datos_vendedor.js?reload=1" type="text/javascript"></script>
    <script type="text/javascript">
        var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    </script>
    <style>
        .fondo_datos {
            border: 2px solid #ff7e06;
            border-radius: 20px;
            padding: 20px;
            margin: 14px;
            background-color: #ffffffd1;
            font-size: 1.3rem;
        }
    </style>
</head>

<body ng-app="misdatosVendedorApp" ng-controller="misdatosVendedorController" class="wrapper layout-top-nav" style="height: auto; min-height: 100%;">
    <?php include 'componentes/mostrar_imagen.php'; ?>
    <div class="content-wrapper" style="min-height: 556px;">
        <?php include 'componentes/controles_superiores.php'; ?>
        <?php include 'componentes/menu.php'; ?>
        <div class="container">
            <section class="content-header">
                <h1>
                    Mis Datos
                </h1>
                <ol class="breadcrumb">
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Mis datos</a></li>
                </ol>
            </section>
            <section class="container">
                <div class="row fondo_datos">
                    <div class="col-sm-12 col-md-4">
                        <label for="cedula">Cedula</label>
                        <input type="text" id="cedula" class="form-control" ng-model="datos_vendedor.cedula">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="cod_formas">Codigo Formas</label>
                        <input type="text" id="cod_formas" disabled class="form-control" ng-model="datos_vendedor.cod_formas">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" ng-model="datos_vendedor.nombre">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="telefono">Telefono</label>
                        <input type="number" id="telefono" class="form-control" ng-model="datos_vendedor.telefono">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="celular">Celular</label>
                        <input type="number" id="celular" class="form-control" ng-model="datos_vendedor.celular">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="nacimiento">Fecha Nacimiento</label>
                        <input type="date" id="nacimiento" class="form-control" ng-model="datos_vendedor.nacimiento">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" class="form-control" ng-model="datos_vendedor.direccion">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="email">Correo</label>
                        <input type="email" id="email" class="form-control" ng-model="datos_vendedor.email">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="id_representante">Representante</label>
                        <select name="id_representante" id="id_representante" class="form-control" ng-model="datos_vendedor.id_representante" ng-change="CargarAlmacenesRepresentante(datos_vendedor.id_representante)">
                            <option ng-repeat="r in representante" value="{{r.id}}">{{r.nombre}}</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="id_almacen">Almacen</label>
                        <select name="id_almacen" id="id_almacen" class="form-control" ng-model="datos_vendedor.id_almacen">
                            <option ng-repeat="a in almacenes" value="{{a.id}}">{{a.nombre}}</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-4">
                        <label for="id_genero">Genero</label>
                        <select name="id_genero" id="id_genero" class="form-control" ng-model="datos_vendedor.id_genero">
                            <option value="1">Masculino</option>
                            <option value="2">Femenio</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" class="form-control" ng-model="datos_vendedor.id_estatus">
                            <option value="4">Vendedor Activo</option>
                            <option value="5">Vendedor Inactivo</option>
                            <option value="6">Pendiente Aprobar</option>
                        </select>
                    </div>
                    <div class="col-sm-12 text-center">
                        <br>
                        <button ng-click="ActualizarDatosVendedores();" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <?php include 'componentes/footer.php'; ?>
            <?php include 'componentes/coponentes_js.php'; ?>
        </footer>
    </div>
</body>

</html>