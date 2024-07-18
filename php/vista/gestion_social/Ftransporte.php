<script type="text/javascript">
	$(document).ready(function () {
		preguntas_transporte();
	})
	
  function preguntas_transporte(){  		
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?preguntas_transporte=true',
		    // data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	$('#lista_preguntas').html(data);		    	
		    }
		});  	
  }

  function cambiar_tipo()
  {

  	var type = $('input[name="rb_op_vehiculo"]:checked').val();  	
  	if(type==1)
  	{
  		$('#rb_furgon_lbl').css('display','none');
  		$('#rb_camion').prop('checked',true);
  		placas_auto(81);
  		$('#ddl_datos_vehiculo').css('display','block');

  	}else
  	{
  		$('#rb_furgon_lbl').css('display','inline-block');
  		$('#ddl_datos_vehiculo').css('display','none');
  	}
  }

  function placas_auto(tipo)
  {
  		var type = $('input[name="rb_op_vehiculo"]:checked').val();  	
  		if(type==0)
  		{
  			return false;
  		}else{
	  		$.ajax({
			    type: "POST",
		      	url:   '../controlador/inventario/alimentos_recibidosC.php?placas_auto=true',
			    data:{tipo:tipo},
		        dataType:'json',
			    success: function(data)
			    {
			    	op = '';
			    	data.forEach(function(item,i){
			    		op+='<option value="'+item.Cmds+'">'+item.Proceso+'</option>';
			    	})
			    	$('#ddl_datos_vehiculo').html(op);		    	
			    }
			});  
		}	
  	
  }

</script>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-xs-4 col-sm-6">
					<b>vehiculo</b>
					<br>
					<label class="label-success btn-sm btn">
						<input type="radio" class="rbl_opciones" onchange="cambiar_tipo()" name="rb_op_vehiculo" id="" value="1">  Interno
					</label>
					<label class="label-danger btn-sm btn">
						<input type="radio" class="rbl_opciones" onchange="cambiar_tipo()"  name="rb_op_vehiculo" id="" value="0" checked>  Externo
					</label>					
				</div>
				<div class="col-xs-8 col-sm-6">
						<label class="btn btn-default btn-sm" id="rb_furgon_lbl"><img src="../../img/png/furgon.png"><br><input type="radio" name="rb_tipo_vehiculo" value="1" checked />  Furgón</label>
						<label class="btn btn-default btn-sm"><img src="../../img/png/camion2.png"><br><input type="radio" id="rb_camion" name="rb_tipo_vehiculo" value="2" onchange="placas_auto('81')" />  Camión</label>
						<label class="btn btn-default btn-sm"><img src="../../img/png/livianoAu.png"><br><input type="radio" id="rb_" name="rb_tipo_vehiculo" value="3" onchange="placas_auto('82')" />  Liviano</label>
						<select class="form-control form-control-sm" style="display: none;" id="ddl_datos_vehiculo" name="ddl_datos_vehiculo">
							<option>Seleccione vehiculo</option>
						</select>
					
				</div>
				
			</div>			 		
		</div>	
		<div class="col-sm-12">
			<form id="form_estado_transporte" class="">
				<ul class="list-group list-group-flush" id="lista_preguntas"></ul>		
			</form>	
		</div>
	</div>
</div>

<div class="row">		
		
	
		
</div>