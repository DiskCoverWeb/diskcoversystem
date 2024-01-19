<script>
    /*
        AUTOR DE RUTINA	: Leonardo Súñiga
        FECHA CREACION	: 03/01/2024
        FECHA MODIFICACION: 17/01/2024
        DESCIPCION : Clase que se encarga de manejar la interfaz de la pantalla de recaudacion de bancos
    */
    var TextoBanco = "";

    var Cta_Cobrar = "";
    var CxC_Clientes = "";
    var LogoFactura = "";
    var AltoFactura = "";
    var AnchoFactura = "";
    var EspacioFactura = "";
    var Pos_Factura = "";
    var Individual = "";
    var TipoFactura = "";
    var Tipo_Carga = "";
    var Costo_Banco = "";
    var Cta_Bancaria = "";
    var Cta_Gasto_Banco = "";
    var Factura_No = "";

    let FA = {
        'Factura': '.',
        'Serie': '.',
        'Nuevo_Doc': true,
        'TC': '.',
        'Cod_CxC': '.',
        'Autorizacion': '.',
        'Tipo_PRN': '.',
        'Imp_Mes': '.',
        'Porc_IVA': '.',
        'Cta_CxP': '.',
        'Vencimiento': '.',
        'Cta_CxP_Anterior': '.',
        'Fecha_Corte': '.',
        'Fecha': '.'
    };

    $(document).ready(function () {

        //FORM ACTIVATE
        DCLinea();
        DCBanco();
        DCGrupos();
        DCEntidadBancaria();
        DatosBanco();

        //Se encarga de manejar la entidad bancaria cuando cambia
        $('#DCEntidadBancaria').change(function () {
            var entidad = $(this).val();
            TextoBanco = entidad;
            OpcionesEntidadesBancarias(TextoBanco);
        });

        //MBFechaI_LostFocus
        $('#MBFechaI').blur(function () {
            $('#MBFechaF').val('<?php echo date('Y-m-t'); ?>');
            var parametros = {
                'MBFechaI': $('#MBFechaI').val()
            };
            $.ajax({
                url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?MBFechaI_LostFocus=true',
                type: 'post',
                dataType: 'json',
                data: { 'parametros': parametros },
                success: function (data) {
                    $('#myModal_espera').modal('hide');
                    if (data.length > 0) {
                        $('#DCLinea').empty();
                        $.each(data, function (index, value) {
                            $('#DCLinea').append('<option value="' + value['Concepto'] + '">' + value['Concepto'] + '</option>');
                        });
                    } else {
                        $('#DCLinea').empty();
                        $('#DCLinea').append('<option value="No existen datos">No existen datos</option>');
                    }
                    FA.Fecha = $('#MBFechaI').val();
                    FA.TC = "FA";
                }
            });
        });

        //TextFacturaNo_LostFocus
        $('#TextFacturaNo').blur(function () {
            Factura_No = $('#TextFacturaNo').val();
            FA.Factura = $('#TextFacturaNo').val();
            var parametros = {
                'FA': FA
            };
            $.ajax({
                url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?TextFacturaNo_LostFocus=true',
                type: 'post',
                dataType: 'json',
                data: { 'parametros': parametros },
                success: function (data) {
                    $('#myModal_espera').modal('hide');
                    if (data.response) {
                        FA.Factura = $('#TextFacturaNo').val();
                    } else {
                        FA.Nuevo_Doc = true;
                        FA.Factura = data.Factura;
                        if ($('#TextFacturaNo').val() < FA.Factura) {
                            var Mensajes = "La Factura/Nota de Venta No. " + $('#TextFacturaNo').val() +
                                " No está Procesada. ¿Desea Procesarla?";
                            var Titulo = "Formulario de Confirmación";
                            //Ask with swal fire 
                            Swal.fire({
                                title: Titulo,
                                text: Mensajes,
                                type: "warning",
                                confirmButtonText: 'Sí!',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                cancelButtonText: 'No!'
                            }).then((result) => {
                                if (result.value) {
                                    FA.Factura = $('#TextFacturaNo').val();
                                }
                            });
                        } else {
                            FA.Factura = data.Factura;
                            $('#TextFacturaNo').val(FA.Factura);
                        }
                    }
                }
            });
        });

        //DCLinea_LostFocus
        $('#DCLinea').blur(function () {
            FA.Cod_CxC = $(this).val();
            FA.Fecha = '<?php echo date('Y-m-d'); ?>';
            var parametros = {
                'FA': FA
            };
            $.ajax({
                url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DCLinea_LostFocus=true',
                type: 'post',
                dataType: 'json',
                data: { 'parametros': parametros },
                success: function (data) {
                    $('#myModal_espera').modal('hide');
                    var tmp = data.TFA;
                    for (var key in tmp) {
                        if (tmp.hasOwnProperty(key)) {
                            FA[key] = tmp[key];
                        }
                    }
                    $('#Label6').text('Aut. ' + FA.Autorizacion + " " + FA.TC + " No. " + FA.Serie + "-");
                    $('#TextFacturaNo').val(FA.Factura);
                }
            });
        });

        //Handle DCLinea on change
        $('#DCLinea').change(function () {
            FA.Cod_CxC = $(this).val();
            FA.Fecha = '<?php echo date('Y-m-d'); ?>';
            var parametros = {
                'FA': FA
            };
            $.ajax({
                url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DCLinea_LostFocus=true',
                type: 'post',
                dataType: 'json',
                data: { 'parametros': parametros },
                success: function (data) {
                    $('#myModal_espera').modal('hide');
                    var tmp = data.TFA;
                    for (var key in tmp) {
                        if (tmp.hasOwnProperty(key)) {
                            FA[key] = tmp[key];
                        }
                    }
                    $('#Label6').text('Aut. ' + FA.Autorizacion + " " + FA.TC + " No. " + FA.Serie + "-");
                    $('#TextFacturaNo').val(FA.Factura);
                }
            });
        });

        //Navegacion cuando pierden el foco
        $('#DCEntidadBancaria').blur(function () {
            $('#MBFechaI').focus();
        });

        $('#MBFechaI').blur(function () {
            $('#myModal_espera').modal('show');
            $('#MBFechaF').focus();
        });

        $('#MBFechaF').blur(function () {
            $('#CheqRangos').focus();
        });

        $('#CheqRangos').blur(function () {
            $('#DCGrupoI').focus();
        });

        $('#DCGrupoI').blur(function () {
            $('#DCGrupoF').focus();
        });

        $('#DCGrupoF').blur(function () {
            $('#DCBanco').focus();
        });

        $('#DCBanco').blur(function () {
            $('#TxtCodBanco').focus();
        });

        $('#TxtCodBanco').blur(function () {
            $('#MBFechaV').focus();
        });

        $('#MBFechaV').blur(function () {
            $('#DCLinea').focus();
        });

        $('#DCLinea').blur(function () {
            $('#myModal_espera').modal('show');
            $('#CheqMatricula').focus();
        });

        $('#CheqMatricula').blur(function () {
            $('#TextFacturaNo').focus();
        });

        $('#TextFacturaNo').blur(function () {
            $('#myModal_espera').modal('show');
            $('#CheqNumCodigos').focus();
        });

        $('#CheqNumCodigos').blur(function () {
            $('#CheqAlDia').focus();
        });

        $('#CheqAlDia').blur(function () {
            $('#LabelAbonos').focus();
        });

        //Handle Command1_Click
        $('#Command1').click(function () {
            $('#modal_subir_archivo').modal('show');
        });

        $('#btnSubirArchivo').click(function () {
            $('#modal_subir_archivo').modal('hide');
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            Command1_Click();
        });

        //Handle Command4_Click
        $('#Command4').click(function () {
            $('#myModal_espera').show();
            $('#myModal_espera').modal('show');
            Command4_Click();
        });


    });

    function Command4_Click() {
        $('#DGFactura').empty();
        var parametros = {
            'FA': FA,
            'Factura_No': Factura_No,
            'MBFechaI': $('#MBFechaI').val(),
            'TextoBanco': TextoBanco,
            'DCBanco': $('#DCBanco').val(),
            'MBFechaF': $('#MBFechaF').val(),
            'CheqRangos': $('#CheqRangos').is(':checked'),
            'Tipo_Carga': Tipo_Carga,
            'CheqAlDia': $('#CheqAlDia').is(':checked'),
            'DCGrupoF': $('#DCGrupoF').val(),
            'DCGrupoI': $('#DCGrupoI').val(),
            'Cta_Bancaria': Cta_Bancaria
        }

        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?Command4_Click=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                $('#myModal_espera').modal('hide');
                $('#LabelAbonos').val(data.LabelAbonos);
                swal.fire({
                    title: 'Información',
                    text: data.Mensaje,
                    type: 'info',
                    confirmButtonText: 'Aceptar'
                });
            
                var url = "../../TEMP/FRECAUDACIONBANCOSPREFA/" + data.Nombre1;
                var url2 = "../../TEMP/FRECAUDACIONBANCOSPREFA/" + data.Nombre2;

                var enlaceTemporal = $('<a></a>')
                    .attr('href', url)
                    .attr('download', data.Nombre1)
                    .appendTo('body');

                enlaceTemporal[0].click();
                enlaceTemporal.remove();

                var enlaceTemporal2 = $('<a></a>')
                    .attr('href', url2)
                    .attr('download', data.Nombre2)
                    .appendTo('body');

                enlaceTemporal2[0].click();
                enlaceTemporal2.remove();
            }
        });

    }

    function DatosBanco() {
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DatosBanco=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                Tipo_Carga = data.Tipo_Carga;
                Costo_Banco = data.Costo_Banco;
                Cta_Bancaria = data.Cta_Bancaria;
                Cta_Gasto_Banco = data.Cta_Gasto_Banco;
            }
        });
    }

    /*function DGFactura() {
        $.ajax({
            type: "POST",
            url: "../controlador/facturacion/FRecaudacionBancosPreFaC.php?DGFactura=true",
            dataType: "json",
            success: function (response) {
                $('#DGFactura').empty();
                $('#DGFactura').html(response.tbl);
            }
        });
    }*/

    function Command1_Click() {
        $('#myModal_espera').show();
        //DGFactura.Visible = false
        FA.Cod_CxC = $('#DCLinea').val();
        var fileInput = $('#fileInput')[0];
        var archivo = fileInput.files[0];

        var formData = new FormData();
        formData.append('FA', JSON.stringify(FA)); // Asegúrate de que FA es un objeto serializable
        formData.append('Factura_No', Factura_No);
        formData.append('MBFechaI', $('#MBFechaI').val());
        formData.append('TextoBanco', TextoBanco);

        if (archivo) {
            formData.append('archivoBanco', archivo, archivo.name);
        }

        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?Command1_Click=true',
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
            success: function (data) {
                $('#myModal_espera').modal('hide');
                var response = JSON.parse(data);
                $('#DGFactura').empty();
                $('#DGFactura').html(response.tbl);
                $('#LabelAbonos').val(response.TotalIngreso);
                $('#TxtFile').text(response.TxtFile);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud: " + textStatus, errorThrown);
                $('#myModal_espera').modal('hide');
            }
        });
    }




    function OpcionesEntidadesBancarias(TextoBanco) {
        color = 'rgb(255,255,255)';
        fontColor = 'black';
        switch (TextoBanco) {
            case 'BOLIVARIANO':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoBolivarianoLogo.png");
                $("#imgBanco").attr("alt", "Logo Banco Bolivariano");
                color = '#008080';
                fontColor = 'white';
                break;
            case 'INTERNACIONAL':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoInternacionalLogo.png");
                $("#imgBanco").attr("alt", "Logo Banco Internacional");
                color = '#f3a446';
                fontColor = 'black';
                break;
            case 'GUAYAQUIL':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoGuayaquilLogo.png");
                $("#imgBanco").attr("alt", "Logo Banco Guayaquil");
                color = '#f30582';
                fontColor = 'black';
                break;
            case 'PICHINCHA':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoPichinchaLogo.png");
                $("#imgBanco").attr("alt", "Logo Banco Pichincha");
                color = '#FFFF80';
                fontColor = 'black';
                break;
            case 'PACIFICO':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoPacificoLogo.png");
                $("#imgBanco").attr("alt", "Logo Banco Pacifico");
                color = '#C0C0FF';
                fontColor = 'black';
                break;
            case 'PRODUBANCO':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/ProduBancoLogo.png");
                $("#imgBanco").attr("alt", "Logo Produbanco");
                color = 'white';
                fontColor = 'black';
                break;
            case 'OTROSBANCOS':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/OtrosBancosLogo.png");
                $("#imgBanco").attr("alt", "Logo Otros Bancos");
                color = 'rgb(255,255,255)';
                fontColor = 'black';
                break;
            case 'TARJETAS':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/TarjetasLogo.png");
                $("#imgBanco").attr("alt", "Logo Tarjetas");
                color = 'rgb(255,253,253)';
                fontColor = 'black';
                break;
            case 'COOPJEP':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/CoopJEPLogo.png");
                $("#imgBanco").attr("alt", "Logo Cooperativa JEP");
                color = '#80FF80';
                fontColor = 'black';
                break;
            case 'FARMACIAS':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/OtrosBancosLogo.png");
                $("#imgBanco").attr("alt", "Logo Otros Bancos");
                color = 'rgb(255,255,255)';
                fontColor = 'black';
                break;
            case 'POREXCEL':
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/RecaudacionExcelLogo.png");
                $("#imgBanco").attr("alt", "Logo Recaudacion por Excel");
                color = 'white';
                fontColor = 'black';
                break;
            default:
                $("#imgBanco").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/OtrosBancosLogo.png");
                $("#imgBanco").attr("alt", "Logo Otros Bancos");
                color = 'rgb(255,255,255)';
                fontColor = 'black';
                break;
        }
        $('#fieldsetForm').css('background-color', color);
        $('#fieldsetForm').css('color', fontColor);

    }

    function DCLinea() {
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DCLinea=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('#DCLinea').empty();
                    $.each(data, function (index, value) {
                        $('#DCLinea').append('<option value="' + value['Concepto'] + '">' + value['Concepto'] + '</option>');
                    });
                    Cta_Cobrar = data[0]['CxC'];
                    CxC_Clientes = data[0]['Concepto'];
                    LogoFactura = data[0]['Logo_Factura'];
                    AltoFactura = data[0]['Largo'];
                    AnchoFactura = data[0]['Ancho'];
                    EspacioFactura = data[0]['Espacios'];
                    Pos_Factura = data[0]['Pos_Factura'];
                    Individual = data[0]['Individual'];
                    TipoFactura = data[0]['Fact'];
                }

            }
        });

    }

    function DCBanco() {
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DCBanco=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('#DCBanco').empty();
                    $.each(data, function (index, value) {
                        $('#DCBanco').append('<option value="' + value['Codigo'] + "     " + value['Cuenta'] + '">' + value['Codigo'] + ' - ' + value['Cuenta'] + '</option>');
                    });
                }
            }
        });
    }

    function DCGrupos() {
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DCGrupos=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
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

    function DCEntidadBancaria() {
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosPreFaC.php?DCEntidadBancaria=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('#DCEntidadBancaria').empty();
                    $.each(data, function (index, value) {
                        $('#DCEntidadBancaria').append('<option value="' + value['Abreviado'] + '">' + "BANCO PICHINCHA" + '</option>');//value['Descripcion']
                    });
                    TextoBanco = data[0]['Abreviado'];
                    TextoBanco = "PICHINCHA";//POR EL MOMENTO SOLO VALE BANCO PICHINCHA
                    OpcionesEntidadesBancarias(TextoBanco);

                }
            }
        });
    }
