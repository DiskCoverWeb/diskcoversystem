<?php date_default_timezone_set('America/Guayaquil'); ?>
<script type="text/javascript">
  $(document).ready(function () {
  	autocoplet_alimento();
  	autocoplet_ingreso();
  })

  function guardar()
  {
  	 var parametros = $('#form_correos').serialize();
  	  $.ajax({
	    type: "POST",
	    url: '../controlador/facturacion/trans_correosC.php?guardar=true',
	    data:{parametros:parametros},
        dataType:'json',
      success: function(data)
      {
      
      }
	    });
  }
 function autocoplet_alimento(){
  $('#ddl_alimento').select2({
    placeholder: 'Seleccione una beneficiario',
    // width:'90%',
    ajax: {
      url:   '../controlador/facturacion/trans_correosC.php?alimentos=true',
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
      	url:   '../controlador/facturacion/trans_correosC.php?detalle_ingreso=true',
	    // data:{parametros:parametros},
        dataType:'json',
	    success: function(data)
	    {
	    	console.log(data);
	    	option = '';
	    	data.forEach(function(item,i){
	    		console.log(item);
	    		option+='<option value="'+item.ID+'">'+item.Cliente+'</option>';
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
//       url:   '../controlador/facturacion/trans_correosC.php?donante=true',
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
			<div class="box-body">					
				<div class="row">
					<div class="col-sm-6">
						<b>Detalle de ingreso</b>
						<select class=" form-control input-xs form-select" id="ddl_ingreso" name="ddl_ingreso" size="7">
                         	<option value="">Seleccione</option>
                         </select>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-6 text-right">
                           		<b>Codigo de Ingreso:</b>
                         	</div>							
                         	<div class="col-sm-6">
		                         <input type="" class="form-control input-xs" id="" readonly>
	                        </div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								 <b>Fecha de Ingreso:</b>
							</div>
							<div class="col-sm-6">
		                         <input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha">		
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
								<input type="" class="form-control input-xs" id=" txt_tipo" name=" txt_tipo" readonly>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								<b>ALIMENTO RECIBIDO:</b>
							</div>
							<div class="col-sm-6">
								<select class=" form-control input-xs form-select" id="ddl_alimento" name="ddl_alimento">
	                         		<option value="">Seleccione Alimento</option>
	                         	</select>								
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								 <b>CANTIDAD:</b>
							</div>
							<div class="col-sm-6">
	                        	 <input type="" class="form-control input-xs" id="txt_cant" name="txt_cant">	
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 text-right">
								<b>COMENTARIO:</b>
							</div>
							<div class="col-sm-6">
	                         	<input type="" class="form-control input-xs" id="txt_comentario" name="txt_comentario">
							</div>
						</div>
					</div>
				</div>
			</div>
			</form>
		</div>	
	</div>
</div>