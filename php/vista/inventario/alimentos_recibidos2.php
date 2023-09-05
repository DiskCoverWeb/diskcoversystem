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

  	$( "#txt_codigo" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                		url:   '../controlador/inventario/alimentos_recibidosC.php?search=true',          
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                      console.log(data);
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
              console.log(ui.item);
                $('#txt_id').val(ui.item.value); // display the selected text
                $('#txt_fecha').val(ui.item.Fecha); // display the selected text
                $('#txt_ci').val(ui.item.CI_RUC); // save selected id to input
                $('#txt_donante').val(ui.item.Cliente); // save selected id to input
                $('#txt_tipo').val(ui.item.Cod_Ejec); // save selected id to input
                $('#txt_cant').val(ui.item.Total); // save selected id to input
                $('#txt_comentario').val(ui.item.mensaje); // save selected id to input
                $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
                $('#ddl_alimento').append($('<option>',{value: ui.item.Cod_C, text:ui.item.Proceso,selected: true }));
                return false;
            },
            focus: function(event, ui){
                 $('#txt_codigo').val(ui.item.label); // display the selected text
                
                return false;
            },
        });


  })

  function guardar()
  {
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
	      		Swal.fire('Registro Guardado','','success');
	      	}
	      
	      }
	  });
  }
 function autocoplet_alimento(){
  $('#ddl_alimento').select2({
    placeholder: 'Seleccione una beneficiario',
    // width:'90%',
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
	    		console.log(item);
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
  	 var panel = $('#panel_add_articulos').is(':visible');
  	 console.log(panel);
  	 if (panel==false){
		   $('#panel_add_articulos').css('display','block');
		 }
		else   {
		   $('#panel_add_articulos').css('display','none');
		 }
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
			<div class="box-body">					
				<div class="row">					
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-6 text-right">
								 <b>Fecha de Ingreso:</b>
							</div>
							<div class="col-sm-6">
								<input type="hidden" name="txt_id" id="txt_id">
		              <input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" onblur="generar_codigo()" readonly>	
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
                 		<b>Codigo de Ingreso:</b>
               	</div>							
               	<div class="col-sm-6">
                   <input type="" class="form-control input-xs" id="txt_codigo" name="txt_codigo">
                </div>
						</div>
						
						<div class="row">
							<div class="col-sm-6 text-right">
								 <b>RUC / CI</b>
							</div>
							<div class="col-sm-6">
	                         	<input type="" class="form-control input-xs" id="txt_ci" name="txt_ci" readonly>								
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
	                           <b>PROVEEDOR / DONANTE</b>								
							</div>
							<div class="col-sm-6">
								<input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								 <b>TIPO DONANTE:</b>								
							</div>
							<div class="col-sm-6">
								<input type="" class="form-control input-xs" id="txt_tipo" name="txt_tipo" readonly>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-6 text-right">
								<b>ALIMENTO RECIBIDO:</b>
							</div>
							<div class="col-sm-6">
								<select class=" form-control input-xs form-select" id="ddl_alimento" name="ddl_alimento" readonly>
               		<option value="">Seleccione Alimento</option>
               	</select>								
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								 <b>CANTIDAD:</b>
							</div>
							<div class="col-sm-6">
	                        	 <input type="" class="form-control input-xs" id="txt_cant" name="txt_cant" readonly>	
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								<b>COMENTARIO:</b>
							</div>
							<div class="col-sm-6">
	                <input type="" class="form-control input-xs" id="txt_comentario" name="txt_comentario" readonly>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-6">
								<!-- <br> -->
									<label><input type="checkbox" name="rbl_recibido" id="rbl_recivido"> <b>Recibido</b></label>
							</div>
							<div class="col-sm-6">
									<!-- <b>Evaluacion</b><br> -->
									<label><input type="radio" name="cbx_evaluacion" checked value="R"> Rojo</label>		
									<label><input type="radio" name="cbx_evaluacion"  value="V"> Verde</label>								
							</div>
							<div class="col-sm-12">
									<b>comentario de ingreso</b>
									<textarea class="form-control input-sm" rows="3" id="txt_comentario2" name="txt_comentario2"></textarea>								
							</div>
						</div>


					<!-- 	<select class=" form-control input-xs form-select" id="ddl_ingreso" name="ddl_ingreso" size="7" onchange="option_select()">
                         	<option value="">Seleccione</option>
                         </select> -->
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-right">
						<button type="button" class="btn btn-primary btn-sm" onclick="show_panel()" ><i class="fa fa-archive"></i>Agregar Articulos</button>
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
  })
	 
	function cargar_pedido()
  {
    var parametros=
    {
      'num_ped':99999,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        $('#tbl_body').html(response.tabla);
        /*num_ped = $('#txt_pedido').val();
        if(num_ped=='')
        {
           $('#tabla').html(response.tabla);
        }else{
          var ped = reload_();
          if(ped==-1)
          {
            num_ped = $('#txt_pedido').val();
            mod = '<?php echo $_GET["mod"]; ?>';
            var url="../vista/farmacia.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&area="+area+"-"+pro+"&num_ped="+num_ped+"&cod="+num_his+"#";
            $(location).attr('href',url);
          }else
          {

             $('#txt_num_lin').val(response.num_lin);
            $('#txt_num_item').val(response.item);
            $('#tabla').html(response.tabla);
            $('#txt_neg').val(response.neg);
            $('#txt_sub_tot').val(response.subtotal);
            $('#txt_tot_iva').val(response.iva);
            $('#txt_pre_tot').val(response.total);
            $('#txt_procedimiento').val(response.detalle);
            if($('#txt_num_lin').val()!=0 && $('#txt_num_lin').val()!='')
            {
              $('#btn_comprobante').css('display','block');
            }

          }
        }*/
      }
    });
  }

   function cargar_detalles()
   {
     var id = $('#ddl_producto').val();
     console.log(id);
     var datos = id.split('_');
      $('#ddl_familia').append($('<option>',{value: datos[1], text:datos[0],selected: true }));
      $('#txt_referencia').val(datos[2]);
      $('#txt_existencias').val(datos[9]);
      $('#txt_ubicacion').val(datos[7]);
      $('#txt_precio_ref').val(datos[3]);
      $('#txt_unidad').val(datos[6]);
      if(datos[8]==0)
      {
        $('#rbl_no').prop('checked',true);
      }else
      {        
        $('#rbl_si').prop('checked',true);
      }
      $('#txt_reg_sani').val(datos[10]);
      $('#txt_max_in').val(datos[11]);
      $('#txt_min_in').val(datos[12]);
          
     // console.log(datos);
   }
 function calculos()
   {
     let cant = parseFloat($('#txt_canti').val());
     let pre = parseFloat($('#txt_precio').val());
     let des = parseFloat($('#txt_descto').val());
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
    var parametros = $("#form_add_producto").serialize();
       $.ajax({
         data:  parametros,
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
      $("#ddl_familia").empty();
      $("#ddl_descripcion").empty();
      $("#ddl_pro").empty();
  }
  function autocoplet_pro(){
	  $('#ddl_producto').select2({
	    placeholder: 'Seleccione una producto',
	    width:'100%',
	    ajax: {
	      url:   '../controlador/farmacia/articulosC.php?autocom_pro=true',
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
              'ped':'99999',
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

</script>



<div class="row" id="panel_add_articulos" style="display:none;">
	<div class="col-sm-12">
		<div class="box">
			<div class="card_body">
					<div class="row">
						  <div class="col-sm-12">
						     <div class="panel panel-primary">
						      <div class="panel-heading">
						        <div class="row">
						         <div class="col-sm-6 text-right"><b>INGRESAR ARTICULOS</b></div>         
						         <div class="col-sm-6 text-right"> No. COMPROBANTE  <u id="num"></u></div>        
						        </div>
						      </div>
						      <div class="panel-body">
						        <form id="form_add_producto">
						          <div class="row">
						            <div class="col-sm-4">
						              <b>Alimentos Recibidos:</b>
						              <!-- <div class="input-group">  -->
						                  <select class="form-control input-sm" id="ddl_proveedor" name="ddl_proveedor" onchange="cargar_datos_prov()">
						                     <option value="">Seleccione un proveedor</option>
						                  </select>             
						                   <!-- <span class="input-group-addon bg-green" title="Buscar" data-toggle="modal" data-target="#myModal_provedor"><i class="fa fa-plus"></i></span> -->
						              <!-- </div> -->
						            </div>            
						            <div class="col-sm-3">
						              <b>Nombre comercial</b><br>
						              <label id="lbl_nom_comercial"></label>
						            </div> 
						            <div class="col-sm-1">
						              <b>Serie</b>
						              <input type="text" name="txt_serie" id="txt_serie" class="form-control input-sm" onkeyup="num_caracteres('txt_serie',6)">            
						            </div>
						            <div class="col-sm-2">
						              <b>Numero de factura</b>
						              <input type="text" name="txt_num_fac" id="txt_num_fac" class="form-control input-sm">            
						            </div>           
						             <div class="col-sm-2">
						              <b>Fecha:</b>
						              <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" onblur="num_comprobante()">
						           </div>
						          </div>
						        <div class="row">
						           <div class="col-md-2">
						              <b>Referencia:</b>
						              <input type="text" name="txt_referencia" id="txt_referencia" class="form-control input-sm" readonly="">
						           </div>
						           <div class="col-sm-5">
						              <b>Producto:</b><br>
						              <select class="form-control input-sm" id="ddl_producto" name="ddl_producto" onchange="cargar_detalles()">
						                <option value="">Seleccione una producto</option>
						              </select>
						           </div>
						          
						           <div class=" col-sm-3">
						              <b>Familia:</b>
						                <select class="form-control input-sm" id="ddl_familia" name="ddl_familia" disabled="">
						                  <option>Seleccione una familia</option>
						                </select>     
						           </div>
						           <div class="col-sm-1">
						            <b>Unidad</b>
						            <input type="" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm">             
						           </div>
						           <div class="col-sm-1" style="padding: 0px;">
						              <b>Lleva iva</b><br>
						              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_no" checked="" onchange="calculos()"> No</label>
						              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_si" onchange="calculos()"> Si</label>            
						            </div>   
						        </div>
						        <div class="row">
						            <div class="col-sm-2">
						               <b>Existente</b>
						                  <input type="text" name="txt_existencias" id="txt_existencias" class="form-control input-sm" readonly="">
						            </div>
						            <div class="col-sm-2">
						               <b>Fecha Elab</b>
						                  <input type="date" name="txt_fecha_ela" id="txt_fecha_ela" class="form-control input-sm" >
						            </div>
						            <div class="col-sm-2">
						               <b>Fecha Exp</b>
						                  <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control input-sm" >
						            </div>
						            <div class="col-sm-2">
						               <b>Reg. Sanitario</b>
						                  <input type="text" name="txt_reg_sani" id="txt_reg_sani" class="form-control input-sm" readonly="" value=".">
						            </div>
						            <div class="col-sm-2">
						               <b>Procedencia</b>
						                  <input type="text" name="txt_procedencia" id="txt_procedencia" class="form-control input-sm">
						            </div>
						            <div class="col-sm-2">
						               <b>Lote</b>
						                  <input type="text" name="txt_lote" id="txt_lote" class="form-control input-sm">
						            </div>              
						        </div>
						        <div class="row">
						          <div class="col-sm-1">
						               <b>Max</b>
						                  <input type="text" name="txt_max_in" id="txt_max_in" class="form-control input-sm" readonly="">
						            </div>
						            <div class="col-sm-1">
						               <b>Min</b>
						                  <input type="text" name="txt_min_in" id="txt_min_in" class="form-control input-sm" readonly="">
						            </div>
						              <div class="col-sm-2">
						               <b>Ubicacion</b>
						               <input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-sm" readonly="">
						            </div>       
						          <div class="col-sm-1">
						               <b>Cantidad</b>
						                  <input type="text" name="txt_canti" id="txt_canti" class="form-control input-sm"  value="1" onblur="calculos()">
						            </div>
						            <div class="col-sm-1">
						               <b>Precio</b>
						                  <input type="text" name="txt_precio" id="txt_precio" class="form-control input-sm"  value="0" onblur="calculos()">
						            </div>
						            <div class="col-sm-1">
						               <b>Pvp Ref</b>
						                  <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm"  value="0" readonly="">
						            </div>
						            <div class="col-sm-1">
						               <b>% descto</b>
						                  <input type="text" name="txt_descto" id="txt_descto" class="form-control input-sm"  value="0" onblur="calculos()">
						            </div>             
						            <div class="col-sm-1">
						               <b>Subtotal</b>
						                  <input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control input-sm" readonly="" value="0">
						            </div>
						            <div class="col-sm-1">
						               <b>Iva</b>
						                  <input type="text" name="txt_iva" id="txt_iva" class="form-control input-sm" readonly="" value="0">
						            </div>  
						            <div class="col-sm-1">
						               <b>Total</b>
						                  <input type="text" name="txt_total" id="txt_total" class="form-control input-sm" readonly="" value="0">
						            </div>          
						        </div>
						        <div class="row">
						          <div class="col-sm-7">
						            
						          </div>
						          <div class="col-sm-5 text-right" style="padding-left: 0px"><br>
						               <button type="button" class="btn btn-primary" onclick="agregar()"><i class="fa fa-plus"></i> Agregar a ingreso</button>
						                <button type="button" class="btn btn-default" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
						            </div>
						        </div>
						        <input type="hidden" id="A_No" name ="A_No" value="0">
						        </form>       
						      </div>
						      </div>
						  </div>						 
						  <div  class="col-sm-12">
						  	<table class="table-sm table-hover" style="width:100%">
				        <thead>
				          <th>ITEM</th>
				          <th>FECHA</th>
				          <th>REFERENCIA</th>
				          <th>DESCRIPCION</th>
				          <th class="text-right">CANTIDAD</th>
				          <th class="text-right">COSTO</th>
				          <!-- <th>PVP</th> -->
				          <!-- <th>DCTO %</th> -->
				          <th class="text-right">IVA</th>
				          <th class="text-right">IMPORTE</th>
				          <th>Stock(-)</th>
				        </thead>
				        <tbody id="tbl_body"></tbody>

						  </div>
						</div>

			</div> 			
		</div>
	</div>
</div>