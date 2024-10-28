<?php date_default_timezone_set('America/Guayaquil'); //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();
$servicio = $_SESSION['INGRESO']['Servicio'];
?>
<script type="text/javascript">
	let FAGlobal = {};
	/*window.closeModal = function () {
		$('#myModal_Abonos').modal('hide');
		Autorizar_Factura_Actual();
	};*/
	let Modificar = false;
	let Bandera = true;
	var PorCodigo = false;
	let producto = ""; //para reserva
	let detalle = ""; //para reserva


	var dataInv = [];//datos del SP.
	$(document).ready(function () {
		var tipo = "<?php echo $_GET['tipo']; ?>";

		//let lienzoElem = ;
		$('#interfaz_facturacion').parent().css('min-height', 'inherit');
		//$('#interfaz_facturacion').parent().css('background-color', 'yellow');

		$('#TipoFactura').val(tipo);
		$('#btnReserva').prop('disabled', true);//Por defecto el btn de reserva no se puede dar clic
		Eliminar_linea('', '');
		// lineas_factura();
		numero_factura();
		DCTipoPago();
		DCMod();
		DCMedico();
		DCGrupo_No();
		DCLineas();
		FPorCodigo();
		CDesc1();
		DCEjecutivo();
		DCBodega();
		DCMarca();
		autocomplete_cliente();
		autocomplete_producto();
		LstOrden();

		DCPorcenIva('MBoxFecha', 'DCPorcenIVA');
		// Lineas_De_CxC();
		$('#DCCliente').on('select2:select', function (e) {
			var data = e.params.data.datos;
			$('#LabelCodigo').val(data.Codigo);
			$('#LabelTelefono').val(data.Telefono);
			$('#LabelRUC').val(data.CI_RUC);
			$('#Label21').val(data.Actividad);
			$('#Label24').val(data.Direccion);
			$('#TxtEmail').val(data.Email);
			$('#LblSaldo').val(parseFloat(data.Saldo_Pendiente).toFixed(2));
			$('#Label13').text('C.I./R.U.C. (' + data.TD + ')');
		});


		$('#cambiar_nombre').on('hide.bs.modal', function () {

			setTimeout(function () { $('#TextComEjec').focus(); }, 500);
			// alert('asda');
		})

		$('#TxtDetalle').keydown(function (e) {
			var keyCode = e.keyCode || e.which;
			if (keyCode == 9) {
				$('#TextComEjec').focus();
			}
		})

		var servicio = '<?php echo $servicio; ?>';
		if (servicio != '0') {
			$('#label36').text(`Servicio ${servicio}%`);
		} else {
			$('#label36').text("Servicio");
		}

		/*let lienzoElem = $('#interfaz_facturacion').parent();

		let tamanoLienzo = lienzoElem[0].parentElement.style.minHeight;
		console.log(tamanoLienzo);*/
		//inicioAbono();
		//inicioAbonoAnticipado();

	});

	document.addEventListener('DOMContentLoaded', () => {
		//inicioAbonoAnticipado();
	})

	function fechaSistema() {
		var fecha = new Date();
		var año = fecha.getFullYear();
		var mes = ('0' + (fecha.getMonth() + 1)).slice(-2); // Sumamos 1 al mes porque en JavaScript los meses van de 0 a 11
		var dia = ('0' + fecha.getDate()).slice(-2);

		// Formatear la fecha como 'YYYY-MM-DD'
		var fechaFormateada = año + '-' + mes + '-' + dia;

		return fechaFormateada;
	}

	function DCTipoPago() {

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCTipoPago=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'DCTipoPago');
			}
		});

	}
	function DCMod() {

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCMod=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					llenarComboList(data, 'DCMod');
				} else {
					$('#DCMod').css('display', 'block');
				}
			}
		});

	}


	function DCMedico() {

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCMedico=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					llenarComboList(data, 'DCMedico');
				} else {
					$('#DCMedico').css('display', 'none');

				}
			}
		});

	}


	function DCGrupo_No() {
		$('#DCGrupo_No').select2({
			placeholder: 'Grupo',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCGrupo_No=true',
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


	function DCLineas() {
		var parametros =
		{
			'Fecha': $('#MBoxFecha').val(),
			'TC': $('#TipoFactura').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCLineas=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'DCLineas');
				$('#Cod_CxC').val(data[0].nombre);  //FA
				//Lineas_De_CxC();
			}
		});
	}

	function FPorCodigo() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?PorCodigo=true',
			// data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				if (data != 0) {
					PorCodigo = true;
				}
			}
		});
	}

	function Lineas_De_CxC(TC, cod_CXC) {
		if (TC != '') {
			TC = $('#TipoFactura').val();

		}
		if (cod_CXC != '') {
			// cod_CXC = $('#DCLineas option:selected').text();
			cod_CXC = $('#Cod_CxC').val();
		}
		var parametros =
		{
			'TC': TC,
			'Fecha': $('#MBoxFecha').val(),
			'Cod_CxC': $('#DCLineas option:selected').text(),
			'Vencimiento': $('#MBoxFechaV').val(),
		}
		//console.log(parametros['Cod_CxC']);

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Lineas_De_CxC=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				$("#TC").val(data.TFA.TC);   //FA
				$("#Autorizacion").val(data.TFA.Autorizacion);   //FA
				$("#CantFact").val(data.TFA.CantFact);   //FA
				$("#Cant_Item").val(data.TFA.Cant_Item_FA);   //FA
				$("#Cod_CxC").val(data.TFA.Cod_CxC);   //FA
				$("#Cta_CxP").val(data.TFA.Cta_CxP);   //FA
				$("#Cta_CxP_Anterior").val(data.TFA.Cta_CxP_Anterior);   //FA
				$("#Cta_Venta").val(data.TFA.Cta_Venta);   //FA
				$("#CxC_Clientes").val(data.TFA.CxC_Clientes);   //FA

				$("#DireccionEstab").val(data.TFA.DireccionEstab);   //FA
				$("#Fecha").val(data.TFA.Fecha);   //FA
				$("#Fecha_Aut").val(data.TFA.Fecha_Aut.date);   //FA
				$("#Fecha_NC").val(data.TFA.Fecha_NC);   //FA
				$("#Imp_Mes").val(data.TFA.Imp_Mes);   //FA


				$("#NoFactura").val(data.TFA.NoFactura);   //FA
				$("#NombreEstab").val(data.TFA.NombreEstab);   //FA
				$("#Porc_IVA").val(data.TFA.Porc_IVA);   //FA
				$("#Porc_Serv").val(data.TFA.Porc_Serv);   //FA
				$("#Pos_Copia").val(data.TFA.Pos_Copia);   //FA
				$("#Pos_Factura").val(data.TFA.Pos_Factura);   //FA
				$("#Serie").val(data.TFA.Serie);   //FA
				$("#TelefonoEstab").val(data.TFA.TelefonoEstab);   //FA
				$("#Vencimiento").val(data.TFA.Vencimiento.date);
				FAGlobal = data.TFA;

				if (data.respuesta == 1) {
					Tipo_De_Facturacion(data.TFA);
					$('#Cant_Item').val(data.TFA.Cant_Item_FA); //FA
				} else if (data.respuesta == 2) {
					Tipo_De_Facturacion(data.TFA);
					$('#Cant_Item').val(data.TFA.Cant_Item_FA); //FA
					swal.fire(data.mensaje, '', 'info');
				} else {
					swal.fire(data.mensaje, '', 'info');
					Tipo_De_Facturacion(data.TFA);
					$('#Cant_Item').val(data.TFA.Cant_Item_FA); //FA
				}
			}
		});
	}

	function Command8_Click() {
		if ($('#DCCiudadI').val() == '' || $('#DCCiudadF').val() == '' || $('#DCRazonSocial').val() == '' || $('#DCEmpresaEntrega').val() == '') {
			swal.fire('Llene todo lso campos', '', 'info');
			return false;
		}
		$('#ClaveAcceso_GR').val('.');
		$('#Autorizacion_GR').val($('#LblAutGuiaRem').val());
		var DCserie = $('#DCSerieGR').val();
		if (DCserie == '') { DCserie = '0_0'; }
		var serie = DCserie.split('_');
		$('#Serie_GR').val(serie[1]);
		$('#Remision').val($('#LblGuiaR').val());
		$('#FechaGRE').val($('#MBoxFechaGRE').val());
		$('#FechaGRI').val($('#MBoxFechaGRI').val());
		$('#FechaGRF').val($('#MBoxFechaGRF').val());
		$('#Placa_Vehiculo').val($('#TxtPlaca').val());
		$('#Lugar_Entrega').val($('#TxtLugarEntrega').val());
		$('#Zona').val($('#TxtZona').val());
		$('#CiudadGRI').val($('#DCCiudadI option:selected').text());
		$('#CiudadGRF').val($('#DCCiudadF option:selected').text());

		var nom = $('#DCRazonSocial').val();
		ci = nom.split('_');
		$('#Comercial').val($('#DCRazonSocial option:selected').text());
		$('#CIRUCComercial').val(ci[0]);
		var nom1 = $('#DCEmpresaEntrega').val();
		ci1 = nom1.split('_');
		$('#Entrega').val($('#DCEmpresaEntrega option:selected').text());
		$('#CIRUCEntrega').val(ci1[0]);
		$('#Dir_EntregaGR').val(ci1[1]);
		sms = "Guia de Remision: " + serie[1] + "-" + $('#LblGuiaR').val() + "  Autorizacion: " + $('#LblAutGuiaRem').val();
		$('#LblGuia').val(sms);
		$('#myModal_guia').modal('hide');

		/*console.log('ClaveAcceso_GR:', '.');
		console.log('Autorizacion_GR:', $('#LblAutGuiaRem').val());
		console.log('Serie_GR:', serie[1]);
		console.log('Remision:', $('#LblGuiaR').val());
		console.log('FechaGRE:', $('#MBoxFechaGRE').val());
		console.log('FechaGRI:', $('#MBoxFechaGRI').val());
		console.log('FechaGRF:', $('#MBoxFechaGRF').val());
		console.log('Placa_Vehiculo:', $('#TxtPlaca').val());
		console.log('Lugar_Entrega:', $('#TxtLugarEntrega').val());
		console.log('Zona:', $('#TxtZona').val());
		console.log('CiudadGRI:', $('#DCCiudadI option:selected').text());
		console.log('CiudadGRF:', $('#DCCiudadF option:selected').text());
		console.log('Comercial:', $('#DCRazonSocial option:selected').text());
		console.log('CIRUCComercial:', ci[0]);
		console.log('Entrega:', $('#DCEmpresaEntrega option:selected').text());
		console.log('CIRUCEntrega:', ci1[0]);
		console.log('Dir_EntregaGR:', ci1[1]);
		console.log('LblGuia:', sms);*/

	}

	function Tipo_De_Facturacion(data) {
		// console.log(data.Autorizacion);
		// console.log(data.Serie);
		// console.log(data.Porc_IVA);
		var TC = data.TC;
		if (TC == "NV") {
			// Facturas.Caption = "INGRESAR NOTA DE VENTA"
			$('#label2').text(data.Autorizacion + " NOTA DE VENTA No. " + data.Serie + "-");
			$('#label3').text("I.V.A. 0.00%");
		} else if (TC == "OP") {
			// Facturas.Caption = "INGRESAR ORDEN DE PEDIDO"
			$('#label2').text(data.Autorizacion + " ORDEN No. " + data.Serie + "-");
			$('#label3').text("I.V.A. 0.00%");
			$('#TextFacturaNo').val(data.NoFactura);
		} else {
			// Facturas.Caption = "INGRESAR FACTURA"
			$('#label2').text(data.Autorizacion + " FACTURA No. " + data.Serie + "-");
			//$('#label3').text("I.V.A. " + (parseFloat(data.Porc_IVA) * 100).toFixed(2) + "%")
			$('#TextFacturaNo').val(data.NoFactura);
		}
		// 'Facturas.Caption = Facturas.Caption & " (" & FA.TC & ")"
		//$('#label36').text("Servicio " + (data.Porc_Serv * 100).toFixed(2) + "%")
	}

	function cambiar_iva(valor) {
		$('#label3').text('I.V.A. ' + parseFloat(valor).toFixed(2) + '%');
	}

	function DCEjecutivo() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCEjecutivo=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					llenarComboList(data, 'DCEjecutivo');
				} else {
					$('#DCEjecutivo').append($('<option>', { value: '.', text: '.', selected: true }));
					$('#DCMedico').css('display', 'none');

				}
			}
		});

	}




	function lineas_factura() {
		let altoContTbl = document.getElementById('interfaz_tabla').clientHeight;
		var parametros =
		{
			'codigoCliente': '',
			'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?lineas_factura=true',
			data: {parametros: parametros},
			dataType: 'json',
			beforeSend: function () { $('#tbl').html('<div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;"><img src="../../img/gif/loader4.1.gif" width="20%"></div> '); },
			success: function (data) {
				$('#tbl').html(data.tbl);
				//$('#tbl').css('height', '100%');
				//$('#tbl tbody').css('height', '100%');
				$('#Mod_PVP').val(data.Mod_PVP);
				if (data.DCEjecutivo == 0) {
					$('#DCEjecutivoFrom').css('display', 'none');
				}
				if (data.TextFacturaNo == 0) {
					$('#TextFacturaNo').attr('readonly', true);
				}

				var servicio = '<?php echo $servicio; ?>';
				var tot_sinIva = data.totales.Sin_IVA;
				var desc = data.totales.Descuento;
				var tot_serv = (tot_sinIva - desc) * (servicio / 100)


				$('#LabelSubTotal').val(parseFloat(data.totales.Sin_IVA).toFixed(2));
				$('#LabelConIVA').val(parseFloat(data.totales.Con_IVA).toFixed(2));
				$('#TextDesc').val(parseFloat(data.totales.Descuento).toFixed(2));
				$('#LabelServ').val(parseFloat(tot_serv).toFixed(2));
				$('#LabelIVA').val(parseFloat(data.totales.Total_IVA).toFixed(2));
				$('#LabelTotal').val(parseFloat(data.totales.Total_MN).toFixed(2));

			}
		});

	}



	function DCBodega() {

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCBodega=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'DCBodega');
			}
		});

	}


	function DCMarca() {

		/*$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCMarca=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'DCMarca');
			}
		});*/

		$('#DCMarca').select2({
			placeholder: 'Seleccione',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCMarca=true',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term,
					}
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});

	}


	function CDesc1() {

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?CDesc1=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'CDesc1');
			}
		});

	}



	function autocomplete_cliente() {
		var grupo = $('#DCGrupo_No').val();
		$('#DCCliente').select2({
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCCliente=true&Grupo=' + grupo,
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

	function autocomplete_producto() {
		/*var marca = $('#DCMarca').val();
		var cod_marca = $('#DCMarca').val();*/
		// console.log(grupo);
		$('#DCArticulos').select2({
			placeholder: 'Producto',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCArticulos=true', //&marca=' + marca + '&codMarca=' + cod_marca
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term,
						marca: $('#DCMarca').val(),
						codMarca: $('#DCMarca').val()
					}
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}

	function LstOrden() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?LstOrden=true',
			// data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {

			}
		});

	}

	function numero_factura() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?numero_factura=true',
			// data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				data.CheqSP == true ? $('#CheqSPFrom').css('visibility', 'visible') : $('#CheqSPFrom').css('visibility', 'hidden');
			}
		});


	}

	function DCArticulo_LostFocus() {
		var parametros = {
			'codigo': $('#DCArticulos').val(),
			'fecha': $('#MBoxFecha').val(),
			'bodega': $('#DCBodega').val(),
			'marca': $('#DCMarca').val(),
			'tipoFactura': $('#TipoFactura').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCArticulo_LostFocus=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				console.log(data);
				$('#TextVUnit').val(data.TextVUnit);
				$('#LabelStock').val(data.labelstock);
				$('#LabelStockArt').html(data.LabelStockArt);
				$('#TextComEjec').val(data.TextComEjec);
				$('#TxtDetalle').val(data.TxtDetalle);
				$('#BanIVA').val(data.baniva);
				producto = data.producto;
				detalle = data.TxtDetalle;
				dataInv = data;
				data.por_reserva ? $('#btnReserva').prop('disabled', false) : $('#btnReserva').prop('disabled', true);
				// $('#DCArticulos').focus();
				// $('#cambiar_nombre').modal('show');

				let inputArticulos = document.getElementById('DCArticulos').getBoundingClientRect();
				let ia_top = inputArticulos.top + 28;
				let ia_left = inputArticulos.left;

				let ia_width = document.querySelector('#DCArticulos + span').getBoundingClientRect().width;

				document.querySelector('#cambiar_nombre div').style = "margin-top:"+ia_top+"px; margin-left:"+ia_left+"px;";
				document.querySelector('#cambiar_nombre div div').style.width = ia_width+"px";

				$('#cambiar_nombre').on('shown.bs.modal', function () {
					$('#TxtDetalle').focus();
				})

				$('#cambiar_nombre').modal('show', function () {
					$('#TxtDetalle').focus();
				})

			}
		})

	}


	function cerrar_modal_cambio_nombre() {

		$('#cambiar_nombre').modal('hide');

		var nuevo = $('#TxtDetalle').val();
		var dcart = $('#DCArticulos').val();
		$('#DCArticulos').append($('<option>', { value: dcart, text: nuevo, selected: true }));
		// $('#TextComEjec').focus();

	}

	function TextCant_Change() {
		var Real1 = 0;
		if ($('#TextCant').val() == "") { $('#TextCant').val(0); }
		if ($('#TextVUnit').val() == "") { $('#TextVUnit').val(0) }

		if ($('#TextCant').val() != 0 && $('#TextVUnit').val() != 0) { var Real1 = $('#TextCant').val() * $('#TextVUnit').val() }
		$('#LabelVTotal').val(Real1.toFixed(2));
	}

	function TextVUnit_LostFocus() {
		if ($('#DCCliente').val() == '') {
			Swal.fire('Seleccione un cliente', '', 'info');
			return false;
		}
		if($('#TextCant').val() == '' || $('#TextCant').val() == 0){
			Swal.fire('Ingrese una cantidad valida', '', 'info');
			return false;
		}
		var parametros = {
			'codigo': $('#DCArticulos').val(),
			'fecha': $('#MBoxFecha').val(),
			'fechaV': $('#MBoxFechaV').val(),
			'fechaVGR': $('#MBoxFechaV').val(), //ojo poner el verdadero
			'TxtDetalle': $('#TxtDetalle').val(),
			'bodega': $('#DCBodega').val(),
			'marca': $('#DCMarca').val(),
			'Cliente': $('#DCCliente').val(),
			'Cant_Item_FA': $('#Cant_Item').val(),
			'tipoFactura': $('#TipoFactura').val(),
			'Mod_PVP': $('#Mod_PVP').val(),
			'DatInv_Serie_No': $('#DatInv_Serie_No').val(),
			'TextVUnit': $('#TextVUnit').val(),
			'TextCant': $('#TextCant').val(),
			'TextFacturaNo': $('#TextFacturaNo').val(),
			'TextComision': $('#TextComision').val(),
			'CDesc1': $('#CDesc1').val(),
			'BanIVA': $('#BanIVA').val(),
			'TextComEjec': $('#TextComEjec').val(),
			'SubCta': '.',
			'Cod_Ejec': $('#DCEjecutivo').val(),
			'CodigoL': $('#DCLineas').val(),
			'MBFechaIn': $('#MBoxFechaV').val(), //ojo poner el verdadero
			'MBFechaOut': $('#MBoxFechaV').val(), //ojo poner el verdadero
			'TxtCantRooms': '.',//$('#MBoxFechaV').val(), //ojo poner el verdadero  	  	
			'TxtTipoRooms': '.',//$('#MBoxFechaV').val(), //ojo poner el verdadero
			'LstOrden': '.',//$('#MBoxFechaV').val(), //ojo poner el verdadero
			'Sec_Public': $('#CheqSP').prop('checked'),
			'PorcIva': $('#DCPorcenIVA').val()
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?TextVUnit_LostFocus=true',
			data: { parametros: parametros },
			dataType: 'json',
			beforeSend: function () { $('#tbl').html('<img src="../../img/gif/loader4.1.gif" width="40%"> '); },
			success: function (data) {
				if (data == 1) {
					lineas_factura();
				} else {
					swal.fire(data, '', 'info');
				}

			}
		})
	}


	function Eliminar_linea(ln_No, Cod) {
		var parametros = {
			'codigo': Cod,
			'ln_No': ln_No,
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Eliminar_linea=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data == 1) {
					lineas_factura();
				}
			}
		})


	}

	function addCliente() {
		$("#myModal_cliente").modal("show");
		var src = "../vista/modales.php?FCliente=true";
		$('#FCliente').attr('src', src).show();
	}

	function DCLinea_LostFocus() {
		Lineas_De_CxC();
	}

	function boton1() {
		var TC = $('#TipoFactura').val();
		var FAC = $('#TextFacturaNo').val();
		if (TC == 'OP') {
			Mensajes = "La Orden de Producción No. " + FAC;
		} else {
			Mensajes = "La Factura No. " + FAC
		}
		Swal.fire({
			title: 'Esta Seguro que desea grabar?',
			text: Mensajes,
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value == true) {
				Grabar_Factura_Actual();
			}
		})
	}

	function Grabar_Factura_Actual() {
		var FA = $("#FA").serialize();
		var parametros = {
			'TextObs': $('#TextObs').val(),
			'TextNota': $('#TextNota').val(),
			'TxtCompra': $('#TxtCompra').val(),
			'TxtPedido': $('#TxtPedido').val(),
			'TxtZona': $('#TxtZona').val(),
			'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
			'TextComision': $('#TextComision').val(),
			'MBoxFechaV': $('#MBoxFechaV').val(),
			'Check1': $('#Check1').prop('checked'),
			'CheqSP': $('#CheqSP').prop('checked'),
			// 'DCTipoPago':$('#DCTipoPago option:selected').text(),
			'DCTipoPago': $('#DCTipoPago').val(),
			'TextFacturaNo': $('#TextFacturaNo').val(),
			'DCMod': $('#DCMod').val(),
			'Reprocesar': $('#Reprocesar').val(),
			'Cliente': $('#DCCliente').val(),
			'Total': $('#LabelTotal').val(),
			'TC': $('#TC').val(),
			'Serie': $('#Serie').val(),
			'Autorizacion': $('#Autorizacion').val(),
			'FA': FAGlobal,
			'Fecha':$('#MBoxFecha').val(),
			'PorcIva':$('#DCPorcenIVA').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Grabar_Factura_Actual=true&' + FA,
			data: { parametros: parametros },
			dataType: 'json',
			beforeSend:function(){$('#myModal_espera').modal('show');},
			success: function (data) {
				$('#myModal_espera').modal('hide');
				if (data.res == -2) {
					alerta_reprocesar('ADVERTENCIA', data.men);
				} else if (data.res == -3) {
					alerta_reprocesar('Formulario de Confirmación', data.men);
				} else if (data.res == 1) {
					Abonos(data.data);
					//Autorizar_Factura_Actual(data.data);
				} else if (data.res == -1) {
					Swal.fire({
						title: 'Algo salió mal',
						text: data.men,
						type: 'error',
						confirmButtonText: 'Ok!',
					})
				}

			}
		})
	}

	function Autorizar_Factura_Actual2() {
		var FA = $("#FA").serialize();
		var parametros = {
			'TextObs': $('#TextObs').val(),
			'TextNota': $('#TextNota').val(),
			'TxtCompra': $('#TxtCompra').val(),
			'TxtPedido': $('#TxtPedido').val(),
			'TxtZona': $('#TxtZona').val(),
			'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
			'TextComision': $('#TextComision').val(),
			'MBoxFechaV': $('#MBoxFechaV').val(),
			'Check1': $('#Check1').prop('checked'),
			'CheqSP': $('#CheqSP').prop('checked'),
			// 'DCTipoPago':$('#DCTipoPago option:selected').text(),
			'DCTipoPago': $('#DCTipoPago').val(),
			'TextFacturaNo': $('#TextFacturaNo').val(),
			'DCMod': $('#DCMod').val(),
			'Reprocesar': $('#Reprocesar').val(),
			'Cliente': $('#DCCliente').val(),
			'Total': $('#LabelTotal').val(),
		}

		// var url=  '../controlador/facturacion/facturarC.php?Autorizar_Factura_Actual=true&'+FA+'&'+parametros.serialize();;
		// window.open(url, '_blank'); 
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Autorizar_Factura_Actual=true&' + FA,
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				var url = '../vista/TEMP/' + data + '.pdf';
				window.open(url, '_blank');
			}
		})
	}


	function Autorizar_Factura_Actual(FAc) {
		$('#myModal_espera').modal('show');
		var FA = $("#FA").serialize();
		var parametros = {
			'TextObs': $('#TextObs').val(),
			'TextNota': $('#TextNota').val(),
			'TxtCompra': $('#TxtCompra').val(),
			'TxtPedido': $('#TxtPedido').val(),
			'TxtZona': $('#TxtZona').val(),
			'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
			'TextComision': $('#TextComision').val(),
			'MBoxFechaV': $('#MBoxFechaV').val(),
			'Check1': $('#Check1').prop('checked'),
			'CheqSP': $('#CheqSP').prop('checked'),
			// 'DCTipoPago':$('#DCTipoPago option:selected').text(),
			'DCTipoPago': $('#DCTipoPago').val(),
			'TextFacturaNo': $('#TextFacturaNo').val(),
			'DCMod': $('#DCMod').val(),
			'Reprocesar': $('#Reprocesar').val(),
			'Cliente': $('#DCCliente').val(),
			'Total': $('#LabelTotal').val(),
			'FA': FAc,
			'PorcIva': $('#DCPorcenIVA').val()
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Autorizar_Factura_Actual=true&' + FA,
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {

				$('#myModal_espera').modal('hide');
				if (data.AU == 1) {
					/*var url = '../../TEMP/' + data.pdf + '.pdf';
					window.open(url, '_blank');*/
					Swal.fire('Factura Creada y Autorizada', '', 'success')
					.then((result)=>{
						var url = '../../TEMP/' + data.pdf + '.pdf';
						window.open(url, '_blank');
					});
					Eliminar_linea('', '');
				} else {
					Swal.fire('Factura creada pero no autorizada', '' , 'warning')
					.then((result) => {
						var url = '../../TEMP/' + data.pdf + '.pdf';
						window.open(url, '_blank');
					});
					/*var url = '../../TEMP/' + data.pdf + '.pdf';
					window.open(url, '_blank');*/
					Eliminar_linea('', '');
				}
			}
		})
	}


	function Abonos(FA) {
		/*Swal.fire({
			title: 'PAGO AL CONTADO',
			text: '',
			type: 'warning',
			confirmButtonText: 'Sí!',
			showCancelButton: true,
			allowOutsideClick: false,
			cancelButtonText: 'No!'
		}).then((result) => {
			if (result.value == true) {
				if (FA['TC'] == "OP") {
					Swal.fire({
						title: 'Formulario de Grabación',
						text: 'Anticipo de Abono',
						type: 'info',
						confirmButtonText: 'Sí!',
						showCancelButton: true,
						allowOutsideClick: false,
						cancelButtonText: 'No!'
					}).then((result) => {
						if (result.value == true) {
							var grupo = $('#DCGrupo_No').val();
							var faFactura = $('#TextFacturaNo').val();
							src = "../vista/modales.php?FAbonoAnticipado=true&tipo=FA&grupo=" + grupo + "&faFactura=" + faFactura;
							$('#frame_anticipado').attr('src', src).show();
							$('#my_modal_abono_anticipado').modal('show').on('hidden.bs.modal', function () {
								Autorizar_Factura_Actual(FA);
							})
						}
					})
				} else {
					Swal.fire({
						title: 'Formulario de Grabación',
						text: 'Pago al Contado',
						type: 'info',
						confirmButtonText: 'Sí!',
						showCancelButton: true,
						allowOutsideClick: false,
						cancelButtonText: 'No!'
					}).then((result) => {
						if (result.value == true) {
							src = "../vista/modales.php?FAbonos=true";
							$('#frame').attr('src', src).show();
							$('#my_modal_abonos').modal('show').on('hidden.bs.modal', function () {
								Autorizar_Factura_Actual(FA);
							})
						}
					})
				}
			} else {
				Autorizar_Factura_Actual(FA);
			}

		})*/
		if (FA['TC'] == "OP") {
			Swal.fire({
				title: 'Formulario de Grabación',
				text: 'Anticipo de Abono',
				type: 'info',
				confirmButtonText: 'Sí!',
				showCancelButton: true,
				allowOutsideClick: false,
				cancelButtonText: 'No!'
			}).then((result) => {
				if (result.value == true) {
					/*var grupo = $('#DCGrupo_No').val();
					var faFactura = $('#TextFacturaNo').val();
					src = "../vista/modales.php?FAbonoAnticipado=true&tipo=FA&grupo=" + grupo + "&faFactura=" + faFactura;
					$('#frame_anticipado').attr('src', src).show();*/
					inicioAbonoAnticipado();
					$('#my_modal_abono_anticipado').modal('show').on('hidden.bs.modal', function () {
						Autorizar_Factura_Actual(FA);
					})
				}else{
					Autorizar_Factura_Actual(FA);
				}
			})
		} else {
			Swal.fire({
				title: 'Formulario de Grabación',
				text: 'Pago al Contado',
				type: 'info',
				confirmButtonText: 'Sí!',
				showCancelButton: true,
				allowOutsideClick: false,
				cancelButtonText: 'No!'
			}).then((result) => {
				if (result.value == true) {
					/*src = "../vista/modales.php?FAbonos=true";
					$('#frame').attr('src', src).show();*/
					inicioAbono();
					$('#my_modal_abonos').modal('show').on('hidden.bs.modal', function () {
						Autorizar_Factura_Actual(FA);
					})
				}else{
					Autorizar_Factura_Actual(FA);
				}
			})
		}
	}



	function alerta_reprocesar(tit, mensaje) {
		Swal.fire({
			title: tit,
			text: mensaje,
			type: 'warning',
			confirmButtonText: 'Sí!',
			showCancelButton: true,
			allowOutsideClick: false,
			cancelButtonText: 'No!'
		}).then((result) => {
			if (result.value == true) {
				$('#Reprocesar').val(1)
				Grabar_Factura_Actual();
			} else {
				$('#Reprocesar').val(0)
			}
		})

	}

	function alerta_abonos(tit, mensaje) {
		Swal.fire({
			title: tit,
			text: mensaje,
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value == true) {
				$('#Reprocesar').val(1)
				Grabar_Factura_Actual();
			} else {
				$('#Reprocesar').val(0)
			}
		})

	}



	function Grabar_Abonos() {
		var FA = $("#FA").serialize();
		var parametros = {
			'TextObs': $('#TextObs').val(),
			'TextNota': $('#TextNota').val(),
			'TxtCompra': $('#TxtCompra').val(),
			'TxtPedido': $('#TxtPedido').val(),
			'TxtZona': $('#TxtZona').val(),
			'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
			'TextComision': $('#TextComision').val(),
			'MBoxFechaV': $('#MBoxFechaV').val(),
			'Check1': $('#Check1').prop('checked'),
			'CheqSP': $('#CheqSP').prop('checked'),
			// 'DCTipoPago':$('#DCTipoPago option:selected').text(),
			'DCTipoPago': $('#DCTipoPago').val(),
			'TextFacturaNo': $('#TextFacturaNo').val(),
			'DCMod': $('#DCMod').val(),
			'Reprocesar': $('#Reprocesar').val(),
			'Cliente': $('#DCCliente').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Grabar_Factura_Actual=true&' + FA,
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if (data.res == -2) {
					alerta_reprocesar('ADVERTENCIA', data.men);
				} else if (data.res == -3) {
					alerta_reprocesar('Formulario de Confirmación', data.men);
				}

			}
		})
	}

	function boton2() {
		DCBodega();
		DCMarca();
		autocomplete_producto();

	}
	function boton3() {
		Listar_Ordenes();
	}
	function boton4() {
		$('#myModal_guia').modal('show');
		DCCiudadI();
		DCCiudadF();
		AdoPersonas();
		DCEmpresaEntrega();

	}
	function boton5() {
		$('#myModal_suscripcion').modal('show');
		$('#LblClienteCod').val($('#LabelCodigo').val());
		$('#form_suscripcion #LblCliente').val($('#DCCliente option:selected').text());
		delete_asientoP();
		DGSuscripcion();
		DCCtaVenta();
		DCEjecutivoModal();

	}
	function boton6() {
		$('#myModal_reserva').modal('show');




		// src ="../vista/modales.php?FAbonos=true";
		// $('#frame').attr('src',src).show();
		// $('#myModal_Abonos').modal('show');

		// $.ajax({
		// 		  type: "POST",
		// 		  url: '../controlador/facturacion/facturarC.php?imprimir=true',
		// 		  // data: {parametros:parametros }, 
		// 		  dataType:'json',
		// 		  success: function(data)
		// 		  {
		// 		  	if(data.length>0)
		// 		  	{

		// 		  		//llena un alista
		// 		  	}else
		// 		  	{
		// 		  		Swal.fire('No existe Ordenes para procesar','','info');
		// 		  	}

		// 		  }
		// 		})



	}
	//---------------Listar_Ordenes()------------
	function Listar_Ordenes() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Listar_Ordenes=true',
			// data: {parametros:parametros }, 
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					$('#myModal_ordenesProd').modal('show');
					// var ordenTableBody = document.getElementById("ordenTableBody");
					// ordenTableBody.innerHTML = "";

					var selectOrden = document.getElementById("selectOrden");
					//var dataTest = ["Orden1", "Orden2", "Orden3"];
					data.forEach(function (orden) {
						var option = document.createElement("option");
						option.value = orden;
						option.text = orden;
						selectOrden.appendChild(option);
					});

					// for (var i = 0; i < data.length; i++) {
					// 	var row = ordenTableBody.insertRow();
					// 	var cell = row.insertCell(0);

					// 	cell.innerHTML = data[i][0]; // "Orden No. XXXXXXXXX - Nombre del Cliente"						
					// }

				} else {
					Swal.fire('No existen órdenes para procesar', '', 'info');
				}
			}
		})
	}
	//---------------fin Listar_Ordenes()--------

	function CommandButton1_Click() {
		$('#myModal_ordenesProd').modal('hide');
		$('#dialog_impresion').modal('show');
	}

	function aceptarimprimir() {
		var ordenNoString = document.getElementById("valOrden").value;
		var ordenNo = parseFloat(ordenNoString);
		var option = "";
		var LstCliente = document.getElementById("DCCliente");
		var selectedOptions = LstCliente.selectedOptions;
		for (var i = 0; i < selectedOptions.length; i++) {
			option = selectedOptions[i].text;
		}
		var parametros = {
			OrdenNo: ordenNo,
			Option: option,
		};
		console.log("facturar " + parametros['Option']);
		console.log(parametros['OrdenNo'])

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Detalle_impresion=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data.status == 200) {
					console.log("facturarC " + data.mensajeEncabData)
					generarPDF(data.mensajeEncabData, data.datos)
				} else {
					console.log("facturarC " + data.dccliente + "" + data.ordenno)
					$('#dialog_impresion').modal('hide');
					swal.fire("No se pudo generar el pdf", '', 'info');
					//generarPDF("no hay datos que mostrar",[])
				}
			}
		});
	}

	function generarPDF(titulo, datos) {
		var url = '../controlador/facturacion/facturarC.php?generar_detalle=true&titulo=' + titulo;

		var datosJSON = JSON.stringify(datos);
		var datosCodificados = encodeURIComponent(datosJSON);

		url += '&datos=' + datosCodificados;
		window.open(url, '_blank');
	}

	//------------------ guia-------------
	function DCCiudadI() {
		$('#DCCiudadI').select2({
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCCiudadI=true',
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
	function DCCiudadF() {
		$('#DCCiudadF').select2({
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCCiudadF=true',
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

	function AdoPersonas() {
		$('#DCRazonSocial').select2({
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?AdoPersonas=true',
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
	function DCEmpresaEntrega() {
		$('#DCEmpresaEntrega').select2({
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCEmpresaEntrega=true',
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
	function MBoxFechaGRE_LostFocus() {
		var parametros = {
			'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?MBoxFechaGRE_LostFocus=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					llenarComboList(data, 'DCSerieGR');
				}

			}
		})
	}

	function DCSerieGR_LostFocus() {
		var DCserie = $('#DCSerieGR').val();
		serie = DCserie.split('_');
		var parametros = {
			'DCSerieGR': serie[1],
			'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCSerieGR_LostFocus=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				//console.log(data);
				/*if (data.length > 0) {
					$('#LblGuiaR').val(data.Guia);
					$('#LblAutGuiaRem').val(data.Auto);
				}*/
				if (data) {
					$('#LblGuiaR').val(data.Guia);
					$('#LblAutGuiaRem').val(data.Auto);
				}
			}
		})

	}




	//---------------------fin de guia-------------
	//--------------sucripcion--------------
	function DGSuscripcion() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DGSuscripcion=true',
			// data: {parametros:parametros }, 
			dataType: 'json',
			beforeSend: function () { $('#tbl_suscripcion').html('<img src="../../img/gif/loader4.1.gif" width="40%"> '); },
			success: function (data) {
				$('#tbl_suscripcion').html(data);
				// console.log(data);
			}
		})
	}

	function DCCtaVenta() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCCtaVenta=true',
			// data: {parametros:parametros }, 
			dataType: 'json',
			success: function (data) {
				// console.log(data);
				llenarComboList(data, 'DCCtaVenta');
			}
		})
	}

	function DCEjecutivoModal() {
		$('#DCEjecutivoModal').select2({
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturarC.php?DCEjecutivoModal=true',
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

	function TextComision_LostFocus() {
		var datos = $('#form_suscripcion').serialize();
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?TextComision_LostFocus=true',
			data: datos,
			dataType: 'json',
			success: function (data) {
				$('#txtperiodo').val(data);
				DGSuscripcion();
				// console.log(data);
			}
		})
	}

	function Command1() {
		var datos = $('#form_suscripcion').serialize();
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Command1=true',
			data: datos,
			dataType: 'json',
			success: function (data) {
				$('#myModal_suscripcion').modal('hide');
				// delete_asientoP();
			}
		})
	}
	function delete_asientoP() {
		$('#TextContrato').val('.');
		$('#TextSector').val('.');
		$('#TxtHasta').val('0.00');
		$('#TextTipo').val('.');
		$('#TextFact').val('0.00');
		$('#TextValor').val('0.00');

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?delete_asientoP=true',
			// data: datos, 
			dataType: 'json',
			success: function (data) {
				// DGSuscripcion();
				// console.log(data);
			}
		})
	}
	function cerrarModal() {
		$('#my_modal_abonos').modal('hide');
	}
	//------------------fin de suscripcion------------------

	//------------------abonos------------------

	function inicioAbono(){
		DCVendedor();
		DiarioCaja();
		DCBanco();
		DCTarjeta();
		DCRetFuente();
		DCRetISer();
		DCRetIBienes();
		DCCodRet();
		DCTipo();
		$('#form_abonos #DCTipo').on('change', DCSerie);
	}

	function DCVendedor() {
		$('#DCVendedor').select2({
			placeholder: 'Vendedor',
			width: 'resolve',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCVendedor=true',
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

	function DCBanco() {
		$('#form_abonos #DCBanco').select2({
			placeholder: 'Cuenta Banco',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCBanco=true',
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
	function DCTarjeta() {
		$('#DCTarjeta').select2({
			placeholder: 'Cuenta Banco',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCTarjeta=true',
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
	function DCRetFuente() {
		$('#DCRetFuente').select2({
			placeholder: 'Cuenta Banco',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCRetFuente=true',
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
	function DCRetISer() {
		$('#DCRetISer').select2({
			placeholder: 'Cuenta Banco',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCRetISer=true',
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
	function DCRetIBienes() {
		$('#DCRetIBienes').select2({
			placeholder: 'Cuenta Banco',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCRetIBienes=true',
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
	function DCCodRet() {
		var MBFecha = $('#form_abonos #MBFecha').val();
		$('#DCCodRet').select2({
			placeholder: 'Cuenta Banco',
			ajax: {
				url: '../controlador/contabilidad/FAbonosC.php?DCCodRet=true&MBFecha=' + MBFecha,
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

		$('#DCCodRet').on('select2:select', function (e) {
			var data = e.params.data.datos;
			$('#TextPorc').val(data.Porcentaje);
		});
	}
	function DCTipo() {
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?DCTipo=true',
			// data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'form_abonos #DCTipo');
				DCSerie();
			}
		});

	}

	function DCSerie() {
		var parametros =
		{
			'tipo': $('#form_abonos #DCTipo').val(),
			'serie': $('#DCLineas').val().slice(-6),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?DCSerie=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if ($('#form_abonos #DCTipo').val() == 'PV') {
					$('#Label2').text('Punto de Venta No.');
				} else if ($('#form_abonos #DCTipo').val() == 'NV') {
					$('#Label2').text('Nota de Venta No.');
				} else {
					$('#Label2').text('Factura No.');
				}
				llenarComboList(data, 'DCSerie');
				DCFactura_();
			}
		});

	}

	function DCFactura_() {
		var parametros =
		{
			'tipo': $('#form_abonos #DCTipo').val(),
			'serie': $('#DCSerie').val(),
			'factura': $('#TextFacturaNo').val()
		}
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?DCFactura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'form_abonos #DCFactura');
			}
		});
	}

	function DCFactura1() {
		var parametros =
		{
			'tipo': $('#form_abonos #DCTipo').val(),
			'serie': $('#DCSerie').val(),
			'factura': $('#form_abonos #DCFactura').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?DCFactura1=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				$('#LabelSaldo').val(parseFloat(data[0].Saldo_MN).toFixed(2));
				$('#form_abonos #TextCajaMN').val(parseFloat(data[0].Saldo_MN).toFixed(2));
				$('#form_abonos #LblCliente').val(data[0].Cliente);
				$('#LblGrupo').val(data[0].Grupo);
				$('#LabelDolares').val(parseFloat(data[0].Cotizacion).toFixed(2));
				$('#Cta_Cobrar').val(data[0].Cta_CxP);
				$('#CodigoC').val(data[0].CodigoC);
				$('#CI_RUC').val(data[0].CI_RUC);
				Calculo_Saldo();
				// $('#').val(data[0].);
				FechaCorte = new Date(data[0].Fecha.date.split(' ')[0]);
				const fechaInput = new Date($('#form_abonos #MBFecha').val());
				if (fechaInput < FechaCorte) {
					Swal.fire({
						title: 'Error',
						text: 'No se puede grabar abonos con fecha inferior a la emision de la factura',
						type: 'error',
					});
				}
				var dia = FechaCorte.getDate();
				var mes = FechaCorte.getMonth() + 1;
				var anio = FechaCorte.getFullYear();
				FechaCorte = `${anio}-${mes}-${dia}`;
				$('#LabelAutorizacion').text(`Autorizacion Fecha de Emisión ${FechaCorte}`);
			}
		});
	}

	function DiarioCaja() {
		var parametros =
		{
			'CheqRecibo': $('#form_abonos #CheqRecibo').prop('checked'),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?DiarioCaja=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				$('#form_abonos #TxtRecibo').val(data);
			}
		});
	}


	function DCAutorizacionF() {
		var parametros =
		{
			'tipo': $('#form_abonos #DCTipo').val(),
			'serie': $('#DCSerie').val(),
			'factura': $('#form_abonos #DCFactura').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?DCAutorizacion=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'DCAutorizacion');
			}
		});

	}

	function Calculo_Saldo() {

		var TotalCajaMN = $('#form_abonos #TextCajaMN').val();
		var TotalCajaME = $('#TextCajaME').val();
		var Total_IVA = 0;
		var Total_Bancos = $('#TextCheque').val();
		var Total_Tarjeta = $('#TextTotalBaucher').val();
		var Total_Ret = $('#TextRet').val();
		var Total_RetIVAB = $('#TextRetIVAB').val();
		var Total_RetIVAS = $('#TextRetIVAS').val();
		var Saldo = $('#LabelSaldo').val();
		// console.log(TotalCajaMN);
		// console.log(TotalCajaME);
		// console.log(Total_IVA);
		// console.log(Total_Bancos);
		// console.log(Total_Tarjeta);
		// console.log(Total_Ret);
		// console.log(Total_RetIVAB);
		// console.log(Total_RetIVAS);
		// console.log(Saldo);
		// console.log()


		var TotalAbonos = parseFloat(TotalCajaMN) + parseFloat(TotalCajaME) + parseFloat(Total_Bancos) + parseFloat(Total_Tarjeta) + parseFloat(Total_IVA) + parseFloat(Total_Ret) + parseFloat(Total_RetIVAB) + parseFloat(Total_RetIVAS);
		var SaldoDisp = parseFloat(Saldo) - parseFloat(TotalAbonos);
		$('#form_abonos #LabelPend').val(SaldoDisp.toFixed(2));
		$('#TextRecibido').val(TotalAbonos.toFixed(2));
	}

	function TextInteres() {
		var TextInteres = $('#TextInteres').val();
		if (TextInteres.substring(TextInteres.length, 1) == "%") {
			var Valor = TextInteres.substring(0, TextInteres.length - 1);
			console.log($('#form_abonos #LabelPend').val());
			TextInteres = parseFloat(Valor) * parseFloat(($('#form_abonos #LabelPend').val()) / 100);
			$('#TextInteres').val(TextInteres.toFixed(2));
		} else {
			console.log(TextInteres);
		}
	}

	function TextRecibido() {
		var TotalCajaMN = $('#form_abonos #TextCajaMN').val();
		var TotalCajaME = $('#TextCajaME').val();
		var Total_IVA = 0;
		var Total_Bancos = $('#TextCheque').val();
		var Total_Tarjeta = $('#TextTotalBaucher').val();
		var Total_Ret = $('#TextRet').val();
		var Total_RetIVAB = $('#TextRetIVAB').val();
		var Total_RetIVAS = $('#TextRetIVAS').val();
		var Saldo = $('#LabelSaldo').val();
		var TotalAbonos = parseFloat(TotalCajaMN) + parseFloat(TotalCajaME) + parseFloat(Total_Bancos) + parseFloat(Total_Tarjeta) + parseFloat(Total_IVA) + parseFloat(Total_Ret) + parseFloat(Total_RetIVAB) + parseFloat(Total_RetIVAS);
		var TextInteres = parseFloat($('#TextInteres').val());
		var TextRecibido = TotalAbonos + TextInteres;
		$('#TextRecibido').val(TextRecibido.toFixed(2));
	}

	function guardar_abonos() {

		const fechaInput = new Date($('#form_abonos #MBFecha').val());
		if (fechaInput < FechaCorte) {
			Swal.fire({
				title: 'Error',
				text: 'No se puede grabar abonos con fecha inferior a la emision de la factura',
				type: 'error',
			});
			return;
		
		}

		Swal.fire({
			title: 'Esta Seguro que desea grabar estos pagos.',
			text: '',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value == true) {
				Grabar_abonos();
			}
		})
	}

	function Grabar_abonos() {
		$('#myModal_espera').modal('show');
		var datos = $('#form_abonos').serialize();
		var fac = $('#DCSerie').val() + '-' + $('#form_abonos #DCFactura').val();
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosC.php?Grabar_abonos=true',
			data: datos,
			dataType: 'json',
			success: function (data) {
				$('#myModal_espera').modal('hide');
				Swal.fire('Abono Guardado', '', 'success').then(function () {
					cerrar_modal();
				})
			}
		});

	}

	function cerrar_modal() {
		parent.cerrarModal();
	}

	//------------------fin de abonos------------------

	//--------------abono anticipado--------------

	function inicioAbonoAnticipado(){
		// Espera a que el DOM esté listo
		//document.addEventListener("DOMContentLoaded", function () {

		var txtCajaMN = document.querySelector("#form_abonos_anti #TextCajaMN");
		DCCtaAnt();//Hay que enviar el subCtaGen como parametro.
		DCBancoAnt();
		var url = window.location.href;
		var urlParams = new URLSearchParams(url.split('?')[1]);
		var TipoFactura = urlParams.get('tipo');
		var grupo = $('#DCGrupo_No').val();
		var faFactura = $('#TextFacturaNo').val();


		if (TipoFactura == "OP") {
			document.querySelector("#form_abonos_anti #LabelPend").style.display = 'block';
			document.getElementById("Label10").style.display = 'block';
			document.getElementById("Frame1").style.display = 'block';
			document.getElementById("Frame2").style.display = 'none';
			DCTipoAnt(faFactura);
		} else {
			document.querySelector("#form_abonos_anti #LabelPend").style.display = 'none';
			document.getElementById("Label10").style.display = 'none';
			document.getElementById("Frame1").style.display = 'none';
			document.getElementById("Frame2").style.display = 'block';
			DCClientes(grupo);
		}
		var CheqRecibo = document.querySelector("#form_abonos_anti #CheqRecibo");
		var txtRecibo = document.querySelector("#form_abonos_anti #TxtRecibo");
		ReadSetDataNum("Recibo_No", true, false)
			.then(function (data) {
				// Aquí puedes trabajar con los datos
				if (CheqRecibo.checked) {
					txtRecibo.value = data.toString().padStart(7, '0');
					console.log(txtRecibo.textContent);
				} else {
					txtRecibo.value = "";
				}
			})
			.catch(function (error) {
				// Manejo de errores si la solicitud Ajax falla
				txtRecibo.value = "";
				console.error("Error en la solicitud Ajax", error);
			});




		// Función clic en el botón "Aceptar"
		window.Command1_Click = function () {
			Swal.fire({
				title: 'Formulario de Grabación',
				text: 'Está Seguro que desea grabar Abono.',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Si!'
			}).then((result) => {
				if (result.value == true) {
					Grabar_abonosAnt();
				}
			});
		};




		//});
	}

	// Función para grabar abonos
	function Grabar_abonosAnt() {

		insertAsientoSC();
		let Total = $('#form_abonos_anti #TextCajaMN').val();
		insertarAsiento(0, Total, 0);
		insertarAsiento(0, 0, Total);

		var parametros = {
			'codigo_cliente': $('#DCClientes').val(),
			'sub_cta_gen': $('#DCCtaAnt').val().split(" ")[0]
		};
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Catalogo_CxCxP=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				return data;
			}
		});

		GrabarComprobante();
	}

	function llenarSelect(data, idSelect, dataName) {
		var select = document.querySelector("#form_abonos_anti #" + idSelect);
		if (data.length == 0) {
			select.innerHTML = '';
			var option = document.createElement("option");
			option.text = "No existen datos";
			option.value = "";
			select.appendChild(option);
		} else {
			select.innerHTML = '';
			for (var i = 0; i < data.length; i++) {
				var option = document.createElement("option");
				option.value = data[i][dataName];
				option.text = data[i][dataName];
				select.appendChild(option);
			}
		}
	}

	/*
	Método conectado con el controlador para obtener todos los tipos de DCBanco existentes
	en la base de datos. Si la data retornada contiene 'status' quiere decir que no hay datos
	y se rellena el select con un 'No existen datos'.
	Con el for llenamos el select de todos los datos que hayamos encontrado de la consulta SQL.
	*/
	function DCBancoAnt() {
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCBanco=true',
			// data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarSelect(data, "DCBanco", "NomCuenta")
			}
		});
	}



	function DCCtaAnt() {
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCCtaAnt=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				llenarSelect(data, "DCCtaAnt", "NomCuenta");
			}
		});
	}

	function DCTipoAnt(faFactura) {
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCTipo=true',
			data: { 'fafactura': faFactura },
			dataType: 'json',
			success: function (data) {
				llenarSelect(data, "DCTipo", "TC");
			}
		});
	}

	function DCClientes(grupo) {
		//let strGrupo = grupo!=null ? `&grupo=${grupo}` : '';
		/*$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCClientes=true' + strGrupo,
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				var select = document.getElementById("DCClientes");
				if (data.length == 0) {
					select.innerHTML = '';
					var option = document.createElement("option");
					option.text = "No existen datos";
					option.value = "";
					select.appendChild(option);
				} else {
					select.innerHTML = '';
					for (var i = 0; i < data.length; i++) {
						var option = document.createElement("option");
						option.value = data[i]['Codigo'];
						option.text = data[i]['Cliente'];
						select.appendChild(option);
					}
				}
			}
		});*/

		$('#DCClientes').select2({
			placeholder: 'Seleccione',
			ajax: {
				url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCClientes=true',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term,
						grupo:  grupo!=null ? grupo : ''
					}
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}

	function ReadSetDataNum(SQLs, ParaEmpresa, Incrementar) {
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: "POST",
				url: '../controlador/contabilidad/FAbonosAnticipadoC.php?ReadSetDataNum=true',
				data: {
					'SQLs': SQLs,
					'ParaEmpresa': ParaEmpresa,
					'Incrementar': Incrementar
				},
				dataType: 'json',
				success: function (data) {
					resolve(data); // Resolvemos la promesa con los datos
				},
				error: function (error) {
					reject(error); // Rechazamos la promesa en caso de error
				}
			});
		});
	}


	function cerrar_modal_ant() {
		window.parent.closeModal();
	}

	function Listar_Facturas_Pendientes() {
		//console.log("TIPO FACTURA LOST FOCUS", TipoFactura);
		var url = window.location.href;
		var urlParams = new URLSearchParams(url.split('?')[1]);
		var TipoFactura = urlParams.get('tipo');
		var faFactura = $('#TextFacturaNo').val();
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCFactura=true',
			data: {
				'TipoFactura': TipoFactura,
				'FaFactura': faFactura
			},
			dataType: 'json',
			success: function (data) {
				llenarSelect(data, "DCFactura", "Factura");
			}
		});
	}

	function insertAsientoSC() {
		var parametros = {
			'Fecha_V': $('#form_abonos_anti #MBFecha').val(),
			'CodigoC': $('#DCClientes').val(),
			'NombreC': $('#DCClientes').find("option:selected").text(),
			'SubCtaGen': $('#DCCtaAnt').val(),
			'Total': $('#form_abonos_anti #TextCajaMN').val(),
			'Trans_No': 200
		};

		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Asiento_SC=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				return data;
			}
		});
	}

	function insertarAsiento(Parcial_MEs, Debes, Habers) {
		if (document.getElementById("Frame2").style.display == 'block') {
			var Cta_Aux = $('#form_abonos_anti #DCBanco').val().split(" ")[0];
			if (Cta_Aux.length <= 1)
				Cta_Aux = '0';//Cta_CajaG
		} else {
			Cta_Aux = '0';
		}
		var parametros = {
			'trans_no': 200,
			'CodCta': Cta_Aux,
			'Parcial_MEs': Parcial_MEs,
			'Debes': Debes,
			'Habers': Habers,
			'CodigoCli': $('#DCClientes').val()
		};
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Asiento=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				return data;
			}
		});
	}

	function GrabarComprobante() {
		var url = window.location.href;
		var urlParams = new URLSearchParams(url.split('?')[1]);
		var TipoFactura = urlParams.get('tipo');
		var faFactura = $('#TextFacturaNo').val();
		var grupo = $('#DCGrupo_No').val();
		var parametros = {
			'Fecha': $('#form_abonos_anti #MBFecha').val(),
			'Total': $('#form_abonos_anti #TextCajaMN').val(),
			'TipoFactura': TipoFactura,
			'NombreC': $('#DCClientes').find("option:selected").text(),
			'Factura': faFactura,
			'Grupo': grupo,
			'TxtConcepto': $('#TxtConcepto').val(),
			'CodigoCli': $('#DCClientes').val(),
			'Trans_No': 200
		};
		//console.log(parametros);
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?GrabarComprobante=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				EnviarEmail(data);
			}
		});

	}

	function EnviarEmail(parametros) {
		/*var parametros = {
			'CodigoCli': $('#DCClientes').val(),
		};*/

		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?EnviarEmail=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data.res == 1) {
					Swal.fire({
						title: data.Titulo,
						text: data.Mensaje,
						type: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si!'
					}).then((result) => {
						if (result.value == true) {
							EnviarEmailAccept(data);
						}
					});
				}
			}
		});
	}

	function EnviarEmailAccept(data){
		$.ajax({
			type: "POST",
			url: '../controlador/contabilidad/FAbonosAnticipadoC.php?EnviarEmailAccept=true',
			data: { parametros: data },
			dataType: 'json',
			success: function (data) {
				
			}
		});
	}

	//------------------fin de abono anticipado------------------

