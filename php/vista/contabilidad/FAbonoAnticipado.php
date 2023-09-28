<? php ?>
<script type="text/javascript"></script>
<script src="../../dist/js/FAbonos.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

	});

</script>
<div class="row">
    <div class="col-sm-10">
        <form id="form_abonos">
            <div class="row">
                <div class="col-sm-6 col-xs-6" style="padding: 0;">
                    <b class="col-sm-7 col-xs-8 control-label" style="font-size: 11.5px; padding-right: 0;">
                        <input type="checkbox" name="CheqRecibo" id="CheqRecibo" checked> RECIBO CAJA No.
                    </b>
                    <div class="col-sm-5 col-xs-4" style="padding: 0;">
                        <input type="text" name="TxtRecibo" id="TxtRecibo" class="form-control input-sm" value="0000000">
                    </div>
                </div>
                <div class="col-sm-6 col-xs-6" style="padding: 0;">
                    <b class="col-sm-6 col-xs-6 control-label" style="padding: 0;">FECHA</b>
                    <div class="col-sm-6 col-xs-6" style="padding: 0;">
                        <input type="date" name="MBFecha" id="MBFecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <b>Cliente</b>
                    <div class="input-group">
                        <select class="form-control input-xs" id="DCCliente" name="DCCliente">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <textarea placeholder="Observacion" rows="2" style="resize: none;" class="form-control input-sm"></textarea>
                    <b>Cuenta Contable del Ingreso</b>
                    <select class="form-control input-sm" id="DCVendedor" name="DCVendedor">
                        <option value="">Banco</option>
                    </select>
                    <b>Cuenta Contable de Anticipo</b>
                    <select class="form-control input-sm" id="DCVendedor" name="DCVendedor">
                        <option value="">Banco</option>
                    </select>
                </div>
                <div class="col-sm-6 col-xs-6">
                </div>
                <div class="col-sm-6 col-xs-6">
                    <div class="row">
                        <label class="col-sm-6 col-xs-6 control-label">Caja MN.</label>
                        <div class="col-sm-6 col-xs-6">
                            <input type="text" name="TextCajaMN" id="TextCajaMN" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-6 col-xs-6 control-label">Saldo Actual</label>
                        <div class="col-sm-6 col-xs-6">
                            <input type="text" name="TextCajaME" id="TextCajaME" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="Cta_Cobrar" id="Cta_Cobrar">
        </form>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-default" id="btn_g" onclick="">
            <img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;
        </button>
        <button class="btn btn-default" onclick="cerrar_modal()">
            <img src="../../img/png/bloqueo.png"><br>Cancelar
        </button>
    </div>
</div>

<script type="text/javascript">

</script>