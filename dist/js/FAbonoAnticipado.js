// Espera a que el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {

    var txtCajaMN = document.getElementById("TextCajaMN");
    DCCtaAnt();//Hay que enviar el subCtaGen como parametro.
    DCBanco();
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');

    if (TipoFactura == "OP") {
        document.getElementById("LabelPend").style.display = 'block';
        document.getElementById("Label10").style.display = 'block';
        document.getElementById("Frame1").style.display = 'block';
        document.getElementById("Frame2").style.display = 'none';
        DCTipo();
    } else {
        document.getElementById("LabelPend").style.display = 'none';
        document.getElementById("Label10").style.display = 'none';
        document.getElementById("Frame1").style.display = 'none';
        document.getElementById("Frame2").style.display = 'block';
        //DCClientes();
    }
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




});

function llenarSelect(data, idSelect, dataName) {
    var select = document.getElementById(idSelect);
    if (data.length == 0) {
        select.innerHTML = '';
        var option = document.createElement("option");
        option.text = "No existen datos";
        option.value = "";
        select.appendChild(option);
    } else {
        select.innerHTML = '';
        console.log(data[0]);
        for (var i = 0; i < data.length; i++) {
            var option = document.createElement("option");
            option.value = data[i][dataName];
            option.text = data[i][dataName];
            select.appendChild(option);
        }
    }
}

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
            llenarSelect(data, "DCBanco", "NomCuenta")
        }
    });
}



function DCCtaAnt() {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCCtaAnt=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCCtaAnt", "NomCuenta");
        }
    });
}

function DCTipo() {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCTipo=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCTipo", "TC");
        }
    });
}

function DCClientes(){
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCClientes=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCClientes", "Cliente");
        }
    });
}
