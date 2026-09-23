<?php
?>
<style>
    .bg-person-sky-blue {
        background-color: #CFE9EF;
        color: #444;
        border-color: #ddd;
    }
    .hp-info-chip {
        font-size: .78rem;
        background: #f4f7f9;
        border: 1px solid #e3e8ec;
        border-radius: .4rem;
        padding: .35rem .6rem;
    }
    .btn-group img {
        width: 24px;
        height: 24px;
    }
</style>
<?php $jsCatCtasRol = dirname(__DIR__, 3).'/dist/js/rol_pagos/catalogo_cuentas_rol.js'; ?>
<script src="../../dist/js/rol_pagos/catalogo_cuentas_rol.js?v=<?php echo file_exists($jsCatCtasRol) ? filemtime($jsCatCtasRol) : time(); ?>"></script>
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

<div class="row mb-2">
    <div class="col-12">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary" title="Guardar cuentas del grupo" onclick="guardarGrupo()">
                <img src="../../img/png/save.png">
            </button>
            <button type="button" class="btn btn-outline-secondary" title="Copiar cuentas de otro grupo" onclick="copiarGrupo()">
                <i class="bx bx-copy fs-5"></i>
            </button>
            <button type="button" class="btn btn-outline-danger" title="Eliminar grupo" onclick="eliminarGrupo()">
                <img src="../../img/png/eliminar.png">
            </button>
            <button type="button" class="btn btn-outline-secondary" title="Limpiar formulario / nuevo grupo" onclick="limpiarFormulario()">
                <img src="../../img/png/nuevo.png">
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body pb-2">
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <label class="form-label fw-bold mb-1">Grupo de Rol</label>
                <select class="form-select" id="cmb_grupo" style="width:100%">
                </select>
            </div>
            <div class="col-12 col-lg-6 d-flex align-items-end">
                <span class="hp-info-chip"><i class="bx bx-info-circle text-primary"></i> Seleccione un grupo existente o escriba uno nuevo y presione Enter</span>
            </div>
        </div>
    </div>
</div>

<div class="mt-1">
    <div class="card">
        <div class="card-header py-1"><h6 class="mb-0"><i class="bx bx-money me-1"></i>Nómina</h6></div>
        <div class="card-body row g-2 pb-2">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>SUELDO NORMAL (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Sueldo" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>HORAS EXTRAS (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Horas_Ext" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>ANTIGÜEDAD</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Antiguedad" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>HORAS NO TRABAJADAS</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Diferencia" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>SUELDO VACACIÓN (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Vacacion" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>QUINCENA (A-CxC)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Quincena" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-1">
    <div class="card">
        <div class="card-header py-1"><h6 class="mb-0"><i class="bx bx-shield-quarter me-1"></i>Aportes IESS</h6></div>
        <div class="card-body row g-2 pb-2">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>APORTE PATRONAL (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Aporte_Patronal_G" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>APORTE PERSONAL (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_IESS_Personal" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>APORTE PATRONAL (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_IESS_Patronal" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-1">
    <div class="card">
        <div class="card-header py-1"><h6 class="mb-0"><i class="bx bx-gift me-1"></i>Décimos</h6></div>
        <div class="card-body row g-2 pb-2">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>DÉCIMO TERCERO (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Decimo_Tercer_G" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>DÉCIMO TERCERO (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Decimo_Tercer_P" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>DÉCIMO CUARTO (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Decimo_Cuarto_G" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>DÉCIMO CUARTO (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Decimo_Cuarto_P" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-1">
    <div class="card">
        <div class="card-header py-1"><h6 class="mb-0"><i class="bx bx-wallet me-1"></i>Fondo de Reserva y Provisión de Vacaciones</h6></div>
        <div class="card-body row g-2 pb-2">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>FONDO DE RESERVA (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Fondo_Reserva_G" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>FONDO DE RESERVA (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Fondo_Reserva_P" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>PROV. VACACIONES (G)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Vacaciones_G" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>PROV. VACACIONES (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Vacaciones_P" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-1 mb-3">
    <div class="card">
        <div class="card-header py-1"><h6 class="mb-0"><i class="bx bx-heart me-1"></i>Otros beneficios</h6></div>
        <div class="card-body row g-2 pb-2">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>PERMISO ENFERMEDAD</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Per_Efermedad" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>EXT. DE CÓNYUGE (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Ext_Conyugue_P" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="input-group"><div class="col-12 bg-person-sky-blue text-center rounded-top"><b>PERMISO MATERNIDAD (P)</b></div>
                    <input type="text" class="form-control form-control-sm cta-input" id="Cta_Per_Maternidad" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas'] ?? ''; ?>" onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
                </div>
            </div>
        </div>
    </div>
</div>
