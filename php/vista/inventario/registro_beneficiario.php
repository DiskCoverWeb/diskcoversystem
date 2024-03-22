<html>

<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION : 21/03/2024
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->

<head>
    <style>
        .table {
            width: 100%;
            table-layout: fixed;
        }

        .table th,
        .table td {
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #tabla-body {
            max-height: 300px;
            overflow-y: auto;
        }

        #modalCalendario table {
            text-align: center;
        }

        #modalCalendario th {
            font-weight: bold;
        }

        .card-header {
            background-color: #f3e5ab;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            background-color: #fffacd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-link {
            color: black;
        }

        #validarSRI {
            transition: transform 0.3s ease;
        }

        #validarSRI:hover {
            transform: translateY(-10px);
        }

        #btnMostrarModal {
            transition: transform 0.3s ease;
        }

        #btnMostrarModal:hover {
            transform: translateY(-10px);
        }

        #descargarArchivo img {
            transition: transform 0.3s ease;
        }

        #descargarArchivo img:hover {
            transform: translateX(10px);
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
    <form id="miFormulario" style="padding-bottom:30px">
        <div class="accordion" id="accordionExample" style="margin-left:30px; margin-right: 30px;">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                            style="font-weight: bold;" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                            INFORMACION GENERAL
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body" style="margin: 1px; padding-top: 5px; padding-bottom: 5px;">
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="select_93" style="display: block;">Tipo de Beneficiario</label>
                                <select class="form-control input-xs" name="select_93" id="select_93"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="tipoDonacion" style="display: block;">Tipo de Donación</label>
                                <select class="form-control input-xs" name="tipoDonacion" id="tipoDonacion"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="ruc" style="display: block;">CI/RUC</label>
                                <select class="form-control input-xs" name="ruc" id="ruc" style="width: 100%;"></select>
                            </div>
                            <div
                                style="display: flex; justify-content: center; align-items: center;  margin-right: 10px;">
                                <img src="../../img/png/SRIlogo.png" width="80" height="50"
                                    onclick="validar_sriC($('#ruc').val())" id="validarSRI" title="VALIDAR RUC">
                            </div>
                            <div style="flex: 1; margin-right: 10px;">
                                <label for="cliente" style="display: block;">Nombre del Beneficiario/Usuario</label>
                                <div class="input-group">
                                    <select class="form-control input-xs" name="cliente" id="cliente"
                                        style="width: 100%;"></select>
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
                                <select class="form-control input-xs" name="select_87" id="select_87"
                                    style="width: 100%;"></select>
                            </div>
                        </div>

                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="nombreRepre" style="display: block;">Nombre Representante Legal</label>
                                <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                    placeholder="Nombre Representante">
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
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
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="contacto" style="display: block;">Contacto/Encargado</label>
                                <input class="form-control input-xs" type="text" name="contacto" id="contacto"
                                    placeholder="Contacto">
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="cargo" style="display: block;">Cargo</label>
                                <input class="form-control input-xs" type="text" name="cargo" id="cargo"
                                    placeholder="Profesion">
                            </div>
                            <div style="margin-right: 10px;  display: flex; ">
                                <img src="../../img/png/calendario2.png" width="60" height="60">
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="diaEntrega" style="display: block;">Día Entrega a Usuarios Finales</label>
                                <select class="form-control input-xs" name="diaEntrega" id="diaEntrega"></select>
                            </div>
                            <div style="margin-right: 10px;  display: flex; ">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div style="flex: 1; ">
                                <label for="horaEntrega" style="display: block;">Hora Entrega a Usuarios Finales</label>
                                <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
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
                                    <input class="form-control input-xs" type="text" name="referencia" id="referencia"
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
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"
                            id="botonInfoAdd" style="font-weight: bold;">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                            INFORMACION ADICIONAL
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body" style="margin: 1px; padding-top: 5px; padding-bottom: 40px;">
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_88" style="display: block;">Tipo de Entrega</label>
                                <select class="form-control input-xs" name="select_88" id="select_88"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/calendario2.png" width="60" height="60" id="btnMostrarModal"
                                    title="CALENDARIO ASIGNACION">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="diaEntregac" style="display: block;">Día de Entrega</label>
                                <select class="form-control input-xs" name="diaEntregac" id="diaEntregac"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="horaEntregac" style="display: block;">Hora de Entrega</label>
                                <input type="time" name="horaEntregac" id="horaEntregac" class="form-control input-xs">
                            </div>

                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_86" style="display: block;">Frecuencia</label>
                                <select class="form-control input-xs" name="select_86" id="select_86"
                                    style="width: 100%;"></select>
                            </div>

                            <div id="comentariodiv"
                                style="flex: 1; margin-right: 10px; margin-lefth: 10px; style=display: none;">
                                <label for="comentario" style="display: block;">Comentario</label>
                                <textarea class="form-control" id="comentario" rows="2" style="resize: none"></textarea>
                            </div>
                        </div>
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="totalPersonas" style="display: block;">Total de Personas Atendidas</label>
                                <input type="number" name="totalPersonas" id="totalPersonas"
                                    class="form-control input-xs" min="0" max="100">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_91" style="display: block;">Tipo de Población</label>
                                <select class="form-control input-xs" name="select_91" id="select_91"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_92" style="display: block;">Acción Social</label>
                                <select class="form-control input-xs" name="select_92" id="select_92"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_90" style="display: block;">Vulnerabilidad</label>
                                <select class="form-control input-xs" name="select_90" id="select_90"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                <label for="select_89" style="display: block;">Tipo de Atención</label>
                                <select class="form-control input-xs" name="select_89" id="select_89"
                                    style="width: 100%;"></select>
                            </div>

                        </div>
                        <div class="row" style="margin: 10px;">
                            <div class="col-sm-5"></div>

                            <div class="col-sm-3">
                                <div class="row" style="display: flex; justify-content: center;">
                                    <a href="#" id="descargarArchivo">
                                        <img src="../../img/png/adjuntar-archivo.png" width="60" height="60"
                                            title="DESCARGAR ARCHIVO">
                                    </a>
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
                                    <textarea class="form-control" id="infoNut" rows="4"
                                        style="resize: none"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalCalendario">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="background-color: white;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                        <h4 class="modal-title">CALENDARIO DE ASIGNACION</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div style="max-height: 300px; overflow-y: auto; overflow-x: auto;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <script>
                                                var diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 
                                                'Viernes','Sabado','Domingo'];
                                                for (var i = 0; i < diasSemana.length; i++) {
                                                    document.write('<th>' + diasSemana[i] + '</th>');
                                                }
                                            </script>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-body">
                                        <!-- Aquí se renderizarían las filas de la tabla -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>


