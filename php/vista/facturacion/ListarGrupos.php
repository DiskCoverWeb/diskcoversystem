<script>

    /*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 06/01/2024
    FECHA MODIFICACION: 20/01/2024
    DESCIPCIÓN		:  Vista ListarGrupos
    */

    //Definicion de variables
    var TipoFactura = "FA";
    var PorGrupo = false;
    var PorDireccion = false;
    var Codigo1 = '';
    var Codigo2 = '';
    var activeTabId = '';
    var backgroundColor = 'white';
    var LstCount = 0;
    var Opcion = 0;
    var AdoQuery = [];

    let FA = {
        'Factura': '.',
        'Serie': '.',
        'Nuevo_Doc': true,
        'TC': TipoFactura,
        'Cod_CxC': '.',
        'Autorizacion': '.',
        'Tipo_PRN': '.',
        'Imp_Mes': '.',
        'Porc_IVA': '0.12',
        'Cta_CxP': '.',
        'Vencimiento': '.',
        'Cta_CxP_Anterior': '.',
        'Fecha_Corte': '.',
        'Fecha': '.'
    };

    $(document).ready(function () {
        //Form Activate 
        ActualizarDatosRepresentantes();
        DCGrupos();
        DCTipoPagoo();
        DCProductos();
        $('#DCLinea').prop('disabled', true);
        //PorGrupo = true;
        //Listar_Grupo(false);
        activeTabId = $('.nav-tabs .active a').attr('href') + 'Data';

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            activeTabId = $(e.target).attr('href') + 'Data'; // Obtiene el ID del contenido del tab activo
        });

        $(".nav-tabs li").click(function () {
            // Reinicia el estilo de todos los enlaces
            $(".nav-tabs li a").css({ "background-color": "white", "color": "#3c8dbc" });

            // Encuentra el href del enlace dentro del li clickeado para obtener el ID del contenido del tab
            var tabID = $(this).find('a').attr('href');

            // Llama a contentColor con el ID del contenido del tab
            contentColor(tabID + 'Data');

            SSTab2_Click(tabID + 'Data');

            $(this).find('a').css({ "background-color": backgroundColor, "color": "black" });
        });


        //Handle Cheq Events
        $('#CheqRangos').change(function () {
            if ($(this).is(':checked')) {
                // Acciones cuando el checkbox está marcado
                $('#DCGrupoI').prop('disabled', false);
                $('#DCGrupoF').prop('disabled', false);
            } else {
                // Acciones cuando el checkbox está desmarcado
                $('#DCGrupoI').prop('disabled', true);
                $('#DCGrupoF').prop('disabled', true);
            }
        });

        $('#CheqFA').change(function () {
            if ($(this).is(':checked')) {
                // Acciones cuando el checkbox está marcado
                $('#MBFecha').prop('disabled', false);
            } else {
                // Acciones cuando el checkbox está desmarcado
                $('#MBFecha').prop('disabled', true);
            }
        });

        $('#CheqPorRubro').change(function () {
            if ($(this).is(':checked')) {
                // Acciones cuando el checkbox está marcado
                $('#DCProductos').prop('disabled', false);
            } else {
                // Acciones cuando el checkbox está desmarcado
                $('#DCProductos').prop('disabled', true);
            }
        });

        $('#DCGrupoI').change(function () {
            Codigo1 = $(this).val();
        });

        $('#DCGrupoF').change(function () {
            Codigo2 = $(this).val();
        });

        $('#DCCliente').blur(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            Listar_Clientes_Grupo();
        });

        /*$('#CTipoConsulta').change(function () {
            CTipoConsulta($(this).val());
        });*/

        $('#CTipoConsulta').blur(function () {
            CTipoConsulta($(this).val());
        });

        $('#DCLinea').change(function () {
            DCLinea();
        });

        $('#DCLinea').blur(function () {
            DCLinea();
        });

        $('#DCProductos').change(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            Listar_Deuda_por_Api();
        });

        $('#DCProductos').blur(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            Listar_Deuda_por_Api();
        });

        //Handle Lost Focus
        $('#MBFechaI').blur(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            var parametros = {
                'TipoFactura': TipoFactura,
                'MBFechaI': $('#MBFechaI').val()
            }
            $.ajax({
                url: "../controlador/facturacion/ListarGruposC.php?DCLinea=true",
                type: "POST",
                data: { 'parametros': parametros },
                success: function (response) {
                    $('#myModal_espera').modal('hide');
                    var response = JSON.parse(response);
                    var data = response.datos;
                    if (data.length > 0) {
                        $('#DCLinea').empty();
                        $('#DCLinea').prop('disabled', false);
                        $.each(data, function (index, value) {
                            $('#DCLinea').append('<option value="' + value['Concepto'] + '">' + value['Concepto'] + '</option>');
                        });
                    } else {
                        $('#DCLinea').empty();
                        $('#DCLinea').prop('disabled', true);
                    }
                    $('#MBFechaF').val(response.fecha);
                }
            });
        });

        $('#MBFecha').blur(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            var parametros = {
                'TipoFactura': TipoFactura,
                'MBFecha': $('#MBFecha').val()
            }
            $.ajax({
                url: "../controlador/facturacion/ListarGruposC.php?MBFecha_LostFocus=true",
                type: "POST",
                data: { 'parametros': parametros },
                success: function (response) {
                    $('#myModal_espera').modal('hide');
                    var data = JSON.parse(response);
                    if (data.length > 0) {
                        $('#DCLinea').empty();
                        $('#DCLinea').prop('disabled', false);
                        $.each(data, function (index, value) {
                            $('#DCLinea').append('<option value="' + value['Concepto'] + '">' + value['Concepto'] + '</option>');
                        });
                    } else {
                        $('#DCLinea').empty();
                        $('#DCLinea').prop('disabled', true);
                    }
                }
            });
        });

        //Handle Buttons
        $('#Command5').click(function () {
            Command5_Click();
        });

        $('#btnGenerarFacturas').click(function () {
            GenerarFacturas_Click();
        });

        $('#btnListarGrupos').click(function () {
            Listado_x_Grupos();
        });

        $('#btnRecalcularFechas').click(function () {
            Recalcular_Fechas();
        });

        $('#btnImpresora').click(function () {
            Impresora();
        });

        $('#btnRecibos').click(function () {
            Recibos();
        });

        $('#btnExcel').click(function () {
            Excel();
        });

        $('#btnGenerarEliminarRubros').click(function () {
            $('#FAsignaFact').modal('show');
        });
    });
    //Definicion de metodos

    function Excel() {
        if (AdoQuery.length == 0) {
            swal.fire({
                title: 'Error',
                text: 'No hay datos para exportar',
                type: 'error'
            });
            return;
        }

        var parametros = {
            'AdoQuery': AdoQuery
        };
        console.log(parametros);
        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Excel=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                if (data.res == 1) {
                    swal.fire({
                        title: 'Excel creado',
                        text: '',
                        type: 'success'
                    });
                    var url = "../../TEMP/" + data.fileName;
                    var enlaceTemporal = $('<a></a>')
                        .attr('href', url)
                        .attr('download', data.fileName)
                        .appendTo('body');
                    enlaceTemporal[0].click();
                    enlaceTemporal.remove();
                } else {
                    swal.fire({
                        title: 'Error al crear el excel',
                        text: data.mensaje,
                        type: 'error'
                    });
                }
            }
        });
    }

    function Recibos() {
        var parametros = {
            'Opcion': Opcion,
            'CheqRangos': $('#CheqRangos').is(':checked') ? 1 : 0,
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'DCCliente': $('#DCCliente').val()
        };

        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Recibos=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                if (data.res == 1) {
                    swal.fire({
                        title: 'Recibo creado',
                        text: '',
                        type: 'success'
                    });
                    var url = "../../TEMP/" + data.fileName;
                    var enlaceTemporal = $('<a></a>')
                        .attr('href', url)
                        .attr('download', data.fileName)
                        .appendTo('body');
                    enlaceTemporal[0].click();
                    enlaceTemporal.remove();
                } else {
                    swal.fire({
                        title: 'Error al crear el recibo',
                        text: data.mensaje,
                        type: 'error'
                    });
                }
            }
        });
    }

    function Impresora() {
        var parametros = {
            'Opcion': Opcion,
            'AdoQuery': AdoQuery,
            'CheqDesc': $('#CheqDesc').is(':checked') ? 1 : 0,
        };

        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Impresora=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                if (data.res == 1) {
                    swal.fire({
                        title: 'Pdf creado',
                        text: '',
                        type: 'success'
                    });
                    var url = "../../TEMP/" + data.fileName;
                    var enlaceTemporal = $('<a></a>')
                        .attr('href', url)
                        .attr('download', data.fileName)
                        .appendTo('body');
                    enlaceTemporal[0].click();
                    enlaceTemporal.remove();
                } else {
                    swal.fire({
                        title: 'Error',
                        text: data.mensaje,
                        type: 'error'
                    });
                }
            }
        });
    }

    function Recalcular_Fechas() {
        var mensaje = "Recalcular Meses de Cobros";
        var titulo = "Formulario de Recalculación";
        swal.fire({
            title: titulo,
            text: mensaje,
            type: 'info',
            showCancelButton: true
        }).then((result) => {
            if (result.value) {
                $('#myModal_espera').modal('show');
                $.ajax({
                    url: "../controlador/facturacion/ListarGruposC.php?Recalcular_Fechas=true",
                    type: "POST",
                    dataType: 'json',
                    success: function (response) {
                        var data = response;
                        $('#myModal_espera').modal('hide');
                        if (data.res == 1) {
                            swal.fire({
                                title: 'Recalculo de Fechas',
                                text: data.mensaje,
                                type: 'success'
                            });
                        } else {
                            swal.fire({
                                title: 'Error',
                                text: data.mensaje,
                                type: 'error'
                            });
                        }
                    }
                });
            } else {
                return;
            }
        });
    }

    function Listado_x_Grupos() {
        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listado_x_Grupos=true",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                if (data.res == 1) {
                    swal.fire({
                        title: 'Pdf creado',
                        text: data.mensaje,
                        type: 'success'
                    });
                    var url = "../../TEMP/" + data.fileName;
                    var enlaceTemporal = $('<a></a>')
                        .attr('href', url)
                        .attr('download', data.fileName)
                        .appendTo('body');
                    enlaceTemporal[0].click();
                    enlaceTemporal.remove();
                } else {
                    swal.fire({
                        title: 'Error',
                        text: data.mensaje,
                        type: 'error'
                    });
                }
            }
        });
    }

    function GenerarFacturas_Click() {

        if ($('#CTipoConsulta').val() != "2") {
            swal.fire({
                title: 'Error',
                text: `Debe seleccionar la opción: 'Listar Todos' caso contrario no podrá facturar.`,
                type: 'error'
            });
            return;
        }

        var parametros = {
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'MBFecha': $('#MBFecha').val(),
            'FA': FA,
            'DCTipoPago': $('#DCTipoPago').val(),
            'DCLinea': $('#DCLinea').val(),
            'CTipoConsulta': $('#CTipoConsulta').val(),
            'TipoFactura': TipoFactura,
            'PorGrupo': PorGrupo ? 1 : 0,
            'CheqRangos': $('#CheqRangos').is(':checked') ? 1 : 0,
            'CheqFA': $('#CheqFA').is(':checked') ? 1 : 0,
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'DCCliente': $('#DCCliente').val()
        };
        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?GenerarFacturas_Click=true",
            type: "POST",
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                var tmp = data.FA;
                for (var key in tmp) {
                    if (tmp.hasOwnProperty(key)) {
                        FA[key] = tmp[key];
                    }
                }
                if (data.response == 1) {
                    swal.fire({
                        title: data.Titulo,
                        text: data.Mensaje,
                        type: 'info',
                        showCancelButton: true
                    }).then((result) => {
                        if (result.value) {
                            ProcGrabarMult(parametros);
                        } else {
                            return;
                        }
                    });
                } else {
                    swal.fire({
                        title: data.Titulo,
                        text: data.Mensaje,
                        type: 'error'
                    });
                }
            }
        });
    }

    function ProcGrabarMult(data) {
        var parametros = data;
        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?ProcGrabarMult=true",
            type: "POST",
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                if (data.Res == 1) {
                    swal.fire({
                        title: 'Facturas',
                        text: data.Mensaje,
                        type: 'info'
                    });
                    $('#LxGData').empty();
                    $('#LxGData').html(data.datos.tbl);
                    $('#LxGData #datos_t tbody').css('height', '36vh');
                    $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);

                } else {
                    swal.fire({
                        title: 'Error',
                        text: data.Mensaje,
                        type: 'error'
                    });
                }
            }
        });

    }

    function Command5_Click() {
        var clientesMarcados = [];
        $('#LstClientes input[type="checkbox"]:checked').each(function () {
            var index = $(this).attr('id');

            var clienteNombre = $(this).siblings('.cliente-nombre').text();
            var clienteEmail = $(this).siblings('.cliente-email').text();
            var clienteSaldo = $(this).siblings('.cliente-saldo').text();

            var cliente = {
                'Cliente': clienteNombre,
                'Email': clienteEmail,
                'Saldo': clienteSaldo
            };
            clientesMarcados.push(cliente);
        });

        if (clientesMarcados.length == 0) {
            swal.fire({
                title: 'Error',
                text: 'Debe seleccionar al menos un cliente para enviar el correo.',
                type: 'error'
            });
            return;
        }

        if ($('#TxtAsunto').val() == '') {
            swal.fire({
                title: 'Error',
                text: 'Debe ingresar un asunto para enviar el correo',
                type: 'error'
            });
            return;
        }

        if ($('#TxtRemitente').val() == '') {
            swal.fire({
                title: 'Error',
                text: 'Debe ingresar un remitente para enviar el correo',
                type: 'error'
            });
            return;
        }

        if ($('#TxtMensaje').val() == '') {
            swal.fire({
                title: 'Error',
                text: 'Debe ingresar un mensaje para enviar el correo',
                type: 'error'
            });
            return;
        }

        $('#myModal_espera').modal('show');
        var parametros = {
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'CheqResumen': $('#CheqResumen').is(':checked') ? 1 : 0,
            'CheqVenc': $('#CheqVenc').is(':checked') ? 1 : 0,
            'LstClientes': clientesMarcados,
            'TxtAsunto': $('#TxtAsunto').val(),
            'TxtMensaje': $('#TxtMensaje').val(),
            'CheqConDeuda': $('#CheqConDeuda').is(':checked') ? 1 : 0,
        };

        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Command5_Click=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                //TODO: Implementar sistema de envio de correos.
                $('#myModal_espera').modal('hide');
            }
        });
    }

    function SSTab2_Click(tabID) {
        Tipo_Rango_Grupos();
        var parametros = {
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'Opcion': activeTabId,
            'CheqRangos': $('#CheqRangos').is(':checked') ? 1 : 0,
            'PorGrupo': PorGrupo == true ? 1 : 0,
            'PorDireccion': PorDireccion == true ? 1 : 0,
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'DCCliente': $('#DCCliente').val(),
            'CheqResumen': $('#CheqResumen').is(':checked') ? 1 : 0,
            'CheqVenc': $('#CheqVenc').is(':checked') ? 1 : 0,
            'DCProductosVisible': $('#DCProductos').is(':visible') ? 1 : 0,
            'OpcActivos': $('#OpcActivos').is(':checked') ? 1 : 0,
        };
        $('#myModal_espera').modal('show');
        switch (tabID) {
            case '#LxGData':
                Opcion = 0;
                Listar_Clientes_Grupo();
                break;
            case '#PmAData':
                Opcion = 1;
                Pensiones_Mensuales_Anio(parametros);
                break;
            case '#AcDData':
                Opcion = 2;
                Listado_Becados(parametros);
                break;
            case '#NdAData':
                Opcion = 3;
                Nomina_Alumnos(parametros);
                break;
            case '#EpEData':
                Opcion = 4;
                Listar_Clientes_Email(parametros);
                break;
            case '#RpPmData':
                Opcion = 5;
                Resumen_Pensiones_Mes(parametros);
                break;
            case '#EdData':
                Opcion = 6;
                Listar_Deuda_por_Api();
                break;

        }
    }

    function Listar_Clientes_Email(parametros) {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listar_Clientes_Email=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                $('#myModal_espera').modal('hide');
                var data = response;
                $('#LstClientes').empty();
                if (data.LstClientes.length > 0) {
                    $('#Command5').prop('disabled', false);
                    $.each(data.LstClientes, function (index, value) {
                        // Crear el elemento label con su contenido
                        var labelContent = `<label for="LstCli${index}" class="cliente-container">
                            <input type="checkbox" name="LstCli${index}" id="LstCli${index}" />
                            <span class="cliente-nombre">${value.Cliente}</span>
                            <span class="cliente-email">${value.Email}</span>
                            <span class="cliente-saldo">${value.SaldoPendiente}</span>
                        </label><br>`;

                        // Añadir el label al DOM
                        $('#LstClientes').append(labelContent);

                        // Aplicar estilos CSS a .cliente-container y sus hijos directamente con jQuery
                        $('.cliente-container').last().css({
                            'display': 'flex',
                            'justify-content': 'space-between',
                            'align-items': 'center',
                            'width': '100%'
                        });

                        $('.cliente-container').last().find('.cliente-nombre').css({
                            'flex': '1',
                            'text-align': 'left'
                        });

                        $('.cliente-container').last().find('.cliente-email').css({
                            'flex': '1',
                            'text-align': 'center'
                        });

                        $('.cliente-container').last().find('.cliente-saldo').css({
                            'flex': '1',
                            'text-align': 'right'
                        });
                    });

                    $('#LstCli0').change(function () {
                        // Verifica si el checkbox 'TODOS' está marcado
                        var estado = $(this).is(':checked');

                        //Si TODOS está marcado, los demas inputs también ser marcan.
                        $('#LstClientes input[type="checkbox"]').prop('checked', estado);
                    });
                    AdoQuery = data.AdoQuery;
                }
            }
        });
    }

    function Resumen_Pensiones_Mes(parametros) {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Resumen_Pensiones_Mes=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                $('#myModal_espera').modal('hide');
                var data = response;
                $('#RpPmData').empty();
                $('#RpPmData').html(data.tbl);
                $('#RpPmData #datos_t tbody').css('height', '36vh');
                $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);
                AdoQuery = data.AdoQuery;
            }
        });
    }

    function Nomina_Alumnos(parametros) {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Nomina_Alumnos=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                $('#myModal_espera').modal('hide');
                var data = response;
                $('#NdAData').empty();
                $('#NdAData').html(data.tbl);
                $('#NdAData #datos_t tbody').css('height', '36vh');
                $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);
                AdoQuery = data.AdoQuery;
            }
        });
    }

    function Listado_Becados(parametros) {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listado_Becados=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                $('#myModal_espera').modal('hide');
                var data = response;
                $('#AcDData').empty();
                $('#AcDData').html(data.tbl);
                $('#AcDData #datos_t tbody').css('height', '36vh');
                $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);
                AdoQuery = data.AdoQuery;
            }
        });
    }

    function Pensiones_Mensuales_Anio(parametros) {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Pensiones_Mensuales_Anio=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                $('#myModal_espera').modal('hide');
                var data = response;
                $('#PmAData').empty();
                $('#PmAData').html(data.tbl);
                $('#PmAData #datos_t tbody').css('height', '36vh');
                $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);
                $('#Label9').val(data.Caption9);
                $('#Label10').val(data.Caption10);
                $('#Label4').val(data.Caption4);
                AdoQuery = data.AdoQuery;
            }
        });
    }

    function Tipo_Rango_Grupos() {
        var CheqRangos = $('#CheqRangos').is(':checked') ? 1 : 0;
        if (CheqRangos != false) {
            Codigo1 = $('#DCGrupoI').val();
            Codigo2 = $('#DCGrupoF').val();
        } else {
            if (PorGrupo || PorDireccion) {
                Codigo1 = $('#DCCliente').val();
                Codigo2 = $('#DCCliente').val();
            } else {
                Codigo1 = 'Todos';
                Codigo2 = 'Todos';
            }
        }
        if (Codigo1 == "") {
            Codigo1 = '<?php echo G_NINGUNO; ?>';
        }
        if (Codigo2 == "") {
            Codigo2 = '<?php echo G_NINGUNO; ?>';
        }
    }

    function contentColor(tabID) {
        switch (tabID) {
            case '#LxGData':
                backgroundColor = '#fffec2';
                break;
            case '#PmAData':
                backgroundColor = '#ff9688';
                break;
            case '#AcDData':
                backgroundColor = '#a5eea0';
                break;
            case '#NdAData':
                backgroundColor = '#b2dafa';
                break;
            case '#EpEData':
                backgroundColor = '#e4fbfb';
                break;
            case '#RpPmData':
                backgroundColor = '#ecd6c0';
                break;
            case '#EdData':
                backgroundColor = '#ffe5f0';
                break;
            default:
                backgroundColor = 'white';
                break;
        }

        $('#tabContent').css({ "background-color": backgroundColor });

    }

    function Listar_Deuda_por_Api() {
        var parametros = {
            'MBFechaF': $('#MBFechaF').val(),
            'CheqRangos': $('#CheqRangos').is(':checked') ? 1 : 0,
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'CheqVenc': $('#CheqVenc').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listar_Deuda_por_Api=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                var data = response;
                $('#myModal_espera').modal('hide');
                if (activeTabId != '#EpEData') {
                    $(activeTabId).empty();
                    $(activeTabId).html(data.tbl);
                    $(`${activeTabId} #datos_t tbody`).css('height', '36vh');
                    $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);
                    AdoQuery = data.AdoQuery;
                }
            }
        });
    }

    function CTipoConsulta(caso) {
        switch (caso) {
            case '0':
                PorGrupo = true;
                PorDireccion = false;
                Listar_Grupo(false);
                $('#DCCliente').prop('disabled', false);
                break;
            case '1':
                PorDireccion = true;
                PorGrupo = false;
                Listar_Grupo(true);
                $('#DCCliente').prop('disabled', false);
                break;
            case '2':
                $('#DCCliente').prop('disabled', true);
                break;
        }
    }

    function DCLinea() {
        $('#myModal_espera').modal('show');
        FA.Cod_CxC = $('#DCLinea').val();
        FA.Fecha = $('#CheqFA').prop('checked') ? $('#MBFecha').val() : $('#MBFechaI').val();
        var parametros = {
            'FA': FA
        }
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?DCLinea_LostFocus=true",
            type: "POST",
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                $('#myModal_espera').modal('hide');
                var tmp = data.tmp.TFA;
                for (var key in tmp) {
                    if (tmp.hasOwnProperty(key)) {
                        FA[key] = tmp[key];
                    }
                }
                $('#Label2').text(data.Caption);
            }
        });
    }

    function Listar_Clientes_Grupo() {
        var parametros = {
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'PorGrupo': PorGrupo,
            'PorDireccion': PorDireccion,
            'CheqRangos': $('#CheqRangos').is(':checked') ? 1 : 0,
            'DCCliente': $('#DCCliente').val()
        };
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listar_Clientes_Grupo=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: 'json',
            success: function (response) {
                var data = response
                $('#myModal_espera').modal('hide');
                $('#LxGData').empty();
                $('#LxGData').html(data.tbl);
                $('#LxGData #datos_t tbody').css('height', '36vh');
                $('#TotalRegistros').text('Total Registros: ' + data.numRegistros);
                AdoQuery = data.AdoQuery;
            }
        });


    }

    function Listar_Grupo(tmp) {
        tmp = tmp == true ? 1 : 0;
        var parametros = {
            'PorDireccion': tmp
        };
        $('#myModal_espera').modal('show');
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listar_Grupo=true",
            type: "POST",
            data: { 'parametros': parametros },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.length > 0) {
                    $('#myModal_espera').modal('hide');
                    $('#DCCliente').empty();
                    $.each(data, function (index, value) {
                        if (tmp) {
                            $('#DCCliente').append('<option value="' + value['Direccion'] + '">' + value['Direccion'] + '</option>');
                        } else {
                            $('#DCCliente').append('<option value="' + value['Grupo'] + '">' + value['Grupo'] + '</option>');
                        }
                    });
                    if (data.length > 0) {
                        var lastValue = tmp ? data[data.length - 1]['Direccion'] : data[data.length - 1]['Grupo'];
                        $('#DCCliente').val(lastValue);
                    }
                }
            }
        });

    }

    function DCProductos() {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?DCProductos=true",
            type: "POST",
            success: function (response) {
                var data = JSON.parse(response);
                if (data.length > 0) {
                    $('#DCProductos').empty();
                    $.each(data, function (index, value) {
                        $('#DCProductos').append('<option value="' + value['Producto'] + '">' + value['Producto'] + '</option>');
                    });
                }
            }
        });
    }

    function ActualizarDatosRepresentantes() {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?ActualizarDatosRepresentantes=true",
            type: "POST",
            success: function (response) {
            }
        });
    }

    function DCGrupos() {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?DCGrupos=true",
            type: "POST",
            success: function (response) {
                var data = JSON.parse(response);
                if (data.length > 0) {
                    $('#DCGrupoI').empty();
                    $('#DCGrupoF').empty();
                    $.each(data, function (index, value) {
                        $('#DCGrupoI').append('<option value="' + value['Grupo'] + '">' + value['Grupo'] + '</option>');
                        $('#DCGrupoF').append('<option value="' + value['Grupo'] + '">' + value['Grupo'] + '</option>');
                    });

                    var ultimoGrupo = data[data.length - 1]['Grupo'];
                    $('#DCGrupoF').val(ultimoGrupo);
                }
            }
        });

    }

    function DCTipoPagoo() {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?DCTipoPago=true",
            type: "POST",
            success: function (response) {
                var data = JSON.parse(response);
                if (data.length > 0) {
                    $('#DCTipoPago').empty();
                    $.each(data, function (index, value) {
                        $('#DCTipoPago').append('<option value="' + value['CTipoPago'] + '">' + value['CTipoPago'] + '</option>');
                    });
                }
            }
        });
    }

