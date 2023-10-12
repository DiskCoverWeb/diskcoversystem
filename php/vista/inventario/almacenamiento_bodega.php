<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/reset.min.css">
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/arbol_bodega.css">
  <script src="../../dist/js/arbol_bodegas/prefixfree.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
  	cargar_bodegas()
  	pedidos();
  


  
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
      
      
      // console.log(data);
    });


  })

   function pedidos(){
  $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    // width:'90%',
    ajax: {
      url:   '../controlador/inventario/alimentos_recibidosC.php?search_contabilizado=true',          
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


function cargar_bodegas(nivel=1,padre='')
{
	var parametros = {
		'nivel':nivel,
		'padre':padre,
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/alimentos_recibidosC.php?lista_bodegas_arbol=true',
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
		<!-- <div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="nuevo_proveedor()">
				<img src="../../img/png/mostrar.png">
			</button>
		</div>    -->
    </div>
</div>
<div class="row">
	<div class="col-sm-12">		
		<div class="box">
			<form id="form_correos">
			<div class="box-body" style="background: antiquewhite;">					
				<div class="row">	
					<div class="col-sm-4" id="">
						<ul class="tree_bod" id="arbol_bodegas">
						</ul>











































					</div>				
					<div class="col-sm-4">
						<div class="row" style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								 <b>Fecha de Ingreso:</b>
							</div>
							<div class="col-sm-6">
								<input type="hidden" name="txt_id" id="txt_id">
		            			<input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" onblur="generar_codigo()" readonly>	
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
			                 	<b>Codigo de Ingreso:</b>
			               	</div>							
			               	<div class="col-sm-6">
				                	<input type="hidden" class="form-control input-xs" id="txt_codigo_p" name="txt_codigo_p" readonly>
			                   <select class="form-control input-xs" id="txt_codigo" name="txt_codigo">
			                   	<option>Seleccione</option>
			                   </select>
			                </div>
						</div>						
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								 <b>RUC / CI</b>
							</div>
							<div class="col-sm-6">
	                         	<input type="" class="form-control input-xs" id="txt_ci" name="txt_ci" readonly>								
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
	                           <b>PROVEEDOR / DONANTE</b>								
							</div>
							<div class="col-sm-6">
								<input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly>
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								 <b>TIPO DONANTE:</b>								
							</div>
							<div class="col-sm-6">
								<input type="" class="form-control input-xs" id="txt_tipo" name="txt_tipo" readonly>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>ALIMENTO RECIBIDO:</b>
							</div>
							<div class="col-sm-6">
								<select class=" form-control input-xs form-select" id="ddl_alimento" name="ddl_alimento" disabled>
               		<option value="">Seleccione Alimento</option>
               	</select>								
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								 <b>CANTIDAD:</b>
							</div>
							<div class="col-sm-6">
	                        	 <input type="" class="form-control input-xs" id="txt_cant" name="txt_cant" readonly>	
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>COMENTARIO:</b>
							</div>
							<div class="col-sm-6">
	                <input type="" class="form-control input-xs" id="txt_comentario" name="txt_comentario" readonly>
							</div>
						</div>
						<div class="row" id="panel_serie"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>TEMPERATURA DE RECEPCION °C</b>
							</div>
							<div class="col-sm-6">
	                <input type="text" name="txt_temperatura" id="txt_temperatura" class="form-control input-xs"  readonly>
							</div>
						</div>
						<div class="row" id="panel_serie"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>ESTADO DE TRANSPORTE</b>
							</div>
							<div class="col-sm-6 text-center">
								<img src="" id="img_estado">
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
<script type="text/javascript">
	

  function agregar()
  {
  	
  	var parametros = $("#form_add_producto").serialize();    
    var parametros2 = $("#form_correos").serialize();
       $.ajax({
         data:  parametros2+'&txt_referencia='+$('#txt_referencia').val()+'&txt_referencia2='+$('#txt_referencia2').val(),
         url:   '../controlador/inventario/alimentos_recibidosC.php?guardar_recibido=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 

            // console.log(response);
           if(response.resp==1)
           {
            $('#txt_pedido').val(response.ped);
              Swal.fire({
                type:'success',
                title: 'Agregado a pedido',
                text :'',
              }).then( function() {              		
                   cargar_pedido();              		
              });

            // Swal.fire('','Agregado a pedido.','success');
            limpiar();
            // location.reload();
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });    

    
  }

</script>
