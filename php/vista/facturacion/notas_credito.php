<?php  //print_r( $_SESSION['SETEOS']);die();?>
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
      // console.log(e);
      var data = e.params.data.data;
      // var dataM = e.params.data.dataMatricula;
      $('#TxtConcepto').val('Nota de Crédito de: '+data.Cliente);
        fecha= fecha_actual();
      	DCLineas(data.Cta_CxP);
      	DCTC(data.Codigo)    
    });

    $('#DCArticulo').on('select2:select', function (e) {
      // console.log(e);
      var data = e.params.data.data;
      $('#TextVUnit').val(data.PVP);
      // $('#TextDesc').val(data.);
      // $('#LabelVTotal').val(data.);
      console.log(data);
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
         $('#tbl_datos').html(data.tabla);

         $('#TxtConIVA').val(data.TxtConIVA);
         $('#TxtDescuento').val(data.TxtDescuento);
         $('#TxtIVA').val(data.TxtIVA);
         $('#TxtSaldo').val(data.TxtSaldo);
         $('#TxtSinIVA').val(data.TxtSinIVA);
         $('#LblTotalDC').val(data.LblTotalDC);
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
      // console.log(data);         
       llenarComboList(data,'DCLineas');
       $('#TextBanco').val(data[0].Autorizacion);
       $('#TextCheqNo').val(data[0].codigo);
       numero_autorizacion();
      }
    });
}


function numero_autorizacion()
{
  var parametros = 
  {
    'serie':$('#DCLineas').val(),
  }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?numero_autorizacion=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        $('#TextCompRet').val(data);
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
        
        $('#TxtAutorizacion').val(data[0].Autorizacion);
        $('#LblTotal').val(data[0].Total_MN); 
        $('#LblSaldo').val(data[0].Saldo_MN); 
        Lineas_Factura(Factura,Serie,TC,data[0].Autorizacion)
        cargar_tabla();
         
      }
    });
}

function Lineas_Factura(Factura=false,Serie=false,TC=false,Autorizacion=false)
{
	if(Factura==false)	{	Factura = $('#DCFactura').val();	}
	if(Serie==false)	{	Serie = $('#DCSerie').val();	}
	if(TC==false)	{	TC = $('#DCTC').val();	}

	var parametros = 
	{		
		'Factura':Factura,
		'Serie':Serie,
		'TC':TC,
		'Autorizacion':Autorizacion,
		'Fecha':$('#MBoxFecha').val(),
		'CodigoC':$('#DCClientes').val(),
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?Lineas_Factura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      { 
        if(data!=null)
        {       
          $('#TxtAutorizacion').val(data[0].Autorizacion);
          $('#LblTotal').val(data[0].Saldo_MN); 
          $('#LblSaldo').val(data[0].Total_MN); 
        }
         
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

function TextDesc_lost()
{
   Factura = $('#DCFactura').val();  
   Serie = $('#DCSerie').val(); 
   TC = $('#DCTC').val();  
   codigoC = $('#DCClientes').val(); 
   auto =  $('#TxtAutorizacion').val();
   if($('#TextCant').val()=='' || $('#TextCant').val()=='0' || $('#TextCant').val() =='.')
   {

      Swal.fire('Cantidad invalida','','info')
      return false;
   }

    var parametros = 
    {   
      'productos':$('#DCArticulo').val(),
      'Factura':Factura,
      'Serie':Serie,
      'CodigoC':codigoC,
      'TC':TC,
      'Autorizacion':auto,
      'TextCant':$('#TextCant').val(),
      'TextVUnit':$('#TextVUnit').val(),
      'TextDesc':$('#TextDesc').val(),
      'MBoxFecha':$('#MBoxFecha').val(),
      'Cod_Bodega':$('#DCBodega').val(),
      'Cod_Marca':$('#DCMarca').val(),
      'ConIVA':$('#TxtConIVA').val(),
      'Descuento':$('#TxtDescuento').val(),
      'IVA':$('#TxtIVA').val(),
      'Saldo':$('#TxtSaldo').val(),
      'SinIVA':$('#TxtSinIVA').val(),
      'TotalDC':$('#LblTotalDC').val(),
    }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?guardar=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        if(data==-3)
        {
          Swal.fire('el producto ya sea ingresado','','info')
        }
        cargar_tabla();         
      }
    });
}

