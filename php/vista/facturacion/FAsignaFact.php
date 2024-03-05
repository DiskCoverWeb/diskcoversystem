<script>
    //document ready
    var datosFilaSelec = {};


    $(document).on('abrirModal', function (event, datosFila) {
        //Form Activate
        vaciarDivs();
        datosFilaSelec = datosFila;
        console.log(datosFilaSelec);
        $('#FAsignaFact').modal('show');
        $('#LblCodigo').text(datosFilaSelec.Codigo);//CodigoCliente
        $('#LblCliente').text(datosFilaSelec.Cliente);//Codigo1
        var fechaStr = $('#MBFechaI').val();
        var fecha = new Date(fechaStr);
        var year = fecha.getUTCFullYear();
        $('#Label1').text(year);
        AdoRubros();
        DCInvFA();
        Listar_Rubros_Grupo();
    });

    //Command1 Click ModalFA, se ponen diferentes nombres para que no interfieran con los otros modales y/o funciones donde se usan.
    function Command1_Click_MDA() {
        var LstMesesSeleccionados = [];
        $('#LstMeses input[type="checkbox"]').each(function (index, value) {
            if ($(this).is(':checked')) {
                LstMesesSeleccionados.push($(this).val());
            }
        });

        if (LstMesesSeleccionados.length == 0) {
            swal.fire({
                title: "Error",
                text: "Debe seleccionar al menos un mes",
                type: "error"
            });
            return;
        }

        if(LstMesesSeleccionados.length === 13){
            //Eliminar el primero y mandar los demas
            LstMesesSeleccionados.shift();
        }

        var CodigoP = $('input[name="MFADCInv"]:checked').val();

        if (CodigoP == undefined) {
            swal.fire({
                title: "Error",
                text: "Debe seleccionar un producto",
                type: "error"
            });
            return;
        }

        var parametros = {
            'TxtArea': $('#TxtArea').val(),
            'CodigoP': CodigoP,
            'CodigoCliente': datosFilaSelec.Codigo,
            'LstMeses': LstMesesSeleccionados,
            'Anio': $('#Label1').text(),
            'Descuento': $('#TxtDesc').val(),
            'Descuento2': $('#TxtDesc2').val(),
            'Codigo2': datosFilaSelec.Grupo
        };

        $.ajax({
            type: "POST",
            url: "../controlador/facturacion/FAsignaFactC.php?Command1_Click=true",
            data: { parametros: parametros },
            dataType: "json",
            success: function (response) {
                var data = response;
                if (data.res == 1) {
                    swal.fire({
                        title: "Correcto",
                        text: data.msj,
                        type: "success"
                    });
                    $('#FAsignaFact').modal('hide');
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

    function AdoRubros() {
        $.ajax({
            type: "POST",
            url: "../controlador/facturacion/FAsignaFactC.php?AdoRubros=true",
            dataType: "json",
            success: function (response) {
                var data = response;
                if (data.res == 1) {
                    $('#LstMeses').empty();
                    var primero = `<label for="MLstMes0">
                                                <input type="checkbox" name="MLstMes0" id="MLstMes0" value="0"> Todos
                                            </label><br>`;
                    $('#LstMeses').append(primero);
                    $.each(data.datos, function (index, value) {
                        var labelContent = `<label for="MLstMes${index + 1}">
                                                <input type="checkbox" name="MLstMes${index + 1}" id="MLstMes${index + 1}" value="${index + 1}"> ${value['Dia_Mes']}
                                            </label><br>`;
                        $('#LstMeses').append(labelContent);
                    });

                    $('#MLstMes0').change(function () {
                        // Verifica si el checkbox 'TODOS' está marcado
                        var estado = $(this).is(':checked');

                        //Si TODOS está marcado, los demas inputs también se marcan.
                        $('#LstMeses input[type="checkbox"]').prop('checked', estado);
                    });
                } else {
                    console.log(data.error);
                }
            }
        });
    }

    function DCInvFA() {
        $.ajax({
            url: "../controlador/facturacion/FAsignaFactC.php?DCInv=true",
            type: "POST",
            dataType: "json",
            success: function (response) {
                var data = response
                if (data.res == 1) {
                    $('#MFADCInv').empty();
                    $.each(data.datos, function (index, value) {
                        var labelContent = `<label for="MFAChkProd${index}">
                                                <input type="radio" name="MFADCInv" id="MFAChkProd${index}" value="${value['NomProd']}"> ${value['NomProd']}
                                            </label><br>`;
                        $('#MFADCInv').append(labelContent);
                    });
                } else {
                    $('#MFADCInv').append('<label for="MFAChkProd0">No se encontraron productos</label>');
                }
            }

        });
    }

    function Listar_Rubros_Grupo() {
        var parametros = {
            'CodigoCliente': datosFilaSelec.Codigo
        };
        $.ajax({
            type: "POST",
            url: "../controlador/facturacion/FAsignaFactC.php?Listar_Rubros_Grupo=true",
            data: { parametros: parametros },
            dataType: "json",
            success: function (response) {
                var data = response;
                if (data.res == 1) {
                    $('#DGRubros').empty();
                    $('#DGRubros').html(data.tbl);
                } else {
                    $('#DGRubros').empty();
                    $('#DGRubros').append('<label for="MFAChkProd0">No se encontraron productos</label>');
                }
            }
        });
    }

</script>

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
                                <div class="col-sm-3">
                                    <div class="row">
                                        <label for="" id="Label1" style="margin-left: 1vw;">
                                            Label1
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div id="LstMeses" class="espaciado" style="padding-left: 1vw;">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <label for="" style="margin-left: 1vw;">
                                            Productos
                                        </label>
                                    </div>
                                    <div id="MFADCInv" class="espaciado">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row centrar">
                                <button class="btn btn-default" data-toggle="tooltip" data-placement="left"
                                    title="Insertar" id="Command1" onclick="Command1_Click_MDA()">
                                    <img src="../../img/png/insertar.png">
                                </button>
                                <button class="btn btn-default" data-toggle="tooltip" data-placement="left"
                                    title="Modificar" id="Command3" onclick="">
                                    <img src="../../img/png/modificar.png">
                                </button>
                                <button class="btn btn-default" data-dismiss="modal" data-toggle="tooltip"
                                    data-placement="left" title="Cancelar" id="Command2" onclick="">
                                    <img src="../../img/png/salire.png">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="">
                            <div class="">
                                <label for="TxtDia">Día</label>
                                <input type="number" name="TxtDia" id="TxtDia" class="" style="width: 3vw;" min="1"
                                    max="31" value="1">
                                <label for="TxtArea">VALOR A FACTURAR</label>
                                <input type="text" name="TxtArea" id="TxtArea" placeholder="0.00" class="anchura"
                                    value="0.00">
                                <label for="TxtDesc">DESCUENTO</label>
                                <input type="text" name="TxtDesc" id="TxtDesc" placeholder="0.00" class="anchura"
                                    value="0.00">
                                <label for="TxtDesc2">DESCUENTO 2</label>
                                <input type="text" name="TxtDesc2" id="TxtDesc2" placeholder="0.00" class="anchura"
                                    value="0.00">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="DGRubros" style="height: 25vh; max-height:25vh; overflow-y:auto">
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->