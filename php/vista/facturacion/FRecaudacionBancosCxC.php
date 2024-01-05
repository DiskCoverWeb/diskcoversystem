<style type="text/css">
    .miPanel {
        background-color: #5bc0de;
    }
</style>

<div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#banco1" aria-controls="banco1" role="tab" data-toggle="tab">Banco Bolivariano</a>
        </li>
        <li role="presentation"><a href="#banco2" aria-controls="banco2" role="tab" data-toggle="tab">Banco
                Internacional</a></li>
        <li role="presentation"><a href="#banco3" aria-controls="banco3" role="tab" data-toggle="tab">Banco
                Pichincha</a></li>
        <li role="presentation"><a href="#banco4" aria-controls="banco4" role="tab" data-toggle="tab">Banco Pacifico</a>
        </li>
        <li role="presentation"><a href="#banco4" aria-controls="banco4" role="tab" data-toggle="tab">Banco Pacifico
                (Intermatico)</a></li>
        <li role="presentation"><a href="#banco5" aria-controls="banco5" role="tab" data-toggle="tab">Banco
                Produbanco</a></li>
        <li role="presentation"><a href="#banco6" aria-controls="banco6" role="tab" data-toggle="tab">Banco General
                Ruminahui</a></li>
        <li role="presentation"><a href="#banco7" aria-controls="banco7" role="tab" data-toggle="tab">Banco
                Guayaquil</a></li>
    </ul>

    <div class="row" style="margin:5px; padding-top:10px">
        <div class="col-sm-4 col-xs-12">
            <div class="col">
                <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
                    <img src="../../img/png/salire.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" title="Visualizar" class="btn btn-default" onclick="Visualizar()">
                    <img src="../../img/png/file2.png" width="25" height="30">
                </a>
            </div>

            <div class="col">
                <a href="javascript:void(0)" id="Enviar Rubros" title="Enviar Rubros" class="btn btn-default"
                    onclick="Enviar()">
                    <img src="../../img/png/folder-check.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Recibir Rubos" title="Recibir Rubos" class="btn btn-default"
                    onclick="Recibir()">
                    <img src="../../img/png/iess.png" width="25" height="30">
                </a>
            </div>
        </div>
        <div class="col-sm-8 col-xs-12">
            <div class="row">
                <div class="form-group col-xs-12 col-md-6 padding-all margin-b-1">
                    <div class="col-xs-12">
                        <label for="CheqRangos" class="col control-label" style="font-size: 13px;">
                            <input type="checkbox" name="CheqRangos" id="CheqRangos"> Procesar por Rangos
                            Grupos
                        </label>
                    </div>
                    <div class="col-xs-5">
                        <select class="form-control input-xs" name="DCGrupoI" id="DCGrupoI" onchange="DCGrupoI">
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <div class="col-xs-5">
                        <select class="form-control input-xs" name="DCGrupoF" id="DCGrupoF" onchange="DCGrupoF">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <b style='font-size: 13px;'>ORDEN No.</b>
                    <input type='text' name='TxtOrden' id='' value='0' class='form-control input-xs'>
                </div>

                <div class='col-xs-4'>
                    <label for='DCBanco' style='font-size: 13px;'>CUENTA A LA QUE SE VA ACREDITAR LOS
                        ABONOS</label>
                    <select class="form-control input-xs" name="DCBanco" id="DCBanco" onchange="DCBanco">
                        <option value="">Seleccione</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin:5px; padding-top:10px">
        <div id="miPanel">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-4">
                        <img id="miLogo" src="../../img/png/logotestbanco.png" class="img-fluid" width="80%" height="10%">
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
                                    <input type="checkbox" name="CheqSat" id="CheqSat"> Generar Matrícula
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

        $("#miPanel").css("background-color", "#5bc0de");
        $("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");

        $(".nav-tabs a").click(function () {
            var index = $(this).parent().index();
            switch (index) {
                case 0:
                    console.log(index);
                    $("#miPanel").css("background-color", "#73ecf5");
                    $("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");
                    break;
                case 1:
                    $("#miPanel").css("background-color", "#f2be82");
                    $("#miLogo").attr("src", "../../img/png/logoBancoInternacional.png");
                    break;
                case 2:
                    $("#miPanel").css("background-color", "#ffed93");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPichincha.png");
                    break;
                case 3:
                    $("#miPanel").css("background-color", "#81dbff");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    break;
                case 4:
                    $("#miPanel").css("background-color", "#81dbff");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    break;
                case 5:
                    $("#miPanel").css("background-color", "#2cd26e");
                    $("#miLogo").attr("src", "../../img/png/logoBancoProdubanco.png");
                    break;
                case 6:
                    $("#miPanel").css("background-color", "#6b8ee7");
                    $("#miLogo").attr("src", "../../img/png/logoBancoGnrlRuminahui.png");
                    break;
                case 7:
                    $("#miPanel").css("background-color", "#f395cf");
                    $("#miLogo").attr("src", "../../img/png/logoBancoGuayaquil.png");
                    break;
            }
        });
    });
</script>