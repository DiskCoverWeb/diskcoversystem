<html>
<!--
    AUTOR DE RUTINA : Dallyana Vanegas
    FECHA CREACION : 30/01/2024
    FECHA MODIFICACION : 20/02/2024
    DESCIPCION : Clase que se encarga de manejar el Historial de Facturas
-->

<head>
    <style>
        #TxtFile {
            resize: none;
            background-color: blue;
            color: white;
            border: 1px solid #ccc;
            font-family: 'Courier New', Courier, monospace;
            overflow-x: auto;
            white-space: nowrap;
        }

        #DGQueryCaption {
            text-align: center;
        }

        .btn-group .btn-default {
            height: 48px;
        }

        .btn-group .btn-default.dropdown-toggle {
            height: 48px;
        }

        .close {
            color: #fff;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="row" style="margin:5px;">
        <div class="col">
            <div class="col">
                <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
                    <img src="../../img/png/exit.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Imprimir" title="Imprimir los resultados" class="btn btn-default">
                    <img src="../../img/png/paper.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Facturas" title="Presenta el resumen de Facturas"
                    class="btn btn-default">
                    <img src="../../img/png/bill.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <div class="btn-group">
                    <a href="javascript:void(0)" id="Resumen" title="Resumen de ventas" class="btn btn-default">
                        <img src="../../img/png/bar-graph.png" width="35" height="35" alt="Icono">
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" id="menuResumen">
                    </ul>
                </div>
            </div>
            <div class="col">
                <div class="btn-group">
                    <a href="javascript:void(0)" id="Detalle_Abonos"
                        title="Detalle de abonos de Facturas/Notas de ventas" class="btn btn-default">
                        <img src="../../img/png/budget.png" width="35" height="35">
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" id="menuDetalleAbonos">
                    </ul>
                </div>

            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Protestado" title="Cheques protestados" class="btn btn-default">
                    <img src="../../img/png/data0.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <div class="btn-group">
                    <a href="javascript:void(0)" id="Facturas_Clientes" title="Listado de Facturas"
                        class="btn btn-default">
                        <img src="../../img/png/people.png" width="35" height="35">
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" id="menuListadoFacturas">
                    </ul>
                </div>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Por_Buses" title="Listado de buses" class="btn btn-default">
                    <img src="../../img/png/bus.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Listado_Tarjetas" title="Listado de clientes con tarjetas de credito"
                    class="btn btn-default">
                    <img src="../../img/png/visa.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Retenciones_NC" title="Presentar retenciones y notas de credito"
                    class="btn btn-default">
                    <img src="../../img/png/data1.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="CxC_Clientes" title="Listado de cartera por meses"
                    class="btn btn-default">
                    <img src="../../img/png/list.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Listado_Por_Meses" title="Listar clientes por rubro de meses"
                    class="btn btn-default">
                    <img src="../../img/png/requirement.png" width="35" height="35">
                </a>
            </div>
            <div class="col" style="margin-top:-30px">
                <a href="javascript:void(0)" id="Estado_Cuenta_Cliente" title="Estado de cuenta de clientes"
                    class="btn btn-default">
                    <img src="../../img/png/social-media.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <div class="btn-group">
                    <a href="javascript:void(0)" id="Ventas_x_Excel" title="Generar las ventas por excel"
                        class="btn btn-default">
                        <img src="../../img/png/account.png" width="35" height="35">
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" id="menuVentasxExcel">
                    </ul>
                </div>
            </div>
            <div class="col">
                <div class="btn-group">
                    <a href="javascript:void(0)" id="Enviar_FA_Emails" title="Enviar por mail facturas electronicas"
                        class="btn btn-default">
                        <img src="../../img/png/payment-check.png" width="35" height="35">
                    </a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" id="menuEnviarFAmails">
                    </ul>
                </div>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Buscar_Malla" title="Patron de busqueda" class="btn btn-default">
                    <img src="../../img/png/analytics.png" width="35" height="35">
                </a>
            </div>
        </div>
    </div>

    <div class="row" style="">
        <div class="col-sm-6">
            <div class="col-sm-12">
                <label>
                    <input type="checkbox" id="inlineCheckbox1" value="option1"> Incluir prefacturación
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Pendiente
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Cancelada
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Anulada
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio4" value="option4"> Todas
                </label>
            </div>
        </div>
        <div class="col-sm-6" style="padding-left:0px">
            <div class="col-sm-4">
                <label>
                    <input type="checkbox" id="CheqCxC" value="CheqAbonos"> Cuenta por Cobrar
                </label>
            </div>
            <div class="col-sm-4">
                <label>
                    <input type="checkbox" id="CheqAbonos" value="CheqAbonos"> Cuenta de Abono
                </label>
            </div>
            <div class="col-sm-4">
                <label>
                    <input type="checkbox" id="CheqIngreso" value="CheqIngreso"> Cuenta de Ingreso
                </label>
            </div>
            <div class="col-sm-12">
                <select class="form-control input-xs" name="DCCxC" id="DCCxC" style="display:none">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row" style="margin:0px;">
        <div class="col-sm-6">
            <div class="col">
                <label for="MBFechaI" class="control-label">Fecha Inicial</label>
                <input type="date" name="MBFechaI" id="MBFechaI" class="form-control input-xs validateDate" onchange=""
                    title="Fecha Inicial" value="<?php echo date('Y-m-d') ?>">
            </div>
            <div class="col">
                <label for="MBFechaF" class="control-label">Fecha Final</label>
                <input type="date" name="MBFechaF" id="MBFechaF" class="form-control input-xs validateDate" onchange=""
                    title="Fecha Final" value="<?php echo date('Y-m-d') ?>">
            </div>

            <div class="col">
                <label for="TxtOrden" style="display: block;">Documento desde</label>
                <input class="form-control input-xs" type="text" name="TxtOrden" id="TxtOrden" placeholder="0" value=0>
            </div>

            <div class="col">
                <label for="TxtOrden1" style="display: block;">Documento hasta</label>
                <input class="form-control input-xs" type="text" name="TxtOrden1" id="TxtOrden1" placeholder="0"
                    value=0>
            </div>
        </div>
        <div class="col-sm-6">
            <textarea class="form-control" id="TxtFile" rows="2"></textarea>
        </div>
    </div>
    <div class="row" style="margin-left:16px; margin-right:16px; margin-top:10px">
        <div class="panel panel-info" style="height:420px">            
            <div class="panel-body">
                <div class="col-sm-12" style="height:420px" id="DGQuery">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalBusqueda">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: white;">
                <div class="modal-header" style="background-color: blue; color: white;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                    <h4 class="modal-title">PATRÓN DE BÚSQUEDA</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="Label7">Patron de Busqueda:</label>
                                <select class="form-control input-xs" name="ListCliente" id="ListCliente">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for=""></label>
                                <select class="form-control input-xs" name="DCCliente" id="DCCliente">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: white;">
                    <button type="button" class="btn btn-success" id="modalBusquedaBtnAceptar">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script type="text/javascript">

    $(document).ready(function () {

        var menuResumen = [
            { id: 'id1', opcion: 'Resumen de productos' },
            { id: 'id2', opcion: 'Resumen de productos por meses' },
            { id: 'id3', opcion: 'Resumen de Ventas/Costos' },
            { id: 'id4', opcion: 'Resumen Comisiones por Vendedor' },
            { id: 'id5', opcion: 'Ventas por Cliente' },
            { id: 'id6', opcion: 'Ventas Clientes por Meses' },
            { id: 'VentasxProducto', opcion: 'Ventas Clientes por Productos' },
            { id: 'id7', opcion: 'Ventas Resumidas por Vendedor' }
        ];

        for (var i = 0; i < menuResumen.length; i++) {
            var menuItem = menuResumen[i];
            $('#menuResumen').append('<li><a href="#" id="' + menuItem.id + '" data-opcion="' + menuItem.opcion + '">' + menuItem.opcion + '</a></li>');
        }

        $('#menuResumen').on('click', 'li a', function () {
            var opcionSel = $(this).data('opcion');
            var idSel = $(this).attr('id');
            console.log('Selected Option:', opcionSel);
            console.log('Selected ID:', idSel);
            if (idSel == "VentasxProducto") {
                Swal.fire({
                    title: 'Formulario de confirmacion',
                    type: 'success',
                    html: 'Reporte con costeo',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#clave_supervisor').modal('show');
                    }
                });
            }
        });

        // $('#clave_supervisor').modal('show');

        var menuDetalleAbonos = ['Anticipados de Abonos',
            'Contrapartida del Abonos',
            'Errores en Abonos Anticipados',
            'Presenta Abonos mal Procesados'
        ];

        for (var i = 0; i < menuDetalleAbonos.length; i++) {
            $('#menuDetalleAbonos').append('<li><a href="#">' + menuDetalleAbonos[i] + '</a></li>');
        }

        var menuListadoFacturas = ['Ordenadas por Clientes',
            'Ordenados por Facturas',
            'CxC Clientes por Vendedor',
            'Resumen de Ventas por Vendedor',
            'Resumen de Cartera Detallado',
            'Cuentas por Cobrar por Tiempo de Credito',
            'Tipo de Pagos Clientes'
        ];

        for (var i = 0; i < menuListadoFacturas.length; i++) {
            $('#menuListadoFacturas').append('<li><a href="#">' + menuListadoFacturas[i] + '</a></li>');
        }

        var menuVentasxExcel = ['Bajar a Excel',
            'Reporte de Ventas',
            'Reporte de Catastro'
        ];

        for (var i = 0; i < menuVentasxExcel.length; i++) {
            $('#menuVentasxExcel').append('<li><a href="#">' + menuVentasxExcel[i] + '</a></li>');
        }

        for (var i = 0; i < menuListadoFacturas.length; i++) {
            $('#menuListadoFacturas').append('<li><a href="#">' + menuListadoFacturas[i] + '</a></li>');
        }

        var menuEnviarFAmails = ['Enviar por mail Facturas Electronicas',
            'Enviar por mail Recibos de Pago',
            'Enviar por Mail Recibos Anticipados',
            'Enviar Resumen de Cartera por mail'
        ];

        for (var i = 0; i < menuEnviarFAmails.length; i++) {
            $('#menuEnviarFAmails').append('<li><a href="#">' + menuEnviarFAmails[i] + '</a></li>');
        }


        $('#MBFechaI').blur(function () {
            let fechaI = $(this).val();
            fechaI = FechaValida(fechaI);
        });

        $('#MBFechaF').blur(function () {
            let fechaF = $(this).val();
            fechaF = FechaValida(fechaF);
        });

        $('#CheqAbonos').click(toggleDCCxC);
        $('#CheqCxC').click(toggleCheqCxC);
        $('#CheqIngreso').click(toggleCheqIngreso);

        Form_Activate();



    });

    //var globalFA;
    $('#modalBusquedaBtnAceptar').on('click', function () {
        var valorSeleccionado = $("#ListCliente").val();
        //console.log("Valor seleccionado:", valorSeleccionado);
    });

    function toggleDCCxC() {
        if ($('#CheqAbonos').is(":checked")) {
            CheqAbonos_Click();
            $('#CheqCxC, #CheqIngreso').prop('checked', false);
        }
        $("#DCCxC").toggle($('#CheqAbonos').is(":checked"));
    }

    function toggleCheqCxC() {
        if ($('#CheqCxC').is(":checked")) {
            CheqCxC_Click();
            $('#CheqAbonos, #CheqIngreso').prop('checked', false);
        }
        $("#DCCxC").toggle($('#CheqAbonos').is(":checked"));
    }

    function toggleCheqIngreso() {
        if ($('#CheqIngreso').is(":checked")) {
            $('#CheqAbonos, #CheqCxC').prop('checked', false);
        }
        $("#DCCxC").toggle($('#CheqAbonos').is(":checked"));
    }

    function FechaValida(fecha) {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?FechaValida=true',
            dataType: 'json',
            data: { 'fecha': fecha },
            success: function (datos) {
                if (datos.ErrorFecha) {
                    Swal.fire({
                        type: 'warning',
                        title: datos.MsgBox,
                        text: fecha
                    });
                }
            }
        });
    }

    function CheqAbonos_Click() {
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?CheqAbonos_Click=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('#DCCxC').empty();
                    $.each(data, function (index, value) {
                        $('#DCCxC').append('<option value="' + value['NomCxC'] + '">' + value['NomCxC'] + '</option>');
                    });
                }
            }
        });
    }

    function CheqCxC_Click() {
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?CheqCxC_Click=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('#DCCxC').empty();
                    $.each(data, function (index, value) {
                        $('#DCCxC').append('<option value="' + value['NomCxC'] + '">' + value['NomCxC'] + '</option>');
                    });
                }
            }
        });
    }

    $('#Facturas').on('click', function () {
        Historico_Facturas();
    });

    function Historico_Facturas() {

        var parametros = {
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'CheqCxC': $('#CheqCxC').prop('checked') ? 1 : 0,
            'FA': globalFA,
            'ListCliente': $("#ListCliente").val(),
            'DCCliente': $('#DCCliente').val(),
            'DCCxC': $('#DCCxC').val(),
            'OpcPend': $('#OpcPend').prop('checked') ? 1 : 0,
            'OpcAnul': $('#OpcAnul').prop('checked') ? 1 : 0,
            'OpcCanc': $('#OpcCanc').prop('checked') ? 1 : 0,
            'CheqIngreso': $('#CheqIngreso').prop('checked') ? 1 : 0,
            'CheqAbonos': $('#CheqAbonos').prop('checked') ? 1 : 0,
            'DescItem': globalDescItem,
            'Cod_Marca': globalCodMarca
        };

        $("#DGQueryCaption").text("HISTORIAL DE FACTURAS");

        //$DGQuery = "HISTORIAL DE FACTURAS";
        //$Label2 = " Facturado";
        //$Label4 = " Cobrado";
        //$Label3 = " Saldo";
        $('#myModal_espera').modal('show');
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?Historico_Facturas=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                $('#myModal_espera').modal('hide');
                $('#DGQuery').html(data);
                $('#DGQuery #datos_t tbody').css('height', '36vh');

            }
        });
        $('#MBFechaI').focus();
    }

    $('#Imprimir').click(function () {
        $('#modalBusqueda').modal('show');
    });

    var globalFA;
    var globalCodigoInv;
    var globalCodMarca;
    var globalDescItem;
    function Form_Activate() {
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?Form_Activate=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {

                //console.log(data);
                globalFA = data["FA"];
                globalCodigoInv = data["CodigoInv"];
                globalCodMarca = data["Cod_Marca"];
                globalDescItem = data["DescItem"];

                $('#ListCliente').empty();
                $.each(data['ListCliente'], function (index, value) {
                    $('#ListCliente').append('<option value="' + value + '">' + value + '</option>');
                });

                $('#ListCliente').attr('size', data['ListCliente'].length);
                $("#ListCliente option:first").prop("selected", true);

                var valorSeleccionado = $("#ListCliente").val();
                console.log("Valor seleccionado:", valorSeleccionado);
            }
        });
    }

    $('#ListCliente').change(function () {
        var ListClienteText = $(this).val(); // Obtener el valor seleccionado del primer select
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?ListCliente_LostFocus=true',
            type: 'post',
            dataType: 'json',
            data: { ListClienteText: ListClienteText },
            success: function (data) {
                //console.log(data);
                $('#DCCliente').empty();

                $.each(data['data'], function (index, obj) {
                    var valor = obj[data["nombreCampo"]];
                    $('#DCCliente').append('<option value="' + valor + '">' + valor + '</option>');
                });

                var dataSize = data['data'].length;
                var selectSize = dataSize > 17 ? 17 : dataSize;
                $('#DCCliente').attr('size', selectSize);
                $('#DCCliente option:first').prop("selected", true);

                var valorSeleccionado = $('#DCCliente').val();
                console.log("Valor seleccionado:", valorSeleccionado);
            }
        });
    });




</script>