<?php $reporte = ''; $facturar='';$numero=''; if(isset($_GET['reporte'])){$reporte=$_GET['reporte'];}if(isset($_GET['factura'])){$factura=$_GET['factura'];} if(isset($_GET['comprobante'])){$numero=$_GET['comprobante'];}?>
<script type="text/javascript">
   $( document ).ready(function() {
   	 var reporte = '<?php echo $reporte; ?>';   	 
   	 var factura = '<?php echo $factura; ?>';  	 
   	 var numero = '<?php echo $numero; ?>';

   	 if(reporte==1)
   	 {
   	 	$('#lbl_fac').text(factura);
   	 	$('#tipo_cli').text('Proveedor');
   	 	detalle_ingresos();
   	 }

   	 if(reporte==4)
   	 {

   	 	$('#lbl_fac').text(factura);
   	 	if(numero==0)
   	 	{		$('#ti').text('Numero de pedido');	
   	 			$('#tipo_cli').text('Paciente');
   	 			$('#ti_to').text('Total de pedido');
   	 			$('#lbl_fac').text(factura);
   	 	}else
   	 	{		$('#ti').text('Numero de comprobante');
   	 			$('#ti_to').text('Total de comprobante');
   	 			$('#lbl_fac').text(numero);
   	 	}
   	 	detalle_descargo();
   	 }
   })

function detalle_ingresos()
   {
    // $('#tbl_op2').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
   	 var parametros=
    {
      'comprobante':'<?php echo $numero; ?>',
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?detalle_reporte_ingresos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#detalle_ingresos').html(response.tabla);
        $('#proveedor').text(response.proveedor);
        $('#lbl_fecha').text(response.fecha);
        $('#lbl_total').text(response.total);
      }
    });

   }

   function detalle_descargo()
   {
    // $('#tbl_op2').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
   	 var parametros=
    {
      'comprobante':'<?php echo $numero; ?>',
      'orden':'<?php echo $factura; ?>',
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?detalle_reporte_descargos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#detalle_ingresos').html(response.tabla);
        $('#proveedor').text(response.proveedor);
        $('#lbl_fecha').text(response.fecha);
        $('#lbl_total').text(response.total);
      }
    });

   }

function reporte_excel()
{
	 var reporte = '<?php echo $reporte; ?>';   	 
 	 var factura = '<?php echo $factura; ?>';  	 
 	 var numero = '<?php echo $numero; ?>';

   var url = '../controlador/farmacia/farmacia_internaC.php?imprimir_excel_detalle=true&';
   var opcion = $('#ddl_opciones').val();
  
   if(reporte==1)
   {
    url+='opcion=1&comprobante='+numero+'&factura='+factura;    
   }  
  
   if(reporte==4)
   {
    url+='opcion=4&comprobante='+numero+'&factura='+factura;    
   }  
  
    window.open(url, '_blank');
}




</script>
  <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="../vista/farmacia.php?mod=Farmacia&acc=farmacia_interna&acc1=Farmacia%20interna&b=1&po=subcu#" class="btn btn-default" title="Regresar">
              <img src="../../img/png/back.png">
            </a>
        </div>        
       <!--  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div> -->
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 	</div>
 </div>
<h2 id="titulo"></h2>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="row box-body">
				<div class="col-sm-4">
					<!-- <div class=""> -->
						<b id="tipo_cli">Proveedor:</b>
						<p id="proveedor"></p>
					<!-- </div>					 -->
				</div>
				<div class="col-sm-3">
					<!-- <div class=""> -->
						<b>Fecha:</b>
						<p id="lbl_fecha"></p>
					<!-- </div>					 -->
				</div>
				<div class="col-sm-3">
					<!-- <div class=""> -->
						<b id="ti">Numero de Factura:</b>
						<p id="lbl_fac"></p>
					<!-- </div>					 -->
				</div>
				<div class="col-sm-2">
					<!-- <div class=""> -->
						<b id="ti_to">Total Factura:</b>
						<p id="lbl_total"></p>
					<!-- </div>					 -->
				</div>
			</div>			
		</div>
		<div class="box">
			<div class="col-sm-12 box box-primary"><br>
				<table class="table">
					<thead>
						<th>Fecha</th>
						<th>Producto</th>
						<th>Cantidad</th>
						<th> PVP</th>
						<th>Total</th>
					</thead>
					<tbody id="detalle_ingresos">
						
					</tbody>
				</table>				
			</div>
			
		</div>
	</div>
	
</div>