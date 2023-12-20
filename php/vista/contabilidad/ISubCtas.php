<?php
date_default_timezone_set('America/Guayaquil');
?>
<style>
    .rotar-180 {
        transform: rotate(180deg);
        transform-origin: center;
    }

    #DLCtas {
        max-height: 84px;
        overflow-y: auto;
    }

    .boton-enfocado {
        border: 2px solid blue;
        /* o cualquier otro estilo que prefieras */
        background-color: lightgray;
    }

    .estiloOscuro {
        background-color: #f5f5f5;
        color: black;
    }
</style>
<script>

    var indiceActual = 0;
    var cadenaEliminar = "";//Para eliminar la subcuenta
    var Nuevo = true;

    $(document).ready(function () {
        OpcI_Click();
        CheqBloquear_Click();
        Nuevo = true;

        deshabilitarbtnEliminar();

        $('#btnSiguiente').click(function () {
            actualizarIndiceYLLenarCta(indiceActual + 1);
            Nuevo = false;
        });

        $('#btnAnterior').click(function () {
            actualizarIndiceYLLenarCta(indiceActual - 1);
            Nuevo = false;
        });

        $('#btnPrimero').click(function () {
            actualizarIndiceYLLenarCta(0);
            Nuevo = false;
        });

        $('#btnUltimo').click(function () {
            var ultimoIndice = $('#DLCtas').children('button').length - 1;
            actualizarIndiceYLLenarCta(ultimoIndice);
            Nuevo = false;
        });

        $(document).dblclick(function (event) {
            // Verifica si el doble clic no ocurrió en el contenedor de botones ni en sus elementos hijos
            if (!$(event.target).closest("#DLCtas").length) {
                Nuevo = true;
                deshabilitarbtnEliminar();
                despintarBoton();
            }
        });

        $('#btnNuevo').on('click', function () {
            deshabilitarbtnEliminar();
            if (Nuevo) {
                despintarBoton();
                NuevaCta();
                $('#TxtNivel').val('00');
                $('#TxtReembolso').val('0');
                $('#CheqNivel').prop('checked', false);
                var TipoCta = $("input[name='TipoCuenta']:checked").val();
                if (TipoCta === 'CC') {
                    $('#TxtCodigo').focus();
                    $('#TxtCodigo').select();
                } else {
                    $('#TxtNivel').focus();
                }
            } else {
                LlenarCta($('#DLCtas').children('button')[indiceActual].innerText);
                $('#TxtNivel').focus();
            }

        });
    });

    function habilitarbtnEliminar() {
        $('#btnEliminar').prop('disabled', false);
    }

    function deshabilitarbtnEliminar() {
        $('#btnEliminar').prop('disabled', true);
    }

    function actualizarIndiceYLLenarCta(nuevoIndice) {
        var listaItems = $('#DLCtas').children('button');
        if (nuevoIndice >= 0 && nuevoIndice < listaItems.length) {
            // Quitar el enfoque de todos los botones
            listaItems.removeClass('boton-enfocado');

            // Actualizar el índice
            indiceActual = nuevoIndice;

            // Enfocar el botón actual y aplicar la clase para resaltar
            var botonActual = $(listaItems[indiceActual]);
            botonActual.addClass('boton-enfocado');

            botonActual[0].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });

            var nombreCta = botonActual.text();
            LlenarCta(nombreCta);
            habilitarbtnEliminar();
        }
    }

    function actualizarIndiceYPintar(nuevoIndice) {//Lo mismo que el de arriba, pero solo es para que se pinte al hacer un click 
        var listaItems = $('#DLCtas').children('button');
        if (nuevoIndice >= 0 && nuevoIndice < listaItems.length) {
            listaItems.removeClass('boton-enfocado');

            indiceActual = nuevoIndice;

            var botonActual = $(listaItems[indiceActual]);
            botonActual.addClass('boton-enfocado');

        }
    }

    function despintarBoton() {
        var listaItems = $('#DLCtas').children('button');
        listaItems.removeClass('boton-enfocado');
    }

    //btnEliminar
    function Eliminar() {
        var parametros = {
            "Cadena": cadenaEliminar
        }
        var TipoCta = $("input[name='TipoCuenta']:checked").val();

        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/contabilidad/ISubCtasC.php?Eliminar=true',
            type: 'post',
            success: function (response) {
                var datos = JSON.parse(response);
                if (datos.length > 0) {
                    swal.fire('No se puede eliminar esta SubCuenta porque tiene cuentas procesables.', '', 'error');
                } else {
                    swal.fire({
                        title: 'Eliminar SubCuenta',
                        text: `¿Está seguro de eliminar la Cuenta No. [${cadenaEliminar}]`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                data: { parametros: parametros },
                                url: '../controlador/contabilidad/ISubCtasC.php?EliminarSubCta=true',
                                type: 'post',
                                success: function (response) {
                                    var datos = JSON.parse(response);
                                    if (datos == 1) {
                                        swal.fire('Subcuenta eliminada correctamente', '', 'success');
                                        ListarSubCtas(TipoCta);
                                    } else {
                                        swal.fire('Error al eliminar la subcuenta', '', 'error');
                                    }
                                }
                            });
                        }
                    });
                }
            }
        });
    }

    //btnNuevo
    function NuevaCta() {
        $("#DLCtas button").off('click dblclick');
        $("#DLCtas button").addClass('estiloOscuro');
        $('#TextSubCta').val('');
        $('#MBoxCta').val('0 .  .  .  .   ');
        var NumEmpresa = <?php echo $_SESSION['INGRESO']['item']; ?>;
        $('#TxtCodigo').val(NumEmpresa + '0000000');
    }


    //btnGrabar
    function GrabarCta() {

        var TipoCta = $("input[name='TipoCuenta']:checked").val();

        var parametros = {
            "CodigoCta": $('#TxtCodigo').val(),
            "TipoCta": TipoCta,
            "TxtNivel": $('#TxtNivel').val(),
            "CheqCaja": $('#CheqCaja').prop('checked') ? 1 : 0,
            "CheqNivel": $('#CheqNivel').prop('checked') ? 1 : 0,
            "CheqBloquear": $('#CheqBloquear').prop('checked') ? 1 : 0,
            "TextSubCta": $('#TextSubCta').val(),
            "TextPresupuesto": $('#TextPresupuesto').val() == '' ? 0 : $('#TextPresupuesto').val(),
            "MBoxCta": $('#MBoxCta').val() == '' ? '0 .  .  .  .   ' : $('#MBoxCta').val(),
            "TxtReembolso": $('#TxtReembolso').val(),
            "MBFechaI": $('#MBFechaI').val(),
            "MBFechaF": $('#MBFechaF').val()
        }
        console.log(parametros);
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/contabilidad/ISubCtasC.php?GrabarCta=true',
            type: 'post',
            success: function (response) {
                var datos = JSON.parse(response);
                if (datos == 1) {
                    swal.fire('Grabación Exitosa', '', 'success');
                    ListarSubCtas(TipoCta);
                } else {
                    swal.fire('Error al grabar la subcuenta', '', 'error');
                }
            }
        });
    }

    function ListarSubCtas(TipoCta) {
        var parametros = {
            "TipoCta": TipoCta
        };

        $("#DLCtas button").removeClass('estiloOscuro');

        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/contabilidad/ISubCtasC.php?ListarSubCtas=true',
            type: 'post',
            success: function (response) {
                var datos = JSON.parse(response);
                var AdoCatalogo = datos.AdoCatalogo;
                const AdoSubCta = datos.AdoSubCta;
                const lista = $('#DLCtas');
                LimpiarCampos();
                lista.empty();
                $('#TxtCodigo').val(datos.TxtCodigo);
                if (AdoSubCta.length > 0) {
                    $.each(AdoSubCta, function (index, item) {
                        $('<button>', {
                            type: 'button',
                            'class': 'list-group-item list-group-item-action',
                            'style': 'white-space: pre; font-family: Courier;',
                            'text': `${item.Nombre_Cta}`,
                            'dblclick': function () {
                                var indice = $('#DLCtas').children('button').index(this);
                                actualizarIndiceYLLenarCta(indice);
                                cadenaEliminar = item.Codigo;
                                Nuevo = false;
                            },
                            'click': function () {
                                var indice = $('#DLCtas').children('button').index(this);
                                actualizarIndiceYPintar(indice);
                                cadenaEliminar = item.Codigo;
                            }
                        }).appendTo(lista);
                    });
                }
            }
        });
    }

    function LimpiarCampos() {
        $('#TextSubCta').val('');
        $('#TxtCodigo').val('');
        $('#MBoxCta').val('0 .  .  .  .   ');
        $('#Label5').val('');
        $('#TxtReembolso').val('');
        $('#TxtNivel').val('');
        $('#TextPresupuesto').val('');
        $('#MBFechaI').val('<?php echo date('Y-m-d'); ?>');
        $('#MBFechaF').val('<?php echo date('Y-m-d'); ?>');
        $('#CheqNivel').prop('checked', false);
        $('#CheqBloquear').prop('checked', false);
        $('#CheqCaja').prop('checked', false);
    }

    function LlenarCta(DLCtas) {
        var parametros = {
            "CodigoCta": DLCtas
        }

        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/contabilidad/ISubCtasC.php?LlenarCta=true',
            type: 'post',
            success: function (response) {
                var data = JSON.parse(response);
                if (data.response == 1) {
                    var fields = data.AdoSubCta1[0];

                    $('#TextSubCta').val(fields.Detalle);
                    $('#TxtCodigo').val(fields.Codigo);
                    switch (fields.TC) {
                        case "G":
                            $('#OpcG').prop('checked', true);
                            break;
                        case "I":
                            $('#OpcI').prop('checked', true);
                            break;
                        case "PM":
                            $('#OpcPM').prop('checked', true);
                            break;
                        case "CC":
                            $('#OpcCC').prop('checked', true);
                            break;
                    }
                    $('#MBoxCta').val(fields.Cta_Reembolso);
                    $('#Label5').val(fields.Label5);
                    if(fields.Reembolso == "."){
                        $('#TxtReembolso').val(0);
                    }else{
                        $('#TxtReembolso').val(fields.Reembolso);
                    }
                    $('#TxtNivel').val(fields.Nivel);
                    $('#TextPresupuesto').val(fields.Presupuesto);
                    $('#MBFechaI').val(fields.Fecha_D);
                    $('#MBFechaF').val(fields.Fecha_H);
                    $('#CheqNivel').prop('checked', fields.Agrupacion == 1 ? true : false);
                    $('#CheqBloquear').prop('checked', fields.Bloquear == 1 ? true : false);
                    $('#CheqCaja').prop('checked', fields.Caja == 1 ? true : false);
                } else {
                    $('#TextSubCta').val('');
                    $('#TxtCodigo').val(data.TxtCodigo);
                }
            }
        });

    }

    function CheqBloquear_Click() {
        if ($('#CheqBloquear').prop('checked')) {
            $('#MBFechaI').css('visibility', 'visible');
            $('#MBFechaF').css('visibility', 'visible');
            $('#Label10').css('visibility', 'visible');
            $('#Label9').css('visibility', 'visible');
        } else {
            $('#MBFechaI').css('visibility', 'hidden');
            $('#MBFechaF').css('visibility', 'hidden');
            $('#Label10').css('visibility', 'hidden');
            $('#Label9').css('visibility', 'hidden');
        }
    }

    function OpcCC_Click() {
        ListarSubCtas('CC');
        $('#Label1').css('visibility', 'hidden'); //Label1 Visible False
        $('#TextPresupuesto').css('visibility', 'hidden'); //TextPresupuesto Visible False
        $('#TxtCodigo').prop('disabled', false); //TxtCodigo Enabled True
        $('#TxtNivel').prop('disabled', true); //TxtNivel Enabled False
        $('#CheqCaja').css('visibility', 'hidden'); //CheqCaja Visible False
        $('#LabelCheqCaja').css('visibility', 'hidden');
    }

    function OpcPM_Click() {
        ListarSubCtas('PM');
        $('#Label1').css('visibility', 'hidden'); //Label1 Visible False
        $('#TextPresupuesto').css('visibility', 'hidden'); //TextPresupuesto Visible False
        $('#TxtCodigo').prop('disabled', true); //TxtCodigo Enabled False
        $('#TxtNivel').prop('disabled', false); //TxtNivel Enabled True
        $('#CheqCaja').css('visibility', 'hidden'); //CheqCaja Visible False
        $('#LabelCheqCaja').css('visibility', 'hidden');
        $('#CheqNivel').prop('checked', true);  //CheqNivel Enabled True
    }

    function OpcG_Click() {
        ListarSubCtas('G');
        $('#Label1').css('visibility', 'visible'); //Label1 Visible True
        $('#TextPresupuesto').css('visibility', 'visible'); //TextPresupuesto Visible True
        $('#TxtCodigo').prop('disabled', true); //TxtCodigo Enabled False
        $('#TxtNivel').prop('disabled', false); //TxtNivel Enabled True
        $('#CheqCaja').css('visibility', 'visible'); //CheqCaja Visible True
        $('#LabelCheqCaja').css('visibility', 'visible');
        $('#CheqNivel').prop('checked', true);  //CheqNivel Enabled True

    }

    function OpcI_Click() {
        ListarSubCtas('I');
        $('#Label1').css('visibility', 'visible'); //Label1 Visible True
        $('#TextPresupuesto').css('visibility', 'visible'); //TextPresupuesto Visible True
        $('#TxtCodigo').prop('disabled', true); //TxtCodigo Enabled False
        $('#TxtNivel').prop('disabled', false); //TxtNivel Enabled True
        $('#CheqCaja').css('visibility', 'hidden'); //CheqCaja Visible False
        $('#LabelCheqCaja').css('visibility', 'hidden');
        $('#CheqNivel').prop('checked', true);  //CheqNivel Enabled True

    }

    function MarcarTexto(element) {
        element.select();
    }

