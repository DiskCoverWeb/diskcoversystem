<html>
<!--
    AUTOR DE RUTINA : Dallyana Vanegas
    FECHA CREACION : 30/01/2024
    FECHA MODIFICACION : 18/03/2024
    DESCIPCION : Clase que se encarga de manejar el Historial de Facturas
-->

<head>
    <style>
        #LblPatronBusqueda {
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
                <a href="javascript:void(0)" id="Imprimir" title="Imprimir los resultados" data-valor="0"
                    class="btn btn-default">
                    <img src="../../img/png/paper.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Facturas" title="Presenta el resumen de Facturas" data-valor="1"
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
                <a href="javascript:void(0)" id="Protestado" title="Cheques protestados" data-valor="0"
                    class="btn btn-default">
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
                <a href="javascript:void(0)" id="Por_Buses" title="Listado de buses" data-valor="12"
                    class="btn btn-default">
                    <img src="../../img/png/bus.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Listado_Tarjetas" title="Listado de clientes con tarjetas de credito"
                    data-valor="0" class="btn btn-default">
                    <img src="../../img/png/visa.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Retenciones_NC" title="Presentar retenciones y notas de credito"
                    data-valor="6" class="btn btn-default">
                    <img src="../../img/png/data1.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="CxC_Clientes" title="Listado de cartera por meses" data-valor="0"
                    class="btn btn-default">
                    <img src="../../img/png/list.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Listar_Por_Meses" title="Listar clientes por rubro de meses"
                    data-valor="0" class="btn btn-default">
                    <img src="../../img/png/requirement.png" width="35" height="35">
                </a>
            </div>
            <div class="col" style="margin-top:-30px">
                <a href="javascript:void(0)" id="Estado_Cuenta_Cliente" title="Estado de cuenta de clientes"
                    data-valor="0" class="btn btn-default">
                    <img src="../../img/png/social-media.png" width="35" height="35">
                </a>
            </div>
            <div class="col">
                <div class="btn-group">
                    <a href="javascript:void(0)" id="Ventas_x_Excel" title="Generar las ventas por excel"
                        class="btn btn-default">
                        <img src="../../img/png/sobresalir.png" width="35" height="35">
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
                    <a href="javascript:void(0)" id="Enviar_Emails" title="Enviar por mail facturas electronicas"
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
                <a href="javascript:void(0)" id="Buscar_Malla" title="Patron de busqueda" data-valor="0"
                    class="btn btn-default">
                    <img src="../../img/png/analytics.png" width="35" height="35">
                </a>
            </div>
        </div>
    </div>

    <div class="row" style="">
        <div class="col-sm-6">
            <div class="col-sm-12">
                <label>
                    <input type="checkbox" id="CheqPreFa" value="CheqPreFa"> Incluir prefacturación
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="Opc" id="OpcPend" value="OpcPen" checked> Pendiente
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="Opc" id="OpcCanc" value="OpcCanc"> Cancelada
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="Opc" id="OpcAnul" value="OpcAnul"> Anulada
                </label>
            </div>
            <div class="col-sm-3">
                <label>
                    <input type="radio" name="Opc" id="OpcTodas" value="OpcTodas"> Todas
                </label>
            </div>
        </div>
        <div class="col-sm-6" style="padding-left:0px">
            <div class="col-sm-4">
                <label>
                    <input type="checkbox" id="CheqCxC" value="CheqCxC"> Cuenta por Cobrar
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
                <label for="TxtDocDesde" style="display: block;">Documento desde</label>
                <input class="form-control input-xs" type="text" name="TxtDocDesde" id="TxtDocDesde" placeholder="0"
                    value=0>
            </div>

            <div class="col">
                <label for="TxtDocHasta" style="display: block;">Documento hasta</label>
                <input class="form-control input-xs" type="text" name="TxtDocHasta" id="TxtDocHasta" placeholder="0"
                    value=0>
            </div>
        </div>
        <div class="col-sm-6">
            <textarea class="form-control" id="LblPatronBusqueda" rows="2"></textarea>
        </div>
    </div>
    <div class="row" style="margin-left:16px; margin-right:16px; margin-top:10px">
        <div class="panel panel-info" style="height:420px">
            <div class="panel-body">
                <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                    No se encontraron datos que mostrar.
                </div>
                <div class="col-sm-12" style="height:420px" id="DGQuery">
                </div>
                <div class="row" id="">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="lblCommand">S</label>
                            <input type="text" class="form-control-sm" id="lblCommand" placeholder="0" size=1 readonly>
                        </div>
                        <div class="form-group">
                            <label for="lblRegistro">Registros</label>
                            <input type="text" class="form-control-sm" id="lblRegistro" placeholder="000" size=3
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="lblFacturado" id="label2"></label>
                            <input type="text" class="form-control-sm" id="lblFacturado" placeholder="000" size=3
                                readonly style="color:red">
                        </div>
                        <div class="form-group">
                            <label for="lblAbonado" id="label4"></label>
                            <input type="text" class="form-control-sm" id="lblAbonado" placeholder="000" size=3 readonly
                                style="color:red">
                        </div>
                        <div class="form-group">
                            <label for="lblSaldo" id="label3"></label>
                            <input type="text" class="form-control-sm" id="lblSaldo" placeholder="000" size=3 readonly
                                style="color:red">
                        </div>
                    </form>
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
            </div>
        </div>
    </div>
