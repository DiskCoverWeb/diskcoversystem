var activeTab = 0;
var currentAdoQuery = [];
var currentListaDeCampos = '';
var porGrupoActivo = false;
var porDireccionActivo = false;
var Codigo1 = 'Todos';
var Codigo2 = 'Todos';
var selectedFA = {
  TC: 'FA',
  Serie: '',
  Autorizacion: '',
  Factura: '',
  Cod_CxC: '',
  Tipo_Pago: ''
};

$(document).ready(function () {
  var fechaActual = new Date();
  var anio = fechaActual.getFullYear();
  var mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
  var dia = String(fechaActual.getDate()).padStart(2, '0');

  var primerDiaAnio = anio + '-01-01';
  var ultimoDiaAnio = anio + '-12-31';
  var hoy = anio + '-' + mes + '-' + dia;
  var primerDiaMes = anio + '-' + mes + '-01';

  $('#MBFechaI').val(primerDiaAnio);
  $('#MBFechaF').val(ultimoDiaAnio);
  $('#MBFecha').val(hoy);

  // Cargar combos iniciales
  cargarTipoPago();
  cargarGrupos();
  cargarProductos();
  cargarLineas();
  cargarClientesCombo();

  // Control de eventos: Por Rangos
  $('#CheqRangos').on('change', function () {
    var check = $(this).is(':checked');
    if (check) {
      $('#divRangosCombos').removeClass('d-none').addClass('d-inline-flex').show();
      $('#DCGrupoI, #DCGrupoF').prop('disabled', false);
      if ($('#DCGrupoI option').length === 0) {
        cargarGrupos(function () {
          window.Codigo1 = $('#DCGrupoI').val() || 'Todos';
          window.Codigo2 = $('#DCGrupoF').val() || 'Todos';
        });
      } else {
        window.Codigo1 = $('#DCGrupoI').val() || 'Todos';
        window.Codigo2 = $('#DCGrupoF').val() || 'Todos';
      }
    } else {
      $('#divRangosCombos').removeClass('d-inline-flex').addClass('d-none').hide();
      $('#DCGrupoI, #DCGrupoF').prop('disabled', true);
      window.Codigo1 = 'Todos';
      window.Codigo2 = 'Todos';
    }
    // IMPORTANTE: NO hace nada con la grilla, no recarga datos, solo tiene en memoria lo seleccionado
  });

  $('#DCGrupoI').on('change', function () {
    window.Codigo1 = $(this).val() || 'Todos';
    // IMPORTANTE: NO hace nada con la grilla, solo guarda en memoria la selección
  });

  $('#DCGrupoF').on('change', function () {
    window.Codigo2 = $(this).val() || 'Todos';
    // IMPORTANTE: NO hace nada con la grilla, solo guarda en memoria la selección
  });

  $('#CTipoConsulta').on('change', function () {
    var tipo = $(this).val();
    if (tipo === "Listar Todos" || tipo === "2") {
      porGrupoActivo = false;
      porDireccionActivo = false;
      $('#divDCCliente').css('visibility', 'hidden');
      consultarSegunTab();
    } else if (tipo === "Listar por Direccion" || tipo === "1") {
      porGrupoActivo = false;
      porDireccionActivo = true;
      $('#divDCCliente').css('visibility', 'visible');
      cargarClientesCombo();
    } else {
      porGrupoActivo = true;
      porDireccionActivo = false;
      $('#divDCCliente').css('visibility', 'visible');
      cargarClientesCombo();
    }
  });

  $('#DCCliente').on('change', function () {
    var tipo = $('#CTipoConsulta').val();
    if (tipo === "Listar por Direccion" || tipo === "1") {
      porDireccionActivo = true;
      porGrupoActivo = false;
    } else {
      porGrupoActivo = true;
      porDireccionActivo = false;
    }
    consultarSegunTab();
  });

  $('#MBFecha').on('change', function () {
    cargarLineas();
  });

  $('#CheqFA').on('change', function () {
    if ($(this).is(':checked')) {
      $('#divFechaFADetalle').css('display', 'inline-flex');
    } else {
      $('#divFechaFADetalle').hide();
    }
  });

  $('#CheqPorRubro').on('change', function () {
    if ($(this).is(':checked')) {
      $('#DCProductos').show();
      $('#DCProductos').prop('disabled', false);
    } else {
      $('#DCProductos').hide();
      $('#DCProductos').prop('disabled', true);
    }
  });
});

