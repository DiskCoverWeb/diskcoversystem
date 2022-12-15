<script type="text/javascript">
 $(document).ready(function()
  {
  	delete_sientos_nc();
  	DCBodega();
  	DCMarca();
		autocoplete_contraCta()
  	autocoplete_articulos();

  	cargar_tabla();
  	autocoplete_clinete();


  	 $('#DCClientes').on('select2:select', function (e) {
      console.log(e);
      var data = e.params.data.data;
      // var dataM = e.params.data.dataMatricula;
      $('#TxtConcepto').val('Nota de Crédito de: '+data.Cliente);
        fecha= fecha_actual();
      	DCLineas(data.Cta_CxP);
      	DCTC(data.Codigo)    
    });


  })

function delete_sientos_nc()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?delete_sientos_nc=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         //console.log(data);
         //$('#tbl_datos').html(data);
      }
    });
}

function DCBodega()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCBodega=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	llenarComboList(data,'DCBodega'); 
      }
    });
}
function DCMarca()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCMarca=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	llenarComboList(data,'DCMarca'); 
      }
    });
}

function DCMarca()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCMarca=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	llenarComboList(data,'DCMarca'); 
      }
    });
}


function autocoplete_articulos(){
    $('#DCArticulo').select2({
      placeholder: 'Seleccione articulos',
      width:'90%',
      ajax: {
        url:   '../controlador/facturacion/notas_creditoC.php?DCArticulo=true',
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

function autocoplete_contraCta(){
    $('#DCContraCta').select2({
      placeholder: 'Seleccione cuenta',
      width:'90%',
      ajax: {
        url: '../controlador/facturacion/notas_creditoC.php?DCContraCta=true',
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


function cargar_tabla()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?tabla=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         console.log(data);
         $('#tbl_datos').html(data);
      }
    });
}

function DCLineas(cta_cxp)
{
	var parametros = 
	{
		'fecha':$('#MBoxFecha').val(),
		'cta_cxp':cta_cxp,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCLineas=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      console.log(data);         
       llenarComboList(data,'DCLineas');
      }
    });
}

function DCTC(codigoC)
{
	var parametros = 
	{
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCTC=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
         	 Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
         	llenarComboList(data,'DCTC');
         	// console.log(data);
         	DCSerie(data[0].codigo,codigoC);
         	 // console.log(data);
         }
      }
    });
}

function DCSerie(TC=false,codigoC=false)
{
	if(TC==false)	{		TC = $('#DCTC').val();	}
	if(codigoC==false)	{		codigoC = $('#DCClientes').val();	}
	var parametros = 
	{
		'TC':TC,
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCSerie=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
         	 Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
         	llenarComboList(data,'DCSerie');
         	DCFactura(data[0].codigo,TC,codigoC)
         	 // console.log(data);
         }
      }
    });
}

function DCFactura(Serie=false,TC=false,codigoC=false)
{
	if(Serie==false)	{		Serie = $('#DCSerie').val();	}
	if(TC==false)	{		TC = $('#DCTC').val();	}
	if(codigoC==false)	{		codigoC = $('#DCClientes').val();	}

	var parametros = 
	{
		'Serie':Serie,
		'TC':TC,
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCFactura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
         	 Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
         	llenarComboList(data,'DCFactura');
         	Detalle_Factura(data[0].codigo,Serie,TC,codigoC)
         	 // console.log(data);
         }
      }
    });
}

function Detalle_Factura(Factura=false,Serie=false,TC=false,codigoC=false)
{
	if(Factura==false)	{	Factura = $('#DCFactura').val();	}
	if(Serie==false)	{	Serie = $('#DCSerie').val();	}
	if(TC==false)	{	TC = $('#DCTC').val();	}
	if(codigoC==false)	{	codigoC = $('#DCClientes').val();	}

	var parametros = 
	{		
		'Factura':Factura,
		'Serie':Serie,
		'TC':TC,
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?Detalle_Factura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         
         	 console.log(data);
         
      }
    });
}

