<script src="../../dist/js/kardex_ing.js"></script>

<style type="text/css">
    td,
    th {
        padding: 8px;
    }

    .contenedor-envcorreo {
        background-color: white;
        position: fixed;
        left: 50%;
        transform: translate(-50%, -150%);
        /*outline: 1px solid black;*/
        border-radius: 10px;
        height: 160px;
        width: 420px;
        box-shadow: 2px 2px 10px 1px rgba(0, 0, 0, 0.2);
        transition: 0.5s transform;
        z-index: 100;
    }

    .cont-ec-open {
        transform: translate(-50%, 8%);
    }

    .bg-envcorreo {
        background-color: #009df9;
        border-top-left-radius: inherit;
        border-top-right-radius: inherit;
        height: 65%;
        width: 100%;
    }

    .bg-envcorreo img {
        width: 100%;
        height: 100%;
        object-fit: scale-down;
    }

    .text-envcorreo {
        margin: 5px 10px;
    }

    .success-checkmark {
        transform: scale(70%, 70%);
        width: 80px;
        height: 115px;
        margin: 0 auto;

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #fff;

            &::before {
                top: 3px;
                left: -2px;
                width: 30px;
                transform-origin: 100% 50%;
                border-radius: 100px 0 0 100px;
            }

            &::after {
                top: 0;
                left: 30px;
                width: 60px;
                transform-origin: 0 50%;
                border-radius: 0 100px 100px 0;
                animation: rotate-circle 4.25s ease-in;
            }

            &::before,
            &::after {
                content: '';
                height: 100px;
                position: absolute;
                background: #009df9;
                transform: rotate(-45deg);
            }

            .icon-line {
                height: 5px;
                background-color: #fff;
                display: block;
                border-radius: 2px;
                position: absolute;
                z-index: 10;

                &.line-tip {
                    top: 46px;
                    left: 14px;
                    width: 25px;
                    transform: rotate(45deg);
                    animation: icon-line-tip 0.75s;
                }

                &.line-long {
                    top: 38px;
                    right: 8px;
                    width: 47px;
                    transform: rotate(-45deg);
                    animation: icon-line-long 0.75s;
                }
            }

            .icon-circle {
                top: -4px;
                left: -4px;
                z-index: 10;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                position: absolute;
                box-sizing: content-box;
                border: 4px solid rgba(255, 255, 255, .5);
            }

            .icon-fix {
                top: 8px;
                width: 5px;
                left: 26px;
                z-index: 1;
                height: 85px;
                position: absolute;
                transform: rotate(-45deg);
                background-color: #009df9;
            }
        }
    }

    @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }

        5% {
            transform: rotate(-45deg);
        }

        12% {
            transform: rotate(-405deg);
        }

        100% {
            transform: rotate(-405deg);
        }
    }

    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }

        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }

        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }

        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }

        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
    }

    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }

        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
    }

    .sa {
        transform: scale(70%, 70%);
        width: 100%;
        height: 140px;
        padding: 0;
        /*background-color: #fff;*/
    }

    .sa-error {
        border-radius: 50%;
        border: 4px solid #fff;
        box-sizing: content-box;
        height: 80px;
        padding: 0;
        margin: 0 auto;
        position: relative;
        background-color: #F27474;
        width: 80px;
        animation: animateErrorIcon .5s;
    }

    .sa-error:after,
    .sa-error:before {
        background: #F27474;
        content: '';
        height: 120px;
        position: absolute;
        transform: rotate(45deg);
        width: 60px;
    }

    .sa-error:before {
        border-radius: 40px 0 0 40px;
        width: 26px;
        height: 80px;
        top: -17px;
        left: 5px;
        transform-origin: 60px 60px;
        transform: rotate(-45deg);
    }

    .sa-error:after {
        border-radius: 0 120px 120px 0;
        left: 30px;
        top: -11px;
        transform-origin: 0 60px;
        transform: rotate(-45deg);
        animation: rotatePlaceholder 4.25s ease-in;
    }

    .sa-error-x {
        display: block;
        position: relative;
        z-index: 2;
    }

    .sa-error-placeholder {
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.644);
        box-sizing: content-box;
        height: 80px;
        left: -4px;
        position: absolute;
        top: -4px;
        width: 80px;
        z-index: 2;
    }

    .sa-error-fix {
        background-color: #F27474;
        height: 90px;
        left: 28px;
        position: absolute;
        top: 8px;
        transform: rotate(-45deg);
        width: 5px;
        z-index: 1;
    }

    .sa-error-left,
    .sa-error-right {
        border-radius: 2px;
        display: block;
        height: 5px;
        position: absolute;
        z-index: 2;
        background-color: #fff;
        top: 37px;
        width: 47px;
    }

    .sa-error-left {
        left: 17px;
        transform: rotate(45deg);
        animation: animateXLeft .75s;
    }

    .sa-error-right {
        right: 16px;
        transform: rotate(-45deg);
        animation: animateXRight .75s;
    }

    @keyframes rotatePlaceholder {

        0%,
        5% {
            transform: rotate(-45deg);
        }

        100%,
        12% {
            transform: rotate(-405deg);
        }
    }

    @keyframes animateErrorIcon {
        0% {
            transform: rotateX(100deg);
            opacity: 0;
        }

        100% {
            transform: rotateX(0deg);
            opacity: 1;
        }
    }

    @keyframes animateXLeft {

        0%,
        65% {
            left: 82px;
            top: 95px;
            width: 0;
        }

        84% {
            left: 14px;
            top: 33px;
            width: 47px;
        }

        100% {
            left: 17px;
            top: 37px;
            width: 47px;
        }
    }

    @keyframes animateXRight {

        0%,
        65% {
            right: 82px;
            top: 95px;
            width: 0;
        }

        84% {
            right: 14px;
            top: 33px;
            width: 47px;
        }

        100% {
            right: 16px;
            top: 37px;
            width: 47px;
        }
    }
