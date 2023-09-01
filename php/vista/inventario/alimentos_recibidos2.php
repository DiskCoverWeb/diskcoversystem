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
			</div>
			</form>
		</div>	
	</div>
</div>