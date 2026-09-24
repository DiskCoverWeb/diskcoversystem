let estadoBeneficiario = {
	Codigo: '',
	Nombre: '',
	Grupo: '',
	HorasSemMensual: 0,
	Salario: 0,
	Evaluar: false,
};

$(document).ready(function () {

	$('#beneficiario').select2({
		placeholder: 'Seleccione un beneficiario',
		width: '100%'
	});

	$('#beneficiario').on('change', function () {
		const codigo = $(this).val();
		limpiarCamposRegistroManual();
		if (!codigo) {
			limpiarInfoBeneficiario();
			return;
		}
		obtenerBeneficiarios().then(function (beneficiarios) {
			const encontrado = (beneficiarios || []).find(b => b.Codigo == codigo);
			if (!encontrado) {
				Swal.fire('Código no asignado', '', 'error');
				return;
			}
			estadoBeneficiario = {
				Codigo: encontrado.Codigo,
				Nombre: encontrado.Cliente,
				Grupo: encontrado.Grupo,
				HorasSemMensual: (encontrado.Horas_Sem || 0) * 4,
				Salario: encontrado.Salario || 0,
				Evaluar: !!encontrado.Horas_Ext,
			};

			$('#txt_valor_hora').val(parseFloat(encontrado.Valor_Hora || 0).toFixed(2));
			$('#info_fecha_ingreso').text(fechaSQLtoLocale(encontrado.Fecha) || '-');
			$('#info_grupo').text(encontrado.Grupo || '-');
			$('#info_salario').text(encontrado.Salario ? Number(encontrado.Salario).toFixed(2) : '-');

			cargarBeneficiario();
		}).catch(function () {
			Swal.fire('Ocurrió un problema!', '', 'error');
		});
	});

	$('#opc_movimientos').on('change', function () {
		if (estadoBeneficiario.Codigo) cargarBeneficiario();
	});

	$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
		const target = $(e.target).attr("href");
		$(target).find('table.dataTable').each(function () {
			if ($.fn.DataTable.isDataTable(this)) {
				$(this).DataTable().columns.adjust().draw();
			}
		});
	});

	$(document).on('click', '.btn-eliminar-hora', function () {
		eliminarRegistroHoras($(this).data());
	});

	$(document).on('click', '.btn-eliminar-novedad', function () {
		eliminarNovedad($(this).data());
	});
});

// ---------- Helpers de fecha ----------

function fechaSQLraw(valor) {
	if (!valor) return '';
	return (typeof valor === 'object' && valor !== null && 'date' in valor) ? valor.date : valor;
}

function fechaSQLtoLocale(valor) {
	const raw = fechaSQLraw(valor);
	if (!raw) return '';
	const fecha = new Date(raw);
	return isNaN(fecha) ? '' : fecha.toLocaleDateString();
}

// Envuelve fecha_valida_valor (global) y además descarta años fuera de un rango razonable,
// para evitar enviar al servidor fechas incompletas/corruptas que se pueden generar al
// escribir manualmente en el <input type="date"> (ej: el año se queda a medio escribir).
function fechaValidaRango(valor) {
	const base = fecha_valida_valor(valor);
	if (!base.valido) return base;
	const anio = new Date(valor).getFullYear();
	if (anio < 1950 || anio > 2200) {
		return { valido: false, mensaje: 'El año de la fecha no es válido.' };
	}
	return { valido: true };
}

// ---------- Beneficiarios ----------

function obtenerBeneficiarios() {
	let fecha = $('#txt_fecha').val();
	let parametros = {
		'Fecha': fecha
	};
	return $.ajax({
		type: 'POST',
		url: '../controlador/rol_pagos/registro_horas_laboradasC.php?beneficiarios=true',
		data: parametros,
		dataType: 'json'
	});
}

