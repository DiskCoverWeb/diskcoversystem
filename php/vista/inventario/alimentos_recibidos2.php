<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/style_calendar.css">
<script type="text/javascript">
  $(document).ready(function () {
  	 window.addEventListener("message", function(event) {
            if (event.data === "closeModal") {
                autocoplet_ingreso();
            }
        });    
  	autocoplet_alimento();
  	autocoplet_ingreso();
  	pedidos();

  	
  	$('#ddl_producto').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#txt_unidad').val(data[0].Unidad);
      $('#txt_producto').append($('<option>',{value: data[0].Codigo_Inv, text:data[0].Producto,selected: true }));
      
      $('#txt_referencia').val(data[0].Codigo_Inv);
      $('#txt_grupo').val(data[0].Item_Banco);
      $('#txt_costo').val(data[0].PVP);
      $('#txt_cta_inv').val(data[0].Cta_Inventario);
      $('#txt_TipoSubMod').val(data[0].TDP);
      $('#txt_producto').prop('disabled',true);
      $('#modal_producto').modal('hide');

      primera_vez = $('#txt_primera_vez').val();

      if(data[0].TDP=='R')
      {
	      setTimeout(() => {  
	      		$('#txt_titulo_mod').text(data[0].Producto);
	      		if(primera_vez!=1)
	      		{
			      	$('#modal_calendar').modal('show');
			      }else{
			      	$('#modal_producto_2').modal('show');
			      }
	    	}, 1000);     	
	      
      }else{
      	$('#txt_TipoSubMod').val('.')
      }
      costeo(data[0].Codigo_Inv);
    });

   $('#txt_producto').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#txt_unidad').val(data[0].Unidad);
      $('#txt_producto').append($('<option>',{value: data[0].Codigo_Inv, text:data[0].Producto,selected: true }));
      
      $('#txt_referencia').val(data[0].Codigo_Inv);
      $('#txt_grupo').val(data[0].Item_Banco);
      $('#txt_costo').val(data[0].PVP);
      $('#txt_cta_inv').val(data[0].Cta_Inventario);
      $('#txt_TipoSubMod').val(data[0].TDP);
      $('#txt_producto').prop('disabled',true);
      $('#modal_producto').modal('hide');

      primera_vez = $('#txt_primera_vez').val();

      if(data[0].TDP=='R')
      {
	      setTimeout(() => {  
	      		$('#txt_titulo_mod').text(data[0].Producto);
			      if(primera_vez!=1)
	      		{
			      	$('#modal_calendar').modal('show');
			      }else{
			      	$('#modal_producto_2').modal('show');
			      }
	    	}, 1000);     	
	      
      }
      costeo(data[0].Codigo_Inv);
    });

    $('#txt_codigo').on('select2:select', function (e) {
    	limpiar();
    	limpiar_reciclaje();
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
      // if(data.Giro_No!='R')
      // {
      // 	$('#btn_cantidad').prop('disabled',false);
      // 	$('#txt_producto').prop('disabled',false);
      // }else
      // {
      // 	$('#btn_cantidad').prop('disabled',true);
      // 	$('#txt_producto').prop('disabled',true);
      // 	$('#modal_producto_2').modal('show');
      // }

      if(data.Cod_R=='0' || data.Cod_R=='.')
      {
      	$('#img_estado').attr('src','../../img/png/bloqueo.png');
      }else
      {

      	$('#img_estado').attr('src','../../img/png/aprobar.png');
      }
      $('#txt_temperatura').val(data.Porc_C); // save selected id to input
      $('#ddl_alimento').append($('<option>',{value: data.Cod_C, text:data.Proceso,selected: true }));
      	cargar_sucursales();      
      	cargar_pedido();
   
      	 // $('#pnl_normal').css('display','none');
      	 cargar_pedido2();

         setInterval(function() {
            cargar_pedido();
          }, 5000); 
      // console.log(data);
    });

  })

   function pedidos(){
  $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    // width:'90%',
    ajax: {
      url:   '../controlador/inventario/alimentos_recibidosC.php?search=true',          
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


 function costeo(cta_inv)
  {
  	 var parametros = 
  	 {
  	 	 'cta_inv':cta_inv,
  	 }
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?producto_costo=true',
	      data:{parametros:parametros},
          dataType:'json',
	      success: function(data)
	      {
	      	console.log(data)
	      	if(parseFloat(data.Costo)!=0)
	      	{
	      		$('#txt_costo').val(data.Costo);
	      	}
	      
	      }
	  });
  }


  function guardar()
  {
  		var ingresados_kardex = $('#txt_cant_total').val();
  		var ingresados_pedido = $('#txt_cant_total_pedido').val();

  		var total = $('#txt_cant').val();
      var faltantes = $('#txt_faltante').val();
  		
  		if((parseFloat(ingresados_kardex)+parseFloat(ingresados_pedido))< parseFloat(total))
  		{
  			 Swal.fire('No se ha completa todo el pedido ','Asegurese de que el pedido este completo','info');
  			 return false;
  		}
      if(parseFloat(faltantes)<0)
      {
         Swal.fire('No se pudo guardar ','El ingreso realizado necesita de revision y correccion en sus valores la diferencia debe ser 0','error');
         return false;
      }
  	 var parametros = $('#form_correos').serialize();
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?guardar2=true',
	      data:parametros,
          dataType:'json',
	      success: function(data)
	      {
	      	if(data==1)
	      	{
	      		Swal.fire('Registro Guardado','','success').then(function(){
	      			location.reload();
	      		});
	      	}
	      
	      }
	  });
  }

  function guardar_pedido()
  {
  		var total_ingresado_pedido = $('#txt_cant_total_pedido').val();
  		var total_ingresado_kardex = $('#txt_cant_total').val(); 
  		var total_recibir = $('#txt_cant').val();


  		var cant = $('#txt_cantidad_pedido').val();


  		var produc = $('#ddl_producto2').val();


  		if(cant==0 || cant=='')
  		{
  			Swal.fire('Ingrese una cantidad valida','','info')
  			return false;
  		}  	
  		if(produc==null || produc=='')
  		{
  			Swal.fire('Seleccione un producto','','info')
  			return false;
  		}  	

  		total_final = parseFloat(cant)+parseFloat(total_ingresado_kardex)+parseFloat(total_ingresado_pedido);
  		cant_suge = parseFloat(total_recibir);
  		


  	 if(total_final>cant_suge)
  	 {
  	 	console.log(total_final);
  	 	console.log(cant_suge);
  	 		Swal.fire('La cantidad Ingresada supera a la cantidad registrada','','info');
  	 		return false
  	 }
  	  var parametros = $('#form_correos').serialize();
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?guardar_pedido=true',
	      data:parametros+'&producto_pedido='+$('#ddl_producto2').val()+'&cantidad_pedido='+$('#txt_cantidad_pedido').val()+'&total_pedido='+$('#txt_cant_total_pedido').val(),
          dataType:'json',
	      success: function(data)
	      {	      	
	      		cargar_pedido2();	
	      }
	  });
  }
 function autocoplet_alimento(){
  $('#ddl_alimento').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'100%',
    ajax: {
      url:   '../controlador/inventario/alimentos_recibidosC.php?alimentos=true',
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
function autocoplet_ingreso()
  {
  	 // var parametros = $('#form_correos').serialize();
  	  $.ajax({
	    type: "POST",
      	url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso=true',
	    // data:{parametros:parametros},
        dataType:'json',
	    success: function(data)
	    {
	    	console.log(data);
	    	option = '';
	    	data.forEach(function(item,i){
	    		// console.log(item);
	    		option+='<option value="'+item.Codigo+'">'+item.Cliente+'</option>';
	    	})	 
	    	$('#ddl_ingreso').html(option);     
	    }
	});
  }
// function autocoplet_ingreso(){
//   $('#ddl_ingreso').select2({
//     placeholder: 'Seleccione',
//     // width:'90%',
//     ajax: {
//       url:   '../controlador/inventario/alimentos_recibidosC.php?donante=true',
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

  function nuevo_proveedor()
  {
  	$('#myModal_provedor').modal('show');
  }
  function option_select()
  {
	  	var id = $('#ddl_ingreso').val();
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?datos_ingreso=true',
		    data:{id:id},
	        dataType:'json',
		    success: function(data)
		    {
		    	console.log(data);
		    	$('#txt_codigo').val(data.Cod_Ejec)
		    	$('#txt_ci').val(data.CI_RUC)
		    	$('#txt_donante').val(data.Cliente)
		    	$('#txt_tipo').val(data.Actividad)
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
  function show_panel()
  {
  	 var id = $('#txt_id').val();
  	 var cant_suge = $('#txt_cant').val();
  	 var cant_ing = $('#txt_cantidad').val();
  	 var cant_total = $('#txt_cant_total').val();
  	 var fe_exp = $('#txt_fecha_exp').val();


  	 	var cant_total = $('#txt_cant_total_pedido').val();
  		var cant_total_kardex = $('#txt_cant_total').val();  		

	  	 var producto = $('#txt_producto').val();
	  	 // console.log(producto);
		   	if(producto=='' || fe_exp=='' || cant_ing=='' || cant_ing==0)
		  	 {
		  	 	Swal.fire('Ingrese todo los datos','','info');
		  	 		return false
		  	 }


  		total_final = (parseFloat(cant_ing)+parseFloat(cant_total_kardex)+parseFloat(cant_total));
  		cant_suge = parseFloat(cant_suge);
	   if($('#txt_TipoSubMod').val()!='R')
	   {
  	 	if(total_final >cant_suge)
  	 	{
        console.log(total_final+'-'+cant_suge);
  	 			Swal.fire('La cantidad Ingresada supera a la cantidad registrada','','info');
  	 			return false
  	 	}
  	 }

      var sucur = $('#ddl_sucursales').val();
     if($("#pnl_sucursal").is(":visible")==true && sucur=='')
      {
         Swal.fire('Seleccione una sucursal ','','info');
         return false;
      }
  	 if(id=='')
  	 {
  	 		Swal.fire('Seleccione un registro','','info');
  	 		return false;
  	 }else
  	 {
  	 		agregar();
  	 }
  }

  function show_calendar()
  {
  	$('#modal_calendar').modal('show');
  }
  function show_producto()
  {  	
  		$('#modal_producto').modal('show');
  }
  function show_producto2(id)
  {
  	$('#txt_id_linea_pedido').val(id);
  	$('#modal_producto_2').modal('show');
  }
  function show_cantidad()
  {
  	$('#modal_cantidad').modal('show');
    $("#modal_cantidad #txt_cantidad2").focus();
    // $('#txt_cantidad2').trigger( "focus");
  }

  function cambiar_cantidad()
  {
  	var can = $('#txt_cantidad2').val();
  	$('#txt_cantidad').val(can);
  	$('#modal_cantidad').modal('hide');
  }
   function cambiar_sucursal()
  {
  	var can = $('#ddl_sucursales2').val();
  	$('#ddl_sucursales').val(can);
  	$('#modal_sucursal').modal('hide');
  }

  function ocultar_comentario()
  {

  	 var cbx = $('input[type=radio][name=cbx_evaluacion]:checked').val();
  	 if(cbx=='R')
  	 {
  	 	 $('#pnl_comentario').css('display','block');
  	 }else
  	 {
  	 	 // $('#pnl_comentario').css('display','none');
  	 }
  	 console.log(cbx);
  }

  function limpiar_reciclaje()
  {  	

  	$('#txt_producto').val(null).trigger('change');
  	$('#ddl_producto').val(null).trigger('change');
  	$('#txt_producto').attr('readonly',false);
  	$('#txt_referencia').val('');

  	$('#btn_cantidad').prop('disabled',false)  	
  	$('#txt_producto').prop('disabled',false)


  	$('#txt_TipoSubMod').val('.');
  	$('#txt_grupo').val('');
  	$('#txt_unidad').val('');

  	//reciclaje
  	$('#txt_producto2').val(null).trigger('change');
  	$('#txt_referencia2').val('');
  }

  function limpiar_reciclaje2()
  {  	
  	$('#txt_producto2').val(null).trigger('change');
  	$('#ddl_producto2').val(null).trigger('change');
  	$('#txt_producto2').attr('readonly',false);
  	$('#txt_referencia2').val('');
  }
  function cargar_sucursales()
 	{    
    var parametros = {
        'ruc':$('#txt_ci').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?sucursales=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        op = '<option value="">Seleccione sucursal</option>';
        var sucursal = 0;
        response.forEach(function(item,i){
            sucursal = 1;
            op+="<option value=\""+item.Codigo+"\">"+item.Direccion+"</option>";
        })

        if(sucursal==1)
        {
            $('#pnl_sucursal').css('display','block');
        }else{            
            $('#pnl_sucursal').css('display','none');
        }

        $('#ddl_sucursales').html(op);
        console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

 }

  function cargar_sucursales2()
 	{    
    var parametros = {
        'ruc':$('#txt_ci').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?sucursales=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        op = '<option value="">Seleccione sucursal</option>';
        var sucursal = 0;
        response.forEach(function(item,i){
            sucursal = 1;
            op+="<option value=\""+item.Codigo+"\">"+item.Direccion+"</option>";
        })

        if(sucursal==1)
        {
            $('#pnl_sucursal').css('display','block');
        }else{            
            $('#pnl_sucursal').css('display','none');
        }

        $('#ddl_sucursales2').html(op);
        console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

 }

 function show_sucursal()
 {
 		cargar_sucursales2();
 	 $('#modal_sucursal').modal('show');
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
            <div class="row">
              <div class="col-sm-6" style="padding-top:5px">
                <b>Fecha de Clasificacion</b>                
              </div>
              <div class="col-sm-6">
                <input type="date" name="txt_fecha_cla" id="txt_fecha_cla" value="<?php echo date('Y-m-d'); ?>" class="form-control input-xs" readonly>
              </div>
            </div>						
					</div>
					<div class="col-sm-5">
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
								<div class="row">
									<div class="col-sm-5" style="padding-right:1px">
										  <input type="" class="form-control" id="txt_cant" style="font-size:20px; padding: 3px;" name="txt_cant" readonly value="0">	
									</div>
									<div class="col-sm-7" style="padding-left: 1px;">
										<div class="input-group">
										  <span class="input-group-addon input-xs"><b style="font-size: 18px;">Dif</b></span>											
										  <b><input type="" class="form-control" id="txt_faltante" style="font-size:20px; padding: 3px;" name="txt_faltante" readonly></b>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>COMENTARIO:</b>
							</div>
							<div class="col-sm-6">
                 <textarea id="txt_comentario" name="txt_comentario"disabled class="form-control input-sm"></textarea>
							</div>
						</div>
						<div class="row" id="panel_serie"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>TEMPERATURA RECEPCION °C</b>
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
					<div class="col-sm-3">
						<div class="row">							
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-6">
										<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" checked  value="V" > <img src="../../img/png/smile.png"><br> Conforme</label>											
									</div>
									<div class="col-sm-6">
										<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R">  <img src="../../img/png/sad.png"><br> Inconforme </label>											
									</div>									
								</div>
									<!-- <b>Evaluacion</b><br> -->										
														
							</div>
							<div class="col-sm-12" id="pnl_comentario">
									<b>COMENTARIO DE INGRESO</b>
									<textarea class="form-control input-sm" rows="3" id="txt_comentario2" name="txt_comentario2"></textarea>								
							</div>
						</div>


					<!-- 	<select class=" form-control input-xs form-select" id="ddl_ingreso" name="ddl_ingreso" size="7" onchange="option_select()">
                         	<option value="">Seleccione</option>
                         </select> -->
					</div>
				</div>
				<hr>

				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-4 col-md-3">
									<button type="button" class="btn btn-default" onclick="show_producto()"><img src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo de producto</b></button>							
							</div>
							<div class="col-sm-6 col-md-7">
								<b>Producto</b>
								<div class="input-group" style="display:flex;" id="pnl_normal">
	                	<select class="form-control input-xs" name="txt_producto" id="txt_producto">
											<option value="">Seleccione producto</option>
										</select>
										<span class="input-group-btn">
											<button type="button" class="btn btn-default btn-xs btn-flat" onclick="limpiar_reciclaje()"><i class="fa fa-close"></i></button>
										</span>
								 </div>
							</div>
							<div class="col-sm-2 col-md-2">
								<b>Grupo</b>
								<input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-xs" readonly>
								<input type="hidden" name="txt_TipoSubMod" id="txt_TipoSubMod" class="form-control" readonly>
								<input type="hidden" name="txt_primera_vez" id="txt_primera_vez" class="form-control" readonly value="0">
							</div>
						</div>
					</div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-sm-6 col-md-6">
                  <button type="button" style="width: initial;" class="btn btn-default" onclick="show_calendar()"><img src="../../img/png/expiracion.png" /> <br> <b>Fecha de Expiracion</b></button> 
              </div>
              <div class="col-sm-6 col-md-6">
                <br>
                <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control input">
              </div>              
            </div>
          </div>					
				</div>
				<div class="row">
					<!-- <div class="col-sm-6 col-md-4">
						<div class="row">
							<div class="col-sm-6 col-md-6">
									<button type="button" style="width: -webkit-fill-available;" class="btn btn-default" onclick="show_calendar()"><img src="../../img/png/expiracion.png" /> <br> <b>Fecha Expiracion</b></button>	
							</div>
							<div class="col-sm-6 col-md-6">
								<br>
								<input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control input">
							</div>							
						</div>
					</div> -->
					<div class="col-sm-6 col-md-5">
						<div class="rows">
							<div class="col-sm-4 col-md-3">
								<button type="button" style="width: initial;" class="btn btn-default" onclick="show_cantidad()" id="btn_cantidad">
										<img src="../../img/png/kilo.png" />		
										<br>
										<b>Cantidad</b>					
								</button>								
							</div>
							<div class="col-sm-8">
								<div class="row">
									<div class="col-sm-6 col-md-6">
										<b>Cantidad</b>
										<input type="" name="txt_cantidad" id="txt_cantidad" class="form-control input-sm" value="0">	
										<input type="hidden" name="txt_costo" id="txt_costo" readonly class="form-control input-sm">	
										<input type="hidden" name="txt_cta_inv" id="txt_cta_inv" readonly class="form-control input-sm">	
										<input type="hidden" name="txt_contra_cta" id="txt_contra_cta" readonly class="form-control input-sm">										
									</div>
									<div class="col-sm-6 col-md-6">
										<b>Unidad</b>
										<input type="" name="txt_unidad" id="txt_unidad" readonly class="form-control input-sm">	
										<input type="hidden" id="txt_cant_total" name ="txt_cant_total" value="0">
									</div>
								</div>
							</div>
						</div>						
					</div>
          <div class="col-md-4">
            <div class="row"  style="display: none;" id="pnl_sucursal">
              <div class="col-sm-3">
                <button type="button" class="btn btn-default" onclick="show_sucursal()"><img src="../../img/png/sucursal.png" />
                </button>
              </div>
              <div class="col-sm-9">
                <b>SUCURSAL</b>
                <select class="form-control input-xs" id="ddl_sucursales" name="ddl_sucursales">
                  <option value="">Seleccione sucursal</option>
                </select>
              </div>
            </div>
          </div>
					<div class="col-sm-12 col-md-3 text-right">
						<br>
						<button type="button" class="btn btn-primary" onclick="show_panel()" >AGREGAR A INGRESO</button>
						<button type="button" class="btn btn-primary">Limpiar</button>
						<input type="hidden" id="A_No" name ="A_No" value="0">
					</div>
				</div>        
			</div>
			</form>
		</div>	
	</div>
</div>


<script type="text/javascript">
	$( document ).ready(function() {
		cargar_pedido();
    // cargar_productos();
    autocoplet_pro();
    autocoplet_producto();
    autocoplet_pro2();
  })
	 
	function cargar_pedido()
  {
    var parametros=
    {
      'num_ped':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        $('#tbl_body').html(response.tabla);
        var diff = parseFloat(response.cant_total)-parseFloat(response.reciclaje);
        if(diff < 0)
        {
        	diff = diff*(-1);
        }
        $('#txt_primera_vez').val(response.primera_vez);

        var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val(response.reciclaje);
        var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
        var total_pedido = $('#txt_cant').val();
        var faltantes = parseFloat(total_pedido)-parseFloat(response.cant_total);


        $('#txt_faltante').val(faltantes.toFixed(2));
      }
    });
  }

  function cargar_pedido2()
  {
    var parametros=
    {
      'num_ped':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_trans=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        $('#tbl_body_pedido').html(response.tabla);
        $('#txt_cant_total_pedido').val(response.cant_total);       
      }
    });
  }

 function calculos()
   {
     let cant = parseFloat($('#txt_canti').val());
     let pre = parseFloat($('#txt_precio').val());
     let des = 0; //parseFloat($('#txt_descto').val());
     if($('#rbl_si').prop('checked'))
     {
       let subtotal = pre*cant;
       let dscto = (subtotal*des)/100;
       let total = (subtotal-dscto)*1.12;

       let iva = parseFloat($('#txt_iva').val()); 
       $('#txt_subtotal').val(subtotal-dscto);
       $('#txt_total').val(total);
       $('#txt_iva').val(total-(subtotal-dscto));

     }else
     {
      $('#txt_iva').val(0);
       let iva = parseFloat($('#txt_iva').val());       
       let sub = (pre*cant);
       let dscto = (sub*des)/100;

       let total = (sub-dscto);
       $('#txt_subtotal').val(sub-dscto);
       $('#txt_total').val(total);
     }
   }

   function limpiar_nuevo_producto()
   {
     $('#ddl_cta_inv').empty();
     $('#ddl_cta_CV').empty();
     $('#ddl_cta_venta').empty();
     $('#ddl_cta_ventas_0').empty();
     $('#ddl_cta_vnt_anti').empty();
     $('#ddl_familia_modal').empty();
     $('#txt_ref').val('');
     $('#txt_nombre').val('');
     $('#txt_max').val('');
     $('#txt_min').val('');
     $('#txt_reg_sanitario').val('');
     $('#txt_cod_barras').val('');
   }

  function agregar()
  {
  	var reci = $('#txt_TipoSubMod').val();
  	
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


  function limpiar()
  {
  	 var tpd = $('#txt_TipoSubMod').val();
  	  $("#txt_cantidad").val('');
      $("#txt_unidad").val('');
      $("#txt_grupo").val('');
      $("#txt_fecha_exp").val('');


  	 if(tpd=='R')
  	 {

      $("#txt_referencia2").val('');
      $("#txt_producto2").val(null).trigger('change');
      $("#ddl_producto2").val(null).trigger('change');
      $("#txt_producto2").prop('disabled',false);

      $("#txt_producto").prop('disabled',false);      
      $("#txt_producto").val(null).trigger('change');

  	 }else{
      $("#txt_producto").val('');
    
      $("#txt_cantidad2").val('');
      $("#txt_referencia").val('');
      $("#ddl_producto").val(null).trigger('change');
      $("#txt_producto").prop('disabled',false);      
      $("#txt_producto").val(null).trigger('change');
      
    }
  }


  function autocoplet_pro(){
	  $('#ddl_producto').select2({
	    placeholder: 'Seleccione una producto',
	    ajax: {
	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
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

   function autocoplet_producto(){
	  $('#txt_producto').select2({
	    placeholder: 'Seleccione una producto',
	    ajax: {
	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
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

  function autocoplet_pro2(){
	  $('#ddl_producto2').select2({
	    placeholder: 'Seleccione una producto',
	    ajax: {
	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro2=true',
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

function eliminar_lin(num,tipo)
{
  pedido = $('#txt_codigo').val();
  // console.log(cli);

	  if(tipo=='R')
	  {
		  Swal.fire({
		    title: 'Quiere eliminar este registro?',
		    text: "Al eliminar este registro se borrara tambien los productos ligados a este item!",
		    type: 'warning',
		    showCancelButton: true,
		    confirmButtonColor: '#3085d6',
		    cancelButtonColor: '#d33',
		    confirmButtonText: 'Si'
		  }).then((result) => {
		      if (result.value) {
		      	eliminar_all_pedido(pedido);
		      	eliminar_linea_trans(num,'1');
		      	$('#txt_TipoSubMod').val('.');		
		      	$('#btn_cantidad').prop('disabled',false);		
		      	limpiar();
		      	limpiar_reciclaje();         
		      }
		    });
		}else{
		eliminar_linea_trans(num);
	}
}

function eliminar_linea_trans(num,tpd=0)
{
	 var parametros=
    {
      'lin':num,
      'TPD':tpd,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          cargar_pedido();
          cargar_pedido2();
        }
      }
    });
}


function eliminar_all_pedido(pedido)
{
		var parametros=
      {
        'pedido':pedido,
      }
       $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/alimentos_recibidosC.php?eli_all_pedido=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {
            cargar_pedido2();
          }
        }
      });

}

  function eliminar_lin_pedido(num)
  {
    var ruc = $('#txt_ruc').val();
    var cli = $('#ddl_paciente').text();
    // console.log(cli);
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'lin':num,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli_pedido=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedido2();
                }
              }
            });
        }
      });
  }

  function terminar_pedido()
  {
  	pro = $('#txt_producto option:selected').text();
  	var id = $('#txt_id_linea_pedido').val(); 
  	
  	if($('#txt_cant_total_pedido').val()==0 || $('#txt_cant_total_pedido').val()=='')
  	{
  		Swal.fire('Agrege productos para terminar','','info')
  		return false;
  	}

  	primera_vez = $('#txt_primera_vez').val();

  	if(primera_vez==0 || primera_vez==''){
  		$('#txt_cantidad').val($('#txt_cant_total_pedido').val());
  		// $('#btn_cantidad').prop('disabled',true);
  		if($('#txt_cantidad').val()==0 || $('#txt_cantidad').val()=='')
	  	{
	  		Swal.fire('No se olvide de agregar: '+pro,'','info')
	  		return false;
	  	}
	  	show_panel();
	  	limpiar();

  			 $('#modal_producto_2').modal('hide');
  	}else{

  			 $('#modal_producto_2').modal('hide');
  			  Swal.fire('Cantidad Modificada automaticamente','','success');

  	 total = $('#txt_cant_total_pedido').val();
  	
  	  var parametros=
        {
        	'txt_codigo':$('#txt_codigo').val(),
          'total_cantidad':$('#txt_cant_total_pedido').val(),
          'id':id,
          'producto': $('#txt_producto option:selected').text(),
        }
         $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/alimentos_recibidosC.php?actualizar_trans_kardex=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              cargar_pedido();
              cargar_pedido2();
            }
          }
        });

  	 	    limpiar();
  	}






  }