</body>

</html>

<script type="text/javascript">

    $(document).ready(function () {
        var menuResumen = [
            { id: 'Resumen_Prod', opcion: 'Resumen de productos', valor: 3 },
            { id: 'Resumen_Prod_Meses', opcion: 'Resumen de productos por meses', valor: 16 },
            { id: 'ResumenVentCost', opcion: 'Resumen de Ventas/Costos', valor: 5 },
            { id: 'Resumen_Ventas_Vendedor', opcion: 'Resumen Comisiones por Vendedor', valor: 0 },
            { id: 'Ventas_x_Cli', opcion: 'Ventas por Cliente', valor: 4 },
            { id: 'Ventas_Cli_x_Mes', opcion: 'Ventas Clientes por Meses', valor: 14 },
            { id: 'VentasxProductos', opcion: 'Ventas Clientes por Productos', valor: 8 },
            { id: 'Ventas_ResumidasxVendedor', opcion: 'Ventas Resumidas por Vendedor', valor: 0 }
        ];

        var menuDetalleAbonos = [
            { id: 'SMAbonos_Anticipados', opcion: 'Anticipados de Abonos', valor: 20 },
            { id: 'Contra_Cta', opcion: 'Contrapartida del Abonos', valor: 0 },
            { id: 'Abonos_Ant', opcion: 'Errores en Abonos Anticipados', valor: 0 },
            { id: 'Abonos_Erroneos', opcion: 'Presenta Abonos mal Procesados', valor: 0 }
        ];

        var menuListadoFacturas = [
            { id: 'Por_Clientes', opcion: 'Ordenadas por Clientes', valor: 0 },
            { id: 'Por_Facturas', opcion: 'Ordenados por Facturas', valor: 0 },
            { id: 'Por_Vendedor', opcion: 'CxC Clientes por Vendedor', valor: 0 },
            { id: 'Resumen_Vent_x_Ejec', opcion: 'Resumen de Ventas por Vendedor', valor: 0 },
            { id: 'Resumen_Cartera', opcion: 'Resumen de Cartera Detallado', valor: 0 },
            { id: 'CxC_Tiempo_Credito', opcion: 'Cuentas por Cobrar por Tiempo de Credito', valor: 0 },
            { id: 'Tipo_Pago_Cliente', opcion: 'Tipo de Pagos Clientes', valor: 0 },
        ];

        var menuVentasxExcel = [
            { id: 'Bajar_Excel', opcion: 'Bajar a Excel', valor: 0 },
            { id: 'Reporte_Ventas', opcion: 'Reporte de Ventas', valor: 0 },
            { id: 'Reporte_Catastro', opcion: 'Reporte de Catastro', valor: 0 },
        ];

        var menuEnviarFAmails = [
            { id: 'Enviar_FA_Email', opcion: 'Enviar por mail Facturas Electronicas', valor: 0 },
            { id: 'Enviar_RE_Email', opcion: 'Enviar por mail Recibos de Pago', valor: 0 },
            { id: 'Recibos_Anticipados', opcion: 'Enviar por Mail Recibos Anticipados', valor: 0 },
            { id: 'Deuda_x_Mail', opcion: 'Enviar Resumen de Cartera por mail', valor: 0 },
        ];

        function renderMenu(menu, selector) {
            menu.forEach(menuItem => {
                $(selector).append(`<li><a href="#" id="${menuItem.id}" data-valor="${menuItem.valor}">${menuItem.opcion}</a></li>`);
            });
        }

        renderMenu(menuResumen, '#menuResumen');
        renderMenu(menuDetalleAbonos, '#menuDetalleAbonos');
        renderMenu(menuListadoFacturas, '#menuListadoFacturas');
        renderMenu(menuVentasxExcel, '#menuVentasxExcel');
        renderMenu(menuEnviarFAmails, '#menuEnviarFAmails');

        function handleMenuClick(idSel) {
            switch (idSel) {
                case "Resumen_Prod":
                case "Resumen_Ventas_Vendedor":
                case "Ventas_x_Cli":
                case "Ventas_Cli_x_Mes":
                case "Ventas_ResumidasxVendedor":
                case "SMAbonos_Anticipados":
                case "Contra_Cta":
                case "Abonos_Ant":
                case "Abonos_Erroneos":
                case "Por_Clientes":
                case "Por_Facturas":
                case "Por_Vendedor":
                case "Resumen_Cartera":
                case "CxC_Tiempo_Credito":
                case "Tipo_Pago_Cliente":
                case "Bajar_Excel":
                case "Reporte_Ventas":
                case "Reporte_Catastro":
                case "Enviar_FA_Email":
                case "Enviar_RE_Email":
                case "Deuda_x_Mail":
                    ToolbarMenu_ButtonMenuClick(idSel);
                    break;
                case "Resumen_Prod_Meses":
                    Swal.fire({
                        title: 'Formulario de confirmacion',
                        text: "(SI) Reporte por Cantidad\n(NO) Por Valor Económico",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'SI',
                        cancelButtonText: 'NO'
                    }).then((result) => {
                        globalPorCantidad = result.value;
                        ToolbarMenu_ButtonMenuClick(idSel);
                    });
                    break;
                case "ResumenVentCost":
                case "VentasxProductos":
                    Swal.fire({
                        title: 'Formulario de confirmacion',
                        text: "Reporte con costeo",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'SI',
                        cancelButtonText: 'NO'
                    }).then((result) => {
                        if (result.value) {
                            globalConCosteo = true;
                            globalIdCase = idSel;
                            $('#clave_supervisor').modal('show');
                        } else {
                            globalConCosteo = false;
                            ToolbarMenu_ButtonMenuClick(idSel);
                        }
                    });
                    break;
                case "Recibos_Anticipados":
                    if (Opcion == 20) {
                        $('#DGQuery').empty();
                        Swal.fire({
                            title: 'FORMULARIO DE ENVIO POR MAIL',
                            text: "Enviar recibo de abono anticipado por mail?",
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'SI',
                            cancelButtonText: 'NO'
                        }).then((result) => {
                            if (result.value) {
                                SiEnviar = true;
                            }
                            ToolbarMenu_ButtonMenuClick(idSel);
                        });
                    }
                    break;
                default:
                    break;
            }
        }

        $('#menuResumen, #menuDetalleAbonos, #menuListadoFacturas, #menuVentasxExcel, #menuEnviarFAmails').on('click', 'li a', function () {
            var idSel = $(this).attr('id');
            var valor = $(this).data('valor');

            if (valor === 0) {
                Opcion = Opcion !== undefined ? Opcion : 0;
            } else {
                Opcion = valor;
            }
            handleMenuClick(idSel);
        });

        $('#MBFechaI').blur(function () {
            var fechaI = $(this).val();
            fechaI = FechaValida(fechaI);
        });

        $('#MBFechaF').blur(function () {
            var fechaF = $(this).val();
            fechaF = FechaValida(fechaF);
        });

        $('#CheqAbonos').click(toggleDCCxC);
        $('#CheqCxC').click(toggleCheqCxC);
        $('#CheqIngreso').click(toggleCheqIngreso);

        Form_Activate();
    });

    var Fecha = "";
    var TP = "";
    var Numero = "";

    $('#DGQuery').on('click', 'tr', function () {

        if (Opcion == 20) {

            $('#DGQuery tr').css('background-color', '');
            $(this).css('background-color', '#f1c232');
            Fecha = "";
            TP = "";
            Numero = "";
            $(this).find('td').each(function (index) {
                var cellValue = $(this).text();
                switch (index) {
                    case 3:
                        Fecha = cellValue;
                        break;
                    case 4:
                        TP = cellValue;
                        break;
                    case 5:
                        Numero = cellValue;
                        break;
                    default:
                        break;
                }
            });
            globalCo['Fecha'] = Fecha;
            globalCo['TP'] = TP;
            globalCo['Numero'] = Numero;
        }
    });


    $('#modalBusquedaBtnAceptar').on('click', function () {
        var valorSeleccionado = $("#ListCliente").val();
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

    function resp_clave_ingreso(response) {
        if (response.respuesta == 1) {
            $('#clave_supervisor').modal('hide');
            globalSiNo = true;
            ToolbarMenu_ButtonMenuClick(globalIdCase);
        }
        else {
        }
    }

    var Opcion = 0;
    $('#Facturas, #Listado_Tarjetas, #Estado_Cuenta_Cliente, #Buscar_Malla, #Protestado, #Retenciones_NC, #Por_Buses').on('click', function () {
        var idBtn = $(this).attr('id');
        var valor = $(this).data('valor');

        if (valor === 0) {
            Opcion = Opcion !== undefined ? Opcion : 0;
        } else {
            Opcion = valor;
        }
        ToolbarMenu_ButtonClick(idBtn);
    });

    var globalPorFecha;
    $('#CxC_Clientes, #Listar_Por_Meses').on('click', function () {
        var idBtn = $(this).attr('id');
        Swal.fire({
            title: 'PREGUNTA DE CONFIRMACION',
            text: "Listar Reporte por Fecha?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.value) {
                globalPorFecha = true;
            } else {
                $("#MBFechaI").val("01/01/2000");
            }
            ToolbarMenu_ButtonClick(idBtn);
        });
    });

    function ToolbarMenu_ButtonClick(idBtn) {
        var params = {
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'CheqCxC': $('#CheqCxC').prop('checked') ? 1 : 0,
            'CheqIngreso': $('#CheqIngreso').prop('checked') ? 1 : 0,
            'CheqPreFa': $('#CheqPreFa').prop('checked') ? 1 : 0,
            'CheqAbonos': $('#CheqAbonos').prop('checked') ? 1 : 0,
            'OpcPend': $('#OpcPend').prop('checked') ? 1 : 0,
            'OpcAnul': $('#OpcAnul').prop('checked') ? 1 : 0,
            'OpcCanc': $('#OpcCanc').prop('checked') ? 1 : 0,
            'DCCxC': $('#DCCxC').val(),
            'ListCliente': $("#ListCliente").val(),
            'DCCliente': $('#DCCliente').val(),
            'FA': globalFA,
            'DescItem': globalDescItem,
            'Cod_Marca': globalCodMarca,
            'idBtn': idBtn,
            'Por_Fecha': globalPorFecha !== undefined ? globalPorFecha : false,
            'Opcion': Opcion
        };

        console.log('enviado: ' + params['Opcion']);
        $('#myModal_espera').modal('show');
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?ToolBarMenu_ButtonClick=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': params },
            success: function (data) {
                console.log(data);
                if (data['Opcion']) {
                    Opcion = data.Opcion;
                }
                if (data['AdoQuery']) {
                    globalAdoQuery = data.AdoQuery;
                }

                switch (data.idBtn) {
                    case "Facturas":
                    case "CxC_Clientes":
                    case "Listar_Por_Meses":
                    case "Protestado":
                    case "Listado_Tarjetas":
                    case "Por_Buses":
                    case "Estado_Cuenta_Cliente":
                    case "Retenciones_NC":
                        if (data['num_filas'] > 0) {
                            $("#label2").text("Facturado");
                            $("#label4").text("Cobrado");
                            $("#label3").text("Saldo");

                            $("#lblCommand").val(data['Opcion']);
                            $("#lblRegistro").val(data['num_filas']);
                            $("#lblFacturado").val(data['label_facturado']);
                            $("#lblAbonado").val(data['label_abonado']);
                            $("#lblSaldo").val(data['label_saldo']);

                            $('#MBFechaI').focus();
                            mostrarTabla(data.tbl);
                        } else {
                            mostrarAvisoNoData();
                        }
                        break;

                    case "Buscar_Malla":
                        $('#myModal_espera').modal('hide');
                        if (data['DCCliente'].length > 0) {
                            $('#DCCliente').empty();
                            $.each(data['DCCliente'], function (index, value) {
                                $('#DCCliente').append(`<option value="${value['Codigo' + '-' + 'Cliente']}">${value['Codigo' + '-' + 'Cliente']}</option>`);
                            });
                            var dataSize = data['DCCliente'].length;
                            var selectSize = dataSize > 17 ? 17 : dataSize;
                            $('#DCCliente').attr('size', selectSize);
                            $('#DCCliente option:first').prop("selected", true);
                        } else {
                            $('#DCCliente')[0].options[0].textContent = "No se encontraron valores";
                        }
                        $('#modalBusqueda').modal('show');
                        break;
                    default:
                        break;
                }
            }
        });
    }

    var globalFA;
    var globalCodMarca;
    var globalDescItem;
    var globalCodigoInv = false;
    var globalPorCantidad = false;
    var globalSiNo = false;
    var globalConCosteo = false;
    var globalIdCase;
    var SiEnviar = false;

    function Form_Activate() {
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?Form_Activate=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
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
            }
        });
    }

    var DCCliente = [];
    $('#ListCliente').change(function () {
        var ListClienteText = $(this).val();
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?ListCliente_LostFocus=true',
            type: 'post',
            dataType: 'json',
            data: { ListClienteText: ListClienteText },
            success: function (data) {
                $('#DCCliente').empty();

                $.each(data['data'], function (index, obj) {
                    var valor = obj[data["nombreCampo"]];
                    $('#DCCliente').append(`<option value="${valor}">${valor}</option>`);
                    DCCliente.push(valor);
                });

                var dataSize = data['data'].length;
                var selectSize = dataSize > 17 ? 17 : dataSize;
                $('#DCCliente').attr('size', selectSize);
                $('#DCCliente option:first').prop("selected", true);

                var valorSeleccionado = $('#DCCliente').val();
            }
        });
    });

    $('#DCCliente').blur(function () {
        var parametros = {
            'ListClienteVal': $("#ListCliente").val(),
            'DCClienteVal': $('#DCCliente').val(),
            'FA': globalFA,
            'DCCliente': DCCliente
        };

        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?DCCliente_LostFocus=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                globalFA = data["FA"];
                globalCodigoInv = data["CodigoInv"];
                globalCodMarca = data["Cod_Marca"];
                globalDescItem = data["DescItem"];
            }
        });
    });

    $('#DCCliente').on('dblclick', function () {

        if ($("#ListCliente").val() == 'Factura') {
            $('#LblPatronBusqueda').val("P A T R O N   D E   B U S Q U E D A:\n" + $("#ListCliente").val() + " = " + globalFA['TC'] + ": " + globalFA['Serie'] + "-" + globalFA['Factura']);
        } else {
            $('#LblPatronBusqueda').val("P A T R O N   D E   B U S Q U E D A:\n" + $("#ListCliente").val() + " = " + $('#DCCliente').val());
        }
        $('#modalBusqueda').modal('hide');

    });

    var globalCo = {};
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var globalAdoQuery = null;
    var idTabla;
    function ToolbarMenu_ButtonMenuClick(idBtnMenu) {
        var params = {
            'MBFechaI': $('#MBFechaI').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'CheqCxC': $('#CheqCxC').prop('checked') ? 1 : 0,
            'CheqIngreso': $('#CheqIngreso').prop('checked') ? 1 : 0,
            'CheqAbonos': $('#CheqAbonos').prop('checked') ? 1 : 0,
            'OpcPend': $('#OpcPend').prop('checked') ? 1 : 0,
            'OpcAnul': $('#OpcAnul').prop('checked') ? 1 : 0,
            'OpcCanc': $('#OpcCanc').prop('checked') ? 1 : 0,
            'DCCxC': $('#DCCxC').val(),
            'ListCliente': $("#ListCliente").val(),
            'DCCliente': $('#DCCliente').val(),
            'FA': globalFA,
            'DescItem': globalDescItem,
            'Cod_Marca': globalCodMarca,
            'idBtnMenu': idBtnMenu,
            'TipoFactura': TipoFactura,
            'PorCantidad': globalPorCantidad !== undefined ? globalPorCantidad : false,
            'Con_Costeo': globalConCosteo !== undefined ? globalConCosteo : false,
            'Si_No': globalSiNo !== undefined ? globalSiNo : false,
            'CodigoInv': globalCodigoInv !== undefined ? globalCodigoInv : false,
            'AdoQuery': globalAdoQuery,
            'TxtDocDesde': parseInt($("#TxtDocDesde").val()),
            'TxtDocHasta': parseInt($("#TxtDocHasta").val()),
            'Opcion': Opcion,
            'Co': globalCo,
            'SiEnviar': SiEnviar !== undefined ? SiEnviar : false,
        };

        $('#myModal_espera').modal('show');
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?ToolbarMenu_ButtonMenuClick=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': params },
            success: function (data) {

                globalAdoQuery = data.AdoQuery;
                Opcion = data.Opcion;

                if (data.result == 1) {
                    $('#myModal_espera').modal('hide');
                    swal.fire('Proceso Completado con Exito', 'Revise Email para más información', 'success');
                }

                if (data.response !== 3) {
                    $('#myModal_espera').modal('hide');
                }

                switch (data.response) {
                    case 0:
                        swal.fire('Información', 'No se encontraron datos para generar el archivo', 'info');
                        break;
                    case 1:
                        swal.fire('SE HA GENERADO EL SIGUIENTE ARCHIVO:', data.nombre, 'success');
                        descargarArchivo('../../TEMP/HISTORICO/', data.nombre);
                        break;
                    case 2:
                        swal.fire('SE HA GENERADO EL SIGUIENTE ARCHIVO:', data.nombre, 'success');
                        descargarArchivo('../../TEMP/EXCEL/', data.nombre);
                        break;
                    case 3:
                        descargarArchivo('../../TEMP/', data.nombre, true, data.Co);
                        break;
                    case 4:
                        Enviar_Emails_Facturas_Recibos(data.AdoQuery, data.tipoEnvio);
                        break;
                    default:
                        console.log('Respuesta no reconocida');
                }

                var actionsMap = {
                    "Resumen_Prod": { label2: "I.V.A", label4: "VENTAS", label3: "TOTAL" },
                    "Resumen_Prod_Meses": { label2: "VENTAS", label4: "COBRADO", label3: "SALDO" },
                    "ResumenVentCost": { label2: "VENTAS" },
                    //"Resumen_Ventas_Vendedor": {},
                    "Ventas_x_Cli": { label2: "VENTAS", label4: "COBRADO", label3: "SALDO" },
                    "Ventas_Cli_x_Mes": { label2: "VENTAS", label4: "COBRADO", label3: "SALDO" },
                    "VentasxProductos": { label2: "FACTURADO", label4: "PVP", label3: "COSTO" },
                    "Ventas_ResumidasxVendedor": { label2: "FACTURADO" },
                    "SMAbonos_Anticipados": { label2: "VENTAS", label4: "COBRADO", label3: "SALDO" },
                    "Contra_Cta": { label2: "DEBITOS", label4: "CREDITOS", label3: "SALDO" },
                    "Abonos_Ant": { label2: "VENTAS", label4: "COBRADO", label3: "SALDO" },
                    "Abonos_Erroneos": { label2: "FACTURADO", label4: "COBRADO", label3: "SALDO" },
                    /*"Por_Clientes": {},
                    "Por_Facturas": {},
                    "Resumen_Cartera": {},
                    "Por_Vendedor": {},
                    "CxC_Tiempo_Credito": {},
                    "Bajar_Excel": {},
                    "Reporte_Ventas": {},
                    "Reporte_Catastro": {}*/
                };

                var action = actionsMap[data.idBtnMenu] || {};

                Object.keys(action).forEach((label) => {
                    $(`#${label}`).text(action[label]);
                });

                if (data.num_filas > 0) {
                    $('#lblCommand').val(data.Opcion);
                    $('#lblRegistro').val(data.num_filas);
                    $('#lblFacturado').val(data.label_facturado);
                    $('#lblAbonado').val(data.label_abonado);
                    $('#lblSaldo').val(data.label_saldo);

                    mostrarTabla(data.tbl)

                    if (Opcion == 20) {
                        var firstRow = $('#DGQuery tr:eq(1)');
                        firstRow.css('background-color', '#f1c232');
                        firstRow.trigger('click');
                    }
                } else {
                    mostrarAvisoNoData();
                }
            }
        });
    }

    function mostrarTabla(data) {
        $('#DGQuery').html(data);
        $('#DGQuery #datos_t tbody').css('height', '36vh');
        $('#myModal_espera').modal('hide');
        $('#alertNoData').hide();
    }

    function mostrarAvisoNoData() {
        $('#myModal_espera').modal('hide');
        $('#DGQuery').empty();
        $('#alertNoData').show();
        $('#lblCommand').val('');
        $('#lblRegistro').val('');
        $('#lblFacturado').val('');
        $('#lblAbonado').val('');
        $('#lblSaldo').val('');
    }

    function descargarArchivo(url, nombre, enviarxmail = false, Co = false) {
        var ruta = url + nombre;
        var enlaceTemporal = $('<a></a>')
            .attr('href', ruta)
            .attr('download', nombre)
            .appendTo('body');
        enlaceTemporal[0].click();
        enlaceTemporal.remove();
        if (enviarxmail) {
            EnviarMailAbono(nombre, Co);
        }
    }

    function EnviarMailAbono(archivo, Co) {
        var params = {
            'Co': Co,
            'SiEnviar': SiEnviar !== undefined ? SiEnviar : false,
            'archivo': archivo
        }
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?EnviarMailAbono=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': params },
            success: function (data) {

                if (data == 1) {
                    swal.fire('Archivo enviado con exito!', 'mail:' + Co['Email'], 'success');
                } else {
                    swal.fire('El archivo no se envio', 'mail:' + Co['Email'], 'error');
                }
            }
        });
    }

    function SRI_Enviar_Mails(FA, SRI_Autorizacion, Tipo_Documento) {
        var params = {
            'FA': FA,
            'SRI_Autorizacion': SRI_Autorizacion,
            'Tipo_Documento': Tipo_Documento,
        };
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?SRI_Enviar_Mails=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': params },
            success: function (data) {
                var url = '../../TEMP/';
                if (data.res_pdf === 1 && data.res_xml !== -1) {
                    var ruta_pdf = data.pdf + ".pdf";
                    var ruta_xml = data.clave + ".xml";
                    EnviarMails(ruta_pdf, ruta_xml, FA, SRI_Autorizacion, Tipo_Documento);
                } else if (data.res_pdf === 1 && data.res_xml === -1) {
                    var ruta_pdf = url + data.pdf;
                    EnviarMails(ruta_pdf, '', FA, SRI_Autorizacion, Tipo_Documento);
                } else {
                    swal.fire('Informacion', 'Archivos no encontrados', 'error');
                }
            }
        });
    }

    function Recibo_Enviar_Mails(FA) {
        var params = {
            'FA': FA,
        };
        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?Recibo_Enviar_Mails=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': params },
            success: function (data) {
                console.log(data);
                /*var url = '../../TEMP/';
                if (data.res_pdf === 1 && data.res_xml !== -1) {
                    var ruta_pdf = data.pdf +".pdf";
                    var ruta_xml = data.clave +".xml";
                    EnviarMails(ruta_pdf, ruta_xml, FA, SRI_Autorizacion, Tipo_Documento);
                } else if (data.res_pdf === 1 && data.res_xml === -1) {
                    var ruta_pdf = url + data.pdf;
                    EnviarMails(ruta_pdf, '', FA, SRI_Autorizacion, Tipo_Documento);
                } else {
                    swal.fire('Informacion', 'Archivos no encontrados', 'error');
                }*/
            }
        });
    }

    function EnviarMails(ruta_pdf, ruta_xml, FA, SRI_Autorizacion, Tipo_Documento) {
        var params = {
            'archivo_pdf': ruta_pdf,
            'archivo_xml': ruta_xml,
            'FA': FA,
            'SRI_Autorizacion': SRI_Autorizacion,
            'Tipo_Documento': Tipo_Documento,
        };

        $.ajax({
            url: '../controlador/facturacion/HistorialFacturasC.php?EnviarMails=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': params },
            success: function (data) {
                if (data == 1) {
                    swal.fire('Informacion', 'Proceso Completado, Correos Enviado:' + FA['EmailC'], 'success');
                }
            }
        });
    }

    function Enviar_Emails_Facturas_Recibos(datos, Tipo_Documento) {
        if (datos.length > 0) {
            Swal.fire({
                title: 'Pregunta de Envío de Mails',
                text: '¿Está seguro de querer enviar por correo electrónico los documentos?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    datos.forEach((registro) => {
                        globalFA = {
                            'CodigoC': registro['CodigoC'],
                            'ClaveAcceso': registro['Clave_Acceso'],
                            'EstadoSRI': registro['Estado_SRI'],
                            'TC': registro['TC'],
                            'CheqAbonos_Clickfecha': registro['Fecha'],
                            'Fecha_V': registro['Fecha_V'],
                            'Serie': registro['Serie'],
                            'CI_RUC': registro['CI_RUC'],
                            'Factura': registro['Factura'],
                            'Autorizacion': registro['Autorizacion'],
                            'Hora_FA': registro['Hora_Aut'],
                            'Fecha_Aut': registro['Fecha_Aut'],
                            'EmailC': registro['Email'],
                            'EmailR': registro['Email2'],
                            'Cliente': registro['Cliente'],
                            'Comercial': registro['Comercial']
                        };

                        var SRI_Autorizacion = {
                            'Hora_Autorizacion': registro['Hora_Aut'],
                            'Fecha_Autorizacion': registro['Fecha_Aut'],
                            'Autorizacion': registro['Autorizacion'],
                        };

                        if (Tipo_Documento === "FA") {
                            SRI_Enviar_Mails(globalFA, SRI_Autorizacion, Tipo_Documento);
                        } else {
                            Recibo_Enviar_Mails(globalFA);
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'No hay registros para enviar',
                text: (Tipo_Documento === "FA") ? 'No hay Facturas Pendientes para enviar' : 'No hay Recibos Pendientes para enviar',
                type: 'info'
            });
        }
    }

    $('#Imprimir').on('click', function () {

        var idBtn = $(this).attr('id');
        var valor = $(this).data('valor');

        if (valor === 0) {
            Opcion = Opcion !== undefined ? Opcion : 0;
        } else {
            Opcion = valor;
        }
        Impresiones(Opcion);
    });

    function Impresiones(Opcion) {
        var MBFechaI = $('#MBFechaI').val();
        var MBFechaF = $('#MBFechaF').val();

        $('#DGQuery').empty();

        var parametros = {
            'AdoQuery': globalAdoQuery
        }

        var MensajeEncabData = "", SQLMsg1 = "", Mifecha = "";
        var case_no_def = false;

        var CheqCxC = $('#CheqCxC').prop('checked') ? 1 : 0;
        var CheqIngreso = $('#CheqIngreso').prop('checked') ? 1 : 0;
        var CheqAbonos = $('#CheqAbonos').prop('checked') ? 1 : 0;
        var OpcPend = $('#OpcPend').prop('checked') ? 1 : 0;
        var OpcAnul = $('#OpcAnul').prop('checked') ? 1 : 0;
        var OpcCanc = $('#OpcCanc').prop('checked') ? 1 : 0;

        switch (Opcion) {
            case '1':
                MensajeEncabData = "ESTADO DE CUENTA DE CLIENTES";
                SQLMsg1 = "Corte al " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '2':
                MensajeEncabData = "ESTADO DE CUENTA DE CLIENTES";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '3':
                MensajeEncabData = "ESTADO DE PRODUCTOS POR CLIENTES";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '4':
                MensajeEncabData = "RESUMEN DE VENTAS POR CLIENTES";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '5':
                MensajeEncabData = "RESUMEN DE VENTAS POR PRODUCTOS";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '6':
                MensajeEncabData = "ESTADO DE ABONOS DE CLIENTES";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '7':
                MensajeEncabData = "ESTADO DE CHEQUES PROTESTADOS";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '8':
                MensajeEncabData = "VENTAS POR PRODUCTOS";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '9':
            case '10':
            case '13':
                var Codigo4 = "Ninguno";
                if (CheqCxC === 1) Codigo4 = DCCxC;
                SQLMsg1 = "";
                if (OpcPend) SQLMsg1 = "LISTADO DE FACTURAS PENDIENTES";
                if (OpcAnul) SQLMsg1 = "LISTADO DE FACTURAS ANULADAS";
                if (OpcCanc) SQLMsg1 = "LISTADO DE FACTURAS CANCELADAS";
                if (OpcTodas) SQLMsg1 = "LISTADO DE TODAS LAS FACTURAS";

                Mifecha = MBFechaF;
                break;
            case '11':
                if (OpcPend) SQLMsg1 = "LISTADO DE FACTURAS PENDIENTES";
                if (OpcAnul) SQLMsg1 = "LISTADO DE FACTURAS ANULADAS";
                if (OpcCanc) SQLMsg1 = "LISTADO DE FACTURAS CANCELADAS";
                if (OpcTodas) SQLMsg1 = "LISTADO DE TODAS LAS FACTURAS";
                Mifecha = MBFechaF;
                break;
            case '12':
                break;
            case '15':
                MensajeEncabData = "RESUMEN DE COMISIONES POR VENDEDORES";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                Orientacion_Pagina = 2;
                break;
            case '16':
                MensajeEncabData = "RESUMEN DE VENTAS DE PRODUCTOS MENSUALIZADO";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                break;
            case '17':
                MensajeEncabData = "VENTAS RESUMIDAS POR VENDEDOR";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                Orientacion_Pagina = 1;
                break;
            case '18':
                MensajeEncabData = "TOTAL CUENTAS POR COBRAR POR TIEMPO DE CREDITO";
                SQLMsg1 = "CORTE DEL " + MBFechaI + " AL " + MBFechaF;
                Mifecha = MBFechaF;
                Orientacion_Pagina = 2;
                break;

            default:
                case_no_def = true;
                break;
        }

        if (!case_no_def) {
            parametros['MensajeEncabData'] = MensajeEncabData;
            parametros['SQLMsg1'] = SQLMsg1;
            parametros['Mifecha'] = Mifecha;
            parametros['Opcion'] = Opcion;

            $.ajax({
                url: '../controlador/facturacion/HistorialFacturasC.php?Imprimir=true',
                type: 'post',
                dataType: 'json',
                data: { 'parametros': parametros },
                success: function (data) {
                    if (data.response == 1) {
                        $('#myModal_espera').modal('hide');
                        swal.fire('Información', data.mensaje, 'success');
                        var url = "../../TEMP/IMPRIMIR/";
                        descargarArchivo(url, data.nombre);

                        globalAdoQuery = null;
                    }
                }
            });
        } else {
            swal.fire('Información', 'Caso no definido', 'error');
        }
    }
</script>