function generar_pdf()
{
  $.ajax({
    type: "POST",
    url: '../controlador/facturacion/notas_creditoC.php?generar_pdf=true',
    //data: datos,
    dataType:'json', 
    success: function(data)
    {
      if(data.respuesta == 1)
        {
            Swal.fire({
                type: 'success',
                title: 'Factura Procesada y Autorizada',
                confirmButtonText: 'Ok!',
                allowOutsideClick: false,
            }).then(function() {
                var url = '../../TEMP/' + data.pdf + '.pdf';
                window.open(url, '_blank'); 
                location.reload();

            })
        }
      cargar_tabla();         
    }
  });
}

function generar_nc()
{
  if($('#DCContraCta').val()=='')
  {
    Swal.fire('Contra Cuenta a aplicar a la Nota de Credito','','info')
    return false;
  }
  $('#myModal_espera').modal('show');
  var cliente = $('#DCClientes option:selected').text();
  datos = $('#form_nc').serialize();  
  datos = datos+'&Cliente='+cliente;
  $.ajax({
    type: "POST",
    url: '../controlador/facturacion/notas_creditoC.php?generar_nota_credito=true',
    data: datos,
    dataType:'json', 
    success: function(data)
    {
      $('#myModal_espera').modal('hide');
      
        if(data.respuesta==1 && data.clave!='')
        { 
          Swal.fire({
            type:'success',
            title: 'Nota de Credito Procesada y Autorizada',
            confirmButtonText: 'Ok!',
            allowOutsideClick: false,
          }).then(function(){
            var url=  '../../TEMP/'+data.pdf+'.pdf';
            window.open(url, '_blank'); 
            location.reload();    

          })
        }else if(data.respuesta==1 && data.clave=='')
        { 
          Swal.fire({
            type:'success',
            title: 'Nota de Credito Procesada',
            confirmButtonText: 'Ok!',
            allowOutsideClick: false,
          }).then(function(){
            var url=  '../../TEMP/'+data.pdf+'.pdf';
            window.open(url, '_blank'); 
            location.reload();    

          })
        }else if(data.respuesta==-1)
        {

          Swal.fire('XML DEVUELTO:'+data.text,'XML DEVUELTO','error').then(function(){ 
            var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');   
            tipo_error_sri(data.clave);
          }); 
        }else if(data.respuesta==2)
        {
          // tipo_error_comprobante(clave)
          Swal.fire('XML devuelto','','error'); 
          tipo_error_sri(data.clave);
        }
        else if(data.respuesta==4)
        {
          Swal.fire('SRI intermitente intente mas tarde','','info');  
        }else if(data.respuesta==5)
        {
          Swal.fire('El Saldo Pendiente es menor que el total de la Nota de Credito','','info');  
        }else
        {
          Swal.fire('XML devuelto por:'+data.text,'','error');  
        }         

      cargar_tabla();         
    }
  });
}

function valida_cxc()
{
   var serie =  $('#DCLineas').val();
   if(serie=='' || serie =='.')
   {
     Swal.fire('Lineas Cxc No asignada o fuera de fecha','','info');
     $('#TextCheqNo').val('.');
     $('#TextCompRet').val('00000001');
   }
}	

function validar_procesar()
{
  // cambiar el uno opr la variable corespondiente
   numero = $('#TextCompRet').val();
   if(1 != null)
   {
     Swal.fire({
         title: 'Desea procesar esta nota de credito?',
         // text: "Esta usted seguro de que quiere borrar este registro!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {
            $('#ReIngNC').val('1');
         }
       })
   }
}