function rellenarBeneficiarios() {
	limpiarInfoBeneficiario();
	// El evento "change" del input nativo type="date" se dispara con cada dígito
	// que se completa en el segmento de año mientras se escribe a mano, así que aquí
	// una fecha "inválida" (año incompleto) se ignora en silencio, sin interrumpir al
	// usuario con un mensaje; solo se consulta cuando la fecha ya quedó completa y sana.
	const isFecha = fechaValidaRango($('#txt_fecha').val());
	if (!isFecha.valido) {
		return;
	}
	obtenerBeneficiarios().then(function (beneficiarios) {
		$('#beneficiario').html('<option value="">Seleccione un beneficiario</option>');
		$.each(beneficiarios, function (key, value) {
			$('#beneficiario').append(
				$('<option>', { value: value.Codigo, text: value.Cliente })
			);
		});
		$('#beneficiario').trigger('change.select2');
	}).catch(function () {
		Swal.fire('Ocurrió un problema!', '', 'error');
	});
}

function limpiarInfoBeneficiario() {
	estadoBeneficiario = { Codigo: '', Nombre: '', Grupo: '', HorasSemMensual: 0, Salario: 0, Evaluar: false };
	$('#info_fecha_ingreso, #info_grupo, #info_salario').text('-');
	$('#txt_total_horas_t, #txt_total_ing_liq').text('0.00');
	if ($.fn.DataTable.isDataTable('#tbl_sueldo')) { $('#tbl_sueldo').DataTable().clear().draw(); }
	if ($.fn.DataTable.isDataTable('#tbl_novedades')) { $('#tbl_novedades').DataTable().clear().draw(); }
}

function limpiarCamposRegistroManual() {
	['txt_valor_hora', 'txt_horas_trabajadas', 'txt_dias', 'txt_horas_extras', 'txt_valor_por_hora', 'txt_orden'].forEach(id => $('#' + id).val(''));
	$('#cmb_modo_extra').val('%');
}

function limpiarFormulario() {
	limpiarCamposRegistroManual();
	$('#beneficiario').val(null).trigger('change');
}

// ---------- Carga de tablas (sueldo / novedades) ----------

function cargarBeneficiario() {
	if (!estadoBeneficiario.Codigo) return;
	let fecha = $('#txt_fecha').val();
	if (!fecha) return;
	const opcionMovimientos = $('#opc_movimientos').val();

	const parametros = {
		'Codigo': estadoBeneficiario.Codigo,
		'Fecha': fecha,
		'OpcMov': opcionMovimientos,
	};

	ShowModalEspera();
	$.ajax({
		type: 'POST',
		url: '../controlador/rol_pagos/registro_horas_laboradasC.php?datos_beneficiario=true',
		data: parametros,
		dataType: 'json',
		success: function (response) {
			pintarTablaSueldo(response.HorasTrabajadas);
			pintarTablaNovedades(response.Novedades);
			$('#txt_total_horas_t').text(Number(response.Total || 0).toFixed(2));
			$('#txt_total_ing_liq').text(Number(response.Saldo || 0).toFixed(2));
			HideModalEspera();
		},
		error: function () {
			HideModalEspera();
			Swal.fire('Error al obtener datos, intentelo nuevamente', '', 'error');
		}
	});
}

function pintarTablaSueldo(datos) {
	$('#tbl_sueldo').DataTable({
		language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' },
		data: ProcesarDatos(datos || []),
		columns: [
			{ data: 'Codigo', title: 'Código' },
			{ data: 'Dias', title: 'Días' },
			{
				data: 'Fecha', title: 'Fecha',
				render: function (data) { return fechaSQLtoLocale(data); }
			},
			{ data: 'Horas', title: 'Horas' },
			{ data: 'Horas_Exts', title: 'Horas Ext.' },
			{ data: 'Porc_Hr_Ext', title: '% Hr. Ext.' },
			{ data: 'Valor_Hora', title: 'Valor Hora' },
			{ data: 'Ing_Liquido', title: 'Ing. Líquido' },
			{ data: 'Ing_Horas_Ext', title: 'Ing. Hr. Ext.' },
			{ data: 'Orden', title: 'Orden' },
			{
				data: null, title: 'Acciones', orderable: false, searchable: false,
				render: function (data, type, row) {
					const fecha = encodeURIComponent(fechaSQLraw(row.Fecha));
					return `<button type="button" class="btn btn-sm btn-danger btn-eliminar-hora" title="Eliminar registro"
							data-fecha="${fecha}" data-codigo="${row.Codigo}" data-horas="${row.Horas}" data-orden="${row.Orden}">
							<i class="bx bx-trash m-0"></i></button>`;
				}
			}
		],
		scrollX: true,
		scrollY: '400px',
		scrollCollapse: true,
		destroy: true,
		paging: false,
		searching: false,
		info: false,
		createdRow: function (row, data) {
			alignEnd(row, data);
		},
	});
}

