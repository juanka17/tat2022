angular.module('almacenesApp', []).controller('almacenesController', function($scope, $http) {

    $scope.datos_usuario = datos_usuario;
    $scope.filtros = { nombre: "", visitador: "", territorio: "" };
    $scope.legalizacion = {
        nombre: "",
        documento: "",
        fecha: moment().format("YYYY-MM-DD HH:mm:ss"),
        firma: ""
    };

    // <editor-fold defaultstate="collapsed" desc="Listar Almacenes">

    $scope.CargarAlmacenes = function() {
        var parametros = {};
        if ($scope.datos_usuario.es_administrador == 1) {
            parametros = { catalogo: "almacenes_global" };
        } else if ($scope.datos_usuario.es_administrador == 2) {
            parametros = { catalogo: "almacenes_asistente", id_territorio: $scope.datos_usuario.id_marca };
        } else if ($scope.datos_usuario.es_administrador == 4) {
            parametros = { catalogo: "almacenes_gerente", id_afiliado: $scope.datos_usuario.id };
        } else {
            parametros = { catalogo: "almacenes_visitador", id_afiliado: $scope.datos_usuario.id };
        }

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarAlmacenes);
    };

    $scope.MostrarAlmacenes = function(data) {
        $scope.almacenes = data;
        $scope.SeleccionarListadoAlmacenes();
    };

    $scope.lista_almacenes = Array();
    $scope.SeleccionarListadoAlmacenes = function() {
        $scope.lista_almacenes = Array();
        $scope.cantidad_paginas = 0;
        angular.forEach($scope.almacenes, function(almacen) {
            if (
                ($scope.filtros.nombre.length == 0 || almacen.drogueria.toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) &&
                ($scope.filtros.visitador.length == 0 || almacen.visitador.toLowerCase().indexOf($scope.filtros.visitador.toLowerCase()) > -1) &&
                ($scope.filtros.territorio.length == 0 || almacen.territorio.toLowerCase().indexOf($scope.filtros.territorio.toLowerCase()) > -1)

            ) {
                $scope.lista_almacenes.push(almacen);
            }
        });
    };

    $scope.SeleccionaAlmacen = function(index) {
        document.location.href = "modificar_almacen.php?id_almacen=" + $scope.lista_almacenes[index].id_drogueria;
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Informacion Almacen">    

    $scope.ObtenerInformacionAlmacen = function() {
        var parametros = { catalogo: "almacen_informacion", id_almacen: id_almacen };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarInformacionAlmacen);
    };

    $scope.MostrarInformacionAlmacen = function(data) {
        $scope.almacen = data[0];
    };

    //------------------------------

    /*Cargar Estado De Cuenta*/
    $scope.MostrarEstadoCuenta = true;
    $scope.CargarEcuEmpleadosAlmacen = function() {
        if ($scope.MostrarEstadoCuenta) {
            $scope.MostrarEstadoCuenta = false;
            var parametros = {
                catalogo: "estado_cuenta_vendedores_almacen",
                id_almacen: $scope.almacen.id_drogueria
            };

            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEcuEmpleadosAlmacen);
        }

    };

    $scope.MostrarEcuEmpleadosAlmacen = function(data) {
        $scope.puntos_empleados = data;

        $scope.SeleccionarListadoEmpleados();
    };

    $scope.SeleccionarListadoEmpleados = function() {
        $scope.empleados = Array();
        angular.forEach($scope.puntos_empleados, function(empleado) {
            if ($scope.filtros.nombre.length == 0 || empleado.vendedor.toString().toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) {
                $scope.empleados.push(empleado);
            }
        });


    };

    $scope.VerDetalleEstadoCuenta = function(data) {

        var parametros = {
            catalogo: "estado_cuenta_vendedor_detallado",
            id_vendedor: data
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarEstadoCuentaDetalle);
    };

    $scope.MostrarEstadoCuentaDetalle = function(data) {
        $('#detalleEstadoCuenta').modal('show')
        $scope.puntos_empleado_detallado = data;

    };

    /*----------------------------------*/


    /*-----------Cuotas Distribuidora---------*/

    $scope.CargarCuotasAlmacen = function(data) {
        var parametros = { catalogo: "cuotas_almacen", id_almacen: id_almacen, id_periodo: data };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotasDistribuidora);
    };

    $scope.MostrarCuotasDistribuidora = function(data) {
        $scope.cuotas_distribuidora = data;
        if ($scope.cuotas_distribuidora.length == 0) {
            $scope.crear_nueva_cuota = 0;
        } else {
            $scope.crear_nueva_cuota = 1;
            $scope.ResultadoCreacionNuevoCuota();
        }

    };

    $scope.MostrarCuotaAumentada = function() {
        $scope.cuota_aumentada = Math.round(($("#cuota_ventas").val().replace(/\./g, '') * 1.08));
        $("#cuota_aumentada").html($scope.cuota_aumentada);
    }

    $scope.GuardarCuotasDistribuidora = function(cuota, impactos, mes) {
        var datos = {
            id_almacen: id_almacen,
            id_periodo: mes,
            cuota: $("#cuota_ventas").val().replace(/\./g, ''),
            impactos: $("#cuota_impactos").val().replace(/\./g, ''),
            cuota_aumentada: $scope.cuota_aumentada
        }

        if ($scope.crear_nueva_cuota == 0) {
            var parametros = {
                catalogo: "cuotas_almacen",
                datos: datos,
                id_almacen: id_almacen,
                id_periodo: mes
            };
            $scope.crear_nueva_cuota = 1;
            $scope.EjecutarLlamado("catalogos", "RegistraCatalogoSimple", parametros, $scope.ResultadoCreacionNuevoCuota);
            alert("Cuota creada satisfactoriamente");
        } else if ($scope.crear_nueva_cuota == 1) {
            var parametros = {
                catalogo: "cuotas_almacen",
                datos: datos,
                id_almacen: id_almacen,
                id_periodo: mes,
                id: $scope.cuotas_distribuidora[0].id
            };
            console.log(parametros);
            $scope.EjecutarLlamado("catalogos", "ModificaCatalogoSimple", parametros, $scope.ResultadoCreacionNuevoCuota);
            alert("Cuota creada satisfactoriamente");
        }
    };

    $scope.ResultadoCreacionNuevoCuota = function(data) {

        var parametros = {
            catalogo: "consulta_cuotas_vendedor_supervisor",
            id_almacen: $scope.almacen.id_drogueria
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CargarCuotas);
    };

    $scope.CargarCuotas = function(data) {

        $scope.datos_vendedores = data;
        $scope.cuota_total = 0;
        $scope.datos_vendedores.forEach(element => {
            $scope.cuota_total += element.cuota_vendedor;

        });

        //$scope.MostrarCuotasVendedorSupervisor();

    };
    /*---------------------------------------- */


    //ranking Actual y Ganadores Bimestre

    $scope.CargarTemporadas = true;
    $scope.CargarTemporadasVentasAlmacen = function() {
        index_temporada_almacen = 0;
        if ($scope.CargarTemporadas) {
            $scope.CargarTemporadas = false;
            var parametros = {
                catalogo: "periodos_ventas_almacen",
                id_almacen: $scope.almacen.id_drogueria
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.ProcesarTemporadasVentasAlmacen);
        }
    };

    var index_temporada_almacen = 0;
    $scope.ProcesarTemporadasVentasAlmacen = function(data) {
        $scope.periodos_ventas_almacen = data;
        $scope.CargarCuposAlmacenesTemporada();
    };

    $scope.CargarCuposAlmacenesTemporada = function() {

        var parametros = {
            catalogo: "cupos_almacenes_temporada",
            id_almacen: id_almacen
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuposAlmacenesTemporada);

    };
    $scope.MostrarCuposAlmacenesTemporada = function(data) {
        $scope.cupos_temporada = data[0];
        $scope.CargarRankingTemporadaAlmacen();
        $scope.CargaRedencionesAlmacen();
    };

    $scope.temporadas_ranking = Array();
    $scope.temporadas_ranking_diamante = { ranking: [] };
    $scope.temporadas_ranking_oro = { ranking: [] };
    $scope.temporadas_ranking_plata = { ranking: [] };
    $scope.temporadas_ranking_diamante_0 = { ranking: [] };
    $scope.temporadas_ranking_oro_0 = { ranking: [] };
    $scope.temporadas_ranking_plata_0 = { ranking: [] };
    $scope.temporada_1 = { ranking: [] };

    $scope.CargarRankingTemporadaAlmacen = function() {
        if (index_temporada_almacen < $scope.periodos_ventas_almacen.length) {
            var temporada = {
                nombre: $scope.periodos_ventas_almacen[index_temporada_almacen].bimestre,
                id: $scope.periodos_ventas_almacen[index_temporada_almacen].id,
                ranking: []
            };
            var parametros = {
                catalogo: "cargar_ranking_vendedores_almacen_temporada",
                id_temporada: temporada.id,
                id_almacen: $scope.almacen.id_drogueria
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.AgregarRankingATemporada);

            $scope.temporadas_ranking.push(temporada);
        }

    };

    $scope.index_temporada_activa_vendedores = 0;
    $scope.AgregarRankingATemporada = function(data) {
        if (data[0].activo_redencion == 1) {
            $scope.index_temporada_activa_vendedores = index_temporada_almacen;
        }
        $scope.temporadas_ranking[index_temporada_almacen].ranking = data;
        var puesto = 0;
        var id_categoria_anterior = -1;
        angular.forEach(data, function(categorias) {
            if (index_temporada_almacen == 1) {
                if (categorias.id_categoria == 1 && categorias.id_temporada == 6 && categorias.novedad == 0) {
                    $scope.temporadas_ranking_diamante_0.ranking.push(categorias);
                }
                if (categorias.id_categoria == 2 && categorias.id_temporada == 6 && categorias.novedad == 0) {
                    $scope.temporadas_ranking_oro_0.ranking.push(categorias);
                }
                if (categorias.id_categoria == 3 && categorias.id_temporada == 6 && categorias.novedad == 0) {
                    $scope.temporadas_ranking_plata_0.ranking.push(categorias);
                }
            } else
            if (index_temporada_almacen == 0) {

                if (categorias.id_categoria == 1 && categorias.id_temporada == 5 && categorias.novedad == 0) {
                    $scope.temporadas_ranking_diamante.ranking.push(categorias);
                }
                if (categorias.id_categoria == 2 && categorias.id_temporada == 5 && categorias.novedad == 0) {
                    $scope.temporadas_ranking_oro.ranking.push(categorias);
                }
                if (categorias.id_categoria == 3 && categorias.id_temporada == 5 && categorias.novedad == 0) {
                    $scope.temporadas_ranking_plata.ranking.push(categorias);
                }
            } else {
                if (categorias.id_temporada == 1 && categorias.novedad == 0) {
                    $scope.temporada_1.ranking.push(categorias);
                }
            }

            if (id_categoria_anterior != categorias.id_categoria) {
                id_categoria_anterior = categorias.id_categoria;
                puesto = 0;
            }
            puesto++;
            categorias.puesto = puesto;
        });

        if ($scope.temporada_1.ranking.length > 0) {
            $scope.temporada_1.ranking = $scope.temporada_1.ranking.sort(function(a, b) {
                return parseFloat(b.puntos) - parseFloat(a.puntos);
            });
        }


        index_temporada_almacen++;
        $scope.CargarRankingTemporadaAlmacen();
    };

    //---------------------------------------

    // Entregas
    $scope.MostrarRedenciones = true;
    $scope.CargaRedencionesAlmacen = function() {
        if ($scope.MostrarRedenciones) {
            $scope.MostrarRedenciones = false;
            var parametros = {
                catalogo: "redenciones_almacen",
                id_almacen: $scope.almacen.id_drogueria
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRedencionesAlmacen);
        }

    };

    $scope.MostrarRedencionesAlmacen = function(data) {
        $scope.redenciones = data;
        $scope.CalcularEstadosRedenciones();

    };

    $scope.CalcularEstadosRedenciones = function() {
        $scope.redenciones_temporada = 0;
        $scope.redenciones_procesadas = 0;

        $scope.temporadas_por_legalizar = Array();
        $scope.temporadas_activas = Array();
        angular.forEach($scope.redenciones, function(redencion) {

            if (redencion.id_temporada == id_temporada_en_redencion && redencion.clasificacion == "clasificacion") {
                $scope.redenciones_temporada++;
            }

            if (redencion.id_operacion == 4 || redencion.id_operacion == 8) {
                $scope.redenciones_procesadas++;

                if (!ValidateIfObjectExist($scope.temporadas_por_legalizar, "id", redencion.id_temporada)) {
                    $scope.temporadas_por_legalizar.push({ id: redencion.id_temporada, nombre: redencion.temporada });
                }
            }
            if (!ValidateIfObjectExist($scope.temporadas_activas, "id", redencion.id_temporada)) {
                $scope.temporada_seleccionada = 0;
                $scope.temporadas_activas.push({ id: redencion.id_temporada, nombre: redencion.temporada });
            }
        });
    };

    $scope.SeleccionarTemporadaEntregas = function(data) {
        $scope.temporada_seleccionada = data;
    };
    //----------------------------------------

    //Supervisores
    $scope.MostrarSupervisor = true;
    $scope.CargarCuotasSupervisor = function() {
        if ($scope.MostrarSupervisor) {
            $scope.MostrarSupervisor = false;
            var parametros = {
                catalogo: "consulta_cuotas_supervisor",
                id_almacen: $scope.almacen.id_drogueria
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotasSupervisor);
        }

    };

    $scope.MostrarCuotasSupervisor = function(data) {
        var temporada = { id_temporada: 0, temporada: "", periodo_1: "", periodo_2: "", supervisores: [] };
        var cuotas = [];

        angular.forEach(data, function(cuota) {
            cuotas.push(temporada);
            temporada = { id_temporada: 0, temporada: "", periodo_1: "", periodo_2: "", supervisores: [] };
            temporada.supervisores.push(cuota);
        });
        cuotas.push(temporada);
        $scope.cuotas_supervisor = cuotas;
        $scope.CargarCuotasVendedor();
    };

    $scope.CargarCuotasVendedor = function() {
        var parametros = {
            catalogo: "consulta_cuotas_vendedor",
            id_almacen: $scope.almacen.id_drogueria
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotasVendedor);
    };

    $scope.MostrarCuotasVendedor = function(data) {
        var temporada = { id_temporada: 0, temporada: "", periodo_1: "", periodo_2: "", vendedores: [] };
        var cuotas = [];
        $scope.datos_vendedores = data;
        angular.forEach(data, function(cuota) {
            if (temporada.temporada != cuota.temporada) {
                if (temporada.id_temporada > 0) {
                    cuotas.push(temporada);
                    temporada = { id_temporada: 0, temporada: "", periodo_1: "", periodo_2: "", vendedores: [] };
                }
                temporada.temporada = cuota.temporada;
                temporada.periodo_1 = cuota.periodos.split("|")[0];
                temporada.periodo_2 = cuota.periodos.split("|")[1];
            }

            temporada.id_temporada = cuota.id_temporada;
            temporada.vendedores.push(cuota);
        });
        cuotas.push(temporada);

        $scope.cuotas_vendedores = cuotas;
        $scope.SeleccionarListadoEmpleadosCuotas();
    };

    $scope.SeleccionarListadoEmpleadosCuotas = function() {
        $scope.empleados_cuotas = Array();

        angular.forEach($scope.datos_vendedores, function(empleado_cuotas) {
            if ($scope.filtros.nombre.length == 0 || empleado_cuotas.supervisor.toString().toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) {

                $scope.empleados_cuotas.push(empleado_cuotas);
            }
        });

        $scope.empleados_cuotas = $scope.empleados_cuotas.sort(function(a, b) {
            return parseFloat(b.puntos) - parseFloat(a.puntos);
        });
    };

    //----------------------------------

    // inforcacion alamcenes
    $scope.datosAlmacenes = true;
    $scope.CargarCuposAlmacenes = function() {
        if ($scope.datosAlmacenes) {
            $scope.datosAlmacenes = false;
            var parametros = {
                catalogo: "cupos_almacenes",
                id_almacen: id_almacen
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuposAlmacenes);
        }


    };
    $scope.MostrarCuposAlmacenes = function(data) {
        $scope.cupos_almacenes = data;
    };
    //--------------

    // Terminos y condiciones
    $scope.verTerminosCondiciones = true;
    $scope.ObtenerDocumentoHabeasData = function() {
        if ($scope.verTerminosCondiciones) {
            $scope.verTerminosCondiciones = false;
            var parametros = {
                catalogo: "habeas_data",
                id_almacen: $scope.almacen.id_drogueria
            };

            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDocumentoHabeasData);
        }

    };

    $scope.MostrarDocumentoHabeasData = function(data) {
        $scope.habeas_data = data;
    };

    $scope.AbrirModalFirmaVendedor = function() {
        clearCanvasVendedor();
        $scope.legalizacion.firma = "";
    };

    $scope.GuardarFirmaVendedor = function() {
        $scope.legalizacion.firma = $("#sig-dataUrl").val();
        $("#sig-image-confirmed").attr("src", $("#sig-image").attr("src"));
    };

    $scope.LimpiarFirmaVendedor = function() {
        $scope.legalizacion.firma = "";
    };

    $scope.LegalizarRedencion = function() {
        var legalizacion_completa = true;
        angular.forEach($scope.legalizacion, function(dato) {
            if (dato == "") {
                legalizacion_completa = false;
            }
        });

        if (legalizacion_completa) {
            $scope.GuardarActa();
        } else {
            alert("Debe completar toda la información y firmar");
        }
    };

    $scope.GuardarActa = function() {
        $scope.documento_html = $("#img_perfil").html();
        $scope.boton = 0;
        var estado_nuevo = {
            id_almacen: $scope.almacen.id_drogueria,
            nombre: $scope.legalizacion.nombre,
            documento: $scope.legalizacion.documento,
            tipo_acta: 0,
            firma: $scope.documento_html,
            fecha: moment().format("YYYY-MM-DD HH:mm:ss")
        };

        var parametros = {
            catalogo: "habeas_data",
            datos: estado_nuevo,
            id_almacen: $scope.almacen.id_drogueria
        };

        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoSimple", parametros, $scope.FinalizacionLegalizacion);

    };

    $scope.FinalizacionLegalizacion = function() {

        location.reload();
    };

    // ---------------------------------------

    //cuotas vendedores
    // <editor-fold defaultstate="collapsed" desc="Cuotas Vendedores">
    /*
    $scope.vercuotasvendedores = true;
    $scope.CargarCuotasVendedorSupervisor = function() {
        if ($scope.vercuotasvendedores) {
            $scope.vercuotasvendedores = false;
            var parametros = {
                catalogo: "consulta_cuotas_vendedor_supervisor",
                id_almacen: $scope.almacen.id_drogueria
            };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuotasVendedorSupervisor);
        }

    };

    $scope.MostrarCuotasVendedorSupervisor = function(data) {
        $scope.datos_vendedores = data;
        console.log($scope.datos_vendedores);
    };*/

    $scope.SeleccionarListadoEmpleadosCuotasSupervisor = function() {
        $scope.empleados_cuotas_supervisor = Array();
        angular.forEach($scope.datos_vendedores, function(emp) {
            if ($scope.filtros.nombre.length == 0 || emp.supervisor.toString().toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) {

                $scope.empleados_cuotas_supervisor.push(emp);
            }
        });

        $scope.empleados_cuotas_supervisor = $scope.empleados_cuotas_supervisor.sort(function(a, b) {
            return parseFloat(b.puntos) - parseFloat(a.puntos);
        });
    };

    $scope.ActualizarCuotasVendedores = function(id_cuota, id_clasificacion, cuota_1, cuota_2) {
        $scope.ActualizarCuotaIdCuota = id_cuota;
        $scope.ActualizarCuotaIdClasificacion = id_clasificacion;
        $scope.ActualizarCuotaCuota1 = cuota_1;
        $scope.ActualizarCuotaCuota2 = cuota_2;
        $("#actualizar_cuota").modal("show");
    };

    $scope.ActualizarCuotasCargadas = function() {
        $scope.botonactualizarcuota = false;
        $scope.actualizarinformacion = false;
        $scope.cuota_1 = $("#cuota_1").val().replace(/\./g, '');
        $scope.cuota_2 = $("#cuota_2").val().replace(/\./g, '');

        if ($scope.ActualizarCuotaIdClasificacion == 1) {
            if ($scope.cuota_1 >= 5000000 && $scope.cuota_2 >= 5000000) {
                $scope.actualizarinformacion = true;
            }
        }

        if ($scope.ActualizarCuotaIdClasificacion == 2) {
            if ($scope.cuota_1 >= 2500000 && $scope.cuota_2 >= 2500000) {
                $scope.actualizarinformacion = true;
            }
        }

        if ($scope.ActualizarCuotaIdClasificacion == 3) {
            if ($scope.cuota_1 >= 1000000 && $scope.cuota_2 >= 1000000) {
                $scope.actualizarinformacion = true;
            }
        }

        if ($scope.ActualizarCuotaIdClasificacion == 4) {
            if ($scope.cuota_1 >= 500000 && $scope.cuota_2 >= 500000) {
                $scope.actualizarinformacion = true;
            }
        }

        if ($scope.actualizarinformacion) {
            var datos = {
                cuota_1: $scope.cuota_1,
                cuota_2: $scope.cuota_2
            };

            var parametros = {
                catalogo_real: "cuotas",
                catalogo: "obtener_periodo",
                datos: datos,
                id: $scope.ActualizarCuotaIdCuota
            };
            $scope.EjecutarLlamado("catalogos", "ModificaCatalogoMixto", parametros, $scope.ResultadoActualizacionCuotas);
        } else {
            alert("No cumple el valor mínimo para su categoría");
            $scope.botonactualizarcuota = true;
        }

    };

    $scope.ResultadoActualizacionCuotas = function(data) {
        $("#actualizar_cuota").modal("hide");
        alert("Cuotas Actualizadas");
        $scope.botonactualizarcuota = true;
        //$scope.CargarCuotasVendedorSupervisor();
        $scope.GuardarCambioCuotas();

    };

    $scope.GuardarCambioCuotas = function() {

        var datos = {
            id_cuota: $scope.ActualizarCuotaIdCuota,
            cuota_1_anterior: $scope.ActualizarCuotaCuota1,
            cuota_2_anterior: $scope.ActualizarCuotaCuota2,
            cuota_1_nueva: $scope.cuota_1,
            cuota_2_nueva: $scope.cuota_2,
            id_actualiza: datos_usuario.id

        };
        var parametros = {
            catalogo: "log_cuotas",
            datos: datos
        };

        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoSimple", parametros, $scope.CargarCuotasVendedorSupervisor);
    };
    // </editor-fold>
    //----------------------------------------

    //crear afiliado
    // <editor-fold defaultstate="collapsed" desc="Crear Vendedores">

    $scope.nuevo_afiliado = { id_supervisor: "", nombre: "", id_categoria: "", cuota_1: "", cuota_2: "" };
    $scope.BuscarSupervisor = function() {
        var parametros = {
            catalogo: "supervisores_almacen",
            id_almacen: id_almacen
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarSupervisores);

    };

    $scope.MostrarSupervisores = function(data) {
        $scope.supervisores_afiliados = data;
        $scope.BuscarTemporadaActual();
    };

    $scope.BuscarTemporadaActual = function() {
        var parametros = {
            catalogo: "temporada_actual"
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarTemporadaActual);

    };

    $scope.MostrarTemporadaActual = function(data) {
        $scope.temporada_actual = data[0].id_temporada;
        $scope.ObtenerCodFormas();
    };

    $scope.ObtenerCodFormas = function() {
        var parametros = {
            catalogo: "obtener_cod_formas",
            id_clasificacion: 6
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCodFormas);
    };

    $scope.MostrarCodFormas = function(data) {
        let id = data[0].cod_formas;
        $scope.cod_formas = id + 1;
    };

    $scope.CrearNuevoUsuario = function() {
        $scope.actualizarinformacionnuevousuario = false;
        if ($scope.nuevo_afiliado.id_categoria == 1) {
            $scope.impactos = 100;
            if ($scope.nuevo_afiliado.cuota_1 >= 5000000 && $scope.nuevo_afiliado.cuota_2 >= 5000000) {
                $scope.actualizarinformacionnuevousuario = true;
            }
        }

        if ($scope.nuevo_afiliado.id_categoria == 2) {
            $scope.impactos = 75;
            if ($scope.nuevo_afiliado.cuota_1 >= 2500000 && $scope.nuevo_afiliado.cuota_2 >= 2500000) {
                $scope.actualizarinformacionnuevousuario = true;
            }
        }

        if ($scope.nuevo_afiliado.id_categoria == 3) {
            $scope.impactos = 45;
            if ($scope.nuevo_afiliado.cuota_1 >= 1000000 && $scope.nuevo_afiliado.cuota_2 >= 1000000) {
                $scope.actualizarinformacionnuevousuario = true;
            }
        }

        if ($scope.nuevo_afiliado.id_categoria == 4) {
            $scope.impactos = 20;
            if ($scope.nuevo_afiliado.cuota_1 >= 500000 && $scope.nuevo_afiliado.cuota_2 >= 500000) {
                $scope.actualizarinformacionnuevousuario = true;
            }
        }
        if ($scope.actualizarinformacionnuevousuario) {
            var parametros = {
                catalogo: "afiliados",
                id_supervisor: $scope.nuevo_afiliado.id_supervisor,
                nombre: $scope.nuevo_afiliado.nombre,
                id_categoria: $scope.nuevo_afiliado.id_categoria,
                cuota_1: $scope.nuevo_afiliado.cuota_1,
                cuota_2: $scope.nuevo_afiliado.cuota_2,
                impactos: $scope.impactos,
                id_almacen: id_almacen,
                id_temporada: $scope.temporada_actual,
                cod_formas: $scope.cod_formas,
                id_registra: datos_usuario.id
            };
            $scope.EjecutarLlamado("afiliados", "CrearNuevoUsuario", parametros, $scope.ResultadoCreacionNuevoUsuario);
        } else {
            alert("No cumple el valor minimo para su categoria");
        }
    };

    $scope.ResultadoCreacionNuevoUsuario = function(data) {
        if (data.ok) {
            alert("Usuario creado satisfactoriamente");
            location.reload();
        } else {
            alert("Error en la creación");
        }
    };
    // </editor-fold>
    //----------------------

    var catalogos = ["departamento"];


    var indexAsist = 0;
    $scope.CargarCatalogosIniciales = function() {
        if (indexAsist < catalogos.length) {
            var parametros = { catalogo: catalogos[indexAsist] };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CrearCatalogo);
        } else {
            indexAsist = 0;
            $scope.CargaCiudadesDepartamento();
        }
    };

    $scope.CrearCatalogo = function(data) {
        $scope[catalogos[indexAsist]] = data;
        indexAsist++;
        $scope.CargarCatalogosIniciales();
    };

    $scope.CargaCiudadesDepartamento = function() {
        if ($scope.almacen.id_departamento != null) {
            var parametros = { catalogo: "ciudad", departamento: $scope.almacen.id_departamento };
            $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.SeleccionarCiudadAfiliado);
        } else {
            $scope.CargarInformacionPeriodo();
        }
    };

    $scope.SeleccionarCiudadAfiliado = function(data) {
        $scope.ciudad = data;
        $scope.CargarInformacionPeriodo();
    };

    $scope.ModificarAlmacen = function() {
        var datos = {
            nombre: $scope.almacen.nombre,
            direccion: $scope.almacen.direccion,
            telefono: $scope.almacen.telefono,
            id_ciudad: $scope.almacen.id_ciudad
        };

        var parametros = {
            catalogo: "almacenes",
            datos: datos,
            id: $scope.almacen.id_drogueria
        };

        $scope.EjecutarLlamado("catalogos", "ModificaCatalogoSimple", parametros, $scope.ResultadoModificarAlmacen);
    };

    $scope.ResultadoModificarAlmacen = function(data) {
        data.almacen = data[0];
        $scope.operacionCorrectaMensaje = "Modificación realizada correctamente.";
    };

    // </editor-fold>



    // <editor-fold defaultstate="collapsed" desc="Información General Almacen">

    $scope.CargarInformacionPeriodo = function() {
        var parametros = { catalogo: "obtener_periodo" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.SelccionarPeriodo);
    };

    $scope.SelccionarPeriodo = function(data) {
        $scope.periodo = data[0];
    };












    $scope.CargarGanadoresAlmacenCiclo2 = function() {
        var parametros = { catalogo: "consulta_ganadores_ciclo2", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGanadoresAlmacenCiclo2);
    };

    $scope.MostrarGanadoresAlmacenCiclo2 = function(data) {
        $scope.ganadores_almacen_ciclo2 = data;
        $scope.CargarGanadoresAlmacenCiclo3();
    };

    $scope.CargarGanadoresAlmacenCiclo3 = function() {
        var parametros = { catalogo: "consulta_ganadores_ciclo3", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGanadoresAlmacenCiclo3);
    };

    $scope.MostrarGanadoresAlmacenCiclo3 = function(data) {
        $scope.ganadores_almacen_ciclo3 = data;
        $scope.CargarGanadoresAlmacenCiclo4();
    };

    $scope.CargarGanadoresAlmacenCiclo4 = function() {
        var parametros = { catalogo: "consulta_ganadores_ciclo4", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGanadoresAlmacenCiclo4);
    };

    $scope.MostrarGanadoresAlmacenCiclo4 = function(data) {
        $scope.ganadores_almacen_ciclo4 = data;
        $scope.CargarGanadoresAlmacenCiclo5();
    };

    $scope.CargarGanadoresAlmacenCiclo5 = function() {
        var parametros = { catalogo: "consulta_ganadores_ciclo5", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGanadoresAlmacenCiclo5);
    };

    $scope.MostrarGanadoresAlmacenCiclo5 = function(data) {
        $scope.ganadores_almacen_ciclo5 = data;
        $scope.CargarGanadoresAlmacenTotal();
    };

    $scope.CargarGanadoresAlmacenTotal = function() {
        var parametros = { catalogo: "consulta_ganadores_anual", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarGanadoresAlmacenTotal);
    };

    $scope.MostrarGanadoresAlmacenTotal = function(data) {
        $scope.ganadores_anual = data;

        $scope.novedades_anual = 0;
        angular.forEach($scope.ganadores_anual, function(ganador, index) {
            $scope.novedades_anual += ganador.negacion;
        });

        $scope.CargarCuposCierreAnual();
    };

    $scope.CargarCuposCierreAnual = function() {
        var parametros = { catalogo: "cupos_ranking_anual", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarCuposCierreAnual);
    };

    $scope.MostrarCuposCierreAnual = function(data) {
        $scope.cupos_cierre_anual = data[0];
    };

    $scope.SeleccionarRankingAnual = function(index) {
        $scope.ranking_anual_seleccionado = $scope.ganadores_anual[index];
        $scope.ranking_anual_seleccionado.razon = "";
    };

    $scope.DenegarRankingAnual = function(index) {
        var datos = {
            id_afiliado: $scope.ranking_anual_seleccionado.id_vendedor,
            razon: $scope.ranking_anual_seleccionado.razon
        };

        var parametros = {
            catalogo: "cupos_ranking_anual",
            catalogo_real: "negacion_ranking_anual",
            datos: datos,
            id_almacen: $scope.almacen.id_drogueria
        };

        $("#modalDenegarRankingAnual").modal("hide");
        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixto", parametros, $scope.CargarGanadoresAlmacenTotal);
    };

    $scope.ActualizarEntregas = function() {
        var parametros = { catalogo: "redenciones_almacen", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarRedencionesAlmacenActualizadas);
    };

    $scope.MostrarRedencionesAlmacenActualizadas = function(data) {
        $scope.redenciones = data;
        $scope.CalcularEstadosRedenciones();
    };

    $scope.VerDetalleVentas = function(id_empleado) {
        var parametros = { catalogo: "ventas_vendedor", id_afiliado: id_empleado };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVentasVendedor);
    };

    $scope.MostrarVentasVendedor = function(data) {
        $scope.ventas_vendedor = data;
        $scope.ventas_periodo = Array();

        var ventas_consolidadas = Array();
        var venta_categoria = { id_portafolio: "", portafolio: "", categoria: "", unidades: 0, venta_total: 0, detalle: [] };
        var periodo = { id_periodo: 0, periodo: "", impactos: 0, ventas: 0 };

        angular.forEach($scope.ventas_vendedor, function(venta) {

            if (venta.categoria != venta_categoria.categoria) {
                if (venta_categoria.categoria != '')
                    ventas_consolidadas.push(venta_categoria);

                venta_categoria = { id_portafolio: "", portafolio: "", categoria: "", unidades: 0, venta_total: 0, detalle: [] };
                venta_categoria.categoria = venta.categoria;
            }

            venta_categoria.id_portafolio = venta.id_portafolio;
            venta_categoria.portafolio = venta.portafolio;
            venta_categoria.unidades += venta.unidades;
            venta_categoria.venta_total += venta.venta_total;
            venta_categoria.detalle.push(venta);

            if (periodo.id_periodo != venta.id_periodo) {
                if (periodo.id_periodo > 0) {
                    periodo.detalle = ventas_consolidadas;
                    $scope.ventas_periodo.push(periodo);
                    ventas_consolidadas = Array();
                    periodo = { id_periodo: 0, periodo: "", impactos: 0, ventas: 0 };
                }
            }

            periodo.id_periodo = venta.id_periodo;
            periodo.periodo = venta.periodo;
            periodo.impactos += venta.unidades;
            periodo.ventas += venta.venta_total;

        });

        ventas_consolidadas.push(venta_categoria);

        periodo.detalle = ventas_consolidadas;
        $scope.ventas_periodo.push(periodo);
    };

    $scope.IniciarDenegacionRedencion = function(index_temporada, index) {
        $scope.encuesta_seleccionada = $scope.temporadas_ranking[index_temporada].ranking[index];
        $scope.encuesta_seleccionada.razon_denegacion = "";
    };
    $scope.razon_denegacion = "";
    $scope.DenegarRedencion = function() {

        if ($scope.razon_denegacion != "") {
            var datos = {
                novedad: 1,
                comentario: $scope.razon_denegacion
            };

            var parametros = {
                catalogo: "estado_cuenta",
                datos: datos,
                id: $scope.id_estado_cuenta_negacion
            };

            $scope.EjecutarLlamado("catalogos", "ModificaCatalogoSimple", parametros, $scope.CargarTemporadasVentasAlmacen);
            $scope.LimpiarDenegacion();
        } else {
            alert("Debe indicar una razón de denegación");
        }
    };

    $scope.LimpiarDenegacion = function() {
        $scope.encuesta_seleccionada = {};
        $('#modalDenegarRedencion').modal('hide');
    };

    // </editor-fold>    

    // <editor-fold defaultstate="collapsed" desc="Modificar estados de las redenciones del almacen">

    $scope.EjecutarCambioEstadoPremio = function(id_redencion, id_operacion) {
        var estado_nuevo = {
            id_redencion: id_redencion,
            id_operacion: id_operacion,
            fecha_operacion: moment().format("YYYY-MM-DD HH:mm:ss"),
            id_usuario: datos_usuario.id,
            comentario: ""
        };

        var parametros = {
            catalogo: "redenciones_almacen",
            catalogo_real: "seguimiento_redencion",
            datos: estado_nuevo,
            id_almacen: id_almacen
        };

        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoMixto", parametros, $scope.MostrarRedencionesAlmacen);
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Ver documento legalización">

    $scope.ObtenerDocumentoLegalizacion = function(id_redencion) {
        var parametros = {
            catalogo: "documento_legalizacion",
            id_redencion: id_redencion
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDocumentoLegalizacion);
    };

    $scope.MostrarDocumentoLegalizacion = function(data) {
        var winpops = window.open('', "test", "fullscreen=no,toolbar=no,status=no, " +
            "menubar=no,scrollbars=no,resizable=no,directories=no,location=no, " +
            "width=500,height=400,left=100,top=100,screenX=100,screenY=100");
        winpops.document.write(data[0].comentario);
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Captura Llamadas">

    $scope.subCategoria = 0;
    $scope.subcategorias = Array();
    $scope.anteriores = Array();
    $scope.categorias_anteriores = Array();
    $scope.subCategoriaSeleccionada = 0;
    $scope.comentarioLlamadas = '';
    $scope.llamadas_afiliado = null;
    $scope.llamada = { COMENTARIO: "", ID_SUBCATEGORIA: 0 };
    var categorias_llamada = null;

    $scope.ObtenerCategoriasLlamadas = function() {
        var parametros = { catalogo: "categorias_llamada" };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CargarSubCategorias);
        $scope.ObtenerLlamadas();
    };

    $scope.CargarSubCategorias = function(data) {
        categoriasLlamada = data;
        $scope.ObtenerSubcategorias(0);
    };

    $scope.ObtenerSubcategorias = function(idParam) {
        $scope.llamada.ID_SUBCATEGORIA = $scope.subCategoria;
        var id = idParam;
        $scope.subCategoriaSeleccionada = id;
        if (idParam == -1) {
            id = $scope.categoriasAnteriores[$scope.categoriasAnteriores.length - 1].ID_PADRE;
            $scope.categoriasAnteriores.pop();
            $scope.anteriores.pop();
            $scope.categoriasAnteriores.pop();
            $scope.anteriores.pop();
        }

        if (idParam == 0) {
            $scope.categoriasAnteriores = Array();
            $scope.anteriores = Array();
        }

        $scope.subCategorias = Array();
        angular.forEach(categoriasLlamada, function(subCategoria) {
            if (id == subCategoria.ID_PADRE)
                $scope.subCategorias.push(subCategoria);

            if (id != 0 && id == subCategoria.ID) {
                $scope.categoriasAnteriores.push(subCategoria);
                $scope.anteriores.push(subCategoria.NOMBRE);
            }
        });
    };

    $scope.ObtenerLlamadas = function() {
        var parametros = { catalogo: "llamadas_almacen", id_almacen: $scope.almacen.id_drogueria };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarLlamadas);
    };

    $scope.MostrarLlamadas = function(data) {
        $scope.subCategoria = 0;
        $scope.subcategorias = Array();
        $scope.anteriores = Array();
        $scope.categorias_anteriores = Array();
        $scope.subCategoriaSeleccionada = 0;
        $scope.comentarioLlamadas = '';
        $scope.llamadas_afiliado = null;
        $scope.llamada = { COMENTARIO: "", ID_SUBCATEGORIA: 0, QUIEN_LLAMA: $scope.almacen.visitador };
        categorias_llamada = null;

        $scope.llamadas_afiliado = data;
    };

    $scope.RegistraLlamada = function() {
        $scope.llamada.ID_ALMACEN = $scope.almacen.id_drogueria;
        $scope.llamada.FECHA = moment().format("YYYY-MM-DD HH:mm:ss");
        $scope.llamada.ID_USUARIO = $scope.datos_usuario.id;
        var parametros = { catalogo: "llamadas_almacen", id_almacen: $scope.almacen.id_drogueria, datos: $scope.llamada };
        $scope.EjecutarLlamado("catalogos", "RegistraCatalogoSimple", parametros, $scope.MostrarLlamadas);
    };

    // </editor-fold>







    // <editor-fold defaultstate="collapsed" desc="Inactivar Usuarios">

    $scope.BuscarVendedoresAlmacen = function() {
        var parametros = {
            catalogo: "vendedores_almacen",
            id_almacen: id_almacen
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarVendedoresAlmacen);
    };

    $scope.MostrarVendedoresAlmacen = function(data) {
        $scope.vendedeores_inactivar = data;
        $scope.SeleccionarListadoVendedoresInactivar();

    };

    $scope.SeleccionarListadoVendedoresInactivar = function() {
        $scope.inactivar_empleado = Array();
        angular.forEach($scope.vendedeores_inactivar, function(emp) {
            if ($scope.filtros.nombre.length == 0 || emp.nombre.toString().toLowerCase().indexOf($scope.filtros.nombre.toLowerCase()) > -1) {

                $scope.inactivar_empleado.push(emp);
            }
        });

    };

    $scope.InactivarVendedor = function(id) {
        var opcion = confirm("¿Desea Inactivar este usuario?");
        if (opcion == true) {

            var datos = {
                id_estatus: 5,
                id_inactiva: datos_usuario.id
            };

            var parametros = {
                catalogo_real: "afiliados",
                catalogo: "vendedores_almacen",
                datos: datos,
                id: id,
                id_almacen: id_almacen
            };

            $scope.EjecutarLlamado("catalogos", "ModificaCatalogoMixto", parametros, $scope.ResultadoInactivacionUsuario);
        }
    };

    $scope.ResultadoInactivacionUsuario = function() {
        $("#inactivar_usuarios").modal("hide");
    };
    // </editor-fold>

    $scope.EjecutarLlamado = function(modelo, operacion, parametros, CallBack) {
        $http({
            method: "POST",
            url: "clases/jarvis.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: { modelo: modelo, operacion: operacion, parametros: parametros }
        }).success(function(data) {
            if (data.error == "") {
                CallBack(data.data);
            } else {
                $scope.errorGeneral = data.error;
            }
        });
    };

    if (typeof mostrar_almacenes != 'undefined' && mostrar_almacenes) {
        $scope.CargarAlmacenes();
    }

    if (typeof seleccionar_almacen != 'undefined' && seleccionar_almacen) {
        $scope.ObtenerInformacionAlmacen();
    }
});

