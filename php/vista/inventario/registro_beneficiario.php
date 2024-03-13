<html>

<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION : 11/03/2024
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
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
    <form id="miFormulario">
        <div class="accordion" id="accordionExample" style="margin-left:30px; margin-right: 30px;">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                            INFORMACION GENERAL
                            <span id="nombreruc" style="color: red; font-weight: bold;"></span>
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body" style="margin: 1px; padding-top: 5px; padding-bottom: 5px;">
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_93" style="display: block;">Tipo de Beneficiario</label>
                                <select class="form-control input-xs" name="select_93" id="select_93">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="" style="display: block;">Tipo de Donación</label>
                                <select class="form-control input-xs" name="" id="">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="ruc" style="display: block;">CI/RUC</label>
                                <select class="form-control input-xs" name="ruc" id="ruc">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/SRIlogo.png" width="80" height="50">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
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
                            <div style="flex: 1;">
                                <label for="select_87" style="display: block;">Estado</label>
                                <select class="form-control input-xs" name="select_87" id="select_87">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="nombreRepre" style="display: block;">Nombre Representante Legal</label>
                                <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                    placeholder="Nombre Representante">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="ciRepre" style="display: block;">CI Representante Legal</label>
                                <input class="form-control input-xs" type="text" name="ciRepre" id="ciRepre"
                                    placeholder="CI Representante">
                            </div>
                            <div style="flex: 1;">
                                <label for="telfRepre" style="display: block;">Telefono Representante Legal</label>
                                <input class="form-control input-xs" type="text" name="telfRepre" id="telfRepre"
                                    placeholder="Representante legal">
                            </div>
                        </div>

                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="contacto" style="display: block;">Contacto/Encargado</label>
                                <input class="form-control input-xs" type="text" name="contacto" id="contacto"
                                    placeholder="Contacto">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="cargo" style="display: block;">Cargo</label>
                                <input class="form-control input-xs" type="text" name="cargo" id="cargo"
                                    placeholder="Profesion">
                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/calendario2.png" width="60" height="60">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="fechaEntrega" style="display: block;">Día Entrega a Usuarios Finales
                                    FINALES</label>
                                <input type="date" name="fechaEntrega" id="fechaEntrega"
                                    class="form-control input-xs validateDate" onchange="" title="DIA ENTREGA USUARIOS"
                                    value="<?php echo date('Y-m-d') ?>">
                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div style="flex: 1; ">
                                <label for="horaEntrega" style="display: block;">Hora Entrega a Usuarios Finales</label>
                                <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs"
                                    title="HORA ENTREGA">
                            </div>
                        </div>

                        <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                            <div class="col-sm-3">
                                <div class="row">
                                    <label for="direccion" style="display: block;">Dirección</label>
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
                                    <label for="referencia" style="display: block;">Referencia</label>
                                    <input class="form-control input-xs" type="text" name="referecia" id="referencia"
                                        placeholder="Referencia">
                                </div>
                                <div class="row">
                                    <label for="telefono" style="display: block;">Teléfono 1</label>
                                    <input class="form-control input-xs" type="text" name="telefono" id="telefono"
                                        placeholder="Telefono ">
                                </div>
                                <div class="row">
                                    <label for="telefono2" style="display: block;">Teléfono 2</label>
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
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_88" style="display: block;">Tipo de Entrega</label>
                                <select class="form-control input-xs" name="select_88" id="select_88">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/calendario2.png" width="60" height="60">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="diaEntregac" style="display: block;">Día de Entrega</label>
                                <select class="form-control input-xs" name="diaEntregac" id="diaEntregac">
                                    <option value="">Seleccione</option>
                                </select>

                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="horaEntregac" style="display: block;">Hora de Entrega</label>
                                <input type="time" name="horaEntregac" id="horaEntregac" class="form-control input-xs"
                                    title="HORA DE ENTREGA">
                            </div>

                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_86" style="display: block;">Frecuencia</label>
                                <select class="form-control input-xs" name="select_86" id="select_86">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="totalPersonas" style="display: block;">Total de Personas Atendidas</label>
                                <input type="number" name="totalPersonas" id="totalPersonas"
                                    class="form-control input-xs" title="TOTAL DE PERSONAS ASISTIDAS" min="0" max="100">

                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_91" style="display: block;">Tipo de Población</label>
                                <select class="form-control input-xs" name="select_91" id="select_91">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_92" style="display: block;">Acción Social</label>
                                <select class="form-control input-xs" name="select_92" id="select_92">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_90" style="display: block;">Vulnerabilidad</label>
                                <select class="form-control input-xs" name="select_90" id="select_90">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_89" style="display: block;">Tipo de Atención</label>
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
                                        <label for="archivoAdd">Archivos Adjuntos</label>
                                        <input type="file" class="form-control-file" id="archivoAdd">
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <label for="infoNut" style="display: block;">Información Nutricional</label>
                                    <textarea class="form-control" id="infoNut" rows="4"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</form>

