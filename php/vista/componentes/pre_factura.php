<!-- INICIO MODULO PRE-FACTURA -->
<!-- TODO DEFINIR TITLE -->
<div class="col-sm-2">
  <a href="#" title="Agregar Pre-Factura"  class="btn btn-default" onclick="OpenModalPreFactura()">
    <img src="../../img/png/doc-green.png" width="25" height="30">
  </a>
</div>
<div id="myModalPreFactura" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Registrar Pre-Factura</h4>
      </div>
      <div class="modal-body">
        campos
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODULO PRE-FACTURA -->
<script type="text/javascript" src="../../dist/js/pages/preFactura.js"></script>
