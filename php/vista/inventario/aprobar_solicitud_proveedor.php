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
      lineas_pedido_aprobacion_solicitados_proveedor(orden)
    }  
  })

  function pedidos_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?pedido_aprobacion_solicitados_proveedor=true',
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

  // function pedidos_solicitados()
  // {
  //   $('#ddl_pedidos').select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           url:   '../controlador/inventario/solicitud_materialC.php?pedido_aprobacion_solicitados_proveedor=true',
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

  // function llenarProveedores(selector)
  // {
  //    $('#'+selector).select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           url:   '../controlador/inventario/solicitud_materialC.php?lista_proveedores=true',
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

  function lineas_pedido_aprobacion_solicitados_proveedor(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lineas_pedido_aprobacion_solicitados_proveedor=true',
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

  function grabar_compra_pedido()
  {
    parametros = 
    {
      'orden':$('#lbl_orden').text()
    }

      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_compra_pedido=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            $('#myModal_espera').modal('hide');
            if(response==1)
            {
               Swal.fire("Registro guardado","","success").then(function(){
                location.reload();
               });
            }
             if(response==-2)
            {
               Swal.fire("Seleccione los proveedores de todos los articulos","","error")
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
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf_proveedor=true&orden_pdf='+orden,'_blank');
  }
  function imprimir_excel()
  {

    var orden = '<?php echo $orden; ?>';
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel_proveedor=true&orden_pdf='+orden,'_blank');
  }

  function mostrar_proveedor(id,codigo,orden)
  {
    $('#myModal_provedor').modal('show');
    $('#txt_id_linea').val(id);
    parametros = 
    {
      'orden':orden,
      'codigo':codigo,
    }
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lista_provee=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            $('#ddl_proveedores_list').html(response.option);
            $('#txt_costoAnt').val(response.CostoTotal);
           
          
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });
  }

  function guardar_seleccion_proveedor(codigo,orden)
  {
    costo = $('#txt_costoAct').val()
    var ord = '<?php echo $orden; ?>';
    if(costo=='')
    {

        Swal.fire("El costo no puede estar vacio","","info")
      return false;
    }
    parametros = 
    {
      'idProducto':$('#txt_id_linea').val(),
      'CodigoC':$('#ddl_proveedores_list').val(),
      'costo':$('#txt_costoAct').val(),
    }
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_seleccion_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
              if(response==1)
              {
                Swal.fire("Proveedor Asignado","","success")
                $('#myModal_provedor').modal('hide');
                $('#txt_costoAct').val('')
                $('#txt_costoAnt').val('')
                lineas_pedido_aprobacion_solicitados_proveedor(ord)
              }
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });
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
            <button type="button" class="btn btn-default" title="informe Excel" onclick="imprimir_excel()" >
              <img src="../../img/png/excel2.png">
            </button>
          </div>    
         <div class="col-xs-2 col-md-2 col-sm-2">                 
            <button type="button" class="btn btn-default" title="Informe pdf" onclick="imprimir_pdf()">
              <img src="../../img/png/pdf.png">
            </button>
          </div>  
           
          <div class="col-xs-2 col-md-2 col-sm-2">
            <button title="Guardar"  class="btn btn-default" onclick="grabar_compra_pedido()">
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
        <table class="table">
          <thead>
            <thead>
              <th>item</th>
              <th>Codigo</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio</th>
              <th>Fecha Solicitud</th>
              <th>Fecha Entrega</th>
              <th>Total</th>
              <th>Observacion</th>
              <th width="28%">Proveedores proforma</th>
              <th>Proveedor Seleccionado</th>
              <!-- <th></th> -->
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

<div id="myModal_provedor" class="modal fade myModal_provedor" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Seleccionar proveedor</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
              <input type="hidden" name="txt_id_linea" id="txt_id_linea">
              <div class="row">
                <div class="col-sm-12">
                  <b>Seleccione proveedor</b>
                  <select class="form-control input-sm" id="ddl_proveedores_list">
                    <option>Seleccionar</option>
                  </select>
                </div>
                 <div class="col-sm-12">
                  <b>Costo Anterior</b>
                 <input type="text" class="form-control input-sm" name="txt_costoAnt" id="txt_costoAnt" readonly>
                </div>
                 <div class="col-sm-12">
                  <b>Costo de proveedor</b>
                  <input type="text" class="form-control input-sm" name="txt_costoAct" id="txt_costoAct">
                </div>
              </div>
               
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_seleccion_proveedor()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>