$('#MBFechaI, #MBFechaF, #CheqVenc, #CheqResumen, #CheqPendientes, #CheqDesc, input[name="rdoEstado"], #DCProductos').on('change', function () {
  consultarSegunTab();
});

$('#DCLinea').on('change', function () {
  lineaLostFocus();
});

// Evento al cambiar de Tab
$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
  activeTab = parseInt($(e.target).data('tab-index'));
  if (activeTab === 3) {
    $('#panelEmail').show();
    $('#DGQuery_container').hide();
    listarClientesEmail();
  } else {
    $('#panelEmail').hide();
    $('#DGQuery_container').show();
    consultarSegunTab();
  }
});

// Checkbox Seleccionar Todos en lista de correos
$('#chkSelectAllEmail').on('change', function () {
  var isChecked = $(this).is(':checked');
  $('.chk-email-item').prop('checked', isChecked);
});

// Atajos de teclado en el formulario
$(document).on('keydown', function (e) {
  if (e.ctrlKey && (e.key === 'g' || e.key === 'G')) {
    e.preventDefault();
    modalCambiarGrupo();
  } else if (e.ctrlKey && (e.key === 'd' || e.key === 'D')) {
    e.preventDefault();
    modalCambiarDireccion();
  } else if (e.ctrlKey && (e.key === 'r' || e.key === 'R')) {
    e.preventDefault();
    retirarBeneficiarios();
  } else if (e.ctrlKey && (e.key === 'n' || e.key === 'N')) {
    e.preventDefault();
    desactivarGrupo();
  } else if (e.key === 'F1') {
    e.preventDefault();
    modalAyuda();
  }
});

function extractProp(item, keyName) {
  if (!item || typeof item !== 'object') return '';
  var target = keyName.toLowerCase();
  for (var k in item) {
    if (k.toLowerCase() === target) {
      var val = item[k];
      return (val !== null && val !== undefined) ? String(val).trim() : '';
    }
  }
  return '';
}

function getParametrosBase() {
  var cTipoConsulta = $('#CTipoConsulta').val();
  var esTodos = (cTipoConsulta === "Listar Todos" || cTipoConsulta === "2");
  var porGrupo = (!esTodos && porGrupoActivo) ? 1 : 0;
  var porDireccion = (!esTodos && porDireccionActivo) ? 1 : 0;
  var dcCliente = $('#DCCliente').val() || '';
  var codigo1 = dcCliente;
  var codigo2 = dcCliente;

  if (esTodos || (porGrupo === 0 && porDireccion === 0)) {
    codigo1 = "Todos";
    codigo2 = "Todos";
  }

  if (!codigo1) codigo1 = '.';
  if (!codigo2) codigo2 = '.';

  return {
    PorGrupo: porGrupo,
    PorDireccion: porDireccion,
    CTipoConsulta: cTipoConsulta,
    CheqRangos: 0, // En la consulta de grilla NO filtra por rango (solo se guarda en memoria según lo solicitado)
    Codigo1: codigo1,
    Codigo2: codigo2,
    RangoGrupoI: window.Codigo1 || $('#DCGrupoI').val() || 'Todos',
    RangoGrupoF: window.Codigo2 || $('#DCGrupoF').val() || 'Todos',
    DCCliente: dcCliente,
    MBFecha: $('#MBFecha').val(),
    MBFechaI: $('#MBFechaI').val(),
    MBFechaF: $('#MBFechaF').val(),
    CheqFA: $('#CheqFA').is(':checked') ? 1 : 0,
    CheqVenc: $('#CheqVenc').is(':checked') ? 1 : 0,
    CheqResumen: $('#CheqResumen').is(':checked') ? 1 : 0,
    CheqPendientes: $('#CheqPendientes').is(':checked') ? 1 : 0,
    CheqDesc: $('#CheqDesc').is(':checked') ? 1 : 0,
    CheqPorRubro: $('#CheqPorRubro').is(':checked') ? 1 : 0,
    DCProductos: $('#DCProductos').val() || '',
    DCProductosVisible: $('#CheqPorRubro').is(':checked') ? 1 : 0,
    DCTipoPago: $('#DCTipoPago').val() || '',
    DCLinea: $('#DCLinea').val() || '',
    TipoFactura: 'FA',
    OpcActivos: $('#rdoActivos').is(':checked') ? 1 : 0,
    OpcInactivos: $('#rdoInactivos').is(':checked') ? 1 : 0,
    FA: selectedFA
  };
}

