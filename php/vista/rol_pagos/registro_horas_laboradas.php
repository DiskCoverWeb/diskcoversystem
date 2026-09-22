<?php
?>
<style>
    .bg-person-sky-blue {
        background-color: #CFE9EF;
        color: #444;
        border-color: #ddd;
    }
    .hp-toolbar-btn {
        min-width: 96px;
        height: 76px;
        gap: 4px;
    }
    .hp-toolbar-btn img {
        width: 32px;
        height: 32px;
    }
    .hp-toolbar-btn i {
        width: 32px;
        height: 32px;
        font-size: 32px;
        line-height: 32px;
        display: inline-block;
        margin: 0 !important;
    }
    .hp-toolbar-btn label {
        font-size: 0.72rem;
        font-weight: 600;
        margin: 0;
    }
    .hp-stat-card {
        border-left: 4px solid var(--bs-primary);
        border-radius: .5rem;
    }
    .hp-stat-card .hp-stat-value {
        font-size: 1.35rem;
        font-weight: 700;
    }
    .hp-stat-card .hp-stat-label {
        font-size: .75rem;
        text-transform: uppercase;
        color: #8a8a8a;
        letter-spacing: .03em;
    }
    .hp-info-chip {
        font-size: .78rem;
        background: #f4f7f9;
        border: 1px solid #e3e8ec;
        border-radius: .4rem;
        padding: .35rem .6rem;
    }
