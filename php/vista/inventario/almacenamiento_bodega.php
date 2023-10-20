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
      $('#txt_cant').val(parseFloat(data.TOTAL).toFixed(2)); // save selected id to input
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
      if($('#txt_cod_bodega').val()!='.' && $('#txt_cod_bodega').val()!='')
      {
	    	contenido_bodega();
      }
      
      // console.log(data);
    });


  })

  function cargar_nombre_bodega(nombre,cod,nivel)
  {
  	if(nivel==1)
  	{
  		$('#txt_bodega_title').text('Ruta: ')  		
  	}
  	ruta = $('#txt_bodega_title').text();
  	nombre = ruta+'/'+nombre;
  	$('#txt_bodega_title').text(nombre);
  	$('#txt_cod_bodega').val(cod);
  	if(cod!='.')
  	{
  		contenido_bodega();
  	}
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
	    	console.log(data);
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
					<div class="col-sm-4">
	            <b>PROVEEDOR / DONANTE</b>								
								<input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly>
					</div>
					<div class="col-sm-3 text-right">
						 	<b>CANTIDAD:</b>
             	<input type="" class="form-control input-xs" id="txt_cant" name="txt_cant" readonly>	
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
				<hr>
				<div class="row">
					<div class="col-sm-4">
						<ul class="tree_bod" id="arbol_bodegas">
						</ul>
					</div>
					<div class="col-sm-8">
						<div class="row">
							<div class="text-center col-sm-12">
							</div>
							<div class="col-sm-5">
								<div class="box box-success">
										<div class="box-header">
											<h3 class="box-title">Articulos de pedido</h3>
										</div>
										<div class="box-body">
												<ul class="nav nav-pills nav-stacked" id="lista_pedido"></ul>											
										</div>
									</div>
							</div>
							<div class="col-sm-2 text-center">
								<button class="btn btn-primary" type="button" onclick="asignar_bodega()"><i class="fa fa-arrow-right"></i></button>	
								<br>
								<button class="btn btn-primary" type="button" onclick="desasignar_bodega()"><i class="fa fa-arrow-left"></i></button>								
							</div>
							<div class="col-sm-5">
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
			</div>
			</form>
		</div>	
	</div>
</div>


 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
