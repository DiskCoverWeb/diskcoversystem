<?php  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">
  $(document).ready(function () {
  	 $('#ciudad').select2();
  	autocmpletar_entidad(); 

  	 $('#entidad').on('select2:select', function (e) {
  	 	console.log(e);
      var data = e.params.data.data;
      $('#lbl_ruc').html(data.RUC_CI_NIC);
      if(data.ID_Empresa.length<3 && data.ID_Empresa.length>=2)
      {
      	var item = '0'+data.ID_Empresa;
      }else if(data.ID_Empresa.length<2)
      {
      	var item = '00'+data.ID_Empresa
      }
      $('#lbl_enti').html(item);
     
      console.log(data);
    });


  });

 function autocmpletar_entidad()
 {
	$('#entidad').select2({
	  placeholder: 'Seleccione una Entidad',
	  ajax: {
	    url: '../controlador/empresa/niveles_seguriC.php?entidades=true',
	    dataType: 'json',
	    delay: 250,
	    processResults: function (data) {
	      return {
	        results: data
	      };
	    },
	    cache: true
	  }
	});
 }

  function buscar_ciudad()
  {
  	var parametros = 
  	{
  		'entidad':$('#entidad').val(),
  	}
  	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?ciudad=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			llenarComboList(data,'ciudad');
		}
	});
  }

 function buscar_empresas()
 {
 	var ciu = $('#ciudad').val();
 	var ent = $('#entidad').val();
	$('#empresas').select2({
	  placeholder: 'Seleccione una Empresa',
	  ajax: {
	    url: '../controlador/empresa/recuperar_facturaC.php?empresas=true&ciu='+ciu+'&ent='+ent,
	    dataType: 'json',
	    delay: 250,
	    processResults: function (data) {
	      return {
	        results: data
	      };
	    },
	    cache: true
	  }
	});
 }

 function datos_empresa()
  {
  	var sms = !!document.getElementById("Mensaje");
  	if(sms==false)
  	{
  		sms='';
  	}else
  	{
  		sms = $('#Mensaje').val();
  	}
  	var parametros = 
  	{
  		'empresas':$('#empresas').val(),
  		'sms':sms,
  	}
  	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?datos_empresa=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			$('#datos_empresa').html(data.datos);
			$('#ci_ruc').val(data.ci);

			console.log(data);
		}
	});
  }


  function recuperar()
  {
  	if($('#entidad').val()=='' || $('#empresas').val()=='')
  	{
  		Swal.fire('Seleccione una entidad y una empresa','','info')
  		return false;
  	}
  	var parametros = 
  	{
  		'entidad':$('#entidad').val(),
  		'item':$('#empresas').val(),
  	}
  	
  	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/recuperar_facturaC.php?recuperar_factura=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			if(data==1)
			{
				Swal.fire('Factura recuperada de xml','','info');

			}else if(data==-2)
			{
				Swal.fire('Xml encontrado en transdocumentos pero tiene un estado de en proceso','','info');
			}else if(data==-3)
			{
				Swal.fire('No hay facturas que recuperar','','info');				
			}
			

			console.log(data);
		}
	});
  }


</script>

  <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>       
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Mensaje masivo a grupo seleccionado" onclick='recuperar();'><img src="../../img/png/email_grupo.png" ></button>
        </div>
         
 </div>
</div>
	<div class="row" id="form_vencimiento" style="display:none;">
		<br>
		<div class="col-sm-1">
			<br>
			<button class="btn btn-default btn-sm" type="button" onclick="cerrarEmpresa()"><i class="fa fa-close"></i> Cerrar</button>
		</div>
		<div class="col-sm-2">
			<b>Desde:</b>
			<input type="date" class="form-control input-sm" id="desde" value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
		</div>
		<div class="col-sm-2">
			<b>Hasta</b>
			<input type="date" id="hasta"  class="form-control input-sm"  value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);consultar_datos();">
		</div><br>
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="" id="tbl_style" style="width: 98%;">
					<thead>
						<tr>
							<td>Tipo</td>
							<td>Item</td>
							<td>Empresa</td>
							<td>Fecha</td>
							<td>Enero</td>
						</tr>
					</thead>
					<tbody id="tbl_vencimiento">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<form id="form_empresa">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label>Entidad: </label><i id="lbl_ruc"></i>-<i id="lbl_enti"></i>
				<select class="form-control fomr" name="entidad" id='entidad' onChange="buscar_ciudad();">
					<option value=''>Seleccione Entidad</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="Entidad">Ciudad</label>
				<select class="form-control input-sm" name="ciudad" id='ciudad' onchange="buscar_empresas()">
					<option value=''>Seleccione ciudad</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="Entidad">Empresa</label>
				<select class="form-control input-xs" name="empresas" id='empresas' onchange="datos_empresa()">
					<option value=''>Seleccione Empresa</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="Entidad">CI / RUC </label>
				<input type="text" class="form-control input-xs" name="ci_ruc" id="ci_ruc" readonly>
			</div>			
		</div>
	</div>
	<div class="row" id="datos_empresa">
		
	</div>
	</form>	
