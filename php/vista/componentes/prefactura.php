<!-- INICIO MODULO PRE-FACTURA -->
<!-- TODO DEFINIR TITLE -->
<?php define('cantidadProductoPreFacturar', 3); ?>
<style type="text/css">
  .check-group-xs{
    padding: 3px 6px !important;
    font-size: 5px !important;
  }
  .padding-3{
    padding: 3px !important;
  }
</style>
<div class="col-sm-2">
  <a href="#" title="Agregar Pre-Factura"  class="btn btn-default" onclick="OpenModalPreFactura()">
    <img src="../../img/png/doc-green.png" width="25" height="30">
  </a>
</div>
<div id="myModalPreFactura" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Registrar Pre-Factura</h4>
      </div>
      <div class="modal-body">
        <form role="form">
          <div class="box-body">
            <?php $tabindex=55; 
            for ($prod = 1; $prod<=cantidadProductoPreFacturar; $prod++){  ?>
              <div class="row">
                <div class="col-sm-4  no-padding">
                  <div class="input-group">
                    <span class="input-group-addon input-xs check-group-xs">
                      <input type="checkbox" name="PFcheckProducto<?php echo $prod ?>" tabindex="<?php echo $tabindex++ ?>">
                    </span>
                    <label for="PFcheckProducto<?php echo $prod ?>" class="form-control  input-xs">Producto <?php echo $prod ?>: </label>
                  </div>
                </div>
                <div class="col-sm-8 no-padding">
                  <select class="form-control input-xs" id="PFselectProducto<?php echo $prod ?>" name="PFselectProducto<?php echo $prod ?>" tabindex="<?php echo $tabindex++ ?>">
                    <option value="">Seleccione un producto</option>
                  </select>
                </div>
              </div>
                <div class="row">
                <div class="col-md-3 col-sm-offset-1 padding-3">
                  <div class="form-group bg-success">
                    <label for="PFfechaInicial">Fecha Inic.</label>
                    <input type="date" name="PFfechaInicial" id="PFfechaInicial" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>" >
                  </div>
                </div>
                <div class="col-md-2 padding-3">
                  <div class="form-group bg-success">
                    <label for="PFcantidad">Cant.</label>
                    <input type="tel" name="PFcantidad" id="PFcantidad" class="form-control input-xs inputNumero" placeholder="0" >
                  </div>
                </div>
                <div class="col-md-2 padding-3">
                  <div class="form-group bg-success">
                    <label for="PFvalor">Valor</label>
                    <input type="tel" name="PFvalor" id="PFvalor" class="form-control input-xs inputMoneda" placeholder="0.00" >
                  </div>
                </div>
                <div class="col-md-2 padding-3">
                  <div class="form-group bg-success">
                    <label for="PFdescuento">Descuento</label>
                    <input type="tel" name="PFdescuento" id="PFdescuento" class="form-control input-xs inputMoneda" placeholder="0.00" >
                  </div>
                </div>
                <div class="col-md-2 padding-3">
                  <div class="form-group bg-success">
                    <label for="PFdescuento2">Descuento 2</label>
                    <input type="tel" name="PFdescuento2" id="PFdescuento2" class="form-control input-xs inputMoneda" placeholder="0.00" >
                  </div>
                </div>
              </div>
              <hr>
            <?php } ?>
          </div>
        </form>
      </div>
      <div class="modal-footer"> <!--TODO acomodar botones -->
          <img role="button" src="../../img/png/grabar.png" width="25" height="30">
          <img role="button" src="../../img/png/delete_file.png" width="25" height="30">
          <img role="button" data-dismiss="modal" src="../../img/png/salire.png" width="25" height="30">

      </div>
    </div>
  </div>
</div>
<!-- FIN MODULO PRE-FACTURA --> <!-- TODO CAMBIAR DATE POR DIA -->
<script type="text/javascript" src="../../dist/js/pages/preFactura.js?<?php echo date('is') ?>"></script>