</script>
<div>
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-default" data-toggle="tooltip" title="Eliminar" id="btnEliminar"
                onclick="Eliminar();">
                <img src="../../img/png/eliminar.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" title="Nuevo" id="btnNuevo" onclick="">
                <img src="../../img/png/nuevo.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" title="Grabar" id="btnGrabar" onclick="GrabarCta();">
                <img src="../../img/png/grabar.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" title="Primero" id="btnPrimero">
                <img src="../../img/png/primero.png">
            </button>
            <button class="btn btn-default rotar-180" data-toggle="tooltip" title="Anterior" id="btnAnterior">
                <img src="../../img/png/siguiente.png">
            </button>
            <button class="btn btn-default" data-toggle="tooltip" title="Siguiente" id="btnSiguiente">
                <img src="../../img/png/siguiente.png">
            </button>
            <button class="btn btn-default rotar-180" data-toggle="tooltip" title="Ultimo" id="btnUltimo">
                <img src="../../img/png/primero.png">
            </button>

        </div>
    </div>
    <div class="row" style="padding: 10px 0px 0px 15px">
        <div class="col-sm-11 panel panel-info">
            <div class="row ">
                <div class="col-sm-12 ">
                    <div>
                        <h4>Tipo de Cuenta</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcI" value='I' checked
                        onclick="OpcI_Click();">
                    <label class="form-check-label" for="OpcI">
                        Modulo de Ingresos
                    </label>
                </div>
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcG" value='G'
                        onclick="OpcG_Click();">
                    <label class="form-check-label" for="OpcG">
                        Modulo de Gastos
                    </label>
                </div>
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcPM" value='PM'
                        onclick="OpcPM_Click();">
                    <label class="form-check-label" for="OpcPM">
                        Modulo de Primas
                    </label>
                </div>
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcCC" value='CC'
                        onclick="OpcCC_Click();">
                    <label class="form-check-label" for="OpcCC">
                        Centro de Costos
                    </label>
                </div>
            </div>
            <div class="row" style="padding: 0px 10px 0px 10px">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center;">
                        SUBCUENTA DE BLOQUE
                    </div>
                    <div class="panel-body" id="btnContainer">
                        <div id="DLCtas" class="list-group">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 0px 10px 0px 10px">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="row" style="padding: 10px 0">
                            <!-- Código -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="TxtCodigo" class="mr-2">CÓDIGO</label>
                                <input type="text" class="form-control" id="TxtCodigo" placeholder="000"
                                    onclick="MarcarTexto(this);">
                            </div>
                            <!-- Nivel -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="TxtNivel" class="mr-2">NIVEL No.</label>
                                <input type="text" class="form-control" id="TxtNivel" placeholder="00"
                                    onclick="MarcarTexto(this);">
                            </div>
                            <!-- Agrupación Nivel -->
                            <div class="col-md-2 d-flex align-items-center" style="padding-top:30px">
                                <input class="form-check-input mr-1" type="checkbox" name="CheqNivel" id="CheqNivel">
                                <label class="form-check-label" for="CheqNivel" id="LabelCheqNivel">Agrupación
                                    nivel</label>
                            </div>
                            <!-- SUBCUENTA -->
                            <div class="col-md-4 d-flex align-items-center">
                                <label for="TextSubCta">SUBCUENTA</label>
                                <input type="text" class="form-control" id="TextSubCta" placeholder="" value=""
                                    onclick="MarcarTexto(this);">
                            </div>
                        </div>

                        <div class="row" style="padding: 10px 0">
                            <!-- REEMBOLSO -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="TxtReembolso">REEMBOLSO</label>
                                <input type="text" class="form-control" id="TxtReembolso" placeholder="0" value="0"
                                    onclick="MarcarTexto(this);">
                            </div>
                            <!-- VALOR -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="TextPresupuesto" id="Label1">VALOR</label>
                                <input type="text" class="form-control" id="TextPresupuesto" placeholder="0.00"
                                    value="0" style="text-align:right;" onclick="MarcarTexto(this);">
                            </div>
                            <!-- CUENTA RELACIONADA -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="MBoxCta" style="font-size:13.5px">CUENTA RELACIONADA</label>
                                <input type="text" class="form-control" id="MBoxCta" placeholder="0 .  .  .  .   "
                                    value="0 .  .  .  .   " style="text-align:right;" onclick="MarcarTexto(this);">
                            </div>
                            <!-- BLOQUEAR CODIGO -->
                            <div class="col-md-2 d-flex align-items-center" style="padding-top:25px">
                                <input class="form-check-input" type="checkbox" name="CheqBloquear" id="CheqBloquear"
                                    value='' onclick="CheqBloquear_Click();">
                                <label class="form-check-label" for="CheqBloquear" id="LabelCheqBloquear">
                                    Bloquear Codigo
                                </label>
                            </div>
                            <!-- FECHA DESDE -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="MBFechaI" id="Label10">Desde</label>
                                <input type="date" class="form-control" id="MBFechaI"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- FECHA HASTA -->
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="MBFechaF" id="Label9">Hasta</label>
                                <input type="date" class="form-control" id="MBFechaF"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="row" style="padding: 10px 0">
                            <!-- NOMBRE DE LA CUENTA -->
                            <div class="col-md-6 d-flex align-items-center">
                                <input type="text" class="form-control" id="Label5" placeholder="" value="" readonly
                                    style="color:blue;">
                            </div>
                            <!-- GASTO DE CAJA -->
                            <div class="col-md-2 d-flex align-items-center" style="padding-top:5px">
                                <input class="form-check-input" type="checkbox" name="CheqCaja" id="CheqCaja" value=''>
                                <label class="form-check-label" for="CheqCaja" id="LabelCheqCaja">
                                    Gasto de Caja
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-1">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-default" data-toggle="tooltip" title="Grabar" id="" onclick="GrabarCta();">
                        <img src="../../img/png/grabar.png">
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                    print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-default">
                        <img src="../../img/png/salire.png">
                    </a>
                </div>
            </div>
            <!--<div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-default" data-toggle="tooltip" title="Primero" id="">
                        <img src="../../img/png/primero.png">
                    </button>
                </div>
            </div>-->
        </div>
    </div>

</div>