</script>

<style>
	*{
		box-sizing: border-box;
	}
	body {
		padding-right: 0px !important;
	}
	@media screen and (max-width: 600px) {
			.table{
				border: 0px;
			}
			.table caption {
				font-size: 14px;
			}
			.table thead{
				display: none;
			}
			.table tr{
				margin-bottom: 8px;
				border-bottom: 4px solid #ddd;
				display: block;
			}
			.table th, .table td{
				font-size: 12px;
			}
			.table td{
				display: block;
				border-bottom: 1px solid #ddd;
				text-align: right;
			}
			.table td:last-child{
				border-bottom: 0px;
			}
			.table td::before{
				content: attr(data-label);
				font-weight: bold;
				text-transform: uppercase;
				float: left;
			}
		}
</style>
<div id="interfaz_facturacion" style="display:flex; flex-direction:column; min-height:inherit;">

	<div class="interfaz_botones">
		<div class="row row-no-gutters">
			<?php
				function createButton($title, $imagePath, $onclickFunction, $id)
				{
					echo '<div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
							<button type="button" class="btn btn-default" id="' . $id . '" title="' . $title . '" onclick="' . $onclickFunction . '">
								<img src="' . $imagePath . '">
							</button>
						</div>';
				}
			?>
			
			<div class="col-lg-10 col-sm-12 col-md-12 col-xs-12">
				

				
				<div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
					<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
					print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
						<img src="../../img/png/salire.png">
					</a>
				</div>

				<?php createButton("Grabar factura", "../../img/png/grabar.png", "boton1()", "btnGrabar"); ?>
				<?php createButton("Actualizar Productos, Marcas y Bodegas", "../../img/png/update.png", "boton2()", "btnActualizar"); ?>
				<?php createButton("Asignar orden de trabajo", "../../img/png/taskboard.png", "boton3()", "btnOrden"); ?>
				<?php createButton("Asignar guía de remisión", "../../img/png/ats.png", "boton4()", "btnGuia"); ?>
				<?php createButton("Asignar suscripción/contrato", "../../img/png/file2.png", "boton5()", "btnSuscripcion"); ?>
				<?php createButton("Asignar reserva", "../../img/png/archivero2.png", "boton6()", "btnReserva"); ?>
				<!-- Example of a commented-out button
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<button type="button" class="btn btn-default" title="Grabar factura" onclick="boton1()"><img
							src="../../img/png/grabar.png"></button>
				</div>
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<button type="button" class="btn btn-default" title="Actualizar Productos, Marcas y Bodegas"
						onclick="boton2()"><img src="../../img/png/update.png"></button>
				</div>
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<button type="button" class="btn btn-default" title="Asignar orden de trabajo" onclick="boton3()"><img
							src="../../img/png/taskboard.png"></button>
				</div>
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<button type="button" class="btn btn-default" title="Asignar guia de remision" onclick="boton4()"><img
							src="../../img/png/ats.png"></button>
				</div>
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<button type="button" class="btn btn-default" title="Asignar suscripcion / contrato" onclick="boton5()"><img
							src="../../img/png/file2.png"></button>
				</div>
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<button id="btnReserva" type="button" class="btn btn-default" title="Asignar reserva"
						onclick="boton6()"><img src="../../img/png/archivero2.png"></button>
				</div>
				<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
					<a href="#" class="btn btn-default" title="Asignar reserva" onclick="Autorizar_Factura_Actual2();" target="_blank" ><img src="../../img/png/archivero2.png"></a>
				</div> -->
			</div>
			<!--<div class="col-lg-3 col-sm-10 col-md-6 col-xs-12">-->
				<?php //createButton("Asignar guía de remisión", "../../img/png/ats.png", "boton4()", "btnGuia",'2','2','1','4'); ?>
				<?php //createButton("Asignar suscripción/contrato", "../../img/png/file2.png", "boton5()", "btnSuscripcion",'2','2','1','4'); ?>
				<?php //createButton("Asignar reserva", "../../img/png/archivero2.png", "boton6()", "btnReserva",'2','2','1','4'); ?>
			<!--</div>-->
			
		</div>
	</div>


	<div class="interfaz_campos">
		<div class="row">
			<div class="col-sm-12">
				<!-- //valiable  -->
				<input type="hidden" name="Mod_PVP" id="Mod_PVP" value="0">
				<input type="hidden" name="DatInv_Serie_No" id="DatInv_Serie_No" value="">
				<input type="hidden" name="BanIVA" id="BanIVA">
				<input type="hidden" name="Reprocesar" id="Reprocesar"=value="0">

				<form id="FA" style="display:none;">

					<input type="text" name="TC" id="TC" value="">
					<input type="text" name="Cant_Item" id="Cant_Item">

					<input type="text" name="Autorizacion" id="Autorizacion">
					<input type="text" name="CantFact" id="CantFact">
					<!-- <input type="text" name="Cant_Item" id="Cant_Item"> -->
					<input type="text" name="Cod_CxC" id="Cod_CxC">
					<input type="text" name="Cta_CxP" id="Cta_CxP">
					<input type="text" name="Cta_CxP_Anterior" id="Cta_CxP_Anterior">
					<input type="text" name="Cta_Venta" id="Cta_Venta">
					<input type="text" name="CxC_Clientes" id="CxC_Clientes">

					<input type="text" name="DireccionEstab" id="DireccionEstab">
					<input type="text" name="Fecha" id="Fecha" value="">
					<input type="text" name="Fecha_Aut" id="Fecha_Aut">
					<input type="text" name="Fecha_NC" id="Fecha_NC">
					<input type="text" name="Imp_Mes" id="Imp_Mes">


					<input type="text" name="NoFactura" id="NoFactura">
					<input type="text" name="NombreEstab" id="NombreEstab">
					<input type="text" name="Porc_IVA" id="Porc_IVA">
					<input type="text" name="Porc_Serv" id="Porc_Serv">
					<input type="text" name="Pos_Copia" id="Pos_Copia">
					<input type="text" name="Pos_Factura" id="Pos_Factura">
					<input type="text" name="Serie" id="Serie">
					<input type="text" name="TelefonoEstab" id="TelefonoEstab">
					<input type="text" name="Vencimiento" id="Vencimiento">

					<!-- guia -->
					<input type="text" name="ClaveAcceso_GR" id="ClaveAcceso_GR" value="">
					<input type="text" name="Autorizacion_GR" id="Autorizacion_GR" value="">
					<input type="text" name="Serie_GR" id="Serie_GR" value="">
					<input type="text" name="Remision" id="Remision" value="">
					<input type="text" name="FechaGRE" id="FechaGRE" value="">
					<input type="text" name="FechaGRI" id="FechaGRI" value="">
					<input type="text" name="FechaGRF" id="FechaGRF" value="">
					<input type="text" name="Placa_Vehiculo" id="Placa_Vehiculo" value="">
					<input type="text" name="Lugar_Entrega" id="Lugar_Entrega" value="">
					<input type="text" name="Zona" id="Zona" value="">
					<input type="text" name="CiudadGRI" id="CiudadGRI" value="">
					<input type="text" name="CiudadGRF" id="CiudadGRF" value="">
					<input type="text" name="Comercial" id="Comercial" value="">
					<input type="text" name="CIRUCComercial" id="CIRUCComercial" value="">
					<input type="text" name="Entrega" id="Entrega" value="">
					<input type="text" name="CIRUCEntrega" id="CIRUCEntrega" value="">
					<input type="text" name="Dir_EntregaGR" id="Dir_EntregaGR" value="">
					<!-- fin guia -->
				</form>


				<!-- //fin de variables -->
				<input type="hidden" name="TipoFactura" id="TipoFactura">
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-2 col-xs-6" style="padding-right:0;">
						<label><input type="checkbox" name="Check1" id="Check1"> Factura en ME</label>
					</div>
					<div class="col-sm-2 col-xs-6" id="CheqSPFrom" style="visibility: hidden;padding:0;">
						<label><input type="checkbox" name="CheqSP" id="CheqSP"> Sector publico</label>
					</div>
					<div class="col-sm-offset-0 col-sm-3 col-xs-offset-1 col-xs-10" style="padding:0;">
						<b class="col-sm-6 col-xs-5 control-label" style="padding: 0px;width:fit-content;">Orden Compra No</b>
						<div class="col-sm-5 col-xs-7" style="padding-right:0;">
							<input type="" name="TxtCompra" id="TxtCompra" class="form-control input-xs text-right" value="0">
						</div>
					</div>
					<div class="col-sm-offset-0 col-sm-4 col-xs-offset-1 col-xs-10" style="padding:0;">
						<select class="form-control input-xs" id="DCMod" name="DCMod">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-offset-0 col-sm-1 col-xs-offset-1 col-xs-10">
						<input type="text" name="LabelCodigo" id="LabelCodigo" class="form-control input-xs" readonly=""
							value=".">
					</div>
				</div>
				<div class="row" style="display:flex;flex-flow:wrap;align-items:center;margin-top:5px;">
					<div class="col-lg-2 col-sm-2 col-xs-6" style="padding-right:0;">
						<b class="col-lg-4 col-sm-12 control-label" style="padding: 0px;width:fit-content;">Emision</b>
						<div class="col-lg-8 col-sm-12" style="padding-right: 0px">
							<input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control input-xs"
								value="<?php echo date('Y-m-d'); ?>" onblur="DCPorcenIva('MBoxFecha', 'DCPorcenIVA');">
						</div>
					</div>
					<div class="col-lg-3 col-sm-2 col-xs-6" style="padding-right:0;">
						<b class="col-lg-4 col-sm-12 control-label" style="padding: 0px;width:fit-content;">Vencimiento</b>
						<div class="col-lg-6 col-sm-12" style="padding-right: 0px">
							<input type="date" name="MBoxFechaV" id="MBoxFechaV" class="form-control input-xs"
								value="<?php echo date('Y-m-d'); ?>">
						</div>
					</div>
					<div class="col-lg-offset-0 col-lg-3 col-sm-offset-0 col-sm-3 col-xs-offset-1 col-xs-10" style="padding:0;">
						<b class="col-lg-5 col-sm-12 col-xs-5 control-label" style="padding: 0px;width:fit-content;">Cuenta x Cobrar</b>
						<div class="col-lg-7 col-sm-12 col-xs-7" style="padding-right: 0px">
							<select class="form-control input-xs" id="DCLineas" name="DCLineas"
								onblur="DCLinea_LostFocus()">
								<option value="">Seleccione</option>
							</select>

							<input type="hidden" name="DCLineasV" id="DCLineasV">
						</div>
					</div>
					<div class="col-lg-4 col-sm-5 col-xs-12">
						<div class="row row-no-gutters" style="display:flex;align-items:center">
							<div class="col-lg-9 col-sm-12 col-xs-7" style="padding-right: 0px;width:fit-content;">
								<b style="color:red" id="label2">0000000000000 NOTA DE VENTA No. 001001-</b>
							</div>
							<div class="col-lg-3 col-sm-12 col-xs-5" style="padding-left: 8px;">
								<input type="text" name="TextFacturaNo" id="TextFacturaNo" class="form-control input-xs"
									value="000000" style="padding: 0 8px;">
							</div>
						</div>
					</div>
					<!--<div class="col-sm-4">
						<b class="col-sm-4 control-label" style="padding: 0px">Saldo pendiente</b>
						<div class="col-sm-6">
							<input type="text" name="LblSaldo" id="LblSaldo" class="form-control input-xs" value="0.00"
								readonly>
						</div>
					</div>-->
				</div>
				<div class="row" style="margin-top:5px;">
					<div class="col-xs-12 col-sm-6 col-lg-7">
						<b class="col-sm-2 col-lg-2 control-label" style="padding: 0px;width:fit-content;">Tipo de pago</b>
						<div class="col-sm-9 col-lg-10">
							<select class="form-control input-xs" id="DCTipoPago" name="DCTipoPago">
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
					<div class="col-xs-4 col-sm-2 col-lg-2" style="padding-right:0;">
						<div class="col-lg-offset-2 col-lg-4 col-sm-4 col-xs-5 text-right" style="padding: 0">
							<label for="DCPorcenIVA">I.V.A:</label>
						</div>
						<div class="col-sm-8 col-lg-6 col-xs-12" style="padding-right:0;padding-left:10px;">
							<select class="form-control input-xs" name="DCPorcenIVA" id="DCPorcenIVA" onblur="cambiar_iva(this.value)"> </select>
						</div>
					</div>
					<div class="col-xs-8 col-sm-4 col-lg-3">
						<b class="col-sm-7 col-lg-7 col-xs-6 control-label text-right" style="padding: 0px">Saldo pendiente</b>
						<div class="col-sm-5 col-lg-5 col-xs-12">
							<input type="text" name="LblSaldo" id="LblSaldo" class="form-control input-xs" value="0.00" style="text-align:right;"
								readonly>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:5px;">
					<!--<div class="col-sm-3">
						<b>Grupo</b>
						<select class="form-control input-xs" id="DCGrupo_No" name="DCGrupo_No"
							onchange="autocomplete_cliente()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-9">
						<b>Cliente</b>
						<div class="input-group">
							<select class="form-control input-xs" id="DCCliente" name="DCCliente">
								<option value="">Seleccione</option>
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat btn-xs" onclick="addCliente();"><i
										class="fa fa-plus"></i></button>
							</span>
						</div>
					</div>-->
					<div class="col-sm-3 col-md-3">
						<b class="col-sm-2 col-md-2 control-label" style="padding: 0px">Grupo</b>
						<div class="col-sm-10 col-md-10">
							<select class="form-control input-xs" id="DCGrupo_No" name="DCGrupo_No"
								onchange="autocomplete_cliente()">
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
					<div class="col-sm-9 col-md-9">
						<b class="col-sm-1 col-md-1 control-label" style="padding: 0px;width:fit-content;">Cliente</b>
						<div class="col-sm-10 col-md-10 input-group" style="padding-left: 8px;">
							<select class="form-control input-xs" id="DCCliente" name="DCCliente">
								<option value="">Seleccione</option>
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat btn-xs" onclick="addCliente();"><i
										class="fa fa-plus"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:5px;display:flex;flex-flow:wrap;align-items:center;">
					<!--<div class="col-sm-8">
						<b>ACTUALICE SU CORREO ELECTRONICO</b>
						<input type="text" name="TxtEmail" id="TxtEmail" class="form-control input-xs">
					</div>
					<div class="col-sm-2">
						<b id="Label13">C.I / R.U.C</b>
						<input type="text" name="LabelRUC" id="LabelRUC" class="form-control input-xs" readonly=""
							value=".">
					</div>
					<div class="col-sm-2">
						<b>Telefono</b>
						<input type="text" name="LabelTelefono" id="LabelTelefono" class="form-control input-xs" readonly=""
							value=".">
					</div>-->
					<div class="col-sm-6 col-xs-12">
						<b class="col-sm-5 control-label" style="padding:0px">ACTUALICE SU CORREO</b>
						<div class="col-sm-7" style="padding:0px">
							<input type="text" name="TxtEmail" id="TxtEmail" class="form-control input-xs">
						</div>
					</div>
					<div class="col-sm-3 col-xs-6">
						<b id="Label13" class="col-sm-5 control-label text-right" style="padding:0px">C.I / R.U.C</b>
						<div class="col-sm-7" style="padding-right:0px">
							<input type="text" name="LabelRUC" id="LabelRUC" class="form-control input-xs" readonly=""
								value=".">
						</div>
					</div>
					<div class="col-sm-3 col-xs-6">
						<b class="col-sm-4 control-label text-right" style="padding:0px">Telefono</b>
						<div class="col-sm-8">
							<input type="text" name="LabelTelefono" id="LabelTelefono" class="form-control input-xs" readonly=""
								value=".">
						</div>
					</div>
					
				</div>
				<!--<div class="row">
					<div class="col-sm-4">
						<b class="col-sm-4 control-label" style="padding: 0px">Cuenta x Cobrar</b>
						<div class="col-sm-8" style="padding: 0px">
							<select class="form-control input-xs" id="DCLineas" name="DCLineas"
								onblur="DCLinea_LostFocus()">
								<option value="">Seleccione</option>
							</select>

							<input type="hidden" name="DCLineasV" id="DCLineasV">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-9" style="padding-right: 0px;">
								<b style="color:red" id="label2">0000000000000 NOTA DE VENTA No. 001001-</b>
							</div>
							<div class="col-sm-3" style="padding-left: 0px;">
								<input type="text" name="TextFacturaNo" id="TextFacturaNo" class="form-control input-xs"
									value="0">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="col-sm-4 control-label" style="padding: 0px">Saldo pendiente</b>
						<div class="col-sm-6">
							<input type="text" name="LblSaldo" id="LblSaldo" class="form-control input-xs" value="0.00"
								readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<b class="col-sm-5 control-label" style="padding: 0px">Fecha Emision</b>
						<div class="col-sm-7" style="padding: 0px">
							<input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control input-xs"
								value="<?php //echo date('Y-m-d'); ?>" onblur="DCPorcenIva('MBoxFecha', 'DCPorcenIVA');">
						</div>
					</div>
					<div class="col-sm-3">
						<b class="col-sm-6 control-label" style="padding: 0px">Fecha Vencimiento</b>
						<div class="col-sm-6" style="padding: 0px">
							<input type="date" name="MBoxFechaV" id="MBoxFechaV" class="form-control input-xs"
								value="<?php //echo date('Y-m-d'); ?>">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="col-sm-4 text-right" style="padding: 0">
							<label for="DCPorcenIVA">I.V.A:</label>
						</div>
						<div class="col-sm-8">
							<select class="form-control input-xs" name="DCPorcenIVA" id="DCPorcenIVA" onblur="cambiar_iva(this.value)"> </select>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="col-sm-3 control-label" style="padding: 0px">Tipo de pago</b>
						<div class="col-sm-8">
							<select class="form-control input-xs" id="DCTipoPago" name="DCTipoPago">
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<b>Grupo</b>
						<select class="form-control input-xs" id="DCGrupo_No" name="DCGrupo_No"
							onchange="autocomplete_cliente()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-3">
						<b>Cliente</b>
						<div class="input-group">
							<select class="form-control input-xs" id="DCCliente" name="DCCliente">
								<option value="">Seleccione</option>
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat btn-xs" onclick="addCliente();"><i
										class="fa fa-plus"></i></button>
							</span>
						</div>
					</div>
					<div class="col-sm-2">
						<b id="Label13">C.I / R.U.C</b>
						<input type="text" name="LabelRUC" id="LabelRUC" class="form-control input-xs" readonly=""
							value=".">
					</div>
					<div class="col-sm-2">
						<b>Telefono</b>
						<input type="text" name="LabelTelefono" id="LabelTelefono" class="form-control input-xs" readonly=""
							value=".">
					</div>
					<div class="col-sm-3">
						<b>ACTUALICE SU CORREO ELECTRONICO</b>
						<input type="text" name="TxtEmail" id="TxtEmail" class="form-control input-xs">
					</div>
				</div>-->
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-6 col-xs-12">
						<b class="col-sm-2 control-label" style="padding:0;">Direccion</b>
						<div class="col-sm-10" style="padding-right:0px">
							<input type="text" name="Label24" id="Label24" class="form-control input-xs" value="" readonly="">
						</div>
					</div>
					<div class="col-sm-2 col-xs-12">
						<b class="col-sm-2 control-label" style="padding:0;">No</b>
						<div class="col-sm-10 col-xs-12" style="padding-right:0px">
							<input type="text" name="Label21" id="Label21" class="form-control input-xs" value="" readonly="">
						</div>
					</div>
					<div class="col-sm-offset-0 col-sm-4 col-xs-offset-1 col-xs-11">
						<select class="form-control input-xs" id="DCMedico" name="DCMedico">
							<option value="">Seleccione</option>
						</select>
					</div>
				</div>
				<!--<div class="row">
					<div class="col-sm-6">
						<b class="col-sm-2 control-label" style="padding:0;">Direccion</b>
						<div class="col-sm-10" style="padding-right:0px">
							<input type="text" name="Label24" id="Label24" class="form-control input-xs" value="QUINTO AÑO DE EDUCACIÓN GENERAL BASICA B">
						</div>
					</div>
					<div class="col-sm-2">
						<b class="col-sm-2 control-label" style="padding:0;">No</b>
						<div class="col-sm-10" style="padding:0px">
							<input type="text" name="Label21" id="Label21" class="form-control input-xs" value="" readonly="">
						</div>
					</div>
					<div class="col-sm-4"> 
						<select class="form-control input-xs" id="DCMedico" name="DCMedico">
							
						<option value="1801096718001-R-BI01096718">POZO AVALOS LUIS FERNANDO</option></select>
					</div>
				</div>-->
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-6">
						<div id="DCEjecutivoFrom">
							<b class="col-sm-4 control-label" style="padding: 0px"><input type="checkbox" name=""> Ejecutivo
								de
								venta</b>
							<div class="col-sm-8" style="padding-right: 0px">
								<select class="form-control input-xs" name="DCEjecutivo" id="DCEjecutivo">
									<option value="">Seleccione</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div id="TextComisionForm" style="display:none;">
							<b class="col-sm-5 control-label" style="padding: 0px">comision%</b>
							<div class="col-sm-7">
								<input type="text" name="TextComision" id="TextComision" value="0"
									class="form-control input-xs" style="text-align: right;">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="col-sm-2 control-label" style="padding: 0px">Bodega</b>
						<div class="col-sm-9">
							<select class="form-control input-xs" name="DCBodega" id="DCBodega">
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
				</div>
				<!--<div class="row">
					<div class="col-sm-6">
						<b>Observacion</b>
						<input type="text" name="TextObs" id="TextObs" class="form-control input-xs">
					</div>
					<div class="col-sm-6">
						<b>Nota</b>
						<input type="text" name="TextNota" id="TextNota" class="form-control input-xs">
					</div>
				</div>-->
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-12">
						<b class="col-sm-1 control-label" style="padding:0;">Observacion</b>
						<div class="col-sm-11" style="padding-right:0px">
							<input type="text" name="TextObs" id="TextObs" class="form-control input-xs">
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-12">
						<b class="col-sm-1 control-label" style="padding:0;">Nota</b>
						<div class="col-sm-11" style="padding-right:0px">
							<input type="text" name="TextNota" id="TextNota" class="form-control input-xs">
						</div>
					</div>
				</div>
				<div class="row box box-success" style="padding-bottom: 7px; margin-left: 0px; margin-bottom:0; margin-top:5px;">
					<div class="col-sm-4 col-lg-2">
						<b>Marca</b>
						<select class="form-control input-xs" id="DCMarca" name="DCMarca">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-8 col-lg-4">
						<b id="LabelStockArt">Producto</b>
						<select class="form-control input-xs" name="DCArticulos" id="DCArticulos"
							onchange="DCArticulo_LostFocus()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Stock</b>
						<input type="text" name="LabelStock" id="LabelStock" class="form-control input-xs" readonly="" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Ord./lote</b>
						<input type="text" name="TextComEjec" id="TextComEjec" class="form-control input-xs" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Desc%</b>
						<select class="form-control input-xs" id="CDesc1" name="CDesc1" style="text-align: right;">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Cantidad</b>
						<input type="text" name="TextCant" id="TextCant" class="form-control input-xs" onblur="" value="0" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>P.V.P</b>
						<input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-xs"
							onblur="TextVUnit_LostFocus(); TextCant_Change();" value="0" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>TOTAL</b>
						<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control input-xs" readonly=""
							value="0" style="text-align: right;">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="interfaz_tabla" id="interfaz_tabla" style="flex-grow:1;padding-top:10px">
		<div id="tbl">

		</div>
		
	</div>

	<div class="interfaz_totales">
		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b style="letter-spacing: -1.0px;">Total sin Iva</b>
					<input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b>Total con IVA</b>
					<input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b>Total Desc</b>
					<input type="text" name="TextDesc" id="TextDesc" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b id="label36"></b>
					<input type="text" name="LabelServ" id="LabelServ" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b id="label3">I.V.A</b>
					<input type="text" name="LabelIVA" id="LabelIVA" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-2" style="padding: 2px">
					<b>Total Facturado</b>
					<input type="text" name="LabelTotal" id="LabelTotal" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-offset-8 col-sm-4 col-lg-offset-0 col-lg-5">
					<!-- <b>P.V.P</b> -->
					<br>
					<input type="text" name="LblGuia" id="LblGuia" class="form-control input-xs" readonly>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cambiar_nombre" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
	<div class="modal-dialog modal-dialog modal-dialog-centered modal-sm"
		style="margin-left: 25%; margin-top: 30%;">
		<div class="modal-content">
			<div class="modal-body text-center">
				<textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle"
					onblur="cerrar_modal_cambio_nombre()"></textarea>
			</div>
		</div>
	</div>
