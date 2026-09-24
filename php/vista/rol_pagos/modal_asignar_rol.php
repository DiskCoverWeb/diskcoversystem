<?php
// Modal "Asignar a Rol de Pago" (equivalente web de FRolPag.frm). Se incluye en registro_empleados.php.
function rpCampo($id, $etiqueta, $tipo = 'text', $extra = '', $claseInput = ''){
    return '<div class="input-group input-group-sm">
        <span class="input-group-text bg-person-sky-blue hp-rp-lbl">'.$etiqueta.'</span>
        <input type="'.$tipo.'" class="form-control '.$claseInput.'" id="'.$id.'" '.$extra.'>
    </div>';
}
function rpCheck($id, $etiqueta){
    return '<div class="form-check form-check-inline mb-0">
        <input class="form-check-input" type="checkbox" id="'.$id.'">
        <label class="form-check-label fw-bold" for="'.$id.'" style="font-size:.78rem">'.$etiqueta.'</label>
    </div>';
}
function rpCuenta($id, $etiqueta){
    return '<div class="input-group input-group-sm mb-1">
        <span class="input-group-text bg-person-sky-blue hp-rp-lbl">'.$etiqueta.'</span>
        <input type="text" class="form-control" id="cta_'.$id.'" readonly>
    </div>';
}
$cuentasCol = [
    [['Cta_Sueldo','Sueldo Normal (G)'], ['Cta_IESS_Personal','Aporte Personal (P)'], ['Cta_IESS_Patronal','Aporte Patronal (P)'],
     ['Cta_Aporte_Patronal_G','Aporte Patronal (G)'], ['Cta_Decimo_Tercer_G','Décimo Tercero (G)'], ['Cta_Decimo_Tercer_P','Décimo Tercero (P)'],
     ['Cta_Per_Maternidad','Per. de Maternidad']],
    [['Cta_Vacacion','Sueldo Vacación (G)'], ['Cta_Quincena','Quincena (A-CxC)'], ['Cta_Vacaciones_P','Prov. Vacaciones (P)'],
     ['Cta_Vacaciones_G','Prov. Vacaciones (G)'], ['Cta_Decimo_Cuarto_G','Décimo Cuarto (G)'], ['Cta_Decimo_Cuarto_P','Décimo Cuarto (P)'],
     ['Cta_Per_Efermedad','Per. de Enfermedad']],
    [['Cta_Horas_Ext','Horas Extras (G)'], ['Cta_Antiguedad','Antigüedad'], ['Cta_Fondo_Reserva_P','Fondo de Reserva (P)'],
     ['Cta_Fondo_Reserva_G','Fondo de Reserva (G)'], ['Cta_Diferencia','Horas no Trabajadas'], ['Cta_Ext_Conyugue_P','Ext. de Cónyuge (P)']],
];
$gastos = [
    ['rp_vivienda','(-) Vivienda'], ['rp_salud','(-) Salud'], ['rp_educacion','(-) Educación, Arte y Cultura'],
    ['rp_alimentacion','(-) Alimentación'], ['rp_vestimenta','(-) Vestimenta'], ['rp_turismo','(-) Turismo'],
    ['rp_discapacidad','(-) Discapacitados'], ['rp_tercera_edad','(-) Tercera Edad'],
];
?>
<style>
    #modal_asignar_rol .bg-person-sky-blue { background-color: #CFE9EF; color: #444; border-color: #ddd; }
    #modal_asignar_rol .hp-info-chip { font-size: .78rem; background: #f4f7f9; border: 1px solid #e3e8ec; border-radius: .4rem; padding: .35rem .6rem; }
    .hp-rp-lbl { width: 165px; min-width: 165px; font-weight: 700; font-size: .78rem; text-align: left; }
    #modal_asignar_rol .modal-body { background: #f7f9fb; }
    #modal_asignar_rol .hp-tabs { border-bottom: none; background: #eef2f6; border-radius: .5rem; padding: 4px; gap: 4px; }
    #modal_asignar_rol .hp-tabs .nav-link { border: none; border-radius: .4rem; color: #6c757d; font-weight: 600; padding: .4rem 1rem; }
    #modal_asignar_rol .hp-tabs .nav-link.active { background: #fff; color: var(--bs-primary); box-shadow: 0 1px 3px rgba(0,0,0,.12); }
    #modal_asignar_rol .hp-rp-head { background: #fff; border: 1px solid #e3e8ec; border-radius: .5rem; padding: .5rem .8rem; }
