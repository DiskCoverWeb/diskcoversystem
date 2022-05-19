<?php 
$cod = ''; $ci =''; if(isset($_GET['comprobante'])){$cod = $_GET['comprobante'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}?>

<script type="text/javascript">
   $( document ).ready(function() {
    numeroFactura();
    autocopletar_servicios();
   	 cargar_pedidos();
     var num_li =0;
   
  });


      function autocopletar_servicios(){
      $('#ddl_servicios').select2({
        placeholder: 'Seleccione',
        ajax: {
          url:   '../controlador/farmacia/facturacion_insumosC.php?servicios=true',
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


  function cargar_pedidos()
  {
    
    var comprobante = '<?php echo $cod; ?>';    
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/facturacion_insumosC.php?datos_comprobante=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        if(response)
        {
          $('#tbl_body').html(response.tabla);
          $('#paciente').text(response.cliente[0].Cliente);
          $('#detalle').text(response.cliente[0].Concepto);
          $('#fecha').text(response.cliente[0].Fecha.date);
          $('#comp').text(response.cliente[0].Numero);
          $('#txt_total2').text(response.total);
          num_li = response.lineas;
        }
      }
    });
  }

function calcular(id)
{
  console.log(id);
  var por = $('#txt_porcentaje_'+id).val();
  var total = $('#txt_to_'+id).val();
  if(por=='')
  {
    por = 0;
    $('#txt_porcentaje_'+id).val(0);
  }

  var valor = (por/100)*total;
  var tt = 0;
  var gran = parseFloat(total)+parseFloat(valor);

  $('#txt_valor_'+id).val(valor.toFixed(2));
  $('#txt_gran_t_'+id).val(gran.toFixed(2));

  for (var i= 1; i < num_li+1; i++) {
    tt+= parseFloat($('#txt_gran_t_'+i).val());
    
  }
  $('#txt_tt').text(tt.toFixed(2));
  $('#txt_total2').text(tt.toFixed(2));
  
}
function calcular_uti(id)
{
   var  total = $('#txt_to_'+id).val();
   var gran = $('#txt_gran_t_'+id).val();
   if(gran=='')
   {
    gran = 0;
   }
   var valor = gran-total;
   var tt = 0;
   var porc = parseInt((valor*100)/total);

    $('#txt_porcentaje_'+id).val(porc);
    $('#txt_valor_'+id).val(valor.toFixed(2));
    $('#txt_gran_t_'+id).val(parseFloat(gran).toFixed(2));

     for (var i= 1; i < num_li+1; i++) {
    tt+= parseFloat($('#txt_gran_t_'+i).val());
    
  }
  $('#txt_tt').text(tt.toFixed(2));
  $('#txt_total2').text(tt.toFixed(2));

}

function preview()
{
  for (var i= 1; i < num_li+1; i++) {
      $('#btn_linea_'+i).click();
  }
}


function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function Ver_Comprobante_clinica(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante_clinica=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function reporte_excel(comprobante)
{
    url='../controlador/farmacia/facturacion_insumosC.php?reporte_excel=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function reporte_excel_clinica(comprobante)
{
    url='../controlador/farmacia/facturacion_insumosC.php?reporte_excel_clinica=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function guardar_uti(linea,pos)
{
  var parametros = 
  {
    'linea':linea,
    'utilidad':$('#txt_porcentaje_'+pos).val(),
  } 
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/facturacion_insumosC.php?guardar_utilidad=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          Swal.fire('Linea editada','','success')
          cargar_pedidos();
          
        }
      }
    });

}


function facturar()
{
  var servicio =$('#ddl_servicios option:selected').text();
  var servicio_cod = $('#ddl_servicios').val();
  if(servicio =='')
  {
    Swal.fire('Seleccione un servicio a facturar','','info');
    return false;
  }

   var parametros = 
  {
    'servicio':servicio,
    'servicio_cod': servicio_cod,
    'comprobante':'<?php echo $cod;?>',
    'total':$('#txt_total2').text(),
  } 
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/facturacion_insumosC.php?facturar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          Swal.fire('Factura Generada','','success')
          cargar_pedidos();
          
        }
      }
    });
}

 function numeroFactura(){
    $.ajax({
      type: "POST",
      url: '../controlador/farmacia/facturacion_insumosC.php?numFactura=true',
      // data: {
      //   'DCLinea' :'FA',
      // }, 
      success: function(data)
      {
        // datos = JSON.parse(data);
        // labelFac = "("+datos.autorizacion+") No. "+datos.serie;
        // document.querySelector('#numeroSerie').innerText = labelFac;
        $("#factura").val(datos.codigo);
      }
    });
  }