</div>

<!-- Modal cliente nuevo -->
<div id="myModal_guia" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">DATOS DE GUIA DE REMISION</h4>
			</div>
			<div class="modal-body">
				<form id="form_guia">
					<div class="row">
						<div class="col-sm-12">
							<b class="col-sm-6 control-label" style="padding: 0px">Fecha de emisión de guía</b>
							<div class="col-sm-6" style="padding: 0px">
								<input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs"
									value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus()">
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-6 control-label" style="padding: 0px">Guía de remisión No.</b>
							<div class="col-sm-3" style="padding: 0px">
								<select class="form-control input-xs" id="DCSerieGR" name="DCSerieGR"
									onblur="DCSerieGR_LostFocus()">
									<option value="">No Existe</option>
								</select>
							</div>
							<div class="col-sm-3" style="padding: 0px">
								<input type="text" name="LblGuiaR" id="LblGuiaR" class="form-control input-xs"
									value="000000">
							</div>
						</div>
						<div class="col-sm-12">
							<b>AUTORIZACION GUIA DE REMISION</b>
							<input type="text" name="LblAutGuiaRem" id="LblAutGuiaRem" class="form-control input-xs"
								value="0">
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-6 control-label" style="padding: 0px">Iniciación del traslados</b>
							<div class="col-sm-6" style="padding: 0px">
								<input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control input-xs"
									value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-3 control-label" style="padding: 0px">Ciudad</b>
							<div class="col-sm-9" style="padding: 0px">
								<select class="form-control input-xs" style="width:100%" id="DCCiudadI" name="DCCiudadI">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-6 control-label" style="padding: 0px">Finalización del traslados</b>
							<div class="col-sm-6" style="padding: 0px">
								<input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control input-xs"
									value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-3 control-label" style="padding: 0px">Ciudad</b>
							<div class="col-sm-9" style="padding: 0px">
								<select class="form-control input-xs" style="width:100%" id="DCCiudadF" name="DCCiudadF">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b>Nombre o razón social (Transportista)</b>
							<select class="form-control input-xs" style="width:100%" id="DCRazonSocial"
								name="DCRazonSocial">
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b>Empresa de Transporte</b>
							<select class="form-control input-xs" style="width:100%" id="DCEmpresaEntrega"
								name="DCEmpresaEntrega">
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-4">
							<b>Placa</b>
							<input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control input-xs" value="XXX-999">
						</div>
						<div class="col-sm-4">
							<b>Pedido</b>
							<input type="text" name="TxtPedido" id="TxtPedido" class="form-control input-xs">
						</div>
						<div class="col-sm-4">
							<b>Zona</b>
							<input type="text" name="TxtZona" id="TxtZona" class="form-control input-xs">
						</div>
						<div class="col-sm-12">
							<b>Lugar entrega</b>
							<input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control input-xs">
						</div>
					</div>
				</form>


			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="Command8_Click();">Aceptar</button>
				<button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cerrar</button>
			</div>
		</div>

	</div>
