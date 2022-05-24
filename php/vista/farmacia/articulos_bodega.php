<script type="text/javascript">
$( document ).ready(function() {
   // provincias();


       autocoplet_desc();
       autocoplet_ref();
       tabla_catalogo('ref');
       $('#opcion2').css('display','block');
       autocoplet_cta_CV()
       autocoplet_fami();
autocoplet_cta_ventas()
autocoplet_cta_vnt_0()
autocoplet_cta_vnt_ant()
autocoplet_fami_modal()
autocoplet_cta()
autocoplet_cta_inv()


});

//-----------------------opcion2-----------------------------

 function familia_modal()
 {
   var cta = $('#ddl_familia_modal').val();
   var parte = cta.split('-');
   buscar_ultimo(parte[0]);
   // $('#txt_ref').val(cta);
   console.log(cta);
 }
function buscar_ultimo(cta)
 {
   $.ajax({
    data:  {cta:cta},
    url:   '../controlador/farmacia/articulosC.php?buscar_ultimo=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) { 
      $('#txt_ref').val(response);
    }
  });

 }

  function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        width:'100%',
        placeholder: 'Escriba Descripcion',
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

   function autocoplet_ref(){
      $('#ddl_referencia').select2({
        width:'100%',
        placeholder: 'Escriba Referencia',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=ref',
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


   function tabla_catalogo(tipo)
   {
    $('#tbl_op2').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
    var fami = $('#ddl_familia').val();
    console.log(fami);
    if(fami!='' && fami !=null)
    {
     fami = fami.split('-');
     fami = fami[0];
    }
   	 var parametros=
    {
      'descripcion':$('#ddl_descripcion').val(),
      'referencia':$('#ddl_referencia').val(),
      'familia':fami,
      'ubi':$('#txt_ubicacion').val(),
      'tipo':tipo,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?tabla_catalogo_bodega=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	// console.log(response);
        $('#tbl_op2').html(response);
      }
    });

   }


function provincias()
  {
   var option ="<option value=''>Seleccione provincia</option>"; 
     $.ajax({
      url: '../controlador/educativo/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#prov').html(option);
      console.log(response);
    }
    });

  }


   function autocoplet_cta_CV(){
      $('#ddl_cta_CV').select2({
        width: '86%',
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='5','9'",
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

    function autocoplet_cta_ventas(){
      $('#ddl_cta_venta').select2({
        width: '86%',
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

    function autocoplet_cta_vnt_0(){
      $('#ddl_cta_ventas_0').select2({
        width: '86%',
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

    function autocoplet_cta_vnt_ant(){
      $('#ddl_cta_vnt_anti').select2({
        width: '86%',
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

  
  function autocoplet_fami_modal(){
      $('#ddl_familia_modal').select2({
        placeholder: 'Seleccione una familia',
        width:'100%',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?familias2=true',
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

  function autocoplet_fami(){
      $('#ddl_familia').select2({
        placeholder: 'Seleccione una familia',
        width:'100%',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?familias=true',
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

  function autocoplet_cta(){
      $('#ddl_cta').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?cuenta=true',
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

  function autocoplet_cta_inv(){
      $('#ddl_cta_inv').select2({
        width: '245px',
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='1'",
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


    function guardar_producto()
   {
     var datos =  $("#form_nuevo_producto").serialize();
     if($('#ddl_cta_inv').val()==0 || $('#ddl_cta_CV').val()==0 || $('#ddl_cta_CV').val()==null || $('#ddl_cta_inv').val()==null)
     {
       Swal.fire('Asegurese que la cuenta de inventario y la cuenta de costo de venta esten seleccionados.','','info');   
      return false;
     }

     if($('#ddl_familia_modal').val()=='' || $('#txt_ref').val()=='' || $('#txt_nombre').val()=='' || $('#txt_max').val()=='' || $('#txt_min').val()=='' || $('#txt_reg_sanitario').val()=='')
     {
       Swal.fire('Llene todo los campos','','info');   
      return false;
     }

     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?producto_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        
          $('#Nuevo_producto').modal('hide');
        if(response==1)
        {
          Swal.fire('Nuevo producto registrado.','','success'); 
          limpiar_nuevo_producto();         
        }
      }
    });
     // console.log(datos);
   }

  function limpiar_nuevo_producto()
   {
     $('#ddl_cta_inv').empty();
     $('#ddl_cta_CV').empty();
     $('#ddl_cta_venta').empty();
     $('#ddl_cta_ventas_0').empty();
     $('#ddl_cta_vnt_anti').empty();
     $('#ddl_familia_modal').empty();
     $('#txt_ref').val('');
     $('#txt_nombre').val('');
     $('#txt_max').val('');
     $('#txt_min').val('');
     $('#txt_reg_sanitario').val('');
     $('#txt_cod_barras').val('');
   }


   function editar(id)
   {
     // alert(id);
   }

  function eliminar(id)
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
         eliminar_art(id);
       }
     })
   }

   function eliminar_art(id)
   {

     $.ajax({
        data:  {id,id},
        url:   '../controlador/farmacia/articulosC.php?eliminar_articulos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          // console.log(response);
          if(response==1)
          {
            Swal.fire('Producto eliminado.','','success'); 
            tabla_catalogo('ref');
          }
        }
      });

   }

   function cargar_datos(id)
   {    
      $.ajax({
        data:  {id,id},
        url:   '../controlador/farmacia/articulosC.php?detalle_articulos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          $('#Nuevo_producto').modal('show');
          console.log(response);
          $('#txt_nombre').val(response.datos[0].Producto);
          $('#txt_max').val(response.datos[0].Maximo);
          $('#txt_min').val(response.datos[0].Minimo);
          $('#txt_reg_sanitario').val(response.datos[0].Reg_Sanitario);
          $('#txt_uni').val(response.datos[0].Unidad);
          $('#txt_cod_barras').val(response.datos[0].Codigo_Barra);
          $('#txt_id').val(response.datos[0].ID);
          $('#txt_ref').val(response.num);

          // $('#ddl_familia').append($('<option>',{value: datos[1], text:datos[0],selected: true }));
          if(response.cv!='')
          {
           $('#ddl_cta_CV').append($('<option>',{value: response.cv[0].Codigo, text:response.cv[0].Cuenta,selected: true }));
          }
          if(response.inv!='')
          {
           $('#ddl_cta_inv').append($('<option>',{value: response.inv[0].Codigo, text:response.inv[0].Cuenta,selected: true }));
          }
          $('#ddl_familia_modal').append($('<option>',{value: response.fami[0].Codigo_Inv, text:response.fami[0].Producto,selected: true }));

         if(response.v!='')
          {
           $('#ddl_cta_CV').append($('<option>',{value: response.v[0].Codigo, text:response.v[0].Cuenta,selected: true }));
          } 
          if(response.v0!='')
          {
           $('#ddl_cta_venta').append($('<option>',{value: response.v0[0].Codigo, text:response.v0[0].Cuenta,selected: true }));
          }
           if(response.va!='')
          {
           $('#ddl_cta_vnt_anti').append($('<option>',{value: response.va[0].Codigo, text:response.va[0].Cuenta,selected: true }));
          }
          console.log(response);
          
        }
      });

   }
  function abrir_modal()
   {
     $('#Nuevo_producto').modal('show');
   }	
</script>
 <div class="row"><br>
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
             <button type="button" class="btn btn-default" data-toggle="tooltip" title="Nuevo articulo" onclick="abrir_modal()">
               <img src="../../img/png/add_articulo.png">
            </button>          
        </div>
       <!--  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
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
        </div>      -->
 </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
      <div class="panel-heading text-center">
        <b>Catalogo De Productos</b>
      </div>
			<div class="panel-body">
				<div class="col-sm-3">
					<b>Referencia</b>
					<div class="input-group"> 
              <select class="form-control input-sm" id="ddl_referencia" name="ddl_referencia" onchange="tabla_catalogo('ref')">
                 <option value="">Seleccione un proveedor</option>
              </select>             
               <span class="input-group-addon" onclick="$('#ddl_referencia').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
          </div>
				</div>
				<div class="col-sm-4">
					<b>Articulo</b>
				<div class="input-group"> 
            <select class="form-control input-sm" id="ddl_descripcion" name="ddl_descripcion" onchange="tabla_catalogo('ref')">
               <option value="">Seleccione un proveedor</option>
            </select>             
             <span class="input-group-addon" onclick="$('#ddl_descripcion').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
        </div>
				</div>
				<div class="col-sm-3">
					<b>Familia</b>
        <div class="input-group"> 
					<select class="form-control input-sm" id="ddl_familia" onchange="tabla_catalogo('ref')">
						<option value="">Seleccione provincia</option>
					</select>
           <span class="input-group-addon" onclick="$('#ddl_familia').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
        </div>
				</div>		
				<div class="col-sm-2">
					<b>Ubicacion</b>
          <input type="" class="form-control form-control-sm" name="txt_ubicacion" id="txt_ubicacion" onkeyup="tabla_catalogo('ref')">					
				</div>
			</div>
			<div class="row box-body">
				<div class="col-sm-5">
					<!-- <b>Proveedor</b>
					<select class="form-control input-sm" id="prov">
						<option value="">Seleccione provincia</option>
					</select> -->
				</div>				
				<div class="col-sm-7 text-right"><br>
					<!-- <button class="btn btn-primary btn-sm">Limpiar</button> -->
					<!-- <button class="btn btn-primary btn-sm">Buscar</button> -->
					<!-- <button class="btn btn-primary btn-sm" onclick="$('#Nuevo_producto').modal('show')">Nuevo Articulo</button> -->
				</div>	
			</div>						
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<table class="table table-hover" id="tbl_lista">
          <thead>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Familia</th>
            <th>Precio</th>
            <th>Stock</th>
            <th></th>
          </thead>
          <tbody id="tbl_op2">
            
          </tbody>
          
        </table>

		</div>
		
	</div>
</div>

<!-- Modal -->
<div id="Nuevo_producto" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-md">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo producto</h4>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_producto">
          <input type="hidden" name="txt_id" id="txt_id">
        <div class="row">
          <div class="col-sm-6">
             <b>Familiar</b><br>
            <div class="input-group" style="display: flex;"> 
              <select id="ddl_familia_modal" name="ddl_familia_modal" class="form-control input-sm" onchange="familia_modal()">
                 <option value="">seleccione familia</option>
              </select>
                 <span>
                  <button type="button" class="btn btn-success btn-flat" onclick="$('#modal_nueva_familia').modal('show')"><i class="fa fa-plus"></i></button>
                </span>
            </div>   
          </div>
          <div class="col-sm-6" style="padding-right: 5px; padding-left:0px ">
            <div class="input-group input-group-sm">   
                <b>Cuenta Inv</b> <br>             
                <select class="form-control form-control-sm"  name="ddl_cta_inv" id="ddl_cta_inv"></select>
                    <span class="">
                      <button type="button" class="btn btn-info btn-flat" onclick="limpiar_cta('ddl_cta_inv')"><i class="fa fa-close"></i></button>
                    </span>
              </div>
            </div> 
        </div>
        <div class="row">
          <div class="col-sm-8">
             <b>Nombre de producto</b>
            <input type="text" id="txt_nombre" name="txt_nombre" class="form-control input-sm">              
          </div>
          <div class="col-sm-4">
            <b>Referencia</b>
            <input type="text" name="txt_ref" id="txt_ref" class="form-control input-sm" readonly="">              
          </div>
        </div>
        <div class="row">        
          <div class="col-sm-2">
             <b>Max</b>
            <input type="text" id="txt_max" name="txt_max" class="form-control input-sm">              
          </div>
          <div class="col-sm-2">
             <b>Min</b>
            <input type="text" id="txt_min" name="txt_min" class="form-control input-sm">              
          </div> 
          <div class="col-sm-2">
            <b>Unid Med.</b>
            <input type="text" id="txt_uni" name="txt_uni" class="form-control input-sm">              
          </div>
          <div class="col-sm-3">
             <b>Cod Barras</b>
            <input type="text" name="txt_cod_barras" class="form-control input-sm">              
          </div>
          <div class="col-sm-3">
             <b>Reg. Sanitario</b>
            <input type="text" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control input-sm">              
          </div>              
        </div>
        <div class="row">
            <div class="col-sm-6">
              <b>Cuenta Costo venta</b>
               <div class="input-group" style="display:flex;">   
                <select class="form-control form-control-sm"  name="ddl_cta_CV" id="ddl_cta_CV" ></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat" onclick="limpiar_cta('ddl_cta_CV')"><i class="fa fa-close"></i></button>
                    </span>
               </div>    
            </div>  
            <div class="col-sm-6">
               <b>Cuenta Ventas</b><br>
                <div class="input-group" style="display:flex;">   
                <select class="form-control form-control-sm" name="ddl_cta_venta" id="ddl_cta_venta"></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat" onclick="limpiar_cta('ddl_cta_venta')"><i class="fa fa-close"></i></button>
                    </span>
               </div>   
            </div>         
        </div>
        <div class="row">
          <div class="col-sm-6">
            <b>Cuenta Ventas 0</b>
             <div class="input-group" style="display:flex;">   
                <select class="form-control form-control-sm" name="ddl_cta_ventas_0" id="ddl_cta_ventas_0"></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat" onclick="limpiar_cta('ddl_cta_ventas_0')"><i class="fa fa-close"></i></button>
                    </span>
               </div>   
          </div>  
          <div class="col-sm-6">
            <b>Cuenta Ventas Anti</b>  
             <div class="input-group" style="display:flex;">   
                <select class="form-control form-control-sm"  name="ddl_cta_vnt_anti" id="ddl_cta_vnt_anti"></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat"  onclick="limpiar_cta('ddl_cta_vnt_anti')"><i class="fa fa-close"></i></button>
                    </span>
               </div>  
            </div>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardar_producto()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div id="modal_nueva_familia" class="modal fade" role="dialog">
  <div class="modal-dialog modal-centered modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Nueva Familia</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <form enctype="multipart/form-data" id="form_img" method="post">
            <div class="col-sm-12">
              <b>Codigo</b>
              <input type="text" name="txt_cod_familia" id="txt_cod_familia" class="form-control form-control-sm">
              <b>Nombre</b>
              <input type="text" name="txt_nombre_familia_new" id="txt_nombre_familia_new" class="form-control form-control-sm" >              
            </div>
          </form>  
          <br> 
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardar_familia()">Guardar</button>
        <button type="button" class="btn btn-default" onclick="$('#modal_nueva_familia').modal('hide')">Cancelar</button>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
  function guardar_familia()
  {
    var cod = $('#txt_cod_familia').val();
    var nom = $('#txt_nombre_familia_new').val();
    if(cod=='' || nom=='')
    {
      Swal.fire('Llene todo los campos','','info');
      return false;
    }

    var parametros = 
    {
      'codigo':cod,
      'nombre':nom, 
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?familia_new=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response==1)
        {
          Swal.fire('Familia Agregada','','success').then(function(){            
            $('#modal_nueva_familia').modal('hide');
            $('#txt_cod_familia').val('');
            $('#txt_nombre_familia_new').val('');
          })
        }else if(response==-2)
        {
          Swal.fire(cod+' Ya esta registrado','','info');
        }else if(response==-3)
        {
          Swal.fire(nom+' Ya esta registrado','','info');
        }
      }
    });


  }
</script>
