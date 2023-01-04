<script>
</script>
<div class="col-sm-12">
    <div class="box box-default color-palette-box">
        <form class="form-horizontal">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Digite Código del Usuario</h3>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>Código:</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="TxtCodigo" id="TxtCodigo" class="form-control input-xs"
                                        value="">
                                </div>
                                <div class="col-sm-6">
                                    <label hidden="hidden" readonly style="color: red;">Con servicio y medidor</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>Usuario:</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="TxtUsuario" id="TxtUsuario" class="form-control input-xs"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label hidden="hidden" readonly style="color: red;">Ingresando lectura hasta
                            Octubre/2022==>(7440)</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>Año:</label>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="TxtAnio" id="TxtAnio" class="form-control input-xs" value="">
                    </div>
                    <div class="col-sm-2">
                        <label>Mes:</label>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="TxtMes" id="TxtMes" class="form-control input-xs" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="box">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadio" id="RbMMC" value="option1" checked="">
                                    Menos de 10.000 metros cúbicos
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadio" id="RbMC" value="option2">
                                    Más de 10.000 metros cúbicos
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" type="ckMV0">
                                Medidor vuelve a 0
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label hidden="hidden" readonly style="color: red;">Ingrese Lectura de Noviembre/2022</label>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="Txtm3" id="Txtm3" class="form-control input-xs" value="">
                    </div>
                    <div class="col-sm-1">
                        <label>m3</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label hidden="hidden" readonly style="color: red;">Promedio de Consumo: 67</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label hidden="hidden" readonly style="color: red;">Consumo: 20 (47 Bajo el Promedio)</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1">
                        <label>Multas:</label>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="TxtMultas" id="TxtMultas" class="form-control input-xs" value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-app">
                        <i class="glyphicon glyphicon-floppy-disk"></i> Guardar
                    </button>

                    <button type="submit" class="btn btn-app">
                        <i class="glyphicon glyphicon-remove"></i> Cancelar
                    </button>

                    <button type="submit" class="btn btn-app">
                        <i class="glyphicon glyphicon-log-out"></i> Salir
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>