<script>
    $(document).ready(function () {
        Form_Activate();

    });

    function Form_Activate() {
        var valores = [86, 87, 88, 89, 90, 91, 92, 93];
        LlenarSelectTipos(valores);
        LlenarSelectDiaEntrega();
        LlenarDatosCliente();
        var horaActual = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        $('#horaEntregac').val(horaActual);

        /*var fechaActual = new Date();
        var fechaFormateada = fechaActual.getFullYear() + '-' +
            ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' +
            ('0' + fechaActual.getDate()).slice(-2);
        $('#diaEntregac').val(fechaFormateada);*/
    }

    $('#select_93').change(function () {
        var selectedOption = $(this).val();
        var cardBody = $('.card-body');
        var cardHeader = $('.card-header');

        var colorValor = $(this).find('option:selected').data('color-valor');
        var hexColor = colorValor.substring(4);

        // Obtener el color más oscuro para el card-header
        var darkerColor = darkenColor(hexColor, 20); // Puedes ajustar el porcentaje según sea necesario

        if (hexColor) {
            cardBody.css('background-color', '#' + hexColor);
            cardHeader.css('background-color', darkerColor);
        } else {
            cardBody.css('background-color', '#fffacd');
        }
    });

    function darkenColor(color, percent) {
        var num = parseInt(color, 16),
            amt = Math.round(2.55 * percent),
            R = (num >> 16) - amt,
            G = (num >> 8 & 0x00FF) - amt,
            B = (num & 0x0000FF) - amt;

        R = (R < 255 ? (R < 1 ? 0 : R) : 255);
        G = (G < 255 ? (G < 1 ? 0 : G) : 255);
        B = (B < 255 ? (B < 1 ? 0 : B) : 255);

        return "#" + ((1 << 24) + (R << 16) + (G << 8) + B).toString(16).slice(1);
    }

    function LlenarSelectTipos(valores) {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelect=true',
            type: 'post',
            dataType: 'json',
            data: { valores: valores },
            success: function (data) {
                $.each(data, function (index, resultado) {
                    LlenarSelect(resultado.valor, resultado.datos);
                });
            }
        });
    }

    function LlenarSelectDiaEntrega() {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectDiaEntrega=true',
            type: 'post',
            dataType: 'json',
            success: function (datos) {
                $.each(datos, function (index, opcion) {
                    $('#diaEntregac').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
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
                    $select.append('<option value="' + opcion['Cmds'] + '" data-color-valor="' + opcion['Picture'] + '">' + opcion['Proceso'] + '</option>');
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

        var fileInput = $('#archivoAdd')[0];
        var archivo = fileInput.files[0];
        var formData = new FormData();

        formData.append('Cliente', miCliente);
        formData.append('CI_RUC', miRuc);
        formData.append('Codigo', miCodigo);
        formData.append('Actividad', $('#select_93').val());
        formData.append('CodigoA', $('#select_87').val());
        formData.append('Representante', $('#nombreRepre').val());
        formData.append('CI_RUC_R', $('#ciRepre').val());
        formData.append('Telefono_R', $('#telfRepre').val());
        formData.append('Contacto', $('#contacto').val());
        formData.append('Profesion', $('#cargo').val());
        formData.append('Fecha_Cad', $('#fechaEntrega').val());
        formData.append('Hora_Ent', $('#horaEntrega').val());
        formData.append('Direccion', $('#direccion').val());
        formData.append('Email', $('#email').val());
        formData.append('Email2', $('#email2').val());
        formData.append('Lugar_Trabajo', $('#referencia').val());
        formData.append('Telefono', $('#telefono').val());
        formData.append('TelefonoT', $('#telefono2').val());
        // Información adicional
        formData.append('CodigoA2', $('#select_88').val());
        formData.append('Fecha_Registro', $('#diaEntregac').val());
        formData.append('Hora_Registro', $('#horaEntregac').val());
        formData.append('Envio_No', $('#select_86').val());
        formData.append('No_Soc', $('#totalPersonas').val());
        formData.append('Area', $('#select_91').val());
        formData.append('Acreditacion', $('#select_92').val());
        formData.append('Tipo_Dato', $('#select_90').val());
        formData.append('Cod_Fam', $('#select_89').val());
        formData.append('Observaciones', $('#infoNut').val());

        if (archivo) {
            formData.append('Evidencias', archivo, archivo.name);
        }

        var camposVacios = [];
        if (!miRuc) camposVacios.push('RUC');
        if (!$('#select_88').val()) camposVacios.push('Tipo Entrega');
        if (!$('#diaEntregac').val()) camposVacios.push('Fecha Entrega');
        if (!$('#horaEntregac').val()) camposVacios.push('Hora Entrega');
        if (!$('#select_86').val()) camposVacios.push('Frecuencia');
        if (!$('#totalPersonas').val()) camposVacios.push('Personas Atendidas');
        if (!$('#select_91').val()) camposVacios.push('Tipo poblacion');
        if (!$('#select_92').val()) camposVacios.push('Accion social');
        if (!$('#select_90').val()) camposVacios.push('Vulnerabilidad');
        if (!$('#select_89').val()) camposVacios.push('Tipo Atencion');
        if (!archivo) camposVacios.push('Evidencias');
        if (!$('#infoNut').val()) camposVacios.push('Observaciones');

        if (camposVacios.length > 0) {
            var mensaje = 'Los siguientes campos están vacíos:\n';
            camposVacios.forEach(function (campo) {
                mensaje += campo + ',';
            });
            Swal.fire({
                title: 'Campos Vacíos',
                text: mensaje,
                type: 'warning',
                confirmButtonText: 'Aceptar'
            });
        } else {
            $.ajax({
                type: 'post',
                url: '../controlador/inventario/registro_beneficiarioC.php?guardarAsignacion=true',
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.res == '0') {
                        Swal.fire({
                            title: 'AVISO',
                            text: response.mensaje,
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: response.mensaje,
                            type: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        limpiarCampos();
                    }
                }
            });

        }
    });

    var miRuc;
    var miCodigo;
    var miCliente;

    $('#cliente').on('select2:select', function (e) {
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.CI_RUC;
        miCliente = data.text;
        $('#ruc').val(data.miRuc).trigger('change');
        $('#nombreruc').text(miRuc);
        llenarDatos(data);
    });

    $('#ruc').on('select2:select', function (e) {
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.text;
        miCliente = data.Cliente;
        $('#cliente').val(data.miCliente).trigger('change');
        $('#nombreruc').text(miCliente);
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
        $('#horaEntrega').val(datos.Hora_Ent);
        $('#direccion').val(datos.Direccion);
        $('#email').val(datos.Email);
        $('#email2').val(datos.Email2);
        $('#referencia').val(datos.Lugar_Trabajo);
        $('#telefono').val(datos.Telefono);
        $('#telefono2').val(datos.TelefonoT);
    }

    function limpiarCampos() {
        $('#miFormulario').find('input, select, textarea').val('');
        $('#cliente').val('');
        $('#ruc').val('');
        $('#nombreruc').text('');
    }

</script>