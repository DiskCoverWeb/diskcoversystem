<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/reset.min.css">
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/arbol_bodega.css">
  <script src="../../dist/js/arbol_bodegas/prefixfree.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
  	cargar_bodegas()
  	pedidos();
  	lineas_pedidos();
  


  
    $('#txt_codigo').on('select2:select', function (e) {
      var data = e.params.data.data;

      $('#txt_id').val(data.ID); // display the selected text
      $('#txt_fecha').val(formatoDate(data.Fecha_P.date)); // display the selected text
      $('#txt_ci').val(data.CI_RUC); // save selected id to input
      $('#txt_donante').val(data.Cliente); // save selected id to input
      $('#txt_tipo').val(data.Cod_Ejec); // save selected id to input
      var cantidad = parseFloat(data.TOTAL).toFixed(2)
      $('#txt_cant').val(cantidad); // save selected id to input
      if(cantidad>=500)
      {
      	 $('#pnl_alertas').css('display','initial');
      	 $('#txt_cant').css('color','green');
      	 $('#img_alto_stock').attr('src','../../img/gif/alto_stock_titi.gif');
      }else
      {
      	$('#txt_cant').css('color','#000000');
      	 $('#img_alto_stock').attr('src','../../img/png/alto_stock.png');
      }
      $('#txt_comentario').val(data.Mensaje); // save selected id to input
      $('#txt_ejec').val(data.Cod_Ejec); // save selected id to input

      $('#txt_contra_cta').val(data.Cta_Haber); // save selected id to input
      $('#txt_cta_inv').val(data.Cta_Debe); // save selected id to input

      $('#txt_codigo_p').val(data.CodigoP)      
      $('#txt_TipoSubMod').val(data.Giro_No)
      if(data.Giro_No!='R')
      {
      	$('#btn_cantidad').prop('disabled',false);
      	$('#txt_producto').prop('disabled',false);
      }else
      {
      	$('#btn_cantidad').prop('disabled',true);
      	$('#txt_producto').prop('disabled',true);
      	$('#modal_producto_2').modal('show');
      }

      if(data.Cod_R=='0')
      {
      	$('#img_estado').attr('src','../../img/png/bloqueo.png');
      }else
      {

      	$('#img_estado').attr('src','../../img/png/aprobar.png');
      }
      $('#txt_temperatura').val(data.Porc_C); // save selected id to input
      $('#ddl_alimento').append($('<option>',{value: data.Cod_C, text:data.Proceso,selected: true }));

      lineas_pedidos();
      productos_asignados();
      if($('#txt_cod_bodega').val()!='.' && $('#txt_cod_bodega').val()!='')
      {
	    	contenido_bodega();
      }
      
      // console.log(data);
    });


  })

  function cargar_nombre_bodega(nombre,cod)
  {

  	$('#txt_bodega_title').text();
  	$('#txt_bodega_title').text(nombre);
  	$('#txt_cod_bodega').val(cod);
  	if(cod!='.')
  	{
  		contenido_bodega();
  	}

  	// console.log(nombre)
  }

  function pedidos(){
  $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    // width:'90%',
    ajax: {
      url:   '../controlador/inventario/almacenamiento_bodegaC.php?search_contabilizado=true',          
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        // console.log(data);
        return {
          results: data
        };
      },
      cache: true
    }
  });
}

function lineas_pedidos()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?lineas_pedido=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#lista_pedido').html(data);
	    }
	});

  
}


function cargar_bodegas(nivel=1,padre='')
{
	var parametros = {
		'nivel':nivel,
		'padre':padre,
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?lista_bodegas_arbol=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	// console.log(data);
	    	if(nivel==1)
	    	{
	    	 $('#arbol_bodegas').html(data);
	    	}else
	    	{
	    		 $('#h'+padre).html(data);
	    	}
	    }
	});

  
}