</script>



<div class="row" id="panel_add_articulos">
	<div class="col-sm-12">
		<div class="box">
			<div class="card_body" style="background:antiquewhite;">
					<div class="row"> 
						  <div  class="col-sm-12">
						  	<table class="table table-hover" style="width:100%">
				        <thead>
				          <th>ITEM</th>
				          <th>FECHA DE CLASIFICACION</th>
				          <th>FECHA DE EXPIRACION</th>
				          <th>DESCRIPCION</th>
				          <th>CANTIDAD</th>
				          <th>CODIGO USUARIO</th>
                  <th>CODIGO DE BARRAS</th>
				          <th width="8%"></th>
				        </thead>
				        <tbody id="tbl_body"></tbody>
				      </table>

						  </div>
						</div>

			</div> 			
		</div>
	</div>
</div>

<div id="modal_producto" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Producto</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          	<div class="row">
		           <div class="col-md-3">
		              <b>Referencia:</b>
		              <input type="text" name="txt_referencia" id="txt_referencia" class="form-control input-sm" readonly="">
		           </div>
		           <div class="col-sm-9">
		              <b>Producto:</b><br>
		              <select class="form-control" id="ddl_producto" name="ddl_producto"style="width: 100%;">
		                <option value="">Seleccione una producto</option>
		              </select>
		           </div>        
		        </div>  
					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_producto_2" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="txt_titulo_mod"></h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          	<div class="row">
		          <!--  <div class="col-sm-3">
		              <b>Referencia:</b>
		              <input type="text" name="txt_referencia2" id="txt_referencia2" class="form-control input-sm" readonly="">
		           </div> -->
		           <div class="col-sm-9">
		              <b>Producto:</b><br>
		              <select class="form-control" id="ddl_producto2" name="ddl_producto2"style="width: 100%;">
		                <option value="">Seleccione una producto</option>
		              </select>
		           </div>
		           <div class="col-sm-3">
			           	<b>Cantidad</b>
			           	<input type="text" name="txt_cantidad_pedido" id="txt_cantidad_pedido" class="form-control input-sm" />
			           	<input type="hidden" name="txt_id_linea_pedido" id="txt_id_linea_pedido">
		           </div> 
		         </div>
		         <div class="row">
		           
		           <div class="col-sm-12 text-right">
		           	<br>
		           		<button type="button" class="btn btn-primary btn-sm" onclick="guardar_pedido()"><i class="bx bx-plus"></i>Agregar</button>
		           </div>
		         </div>
		         <div class="row">
		           <div class="col-sm-12">
		           	<br>
		           	 <input type="hidden" id="txt_cant_total_pedido" name ="txt_cant_total_pedido" value="0">
			        	 <div class="table-responsive">
			        	 	 <table class="table">
			        	 	 	<thead>		        	 	 		
					        	 	 	<th>N°</th>
					        	 	 	<th>Producto</th>
					        	 	 	<th>Cantidad</th>
					        	 	 	<th></th>
			        	 	 	</thead>
			        	 	 	<tbody id="tbl_body_pedido">
			        	 	 		<tr><td colspan="4">Sin registros</td></tr>			        	 	 		
			        	 	 	</tbody>
			        	 	 </table>
			        	 </div>
		           </div>       
		        </div>					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
              <button type="button" class="btn btn-primary" onclick="terminar_pedido()">Terminar</button>
              <button type="button" class="btn btn-default"  data-dismiss="modal">Cerrar</button>
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

