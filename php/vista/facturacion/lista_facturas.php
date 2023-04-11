<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();
    $cartera_usu ='';
    $cartera_pass = '';
    if(isset($_SESSION['INGRESO']['CARTERA_USUARIO']))
    {
     $cartera_usu = $_SESSION['INGRESO']['CARTERA_USUARIO'];
     $cartera_pass = $_SESSION['INGRESO']['CARTERA_PASS'];
    }
    $tipo = '';

    if(isset($_GET['tipo']) && $_GET['tipo']==2)
    {
      $tipo=2;
    }
?>
<script type="text/javascript">

  $(document).ready(function()
  {
    catalogoLineas();
    paginacion('cargar_registros','panel_pag');
    // fin paginacion

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
    var tipo = '<?php echo $tipo; ?>';
    autocmpletar_cliente();
    if(tipo==2)
    {
      autocmpletar_cliente_tipo2();
      $('#campo_clave').css('display','none');
    }

  	// cargar_registros();
  	autocmpletar();
  	
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

   function autocmpletar_cliente_tipo2(){
       var g = $('#ddl_grupo').val();
      $('#ddl_cliente').select2({
        placeholder: 'Seleccione Cliente',
        width:'resolve',
      // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_facturasC.php?clientes2=true&g='+g,
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


   function catalogoLineas(){
    fechaEmision = fecha_actual();
    fechaVencimiento = fecha_actual();
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision},      
      dataType:'json', 
      success: function(data)             
      {
        if (data.length>0) {
          datos = data;
          // Limpiamos el select
          console.log(datos);
          $("#DCLinea").find('option').remove();
          if(data.length>1)
          {
            $("#DCLinea").append('<option value="">Todos</option>');
          }
          data.forEach(function(item,i){
            serie = item.id.split(' ');
            serie = serie[1];
             $("#DCLinea").append('<option value="' + item.id +" "+item.text+ ' ">' + serie + '</option>');

            // console.log(item);
             // console.log(i);
          })
        }else{
          Swal.fire({
            type:'info',
            title: 'Usted no tiene un punto de venta asignado o esta mal configurado, contacte con la administracion del sistema',
            text:'',
            allowOutsideClick: false,
          }).then(()=>{
            console.log('ingresa');
                location.href = '../vista/modulos.php';
              });

        }  
        cargar_registros();       
      }
    });
  }


   function cargar_registros()
   {
   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    var tipo = '<?php echo $tipo; ?>'
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,      
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?tabla=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
        $("#tbl_tabla").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
      },
       success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla').html(response);
       $('#myModal_espera').modal('hide');
      }
    });

   }

  function Ver_factura(id,serie,ci,aut)
	{		 
    peri = $('#ddl_periodo').val();
		var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri+'&auto='+aut;		
		window.open(url,'_blank');
	}

  function autorizar(tc,factura,serie,fecha)
  { 
    // $('#myModal_espera').modal('show');
    var parametros = 
    {
      'tc':tc,
      'FacturaNo':factura,
      'serie':serie,
      'Fecha':fecha,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?re_autorizar=true',
      type:  'post',
      dataType: 'json',
       success:  function (data) {
       

    // $('#myModal_espera').modal('hide');
    //    if(response==1)
    //    {
    //      Swal.fire('Factura autoizada','','success').then(function()
    //      {
    //        cargar_registros();
    //      })
    //    }else if(response == 2)
    //    {
    //     Swal.fire('Error al enviar el comprobante estado : Revisar la carpeta de rechazados','','error')
    //    }else if(response==-1)
    //    {
    //     Swal.fire('Comprobante devuelto : Revisar la carpeta de rechazados','','error')
    //    }else{        
    //     Swal.fire(response,'','error')
    //    }

    $('#myModal_espera').modal('hide');
      // console.log(data);
      if(data.respuesta==1)
      { 
        Swal.fire({
          type:'success',
          title: 'Factura Procesada y Autorizada',
          confirmButtonText: 'Ok!',
          allowOutsideClick: false,
        }).then(function(){
          // var url=  '../../TEMP/'+data.pdf+'.pdf';
          // window.open(url, '_blank'); 
          // location.reload();    

        })
      }else if(data.respuesta==-1)
      {
        if(data.text==2 || data.text==null)
          {

          Swal.fire('XML devuleto','XML DEVUELTO','error').then(function(){ 
            // var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');             
          }); 
            tipo_error_sri(data.clave);
          }else
          {

            Swal.fire(data.text,'XML DEVUELTO','error').then(function(){ 
              // var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');             
            }); 
          }
      }else if(data.respuesta==2)
      {
        tipo_error_comprobante(clave)
        Swal.fire('XML devuelto','','error'); 
        tipo_error_sri(data.clave);
      }
      else if(data.respuesta==4)
      {
        Swal.fire('SRI intermitente intente mas tarde','','info');  
      }else
      {
        if(data==-1)
        {
           Swal.fire('Revise CI_RUC de factura en base','Cliente no encontrado','info');
         }else{
          Swal.fire('XML devuelto por:'+data.text,'','error');  
        }
      }


      }
    });
    
  }


  function tipo_error_sri(clave)
  {
    var parametros = 
    {
      'clave':clave,
    }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/punto_ventaC.php?error_sri=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        
         console.log(data);
        $('#myModal_sri_error').modal('show');
        $('#sri_estado').text(data.estado[0]);
        $('#sri_codigo').text(data.codigo[0]);
        $('#sri_fecha').text(data.fecha[0]);
        $('#sri_mensaje').text(data.mensaje[0]);
        $('#sri_adicional').text(data.adicional[0]);
        // $('#doc_xml').attr('href','')
      }
    });
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
	 var cli = $('#ddl_cliente').val();
     var datos =  $("#filtros").serialize();
	   var url = '../controlador/facturacion/lista_facturasC.php?imprimir_excel_fac=true&ddl_cliente='+cli+'&'+datos;
	   window.open(url);

	}
	function validar()
	{
		var cli = $('#ddl_cliente').val();
		var cla = $('#txt_clave').val();
    var tip = '<?php echo $tipo; ?>';
    var ini = $('#txt_desde').val();
    var fin = $('#txt_hasta').val();
    var periodo = $('#ddl_periodo').val();
    //si existe periodo valida si esta en el rango
    if(periodo!='.')
    {
      const fechaInicio=new Date(periodo+'-01-01');
      const fechaFin=new Date(periodo+'-01-30');
      var ini = new Date(ini);
      var fin = new Date(fin);

      console.log(fechaInicio)
      console.log(fechaFin)
      console.log(ini)
      console.log(fin)

      if(ini>fechaFin || ini<fechaInicio)
      {
        Swal.fire('la fecha desde:'+ini,'No esta en el rango','info').then(function(){
           $('#txt_desde').val(periodo+'-01-01');
           return false;
        })
      }
      if(fin>fechaFin || fin<fechaInicio)
      {
        Swal.fire('la fecha hasta:'+fin,'No esta en el rango','info').then(function(){
           $('#txt_hasta').val(periodo+'-01-30');
           return false;
        })
      }

    }

    
      if(cli=='')
      {
        Swal.fire('Seleccione un cliente','','error');
        return false;
      }
    if(tip=='')
    {
  		if(cla=='')
  		{
  			Swal.fire('Clave no ingresados','','error');
  			return false;
  		}
    }
		var parametros = 
		{
			'cli':cli,
			'cla':cla,
      'tip':tip,
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

 function rangos()
 {
    var periodo = $('#ddl_periodo').val();
    if(periodo!='.')
    {
       $('#txt_desde').val(periodo+'-01-01');
       $('#txt_hasta').val(periodo+'-12-31');
    }else
    {
      var currentTime = new Date();
      var year = currentTime.getFullYear()
      $('#txt_desde').val(year+'-01-01');
      $('#txt_hasta').val(year+'-12-31');
    }
 }


 function anular_factura(Factura,Serie,Codigo)
 {
    Swal.fire({
       title: 'Esta seguro? \n Esta usted seguro de Anular la factura:'+Factura,
       text:'' ,
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {
         Anular(Factura,Serie,Codigo);
       }
     })
 }

 function Anular(Factura,Serie,Codigo)
 {
  var parametros = 
  {
    'factura':Factura,
    'serie':Serie,
    'codigo':Codigo,
  }
   $.ajax({
      data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_facturasC.php?Anular=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          Swal.fire('Factura Anulada','','success').then(function()
          {
            cargar_registros();
          })
        }
       }
        
    });
 }

