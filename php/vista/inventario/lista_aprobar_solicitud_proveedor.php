<script type="text/javascript">
$(document).ready(function () {
    pedidos_contratista();

})
	
function pedidos_contratista()
{     
  var parametros = 
  {
    'fecha': $('#txt_fecha').val(),
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_materialC.php?lista_pedido_aprobacion_solicitados_proveedor=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
         $('#tbl_body').html(response);                     
      }
  });
}

</script>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			ss
		</div>
		<div class="col-sm-12">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-2">
							<input type="date" class="form-control input-sm" name="txt_fecha" id="txt_fecha">
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">		
			<table class="table table-hover">
				<thead>
					<th>Item</th>
					<th>Contratista</th>
					<th>Orden</th>
					<th>Fecha Solicitud</th>
					<th>Fecha Entrega</th>
					<th>Presupuesto</th>
				</thead>
				<tbody id="tbl_body">
					
				</tbody>
			</table>
							
		</div>
	</div>
</section>