</style>
<script type="text/javascript">

    let Trans_No = '97';
    let Cod_Inv_Producto = '';
    let OpcDH = 0;
    let Cantidad = 0;
    let SaldoAnterior = 0;
    let Contra_Cta1 = '.';
    let CodigoCliente = '.';
    let Cta_Inventario = '.';
    let Cod_Benef = '.';

    $(document).ready(function () {
        familias();
        contracuenta();
        Trans_Kardex();
        bodega();
        marca();
        DCPorcenIva('MBFechaI', 'DCPorcIVA');
        $('#DCPorcIVA').attr('disabled', true);
        iniciar_asientos();

        //DCBenef_LostFocus
        $('#DCBenef').on('select2:select', function (e) {
            let data = e.params.data;
            let parametros = {
                'CodigoCliente': data.id
            }
            $('#Label3').val(data.CICLIENTE);
            CodigoCliente = data.CICLIENTE;
            $('#TextConcepto').val(data.text);
            Cod_Benef = data.cod_benef;
        });

        //Text_Orden Got Focus
        $('#TextOrden').one('focus', function () {
            stock_actual_inventario();
        });

        $('#TextOrden').attr('disabled', true);

        //TextEntrada_GotFocus
        $('#TextEntrada').on('blur', function () {
            TextEntrada_GotFocus();
        });

        $('#TextTotal').one('focus', function () {
            TextTotal_GotFocus();
        });

        $('#myModal_comprobante').on('show.bs.modal', function (e) {
            $('#titulo-modal').text(`GRABACIÓN DEL COMPROBANTE: ${$('#CLTP').val()}`);
        });

    });

    //Seleccionar comprobante
    function Command3_Click() {
        var numero = parseInt($('#numComprobante').val());
        if(numero < 1){
            swal.fire({
                type: 'error',
                title: 'Error',
                text: 'Debe ingresar un número de comprobante válido'
            });
            return;
        }
        var parametros = {
            'Numero': numero,
            'Trans_No':Trans_No,
            'CLTP': $('#CLTP').val(),
            'MBFechaI': $('#MBFechaI').val(),

        };

        $.ajax({
            data: { 'parametros': parametros },
            url: '../controlador/inventario/registro_esC.php?seleccionar_comprobante=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.res == 1) {
                    //close modal
                    $('#myModal_comprobante').modal('hide');
                    swal.fire({
                        type: 'success',
                        title: '',
                        text: data.msg
                    });
                    $('tbody').empty();
                }else{
                    swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'No se pudo procesar el comprobante' + data.msg
                    });
                }
            }
        });

    }

    function validar_grabacion(){
        if($('tbody').children().length == 2){
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'No se ha ingresado ningún producto'
            });
            return;
        }
        swal.fire({
            title: '¿Está seguro de grabar el comprobante?',
            text: "GRABACIÓN DEL COMPROBANTE",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Grabar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                grabar_comprobante();
            }else{
                return;
            }
        });
    }

    function grabar_comprobante(){
        var parametros = {
            'Trans_No': Trans_No,
            'CodigoCli': CodigoCliente,
            'MBFechaI': $('#MBFechaI').val(),
            'MBVence': $('#MBVence').val(),
            'TextOrden': $('#TextOrden').val(),
            'Factura_No': $('#TxtFactNo').val(),
            'CLTP': $('#CLTP').val(),
            'OpcI': $('#OpcI').prop('checked') ? 1 : 0,
            'OpcE': $('#OpcE').prop('checked') ? 1 : 0,
            'NombreCliente': $('#DCBenef').val(),
            'CheqContraCta': $('#CheqContraCta').prop('checked') ? 1 : 0,
            'TxtDifxDec': $('#TxtDifxDec').val(),
            'DCCtaObra': $('#DCCtaObra').val(),
            'TxtFactNo': $('#TxtFactNo').val(),
            'Cod_Benef': Cod_Benef,
            'TextConcepto': $('#TextConcepto').val(),
        };
        /*
        $.ajax({
            data: { 'parametros': parametros },
            url: '../controlador/inventario/registro_esC.php?grabar_comprobante=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                
            }
        });*/


    }

    function iniciar_asientos() {
        $.ajax({
            url: '../controlador/inventario/registro_esC.php?iniciar_aseinto=true',
            type: 'post',
            dataType: 'json',
            data: { 'Trans_No': Trans_No },
            success: function (data) {
                grid_kardex();
            }
        });
    }

    function toupper(input) {
        input.value = input.value.toUpperCase();
    }

    function TextTotal_GotFocus() {
        let Cod_Bodega = $('#DCBodega').val();
        let Cod_Marca = $('#DCMarca').val();
        let Entrada = $('#TextEntrada').val();
        let ValorUnit = $('#TextVUnit').val();
        let DValorUnit = ValorUnit;
        let Total_Desc = $('#TextDesc').val() / 100;
        DValorUnit = DValorUnit - (DValorUnit * Total_Desc);
        Total_Desc = $('#TextDesc1').val() / 100;
        if (Total_Desc > 0) {
            DValorUnit = DValorUnit - (DValorUnit * Total_Desc);
        }
        let DValorTotal = DValorUnit * Entrada;
        ValorUnit = DValorUnit;
        let ValorTotal = DValorTotal.toFixed(2);
        $('#TextTotal').val(ValorTotal);
    }

    function TexTotal_LostFocus() {
        let FechaTexto = $('#MBFechaI').val();
        let Entrada = parseInt($('#TextEntrada').val());
        let Factura_No = $('#TxtFactNo').val() <= 0 ? 0 : $('#TxtFactNo').val();
        let SubTotal_IVA = 0;
        let ValorTotal = parseFloat($('#TextTotal').val());

        if (Entrada <= 0 || $('#TextVUnit').val() <= 0) {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'Falta de Ingresar la cantidad o el valor unitario'
            });
            return;
        }

        if ($('#OpcIVA').prop('checked')) {
            SubTotal_IVA = $('#TextTotal').val() * ($('#DCPorcenIva').val() / 100);
        }

        let Saldo = 0;
        let Contra_Cta = '.';

        if ($('#OpcI').prop('checked')) {
            Cantidad = Cantidad + Entrada;
            Saldo = SaldoAnterior + ValorTotal
            OpcDH = 1;
            Contra_Cta = $('#DCCtaObra').val();
        } else {
            Cantidad = Cantidad - Entrada;
            Saldo = SaldoAnterior - ValorTotal;
            OpcDH = 2;
            Contra_Cta = $('#CheqContraCta').prop('checked') ? $('#DCCtaObra').val() : Contra_Cta1;
        }


        var parametros = {
            'OpcDH': OpcDH,
            'CodigoInv': $('#LabelCodigo').val(),
            'TextDesc': $('#TextDesc').val(),
            'TextDesc1': $('#TextDesc1').val(),
            'Producto': $('#labelProductro').val(),
            'Entrada': $('#TextEntrada').val(),
            'ValorUnit': $('#TextVUnit').val(),
            'ValorTotal': $('#TextTotal').val(),
            'SubTotal_IVA': SubTotal_IVA,
            'Cta_Inventario': Cta_Inventario,
            'Contra_Cta': Contra_Cta,
            'Cantidad': Cantidad,
            'Saldo': Saldo,
            'UNIDAD': $('#LabelUnidad').val(),
            'Cod_Bodega': $('#DCBodega').val(),
            'Cod_Marca': $('#DCMarca').val(),
            'Trans_No': Trans_No,
            'SubCtaGen': '.',
            'SubCta': $('#SubCta').val(),
            'CodigoCliente': CodigoCliente,
            'TxtCodBar': $('#TxtCodBar').val(),
            'TextOrden': $('#TextOrden').val(),
            'TxtLoteNo': $('#TxtLoteNo').val(),
            'MBFechaFab': $('#MBFechaFab').val(),
            'MBFechaExp': $('#MBFechaExp').val(),
            'TxtRegSanitario': $('#TxtRegSanitario').val(),
            'TxtModelo': $('#TxtModelo').val(),
            'TxtProcedencia': $('#TxtProcedencia').val(),
            'TxtSerieNo': $('#TxtSerieNo').val(),
        };
        $.ajax({
            data: { 'parametros': parametros },
            url: '../controlador/inventario/registro_esC.php?IngresoAsientoK=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.res == 1) {
                    $('#TxtSubTotal').val(data.total.toFixed(2));
                    $('#TxtIVA').val(data.total_iva.toFixed(2));
                    const tmp = data.total + data.total_iva;
                    $('#Label1').val(tmp.toFixed(2));
                    grid_kardex();
                }
            }
        });
    }

    function stock_actual_inventario() {
        var parametros = {
            'Codigo_Inventario': Cod_Inv_Producto,
            'Fecha_Inv': $('#MBFechaI').val()
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?stock_actual_inventario=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.res) {
                    const precio = data.valor_unit;
                    $("#TextVUnit").val(precio.toFixed(2));
                    Cantidad = data.cantidad;
                    SaldoAnterior = data.saldo_anterior;
                }
            }
        });
    }

    function TextVUnit_LostFocus() {
        const valorUnit = $('#TextVUnit').val();
        const entrada = $('#TextEntrada').val();
        const valorTotal = valorUnit * entrada;
        $('#TextTotal').val(valorTotal.toFixed(2));
    }

    function TextEntrada_GotFocus() {
        const OpcI = $('#OpcI').prop('checked');
        const OpcE = $('#OpcE').prop('checked');
        const precio = $('#TextVUnit').val();
        if (OpcI) {
            OpcDH = 1;
        } else {
            OpcDH = 2;
        }
        if (OpcE) {
            if (precio == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: `Falta de Ingresar en este codigo ${$('#LabelCodigo').val()}: La entrada inicial`,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    }

    function grid_kardex() {
        var parametros = {
            'Trans_No': Trans_No
        };
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?grid_kardex=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                if (data.res == 1) {
                    console.log(data);
                    $('#tbl-container').empty();
                    $('#tbl-container').html(data.tabla);
                }
            }
        });
    }

    function habilitar_iva() {
        if ($('#OpcIVA').prop('checked')) {
            $('#DCPorcIVA').attr('disabled', false);
        } else {
            $('#DCPorcIVA').attr('disabled', true);
        }
    }

    function familias() {
        $('#ddl_familia').select2({
            placeholder: 'Seleccione una Familia',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?familias=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    /// console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }
    function producto_famili(familia) {
        var fami = $('#ddl_familia').val();
        $('#ddl_producto').select2({
            placeholder: 'Seleccione producto',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?producto=true&fami=' + fami,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });


    }
    function contracuenta() {
        $('#DCCtaObra').select2({
            placeholder: 'Seleccione Contracuenta',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?contracuenta=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });


    }

    function leercuenta() {
        $('#DCBenef').val('').trigger('change');
        var parametros =
        {
            'cuenta': $('#DCCtaObra').val(),
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?leercuenta=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $('#Codigo').val(response.Codigo);
                    $('#Cuenta').val(response.Cuenta);
                    $('#SubCta').val(response.SubCta);
                    $('#Moneda_US').val(response.Moneda_US);
                    $('#TipoCta').val(response.TipoCta);
                    $('#TipoPago').val(response.TipoPago);
                    ListarProveedorUsuario();

                }

            }
        });

    }

    function Trans_Kardex() {
        $('#DCDiario').select2({
            placeholder: 'Seleccione Diario',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?trans_kardex_opcional=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    //  console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function bodega() {
        //var option = '<option value="">Seleccione bodega</option>';
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/inventario/registro_esC.php?bodega=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $.each(response, function (i, item) {
                        //  console.log(item);
                        let option = $('<option>', {
                            value: item.CodBod,
                            text: item.Bodega
                        });
                        $('#DCBodega').append(option);
                    });

                }
            }
        });

    }


    function marca() {
        //var option = '<option value="">Seleccione marca</option>';
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/inventario/registro_esC.php?marca=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $.each(response, function (i, item) {
                        // console.log(item);
                        let option = $('<option>', {
                            value: item.CodMar,
                            text: item.Marca
                        });
                        $('#DCMarca').append(option);
                    });

                }
            }
        });

    }



    function ListarProveedorUsuario() {
        var cta = $('#SubCta').val();
        var contra = $('#DCCtaObra').val();
        $('#DCBenef').select2({
            placeholder: 'Seleccione Cliente',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?ListarProveedorUsuario=true&cta=' + cta + '&contra=' + contra,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    //  console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function guardar() {
        var tipo = $('input:radio[name=rbl_]:checked').val();
    }


    function modal_retencion() {
        if ($('#CheqRF').prop('checked')) {
            $('#myModal').modal('show');
        }
    }

    function detalle_articulo() {
        var arti = $('#ddl_producto').val();
        var fami = $('#ddl_familia').val();
        var nom_ar = $('select[name="ddl_producto"] option:selected').text();
        var parametros =
        {
            'arti': arti,
            'nom': nom_ar,
            'fami': fami,
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?detalle_articulos=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $('#labelProductro').val(response.producto);
                    $('#LabelUnidad').val(response.unidad);
                    $('#LabelCodigo').val(response.codigo);
                    $('#TxtRegSanitario').val(response.registrosani);
                    Cod_Inv_Producto = response.codigo;
                    Contra_Cta1 = response.contra_cta1;
                    Cta_Inventario = response.cta_inventario;
                    $('#TextOrden').attr('disabled', false);
                    if (response.si_no == 0) {
                        $('#OpcX').prop('checked', true);
                    } else {
                        $('#OpcIVA').prop('checked', true);
                    }
                    // console.log(response);
                }
            }
        });

    }
    function tipo_ingreso() {
        if ($('#OpcI').prop('checked')) {
            // alert('ingreso');
            //make visible Label 11
            $('#TextIVA').attr('disabled', false);
            $('#CheqContraCta').attr('checked', true);
            $('#CheqContraCta').attr('disabled', false);
            $('#DCCtaObra').attr('disabled', false);
            $('#DCBenef').attr('disabled', false);
            $('#CheqRF').attr('disabled', false);

        } else {
            $('#TextIVA').attr('disabled', true);
            $('#CheqContraCta').attr('checked', false);
            $('#CheqContraCta').attr('disabled', true);
            $('#DCCtaObra').attr('disabled', true);
            $('#DCBenef').attr('disabled', true);
            $('#CheqRF').attr('disabled', true);
            let cltp = $('#CLTP').val();
            switch (cltp) {
                case "ND":
                case "NC":
                    $('#CheqRF').attr('disabled', false);
                    $('#TextIVA').attr('disabled', true);
                    break;
            }
            // alert('egreso');
        }

    }

    function CheqContraCuenta_Clic() {
        const contra = $('#CheqContraCta').prop('checked');
        if (contra) {
            //DCCtaObra visible true
            $('#DCCtaObra').attr('disabled', false);
            //DCBenef visible true
            $('#DCBenef').attr('disabled', false);
        } else {
            const opce = $('#OpcE').prop('checked');
            if (opce) {
                //DCCtaObra visible false
                $('#DCCtaObra').attr('disabled', true);
                //DCBenef visible false
                $('#DCBenef').attr('disabled', true);
            }
        }
    }

    function limpiar_retencaion() {
        $('#CheqRF').prop('checked', false);
        $('#myModal').modal('hide');
        cancelar();
    }
    function enviar_correo() {
        let htmlLoading = "<div class='bg-envcorreo' id='bg-envcorreo'><img id='load-gif' src='../../img/gif/correo_fin.gif' alt='Enviando correo'></div><div class='text-envcorreo' id='text-envcorreo'>Estimado usuario, su correo está siendo procesado para ser envíado...</div>"
        let htmlSuccess = "<div class='success-checkmark'><div class='check-icon'><span class='icon-line line-tip'></span><span class='icon-line line-long'></span><div class='icon-circle'></div><div class='icon-fix'></div></div></div>";
        let htmlError = "<div class='sa'><div class='sa-error'><div class='sa-error-x'><div class='sa-error-left'></div><div class='sa-error-right'></div></div><div class='sa-error-placeholder'></div><div class='sa-error-fix'></div></div></div>";

        document.getElementById("contenedor-envcorreo").innerHTML = htmlLoading;
        document.getElementById("contenedor-envcorreo").style.backgroundColor = "#fff";

        let advEnvCorreo = document.getElementById("contenedor-envcorreo");
        let bannerEC = document.getElementById("bg-envcorreo");
        let txtEC = document.getElementById("text-envcorreo");

        const datosCorreo = {
            'subject': "Prueba de Correo",
            'de': "electronicos@diskcoversystem.com",
            'mensaje': "Prueba de Envio de Correos",
            'adjunto': "",
            'credito_no': '',
            'tipoDeEnvio': '',
            'listaMail': null,
            'para': 'tedalemorvel@gmail.com;'
        };

        advEnvCorreo.classList.add("cont-ec-open");
        $.ajax({
            data: { 'data': datosCorreo },
            url: './inventario/sv_envio_correo.php',
            type: 'post',
        })
            .done(msg => {
                if (msg == "success") {
                    bannerEC.innerHTML = htmlSuccess;
                    advEnvCorreo.style.backgroundColor = "#009df9";
                    txtEC.innerText = "El correo ha sido enviado con exito";
                } else {
                    bannerEC.innerHTML = htmlError;
                    bannerEC.style.backgroundColor = "#F27474";
                    advEnvCorreo.style.backgroundColor = "#F27474";
                    txtEC.innerText = "Ocurrió un error al envíar el correo";
                }
                txtEC.style.color = "#fff";
                txtEC.style.textAlign = "center";
                txtEC.style.fontWeight = "700";

                setTimeout(function () {
                    advEnvCorreo.classList.remove("cont-ec-open");
                }, 3000);

            });

    }