function consultarSegunTab() {
  limpiarTotales();
  switch (activeTab) {
    case 0:
      listarClientesGrupo();
      break;
    case 1:
      pensionesMensualesAnio();
      break;
    case 2:
      listadoBecados();
      break;
    case 3:
      listarClientesEmail();
      break;
    case 4:
      resumenPensionesMes();
      break;
    case 5:
      nominaAlumnos();
      break;
    case 6:
      listarDeudaPorApi();
      break;
  }
}

function limpiarTotales() {
  $('#lblSubTotalCxC').text('0.00');
  $('#lblTotalAnticipos').text('0.00');
  $('#lblTotalPorCobrar').text('0.00');
}

function mostrarLoader() {
  $('#DGQuery_container').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><div class="mt-2">Cargando datos...</div></div>');
}

// -------------------------------------------------------------------------------------
// Carga de combos
// -------------------------------------------------------------------------------------

function cargarTipoPago() {
  $.ajax({
    type: 'GET',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?DCTipoPago=true',
    dataType: 'json',
    success: function (data) {
      var html = '<option value="">-- Seleccione Forma de Pago --</option>';
      if (Array.isArray(data)) {
        data.forEach(function (item) {
          html += '<option value="' + item.Codigo + '">' + item.CTipoPago + '</option>';
        });
      }
      $('#DCTipoPago').html(html);
    }
  });
}

function cargarGrupos(callback) {
  $.ajax({
    type: 'GET',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?DCGrupos=true',
    dataType: 'json',
    success: function (data) {
      var htmlI = '';
      var htmlF = '';
      if (Array.isArray(data) && data.length > 0) {
        data.forEach(function (item, index) {
          var val = extractProp(item, 'Grupo');
          if (val) {
            htmlI += '<option value="' + val + '">' + val + '</option>';
            htmlF += '<option value="' + val + '"' + (index === data.length - 1 ? ' selected' : '') + '>' + val + '</option>';
          }
        });
      }
      // Fallback si vino vacío pero DCCliente ya contiene grupos
      if (!htmlI && $('#DCCliente option').length > 0 && ($('#CTipoConsulta').val() === 'Listar por Grupo' || $('#CTipoConsulta').val() === '0')) {
        $('#DCCliente option').each(function (idx, opt) {
          var v = $(opt).val();
          if (v && v !== 'Todos') {
            htmlI += '<option value="' + v + '">' + v + '</option>';
            htmlF += '<option value="' + v + '">' + v + '</option>';
          }
        });
      }
      $('#DCGrupoI').html(htmlI);
      $('#DCGrupoF').html(htmlF);
      window.Codigo1 = $('#DCGrupoI').val() || 'Todos';
      window.Codigo2 = $('#DCGrupoF').val() || 'Todos';
      if (typeof callback === 'function') callback();
    },
    error: function () {
      if (typeof callback === 'function') callback();
    }
  });
}

function cargarClientesCombo() {
  var parametros = getParametrosBase();
  if (parametros.CTipoConsulta === "Listar Todos" || parametros.CTipoConsulta === "2") {
    consultarSegunTab();
    return;
  }
  mostrarLoader();
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Listar_Grupo=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (data) {
      var html = '';
      if (Array.isArray(data) && data.length > 0) {
        data.forEach(function (item, index) {
          var valor = '';
          if (parametros.CTipoConsulta === 'Listar por Direccion' || parametros.CTipoConsulta === '1') {
            valor = extractProp(item, 'Direccion');
          } else {
            valor = extractProp(item, 'Grupo');
          }
          if (valor) {
            var isSelected = (index === 0) ? ' selected' : '';
            html += '<option value="' + valor + '"' + isSelected + '>' + valor + '</option>';
          }
        });
      }
      $('#DCCliente').html(html);

      // Si cargamos grupos en DCCliente y DCGrupoI está vacío, también lo alimentamos
      if (($('#CTipoConsulta').val() === 'Listar por Grupo' || $('#CTipoConsulta').val() === '0') && $('#DCGrupoI option').length === 0) {
        $('#DCGrupoI').html(html);
        $('#DCGrupoF').html(html);
        window.Codigo1 = $('#DCGrupoI').val() || 'Todos';
        window.Codigo2 = $('#DCGrupoF').val() || 'Todos';
      }

      consultarSegunTab();
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-warning m-2">No se pudieron cargar los registros de selección.</div>');
    }
  });
}