function modal_email_fac(factura,serie,codigoc,emails)
  {
    $('#myModal_email').modal('show'); 
    $('#txt_fac').val(factura);
    $('#txt_serie').val(serie);
    $('#txt_codigoc').val(codigoc);

    var to = emails.substring(0,emails.length-1);
    var ema = to.split(',');
    var t = ''
    ema.forEach(function(item,i)
    {
      t+='<div class="emails emails-input"><span role="email-chip" class="email-chip"><span>'+item+'</span><a href="#" class="remove">×</a></span><input type="text" role="emails-input" placeholder="añadir email ...">       </div>';
       console.log(item);
    })
   $('#emails-input').html(t)
   $('#txt_to').val(emails);

  }

 function enviar_email()
  {
     $('#myModal_espera').modal('show');
    var to = $('#txt_to').val();
    var cuerpo = $('#txt_texto').val();
    var pdf_fac = $('#cbx_factura').prop('checked');
    var titulo = $('#txt_titulo').val();
    var factura = $('#txt_fac').val();
    var serie = $('#txt_serie').val();
    var codigoc = $('#txt_codigoc').val();

    // var adjunto =  new FormData(document.getElementById("form_img"));

    // console.log()
// return false;
    console.log(to);
    parametros = 
    {
        'to':to,
        'cuerpo':cuerpo,
        'pdf_fac':pdf_fac,
        'titulo':titulo,
        'fac':factura,
        'serie':serie,
        'codigoc':codigoc,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_facturasC.php?enviar_email_detalle=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
           $('#myModal_espera').modal('hide');
            if(response==1)
            {
                Swal.fire('Email enviado','','success').then(function(){
                    $('#myModal_email').modal('hide');
                })
            }else
            {
                Swal.fire('Email no enviado','Revise que sea un correo valido','info');
            }
         
        }, 
        error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');
            // $('#lbl_mensaje').text(xhr.statusText);
            // alert(xhr.statusText);
            // alert(textStatus);
            // alert(error);
        }
      });

  }


  function descargar_fac(factura,serie,codigoc)
  {
    var parametros = 
    {
        'fac':factura,
        'serie':serie,
        'codigoc':codigoc,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_facturasC.php?descargar_factura=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            console.log(response);
              var link = document.createElement("a");
              link.download = response;
              link.href = '../../TEMP/'+response;
              link.click();
        
         
        }
      });

  }

  function descargar_xml(xml)
  {
    var parametros = 
    {
        'xml':xml,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_facturasC.php?descargar_xml=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
          if(response!='-1')
          {
            console.log(response);
              var link = document.createElement("a");
              link.download = response.xml;
              link.href ='../../php/'+response.ruta;
              link.click();
              console.log(link.href)
          }else
          {
            Swal.fire('No se encontro el xml','','info');
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
          <div class="col-sm-4">
            <b>CI / RUC</b>
            <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente" onchange="periodos(this.value);rangos();">
              <option value="">Seleccione Cliente</option>
            </select>
          </div>
          <div class="col-sm-1" style="padding: 0px;">
            <b>Serie</b>
              <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
                <option value=""></option>
              </select>
          </div>
          <div class="col-sm-2" id="campo_clave">
            <b>CLAVE</b>
            <input type="password" name="txt_clave" id="txt_clave" class="form-control input-xs">
            <a href="#" onclick="recuperar_clave()"><i class="fa fa-key"></i> Recupera clave</a>
          </div>
          <div class="col-sm-2">
            <b>Periodo</b>
            <select class="form-control input-xs" id="ddl_periodo" name="ddl_periodo" onchange="rangos()">
              <option value=".">Seleccione perido</option>
            </select>
          </div>
          <div class="col-sm-2">
            <b>Desde</b>
              <input type="date" name="txt_desde" id="txt_desde" class="form-control input-xs">           
          </div>
          <div class="col-sm-2">
            <b>Hasta</b>
              <input type="date" name="txt_hasta" id="txt_hasta" class="form-control input-xs">            
          </div>    			
    			<div class="col-sm-2"><br>
    				<button class="btn btn-primary btn-xs" type="button" onclick="validar()"><i class="fa fa-search"></i> Buscar</button>
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
    <div class="col-sm-6">
      <h2 style="margin-top: 0px;">Listado de facturas</h2>
    </div>
    <div class="col-sm-6 text-right" id="panel_pag">
      
    </div>
		<div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
      <table class="table text-sm" style=" white-space: nowrap;">
        <thead>
          <th></th>
          <th>T</th>          
          <th>Razon_Social</th>
          <th>TC</th>
          <th>Serie</th>
          <th>Autorizacion</th>
          <th>Factura</th>
          <th>Fecha</th>
          <th>SubTotal</th>
          <th>Con_IVA</th>
          <th>IVA</th>
          <th>Descuento</th>
          <th>Total</th>
          <th>Saldo</th>
          <th>RUC_CI</th>
          <th>TB</th>
        </thead>
        <tbody  id="tbl_tabla">
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
	
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
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar email</h5>
            </div>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col-sm-12">
                        <div id="emails-input" name="emails-input" placeholder="añadir email"></div>
                        <input type="hidden" name="txt_fac" id="txt_fac">
                        <input type="hidden" name="txt_serie" id="txt_serie">
                        <input type="hidden" name="txt_codigoc" id="txt_codigoc">
                        <input type="hidden" name="txt_to" id="txt_to">
                    </div>
                    <div class="col-sm-12">
                      <input type="" id="txt_titulo" name="txt_titulo" class="form-control form-control-sm" placeholder="titulo de correo" value="comprobantes">
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="3" style="resize:none" placeholder="Texto" id="txt_texto" name="txt_texto"></textarea>
                    </div>                                                  
                    <div class="col-sm-3">
                        <label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Factura</label>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="enviar_email()" >Enviar</button>
            </div>
        </div>
  </div>
</div>





  <script src="../../dist/js/utils.js"></script>
  <script src="../../dist/js/emails-input.js"></script>
  <script src="../../dist/js/multiple_email.js"></script>