</script>

<style>
    body {
        padding-right: 0px !important;
    }

    .alineacion {
        padding: 0px;
    }

    .row {
        display: flex;
        align-items: center;
    }
</style>
<div id="contenedor-envcorreo" class="contenedor-envcorreo">
    <div class="bg-envcorreo" id="bg-envcorreo">
        <img id="load-gif" src="../../img/gif/correo_fin.gif" alt="Enviando correo">
    </div>
    <div class="text-envcorreo" id="text-envcorreo">Estimado usuario, su correo está siendo procesado para ser
        envíado...
    </div>
</div>
<div class="container-lg">
    <div class="row">
        <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
            <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
                <img src="../../img/png/impresora.png">
            </button>
            <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
                <img src="../../img/png/table_excel.png">
            </button>
            <button title="Guardar" class="btn btn-default" onclick="validar_grabacion()">
                <img src="../../img/png/grabar.png">
            </button>
            <button title="Enviar" class="btn btn-default" id="enviar_btn" onclick="enviar_correo()">
                <img src="../../img/png/send_email.png" style="height:32px; width:32px">
            </button>
        </div>
    </div>
    <div class="">

        <div class="row text-center">
            <div class="col-sm-12">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    CONTROL DE INVENTARIO PARA INGRESOS/EGRESOS
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                            aria-labelledby="headingOne">
                            <div class="panel-body">
                                <input type="hidden" name="si_no" id="si_no">


                                <input type="hidden" name="" id="Codigo">
                                <input type="hidden" name="" id="Cuenta">
                                <input type="hidden" name="" id="SubCta">
                                <input type="hidden" name="" id="Moneda_US">
                                <input type="hidden" name="" id="TipoCta">
                                <input type="hidden" name="" id="TipoPago">


                                <input type="hidden" name="grupo_no" id="grupo_no">
                                <input type="hidden" name="Tipodoc" id="Tipodoc">
                                <input type="hidden" name="TipoBenef" id="TipoBenef">
                                <input type="hidden" name="cod_benef" id="cod_benef">
                                <input type="hidden" name="InvImp" id="InvImp">
                                <input type="hidden" name="ci" id="ci">


                                <div class="row"><br>
                                    <div class="col-sm-1 text-right">
                                        <label for="CLTP">TD:</label>
                                    </div>
                                    <div class="col-sm-1 alineacion">
                                        <select class="form-control input-xs" id="CLTP" name="CLTP">
                                            <!--<option value="">Seleccione TP</option>-->
                                            <option value="CD">CD</option>
                                            <option value="NC">NC</option>
                                            <option value="ND">ND</option>
                                            <option value="CD">CD</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="radio-inline"><b><input type="radio" name="rbl_tipo" checked=""
                                                    id="OpcI" onclick="tipo_ingreso()"> Ingreso</b></label>
                                        <label class="radio-inline"><b><input type="radio" name="rbl_tipo" id="OpcE"
                                                    onclick="tipo_ingreso()">
                                                Egreso</b></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="radio-inline"><b><input type="checkbox" name="CheqContraCta"
                                                    checked="" id="CheqContraCta" onchange="CheqContraCuenta_Clic();">
                                                CONTRA CUENTA</b></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="form-control input-xs" id="DCCtaObra" onchange="leercuenta();"
                                            placeholder="Contra Cuenta">
                                            <!--<option>Contra Cuenta</option>-->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1 text-right">
                                        <label for="MBFechaI">Fecha:</label>
                                    </div>
                                    <div class="col-sm-2 alineacion">
                                        <input class="form-control input-xs" style="width: 65%;" type="date"
                                            name="MBFechaI" value="<?php echo date('Y-m-d') ?>" id="MBFechaI"
                                            onblur="DCPorcenIva('MBFechaI', 'DCPorcIVA');">
                                    </div>
                                    <div class="col-sm-4 col-md-offset-3">
                                        <select class="form-control input-xs" id="DCBenef" name="DCBenef"
                                            placeholder="Clientes">
                                            <!--<option>Clientes</option>-->
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control input-xs" name="DCDiario" id="DCDiario"
                                            placeholder="Diario"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1">
                                        <label for="MBVence"><small>Vencimiento:</small></label>
                                    </div>
                                    <div class="col-sm-2 alineacion">
                                        <input class="form-control input-xs" style="width: 65%;" type="date"
                                            name="MBVence" id="MBVence" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-sm-2 alineacion text-right">
                                        <label for="TextConcepto">POR CONCEPTO DE:</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="TextConcepto" class="form-control input-xs"
                                            id="TextConcepto">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="radio-inline">
                                            <b>
                                                <input type="checkbox" name="CheqRF"
                                                    onclick="Ult_fact_Prove($('#DCProveedor').val());modal_retencion();"
                                                    id="CheqRF">
                                                Retencion en la
                                                fuente:
                                            </b>
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="LblRF" class="input-xs form-control" id="LblRF"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-2 alineacion text-right">
                                        <label for="LblRIVA">Retencion del I.V.A:</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="LblRIVA" id="LblRIVA" class="input-xs form-control"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-1 alineacion text-right">
                                        <label for="TxtFactNo">N° Factura:</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="TxtFactNo" id="TxtFactNo" class="input-xs form-control"
                                            value="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-control input-xs" id="ddl_familia" name="ddl_familia"
                                            onchange="producto_famili($('#ddl_familia').val())">
                                            <option value="">Seleccione un Familiar</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control text-center input-xs"
                                            name="labelProductro" id="labelProductro" value="PRODUCTO" readonly="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-control input-xs" id="ddl_producto" name="ddl_producto"
                                            placeholder="Seleccione Producto" onchange="detalle_articulo()">
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline"><b><input type="radio" name="rbl_"
                                                                    id="OpcIVA" onchange="habilitar_iva();"> Con
                                                                Iva</b>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="radio-inline"><b><input type="radio" name="rbl_"
                                                                    id="OpcX" checked onchange="habilitar_iva();"> Sin
                                                                Iva</b>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="DCPorcIVA">I.V.A</label>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="DCMarca">MARCA</label>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="LabelCodigo">CODIGO</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <!--Bodega-->
                                                <select class="form-control input-xs" id="DCBodega">
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <!--IVA-->
                                                <select class="form-control input-xs" id="DCPorcIVA" name="DCPorcIVA">
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <!--Marca-->
                                                <select class="form-control input-xs" id="DCMarca">
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <!--Codigo-->
                                                <input type="text" class="form-control input-xs" id="LabelCodigo"
                                                    name="LabelCodigo" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="LabelUnidad">UNIDAD</label>
                                        <input type="text" name="LabelUnidad" class="form-control input-xs"
                                            id="LabelUnidad" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextOrden" id="">GUIA N°</label>
                                        <input type="text" name="TextOrden" id="TextOrden" class="form-control input-xs"
                                            value="0">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextEntrada">CANTIDAD</label>
                                        <input type="text" name="TextEntrada" id="TextEntrada"
                                            class="form-control input-xs" value="0">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextVUnit">VALOR UNIT.</label>
                                        <input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-xs"
                                            value="0.00" onblur="TextVUnit_LostFocus();">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="TxtCodBar">CODIGO DE BARRA</label>
                                        <input type="text" name="TxtCodBar" id="TxtCodBar"
                                            class="form-control input-xs">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="TxtLoteNo">LOTE N°</label>
                                        <input type="text" name="TxtLoteNo" id="TxtLoteNo" class="form-control input-xs"
                                            value="0">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="MBFechaFab">FECHA FAB</label>
                                        <input type="date" name="MBFechaFab" id="MBFechaFab"
                                            class="form-control input-xs" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="MBFechaExp">FECHA EXP</label>
                                        <input type="date" name="MBFechaExp" id="MBFechaExp"
                                            class="form-control input-xs" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TxtRegSanitario">REG. SANITARIO</label>
                                        <input type="text" name="TxtRegSanitario" id="TxtRegSanitario"
                                            class="form-control input-xs" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="TxtModelo">MODELO</label>
                                        <input type="text" name="TxtModelo" id="TxtModelo" class="form-control input-xs"
                                            onblur="toupper(this);">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="TxtProcedencia">PROCEDENCIA/UBICACION</label>
                                        <input type="text" name="TxtProcedencia" id="TxtProcedencia"
                                            class="form-control input-xs" onblur="toupper(this);">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="TxtSerieNo">SERIE No.</label>
                                        <input type="text" name="TxtSerieNo" id="TxtSerieNo"
                                            class="form-control input-xs">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextDesc">DESC. 1</label>
                                        <input type="text" name="TextDesc" id="TextDesc" class="form-control input-xs"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextDesc1">DESC. 2</label>
                                        <input type="text" name="TextDesc1" id="TextDesc1" class="form-control input-xs"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextTotal">VALOR TOTAL</label>
                                        <input type="text" name="TextTotal" id="TextTotal" class="form-control input-xs"
                                            value="0.00" onblur="TexTotal_LostFocus();">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tbl-container" style="margin:1vw;" id="tbl-container">
            <div class="row">
                <div class="table-responsive" style="height: 400px">
                    <table>
                        <thead>
                            <th width="25px">TP</th>
                            <th>CODIGO_INV</th>
                            <th>DH</th>
                            <th>PRODUCTO</th>
                            <th>CANT_ES</th>
                            <th>VALOR_UNI</th>
                            <th>VALOR_TOTAL</th>
                            <th>CANTIDAD</th>
                            <th>SALDO</th>
                            <th>P_DESC</th>
                            <th>P_DESC1</th>
                            <th>IVA</th>
                            <th>CTA_INVENTARIO</th>
                            <th>CONTRA_CTA</th>
                            <th>UNIDAD</th>
                            <th>CodBod</th>
                            <th>CodMar</th>
                            <th>COD_BAR</th>
                            <th>T_No</th>
                            <th>Item</th>
                            <th>CodigoU</th>
                            <th>SUBCTA</th>
                            <th>Cod_Tarifa</th>
                            <th>Fecha_DUI</th>
                            <th>No_Refrendo</th>
                            <th>DUI</th>
                            <th>A_No</th>
                            <th>ValorEM</th>
                            <th>Especifico</th>
                            <th>Consumo</th>
                            <th>Antidumping</th>
                            <th>Modernizacion</th>
                            <th>Control</th>
                            <th>Almacenaje</th>
                            <th>FODIN</th>
                            <th>Salvaguardas</th>
                            <th>Interes</th>
                            <th>CODIGO_INV1</th>
                            <th>CodBod1</th>
                            <th>Codigo_B</th>
                            <th>Codigo_Dr</th>
                            <th>ORDEN</th>
                            <th>VALOR_FOB</th>
                            <th>COMIS</th>
                            <th>TRANS_UNI</th>
                            <th>TRANS_TOTAL</th>
                            <th>PRECION_CIF</th>
                            <th>UTIL</th>
                            <th>PVP</th>
                            <th>CTA_COSTO</th>
                            <th>CTA_VENTA</th>
                            <th>TOTAL_PVP</th>
                            <th>Codigo_Tra</th>
                            <th>Lote_N°</th>
                            <th>Fecha_Fab</th>
                            <th>Fecha_Exp</th>
                            <th>Reg_Sanitario</th>
                            <th>Modelo</th>
                            <th>Procedencia</th>
                            <th>Serie_N°</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row"><br><br>
            <div class="col-sm-2">
                <input type="text" name="Label3" id="Label3" class="form-control input-xs">
            </div>
            <div class="col-sm-2">
                <button class="btn btn-default" data-toggle="modal" data-target="#myModal_comprobante">Seleccionar <br>
                    comprobante</button>
            </div>
            <div class="col-sm-2">
                <label for="TxtDifxDec">DIFxDECIMALES</label>
                <input type="text" name="TxtDifxDec" id="TxtDifxDec" class="input-xs form-control" value="0">
            </div>
            <div class="col-sm-2">
                <label for="TxtSubTotal">SUBTOTAL</label>
                <input type="text" name="TxtSubTotal" id="TxtSubTotal" class="input-xs form-control" value="0">
            </div>
            <div class="col-sm-2">
                <label for="TextIVA" id="Label11">I.V.A</label>
                <input type="text" name="TextIVA" id="TextIVA" class="input-xs form-control" value="0">
            </div>
            <div class="col-sm-2">
                <label for="Label1">TOTAL</label>
                <input type="text" name="Label1" id="Label1" class="input-xs form-control">
            </div>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Compras</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="box box-info">
                                    <div class="box-header" style="padding:0px">
                                        <h3 class="box-title">Retencion de IVA por</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="radio-inline" onclick="habilitar_bienes()"><input
                                                        type="checkbox" name="ChRetB" id="ChRetB"> Bienes</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="form-control input-sm" id="DCRetIBienes">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="radio-inline" onclick="habilitar_servicios()"><input
                                                        type="checkbox" name="ChRetS" id="ChRetS">Servicios</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="form-control input-sm" id="DCRetISer"
                                                    onblur="alert('s');">
                                                    <option>Seleccione Tipo Retencion</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center">
                                <button class="btn btn-default"> <img src="../../img/png/grabar.png"
                                        onclick="validar_formulario()"><br>
                                    Guardar</button>
                                <button class="btn btn-default" data-dismiss="modal" onclick="limpiar_retencaion()">
                                    <img src="../../img/png/bloqueo.png"><br> Cancelar</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <b>PROVEEDOR</b>
                                <select class="form-control" id="DCProveedor">
                                    <option value="">No seleccionado</option>
                                </select>
                            </div>
                            <div class="col-sm-1"><br>
                                <input type="text" class="form-control input-sm" name="" id="LblTD" style="color: red"
                                    readonly="">
                            </div>
                            <div class="col-sm-3"><br>
                                <input type="text" class="form-control input-sm" name="" id="LblNumIdent" readonly="">
                            </div>
                        </div>
                    </div><br>
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item active">
                                <a class="nav-link" data-toggle="tab" href="#home">Comprobante de compra</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1">Conceptos AIR</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu2">Partidos politicos</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane modal-body active" id="home">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <b>Devolucion del IVA:</b>
                                            </div>
                                            <div class="col-sm-19">
                                                <label class="radio-inline"><input type="radio" name="cbx_iva"
                                                        id="iva_si" value="S" checked="">
                                                    SI</label>
                                                <label class="radio-inline"><input type="radio" name="cbx_iva"
                                                        id="iva_no" value="N"> NO</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b>Tipo de sustento Tributario</b>
                                                <select class="form-control input-sm" id="DCSustento"
                                                    onchange="ddl_DCTipoComprobante();ddl_DCDctoModif();">
                                                    <option value="">seleccione sustento </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <br>
                                        <button type="button" id="btn_air" class="btn btn-default text-center"
                                            onclick="cambiar_air()"><i class="fa fa-arrow-right"></i><br>AIR</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="box box-info">
                                            <div class="box-header" style="padding:0px">
                                                <h3 class="box-title"><b>INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA,
                                                        ETC_________________FORMULARIO 104</b></h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <b>tipo de comprobate</b>
                                                        <select class="form-control input-sm" id="DCTipoComprobante"
                                                            onchange="mostrar_panel()">
                                                            <option value="">Seleccione tipo de comprobante</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>Serie</b>
                                                        <div class="row">
                                                            <div class="col-sm-6" style="padding: 0px">
                                                                <input type="text" name="" class="form-control input-sm"
                                                                    id="TxtNumSerieUno" placeholder="001"
                                                                    onblur="autocompletar_serie_num(this.id)"
                                                                    onkeyup=" solo_3_numeros(this.id)">
                                                            </div>
                                                            <div class="col-sm-6" style="padding: 0px">
                                                                <input type="text" name="" class="form-control input-sm"
                                                                    id="TxtNumSerieDos" placeholder="001"
                                                                    onblur="autocompletar_serie_num(this.id)"
                                                                    onkeyup=" solo_3_numeros(this.id)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>Numero</b>
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtNumSerietres" onblur="validar_num_factura(this.id)"
                                                            placeholder="000000001" onkeyup="solo_9_numeros(this.id)">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <b>Autorizacion</b>
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtNumAutor" onblur="autorizacion_factura()"
                                                            placeholder="0000000001" onkeyup="solo_10_numeros(this.id)">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-2"
                                                            style="padding-left: 0px;padding-right: 0px">
                                                            <b>Emision</b>
                                                            <input type="date" name="" class="form-control input-sm"
                                                                value="<?php echo date('Y-m-d') ?>" id="MBFechaEmi">
                                                        </div>
                                                        <div class="col-sm-2"
                                                            style="padding-left: 0px;padding-right: 0px">
                                                            <b>Registro</b>
                                                            <input type="date" name="" class="form-control input-sm"
                                                                value="<?php echo date('Y-m-d') ?>" id="MBFechaRegis">
                                                        </div>
                                                        <div class="col-sm-2"
                                                            style="padding-left: 0px;padding-right: 0px">
                                                            <b>Caducidad</b>
                                                            <input type="date" name="" class="form-control input-sm"
                                                                value="<?php echo date('Y-m-d') ?>" id="MBFechaCad">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <b>No Obj. IVA</b>
                                                            <input type="text" name="" class="form-control input-sm"
                                                                value="0.00" id="TxtBaseImpoNoObjIVA">
                                                        </div>
                                                        <div class="col-sm-1"
                                                            style="padding-right: 5px;padding-left: 5px;">
                                                            <b>Tarifa 0</b>
                                                            <input type="text" name="" class="form-control input-sm"
                                                                value="0.00" id="TxtBaseImpo">
                                                        </div>
                                                        <div class="col-sm-1"
                                                            style="padding-right: 5px;padding-left: 5px;">
                                                            <b>Tarifa 12</b>
                                                            <input type="text" name="" class="form-control input-sm"
                                                                value="0.00" id="TxtBaseImpoGrav">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <b>Valor ICE</b>
                                                            <input type="text" name="" class="form-control input-sm"
                                                                value="0.00" id="TxtBaseImpoIce">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="box box-info">
                                            <div class="box-header" style="padding:0px">
                                                <h3 class="box-title">Porcentajes de las bases Imponibles</h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-1">
                                                        IVA
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control input-sm" id="DCPorcenIva"
                                                            onchange="calcular_iva()">
                                                            <option value="I">Iva</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Valor I.V.A
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtMontoIva" value="0">
                                                    </div>
                                                </div>
                                                <div class="row"><br>
                                                    <div class="col-sm-1">
                                                        ICE
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control input-sm" id="DCPorcenIce"
                                                            onchange="calcular_ice()">
                                                            <option value="I">ICE</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Valor ICE
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtMontoIce" readonly="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="box box-warning">
                                            <div class="box-header" style="padding:0px">
                                                <h3 class="box-title">Retencion del IVA por Bienes Y/O Servicios </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-4"><br>
                                                        Monto
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <b>BIENES</b>
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtIvaBienMonIva" readonly="">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <b>SERVICIOS</b>
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtIvaSerMonIva" readonly="">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        Porcentaje
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control input-sm" id="DCPorcenRetenIvaBien"
                                                            disabled="" onchange="calcular_retencion_porc_bienes()">
                                                            <option value="0">0</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <select class="form-control input-sm" id="DCPorcenRetenIvaServ"
                                                            disabled="" onchange="calcular_retencion_porc_serv()">
                                                            <option value="0">0</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        Valor RET
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtIvaBienValRet" value="0" readonly="">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtIvaSerValRet" value="0" readonly="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="panel_notas" style="display: none">
                                    <div class="col-sm-12">
                                        <div class="box box-info">
                                            <div class="box-header" style="padding:0px">
                                                <h3 class="box-title"><b>NOTAS DE DEBITO / NOTAS DE CREDITO</b></h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <b>tipo de comprobate</b>
                                                        <select class="form-control input-sm" id="DCDctoModif">
                                                            <option>Seleccione tipo de comprobante</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>Serie</b>
                                                        <div class="row">
                                                            <div class="col-sm-6" style="padding: 0px">
                                                                <input type="text" name="" class="form-control input-sm"
                                                                    id="TxtNumSerieUnoComp" placeholder="001"
                                                                    onblur="autocompletar_serie_num(this.id)"
                                                                    onkeyup="solo_3_numeros(this.id)">
                                                            </div>
                                                            <div class="col-sm-6" style="padding: 0px">
                                                                <input type="text" name="" class="form-control input-sm"
                                                                    id="TxtNumSerieDosComp" placeholder="001"
                                                                    onblur="autocompletar_serie_num(this.id)"
                                                                    onkeyup="solo_3_numeros(this.id)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-1" style="padding-left: 5px;padding-right: 5px">
                                                        <b>Numero</b>
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="CNumSerieTresComp" onkeyup="solo_9_numeros(this.id)"
                                                            onblur="validar_num_factura(this.id)"
                                                            placeholder="000000001">
                                                    </div>
                                                    <div class="col-sm-2" style="padding-left: 5px;padding-right: 5px">
                                                        <b>Fecha</b>
                                                        <input type="date" name="" class="form-control input-sm"
                                                            id="MBFechaEmiComp">
                                                    </div>
                                                    <div class="col-sm-3" style="padding-right: 5px;">
                                                        <b>Autorizacion sri</b>
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtNumAutComp">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane modal-body fade" id="menu1">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <b>Forma de pago</b>
                                        <select class="form-control input-sm" onchange="mostrar_panel_ext()"
                                            id="CFormaPago">
                                            <option value="">Seleccione forma de pago</option>
                                            <option value="1">Local</option>
                                            <option value="2">Exterior</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <b>Tipo de pago</b>
                                        <select class="form-control input-sm" id="DCTipoPago"
                                            onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');">
                                            <option value="">Seleccione tipo de pago</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="panel_exterior" style="display: none;">
                                    <div class="col-sm-4">
                                        <b>Pais al que se efectua el pago</b>
                                        <select class="form-control input-sm" id="DCPais">
                                            <option>Seleccione Pais</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6"><br>
                                        Aplica convenio de doble tributacion?
                                        <br>
                                        Pago sujeto a retencion en aplicacion de la forma legal?
                                        <br>
                                    </div>
                                    <div class="col-sm-2 text-right"><br>
                                        <label class="radio-inline"><input type="radio" name="rbl_convenio" checked=""
                                                value="SI">SI</label>
                                        <label class="radio-inline"><input type="radio" name="rbl_convenio"
                                                value="NO">NO</label>
                                        <label class="radio-inline"><input type="radio" name="rbl_pago_retencion"
                                                checked="" value="SI">SI</label>
                                        <label class="radio-inline"><input type="radio" name="rbl_pago_retencion"
                                                value="NO">NO</label>
                                    </div>
                                </div>
                                <div class="row"><br>
                                    <div class="col-sm-12">
                                        <div class="box box-info">
                                            <div class="box-header" style="padding:0px">
                                                <h3 class="box-title"><b>INGRESE LOS DATOS DE LA
                                                        RETENCION_________________FORMULARIO 103</b>
                                                </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="radio-inline" onclick="mostra_select()"
                                                            id="lbl_rbl"><input type="checkbox" name="ChRetF"
                                                                id="ChRetF"> Retenecion en la fuente</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <select class="form-control input-sm" id="DCRetFuente"
                                                            style="display: none;"
                                                            onchange="$('#DCRetFuente').css('border','1px solid #d2d6de');">
                                                            <option value=""> Seleccione Tipo de retencion</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        Serie
                                                        <div class="row">
                                                            <div class="col-sm-6"
                                                                style="padding-left: 0px;padding-right: 0px;"><input
                                                                    type="text" class="form-control input-sm"
                                                                    name="TxtNumUnoComRet" id="TxtNumUnoComRet"
                                                                    onkeyup="solo_3_numeros(this.id)" placeholder="001"
                                                                    onblur="autocompletar_serie_num(this.id)"></div>
                                                            <div class="col-sm-6"
                                                                style="padding-left: 0px;padding-right: 0px;"><input
                                                                    type="text" class="form-control input-sm"
                                                                    name="TxtNumDosComRet" id="TxtNumDosComRet"
                                                                    onkeyup="solo_3_numeros(this.id)" placeholder="001"
                                                                    onblur="autocompletar_serie_num(this.id)"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        Numero
                                                        <input type="text" class="form-control input-sm"
                                                            name="TxtNumTresComRet" id="TxtNumTresComRet"
                                                            onblur="validar_num_retencion()"
                                                            onkeyup="solo_9_numeros(this.id)" placeholder="000000001">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        Autorizacion
                                                        <input type="text" name="" class="form-control input-sm"
                                                            id="TxtNumUnoAutComRet">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="row">
                                                            <div class="col-sm-4"><br>
                                                                SUMATORIA
                                                            </div>
                                                            <div class="col-sm-8"><br>
                                                                <input type="text" name="" class="form-control input-sm"
                                                                    id="TxtSumatoria">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-7">
                                                        <b>CODIGO DE RETENCION</b>
                                                        <select class="form-control input-sm" id="DCConceptoRet"
                                                            name="DCConceptoRet" onchange="calcular_porc_ret()">
                                                            <option value="">Seleccione Codigo de retencion</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>BASE IMP</b>
                                                        <input type="text" class="form-control input-sm"
                                                            name="TxtBimpConA" id="TxtBimpConA">
                                                    </div>
                                                    <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px">
                                                        <b>PORC</b>
                                                        <input type="text" class="form-control input-sm"
                                                            name="TxtPorRetConA" id="TxtPorRetConA"
                                                            onblur="insertar_grid()" readonly="">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <b>VALOR RET</b>
                                                        <input type="text" class="form-control input-sm"
                                                            name="TxtValConA" id="TxtValConA" readonly="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <th>CodRet</th>
                                                <th>Detalle</th>
                                                <th>BaseImp</th>
                                                <th>Porcentaje</th>
                                                <th>ValRet</th>
                                                <th>EstabRetencion</th>
                                                <th>PtoEmiRetencion</th>
                                                <th>SecRetencion</th>
                                                <th>AutoRetencion</th>
                                                <th>FechaEmiRet</th>
                                                <th>Cta_Retencion</th>
                                                <th>EstabFactura</th>
                                                <th>PuntoEmiFactura</th>
                                                <th>Factura_No</th>
                                                <th>IdProv</th>
                                                <th>Item</th>
                                                <th>codigoU</th>
                                                <th>A_No</th>
                                                <th>T_No</th>
                                                <th>Tipo_Trans</th>
                                            </thead>
                                            <tbody id="tbl_retencion">

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <b>Total Retencion</b>
                                        <input type="text" class="input-sm" name="">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane modal-body fade" id="menu2">
                                <div class="row text">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <b>NUMERO DEL CONTRATO DEL PARTIDO POLITICO</b>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="" id="TxtNumConParPol">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <b>MONTO TITULO ONEROSO</b>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="TxtMonTitOner"
                                                    id="TxtMonTitOner">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <b>MONTO DEL CONTRATO</b>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="TxtMonTitGrat"
                                                    id="TxtMonTitGrat">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="myModal_comprobante" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titulo-modal">GRABACIÓN DE COMPROBANTE:</h4>
            </div>
            <div class="modal-body">
                <label for="numComprobante">Ingrese el número de comprobante:</label>
                <input type="text" name="numComprobante" id="numComprobante" value="0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="Command3_Click()">Buscar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>

<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->