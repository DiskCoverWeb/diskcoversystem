<?php  $cod='';if(isset($_GET['comprobante'])){$cod =$_GET['comprobante'];} $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
   $( document ).ready(function() {
    // autocoplet_paci();
    // autocoplet_ref();
    // autocoplet_desc();
    // autocoplet_cc();
    // autocoplet_area();
    num_comprobante();
    //  // buscar_cod();
    // if(c!='')
    // {
    //   buscar_codi();
    // }
    // if(area !='')
    // {
    //   buscar_Subcuenta();
    // }
    var num_li=0;
    cargar_pedido();
    lista_devolucion();

  });



 
  // function buscar_cod()
  // {
  //     var  parametros = 
  //     { 
  //       'query':$('#ddl_paciente').val(),
  //       'tipo':'R1',
  //       'codigo':'',
  //     }    
  //     // console.log(parametros);
  //    $.ajax({
  //     data:  {parametros:parametros},
  //     url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
  //     type:  'post',
  //     dataType: 'json',
  //     success:  function (response) { 
  //       // console.log(response);
  //       if(response != -1){       
  //          $('#txt_codigo').val(response[0].Matricula);
  //          $('#txt_nombre').val(response[0].Cliente);
  //          $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
  //          $('#txt_ruc').val(response[0].CI_RUC);
  //        }
  //     }
  //   });
  // }

  //  function buscar_codi()
  // {
  //     var  parametros = 
  //     { 
  //       'query':'<?php echo $cod; ?>',
  //       'tipo':'C1',
  //       'codigo':'',
  //     }    
  //     // console.log(parametros);
  //    $.ajax({
  //     data:  {parametros:parametros},
  //     url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
  //     type:  'post',
  //     dataType: 'json',
  //     success:  function (response) { 
  //       // console.log(response);
       
  //          $('#txt_codigo').val(response.matricula);
  //          $('#txt_nombre').val(response.nombre);
  //          $('#ddl_paciente').append($('<option>',{value: response.ci, text:response.nombre,selected: true }));
  //          $('#txt_ruc').val(response.ci);
  //     }
  //   });
  // }






  function cargar_pedido()
  {   
    $('#modal_espera').modal('show');
    var comprobante = '<?php echo $cod; ?>';  
    var query = $('#txt_query').val();    
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante,query:query},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?datos_comprobante=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 

    $('#modal_espera').modal('hide');
      	console.log(response);
        if(response)
        {
          $('#tbl_body').html(response.tabla);
          $('#paciente').val(response.cliente[0].Cliente);
          $('#cod').val(response.cliente[0].CodigoL);
          $('#detalle').text(response.cliente[0].Concepto);
          $('#fecha').text(response.cliente[0].Fecha.date);
          $('#comp').val(response.cliente[0].Numero);
          num_li = response.lineas;
        }
      }
    });
  }