function cargarProductos() {
  $.ajax({
    type: 'GET',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?DCProductos=true',
    dataType: 'json',
    success: function (data) {
      var html = '<option value="">-- Seleccione Rubro --</option>';
      if (Array.isArray(data)) {
        data.forEach(function (item) {
          html += '<option value="' + item.Codigo_Inv + '">' + item.Producto + '</option>';
        });
      }
      $('#DCProductos').html(html);
    }
  });
}

function cargarLineas() {
  var parametros = getParametrosBase();
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?DCLinea=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      var html = '';
      if (res && res.datos && Array.isArray(res.datos)) {
        res.datos.forEach(function (item) {
          html += '<option value="' + item.Codigo + '" data-serie="' + item.Serie + '" data-autorizacion="' + item.Autorizacion + '" data-fact="' + item.Fact + '">' + item.Concepto + '</option>';
        });
      }
      $('#DCLinea').html(html);
      lineaLostFocus();
    }
  });
}

function lineaLostFocus() {
  var selected = $('#DCLinea').find(':selected');
  selectedFA.Cod_CxC = $('#DCLinea').val() || '';
  selectedFA.Serie = selected.data('serie') || '';
  selectedFA.Autorizacion = selected.data('autorizacion') || '';
  selectedFA.TC = selected.data('fact') || 'FA';

  var parametros = getParametrosBase();
  parametros.FA = selectedFA;

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?DCLinea_LostFocus=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      if (res && res.Caption) {
        $('#LblFactura').text(res.Caption);
      }
      if (res && res.tmp && res.tmp.TFA) {
        selectedFA = Object.assign(selectedFA, res.tmp.TFA);
      }
    }
  });
}

// -------------------------------------------------------------------------------------
// Funciones de Construcción y Renderizado de la Grilla (DGQuery)
// -------------------------------------------------------------------------------------

function construirTablaHTML(datos) {
  if (!Array.isArray(datos) || datos.length === 0) {
    return '<div class="alert alert-info m-2">No se encontraron registros.</div>';
  }

  var limite = 2000;
  var totalRegistros = datos.length;
  var datosRender = (totalRegistros > limite) ? datos.slice(0, limite) : datos;

  var columnas = Object.keys(datos[0]);
  var html = '<table class="table table-sm table-striped table-hover table-bordered small text-nowrap align-middle mb-0">';
  html += '<thead class="table-light sticky-top"><tr>';
  columnas.forEach(function (col) {
    var colLower = col.toLowerCase();
    var isNumeric = (colLower.includes('saldo') || colLower.includes('total') || colLower.includes('cargos') || colLower.includes('abonos') || colLower.includes('cxc') || colLower.includes('anticipo') || colLower.includes('valor'));
    var alignClass = isNumeric ? 'text-end' : (colLower === 'no' ? 'text-center' : '');
    html += '<th class="' + alignClass + '">' + col.replace(/_/g, ' ') + '</th>';
  });
  html += '</tr></thead><tbody>';

  datosRender.forEach(function (fila) {
    html += '<tr>';
    columnas.forEach(function (col) {
      var val = fila[col];
      if (val === null || val === undefined) val = '';
      if (typeof val === 'object' && val.date) {
        val = val.date.substring(0, 10);
      }
      var colLower = col.toLowerCase();
      var isNumeric = (colLower.includes('saldo') || colLower.includes('total') || colLower.includes('cargos') || colLower.includes('abonos') || colLower.includes('cxc') || colLower.includes('anticipo') || colLower.includes('valor'));
      if (isNumeric && val !== '' && !isNaN(val)) {
        val = Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }
      var alignClass = isNumeric ? 'text-end font-monospace' : (colLower === 'no' ? 'text-center' : '');
      html += '<td class="' + alignClass + '">' + val + '</td>';
    });
    html += '</tr>';
  });
  html += '</tbody></table>';

  if (totalRegistros > limite) {
    html += '<div class="p-2 text-secondary small bg-light text-center border-top fw-bold">Mostrando los primeros ' + limite.toLocaleString() + ' de ' + totalRegistros.toLocaleString() + ' registros. Seleccione un grupo o filtro específico para un listado más acotado.</div>';
  }

  return html;
}

