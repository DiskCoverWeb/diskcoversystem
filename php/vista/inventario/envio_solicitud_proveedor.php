<?php
 $orden = '';
if(isset($_GET['orden']))
{
  $orden = $_GET['orden'];
}

?>
<script type="text/javascript">

  $(document).ready(function () {   
   var orden = '<?php echo $orden; ?>';
    if(orden!='')
    {
        pedidos_solicitados(orden);
       lineas_pedido_solicitados_proveedor(orden)
    }

  })

  // function pedidos_solicitados()
  // {
  //   $('#ddl_pedidos').select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           dataType: 'json',
  //           delay: 250,
  //           processResults: function (data) {
  //             // console.log(data);
  //             return {
  //               results: data
  //           };
  //       },
  //       cache: true
  //     }
  //   });
  // }

  function pedidos_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?pedido_solicitados_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {   

            $('#lbl_orden').text(response[0].Orden_No);   
            $('#lbl_contratista').text(response[0].Cliente);   
            $('#lbl_total').text(response[0].Total);   

          // $('#').text(response.)   
          // console.log(response);                  
          }
      });
  }  

  function lineas_pedido_solicitados_proveedor(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lineas_pedido_solicitados_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);     

                 $('.select2_prove').select2({
                      placeholder: 'Seleccione',
                      width:'100%',
                      ajax: {
                          url:   '../controlador/inventario/solicitud_materialC.php?lista_proveedores=true',
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
      });
  }


  function eliminar_linea(id)
  {
      Swal.fire({
        title: 'Esta seguro?',
        text: "Esta usted seguro de que quiere borrar este registro!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
     }).then((result) => {
          if (result.value==true) {
              eliminar(id);
          }
    })
  }

  function eliminar(id)
  {     
      var parametros = 
      {
        'id': id,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?eliminar_linea=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Registro eliminado","","success");
               linea_pedido();
            }
          
          }
      });
  }

  function grabar_envio_solicitud()
  {

    var selects = document.querySelectorAll('#form_lineas select');
    var todosSeleccionados = true;

    // Iterar sobre cada select y verificar si está seleccionado
    selects.forEach(function(select) {
        if (select.value === '') {
            todosSeleccionados = false;
           
        } 
      });

    if(todosSeleccionados==false)
    {
      Swal.fire("Seleccione los proveedores para todas las lineas","","info")
      return false;
    }


     $('#myModal_espera').modal('show');
      form = $('#form_lineas').serialize();
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_envio_solicitud=true',
          type:  'post',
          data: form,
          dataType: 'json',
          success:  function (response) {
            $('#myModal_espera').modal('hide');
            if(response==1)
            {
               Swal.fire("Registro guardado","","success").then(function(){
                location.reload();
               });
            }
          
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });
  }
  function imprimir_pdf()
  {

     var orden = '<?php echo $orden; ?>';
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf_envio=true&orden_pdf='+orden,'_blank');
  }
  function imprimir_excel()
  {
     var orden = '<?php echo $orden; ?>';
     window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel_envio=true&orden_pdf='+orden,'_blank');
  }

  function lineaSolProv(linea)
  {
    $('#txt_linea_Select').val(linea);
  }

  function usar_cliente(nombre, ruc, codigocliente, email, T,grupo)
  {
    linea = $('#txt_linea_Select').val();
    $('#ddl_selector_'+linea).append($('<option>',{value:  codigocliente, text: nombre,selected: true }));
    $('#myModal').modal('hide');
  }

</script>
<section class="content">
  <div class="row">
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-9">
          <div class="col-xs-2 col-md-2 col-sm-2">
            <a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
          </div>
           <div class="col-xs-2 col-md-2 col-sm-2">
            <button type="button" class="btn btn-default" title="Informe pdf" onclick="imprimir_pdf()" >
              <img src="../../img/png/pdf.png">
            </button>
          </div>
          <div class="col-xs-2 col-md-2 col-sm-2">                 
            <button type="button" class="btn btn-default" title="Informe excel" onclick="imprimir_excel()">
              <img src="../../img/png/excel2.png">
            </button>
          </div>
          <div class="col-xs-2 col-md-2 col-sm-2">
            <button title="Guardar"  class="btn btn-default" onclick="grabar_envio_solicitud()">
              <img src="../../img/png/grabar.png" >
            </button>
          </div>
      </div>
  </div>
  <br>
  <div class="row">
    <div class="box">
      <div class="box-body">
        <div class="col-xs-4">
          <b>Numero de orden </b><br>
          <span id="lbl_orden"></span>
        </div>
         <div class="col-sm-3">
          <b>Contratista</b><br>
          <span id="lbl_contratista"></span>
        </div>
        <div class="col-sm-3">
          <b>Total</b><br>
          <span id="lbl_total"></span>
        </div>
       
        
      </div>  
    </div>
  </div>
  <div class="row">
    <form id="form_lineas">
    <div class="col-sm-12">
      <input type="hidden" name="txt_linea_Select" id="txt_linea_Select" value="">
        <table class="table">
          <thead>
            <thead>
              <th>item</th>
              <th>Codigo</th>
              <th>Producto</th>
              <th>Cant</th>
              <th>Unidad</th>
              <th>Costo</th>
              <th>Fecha solicitud</th>
              <th>Fecha Entrega</th>
              <th>Total</th>
              <th>Observacion</th>
              <th>Proveedores</th>
            </thead>
            <tbody id="tbl_body">
             <!--  <tr>
                <td>1</td>
                <td>ss</td>
                <td>producto</td>
                <td>3</td>
                <td>2024-05-06</td>
                <td>
                  <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </td>
              </tr> -->
            </tbody>
          </thead>
        </table>
    </div>    
    </form>
  </div>  
</section>
