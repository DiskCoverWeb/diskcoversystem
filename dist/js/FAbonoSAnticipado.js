$(document).ready(function () {
    // Asignación de variables
    var cheqRecibo = document.getElementById("CheqRecibo");
    var txtRecibo = document.getElementById("TxtRecibo");
    var mbFecha = document.getElementById("MBFecha");
    var selectCliente = document.getElementById("DCCliente");
    var txtConcepto = document.getElementById("TxtConcepto");
    var selectBanco = document.getElementById("DCBanco");
    var selectCtaAnt = document.getElementById("DCCtaAnt");
    var txtCajaMN = document.getElementById("TextCajaMN");
    var labelPend = document.getElementById("LabelPend");
    var reciboNo = document.getElementById("Recibo_No");

    //var fechaValida = FechaValida(mbFecha);
    var fechaTexto = fechaValida;
    var fechaComp = fechaTexto;
    var total = parseFloat(totalCajaMN)
    var DetalleComp = ninguno
    var codigoCli = codigoCliente

    // ControlEsNumerico TextCajaMN <- se declaro type = number en la vista para restringir que sea numerico

    var subCtaGen;

    /*$.ajax({
        url: '../../funciones/Leer_Seteos_Ctas.php',
        data: { Det_Cta: 'Cta_Anticipos_Clientes' },
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data && data.Codigo !== undefined) {
                subCtaGen = data.Codigo;
                console.log(subCtaGen);
            } else {
                console.error("Datos incompletos en la respuesta");
            }
        },
        error: function (error) {
            console.error("Error al obtener datos:", error);
        }
    });*/

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
        //var DiarioCaja = ReadSetDataNum("Recibo_No", true, false);
        var txtRecibo = document.getElementById('TxtRecibo');

        if (cheqRecibo.checked) {
            // txtRecibo.value = DiarioCaja.toString().padStart(7, '0');
            console.log("El checkbox está seleccionado al hacer clic en Aceptar");
        } else {
            txtRecibo.value = "";
            console.log("El checkbox no está seleccionado al hacer clic en Aceptar");
        }
    }

    //var miFecha = BuscarFecha(fechaTexto)
    //mbFecha.value = FechaSistema()

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
            url: '../controlador/contabilidad/FAbonoAnticipadoC.php?DCCtaAnt=true',
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
            url: '../controlador/contabilidad/FAbonoAnticipadoC.php?DCBanco=true',
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
        url: '../controlador/contabilidad/FAbonoAnticipadoC.php?DCTipo=true',
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'DCTipo');
        }
    });
}

function DCCliente() {
    $('#DCCliente').select2({
        ajax: {
            url: '../controlador/contabilidad/FAbonoAnticipadoC.php?DCCliente=true',
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








