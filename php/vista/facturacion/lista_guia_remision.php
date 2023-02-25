<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();
$tipo='';
?>

<script type="text/javascript">

  $(document).ready(function()
  {

    paginacion('cargar_registros','panel_pag');
  	catalogoLineas();
  	cargar_registros();
  	autocmpletar_cliente()
  })

   function cargar_registros()
   {   
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
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_guia_remisionC.php?tabla=true',
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

  function Ver_guia_remision(TC,Serie,Factura,Autorizacion,Autorizacion_GR)
  {    
    var url = '../controlador/facturacion/lista_guia_remisionC.php?Ver_guia_remision=true&tc='+TC+'&factura='+Factura+'&serie='+Serie+'&Auto='+Autorizacion+'&AutoGR='+Autorizacion_GR;   
    window.open(url,'_blank');
  }




  function autocmpletar_cliente(){
  	   var g = '.';
      $('#ddl_cliente').select2({
        placeholder: 'RUC / CI / Nombre',
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
          if(data.length>0)
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
      }
    });
  }


  function autorizar(factura,serie,fecha)
  { 
    // $('#myModal_espera').modal('show');
    var parametros = 
    {
      'nota':factura,
      'serie':serie,
      'Fecha':fecha,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_guia_remisionC.php?autorizar_nota=true',
      type:  'post',
      dataType: 'json',
       success:  function (data) {

    $('#myModal_espera').modal('hide');
      // console.log(data);
      if(data.respuesta==1)
      { 
        Swal.fire({
          type:'success',
          title: 'Retencion Procesada y Autorizada',
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
        // tipo_error_comprobante(clave)
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

  function descargar_xml(xml)
  {
    var parametros = 
    {
        'xml':xml,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_guia_remisionC.php?descargar_xml=true',
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

   function descargar_guia(factura,serie,auto,auto_gr,guia,serie_gr)
  {
    var parametros = 
    {
        'factura':factura,
        'serie':serie,
        'autorizacion':auto,
        'autorizacion_gr':auto_gr,
        'guia':guia,
        'serie_gr':serie_gr,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_guia_remisionC.php?descargar_guia=true',
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

function modal_email_guia(Remision,Serie_GR,Factura,Serie,Autorizacion_GR,Autorizacion,emails)
{

    $('#myModal_email').modal('show'); 
    $('#txt_fac').val(Remision);
    $('#txt_serie').val(Serie);
    $('#txt_seriegr').val(Serie_GR);
    $('#txt_numero').val(Factura);
    $('#txt_autorizacion').val(Autorizacion);
    $('#txt_autorizaciongr').val(Autorizacion_GR);

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
    var factura = $('#txt_numero').val();
    var serie = $('#txt_serie').val();
    var seriegr = $('#txt_seriegr').val();
    var remision = $('#txt_fac').val();
    var autoriza = $('#txt_autorizacion').val();
    var autorizagr = $('#txt_autorizaciongr').val();

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
        'factura':factura,
        'serie':serie,
        'seriegr':seriegr,
        'remision':remision,
        'autoriza':autoriza,
        'autorizagr':autorizagr,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_guia_remisionC.php?enviar_email_detalle=true',
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
          <div class="col-sm-5">
            <b>Nombre</b>
            <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente">
              <option value="">Seleccione Cliente</option>
            </select>
          </div>
          <div class="col-sm-1" style="padding: 0px;">
            <b>Serie</b>
              <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
                <option value=""></option>
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
    				<button class="btn btn-primary btn-xs" type="button" onclick="cargar_registros();"><i class="fa fa-search"></i> Buscar</button>
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
      <h2 style="margin-top: 0px;">Listado de guias de remision</h2>
    </div>
    <div class="col-sm-6 text-right" id="panel_pag">
      
    </div>
		<div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
      <table class="table text-sm" style=" white-space: nowrap;">
        <thead>
          <th></th>   
          <th>Razon_Social</th>
          <th>TC</th>
          <th>Serie</th>
          <th>Autorizacion</th>
          <th>guia remision</th>
          <th>Fecha</th>
          <th>Factura</th>
          <th>Serie</th>
          <th>Autorizacion FA</th>
          <th>Ciudad inicio</th>
          <th>Ciudad Final</th>
          <th>Placa</th>
          <th>RUC_CI</th>
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
                        <input type="hidden" name="txt_seriegr" id="txt_seriegr">
                        <input type="hidden" name="txt_codigoc" id="txt_codigoc">
                        <input type="hidden" name="txt_autorizacion" id="txt_autorizacion">
                        <input type="hidden" name="txt_autorizaciongr" id="txt_autorizaciongr">
                        <input type="hidden" name="txt_numero" id="txt_numero">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="enviar_email()" >Enviar</button>
            </div>
        </div>
  </div>
</div>





  <script src="../../dist/js/utils.js"></script>
  <script src="../../dist/js/emails-input.js"></script>
  <script src="../../dist/js/multiple_email.js"></script>