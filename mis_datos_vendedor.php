<?php include 'componentes/control_sesiones.php'; ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <?php include 'componentes/componentes_basicos.php'; ?>
    <script src="js/mis_datos_vendedor.js?reload=13" type="text/javascript"></script>
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

        $(function() {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
        });
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

        .info-box {
            cursor: pointer;
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
                    <li><a href="bienvenida.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Mis datos</li>
                </ol>
            </section>
            <section class="">
                <div class="row fondo_datos">
                    <div class="col sm-12 col-md-8">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label for="cedula">Cedula</label>
                                <input type="text" id="cedula" ng-disabled="datos_usuario.ID_ROL != 2" class="form-control" ng-model="datos_vendedor.CEDULA">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="cod_formas">Codigo Formas</label>
                                <input type="text" id="cod_formas" disabled class="form-control" ng-model="datos_vendedor.COD_FORMAS">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" ng-disabled="datos_usuario.ID_ROL != 2" class="form-control" ng-model="datos_vendedor.NOMBRE">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="telefono">Telefono</label>
                                <input type="number" id="telefono" class="form-control" ng-model="datos_vendedor.TELEFONO">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="celular">Celular</label>
                                <input type="number" id="celular" class="form-control" ng-model="datos_vendedor.CELULAR">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="nacimiento">Fecha Nacimiento</label>
                                <input type="text" id="datepicker" class="form-control" ng-model="datos_vendedor.NACIMIENTO">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_departamento">Departamento</label>
                                <select name="id_departamento" id="id_departamento" class="form-control" ng-model="datos_vendedor.ID_DEPARTAMENTO" ng-change="CargarCiudades(1,datos_vendedor.ID_DEPARTAMENTO)">
                                    <option ng-repeat="d in departamento" value="{{d.ID}}">{{d.NOMBRE}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_ciudad">Ciudad</label>
                                <select name="id_ciudad" id="id_ciudad" class="form-control" ng-model="datos_vendedor.ID_CIUDAD">
                                    <option ng-repeat="c in ciudades" value="{{c.ID}}">{{c.NOMBRE}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="direccion">Dirección <small>(Opcional)</small></label>
                                <input type="text" id="direccion" class="form-control" ng-model="datos_vendedor.DIRECCION">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="email">Correo</label>
                                <input type="email" id="email" ng-disabled="datos_usuario.ID_ROL != 2" class="form-control" ng-model="datos_vendedor.EMAIL">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_representante">Representante</label>
                                <select name="id_representante" ng-disabled="datos_usuario.ID_ROL != 2" id="id_representante" class="form-control" ng-model="datos_vendedor.ID_REPRESENTANTE" ng-change="CargarAlmacenesRepresentante(1,datos_vendedor.ID_REPRESENTANTE)">
                                    <option ng-repeat="r in representante" value="{{r.id}}">{{r.nombre}}</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="id_almacen">Distribuidora</label>
                                <select name="id_almacen" ng-disabled="datos_usuario.ID_ROL != 2" id="id_almacen" class="form-control" ng-model="datos_vendedor.ID_ALMACEN">
                                    <option ng-repeat="a in almacenes" value="{{a.id}}">{{a.nombre}}</option>
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label for="id_genero">Genero</label>
                                <select name="id_genero" ng-disabled="datos_usuario.ID_ROL != 2" id="id_genero" class="form-control" ng-model="datos_vendedor.ID_GENERO">
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenio</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="estado">Estado</label>
                                <select name="estado" id="estado" ng-disabled="datos_usuario.ID_ROL != 2" class="form-control" ng-model="datos_vendedor.id_estatus">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                    <option value="3">Pendiente Aprobar</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="rol">Rol</label>
                                <select name="rol" id="rol" ng-disabled="datos_usuario.ID_ROL != 2" class="form-control" ng-model="datos_vendedor.id_rol">
                                    <option value="1">Representante</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Visionymarketing</option>
                                    <option value="4">Vendedor</option>
                                    <option value="5">Gerente</option>
                                    <option value="6">Supervisor</option>
                                    <option value="7">Informatico</option>
                                </select>
                            </div>
                            <div class="col-sm-12 text-center">
                                <br>
                                <button ng-click="ActualizarDatosVendedores();" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col sm-12 col-md-4">
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
                        <div class="info-box" data-toggle="modal" data-target="#modal_llamadas" ng-click="ObtenerCategoriasLlamada()" ng-show="datos_usuario.ID_ROL==2">
                            <span class="info-box-icon bg-aqua">
                                <i class="fa fa-phone"></i>
                            </span>
                            <div class="info-box-content">
                                <br />
                                <span class="info-box-text">Llamadas</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <div class="info-box" data-toggle="modal" data-toggle="modal" data-target="#modal_redenciones" ng-click="ObtenerRedenciones()">
                            <span class="info-box-icon bg-aqua">
                                <i class="fa fa-bell"></i>
                            </span>
                            <div class="info-box-content">
                                <br />
                                <span class="info-box-text">Mis redenciones</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <div ng-if="datos_usuario.ID_ROL == 2" class="info-box" ng-click="RestaurarClaveUsuario()">
                            <span class="info-box-icon bg-aqua">
                                <i class="fa fa-lock"></i>
                            </span>
                            <div class="info-box-content">
                                <br />
                                <span class="info-box-text">Restaurar Clave Usuario</span>
                            </div>
                            <!-- /.info-box-content -->

                        </div>
                    </div>
                </div>
            </section>
            <footer class=" main-footer">
                <?php include 'componentes/footer.php'; ?>
                <?php include 'componentes/coponentes_js.php'; ?>
            </footer>
        </div>

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
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 ">
                            <label>
                                <a ng-repeat="item in anteriores track by $index">{{item}} - </a>
                                <span class="fa fa-remove" ng-show="anteriores.length > 0" aria-hidden="true" ng-click="ObtenerSubcategorias(-1)"></span>
                            </label>
                        </div>
                        <div class="col-lg-12 col-xs-12 ">
                            <label class="form-group" ng-show='subCategorias.length > 0'>
                                Categoria llamada
                                <select class="form-control " id='categoriaLlamada' ng-model='subCategoria' ng-change='ObtenerSubcategorias(subCategoria)'>
                                    <option value='0' ng-selected="true">Seleccionar</option>
                                    <option ng-repeat="item in subCategorias track by item.ID" value='{{item.ID}}  '>
                                        {{item.NOMBRE}}
                                    </option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-12 col-xs-12 ">
                            Comentarios
                            <textarea class="form-control" rows="4" ng-model='llamada.comentario' maxlength="250"></textarea>
                        </div>
                        <div class="col-lg-12 col-xs-12 ">

                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" ng-click="RegistraLlamada(id_usuario)" ng-disabled=" !(subCategorias.length == 0 && llamada.comentario.length > 0)">
                        Registrar llamada
                    </button>
                    <br />
                    <br />
                    <div>
                        <div class="box" style="overflow: auto;">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 171px;">FECHA</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 235px;">CATEGORIA</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 216px;">REGISTRO</th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 146px;">COMENTARIO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr role="row" class="even" ng-repeat="llamada in llamadas_usuarios track by $index">
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

    <div class="modal" id="modal_redenciones">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="modal-title">Redenciones</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Premio</th>
                                <th>Fecha Redencion</th>
                                <th>Puntos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="redencion in redenciones track by $index">
                                <td>{{redencion.folio}}</td>
                                <td>{{redencion.premio}}</td>
                                <td>{{redencion.fecha_redencion}}</td>
                                <td>{{redencion.puntos}}</td>
                                <td>{{redencion.operacion}}</td>
                                <td><a ng-show="datos_usuario.ID_ROL == 2" href="estados_redenciones.php?id_redencion={{redencion.folio}}" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>