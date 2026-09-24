// Modal "Asignar a Rol de Pago" (equivalente web de FRolPag.frm).
// Se abre desde el formulario de cliente/empleado (iframe) con: window.parent.abrirAsignarRol(codigo, nombre)

const RP_URL = '../controlador/rol_pagos/asignar_rolC.php';
const RP_CAMPOS_CTA = [
	'Cta_Sueldo', 'Cta_IESS_Personal', 'Cta_IESS_Patronal', 'Cta_Aporte_Patronal_G', 'Cta_Decimo_Tercer_G', 'Cta_Decimo_Tercer_P',
	'Cta_Per_Maternidad', 'Cta_Vacacion', 'Cta_Quincena', 'Cta_Vacaciones_P', 'Cta_Vacaciones_G', 'Cta_Decimo_Cuarto_G',
	'Cta_Decimo_Cuarto_P', 'Cta_Per_Efermedad', 'Cta_Horas_Ext', 'Cta_Antiguedad', 'Cta_Fondo_Reserva_P', 'Cta_Fondo_Reserva_G',
	'Cta_Diferencia', 'Cta_Ext_Conyugue_P',
];
const RP_MESES = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];

let rpCatalogos = null;
let rpEstado = { codigo: '', nombre: '' };
let rpInicializado = false;

function rpVal(id) { return $('#' + id).val(); }
function rpChk(id) { return $('#' + id).prop('checked'); }
function rpNum(v) { const n = parseFloat(v); return isNaN(n) ? 0 : n; }
function rpFmt(v, dec) { return rpNum(v).toFixed(dec === undefined ? 2 : dec); }

function abrirAsignarRol(codigo, nombre) {
	rpEstado = { codigo: codigo, nombre: nombre };
	$('#modal_asignar_rol').appendTo('body');
	const cargar = rpCatalogos ? $.Deferred().resolve().promise() : cargarCatalogosRol();
	cargar.then(function () {
		if (!rpInicializado) { inicializarAsignarRol(); }
		limpiarAsignarRol();
		$.ajax({
			type: 'POST',
			url: RP_URL + '?empleado=true',
			data: { Codigo: codigo },
			dataType: 'json'
		}).then(function (resp) {
			rpEstado.pais = resp.cliente ? parseInt(resp.cliente.Pais || 0) : 0;
			pintarAsignarRol(resp);
			mostrarModalAsignarRol();
		}).catch(function () {
			Swal.fire('Ocurrió un problema al cargar el empleado', '', 'error');
		});
	});
}

function mostrarModalAsignarRol() {
	const el = document.getElementById('modal_asignar_rol');
	el.style.zIndex = 1058;
	el.addEventListener('shown.bs.modal', function () {
		$('.modal-backdrop').last().css('z-index', 1057);
	}, { once: true });
	bootstrap.Modal.getOrCreateInstance(el).show();
}

function cancelarAsignarRol() {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('modal_asignar_rol')).hide();
}

function cargarCatalogosRol() {
	return $.ajax({
		type: 'POST',
		url: RP_URL + '?catalogos=true',
		dataType: 'json'
	}).then(function (data) {
		rpCatalogos = data;
	}).catch(function () {
		Swal.fire('Ocurrió un problema al cargar los catálogos', '', 'error');
	});
}

function llenarSelect(id, items, valorKey, textoKey, primero) {
	const $s = $('#' + id).empty();
	if (primero) { $s.append($('<option>', { value: '.', text: primero })); }
	(items || []).forEach(function (it) {
		$s.append($('<option>', { value: it[valorKey], text: String(it[textoKey]).trim() }).data('tc', it.TC));
	});
}