function actualizarContenedorGrilla(res) {
  if (!res) {
    $('#DGQuery_container').html('<div class="alert alert-info m-2">No se encontraron registros.</div>');
    $('#lblInfoResultados').text('0 registros encontrados.');
    return;
  }
  currentAdoQuery = res.AdoQuery || [];
  var tablaHTML = '';
  if (Array.isArray(res.AdoQuery) && res.AdoQuery.length > 0) {
    tablaHTML = construirTablaHTML(res.AdoQuery);
  } else if (typeof res.tbl === 'string' && res.tbl.trim().startsWith('<table')) {
    tablaHTML = res.tbl;
  } else if (res.tbl && Array.isArray(res.tbl.data) && res.tbl.data.length > 0) {
    tablaHTML = construirTablaHTML(res.tbl.data);
  } else {
    tablaHTML = '<div class="alert alert-info m-2">No se encontraron registros.</div>';
  }
  $('#DGQuery_container').html(tablaHTML);
  var num = (res.numRegistros !== undefined && res.numRegistros !== null) ? res.numRegistros : (res.AdoQuery ? res.AdoQuery.length : 0);
  $('#lblInfoResultados').text(num + ' registros encontrados.');
}

// -------------------------------------------------------------------------------------
// Pestañas (Tab 0..6)
// -------------------------------------------------------------------------------------

// Tab 0: Listado de clientes por grupo
function listarClientesGrupo() {
  mostrarLoader();
  var parametros = getParametrosBase();
  if (parametros.CTipoConsulta === "Listar Todos" || parametros.CTipoConsulta === "2") {
    $('#lblGridHeaderBanner').text('LISTADO DE CLIENTES');
  } else if (parametros.PorGrupo && parametros.DCCliente && parametros.DCCliente !== 'Todos') {
    $('#lblGridHeaderBanner').text('LISTADO DE CLIENTES (GRUPO No. ' + parametros.DCCliente + ')');
  } else if (parametros.PorDireccion && parametros.DCCliente && parametros.DCCliente !== 'Todos') {
    $('#lblGridHeaderBanner').text('LISTADO DE CLIENTES (DIRECCIÓN: ' + parametros.DCCliente + ')');
  } else {
    $('#lblGridHeaderBanner').text('LISTADO DE CLIENTES');
  }

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Listar_Clientes_Grupo=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      actualizarContenedorGrilla(res);
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-danger m-2">Error al cargar listado de clientes por grupo.</div>');
    }
  });
}

// Tab 1: Pensión mensual del año
function pensionesMensualesAnio() {
  mostrarLoader();
  var parametros = getParametrosBase();
  $('#lblGridHeaderBanner').text('PENSIÓN MENSUAL DEL AÑO');
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Pensiones_Mensuales_Anio=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      if (res && res.error) {
        $('#DGQuery_container').html('<div class="alert alert-warning m-2">' + res.error + '</div>');
      } else {
        actualizarContenedorGrilla(res);
      }
      $('#lblSubTotalCxC').text(res.Caption9 || '0.00');
      $('#lblTotalAnticipos').text(res.Caption10 || '0.00');
      $('#lblTotalPorCobrar').text(res.Caption4 || '0.00');
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-danger m-2">Error al cargar pensiones mensuales.</div>');
    }
  });
}

// Tab 2: Alumnos con descuento / becados
function listadoBecados() {
  mostrarLoader();
  var parametros = getParametrosBase();
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Listado_Becados=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      actualizarContenedorGrilla(res);
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-danger">Error al cargar alumnos con descuento.</div>');
    }
  });
}

// Tab 3: Envíos por Mail
function listarClientesEmail() {
  var parametros = getParametrosBase();
  $('#tblEmailClientesBody').html('<tr><td colspan="4" class="text-center p-3"><div class="spinner-border spinner-border-sm text-primary"></div> Cargando clientes para email...</td></tr>');
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Listar_Clientes_Email=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      currentAdoQuery = res.AdoQuery || [];
      currentListaDeCampos = res.ListaDeCampos || '';
      var html = '';
      if (res.LstClientes && res.LstClientes.length > 0) {
        res.LstClientes.forEach(function (c, idx) {
          if (idx === 0) return; // Registro cabecera TODOS
          html += '<tr>' +
            '<td><input type="checkbox" class="form-check-input chk-email-item" value="' + c.Email + '" data-cliente="' + c.Cliente + '" checked></td>' +
            '<td>' + c.Cliente + '</td>' +
            '<td>' + c.Email + '</td>' +
            '<td class="text-end">' + c.SaldoPendiente + '</td>' +
            '</tr>';
        });
      } else {
        html = '<tr><td colspan="4" class="text-center">No hay clientes con email en este grupo</td></tr>';
      }
      $('#tblEmailClientesBody').html(html);
      $('#lblInfoResultados').text((res.numRegistros - 1) + ' clientes listados para envío.');
    },
    error: function () {
      $('#tblEmailClientesBody').html('<tr><td colspan="4" class="text-danger text-center">Error al listar clientes para email</td></tr>');
    }
  });
}