function lista_devolucion()
  {   
    var comprobante = '<?php echo $cod; ?>';  
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?lista_devolucion=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        $('#tbl_devoluciones').html(response.tr);
        $('#lineas').val(response.lineas)
      }
    });
  }




  function costo(codigo,id)
  {    
     $.ajax({
      data:  {codigo:codigo},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?costo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#txt_valor_'+id).val(response[0].Costo.toFixed(2));
        var Costo = response[0].Costo;
        var devolucion = $('#txt_cant_dev_'+id).val();
        var tot = Costo*devolucion;
        $('#txt_gran_t_'+id).val(tot.toFixed(2));
         var total =0; 
         for (var i =1 ; i < num_li+1; i++){
            total+=parseFloat($('#txt_gran_t_'+i).val());       
         }
         $('#txt_tt').text(total.toFixed(2));
      }
    });
  }


  function calcular_dev(id)
  {
    var salida = parseFloat($('#txt_salida_'+id).val());
    var devolucion = parseFloat($('#txt_cant_dev_'+id).val());
    if(devolucion>salida)
    {
      Swal.fire('la devolucion no debe ser mayor a la cantidad de salida','','info');
      $('#txt_cant_dev_'+id).val(salida);
      var codigo = $('#codigo_'+id).text();
      costo(codigo,id);
      return false;
    }
    if(devolucion ==0)
    {
      $('#txt_valor_'+id).val(0); 
      $('#txt_gran_t_'+id).val(0);
      var total =0; 
         for (var i =1 ; i < num_li+1; i++){
            total+=parseFloat($('#txt_gran_t_'+i).val());       
         }
         $('#txt_tt').text(total.toFixed(2));
    }else
    {
       var codigo = $('#codigo_'+id).text();
       costo(codigo,id);
    }
   

  }


   function num_comprobante()
   {
    var fecha = $('#txt_fecha').val();
     $.ajax({
       data:  {fecha:fecha},
      url:   '../controlador/farmacia/articulosC.php?num_com=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#num').text(response);
      }
    });

   }


   function guardar_devolucion(linea,id)
   {
    var parametros = 
    {
      'codigo':$('#codigo_'+id).text(),
      'producto':$('#producto_'+id).text(),
      'cantidad':$('#txt_cant_dev_'+id).val(),
      'precio':$('#txt_valor_'+id).val(),
      'total':$('#txt_gran_t_'+id).val(),
      'comprobante': '<?php echo $cod; ?>',  
      'linea':linea,
    }
    if( $('#txt_cant_dev_'+id).val() == 0 || $('#txt_valor_'+id).val()==0 || $('#txt_gran_t_'+id).val() ==0)
    {
      // Swal.fire('Asegurese que los totales y la cantidad no sean igual a cero','','info');
      return false;
    }

    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?guardar_devolucion=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Agregado a lista de devoluciones','','success');
          lista_devolucion();
          cargar_pedido();
         }
        }
      });

   }

    function Eliminar(comp,codigo)
  {
       Swal.fire({
      title: 'Esta seguro de eliminar este registro?',
      text:  "No se eliminara el registro seleccionado",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
         Eliminar_linea(comp,codigo)
        }
      })
  }

   function Eliminar_linea(comp,codigo)
   {
    var parametros = 
    {
      'codigo':codigo,
      'comprobante': comp,  
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?eliminar_linea_dev=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Devolucion eliminada','','success');
          lista_devolucion();
          cargar_pedido();
         }
        }
      });

   }

  function generar_factura(numero)
   {
    var prove = $('#cod').val();
    // $('#myModal_espera').modal('show');  
     var parametros = 
     {
      'num_fact':numero,
      'prove':prove,
      'iva_exist':0,
     }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?generar_factura=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);

       $('#myModal_espera').modal('hide');  
       if(response.resp==1)
        {
          Swal.fire('Comprobante '+response.com+' generado.','','success'); 
          lista_devolucion();
          cargar_pedido();
        }else if(response.resp==-2)
        {
          Swal.fire('Asegurese de tener una cuenta Cta_Iva_Inventario.','','info'); 
        }else if(response.resp==-3)
        {
          Swal.fire('','Esta factura Tiene dos  o mas fechas','info'); 
          lista_devolucion();
          cargar_pedido();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });
     // console.log(datos);
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
          <button href="#" title="Generar reporte pdf"  class="btn btn-default" onclick="generar_informe()">
            <img src="../../img/png/impresora.png" >
          </button>
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
            
 </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="row">
           <div class="col-sm-6 text-right"><b>Devoluciones de insumos</b></div>         
           <div class="col-sm-6 text-right"> No. COMPROBANTE  <u id="num"></u></div>        
        </div>
      </div>
      <div class="panel-body" style="border: 1px solid #337ab7;">
        <div class="row">         
          <div class="col-sm-2"> 
            <b>Comprobante:</b>
            <input type="text" name="comp" id="comp" class="form-control input-sm" readonly="">      
          </div>
          <div class="col-sm-4">
            <b>Nombre:</b>
            <input type="text" name="paciente" id="paciente" class="form-control input-sm">
            <input type="hidden" name="cod" id="cod">
          </div>
          <div class="col-sm-6">
            <b>Detalle:</b>
            <textarea class="form-control" id="detalle" readonly="" rows="2"></textarea>            
          </div>          
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
      <div class="col-sm-6">
        <b>Buscar medicamento</b>
        <input type="text" name="txt_query" id="txt_query" class="form-control input-sm" placeholder="Buscar medicamento" onkeyup="cargar_pedido()">
      </div>
      <div class="col-sm-6 text-right">
        <button id="" class="btn btn-primary" onclick="generar_factura('<?php echo $cod;?>')">Generar devolucion</button> 
      </div>
  </div><br>
  <div class="col-sm-12">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Descargos Realizados</a></li>
        <li><a data-toggle="tab" href="#menu1">Lista de devoluciones</a></li>
    </ul>
    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <div class="table-responsive">
          <input type="hidden" name="" id="txt_num_lin" value="0">
          <input type="hidden" name="" id="txt_num_item" value="0">
          <input type="hidden" name="txt_neg" id="txt_neg" value="false">
          <div class="col-sm-12"> 
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <th>Codigo</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Uni</th>
                    <th>Precio Total</th>
                    <th>cant devolver</th>
                    <th>Valor</th>
                    <th>Total devolucion</th>
                    <th></th>
                  </thead>
                  <tbody id="tbl_body">
                  </tbody>
                </table>
              </div>
          </div>         
        </div>
      </div>
      <div id="menu1" class="tab-pane fade in">
        <div class="col-sm-12" id="tbl_devoluciones">
          
        </div>
      </div>

         
      </div>
    
    </div>
  
</div>
</div>

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control input-sm" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar Todo</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
