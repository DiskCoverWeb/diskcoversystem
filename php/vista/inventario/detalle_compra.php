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
      pedidos_compra_solicitados(orden);
      lineas_compras_solicitados(orden);
    }
  })

  function pedidos_compra_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?pedidos_compra_solicitados=true',
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

  function lineas_compras_solicitados(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?lineas_compras_solicitados=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);                     
          }
      });
  }


 
function imprimir_pdf()
{ 
  var orden = '<?php echo $orden; ?>';
  window.open('../controlador/inventario/lista_comprasC.php?imprimir_pdf=true&orden_pdf='+orden,'_blank');
}

function imprimir_excel()
{ 
  var orden = '<?php echo $orden; ?>';
  window.open('../controlador/inventario/lista_comprasC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
}

function grabar_kardex()
{
  var orden = '<?php echo $orden; ?>';
  var parametros = 
      {
        'orden':orden,
      }
    //  $('#myModal_espera').modal('show');
      $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?grabar_kardex=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {   
            $('#myModal_espera').modal('hide');
            if(response.resp==1)
            {
              Swal.fire(response.com,"","success").then(function(){
                location.href = 'inicio.php?mod=03&acc=lista_compras';
              })
            }  
                              
          }
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
            <button title="Guardar"  class="btn btn-default" onclick="grabar_kardex()">
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
              <th>Costo</th>
              <th>Fecha Solicitud</th>
              <th>Fecha Entrega</th>
              <th>Total</th>
              <th>Proveedor</th>
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