// Tab 4: Resumen de pensiones por mes
function resumenPensionesMes() {
  mostrarLoader();
  var parametros = getParametrosBase();
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Resumen_Pensiones_Mes=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      actualizarContenedorGrilla(res);
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-danger">Error al cargar resumen de pensiones por mes.</div>');
    }
  });
}

// Tab 5: Cartera recaudada / Nómina de alumnos
function nominaAlumnos() {
  mostrarLoader();
  var parametros = getParametrosBase();
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Nomina_Alumnos=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      actualizarContenedorGrilla(res);
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-danger">Error al cargar nómina de alumnos / cartera recaudada.</div>');
    }
  });
}

// Tab 6: Enviar deuda por API y Email
function listarDeudaPorApi() {
  mostrarLoader();
  var parametros = getParametrosBase();
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Listar_Deuda_por_Api=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      actualizarContenedorGrilla(res);
      $('#lblTotalPorCobrar').text(res.Caption || '0.00');
    },
    error: function () {
      $('#DGQuery_container').html('<div class="alert alert-danger">Error al cargar listado de deuda por API.</div>');
    }
  });
}

// -------------------------------------------------------------------------------------
// Acciones del Toolbar
// -------------------------------------------------------------------------------------

function generarFacturas() {
  var parametros = getParametrosBase();
  if (parametros.CTipoConsulta !== "2") {
    Swal.fire({
      icon: 'warning',
      title: 'Atención',
      text: 'Debe seleccionar la opción: "Listar Todos". Caso contrario no podrá facturar en bloque.'
    });
    return;
  }
  if (!parametros.DCTipoPago) {
    Swal.fire({
      icon: 'warning',
      title: 'Atención',
      text: 'NO HA SELECCIONADO LA FORMA DE PAGO'
    });
    return;
  }

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?GenerarFacturas_Click=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      if (res.response === 1) {
        Swal.fire({
          title: res.Titulo || 'Formulario de Grabación',
          text: res.Mensaje,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Sí, Grabar',
          cancelButtonText: 'Cancelar'
        }).then(function (result) {
          if (result.isConfirmed) {
            grabarFacturasLote(res.FA);
          }
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.Mensaje
        });
      }
    }
  });
}

function grabarFacturasLote(FA) {
  var parametros = getParametrosBase();
  parametros.FA = FA;
  parametros.PorcIva = 12; // Valor base configurable

  Swal.fire({
    title: 'Procesando...',
    text: 'Generando facturas en lote. Por favor espere.',
    allowOutsideClick: false,
    didOpen: function () {
      Swal.showLoading();
    }
  });

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?ProcGrabarMult=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      if (res.Res === 1) {
        Swal.fire({
          icon: 'success',
          title: 'Proceso Exitoso',
          text: res.Mensaje || 'Facturas grabadas correctamente.'
        });
        consultarSegunTab();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error al grabar',
          text: res.Mensaje
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un error inesperado al generar las facturas.'
      });
    }
  });
}

function listadoPorGrupos() {
  Swal.fire({
    title: 'Generando PDF...',
    allowOutsideClick: false,
    didOpen: function () {
      Swal.showLoading();
    }
  });

  $.ajax({
    type: 'GET',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Listado_x_Grupos=true',
    dataType: 'json',
    success: function (res) {
      Swal.close();
      if (res.res === 1 && res.fileName) {
        window.open('../../TEMP/' + res.fileName, '_blank');
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Aviso',
          text: res.mensaje || 'No se pudo generar el reporte.'
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error de comunicación al generar el reporte.'
      });
    }
  });
}

function generarEliminarRubros() {
  var parametros = getParametrosBase();
  if (!parametros.PorGrupo) {
    Swal.fire({
      icon: 'warning',
      title: 'Aviso',
      text: 'Debe estar seleccionada la opción: "Listar por Grupo"'
    });
    return;
  }
  window.location.href = 'inicio.php?mod=02&acc=facturarPension';
}

function generarDeudaPendiente() {
  var parametros = getParametrosBase();
  if (!parametros.PorGrupo) {
    Swal.fire({
      icon: 'warning',
      title: 'Aviso',
      text: 'Debe estar seleccionada la opción: "Listar por Grupo"'
    });
    return;
  }
  window.location.href = 'inicio.php?mod=02&acc=facturarPension';
}

