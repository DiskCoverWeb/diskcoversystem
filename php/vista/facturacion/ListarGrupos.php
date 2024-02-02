<script>
    //Definicion de variables
    var TipoFactura = "FA";
    var PorGrupo = false;
    var PorDireccion = false;
    var Codigo1 = '';
    var Codigo2 = '';
    var activeTabId = '';

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
        PorGrupo = true;
        Listar_Grupo(false);
        activeTabId = $('.nav-tabs .active a').attr('href') + 'Data';

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            activeTabId = $(e.target).attr('href') + 'Data'; // Obtiene el ID del contenido del tab activo
        });

        console.log('<?php echo $_SESSION['INGRESO']['Fecha_P12']; ?>');
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

        $('#DCCliente').change(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            Listar_Clientes_Grupo();
        });

        $('#CTipoConsulta').change(function () {
            CTipoConsulta($(this).val());
        });

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





    });
    //Definicion de metodos

    function Listar_Deuda_por_Api() {
        var parametros = {
            'MBFechaF': $('#MBFechaF').val(),
            'CheqRangos': $('#CheqRangos').is(':checked'),
            'Codigo1': Codigo1,
            'Codigo2': Codigo2,
            'CheqVenc': $('#CheqVenc').is(':checked')
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
            'CheqRangos': $('#CheqRangos').is(':checked'),
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
            }
        });


    }

    function Listar_Grupo(tmp) {
        var parametros = {
            'PorDireccion': tmp
        };
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?Listar_Grupo=true",
            type: "POST",
            data: { 'parametros': parametros },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.length > 0) {
                    $('#DCCliente').empty();
                    $.each(data, function (index, value) {
                        if (tmp) {
                            $('#DCCliente').append('<option value="' + value['Direccion'] + '">' + value['Direccion'] + '</option>');
                        } else {
                            $('#DCCliente').append('<option value="' + value['Grupo'] + '">' + value['Grupo'] + '</option>');
                        }
                    });
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
</style>
<div>
    <div class="row"> <!--Botones-->
        <div class="col-sm-6" style="padding: 0px 0px 0px 10px;" id="btnsContainers">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-default" style="border: solid 1px">
                <img src="../../img/png/salire.png">
            </a>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Generar Facturas"
                id="btnGenerarFacturas" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/facturas.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Listar por Grupos"
                id="btnListarGrupos" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/papers.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Generar Eliminar Rubros" id="btnGenerarEliminarRubros" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/upload-file.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom"
                title="Generar Deuda Pendiente" id="btnGenerarDeudaPendiente" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/alumnos.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Recalcular Fechas"
                id="btnRecalcularFechas" onclick="" style="border: solid 1px" disabled>
                <img src="../../img/png/FRecaudacionBancosPreFa/renumerar.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Imprimir Codigos"
                id="btnImprimirCodigos" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/printer.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Recibos"
                id="btnRecibos" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/printer.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Excel" id="btnExcel"
                onclick="" style="border: solid 1px">
                <img src="../../img/png/excel2.png">
            </button>
        </div>

        <div class="col-sm-2" style="">
            <label for="CheqRangos" style="">
                <input type="checkbox" name="CheqRangos" id="CheqRangos" /> Por Rangos Grupos:
            </label>
            <label for="CheqPendientes" style="font-size:12.9px">
                <input type="checkbox" name="CheqPendientes" id="CheqPendientes" /> Listar Solo Pendientes
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
                    <input type="checkbox" name="CheqVenc" id="CheqVenc" /> Vencimiento
                </div>
            </div>
            <div class="row" style="font-size:12.9px">
                <input id="MBFechaI" name="MBFechaI" type="date" style=" width:48%; text-align:center;"
                    value="<?php echo date('Y-m-d'); ?>" />
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
                        <input type="checkbox" name="CheqResumen" id="CheqResumen" /> Resumen Periodos
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
                    <input type="radio" name="OpcActivos" id="OpcActivos" /> Activo
                </label>
                <label for="OpcInactivos">
                    <input type="radio" name="OpcActivos" id="OpcInactivos" /> Inactivo
                </label>
            </div>
        </div>
    </div>
    <div class="row alineacion"><!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#LxG" aria-controls="LxG" role="tab"
                    data-toggle="tab">LISTADO POR GRUPOS</a></li>
            <li role="presentation"><a href="#PmA" aria-controls="PmA" role="tab" data-toggle="tab">PENSION
                    MENSUAL DEL AÑO</a>
            </li>
            <li role="presentation"><a href="#AcD" aria-controls="AcD" role="tab" data-toggle="tab">ALUMNOS CON
                    DESCUENTO</a></li>
            <li role="presentation"><a href="#NdA" aria-controls="NdA" role="tab" data-toggle="tab">NOMINA DE
                    ALUMNOS</a></li>
            <li role="presentation"><a href="#EpE" aria-controls="EpE" role="tab" data-toggle="tab">ENVIOS POR
                    EMAIL
                </a></li>
            <li role="presentation"><a href="#RpPm" aria-controls="RpPm" role="tab" data-toggle="tab">RESUMEN
                    PENSIONES
                    POR MES
                </a></li>
            <li role="presentation"><a href="#Ed" aria-controls="Ed" role="tab" data-toggle="tab">ENVIAR DEUDA POR
                    API Y EMAIL
                </a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
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
                        <div class="row">
                            <div class="sol-sm-5">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="Label13" id="Label12">Remitente</label>
                                    </div>
                                    <div class="sol-sm-10">
                                        <input type="text" name="Label13" id="Label13">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="TxtAsunto" id="Label7">Asunto</label>
                                    </div>
                                    <div class="sol-sm-10">
                                        <input type="text" name="TxtAsunto" id="TxtAsunto">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="LblArchivo">Adjuntar</label>
                                    </div>
                                    <div class="sol-sm-10">
                                        <input type="file" name="LblArchivo" id="LblArchivo">
                                    </div>
                                </div>
                            </div>
                            <div class="sol-sm-5">

                            </div>
                            <div class="sol-sm-2">

                            </div>
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