function asignar_bodega()
{
	 id = '';
	 $('.rbl_pedido').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	        id+= checkbox.val()+',';
	    }
	});

	 bodega = $('#txt_cod_bodega').val();

	if(bodega=='.' || bodega =='')
	{
		Swal.fire('Seleccione una bodega','','info');
		return false;
	}
	if(id=='')
	{
		Swal.fire('Seleccione un pedido','','info');
		return false;
	}

	var parametros = {
		'id':id,
		'bodegas':bodega,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?asignar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	
	    	contenido_bodega();
	    	productos_asignados();
	    }
	});
	
}

function desasignar_bodega()
{
	 id = '';
	 $('.rbl_pedido_des').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	        id+= checkbox.val()+',';
	    }
	});

	 bodega = $('#txt_cod_bodega').val();

	if(bodega=='.' || bodega =='')
	{
		Swal.fire('Seleccione una bodega','','info');
		return false;
	}
	if(id=='')
	{
		Swal.fire('Seleccione un pedido','','info');
		return false;
	}

	var parametros = {
		'id':id,
		'bodegas':bodega,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?desasignar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	
	    	contenido_bodega();	    	
	    	productos_asignados();
	    }
	});
	
}

function contenido_bodega()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
		'bodega':$('#txt_cod_bodega').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#contenido_bodega').html(data);
	    }
	});

}

function productos_asignados()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?productos_asignados=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#tbl_asignados').html(data);
	    }
	});

}

function  eliminar_bodega(id)
{
	var parametros = {
		'id':id,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?eliminar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	 	
	    	productos_asignados();
	    	$('#contenido_bodega').html('');
	    	$('#txt_cod_bodega').val('.');
	    	$('#txt_bodega_title').text('Ruta: ');
	    }
	});

}

function cargar_info(codigo)
{
	var parametros = {
		'codigo':codigo,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_info=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#pnl_contenido').html(data)
	    }
	});

}

async function buscar_ruta()
{  
	// if($('#txt_cod_bodega').val()!='' && $('#txt_cod_bodega').val()!='.' ){cargar_bodegas();}

	 codigo = $('#txt_cod_lugar').val();
	 pasos = codigo.split('.');	 
	 let ruta = '';
	 let bodega = '';
	 for (var i=0 ; i <= pasos.length ; i++) {
	 		bodega+=pasos[i]+'_';
			let pasos2 = bodega.substring(0 ,bodega.length-1);
			$('#c'+pasos2).prop('checked',false);
    	$('#c_'+pasos2).click();
			await sleep(3000);
			console.log('espera');
	 }
	// await pasos.forEach(function(item,i){
	// 		bodega+=item+'_';
	// 		let pasos2 = bodega.substring(0 ,bodega.length-1);
  //   	$('#c_'+pasos2).click();
	// 		await sleep(7000);
	// 		console.log('espera');
	//  })
	 // var parametros = {
	// 		'codigo':codigo,
	// 	}
	// 	$.ajax({
	// 	    type: "POST",
	 //       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_lugar=true',
	// 	     data:{parametros:parametros},
	 //       dataType:'json',
	// 	    success: function(data)
	// 	    {
	// 	    	$('#txt_bodega_title').text('Ruta:'+data);
	// 	    	$('#txt_cod_bodega').val(codigo);

	// 	    }
	// 	});
}


  
 
</script>

 <div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="guardar()">
				<img src="../../img/png/grabar.png">
			</button>
		</div>  	
  </div>
