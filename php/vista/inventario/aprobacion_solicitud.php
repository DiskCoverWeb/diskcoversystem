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
      lineas_pedido_solicitados(orden);
    }
  })

  function pedidos_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?pedidos_solicitados=true',
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

  function lineas_pedido_solicitados(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lineas_pedido_solicitados=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);                     
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

    var orden = '<?php echo $orden; ?>';
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
                pedidos_solicitados(orden);
                lineas_pedido_solicitados(orden);
            }
          
          }
      });
  }

  function grabar_solicitud_proveedor()
  {   

     data = $('#form_aprobacion').serialize();
    // if($('#ddl_pedidos').val()=='')
    // {
    //   Swal.fire("Seleccione un pedido","","info")
    //   return false;
    // }

     // console.log(data);
     // return false;
    var parametros = 
    {
      'pedido':$('#lbl_orden').text(),
      'aprobacion':data,
    }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_solicitud_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
              location.reload();
            }
          
          }
      });
  }

  function guardar_linea_aprobacion(id)
  {

              
    var orden = '<?php echo $orden; ?>';
    var parametros = 
    {
      'id_linea':id,
      'cantida':$('#txt_cant_'+id).val(),
    }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_linea_aprobacion=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
                Swal.fire("Linea Editada","","success")
                pedidos_solicitados(orden);
                lineas_pedido_solicitados(orden);
            }
          
          }
      });
  }
function imprimir_pdf()
{ 
  var orden = '<?php echo $orden; ?>';
  window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf=true&orden_pdf='+orden,'_blank');
}

function imprimir_excel()
{ 
  var orden = '<?php echo $orden; ?>';
  window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
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
            <button type="button" class="btn btn-default" title="Informe excel" onclick="imprimir_excel()" >
              <img src="../../img/png/excel2.png">
            </button>
          </div> 
          <div class="col-xs-2 col-md-2 col-sm-2">                 
            <button type="button" class="btn btn-default" title="Informe pdf" onclick="imprimir_pdf()">
              <img src="../../img/png/pdf.png">
            </button>
          </div>  
          <div class="col-xs-2 col-md-2 col-sm-2">
            <button title="Guardar"  class="btn btn-default" onclick="grabar_solicitud_proveedor()">
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
    <form id="form_aprobacion">
    <div class="col-sm-12">
        <table class="table">
          <thead>
            <thead>
              <th>item</th>
              <th>Codigo</th>
              <th>Producto</th>
              <th>Cantidad</th>  
              <th>Unidad</th>              
              <th>Costo</th>
              <th>Fecha Solicitud</th>
              <th>Fecha Entrega</th>
              <th>Total</th>
              <th>Observacion</th>
              <th>Aprobado</th>
              <th></th>
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