</div>

<!--script type="text/javascript">
	function Command8_Click() {
		if ($('#DCCiudadI').val() == '' || $('#DCCiudadF').val() == '' || $('#DCRazonSocial').val() == '' || $('#DCEmpresaEntrega').val() == '') {
			swal.fire('Llene todo los campos', '', 'info');
			return false;
		}
		$('#ClaveAcceso_GR').val('.');
		$('#Autorizacion_GR').val($('#LblAutGuiaRem').val());
		var DCserie = $('#DCSerieGR').val();
		if (DCserie == '') { DCserie = '0_0'; }
		var serie = DCserie.split('_');
		$('#Serie_GR').val(serie[1]);
		$('#Remision').val($('#LblGuiaR').val());
		$('#FechaGRE').val($('#MBoxFechaGRE').val());
		$('#FechaGRI').val($('#MBoxFechaGRI').val());
		$('#FechaGRF').val($('#MBoxFechaGRF').val());
		$('#Placa_Vehiculo').val($('#TxtPlaca').val());
		$('#Lugar_Entrega').val($('#TxtLugarEntrega').val());
		$('#Zona').val($('#TxtZona').val());
		$('#CiudadGRI').val($('#DCCiudadI option:selected').text());
		$('#CiudadGRF').val($('#DCCiudadF option:selected').text());

		var nom = $('#DCRazonSocial').val();
		ci = nom.split('_');
		$('#Comercial').val($('#DCRazonSocial option:selected').text());
		$('#CIRUCComercial').val(ci[0]);
		var nom1 = $('#DCEmpresaEntrega').val();
		ci1 = nom1.split('_');
		$('#Entrega').val($('#DCEmpresaEntrega option:selected').text());
		$('#CIRUCEntrega').val(ci1[0]);
		$('#Dir_EntregaGR').val(ci1[1]);
		sms = "Guia de Remision: " + serie[1] + "-" + $('#LblGuiaR').val() + "  Autorizacion: " + $('#LblAutGuiaRem').val();
		$('#LblGuia').val(sms);
		$('#myModal_guia').modal('hide');

	}
