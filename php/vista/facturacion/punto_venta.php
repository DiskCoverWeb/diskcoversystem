<?php date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();
$TC = 'FA'; if(isset($_GET['tipo'])){$TC = $_GET['tipo'];}
?>
<script type="text/javascript">
	eliminar_linea('','');
  $(document).ready(function () {
    catalogoLineas();
  	autocomplete_cliente(); 
  	autocomplete_producto();
  	serie();  	
  	// tipo_documento();
  	DCBodega();
  	DGAsientoF();


  	DCBanco();

  	$(document).keyup(function(e) {
	     if (e.key === "Escape") { // escape key maps to keycode `27`
	       ingresar_total();
	     }
	     console.log(e.key);
	});


	 // $('#DCCliente').on('select2:select', function (e) {
  //     var data = e.params.data.data;
  //     console.log(e);
  //     $('#Lblemail').val(data[0].Email);
  //     $('#LblRUC').val(data[0].CI_RUC);
  //     $('#codigoCliente').val(data[0].Codigo);
  //     $('#LblT').val(data[0].T);

  //     console.log(data);
  //   });


  });


  function usar_cliente(nombre,ruc,codigo,email,t='N')
  {
  	$('#Lblemail').val(email);
	  $('#LblRUC').val(ruc);
	  $('#codigoCliente').val(codigo);
	  $('#LblT').val(t);
	  // $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
	  $('#DCCliente').append($('<option>',{value:codigo, text:nombre,selected: true }));
	  $('#myModal').modal('hide');
  }

  function select()
  {
  	var seleccionado = $('#DCCliente').select2("data");
  	var data = seleccionado[0].data;
  	// console.log(data);
  	$('#Lblemail').val(data[0].Email);
	  $('#LblRUC').val(data[0].CI_RUC);
	  $('#codigoCliente').val(data[0].Codigo);
	  $('#LblT').val(data[0].T);
  }

  function validar_cta()
  {
  	var parametros = 
  	{
  		'TC':'<?php echo $TC; ?>',
  		'Serie': $('#LblSerie').text(),
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?validar_cta=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			if(data!=1)
			{
			Swal.fire({
				  type:'info',
				  title: data,
				  text:'',
				  allowOutsideClick: false,
				});
		}
		}
	});

  }
  function tipo_documento()
  {
  	var tc = $('#DCLinea').val();
  	tc = tc.split(' ');

  	// var TipoFactura = '<?php //echo $TC; ?>';

  	var TipoFactura = tc[0];

  	var Porc_IVA = '<?php echo $_SESSION['INGRESO']['porc']; ?>';
  	var Porc_IVA = parseFloat(Porc_IVA)*100;
  	 if(TipoFactura == "PV"){
  	    // FacturasPV.Caption = "INGRESAR TICKET"
     	$('#Label1').text(" TICKET No.");
     	$('#Label3').text(" I.V.A. "+Porc_IVA.toFixed(2)+"%")
	  }else if(TipoFactura == "CP"){
	     // FacturasPV.Caption = "INGRESAR CHEQUES PROTESTADOS"
	     $('#Label1').text(" COMPROBANTE No.");
	     $('#Label3').text(" I.V.A. 0.00%")
	  }else if(TipoFactura == "NV"){
	     // FacturasPV.Caption = "INGRESAR NOTA DE VENTA"
	     $('#Label1').text(" NOTA DE VENTA No.");
	     $('#Label3').text(" I.V.A. 0.00%")
	  }else if(TipoFactura == "DO"){
	     // FacturasPV.Caption = "INGRESAR NOTA DE DONACION"
	     $('#Label1').text(" NOTA DE DONACION No.");
	     $('#Label3').text(" I.V.A. 0.00%")
	  }else if(TipoFactura == "LC"){
	     // FacturasPV.Caption = "INGRESAR LIQUIDACION DE COMPRAS"
	     $('#Label1').text(" LIQUIDACION DE COMPRAS No.");
	     $('#Label3').text(" I.V.A. 0.00%")
	     OpcDiv.value = True
	     // 'If Len(Opc_Grupo_Div) > 1 Then Grupo_Inv = Opc_Grupo_Div
	  }else{
	     // FacturasPV.Caption = "INGRESAR FACTURA"
	     $('#Label1').text(" FACTURA No.");
	     $('#Label3').text(" I.V.A. "+Porc_IVA.toFixed(2)+"%")
	     $('#CodDoc').val("01");
	   }
  }


  function autocomplete_cliente()
  {
    $('#DCCliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '../controlador/facturacion/punto_ventaC.php?DCCliente=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }


  function autocomplete_producto()
  {
  	var parametros = '&TC='+'<?php echo $TC; ?>';
    $('#DCArticulo').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '../controlador/facturacion/punto_ventaC.php?DCArticulo=true'+parametros,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function DCBanco()
  {
  	// alert('das');
    $('#DCBanco').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '../controlador/facturacion/punto_ventaC.php?DCBanco=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }


  function serie()
  {
  var TC = '<?php echo $TC; ?>';  	
  	var parametros =
  	{
  		'TC':TC,
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?LblSerie=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			if(data.serie!='.'){
			$('#LblSerie').text(data.serie);
			$('#TextFacturaNo').val(data.NumCom);
		  }else
		  {
		  	numeroFactura();
		  }
			validar_cta();
		}
	});
  }

  function DCBodega()
  { 	
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?DCBodega=true',
		//data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			llenarComboList(data,'DCBodega'); 
		}
	});

  }
  function DGAsientoF()
  { 	
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?DGAsientoF=true',
		//data: {parametros: parametros},
		dataType:'json',		
		beforeSend: function () {	$('#tbl_DGAsientoF').html('<img src="../../img/gif/loader4.1.gif" width="40%"> ');}, 	
		success: function(data)
		{
			$('#tbl_DGAsientoF').html(data);
		}
	});

  }

  function Articulo_Seleccionado()
  {
  	var parametros = {
  		'codigo':$('#DCArticulo').val(),
  		'fecha':$('#MBFecha').val(),
  		'CodBod':$('#DCBodega').val(),
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?ArtSelec=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			// console.log(data);
			if(data.respueta==true)
			{
				if(data.datos.Stock<=0)
				{
					Swal.fire(data.datos.Producto+' ES UN PRODUCTO SIN EXISTENCIA','','info').then(function()
						{
							$('#DCArticulo').empty();
							// $('#LabelStock').val(0);
						});

				}else{

					$('#LabelStock').val(data.datos.Stock);
					$('#TextVUnit').val(data.datos.PVP);
					$('#LabelStock').focus();
					// $('#').val(data.datos.);
				}

			}
		}
	});

  }

  function calcular()
  {
  	 var VUnit = $('#TextVUnit').val();
  	 if(VUnit=='' || VUnit==0)
  	 {
  	 	Swal.fire('INGRESE UN PRECIO VALIDO','PUNTO VENTA','info').then(function(){$('#TextVUnit').select()})
  	 }
  	 var Cant = $('#TextCant').val();
  	 var OpcMult = $('#OpcMult').prop('checked');
  	 var ban = $('#TextCheque').val()
  	 if(is_numeric(VUnit) && is_numeric(Cant)){
      if( VUnit== 0 ){ VUnit = "0.01";}
      if(OpcMult){ Real1 = parseFloat(Cant) *parseFloat(VUnit);}else{ Real1 = parseFloat(Cant) / parseFloat(VUnit);}
   // console.log(Real1);
      $('#LabelVTotal').val(Real1.toFixed(4));
   }else{
     $('#LabelVTotal').val(0.0000);
   }
  }

  function valida_Stock()
  {
  	var Cantidad = $('#TextCant').val();
  	if(Cantidad=='' || Cantidad==0){Swal.fire('INGRESE UNA CANTIDAD VALIDA','PUNTO DE VENTA','info').then(function(){$('#TextCant').select();});}
  	var DifStock = parseFloat($('#LabelStock').val()) - parseFloat(Cantidad);
  	var producto = $('#DCArticulo option:selected').text();
  	if(DifStock.toFixed(2) < 0) {
  		Swal.fire(producto+' NO PUEDE QUEDAR EXISTENCIA NEGATIVA, SOLICITE ALIMENTACION DE STOCK','PUNTO DE VENTA','info').then(function(){
  			$('#TextCant').select();
  		});
  		// $('#DCArticulo').focus();
    }
  }

  function ingresar()
  {
  	 var cli = $('#DCCliente').val();
  	if(cli=='')
  	{
  		Swal.fire('Seleccione un cliente','','info');
  		return false;
  	}
  	var tc = $('#DCLinea').val();
  	tc = tc.split(' ');
  	var parametros = 
  	{
  		'opc':$('input[name="radio_conve"]:checked').val(),
  		'TextVUnit':$('#TextVUnit').val(),
  		'TextCant':$('#TextCant').val(),
  		'TC':tc[0],
  		'TxtDocumentos':$('#TxtDocumentos').val(),
  		'Codigo':$('#DCArticulo').val(),
  		'fecha':$('#MBFecha').val(),
  		'CodBod':$('#DCBodega').val(),
  		'VTotal':$('#LabelVTotal').val(),
  		'TxtRifaD':$('#TxtRifaD').val(),
  		'TxtRifaH':$('#TxtRifaH').val(),
  		'Serie':$('#LblSerie').text(),
  		'CodigoCliente':$('#codigoCliente').val(),
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?IngresarAsientoF=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			if(data==2)
			{
				Swal.fire('Ya no puede ingresar mas productos','','info');
			}else if(data==null)
			{
				DGAsientoF();
				Calculos_Totales_Factura();
			}else
			{
				Swal.fire('Intente mas tarde','','info');
			}
		}
	});

  }

  function Eliminar(A_no,cod)
  {
  	 Swal.fire({
         title: 'Esta seguro?',
         text: "Esta usted seguro de eliminar este registro!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {
         eliminar_linea(cod,A_no);
         }
       })
  }
  function eliminar_linea(cod,A_no)
  {
  	var parametros = 
  	{
  		'cod':cod,
  		'A_no':A_no,
  	}
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/punto_ventaC.php?eliminar_linea=true',
			data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				if(data==1)
				{
					DGAsientoF();
				}
			}
		});

  }

  function Calculos_Totales_Factura()
  {
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?Calculos_Totales_Factura=true',
		// data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			// console.log(data)
			$('#LabelSubTotal').val(parseFloat(data.SubTotal).toFixed(2));
		    $('#LabelConIVA').val(parseFloat(data.Con_IVA).toFixed(2));
		    $('#LabelIVA').val(parseFloat(data.Total_IVA).toFixed(2));
		    $('#LabelTotal').val(parseFloat(data.Total_MN).toFixed(2));			
		}
	});
  }

  function ingresar_total()
  {
  	Swal.fire({
  	  allowOutsideClick:false,
	  title: 'INGRESE EL TOTAL DEL RECIBO',
	  input: 'text',
	  inputValue:0,
	  inputAttributes: {
	    autocapitalize: 'off',
	  },
	  showCancelButton: true,
	  confirmButtonText: 'Aceptar',
	  showLoaderOnConfirm: true,	  
	}).then((result) => {
	  if (result.value>=0) {
	  	var total = result.value;
	  	 editar_factura(total);	  	
	  }else
	  {

	  }
	})
  }

  function editar_factura(total)
  {
  	var parametros = 
  	{
  		'total':total,
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?editar_factura=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			DGAsientoF();
			Calculos_Totales_Factura();
		}
	});
  }

   function generar()
  {
  	var bod = $('#DCBodega').val();
  	if(bod=='')
  	{

  		Swal.fire('Ingrese o Seleccione una bodega','','info').then(function(){$('#TextFacturaNo').focus()});
  		return false;
  	}
  	var Cli = $('#DCCliente').val();
  	if(Cli=='')
  	{
  		Swal.fire('Seleccione un cliente','','info');
  		return false;
  	}
  	var total = parseFloat($('#LabelTotal').val()).toFixed(4);
  	var efectivo = parseFloat($('#TxtEfectivo').val()).toFixed(4);
  	var banco = parseFloat($('#TextCheque').val()).toFixed(4);
  	 Swal.fire({
  	 	allowOutsideClick:false,
         title: 'Esta Seguro que desea grabar:',
         text: "Recibo No. "+$('#TextFacturaNo').val(),
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {
         	if(banco>total)
         	{
         		Swal.fire('Si el pago es por banco este no debe superar el total de la factura','PUNTO VENTA','info').then(function(){$('#TextCheque').select();});
         		return false;
         	}
          generar_factura()
         }
       })
  }

  function generar_factura()
  {
  	$('#myModal_espera').modal('show');
  	var tc = $('#DCLinea').val();
  	tc = tc.split(' ');

  	var parametros = 
  	{
  		'MBFecha':$('#MBFecha').val(),
  		'TxtEfectivo':$('#TxtEfectivo').val(),
  		'TextFacturaNo':$('#TextFacturaNo').val(),
  		'TxtNota':$('#TxtNota').val(),
  		'TxtObservacion':$('#TxtObservacion').val(),
  		'TipoFactura':tc[0],
  		'TxtGavetas':$('#TxtGavetas').val(),
  		'CodigoCliente':$('#codigoCliente').val(),
  		'email':$('#Lblemail').val(),
  		'CI':$('#LblRUC').val(),
  		'NombreCliente':$('#DCCliente option:selected').text(),
  		'TC':tc[0],
  		'Serie':$('#LblSerie').text(),
  		'DCBancoN':$('#DCBanco option:selected').text(),
  		'DCBancoC':$('#DCBanco').val(),
  		'T':$('#LblT').val(),
  		'TextBanco': $('#TextBanco').val(),
  		'TextCheqNo':$('#TextCheqNo').val(),
  		'TextBanco': $('#TextBanco').val(),
  		'TextCheqNo':$('#TextCheqNo').val(),
  		'CodDoc':$('#CodDoc').val(),
  		'valorBan':$('#TextCheque').val(),
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?generar_factura=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			$('#myModal_espera').modal('hide');
			// console.log(data);
			if(data.respuesta.respuesta==1)
			{
				var url=  '../../TEMP/'+data.pdf+'.pdf';
			// console.log(url);
			window.open(url, '_blank'); 		
			

				Swal.fire({
				  type:'success',
				  title: 'Factura Procesada',
				  confirmButtonText: 'Ok!',
				  allowOutsideClick: false,
				}).then((result) => {
				  /* Read more about isConfirmed, isDenied below */
				  if (result.value) {
				  	location.reload();
				  } 
				})	
				
			}else if(data.respuesta==-1)
			{
				Swal.fire(data.text,'','error').then(function(){ var url=  '../../TEMP/'+data.pdf+'.pdf';		window.open(url, '_blank'); 	location.reload();	});	
			}else if(data.respuesta.respuesta==2)
			{
				Swal.fire('XML devuelto','','error');	
			}
			else if(data.respuesta.respuesta==4)
			{
				Swal.fire('SRI intermitente intente mas tarde','','info');	
			}else
			{
				Swal.fire(data.text,'','error');	
			}
			
		}
	});

  }

  function calcular_pago()
  {

  	var cotizacion = parseInt($('#TextCotiza').val());
  	var efectivo = parseFloat($('#TxtEfectivo').val());
  	var Total_Factura = parseFloat($('#LabelTotalME').val());
  	var Total_Factura2 = parseFloat($('#LabelTotal').val());
  	var Total_banco = parseFloat($('#TextCheque').val());
  	if(cotizacion > 0){
     if(parseFloat(efectivo) > 0) {var ca = efectivo-Total_Factura+Total_banco; $('#LblCambio').val(ca.toFixed(2));}
     }else{
     if(efectivo > 0 ||  Total_banco>0){ var ca = efectivo-Total_Factura2+Total_banco; $('#LblCambio').val(ca.toFixed(2)) }
   }

  }

    function numeroFactura(){
    DCLinea = $("#DCLinea").val();
    // console.log(DCLinea);
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
      data: {
        'DCLinea' : DCLinea,
      },       
      dataType:'json', 
      success: function(data)
      {
        datos = data;
        document.querySelector('#LblSerie').innerText = datos.serie;
        $("#TextFacturaNo").val(datos.codigo);
      }
    });
  }

   function catalogoLineas(){
    $('#myModal_espera').modal('show');
    var cursos = $("#DCLinea");
    fechaEmision = $('#MBFecha').val();
    fechaVencimiento = $('#MBFecha').val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision},      
      dataType:'json', 
      success: function(data)             
      {
        if (data) {
          datos = data;
          // Limpiamos el select
          cursos.find('option').remove();
          for (var indice in datos) {
            cursos.append('<option value="' + datos[indice].id +" "+datos[indice].text+ ' ">' + datos[indice].text + '</option>');
          }
        }else{
          console.log("No tiene datos");
        }

    tipo_documento();
        numeroFactura();            
      }
    });
    $('#myModal_espera').modal('hide');
  }
  function validar_bodega()
  {
  	var ddl = $('DCBodega').val();
  	if(ddl=='')
  	{
  		Swal.fire('Ingrese o Seleccione una bodega','','info').then(function(){$('#TextFacturaNo').focus()});
  	}
  }


