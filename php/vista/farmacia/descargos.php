<?php  $cod = ''; $ci =''; if(isset($_GET['cod'])){$cod = $_GET['cod'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}  unset($_SESSION['NEGATIVOS']);?>
<script type="text/javascript">
   $( document ).ready(function() {
    cargar_pedidos();
    cargar_ficha();
    autocoplet_paci();
    autocoplet_area();
    autocoplet_desc();
    // cargar_ficha();
  });

    function autocoplet_desc(){
      $('#ddl_articulo').select2({
        placeholder: 'Escriba Descripcion',
        width:'90%',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=desc',
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

  function cargar_pedidos(f='')
  {
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
        'area':$('#txt_area').val(),
        'arti':$('#ddl_articulo').val(),
        'nega':$('#rbl_negativos').prop('checked'),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
                $("#tbl_body").html('<tr class="text-center"><td colspan="7"><img src="../../img/gif/loader4.1.gif" width="25%"></td></tr>');
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
        'area':$('#txt_area').val(),
        'arti':$('#ddl_articulo').val(),
        'busfe':f,
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?tabla_detalles=true',
      type:  'post',
      dataType: 'json',
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
      Swal.fire('Paciente, procedimiento o Area no seleccionada.','','info');
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
      Swal.fire('Ingrese un numero de historial.','','info');
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
      url:   '../controlador/farmacia/descargosC.php?actualizar_his=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
           var href="../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+num_hi+"&ci="+ci+"#";
           $(location).attr('href',href);
        }else if(response == -2)
        {
           Swal.fire('Numero ingresado ya esta registrado.','','info');
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
              url:   '../controlador/farmacia/descargosC.php?eli_pedido=true',
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
   var url = '../controlador/farmacia/descargosC.php?imprimir_pdf=true&';
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
   var url = '../controlador/farmacia/descargosC.php?imprimir_excel=true&';
   var datos =  $("#filtro_bus").serialize();
    window.open(url+datos, '_blank');
     // $.ajax({
     //     data:  {datos:datos},
     //     url:   url,
     //     type:  'post',
     //     dataType: 'json',
     //     success:  function (response) {  
          
     //      } 
     //   });

}

function reporte_excel_nega()
{
   var url = '../controlador/farmacia/descargosC.php?imprimir_excel_nega=true&';
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

function reporte_pdf_nega()
{
   var url = '../controlador/farmacia/descargosC.php?imprimir_pdf_nega=true&';
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

 function mayorizar_inventario()
  {
    $('#myModal_espera').modal('show');
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?mayorizar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#myModal_espera').modal('hide');
        Swal.fire('Mayorizacion completada','','success');
      
      }
    });
  }


</script>

  <div class="row">
    <div class="col-lg-8 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
     
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <img src="../../img/png/pdf.png">
              <span class="caret"></span>
            </button>
             <ul class="dropdown-menu" role="menu" id="year">
              <li><a href="#" onclick="reporte_pdf()"> Descargos</a></li>
              <li><a href="#" onclick="reporte_pdf_nega()"> Descargos en Negativos</a></li>
            </ul>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <img src="../../img/png/table_excel.png">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" id="year">
              <li><a href="#" onclick="reporte_excel()"> Descargos</a></li>
              <li><a href="#" onclick="reporte_excel_nega()"> Descargos en Negativos</a></li>
            </ul>
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
          <button title="Mayorizar Articulos"  class="btn btn-default" onclick="mayorizar_inventario()">
            <img src="../../img/png/update.png" >
          </button>
        </div>    
 </div>
</div>
<br>
<div class="row">
  <div class="col-sm-12">
     <div class="panel panel-primary">
      <div class="panel-heading text-center"><b>RESCARGOS REALIZADOS</b></div>
      <div class="panel-body">
      	<div class="row">
          <div class="col-sm-4">
            <b>Num. Historia Clinica :</b>
            <input type="text" class="form-control input-sm" readonly="" id="txt_codigo"value="<?php echo $cod; ?>">
          </div>
          <div class="col-sm-8">
            <b>Nombre</b>
            <div class="input-group">
                <select class="form-control input-sm" id="ddl_paciente" onchange="buscar_cod()">
                  <option value="">Seleccione paciente</option>
                </select>
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
            </div>
          </div>  
        </div>
        <div class="row">
          <div class="col-sm-6">
            <b>Area de descargo</b>
            <select class="form-control input-sm" id="ddl_areas">
              <option value="">Seleccione area de ingreso</option>
            </select>            
          </div>
          <div class="col-sm-6">
            <b>Procedimiento</b>
            <input type="text" name="" class="form-control input-sm" name="txt_procedimiento" id="txt_procedimiento">
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-12 text-right">
            <button type="button" class="btn btn-primary" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
            <button type="button" class="btn btn-success" onclick="nuevo_pedido()"><i class="fa fa-plus"></i> Nuevo Descargos</button>
          </div> 
        </div>        
      </div>
    </div>
  </div>
  <div class="col-sm-12">
        <form method="post" id="filtro_bus" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-5">
              <b>NOMBRE DE PACIENTE</b>
              <div class="pull-right"  name="txt_codigo" id="txt_codigo" >
                <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"> Nombre</label>
                 <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="C"> CI / RUC</label>
                 <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_pedido" value="P"> Pedido</label>
              </div>
              <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Nombre paciente" onkeyup="cargar_pedidos()">
            </div>
            <div class="col-sm-2">
              <b>FECHA INICIO</b>
              <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
            </div>
            <div class="col-sm-2">
              <b>FECHA FIN</b>
              <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
            </div>
            <div class="col-sm-3">
              <b>Area de descargo</b>
              <input type="text" name="txt_area" id="txt_area" class="form-control form-control-sm" value="" onkeyup="cargar_pedidos();cargar_pedidos_detalle()">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-5">
              
            </div>
            <div class="col-sm-7">
              <b>Articulo</b>
              <div class="row">
                <div class=" col-sm-12 input-group">
                   <select class="form-control" id="ddl_articulo" name="ddl_articulo" onchange="cargar_pedidos();cargar_pedidos_detalle()">
                   <option value="">Seleccione producto</option>
                </select>
                <span>
                  <button type="button" class="btn btn-default btn-flat" onclick="$('#ddl_articulo').val(null).trigger('change');"><i class="fa fa-close"></i></button>
                </span>    
                  
                </div>                           
              </div>
              




            </div>
          </div>

          <input type="hidden" name="txt_tipo_filtro" id="txt_tipo_filtro" value=""> 
        </form>      
  </div>
  <div class="col-sm-12">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">Descargos Realizados</a></li>
      <li><a data-toggle="tab" href="#menu1">Detalle de descargos</a></li>
    </ul>
    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <div class="row">
          <div class="col-sm-12 text-right">
            <label><input type="checkbox" name="rbl_negativos" id="rbl_negativos" onclick="cargar_pedidos()"> Mostrar pedidos en negativo?</label>
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-12">
           <div class="table-responsive">      
             <table class="table table-hover">
               <thead>
                 <th>ITEM</th>
                 <th>NUM PEDIDO</th>
                 <th>PACIENTE</th>
                 <th>AREA INGRESO</th>
                 <th>IMPORTE</th>
                 <th>FECHA</th>
                 <th>ESTADO</th>
                 <th></th>
               </thead>
               <tbody id="tbl_body">
          
               </tbody>
             </table>      
           </div>
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
              <!-- <tr>
                <td colspan="2"><b>NOMBRE:</b></td>
                <td><b>PROCEDIMIENTO:</b></td>
                <td><b>AREA:</b></td>
                <td><b>No. DESCARGO</b></td>                
              </tr>
              <tr>
                <td colspan="5"><b>FECHA DE DESCARGO:</b></td>
              </tr>
              <tr>
                <td><b>CODIGO</b></td>
                <td><b>PRODUCTO</b></td>
                <td><b>CANTIDAD</b></td>
                <td><b>VALOR UNI</b></td>
                <td><b>VALOR TOTAL</b></td>
              </tr> -->
            </table>
            
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
            <input type="text" name="txt_histo_actu" id = "txt_histo_actu" class="form-control input-sm">  
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

