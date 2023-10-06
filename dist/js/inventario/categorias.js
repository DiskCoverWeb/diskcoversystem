// Se ejecuta cuando el documento está listo
$(document).ready(function () {
    listarDatos();
});

// Se ejecuta cuando cambia la opción en el select con id 'selectOption'
$('#selectOption').change(function () {
    listarDatos();
});

// Función para realizar una solicitud AJAX y mostrar los resultados en una tabla
function listarDatos() {
    var selectedOption = $('#selectOption').val();
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/categoriasC.php?MostrarTabla=true',
        data: { option: selectedOption },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['status'] == 200) {
                mostrarTabla(data['datos']);
            } else {
                mostrarLabel();
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

// Función para mostrar un mensaje cuando no hay datos
function mostrarLabel() {
    $('#alertNoData').css('display', 'block');
    $('#idTabla').css('display', 'none');
}

// Función para mostrar datos en una tabla
function mostrarTabla(data) {
    $('#alertNoData').css('display', 'none');
    $('#idTabla').css('display', 'table');
    $('#idTabla').empty();

    var columns;
    var columns = Object.keys(data[0]).filter(function (column) {
        return column !== 'ID';
    });

    // Titulo de la tabla
    var headerRow = '<thead class="table-primary"><tr>';
    for (var i = 0; i < columns.length; i++) {
        headerRow += '<th class="text-light text-center">' + columns[i] + '</th>';
    }
    headerRow += '<th class="text-light text-center">Acciones</th>';
    headerRow += '</tr></thead>';
    $('#idTabla').append(headerRow);

    // Contenido de la tabla
    var body = '<tbody>';
    for (var j = 0; j < data.length; j++) {
        body += '<tr>';
        for (var k = 0; k < columns.length; k++) {
            if (columns[k] !== 'ID') {
                body += '<td data-label="' + columns[k] + '" class="text-light text-center">' + data[j][columns[k]] + '</td>';
            }
        }
        var id = data[j]['ID'];

        body += '<td class="text-center btn-container">';
        body += '<a href="#" class="btn btn-primary btn-md" style="margin-right:5px" onclick="editarFila(' + id + ')">';
        body += '<span class="glyphicon glyphicon-edit"></span></a>';
        body += '<a href="#" class="btn btn-danger btn-md" onclick="eliminarFila(' + id + ')">';
        body += '<span class="glyphicon glyphicon-trash"></span></a>';
        body += '</td>';
        body += '</div>';
        body += '</td>';
        body += '</tr>';
    }
    body += '</tbody>';
    $('#idTabla').append(body);
}

/*/ Agregar diseño responsivo para pantallas pequeñas
**/
// Manejador de evento al hacer clic en el botón de eliminar
function eliminarFila(id) {
    Swal.fire({
        title: 'Está seguro que desea eliminar?',
        text: 'No podrás revertir esto!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: 'POST',
                url: '../controlador/inventario/categoriasC.php?AceptarEliminar=true',
                data: { id: id },
                success: function (data) {
                    var data = JSON.parse(data);
                    if (data['status'] == 200) {
                        Swal.fire({
                            title: 'Éxito!, los datos se eliminaron correctamente.',
                            type: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        listarDatos();
                    } else {
                        Swal.fire({
                            title: 'Error, no se pudieron eliminar los datos.',
                            type: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });

        }
    });
}

// Manejador de evento al hacer clic en el botón de editar
function editarFila(id) {
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/categoriasC.php?MostrarDatosPorId=true',
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

// Función para llenar campos en el Modal Editar
function llenarCampos(data) {
    var selectedOptionLblE = $('#selectOption option:selected').text();
    $('#selectedOptionLabelE').text(selectedOptionLblE);
    $('#tipoE').val(data[0].Tipo_Dato);
    $('#idE').val(data[0].ID);
    $('#beneficiarioE').val(data[0].Beneficiario);
    $('#codigoE').val(data[0].Codigo);
    $('#modalEditar').modal('show');
}

// Manejador de evento al hacer clic en el botón de aceptar para editar datos
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
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: 'POST',
                url: '../controlador/inventario/categoriasC.php?AceptarEditar=true',
                data: { parametros: parametros },
                success: function (data) {
                    var data = JSON.parse(data);
                    if (data['status'] == 200) {
                        Swal.fire({
                            title: 'Éxito!, se actualizó correctamente.',
                            type: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $('#beneficiarioE').val('');
                        $('#codigoE').val('');
                        listarDatos();
                    } else {
                        Swal.fire({
                            title: 'Error, no se actualizó.',
                            type: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        });
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

// Manejador de evento al hacer clic en el botón de agregar
$('#btnAgregar').click(function () {
    var selectedOptionLbl = $('#selectOption option:selected').text();
    $('#selectedOptionLabel').text(selectedOptionLbl);
    $('#codigoA').val(generarCodigoRandom(5));
    $('#modalAgregar').modal('show');
});

// Manejador de evento al hacer clic en el botón de agregar en el menú colapsable
$('#btnAgregarCollapse').click(function () {
    event.preventDefault();
    var selectedOptionLbl = $('#selectOption option:selected').text();
    $('#selectedOptionLabel').text(selectedOptionLbl);
    $('#codigoA').val(generarCodigoRandom(5));
    $('#modalAgregar').modal('show');
});

// Manejador de evento al hacer clic en el botón de aceptar para agregar datos
$('#btnAceptarAgregar').click(function () {
    var selectOptionA = $('#selectOption').val();
    var beneficiarioA = $('#beneficiarioA').val();
    var codigoA = $('#codigoA').val();
    var parametros = {
        "tipo": selectOptionA,
        "beneficiario": beneficiarioA,
        "codigo": codigoA
    };
    Swal.fire({
        title: 'Está seguro que desea guardar?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: 'POST',
                url: '../controlador/inventario/categoriasC.php?AceptarAgregar=true',
                data: { parametros: parametros },
                success: function (data) {

                    var data = JSON.parse(data);
                    if (data['status'] == 200) {
                        Swal.fire({
                            title: "Éxito!, se registro correctamente.",
                            type: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $('#beneficiarioA').val('');
                        $('#codigoA').val('');
                        listarDatos();
                    } else {
                        Swal.fire({
                            title: 'Error, no se registró.',
                            type: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        });
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

// Función para generar un código alfanumérico de longitud dada
function generarCodigoRandom(length) {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}
