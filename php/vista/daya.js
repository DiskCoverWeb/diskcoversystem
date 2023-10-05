$(document).ready(function () {
    buscarDatos();
});

$('#selectOption').change(function () {
    buscarDatos();
});

function buscarDatos() {
    var selectedOption = $('#selectOption').val();

    $.ajax({
        type: 'POST',
        url: '../vista/dayaC.php?MostrarTabla=true',
        data: { option: selectedOption },
        success: function (data) {
            var data = JSON.parse(data);
            if (data.length > 0) {
                mostrarTabla(data);
            } else {
                mostrarLabel();
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

/*
$('#btnBuscar').click(function () {
    buscarDatos();
});*/

function mostrarLabel() {
    $('#alertNoData').css('display', 'block');
    $('#idTabla').css('display', 'none');
}

function mostrarTabla(data) {

    $('#alertNoData').css('display', 'none');
    $('#idTabla').css('display', 'table');
    $('#idTabla').empty();

    var columns;
    //var query;

    switch ($('#selectOption').val()) {
        case 'CAT_GFN':
            columns = ['TP', 'Proceso', 'Cmds'];
            //query = 'SELECT TP, Proceso, Cmds, ID FROM Catalogo_Proceso WHERE Item = \'999\' AND Nivel = 0 AND TP = \'CAT_GFN\'';
            break;
        case 'CATEG_BPM':
        case 'CATEG_BPMT':
        case 'INDIC_NUT':
            columns = ['Tipo_Dato', 'Codigo', 'Beneficiario'];
            //query = 'SELECT Tipo_Dato, Codigo, Beneficiario, ID FROM Clientes_Datos_Extras WHERE Tipo_Dato = \'CATEG_BPM_ALERGENOS\'';
            break;
        default:
            console.error('Opción no reconocida');
            return;
    }

    // Titulo
    var headerRow = '<thead class="table-primary"><tr>';
    for (var i = 0; i < columns.length; i++) {
        headerRow += '<th class="text-light text-center">' + columns[i] + '</th>';
    }
    headerRow += '<th class="text-light text-center">Acciones</th>';
    headerRow += '</tr></thead>';
    $('#idTabla').append(headerRow);

    // Contenido
    var body = '<tbody>';
    for (var j = 0; j < data.length; j++) {
        body += '<tr>';
        for (var k = 0; k < columns.length; k++) {
            if (columns[k] !== 'ID') {
                body += '<td class="text-light text-center">' + data[j][columns[k]] + '</td>';
            }
        }

        var id = data[j]['ID'];

        body += '<td class="text-center">';
        body += '<a href="#" class="btn btn-primary btn-md" style="margin-right:5px" onclick="editarFila(' + id + ')">';
        body += '<span class="glyphicon glyphicon-edit"></span></a>';
        body += '<a href="#" class="btn btn-danger btn-md" onclick="eliminarFila(' + id + ')">';
        body += '<span class="glyphicon glyphicon-trash"></span></a>';
        body += '</td>';

        body += '</tr>';
    }
    body += '</tbody>';
    $('#idTabla').append(body);


}

function eliminarFila(id) {

    Swal.fire({
        title: 'Está seguro que desea eliminar?',
        text: 'No podrás revertir esto.!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '../vista/dayaC.php?AceptarEliminar=true',
                data: { id: id },
                success: function (data) {
                    var data = JSON.parse(data);
                    if (data.status == 200) {
                        Swal.fire("Éxito!, los datos se eliminaron correctamente.", "", 'success');
                        buscarDatos();
                    } else {
                        Swal.fire("Error, no se pudieron eliminar los datos.", "", 'error');
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });

        }
    });
}

function editarFila(id) {
    $.ajax({
        type: 'POST',
        url: '../vista/dayaC.php?MostrarDatosPorId=true',
        data: { id: id },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['status'] == 200) {
                llenarCampos(data['datos']);
            } else {
                mostrarLabel()
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

function llenarCampos(data) {
    var selectedOptionLblE = $('#selectOption option:selected').text();
    $('#selectedOptionLabelE').text(selectedOptionLblE);
    $('#tipoE').val(data[0].Tipo_Dato);
    $('#idE').val(data[0].ID);
    $('#beneficiarioE').val(data[0].Beneficiario);
    $('#codigoE').val(data[0].Codigo);
    $('#modalEditar').modal('show');
}

$('#btnAceptarEditar').click(function () {
    var selectOptionE = $('#tipoE').val();
    var beneficiarioE = $('#beneficiarioE').val()
    var codigoE = $('#codigoE').val()
    var idE = $('#idE').val()

    var parametros = {
        "tipo": selectOptionE,
        "beneficiario": beneficiarioE,
        "codigo": codigoE,
        "id": idE
    };

    Swal.fire({
        title: 'Está seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '../vista/dayaC.php?AceptarEditar=true',
                data: { parametros: parametros },
                success: function (data) {
                    var data = JSON.parse(data);
                    if (data['status'] == 200) {
                        Swal.fire("Éxito!, se actualizo correctamente.", "", 'success');
                        $('#beneficiarioE').val('');
                        $('#codigoE').val('');
                        buscarDatos();
                    } else {
                        Swal.fire("Error, no se actualizo.", "", 'error');
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
            $('#modalEditar').modal('hide');
        }
    });
});

$('#btnAgregar').click(function () {
    var selectedOptionLbl = $('#selectOption option:selected').text();
    $('#selectedOptionLabel').text(selectedOptionLbl);

    $('#modalAgregar').modal('show');
});

$('#btnAceptarAgregar').click(function () {
    var selectOptionA = $('#selectOption').val();
    var beneficiarioA = $('#beneficiarioA').val()
    var codigoA = $('#codigoA').val()

    var parametros = {
        "tipo": selectOptionA,
        "beneficiario": beneficiarioA,
        "codigo": codigoA
    };

    Swal.fire({
        title: 'Está seguro que desea guardar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '../vista/dayaC.php?AceptarAgregar=true',
                data: { parametros: parametros },
                success: function (data) {

                    var data = JSON.parse(data);
                    if (data['status'] == 200) {
                        Swal.fire("Éxito!, se registro correctamente.", "", 'success');
                        $('#beneficiarioA').val('');
                        $('#codigoA').val('');
                        buscarDatos();
                    } else {
                        Swal.fire("Error, no se registro.", "", 'error');
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
            $('#modalAgregar').modal('hide');
        }
    });
});