</script-->

<div id="myModal_suscripcion" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 55%;min-width:350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">FORMULARIO DE SUSCRIPCION</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10">
						<form id="form_suscripcion">
							<input type="hidden" name="LblClienteCod" id="LblClienteCod">
							<div class="row">
								<div class="col-sm-12">
									<input type="text" name="LblCliente" id="LblCliente" class="form-control input-xs"
										readonly>
									<select class="form-control input-xs" id="DCCtaVenta" name="DCCtaVenta">
										<option value="">Seleccione</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8">
									<div class="row">
										<div class="col-sm-7">
											<b>Periodo</b>
											<div class="row">
												<div class="col-sm-6" style="padding-right: 1px;">
													<input type="date" name="MBDesde" id="MBDesde"
														class="form-control input-xs"
														style="font-size: 10.5px; padding: 2px;"
														value="<?php echo date('Y-m-d') ?>">
												</div>
												<div class="col-sm-6" style="padding-left: 1px;">
													<input type="date" name="MBHasta" id="MBHasta"
														class="form-control input-xs"
														style="font-size: 10.5px; padding: 2px;"
														value="<?php echo date('Y-m-d') ?>">
												</div>
											</div>
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Contrato No.</b>
											<input type="text" name="TextContrato" id="TextContrato"
												class="form-control input-xs" value=".">
										</div>
										<div class="col-sm-2" style="padding-left: 1px; padding-top: 1px;">
											<b>Sector</b>
											<input type="text" name="TextSector" id="TextSector"
												class="form-control input-xs" value=".">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3" style="padding-right: 1px; padding-top: 1px;">
											<b>Ent. hasta</b>
											<input type="text" name="TxtHasta" id="TxtHasta"
												class="form-control input-xs" value="0.00">
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Tipo</b>
											<input type="text" name="TextTipo" id="TextTipo"
												class="form-control input-xs" value=".">
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Comp. Venta</b>
											<input type="text" name="TextFact" id="TextFact"
												class="form-control input-xs" value="0.00">
										</div>
										<div class="col-sm-3" style="padding-left: 1px;  padding-top: 1px;">
											<b>Valor suscr</b>
											<input type="text" name="TextValor" id="TextValor"
												class="form-control input-xs" value="0.00">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b> Atención /Entregar a:</b>
											<input type="text" name="TxtAtencion" id="TxtAtencion"
												class="form-control input-xs">
										</div>
									</div>

								</div>
								<div class="col-sm-4">
									<div class="row">
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcMensual" id="OpcMensual"
														checked> Mensual
												</label>
											</div>
										</div>
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcAnual" id="OpcAnual"> Anual
												</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcQuincenal"
														id="OpcQuincenal">Quincenal
												</label>
											</div>
										</div>
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcTrimestral"
														id="OpcTrimestral"> Trimestral
												</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcSemanal" id="OpcSemanal">
													Semanal
												</label>
											</div>
										</div>
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcSemestral"
														id="OpcSemestral"> Semestral
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-8">
											<div class="row">
												<div class="col-sm-6">
													<b>Ejecutivo de Venta</b>
													<select class="form-control input-xs" id="DCEjecutivoModal"
														name="DCEjecutivoModal">
														<option value="">Seleccione</option>
													</select>
												</div>
												<div class="col-sm-6">
													<b>Comisión %</b>
													<input type="text" name="TextComisionModal" id="TextComisionModal"
														class="form-control input-xs" onblur="TextComision_LostFocus()">
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="row">
												<div class="col-sm-6" style="padding: 0px;">
													<div class="checkbox">
														<label style="padding: 0px;">
															<input type="radio" name="opc2" value='OpcN' id="OpcN"
																checked>
															Nuevo
														</label>
													</div>
												</div>
												<div class="col-sm-6" style="padding: 0px;">
													<div class="checkbox">
														<label style="padding: 0px;">
															<input type="radio" name="opc2" value='OpcR' id="OpcR">
															Renovación
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12" style="padding-top: 5px;">
									<div class="row">
										<div class="col-sm-12 text-center" id="tbl_suscripcion" style="height:170px">
										</div>
										<br>
										<div class="col-sm-12">
											<label>Periodo:<input type="texto" name="txtperiodo"
													id="txtperiodo"></label>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-default btn-block" id="btn_g" onclick="Command1();">
									<img src="../../img/png/grabar.png"><br> Guardar
								</button>
							</div>
							<div class="col-sm-12" style="padding-top: 5px;">
								<button class="btn btn-default btn-block" data-dismiss="modal"
									onclick="delete_asientoP();">
									<img src="../../img/png/bloqueo.png"><br> Cancelar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
			</div>
		</div>

	</div>