<div id="modal_sucursal" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Sucursal</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          <b>Sucursal</b>
	         <select class="form-control input-sm" id="ddl_sucursales2" name="ddl_sucursales2" onchange="cambiar_sucursal()">
	         		<option value="">Seleccione Sucursal</option>
	         </select>        					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_sucursal()">OK</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_calendar" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fecha de vencimiento</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">


            	<div id="app">
							  <div class="container">
							    <div class="controls">
							      <button @click="date = prevMonth">{{ prevMonth.toLocaleString({ month: 'long' }) }}</button>
							      <input type="date" v-model="dateString">
							      <button @click="date = nextMonth">{{ nextMonth.toLocaleString({ month: 'long' }) }}</button>
							    </div>
							    <calendar v-model="date"></calendar>
							  </div>
							</div>

							<script type="text/x-template" id="calendar-template">
							  <div class="calendar">
							    <table>
							      <tr>
							        <th v-for="day in days">{{ day }}</th>
							      </tr>
							      <tr v-for="(weekDays, week) in calendar">
							        <td v-for="(date, dayInWeek) in weekDays" :class="classes(date)" @click="select(date)">
							          {{ date.day }}
							        </td>
							      </tr>
							    </table>
							  </div>
							</script>   
							<script src='../../dist/js/vue.js'></script>
							<script src='../../dist/js/luxon.js'></script>   

            </div>
            <div class="modal-footer" style="background-color:antiquewhite;">
                <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>

<script src="../../dist/js/script_calendar.js"></script>