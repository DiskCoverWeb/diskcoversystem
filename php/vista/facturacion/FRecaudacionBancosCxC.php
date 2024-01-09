<style type="text/css">
    .miPanel {
        background-color: #5bc0de;
    }
</style>

<div>
    <div class="row" style="margin:5px; padding-top:10px">
        <div class="col-sm-12 col-xs-12">
            <div class="col">
                <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
                    <img src="../../img/png/salire.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" title="Visualizar" class="btn btn-default" onclick="Visualizar()">
                    <img src="../../img/png/visual.png" width="25" height="30">
                </a>
            </div>

            <div class="col">
                <a href="javascript:void(0)" id="Enviar Rubros" title="Enviar Rubros" class="btn btn-default"
                    onclick="Enviar()">
                    <img src="../../img/png/enviarRubros.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Recibir Rubos" title="Recibir Rubos" class="btn btn-default"
                    onclick="Recibir()">
                    <img src="../../img/png/recibirRubros.png" width="25" height="30">
                </a>
            </div>
        </div>
    </div>
    <div class="row" style="margin:5px; padding-top:10px">
        <div class="col-sm-12 col-xs-12">
            <div class="col-sm-4">
                <label for="DCEntidad" style="font-size: 13px;">ENTIDAD FINANCIERA</label>
                <select class="form-control input-xs" name="DCEntidad" id="DCEntidad" onchange="DCEntidad">
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="col-xs-12">
                    <label for="CheqRangos" class="col control-label" style="font-size: 13px;">
                        <input type="checkbox" name="CheqRangos" id="CheqRangos"> Procesar por Rangos Grupos
                    </label>
                </div>
                <div class="col-xs-6">
                    <select class="form-control input-xs" name="DCGrupoI" id="DCGrupoI" style="display:none">
                        <option value="">Seleccione</option>
                    </select>
                </div>
                <div class="col-xs-6">
                    <select class="form-control input-xs" name="DCGrupoF" id="DCGrupoF" style="display:none">
                        <option value="">Seleccione</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-1">
                <label style="font-size: 13px;">ORDEN No.</label>
                <input type="text" name="TxtOrden" id="TxtOrden" placeholder="0" size="4">
            </div>
            <div class="col-sm-4">
                <label for="DCBanco" style="font-size: 13px;">CUENTA A LA QUE SE VA ACREDITAR LOS ABONOS</label>
                <select class="form-control input-xs" name="DCBanco" id="DCBanco" onchange="DCBanco">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
    </div>


    <div class="row" style="margin:5px; padding-top:10px">
        <div id="miPanel">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-4">
                        <img id="miLogo" src="" class="img-fluid" width="80%" height="10%">
                    </div>
                    <div class="col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="MBFechaI" class="control-label">Facturación</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="date" name="MBFechaI" id="MBFechaI"
                                    class="form-control input-xs validateDate" onchange="" title="Facturación"
                                    value="<?php echo date('Y-m-d') ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="CheqMatricula" class="control-label">
                                    <input type="checkbox" name="CheqMatricula" id="CheqMatricula">
                                    Generar Matrícula </label>
                            </div>
                            <div class="col-xs-1">
                                <button class="btn" onclick="Command2()"><i class="fa fa-close"></i></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                            </div>
                            <div class="col-xs-6">
                                <label for="CheqPend" class="control-label">
                                    <input type="checkbox" name="CheqPend" id="CheqPend"> Sin Deuda
                                    Pendiente
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="MBFechaF" class="control-label">Tope de pago</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="date" name="MBFechaF" id="MBFechaF"
                                    class="form-control input-xs validateDate" onchange="" title="Tope de pago"
                                    value="<?php echo date('Y-m-d') ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="CheqSat" class="control-label">
                                    <input type="checkbox" name="CheqSat" id="CheqSat" style="display:none"> Generar
                                    Matrícula
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row" style="margin:20px;">
                    <textarea class="form-control" id="TxtFile" rows="10"></textarea>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        Form_Activate();
        $('#CheqRangos').click(function () {
            $("#DCGrupoI, #DCGrupoF").toggle($(this).is(":checked") ? true : false);
        });

        Select_DCEntidad();
        Select_Banco();
    });

    function Select_Banco() {

        $("#miPanel").css("background-color", "#5bc0de");
        $("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");

        var selectElement = $('#DCEntidad');
        selectElement.change(function () {

            console.log('Valor: ' + selectElement.val());
            var index = selectElement.val();

            switch (index) {
                case 'BANCO BOLIVARIANO':
                    console.log(index);
                    $("#miPanel").css("background-color", "#73ecf5");
                    $("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");
                    break;
                case 'BANCO INTERNACIONAL':
                    $("#miPanel").css("background-color", "#f2be82");
                    $("#miLogo").attr("src", "../../img/png/logoBancoInternacional.png");
                    break;
                case 'BANCO PICHINCHA':
                    $("#miPanel").css("background-color", "#ffed93");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPichincha.png");
                    break;
                case 'BANCO DEL PACIFICO':
                    $("#miPanel").css("background-color", "#81dbff");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    break;
                case 'BANCO DEL PACIFICO INTERMATICO':
                    $("#miPanel").css("background-color", "#81dbff");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    break;
                case 'BANCO PRODUBANCO':
                    $("#miPanel").css("background-color", "#2cd26e");
                    $("#miLogo").attr("src", "../../img/png/logoBancoProdubanco.png");
                    break;
                case 'BANCO GENERAL RUMINAHUI':
                    $("#miPanel").css("background-color", "#6b8ee7");
                    $("#miLogo").attr("src", "../../img/png/logoBancoGnrlRuminahui.png");
                    break;
                case 'BANCO GUAYAQUIL':
                    $("#miPanel").css("background-color", "#f395cf");
                    $("#miLogo").attr("src", "../../img/png/logoBancoGuayaquil.png");
                    break;
            }
        });
    }


    function Select_DCEntidad() {

        $('#DCEntidad').select2({
            placeholder: 'Entidad Financiera',
            ajax: {
                url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCEntidad=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    console.log(data.length);
                    return {
                        results: data.map(item => ({
                            id: item.Descripcion,
                            text: item.Descripcion
                        }))
                    };
                },
                cache: true
            }
        });
    }

    function Form_Activate() {
        /**$.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxC.php?Form_Activate=true',
            dataType: 'json',
            success: function (data) {
                var DCGrupoI = $("#DCGrupoI");
                for (var indice in data.AdoCtaBanco) {
                    DCGrupoI.append('<option value="' + data.AdoCtaBanco[indice].NomCuenta + ' ">' + data.AdoCtaBanco[indice].NomCuenta + '</option>');
                }

                var DCGrupoF = $("#DCGrupoF"); 
                for (var indice in data.AdoClientes) {
                    DCGrupoF.append('<option value="' + data.AdoClientes[indice].Codigo + ' ">' + data.AdoClientes[indice].Cajero + '</option>');
                }
            }
        });*/

        var parametros = {
            'MBFechaI': $("#MBFechaI").val(),
            'MBFechaF': $("#MBFechaF").val()
        }
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?Form_Activate=true',
            dataType: 'json',
            data: { parametros, parametros },
            success: function (data) {

            }
        });

    }
</script>