</div>

<!-- Modal reserva -->
<div id="myModal_reserva" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Datos de la reserva</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4">
						<b>Entrada</b>
						<input type="date" name="ResvEntrada" id="ResvEntrada" class="form-control input-xs"
							style="font-size: 12px;" value="<?php echo date('Y-m-d') ?>">
					</div>
					<div class="col-sm-4">
						<b>Salida</b>
						<input type="date" name="ResvSalida" id="ResvSalida" class="form-control input-xs"
							style="font-size: 12px;" value="<?php echo date('Y-m-d') ?>">
					</div>
					<div class="col-sm-4">
						<b>Noches</b>
						<input type="text" name="cantNoches" id="cantNoches" class="form-control input-xs" value="0">
					</div>
				</div>
				<div class="row" style="padding-top:5px">
					<div class="col-sm-6">
						<b>Cantidad de Habitaciones</b>
						<input type="text" name="TxtCantHab" id="TxtCantHab" class="form-control input-xs" value="0">
					</div>
					<div class="col-sm-6">
						<b>Tipo de Habitación</b>
						<input type="text" name="TxtTipoHab" id="TxtTipoHab" class="form-control input-xs">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary" onclick="abrirDetalle()">Aceptar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="divTxtDetalleReserva" role="dialog" data-keyboard="false" data-backdrop="static"
	tabindex="-1">
	<div class="modal-dialog modal-dialog modal-dialog-centered modal-sm"
		style="margin-left: 300px; margin-top: 345px;">
		<div class="modal-content">
			<div class="modal-body text-center">
				<textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalleReserva" name="TxtDetalle"
					onblur="txtDetalleLostFocus()"></textarea>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function abrirDetalle() {
		$('#divTxtDetalleReserva').on('shown.bs.modal', function () {
			$('#TxtDetalleReserva').focus();
		})
		$('#divTxtDetalleReserva').modal('show', function () {
			$('#TxtDetalleReserva').focus();
		})

		$('#myModal_reserva').modal('hide');
		var noches = $('#cantNoches').val();
		$('#TextCant').val(noches);
		$('#TxtDetalleReserva').val(producto);
		if (detalle.length > 3) {
			$('#TxtDetalleReserva').val($('#TxtDetalleReserva').val() + '\n' + detalle);
		}
	}

	function txtDetalleLostFocus() {
		$('#divTxtDetalleReserva').modal('hide');
	}
