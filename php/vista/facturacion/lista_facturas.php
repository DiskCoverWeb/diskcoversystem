<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">

  $(document).ready(function()
  {
  	cargar_registros();
  	autocmpletar();
  	autocmpletar_cliente();
  	
  });

  function autocmpletar(){
      $('#ddl_grupo').select2({
        placeholder: 'Seleccione grupo',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_facturasC.php?grupos=true',
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

  function autocmpletar_cliente(){
  	   var g = $('#ddl_grupo').val();
      $('#ddl_cliente').select2({
        placeholder: 'Seleccione Cliente',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_facturasC.php?clientes=true&g='+g,
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

   function cargar_registros()
   {
   
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?tabla=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla').html(response);
       $('#myModal_espera').modal('hide');
      }
    });

   }

   	function Ver_factura(id,serie,ci)
	{		 
		var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci;		
		window.open(url,'_blank');
	}

    function reporte_pdf()
    {
       var url = '../controlador/facturacion/lista_facturasC.php?imprimir_pdf=true&';
       var datos =  $("#filtros").serialize();
        window.open(url+datos, '_blank');
         $.ajax({
             data:  {datos:datos},
             url:   url,
             type:  'post',
             dataType: 'json',
             success:  function (response) {  
          
          } 
           });

    }
    function generar_excel()
	{		
	  var cod = $('#ddl_cliente').val();
	   var url = '../controlador/educativo/detalle_estudianteC.php?imprimir_excel=true&codigo='+cod;
	   window.open(url);

	}
	function validar()
	{
		var cli = $('#ddl_cliente').val();
		var cla = $('#txt_clave').val();
		if(cli=='' || cla=='')
		{
			Swal.fire('Clave o clientes no ingresados','','error');
			return false;
		}
		var parametros = 
		{
			'cli':cli,
			'cla':cla,
		}
		 $.ajax({
             data:  {parametros:parametros},
             url:   '../controlador/facturacion/lista_facturasC.php?validar=true',
             type:  'post',
             dataType: 'json',
             success:  function (response) {
             if(response == 1)
             {
             	$('#myModal_espera').modal('show');
             	cargar_registros();
             }else
             {
             	Swal.fire('Clave incorrecta.','Asegurese de que su clave sea correcta','error');
             }
          } 
        });
	}

 function recuperar_clave()
 {
 	$("#modal_email").modal('show');
 	var g = $('#ddl_grupo').val();
 	var cli = $('#ddl_cliente').val();
 	if(cli=='')
 	{
 		Swal.fire('Seleccione Cliente.','','error');
 		return false;
 	}
 	 var parametros = {  'ci':cli,'gru':g, }
     $.ajax({
      data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_facturasC.php?clientes_datos=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
       	console.log(response);
       	if(response.length >0)
       	{
       		var ema = response[0]['Email'];
       		if(ema!='' && ema !='.')
       		{
       			"intimundosa@hotmail.com"
       			var ini = ema.substring(0,4);
       			var divi = ema.split('@');
       			var num_car =  divi[0].substring(4).length;
       			// num_car = num_car
       			var medio = '';
       			for (var i = 0; i < num_car; i++) {
       				medio+='*';       				
       			}
       			var fin = divi[1];
       			// console.log(ini+medio+fin);

       		 $('#lbl_email').text(ini+medio+'@'+fin);
       		 $('#txt_email').val(ema);
       		 $('#btn_email').css('display','initial');
       		}else
       		{
       			$('#lbl_email').text('El usuario no tien un Email registrado contacte con la institucion');
       			$('#btn_email').css('display','none');
       			$('#txt_email').val('');
       		}
       	}else
       	{
       		$('#btn_email').css('display','none');
       		$('#lbl_email').text('El usuario no tien un Email registrado contacte con la institucion');
       		$('#txt_email').val('');
       	}

      }
    });
 }

 function enviar_mail()
 {
 	var cli = $('#ddl_cliente').val();
    var ema = $('#txt_email').val();
 	 var parametros = {  'ci':cli,'ema':ema }
 	 $.ajax({
      data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_facturasC.php?enviar_mail=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
       	console.log(response);
       	if(response==1)
       	{
       		Swal.fire('Email enviado.','Revise su correo','success');
       		$('modal_email').modal('hide');
       	}
       }
       	
    });
 }

</script>
  <div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
		<div class="row">
      <form id="filtros">
			<div class="col-sm-2">
				<b>GRUPO</b>
				<select class="form-control input-sm" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
					<option value=".">TODOS</option>
				</select>
				<!-- <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm"> -->
			</div>
			<div class="col-sm-4">
				<b>CI / RUC</b>
				<select class="form-control input-sm" id="ddl_cliente" name="ddl_cliente">
					<option value="">Seleccione Cliente</option>
				</select>
			</div>
			<div class="col-sm-2">
				<b>CLAVE</b>
				<input type="password" name="txt_clave" id="txt_clave" class="form-control input-sm">
				<a href="#" onclick="recuperar_clave()"><i class="fa fa-key"></i> Recupera clave</a>
			</div>
			<div class="col-sm-2"><br>
				<button class="btn btn-primary btn-sm" type="button" onclick="validar()"><i class="fa fa-search"></i> Buscar</button>
			</div>

  </form>

		</div>
	<div class="row">
		<h2 style="margin-top: 0px;">Listado de facturas</h2>
		<div  class="col-sm-12" id="tbl_tabla">
			
		</div>		
	</div>
  
</div>

<div id="modal_email" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Recuperar Clave</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <p>Su nueva clave se enviara al correo:</p>
          <h3 id="lbl_email">El usuario no tien un Email registrado contacte con la institucion</h3>
          <input type="hidden" name="txt_email" id="txt_email">
          <!-- <form enctype="multipart/form-data" id="form_img" method="post"> -->
            <button type="button" class="btn btn-primary btn-sm" style="width: 100%" id="btn_email" onclick="enviar_mail()"> Enviar Email</button>
          <!-- </form>   -->
          <br> 
        </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary" onclick="guardar_proveedor()">Guardar</button> -->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
