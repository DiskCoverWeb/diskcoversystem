<script type="text/javascript">
   $( document ).ready(function() {
   
// /------------------------
   	
//---------------------------

//--------------------------
//-----------------------------


   
  });
   function autocoplet_prov(){
      $('#ddl_proveedor').select2({
        width:'300px',
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
   
  }

   function tabla_ingresos()
   {
    $('#tbl_opcion1').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
   	 var parametros=
    {
      'proveedor':$('#ddl_proveedor').val(),
      'factura':$('#txt_factura').val(),
      'comprobante':$('#txt_comprobante').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?tabla_ingresos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#tbl_opcion1').html(response);    
      }
    });

   }

 //-----------------------opcion2-----------------------------
  function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        width:'310px',
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
        width:'310px',
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
   	 var parametros=
    {
      'descripcion':$('#ddl_descripcion').val(),
      'referencia':$('#ddl_referencia').val(),
      'tipo':tipo,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?tabla_catalogo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#tbl_op2').html(response);
      }
    });

   }




function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}
function Ver_detalle(comprobante)
{
    var mod = '<php echo $_SESSION["INGRESO"]["modulo_"]; ?>'; 
    url='../vista/farmacia.php?mod='+mod+'&acc=utilidad_insumos&acc1=Utilidad insumos&b=1&po=subcu&comprobante='+comprobante;
    window.open(url, '_blank');
}
 function cargar_pedidos(f='')
  {
   
    $('#tbl_descargos').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');   
      var  parametros = 
      { 
        'nom':$('#txt_paciente').val(),
        'ci':$('#txt_ci').val(),
        'historia':$('#txt_historia').val(),
        'depar':$('#txt_departamento').val(),
        'proce':$('#txt_procedimiento').val(),
        'desde':$('#txt_desde').val(),
        'hasta':$('#txt_hasta').val(),
        'busfe':$('#rbl_fecha').prop('checked'),
        // 'numero':$('input[name="rbl_proce"]:checked').val(),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response)
        if(response)
        {
          $('#tbl_descargos').html(response.tbl);
        }
      }
    });
  }



  //----------------------------  opcion 5 ----------------
  function cargar_medicamentos()
  {
     $('#tbl_medicamentos').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
     if($('#rbl_fecha5').prop('checked'))
     {
       $('#cantidad_consu').css('display','initial');       
       $('#des').text($('#txt_desde5').val());  
       $('#has').text($('#txt_hasta5').val());
     }else
     {
      $('#cantidad_consu').css('display','none');     
     }
     var paginacion = 
    {
      '0':$('#pag').val(),
      '1':$('#ddl_reg').val(),
      '2':'cargar_medicamentos',
    }   
      var  parametros = 
      { 
        'nom':$('#txt_paciente5').val(),
        'ci':$('#txt_ci_ruc').val(),
        'medicamento':$('#txt_medicamento').val(),
        'depar':$('#txt_departamento5').val(),
        'desde':$('#txt_desde5').val(),
        'hasta':$('#txt_hasta5').val(),
        'busfe':$('#rbl_fecha5').prop('checked'),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros,paginacion:paginacion},
      url:   '../controlador/farmacia/farmacia_internaC.php?descargos_medicamentos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response)
        {
          $('#tbl_medicamentos').html(response.tbl);
          $('#consumido').html(response.Total_consumido);
        }
      }
    });
  }


  function cargar_tablas()
  {
    $('#estilo_tabla').remove();
    var opcion = $('#ddl_opciones').val();

      $('#opcion5').css('display','none')  
      $('#opcion4').css('display','none')
      $('#opcion2').css('display','none')
      $('#opcion1').css('display','none')
    if(opcion==1)
    {

         tabla_ingresos();
         autocoplet_prov();
         $('#opcion1').css('display','block')
    }else if(opcion==2)
    {
       autocoplet_desc();
       autocoplet_ref();
       tabla_catalogo('ref');
       $('#opcion2').css('display','block')

    }else if(opcion==3)
    {
      
    }else if(opcion==4)
    {   
    cargar_pedidos();
    $('#opcion4').css('display','block')

    }else if(opcion==5)
    {
      cargar_medicamentos(); 
      $('#opcion5').css('display','block')       
    }
  }


function reporte_pdf()
{
   var url = '../controlador/farmacia/farmacia_internaC.php?imprimir_pdf=true&';
   var opcion = $('#ddl_opciones').val();
   if(opcion=='')
   {
    Swal.fire('Seleccione una tipo de informe','','info');
    return false;
   }
   if(opcion==1)
   {
    url+='opcion=1&';
     var datos =  $("#form_1").serialize();
   }  
   if(opcion==2)
   {
    url+='opcion=2&';
     var datos =  $("#form_2").serialize();
   }  
   if(opcion==4)
   {
    url+='opcion=4&';
     var datos =  $("#form_4").serialize();
   }  
   if(opcion==5)
   {
    url+='opcion=5&';
     var datos =  $("#form_5").serialize();
   }  

    window.open(url+datos, '_blank');
}