</script>

<!-- Modal ordenes produccion -->
<div id="myModal_ordenesProd" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">.
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Ordenes de Producción</h4>
			</div>
			<div class="modal-body">
				<select id="selectOrden" form="form-control">
				</select>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="CommandButton1_Click()">Imprimir Detalle
					Orden</button>
				<button class="btn btn-primary btn-block" onclick="llenarOrden()">Procesar Selección</button>
				<button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	function llenarOrden() {
		var LstOrdenP = document.getElementById("selectOrden");
		if (LstOrdenP.length > 0) {
			var selectedOptions = LstOrdenP.selectedOptions;
			var ordenSeleccionadaText = "";
			let cantOrdenes = LstOrden.length;
			for (var i = 0; i < selectedOptions.length; i++) {
				var option = selectedOptions[i];
				ordenSeleccionadaText = option.text;
				switch (ordenSeleccionadaText.substring(0, 4)) {
					case "Lote":
						dataInv.fecha_exp = fechaSistema();
						dataInv.fecha_fab = fechaSistema();
						dataInv.modelo = "Ninguno";

						let stockLote = 0;

						var parametros = {
							"lote_no": ordenSeleccionadaText
						};

						$.ajax({
							type: "POST",
							url: '../controlador/facturacion/facturarC.php?case_lote=true',
							data: { parametros: parametros },
							dataType: 'json',
							success: function (data) {
								// console.log(data);
								if (data.length > 0) {
									dataInv.procedencia = data['procedencia'];
									dataInv.modelo = data['modelo'];
									dataInv.serie_no = data['serie_no'];
									dataInv.fecha_exp = data['fecha_exp'];
									dataInv.fecha_fab = data['fecha_fab'];
									stockLote = data['totStock'];
								}
							}
						});
						break;
					case "Orde":

						let cadena = ordenSeleccionadaText;
						var parametros = {
							'cadena': cadena,
							'cod_cxc': document.getElementById("Cod_CxC"),
							'cta': document.getElementById("Cta_CxP")
						};

						$.ajax({
							type: "POST",
							url: '../controlador/facturacion/facturarC.php?case_orde=true',
							data: { parametros: parametros },
							dataType: 'json',
							success: function (data) {
								// console.log(data);
								if (data != '1') {
									Swal.fire('Se han procesado las ordenes', '', 'info');
								} else {
									Swal.fire('No existen Ã³rdenes para procesar', '', 'error');
								}
							}
						});
						break;
				}

				console.log("Opcion seleccionada: ", ordenSeleccionadaText);
			}
		} else {

			lineas_factura();
		}

	}
</script>