function inicializarAsignarRol() {
	rpInicializado = true;
	const padre = $('#modal_asignar_rol');

	llenarSelect('rp_grupo', rpCatalogos.grupos, 'Grupo_Rol', 'Grupo_Rol', 'Seleccione grupo');
	llenarSelect('rp_submodulo', rpCatalogos.subcuentas, 'Codigo', 'Detalle', 'NINGUNO');
	llenarSelect('rp_condicion', rpCatalogos.condiciones, 'Codigo', 'Descripcion');
	llenarSelect('rp_aplica', rpCatalogos.convenio, 'Codigo', 'Descripcion');
	llenarSelect('rp_banco', rpCatalogos.bancos, 'Codigo', 'Descripcion', 'NINGUN BANCO ASIGNADO');

	['rp_grupo', 'rp_submodulo', 'rp_forma_pago', 'rp_banco'].forEach(function (id) {
		$('#' + id).select2({ dropdownParent: padre, width: '100%' });
	});

	$('input[name="rp_sn"]').on('change', recalcularAportes);
	$('#rp_horas, #rp_salario').on('blur', recalcularValorHora);
	$('#rp_grupo').on('change', pintarCuentasGrupo);
	$('input[name="rp_fp"]').on('change', function () { cargarFormasPago(); });
	$('#rp_chk_salida').on('change', function () { $('#rp_fecha_c').prop('disabled', !this.checked); });
	$('#rp_chk_maternidad').on('change', function () { $('#rp_fecha_m').prop('disabled', !this.checked); });
	$('#rp_chk_extc').on('change', function () {
		$('#rp_extc').val(this.checked ? rpFmt(rpCatalogos.iess.IESS_ExtC) : '0.00');
	});
	$('#rp_condicion').on('change', actualizarVisibilidadCondicion);
	$('#rp_fecha_vi').on('change', actualizarMesVacacion);
	$('#rp_cod_profesion').on('blur', function () {
		this.value = String(parseInt(this.value || 0)).padStart(10, '0');
	});
	$('#rp_porc_discap').on('blur', function () { if (rpNum(this.value) > 100) this.value = 100; });
	$('#rp_ci_sustituye').on('blur', function () {
		const ci = this.value.trim();
		if (!ci) return;
		$.ajax({ type: 'POST', url: RP_URL + '?tipoIdentificacion=true', data: { CI: ci }, dataType: 'json' })
			.then(function (r) { $('#rp_td_sustituye').text(r.Tipo || 'N'); });
	});
}

function recalcularAportes() {
	const iess = rpCatalogos.iess;
	if ($('#rp_sn_si').prop('checked')) {
		$('#rp_porc_per').val('0.00');
		$('#rp_porc_pat').val(rpFmt(iess.IESS_Pat + iess.IESS_Per));
	} else {
		$('#rp_porc_per').val(rpFmt(iess.IESS_Per));
		$('#rp_porc_pat').val(rpFmt(iess.IESS_Pat));
	}
}

function recalcularValorHora() {
	let horas = rpNum(rpVal('rp_horas'));
	if (horas <= 0) { horas = 1; $('#rp_horas').val('1.00'); }
	$('#rp_valor_hora').val((rpNum(rpVal('rp_salario')) / (horas * 4)).toFixed(5));
}

function actualizarMesVacacion() {
	const f = rpVal('rp_fecha_vi');
	const mes = f ? new Date(f + 'T00:00:00').getMonth() : -1;
	$('#rp_mes_vac').val(mes >= 0 ? String(mes + 1).padStart(2, '0') + ' ' + RP_MESES[mes] : '');
}

function actualizarVisibilidadCondicion() {
	const visible = parseInt(rpVal('rp_condicion') || 0) > 2;
	$('#rp_fila_sustituye').toggle(visible);
}

function pintarCuentasGrupo() {
	const grupo = rpVal('rp_grupo');
	const datos = (rpCatalogos.grupos || []).find(function (g) { return g.Grupo_Rol === grupo; });
	RP_CAMPOS_CTA.forEach(function (c) {
		const v = datos && datos[c] !== undefined && datos[c] !== null ? String(datos[c]).trim() : '';
		$('#cta_' + c).val(v === '0' ? '' : v);
	});
}

function cargarFormasPago(seleccionar) {
	const tipo = $('input[name="rp_fp"]:checked').val();
	$('#rp_bloque_transf').toggle(tipo === 'T');
	return $.ajax({
		type: 'POST',
		url: RP_URL + '?formasPago=true',
		data: { Tipo: tipo },
		dataType: 'json'
	}).then(function (lista) {
		llenarSelect('rp_forma_pago', lista, 'Codigo', 'Cuentas');
		if (seleccionar) { $('#rp_forma_pago').val(seleccionar); }
		$('#rp_forma_pago').trigger('change.select2');
	});
}

