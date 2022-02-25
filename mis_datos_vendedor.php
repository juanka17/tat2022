<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/mis_datos_vendedor.js?reload=4" type="text/javascript"></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
    var id_usuario = 0;
    var datos_usuario = <?php echo json_encode($_SESSION["usuario"]); ?>;
    if (typeof getParameterByName("id_usuario") !== 'undefined' && getParameterByName("id_usuario") != "") {
        id_usuario = getParameterByName("id_usuario");
    } else {
        alert("No hay usuario seleccionado.");
        document.location.href = "listado_usuarios.php";
    }
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

    .container {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: 7%;
    }
    </style>
</head>

<body ng-app="misdatosVendedorApp" ng-controller="misdatosVendedorController" class="wrapper layout-top-nav"
    style="height: auto; min-height: 100%;">
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
            <section class="">
                <div class="row fondo_datos">
                    <div class="col sm-12 col-md-8">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label for="cedula">Cedula</label>
                                <input type="text" id="cedula" class="form-control" ng-model="datos_vendedor.cedula">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="cod_formas">Codigo Formas</label>
                                <input type="text" id="cod_formas" disabled class="form-control"
                                    ng-model="datos_vendedor.cod_formas">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" class="form-control" ng-model="datos_vendedor.nombre">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="telefono">Telefono</label>
                                <input type="number" id="telefono" class="form-control"
                                    ng-model="datos_vendedor.telefono">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="celular">Celular</label>
                                <input type="number" id="celular" class="form-control"
                                    ng-model="datos_vendedor.celular">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="nacimiento">Fecha Nacimiento</label>
                                <input type="date" id="nacimiento" class="form-control"
                                    ng-model="datos_vendedor.nacimiento">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_departamento">Departamento</label>
                                <select name="id_departamento" id="id_departamento" class="form-control"
                                    ng-model="datos_vendedor.id_departamento"
                                    ng-change="CargarCiudades(1,datos_vendedor.id_departamento)">
                                    <option ng-repeat="d in departamento" value="{{d.ID}}">{{d.NOMBRE}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_ciudad">Ciudad</label>
                                <select name="id_ciudad" id="id_ciudad" class="form-control"
                                    ng-model="datos_vendedor.id_ciudad">
                                    <option ng-repeat="c in ciudades" value="{{c.ID}}">{{c.NOMBRE}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="direccion">Dirección</label>
                                <input type="text" id="direccion" class="form-control"
                                    ng-model="datos_vendedor.direccion">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="email">Correo</label>
                                <input type="email" id="email" class="form-control" ng-model="datos_vendedor.email">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_representante">Representante</label>
                                <select name="id_representante" id="id_representante" class="form-control"
                                    ng-model="datos_vendedor.id_representante"
                                    ng-change="CargarAlmacenesRepresentante(datos_vendedor.id_representante)">
                                    <option ng-repeat="r in representante" value="{{r.id}}">{{r.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_almacen">Almacen</label>
                                <select name="id_almacen" id="id_almacen" class="form-control"
                                    ng-model="datos_vendedor.id_almacen">
                                    <option ng-repeat="a in almacenes" value="{{a.id}}">{{a.nombre}}</option>
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label for="id_genero">Genero</label>
                                <select name="id_genero" id="id_genero" class="form-control"
                                    ng-model="datos_vendedor.id_genero">
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenio</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="estado">Estado</label>
                                <select name="estado" id="estado" class="form-control"
                                    ng-model="datos_vendedor.id_estatus">
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
                    </div>
                    <div class="col sm-12 col-md-4" >
                        <div class="info-box">
                            <a href="estado_cuenta.php?id_usuario={{id_usuario}}">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fa fa-pie-chart"></i>

                                </span>
                                <div class="info-box-content">
                                    <br />
                                    <span class="info-box-text">Estado De Cuenta</span>
                                </div>
                            </a>
                            <!-- /.info-box-content -->
                        </div>
                        <div class="info-box">
                            <a href="catalogo.php?id_usuario={{id_usuario}}">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fa fa-gift"></i>
                                </span>
                                <div class="info-box-content">
                                    <br />
                                    <span class="info-box-text">Catalogo</span>
                                </div>
                            </a>
                            <!-- /.info-box-content -->
                        </div>
                        <div class="info-box" data-toggle="modal" data-target="#modal_llamadas"
                            ng-click="ObtenerCategoriasLlamada()" ng-show="datos_usuario.ID_ROL==2">
                            <span class="info-box-icon bg-aqua">
                                <i class="fa fa-phone"></i>
                            </span>
                            <div class="info-box-content">
                                <br />
                                <span class="info-box-text">Llamadas</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <div class="info-box">
                            <a href="redenciones.php?id_usuario={{id_usuario}}">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fa fa-bell"></i>
                                </span>
                                <div class="info-box-content">
                                    <br />
                                    <span class="info-box-text">Redenciones</span>
                                </div>
                            </a>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class=" main-footer">
            <?php include 'componentes/footer.php'; ?>
            <?php include 'componentes/coponentes_js.php'; ?>
        </footer>
    </div>

    <!-- Modal -->
    <div class="modal" id="modal_llamadas">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Captura de llamadas</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 ">
                            <label>
                                <a ng-repeat="item in anteriores track by $index">{{item}} - </a>
                                <span class="fa fa-remove" ng-show="anteriores.length > 0" aria-hidden="true"
                                    ng-click="ObtenerSubcategorias(-1)"></span>
                            </label>
                        </div>
                        <div class="col-lg-12 col-xs-12 ">
                            <label class="form-group" ng-show='subCategorias.length > 0'>
                                Categoria llamada
                                <select class="form-control " id='categoriaLlamada' ng-model='subCategoria'
                                    ng-change='ObtenerSubcategorias(subCategoria)'>
                                    <option value='0' ng-selected="true">Seleccionar</option>
                                    <option ng-repeat="item in subCategorias track by item.ID" value='{{item.ID}}  '>
                                        {{item.NOMBRE}}
                                    </option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-12 col-xs-12 ">
                            Comentarios
                            <textarea class="form-control" rows="4" ng-model='llamada.comentario'
                                maxlength="250"></textarea>
                        </div>
                        <div class="col-lg-12 col-xs-12 ">

                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" ng-click="RegistraLlamada(id_usuario)"
                        ng-disabled=" !(subCategorias.length == 0 && llamada.comentario.length > 0)">
                        Registrar llamada
                    </button>
                    <br />
                    <br />
                    <div>
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example1" class="table table-bordered table-striped dataTable"
                                                role="grid" aria-describedby="example1_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1" aria-sort="ascending"
                                                            aria-label="Rendering engine: activate to sort column descending"
                                                            style="width: 171px;">FECHA</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Browser: activate to sort column ascending"
                                                            style="width: 235px;">CATEGORIA</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Platform(s): activate to sort column ascending"
                                                            style="width: 216px;">REGISTRO</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending"
                                                            style="width: 146px;">COMENTARIO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr role="row" class="even"
                                                        ng-repeat="llamada in llamadas_usuarios track by $index">
                                                        <td class="sorting_1">{{llamada.fecha}}</td>
                                                        <td>{{llamada.categoria}}</td>
                                                        <td>{{llamada.registro}}</td>
                                                        <td>{{llamada.comentario}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>