<div id="my_modal_abonos" class="modal" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="padding: 6px 0px 6px 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">INGRESO DE CAJA</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10">
						<form id="form_abonos">
							<div class="row">
								<div class="col-sm-2">
									<label class="control-label"
										style="font-size: 11.5px;padding-right: 0px; white-space: nowrap;"><input type="checkbox"
											name="CheqRecibo" id="CheqRecibo" checked> INGRESO CAJA No.</label>
								</div>
								<div class="col-sm-2 col-xs-4">
									<input type="text" name="TxtRecibo" id="TxtRecibo" class="form-control input-xs" value="0000000"
										style="padding-right: 0;">
								</div>
								<div class="col-sm-3 col-xs-3">
									<div class="col-sm-6">
										<label for="LabelDolares" style="font-size: 11.5px; padding-top:5px">COTIZACION</label>
									</div>
									<div class="col-sm-6 col-xs-5">
										<input type="text" name="LabelDolares" id="LabelDolares"
											class="form-control input-xs text-right" value="0.00" style="padding:0;">
									</div>
								</div>
								<div class="col-sm-4 col-xs-5">
									<div class="col-sm-6" style="padding:0;">
										<label for="MBFecha" style="font-size: 11.5px;">Fecha
											del
											abono</label>
									</div>
									<div class="col-sm-6 col-xs-6" style="padding: 0;">
										<input type="date" name="MBFecha" id="MBFecha" class="form-control input-xs"
											value="<?php echo date('Y-m-d'); ?>" style="padding:0;">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-xs-4">
									<div class="col-sm-6" style="padding:0">
										<label for="DCTipo" style="font-size: 11.5px; white-space: nowrap;" class="text-left" for="DCTipo">Tipo
											de
											Documento.</label>
									</div>
									<div class="col-sm-5 col-xs-4">
										<select class="form-control input-xs" id="DCTipo" name="DCTipo" style="padding: 0;" onblur="DCSerie();">
											<option value="FA">FA</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-xs-3">
									<div class="col-sm-3">
										<label for="DCSerie" class="text-left">Serie.</label>
									</div>
									<div class="col-sm-9">
										<select class="form-control input-xs" id="DCSerie" name="DCSerie" onblur="DCFactura_()">
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-xs-3">
									<div class="col-sm-4" style="padding:0;">
										<label for="DCFactura" style="font-size: 11.5px; white-space: nowrap;" id="Label2"
											name="Label2">No.</label>
									</div>
									<div class="col-sm-8 col-xs-9" style="padding-right: 0;">
										<select class="form-control input-xs" id="DCFactura" name="DCFactura"
											onblur="DCAutorizacionF();DCFactura1()">
										</select>
									</div>
								</div>
								<div class="col-sm-2 col-xs-2" style="padding:0px">
									<div class="col-sm-4">
										<label for="LabelSaldo">Saldo</label>
									</div>
									<div class="col-sm-8 col-xs-8">
										<input type="text" name="LabelSaldo" id="LabelSaldo" class="form-control input-xs text-right"
											value="0.00">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-10" style="padding:0px">
									<div class="col-sm-2 col-xs-2">
										<label for="DCAutorizacion">Autorizacion.</label>
									</div>
									<div class="col-sm-10 col-xs-10">
										<select class="form-control input-xs" id="DCAutorizacion" name="DCAutorizacion">
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-9 col-xs-8">
									<input type="text" name="LblCliente" id="LblCliente" class="form-control input-xs"
										placeholder="Cliente">
									<input type="hidden" name="CodigoC" id="CodigoC" class="form-control input-xs"
										placeholder="Cliente">
									<input type="hidden" name="CI_RUC" id="CI_RUC" style="padding:5px 0px 0px 0px">
								</div>
								<div class="col-sm-3 col-xs-4">
									<input type="text" name="LblGrupo" id="LblGrupo" class="form-control input-xs"
										placeholder="Grupo No">
								</div>
							</div>


							<div class="row">
								<div class="col-sm-3 col-xs-2">
									<label for="TxtSerieRet">Serie Retencion</label>
									<input type="text" name="TxtSerieRet" id="TxtSerieRet" class="form-control input-xs"
										placeholder="001" value="001001">
								</div>
								<div class="col-sm-3 col-xs-2">
									<label for="TextCompRet">Retencion No</label>
									<input type="text" name="TextCompRet" id="TextCompRet" class="form-control input-xs text-right"
										placeholder="00000000" value="99999999">
								</div>
								<div class="col-sm-6 col-xs-8">
									<label for="TxtAutoRet" id="LabelAutorizacion">Autorizacion </label>
									<input type="text" name="TxtAutoRet" id="TxtAutoRet" class="form-control input-xs"
										placeholder="Grupo No" value="000000000">
								</div>
							</div>


							<div class="row">
								<div class="col-sm-12 col-xs-7">
									<div class="row">
										<div class="col-sm-6 col-xs-8">
											<label for="DCRetIBienes">RETENCION DEL I.V.A. EN BIENES</label>
											<input type="hidden" name="DCRetIBienesNom" id="DCRetIBienesNom">
											<select class="form-control input-xs" id="DCRetIBienes" name="DCRetIBienes"
												onchange="$('#DCRetIBienesNom').val($('#DCRetIBienes option:selected').text())"
												placeholder="Retencion en bienes">
											</select>
										</div>
										<div class="col-sm-1" style="padding:0px">
											<label for="CBienes">%</label>
											<select class="form-control input-xs" id="CBienes" name="CBienes">
												<option value="0">0</option>
												<option value="10">10</option>
												<option value="30">30</option>
												<option value="100">100</option>
											</select>
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 col-xs-6 text-right">
													<label for="TextRetIVAB">VALOR RETENIDO.</label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRetIVAB" id="TextRetIVAB"
														class="form-control input-xs text-right" placeholder="0.00" value="0.00"
														onblur="Calculo_Saldo()">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-7">
									<div class="row">
										<div class="col-sm-6 col-xs-6">
											<label for="DCRetISer">RETENCION DEL I.V.A. EN SERVICIO </label>
											<input type="hidden" name="DCRetISerNom" id="DCRetISerNom">
											<select class="form-control input-xs" id="DCRetISer" name="DCRetISer"
												onchange="$('#DCRetISerNom').val($('#DCRetISer option:selected').text())">
												<option value="">Retencion en servicios</option>
											</select>
										</div>
										<div class="col-sm-1 col-xs-1" style="padding:0;">
											<label for="CServicio">%</label>
											<select class="form-control input-xs" id="CServicio" name="CServicio">
												<option value="0">0</option>
												<option value="20">20</option>
												<option value="70">70</option>
												<option value="100">100</option>
											</select>
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 col-xs-6 text-right">
													<label for="TextRetIVAS">VALOR RETENIDO.</label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRetIVAS" id="TextRetIVAS"
														class="form-control input-xs text-right" placeholder="0.00"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-7">
									<div class="row">
										<div class="col-sm-4 col-xs-7">
											<label for="DCRetFuente">RETENCION EN LA FUENTE</label>
											<select class="form-control input-xs" id="DCRetFuente" name="DCRetFuente">
											</select>
										</div>
										<div class="col-sm-2 col-xs-3" style="padding: 0;">
											<label for="DCCodRet">CODIGO</label>
											<select class="form-control input-xs" id="DCCodRet" name="DCCodRet">
											</select>
										</div>
										<div class="col-sm-1 col-xs-2" style="padding-right: 0;">
											<label for="TextPorc">%</label>
											<input type="text" name="TextPorc" id="TextPorc" class="form-control input-xs"
												placeholder="000">
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 text-right">
													<label for="TextRet">VALOR RETENIDO.</label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRet" id="TextRet"
														class="form-control input-xs text-right" placeholder="00000000"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-8">
									<div class="row">
										<div class="col-sm-5 col-xs-5">
											<label for="DCBancoNom">CUENTA DEL BANCO </label>
											<input type="hidden" name="DCBancoNom" id="DCBancoNom">
											<select class="form-control input-xs" id="DCBanco" name="DCBanco"
												onchange="$('#DCBancoNom').val($('#form_abonos #DCBanco option:selected').text())">
											</select>
										</div>
										<div class="col-sm-1 col-xs-3" style="padding:0;">
											<label for="TextCheqNo">CHEQUE </label>
											<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control input-xs"
												placeholder="00000000">
										</div>
										<div class="col-sm-3 col-xs-4">
											<label for="TextBanco">NOMBRE DE BANCO</label>
											<input type="text" name="TextBanco" id="TextBanco" class="form-control input-xs"
												placeholder="00000000">
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 text-right">
													<label for="TextCheque">VALOR.</label>
												</div>
												<div class="col-sm-6">
													<input type="text" name="TextCheque" id="TextCheque"
														class="form-control input-xs text-right" placeholder="0.00"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-8">
									<div class="row">
										<div class="col-sm-5 col-xs-5">
											<label for="DCTarjeta">TARJETA DE CREDITO</label>
											<input type="hidden" name="DCTarjetaNom" id="DCTarjetaNom">
											<select class="form-control input-xs" id="DCTarjeta" name="DCTarjeta"
												onchange="$('#DCTarjetaNom').val($('#DCTarjeta option:selected').text())">
												<option value="">Tarjeta credito</option>
											</select>
										</div>
										<div class="col-sm-2 col-xs-3" style="padding:0;">
											<label for="TextBaucher">BAUCHER</label>
											<input type="text" name="TextBaucher" id="TextBaucher" class="form-control input-xs"
												placeholder="00000000">
										</div>
										<div class="col-sm-2 col-xs-4">
											<label for="TextInteres" style="font-size: 11.5px;">INTERES TARJETA</label>
											<input type="text" name="TextInteres" id="TextInteres"
												class="form-control input-xs text-right" placeholder="00000000" value="0"
												onblur="TextInteres();TextRecibido();">
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 text-right">
													<label for="TextTotalBaucher">VALOR.</label>
												</div>
												<div class="col-sm-6">
													<input type="text" name="TextTotalBaucher" id="TextTotalBaucher"
														class="form-control input-xs text-right" placeholder="00000000"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-7 col-xs-6">
									<div class="row">
										<div class="col-sm-12 col-xs-12" style="padding-top:10px">
											<textarea placeholder="Observacion" rows="2" style="resize: none;"
												class="form-control input-xs"></textarea>
											<textarea placeholder="Nota" rows="2" style="resize: none;"
												class="form-control input-xs"></textarea>
											<label for="DCVendedor">Vendedor</label>
											<select class="form-control input-xs" id="DCVendedor" name="DCVendedor">
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-5 col-xs-6" style="padding-top:10px">
									<div class="row">
										<label for="TextCajaMN" class="col-sm-6 col-xs-6 control-label">Caja MN.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="TextCajaMN" id="TextCajaMN"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="TextCajaME" class="col-sm-6 col-xs-6 control-label">Caja ME.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="TextCajaME" id="TextCajaME"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="LabelPend" class="col-sm-6 col-xs-6 control-label">SALDO ACTUAL.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="LabelPend" style="color:red;" id="LabelPend"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="TextRecibido" class="col-sm-6 col-xs-6 control-label">VALOR RECIBIDO.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="TextRecibido" id="TextRecibido"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="LabelCambio" style="font-size: 11.5px;"
											class="col-sm-6  col-xs-6 control-label">CAMBIO A ENTREGAR.</label>
										<div class="col-sm-6 col-xs-6 ">
											<input type="text" name="LabelCambio" style="color:red;" id="LabelCambio"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>

								</div>
							</div>
							<input type="hidden" name="Cta_Cobrar" id="Cta_Cobrar">
						</form>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-default" id="btn_g" onclick="guardar_abonos();"> <img
								src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;</button>
						<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> --><br> <br>
						<button class="btn btn-default" data-dismiss="modal"> <img src="../../img/png/bloqueo.png"><br>
							Cancelar</button>
					</div>
				</div>
				<!--<iframe src="" id="frame" width="100%" height="560px" marginheight="0" frameborder="0"></iframe>-->
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
			</div>
		</div>

	</div>
</div>

<div id="my_modal_abono_anticipado" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">INGRESO DE ABONOS ANTICIPADOS</h4>
			</div>
			<div class="modal-body">
				<!--<iframe src="" id="frame_anticipado" width="100%" height="500px" marginheight="0"
					frameborder="0"></iframe>-->
				<div class="row">
					<div class="col-sm-10">
						<form id="form_abonos_anti" class="row">

							<div class="form-inline col-sm-12">
								<div class="checkbox col-sm-4">
									<input type="checkbox" id="CheqRecibo" name="CheqRecibo" checked>
									<label for="CheqRecibo">RECIBO CAJA No.</label>
								</div>
								<div class="form-group col-sm-4">
									<input type="" class="form-control" id="TxtRecibo" value="0">
								</div>
								<div class="form-group col-sm-4">
									<label for="MBFecha">FECHA</label>
									<input type="date" class="form-control" id="MBFecha" name="MBFecha"
										value="<?php echo date('Y-m-d'); ?>">
								</div>
							</div>

							<div class="col-sm-12" style="padding-top: 5px;" id="Frame1">
								<div class="panel panel-default">
									<div class="panel-heading">
									</div>
									<div class="panel-body">

										<div class="row">
											<div class="col-sm-6">
												<div class="input-group">
													<label class="input-group-addon" for="DCTipo" style="color: red;">TIPO</label>
													<select class="form-control" id="DCTipo" name="DCTipo" style="width: 100%;" onchange="Listar_Facturas_Pendientes()">
														<option value="">Seleccione</option>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="input-group">
													<label class="input-group-addon" for="DCFactura">Factura No.</label>
													<select class="form-control" id="DCFactura" name="DCFactura" style="width: 100%;">
														<option value="">Factura</option>
													</select>
												</div>
											</div>
										</div>
										<div style="padding: 5px;"></div>
										<div class="row">
											<div class="col-sm-5">
												<div class="form-control">
													<label  id="Label4">FECHA DE EMISION</label>
												</div>
											</div>
											<div class="col-sm-3">                               
												<div class="form-control">
													<label  id="Label8"></label>
												</div>                              
											</div>
											<div class="col-sm-4">
												<div class="form-control" style="background-color: red;">
													<label id="Label1" ></label>
												</div>
											</div>
										</div>
										<div style="padding: 5px;"></div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-control">
													<label id="Label3"></label>
												</div>
											</div>
										</div>
										<div style="padding: 5px;"></div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-control">
													<label id="Label6">Saldo Pendiente</label>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-control">
													<label id="LabelSaldo"></label>
												</div>
											</div>
										</div>
										<div style="padding: 5px;"></div>
										<div class="row">
											<div class="col-sm-12" >
												<div class="form-control " style="padding-bottom: 50px;">
													<label id="LblObs" style="color: violet;">Observacion</label>
												</div>
											</div>
										</div>
										<div style="padding: 5px;"></div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-control " style="padding-bottom: 50px;">
													<label id="LblNota" style="color: violet;">Nota</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-12" style="padding-top: 5px;" id="Frame2">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Abono Anticipado</h3>
									</div>
									<div class="panel-body">

										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="DCCliente">Cliente</label>
													<select class="form-select form-select-sm" id="DCClientes" name="DCCliente"
														style="width: 100%;">
														<option value="">Seleccione</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="TxtConcepto">Observación</label>
													<textarea class="form-control" id="TxtConcepto" rows="3"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="DCBanco">Cuenta Contable del Ingreso</label>
													<select class="form-select form-select-sm" id="DCBanco" name="DCBanco"
														style="width: 100%;">
														<option value="">Banco</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="DCCtaAnt">Cuenta Contable de Anticipo</label>
													<select class="form-select form-select-sm" id="DCCtaAnt" name="DCCtaAnt"
														style="width: 100%;">
														<option value="">Banco</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="TextCajaMN" class="col-sm-5 control-label">Caja MN.</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" id="TextCajaMN" placeholder="00000000" value="0.00">
									</div>
								</div>
								<div class="form-group">
									<label for="LabelPend" class="col-sm-5 control-label" id="Label10">Saldo Actual</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" id="LabelPend" placeholder="00000000" value="0.00">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-default btn-block" id="btn_g" onclick="Command1_Click()">
							<img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;
						</button>
						<button class="btn btn-default btn-block" data-dismiss="modal">
							<img src="../../img/png/bloqueo.png"><br>Cancelar
						</button>
					<scrv>
				</div>
			</div>
			<div class="modal-footer"> </div>
		</div>
	</div>
</div>



<!-- Fin Modal cliente nuevo-->