function reporte_excel()
{
   var url = '../controlador/farmacia/farmacia_internaC.php?imprimir_excel=true&';
   var opcion = $('#ddl_opciones').val();
   if(opcion=='')
   {
    Swal.fire('Seleccione una tipo de informe','','info');
    return false;
   }
   if(opcion==1)
   {
    url+='opcion=1&';
     var datos =  $("#form_1").serialize();
   }  
   if(opcion==2)
   {
    url+='opcion=2&';
     var datos =  $("#form_2").serialize();
   }  
   if(opcion==4)
   {
    url+='opcion=4&';
     var datos =  $("#form_4").serialize();
   }  
   if(opcion==5)
   {
    url+='opcion=5&';
     var datos =  $("#form_5").serialize();
   }  

    window.open(url+datos, '_blank');
}


function Ver_detalle(reporte,factura='',comprobante='')
{
   location.href = 'farmacia.php?mod=Farmacia&acc=farmacia_interna_detalle&acc1=Farmacia%20interna detalle&b=1&po=subcu&reporte='+reporte+'&comprobante='+comprobante+'&factura='+factura;
}

function Ver_pedido(reporte,factura='',comprobante='')
{
   location.href = 'farmacia.php?mod=Farmacia&acc=farmacia_interna_detalle&acc1=Farmacia%20interna detalle&b=1&po=subcu&reporte='+reporte+'&comprobante='+comprobante+'&factura='+factura;
}


</script>
  <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>      
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div> 
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 	</div>
 </div>
