// Espera a que el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {

    var txtCajaMN = document.getElementById("TextCajaMN");
    DCCtaAnt();//Hay que enviar el subCtaGen como parametro.
    DCBanco();
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var grupo = urlParams.get('grupo');
    var faFactura = urlParams.get('faFactura');


    if (TipoFactura == "OP") {
        document.getElementById("LabelPend").style.display = 'block';
        document.getElementById("Label10").style.display = 'block';
        document.getElementById("Frame1").style.display = 'block';
        document.getElementById("Frame2").style.display = 'none';
        DCTipo(faFactura);
    } else {
        document.getElementById("LabelPend").style.display = 'none';
        document.getElementById("Label10").style.display = 'none';
        document.getElementById("Frame1").style.display = 'none';
        document.getElementById("Frame2").style.display = 'block';
        DCClientes(grupo);
    }
    var CheqRecibo = document.getElementById("CheqRecibo");
    var txtRecibo = document.getElementById("TxtRecibo");
    ReadSetDataNum("Recibo_No", true, false)
        .then(function (data) {
            // Aquí puedes trabajar con los datos
            if (CheqRecibo.checked) {
                txtRecibo.value = data.toString().padStart(7, '0');
                console.log(txtRecibo.textContent);
            } else {
                txtRecibo.value = "";
            }
        })
        .catch(function (error) {
            // Manejo de errores si la solicitud Ajax falla
            txtRecibo.value = "";
            console.error("Error en la solicitud Ajax", error);
        });




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

    insertAsientoSC();
    let Total = $('#TextCajaMN').val();
    insertarAsiento(0, Total, 0);
    insertarAsiento(0, 0, Total);

    var parametros = {
        'codigo_cliente': $('#DCClientes').val(),
        'sub_cta_gen': $('#DCCtaAnt').val().split(" ")[0]
    };
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Catalogo_CxCxP=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            return data;
        }
    });

    GrabarComprobante();
}

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
            console.log(data);
            llenarSelect(data, "DCCtaAnt", "NomCuenta");
        }
    });
}

function DCTipo(faFactura) {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCTipo=true',
        data: { 'fafactura': faFactura },
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCTipo", "TC");
        }
    });
}

function DCClientes(grupo) {
    let strGrupo = grupo!=null ? `&grupo=${grupo}` : '';
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCClientes=true' + strGrupo,
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            var select = document.getElementById("DCClientes");
            if (data.length == 0) {
                select.innerHTML = '';
                var option = document.createElement("option");
                option.text = "No existen datos";
                option.value = "";
                select.appendChild(option);
            } else {
                select.innerHTML = '';
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i]['Codigo'];
                    option.text = data[i]['Cliente'];
                    select.appendChild(option);
                }
            }
        }
    });
}

function ReadSetDataNum(SQLs, ParaEmpresa, Incrementar) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: "POST",
            url: '../controlador/contabilidad/FAbonosAnticipadoC.php?ReadSetDataNum=true',
            data: {
                'SQLs': SQLs,
                'ParaEmpresa': ParaEmpresa,
                'Incrementar': Incrementar
            },
            dataType: 'json',
            success: function (data) {
                resolve(data); // Resolvemos la promesa con los datos
            },
            error: function (error) {
                reject(error); // Rechazamos la promesa en caso de error
            }
        });
    });
}


function cerrar_modal() {
    window.parent.closeModal();
}

function Listar_Facturas_Pendientes() {
    //console.log("TIPO FACTURA LOST FOCUS", TipoFactura);
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var faFactura = urlParams.get('faFactura');
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCFactura=true',
        data: {
            'TipoFactura': TipoFactura,
            'FaFactura': faFactura
        },
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCFactura", "Factura");
        }
    });
}

function insertAsientoSC() {
    var parametros = {
        'Fecha_V': $('#MBFecha').val(),
        'CodigoC': $('#DCClientes').val(),
        'NombreC': $('#DCClientes').find("option:selected").text(),
        'SubCtaGen': $('#DCCtaAnt').val(),
        'Total': $('#TextCajaMN').val(),
        'Trans_No': 200
    };

    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Asiento_SC=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            return data;
        }
    });
}

function insertarAsiento(Parcial_MEs, Debes, Habers) {
    if (document.getElementById("Frame2").style.display == 'block') {
        var Cta_Aux = $('#DCBanco').val().split(" ")[0];
        if (Cta_Aux.length <= 1)
            Cta_Aux = '0';//Cta_CajaG
    } else {
        Cta_Aux = '0';
    }
    var parametros = {
        'trans_no': 200,
        'CodCta': Cta_Aux,
        'Parcial_MEs': Parcial_MEs,
        'Debes': Debes,
        'Habers': Habers,
        'CodigoCli': $('#DCClientes').val()
    };
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Asiento=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            return data;
        }
    });
}

function GrabarComprobante() {
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var faFactura = urlParams.get('faFactura');
    var grupo = urlParams.get('grupo');
    var parametros = {
        'Fecha': $('#MBFecha').val(),
        'Total': $('#TextCajaMN').val(),
        'TipoFactura': TipoFactura,
        'NombreC': $('#DCClientes').find("option:selected").text(),
        'Factura': faFactura,
        'Grupo': grupo,
        'TxtConcepto': $('#TxtConcepto').val(),
        'CodigoCli': $('#DCClientes').val(),
        'Trans_No': 200
    };
    //console.log(parametros);
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?GrabarComprobante=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            EnviarEmail(data);
        }
    });

}

function EnviarEmail(parametros) {
    /*var parametros = {
        'CodigoCli': $('#DCClientes').val(),
    };*/

    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?EnviarEmail=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if (data.res == 1) {
                Swal.fire({
                    title: data.Titulo,
                    text: data.Mensaje,
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si!'
                }).then((result) => {
                    if (result.value == true) {
                        EnviarEmailAccept(data);
                    }
                });
            }
        }
    });
}

function EnviarEmailAccept(data){
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?EnviarEmailAccept=true',
        data: { parametros: data },
        dataType: 'json',
        success: function (data) {
            
        }
    });
}



