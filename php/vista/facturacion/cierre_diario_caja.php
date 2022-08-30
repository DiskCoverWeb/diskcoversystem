<?php //print_r($_SESSION['INGRESO']);?>
<script type="text/javascript">
   $( document ).ready(function() {
  	 	autocomplete_cliente();
  	 	consultar_datos();
   })
 function autocomplete_cliente()
  {
    $('#DCCliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '../controlador/facturacion/punto_ventaC.php?DCCliente=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
         var todos = {'id':'Todos','text':'Todos'}
         data.push(todos);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function consultar_datos()
  {
  	if($('#txt_desde').val()=='' && $('#txt_hasta').val()=='')
  	{
  		Swal.fire('Seleccione fechas para la busqueda','','');
  		return false;
  	}
  	var parametros = 
  	{
  		'cliente':$('#DCCliente').val(),
  		'desde':$('#txt_desde').val(),
  		'hasta':$('#txt_hasta').val(),
  	}
  	 $('#myModal_espera').modal('show');
  	 $.ajax({
  	 data: {parametros:parametros},  
      url: '../controlador/facturacion/cierre_diario_cajaC.php?consultar_cierre=true',     
      type: "post",
      dataType:'json', 
      success: function(response)
      {
        // console.log(data);
        $('#tabla').html(response);
  	 	$('#myModal_espera').modal('hide');
      }
    });
  }

  function generar_pdf()
  {
  		filtro = $('#filtros').serialize();
  		url = "../controlador/facturacion/cierre_diario_cajaC.php?pdf=true&"+filtro;
  		window.open(url,'_blank');

  }
  function generar_excel()
  {
  		filtro = $('#filtros').serialize();
  		url = "../controlador/facturacion/cierre_diario_cajaC.php?excel=true&"+filtro;
  		window.open(url,'_blank');
  	
  }
  function generar_email()
  {
     
    if($('#txt_desde').val()!='' && $('#txt_hasta').val()=='' || $('#txt_desde').val()=='' && $('#txt_hasta').val()!='')
    {
      Swal.fire('Seleccione las dos fechas','','');
      return false;
    }
    var parametros = 
    {
      'DCCliente':$('#DCCliente').val(),
      'txt_desde':$('#txt_desde').val(),
      'txt_hasta':$('#txt_hasta').val(),
    }
     $('#myModal_espera').modal('show');
     $.ajax({
     data: {parametros:parametros},  
      url: '../controlador/facturacion/cierre_diario_cajaC.php?generar_email=true',     
      type: "post",
      dataType:'json', 
      success: function(response)
      {
        if(response==1)
        {
          Swal.fire('Email enviado','','success');
        }
        $('#myModal_espera').modal('hide');
      }
    });
    
  }

  function activar_email()
  {
    var cli = $('#DCCliente').val();
    if(cli!= 'Todos')
    {
      $('#btn_email').attr('disabled',false);
    }else
    {
      $('#btn_email').attr('disabled',true);      
    }
  }


</script>  
  <div class="row">
    <div class="col-lg-8 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
         <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button title="Consultar Datos"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos();">
            		<img src="../../img/png/consultar.png" >
            </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a type="button" class="btn btn-default" data-toggle="tooltip" href="#" onclick="generar_pdf()">
              <img src="../../img/png/pdf.png">
            </a>             
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a type="button" class="btn btn-default" data-toggle="tooltip" href="#" onclick="generar_excel()">
              <img src="../../img/png/table_excel.png">
            </a>           
        </div>       
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="btn_email" title="Enviar por email" data-toggle="tooltip" onclick="generar_email()" disabled>
              <img src="../../img/png/email.png">
            </button>         
        </div>
 	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box">	
			<div class="box-body">
				<div class="row">
					<form id="filtros">
						<div class="col-sm-4">
							<b>Cliente</b>
					    	<select class="form-control" id="DCCliente" name="DCCliente" onchange="activar_email()">
								<option value="Todos">Todos</option>
							</select>	
					    </div>
						<div class="col-sm-3"><br>
				     	    <div class="form-group">
					          <label for="inputEmail3" class="col-sm-3 control-label">Desde</label>
					          <div class="col-sm-9">
					            <input type="date" class="form-control input-xs" id="txt_desde" name="txt_desde" value="<?php echo date('Y-m-d'); ?>">
					          </div>
					        </div>	
					    </div>
					    <div class="col-sm-3"><br>
				     	    <div class="form-group">
					          <label for="inputEmail3" class="col-sm-3 control-label">Hasta</label>
					          <div class="col-sm-9">
					            <input type="date" class="form-control input-xs" id="txt_hasta" name="txt_hasta" value="<?php echo date('Y-m-d'); ?>">
					          </div>
					        </div>	
					    </div>				    
					</form>
				</div>
				<div class="row">
          <br>
					<div class="col-sm-12">
						<div id="tabla">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>