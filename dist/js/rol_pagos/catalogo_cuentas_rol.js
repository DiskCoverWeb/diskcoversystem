const CAMPOS_CUENTA = [
	'Cta_Sueldo', 'Cta_Horas_Ext', 'Cta_Antiguedad', 'Cta_Diferencia', 'Cta_Vacacion', 'Cta_Quincena',
	'Cta_Aporte_Patronal_G', 'Cta_IESS_Personal', 'Cta_IESS_Patronal',
	'Cta_Decimo_Tercer_G', 'Cta_Decimo_Tercer_P', 'Cta_Decimo_Cuarto_G', 'Cta_Decimo_Cuarto_P',
	'Cta_Fondo_Reserva_G', 'Cta_Fondo_Reserva_P', 'Cta_Vacaciones_G', 'Cta_Vacaciones_P',
	'Cta_Per_Efermedad', 'Cta_Ext_Conyugue_P', 'Cta_Per_Maternidad',
];

$(document).ready(function () {
	$('#cmb_grupo').select2({
		placeholder: 'Seleccione o escriba un grupo nuevo',
		width: '100%',
		tags: true
	});

	rellenarGrupos();

	$('.cta-input').on('blur', function () {
		this.value = this.value.trim().replace(/\.+$/, '');
	});

	$('#cmb_grupo').on('change', function () {
		const grupo = $(this).val();
		if (!grupo) {
			limpiarCampos();
			return;
		}
		cargarGrupo(grupo);
	});
});

function limpiarCampos() {
	CAMPOS_CUENTA.forEach(campo => $('#' + campo).val(''));
}

function recolectarCampos() {
	const datos = {};
	CAMPOS_CUENTA.forEach(campo => { datos[campo] = $('#' + campo).val() || '0'; });
	return datos;
}

function pintarCampos(datos) {
	CAMPOS_CUENTA.forEach(campo => {
		const valor = datos && datos[campo] !== undefined && datos[campo] !== null ? String(datos[campo]).trim() : '';
		$('#' + campo).val(valor === '0' ? '' : valor);
	});
}

function obtenerGrupos() {
	return $.ajax({
		type: 'POST',
		url: '../controlador/rol_pagos/catalogo_cuentas_rolC.php?grupos=true',
		dataType: 'json'
	});
}

function rellenarGrupos(seleccionar) {
	obtenerGrupos().then(function (grupos) {
		const valorActual = seleccionar || $('#cmb_grupo').val();
		$('#cmb_grupo').empty().append('<option value=""></option>');
		(grupos || []).forEach(function (g) {
			$('#cmb_grupo').append($('<option>', { value: g.id, text: g.text }));
		});
		if (valorActual) {
			if (!$('#cmb_grupo').find('option[value="' + valorActual + '"]').length) {
				$('#cmb_grupo').append($('<option>', { value: valorActual, text: valorActual }));
			}
			$('#cmb_grupo').val(valorActual).trigger('change.select2');
		}
	}).catch(function () {
		Swal.fire('Ocurrió un problema al cargar los grupos', '', 'error');
	});
}

function cargarGrupo(grupoRol) {
	ShowModalEspera();
	$.ajax({
		type: 'POST',
		url: '../controlador/rol_pagos/catalogo_cuentas_rolC.php?datosGrupo=true',
		data: { GrupoRol: grupoRol },
		dataType: 'json',
		success: function (response) {
			HideModalEspera();
			pintarCampos(response);
		},
		error: function () {
			HideModalEspera();
			Swal.fire('Ocurrió un problema al cargar el grupo', '', 'error');
		}
	});
}

function guardarGrupo() {
	const grupoRol = $('#cmb_grupo').val();
	if (!grupoRol || String(grupoRol).trim().length <= 1) {
		Swal.fire('Ingrese o seleccione un Grupo de Rol', '', 'warning');
		return;
	}

	const datos = recolectarCampos();
	datos.GrupoRol = grupoRol;

	Swal.fire({
		title: '¿Guardar las cuentas de este grupo?',
		text: 'Grupo: ' + grupoRol,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, guardar'
	}).then((result) => {
		if (result.value) {
			ShowModalEspera();
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/catalogo_cuentas_rolC.php?guardarGrupo=true',
				data: datos,
				dataType: 'json',
				success: function (response) {
					HideModalEspera();
					if (response && response.Respuesta == 1) {
						Swal.fire('Proceso grabado exitosamente', '', 'success');
						rellenarGrupos(response.GrupoRol);
					} else {
						Swal.fire('Error', 'No se pudo grabar el grupo', 'error');
					}
				},
				error: function () {
					HideModalEspera();
					Swal.fire('Ocurrió un problema al guardar', '', 'error');
				}
			});
		}
	});
}

function copiarGrupo() {
	const grupoActual = $('#cmb_grupo').val();
	if (!grupoActual || String(grupoActual).trim().length <= 1) {
		Swal.fire('Primero seleccione o escriba el Grupo de Rol destino', '', 'warning');
		return;
	}

	obtenerGrupos().then(function (grupos) {
		const opciones = {};
		(grupos || []).forEach(function (g) {
			if (g.id !== grupoActual) opciones[g.id] = g.text;
		});
		if (Object.keys(opciones).length === 0) {
			Swal.fire('No hay otros grupos para copiar', '', 'info');
			return;
		}
		Swal.fire({
			title: 'Copiar cuentas de otro grupo',
			input: 'select',
			inputOptions: opciones,
			inputPlaceholder: 'Seleccione el grupo de origen',
			showCancelButton: true,
			confirmButtonText: 'Copiar'
		}).then((result) => {
			if (result.isConfirmed && result.value) {
				cargarGrupo(result.value);
				Swal.fire('Cuentas copiadas', 'Revise los valores y presione Guardar para confirmarlas sobre "' + grupoActual + '"', 'success');
			}
		});
	}).catch(function () {
		Swal.fire('Ocurrió un problema al obtener los grupos', '', 'error');
	});
}

function eliminarGrupo() {
	const grupoRol = $('#cmb_grupo').val();
	if (!grupoRol || String(grupoRol).trim().length <= 1) {
		Swal.fire('Seleccione un Grupo de Rol', '', 'warning');
		return;
	}

	Swal.fire({
		title: '¿Eliminar este grupo?',
		text: 'Grupo: ' + grupoRol,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sí, eliminar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				type: 'POST',
				url: '../controlador/rol_pagos/catalogo_cuentas_rolC.php?eliminarGrupo=true',
				data: { GrupoRol: grupoRol },
				success: function (response) {
					if (response == 1) {
						Swal.fire('Grupo eliminado', '', 'success');
						limpiarFormulario();
						rellenarGrupos();
					} else if (response == -2) {
						Swal.fire('No se puede eliminar', 'Hay empleados asignados a este grupo', 'error');
					} else {
						Swal.fire('Error', 'No se pudo eliminar el grupo', 'error');
					}
				},
				error: function () {
					Swal.fire('Ocurrió un problema al eliminar', '', 'error');
				}
			});
		}
	});
}

function limpiarFormulario() {
	$('#cmb_grupo').val(null).trigger('change');
	limpiarCampos();
}