function recalcularFechas() {
  Swal.fire({
    title: '¿Recalcular Meses de Cobros?',
    text: 'Esta acción recalculará las fechas de facturación.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, Recalcular',
    cancelButtonText: 'Cancelar'
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        type: 'GET',
        url: '../controlador/facturacion/listar_clientes_grupoC.php?Recalcular_Fechas=true',
        dataType: 'json',
        success: function (res) {
          if (res.res === 1) {
            Swal.fire({
              icon: 'success',
              title: 'Éxito',
              text: res.mensaje
            });
            consultarSegunTab();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: res.mensaje
            });
          }
        }
      });
    }
  });
}

function imprimirResultados() {
  if (!currentAdoQuery || currentAdoQuery.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Sin datos',
      text: 'No hay registros cargados para imprimir.'
    });
    return;
  }

  var parametros = getParametrosBase();
  parametros.Opcion = activeTab;
  parametros.AdoQuery = currentAdoQuery;

  Swal.fire({
    title: 'Generando reporte...',
    allowOutsideClick: false,
    didOpen: function () {
      Swal.showLoading();
    }
  });

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Impresora=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      Swal.close();
      if (res.res === 1 && res.fileName) {
        window.open('../../TEMP/' + res.fileName, '_blank');
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Aviso',
          text: res.mensaje || 'No se pudo generar el reporte.'
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error de servidor al generar el reporte.'
      });
    }
  });
}

function imprimirRecibos() {
  var parametros = getParametrosBase();
  parametros.Opcion = activeTab;

  Swal.fire({
    title: 'Generando recibos...',
    allowOutsideClick: false,
    didOpen: function () {
      Swal.showLoading();
    }
  });

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Recibos=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      Swal.close();
      if (res.res === 1 && res.fileName) {
        window.open('../../TEMP/' + res.fileName, '_blank');
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Aviso',
          text: res.mensaje || 'No se pueden imprimir los recibos para este rango.'
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error al solicitar impresión de recibos.'
      });
    }
  });
}

function exportarExcel() {
  if (!currentAdoQuery || currentAdoQuery.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Sin datos',
      text: 'No hay registros cargados para exportar a Excel.'
    });
    return;
  }

  var parametros = {
    AdoQuery: currentAdoQuery
  };

  Swal.fire({
    title: 'Exportando a Excel...',
    allowOutsideClick: false,
    didOpen: function () {
      Swal.showLoading();
    }
  });

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Excel=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      Swal.close();
      if (res.res === 1 && res.fileName) {
        var link = document.createElement('a');
        link.href = '../../TEMP/' + res.fileName;
        link.download = res.fileName;
        link.click();
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Aviso',
          text: res.mensaje || 'No se pudo exportar a Excel.'
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error de servidor al exportar a Excel.'
      });
    }
  });
}

// Envío de correo en Tab 3
function enviarEmailClientes() {
  var asunto = $('#TxtAsunto').val().trim();
  var mensaje = $('#TxtMensaje').val().trim();
  var conDeuda = $('#CheqConDeuda').is(':checked') ? 1 : 0;
  var fileInput = document.getElementById('archivoEmail');

  if (!asunto) {
    Swal.fire({ icon: 'warning', title: 'Atención', text: 'Ingrese el asunto del correo.' });
    return;
  }

  var parametros = getParametrosBase();
  parametros.TxtAsunto = asunto;
  parametros.TxtMensaje = mensaje;
  parametros.CheqConDeuda = conDeuda;
  parametros.ListaDeCampos = currentListaDeCampos;

  var formData = new FormData();
  formData.append('parametros', JSON.stringify(parametros));
  if (fileInput.files.length > 0) {
    formData.append('archivoEmail', fileInput.files[0]);
  }

  Swal.fire({
    title: 'Enviando correos...',
    text: 'Por favor espere mientras se despachan los correos.',
    allowOutsideClick: false,
    didOpen: function () {
      Swal.showLoading();
    }
  });

  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Command5_Click=true',
    data: formData,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function (res) {
      if (res.res === 1) {
        Swal.fire({
          icon: 'success',
          title: 'Completado',
          text: res.mensaje || 'Correos enviados exitosamente.'
        });
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Aviso',
          text: res.mensaje || res.error || 'Ocurrió un inconveniente al enviar correos.'
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error en la conexión al enviar correos.'
      });
    }
  });
}