</script>

  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>   
    </div>
	</div>
			<input type="hidden" name="CodDoc" id="CodDoc" class="form-control input-sm" value="00">	 
			<div class="row">
				<div class="col-sm-2">					
            <input type="hidden" id="Autorizacion">
            <input type="hidden" id="Cta_CxP">
					<select class="form-control input-sm" name="DCLinea" id="DCLinea" tabindex="1" onchange="numeroFactura(); tipo_documento();"></select>

					<b>Fecha</b>
					<input type="date" name="MBFecha" id="MBFecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>">	
				</div>
				<div class="col-sm-4">
					<b>Nombre del cliente</b>
					 <div class="input-group input-group-sm" id="ddl">
			        <select class="form-control input-sm select2" id="DCCliente" name="DCCliente" onchange="select()">
								<option value="">Seleccione Bodega</option>
							</select>	
			          <span class="input-group-btn">	
			               <button type="button" class="btn btn-success btn-md btn-flat" onclick="addCliente()" title="Nuevo cliente"><span class="fa fa-user-plus"></span></button>			               
			          </span>
			     </div>  
					<input type="hidden" name="codigoCliente" id="codigoCliente" class="form-control input-sm">	
					<input type="hidden" name="LblT" id="LblT" class="form-control input-sm">	
				</div>
				<div class="col-sm-2">
					<b>CI/RUC/PAS</b>
					<input type="" name="LblRUC" id="LblRUC" class="form-control input-sm" readonly>	
					<input type="hidden" name="Lblemail" id="Lblemail" class="form-control input-sm">	
					
				</div>
				<div class="col-sm-2">
					<b id="Label1">FACTURA No.</b>			
					<div class="row">
						<div class="col-sm-3" id="LblSerie">
							999999
						</div>
						<div class="col-sm-9">
							<input type="" class="form-control input-sm" id="TextFacturaNo" name="TextFacturaNo" readonly>					
						</div>				
					</div>			
				</div>
				<div class="col-sm-2">
					<b>BODEGAS</b>
					<select class="form-control input-sm" id="DCBodega" name="DCBodega" onblur="validar_bodega()">
						<option value="">Seleccione Bodega</option>
					</select>
					
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<b>NOTA</b>
					<input type="text" name="TxtNota" id="TxtNota" class="form-control input-sm">	
				</div>
				<div class="col-sm-4">			
					<b>OBSERVACION</b>
					<input type="text" name="TxtObservacion" id="TxtObservacion" class="form-control input-sm">		
				</div>
				<div class="col-sm-2">			
					<b>COTIZACION</b>
					<input type="text" name="TextCotiza" id="TextCotiza" class="form-control input-sm">				
				</div>
				<div class="col-sm-1">			
					<b>CONVERSION</b>
					<div class="row">
						<div class="col-sm-6" style="padding-right:0px">					
							<label><input type="radio" name="radio_conve" id="OpcMult" value="OpcMult" checked> (X)</label>
						</div>
						<div class="col-sm-6" style="padding-right:0px">				
							<label><input type="radio" name="radio_conve" id="OpcDiv" value="OpcDiv"> (Y)</label>			
						</div>				
					</div>				
				</div>
				<div class="col-sm-1">			
					<b>Gavetas</b>
					<input type="text" name="TxtGavetas" id="TxtGavetas" class="form-control input-sm">				
				</div>
			</div>
			<div class="row">
				<div class="col-sm-9">
					<div class="row box box-success">
						<div class="col-sm-6">
							<b>Producto</b>
							<select class="form-control input-sm" id="DCArticulo" name="DCArticulo" onchange="Articulo_Seleccionado()">
								<option value="">Seleccione Bodega</option>
							</select>					
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>Stock</b>
							<input type="text" name="LabelStock" id="LabelStock" class="form-control input-sm" readonly style="color: red;" value="9999">
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>Cantidad</b>
							<input type="text" name="TextCant" id="TextCant" class="form-control input-sm" value="1" onblur="valida_Stock()">
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>P.V.P</b>
							<input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-sm" value="0.01" onblur="calcular()">						
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>TOTAL</b>
							<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control input-sm" value="0">					
						</div>
						<div class="col-sm-2">
							<b>Detalle</b>
							<input type="text" name="TxtDocumentos" id="TxtDocumentos" class="form-control input-sm" value="." onblur="ingresar()">					
						</div>

					</div>
					<div class="row text-center" >
						<div class="col-sm-12" id="tbl_DGAsientoF">
							
						</div>
						
					</div>
					
				</div>		
				<div class="col-sm-3">
					<div class="row">
						<div class="col-sm-6">
							<b>Total Tarifa 0%</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control input-sm text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>Total Tarifa 12%</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelConIVA"  id="LabelConIVA" class="form-control input-sm text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b id="Label3">I.V.A. 12.00</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelIVA"  id="LabelIVA" class="form-control input-sm text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>Total Factura</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelTotal"  id="LabelTotal" class="form-control input-sm text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>Total Fact (ME)</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelTotalME"  id="LabelTotalME" class="form-control input-sm text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>EFECTIVO</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="TxtEfectivo" id="TxtEfectivo"  class="form-control input-sm text-right" value="0.00" onblur="calcular_pago()" onkeyup="calcular_pago()">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<b>CUENTA DEL BANCO</b>
							<select class="form-control input-sm select2" id="DCBanco" name="DCBanco">
								<option value="">Seleccione Banco</option>
							</select>					
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-3">
							<b>Documento</b>
						</div>
						<div class="col-sm-9">
							<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control input-sm">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<b>NOMBRE DEL BANCO</b>	
							<input type="text" name="TextBanco" id="TextBanco" class="form-control input-sm">						
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>VALOR BANCO</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="TextCheque" id="TextCheque" class="form-control input-sm text-right" value="0.00" onblur="calcular_pago()">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>CAMBIO</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LblCambio" id="LblCambio" class="form-control input-sm text-right" style="color: red;" value="0.00">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12"><br>
							 <button class="btn btn-default btn-block" id="btn_g"> <img src="../../img/png/grabar.png"  onclick="generar()"><br> Guardar</button>
						</div>				
					</div>
					
				</div>
			</div>
	


<div id="myModal_boletos" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ingrese el rango de boletos</h4>
      </div>
      <div class="modal-body">
      	<b>Desde:</b>
      	<input type="text" name="TxtRifaD" id="TxtRifaD" class="form-control input-sm" value="0">
      	<b>Hasta:</b>
      	<input type="text" name="TxtRifaH" id="TxtRifaH" class="form-control input-sm" value="0">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