</style>

<div class="modal fade" id="modal_asignar_rol" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary py-2">
                <h5 class="modal-title text-white"><i class="bx bx-group me-1"></i>ASIGNACIÓN A ROL DE PAGOS</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cancelarAsignarRol()"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="hp-rp-head d-flex justify-content-between flex-wrap gap-2 mb-2">
                    <b id="rp_nombre_lbl">-</b>
                    <span class="hp-info-chip"><i class="bx bx-id-card text-primary"></i> Código: <b id="rp_codigo_lbl">-</b> <span id="rp_estado_lbl" class="badge bg-success ms-1">Activo</span></span>
                </div>

                <!-- Datos generales (misma distribución que FRolPag.frm) -->
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl">Salario Neto?</span>
                                    <div class="form-control d-flex gap-3 align-items-center">
                                        <div class="form-check mb-0"><input class="form-check-input" type="radio" name="rp_sn" id="rp_sn_si" value="2"><label class="form-check-label" for="rp_sn_si">Si</label></div>
                                        <div class="form-check mb-0"><input class="form-check-input" type="radio" name="rp_sn" id="rp_sn_no" value="1" checked><label class="form-check-label" for="rp_sn_no">No</label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_fecha', 'Fecha Ingreso', 'date', 'min="1950-01-01" max="2200-12-31"'); ?></div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_salario', 'Ingreso Líquido', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_horas', 'Horas por Semana', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_valor_hora', 'Valor por Hora', 'number', 'step="0.00001" placeholder="0.00000"'); ?></div>
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl">Grupo del Rol</span>
                                    <select class="form-select" id="rp_grupo" style="width:100%"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_usuario', 'Nombre Corto', 'text', 'maxlength="10"'); ?></div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_tarjeta', 'Pase la Tarjeta', 'text', 'autocomplete="off"'); ?></div>
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl">Mes Vacación</span>
                                    <input type="text" class="form-control" id="rp_mes_vac" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_clave', 'Clave de Registro', 'text', 'maxlength="8" autocomplete="new-password"'); ?></div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_porc_com', 'Comisión Fact. %', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_porc_per', 'Aporte Personal %', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl" style="width:110px;min-width:110px" title="Sección del Plantel Educativo">Plantel</span>
                                    <div class="form-control d-flex gap-0 align-items-center px-1">
                                        <?php echo rpCheck('rp_cheq_p', 'Inicial'), rpCheck('rp_cheq_s', 'Básico'), rpCheck('rp_cheq_b', 'Bach.'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl"><?php echo rpCheck('rp_chk_salida', 'Fecha de Salida'); ?></span>
                                    <input type="date" class="form-control" id="rp_fecha_c" disabled>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4"><?php echo rpCampo('rp_porc_pat', 'Aporte Patronal %', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl"><?php echo rpCheck('rp_chk_parcial', 'Tiempo parcial'); ?></span>
                                    <span class="form-control text-muted" style="font-size:.78rem">Salario Tiempo parcial</span>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl"><?php echo rpCheck('rp_chk_maternidad', 'Maternidad'); ?></span>
                                    <input type="date" class="form-control" id="rp_fecha_m" disabled>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl"><?php echo rpCheck('rp_chk_extc', 'Ext. Cónyuge %'); ?></span>
                                    <input type="number" step="0.01" class="form-control" id="rp_extc" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl"><?php echo rpCheck('rp_chk_guardian', 'Guardianía'); ?></span>
                                    <span class="form-control text-muted" style="font-size:.78rem">Tipo de Empleado Guardianía</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestañas -->
                <div class="card">
                    <div class="card-body py-2">
                        <ul class="nav nav-tabs hp-tabs w-100">
                            <li class="nav-item flex-fill text-center"><a class="nav-link active" href="#rp_tab_cuentas" data-bs-toggle="tab">Seteos de Cuentas</a></li>
                            <li class="nav-item flex-fill text-center"><a class="nav-link" href="#rp_tab_seguro" data-bs-toggle="tab">Seguro Social y Décimos</a></li>
                            <li class="nav-item flex-fill text-center"><a class="nav-link" href="#rp_tab_pago" data-bs-toggle="tab">Forma de Pago</a></li>
                            <li class="nav-item flex-fill text-center"><a class="nav-link" href="#rp_tab_gastos" data-bs-toggle="tab">Gastos Personales</a></li>
                        </ul>
                        <div class="tab-content mt-2">

                            <!-- Seteos de Cuentas (solo lectura: se toman del Grupo de Rol) -->
                            <div class="tab-pane fade show active" id="rp_tab_cuentas">
                                <div class="row g-2">
                                    <?php foreach ($cuentasCol as $col) { ?>
                                        <div class="col-12 col-lg-4">
                                            <?php foreach ($col as $c) { echo rpCuenta($c[0], $c[1]); } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="row g-2 mt-1">
                                    <div class="col-12">
                                        <div class="input-group input-group-sm flex-nowrap">
                                            <span class="input-group-text bg-person-sky-blue hp-rp-lbl">Sub-Cuenta</span>
                                            <select class="form-select" id="rp_submodulo" style="width:100%"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-4 mt-2">
                                    <?php echo rpCheck('rp_chk_rfr', 'REINGRESO DE FONDOS DE RESERVA'), rpCheck('rp_chk_fondo', 'PAGAR FONDO DE RESERVA EN ROL DE PAGOS'), rpCheck('rp_chk_decimos', 'PAGAR DÉCIMOS EN ROL DE PAGOS'); ?>
                                </div>
                            </div>

                            <!-- Seguro Social y Décimos -->
                            <div class="tab-pane fade" id="rp_tab_seguro">
                                <div class="row g-2">
                                    <div class="col-12 col-lg-8">
                                        <div class="row g-2 mb-1">
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_fecha_vi', 'Vacación desde', 'date'); ?></div>
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_fecha_vf', 'Vacación hasta', 'date'); ?></div>
                                        </div>
                                        <div class="row g-2 mb-1">
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_cod_profesion', 'Cod. Profesión', 'text', 'maxlength="15" placeholder="Tabla sectoral"'); ?></div>
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_fp_dec', 'F. Pago Décimos', 'text', 'maxlength="1" placeholder="A"'); ?></div>
                                        </div>
                                        <div class="row g-2 mb-1">
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_valor_dec3', 'Valor Décimo 3ro.', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_dias_dec3', 'Días Trabajados', 'number', 'maxlength="3" placeholder="0"'); ?></div>
                                        </div>
                                        <div class="row g-2 mb-1">
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_valor_dec4', 'Valor Décimo 4to.', 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                                            <div class="col-12 col-md-6"><?php echo rpCampo('rp_dias_dec4', 'Días Trabajados', 'number', 'maxlength="3" placeholder="0"'); ?></div>
                                        </div>
                                        <div class="row g-2 mb-1">
                                            <div class="col-12 col-md-8">
                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl" title="Condiciones del trabajador con respecto a discapacidades">Condición Trab.</span>
                                                    <select class="form-select" id="rp_condicion" style="width:100%"></select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4"><?php echo rpCampo('rp_porc_discap', 'Porc. Discap.', 'number', 'min="0" max="100" placeholder="0"'); ?></div>
                                        </div>
                                        <div class="row g-2 mb-1" id="rp_fila_convenio" style="display:none">
                                            <div class="col-12">
                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl" title="Aplica convenio para evitar doble imposición">Aplica Convenio</span>
                                                    <select class="form-select" id="rp_aplica" style="width:100%"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-2" id="rp_fila_sustituye" style="display:none">
                                            <div class="col-12">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl" title="Identificación de la persona con discapacidad a quien sustituye o representa">CI a quien sustituye</span>
                                                    <input type="text" class="form-control" id="rp_ci_sustituye" maxlength="15">
                                                    <span class="input-group-text" id="rp_td_sustituye">C</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="card mb-0">
                                            <div class="card-header py-1"><h6 class="mb-0">DATOS EXTRAS</h6></div>
                                            <div class="card-body py-2">
                                                <div class="mb-1"><?php echo rpCampo('rp_no_seguro', 'Carnet Es Salud', 'text', 'maxlength="15"'); ?></div>
                                                <div class="mb-1"><?php echo rpCampo('rp_cussp', 'C.U.S.S.P.', 'text', 'maxlength="12"'); ?></div>
                                                <div class="mb-1"><?php echo rpCampo('rp_cssp', 'No. C.S.S.P.', 'text', 'maxlength="15"'); ?></div>
                                                <div><?php echo rpCampo('rp_afponp', 'A.F.P. / O.N.P.', 'text', 'maxlength="10"'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Forma de Pago -->
                            <div class="tab-pane fade" id="rp_tab_pago">
                                <div class="btn-group mb-2" role="group">
                                    <input type="radio" class="btn-check" name="rp_fp" id="rp_fp_e" value="E" checked>
                                    <label class="btn btn-outline-primary btn-sm" for="rp_fp_e">Efectivo</label>
                                    <input type="radio" class="btn-check" name="rp_fp" id="rp_fp_t" value="T">
                                    <label class="btn btn-outline-primary btn-sm" for="rp_fp_t">Transferencia</label>
                                    <input type="radio" class="btn-check" name="rp_fp" id="rp_fp_c" value="C">
                                    <label class="btn btn-outline-primary btn-sm" for="rp_fp_c">Cheque</label>
                                    <input type="radio" class="btn-check" name="rp_fp" id="rp_fp_o" value="O">
                                    <label class="btn btn-outline-primary btn-sm" for="rp_fp_o">Otros</label>
                                </div>
                                <div class="input-group input-group-sm flex-nowrap mb-2">
                                    <span class="input-group-text bg-person-sky-blue hp-rp-lbl">Cuenta Forma Pago</span>
                                    <select class="form-select" id="rp_forma_pago" style="width:100%"></select>
                                </div>
                                <div id="rp_bloque_transf" style="display:none">
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-md-6"><?php echo rpCampo('rp_acreditar_ci', 'Acreditar CI Otro', 'text', 'maxlength="10" title="Acreditar CI de otro empleado"'); ?></div>
                                        <div class="col-12 col-md-6"><?php echo rpCampo('rp_cta_abono', 'Cta. Transferencia', 'text', 'maxlength="20"'); ?></div>
                                    </div>
                                    <div class="input-group input-group-sm flex-nowrap">
                                        <span class="input-group-text bg-person-sky-blue hp-rp-lbl">Banco a Acreditar</span>
                                        <select class="form-select" id="rp_banco" style="width:100%"></select>
                                    </div>
                                </div>
                            </div>

                            <!-- Gastos Personales -->
                            <div class="tab-pane fade" id="rp_tab_gastos">
                                <div class="row g-2">
                                    <div class="col-12 col-lg-7">
                                        <?php foreach ($gastos as $g) { ?>
                                            <div class="mb-1"><?php echo rpCampo($g[0], $g[1], 'number', 'step="0.01" placeholder="0.00"'); ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <?php echo rpCampo('rp_cargas', '(-) Nº Cargas Familiares', 'number', 'min="0" placeholder="0"'); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelarAsignarRol()"><i class="bx bx-x"></i> Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="guardarAsignarRol()"><i class="bx bx-check"></i> Aceptar</button>
            </div>
        </div>
    </div>
</div>