function pintarTablaNovedades(datos) {
	$('#tbl_novedades').DataTable({
		language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' },
		data: ProcesarDatos(datos || []),
		columns: [
			{
				data: 'Fecha', title: 'Fecha',
				render: function (data) { return fechaSQLtoLocale(data); }
			},
			{ data: 'Hora', title: 'Hora' },
			{ data: 'Proceso', title: 'Proceso' },
			{ data: 'Novedades', title: 'Novedad' },
			{ data: 'Codigo', title: 'Código' },
			{
				data: null, title: 'Acciones', orderable: false, searchable: false,
				render: function (data, type, row) {
					const fecha = encodeURIComponent(fechaSQLraw(row.Fecha));
					return `<button type="button" class="btn btn-sm btn-danger btn-eliminar-novedad" title="Eliminar novedad"
							data-fecha="${fecha}" data-codigo="${row.Codigo}" data-hora="${row.Hora}">
							<i class="bx bx-trash m-0"></i></button>`;
				}
			}
		],
		scrollX: true,
		scrollY: '400px',
		scrollCollapse: true,
		destroy: true,
		paging: false,
		searching: false,
		info: false,
		createdRow: function (row, data) {
			alignEnd(row, data);
		},
	});
}

// ---------- Generar / eliminar días (masivo) ----------

function generarDias() {
	let opcionIngreso = $('input[name="Ingreso"]:checked').val();
	let fecha = $('#txt_fecha').val();
	const isFecha = fechaValidaRango(fecha);
	if (!isFecha.valido) {
		Swal.fire('Fecha inválida', isFecha.mensaje, 'error');
		return;
	}
	if (opcionIngreso !== 'Semanal' && opcionIngreso !== 'Quincenal' && opcionIngreso !== 'Mensual') {
		Swal.fire('Tipo de ingreso no válido', 'La generación masiva de días solo aplica para Semanal, Quincenal o Mensual.', 'warning');
		return;
	}
	fecha = fecha.replace(/-/g, "/");
	const orden = $('#txt_orden').val() || '0';

	ShowModalEspera();
	$.ajax({
		type: 'POST',
		url: '../controlador/rol_pagos/registro_horas_laboradasC.php?generarDias=true',
		data: { 'Fecha': fecha, 'OpcIngreso': opcionIngreso, 'Orden': orden },
		success: function (response) {
			HideModalEspera();
			if (response == 1) {
				Swal.fire('Días generados', '', 'success');
				cargarBeneficiario();
			} else {
				Swal.fire('Error', 'Ocurrió un error, intentelo nuevamente', 'error');
			}
		},
		error: function () {
			HideModalEspera();
			Swal.fire('Ocurrió un problema!', '', 'error');
		}
	});
}

