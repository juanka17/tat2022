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
        var parametros = {
            catalogo: "almacen_informacion",
            id_almacen: id_almacen
        };
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
        $scope.periodo_seleccionado = data;

        var parametros = {
            catalogo: "cuotas_almacen",
            id_almacen: id_almacen,
            id_periodo: data
        };
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
        var margen1 = $scope.almacen.margen / 100;
        var margen2 = 1 + $scope.almacen.margen / 100;
        $scope.cuota_aumentada = Math.round(($("#cuota_ventas").val().replace(/\./g, '') * margen1));
        $scope.margen = Math.round(($("#cuota_ventas").val().replace(/\./g, '') * margen2));
        $("#cuota_aumentada").html($scope.margen);
    }

    $scope.GuardarCuotasDistribuidora = function(cuota, impactos, mes) {
        var datos = {
            id_almacen: id_almacen,
            id_periodo: mes,
            cuota: $("#cuota_ventas").val().replace(/\./g, '')
                //impactos: $("#cuota_impactos").val().replace(/\./g, ''),
                //cuota_aumentada: $scope.margen
        }

        if ($scope.crear_nueva_cuota == 0) {
            var parametros = {
                catalogo: "actualizar_cuotas_kam",
                datos: datos,
                id_almacen: id_almacen,
                id_periodo: mes
            };
            $scope.crear_nueva_cuota = 1;
            $scope.EjecutarLlamado("afiliados", "actualizar_cuotas_kam", parametros, $scope.ResultadoCreacionNuevoCuota);
            alert("Cuota creada satisfactoriamente");
        } else if ($scope.crear_nueva_cuota == 1) {
            var parametros = {
                catalogo: "cuotas_almacen",
                datos: datos,
                id_almacen: id_almacen,
                id_periodo: mes,
                id: $scope.cuotas_distribuidora[0].id
            };
            $scope.EjecutarLlamado("afiliados", "actualizar_cuotas_kam", parametros, $scope.ResultadoCreacionNuevoCuota);

        }
    };

    $scope.ResultadoCreacionNuevoCuota = function(data) {
        document.getElementById("overlay").style.display = "block";
        $scope.total = 0;
        var parametros = {
            catalogo: "consulta_cuotas_vendedor_supervisor",
            id_almacen: $scope.almacen.id_drogueria,
            id_periodo: $scope.mes_cuota_seleccionado
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.CargarCuotas);
    };

    $scope.CargarCuotas = function(data) {
        $scope.cuota_supervisor = [];
        $scope.datos_vendedores = data;
        var periodos = Array();
        var periodo_actual = { id_supervisor: 0, supervisor: "", cuota_supervisor: 0, registros: [] };

        angular.forEach(data, function(registro) {

            if (periodo_actual.id_supervisor != registro.id_supervisor) {
                periodos.push(periodo_actual);
                periodo_actual = { id_supervisor: 0, supervisor: "", registros: [] };
                periodo_actual.id_supervisor = registro.id_supervisor;
                periodo_actual.supervisor = registro.supervisor;
                periodo_actual.registros = [];
            }
            periodo_actual.registros.push(registro);

            $scope.cuota_supervisor.push({
                "id_supervisor": registro.id_supervisor,
                "cuota": registro.cuota_vendedor,
                "cuota_impactos": registro.cuota_impactos,
                "cuota_impactos_modificados": registro.impactos_modificados
            })

        });


        //Obtenemos solo las claves. 
        let claves = $scope.cuota_supervisor.map(x => x.id_supervisor)
            //Quitamos repetidos

        claves = Array.from(new Set(claves))
        $scope.total_supervisores = claves;
        //Unimos todos los objetos. 
        let todoParaSumarse = $scope.cuota_supervisor

        let resultados = []

        //Recorremos las claves para crear los nuevos valores. 
        claves.forEach(clave => {
            //Sumamos todo lo que coincida con la clave. 
            let sumaPorClave = todoParaSumarse.filter(x => x.id_supervisor === clave)
                .reduce((pre, cur) => pre + cur.cuota, 0)

            let sumaPorimpactos = todoParaSumarse.filter(x => x.id_supervisor === clave)
                .reduce((pree, curr) => pree + curr.cuota_impactos, 0)

            let sumaPorimpactosModificador = todoParaSumarse.filter(x => x.id_supervisor === clave)
                .reduce((pree, curr) => pree + curr.cuota_impactos_modificados, 0)

            //Creamos el nuevo objeto de la clave
            let objeto = {
                    id_supervisor: clave,
                    cuota: sumaPorClave,
                    cuota_impactos: sumaPorimpactos,
                    cuota_impactos_modificados: sumaPorimpactosModificador
                }
                //Lo agregamos a nuestra pila
            resultados.push(objeto)
        })

        $scope.resultados_suma = resultados;
        periodos.push(periodo_actual);
        $scope.estado_cuenta = periodos;
        $scope.cuota_total = 0;
        $scope.cuota_impactos = 0;
        $scope.datos_vendedores.forEach(element => {
            $scope.cuota_total += element.cuota_vendedor;
            $scope.cuota_impactos += element.cuota_impactos;

        });

        $scope.porcentaje_costo = $scope.cuota_total * ($scope.almacen.margen / 100);
        $scope.total_costo = $scope.cuota_total - $scope.porcentaje_costo

        //$scope.MostrarCuotasVendedorSupervisor();
        document.getElementById("overlay").style.display = "none";

        $scope.ObtenerTotalImpactos();
    };

    $scope.ObtenerTotalImpactos = function() {

        var parametros = {
            catalogo: "total_impactos_supervisores",
            id_periodo: $scope.periodo_seleccionado,
            total_supervisores: $scope.total_supervisores
        };
        $scope.EjecutarLlamado("afiliados", "total_impactos_supervisores", parametros, $scope.MostrarTotalImpactos);
    }

    $scope.MostrarTotalImpactos = function(data) {
        $scope.total_impactos = data.suma[0].total
    }

    $scope.CargarImpactosSupervisor = function(data) {

        $scope.supervisor_seleccionado = data;
        var parametros = {
            catalogo: "impactos_supervisores",
            id_afiliado: data,
            id_periodo: $scope.periodo_seleccionado
        };
        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarDatosImpactos);

    };

    $scope.MostrarDatosImpactos = function(data) {
        $scope.impactos_supervisor = data;
        console.log("hola")
        if (data.length == 0) {

        } else {
            console.log($scope.supervisor_seleccionado)
            let c = 0
            $scope.resultados_suma.forEach(element => {
                if (element.id_supervisor == $scope.supervisor_seleccionado) {
                    $scope.resultados_suma[c].cuota_impactos = $scope.impactos_supervisor[0].impactos
                }
                c++
            });
        }

    }


    $scope.AbrirPanelSupervisor = function(id_supervisor) {
        $('#collapse_' + id_supervisor).collapse('toggle', $("#collapse_" + id_supervisor));
    };

    $scope.ModificarCuotasVendedor = function(data) {

        $scope.RegistroSeleccionado = data;
        console.log(data)
        $("#confirmacion_edicion_cuotas").modal("show");
    }

    $scope.ConfirmarActualizacionCuotas = function() {

        $scope.Nueva_Cuota = $("#cuota_nueva").val().replace(/\./g, '');

        $scope.cuota_total_vendedor = (parseInt($scope.RegistroSeleccionado.cuota_modificada) + parseInt($scope.Nueva_Cuota)) - parseInt($scope.RegistroSeleccionado.cuota_vendedor);


        if ($scope.cuota_total_vendedor >= parseInt($scope.RegistroSeleccionado.cuota_modificada)) {
            let diferencia = $scope.cuota_total_vendedor - parseInt($scope.RegistroSeleccionado.cuota_modificada)
            let a = $scope.cuota_total + diferencia;
            var opcion = confirm(`La cuota ingresada supera la cuota total distribuidora en $` + formatNumber(diferencia) + `
            Cuota total distribuidora actualmente es de $` + formatNumber($scope.cuota_total) + `.
            la cuota total quedaria en $` + formatNumber(a) + `
            ¿Desea dejar está cuota total distribuidora?`);
            if (opcion == true) {
                var parametros = {
                    cuota_almacen: $scope.cuota_total_vendedor,
                    cuota_vendedor: $scope.Nueva_Cuota,
                    id_almacen: $scope.RegistroSeleccionado.id_almacen,
                    id_periodo: $scope.RegistroSeleccionado.id_periodo,
                    id_vendedor: $scope.RegistroSeleccionado.id_vendedor
                };

                $scope.EjecutarLlamado("afiliados", "actualizar_cuotas", parametros, $scope.ResultadoCreacionNuevoCuota);
                alert("Cuota actualizada satisfactoriamente");
                $("#confirmacion_edicion_cuotas").modal("hide");
            } else {
                alert("Proceso Cancelado");
                $("#confirmacion_edicion_cuotas").modal("hide");
            }
        } else {
            let diferencia_menos = parseInt($scope.Nueva_Cuota) - parseInt($scope.RegistroSeleccionado.cuota_vendedor)
            let b = $scope.cuota_total + diferencia_menos;
            var opcion_menos = confirm(`La cuota total  distribuidora actualmente es $` + formatNumber($scope.cuota_total) +
                `, la cuota ingresada es inferior a la cuota total distribuidora faltan, $` + formatNumber(diferencia_menos) + `
                la cuota total quedaria en $` + formatNumber(b) + `
            ¿Desea dejar está cuota total distribuidora?`);
            if (opcion_menos == true) {
                var parametros = {
                    cuota_almacen: $scope.cuota_total_vendedor,
                    cuota_vendedor: $scope.Nueva_Cuota,
                    id_almacen: $scope.RegistroSeleccionado.id_almacen,
                    id_periodo: $scope.RegistroSeleccionado.id_periodo,
                    id_vendedor: $scope.RegistroSeleccionado.id_vendedor
                };

                $scope.EjecutarLlamado("afiliados", "actualizar_cuotas", parametros, $scope.ResultadoCreacionNuevoCuota);
                alert("Cuota actualizada satisfactoriamente");
                $("#confirmacion_edicion_cuotas").modal("hide");
            } else {
                alert("Proceso Cancelado");
                $("#confirmacion_edicion_cuotas").modal("hide");
            }
        }

    }

    $scope.ActualizarSupervisores = function(data) {
        console.log($scope.RegistroSeleccionado)
        console.log(data)

        var parametros = {
            id_supervisor: data,
            id_periodo: $scope.RegistroSeleccionado.id_periodo,
            id_vendedor: $scope.RegistroSeleccionado.id_vendedor
        };
        if ($scope.RegistroSeleccionado.id_supervisor == null) {
            $scope.EjecutarLlamado("afiliados", "actualizar_supervisores_nuevo", parametros, $scope.ResultadoCreacionNuevoCuota);
        } else {
            $scope.EjecutarLlamado("afiliados", "actualizar_supervisores_antiguo", parametros, $scope.ResultadoCreacionNuevoCuota);
        }
        $("#confirmacion_edicion_cuotas").modal("hide");

    }

    $scope.ExportarExcel = function() {

        let data = $scope.datos_vendedores;
        let wb = new ExcelJS.Workbook();
        let workbookName = "Cuotas.xlsx";
        let worksheetName = "Cuotas";
        let ws = wb.addWorksheet(worksheetName, {
            properties: {
                tabColor: { argb: 'FFFF0000' }
            }
        });


        ws.columns = [{
                key: "id_periodo",
                header: "Periodo",
                width: 10
            },
            {
                key: "almacen",
                header: "Distribuidora",
                width: 20
            }, {
                key: "id_vendedor",
                header: "IDvendedor",
                width: 20
            },
            /* {
                 key: "cedula",
                 header: "Cedula",
                 width: 20
             },*/
            {
                key: "vendedor",
                header: "Vendedor",
                width: 20
            },
            {
                key: "cuota_vendedor",
                header: "Cuota",
                width: 12,
            },

        ];


        ws.addRows(data);

        console.log(ws.getRow(5).getCell(9)._address);
        wb.xlsx.writeBuffer()
            .then(function(buffer) {
                saveAs(
                    new Blob([buffer], { type: "application/octet-stream" }),
                    workbookName
                );
            });
    };


    $scope.ArchivoSeleccionado = function() {
        $scope.boton_ver_archivo = false;
    };

    $scope.LeerExcel = function() {
        $scope.datos_cargados = [];
        console.log("Inicio")
        $scope.boton_ver_archivo = true;
        $scope.boton_guardar = false;
        /*Checks whether the file is a valid excel file*/
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
        if ($("#ngexcelfile").val().toLowerCase().indexOf(".xlsx") > 0) {
            xlsxflag = true;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = e.target.result;
            if (xlsxflag) {
                var workbook = XLSX.read(data, { type: 'binary' });
            } else {
                var workbook = XLS.read(data, { type: 'binary' });
            }

            var sheet_name_list = workbook.SheetNames;
            var cnt = 0;
            sheet_name_list.forEach(function(y) { /*Iterate through all sheets*/
                if (xlsxflag) {
                    var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                } else {
                    var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                }

                if (exceljson.length > 0) {

                    for (var i = 0; i < exceljson.length; i++) {
                        $scope.datos_cargados.push(exceljson[i]);
                        $scope.$apply();
                    }

                    $scope.suma_cuotas_actualizadas = 0;
                    $("#cuota_total_actualizada").html("")
                    $scope.datos_cargados.forEach(element => {
                        $scope.suma_cuotas_actualizadas += parseInt(element.Cuota)
                    });
                }
                $scope.MostrarDatosExcel();
            });
        }
        if (xlsxflag) {
            reader.readAsArrayBuffer($("#ngexcelfile")[0].files[0]);
        } else {
            reader.readAsBinaryString($("#ngexcelfile")[0].files[0]);
        }

    }


    $scope.MostrarDatosExcel = function() {
        $("#cuotasMasivas").modal("show")
        $("#cuota_total_actualizada").append("<b> $" + formatNumber($scope.suma_cuotas_actualizadas) + "</b>")
    };

    $scope.ActualizarDatosCargueMasivo = function() {
        /*$scope.cuota_a = $("#cuota_total").html().replace(/\,/g, '');
        $scope.cuota_total = parseInt($scope.cuota_a.replace(/\$/g, ''));*/

        if ($scope.suma_cuotas_actualizadas >= $scope.cuota_total) {
            let diferencia = $scope.cuota_total - $scope.suma_cuotas_actualizadas
            var opcion = confirm(`La cuota ingresada supera la cuota total distribuidora en $` + formatNumber(diferencia) + `
                    Cuota total distribuidora actualmente es de $` + formatNumber($scope.cuota_total) + `.
                    La nueva cuota es : $` + formatNumber($scope.suma_cuotas_actualizadas) + `.
                    ¿Desea dejar esta cuota total distribuidora?`);
            if (opcion == true) {
                var parametros = {
                    nueva_cuota_almacen: $scope.suma_cuotas_actualizadas,
                    id_almacen: $scope.almacen.id_drogueria,
                    id_periodo_seleccionado: $scope.periodo_seleccionado,
                    cuota_vendedores: $scope.datos_cargados
                };

                $scope.EjecutarLlamado("afiliados", "actualizar_cuotas_masivas", parametros, $scope.ResultadoCreacionNuevoCuota);

                alert("Cuota actualizada satisfactoriamente");
                $("#cuotasMasivas").modal("hide");
            } else {
                alert("Proceso Cancelado");
                $("#cuotasMasivas").modal("hide");
            }
        } else {
            let diferencia_menos = $scope.suma_cuotas_actualizadas - $scope.cuota_total
            var opcion_menos = confirm(`La cuota ingresada es inferior a la cuota total distribuidora, 
                    faltan $` + formatNumber(diferencia_menos) + ` para completar la cuota inicial registrada. 
                    La nueva cuota es : $` + formatNumber($scope.suma_cuotas_actualizadas) + `.
                    ¿Desea dejar esta cuota total distribuidora?`);
            if (opcion_menos == true) {
                var parametros = {
                    nueva_cuota_almacen: $scope.suma_cuotas_actualizadas,
                    id_almacen: $scope.almacen.id_drogueria,
                    id_periodo_seleccionado: $scope.periodo_seleccionado,
                    cuota_vendedores: $scope.datos_cargados
                };

                $scope.EjecutarLlamado("afiliados", "actualizar_cuotas_masivas", parametros, $scope.ResultadoCreacionNuevoCuota);

                alert("Cuota actualizada satisfactoriamente");
                $("#cuotasMasivas").modal("hide");
            } else {
                alert("Proceso Cancelado");
                $("#cuotasMasivas").modal("hide");
            }
        }

    };
    /*---------------------------------------- */

    $scope.DireccionarVendedor = function(data) {
        window.open('estado_cuenta.php?id_usuario=' + data, '_blank');
    }

    $scope.HabilitarModificacionImpactos = function() {

        $scope.modificar_impactos = 1;
    };

    $scope.GuardarCuotasImpactos = function(id_supervisor, data) {

        $scope.modificar_impactos = 1;
        $scope.c_impactos = $("#cuota_impactos_supervisor_" + data).val();
        var datos = {
            id_afiliado: id_supervisor,
            id_periodo: $scope.periodo_seleccionado,
            impactos: $scope.c_impactos
        }
        var parametros = {
            catalogo: "impactos",
            datos: datos,
            id_almacen: id_almacen,
            id_periodo: $scope.periodo_seleccionado,
            total_supervisores: $scope.total_supervisores
        };
        $scope.EjecutarLlamado("afiliados", "registrar_cuotas_supervisores", parametros, $scope.ResultadoActualizacionImpactos);

    };

    $scope.ResultadoActualizacionImpactos = function(data) {
        location.reload(true);

    };

    $scope.RazonEliminacionCuotasVendedor = function(data) {

        $scope.razon_eliminacion = data;
        $("#modalDenegarVendedorPerfecto").modal("show");

    };

    $scope.EliminarCuotasVendedor = function() {

            console.log($scope.razon_eliminacion)
            console.log($scope.cuota_total)

            let diferencia_elminacion = $scope.cuota_total - $scope.razon_eliminacion.cuota_vendedor
            var opcion_eliminacion = confirm("Al eliminar este vendedor su cuota seria de : $" + formatNumber(diferencia_elminacion) +
                " ¿Desea continuar?");

            if (opcion_eliminacion == true) {
                var parametros = {
                    id_vendedor: $scope.razon_eliminacion.id_vendedor,
                    id_periodo: $scope.razon_eliminacion.id_periodo,
                    razon: $scope.razon_denegacion,
                    diferencia: diferencia_elminacion,
                    id_almacen: $scope.razon_eliminacion.id_almacen
                }

                $scope.EjecutarLlamado("afiliados", "eliminar_cuotas_vendedor", parametros, $scope.ResultadoCreacionNuevoCuota);
                $("#modalDenegarVendedorPerfecto").modal("hide")
            }

        }
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

    $scope.nuevo_afiliado = { id_supervisor: "", cedula: "", telefono: "", rol: "", nombre: "", cuota: "", id_periodo: "" };
    $scope.BuscarSupervisor = function() {
        var parametros = {
            catalogo: "supervisores_almacen",
            id_almacen: id_almacen
        };

        $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.MostrarSupervisores);

    };

    $scope.MostrarSupervisores = function(data) {
        $scope.supervisores_afiliados = data;
    };

    $scope.ReemplazarVendedor = function() {
        var creacion_completa = true;
        angular.forEach($scope.nuevo_afiliado, function(dato) {
            if (dato == "") {
                creacion_completa = false;
            }
        });
        if (creacion_completa) {
            if ($scope.reemplazar_vendedor == 1) {
                let periodos_reemplazo = 0;
                $scope.btn_crear_usuario = 1
                if ($scope.nuevo_afiliado.id_periodo == 20) {
                    periodos_reemplazo = "(15,16,17)";
                } else if ($scope.nuevo_afiliado.id_periodo == 21) {
                    periodos_reemplazo = "(16,17,18)";
                } else if ($scope.nuevo_afiliado.id_periodo == 22) {
                    periodos_reemplazo = "(17,18,19)";
                } else if ($scope.nuevo_afiliado.id_periodo == 23) {
                    periodos_reemplazo = "(18,19,20)";
                }

                var parametros = {
                    catalogo: "vendedores_reemplazo",
                    id_almacen: id_almacen,
                    id_periodo: periodos_reemplazo
                }

                $scope.EjecutarLlamado("catalogos", "CargaCatalogo", parametros, $scope.VendedoresParaReemplazar);
            } else {
                $scope.btn_crear_usuario = 0;
            }
        } else {
            $scope.reemplazar_vendedor = 0;
            alert("Debe completar todos los datos");

        }
    };

    $scope.VendedoresParaReemplazar = function(data) {

        $scope.listado_vendedores_reemplazo = data;

    };

    $scope.ValidarReemplazo = function(data) {
        $scope.btn_crear_usuario = 0;
        $scope.vendedor_reemplazo = data;

    }

    $scope.CrearNuevoUsuario = function() {
        $scope.btn_crear_usuario = 1;
        if (parseInt($scope.nuevo_afiliado.cuota) >= 500000) {
            var parametros = {
                catalogo: "afiliados",
                id_supervisor: $scope.nuevo_afiliado.id_supervisor,
                cedula: $scope.nuevo_afiliado.cedula,
                nombre: $scope.nuevo_afiliado.nombre,
                telefono: $scope.nuevo_afiliado.telefono,
                cuota: $scope.nuevo_afiliado.cuota,
                rol: $scope.nuevo_afiliado.rol,
                id_almacen: id_almacen,
                id_periodo: $scope.nuevo_afiliado.id_periodo,
                id_registra: datos_usuario.id,
                habilitar_reemplazo: $scope.reemplazar_vendedor,
                vendedor_reemplazo: $scope.vendedor_reemplazo
            };

            $scope.EjecutarLlamado("afiliados", "CrearNuevoUsuario", parametros, $scope.ResultadoCreacionNuevoUsuario);
        } else {
            alert("La cuota minima es de $500.000")
            $scope.btn_crear_usuario = 0;
        }
    };

    $scope.ResultadoCreacionNuevoUsuario = function(data) {
        if (data.ok) {
            alert("Usuario creado satisfactoriamente");
            $scope.btn_crear_usuario = 0;
        } else {
            alert(data.msj);
            $scope.btn_crear_usuario = 0;
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
                id_estatus: 2,
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
        $scope.ResultadoCreacionNuevoCuota();
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


    $scope.imput_subir_archivo = true;
    $scope.boton_ver_archivo = true;
    $scope.boton_guardar = true;
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

function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === '' ? n : Number(n).toLocaleString();
}