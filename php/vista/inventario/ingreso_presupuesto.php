 <script type="text/javascript">
 $(document).ready(function(){
   $('.js-example-basic-multiple').select2();
 	//$('#ddl_productos').val();
  proyectos();
 	 productos();
 	 // centro_costos();


   $('#imprimir_pdf').click(function(){
       centro = $('#ddl_centro_costo').val();
       proyecto = $('#ddl_proyecto option:selected').text();
      var url = '../controlador/inventario/ingreso_presupuestoC.php?reporte_pdf&centro='+centro+'&proye='+proyecto;                
      window.open(url, '_blank');
    }); 
  $('#imprimir_excel').click(function(){
       centro = $('#ddl_centro_costo').val();
       proyecto = $('#ddl_proyecto option:selected').text();
    var url = '../controlador/inventario/ingreso_presupuestoC.php?reporte_excel&centro='+centro+'&proye='+proyecto;                
    window.open(url, '_blank');
  }); 

  

  })

 function proyectos()
{
  var pro = $('#txt_proyecto').val();
  $.ajax({
      // data:  {id:id},
      url:   '../controlador/inventario/inventario_onlineC.php?proyecto=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
           llenarComboList(response,'ddl_proyecto');
            centro_costos();
      }
    });
}

 	
 function productos()
 {
      $('#ddl_productos').select2({
        placeholder: 'Seleccione una producto',
        width:'98%',
        ajax: {
          url: '../controlador/inventario/ingreso_presupuestoC.php?producto=true',
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

 function centro_costos()
 {    var proyecto = $('#ddl_proyecto').val();  
      $('#ddl_centro_costo').select2({
        placeholder: 'Seleccione centro de costos',
        width:'98%',
        ajax: {
          url: '../controlador/inventario/ingreso_presupuestoC.php?centro_costo=true&proyecto='+proyecto,
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

  function listado()
  {
    var parametros = 
    {
      'centro':$('#ddl_centro_costo').val(),
    }
    $.ajax({
     data:  {parametros:parametros},
      url:    '../controlador/inventario/ingreso_presupuestoC.php?listado',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          if(response!='')
          {
            $('#tbl_datos').html(response);
          }else
          {
             $('#tbl_datos').html('<tr><td colspan="4">Sin Resultados</td></tr>');
          }
                 
      }
    });
  }

  function guardar()
  {
    var cc = $('#ddl_centro_costo').val();
    var pro = $('#ddl_productos').val();
    var cant = $('#txt_cantidad').val();
    if(cc=='' || cant =='' || pro =='')
    {
      Swal.fire('Llene todo los campos','','warning');
    }

    var parametros =
    {
      'centro':cc,
      'producto':pro,
      'cantidad':cant,
    }
    $.ajax({
     data:  {parametros:parametros},
      url:    '../controlador/inventario/ingreso_presupuestoC.php?guardar',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          if(response==1)
          {
            Swal.fire('Agregado','','success');
            listado();
          }else if(response==-1)
          {
            Swal.fire('No se pudo guardar con uno o varios Centros de costo','','error');            
          }
                 
      }
    });

  }

   function update(id)
  {
    var cant = $('#txt_cant'+id).val();
    if( cant =='' || cant ==0)
    {
      Swal.fire('Agrege un numero valido','','warning');
    }

    var parametros =
    {
      'id':id,
      'cantidad':cant,
    }
    $.ajax({
     data:  {parametros:parametros},
      url:    '../controlador/inventario/ingreso_presupuestoC.php?update',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          if(response==1)
          {
            Swal.fire('Actualizado','','success');
            listado();
          }
                 
      }
    });

  }

  function eliminar(num)
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
              'id':num,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/inventario/ingreso_presupuestoC.php?eliminar=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                   listado();
                }
              }
            });
        }
      });
  }
 </script>
 <div class="row">
  <div class="col-lg-4 col-sm-4 col-md-8 col-xs-12">
    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
         <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>"  data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/salire.png">
        </a>
    </div>
    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
      <button type="button" class="btn btn-default" id="imprimir_pdf"  data-toggle="tooltip" title="Descargar PDF">
        <img src="../../img/png/pdf.png">
      </button>           
    </div>
    <div class="col-xs-2 col-md-2 col-sm-2">
      <button type="button" class="btn btn-default" id="imprimir_excel"  data-toggle="tooltip" title="Descargar Excel">
        <img src="../../img/png/table_excel.png">
      </button>         
    </div>    
  </div>      
</div>
 <div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
          <div class="panel-heading text-center"><b>INGRESO DE PRESUPUESTO</b></div>
          <div class="panel-body">
            
            <div class="row">
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-7">
                  <label>Proyectos</label>
                  <select class="form-control" name="ddl_proyecto" id="ddl_proyecto" onchange="centro_costos()">
                    <option value="">Seleccione proyecto</option>
                  </select>   
                </div>
                <div class="col-sm-5">
                   <label for="exampleInputEmail1">Centro de costos</label>
                   <select class="form-control js-example-basic-multiple" id="ddl_centro_costo" name="ddl_centro_costo[]" multiple="multiple" onchange="listado()">
                    <option value=""></option>
                   </select>
                </div>
                <div class="col-sm-11">
                   <label for="exampleInputEmail1">Producto</label>
                   <select class="form-control" id="ddl_productos">
                    <option value=""></option>
                   </select>
                </div>
                 <div class="col-sm-1" style=" padding: 3px 5px 0px 5px;">
                   <label for="exampleInputEmail1">Cantidad</label>                 
                   <input type="text" name="txt_cantidad" id="txt_cantidad" class="form-control input-sm">
                </div>                  
                </div>                
              </div>
              <div class="col-sm-2"><br>
                <button class="btn btn-primary btn-sm" onclick="guardar()"><i class="fa fa-save"></i> Guardar</button>                
              </div>
                
               
               
              </div>

          </div>
        </div>
    </div>
 </div>

 <div class="row">
	<div class="col-sm-12">
		<div class="box box-default">
           
            <!-- /.box-header -->
            <!-- form start -->
            <div class="row">

              <div class="box-body">
            	<table class="table table-responsive">
            		<thead>
            			<th>Centro de costos</th>
            			<th>Productos</th>
            			<th>Cantidad</th>
            			<th></th>
            		</thead>
            		<tbody id="tbl_datos">
                  <tr>
                    <td colspan="4">Sin Resultados</td>
                  </tr>
            			
            		</tbody>
            	</table>
            	</div>
            </div>
          </div>
	</div>
	
</div>