function eliminarDiasFecha() {
	let fecha = $('#txt_fecha').val();
	const isFecha = fechaValidaRango(fecha);
	if (!isFecha.valido) {
		Swal.fire('Fecha inválida', isFecha.mensaje, 'error');
		return;
	}
	fecha = fecha.replace(/-/g, "/");

	Swal.fire({
		title: '¿Eliminar TODOS los registros de horas de esta fecha?',
		text: 'Esta acción elimina las horas de TODOS los empleados registradas en la fecha seleccionada. No se puede deshacer.',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sí, eliminar'
	}).then((result) => {
		if (result.value) {
			ShowModalEspera();
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/registro_horas_laboradasC.php?eliminarDiasFecha=true',
				data: { 'Fecha': fecha },
				success: function (response) {
					HideModalEspera();
					if (response == 1) {
						Swal.fire('Registros eliminados', '', 'success');
						cargarBeneficiario();
					} else {
						Swal.fire('Error', 'Ocurrió un error, intentelo nuevamente', 'error');
					}
				},
				error: function () {
					HideModalEspera();
					Swal.fire('Ocurrió un problema!', '', 'error');
				}
			});
		}
	});
}

// ---------- Registro manual ----------

function agregarRegistroManual() {
	if (!estadoBeneficiario.Codigo) {
		Swal.fire('Seleccione un beneficiario', '', 'warning');
		return;
	}
	let fecha = $('#txt_fecha').val();
	const isFecha = fechaValidaRango(fecha);
	if (!isFecha.valido) {
		Swal.fire('Fecha inválida', isFecha.mensaje, 'error');
		return;
	}

	const horas = parseFloat($('#txt_horas_trabajadas').val()) || 0;
	const valorHora = parseFloat($('#txt_valor_hora').val()) || 0;
	const dias = parseInt($('#txt_dias').val()) || 0;
	const orden = $('#txt_orden').val() || '0';
	const horasExtras = parseFloat($('#txt_horas_extras').val()) || 0;
	const modo = $('#cmb_modo_extra').val();
	const valorPorHora = parseFloat($('#txt_valor_por_hora').val()) || 0;

	if (horas <= 0 || valorHora <= 0) {
		Swal.fire('Datos incompletos', 'Ingrese horas trabajadas y valor hora', 'warning');
		return;
	}

	let cuota = 0, totalExtra = 0;
	if (modo === '%') {
		cuota = 1 + (valorPorHora / 100);
		totalExtra = horasExtras * (valorHora * cuota);
	} else {
		cuota = valorPorHora;
		totalExtra = horasExtras * cuota;
	}
	const totalPropuesto = (horas * valorHora) + totalExtra;

	Swal.fire({
		title: 'Confirmar registro',
		html: 'El salario mensual asignado es <b>' + Number(estadoBeneficiario.Salario || 0).toFixed(2) + '</b>.<br>Verifique el valor a registrar:',
		input: 'number',
		inputValue: totalPropuesto.toFixed(2),
		showCancelButton: true,
		confirmButtonText: 'Guardar'
	}).then((result) => {
		if (result.isConfirmed) {
			const ingLiquido = parseFloat(result.value);
			ShowModalEspera();
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/registro_horas_laboradasC.php?insertarRegistroManual=true',
				data: {
					Codigo: estadoBeneficiario.Codigo,
					Fecha: fecha.replace(/-/g, "/"),
					Dias: dias,
					Horas: horas,
					HorasExtras: horasExtras,
					ValorHora: valorHora,
					ModoHoraExtra: modo,
					ValorPorHora: valorPorHora,
					IngLiquido: isNaN(ingLiquido) ? totalPropuesto : ingLiquido,
					Orden: orden,
				},
				success: function (response) {
					HideModalEspera();
					if (response == 1) {
						Swal.fire('Registro agregado', '', 'success');
						limpiarCamposRegistroManual();
						cargarBeneficiario();
					} else {
						Swal.fire('Error', 'No se pudo agregar el registro', 'error');
					}
				},
				error: function () {
					HideModalEspera();
					Swal.fire('Ocurrió un problema!', '', 'error');
				}
			});
		}
	});
}

function eliminarRegistroHoras(data) {
	Swal.fire({
		title: '¿Eliminar este registro de horas?',
		text: 'Código: ' + data.codigo,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Sí, eliminar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/registro_horas_laboradasC.php?eliminarRegistroHoras=true',
				data: { Codigo: data.codigo, Fecha: decodeURIComponent(data.fecha), Horas: data.horas, Orden: data.orden },
				success: function (response) {
					if (response == 1) {
						Swal.fire('Registro eliminado', '', 'success');
						cargarBeneficiario();
					} else {
						Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
					}
				},
				error: function () {
					Swal.fire('Ocurrió un problema!', '', 'error');
				}
			});
		}
	});
}

