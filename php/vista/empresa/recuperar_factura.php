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

  	if($('#total_fac').text()=='0')
  	{
  		Swal.fire('Realize una busqueda primero','','info');
  		return false;
  	}
  	if($('#entidad').val()=='' || $('#empresas').val()=='')
  	{
  		Swal.fire('Seleccione una entidad y una empresa','','info')
  		return false;
  	}
  	$('#myModal_espera').modal('show');
  	
  	var parametros = 
  	{
  		'desde':$('#txt_desde').val(),
  		'hasta':$('#txt_hasta').val(),
  	}
  	
  	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/recuperar_facturaC.php?recuperar_factura=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			 $('#myModal_espera').modal('hide');
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
		},error: function (request, status, error) {   
          Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
          $('#myModal_espera').modal('hide');
      }
	});
  }

  function lista_recuperar()
  {
  	$('#myModal_espera').modal('show');
  	var parametros = 
  	{
  		'desde':$('#txt_desde').val(),
  		'hasta':$('#txt_hasta').val(),
  	}  	  	
  	$.ajax({
			type: "POST",
			 url: '../controlador/empresa/recuperar_facturaC.php?lista_factura_recuperar=true',
			data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				$('#myModal_espera').modal('hide');			
				$('#tbl_datos').html(data.tabla);
				$('#total_fac').text(data.num)	
				if(data.num==0)
				{
						Swal.fire('No existen documentos electronicos','','info')
				}

				console.log(data);
			},
      error: function (request, status, error) {   
          Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
          $('#myModal_espera').modal('hide');
      }
		});
  }

  function editar_fechas()
  {
  	if($('#total_fac').text()=='0')
  	{
  		Swal.fire('Realize una busqueda primero','','info');
  		return false;
  	}
  	$('#myModal_espera').modal('show');
  	
  	var parametros = 
  	{
  		'desde':$('#txt_desde').val(),
  		'hasta':$('#txt_hasta').val(),
  	}  	  	
  	$.ajax({
			type: "POST",
			 url: '../controlador/empresa/recuperar_facturaC.php?actualizar_fechas=true',
			data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				if(data!=1){ text = 'Uno o varios Documentos no pudieron editar fecha'; tipo = 'info'}else{text = 'Fechas de Documentos Actualizados';tipo='success'}
				Swal.fire(text,'',tipo).then(function()
					{
						lista_recuperar();
					})

			},error: function (request, status, error) {   
          Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
          $('#myModal_espera').modal('hide');
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
            <button type="button" class="btn btn-default" title="Buscar" onclick='lista_recuperar();'><img src="../../img/png/consultar.png" ></button>
        </div>
       	<div class="col-xs-1 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" title="Recuperar Facturas" onclick='recuperar();'><img src="../../img/png/update.png" ></button>
      	</div>  
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Igualar fecha de autorizacion con fecha de Emision" onclick='editar_fechas();'><img src="../../img/png/sub_mod_mes.png" ></button>
        </div>              
 </div>
</div>
	<div class="row">
		<div class="col-sm-6">			
			<p style="margin: 0px;"><b>Entidad:</b><?php echo $_SESSION['INGRESO']['IDEntidad']; ?></p>
			<p style="margin: 0px;"><b>Item: </b><?php echo $_SESSION['INGRESO']['item']; ?></p>
			<p style="margin: 0px;"><b>Empresa: </b><?php echo $_SESSION['INGRESO']['Nombre_Comercial']; ?></p>
			<p style="margin: 0px;"><b>Base de datos: </b><?php echo $_SESSION['INGRESO']['Base_Datos']; ?></p>

			<!-- <p><?php print_r($_SESSION['INGRESO']);?></p> -->			
		</div>
		<div class="col-sm-6">
			<div class="row">			
				<div class="col-sm-6">
					<b>Desde:</b>
					<input type="date" class="form-control input-xs" id="txt_desde" value=""  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
				</div>
				<div class="col-sm-6">
					<b>Hasta</b>
					<input type="date" id="txt_hasta"  class="form-control input-xs"  value=""  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);consultar_datos();">
				</div>
			</div>
			<!-- <i>*Si las fechas son iguales este motrara todos los registros entocntrados</i> -->
	</div>
</div>	

	<div class="row">
		<div class="col-sm-8">			
		<p>Total de facturas:<b id="total_fac">0</b></p>
			<div class="table-responsive">
				<table class="table text-sm">
					<thead>
						<th>Fecha Emision</th>
						<th>Autorizacion</th>
						<th>Serie</th>
						<th>Factura</th>
					</thead>
					<tbody id="tbl_datos">
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>						
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-4">
						
		</div>
	</div>
