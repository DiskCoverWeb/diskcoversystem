<html>
<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    FECHA CREACION	: 16/02/2024
    FECHA MODIFICACION: 23/02/2024
    DESCIPCION :   Actualizacion de la interfaz para vista
 -->

<head>
    <style>
        .card-header {
            background-color: #f3e5ab;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header h2 {
            font-weight: bold;
        }

        .card-body {
            background-color: #fffacd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-link {
            color: black;
        }
    </style>
</head>

<body>
    <div>
        <div class="row" style="margin:5px; padding-top:10px; color:black;">
            <div class="col-sm-2 col-xs-12">
                <div class="col">
                    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo"
                        class="btn btn-default">
                        <img src="../../img/png/salire.png" width="25" height="30">
                    </a>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" id="btnGuardarAsignacion" title="Guardar" class="btn btn-default">
                        <img src="../../img/png/grabar.png" width="25" height="30">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion" id="accordionExample" style="margin-left:30px; margin-right: 30px;">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        INFORMACION GENERAL
                    </button>
                </h2>
            </div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body" style="margin:1px; padding-top:5px; padding-bottom:5px;">
                    <div class="row" style="margin:10px; ">
                        <div class="col-sm-2">
                            <label for="ruc" style="display: block;">CI/RUC</label>
                            <select class="form-control input-xs" name="ruc" id="ruc">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/SRIlogo.png" width="80" height="50">
                        </div>

                        <div class="col-sm-3">
                            <label for="cliente" style="display: block;">Nombre del Beneficiario/Usuario</label>
                            <div class="input-group">
                                <select class="form-control input-xs" name="cliente" id="cliente">
                                    <option value="">Seleccione</option>
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn-xs btn-flat" id="btn_nuevo_cli"
                                        onclick="addCliente()" title="Nuevo cliente">
                                        <span class="fa fa-user-plus"></span>
                                    </button>
                                </span>
                            </div>

                        </div>

                        <div class="col-sm-4">
                            <label for="select_93" style="display: block;">Tipo de Beneficiario</label>
                            <select class="form-control input-xs" name="select_93" id="select_93">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="select_87" style="display: block;">Estado</label>
                            <select class="form-control input-xs" name="select_87" id="select_87">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin: 10px;">
                        <div class="col-sm-3">
                            <label for="nombreRepre" style="display: block;">NOMBRE REPRESENTANTE LEGAL</label>
                            <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                placeholder="Nombre Representante">
                        </div>
                        <div class="col-sm-3">
                            <label for="ciRepre" style="display: block;">CI REPRESENTANTE LEGAL</label>
                            <input class="form-control input-xs" type="text" name="ciRepre" id="ciRepre"
                                placeholder="CI Representante">
                        </div>
                        <div class="col-sm-3">
                            <label for="telfRepre" style="display: block;">TELEFONO REPRESENTANTE LEGAL</label>
                            <input class="form-control input-xs" type="text" name="telfRepre" id="telfRepre"
                                placeholder="Representante legal">
                        </div>
                    </div>
                    <div class="row" style="margin:10px; ">
                        <div class="col-sm-2">
                            <label for="contacto" style="display: block;">CONTACTO/ENCARGADO</label>
                            <input class="form-control input-xs" type="text" name="contacto" id="contacto"
                                placeholder="Contacto">
                        </div>
                        <div class="col-sm-3">
                            <label for="cargo" style="display: block;">CARGO</label>
                            <input class="form-control input-xs" type="text" name="cargo" id="cargo"
                                placeholder="Profesion">
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/calendario2.png" width="60" height="60">
                        </div>
                        <div class="col-sm-3">
                            <label for="diaEntrega" style="display: block;">DIA ENTREGA A USUARIOS
                                FINALES</label>
                            <input class="form-control input-xs" type="text" name="diaEntrega" id="diaEntrega"
                                placeholder="Dia entrega">
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/reloj.png" width="55" height="55">
                        </div>
                        <div class="col-sm-2">
                            <label for="horaEntrega" style="display: block;">HORA DE ENTREGA</label>
                            <input class="form-control input-xs" type="text" name="horaEntrega" id="horaEntrega"
                                placeholder="Hora entrega">
                        </div>
                    </div>
                    <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                        <div class="col-sm-3">
                            <div class="row">
                                <label for="direccion" style="display: block;">DIRECCION</label>
                                <input class="form-control input-xs" type="text" name="direccion" id="direccion"
                                    placeholder="Direccion">
                            </div>
                            <div class="row">
                                <label for="email" style="display: block;">Email</label>
                                <input class="form-control input-xs" type="text" name="email" id="email"
                                    placeholder="Email">
                            </div>
                            <div class="row">
                                <label for="email2" style="display: block;">Email 2</label>
                                <input class="form-control input-xs" type="text" name="email2" id="email2"
                                    placeholder="Email2">
                            </div>
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <div class="row">
                                <label for="referencia" style="display: block;">REFERENCIA</label>
                                <input class="form-control input-xs" type="text" name="referecia" id="referencia"
                                    placeholder="Referencia">
                            </div>
                            <div class="row">
                                <label for="telefono" style="display: block;">TELEFONO</label>
                                <input class="form-control input-xs" type="text" name="telefono" id="telefono"
                                    placeholder="Telefono ">
                            </div>
                            <div class="row">
                                <label for="telefono2" style="display: block;">TELEFONO 2</label>
                                <input class="form-control input-xs" type="text" name="telefono2" id="telefono2"
                                    placeholder="Telefono 2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">

                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        INFORMACION ADICIONAL
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body" style="margin:1px; padding-top:5px; padding-bottom:5px;">
                    <div class="row" style="margin:10px; ">
                        <div class="col-sm-3">
                            <label for="select_88" style="display: block;">TIPO DE ENTREGA</label>
                            <select class="form-control input-xs" name="select_88" id="select_88">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/calendario2.png" width="60" height="60">
                        </div>
                        <div class="col-sm-2">
                            <label for="diaEntregac" style="display: block;">DIA DE ENTREGA</label>
                            <input type="date" name="diaEntregac" id="diaEntregac"
                                class="form-control input-xs validateDate" onchange="" title="DIA DE ENTREGA"
                                value="<?php echo date('Y-m-d') ?>">
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/reloj.png" width="55" height="55">
                        </div>
                        <div class="col-sm-2">
                            <label for="horaEntrega" style="display: block;">HORA DE ENTREGA</label>
                            <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs"
                                title="HORA DE ENTREGA">
                        </div>

                        <div class="col-sm-3">
                            <label for="select_86" style="display: block;">FRECUENCIA</label>
                            <select class="form-control input-xs" name="select_86" id="select_86">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin:10px;">
                        <div class="col-sm-2">
                            <label for="totalPersonas" style="display: block;">TOTAL DE PERSONAS
                                ASISTIDAS</label>
                            <input type="number" name="totalPersonas" id="totalPersonas" class="form-control input-xs"
                                title="TOTAL DE PERSONAS ASISTIDAS" min="0" max="100">

                        </div>
                        <div class="col-sm-2">
                            <label for="select_91" style="display: block;">TIPO DE POBLACION</label>
                            <select class="form-control input-xs" name="select_91" id="select_91">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="select_92" style="display: block;">ACCION SOCIAL</label>
                            <select class="form-control input-xs" name="select_92" id="select_92">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="select_90" style="display: block;">VULNERABILIDAD</label>
                            <select class="form-control input-xs" name="select_90" id="select_90">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="select_89" style="display: block;">TIPO DE ATENCION</label>
                            <select class="form-control input-xs" name="select_89" id="select_89">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin: 10px;">
                        <div class="col-sm-5"></div>

                        <div class="col-sm-3">
                            <div class="row" style="display: flex; justify-content: center;">
                                <img src="../../img/png/adjuntar-archivo.png" width="60" height="60">
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="archivoAdd">ARCHIVOS ADJUNTOS</label>
                                    <input type="file" class="form-control-file" id="archivoAdd">
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <label for="infoNut" style="display: block;">INFORMACION NUTRICIONAL</label>
                                <textarea class="form-control" id="infoNut" rows="4"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        Form_Activate();
    });

    function Form_Activate() {
        var valores = [86, 87, 88, 89, 90, 91, 92, 93];
        LlenarSelectTipos(valores);
        LlenarDatosCliente();
    }

    function LlenarSelectTipos(valores) {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelect=true',
            type: 'post',
            dataType: 'json',
            data: { valores: valores },
            success: function (data) {
                console.log(data);
                $.each(data, function (index, resultado) {
                    LlenarSelect(resultado.valor, resultado.datos);
                });
            }
        });
    }

    function LlenarSelect(valor, datos) {
        if (valor) {
            var selectId = '#select_' + valor;
            var $select = $(selectId);
            $select.empty();
            if (datos === "No se encontraron datos para mostrar") {
                $select.append('<option value="">' + datos + '</option>');
            } else {
                $.each(datos, function (index, opcion) {
                    $select.append('<option value="' + opcion['Cmds'] + '">' + opcion['Proceso'] + '</option>');
                });
            }
        }
    }

    function LlenarDatosCliente() {
        $('#ruc').select2({
            placeholder: 'Seleccione un RUC',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarDatosCliente: true
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.rucs
                    };
                },
                cache: true
            }
        });

        $('#cliente').select2({
            placeholder: 'Seleccione un Cliente',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarDatosCliente: true
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.clientes
                    };
                },
                cache: true
            }
        });
    }

    $('#btnGuardarAsignacion').click(function () {
        var params = {
            'Cliente': miCliente,
            'CI_RUC': miRuc,
            'CodigoA': $('#estado').val(),
            'Representante': $('#nombreRepre').val(),
            'CI_RUC_R': $('#ciRepre').val(),
            'Telefono_R': $('#telfRepre').val(),
            'Contacto': $('#contacto').val(),
            'Profesion': $('#cargo').val(),
            'Fecha_Cad': $('#fechaEntrega').val(),
            'Hora_Ent': $('#horaEntrega').val(),
            'Direccion': $('#direccion').val(),
            'Email': $('#email').val(),
            'Email2': $('#email2').val(),
            'Lugar_Trabajo': $('#referencia').val(),
            'Telefono': $('#telefono').val(),
            'TelefonoT': $('#telefono2').val()
        };
        if (miCliente != null && miRuc != null) {
            $.ajax({
                url: '../controlador/inventario/guardarAsignacion.php',
                type: 'post',
                dataType: 'json',
                data: { 'params': params },
                success: function (data) {
                    if (data.response == 0) {
                        swal.fire({
                            title: 'Guardar',
                            text: 'Se ha registrado correctamente',
                            type: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }
            });
        } else {
            swal.fire({
                title: 'No se llenaron los campos mínimos requeridos',
                type: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });


    var miRuc;
    $('#cliente').on('select2:select', function (e) {
        var data = e.params.data;
        //console.log("VALOR DEL RUC ", data.CI_RUC);
        miRuc = data.CI_RUC
        $('#ruc').val(data.CI_RUC).trigger('change');
        //console.log("Valor de $('#ruc').val():", $('#ruc').val());
        llenarDatos(data);
    });

    var miCliente;
    $('#ruc').on('select2:select', function (e) {
        var data = e.params.data;
        //console.log("VALOR DEL CLIENTE ", data.Cliente);
        miCliente = data.Cliente;
        $('#cliente').val(data.Cliente).trigger('change.select2');
        //#collapseOneconsole.log("Valor de $('#ruc').val():", $('#ruc').val());
        llenarDatos(data);
    });

    function llenarDatos(datos) {
        $('#estado').val(datos.CodigoA);
        $('#nombreRepre').val(datos.Representante);
        $('#ciRepre').val(datos.CI_RUC_R);
        $('#telfRepre').val(datos.Telefono_R);
        $('#contacto').val(datos.Contacto);
        $('#cargo').val(datos.Profesion);
        $('#fechaEntrega').val(datos.Fecha_Cad);
        //$('#horaEntrega').val(datos.Hora_Ent);
        $('#direccion').val(datos.Direccion);
        $('#email').val(datos.Email);
        $('#email2').val(datos.Email2);
        $('#referencia').val(datos.Lugar_Trabajo);
        $('#telefono').val(datos.Telefono);
        $('#telefono2').val(datos.TelefonoT);
    }

</script>