// -------------------------------------------------------------------------------------
// Atajos y Modales en bloque
// -------------------------------------------------------------------------------------

function modalAyuda() {
  $('#modalAyuda').modal('show');
}

function modalCambiarGrupo() {
  var grupoActual = $('#DCCliente').val() || '';
  $('#txtGrupoOrigen').val(grupoActual);
  $('#txtGrupoDestino').val('');
  $('#modalCambiarGrupo').modal('show');
}

function ejecutarCambioGrupo() {
  var cod1 = $('#txtGrupoOrigen').val().trim();
  var cod2 = $('#txtGrupoDestino').val().trim();

  if (!cod1 || !cod2) {
    Swal.fire({ icon: 'warning', title: 'Campos requeridos', text: 'Complete los valores de origen y destino.' });
    return;
  }

  var parametros = { Codigo1: cod1, Codigo2: cod2 };
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Update_Grupo=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      $('#modalCambiarGrupo').modal('hide');
      if (res.res === 1) {
        Swal.fire({ icon: 'success', title: 'Éxito', text: res.msj });
        cargarGrupos();
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res.msj });
      }
    }
  });
}

function modalCambiarDireccion() {
  var dirActual = $('#DCCliente').val() || '';
  $('#txtDireccionOrigen').val(dirActual);
  $('#txtDireccionDestino').val('');
  $('#modalCambiarDireccion').modal('show');
}

function ejecutarCambioDireccion() {
  var cod1 = $('#txtDireccionOrigen').val().trim();
  var cod2 = $('#txtDireccionDestino').val().trim();

  if (!cod1 || !cod2) {
    Swal.fire({ icon: 'warning', title: 'Campos requeridos', text: 'Complete los valores de origen y destino.' });
    return;
  }

  var parametros = { Codigo1: cod1, Codigo2: cod2 };
  $.ajax({
    type: 'POST',
    url: '../controlador/facturacion/listar_clientes_grupoC.php?Update_Direccion=true',
    data: { parametros: parametros },
    dataType: 'json',
    success: function (res) {
      $('#modalCambiarDireccion').modal('hide');
      if (res.res === 1) {
        Swal.fire({ icon: 'success', title: 'Éxito', text: res.msj });
        cargarGrupos();
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res.msj });
      }
    }
  });
}

function retirarBeneficiarios() {
  var parametros = getParametrosBase();
  Swal.fire({
    title: '¿Retirar Beneficiarios?',
    text: 'Esta acción retirará a los beneficiarios sin deuda del grupo actual: ' + parametros.Codigo1,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, Retirar',
    cancelButtonText: 'Cancelar'
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        type: 'POST',
        url: '../controlador/facturacion/listar_clientes_grupoC.php?Retirar_Beneficiarios=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (res) {
          if (res.res === 1) {
            Swal.fire({ icon: 'success', title: 'Éxito', text: res.msj });
            consultarSegunTab();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: res.msj });
          }
        }
      });
    }
  });
}

function desactivarGrupo() {
  var parametros = getParametrosBase();
  Swal.fire({
    title: '¿Desactivar Grupo?',
    text: 'Se actualizarán las referencias y registros del grupo.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, Desactivar',
    cancelButtonText: 'Cancelar'
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        type: 'POST',
        url: '../controlador/facturacion/listar_clientes_grupoC.php?Desactivar_Grupo=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (res) {
          if (res.res === 1) {
            Swal.fire({ icon: 'success', title: 'Éxito', text: res.msj });
            consultarSegunTab();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: res.msj });
          }
        }
      });
    }
  });
}

function eliminarRubrosFacturacion() {
  Swal.fire({
    title: '¿Eliminar Todos los Rubros?',
    text: 'Se eliminarán todos los rubros de facturación del periodo actual.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    confirmButtonText: 'Sí, Eliminar Rubros',
    cancelButtonText: 'Cancelar'
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        type: 'GET',
        url: '../controlador/facturacion/listar_clientes_grupoC.php?Eliminar_Rubros_Facturacion=true',
        dataType: 'json',
        success: function (res) {
          if (res.res === 1) {
            Swal.fire({ icon: 'success', title: 'Éxito', text: res.msj });
            consultarSegunTab();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: res.msj });
          }
        }
      });
    }
  });
}

function activarTabEmail() {
  var tabTrigger = document.querySelector('#tab3-link');
  if (tabTrigger) {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
      var tab = new bootstrap.Tab(tabTrigger);
      tab.show();
    } else {
      $('#tab3-link').tab('show');
    }
  }
}



