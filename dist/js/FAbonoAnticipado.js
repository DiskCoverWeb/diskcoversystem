// Espera a que el DOM esté listo
document.addEventListener("DOMContentLoaded", function() {

    var txtCajaMN = document.getElementById("TextCajaMN");
    var subCtaGen = "Cta_Anticipos_Clientes" //Se tiene que realizar "Leer_Seteos_Ctas" en el controlador
    DCCtaAnt();//Hay que enviar el subCtaGen como parametro.

    var cheqRecibo = document.getElementById("CheqRecibo");
    var txtRecibo = document.getElementById("TxtRecibo");
    var mbFecha = document.getElementById("MBFecha");
    var selectCliente = document.getElementById("DCCliente");
    var txtConcepto = document.getElementById("TxtConcepto");
    var selectBanco = document.getElementById("DCBanco");
    var selectCtaAnt = document.getElementById("DCCtaAnt");
    
    var labelPend = document.getElementById("LabelPend");

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

    // Función para grabar abonos
    function Grabar_abonos() {
        if (cheqRecibo.checked) {
            console.log("El checkbox está seleccionado al hacer clic en Aceptar");
        } else {
            console.log("El checkbox no está seleccionado al hacer clic en Aceptar");
        }
        // Resto de la lógica de Grabar_abonos
    }

    DCBanco();


});

/*
Método conectado con el controlador para obtener todos los tipos de DCBanco existentes
en la base de datos. Si la data retornada contiene 'status' quiere decir que no hay datos
y se rellena el select con un 'No existen datos'.
Con el for llenamos el select de todos los datos que hayamos encontrado de la consulta SQL.
*/
function DCBanco() {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCBanco=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            var selectBanco = document.getElementById("DCBanco");
            if ('status' in data) {
                selectBanco.innerHTML = 'No existen datos';
            } else {
                selectBanco.innerHTML = '';
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i].NomCuenta;
                    option.text = data[i].NomCuenta;
                    selectBanco.appendChild(option);
                }
            }


        }
    });

}

function DCCtaAnt(){
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCCtaAnt=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            var select = document.getElementById("DCCtaAnt");
            if ('status' in data) {
                select.innerHTML = 'No existen datos';
            } else {
                select.innerHTML = '';
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i].NomCuenta;
                    option.text = data[i].NomCuenta;
                    select.appendChild(option);
                }
            }


        }
    });
}