</script>
<style>
    .inline {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .inline input {
        margin-left: 10px;
    }

    #TxtFile {
        width: 100%;
        background-color: black;
        color: white;
    }

    input,
    select {
        color: black;
    }

    fieldset {
        background-color: #008080;
        padding: 10px;
        color: white;
        height: 500px;
        max-height: 500px;
    }
</style>
<div>
    <div class="row">
        <div class="col-sm-5" style="" id="btnsContainers">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-default" style="border: solid 1px">
                <img src="../../img/png/salire.png">
            </a>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Enviar Cobros"
                id="Command4" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/upload-file.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Subir Abonos"
                id="Command1" onclick="" style="border: solid 1px">
                <img src="../../img/png/FRecaudacionBancosPreFa/papers.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Generar Facturas"
                id="Command6" onclick="" style="border: solid 1px" disabled>
                <img src="../../img/png/FRecaudacionBancosPreFa/facturas.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Alumnos Contabilidad"
                id="Command7" onclick="" style="border: solid 1px" disabled>
                <img src="../../img/png/FRecaudacionBancosPreFa/alumnos.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Renumerar Codigos"
                id="Command3" onclick="" style="border: solid 1px" disabled>
                <img src="../../img/png/FRecaudacionBancosPreFa/renumerar.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Imprimir Codigos"
                id="Command5" onclick="" style="border: solid 1px" disabled>
                <img src="../../img/png/FRecaudacionBancosPreFa/printer.png">
            </button>
        </div>
        <div class="col-sm-3">
            <label for="DCEntidadBancaria" style="display:block;">Entidad Bancaria:</label>
            <select name="DCEntidadBancaria" id="DCEntidadBancaria" style="width: 100%; max-width: 100%;" disabled></select>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <label for="DCBanco" style="">CUENTA A LA QUE SE VA ACREDITAR LOS
                    ABONOS:</label>
                <select name="DCBanco" id="DCBanco" style="width:98%; max-width: 98%;">
                    <option value="0">Banco</option>
                </select>
            </div>
            <div class="row">
                <label for="CheqMatricula" style="font-size:12.9px">
                    <input type="checkbox" name="CheqMatricula" id="CheqMatricula" /> Generar Matricula y
                    Pension
                </label>
            </div>
        </div>
    </div>
    <div class="row" style="padding: 10px;">
        <form action="">
            <fieldset style="" id="fieldsetForm">
                <div class="row">
                    <div class="col-sm-3" style="display:flex; align-items:center; justify-content: center;">
                        <img src="../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoBolivarianoLogo.png"
                            alt="Logo Banco Bolivariano" width="90%" id="imgBanco" style="margin-top:15px">
                    </div>
                    <div class="col-sm-3">
                        <label class="inline" for="MBFechaI">Facturacion <input id="MBFechaI" name="MBFechaI"
                                type="date" style="margin-left:18px; width:100%; text-align:center;"
                                value="<?php echo date('Y-m-d'); ?>" /></label>
                        <label class="inline" for="MBFechaF" style="white-space:nowrap;">Tope de Pago <input
                                id="MBFechaF" name="MBFechaF" type="date" style="width:100%; text-align:center;"
                                value="<?php echo date('Y-m-d'); ?>" /></label>
                        <label class="inline" for="MBFechaV">Vencimiento <input id="MBFechaV" name="MBFechaV"
                                type="date" style="margin-left:14px; width:100%; text-align:center;"
                                value="<?php echo date('Y-m-d'); ?>" /></label>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <label for="CheqRangos">
                                <input type="checkbox" name="CheqRangos" id="CheqRangos" checked /> Procesar Por Rangos
                                Grupos:
                            </label>
                        </div>
                        <div class="row">
                            <select name="DCGrupoI" id="DCGrupoI" style="width:48%; max-width: 48%;">
                                <option value="0"></option>
                            </select>
                            <select name="DCGrupoF" id="DCGrupoF" style="width:48%; max-width: 48%;">
                                <option value="0"></option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="DCLinea" style="font-size:12px;">LINEA DE CUENTAS POR COBRAR PENSIONES:</label>
                            <select name="DCLinea" id="DCLinea" style="width:98%; max-width: 98%;">
                                <option value="0">CxC Clientes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="TxtCodBanco" style="display:block">COD. BANCO
                            <input type="text" name="TxtCodBanco" id="TxtCodBanco" style="display:block; width:100%"
                                value="0" />
                        </label>
                        <label for="TextFacturaNo" style="display:block" id="Label6">Nota de Venta No.
                        </label>
                        <input type="text" name="TextFacturaNo" id="TextFacturaNo" style="display:block; width:100%"
                            value="99999" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="CheqNumCodigos">
                            <input type="checkbox" name="CheqNumCodigos" id="CheqNumCodigos" checked /> Procesar Con
                            Codigo de
                            la Empresa
                        </label>
                        <label for="CheqAlDia" style="padding-left:10px">
                            <input type="checkbox" name="CheqAlDia" id="CheqAlDia" style="font-size:12.9px" /> Generar
                            quienes esten al dia
                        </label>
                        <label for="LabelAbonos" style="padding-left:10px">TOTAL RECAUDADO
                            <input type="text" name="LabelAbonos" id="LabelAbonos" value="0.00"
                                style="text-align: right;" />
                        </label>
                    </div>
                </div>
                <div class="row" style="margin: 10px;">
                    <textarea class="form-control" name="TxtFile" id="TxtFile" rows="5" readonly></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div id="DGFactura">

                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_subir_archivo" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Archivo del Banco</h4>
            </div>
            <div class="modal-body">
                <input type="file" id="fileInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSubirArchivo">Subir Archivo</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->