<!-- Script para Modal FPensiones -->
<script>

    /*
        AUTOR DE RUTINA	: Leonardo Súñiga
        FECHA CREACION	: 01/03/2024
        FECHA MODIFICACION: P/01/2024
        DESCIPCIÓN		: Vista del modal FPensiones, se encarga del modal cuando se presiona el boton 
        de Generar o Eliminar por lotes los rubros a facturar o el boton de Generar Deudas Pendientes
    */

    var MCodigo1;
    var MCodigo2;
    var btnCase;
    $(document).ready(function () {
        MCodigo1 = "";
        MCodigo2 = "";
        btnCase = "";
        DCInv();
        DCGruposM();
        $('#MCheqRangos').change(function () {
            if ($(this).is(':checked')) {
                // Acciones cuando el checkbox está marcado
                $('#MDCGrupoI').prop('disabled', false);
                $('#MDCGrupoF').prop('disabled', false);
            } else {
                // Acciones cuando el checkbox está desmarcado
                $('#MDCGrupoI').prop('disabled', true);
                $('#MDCGrupoF').prop('disabled', true);
            }
        });

        $('#MDCGrupoI').change(function () {
            MCodigo1 = $(this).val();
        });

        $('#MDCGrupoF').change(function () {
            MCodigo2 = $(this).val();
        });

        //Handle buttons
        $('#btnInsertarPensiones').click(function () {
            $('#clave_supervisor').modal('show');
            btnCase = "Insertar";
        });

        $('#btnEliminarPensiones').click(function () {
            $('#clave_supervisor').modal('show');
            btnCase = "Eliminar";
        });

        $('#btnMasMenosPensiones').click(function () {
            Toolbarl_ButtonClick("Pension");
            btnCase = "Pension";
        });

        $('#btnMasMenosDescuento').click(function () {
            Toolbarl_ButtonClick("Descuento");
            btnCase = "Descuento";
        });

        $('#btnMasMenosDescuento2').click(function () {
            Toolbarl_ButtonClick("Descuento2");
            btnCase = "Descuento2";
        });

        $('#btnCopiarMes').click(function () {
            $('#clave_supervisor').modal('show');
            btnCase = "Copiar_Mes";
        });

        $('#btnMultas').click(function () {
            $('#clave_supervisor').modal('show');
            btnCase = "Multas";
        });

        //Manejador del modal Copiar mes cuando se presiona Enter.
        $('#LstCopiar').keydown(function (e) {
            if (e.key === "Enter") {
                LstCopiar_KeyDown();
            }
        });

    });

    function resp_clave_ingreso(response) {
        if (response.respuesta == 1) {
            Toolbarl_ButtonClick(btnCase);
        } else {
            console.log("Clave incorrecta");
        }
    }

    function LstCopiar_KeyDown() {
        var parametros = {
            'LstCopiar': $('input[name="LstCopiar"]:checked').val(),
            'FechaTexto': $('#MMBFechaI').val(),
            'Codigo1': MCodigo1,
            'Codigo2': MCodigo2,
            'Contador': $('#MTextCant').val(),
            'CheqRangos': $('#MCheqRangos').is(':checked') ? 1 : 0,
        };

        if (parametros['LstCopiar'] == undefined) {
            swal.fire({
                title: "Error",
                text: "Debe seleccionar un mes",
                type: "error"
            });
            return;
        }

        $.ajax({
            url: "../controlador/facturacion/FPensionesC.php?Copiar_Mes_KeyDown=true",
            type: "POST",
            data: { 'parametros': parametros },
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    swal.fire({
                        title: "Correcto",
                        text: data.msj,
                        type: "success"
                    });
                    //Hide modal
                    $('#FrmCopiar').modal('hide');
                } else {
                    swal.fire({
                        title: "Error",
                        text: data.msj,
                        type: "error"
                    });
                    console.log(data.error);
                }
            }
        });
    }


    function DCInv() {
        $.ajax({
            url: "../controlador/facturacion/FPensionesC.php?DCInv=true",
            type: "POST",
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    $('#MDCInv').empty();
                    $.each(data.datos, function (index, value) {
                        var labelContent = `<label for="MChkProd${index}">
                                                <input type="radio" name="DCInv" id="MChkProd${index}" value="${value['NomProd']}"> ${value['NomProd']}
                                            </label><br>`;
                        $('#MDCInv').append(labelContent);
                    });
                } else {
                    $('#MDCInv').append('<label for="MChkProd0">No se encontraron productos</label>');
                }
            }

        });
    }

    function DCGruposM() {
        $.ajax({
            url: "../controlador/facturacion/ListarGruposC.php?DCGrupos=true",
            type: "POST",
            success: function (response) {
                var data = JSON.parse(response);
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $('#MDCGrupoI').append('<option value="' + value['Grupo'] + '">' + value['Grupo'] + '</option>');
                        $('#MDCGrupoF').append('<option value="' + value['Grupo'] + '">' + value['Grupo'] + '</option>');
                    });
                    MCodigo1 = data[0]['Grupo'];

                    var ultimoGrupo = data[data.length - 1]['Grupo'];
                    $('#MDCGrupoF').val(ultimoGrupo);
                    MCodigo2 = ultimoGrupo;
                }
            }
        });
    }

    function Toolbarl_ButtonClick(btnCase) {
        var Contador = $('#MTextCant').val();
        var Valor = $('#MTxtArea').val();
        var Total_Desc = $('#MTxtDesc').val();
        var Total_Desc2 = $('#MTxtDesc2').val();
        var CodigoP = $('input[name="DCInv"]:checked').val();
        var CheqRangos = $('#MCheqRangos').is(':checked') ? 1 : 0;
        if (CodigoP == undefined) {
            swal.fire({
                title: "Error",
                text: "Debe seleccionar un producto",
                type: "error"
            });
            return;
        }
        var FechaTexto = $('#MMBFechaI').val();

        var parametros = {
            'Contador': Contador,
            'Valor': Valor,
            'Total_Desc': Total_Desc,
            'Total_Desc2': Total_Desc2,
            'CodigoP': CodigoP,
            'Codigo1': MCodigo1,
            'Codigo2': MCodigo2,
            'FechaTexto': FechaTexto,
            'CheqRangos': CheqRangos
        };

        switch (btnCase) {
            case "Insertar":
                Existen_Rubros(parametros);
                break;
            case "Eliminar":
                Eliminar_Pensiones(parametros);
                break;
            case "Pension":
                Tipo_Cambio_Valor("Pension", parametros);
                break;
            case "Descuento":
                Tipo_Cambio_Valor("Descuento", parametros);
                break;
            case "Descuento2":
                Tipo_Cambio_Valor("Descuento2", parametros);
                break;
            case "Copiar_Mes":
                Copiar_Mes();
                break;
            case "Multas":
                Multas(parametros);
                break;
        }
    }

    function Multas(param) {
        $.ajax({
            url: "../controlador/facturacion/FPensionesC.php?Multas=true",
            type: "POST",
            data: { 'parametros': param },
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    swal.fire({
                        title: "Correcto",
                        text: data.msj,
                        type: "success"
                    });
                } else {
                    swal.fire({
                        title: "Error",
                        text: data.msj,
                        type: "error"
                    });
                    console.log(data.error);
                }
            }
        });
    }

    function Copiar_Mes() {
        $.ajax({
            url: "../controlador/facturacion/FPensionesC.php?Copiar_Mes=true",
            type: "POST",
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    $('#FrmCopiar').modal('show');
                    $('#LstCopiar').empty();
                    $.each(data.datos, function (index, value) {
                        var valor = value['Periodo'] + " " + value['Num_Mes'];
                        var labelContent = `<label for="LstCopiar${index}">
                                                <input type="radio" name="LstCopiar" id="LstCopiar${index}" value="${valor}"> ${valor}
                                            </label><br>`;
                        $('#LstCopiar').append(labelContent);
                    });
                } else {
                    console.log(data.error);
                }
            }
        });
    }

    function Tipo_Cambio_Valor(tipo_cambio, param) {
        param['Tipo_Cambio'] = tipo_cambio;
        var valor_cambiar = 0.00;
        var titulo = "CAMBIO DE VALORES EN GRUPO";
        var msj = "";
        switch (tipo_cambio) {
            case "Pension":
                valor_cambiar = parseFloat($('#MTxtArea').val());
                msj = "AUMENTA/DECREMENTA LOS VALORES DE PENSION: ";
                break;
            case "Descuento":
                valor_cambiar = parseFloat($('#MTxtDesc').val());
                msj = "AUMENTA/DECREMENTA LOS VALORES DE DESCUENTOS: ";
                break;
            case "Descuento2":
                valor_cambiar = parseFloat($('#MTxtDesc2').val());
                msj = "AUMENTA/DECREMENTA LOS VALORES DE DESCUENTOS2: ";
                break;
        }

        if (valor_cambiar == 0) {
            switch (tipo_cambio) {
                case "Pension":
                    msjE = "El valor a facturar por mes no puede ser 0";
                    break;
                case "Descuento":
                    msjE = "El valor de descuento por mes no puede ser 0";
                    break;
                case "Descuento2":
                    msjE = "El valor de descuento 2 por mes no puede ser 0";
                    break;
            }
            swal.fire({
                title: "Error",
                text: msjE,
                type: "error"
            });
            return;
        }

        msj += $('input[name="DCInv"]:checked').val() + " POR: " + valor_cambiar;
        swal.fire({
            title: titulo,
            text: msj,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                param['Valor_Cambiar'] = valor_cambiar;
                $.ajax({
                    url: "../controlador/facturacion/FPensionesC.php?Tipo_Cambio_Valor=true",
                    type: "POST",
                    data: { 'parametros': param },
                    dataType: "json",
                    success: function (response) {
                        var data = response
                        if (data.res == 1) {
                            swal.fire({
                                title: "Correcto",
                                text: data.msj,
                                type: "success"
                            });
                        } else {
                            swal.fire({
                                title: "Error",
                                text: data.msj,
                                type: "error"
                            });
                            console.log(data.error);
                        }
                    }
                });
            } else {
                return;
            }
        });
    }

    function Eliminar_Pensiones(param) {
        swal.fire({
            title: "Eliminar",
            text: "¿Está seguro de eliminar los rubros con codigo " + param.CodigoP + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "../controlador/facturacion/FPensionesC.php?EliminarPensiones=true",
                    type: "POST",
                    data: { 'parametros': param },
                    dataType: "json",
                    success: function (response) {
                        var data = response
                        if (data.res == 1) {
                            swal.fire({
                                title: "Correcto",
                                text: data.msj,
                                type: "success"
                            });
                        } else {
                            swal.fire({
                                title: "Error",
                                text: data.msj,
                                type: "error"
                            });
                            console.log(data.error);
                        }
                    }
                });
            } else {
                return;
            }
        });


    }

    // Método intermedio para verificar si existen rubros.
    function Existen_Rubros(param) {
        $.ajax({
            url: "../controlador/facturacion/FPensionesC.php?ExistenRubros=true",
            type: "POST",
            data: { 'parametros': param },
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    swal.fire({
                        title: data.titulo,
                        text: data.msj,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value) {
                            Insertar_Pensiones(param);
                        } else {
                            return;
                        }
                    });
                } else {
                    swal.fire('Información', data.msj, 'info');
                    Insertar_Pensiones(param);
                }
            }
        });
    }

    function Insertar_Pensiones(param) {
        $.ajax({
            url: "../controlador/facturacion/FPensionesC.php?InsertarPensiones=true",
            type: "POST",
            data: { 'parametros': param },
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    swal.fire({
                        title: "Correcto",
                        text: data.msj,
                        type: "success"
                    });
                } else {
                    swal.fire({
                        title: "Error",
                        text: data.msj,
                        type: "error"
                    });
                    console.log(data.error);
                }
            }
        });
    }