function limpiarAsignarRol() {
	$('#modal_asignar_rol input[type="text"], #modal_asignar_rol input[type="number"], #modal_asignar_rol input[type="date"]').val('');
	$('#modal_asignar_rol input[type="checkbox"]').prop('checked', false);
	$('#rp_sn_no').prop('checked', true);
	$('#rp_fp_e').prop('checked', true);
	$('#rp_fecha_c, #rp_fecha_m').prop('disabled', true);
	$('#rp_grupo').val('.').trigger('change');
	$('#rp_condicion').val($('#rp_condicion option:first').val());
	$('#rp_aplica').val($('#rp_aplica option:first').val());
	$('#rp_td_sustituye').text('C');
	$('#rp_estado_lbl').removeClass('bg-danger').addClass('bg-success').text('Activo');
	$('#modal_asignar_rol .nav-link:first').tab('show');
}

function pintarAsignarRol(resp) {
	const cli = resp.cliente || {};
	const e = resp.empleado;
	const a = resp.acceso;
	const iess = rpCatalogos.iess;

	$('#rp_nombre_lbl').text(rpEstado.nombre || cli.Cliente || '-');
	$('#rp_codigo_lbl').text(rpEstado.codigo);
	$('#rp_fila_convenio').toggle(rpEstado.pais > 0 && rpEstado.pais !== 593);

	$('#rp_fecha').val(rpCatalogos.fecha);
	$('#rp_fecha_vi, #rp_fecha_vf').val(rpCatalogos.fecha);
	$('#rp_fp_dec').val('A');
	['rp_salario', 'rp_valor_dec3', 'rp_valor_dec4', 'rp_porc_com', 'rp_vivienda', 'rp_salud', 'rp_educacion',
		'rp_alimentacion', 'rp_vestimenta', 'rp_turismo', 'rp_discapacidad', 'rp_tercera_edad'].forEach(function (id) { $('#' + id).val('0.00'); });
	$('#rp_horas').val('0.00');
	$('#rp_valor_hora').val('0.00000');
	$('#rp_dias_dec3, #rp_dias_dec4, #rp_cargas, #rp_porc_discap').val('0');
	$('#rp_extc').val('0.00');
	$('#rp_cod_profesion').val('0000000000');
	recalcularAportes();

	if (a) {
		$('#rp_cheq_p').prop('checked', !!a.Primaria);
		$('#rp_cheq_s').prop('checked', !!a.Secundaria);
		$('#rp_cheq_b').prop('checked', !!a.Bachillerato);
		$('#rp_usuario').val(a.Usuario === '.' ? '' : a.Usuario);
		$('#rp_clave').val(a.Clave === '.' ? '' : a.Clave);
	}

	if (e) {
		const limpio = function (v) { return (v === '.' || v === null || v === undefined) ? '' : String(v).trim(); };
		$('#rp_grupo').val(e.Grupo_Rol).trigger('change');
		$('#rp_fecha').val(e.Fecha);
		$('#rp_fecha_c').val(e.FechaC);
		$('#rp_fecha_m').val(e.FechaMat);
		$('#rp_fecha_vi').val(e.FechaVI);
		$('#rp_fecha_vf').val(e.FechaVF);
		$('#rp_porc_com').val(rpFmt(rpNum(e.Porc_Com) * 100));
		$('#rp_valor_hora').val(rpFmt(e.Valor_Hora, 5));
		$('#rp_horas').val(rpFmt(e.Horas_Sem));
		$('#rp_salario').val(rpFmt(e.Salario));
		$('#rp_valor_dec3').val(rpFmt(e.Valor_Dec_3ro));
		$('#rp_valor_dec4').val(rpFmt(e.Valor_Dec_4to));
		$('#rp_dias_dec3').val(e.Dias_Dec_3ro);
		$('#rp_dias_dec4').val(e.Dias_Dec_4to);
		$('#rp_no_seguro').val(limpio(e.No_Personal));
		$('#rp_cta_abono').val(limpio(e.Cta_Transferencia));
		$('#rp_cssp').val(limpio(e.No_CSSP));
		$('#rp_cussp').val(limpio(e.No_CUSSP));
		$('#rp_afponp').val(limpio(e.AFP_ONP));
		$('#rp_cod_profesion').val(String(parseInt(e.CodProfesion || 0)).padStart(10, '0'));
		$('#rp_fp_dec').val(limpio(e.FormaPago10to) || 'A');
		$('#rp_acreditar_ci').val(limpio(e.Acreditar_Cta));
		$('#rp_tarjeta').val(limpio(e.Tarjeta));
		$('#rp_vivienda').val(rpFmt(e.Vivienda));
		$('#rp_salud').val(rpFmt(e.Salud));
		$('#rp_educacion').val(rpFmt(e.Educacion));
		$('#rp_alimentacion').val(rpFmt(e.Alimentacion));
		$('#rp_vestimenta').val(rpFmt(e.Vestimenta));
		$('#rp_discapacidad').val(rpFmt(e.Discapacidad));
		$('#rp_tercera_edad').val(rpFmt(e.Tercera_Edad));
		$('#rp_turismo').val(rpFmt(e.Turismo));
		$('#rp_cargas').val(e.Carga_Familiar);
		$('#rp_ci_sustituye').val(limpio(e.Identificacion));
		$('#rp_td_sustituye').text(limpio(e.TIdentificacion) || 'C');
		$('#rp_porc_discap').val(e.Porcentaje);
		$('#rp_condicion').val(e.Condicion);
		$('#rp_aplica').val(e.Aplica);
		$('#rp_porc_per').val(rpFmt(rpNum(e.Porc_IESS_Per) * 100));
		$('#rp_porc_pat').val(rpFmt(rpNum(e.Porc_IESS_Pat) * 100));
		$('#rp_extc').val(rpFmt(rpNum(e.Porc_IESS_ExtC) * 100));

		$('#rp_chk_fondo').prop('checked', !!e.Pagar_Fondo_Reserva);
		$('#rp_chk_decimos').prop('checked', !!e.Pagar_Decimos);
		$('#rp_chk_rfr').prop('checked', !!e.Reingreso_FR);
		$('#rp_chk_parcial').prop('checked', !!e.TiempoParcial);
		$('#rp_chk_guardian').prop('checked', !!e.Opc_Guardian);
		$('#rp_chk_extc').prop('checked', !!e.ExtC);
		$('#rp_chk_maternidad').prop('checked', !!e.Maternidad);
		$('#rp_fecha_m').prop('disabled', !e.Maternidad);
		if (e.T === 'R') {
			$('#rp_chk_salida').prop('checked', true);
			$('#rp_fecha_c').prop('disabled', false);
			$('#rp_estado_lbl').removeClass('bg-success').addClass('bg-danger').text('Retirado');
		}
		if (String(e.SN) === '2') { $('#rp_sn_si').prop('checked', true); } else { $('#rp_sn_no').prop('checked', true); }
		$('#rp_submodulo').val(e.SubModulo).trigger('change.select2');
		if (e.Codigo_Banco > 0) { $('#rp_banco').val(String(e.Codigo_Banco)).trigger('change.select2'); }

		const fp = { E: 'rp_fp_e', C: 'rp_fp_c', T: 'rp_fp_t', O: 'rp_fp_o' }[e.FP] || 'rp_fp_e';
		$('#' + fp).prop('checked', true);
		cargarFormasPago(e.Cta_Forma_Pago);
	} else {
		$('#rp_submodulo').val($('#rp_submodulo option').filter(function () { return /NOMINA SIN SUBMODULO/i.test(this.text); }).val() || '.').trigger('change.select2');
		$('#rp_banco').val($('#rp_banco option').filter(function () { return /NINGUN BANCO/i.test(this.text); }).val() || '.').trigger('change.select2');
		cargarFormasPago();
	}
	$('#rp_grupo').trigger('change.select2');
	actualizarMesVacacion();
	actualizarVisibilidadCondicion();
}