</div>
<div class="row">
	<div class="col-sm-12">		
		<div class="box">
			<form id="form_correos">
			<div class="box-body" style="background: antiquewhite;">					
				<div class="row">						
					<div class="col-sm-2">					
							<b>Fecha de Ingreso:</b>
							<input type="hidden" name="txt_id" id="txt_id">
		          <input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" readonly>		
		      </div>						
					<div class="col-sm-3">
			       	<b>Codigo de Ingreso:</b>
			       	<input type="hidden" class="form-control input-xs" id="txt_codigo_p" name="txt_codigo_p" readonly>
			        <select class="form-control input-xs" id="txt_codigo" name="txt_codigo">
			           	<option>Seleccione</option>
			        </select>
			    </div>
					<div class="col-sm-3">
	            <b>PROVEEDOR / DONANTE</b>								
								<input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly>
					</div>
					<div class="col-sm-2 text-right">
						 	<b>CANTIDAD:</b>
             	<input type="" class="form-control input-xs" id="txt_cant" name="txt_cant" readonly>	
					</div>
					<div class="col-sm-2 text-right">
						 	<b>FECHA EXPIRACION:</b>
             	<input type="date" class="form-control input-xs" id="txt_fecha_exp" name="txt_fecha_exp" readonly>	
					</div>
					<!-- <div class="col-sm-12 text-right">
						<div class="row">
							<div class="col-sm-10"></div>
								<div class="col-sm-2">
										<b>ALIMENTO RECIBIDO:</b>
										<select class=" form-control input-xs form-select" id="ddl_alimento" name="ddl_alimento" disabled>
		               		<option value="">Seleccione Alimento</option>
		               	</select>										
								</div>
						</div>								
						
					</div> -->
				</div>
				<div class="row">
					<div class="col-sm-9">
						Codigo de paquete
						<input type="" class="form-control input-xs" id="txt_paquete" name="txt_paquete" readonly>	

					</div>
					<div class="col-sm-3 text-right" id="pnl_alertas" style="display:none;">
						<button class="btn btn-default" type="button">
							<img id="img_alto_stock"  src="../../img/gif/alto_stock_titi.gif" style="width:48px">
							<br>
							Alto Stock
						</button>
					<button class="btn btn-default" type="button">
							<img id="img_por_expirar" src="../../img/gif/expired_titi.gif" style="width:48px">
							<br>
							Por Expirar
						</button>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm-3">
						<ul class="tree_bod" id="arbol_bodegas">
						</ul>
					</div>
					<div class="col-sm-9">
						<div class="row">
							<div class="text-center col-sm-12">
							</div>
							<div class="col-md-6">

								<div class="box box-primary direct-chat direct-chat-primary">	
										<div class="box-header">
													<h3 class="box-title">Articulos de pedido</h3>
										</div>									
										<div class="box-body">
												<div class="direct-chat-messages">	
													<ul class="list-group list-group-flush" id="lista_pedido"></ul>											
												</div>
												<div class="direct-chat-contacts">
													
													<ul class="contacts-list">
														<button type="button" class="btn btn-box-tool pull-right" data-toggle="tooltip" title="" data-widget="chat-pane-toggle">
																	<i class="fa fa-times"></i>
															</button>
															<li id="pnl_contenido">
													  		 ssssss
															</li>
													</ul>
												</div>

										</div>
								</div>
						</div>
							<div class="col-sm-1 text-center">
								<button class="btn btn-primary" type="button" onclick="asignar_bodega()"><i class="fa fa-arrow-right"></i></button>	
								<br>
								<button class="btn btn-primary" type="button" onclick="desasignar_bodega()"><i class="fa fa-arrow-left"></i></button>								
							</div>
							<div class="col-sm-5">
								Codigo de lugar
								<input type="" class="form-control input-xs" id="txt_cod_lugar" name="txt_cod_lugar" onblur="buscar_ruta()">	
								<div class="box box-success">
										<div class="box-header">
											<h3 class="box-title" id="txt_bodega_title">Ruta: </h3>
											<input type="hidden" class="form-control input-xs" id="txt_cod_bodega" name="txt_cod_bodega" readonly>
										</div>
										<div class="box-body">
											<ul class="nav nav-pills nav-stacked" id="contenido_bodega"></ul>						
										</div>
									</div>								
							</div>
						</div>
						
						
					</div>
				</div>
				<div class="row">
						<div class="col-sm-12">
							<table class="table-sm table-hover table">
								<thead>
									<th><b>Producto</b></th>
									<th><b>Ruta</b></th>
									<th></th>
								</thead>
								<tbody id="tbl_asignados">
									<tr>
										<td colspan="3">Productos asignados</td>
									</tr>
								</tbody>
							</table>
						</div>					
				</div>
			</div>
			</form>
		</div>	
	</div>
</div>


 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
