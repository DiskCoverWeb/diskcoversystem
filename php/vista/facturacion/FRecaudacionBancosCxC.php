<style type="text/css">
    .miPanel {
        background-color: #5bc0de;
    }
</style>

<div>
    <div class="row" style="margin:5px; padding-top:10px">
        <div class="col-sm-2 col-xs-12">
            <div class="col">
                <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
                    <img src="../../img/png/salire.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" title="Visualizar" class="btn btn-default" onclick="Visualizar()">
                    <img src="../../img/png/visual.png" width="25" height="30">
                </a>
            </div>

            <div class="col">
                <a href="javascript:void(0)" id="Enviar Rubros" title="Enviar Rubros" class="btn btn-default"
                    onclick="Enviar()">
                    <img src="../../img/png/enviarRubros.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="Recibir Rubos" title="Recibir Rubos" class="btn btn-default"
                    onclick="Recibir()">
                    <img src="../../img/png/recibirRubros.png" width="25" height="30">
                </a>
            </div>
        </div>

        <div class="col-sm-10 col-xs-12">
            <div class="col-sm-4">
                <label for="DCEntidad">ENTIDAD FINANCIERA</label>
                <select class="form-control input-xs" name="DCEntidad" id="DCEntidad" onchange="DCEntidad">
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <label for="CheqRangos" class="col control-label">
                        <input type="checkbox" name="CheqRangos" id="CheqRangos"> Procesar por Rangos Grupos
                    </label>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <select class="form-control input-xs" name="DCGrupoI" id="DCGrupoI" style="display:none">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <select class="form-control input-xs" name="DCGrupoF" id="DCGrupoF" style="display:none">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-1">
                <label>ORDEN No.</label>
                <input type="text" name="TxtOrden" id="TxtOrden" placeholder="0" size="4">
            </div>
            <div class="col-sm-4">
                <label for="DCBanco">CUENTA A LA QUE SE VA ACREDITAR LOS ABONOS</label>
                <select class="form-control input-xs" name="DCBanco" id="DCBanco" onchange="DCBanco">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
    </div>


    <div class="row" style="margin:5px; padding-top:10px">
        <div id="miPanel">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-4">
                        <img id="miLogo" src="" class="img-fluid" width="80%" height="10%">
                    </div>
                    <div class="col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="MBFechaI" class="control-label">Facturación</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="date" name="MBFechaI" id="MBFechaI"
                                    class="form-control input-xs validateDate" onchange="" title="Facturación"
                                    value="<?php echo date('Y-m-d') ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="CheqMatricula" class="col control-label">
                                    <input type="checkbox" name="CheqMatricula" id="CheqMatricula"> Generar Matrícula
                                </label>
                            </div>
                            <div class="col-xs-1">
                                <button class="btn"
                                    onclick="window.location.href='./inicio.php?mod=<?php echo @$_GET['mod']; ?>'">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                            </div>
                            <div class="col-xs-6">
                                <label for="CheqPend" class="control-label">
                                    <input type="checkbox" name="CheqPend" id="CheqPend"> Sin Deuda Pendiente
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="MBFechaF" class="control-label">Tope de pago</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="date" name="MBFechaF" id="MBFechaF"
                                    class="form-control input-xs validateDate" onchange="" title="Tope de pago"
                                    value="<?php echo date('Y-m-d') ?>">
                            </div>
                            <div class="col-xs-6 cheqSatMostrar" style="display:none">
                                <label for="CheqSat" class="control-label">
                                    <input type="checkbox" name="CheqSat" id="CheqSat"> Generar Matrícula
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row" style="margin:20px;">
                    <textarea class="form-control" id="TxtFile" rows="10"></textarea>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#MBFechaI').blur(function () {
            let fechaI = $(this).val();
            FechaValida(fechaI);
        });

        $('#MBFechaF').blur(function () {
            let fechaF = $(this).val();
            FechaValida(fechaF);
        });

        //$NuevoCompe = false
        //$CopiarComp = false
        //$Co.CodigoB = ""
        //$Co.Numero = 0

        Select('#DCGrupoI');
        Select('#DCGrupoF', true); // true indica que se debe invertir el orden

        AdoAux();
        AdoProducto();
        Select_DCEntidad();
        Select_DCBanco();
        Select_Banco();

        $('#CheqRangos').click(function () {
            $("#DCGrupoI, #DCGrupoF").toggle($(this).is(":checked") ? true : false);
        });

        var Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        var Costo_Banco = Leer_Campo_Empresa("Costo_Bancario");
        var Cta_Bancaria = Leer_Campo_Empresa("Cta_Banco");
        var Cta_Gasto_Banco = Leer_Seteos_Ctas("Cta_Gasto_Bancario");

    });

    function mostrarCheqSat() {
        $('.cheqSatMostrar').css('display', function (index, value) {
            return value === 'none' ? 'block' : 'none';
        });
    }

    function Select_Banco() {

        $("#miPanel").css("background-color", "#FFFFF1");
        //$("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");

        var selectElement = $('#DCEntidad');
        selectElement.change(function () {

            console.log('Valor: ' + selectElement.val());
            var index = selectElement.val();

            switch (index) {
                case 'BANCO PICHINCHA':
                    $("#miPanel").css("background-color", "#80FFFF");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPichincha.png");
                    break;
                case 'BANCO BOLIVARIANO':
                    $("#miPanel").css("background-color", "#808000");
                    $("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");
                    mostrarCheqSat();
                    break;
                case 'BANCO GENERAL RUMINAHUI':
                    $("#miPanel").css("background-color", "#800004");
                    $("#miLogo").attr("src", "../../img/png/logoBancoGnrlRuminahui.png");
                    break;
                case 'BANCO INTERNACIONAL':
                    $("#miPanel").css("background-color", "#FF8080");
                    //$("#miLogo").attr("src", "../../img/png/logoBancoInternacional.png");
                    break;
                case 'BANCO DEL PACIFICO':
                    $("#miPanel").css("background-color", "#C0C000");
                    $("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    mostrarCheqSat();
                    break;
                case 'BANCO DEL PACIFICO INTERMATICO':
                    $("#miPanel").css("background-color", "#C0C000");
                    //$("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    mostrarCheqSat();
                    break;
                case 'BIZBANCKPACIFICO':
                    $("#miPanel").css("background-color", "#C0C000");
                    //$("#miLogo").attr("src", "../../img/png/logoBancoPacifico.png");
                    mostrarCheqSat();
                    break;
                case 'BANCO PRODUBANCO':
                    $("#miPanel").css("background-color", "#FFFFFF");
                    $("#miLogo").attr("src", "../../img/png/logoBancoProdubanco.png");
                    break;
                case 'BANCO GUAYAQUIL':
                    $("#miPanel").css("background-color", "#FF8080");
                    $("#miLogo").attr("src", "../../img/png/logoBancoGuayaquil.png");
                    break;
                case 'COOPERATIVA JEP':
                    $("#miPanel").css("background-color", "#80FF80");
                    //$("#miLogo").attr("src", "../../img/png/logoBancoGuayaquil.png");
                    break;
                case 'CACPE':
                    $("#miPanel").css("background-color", "#80FF80");
                    //$("#miLogo").attr("src", "../../img/png/logoBancoGuayaquil.png");
                    break;
                default:
                    $("#miPanel").css("background-color", "#FFFFFF");
                    //$("#miLogo").attr("src", "../../img/png/logoBancoGuayaquil.png");
                    break;
            }
        });
    }

    function Select_DCEntidad() {
        $('#DCEntidad').select2({
            placeholder: 'Entidad Financiera',
            ajax: {
                url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCEntidad=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    //console.log('DATA DCENTIDAD ', data);
                    return {
                        results: data.map(item => ({
                            id: item.Descripcion,
                            text: item.Descripcion
                        }))
                    };
                },
            }
        });
    }

    function Select(selector, reverseOrder = false) {
        $(selector).select2({
            placeholder: 'Seleccione',
            ajax: {
                url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCGrupoI_DCGrupoF=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    var AdoGrupo = data;
                    if (reverseOrder) {
                        data.reverse();
                    }
                    return {
                        results: data.map(item => ({
                            id: item.Grupo,
                            text: item.Grupo
                        }))
                    };
                },
            }
        });
    }

    function Select_DCBanco() {
        $('#DCBanco').select2({
            placeholder: 'Seleccione',
            ajax: {
                url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCBanco=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    /*var Cta_Banco = null;
                    var Cta_Del_Banco = // el valor se llena en el boton Recibir_Abonos;

                    var bancoEncontrado = data.find(function (item) {
                        return Cta_Del_Banco === item.Codigo;
                    });

                    if (bancoEncontrado) {
                        Cta_Banco = bancoEncontrado.NomCuenta;
                    } else {
                        Swal.fire({
                            title: 'No existen cuentas asignadas',
                            text: 'No existen cuentas asignadas o no están bien establecidas las cuentas contables',
                            type: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                    if (data.length === 0) {
                        Swal.fire({
                            title: 'No existen cuentas asignadas',
                            text: 'No existen cuentas asignadas o no están bien establecidas las cuentas contables',
                            type: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }*/

                    return {
                        results: data.map(item => ({
                            id: item.NomCuenta,
                            text: item.NomCuenta
                        }))
                    };
                },
            }
        });
    }


    function FechaValida(fecha) {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?FechaValida=true',
            dataType: 'json',
            data: { 'fecha': fecha },
            success: function (datos) {
                if (datos.ErrorFecha) {
                    Swal.fire({
                        type: 'warning',
                        title: datos.MsgBox,
                        text: fecha
                    });
                }
            }
        });
    }

    function Leer_Campo_Empresa(campo) {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?LeerCampoEmpresa=true',
            dataType: 'json',
            data: { 'campo': campo },
            success: function (datos) {
                //console.log(datos);
            }
        });
    }

    function AdoAux() {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?AdoAux=true',
            dataType: 'json',
            success: function (datos) {
                //console.log(datos);
            }
        });
    }

    function AdoProducto() {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?AdoProducto=true',
            dataType: 'json',
            success: function (datos) {
                //console.log(datos);
            }
        });
    }
</script>