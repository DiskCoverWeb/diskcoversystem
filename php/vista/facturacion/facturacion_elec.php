<?php date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();
// print_r($_SESSION['INGRESO']);die();
$TC = 'FA'; if(isset($_GET['tipo'])){$TC = $_GET['tipo'];}
$operadora = $_SESSION['INGRESO']['RUC_Operadora'];
if($operadora!='.' && strlen($operadora)>=13)
{
	$operadora = $_SESSION['INGRESO']['RUC_Operadora'];
}
?>
<script type="text/javascript">
	eliminar_linea('','');
  $(document).ready(function () {

  	var operadora = '<?php echo $operadora; ?>';
  	if(operadora!='.')
  	{
  		buscar_cliente(operadora);
  		$('#btn_nuevo_cli').css('display','none');
  		$('#DCCliente').prop('disabled',true);
  	}
  	ddl_DCTipoPago();
    catalogoLineas();
  	autocomplete_cliente(); 
  	autocomplete_producto();
  	serie();  	
  	// tipo_documento();
  	DCBodega();
  	DGAsientoF();


  	DCBanco();

  	$(document).keyup(function(e) {
	     // if (e.key === "Escape") { // escape key maps to keycode `27`
	     //   ingresar_total();
	     // }
	     console.log(e.key);
	});


  function buscar_cliente(ruc)
  {   

  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?DCCliente_exacto=true&q='+ruc,
		// data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			datos = data[0].data[0];
			// console.log(datos);
			$('#Lblemail').val(datos.Email);
		  $('#LblRUC').val(datos.CI_RUC);
		  $('#codigoCliente').val(datos.Codigo);
		  $('#LblT').val(datos.T);
		  // $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
		  $('#DCCliente').append($('<option>',{value:datos.Codigo, text:datos.Cliente,selected: true }));
			 // console.log(data);
		}
	});


   // $.ajax({
   //      url: '../controlador/facturacion/punto_ventaC.php?DCCliente=true&q='+ruc,
   //      dataType: 'json',
   //      delay: 250,
   //      processResults: function (data) {
   //        return {
   //          results: data
   //        };
   //      },
   //      cache: true
   //    })
  }




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
  	if(tc=='')
  	{
  		return false;
  	}
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
			console.log(data);
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

			  	$('#TxtDetalle').val(data.datos.Producto);
					// $('#').val(data.datos.);


			  $('#cambiar_nombre').on('shown.bs.modal', function () {
					    $('#TxtDetalle').focus();
					})

			   $('#cambiar_nombre').modal('show', function () {
    					$('#TxtDetalle').focus();
					})

				}

			}
		}
	});

  }

  function calcular()
  {
  	 var VUnit = $('#TextVUnit').val();
  	 if(VUnit=='')
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
  	var pro = $('#DCArticulo').val();
  	if(pro=='')
  	{
  		Swal.fire('Seleccione un producto valido','','info');
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
  		'Producto':$('#DCArticulo :selected').text(),
  		'fecha':$('#MBFecha').val(),
  		'CodBod':$('#DCBodega').val(),
  		'VTotal':$('#LabelVTotal').val(),
  		'TxtRifaD':$('#TxtRifaD').val(),
  		'TxtRifaH':$('#TxtRifaH').val(),
  		'Serie':$('#LblSerie').text(),
  		'CodigoCliente':$('#codigoCliente').val(),
  		'electronico':1,
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
				$('#DCArticulo').empty();
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
					Calculos_Totales_Factura();
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
			$('#LabelSubTotal').val(parseFloat(data.Sin_IVA).toFixed(2));
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
         title: 'Esta Seguro que desea grabar: \n Recibo  No. '+$('#TextFacturaNo').val(),
         text:'',
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
  		'electronico':1,
  		'tipo_pago':$('#DCTipoPago').val(),
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/punto_ventaC.php?generar_factura_elec=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			$('#myModal_espera').modal('hide');
			// console.log(data);
			if(data.respuesta==1)
			{	
				Swal.fire({
					type:'success',
				  title: 'Factura Procesada y Autorizada',
				  confirmButtonText: 'Ok!',
				  allowOutsideClick: false,
				}).then(function(){
					var url=  '../../TEMP/'+data.pdf+'.pdf';
					window.open(url, '_blank'); 
					location.reload();		

				})
			}else if(data.respuesta==5)
			{	
				Swal.fire({
					type:'success',
				  title: 'Factura Procesada y Autorizada',
				  text:'No se pudo enviar por email Revise sus credenciales smtp o el correo del cliente',
				  confirmButtonText: 'Ok!',
				  allowOutsideClick: false,
				}).then(function(){
					var url=  '../../TEMP/'+data.pdf+'.pdf';
					window.open(url, '_blank'); 
					location.reload();		

				})
			}
			else if(data.respuesta==-1)
			{

				if(data.text==2 || data.text==null)
				{

				Swal.fire('XML devuleto','XML DEVUELTO','error').then(function(){ 
					var url=  '../../TEMP/'+data.pdf+'.pdf';		window.open(url, '_blank'); 						
				});	
					tipo_error_sri(data.clave);
				}else
				{

					Swal.fire(data.text,'XML DEVUELTO','error').then(function(){ 
						var url=  '../../TEMP/'+data.pdf+'.pdf';		window.open(url, '_blank'); 						
					});	
				}
			}else if(data.respuesta==2)
			{
				tipo_error_comprobante(clave)
				Swal.fire('XML devuelto','','error');	
				tipo_error_sri(data.clave);
			}
			else if(data.respuesta==4)
			{
				Swal.fire('SRI intermitente intente mas tarde','','info');	
			}else
			{
				Swal.fire('XML devuelto por:'+data.text,'','error');	
			}
			
		}
	});

  }


  function tipo_error_sri(clave)
  {
  	var parametros = 
  	{
  		'clave':clave,
  	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/punto_ventaC.php?error_sri=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	
         console.log(data);
        $('#myModal_sri_error').modal('show');
        $('#sri_estado').text(data.estado[0]);
				$('#sri_codigo').text(data.codigo[0]);
				$('#sri_fecha').text(data.fecha[0]);
				$('#sri_mensaje').text(data.mensaje[0]);
				$('#sri_adicional').text(data.adicional[0]);
				// $('#doc_xml').attr('href','')
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
        if (data.length>0) {
          datos = data;
          // Limpiamos el select
          cursos.find('option').remove();
          for (var indice in datos) {
            cursos.append('<option value="' + datos[indice].id +" "+datos[indice].text+ ' ">' + datos[indice].text + '</option>');
          }
        }else{
        	Swal.fire({
					  type:'info',
					  title: 'Usted no tiene un punto de venta asignado, contacte con la administracion del sistema',
					  text:'',
					  allowOutsideClick: false,
					}).then(()=>{
						console.log('ingresa');
								location.href = '../vista/modulos.php';
							});

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


  function cerrar_modal_cambio_nombre()
  {
     	 var nuevo = $('#TxtDetalle').val();
	     var dcart = $('#DCArticulo').val();
	     $('#DCArticulo').append($('<option>',{value:dcart, text:nuevo,selected: true }));
	     $('#LabelStock').focus();   

     	$('#cambiar_nombre').modal('hide');
    
  }

  function mostara_observacion()
  {
  	 var op = $('#rbl_obs').prop('checked');
  	 $('#modal_obs').modal('show');
  }

  function ddl_DCTipoPago() {
 	var opcion = '<option value="">Seleccione tipo de pago</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?DCTipoPago=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Codigo+'">'+item.CTipoPago+'</option>';
        	})
        	$('#DCTipoPago').html(opcion);
          $('#DCTipoPago').val("01");
                    // console.log(response);
      }
    }); 
}



