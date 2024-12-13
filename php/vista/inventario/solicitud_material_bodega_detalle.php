<?php $order = ''; if(isset($_GET['orden'])){$order = $_GET['orden']; } ?>
<script type="text/javascript">
orden = '<?php echo $order; ?>';
$(document).ready(function () {
	if(orden!='')
	{
    	pedidos_contratista(orden);
    }

})
	
function pedidos_contratista(orden)
{     
  var parametros = 
  {
    'order': orden,
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_material_bodegaC.php?pedidos_contratista_detalle=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
         $('#tbl_body').html(response.tabla);       
        $('#lbl_contratista').text(response.datos[0]['Cliente']);
		$('#lbl_orden').text(response.datos[0]['Orden_No']); 
		if(response.estado=='.')
		{
			$('#btn_aprobar').css('display','block');
		}else
		{
			$('#btn_comprobante').css('display','block');
		}             
      }
  });
}

function cargar_rubro_linea(id,cc)
{
	var parametros = 
	  {
	    'cc':cc,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?listar_rubro=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	        op = '<option value="">Seleccione rubro</option>';           
	        response.forEach(function(item,i){
	        	op+='<option value="'+item.id+'">'+item.text+'</option>'
	        })       

	        $('#ddl_linea_rubro_'+id).html(op);
	      }
	  });

}

function guardar_linea(id)
{
	if($('#ddl_linea_cc_'+id).val()=='' || $('#ddl_linea_rubro_'+id).val()=='')
	{
		Swal.fire("","Seleccione todos los capos","info")
		return false;
	}
	var parametros = 
	  {
	    'cc':$('#ddl_linea_cc_'+id).val(),
	    'rubro':$('#ddl_linea_rubro_'+id).val(),
	    'ID':id,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?editarCCRubro=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	      	if(response==1)
	      	{
	      		Swal.fire('','Linea Editada','success').then(function(){
	      			pedidos_contratista(orden);	 
	      		})   
	      	}     
	      }
	  });

}

function AprobarSolicitud()
{
	var parametros = 
	  {
	    'order': orden,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?AprobarSolicitud=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {           
	        if(response==1)
	        {
	        	Swal.fire("","Solicitud Aprobada","success").then(function(){
	        		location.reload();
	        	});
	        }   
	      }
	  });
}
function GenerarComprobante()
{
	$('#myModal_espera').modal('show');
	var parametros = 
	  {
	    'order': orden,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?GenerarComprobante=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
					$('#myModal_espera').modal('hide');           
	        if(response.resp==1)
	        {
	        	Swal.fire("Comprobate "+response.com+" Generado:","","success").then(function(){
	        		window.open('../controlador/contabilidad/comproC.php?reporte&comprobante='+response.com+'&TP=CD','_blank')
	        		location.reload();
	        	});
	        }   
	      }
	  });

}

</script>
<section class="content">
	<div class="row">
		<div class="col-sm-4">
			<div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
		         <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
		          <img src="../../img/png/salire.png">
		        </a>
		    </div>
		    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2" style="display:none;" id="btn_aprobar" onclick="AprobarSolicitud()">
		      <button type="button" class="btn btn-default" id="imprimir_pdf" title="Aprobar salida">
		        <img src="../../img/png/aprobar.png" >
		      </button>           
		    </div> 
		    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2" style="display:none;" id="btn_comprobante" onclick="GenerarComprobante()">
		      <button type="button" class="btn btn-default" id="imprimir_pdf" title="Generar comprobante">
		           <img src="../../img/png/grabar.png" >
		      </button>           
		    </div>    			
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<h2>Detalle de solicitud de material</h2>
		</div>
	</div>	
	<div class="row">		
		<div class="col-sm-12">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-4">
							<b>Contratista</b><br>
							<label id="lbl_contratista"></label>
						</div>
						<div class="col-sm-2">
							<b>Numero de Orden</b><br>
							<label id="lbl_orden"></label>
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
					<th>Codigo</th>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Centro de costos</th>
					<th>Rubro</th>
					<th></th>
				</thead>
				<tbody id="tbl_body">
					
				</tbody>
			</table>
							
		</div>
	</div>
</section>