</script>
  <div class="row">
    <div class="col-lg-8 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>        
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
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
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default"  data-toggle="dropdown" title="Generar pdf" aria-expanded="true">
               <i class="fa fa-caret-down"></i>
               <img src="../../img/png/pdf.png"></button>
            <ul class="dropdown-menu">
             <li><a href="#" id="imprimir_pdf" onclick="Ver_Comprobante_clinica('<?php echo $cod; ?>')" >Para Clinica</a></li>
              <li><a href="#" id="imprimir_pdf_2" onclick="Ver_Comprobante('<?php echo $cod; ?>')" >Para paciente</a></li>
            </ul>
        </div>

        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" data-toggle="dropdown" aria-expanded="true" title="Generar pdf"> 
             <i class="fa fa-caret-down"></i>
              <img src="../../img/png/table_excel.png">
            </button>
             <ul class="dropdown-menu">
             <li><a href="#" id="imprimir_pdf" onclick="reporte_excel_clinica('<?php echo $cod; ?>')">Para Clinica</a></li>
              <li><a href="#" id="imprimir_pdf_2" onclick="reporte_excel('<?php echo $cod; ?>')" >Para paciente</a></li>
            </ul>
        </div>
 	</div>
</div>
<div class="row"><br> 
	<div class="col-sm-12">
		<div class="panel panel-primary" style="margin: 2px;">
		  <div class="panel-heading">Datos de paciente</div>
		  <div class="panel-body">
		  	<div class="row">
		  		<div class="col-xs-4">
		  			<b>Paciente: </b><i id="paciente">asdasd</i>		  			
		  		</div>
		  		<div class="col-xs-4">
		  			<b>Comprobante: </b><i id="comp">asdasd</i>		  			
		  		</div>
		  		<div class="col-xs-4">
		  			<b>Fecha: </b><i id="fecha">asdasd</i>		  			
		  		</div>		  		
		  	</div>
		  	<div class="row">
		  		<div class="col-sm-8">
		  			<b>Detalle: </b><i id="detalle">asdasd</i>		
		  		</div>
          <div class="col-sm-4">
            <b>Numero Factura: </b><i id="factura">01</i>    
          </div>
		  	</div>
		  	
		  	
		  </div>
		</div>		
	</div><br>
   <div class="col-sm-12">
    <div class="col-sm-12 text-right">
      <button class="btn btn-primary" onclick="preview()">Guardar utilidad</button>   
      <!-- <button class="btn btn-success" onclick="facturar()">Facturar</button>       -->
    </div>
  </div>
  <div class="col-sm-12">
    <div class="col-sm-6">
      <!-- <select class="form-control input-sm" id="ddl_servicios" name="ddl_servicios">
        <option value="">Seleccione </option>
      </select>  -->     
    </div>
    <div class="col-sm-6 text-right">
      <h4><b>Gran Total:</b> <b id="txt_total2">0.00</b></h4>
    </div>    
  </div>
	<div class="col-sm-12">
        	<div class="table-responsive">
        		<table class="table table-hover">
        			<thead>
        				<th>Codigo</th>
        				<th>Producto</th>
        				<th>Cantidad</th>
        				<th>Precio Uni</th>
        				<th>Precio Total</th>
        				<th> % Utilidad</th>
        				<th>Valor utilidad</th>
        				<th>Gran total</th>
                <th></th>
        			</thead>
        			<tbody id="tbl_body">
        				
        			</tbody>
        		</table>
        	</div>
  	</div>
    <div class="row">
       <style type="text/css">
          #datos_t tbody tr:nth-child(even) { background:#fffff;}
          #datos_t tbody tr:nth-child(odd) { background: #e2fbff;}
          #datos_t tbody tr:nth-child(even):hover {  background: #DDB;}
          #datos_t thead { background: #afd6e2; }
          #datos_t tbody tr:nth-child(odd):hover {  background: #DDA;}
          #datos_t table {border-collapse: collapse;}
          #datos_t table, th, td {  border: solid 1px #aba0a0;  padding: 2px;  }
          #datos_t tbody { box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);  }
          #datos_t thead { background: #afd6e2;  box-shadow: 10px 0px 6px rgba(0, 0, 0, 0.6);} 

          /*#datos_t tbody { display:block; height:300px;  overflow-y:auto; width:fit-content;}*/
          /*#datos_t thead,tbody tr {    display:table;  width:100%;  table-layout:fixed; } */
          #datos_t thead { width: calc( 100% - 1.2em ) /*scrollbar is average 1em/16px width, remove it from thead width*/ }


       </style>      
    </div>
</div>