function ValidateIfObjectExist(array, key, new_value) {
    var exist = false;

    $.each(array, function(object) {
        Object.keys(array[object]).forEach(function(key) {
            if (array[object][key] == new_value) {
                exist = true;
            }
        });
    });

    return exist;
}

var canvas = null;
var ctx = null;
$(function() {
    $('#singModal').on('shown.bs.modal', function() {
        if (canvas == null) {
            canvas = document.getElementById("sig-canvas");
        }
        var width = $("#singModal").width();
        var height = $("#singModal").height();
        width = width - ((width * 0.2));
        height = height - ((height * 0.2));
        canvas.width = width;
        canvas.height = height;

        ctx = canvas.getContext("2d");
        ctx.strokeStyle = "#222222";
        ctx.lineWith = 2;

        var sigText = document.getElementById("sig-dataUrl");
        var sigImage = document.getElementById("sig-image");
        //var clearBtn = document.getElementById("singModal");
        var submitBtn = document.getElementById("sig-submitBtn");
        submitBtn.addEventListener("click", function(e) {
            var dataUrl = canvas.toDataURL();
            sigText.innerHTML = dataUrl;
            sigImage.setAttribute("src", dataUrl);
        }, false);
    });
});

function clearCanvasVendedor() {

    if (canvas != null) {
        canvas.width = canvas.width;
        var sigText = document.getElementById("sig-dataUrl");
        var sigImage = document.getElementById("sig-image");
        sigText.innerHTML = "Data URL for your signature will go here!";
        sigImage.setAttribute("src", "");
    }
}