function autocoplete_clinete(){
      $('#DCClientes').select2({
        placeholder: 'Seleccione una beneficiario',
        width:'90%',
        ajax: {
          url:   '../controlador/facturacion/notas_creditoC.php?cliente=true',
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

	
</script>
<div class="row">
	<div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
		<div class="col-xs-2 col-md-2 col-sm-2">
			 <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
    		<img src="../../img/png/salire.png">
    	</a>
    	</div>
   </div>
</div>
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-4" style="padding:0px">Fecha NC</label>
          <div class="col-sm-8" style="padding:0px">
            <input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
	</div>
	<div class="col-sm-9">
		<div class="form-group">
          <label class="col-sm-1" style="padding:0px">Cliente</label>
          <div class="col-sm-11" style="padding:0px">
          	<select class="form-control input-xs" id="DCClientes" name="DCClientes" onchange="">
          		<option>Seleccione cliente</option>
          	</select>
          </div>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-sm-3">
		<b>Lineas de Nota de Credito</b>
		<select class="form-control input-xs" id="DCLineas">
          	<option value="">Seleccione</option>
        </select>
	</div>
	<div class="col-sm-3">
		<b>Autorizacion Nota de Credito</b>
		<input type="text" name="" class="form-control input-xs" value=".">
	</div>
	<div class="col-sm-1" style="padding:0px">
		<b>Serie</b>
		<input type="text" name="" class="form-control input-xs" value="001001">
	</div>
	<div class="col-sm-1" style="padding: 0px;">
		<b>Comp No.</b>
		<input type="text" name="" class="form-control input-xs" value="00000000">
	</div>
	<div class="col-sm-4">
		<b>Contra Cuenta a aplicar a la Nota de Credito</b>
		<select class="form-control input-xs" id="DCContraCta" name="DCContraCta">
      		<option>Seleccione cuenta</option>
      	</select>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
          <label class="col-sm-2" style="padding:0px">Motivo de la Nota de credito</label>
          <div class="col-sm-10" style="padding:0px">
            <input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs">
          </div>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-sm-1">
		<b>T.D.</b>
		<select class="form-control input-xs" id="DCTC" style="padding: 2px 5px;"  onchange="DCSerie()">
      		<option>Seleccione cliente</option>
      	</select>
	</div>
	<div class="col-sm-1" style="padding: 2px">
		<b>Serie</b>
		<select class="form-control input-xs" id="DCSerie" style="padding: 2px 7px;" onchange="DCFactura()">
      		<option>Seleccione cliente</option>
      	</select>
	</div>
	<div class="col-sm-2">
		<b>No.</b>
		<select class="form-control input-xs" id="DCFactura" onchange="Detalle_Factura()">
      		<option>Seleccione cliente</option>
      	</select>
	</div>
	<div class="col-sm-4">
		<b>Autorizacion del documento</b>
		<input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs">
	</div>
	<div class="col-sm-2">
		<b>Total de Factura</b>
		<input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs">
	</div>
	<div class="col-sm-2">
		<b>Saldo de Factura</b>
		<input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs">
	</div>	
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
          <label class="col-sm-2" style="padding:0px">Bodega</label>
          <div class="col-sm-10" style="padding:0px">
            <select class="form-control input-xs" id="DCBodega" name="DCBodega">
	      		<option>Seleccione bodega</option>
	      	</select>
          </div>
        </div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
          <label class="col-sm-2" style="padding:0px">Marca</label>
          <div class="col-sm-10" style="padding:0px">
            <select class="form-control input-xs" id="DCMarca" name="DCMarca">
	      		<option>Seleccione marca</option>
	      	</select>
          </div>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-primary" style="margin-bottom: 0px;">			
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-7">
						<b>Producto</b>
						<select class="form-control input-xs" id="DCArticulo" name="DCArticulo">
				          	<option>Seleccione producto</option>
				        </select>
					</div>
					<div class="col-sm-1" style="padding:3px">
						<b>Cantidad</b>
						<input type="text" name="" class="form-control input-xs" value="0">
					</div>
					<div class="col-sm-1" style="padding:3px">
						<b>P.V.P.</b>
						<input type="text" name="" class="form-control input-xs" value="0.00">
					</div>
					<div class="col-sm-1" style="padding:3px">
						<b>DESC</b>
						<input type="text" name="" class="form-control input-xs" value="0.00">
					</div>
					<div class="col-sm-2">
						<b>TOTAL</b>		
						<input type="text" name="" class="form-control input-xs" value="0.00">
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="panel panel-primary">			
			<div class="panel-body">
				<div class="row" style="height: 200px;">
					<div class="col-sm-12" id="tbl_datos">
						
					</div>	
				</div>
			</div>
		</div> -->
	</div>
</div>
<div class="row" style="height: 200px;">
					<div class="col-sm-12" id="tbl_datos">
						
					</div>	
				</div>


<div class="row">
	<div class="col-sm-3">
		
	</div>
	<div class="col-sm-3">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">sub total sin iva</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">sub total con iva</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Total descuento</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="" class="form-control input-xs">
		          </div>
		        </div>
			</div>
		</div>		
	</div>
	<div class="col-sm-3">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Sub total</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Total del I.V.A</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Total Nota Credito</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="" class="form-control input-xs">
		          </div>
		        </div>
			</div>
		</div>		
	</div>
	<div class="col-sm-3">		
		<button class="btn btn-default">
			<img src="../../img/png/grabar.png">
			<br>
			Nota de credito
		</button>
	</div>
</div>