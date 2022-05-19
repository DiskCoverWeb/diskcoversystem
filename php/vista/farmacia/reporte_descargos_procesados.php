<?php 
$cod = ''; $ci =''; if(isset($_GET['cod'])){$cod = $_GET['cod'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}?>
<script type="text/javascript">
   $( document ).ready(function() {
    cargar_pedidos();
    cargar_ficha();
    autocoplet_paci();
    autocoplet_area();
    // cargar_ficha();
  });

  function cargar_pedidos(f='')
  {
     var paginacion = 
    {
      '0':$('#pag').val(),
      '1':$('#ddl_reg').val(),
      '2':'cargar_pedidos',
    }
    $('#txt_tipo_filtro').val(f);
    var ruc = '<?php echo $ci; ?>';
    var nom = $('#txt_query').val();
    var ci = ruc.substring(0,10);
    var desde=$('#txt_desde').val();
      var  parametros = 
      { 
        'codigo':ci,
        'nom':$('#txt_nombre').val(),
        'query':nom,
        'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
        'desde':desde,
        'hasta':$('#txt_hasta').val(),
        'busfe':f,
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros,paginacion:paginacion},
      url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
        beforeSend: function () {   
          var spiner = '<tr><td colspan="5"><img src="../../img/gif/loader4.1.gif" width="20%"></td> </tr>';   
          $('#tbl_body').html(spiner);
         },
      success:  function (response) { 
        if(response)
        {
          $('#tbl_body').html(response.tabla);
        }
      }
    });
  }

   function cargar_pedidos_detalle(f='')
  {
    $('#txt_tipo_filtro').val(f);
    if(f!='')
    {
      $('#titulo_detalle').text('desde: '+$('#txt_hasta').val()+' hasta: '+$('#txt_desde').val());
    }else
    {

      $('#titulo_detalle').text('');
    }
    var ruc = '<?php echo $ci; ?>';
    var nom = $('#txt_query').val();
    var ci = ruc.substring(0,10);
    var desde=$('#txt_desde').val();
      var  parametros = 
      { 
        'codigo':ci,
        'nom':$('#txt_nombre').val(),
        'query':nom,
        'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
        'desde':desde,
        'hasta':$('#txt_hasta').val(),
        'busfe':f,
      }    
     // console.log(parametros);

     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?tabla_detalles=true',
      type:  'post',
      dataType: 'json',
       beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/loader4.1.gif" width="20%"> </div>';   
          $('#tbl_detalle').html(spiner);
         },
      success:  function (response) { 
        console.log(response);
        if(response)
        {
          $('#tbl_detalle').html(response);
        }
      }
    });
  }


  function cargar_ficha()
  {
    var cod ='<?php echo $cod; ?>';
    var ci = '<?php echo $ci; ?>';
    var parametros=
    {
      'cod':cod,
      'ci':ci,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?pedido=true',
      type:  'post',
      dataType: 'json',

      success:  function (response) { 
        
          console.log(response);
        if(response)
        {
          if(cod!='0')
          {
            $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
            // $('#txt_nombre').val(response[0].Cliente);
            $('#txt_codigo').val(response[0].Matricula);
            cargar_pedidos();
          }else
          {
            var url = "../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+response[0].ORDEN+"&ci="+ci;
            $(location).attr('href',url);
          }
        }
      }
    });
  }

  function nuevo_pedido()
  {
    var cod_cli = $('#txt_codigo').val();
    var area = $('#ddl_areas').val();
    var pro = $('#txt_procedimiento').val();
    if(cod_cli!='' && area !='' && pro!='')
    {
      var href="../vista/farmacia.php?mod=Farmacia&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&cod="+cod_cli+"&area="+area+"-"+$('#txt_procedimiento').val()+"#";
      $(location).attr('href',href);
    }else
    {
      Swal.fire('','Paciente, procedimiento o Area no seleccionada.','info');
    }
  }


   function autocoplet_paci(){
      $('#ddl_paciente').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?paciente=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

  function autocoplet_area(){
      $('#ddl_areas').select2({
        placeholder: 'Seleccione una Area de descargo',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }


  function buscar_cod()
  {
      var  parametros = 
      { 
        'query':$('#ddl_paciente').val(),
        'tipo':'R1',
        'codigo':'',
      }    
      //console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response.matricula == 0)
        {
           $('#txt_codigo').val(response.matricula);
          Swal.fire({
            title: 'Este Paciente no tiene Historial!',
            text: "Desea actualizar el numero de historial clinico?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Actualizar!'
          }).then((result) => {
            if (result.value) {
              $('#num_historial').modal('show');
            }else
            {
              $('#ddl_paciente').empty();
              $('#txt_codigo').val('');
            }
          })
        }else
        {
           $('#ddl_paciente').append($('<option>',{value: response.ci, text:response.nombre,selected: true }));
           $('#txt_codigo').val(response.matricula);

        }
       
           // $('#txt_nombre').val(response[0].Cliente);
           // $('#txt_ruc').val(response[0].CI_RUC);
      }
    });
  }

  function limpiar()
  {
    
    var href="../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#";
    $(location).attr('href',href);
    $('#txt_query').val('');
    $("#ddl_paciente").empty();
    $("#txt_codigo").val('');
  }

  function actualizar_num_historia()
  {
    var num_hi = $('#txt_histo_actu').val();
    console.log(num_hi);
    var ci = $('#ddl_paciente').val();
    if(num_hi=='')
    {
      Swal.fire('','Ingrese un numero de historial.','info');
      return false;
    }
    $('#txt_codigo').val(num_hi);
     var  parametros = 
      { 
        'ci':ci,
        'num':num_hi,
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?actualizar_his=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
           var href="../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+num_hi+"&ci="+ci+"#";
           $(location).attr('href',href);
        }else if(response == -2)
        {
           Swal.fire('','Numero ingresado ya esta registrado.','info');
        }
      }
    });


  }

  function eliminar_pedido(ped,area)
  {
    // console.log(cli);
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'area':area,
              'ped':ped,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?eli_pedido=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedidos();
                }
              }
            });
        }
      });
  }