function eliminar(CODIGO,A_NO)
{
  parametros = 
  {
    'codigo':CODIGO,
    'a_no':A_NO,
  }
   $.ajax({
    type: "POST",
    url: '../controlador/facturacion/notas_creditoC.php?eliminar_linea=true',
    data: {parametros,parametros},
    dataType:'json', 
    success: function(data)
    {
      
      cargar_tabla();         
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
<form id="form_nc">
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
    <input type="hidden" name="ReIngNC" id="ReIngNC" value="0">
		<b>Lineas de Nota de Credito</b>
		<select class="form-control input-xs" id="DCLineas" name="DCLineas" onblur="valida_cxc()">
          	<option value="">Seleccione</option>
        </select>
	</div>
	<div class="col-sm-3">
		<b>Autorizacion Nota de Credito</b>
		<input type="text" name="TextBanco" id="TextBanco" class="form-control input-xs" value=".">
	</div>
	<div class="col-sm-1" style="padding:0px">
		<b>Serie</b>
		<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control input-xs" value="001001">
	</div>
	<div class="col-sm-1" style="padding: 0px;">
		<b>Comp No.</b>
		<input type="text" name="TextCompRet" id="TextCompRet" class="form-control input-xs" value="00000000" onblur="validar_procesar()">
	</div>
	<div class="col-sm-4">
		<b>Contra Cuenta a aplicar a la Nota de Credito</b>
		<select class="form-control input-xs" id="DCContraCta" name="DCContraCta">
      		<option value="">Seleccione cuenta</option>
      	</select>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
          <label class="col-sm-3" style="padding:0px">Motivo de la Nota de credito</label>
          <div class="col-sm-9" style="padding:0px">
            <input type="text" name="TxtConcepto" id="TxtConcepto" class="form-control input-xs">
          </div>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-sm-1">
		<b>T.D.</b>
		<select class="form-control input-xs" id="DCTC" name="DCTC" style="padding: 2px 5px;"  onchange="DCSerie()">
      		<option>Seleccione cliente</option>
      	</select>
	</div>
	<div class="col-sm-1" style="padding: 2px">
		<b>Serie</b>
		<select class="form-control input-xs" id="DCSerie" name="DCSerie" style="padding: 2px 7px;" onchange="DCFactura()">
      		<option>Seleccione cliente</option>
      	</select>
	</div>
	<div class="col-sm-2">
		<b>No.</b>
		<select class="form-control input-xs" id="DCFactura" name="DCFactura" onchange="Detalle_Factura()">
      		<option>Seleccione cliente</option>
      	</select>
	</div>
	<div class="col-sm-4">
		<b>Autorizacion del documento</b>
		<input type="text" name="TxtAutorizacion" id="TxtAutorizacion" class="form-control input-xs">
	</div>
	<div class="col-sm-2">
		<b>Total de Factura</b>
		<input type="text" name="LblTotal" id="LblTotal" class="form-control input-xs" value="0.00">
	</div>
	<div class="col-sm-2">
		<b>Saldo de Factura</b>
		<input type="text" name="LblSaldo" id="LblSaldo" class="form-control input-xs" value="0.00">
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
						<input type="text" name="TextCant" id="TextCant" class="form-control input-xs" value="0">
					</div>
					<div class="col-sm-1" style="padding:3px">
						<b>P.V.P.</b>
						<input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-xs" value="0.00">
					</div>
					<div class="col-sm-1" style="padding:3px">
						<b>DESC</b>
						<input type="text" name="TextDesc" id="TextDesc" class="form-control input-xs" value="0.00" onblur="TextDesc_lost()">
					</div>
					<div class="col-sm-2">
						<b>TOTAL</b>		
						<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control input-xs" value="0.00">
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
		            <input type="text" name="TxtSinIVA" id="TxtSinIVA" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">sub total con iva</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="TxtConIVA" id="TxtConIVA" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Total descuento</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="TxtDescuento" id="TxtDescuento" class="form-control input-xs">
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
		            <input type="text" name="TxtSaldo" id="TxtSaldo" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Total del I.V.A</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="TxtIVA" id="TxtIVA" class="form-control input-xs">
		          </div>
		        </div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:0px">Total Nota Credito</label>
		          <div class="col-sm-6" style="padding:0px">
		            <input type="text" name="LblTotalDC" id="LblTotalDC" class="form-control input-xs">
		          </div>
		        </div>
			</div>
		</div>		
	</div>
	<div class="col-sm-3">		
		<button type="button" class="btn btn-default" onclick="generar_nc()">
			<img src="../../img/png/grabar.png">
			<br>
			Nota de credito
		</button>
    <!-- <button type="button" class="btn btn-default" onclick="generar_pdf()">
      <img src="../../img/png/grabar.png">
      <br>
      Nota de credito
    </button> -->
	</div>
</div>
</form>