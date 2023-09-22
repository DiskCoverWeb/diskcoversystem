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
                if(ui.item.Proceso.toUpperCase() =='COMPRAS' || ui.item.Proceso.toUpperCase() =='COMPRA')
                {
                	$('#pnl_factura').css('display','block');
                }else
                {

                	$('#pnl_factura').css('display','none');
                }
                console.log(ui.item.Proceso.toUpperCase())
                cargar_pedido();
                return false;
            },
            focus: function(event, ui){
                 $('#txt_codigo').val(ui.item.label); // display the selected text
                
                return false;
            },
        });

  	$('#ddl_producto').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#txt_unidad').val(data[0].Unidad);
      $('#txt_referencia').val(data[0].Codigo_Inv);
      $('#txt_producto').val(data[0].Producto);
      $('#txt_grupo').val(data[0].Item_Banco);
      $('#modal_producto').modal('hide');
      console.log(data);
    });



  })

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
  	}





  	 // var parametros = $('#form_correos').serialize();
  	 //  $.ajax({
	 //      type: "POST",
	 //      url: '../controlador/inventario/alimentos_recibidosC.php?guardar2=true',
	 //      data:parametros,
     //      dataType:'json',
	 //      success: function(data)
	 //      {
	 //      	if(data==1)
	 //      	{
	 //      		Swal.fire('Registro Guardado','','success');
	 //      	}
	      
	 //      }
	 //  });
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
  	 var id = $('#txt_id').val();
  	 var cant_suge = $('#txt_cant').val();
  	 var cant_ing = $('#txt_cantidad').val();
  	 var cant_total = $('#txt_cant_total').val();

  	 var producto = $('#txt_producto').val();
  	 var fe_exp = $('#txt_fecha_exp').val();
  	 if(producto=='' || fe_exp=='' || cant_ing=='' || cant_ing==0)
  	 {
  	 	Swal.fire('Ingrese todo los datos','','info');
  	 		return false
  	 }
  	 if((parseFloat(cant_ing)+parseFloat(cant_total))>parseFloat(cant_suge))
  	 {
  	 		Swal.fire('La cantidad Ingresada supera a la cantidad registrada','','info');
  	 		return false
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
  	 	 $('#pnl_comentario').css('display','none');
  	 }
  	 console.log(cbx);
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
                   <input type="" class="form-control input-xs" id="txt_codigo" name="txt_codigo" style="z-index: auto;" >
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
	                <input type="text" name="txt_serie" id="txt_serie" class="form-control input-xs"  readonly>
							</div>
						</div>
						<div class="row" id="panel_serie"  style="padding-top: 5px;">
							<div class="col-sm-6 text-right">
								<b>ESTADO DE TRANSPORTE</b>
							</div>
							<div class="col-sm-6">
	                <input type="text" name="txt_serie" id="txt_serie" class="form-control input-xs"  readonly>
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
								<div class="row">
									<div class="col-sm-6">
										<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R" checked>  <img src="../../img/png/sad.png"> </label>											
									</div>
									<div class="col-sm-6">
										<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion"  value="V" > <img src="../../img/png/smile.png"></label>											
									</div>
								</div>
									<!-- <b>Evaluacion</b><br> -->
										
														
							</div>
							<div class="col-sm-12" id="pnl_comentario">
									<b>comentario de ingreso</b>
									<textarea class="form-control input-sm" rows="3" id="txt_comentario2" name="txt_comentario2"></textarea>								
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
				          <th>DESCRIPCION</th>
				          <th>CANTIDAD</th>
				          <th>PRECIO O COSTO</th>
				          <th>COSTO TOTAL</th>
				          <!-- <th>UNIDAD</th> -->
				          <th>PARA CONTABILIZAR</th>
				        </thead>
				        <tbody id="tbl_body"></tbody>

						  </div>
						</div>

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

   function agregar()
  {
    var parametros = $("#form_add_producto").serialize();    
    var parametros2 = $("#form_correos").serialize();
       $.ajax({
         data:  parametros2+'&txt_referencia='+$('#txt_referencia').val(),
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

</script>





