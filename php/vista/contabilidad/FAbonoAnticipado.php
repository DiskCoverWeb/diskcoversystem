<script type="text/javascript"></script>
<script src="../../dist/js/FAbonoAnticipado.js"></script>

<div class="row">
    <div class="col-sm-10">
        <form id="form_abonos" class="row">

            <div class="form-inline col-sm-12">
                <div class="checkbox col-sm-4">
                    <input type="checkbox" id="CheqRecibo">
                    <label for="CheqRecibo">RECIBO CAJA No.</label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="" class="form-control" id="TxtRecibo" value="0">
                </div>
                <div class="form-group col-sm-4">
                    <label for="MBFecha">FECHA</label>
                    <input type="date" class="form-control" id="MBFecha" name="MBFecha"
                        value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="col-sm-12" style="padding-top: 5px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Abono Anticipado</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="DCCliente">Cliente</label>
                                    <select class="form-select form-select-sm" id="DCCliente" name="DCCliente"
                                        style="width: 100%;">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="TxtConcepto">Observación</label>
                                    <textarea class="form-control" id="TxtConcepto" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="DCBanco">Cuenta Contable del Ingreso</label>
                                    <select class="form-select form-select-sm" id="DCBanco" name="DCBanco"
                                        style="width: 100%;">
                                        <option value="">Banco</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="DCCtaAnt">Cuenta Contable de Anticipo</label>
                                    <select class="form-select form-select-sm" id="DCCtaAnt" name="DCCtaAnt"
                                        style="width: 100%;">
                                        <option value="">Banco</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="TextCajaMN" class="col-sm-5 control-label">Caja MN.</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control" id="TextCajaMN" placeholder="00000000" value="0.00" pattern="\d*">
                    </div>
                </div>
                <div class="form-group" id="idLabelPend">
                    <label for="LabelPend" class="col-sm-5 control-label">Saldo Actual</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="LabelPend" placeholder="00000000" value="0.00">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-default btn-block" id="btn_g" onclick="Command1_Click()">
            <img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;
        </button>
        <button class="btn btn-default btn-block" onclick="cerrar_modal()">
            <img src="../../img/png/bloqueo.png"><br>Cancelar
        </button>
    </div>
</div>