</script>
<style>
    .alineacion {
        margin-left: -5px;
    }

    .inline {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        margin-right: 10px;
    }

    .inline input {
        margin-left: 10px;
    }

    .totales {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        margin-top: 10px;
    }

    #TxtFile {
        width: 100%;
    }

    .espacio {
        min-height: 47vh;
    }

    body {
        padding-right: 0px !important;
    }
</style>
<div>
    <div class="row"> <!--Botones-->
        <div class="col-sm-6" style="padding: 0px 0px 0px 10px;" id="btnsContainers">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-default" style="border: solid 1px">
                <img src="../../img/png/salire.png">
            </a>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Generación de Facturas en Bloque" id="btnGenerarFacturas" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/facturas.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Imprime un listado resumido de los grupos creados" id="btnListarGrupos" onclick=""
                style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/papers.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Generar o Eliminar por Lotes los Rubros a Facturar" id="btnGenerarEliminarRubros" onclick=""
                style="border: solid 1px">
                <img src="../../img/png/anular.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Genera las Deudas Pendientes" id="btnGenerarDeudaPendiente" onclick="" style="border: solid 1px">
                <img src="../../img/png/deuda.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Imprimir Resultados"
                id="btnImpresora" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/printer.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Recalcula Fecha de Facturación" id="btnRecalcularFechas" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/renumerar.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Imprimir Recibos"
                id="btnRecibos" onclick="" style="border: solid 1px">
                <img src="../../img/png/reporte_1.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Enviar Excel"
                id="btnExcel" onclick="" style="border: solid 1px">
                <img src="../../img/png/excel2.png">
            </button>
        </div>

        <div class="col-sm-2" style="">
            <label for="CheqRangos" style="">
                <input type="checkbox" name="CheqRangos" id="CheqRangos" /> Por Rangos Grupos:
            </label>
            <label for="CheqPendientes" style="font-size:12.9px">
                <input type="checkbox" name="CheqPendientes" id="CheqPendientes" checked /> Listar Solo Pendientes
            </label>
        </div>
        <div class="col-sm-4" style="">
            <div class="row">
                <select name="DCGrupoI" id="DCGrupoI" style="width:48%; max-width: 48%;" disabled>
                    <option value="0"></option>
                </select>
                <select name="DCGrupoF" id="DCGrupoF" style="width:48%; max-width: 48%;" disabled>
                    <option value="0"></option>
                </select>
            </div>
            <div class="row" style="padding-top: 3px;">
                <label for="DCTipoPago"> TIPO DE PAGO </label>
                <select name="DCTipoPago" id="DCTipoPago" style="width:73%; max-width: 73%;">
                    <option value="0"></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row alineacion">
        <div class="col-sm-2">
            <div class="row">
                <div class="col-sm-6" style="padding: 0px;">
                    <label for="CheqVenc"> Emisión:
                    </label>
                </div>
                <div class="col-sm-6" style="padding: 0px;">
                    <input type="checkbox" name="CheqVenc" id="CheqVenc" checked /> Vencimiento
                </div>
            </div>
            <div class="row" style="font-size:12.9px">
                <input id="MBFechaI" name="MBFechaI" type="date" style=" width:48%; text-align:center;"
                    value="<?php echo date("Y-m-d", strtotime('first day of january this year')); ?>" />
                <input id="MBFechaF" name="MBFechaF" type="date" style=" width:48%; text-align:center;"
                    value="<?php echo date('Y-m-d'); ?>" />
            </div>
        </div>
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-4" style="padding: 0px;">
                    <select name="CTipoConsulta" id="CTipoConsulta" style="width:100%; max-width: 100%;">
                        <option value="0">Listar por Grupo</option>
                        <option value="1">Listar por Direccion</option>
                        <option value="2">Listar Todos</option>
                    </select>
                </div>
                <div class="col-sm-4" style="padding: padding: 0px 0px 0px 15px;">
                    <label for="CheqResumen" style="font-size:12.9px">
                        <input type="checkbox" name="CheqResumen" id="CheqResumen" checked /> Resumen Periodos
                    </label>
                </div>
                <div class="col-sm-4" style="padding: 0px;">
                    <label for="CheqDesc" style="font-size:12.9px">
                        <input type="checkbox" name="CheqDesc" id="CheqDesc" /> Descuentos
                    </label>
                </div>
            </div>
            <div class="row">
                <select name="DCCliente" id="DCCliente" style="width:99%; max-width: 99%;">
                    <option value="0"></option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="row">
                <div class="col-sm-4" style="padding: 0px;">
                    <label for="CheqFA">
                        <input type="checkbox" name="CheqFA" id="CheqFA" /> Fecha FA
                    </label>
                </div>
                <div class="col-sm-8" style="padding: 0px;">
                    <label for="" id="Label2">
                        Linea de Facturación:
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4" style="padding: 0px 3px 0px 0px; font-size:12.9px">
                    <input id="MBFecha" name="MBFecha" type="date" style=" width:100%; text-align:center;"
                        value="<?php echo date('Y-m-d'); ?>" disabled />
                </div>
                <div class="col-sm-8" style="padding: 0px;">
                    <select name="DCLinea" id="DCLinea" style="width:99%; max-width: 99%;">
                        <option value="0"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="row">
                <label for="CheqPorRubro">
                    <input type="checkbox" name="CheqPorRubro" id="CheqPorRubro" /> Por Rubro de Facturacion
                </label>
            </div>
            <div class="row">
                <select name="DCProductos" id="DCProductos" style="width:99%; max-width: 99%;" disabled>
                    <option value="0"></option>
                </select>
            </div>
            <div class="row">
                <label for="OpcActivos">
                    <input type="radio" name="OpcActivos" id="OpcActivos" checked /> Activo
                </label>
                <label for="OpcInactivos">
                    <input type="radio" name="OpcActivos" id="OpcInactivos" /> Inactivo
                </label>
            </div>
        </div>
    </div>
    <div class="row alineacion" style="margin-right: 0vw;"><!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="active"><a href="#LxG" aria-controls="LxG" role="tab" data-toggle="tab"
                    style="background-color: #fffec2;">LISTADO POR GRUPOS</a></li>
            <li role="presentation"><a href="#PmA" aria-controls="PmA" role="tab" data-toggle="tab">PENSION
                    MENSUAL DEL AÑO</a>
            </li>
            <li role="presentation"><a href="#AcD" aria-controls="AcD" role="tab" data-toggle="tab">ALUMNOS
                    CON
                    DESCUENTO</a></li>
            <li role="presentation"><a href="#NdA" aria-controls="NdA" role="tab" data-toggle="tab">NOMINA
                    DE
                    ALUMNOS</a></li>
            <li role="presentation"><a href="#EpE" aria-controls="EpE" role="tab" data-toggle="tab">ENVIOS
                    POR
                    EMAIL
                </a></li>
            <li role="presentation"><a href="#RpPm" aria-controls="RpPm" role="tab" data-toggle="tab">RESUMEN
                    PENSIONES
                    POR MES
                </a></li>
            <li role="presentation"><a href="#Ed" aria-controls="Ed" role="tab" data-toggle="tab">ENVIAR
                    DEUDA POR
                    API Y EMAIL
                </a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" id="tabContent" style="background-color: #fffec2;">
            <div role="tabpanel" class="tab-pane fade in active" id="LxG">
                <fieldset class="espacio">
                    <div id="LxGData">

                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="PmA">
                <fieldset class="espacio">
                    <div id="PmAData">

                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="AcD">
                <fieldset class="espacio">
                    <div id="AcDData">

                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="NdA">
                <fieldset class="espacio">
                    <div id="NdAData">

                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="EpE">
                <fieldset class="espacio">
                    <div id="EpEData">
                        <div class="row" style="margin-top:2vh;">
                            <div class="col-sm-5">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="Label13" id="Label12">Remitente</label>
                                    </div>
                                    <div class="sol-sm-9">
                                        <input type="email" name="Label13" id="Label13"
                                            style="width: 70%; max-width:75%">
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-sm-3">
                                        <label for="TxtAsunto" id="Label7">Asunto</label>
                                    </div>
                                    <div class="sol-sm-9">
                                        <input type="text" name="TxtAsunto" id="TxtAsunto"
                                            style="width: 70%; max-width:75%">
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-sm-3">
                                        <label for="LblArchivo">Adjuntar</label>
                                    </div>
                                    <div class="sol-sm-9">
                                        <input type="file" name="LblArchivo" id="LblArchivo"
                                            style="width: 70%; max-width:75%">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="TxtMensaje">Esciba el mensaje</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="TxtMensaje" id="TxtMensaje"
                                            rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="CheqConDeuda"> <input type="checkbox" name="CheqConDeuda"
                                                id="CheqConDeuda" checked> Enviar mail con deuda pendiente</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                                            title="Enviar mail" id="Command5" onclick="" disabled>Enviar mail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:1vh;">
                            <fieldset
                                style="min-height: 26vh; background-color: white; margin: 20px; max-height: 26vh; overflow-y: auto;">
                                <div class="col-sm-12" id="LstClientes">

                                </div>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="RpPm">
                <fieldset class="espacio">
                    <div id="RpPmData">

                    </div>
                </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="Ed">
                <fieldset class="espacio">
                    <div id="EdData">

                    </div>
                </fieldset>
            </div>
        </div>

    </div>
    <div class="row totales alineacion">
        <label class="inline" for="Label9"> SubTotal CxC
            <input type="text" name="Label9" id="Label9" />
        </label>
        <label class="inline" for="Label10"> Total Anticipos
            <input type="text" name="Label10" id="Label10" />
        </label>
        <label class="inline" for="Label4"> Total Anticipos
            <input type="text" name="Label4" id="Label4" />
        </label>
        <label class="inline" for="Label5" id="TotalRegistros">
        </label>
    </div>
    <div class="row alineacion">
        <!--<textarea class="form-control" name="TxtFile" id="TxtFile" rows="10" readonly></textarea>-->
    </div>