function reporte_pdf()
{
   var url = '../controlador/farmacia/reporte_descargos_procesadosC.php?imprimir_pdf=true&';
   var datos =  $("#filtro_bus").serialize();
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

function formatoEgreso()
{
   var url = '../controlador/farmacia/reporte_descargos_procesadosC.php?formatoEgreso=true&';
   var datos =  $("#filtro_bus").serialize();
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

function reporte_excel()
{
   var url = '../controlador/farmacia/reporte_descargos_procesadosC.php?imprimir_excel=true&';
   var datos =  $("#filtro_bus").serialize();
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


function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}
function Ver_detalle(comprobante)
{
    url='../vista/farmacia.php?mod=Farmacia&acc=facturacion_insumos&acc1=Utilidad insumos&b=1&po=subcu&comprobante='+comprobante;
    window.open(url, '_blank');
}


</script>

  <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>        
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar excel" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
<div class="row">
 
  <div class="col-sm-12">
      <div class="col-sm-8"> 
        <form method="post" id="filtro_bus" enctype="multipart/form-data">
          <div class="col-sm-6">
             <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"> Nombre</label>
             <br>
            <b>NOMBRE DE PACIENTE</b>
            <input type="text" name="txt_query" id="txt_query" class="form-control" placeholder="Nombre paciente" onkeypress="cargar_pedidos()" onblur="cargar_pedidos();cargar_pedidos_detalle()">
          </div>
          <div class="col-sm-3">
            <br>
            <b>FECHA INICIO</b>
            <input type="date" name="txt_desde" id="txt_desde" class="form-control" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
          </div>
          <div class="col-sm-3">
            <br>
            <b>FECHA FIN</b>
            <input type="date" name="txt_hasta" id="txt_hasta" class="form-control" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
          </div>
            <input type="hidden" name="txt_tipo_filtro" id="txt_tipo_filtro" value=""> 
        </form>   
    </div>
    <div class="col-sm-4">
       <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
        <button type="button" class="btn btn-success" onclick="nuevo_pedido()"><i class="fa fa-plus"></i> Nuevo Descargos</button>
      </div>
    </div>   
  </div>
  <div class="col-sm-12">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">Descargos Realizados</a></li>
      <li><a data-toggle="tab" href="#menu1">Detalle de descargos</a></li>
    </ul>
    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
       
         <div class="row">
            <div class="col-sm-12" id="tbl_body">
           
            </div>      
          </div>

         
      </div>
      <div id="menu1" class="tab-pane fade">
        <div class="row">
          <div class="col-sm-12 text-center">
              <h4><b id="titulo_detalle"></b></h4>
          </div>
          <div class="col-sm-12" >
            <br>
            <table class="table table-hover" id="tbl_detalle">
             
            </table>
            
          </div>
        </div>
      </div>
    </div>
  
</div>


<div id="num_historial" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Numero historial</h4>
      </div>
      <div class="modal-body">
        <form>
        <div class="row text-center">
          <div class="col-sm-12">
            <b>Numero de Historia clinica</b>
            <input type="txt_nombre" name="txt_histo_actu" id = "txt_histo_actu" class="form-control input-sm">  
          </div> 
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="actualizar_num_historia()">Guardar</button>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>