</style>
<?php $jsRolHoras = dirname(__DIR__, 3).'/dist/js/rol_pagos/registro_horas_laboradas.js'; ?>
<script src="../../dist/js/rol_pagos/registro_horas_laboradas.js?v=<?php echo file_exists($jsRolHoras) ? filemtime($jsRolHoras) : time(); ?>"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?>
    </div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0" id="ruta_menu">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-lg-3">
                <label class="form-label fw-bold mb-1">Fecha</label>
                <input type="date" id="txt_fecha" class="form-control" onchange="rellenarBeneficiarios()">
            </div>
            <div class="col-12 col-lg-5">
                <label class="form-label fw-bold mb-1">Tipo de ingreso</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="Ingreso" id="Check_diario" value="Diario" checked>
                    <label class="btn btn-outline-primary" for="Check_diario">Diario</label>

                    <input type="radio" class="btn-check" name="Ingreso" id="Check_semanal" value="Semanal">
                    <label class="btn btn-outline-primary" for="Check_semanal">Semanal</label>

                    <input type="radio" class="btn-check" name="Ingreso" id="Check_quincenal" value="Quincenal">
                    <label class="btn btn-outline-primary" for="Check_quincenal">Quincenal</label>

                    <input type="radio" class="btn-check" name="Ingreso" id="Check_mensual" value="Mensual">
                    <label class="btn btn-outline-primary" for="Check_mensual">Mensual</label>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <label class="form-label fw-bold mb-1">Movimientos de</label>
                <select class="form-select" id="opc_movimientos" onchange="cargarBeneficiario()">
                    <option value="mes_a" selected>Mes actual</option>
                    <option value="dos_m">Dos meses</option>
                    <option value="tres_m">Tres meses</option>
                    <option value="cuatro_m">Cuatro meses</option>
                    <option value="anio_a">Anual actual</option>
                </select>
            </div>

            <div class="col-12">
                <hr class="my-1">
            </div>

            <div class="col-12 col-lg-6">
                <label class="form-label fw-bold mb-1">Beneficiario</label>
                <select class="form-select" id="beneficiario" style="width:100%">
                    <option value="">Seleccione un beneficiario</option>
                </select>
            </div>
            <div class="col-12 col-lg-6 d-flex align-items-end gap-2 flex-wrap">
                <span class="hp-info-chip"><i class="bx bx-calendar-check text-primary"></i> Ingreso: <b id="info_fecha_ingreso">-</b></span>
                <span class="hp-info-chip"><i class="bx bx-group text-primary"></i> Grupo: <b id="info_grupo">-</b></span>
                <span class="hp-info-chip"><i class="bx bx-money text-primary"></i> Sueldo: <b id="info_salario">-</b></span>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0"><i class="bx bx-time-five me-1"></i>Registro manual de horas</h6>
        </div>
        <div class="card-body row g-3">
            <div class="col-6 col-lg-2">
                <div class="input-group">
                    <div class="col-12 bg-person-sky-blue text-center rounded-top">
                        <b>VALOR HORA</b>
                    </div>
                    <input class="form-control form-control-sm" type="number" step="0.01" id="txt_valor_hora" placeholder="0.00">
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="input-group">
                    <div class="col-12 bg-person-sky-blue text-center rounded-top">
                        <b>HORAS TRABAJADAS</b>
                    </div>
                    <input class="form-control form-control-sm" type="number" step="0.01" id="txt_horas_trabajadas" placeholder="0.00">
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="input-group">
                    <div class="col-12 bg-person-sky-blue text-center rounded-top">
                        <b>DIAS</b>
                    </div>
                    <input class="form-control form-control-sm" type="number" id="txt_dias" placeholder="0">
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="input-group">
                    <div class="col-12 bg-person-sky-blue text-center rounded-top">
                        <b>HORAS EXTRAS</b>
                    </div>
                    <input class="form-control form-control-sm" type="number" step="0.01" id="txt_horas_extras" placeholder="0.00">
                </div>
            </div>
            <div class="col-8 col-lg-3">
                <div class="input-group">
                    <div class="col-12 bg-person-sky-blue text-center rounded-top">
                        <b>VALOR HORA EXTRA</b>
                    </div>
                    <div class="d-flex">
                        <select class="form-select form-select-sm" id="cmb_modo_extra" style="max-width: 70px;">
                            <option value="%">%</option>
                            <option value="V">V</option>
                        </select>
                        <input class="form-control form-control-sm" type="number" step="0.01" id="txt_valor_por_hora" placeholder="0.00">
                    </div>
                </div>
            </div>
            <div class="col-4 col-lg-1">
                <div class="input-group">
                    <div class="col-12 bg-person-sky-blue text-center rounded-top">
                        <b>ORDEN</b>
                    </div>
                    <input class="form-control form-control-sm" id="txt_orden" placeholder="0">
                </div>
            </div>

            <div class="col-12 text-end">
                <button class="btn btn-primary btn-sm" onclick="agregarRegistroManual()">
                    <i class="bx bx-plus"></i> Agregar registro
                </button>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <div class="card">
        <div class="card-body d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
            <button class="btn btn-sm btn-outline-secondary d-flex flex-column justify-content-center align-items-center hp-toolbar-btn" title="Generar los días del período según el tipo de ingreso seleccionado" onclick="generarDias()">
                <img src="../../img/png/users.png">
                <label>Generar días</label>
            </button>
            <button class="btn btn-sm btn-outline-danger d-flex flex-column justify-content-center align-items-center hp-toolbar-btn" title="Elimina TODOS los registros de horas de la fecha seleccionada" onclick="eliminarDiasFecha()">
                <img src="../../img/png/eliminar.png">
                <label>Eliminar días</label>
            </button>
            <button class="btn btn-sm btn-outline-secondary d-flex flex-column justify-content-center align-items-center hp-toolbar-btn" title="Registrar permiso de enfermedad del beneficiario" onclick="permisoEnfermedad()">
                <i class='bx bx-plus-medical'></i>
                <label>Permiso enfermedad</label>
            </button>
            <button class="btn btn-sm btn-outline-secondary d-flex flex-column justify-content-center align-items-center hp-toolbar-btn" title="Limpiar formulario" onclick="limpiarFormulario()">
                <img src="../../img/png/salire.png">
                <label>Limpiar</label>
            </button>
        </div>
    </div>
</div>

<div class="mt-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs w-100">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <a class="nav-link active" href="#sueldo_div" data-bs-toggle="tab">SUELDO</a>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <a class="nav-link" href="#novedades_div" data-bs-toggle="tab">NOVEDADES</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="sueldo_div">
                            <div class="col-sm-12">
                                <table class="table text-sm w-100" id="tbl_sueldo"></table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="novedades_div">
                            <div class="col-sm-12 text-end mb-2">
                                <button class="btn btn-sm btn-primary" onclick="agregarNovedad()">
                                    <i class="bx bx-plus"></i> Agregar novedad
                                </button>
                            </div>
                            <div class="col-sm-12">
                                <table class="table text-sm w-100" id="tbl_novedades"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-6 col-lg-3">
            <div class="card hp-stat-card">
                <div class="card-body py-2 px-3">
                    <div class="hp-stat-label">Horas trabajadas</div>
                    <div class="hp-stat-value" id="txt_total_horas_t">0.00</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card hp-stat-card">
                <div class="card-body py-2 px-3">
                    <div class="hp-stat-label">Ingreso líquido</div>
                    <div class="hp-stat-value" id="txt_total_ing_liq">0.00</div>
                </div>
            </div>
        </div>
    </div>
</div>
