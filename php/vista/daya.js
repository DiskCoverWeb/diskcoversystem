
$(document).ready(function () {


});

function Buscar() {
    var selectedOption = $('#selectOption').val();

    $.ajax({
        type: 'POST',
        url: '../vista/dayaC.php?MostrarTabla=true',
        data: { option: selectedOption },
        success: function (data) {
            if (data.status == 200) {
                mostrarTabla(data.datos);
            } else {
                //console.log("no se encontraron datos en la base");
                Swal.fire("No se encontraron datos en la base","",'error');
            }            
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

function mostrarTabla(data) {

    $('#idTabla').empty();

    var columns;
    //var query;

    switch ($('#selectOption').val()) {
        case 'CAT_GFN':
            columns = ['TP', 'Proceso', 'Cmds', 'ID'];
            //query = 'SELECT TP, Proceso, Cmds, ID FROM Catalogo_Proceso WHERE Item = \'999\' AND Nivel = 0 AND TP = \'CAT_GFN\'';
            break;
        case 'CATEG_BPM':
            columns = ['Tipo_Dato', 'Codigo', 'Beneficiario', 'ID'];
            //query = 'SELECT Tipo_Dato, Codigo, Beneficiario, ID FROM Clientes_Datos_Extras WHERE Tipo_Dato = \'CATEG_BPM_ALERGENOS\'';
            break;
        case 'CATEG_BPMT':
            columns = ['Tipo_Dato', 'Codigo', 'Beneficiario', 'ID'];
            //query = 'SELECT Tipo_Dato, Codigo, Beneficiario, ID FROM Clientes_Datos_Extras WHERE Tipo_Dato = \'CATEG_BPMT\'';
            break;
        default:
            console.error('Opción no reconocida');
            return;
    }

    //titulo
    var headerRow = '<thead><tr>';
    for (var i = 0; i < columns.length; i++) {
        headerRow += '<th>' + columns[i] + '</th>';
    }
    headerRow += '<th>Acciones</th>';
    headerRow += '</tr></thead>';
    $('#idTabla').append(headerRow);

    //contenido
    var body = '<tbody>';
    for (var j = 0; j < data.length; j++) {
        body += '<tr>';
        for (var k = 0; k < columns.length; k++) {
            body += '<td>' + data[j][columns[k]] + '</td>';
        }

        body += '<td><button class="btn btn-primary" onclick="editarFila(' + j + ')">Editar</button></td>';
        body += '<td><button class="btn btn-danger" onclick="eliminarFila(' + j + ')">Eliminar</button></td>';
        body += '</tr>';
    }
    body += '</tbody>';
    $('#idTabla').append(body);
}
