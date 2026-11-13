<?php
@session_start();
date_default_timezone_set('America/Guayaquil');
?>
<link rel="stylesheet" href="../../dist/css/customTable.css">

<style>
  /* Diseño moderno con fondo en tonos pasteles suaves y descansados a la vista */
  .formulario-grupos-pastel-wrapper {
    background-color: #eef3f8; /* Tono pastel suave azulado/gris perla */
    border: 1px solid #d5e0ec;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
  }

  .toolbar-container {
    background-color: #f8fafc;
    border: 1px solid #d8e2ed;
    border-radius: 6px;
    padding: 6px 10px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  .btn-toolbar-icon {
    width: 32px;
    height: 30px;
    padding: 2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #cbd8e5;
    background: #ffffff;
    border-radius: 4px;
    transition: all 0.2s ease;
  }

  .btn-toolbar-icon:hover {
    background-color: #e8f0f8;
    border-color: #b5c7d8;
  }

  .btn-toolbar-icon img {
    max-width: 20px;
    max-height: 20px;
    object-fit: contain;
  }

  .btn-toolbar-help {
    width: 30px;
    height: 30px;
    padding: 0;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .card-filter {
    background-color: #f8fafc;
    border: 1px solid #d8e2ed;
    border-radius: 6px;
    padding: 10px 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  .custom-nav-tabs {
    border-bottom: 2px solid #0d6efd;
    background: transparent;
  }

  .custom-nav-tabs .nav-link {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    padding: 7px 12px;
    border: 1px solid #cbd8e5;
    border-bottom: none;
    margin-right: 3px;
    background: #e2ebf5; /* Pestañas en color pastel suave */
    border-radius: 5px 5px 0 0;
    transition: all 0.2s ease;
  }

  .custom-nav-tabs .nav-link:hover {
    background: #d4e3f1;
    color: #0d6efd;
  }

  .custom-nav-tabs .nav-link.active {
    background: #ffffff;
    color: #0d6efd;
    border-top: 3px solid #0d6efd;
    border-left: 1px solid #d8e2ed;
    border-right: 1px solid #d8e2ed;
    font-weight: 800;
  }

  .tab-card-body {
    background: #ffffff;
    border: 1px solid #d8e2ed;
    border-top: none;
    border-radius: 0 0 6px 6px;
    padding: 10px;
    min-height: 420px;
  }

  .table-title-banner {
    background: #e2ecf7; /* Banner en tono pastel celeste suave */
    border: 1px solid #c2d5e8;
    border-radius: 4px;
    padding: 6px 12px;
    font-weight: 700;
    font-size: 13px;
    color: #1e3a5f;
    text-align: center;
    letter-spacing: 0.5px;
  }

  .stat-card {
    background: #ffffff;
    border: 1px solid #d8e2ed;
    border-radius: 6px;
    padding: 6px 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  .stat-card .stat-title {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    display: block;
    text-transform: uppercase;
  }

  .stat-card .stat-value {
    font-size: 15px;
    font-weight: 800;
  }

  .footer-shortcuts-legend {
    color: #dc3545;
    font-weight: 700;
    font-size: 11px;
    text-align: center;
    padding-top: 6px;
    letter-spacing: 0.3px;
  }
</style>

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-2">
  <div class="breadcrumb-title pe-3 fw-bold"><?php echo isset($NombreModulo) ? $NombreModulo : 'Facturación'; ?></div>
  <div class="ps-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 p-0" id="ruta_menu">
        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="formulario-grupos-pastel-wrapper">

<!-- ========================================================================= -->
<!-- 1. BARRA SUPERIOR: BOTONES DE ACCIÓN + RANGOS + TIPO DE PAGO              -->
<!-- ========================================================================= -->
<div class="toolbar-container mb-2">
  <div class="d-flex align-items-center justify-content-between flex-nowrap gap-2 overflow-x-auto">
    <!-- Grupo de los 10 Botones (Toolbar1) -->
    <div class="btn-group btn-group-sm flex-nowrap flex-shrink-0" role="toolbar" aria-label="Toolbar Botones">
      <a href="./inicio.php?mod=<?php echo isset($_GET['mod']) ? $_GET['mod'] : '02'; ?>" class="btn btn-outline-secondary btn-toolbar-icon" title="Salir del Módulo" id="btnSalir">
        <img src="../../img/png/salire.png" alt="Salir">
      </a>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Generación de Facturas en Bloque" onclick="generarFacturas()" id="btnGenerarFacturas">
        <img src="../../img/png/grabar.png" alt="Generar Facturas">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Imprime un listado resumido de los grupos creados" onclick="listadoPorGrupos()" id="btnListadoGrupos">
        <img src="../../img/png/paper.png" alt="Listado Grupos">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Generar o Eliminar por Lotes los Rubros a Facturar" onclick="generarEliminarRubros()" id="btnGenerarEliminarRubros">
        <img src="../../img/png/archivero1.png" alt="Generar o Eliminar Rubros">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Genera las Deudas Pendientes" onclick="generarDeudaPendiente()" id="btnGenerarDeudaPendiente">
        <img src="../../img/png/bueno2.png" alt="Generar Deuda Pendiente">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Imprimir Resultados" onclick="imprimirResultados()" id="btnImpresora">
        <img src="../../img/png/impresora.png" alt="Imprimir">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Recalcula Fecha de Facturación" onclick="recalcularFechas()" id="btnRecalcularFechas">
        <img src="../../img/png/calendario.png" alt="Recalcular Fechas">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Imprimir Recibos" onclick="imprimirRecibos()" id="btnRecibos">
        <img src="../../img/png/paper.png" alt="Recibos">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Enviar a Excel" onclick="exportarExcel()" id="btnExcel">
        <img src="../../img/png/table_excel.png" alt="Excel">
      </button>
      <button type="button" class="btn btn-outline-secondary btn-toolbar-icon" title="Enviar al Tutor la Nómina por mail" onclick="activarTabEmail()" id="btnEmailTutor">
        <img src="../../img/png/email.png" alt="Email">
      </button>
    </div>

    <!-- Controles Integrados a la Derecha (Frame1) -->
    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
      <!-- Frame Por Rangos (según Screenshot 1) -->
      <div class="frame-rangos d-flex align-items-center gap-1 border bg-white px-2 py-1 rounded shadow-sm" style="border-color: #cbd8e5 !important;">
        <div class="form-check m-0 d-flex align-items-center gap-1">
          <input class="form-check-input m-0" type="checkbox" id="CheqRangos" style="cursor: pointer;">
          <label class="form-check-label fw-bold text-nowrap" for="CheqRangos" style="font-size: 11px; cursor: pointer;">Por Rangos:</label>
        </div>
        <div id="divRangosCombos" class="align-items-center gap-1" style="display: none;">
          <select class="form-select form-select-sm fw-bold py-0" id="DCGrupoI" style="min-width: 95px; max-width: 125px; font-size: 11px; height: 26px;"></select>
          <select class="form-select form-select-sm fw-bold py-0" id="DCGrupoF" style="min-width: 95px; max-width: 125px; font-size: 11px; height: 26px;"></select>
        </div>
      </div>

      <!-- Checkbox Listar Solo Pendientes -->
      <div class="form-check form-check-inline m-0 d-flex align-items-center gap-1">
        <input class="form-check-input m-0" type="checkbox" id="CheqPendientes" checked>
        <label class="form-check-label fw-bold text-nowrap" for="CheqPendientes" style="font-size: 11px;">
          Listar Solo Pendientes
        </label>
      </div>

      <!-- TIPO DE PAGO -->
      <div class="input-group input-group-sm" style="width: 250px;">
        <span class="input-group-text bg-light fw-bold text-secondary text-nowrap" style="font-size: 11px; padding: 3px 6px;">TIPO DE PAGO</span>
        <select class="form-select" id="DCTipoPago" style="font-size: 11px;"></select>
      </div>

      <!-- Botón de Ayuda ? -->
      <button type="button" class="btn btn-sm btn-outline-primary btn-toolbar-help flex-shrink-0" title="Ayuda y Atajos (F1)" onclick="modalAyuda()">?</button>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- 2. PANEL DE FILTROS: POSICIONES EXACTAS DE LA FILA 1, 2 Y 3 (Frame3)      -->
<!-- ========================================================================= -->
<div class="card-filter mb-2">
  <!-- Fila 1: Emisión/Vencimiento, Tipo Consulta, Resumen, Descuentos, Fecha FA y su detalle -->
  <div class="d-flex align-items-center gap-3 flex-wrap mb-2" style="min-height: 31px;">
    <!-- Emisión y Vencimiento -->
    <div class="d-flex align-items-center gap-2 flex-shrink-0" style="min-width: 220px;">
      <span class="fw-bold small text-secondary">Emisión:</span>
      <div class="form-check m-0">
        <input class="form-check-input" type="checkbox" id="CheqVenc" checked>
        <label class="form-check-label fw-bold small text-nowrap" for="CheqVenc">Vencimiento</label>
      </div>
    </div>

    <!-- Tipo de Consulta -->
    <div class="flex-shrink-0" style="width: 190px;">
      <select class="form-select form-select-sm fw-bold" id="CTipoConsulta" style="background-color: #fce8d5; border-color: #f0ad4e;">
        <option value="Listar por Grupo" selected>Listar por Grupo</option>
        <option value="Listar por Direccion">Listar por Dirección</option>
        <option value="Listar Todos">Listar Todos</option>
      </select>
    </div>

    <!-- Resumen Periodos -->
    <div class="form-check m-0 flex-shrink-0">
      <input class="form-check-input" type="checkbox" id="CheqResumen" checked>
      <label class="form-check-label fw-bold small text-nowrap" for="CheqResumen">Resumen Periodos</label>
    </div>

    <!-- Descuentos -->
    <div class="form-check m-0 flex-shrink-0">
      <input class="form-check-input" type="checkbox" id="CheqDesc">
      <label class="form-check-label fw-bold small text-nowrap" for="CheqDesc">Descuentos</label>
    </div>

    <!-- Fecha FA y sus controles vinculados (sin montarse) -->
    <div class="d-flex align-items-center gap-2 flex-wrap m-0" id="divFechaFAGroup">
      <div class="form-check m-0 d-flex align-items-center gap-1">
        <input class="form-check-input" type="checkbox" id="CheqFA">
        <label class="form-check-label fw-bold small text-nowrap" for="CheqFA">Fecha FA</label>
      </div>
      <div id="divFechaFADetalle" class="align-items-center gap-2 flex-nowrap bg-white border border-secondary-subtle px-2 py-1 rounded shadow-sm" style="display: none;">
        <input type="date" class="form-control form-control-sm fw-bold" id="MBFecha" title="Fecha Emisión" style="width: 120px;">
        <span class="small fw-bold text-dark text-nowrap" style="font-size: 11px;">Línea de Facturación:</span>
        <select class="form-select form-select-sm fw-bold" id="DCLinea" style="min-width: 140px; max-width: 200px;"></select>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small fw-bold text-nowrap" id="LblFactura" style="font-family: inherit; font-size: 11px; white-space: nowrap;">No. 000000-000000000</span>
      </div>
    </div>
  </div>

  <!-- Fila 2: Fechas MBFechaI/MBFechaF, Combo DCCliente, Por Rubros y Alumnos/DCProductos -->
  <div class="d-flex align-items-center gap-3 flex-nowrap overflow-x-auto mb-2" style="min-height: 31px;">
    <!-- Rango de Fechas Inicial y Final -->
    <div class="d-flex align-items-center gap-1 flex-shrink-0" style="min-width: 255px;">
      <input type="date" class="form-control form-control-sm fw-bold" id="MBFechaI" title="Fecha Inicial" style="width: 125px;">
      <input type="date" class="form-control form-control-sm fw-bold" id="MBFechaF" title="Fecha Final" style="width: 125px;">
    </div>

    <!-- Combo de Selección de Cliente / Grupo / Dirección -->
    <div id="divDCCliente" class="flex-shrink-0" style="width: 200px;">
      <select class="form-select form-select-sm fw-bold" id="DCCliente" style="width: 200px;"></select>
    </div>

    <!-- Por Rubros, Activos / Inactivos y Selector de Productos -->
    <div class="d-flex align-items-center gap-2 flex-nowrap flex-grow-1" id="divPorRubrosGroup">
      <div class="form-check m-0">
        <input class="form-check-input" type="checkbox" id="CheqPorRubro">
        <label class="form-check-label fw-bold small text-nowrap" for="CheqPorRubro">Por Rubros:</label>
      </div>
      <div class="form-check form-check-inline m-0">
        <input class="form-check-input" type="radio" name="rdoEstado" id="rdoActivos" value="activos" checked>
        <label class="form-check-label fw-bold small text-nowrap" for="rdoActivos">Activos</label>
      </div>
      <div class="form-check form-check-inline m-0">
        <input class="form-check-input" type="radio" name="rdoEstado" id="rdoInactivos" value="inactivos">
        <label class="form-check-label fw-bold small text-nowrap" for="rdoInactivos">Inactivos</label>
      </div>
      <select class="form-select form-select-sm" id="DCProductos" style="min-width: 180px; max-width: 350px; display: none;"></select>
    </div>
  </div>

  <!-- Fila 3: Observación y Nota -->
  <div class="row align-items-center g-2 mt-1">
    <div class="col-12 col-md-6">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light fw-bold small text-secondary" style="min-width: 110px;">OBSERVACIÓN</span>
        <input type="text" class="form-control" id="TxtObs" maxlength="100" placeholder="Observación">
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light fw-bold small text-secondary" style="min-width: 65px;">NOTA:</span>
        <input type="text" class="form-control" id="TxtNota" maxlength="100" placeholder="Nota adicional">
      </div>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- 3. PESTAÑAS (SSTab2) - ESTILO MODERNO BOOTSTRAP 5                        -->
<!-- ========================================================================= -->
<ul class="nav custom-nav-tabs nav-fill mb-0" id="tabsListarGrupos" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="tab0-link" data-bs-toggle="tab" href="#tab_grupos" role="tab" data-tab-index="0">
      <i class="bx bx-group"></i> LISTADO POR GRUPOS
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab1-link" data-bs-toggle="tab" href="#tab_pensiones" role="tab" data-tab-index="1">
      <i class="bx bx-calendar"></i> PENSIÓN MENSUAL DEL AÑO
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab2-link" data-bs-toggle="tab" href="#tab_descuentos" role="tab" data-tab-index="2">
      <i class="bx bx-purchase-tag"></i> ALUMNOS CON DESCUENTO
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab3-link" data-bs-toggle="tab" href="#tab_email" role="tab" data-tab-index="3">
      <i class="bx bx-envelope"></i> ENVÍOS POR MAIL
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab4-link" data-bs-toggle="tab" href="#tab_resumen" role="tab" data-tab-index="4">
      <i class="bx bx-bar-chart-alt-2"></i> RESUMEN PENSIONES POR MES
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab5-link" data-bs-toggle="tab" href="#tab_cartera" role="tab" data-tab-index="5">
      <i class="bx bx-user-check"></i> CARTERA RECAUDADA
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab6-link" data-bs-toggle="tab" href="#tab_api" role="tab" data-tab-index="6">
      <i class="bx bx-network-chart"></i> ENVIAR DEUDA POR API Y EMAIL
    </a>
  </li>
</ul>

<!-- ========================================================================= -->
<!-- 4. CONTENIDO DE PESTAÑAS: GRILLA (DGQuery) Y ENVÍOS POR MAIL (FrmEmail)   -->
<!-- ========================================================================= -->
<div class="tab-card-body shadow-sm">
  <!-- Título Banner de la Grilla (LISTADO DE CLIENTES) -->
  <div class="table-title-banner mb-2" id="lblGridHeaderBanner">
    LISTADO DE CLIENTES
  </div>

  <!-- Contenedor de la Grilla de Datos (DGQuery) -->
  <div id="DGQuery_container" class="table-responsive" style="max-height: 480px; overflow-y: auto;">
    <div class="text-center p-4">
      <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
      <div class="mt-2 small text-muted">Cargando datos...</div>
    </div>
  </div>

  <!-- Panel Exclusivo de Envíos por Mail para Tab 3 (FrmEmail) -->
  <div id="panelEmail" style="display: none;">
    <div class="card border-0 bg-light mb-2">
      <div class="card-body p-3">
        <div class="row g-3">
          <!-- Columna 1: Remitente, Asunto, Adjuntar -->
          <div class="col-12 col-md-5">
            <div class="mb-2">
              <label class="form-label small fw-bold mb-0 text-secondary">Remitente:</label>
              <div class="border rounded bg-white p-2 small fw-bold text-primary" id="lblRemitente">
                <i class="bx bx-user"></i> <?php echo isset($_SESSION['INGRESO']['Nombre']) ? $_SESSION['INGRESO']['Nombre'] : 'Sistema DiskCover'; ?>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label small fw-bold mb-0 text-secondary">Asunto:</label>
              <input type="text" class="form-control form-control-sm" id="TxtAsunto" maxlength="60" placeholder="Asunto del correo">
            </div>

            <div>
              <label class="form-label small fw-bold mb-0 text-secondary">Adjuntar Archivo:</label>
              <input type="file" class="form-control form-control-sm" id="archivoEmail">
            </div>
          </div>

          <!-- Columna 2: Escriba el mensaje -->
          <div class="col-12 col-md-4">
            <label class="form-label small fw-bold mb-0 text-secondary">Escriba el mensaje:</label>
            <textarea class="form-control form-control-sm" id="TxtMensaje" rows="5" maxlength="255" placeholder="Escriba el cuerpo del mensaje..." style="resize: none;"></textarea>
          </div>

          <!-- Columna 3: Checkbox con Deuda y Botón Enviar -->
          <div class="col-12 col-md-3 d-flex flex-column justify-content-between">
            <div class="form-check pt-3">
              <input class="form-check-input" type="checkbox" id="CheqConDeuda" checked>
              <label class="form-check-label fw-bold small text-secondary" for="CheqConDeuda">
                Enviar mail con deuda pendiente
              </label>
            </div>
            <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-bold" onclick="enviarEmailClientes()">
              <i class="bx bx-send"></i> Enviar mail &gt;&gt;
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista de Clientes con checkboxes (LstClientes) -->
    <div class="card border">
      <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
        <span class="small fw-bold text-primary"><i class="bx bx-list-check"></i> Lista de Destinatarios (LstClientes)</span>
        <div class="form-check m-0">
          <input class="form-check-input" type="checkbox" id="chkSelectAllEmail" checked>
          <label class="form-check-label small fw-bold" for="chkSelectAllEmail">Seleccionar Todos</label>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
          <table class="table table-sm table-hover table-striped mb-0 small">
            <thead class="table-light sticky-top">
              <tr>
                <th width="35" class="text-center">#</th>
                <th>Cliente</th>
                <th>Email</th>
                <th class="text-end">Saldo Pendiente</th>
              </tr>
            </thead>
            <tbody id="tblEmailClientesBody">
              <tr><td colspan="4" class="text-center text-muted p-3">Seleccione un grupo para cargar clientes.</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- 5. PIE DE PÁGINA: TOTALES MODERNOS Y ACCIONES RÁPIDAS                     -->
<!-- ========================================================================= -->
<div class="card border-0 bg-transparent mt-2">
  <div class="card-body p-1">
    <div class="row align-items-center g-2">
      <!-- Totales -->
      <div class="col-12 col-lg-7">
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <div class="stat-card">
            <span class="stat-title">SubTotal CxC</span>
            <span class="stat-value text-primary" id="lblSubTotalCxC">0.00</span>
          </div>

          <div class="stat-card">
            <span class="stat-title">Total Anticipos</span>
            <span class="stat-value text-warning" id="lblTotalAnticipos">0.00</span>
          </div>

          <div class="stat-card">
            <span class="stat-title">Total por Cobrar</span>
            <span class="stat-value text-success" id="lblTotalPorCobrar">0.00</span>
          </div>

          <span class="small text-muted ms-2 fw-bold" id="lblInfoResultados"></span>
        </div>
      </div>

      <!-- Atajos / Acciones Rápidas -->
      <div class="col-12 col-lg-5 text-lg-end">
        <div class="btn-group btn-group-sm flex-wrap" role="group">
          <button type="button" class="btn btn-outline-secondary" onclick="modalCambiarGrupo()" title="Cambiar Grupo (Ctrl+G)">
            <i class="bx bx-edit"></i> Grupo
          </button>
          <button type="button" class="btn btn-outline-secondary" onclick="modalCambiarDireccion()" title="Cambiar Dirección (Ctrl+D)">
            <i class="bx bx-map-pin"></i> Dirección
          </button>
          <button type="button" class="btn btn-outline-warning" onclick="retirarBeneficiarios()" title="Retirar Beneficiarios (Ctrl+R)">
            <i class="bx bx-user-minus"></i> Retirar
          </button>
          <button type="button" class="btn btn-outline-danger" onclick="desactivarGrupo()" title="Desactivar Grupo (Ctrl+N)">
            <i class="bx bx-block"></i> Desactivar
          </button>
          <button type="button" class="btn btn-outline-danger" onclick="eliminarRubrosFacturacion()" title="Eliminar Rubros">
            <i class="bx bx-trash"></i> Rubros
          </button>
        </div>
      </div>
    </div>

    <!-- Leyenda de atajos -->
    <div class="footer-shortcuts-legend">
      &lt;Ctrl+M&gt; Modificar | &lt;Ctrl+F6&gt; No Modifica | &lt;Ctrl+Ins&gt; Insertar | &lt;Ctrl+B&gt; Buscar | &lt;Ctrl+Supr&gt; Eliminar | &lt;Ctrl+V&gt; Cambio de Valores | &lt;Ctrl+N&gt; Desactivar Grupo
    </div>
  </div>
</div>

</div> <!-- Fin formulario-grupos-pastel-wrapper -->

<!-- ========================================================================= -->
<!-- MODALES DE APOYO                                                          -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalAyuda" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white py-2">
        <h6 class="modal-title"><i class="bx bx-help-circle"></i> Ayuda y Atajos de Teclado</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body small">
        <div class="list-group list-group-flush">
          <div class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Ayuda del Formulario</span>
            <span class="badge bg-secondary">F1</span>
          </div>
          <div class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Cambia en Grupo el Valor del Grupo</span>
            <span class="badge bg-secondary">Ctrl + G</span>
          </div>
          <div class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Cambia en Grupo el Valor de la Dirección</span>
            <span class="badge bg-secondary">Ctrl + D</span>
          </div>
          <div class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Retirar Beneficiarios sin deuda del Grupo</span>
            <span class="badge bg-secondary">Ctrl + R</span>
          </div>
          <div class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Desactivar Grupo</span>
            <span class="badge bg-secondary">Ctrl + N</span>
          </div>
          <div class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Facturación de Pensiones</span>
            <span class="badge bg-secondary">Botón Rubros</span>
          </div>
        </div>
      </div>
      <div class="modal-footer py-1">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCambiarGrupo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white py-2">
        <h6 class="modal-title"><i class="bx bx-edit"></i> CAMBIO DE VALORES DEL GRUPO</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body small">
        <div class="mb-3">
          <label class="form-label fw-bold">Grupo Actual:</label>
          <input type="text" class="form-control form-control-sm" id="txtGrupoOrigen" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Nuevo Valor del Grupo:</label>
          <input type="text" class="form-control form-control-sm" id="txtGrupoDestino" placeholder="Ingrese nuevo grupo">
        </div>
      </div>
      <div class="modal-footer py-1">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="ejecutarCambioGrupo()">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCambiarDireccion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white py-2">
        <h6 class="modal-title"><i class="bx bx-map-pin"></i> CAMBIO DE VALORES DE DIRECCIÓN</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body small">
        <div class="mb-3">
          <label class="form-label fw-bold">Ubicación Actual:</label>
          <input type="text" class="form-control form-control-sm" id="txtDireccionOrigen" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Nueva Dirección:</label>
          <input type="text" class="form-control form-control-sm" id="txtDireccionDestino" placeholder="Ingrese nueva dirección">
        </div>
      </div>
      <div class="modal-footer py-1">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="ejecutarCambioDireccion()">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="../../dist/js/facturacion/listar_clientes_grupo.js?v=<?php echo time(); ?>"></script>