// ---------- Permiso de enfermedad ----------

function permisoEnfermedad() {
	if (!estadoBeneficiario.Codigo) {
		Swal.fire('Seleccione un beneficiario', '', 'warning');
		return;
	}
	let fecha = $('#txt_fecha').val();
	const isFecha = fechaValidaRango(fecha);
	if (!isFecha.valido) {
		Swal.fire('Fecha inválida', isFecha.mensaje, 'error');
		return;
	}

	Swal.fire({
		title: 'Permiso de enfermedad',
		text: 'Días de permiso para ' + estadoBeneficiario.Nombre,
		input: 'number',
		inputAttributes: { min: 0, max: 30 },
		inputValue: 0,
		showCancelButton: true,
		confirmButtonText: 'Guardar'
	}).then((result) => {
		if (result.isConfirmed) {
			const dias = parseInt(result.value || 0);
			if (dias <= 3) {
				Swal.fire('Advertencia', 'Ingrese más de 3 días de enfermedad', 'warning');
				return;
			}
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/registro_horas_laboradasC.php?permisoEnfermedad=true',
				data: { Codigo: estadoBeneficiario.Codigo, Fecha: fecha.replace(/-/g, "/"), Dias: dias },
				success: function (response) {
					if (response == 1) {
						Swal.fire('Permiso registrado', '', 'success');
						cargarBeneficiario();
					} else {
						Swal.fire('Error', 'No se pudo registrar el permiso', 'error');
					}
				},
				error: function () {
					Swal.fire('Ocurrió un problema!', '', 'error');
				}
			});
		}
	});
}

// ---------- Novedades ----------

function agregarNovedad() {
	if (!estadoBeneficiario.Codigo) {
		Swal.fire('Seleccione un beneficiario', '', 'warning');
		return;
	}
	let fecha = $('#txt_fecha').val();
	const isFecha = fechaValidaRango(fecha);
	if (!isFecha.valido) {
		Swal.fire('Fecha inválida', isFecha.mensaje, 'error');
		return;
	}

	Swal.fire({
		title: 'Nueva novedad',
		input: 'text',
		inputPlaceholder: 'Ingrese la novedad del mes',
		showCancelButton: true,
		confirmButtonText: 'Guardar'
	}).then((result) => {
		if (result.isConfirmed && result.value && result.value.trim() !== '') {
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/registro_horas_laboradasC.php?agregarNovedad=true',
				data: { Codigo: estadoBeneficiario.Codigo, Fecha: fecha.replace(/-/g, "/"), Tarea: result.value.trim().toUpperCase() },
				success: function (response) {
					if (response == 1) {
						Swal.fire('Novedad agregada', '', 'success');
						cargarBeneficiario();
					} else {
						Swal.fire('Error', 'No se pudo agregar la novedad', 'error');
					}
				},
				error: function () {
					Swal.fire('Ocurrió un problema!', '', 'error');
				}
			});
		}
	});
}

function eliminarNovedad(data) {
	Swal.fire({
		title: '¿Eliminar esta novedad?',
		text: 'Código: ' + data.codigo + ' - Hora: ' + data.hora,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Sí, eliminar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/registro_horas_laboradasC.php?eliminarNovedad=true',
				data: { Codigo: data.codigo, Fecha: decodeURIComponent(data.fecha), Hora: data.hora },
				success: function (response) {
					if (response == 1) {
						Swal.fire('Novedad eliminada', '', 'success');
						cargarBeneficiario();
					} else {
						Swal.fire('Error', 'No se pudo eliminar la novedad', 'error');
					}
				},
				error: function () {
					Swal.fire('Ocurrió un problema!', '', 'error');
				}
			});
		}
	});
}
