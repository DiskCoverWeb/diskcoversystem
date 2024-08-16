<script type="text/javascript">

  $(document).ready(function () {
    pedidos_solicitados();
    lineas_pedido_solicitados();
  })

  function pedidos_solicitados()
  {
    $('#ddl_pedidos').select2({
        placeholder: 'Seleccione',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/solicitud_materialC.php?pedidos_solicitados=true',
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

  function grabar_solicitud_proveedor()
  {   
    if($('#ddl_pedidos').val()=='')
    {
      Swal.fire("Seleccione un pedido","","info")
      return false;
    }
    var parametros = 
    {
      'pedido':$('#ddl_pedidos').val(),
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


</script>
<section class="content">
  <div class="row">
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-9">
          <div class="col-xs-2 col-md-2 col-sm-2">
            <a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
          </div>
         <!--  <div class="col-xs-2 col-md-2 col-sm-2">
            <button type="button" class="btn btn-default" title="Copiar Catalogo" onclick="mostrarModalPass()" >
              <img src="../../img/png/copiar_1.png">
            </button>
          </div>
          <div class="col-xs-2 col-md-2 col-sm-2">                 
            <button type="button" class="btn btn-default" title="Cambiar Cuentas" onclick="validar_cambiar()">
              <img src="../../img/png/pbcs.png">
            </button>
          </div> -->
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
          <b>Numero de orden </b>
          <select class="form-control" id="ddl_pedidos" name="ddl_pedidos" onchange="lineas_pedido_solicitados(this.value)">
            <option value="">Seleccione pedidos</option>
          </select>
        </div>
         <div class="col-sm-3">
          <b>Contratista</b><br>
          <span><?php echo $_SESSION['INGRESO']['Nombre']; ?></span>
        </div>
       
        
      </div>  
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
        <table class="table">
          <thead>
            <thead>
              <th>item</th>
              <th>Codigo</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Fecha</th>
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
  </div>  
</section>
