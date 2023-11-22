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

  

  

      $('#txt_codigo').on('select2:select', function (e) {
		      var data = e.params.data.data;

					console.log(data);
		      $('#txt_id').val(data.ID); // display the selected text
		      $('#txt_fecha').val(formatoDate(data.Fecha_P.date)); // display the selected text
		      $('#txt_ci').val(data.CI_RUC); // save selected id to input
		      $('#txt_donante').val(data.Cliente); // save selected id to input
		      $('#txt_tipo').val(data.Actividad); // save selected id to input
		      $('#txt_cant').val(data.TOTAL); // save selected id to input
		      $('#txt_comentario').val(data.Mensaje); // save selected id to input
		      $('#txt_comentario_clas').val(data.Llamadas); // save selected id to input
		      $('#txt_ejec').val(data.Cod_Ejec); // save selected id to input

		      $('#txt_contra_cta').val(data.Cta_Haber); // save selected id to input
		      $('#txt_cta_inv').val(data.Cta_Debe); // save selected id to input

		      $('#txt_codigo_p').val(data.CodigoP)
		      if(data.Cod_R=='0')
		      {
		      	$('#img_estado').attr('src','../../img/png/bloqueo.png');
		      }else
		      {

		      	$('#img_estado').attr('src','../../img/png/aprobar.png');
		      }
		      $('#txt_temperatura').val(data.Porc_C); // save selected id to input
		      $('#ddl_alimento').append($('<option>',{value: data.Cod_C, text:data.Proceso,selected: true }));
		      if(data.Proceso.toUpperCase() =='COMPRAS' || data.Proceso.toUpperCase() =='COMPRA')
            {
            	$('#pnl_factura').css('display','block');
            }else
            {

            	$('#pnl_factura').css('display','none');
            }
   		 		cargar_pedido();
   		});


  })

  function pedidos(){
  $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    // width:'90%',
    ajax: {
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedidos_proce=true',          
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


  function guardar()
  {
  	var todos = 1;
  	 $('.rbl_conta').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (!isChecked) {
	        todos = 0;
	    }
	});

  	if(todos==0)
  	{
  		Swal.fire('Asegurese de que todos los productos esten seleccionados','','info');
  		return false;
  	}else
  	{
  		contabilizar();
  	}
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
  

  function show_calendar()
  {
  	$('#modal_calendar').modal('show');
  }
    function show_producto()
  {
  	$('#modal_producto').modal('show');
  }
    function show_cantidad()
  {
  	$('#modal_cantidad').modal('show');
  }

  function cambiar_cantidad()
  {
  	var can = $('#txt_cantidad2').val();
  	$('#txt_cantidad').val(can);
  	$('#modal_cantidad').modal('hide');
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

  function recalcular(id)
  {
  	var cant =  $('#txt_cant_ped_'+id).text();
  	var pvp =  $('#txt_pvp_linea_'+id).val();
  	var total = parseFloat(cant)* parseFloat(pvp);
  	$('#txt_total_linea_'+id).val(total.toFixed(4));
  	console.log(total);
  }

  function editar_precio(id)
  {
  		parametros = 
  		{
  			'id':id,
  			'pvp':$('#txt_pvp_linea_'+id).val(),
  			'total':$('#txt_total_linea_'+id).val(),
  		}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?editar_precio=true',
		    data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	cargar_pedido();
		    	
		    }
		});  	

  }

  function guardar_check()
  {
  	var checked = '';
  	var no_checked = '';
  	 $('.rbl_conta').each(function() {
		    const checkbox = $(this);
		    const isChecked = checkbox.prop('checked'); 
		    if (isChecked) {
		       checked+=checkbox[0].defaultValue+',';
		    }else
		    {
		    	no_checked+=checkbox[0].defaultValue+',';
		    }
			});
  	 // if(checked=='')
  	 // {
  	 // 	Swal.fire('No se ha seleccionado ningun item','','info');
  	 // 	return false;
  	 // }

  	 parametros = 
  		{
  			'check':checked,
  			'no_check':no_checked,
  		}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?editar_checked=true',
		    data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire('Items Seleccionados Guardados','','success');
		    	}
		    	
		    }
		});  	

  	 // console.log(checked)

  }

  function cargar_tras_pedidos(nombre,pedido)
  {
  	$('#lbl_titulo').text(nombre)
  	 var parametros=
    {
      'num_ped':pedido,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_trans_datos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        var lista = '';
        response.forEach(function(item,i){
        	lista+='<li style="font-size: large;"><a href="#" style="padding-right:0px"><label>'+item.Producto+'</label><span class="label label-danger pull-right">'+item.Cantidad+'</span></a></li>';
        })
        $('#lista_pedido').html(lista);    
      }
    });

  	$('#myModal_trans_pedido').modal('show');

  }

  function abrir_modal_notificar(codigoU)
  {
  	$('#txt_codigo_usu').val(codigoU);
  	$('#myModal_notificar_usuario').modal('show');
  }

 function notificar2()
 {
   var codigo = $('#txt_codigo').val();
   console.log(codigo);
    if(codigo=='')
    {
       Swal.fire("Seleccione un pedido","","info");
       return false;
    }

    var parametros = {
        'notificar':$('#txt_comentario2').val(),
        'id':$('#txt_id').val(),
        'asunto':'Recepcion',
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_clasificacion=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire("","Notificacion enviada","success");
        }
        console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });
 }


 function editar_comentario(mod)
 {
 	 if(mod)
 	 {
 	 	 if($('#txt_comentario_clas').prop('readonly'))
 	 	 {
 	 	 	$('#txt_comentario_clas').prop('readonly',false)
 	 	 }
 	 }else
 	 {
 	 	 if($('#txt_comentario').prop('readonly'))
 	 	 {
 	 	 	$('#txt_comentario').prop('readonly',false)
 	 	 }
 	 }

 	

 	 console.log(editar);
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
					<button class="btn btn-default" title="Guardar checks temporalmente" onclick="guardar_check()">
						<img src="../../img/png/check.png">
					</button>
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
								<b>COMENTARIO DE RECEPCION:</b>
							</div>
							<div class="col-sm-6">
								<div class="input-group input-group-sm">
										<textarea class="form-control input-xs" id="txt_comentario" name="txt_comentario" readonly rows="1">
																	</textarea>
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" onclick="editar_comentario()"><i class="fa fa-pencil"></i></button>
									</span>
								</div>						
							</div>
						</div>
						<div class="row"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>COMENTARIO DE CLASIFICACION:</b>
							</div>
							<div class="col-sm-6">
								<div class="input-group input-group-sm">
									<textarea class="form-control input-xs" id="txt_comentario_clas" name="txt_comentario_clas" readonly rows="1">
								</textarea>
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" onclick="editar_comentario(1)"><i class="fa fa-pencil"></i></button>
									</span>
								</div>

								
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
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-6">
								<!-- <br> -->
									<!-- <label><input type="checkbox" name="rbl_recibido" id="rbl_recivido"> <b>Recibido</b></label> -->
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-6">
										<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion"  value="V" checked > <img src="../../img/png/smile.png"><br> Conforme </label>											
									</div>
									<div class="col-sm-6">
										<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R" >  <img src="../../img/png/sad.png"><br> Inconforme</label>											
									</div>
									
								</div>
									<!-- <b>Evaluacion</b><br> -->
										
														
							</div>
							<div class="col-sm-12" id="pnl_comentario">
									<b>COMENTARIO DE CHECKING</b>									
									<textarea class="form-control input-sm" rows="3" id="txt_comentario2" name="txt_comentario2" style="font-size: 16px;"></textarea>
									<div class="text-right">
										<button type="button" class="btn btn-primary btn-sm" onclick="notificar2()">Notificar</button>
									</div>								
							</div>
						</div>


					<!-- 	<select class=" form-control input-xs form-select" id="ddl_ingreso" name="ddl_ingreso" size="7" onchange="option_select()">
                         	<option value="">Seleccione</option>
                         </select> -->
					</div>
				</div>
				<hr>
				<div class="row" id="pnl_factura" style="display:none;">
					<div class="col-sm-7">
						
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Serie</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-xs" id="txt_serie" name="txt_serie">		
							</div>
						</div>						
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-4 control-label">Factura</label>
							<div class="col-sm-8">
								<input type="text" class="form-control input-xs" id="txt_factura" name="txt_factura">	
							</div>
						</div>						
					</div>
				</div>			
			</div>
			</form>
		</div>	
	</div>
</div>
<div class="row" id="panel_add_articulos">
	<div class="col-sm-12">
		<div class="box">
			<div class="card_body" style="background:antiquewhite;">
					<div class="row"> 
						  <div  class="col-sm-12">
						  	<table class="table-sm table-hover" style="width:100%">
				        <thead>
				          <th>ITEM</th>
				          <th>FECHA DE CLASIFICACION</th>
				          <th>FECHA DE EXPIRACION</th>
				          <th width="224px">DESCRIPCION</th>
				          <th>CANTIDAD</th>
				          <th>PRECIO O COSTO</th>
				          <th>COSTO TOTAL</th>
				          <th width="200px">USUARIO</th>
				          <th>PARA CONTABILIZAR</th>				          
				          <th></th>
				        </thead>
				        <tbody id="tbl_body"></tbody>

						  </div>
						</div>

			</div> 			
		</div>
	</div>
</div>

<div id="myModal_trans_pedido" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="lbl_titulo"></h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
            	<div class="direct-chat-messages">	
									<ul class="list-group list-group-flush" id="lista_pedido"></ul>											
							</div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>



<script type="text/javascript">
	$( document ).ready(function() {
		cargar_pedido();
    // cargar_productos();
    autocoplet_pro();
  })
	 
	function cargar_pedido()
  {
    var parametros=
    {
      'num_ped':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_checking=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        $('#tbl_body').html(response.tabla);
        $('#txt_cant_total').val(response.cant_total);
       
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

  function contabilizar()
  { 
    var parametros2 = $("#form_correos").serialize();
       $.ajax({
         data:  parametros2,
         url:   '../controlador/inventario/alimentos_recibidosC.php?contabilizar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 

            // console.log(response);
           if(response==1)
           {
              Swal.fire({
                type:'success',
                title: 'Pedido contabilizado',
                text :'',
              }).then( function() {
              			location.reload();
                });
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });    
  }


  function limpiar()
  {
      $("#ddl_familia").empty();
      $("#ddl_descripcion").empty();
      $("#ddl_pro").empty();
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

function eliminar_lin(num)
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
              url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedido();
                }
              }
            });
        }
      });
  }

   function notificar()
 {
   
    var parametros = {
        'notificar':$('#txt_texto').val(),
        'usuario':$('#txt_codigo_usu').val(),
        'asunto':'Clasificacion',
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_usuario=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire("","Notificacion enviada","success").then(function(){
          	$('#myModal_notificar_usuario').modal('hide'); 
          	$('#txt_texto').val('');   
          	$('#txt_codigo_usu').val('') 
          });
        }
        console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });
 }


</script>


<div id="myModal_notificar_usuario" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Notificacion</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
              <input type="hidden" name="txt_codigo_usu" id="txt_codigo_usu">
                <textarea class="form-control form-control-sm" rows="3" id="txt_texto" name="txt_texto" placeholder="Detalle de notificacion"></textarea>
            </div>
             <div class="modal-footer">             	
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="notificar()">Notificar</button>
            </div>
        </div>
    </div>
  </div>