function guardarAsignarRol() {
	const grupo = rpVal('rp_grupo');
	if (!grupo || grupo === '.') {
		Swal.fire('Seleccione el Grupo del Rol', '', 'warning');
		return;
	}
	if (rpNum(rpVal('rp_salario')) <= 0) {
		Swal.fire('Ingrese el Ingreso Líquido', '', 'warning');
		return;
	}
	const tipoFP = $('input[name="rp_fp"]:checked').val();
	const grupoDatos = (rpCatalogos.grupos || []).find(function (g) { return g.Grupo_Rol === grupo; }) || {};

	const datos = {
		Codigo: rpEstado.codigo,
		Fecha: rpVal('rp_fecha'),
		Salario: rpVal('rp_salario'),
		HorasSem: rpVal('rp_horas'),
		ValorHora: rpVal('rp_valor_hora'),
		GrupoRol: grupo,
		CtaHorasExt: grupoDatos.Cta_Horas_Ext || '0',
		Usuario: rpVal('rp_usuario'),
		Clave: rpVal('rp_clave'),
		Tarjeta: rpVal('rp_tarjeta'),
		SalarioNeto: $('input[name="rp_sn"]:checked').val(),
		PorcCom: rpVal('rp_porc_com'),
		PorcIESSPer: rpVal('rp_porc_per'),
		PorcIESSPat: rpVal('rp_porc_pat'),
		PorcIESSExtC: rpVal('rp_extc'),
		ExtC: rpChk('rp_chk_extc'),
		Primaria: rpChk('rp_cheq_p'),
		Secundaria: rpChk('rp_cheq_s'),
		Bachillerato: rpChk('rp_cheq_b'),
		Salida: rpChk('rp_chk_salida'),
		FechaC: rpVal('rp_fecha_c'),
		Maternidad: rpChk('rp_chk_maternidad'),
		FechaMat: rpVal('rp_fecha_m'),
		TiempoParcial: rpChk('rp_chk_parcial'),
		Guardian: rpChk('rp_chk_guardian'),
		SubModulo: rpVal('rp_submodulo'),
		ReingresoFR: rpChk('rp_chk_rfr'),
		PagarFondoReserva: rpChk('rp_chk_fondo'),
		PagarDecimos: rpChk('rp_chk_decimos'),
		FechaVI: rpVal('rp_fecha_vi'),
		FechaVF: rpVal('rp_fecha_vf'),
		CodProfesion: rpVal('rp_cod_profesion'),
		FPDec: rpVal('rp_fp_dec'),
		ValorDec3ro: rpVal('rp_valor_dec3'),
		DiasDec3ro: rpVal('rp_dias_dec3'),
		ValorDec4to: rpVal('rp_valor_dec4'),
		DiasDec4to: rpVal('rp_dias_dec4'),
		Condicion: rpVal('rp_condicion'),
		PorcDiscap: rpVal('rp_porc_discap'),
		Aplica: rpVal('rp_aplica'),
		Identificacion: rpVal('rp_ci_sustituye'),
		TIdentificacion: $('#rp_td_sustituye').text(),
		NoSeguro: rpVal('rp_no_seguro'),
		NoCUSSP: rpVal('rp_cussp'),
		NoCSSP: rpVal('rp_cssp'),
		AFPONP: rpVal('rp_afponp'),
		FP: tipoFP,
		CtaFormaPago: rpVal('rp_forma_pago'),
		TC: $('#rp_forma_pago option:selected').data('tc') || '.',
		AcreditarCI: rpVal('rp_acreditar_ci'),
		CtaAbono: rpVal('rp_cta_abono'),
		CodigoBanco: tipoFP === 'T' ? rpVal('rp_banco') : 0,
		Vivienda: rpVal('rp_vivienda'),
		Salud: rpVal('rp_salud'),
		Educacion: rpVal('rp_educacion'),
		Alimentacion: rpVal('rp_alimentacion'),
		Vestimenta: rpVal('rp_vestimenta'),
		Turismo: rpVal('rp_turismo'),
		Discapacidad: rpVal('rp_discapacidad'),
		TerceraEdad: rpVal('rp_tercera_edad'),
		Cargas: rpVal('rp_cargas'),
	};

	ShowModalEspera();
	$.ajax({
		type: 'POST',
		url: RP_URL + '?guardar=true',
		data: datos,
		dataType: 'json',
		success: function (resp) {
			HideModalEspera();
			if (resp && resp.ok == 1) {
				Swal.fire('Asignación al rol de pagos guardada', '', 'success');
				cancelarAsignarRol();
			} else {
				Swal.fire('No se pudo guardar', (resp && resp.msg) || '', 'error');
			}
		},
		error: function () {
			HideModalEspera();
			Swal.fire('Ocurrió un problema al guardar', '', 'error');
		}
	});
}
