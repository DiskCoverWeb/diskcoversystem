// Espera a que el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {

    var cheqRecibo, txtRecibo, mbFecha, selectCliente, txtConcepto, selectBanco, selectCtaAnt, txtCajaMN, labelPend, reciboNo;

    var fechaValida = FechaValida(mbFecha);
    var fechaTexto = fechaValida;
    var fechaComp = fechaTexto;
    var total = parseFloat(totalCajaMN)
    var DetalleComp = ninguno
    var codigoCli = codigoCliente

    //Form_Acticate()
    $(document).ready(function () {
        // Asignación de variables
        cheqRecibo = document.getElementById("CheqRecibo");
        txtRecibo = document.getElementById("TxtRecibo");
        mbFecha = document.getElementById("MBFecha");
        selectCliente = document.getElementById("DCCliente");
        txtConcepto = document.getElementById("TxtConcepto");
        selectBanco = document.getElementById("DCBanco");
        selectCtaAnt = document.getElementById("DCCtaAnt");
        txtCajaMN = document.getElementById("TextCajaMN");
        labelPend = document.getElementById("LabelPend");
        reciboNo = document.getElementById("Recibo_No");

        // ControlEsNumerico TextCajaMN <- se declaro type = number en la vista para restringir que sea numerico
        var subCtaGen = Leer_Seteos_Ctas("Cta_Anticipos_Clientes");
        // se llena el select con id DCCtaAnt
        DCCtaAnt();
        // se llena el select con id DCBanco
        DCBanco();
        if (TipoFactura === "OP") {
            $('#idLabelPend').css('display', 'initial');
            // se llena el select con id DCTipo
            DCTipo();
        } else {
            $('#idLabelPend').css('display', 'none');
            // se llena el select con id DCCliente 
            DCCliente();
        }

        // Si CheqRecibo.Value = 1 Then TxtRecibo = Format$(DiarioCaja, "0000000") Else TxtRecibo = "";
        actualizarTxtRecibo();

        // función al evento click en cheqRecibo        
        cheqRecibo.addEventListener('click', actualizarTxtRecibo);

        function actualizarTxtRecibo() {
            var DiarioCaja = ReadSetDataNum("Recibo_No", true, false);
            var txtRecibo = document.getElementById('TxtRecibo');

            if (cheqRecibo.checked) {
                txtRecibo.value = DiarioCaja.toString().padStart(7, '0');
            } else {
                txtRecibo.value = "";
            }
        }

        var miFecha = BuscarFecha(fechaTexto)
        mbFecha.value = FechaSistema()

        /**If Bloquear_Control Then Command1.Enabled = False */

        // Función clic en el botón "Aceptar"
        window.Command1_Click = function () {
            Swal.fire({
                title: 'Formulario de Grabación',
                text: 'Está Seguro que desea grabar Abono.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!'
            }).then((result) => {
                if (result.value == true) {
                    Grabar_abonos();
                }
            });
        };

    });


    // Función para grabar abonos
    function Grabar_abonos() {

    }

    function DCCtaAnt() {
        $('#DCtaAnt').select2({
            ajax: {
                url: '../controlador/contabilidad/FAbonoAnticipado.php?DCCtaAnt=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function DCBanco() {
        $('#DCBanco').select2({
            ajax: {
                url: '../controlador/contabilidad/FAbonoAnticipado.php?DCBanco=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function DCTipo() {
        $.ajax({
            type: "POST",
            url: '../controlador/contabilidad/FAbonoAnticipado.php?DCTipo=true',
            dataType: 'json',
            success: function (data) {
                llenarComboList(data, 'DCTipo');
            }
        });
    }

    function DCCliente() {
        $('#DCCliente').select2({
            ajax: {
                url: '../controlador/contabilidad/FAbonosC.php?DCCliente=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }
});