</div>

<!-- Estilo Modales -->
<style>
    .centrar {
        text-align: center;
    }

    .label-colores {
        background-color: black;
        color: yellow;
        width: 100%;
    }

    .especiado {
        height: 30vh;
        max-height: 30vh;
        overflow-y: auto;
    }
</style>

<!-- Modal FAsignaFact -->
<div class="modal fade" tabindex="-1" role="dialog" id="FAsignaFact">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">CUENTAS POR COBRAR / CUENTAS POR PAGAR</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-10 centrar">
                                    <label for="" id="LblCliente" class="label-colores">
                                        CUENTA DE ASIGNACION
                                    </label>
                                </div>
                                <div class="col-sm-2 centrar" style="padding: 0; margin: 0;">
                                    <label for="" id="LblCodigo" class="label-colores">
                                        XXXXXXXXXX
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="row">
                                        <label for="" id="Label1" style="margin-left: 1vw;">
                                            Label1
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div id="LstMeses" class="espaciado">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <label for="" style="margin-left: 1vw;">
                                            Productos
                                        </label>
                                    </div>
                                    <div id="DCInv" class="espaciado">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row centrar">
                                <button class="btn btn-default" data-toggle="tooltip" data-placement="left"
                                    title="Insertar" id="Command1" onclick="">
                                    <img src="../../img/png/insertar.png">
                                </button>
                                <button class="btn btn-default" data-toggle="tooltip" data-placement="left"
                                    title="Modificar" id="Command3" onclick="">
                                    <img src="../../img/png/modificar.png">
                                </button>
                                <button class="btn btn-default" data-dismiss="modal" data-toggle="tooltip"
                                    data-placement="left" title="Cancelar" id="Command2" onclick="">
                                    <img src="../../img/png/close.png">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <form action="" class="form-inline">
                            <div class="form-group">
                                <label for="TxtDia">Día</label>
                                <input type="number" name="TxtDia" id="TxtDia" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="TxtArea">VALOR A FACTURAR</label>
                                <input type="text" name="TxtArea" id="TxtArea" placeholder="0.00" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="TxtDesc">DESCUENTO</label>
                                <input type="text" name="TxtDesc" id="TxtDesc" placeholder="0.00" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="TxtDesc2">DESCUENTO 2</label>
                                <input type="text" name="TxtDesc2" id="TxtDesc2" placeholder="0.00" class="form-control">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->