</script>
<style>
    .pensiones-body {
        background-color: #fffec2;
    }
</style>

<!-- Modal FPensiones -->
<div class="modal fade" tabindex="-1" role="dialog" id="FPensiones">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row" style="text-align: center;">
                    <button class="btn btn-default" data-toggle="tooltip" data-dismiss="modal" data-placement="bottom"
                        title="Salir" id="" onclick="">
                        <img src="../../img/png/salire.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Insertar"
                        id="btnInsertarPensiones" onclick="">
                        <img src="../../img/png/insertar.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar"
                        id="btnEliminarPensiones" onclick="">
                        <img src="../../img/png/eliminarDoc.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="(+/-) Pensión"
                        id="btnMasMenosPensiones" onclick="">
                        <img src="../../img/png/mas_menos.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="(+/-) Desc."
                        id="btnMasMenosDescuento" onclick="">
                        <img src="../../img/png/mas_menos.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="(+/-) Desc. 2"
                        id="btnMasMenosDescuento2" onclick="">
                        <img src="../../img/png/mas_menos.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Copiar Mes"
                        id="btnCopiarMes" onclick="">
                        <img src="../../img/png/copiar_1.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Multa"
                        id="btnMultas" onclick="">
                        <img src="../../img/png/multa.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Recargos"
                        id="btnRecargos" onclick="" disabled>
                        <img src="../../img/png/depositar.png">
                    </button>
                </div>
            </div>
            <div class="modal-body pensiones-body">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <label for="MCheqRangos">
                            <input type="checkbox" name="MCheqRangos" id="MCheqRangos" checked> Por Rangos:
                        </label>
                        <select name="MDCGrupoI" id="MDCGrupoI" class="selectM"></select>
                        <select name="MDCGrupoF" id="MDCGrupoF" class="selectM"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <label for="MMBFechaI">Fecha Inicio de Emision:</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="date" name="MMBFechaI" id="MMBFechaI" value="<?php echo date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <label for="MTextCant">Cantidad de Meses:</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="MTextCant" id="MTextCant" style="" min="1" max="100"
                                    value="1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <label for="MTxtArea">Valor a Facturar por Mes:</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="MTxtArea" id="MTxtArea" class="" value="0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <label for="MTxtDesc">Descuento por Mes:</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="MTxtDesc" id="MTxtDesc" class="" value="0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <label for="MTxtDesc2">Descuento 2 por Mes:</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="MTxtDesc2" id="MTxtDesc2" class="" value="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="">
                                    Productos
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="MDCInv" style="height: 25vh; max-height:25vh; overflow-y:auto">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="FrmCopiar">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label for="">DUPLICAR</label>
            </div>
            <div class="modal-body">
                <div class="row" style="text-align: center;">
                    <div id="LstCopiar" style="height: 25vh; max-height:25vh; overflow-y:auto">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row" style="text-align: center;">
                    <label for="">Enter para cambiar</label>
                </div>
            </div>
        </div>
    </div>
</div>