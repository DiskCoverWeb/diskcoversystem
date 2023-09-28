<?php date_default_timezone_set('America/Guayaquil'); ?>
<script type="text/javascript">
  $(document).ready(function () {
  	 window.addEventListener("message", function(event) {
            if (event.data === "closeModal") {
                autocoplet_ingreso();
            }
        });    
  	autocoplet_alimento();
  	autocoplet_ingreso();
  	autocoplet_ingreso_donante();
  	cargar_datos();
  })

  function guardar()
  {
  	var donante = $('#txt_donante').val();
  	var tempe = $('#txt_temperatura').val();
  	var tipo = $('#ddl_alimento_text').val();
  	var can = $('#txt_cant').val();
  	var cod = $('#txt_codigo').val();
  	if(donante=='' || tempe=='' || tipo=='' || can =='' || cod=='' || can ==0)
  	{
  		Swal.fire('Ingrese todos los datos','','info')
  		return false;
  	}

  	 var parametros = $('#form_correos').serialize();
  	 parametros+='&ddl_ingreso='+$('#txt_donante').val();
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?guardar=true',
	      data:parametros,
          dataType:'json',
	      success: function(data)
	      {
	      	if(data==1)
	      	{
	      		Swal.fire('Alimento Recibido Guardado','','success').then(function()
	      			{
	      				limpiar();
	      				cargar_datos();
	      			});
	      	}
	      
	      }
	  });
  }


  function limpiar()
  {
  	$('#txt_donante').val('');
  	$('#txt_tipo').val('');
  	$('#txt_temperatura').val('');
  	$('#ddl_alimento_text').val('');
  	$('#txt_cant').val('');
  	$('#txt_codigo').val('');
  	$('#txt_ci').val('');
  	$('#txt_comentario').val('');

  	// modales
  	$('#ddl_ingreso').val(null).trigger('change');
  	$('#txt_temperatura2').val('');
  	$('#txt_cantidad2').val('');
  }

  function autocoplet_alimento()
  {
  	 // var parametros = $('#form_correos').serialize();
  	  $.ajax({
	    type: "POST",
       url:   '../controlador/inventario/alimentos_recibidosC.php?alimentos=true',
	    // data:{parametros:parametros},
        dataType:'json',
	    success: function(data)
	    {
	    	console.log(data);
	    	option = '';
	    	opt = '<option>Tipo de donacion</option>';
	    	data.forEach(function(item,i){
	    		// console.log(item);
	    		option+= '<div class="col-md-6 col-sm-6">'+
											'<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+item.picture+'.png" onclick="cambiar_tipo_alimento(\''+item.id+'\',\''+item.text+'\')"></button><br>'+
											'<b>'+item.text+'</b>'+
										'</div>';
	    		// option+='<option value="'+item.Codigo+'">'+item.Cliente+'</option>';
					opt+='<option value="'+item.id+'">'+item.text+'</option>';
	    	})	 
	    	$('#pnl_tipo_alimento').html(option);   
	    	$('#ddl_tipo_alimento').html(opt);     
	    }
	});
  }

//  function autocoplet_alimento(){
//   $('#ddl_alimento').select2({
//     placeholder: 'Seleccione una beneficiario',
//     // width:'90%',
//     ajax: {
//       url:   '../controlador/inventario/alimentos_recibidosC.php?alimentos=true',
//       dataType: 'json',
//       delay: 250,
//       processResults: function (data) {
//         // console.log(data);
//         return {
//           results: data
//         };
//       },
//       cache: true
//     }
//   });
// }

// function autocoplet_ingreso()
//   {
//   	 // var parametros = $('#form_correos').serialize();
//   	  $.ajax({
// 	    type: "POST",
//       	url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso=true',
// 	    // data:{parametros:parametros},
//         dataType:'json',
// 	    success: function(data)
// 	    {
// 	    	console.log(data);
// 	    	option = '';
// 	    	data.forEach(function(item,i){
// 	    		console.log(item);
// 	    		option+='<option value="'+item.Codigo+'">'+item.Cliente+'</option>';
// 	    	})	 
// 	    	$('#ddl_ingreso').html(option);     
// 	    }
// 	});
//   }

function autocoplet_ingreso(){
  $('#ddl_ingreso').select2({
    placeholder: 'Seleccione',
    width:'100%',
    ajax: {
     url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso2=true',
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
function autocoplet_ingreso_donante(){
  $('#txt_donante').select2({
    placeholder: 'Seleccione',
    // width:'100%',
    ajax: {
     url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso2=true',
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

  function nuevo_proveedor()
  {
  	 $('#myModal_provedor').modal('show');
  	 $('#FProveedor').contents().find('body').css('background-color', 'antiquewhite');

  }
  function option_select()
  {
	  	var id = $('#ddl_ingreso').val();
	  	if(id==null || id=='')
	  	{
	  		return false;
	  	}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?datos_ingreso=true',
		    data:{id:id},
	        dataType:'json',
		    success: function(data)
		    {

      		$('#txt_donante').append($('<option>',{value: data.Codigo, text:data.Cliente,selected: true }));
      		$('#txt_donante').prop('disabled',true);
		    	// console.log(data);
		    	$('#txt_codigo').val(data.Cod_Ejec)
		    	$('#txt_ci').val(data.CI_RUC)
		    	// $('#txt_donante').val(data.Cliente)
		    	$('#txt_tipo').val(data.Actividad)
		    	$('#modal_proveedor').modal('hide');
		    	generar_codigo();
		    }
		});  	
  }

  function option_select2()
  {
	  	var id = $('#txt_donante').val();
	  	if(id==null || id=='')
	  	{
	  		return false;
	  	}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?datos_ingreso=true',
		    data:{id:id},
	        dataType:'json',
		    success: function(data)
		    {

      		$('#txt_donante').append($('<option>',{value: data.Codigo, text:data.Cliente,selected: true }));
      		$('#txt_donante').prop('disabled',true);
		    	// console.log(data);
		    	$('#txt_codigo').val(data.Cod_Ejec)
		    	$('#txt_ci').val(data.CI_RUC)
		    	// $('#txt_donante').val(data.Cliente)
		    	$('#txt_tipo').val(data.Actividad)
		    	$('#modal_proveedor').modal('hide');
		    	generar_codigo();
		    }
		});  	
  }

  function generar_codigo()
  {
  	 var cod = $('#txt_codigo').val();
  	 var partes = cod.split('-');
  	 cod = partes[0];
  	 var fecha = $('#txt_fecha').val();
  	 if(fecha!='')
  	 {
	  	 var fecha_formato = new Date(fecha);
	  	 // $('#txt_codigo').val('');
	  	 year = fecha_formato.getFullYear().toString();
	  	 mes = fecha_formato.getMonth()+1;
	  	 if(mes<10)
	  	 {
	  	 	mes = '0'+mes; 
	  	 }
	  	 day = fecha_formato.getDate()+1
	  	 if(day<10)
	  	 {
	  	 	day = '0'+day; 
	  	 }
	  	 // console.log(year.substr(2,4))
	  	 $('#txt_codigo').val(cod+'-'+year.substr(2,4)+''+mes+''+day)
	  	 autoincrementable();
  		}
  }
  function autoincrementable(){
  		parametros = 
  		{
  			'fecha':$('#txt_fecha').val(),
  		}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?autoincrementable=true',
		    data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	console.log(data);
		    	var cod = $('#txt_codigo').val();
		    	$('#txt_codigo').val(cod+'-'+data)
		    	
		    }
		});  	
  }

  function cargar_datos(){
  		parametros = 
  		{
  			'fecha':$('#txt_fecha_b').val(),
  			'query':$('#txt_query').val(),
  		}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?cargar_datos=true',
		    data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_body').html(data);
		    	console.log(data);
		    	// var cod = $('#txt_codigo').val();
		    	// $('#txt_codigo').val(cod+'-'+data)
		    	
		    }
		});  	
  }

  function show_proveedor()
  {
  	$('#modal_proveedor').modal('show');
  }
  function show_cantidad()
  {
  	$('#modal_cantidad').modal('show');
  }
  function show_temperatura()
  {
  	$('#modal_temperatura').modal('show');
  }

  function show_tipo_donacion()
  {
  	$('#modal_tipo_donacion').modal('show');
  }
   function ocultar_comentario()
  {

  	 var cbx = $('input[type=radio][name=cbx_estado_tran]:checked').val();
  	 if(cbx=='0')
  	 {
  	 	 $('#pnl_comentario').css('display','block');
  	 }else
  	 {
  	 	 $('#pnl_comentario').css('display','none');
  	 }
  	 console.log(cbx);
  }
  function cambiar_cantidad()
  {
  	var can = $('#txt_cantidad2').val();
  	$('#txt_cant').val(can);
  	$('#modal_cantidad').modal('hide');
  }
  function cambiar_temperatura()
  {
  	var can = $('#txt_temperatura2').val();
  	$('#txt_temperatura').val(can);
  	$('#modal_temperatura').modal('hide');
  }

  function cambiar_tipo_alimento(cod,texto)
  {

  	$('#ddl_alimento_text').val(texto);
  	$('#ddl_alimento').val(cod);
  	$('#ddl_tipo_alimento').val(cod)
  	$('#modal_tipo_donacion').modal('hide');
  }
  function eliminar_pedido(ID)
  {
  	 Swal.fire({
       title: 'Esta seguro?',
       text: "Esta usted seguro de que quiere eliminar este registro!",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {
        eliminar(ID);
       }
     })

  }

  function eliminar(ID)
  {
  	
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?eliminar_pedido=true',
		      data:{ID:ID},
	        dataType:'json',
		    success: function(data)
		    {
		    	if(data ==1)
		    	{
		    		Swal.fire('Registro eliminado','','info');
		    		cargar_datos();
		    	}    	
		    }
		});  	

  }

  function limpiar_donante()
  {  	
  	$('#ddl_ingreso').val(null).trigger('change');
  	$('#txt_donante').val(null).trigger('change');
  	$('#txt_donante').prop('disabled',false);
  }

  function tipo_seleccion()
  {
  	 var nom = $('#ddl_tipo_alimento option:selected').text();
  	 var cod = $('#ddl_tipo_alimento').val();

  	$('#ddl_alimento_text').val(nom);
  	$('#ddl_alimento').val(cod);
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
		<div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="nuevo_proveedor()">
				<img src="../../img/png/mostrar.png">
			</button>
		</div>   
    </div>
</div>
<div class="row">
	<div class="col-sm-12">		
		<div class="box">
			<form id="form_correos">
			<div class="box-body" style="background:antiquewhite;">					
				<div class="row">
					<div class="col-sm-12 col-md-8">
						<b>Detalle de ingreso</b>
							<div class="row">
								<div class="col-sm-8">
									<div class="input-group">
											<span class="input-group-btn" style="padding-right: 10px;">
													<button type="button" class="btn btn-default btn-sm btn btn-flat" onclick="show_proveedor()"><img src="../../img/png/donacion2.png"></button>
											</span>
											<b>PROVEEDOR / DONANTE</b>	
											<div class="form-group">
												<div class="col-sm-9">	
												<div class="input-group" style="display:flex;">
				                	<select class=" form-control input-xs form-select" id="txt_donante" name="txt_donante" onchange="option_select2()">
								           		<option value="">Seleccione</option>
								           </select>   	
													<span class="input-group-btn">
														<button type="button" class="btn btn-default btn-xs btn-flat" onclick="limpiar_donante()"><i class="fa fa-close"></i></button>
													</span>
											 </div>

																	

													<!-- <input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly> -->
												</div>
												<div class="col-sm-3">
													<input type="" class="form-control input-xs" id="txt_tipo" name="txt_tipo" readonly>
												</div>
											</div>

									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
											<span class="input-group-btn" style="padding-right: 10px;">
												<button type="button" class="btn btn-default btn-sm" onclick="show_temperatura()"><img src="../../img/png/temperatura2.png"></button>
											</span>
											 <b>TEMPERATURA DE RECEPCION °C:</b>	
											 <div class="input-group">
				                	<input type="" class="form-control input-sm" id="txt_temperatura" name="txt_temperatura">	
													<span class="input-group-addon">°C</span>
											 </div>

									</div>
								</div>								
							</div>
							<div class="row">
								<div class="col-sm-8">									
									<div class="input-group">
											<span class="input-group-btn" style="padding-right: 10px;">
													<button type="button" class="btn btn-default btn-sm" onclick="show_tipo_donacion()"><img src="../../img/png/tipo_donacion.png"></button>
											</span>
												<b>ALIMENTO RECIBIDO:</b>
												<select class="form-control input-sm" id="ddl_tipo_alimento" name="ddl_tipo_alimento" onchange="tipo_seleccion()">
													<option value="">Tipo donacion</option>
												</select>												
												<input type="hidden" class="form-control input-xs" id="ddl_alimento_text" name="ddl_alimento_text" readonly>
												<input type="hidden" class="form-control input-xs" id="ddl_alimento" name="ddl_alimento" readonly>
									</div>
								</div>
								<div class="col-sm-4">									
									<div class="input-group">
											<span class="input-group-btn" style="padding-right: 10px;">
													<button type="button" class="btn btn-default btn-sm" onclick="show_cantidad()"><img src="../../img/png/kilo2.png"></button>
											</span>
												<b>CANTIDAD:</b>
												<input type="" class="form-control input-xs" id="txt_cant" name="txt_cant">	
									</div>
								</div>								
							</div>						
					</div>
					<div class="col-sm-12 col-md-4">
						<div class="col-sm-6 col-md-12">
							<div class="form-group">
									<label for="inputEmail3" class="col-sm-6 control-label">Fecha de Ingreso</label>
									<div class="col-sm-6">
										<input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
									</div>
							</div>		
						</div>
						<div class="col-sm-6 col-md-12">
								<div class="form-group">
								<label for="inputEmail4" class="col-sm-6 control-label">Codigo de Ingreso</label>
									<div class="col-sm-6">									
	                 	<input type="" class="form-control input-xs" id="txt_codigo" name="txt_codigo" readonly>
									</div>
								</div>
						</div>
						<div class="col-sm-6 col-md-12">							
							<div class="form-group">
									<label for="inputEmail3" class="col-sm-6 control-label">RUC / CI</label>
									<div class="col-sm-6">									
	                		<input type="" class="form-control input-xs" id="txt_ci" name="txt_ci" readonly>
									</div>
							</div>
						</div>						
						<div class="col-sm-6 col-md-12">							
								<div class="form-group">
										<label for="inputEmail3" class="col-sm-6 control-label">ESTADO DE TRANSPORTE</label>
										<div class="col-sm-6 text-center">									
		                		 <label style="padding-right: 10px;"><img src="../../img/png/bueno2.png" onclick="ocultar_comentario()"><input type="radio" name="cbx_estado_tran" onclick="ocultar_comentario()" checked value="1"></label>		
		                		 <label style="padding-left: 10px;"><img src="../../img/png/close.png" onclick="ocultar_comentario()"><input type="radio" name="cbx_estado_tran" onclick="ocultar_comentario()" value="0"></label>					
										</div>
								</div>	
						</div>
							<div class="col-sm-12 col-md-12" style="padding-top:5px;display: none;" id="pnl_comentario">
								<div class="form-group">
										<label for="inputEmail3" class="col-sm-3 col-md-6 control-label">COMENTARIO</label>
										<div class="col-sm-9 col-md-6">
										<textarea rows="4" class="form-control" style="resize:none;" id="txt_comentario" name="txt_comentario"></textarea>								
										</div>
								</div>	
							</div>
						</div>	
					</div>

					<hr>
					<div class="row">
						<div class="col-sm-4" style="display:none;">
							<b>Codigo de orden</b>
							<input type="" name="txt_query" id="txt_query" class="form-control input-xs">
						</div>
						<div class="col-sm-2">							
							<b>Fecha</b>
							<input type="date" name="txt_fecha_b" id="txt_fecha_b" class="form-control input-xs">
						</div>
						<div class="col-sm-10 text-right">							
							<br>
							<button type="button" class="btn-sm btn-primary btn" id="" name="" onclick="cargar_datos()"><i class="fa fa-search"></i> Buscar</button>
						</div>
					</div> 
					<br>
					<div class="row">
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<th>Codigo</th>
										<th>Fecha de ingreso</th>
										<th>Donante / Proveedor</th>
										<th>Alimento Recibido </th>
										<th>Cantidad</th>
										<th>Temperatura de ingreso</th>
										<th></th>
									</thead>
									<tbody id="tbl_body">
										<tr></tr>
									</tbody>
								</table>
							</div>							
						</div>						
					</div>
				</div>
			</div>
			</form>
		</div>	
	</div>
</div>


<div id="modal_tipo_donacion" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tipo de donacion</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          	<div class="row text-center" id="pnl_tipo_alimento">
          		<div class="col-md-6 col-sm-6">
									<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/canasta.png"></button><br>
									<b>COMPRAS</b>
							</div>	
          		<div class="col-md-6 col-sm-6">
								<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/salvar.png"></button><br>
								<b>RESCATE</b>
							</div>
          		<div class="col-md-6 col-sm-6">
									<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/donacion3.png"></button><br>
									<b>DONACIÓN</b>
								</div>
          			<div class="col-md-6 col-sm-6">
									<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/produccion.png"></button><br>
									<b>RESCATE PRODUCCIÓN</b>
								</div>
          	</div>
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button> -->
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_proveedor" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Proveedor</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
	          <select class=" form-control input-xs form-select" id="ddl_ingreso" name="ddl_ingreso" onchange="option_select()">
	           		<option value="">Seleccione</option>
	           </select>   					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button> -->
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_temperatura" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Temperatura</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          <b>Temperatura</b>
          <div class="input-group">
						<input type="text" class="form-control" id="txt_temperatura2" name="txt_temperatura2" onblur="cambiar_temperatura()">
						<span class="input-group-addon">°C</span>
					</div>    					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_temperatura()">OK</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_cantidad" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Cantidad</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          <b>Cantidad</b>
          <input type="" name="txt_cantidad2" id="txt_cantidad2" class="form-control" placeholder="0" onblur="cambiar_cantidad()">        					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>