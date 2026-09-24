<?php
$formatoCta = $_SESSION['INGRESO']['Formato_Cuentas'] ?? '';

// Cada bloque = filas de pares [Gastos (izquierda), Pasivo (derecha)], igual que el formulario VB6.
$bloques = [
    [
        [['Cta_Sueldo', 'Sueldo Normal (G)'],               ['Cta_Antiguedad', 'Antigüedad']],
        [['Cta_Vacacion', 'Sueldo Vacación (G)'],           ['Cta_Per_Maternidad', 'Per. de Maternidad (P)']],
        [['Cta_Horas_Ext', 'Horas Extras (G)'],             ['Cta_Ext_Conyugue_P', 'Ext. de Cónyuge (P)']],
        [['Cta_Quincena', 'Quincena (A-CxC)'],              ['Cta_IESS_Personal', 'Aporte Personal (P)']],
    ],
    [
        [['Cta_Aporte_Patronal_G', 'Aporte Patronal (G)'],  ['Cta_IESS_Patronal', 'Aporte Patronal (P)']],
        [['Cta_Decimo_Tercer_G', 'Décimo Tercero (G)'],     ['Cta_Decimo_Tercer_P', 'Décimo Tercero (P)']],
        [['Cta_Decimo_Cuarto_G', 'Décimo Cuarto (G)'],      ['Cta_Decimo_Cuarto_P', 'Décimo Cuarto (P)']],
        [['Cta_Fondo_Reserva_G', 'Fondo de Reserva (G)'],   ['Cta_Fondo_Reserva_P', 'Fondo de Reserva (P)']],
        [['Cta_Vacaciones_G', 'Prov. Vacaciones (G)'],      ['Cta_Vacaciones_P', 'Prov. Vacaciones (P)']],
    ],
    [
        [['Cta_Per_Efermedad', 'Per. de Enfermedad'],       ['Cta_Diferencia', 'Horas no Trabajadas']],
    ],
];

function campoCuenta($id, $etiqueta, $formatoCta){
    return '<div class="input-group input-group-sm">
        <span class="input-group-text bg-person-sky-blue hp-cta-label">'.htmlspecialchars($etiqueta).'</span>
        <input type="text" class="form-control cta-input" id="'.$id.'" placeholder="'.htmlspecialchars($formatoCta).'"
            onkeyup="if(event.keyCode!=46 && event.keyCode!=8){ validar_cuenta(this); }">
    </div>';
}
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
    .hp-cta-label {
        width: 185px;
        min-width: 185px;
        font-weight: 700;
        font-size: .78rem;
        text-align: left;
    }
    .hp-col-title {
        text-align: center;
        font-weight: 700;
        letter-spacing: .04em;
        color: #fff;
        border-radius: .4rem;
        padding: .3rem 0;
    }
    .hp-bloque + .hp-bloque {
        border-top: 1px dashed #d5dbe1;
        margin-top: .6rem;
        padding-top: .6rem;
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
    <div class="card-body pb-0">
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <label class="form-label fw-bold mb-1">Grupo de Rol Pago</label>
                <select class="form-select" id="cmb_grupo" style="width:100%">
                </select>
            </div>
            <div class="col-12 col-lg-6 d-flex align-items-end">
                <span class="hp-info-chip"><i class="bx bx-info-circle text-primary"></i> Seleccione un grupo existente o escriba uno nuevo y presione Enter</span>
            </div>
        </div>
    </div>
</div>

<div class="mb-3" style="margin-top: 1px;">
    <div class="card">
        <div class="card-body pb-2" style="padding-top: 1px;">
            <div class="row g-2 mb-2">
                <div class="col-12 col-md-6"><div class="hp-col-title bg-primary">GASTOS (G)</div></div>
                <div class="col-12 col-md-6"><div class="hp-col-title bg-secondary">PASIVO / PROVISIÓN (P)</div></div>
            </div>
            <?php foreach ($bloques as $filas) { ?>
                <div class="hp-bloque">
                    <?php foreach ($filas as $par) { ?>
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-md-6"><?php echo campoCuenta($par[0][0], $par[0][1], $formatoCta); ?></div>
                            <div class="col-12 col-md-6"><?php echo campoCuenta($par[1][0], $par[1][1], $formatoCta); ?></div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
