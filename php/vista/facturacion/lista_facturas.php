<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();
    $cartera_usu ='';
    $cartera_pass = '';
    if(isset($_SESSION['INGRESO']['CARTERA_USUARIO']))
    {
     $cartera_usu = $_SESSION['INGRESO']['CARTERA_USUARIO'];
     $cartera_pass = $_SESSION['INGRESO']['CARTERA_PASS'];
    }
?>
<script type="text/javascript">

  $(document).ready(function()
  {
    var cartera_usu = '<?php echo $cartera_usu; ?>';
    var cartera_pas = '<?php echo $cartera_pass;?>';
    if(cartera_usu!='')
    {
      buscar_cliente(cartera_usu);
      periodos(cartera_usu);
      $('#txt_clave').val(cartera_pas);
      $('#ddl_cliente').attr('disabled',true);
      $('#ddl_grupo').attr('disabled',true);
      $('#txt_clave').attr('readonly',true);
    }

  	cargar_registros();
  	autocmpletar();
  	autocmpletar_cliente();
  	
  });


  function periodos(codigo){
    var parametros = 
    {
      'codigo':codigo,
    }
    $.ajax({
      type: "POST",      
      dataType: 'json',
      url: '../controlador/facturacion/lista_facturasC.php?perido=true',
      data: {parametros:parametros }, 
      success: function(data)
      {
        if(data!='')
        {
          $('#ddl_periodo').html(data);
        }
      }
    });
  }


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

  function buscar_cliente(ci_ruc)
  {
     var g = $('#ddl_grupo').val();
     $.ajax({
       // data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_facturasC.php?clientes=true&q='+ci_ruc+'&g='+g,         
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
        console.log(response);
           if(response.length==0)
            {
              Swal.fire('Cliente no apto para facturar <br> Asegurese que el cliente este asignado a facturacion','asegurece que FA = 1','info').then(function()
                {
                  location.href = '../vista/modulos.php';
                });
            }
          $('#ddl_cliente').append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));
          $('#lbl_cliente').text(response[0].data.Cliente);
          $('#lbl_ci_ruc').text(response[0].data.CI_RUC);
          $('#lbl_tel').text(response[0].data.Telefono);
          $('#lbl_ema').text(response[0].data.Email);
          $('#lbl_dir').text(response[0].data.Direccion);  
          $('#panel_datos').css('display','block'); 
      }
    });
  }

   function cargar_registros()
   {
   
    var per = $('#ddl_periodo').val();
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,
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
    peri = $('#ddl_periodo').val();
		var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri;		
		window.open(url,'_blank');
	}

    function reporte_pdf()
    {  var cli = $('#ddl_cliente').val();
       var url = '../controlador/facturacion/lista_facturasC.php?imprimir_pdf=true&ddl_cliente='+cli+'&';
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
	   var url = '../controlador/educativo/detalle_estudianteC.php?imprimir_excel=true&codigo='+cod+'&per='+$('#ddl_periodo').val();
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
    				<select class="form-control input-xs" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
    					<option value=".">TODOS</option>
    				</select>
    				<!-- <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm"> -->
    			</div>
          <div class="col-sm-2">
            <b>Periodo</b>
            <select class="form-control input-xs" id="ddl_periodo" name="ddl_periodo">
              <option value=".">Seleccione perido</option>
            </select>
          </div>
    			<div class="col-sm-4">
    				<b>CI / RUC</b>
    				<select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente">
    					<option value="">Seleccione Cliente</option>
    				</select>
    			</div>
    			<div class="col-sm-2">
    				<b>CLAVE</b>
    				<input type="password" name="txt_clave" id="txt_clave" class="form-control input-xs">
    				<a href="#" onclick="recuperar_clave()"><i class="fa fa-key"></i> Recupera clave</a>
    			</div>
    			<div class="col-sm-2"><br>
    				<button class="btn btn-primary btn-sm" type="button" onclick="validar()"><i class="fa fa-search"></i> Buscar</button>
    			</div>
      </form>

		</div>
    <div class="panel" id="panel_datos" style="display:none;margin-bottom: 1px;">
      <div class="row">
        <div class="col-sm-4">
          <b>Cliente: </b><i id="lbl_cliente"></i>
        </div>
         <div class="col-sm-3">
          <b>CI / RUC: </b><i id="lbl_ci_ruc"></i>
        </div>
         <div class="col-sm-3">
          <b>Telefono: </b><i id="lbl_tel"></i>
        </div>
         <div class="col-sm-4">
          <b>Email: </b><i id="lbl_ema"></i>
        </div>
         <div class="col-sm-8">
          <b>Direccion: </b><i id="lbl_dir"></i>
        </div>
      </div>      
    </div>
	<div class="row">
    <div class="col-sm-12">
      <h2 style="margin-top: 0px;">Listado de facturas</h2>
    </div>
		<div  class="col-sm-12" id="tbl_tabla">
			
		</div>		
	</div>
  
</div>

<div id="modal_email" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Recuperar Clave</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <p>Su nueva clave se enviara al correo:</p>
          <h5 id="lbl_email">El usuario no tien un Email registrado contacte con la institucion</h5>
          <input type="hidden" name="txt_email" id="txt_email">
          <!-- <form enctype="multipart/form-data" id="form_img" method="post"> -->
           
          <!-- </form>   -->
          <br> 
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm btn-block"  id="btn_email" onclick="enviar_mail()"> Enviar Email</button>
        <button type="button" class="btn btn-default btn-sm btn-block"   data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