<script>
    $(document).ready(function () {
        Form_Activate();
    });

    function validarRucYValidarSriC() {
        var ruc = $('#ruc').val();
        if (ruc) {
            validar_sriC(ruc);
        } else {
            Swal.fire('', 'Por favor ingrese un RUC válido.', 'error');
        }
    }

    function usar_cliente(nombre, ruc, codigo, email, td = 'N') {
        $('#cliente').val(ruc).trigger('change');
        $('#myModal').modal('hide');
    }

    var horaActual;
    function Form_Activate() {
        $('#comentariodiv').hide();

        LlenarSelectDiaEntrega();
        LlenarSelectRucCliente();
        LlenarTipoDonacion();

        [86, 87, 88, 89, 90, 91, 92, 93].forEach(LlenarSelects_Val);

        horaActual = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        $('#horaEntregac').val(horaActual);
    }

    $('#select_86').change(function () {
        var selectedValue = $(this).val();
        if (selectedValue === '86.04') {
            $('#comentariodiv').show();
        } else {
            $('#comentariodiv').hide();
        }
    });

    function LlenarCalendario(Actividad) {
        if (Actividad) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?LlenarCalendario=true',
                type: 'post',
                dataType: 'json',
                data: { valor: Actividad },
                success: function (datos) {
                    console.log(typeof datos);
                    if (datos != 0) {
                        LlenarCalendarioC(datos);
                    } else {
                        $('#tabla-body').empty();
                        swal.fire('', 'No se encontraron datos de asignacion', '');
                    }
                }
            });
        }
    }

    var miColor;
    function ObtenerColor(valEnvio_No, callback) {
        if (valEnvio_No) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?ObtenerColor=true',
                type: 'post',
                dataType: 'json',
                data: { valor: valEnvio_No },
                success: function (data) {
                    if (data != 0) {
                        miColor = data.Picture;
                        callback(miColor);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al obtener color:', error);
                }
            });
        }
    }

    function LlenarCalendarioC(data) {

        var horas = ['01:00 - 02:00', '02:00 - 03:00', '03:00 - 04:00',
         '04:00 - 05:00', '05:00 - 06:00', '06:00 - 07:00',
          '07:00 - 08:00', '08:00 - 09:00', '09:00 - 10:00', 
          '10:00 - 11:00', '11:00 - 12:00', '12:00 - 13:00', 
          '13:00 - 14:00', '14:00 - 15:00', '15:00 - 16:00', 
          '16:00 - 17:00', '17:00 - 18:00', '18:00 - 19:00', 
          '19:00 - 20:00', '20:00 - 21:00', '21:00 - 22:00', 
          '22:00 - 23:00', '23:00 - 24:00'];
        var diasSemana = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        $('#tabla-body').empty();

        $.each(data, function (index, cliente) {
            var Actividad = cliente.Actividad || '';
            var Cliente = cliente.Cliente || '';
            var Envio_No = cliente.Envio_No || '';
            var Dia_Ent = cliente.Dia_Ent || '';
            var Hora_Ent = cliente.Hora_Ent || '';

            ObtenerColor(Envio_No, function (color) {
                var colorV = color.substring(4);
                var indiceColumna = diasSemana.indexOf(Dia_Ent);
                var indiceFila = -1;
                for (var i = 0; i < horas.length; i++) {
                    var horaInicio = horas[i].split(' - ')[0];
                    var horaFin = horas[i].split(' - ')[1];
                    if (Hora_Ent >= horaInicio && Hora_Ent < horaFin) {
                        indiceFila = i;
                        break;
                    }
                }

                var $fila = $('#tabla-body tr').filter(function () {
                    return $(this).find('td:first').text() === horas[indiceFila];
                });

                if ($fila.length === 0) {
                    $fila = $('<tr>');
                    $fila.append($('<td>').text(horas[indiceFila]));
                    $.each(diasSemana, function (indiceDia, dia) {
                        var $celda = $('<td>');
                        $fila.append($celda);
                    });
                    $('#tabla-body').append($fila);
                }

                var $celda = $fila.find('td').eq(indiceColumna + 1);
                var $div = $('<div>').text(Cliente).css('background-color', '#' + colorV);
                $celda.append($div);
            });
        });
        $('#modalCalendario').modal('show');
    }

    function LlenarSelectDiaEntrega() {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectDiaEntrega=true',
            type: 'post',
            dataType: 'json',
            success: function (datos) {
                $.each(datos, function (index, opcion) {
                    $('#diaEntregac').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
                    $('#diaEntrega').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
                });
            }
        });
    }

    function LlenarSelectRucCliente() {
        $('#ruc').select2({
            placeholder: 'Seleccione una opcion',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarSelectRucCliente: true
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
            placeholder: 'Seleccione una opcion',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarSelectRucCliente: true
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

    function LlenarTipoDonacion() {
        $('#tipoDonacion').select2({
            placeholder: 'Seleccione una opcion',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarTipoDonacion: true
                    }
                },
                processResults: function (data) {
                    var options = [];
                    if (data.respuesta === "No se encontraron datos para mostrar") {
                        options.push({
                            id: '',
                            text: data.respuesta
                        });
                    } else {
                        $.each(data.respuesta, function (index, item) {
                            var idDigits = item.id.slice(-3);
                            options.push({
                                id: idDigits,
                                text: item.text
                            });
                        });
                    }
                    return {
                        results: options
                    };
                },
                cache: true
            }
        });
    }

    function LlenarSelects_Val(valor) {
        $('#select_' + valor).select2({
            placeholder: 'Seleccione una opción',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        valor: valor,
                        LlenarSelects_Val: true
                    };
                },
                processResults: function (data) {
                    var options = [];
                    if (data.respuesta === "No se encontraron datos para mostrar") {
                        options.push({
                            id: '',
                            text: data.respuesta
                        });
                    } else {
                        $.each(data.respuesta, function (index, opcion) {
                            var option = {
                                id: opcion.id,
                                text: opcion.text,
                                color: opcion.color
                            };
                            options.push(option);
                        });
                    }
                    return {
                        results: options
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
        var valorTipoDonacion = $('#tipoDonacion').val() ? $('#tipoDonacion').val() : '.';
        formData.append('Calificacion', valorTipoDonacion);
        formData.append('Representante', $('#nombreRepre').val());
        formData.append('CI_RUC_R', $('#ciRepre').val());
        formData.append('Telefono_R', $('#telfRepre').val());
        formData.append('Contacto', $('#contacto').val());
        formData.append('Profesion', $('#cargo').val());
        formData.append('Dia_Ent', $('#diaEntrega').val());
        formData.append('Hora_Ent', $('#horaEntrega').val());
        formData.append('Direccion', $('#direccion').val());
        formData.append('Email', $('#email').val());
        formData.append('Email2', $('#email2').val());
        formData.append('Lugar_Trabajo', $('#referencia').val());
        formData.append('Telefono', $('#telefono').val());
        formData.append('TelefonoT', $('#telefono2').val());
        // Información adicional
        formData.append('CodigoA2', $('#select_88').val());
        formData.append('Dia_Ent2', $('#diaEntregac').val());
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

        formData.forEach(function (value, key) {
            console.log(key + ': ' + value);
        });

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
                    }
                }
            });
        }
    });

    function LimpiarSelectsInfoAdd() {
        $('#collapseTwo').collapse('hide');
        $('#select_82').val(null).trigger('change');
        $('#select_86').val(null).trigger('change');
        $('#select_88').val(null).trigger('change');
        $('#select_89').val(null).trigger('change');
        $('#select_90').val(null).trigger('change');
        $('#select_91').val(null).trigger('change');
        $('#archivoAdd').val('');
        $('#diaEntregac').val('');
        $('#horaEntregac').val('');
        $('#totalPersonas').val('');
        $('#infoNut').val('');
        $('#select_93').val(null).trigger('change');
        //miActividad = '';
    }

    var miRuc;
    var miCodigo;
    var miCliente;
    var nombreArchivo;
    $('#cliente').on('select2:select', function (e) {
        LimpiarSelectsInfoAdd();
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.CI_RUC;
        miCliente = data.text;
        if (data.id === '.') {
            swal.fire("", "No se encontró un RUC relacionado.", "error");
        } else {
            if ($('#ruc').find("option[value='" + data.id + "']").length) {
                $('#ruc').val(data.id).trigger('change');
            } else {
                var newOption = new Option(data.CI_RUC, data.id, true, true);
                $('#ruc').append(newOption).trigger('change');
            }
            var valorSeleccionado = $('#ruc').val();
            llenarCamposInfo(miCodigo);
        }
    });

    $('#ruc').on('select2:select', function (e) {
        LimpiarSelectsInfoAdd();
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.text;
        miCliente = data.Cliente;
        if (data.id === '.') {
            swal.fire("", "No se encontró un Cliente relacionado.", "error");
        } else {
            if ($('#cliente').find("option[value='" + data.id + "']").length) {
                $('#cliente').val(data.id).trigger('change');
            } else {
                var newOption = new Option(data.Cliente, data.id, true, true);
                $('#cliente').append(newOption).trigger('change');
            }
            var valorSeleccionado = $('#cliente').val();
            llenarCamposInfo(miCodigo);
        }
    });

    //var miActividad = '';
    $('#select_93').on('select2:select', function (e) {
        $('#collapseTwo').collapse('hide');
        var data = e.params.data;
        //miActividad = data.id;
        //console.log(miActividad);
        actualizarEstilo(data.color);
    });

    function llenarCamposInfo(Codigo) {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposInfo=true',
            type: 'post',
            dataType: 'json',
            data: { valor: Codigo },
            success: function (data) {
                if (data != 0) {
                    $('#nombreRepre').val(data.Representante);
                    $('#ciRepre').val(data.CI_RUC_R);
                    $('#telfRepre').val(data.Telefono_R);
                    $('#contacto').val(data.Contacto);
                    $('#cargo').val(data.Profesion);
                    $('#direccion').val(data.Direccion);
                    $('#email').val(data.Email);
                    $('#email2').val(data.Email2);
                    $('#referencia').val(data.Lugar_Trabajo);
                    $('#telefono').val(data.Telefono);
                    $('#telefono2').val(data.TelefonoT);
                    llenarSelects2Info(data.Actividad, data.Calificacion, data.CodigoA);

                    if (data.Dia_Ent == '.') {
                        $('#diaEntrega').val($('#diaEntrega option:first').val());
                    } else {
                        $('#diaEntrega').val(data.Dia_Ent);
                    }
                    if (/^\d{2}:\d{2}$/.test(data.Hora_Ent)) {
                        $('#horaEntrega').val(data.Hora_Ent);
                    } else {
                        $('#horaEntrega').val(horaActual);
                    }
                }
            }
        });
    }

    function llenarSelects2Info(actividad, calificacion, estado) {
        var params = {};
        if (actividad == '.') {
            params.actividad = false;
        } else {
            params.actividad = actividad;
        }
        if (calificacion == '.') {
            params.calificacion = false;
        } else {
            params.calificacion = calificacion;
        }
        if (estado == '.') {
            params.estado = false;
        } else {
            params.estado = estado;
        }
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?llenarSelects2Info=true',
            type: 'post',
            dataType: 'json',
            data: { 'params': params },
            success: function (data) {
                var dato1 = data.dato1;
                var dato2 = data.dato2;
                var dato3 = data.dato3;
                actualizarEstilo(dato1.Picture);
                if (dato1 != 0) {
                    if ($('#select_93').find("option[value='" + dato1.Cmds + "']").length) {
                        $('#select_93').val(dato1.Cmds).trigger('change');
                    } else {
                        var newOption = new Option(dato1.Proceso, dato1.Cmds, true, true);
                        $('#select_93').append(newOption).trigger('change');
                    }
                }
                if (dato2 != 0) {
                    var codigo = dato2.Codigo;
                    var Cod = codigo.substring(codigo.length - 3)
                    if ($('#tipoDonacion').find("option[value='" + Cod + "']").length) {
                        $('#tipoDonacion').val(Cod).trigger('change');
                    } else {
                        var newOption = new Option(dato2.Concepto, Cod, true, true);
                        $('#tipoDonacion').append(newOption).trigger('change');
                    }
                }
                if (dato3 != 0) {
                    if ($('#select_87').find("option[value='" + dato3.Cmds + "']").length) {
                        $('#select_87').val(dato3.Cmds).trigger('change');
                    } else {
                        var newOption = new Option(dato3.Proceso, dato3.Cmds, true, true);
                        $('#select_87').append(newOption).trigger('change');
                    }
                }
            }
        });
    }

    $('#botonInfoAdd').click(function () {
        if (miCodigo) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposInfoAdd=true',
                type: 'post',
                dataType: 'json',
                data: { valor: miCodigo },
                success: function (datos) {
                    if (datos != 0) {
                        llenarCamposInfoAdd(datos);
                    } else {
                        swal.fire('', 'No se encontraron datos adicionales', 'info');
                    }

                }
            });

        }
    });

    function llenarCamposInfoAdd(datos) {
        $('#diaEntregac').val(datos.Dia_Ent2);
        $('#horaEntregac').val(datos.Hora_Ent2);
        $('#totalPersonas').val(datos.No_Soc);
        $('#infoNut').val(datos.Observaciones);
        nombreArchivo = datos.Evidencias;
        llenarSelects2InfoAdd(datos.CodigoA2);
        llenarSelects2InfoAdd(datos.Envio_No);
        llenarSelects2InfoAdd(datos.Area);
        llenarSelects2InfoAdd(datos.Acreditacion);
        llenarSelects2InfoAdd(datos.Tipo_Dato);
        llenarSelects2InfoAdd(datos.Cod_Fam);
    }

    function llenarSelects2InfoAdd(valor) {
        if (valor == '.') {
            valor = false;
        }
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?llenarSelects2InfoAdd=true',
            type: 'post',
            dataType: 'json',
            data: { valor: valor },
            success: function (datos) {
                if (datos != 0) {
                    valorp = datos.Cmds.slice(0, 2);
                    if ($('#select_' + valorp).find("option[value='" + datos.Cmds + "']").length) {
                        $('#select_' + valorp).val(datos.Cmds).trigger('change');
                    } else {
                        var newOption = new Option(datos.Proceso, datos.Cmds, true, true);
                        $('#select_' + valorp).append(newOption).trigger('change');
                    }
                }

            }
        });
    }

    $('#btnMostrarModal').click(function () {
        var valorSeleccionado = $('#select_93').val();
        if (valorSeleccionado && valorSeleccionado.length > 0) {
            console.log(valorSeleccionado);
            LlenarCalendario(valorSeleccionado);
        } else {
            swal.fire('', 'Por favor, seleccione una organizacion', 'info');
        }
    });


    function actualizarEstilo(colorValor) {
        if (colorValor) {
            var hexColor = colorValor.substring(4);
            var darkerColor = darkenColor(hexColor, 20);
            $('.card-body').css('background-color', '#' + hexColor);
            $('.card-header, .modal-header').css('background-color', darkerColor);
        } else {
            $('.card-body').css('background-color', '#fffacd');
            $('.card-header').css('background-color', '#f3e5ab');
        }
    }

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

    function descargarArchivo(url, nombre) {
        var ruta = "../../" + url + nombre;
        var enlaceTemporal = $('<a></a>')
            .attr('href', ruta)
            .attr('download', nombre)
            .appendTo('body');
        enlaceTemporal[0].click();
        enlaceTemporal.remove();
    }

    $('#descargarArchivo').click(function () {
        if (nombreArchivo) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?descargarArchivo=true',
                type: 'post',
                dataType: 'json',
                data: { valor: nombreArchivo },
                success: function (data) {
                    if (data.response === 1) {
                        descargarArchivo(data.Dir, data.Nombre);
                    } else {
                        swal.fire('', 'El archivo no se encuentra en la base de datos', 'error');
                    }
                },
                error: function () {
                    swal.fire('', 'Error al intentar descargar el archivo', 'error');
                }
            });
        } else {
            if (miCliente) {
                swal.fire('', "No se encontró un archivo adjunto para el beneficiario " + miCliente, 'error');
            } else {
                swal.fire('', 'Seleccione un nombre de Beneficiario/Usuario o CI/RUC', 'error')
            }

        }
    });


</script>