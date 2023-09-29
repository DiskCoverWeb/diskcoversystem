// Espera a que el DOM esté listo
document.addEventListener("DOMContentLoaded", function() {

    var cheqRecibo = document.getElementById("CheqRecibo");
    var txtRecibo = document.getElementById("TxtRecibo");
    var mbFecha = document.getElementById("MBFecha");
    var selectCliente = document.getElementById("DCCliente");
    var txtConcepto = document.getElementById("TxtConcepto");
    var selectBanco = document.getElementById("DCBanco");
    var selectCtaAnt = document.getElementById("DCCtaAnt");
    var txtCajaMN = document.getElementById("TextCajaMN");
    var labelPend = document.getElementById("LabelPend");

    // Función clic en el botón "Aceptar"
    window.Command1_Click = function() {
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

    // Función para grabar abonos
    function Grabar_abonos() {
        
        if (cheqRecibo.checked) {
            console.log("El checkbox está seleccionado al hacer clic en Aceptar");
        } else {
            console.log("El checkbox no está seleccionado al hacer clic en Aceptar");
        }
        // Resto de la lógica de Grabar_abonos
    }
});