<div class="row">
	<div class="col-sm-12">
		<div class="col-sm-6">
			<select class="form-control input-sm" onchange="cargar_tablas()" name="ddl_opciones" id="ddl_opciones">
				<option value="">Seleccione opcion</option>
				<option value="1">INGRESOS</option>
				<option value="2">LISTADO DEL CATALOGO</option>
				<!-- <option value="3">EGRESOS O DESCARGOS DE PACIENTES</option> -->
				<option value="4">DESCARGOS PARA VISUALIZAR POR PACIENTE</option>
				<option value="5">VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA</option>
			</select>			
		</div>		
	</div>
	<div class="col-sm-12" id="opcion1" style="display:none;">
		<div class="row">
      <form id="form_1">
			<div class="col-sm-4">
				<b>Proveedor</b>
				<div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_proveedor" name="ddl_proveedor" onchange="tabla_ingresos()">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon" onclick="$('#ddl_proveedor').empty();tabla_ingresos()" title="Borrar seleccion"><i class="fa fa-close"></i></span>
              </div>
			</div>	
			<div class="col-sm-4">
				Facturas
				<input type="text" class="form-control input-sm" name="txt_factura" id="txt_factura" placeholder="Numero de factura" onkeyup="tabla_ingresos()">
			</div>	
			<div class="col-sm-4">
				No. Comprobante
				<input type="text" class="form-control input-sm" name="txt_comprobante" id="txt_comprobante" onkeyup="tabla_ingresos()" placeholder="Numero de Comprobante">
			</div>
      </form>			
		</div>
		<div class="row"><br>
			<div id="tbl_opcion1" class="col-sm-12">
				
			</div>
			
		</div>		
	</div>
	<div  class="col-sm-12" id="opcion2" style="display:none">
		<div class="row">
      <form id="form_2">
			<div class="col-sm-4">
				<b>Codigo</b>
				<div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_referencia" name="ddl_referencia" onchange="tabla_catalogo('ref')">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon" onclick="$('#ddl_referencia').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
              </div>
			</div>	
			<div class="col-sm-4">
				Descripcion
				<div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_descripcion" name="ddl_descripcion" onchange="tabla_catalogo('ref')">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon" onclick="$('#ddl_descripcion').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
              </div>
			</div>
      </form>					
		</div><br>
		<div class="row" id="tbl_opcion2">
      <div class="table-responsive col-sm-12">
        <table class="table table-hover" id="tbl_lista">
          <thead>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Cantidad Actual</th>
            <th>Precio</th>
            <th>Fecha de compra</th>
            <th>Ingresado</th>
          </thead>
          <tbody id="tbl_op2">
            
          </tbody>
          
        </table>
      </div>
			
		</div>	
	</div>

  <div id="opcion3" class="col-sm-12">
    <div class="row">
      <div class="col-sm-12">
        
      </div>      
    </div>
    <div class="row">
      <div class="col-sm-12">
        
      </div>      
    </div>
    
  </div>

  <div id="opcion4" style="display:none" class="col-sm-12">
    <div class="row">
      <form id="form_4">
      <div class="col-sm-3">
        <b>Paciente</b>
        <input type="text"  class="form-control input-sm" name="txt_paciente" id="txt_paciente" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Numero de Cedula</b>       
        <input type="text"  class="form-control input-sm" name="txt_ci" id="txt_ci" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Historia Clinica</b>        
        <input type="text"  class="form-control input-sm" name="txt_historia" id="txt_historia" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-3">
        <b>Departamento</b>   
        <input type="text"  class="form-control input-sm" name="txt_departamento" id="txt_departamento" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Procedimiento</b>       
        <input type="text"  class="form-control input-sm" name="txt_procedimiento" id="txt_procedimiento" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Desde</b>        
        <input type="date"  class="form-control input-sm" name="txt_desde" value="<?php echo date('Y-m-d'); ?>" id="txt_desde" onblur="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Hasta</b>        
        <input type="date"  class="form-control input-sm" name="txt_hasta" value="<?php echo date('Y-m-d'); ?>" id="txt_hasta" onblur="cargar_pedidos();">
      </div> 
      <div class="col-sm-2"><br>
          <label><input type="checkbox" name="rbl_fecha" id="rbl_fecha" onchange="cargar_pedidos()">Por Fecha</label>
      </div>
      <!-- <div class="col-sm-6"><br>        
          <label><input type="radio" name="rbl_proce" id="rbl_sin_proce" value="false" onchange="cargar_pedidos()" checked>Descargos sin procesar</label>
          <label><input type="radio" name="rbl_proce" id="rbl_procesado" value="true" onchange="cargar_pedidos()">Descargos procesados</label>
      </div> -->
      </form> 
    </div>
    <div class="row"><br>
      <div class="col-sm-12" id="tbl_descargos">
        <!-- <div id="tbl_descargos">
          
        </div> -->
        
      </div>      
    </div>
    
  </div>

  <div id="opcion5" style="display:none" class="col-sm-12">
    <div class="row">
      <form id="form_5"> 
      <div class="col-sm-2">
        <b>Desde</b>
        <input type="date" name="txt_desde5" class="form-control input-sm" id="txt_desde5" value="<?php echo date('Y-m-d');?>" onblur="cargar_medicamentos()">      
         <label><input type="checkbox" name="rbl_fecha5" id="rbl_fecha5" onchange="cargar_medicamentos()">Por Fecha</label>          
      </div> 
      <div class="col-sm-2">
        <b>Hasta</b>
        <input type="date" name="txt_hasta5" class="form-control input-sm" id="txt_hasta5" value="<?php echo date('Y-m-d');?>" onblur="cargar_medicamentos()">        
      </div> 
      <div class="col-sm-2">
        <b>Medicamento o insumo</b>
        <input type="text" name="txt_medicamento" id="txt_medicamento" class="form-control input-sm" onkeyup="cargar_medicamentos()">        
      </div> 
      <div class="col-sm-2">
        <b>Paciente</b>
        <input type="text" name="txt_paciente5" id="txt_paciente5" class="form-control input-sm" onkeyup="cargar_medicamentos()">        
      </div> 
       <div class="col-sm-2">
        <b>Numero de cedula</b>
        <input type="text" name="txt_ci_ruc" id="txt_ci_ruc" class="form-control input-sm" onkeyup="cargar_medicamentos()">        
      </div> 
       <div class="col-sm-2">
        <b>Departamento</b>
        <input type="text" name="txt_departamento5" id="txt_departamento5" class="form-control input-sm" onkeyup="cargar_medicamentos()">       
      </div> 
    </form>
    </div>
    <div class="row">      
      <div class="col-sm-12">
       
      </div>
    </div>
    <!-- <div class="row" id="cantidad_consu" style="display:none">
      <div class="col-sm-6">
        <label>Cantidad consumida desde :<i id="des"></i> hasta <i id="has"></i>  Total de = </label><b id="consumido"></b>
      </div>
    </div> -->
    <div class="row" >
      <div class="col-sm-12" id="cantidad_consu" style="display:none">
        Cantidad consumida desde :<b id="des"></b> hasta <b id="has"></b>  es de  <b id="consumido"></b>
      </div>
    </div>
    <div class="row"><br>
      <div class="col-sm-12">
       
        <div id="tbl_medicamentos">
          
        </div>
        
      </div>      
    </div>
    
  </div>


</div>

