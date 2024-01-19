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
                <a href="javascript:void(0)" id="EnviarRubros" title="Enviar Rubros" class="btn btn-default"
                    onclick="Enviar_Rubros()">
                    <img src="../../img/png/enviarRubros.png" width="25" height="30">
                </a>
            </div>
            <div class="col">
                <a href="javascript:void(0)" id="btnRecibirAbonos" title="Recibir Abonos" class="btn btn-default">
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
                        <img id="miLogo" src="" class="img-fluid" width="75%" height="20%">
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
    <div class="modal" id="modalSubirArchivo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">RECIBIR RUBROS</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input class="form-control" type="file" id="fileInput" accept=".txt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnSubirArchivo">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </div>
        </div>
    </div>


</div>

<script type="text/javascript">

    $(document).ready(function () {

        $('#MBFechaI').blur(function () {
            let fechaI = $(this).val();
            fechaI = FechaValida(fechaI);
        });

        $('#MBFechaF').blur(function () {
            let fechaF = $(this).val();
            let FechaValidares = FechaValida(fechaF, true); //true indica realizar MBFechaF_LostFocus
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
        Case_Banco();

        $('#CheqRangos').click(function () {
            $("#DCGrupoI, #DCGrupoF").toggle($(this).is(":checked") ? true : false);
        });

        //Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        //Costo_Banco = Leer_Campo_Empresa("Costo_Bancario");
        //Cta_Bancaria = Leer_Campo_Empresa("Cta_Banco");
        //Cta_Gasto_Banco = Leer_Seteos_Ctas("Cta_Gasto_Bancario");

        //var CheqMatricula = $("#CheqMatricula").prop("checked");

    });

    function mostrarCheqSat() {
        $('.cheqSatMostrar').css('display', function (index, value) {
            return value === 'none' ? 'block' : 'none';
        });
    }

    function Case_Banco() {

        $("#miPanel").css("background-color", "rgb(255,255,255)");
        //$("#miLogo").attr("src", "../../img/png/logoBancoBolivariano.png");

        var selectElement = $('#DCEntidad');
        selectElement.change(function () {

            console.log('Valor: ' + selectElement.val());
            var index = selectElement.val();

            switch (index) {
                case 'PICHINCHA':
                    $("#miPanel").css("background-color", " #FFFF80");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoPichinchaLogo.png");
                    $("#imgBanco").attr("alt", "Logo Banco Pichincha");
                    break;
                case 'BOLIVARIANO':
                    $("#miPanel").css("background-color", "#008080");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoBolivarianoLogo.png");
                    $("#imgBanco").attr("alt", "Logo Banco Bolivariano");
                    mostrarCheqSat();
                    break;
                case 'BGR_EC':
                    $("#miPanel").css("background-color", "#800004");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BGR.png");
                    break;
                case 'INTERNACIONAL':
                    $("#miPanel").css("background-color", "#f3a446");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoInternacionalLogo.png");
                    $("#imgBanco").attr("alt", "Logo Banco Internacional");
                    break;
                case 'PACIFICO':
                    $("#miPanel").css("background-color", "#C0C0FF");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoPacificoLogo.png");
                    $("#imgBanco").attr("alt", "Logo Banco Pacifico");
                    mostrarCheqSat();
                    break;
                case 'INTERMATICO':
                    $("#miPanel").css("background-color", "#C0C0FF");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/Intermatico.png");
                    mostrarCheqSat();
                    break;
                case 'BIZBANCKPACIFICO':
                    $("#miPanel").css("background-color", "#C0C000");
                    //$("#miLogo").attr("src", "../../img/png/ima.png");
                    mostrarCheqSat();
                    break;
                case 'PRODUBANCO':
                    $("#miPanel").css("background-color", "#FFFFFF");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/ProduBancoLogo.png");
                    $("#imgBanco").attr("alt", "Logo Produbanco");
                    break;
                case 'GUAYAQUIL':
                    $("#miPanel").css("background-color", "#f30582");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/BancoGuayaquilLogo.png");
                    $("#imgBanco").attr("alt", "Logo Banco Guayaquil");
                    break;
                case 'COOPJEP':
                    $("#miPanel").css("background-color", "#80FF80");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/CoopJEPLogo.png");
                    $("#imgBanco").attr("alt", "Logo Cooperativa JEP");
                    break;
                case 'CACPE':
                    $("#miPanel").css("background-color", "#80FF80");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/CACPE.png");
                    break;
                default:
                    $("#miPanel").css("background-color", "rgb(255,255,255)");
                    $("#miLogo").attr("src", "../../img/png/FRecaudacionBancosPreFa/LogosBancos/OtrosBancosLogo.png");
                    $("#imgBanco").attr("alt", "Logo Otros Bancos");
                    break;

            }
        });
    }

    function Select_DCEntidad() {
        var $DCEntidad = $('#DCEntidad');
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCEntidad=true',
            dataType: 'json',
            success: function (data) {
                $DCEntidad.empty();
                data.forEach(function (item) {
                    $DCEntidad.append('<option value="' + item.Abreviado + '">' + item.Descripcion + '</option>');
                });
            }
        });
    }

    function Select(selector, reverseOrder = false) {
        var $selector = $(selector);
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCGrupoI_DCGrupoF=true',
            dataType: 'json',
            success: function (data) {
                $selector.empty();
                if (reverseOrder) {
                    data.reverse();
                }
                data.forEach(function (item) {
                    $selector.append('<option value="' + item.Grupo + '">' + item.Grupo + '</option>');
                });
            }
        });
    }

    function Select_DCBanco() {
        var $DCBanco = $('#DCBanco');
        $.ajax({
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?DCBanco=true',
            dataType: 'json',
            success: function (data) {
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

                $DCBanco.empty(); // Limpia el select antes de agregar nuevas opciones
                data.forEach(function (item) {
                    $DCBanco.append('<option value="' + item.NomCuenta + '">' + item.NomCuenta + '</option>');
                });
            }
        });
    }
    function FechaValida(fecha, MBFecha = false) {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?FechaValida=true',
            dataType: 'json',
            data: { 'fecha': fecha },
            success: function (datos) {
                console.log(datos, MBFecha, 'fecha:' + fecha);
                if (datos.ErrorFecha) {
                    Swal.fire({
                        type: 'warning',
                        title: datos.MsgBox,
                        text: fecha
                    });
                }
                else if (MBFecha) {
                    MBFechaF_LostFocus(fecha);
                }
            }
        });
    }

    function MBFechaF_LostFocus(fecha) {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?MBFechaFLostFocus=true',
            dataType: 'json',
            data: { 'fecha': fecha },
            success: function (datos) {
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
                //console.log('dato', datos);
            }
        });
    }

    function Leer_Seteos_Ctas(campo) {
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?LeerSeteosCtas=true',
            dataType: 'json',
            data: { 'campo': campo },
            success: function (datos) {
                console.log('dato seteo', datos);
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

    function Enviar_Rubros() {

        var DCEntidad = $('#DCEntidad').val();
        var DCGrupoI = $('#DCGrupoI').val();
        var DCGrupoF = $('#DCGrupoF').val();
        var TxtOrden = $('#TxtOrden').val();
        var DCBanco = $('#DCBanco').val();

        var MBFechaI = $('#MBFechaI').val();
        var MBFechaF = $('#MBFechaF').val();

        var CheqRangos = $('#CheqRangos').prop('checked');
        var CheqMatricula = $('#CheqMatricula').prop('checked');
        var CheqPend = $('#CheqPend').prop('checked');
        var CheqSat = $('#CheqSat').prop('checked');

        var parametros = {
            'DCEntidad': DCEntidad,
            'MBFechaI': MBFechaI,
            'MBFechaF': MBFechaF,
            'CheqMatricula': CheqMatricula,
            'DCBanco': DCBanco,
            'DCGrupoI': DCGrupoI,
            'DCGrupoF': DCGrupoF,
            'CheqRangos': CheqRangos,
        };
        console.log(parametros);

        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?EnviarRubros=true',
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                console.log('respuesta', data.textoBanco);
                if (data.res == 'Ok') {
                    switch (data.textoBanco) {
                        case "PICHINCHA":
                            Swal.fire({
                                title: 'SE GENERARON LOS SIGUIENTES ARCHIVOS:',
                                type: 'success',
                                html: data.mensaje,
                                confirmButtonText: 'Aceptar'
                            });

                            var url = "../../TEMP/BANCO/FACTURAS/" + data.Nombre1;
                            var url2 = "../../TEMP/BANCO/FACTURAS/" + data.Nombre2;

                            var enlaceTemporal = $('<a></a>')
                                .attr('href', url)
                                .attr('download', data.Nombre1)
                                .appendTo('body');

                            enlaceTemporal[0].click();
                            enlaceTemporal.remove();

                            var enlaceTemporal2 = $('<a></a>')
                                .attr('href', url2)
                                .attr('download', data.Nombre2)
                                .appendTo('body');

                            enlaceTemporal2[0].click();
                            enlaceTemporal2.remove();
                            break;
                        /*
                    case "BGR_EC":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_BGR_EC();
                        break;
                    case "INTERNACIONAL":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_Internacional();
                        break;
                    case "BOLIVARIANO":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_Bolivariano();
                        break;
                    case "PACIFICO":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_Pacifico();
                        break;
                    case "PRODUBANCO":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_Produbanco();
                        break;
                    case "GUAYAQUIL":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_Guayaquil();
                        break;
                    case "COOPJEP":
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        //Generar_Coop_Jep();
                        break;
                    default:
                        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                        echo "No está definido este Banco";
                        break;   */
                    }
                }

            }
        });
    }

    $('#btnRecibirAbonos').click(function () {
        $('#modalSubirArchivo').modal('show');
    });

    $('#btnSubirArchivo').click(function () {
        $('#modalSubirArchivo').modal('hide');
        Recibir_Abonos();
    });


    function Recibir_Abonos() {
        var MBFechaI = $('#MBFechaI').val();
        var MBFechaF = $('#MBFechaF').val();
        var TxtOrden = $('#TxtOrden').val();
        var DCEntidad = $('#DCEntidad').val();
        var fileInput = $('#fileInput')[0];
        var archivo = fileInput.files[0];

        var formData = new FormData();
        formData.append('MBFechaI', MBFechaI);
        formData.append('MBFechaF', MBFechaF);
        formData.append('TxtOrden', TxtOrden);
        formData.append('DCEntidad', DCEntidad);

        if (archivo) {
            formData.append('archivoBanco', archivo, archivo.name);
        }

        $.ajax({
            type: 'post',
            url: '../controlador/facturacion/FRecaudacionBancosCxCC.php?RecibirAbonos=true',
            processData: false,
            contentType: false,
            data: formData,
            success: function (data) {
                var datos = JSON.parse(data);
                console.log('resultado', datos);
                if (datos.res == 'Error') {
                    Swal.fire({
                        title: datos.mensaje,
                        type: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud: " + textStatus, errorThrown);
                //$('#myModal_espera').hide();
            }
        });
    }





</script>