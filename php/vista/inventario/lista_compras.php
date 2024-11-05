<script type="text/javascript">
$(document).ready(function () {
    pedidos_contratista();

})
	
function pedidos_contratista()
{     
  var parametros = 
  {
    'fecha': $('#txt_fecha').val(),
    'query': $('#txt_query').val(),
  }
  $.ajax({
      url:   '../controlador/inventario/lista_comprasC.php?pedidos_compra_contratista=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
         $('#tbl_body').html(response);                     
      }
  });
}

function imprimir_pdf(orden)
{
	window.open('../controlador/inventario/lista_comprasC.php?imprimir_pdf=true&orden_pdf='+orden,'_blank');
}
function imprimir_excel(orden)
{
	window.open('../controlador/inventario/lista_comprasC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
}

</script>
<section class="content">
	<div class="row">		
		<div class="col-sm-12">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-4">
							<b>Contratista</b>
							<input type="text" class="form-control input-sm" name="txt_query" id="txt_query">
						</div>
						<div class="col-sm-2">
							<b>Fecha Solicitud</b>
							<input type="date" class="form-control input-sm" name="txt_fecha" id="txt_fecha">
						</div>
						<div class="col-sm-2">
							<br>
							<button class="btn btn-primary btn-sm" onclick="pedidos_contratista()"><i class="fa fa-search"></i>Buscar</button>
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
					<th>Presupuesto</th>
					<th></th>
				</thead>
				<tbody id="tbl_body">
					
				</tbody>
			</table>
							
		</div>
	</div>
</section>