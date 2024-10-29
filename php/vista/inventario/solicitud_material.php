<script type="text/javascript">

  $(document).ready(function () {
    productos();
    // familias();
    // $('#ddl_productos').select2();
    // $('#ddl_marca').select2();
     marcas()
    linea_pedido();

     $('#ddl_productos').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#txt_costo').val(data.Costo);
      $('#txt_stock').val(data.Existencia);
      $('#txt_uni').val(data.Unidad);
      $('#ddl_familia').text('Familia: '+data.familia);
      $('#ddl_idfamilia').text(data.codfamilia);
      console.log(data);
    });


  })

  function marcas()
  {
    $('#ddl_marca').select2({
        placeholder: 'Seleccione',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/solicitud_materialC.php?marca=true',
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

  // function familias()
  // {
  //   $('#ddl_familia').select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           url:   '../controlador/inventario/solicitud_materialC.php?familia=true',
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

  function guardar_linea()
  {
     cant = $('#txt_cantidad').val();
     prod = $('#ddl_productos').val();
     costo = $('#txt_costo').val();

     if(prod=='')
     {
       Swal.fire("Seleccione un producto",'','info');
       return false;      
     }
      if(cant==0 || cant =='')
     {
       Swal.fire("Cantidad no valida",'','info');
       return false;
     }
     //  if(costo==0 || costo =='')
     // {
     //   Swal.fire("Costo no valida",'','info');
     //   return false;
     // }
     var parametros = 
     {
        'cantidad':cant,
        'productos':prod,
        'familia':$('#ddl_idfamilia').val(),
        'marca':$('#ddl_marca').val(),
        'fecha':$('#txt_fecha').val(),
        'fechaEnt':$('#txt_fechaEnt').val(),
        'costo':$('#txt_costo').val(),
        'total':$('#txt_total').val(),
        'obs':$('#txt_observacion').val(),
        'stock':$('#txt_stock').val(),
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

  function calcular()
  {
    var costo = $('#txt_costo').val();
    var cantidad = $('#txt_cantidad').val();
    if(costo=='')
    {
      $('#txt_costo').val(0);
    }
    if(cantidad=='')
    {
      $('#txt_cantidad').val(0);
    }

    var total = parseFloat(costo*cantidad);
    $('#txt_total').val(total.toFixed(2))
  }

  function imprimir_excel(orden)
  {
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
  }

  function modal_marcas()
  {
    $('#myModal_marcas').modal('show');
  }

  function guardar_marca()
  {
    parametros = 
    {
      'marca':$('#txt_new_marca').val(),
    }
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_marca=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Registro Guardado","","success");
               $('#txt_new_marca').val('');
               $('#myModal_marcas').modal('hide');
               // linea_pedido();
            }
          
          }
      });
  }

  function buscar_modal()
  {
    $('#myModal_buscar').modal('show');
   // bucar_producto_modal();
    
  }

  function bucar_producto_modal()
  {
    $('#myModal_espera').modal('show');

    query = $('#txt_search_producto').val();
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?productos=true&q='+query,
          type:  'post',
          // data: {parametros:parametros},
          dataType: 'json',          
          success:  function (response) {
            $('#myModal_espera').modal('hide');
            console.log(response);
            var tr = '';
            response.forEach(function(item,i){

               tr+=`<tr><td>`+item.id+`</td><td>`+item.text+`</td><td>`+item.data.Costo+`</td><td>
                        <button class=" btn-sm btn btn-primary" onclick="usar_producto('`+item.id+`','`+item.text+`','`+item.data.Costo+`','`+item.data.familia+`','`+item.data.Existencia+`','`+item.data.codfamilia+`','`+item.data.Unidad+`')"><i class="bx bx-box"></i>Usar</button></td></tr>`;
            })

            $('#tbl_producto_search').html(tr);
          
          }
      });
  }

  function usar_producto(cod,prod,cost,fami,stock,codfam,uni)
  {    
    $('#ddl_productos').empty();
    $('#myModal_buscar').modal('hide');
    $('#txt_costo').val(cost)
    $('#ddl_familia').text(fami)
    $('#ddl_idfamilia').val(codfam)
    $('#txt_stock').val(stock)
    $('#txt_uni').val(uni)

    $('#ddl_productos').append($('<option>',{value:  cod, text: prod,selected: true }));
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
            <button type="button" class="btn btn-default" title="Cambiar Cuentas" onclick="imprimir_pdf()">
              <img src="../../img/png/pdf.png">
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
        <div class="row">
          
        <div class="col-sm-2">
              <b>Contratista</b><br>
              <span><?php echo $_SESSION['INGRESO']['Nombre']; ?></span>
        </div>
        <div class="col-sm-2">
            <b>Fecha</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" readonly >
        </div>        
        <div class="col-sm-5">
            <b>Producto / articulo </b>
            <div class="input-group">
              <select class="form-control" id="ddl_productos" name="ddl_productos">
                <option value="">Seleccione producto</option>
              </select>         
              <span class="input-group-btn">
                <button type="button" class="btn btn-primary btn-flat btn-xs" onclick="buscar_modal()"><i class="fa fa-search"></i></button>
              </span>
            </div>

               
            <label id="ddl_familia" name="ddl_familia" ></label>
            <input type="hidden" name="ddl_idfamilia" id="ddl_idfamilia">
        </div>
      <!--   <div class="col-sm-2">
            <b>Familias </b> -->
            <!-- <select class="form-control" id="ddl_familia" name="ddl_familia" onchange="productos()" >
              <option value="">Seleccione familia</option>
            </select> -->
        <!-- </div> -->
        <div class="col-sm-3">
          <b>Marcas </b>
          <div class="input-group">
            <select class="form-control" id="ddl_marca" name="ddl_marca">
              <option value="">Seleccione</option>
            </select>
            <span class="input-group-btn">
              <button type="button" class="btn btn-primary btn-flat btn-xs" onclick="modal_marcas()"><i class="fa fa-plus"></i></button>
            </span>
            
          </div>
          
        </div>
      </div>
      <div class="row">

        <div class="col-sm-2">
            <b>Fecha Entrega</b>
            <input type="date" name="txt_fechaEnt" id="txt_fechaEnt" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" >
        </div>
        <div class="col-sm-4">
            <b>Observacion</b>  
            <input type="text" name="txt_observacion" id="txt_observacion" class="form-control input-sm" placeholder="Observacion" >
        </div>
        <div class="col-sm-1">
          <b>unidad</b>
          <input type="text" name="txt_uni" id="txt_uni" class="form-control input-sm" placeholder="0"  onblur="calcular()" readonly>
        </div> 
        <div class="col-sm-1">
          <b>Costo</b>
          <input type="text" name="txt_costo" id="txt_costo" class="form-control input-sm" placeholder="0"  onblur="calcular()" readonly>
        </div>
        <div class="col-sm-1">
          <b>Stock</b>
          <input type="text" name="txt_stock" id="txt_stock" class="form-control input-sm" readonly >
        </div>
        <div class="col-sm-1">
          <b>Cantidad</b>
          <input type="text" name="txt_cantidad" id="txt_cantidad" class="form-control input-sm" placeholder="0" onblur="calcular()" >
        </div>
         <div class="col-sm-1">
          <b>Total</b>
          <input type="text" name="txt_total" id="txt_total" class="form-control input-sm" placeholder="0" readonly >
        </div>        

       
        
        <div class="col-sm-1 text-right">
          <br>
            <button type="button" class="bt  btn-sm btn-primary" onclick="guardar_linea()" >Agregar</button>
        </div>
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
              <th>Unidad</th>   
              <th>Costo ref</th>     
              <th>Total ref </th> 
              <th>Marca</th>          
              <th>Fecha Solicitud</th>
              <th>Fecha Entrega</th> 
              <th>Observacion</th>
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

 <div id="myModal_marcas" class="modal fade myModalMArcas" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nueva Marca</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                    <b>Nombre de marca</b>
                    <input type="" class="form-control input-sm" name="txt_new_marca" id="txt_new_marca">
                </div>
                
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_marca()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>

   <div id="myModal_buscar" class="modal fade myModalBuscar" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar productos</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-10">
                    <b>Nombre producto</b>
                    <input type="" class="form-control input-sm" name="txt_search_producto" id="txt_search_producto">
                    <br>
                </div>
                <div class="col-sm-2">
                  <br>
                  <button type="button" class="btn btn-primary" onclick="bucar_producto_modal()"><i class="fa fa-search"></i> Buscar</button>                  
                </div> 
                <div class="col-sm-12" style="overflow-y: scroll; height: 300px;">
                    <table class="table table-hover text-sm">
                      <thead>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Costo</th>
                        <th></th>
                      </thead>
                      <tbody id="tbl_producto_search">
                        <tr>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_marca()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>