</script>

  <div class="row">
    <div class="col-lg-9 col-sm-10 col-md-8 col-xs-10">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>   
    </div>
    <div class="col-sm-2 col-lg-3 col-md-4 col-xs-2">
        	<?php if($_SESSION['INGRESO']['Ambiente']==1){echo '<h4>Ambiente Pruebas</h4>';}else if($_SESSION['INGRESO']['Ambiente']==2){echo '<h4>Ambiente Produccion</h4>';} ?>
     </div>
	</div>
			<input type="hidden" name="CodDoc" id="CodDoc" class="form-control input-xs" value="00">	 
			<div class="row">
				<div class="col-sm-2">					
            <input type="hidden" id="Autorizacion">
            <input type="hidden" id="Cta_CxP">
            <b>Punto de emision</b>
					<select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" onchange="numeroFactura(); tipo_documento();"><option value=""></option></select>

					<b>Fecha</b>
					<input type="date" name="MBFecha" id="MBFecha" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>">	
				</div>
				<div class="col-sm-4">
					<b>Nombre del cliente</b>
					 <div class="input-group" id="ddl" style="width:100%">
			        <select class="form-control" id="DCCliente" name="DCCliente" onchange="select()">
								<option value="">Seleccione Bodega</option>
							</select>	
							<span class="input-group-btn">
			        <button type="button" class="btn btn-success btn-xs btn-flat" id="btn_nuevo_cli" onclick="addCliente()" title="Nuevo cliente"><span class="fa fa-user-plus"></span></button>		
			        </span>	  

			        <!-- <button onclick="tipo_error_sri('0308202203179238540700120010020000006811234567815')" class="btn">error</button>  -->
			     </div>  
					<input type="hidden" name="codigoCliente" id="codigoCliente" class="form-control input-xs">	
					<input type="hidden" name="LblT" id="LblT" class="form-control input-xs">	
					
            <b>Tipo de pago</b>
            <select class="" style="width: 100%;" id="DCTipoPago" onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');">
              <option value="">Seleccione tipo de pago</option>
            </select> 

				</div>
				<div class="col-sm-2">
					<b>CI/RUC/PAS</b>
					<input type="" name="LblRUC" id="LblRUC" class="form-control input-xs" readonly>	
					<input type="hidden" name="Lblemail" id="Lblemail" class="form-control input-xs">	
					
				</div>
				<div class="col-sm-2">
					<b id="Label1">FACTURA No.</b>			
					<div class="row">
						<div class="col-sm-3" id="LblSerie">
							999999
						</div>
						<div class="col-sm-9">
							<input type="" class="form-control input-xs" id="TextFacturaNo" name="TextFacturaNo" readonly>					
						</div>				
					</div>			
				</div>
				<div class="col-sm-2"  style="display:none">			
					<b>BODEGAS</b>
					<select class="form-control input-xs" id="DCBodega" name="DCBodega" onblur="validar_bodega()">
						<option value="01">Seleccione Bodega</option>
					</select>
					
				</div>
			</div>			
			<div class="row">
				<div class="col-sm-9">
					<div class="row box box-success">
						<div class="col-sm-6">
							<b>Producto</b>
							<select class="form-control input-xs" id="DCArticulo" name="DCArticulo" onchange="Articulo_Seleccionado()">
								<option value="">Seleccione Bodega</option>
							</select>					
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>Stock</b>
							<input type="text" name="LabelStock" id="LabelStock" class="form-control input-xs" readonly style="color: red;" value="999999999">
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>Cantidad</b>
							<input type="text" name="TextCant" id="TextCant" class="form-control input-xs" value="1" onblur="valida_Stock()">
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>P.V.P</b>
							<input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-xs" value="0.01" onblur="calcular()">						
						</div>
						<div class="col-sm-1" style="padding-right:0px">
							<b>TOTAL</b>
							<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control input-xs" value="0">					
						</div>
						<div class="col-sm-2">
							<b>Detalle</b>
							<input type="text" name="TxtDocumentos" id="TxtDocumentos" class="form-control input-xs" value="." onblur="ingresar()">					
						</div>

					</div>
					<div class="row text-center" >
						<div class="col-sm-12" id="tbl_DGAsientoF">
							
						</div>
						
					</div>
					<div class="row">
				<div class="col-sm-6">
					<b>NOTA</b>
					<input type="text" name="TxtNota" id="TxtNota" class="form-control input-xs">	
				</div>
				<div class="col-sm-6">			
					<label><input type="checkbox" name="rbl_obs" id="rbl_obs" onclick="mostara_observacion()"> OBSERVACION</label>
					<input type="text" name="TxtObservacion" id="TxtObservacion" class="form-control input-xs">		
				</div>
				<div class="col-sm-2" style="display:none">			
					<b>COTIZACION</b>
					<input type="text" name="TextCotiza" id="TextCotiza" class="form-control input-xs">				
				</div>
				<div class="col-sm-1"  style="display:none">						
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
				<div class="col-sm-1"  style="display:none">						
					<b>Gavetas</b>
					<input type="text" name="TxtGavetas" id="TxtGavetas" class="form-control input-xs" value="0">				
				</div>
			</div>
					
				</div>		
				<div class="col-sm-3">
					<div class="row">
						<div class="col-sm-6">
							<b>Total Tarifa 0%</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control input-xs text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>Total Tarifa 12%</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelConIVA"  id="LabelConIVA" class="form-control input-xs text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b id="Label3">I.V.A. 12.00</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelIVA"  id="LabelIVA" class="form-control input-xs text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>Total Factura</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelTotal"  id="LabelTotal" class="form-control input-xs text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row" style="display:none;">
						<div class="col-sm-6">
							<b>Total Fact (ME)</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LabelTotalME"  id="LabelTotalME" class="form-control input-xs text-right" value="0.00" style="color:red" readonly>						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>EFECTIVO</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="TxtEfectivo" id="TxtEfectivo"  class="form-control input-xs text-right" value="0.00" onblur="calcular_pago()" onkeyup="calcular_pago()">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<b>CUENTA DEL BANCO</b>
							<select class="form-control input-xs select2" id="DCBanco" name="DCBanco">
								<option value="">Seleccione Banco</option>
							</select>					
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-3">
							<b>Documento</b>
						</div>
						<div class="col-sm-9">
							<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control input-xs">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<b>NOMBRE DEL BANCO</b>	
							<input type="text" name="TextBanco" id="TextBanco" class="form-control input-xs">						
						</div>				
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>VALOR BANCO</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="TextCheque" id="TextCheque" class="form-control input-xs text-right" value="0.00" onblur="calcular_pago()">						
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<b>CAMBIO</b>
						</div>
						<div class="col-sm-6">
							<input type="text" name="LblCambio" id="LblCambio" class="form-control input-xs text-right" style="color: red;" value="0.00">						
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
      	<input type="text" name="TxtRifaD" id="TxtRifaD" class="form-control input-xs" value="0">
      	<b>Hasta:</b>
      	<input type="text" name="TxtRifaH" id="TxtRifaH" class="form-control input-xs" value="0">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cambiar_nombre" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-sm" style="margin-left: 300px; margin-top: 345px;">
      <div class="modal-content">
        <div class="modal-body text-center">
        	<textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle" onblur="cerrar_modal_cambio_nombre()"></textarea> 	
        	 <button style="border:0px"></button>	
        </div>
      </div>
    </div>
  </div>


<div class="modal fade" id="modal_obs" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
   <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Observaciones</h4>
      </div>
      <div class="modal-body">
      	<b>Tonelaje:</b>
      	<input type="text" name="TxtTonelaje" id="TxtTonelaje" class="form-control input-xs" value="0">
      	<b>Año:</b>
      	<input type="text" name="TxtAnio" id="TxtAnio" class="form-control input-xs" value="0">
      	<b>Placas:</b>
      	<input type="text" name="TxtPlacas" id="TxtPlacas" class="form-control input-xs" value="0">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="add_observaciones()">Agregar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	function add_observaciones()
	{
		var to = $('#TxtTonelaje').val();
		var an = $('#TxtAnio').val();
		var pl = $('#TxtPlacas').val();
		$('#modal_obs').modal('hide');
		$('#TxtObservacion').val('Tonelaje='+to+', Año='+an+', Placa='+pl);
	}
</script>



