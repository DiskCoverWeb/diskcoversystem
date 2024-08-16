<script type="text/javascript">

  $(document).ready(function () {
    productos()
    linea_pedido();
  })
  

  function productos()
  {
    $('#ddl_productos').select2({
        placeholder: 'Seleccione',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/solicitud_materialC.php?productos=true',
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

  function guardar_linea()
  {
     cant = $('#txt_cantidad').val();
     prod = $('#ddl_productos').val();

     if(cant==0 || cant =='')
     {
       Swal.fire("Cantidad no valida",'','info');
       return false;
     }

     if(prod=='')
     {
       Swal.fire("Seleccione un producto",'','info');
       return false;      
     }
     var parametros = 
     {
        'cantidad':cant,
        'productos':prod,
        'fecha':$('#txt_fecha').val(),
     }

      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_linea=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Agregado","","success");
               linea_pedido();
            }
          
          }
      });
  }

  function linea_pedido()
  {     
      var parametros = 
      {
        'fecha': $('#txt_fecha').val(),
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?linea_pedido=true',
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

  
  function grabar_solicitud()
  {   
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_solicitud=true',
          type:  'post',
          // data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Registro Guardado","","success").then(function(){
                location.reload();
               });
               // linea_pedido();
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
            <button title="Guardar"  class="btn btn-default" onclick="grabar_solicitud()">
              <img src="../../img/png/grabar.png" >
            </button>
          </div>
      </div>
  </div>
  <br>
  <div class="row">
    <div class="box">
      <div class="box-body">
         <div class="col-sm-3">
          <b>Contratista</b><br>
          <span><?php echo $_SESSION['INGRESO']['Nombre']; ?></span>
        </div>
         <div class="col-sm-2">
          <b>Fecha</b>
          <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" readonly >
        </div>
        <div class="col-xs-4">
          <b>Producto / articulo </b>
          <select class="form-control" id="ddl_productos" name="ddl_productos">
            <option value="">Seleccione producto</option>
          </select>
        </div>
        <div class="col-sm-1">
          <b>Cantidad</b>
          <input type="text" name="txt_cantidad" id="txt_cantidad" class="form-control input-sm" placeholder="0" >
        </div>
        <div class="col-sm-2">
          <br>
            <button type="button" class="bt  btn-sm btn-primary" onclick="guardar_linea()" >Agregar</button>
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
