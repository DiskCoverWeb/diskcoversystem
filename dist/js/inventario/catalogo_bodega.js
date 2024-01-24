$(document).ready(function () {
    $("input[name='cbxProdc']").prop("checked", false);
    ocultarMsjError();
    llenarListaTipoProcesosGenerales();
    $('#selectTipo').on('change', tipoProceso);
});

var idSeleccionada = null;
var valproducto = null;

$('#txtConcepto').on('click', function () {

});

$('#txtConcepto').on('blur', function () {
    if ($(this).val() === '') {
        $(this).val(valproducto);
    }
});

$("#btnGuardar").click(function () {
    var codigoP = $("#codigoP").val();
    var txtConcepto = $("#txtConcepto").val();
    var tipoProducto = $("input[name='cbxProdc']:checked").val();
    var nivel = $('#selectTipo').val();//nivel
    var tp = $('#tp').val();//tipo de proceso
    var picture = ".";
    if (nivel === '99') {
        picture = $('#picture').val();
        tp = codigoP;
        tipoProducto = $("input[name='cbxReqFA']:checked").val();
    }


    var mensaje = "";

    if (!codigoP.trim()) {
        mensaje = "Ingrese un código de producto válido";
    } else if (!txtConcepto.trim()) {
        mensaje = "Ingrese un concepto válido";
    } else if (!tipoProducto) {
        tipoProducto = ".";
        //mensaje = "Seleccione un tipo de producto";
    }

    if (mensaje) {
        mostrarMsjError(mensaje);
        return;
    }

    ocultarMsjError();
    var parametros = {
        "codigo": codigoP,
        "concepto": txtConcepto,
        "tipo": tipoProducto,
        "nivel": nivel,
        "tp": tp,
        "picture": picture
    };

    verificarExistenciaCodigo(codigoP)
        .then(function (resp) {
            if (resp.existe) {
                var id = resp.id;
                parametros.id = id;
                actualizarProducto(parametros);
            } else {
                guardarNuevoProducto(parametros);
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
        });
});

function mostrarMsjError(mensaje) {
    $('#alertUse').find('.text-danger').text(mensaje);
    $('#alertUse').css('display', 'block');
}

function ocultarMsjError() {
    $('#alertUse').css('display', 'none');
}

function verificarExistenciaCodigo(codigoP) {
    var codigoP = $("#codigoP").val();
    var txtConcepto = $("#txtConcepto").val();
    var tipoProducto = $("input[name='cbxProdc']:checked").val();
    var nivel = $('#selectTipo').val();//nivel
    var tp = $('#tp').val();//tipo de proceso
    return new Promise(function (resolve, reject) {
        var parametros = {
            "codigo": codigoP,
            "concepto": txtConcepto,
            "tipo": tipoProducto,
            "nivel": nivel,
            "tp": tp
        };
        console.log(parametros);
        $.ajax({
            type: 'POST',
            url: '../controlador/inventario/catalogo_bodegaC.php?ListaProductos=true',
            data: { parametros: parametros },
            success: function (data) {
                var responseData = JSON.parse(data);
                if (responseData['status'] == 200 && responseData['datos'].length > 0) {
                    for (var i = 0; i < responseData['datos'].length; i++) {
                        if (responseData['datos'][i].Nivel === 99 && responseData['datos'][i].TP === codigoP) {
                            resolve({ existe: true, id: responseData['datos'][i].ID });
                            return;
                        }
                        if (responseData['datos'][i].Cmds === codigoP) {
                            resolve({ existe: true, id: responseData['datos'][i].ID });
                            return;
                        }
                    }
                    resolve({ existe: false, id: null });
                } else {
                    resolve({ existe: false, id: null });
                }
            },
            error: function (error) {
                console.error('Error en la solicitud AJAX:', error);
                reject(error);
            }
        });
    });
}

function actualizarProducto(parametros) {
    Swal.fire({
        title: 'Está seguro que desea actualizar?',
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
                url: '../controlador/inventario/catalogo_bodegaC.php?EditarProducto=true',
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
                        $('#codigoP').val('');
                        $('#txtConcepto').val('');
                        $("input[name='cbxProdc']").prop("checked", false);
                        if (parametros.nivel === '99') {
                            $('#picture').val('');
                            $('#siFA').prop('checked', false);
                            $('#noFA').prop('checked', false);
                        }
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
}

function guardarNuevoProducto(parametros) {
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
                url: '../controlador/inventario/catalogo_bodegaC.php?GuardarProducto=true',
                data: { parametros: parametros },
                success: function (data) {
                    var responseData = JSON.parse(data);
                    if (responseData['status'] == 200) {
                        Swal.fire({
                            title: "Éxito!, se registro correctamente.",
                            type: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $('#codigoP').val('');
                        $('#txtConcepto').val('');
                        $("input[name='cbxProdc']").prop("checked", false);
                        if (parametros.nivel === '99') {
                            $('#picture').val('');
                            $('#siFA').prop('checked', false);
                            $('#noFA').prop('checked', false);
                        }
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
}

function listarDatos() {
    var nivel = $('#selectTipo').val();//nivel
    var tp = $('#tp').val();//tipo de proceso
    var parametros = {
        "nivel": nivel,
        "tp": tp
    };
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaProductos=true',
        data: { parametros: parametros },
        success: function (data) {
            var responseData = JSON.parse(data);
            if (responseData['status'] == 200 && responseData['datos'].length > 0) {
                llenarAcordeon(responseData['datos']);
                $('#alertNoData').css('display', 'none');
            } else {
                var acordeon = $('#accordion');
                acordeon.empty();
                $('#alertNoData').css('display', 'block');
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}



function llenarAcordeon(datos) {
    var acordeon = $('#accordion');
    acordeon.empty();

    var grupos = {};

    datos.forEach(function (dato) {
        var niveles = "";
        if (dato.Nivel === 99) {
            niveles = dato.TP.split('.');
        } else {
            niveles = dato.Cmds.split('.');
        }
        var nivel1 = niveles[0];

        if (!grupos[nivel1]) {
            grupos[nivel1] = [];
        }

        grupos[nivel1].push(dato);
    });

    Object.keys(grupos).forEach(function (nivel1, index) {
        var panel = $('<div class="panel panel-default">');
        var panelHeading = $('<div class="panel-heading">');
        var panelTitle = $('<h4 class="panel-title">');
        var title = $('<a data-toggle="collapse" data-parent="#accordion" href="#collapse' + index + '">');

        title.html('<span class="glyphicon glyphicon-folder-open text-info icono"></span> ' + nivel1 + ' ' + grupos[nivel1][0].Proceso);

        var panelBody = $('<div id="collapse' + index + '" class="panel-collapse collapse">');

        grupos[nivel1].forEach(function (dato, subindex) {
            var niveles = dato.Cmds.split('.');

            if (niveles.length > 1) {
                var subnivel = niveles.slice(1).join('.');
                var body = $('<div class="panel-body">').text(dato.Cmds + ' ' + dato.Proceso);
                panelBody.append(body);

                body.on('click', function () {
                    clickProducto(dato);
                });
            }
        });

        title.on('click', function () {
            var dato = grupos[nivel1][0];
            clickProducto(dato);
        });

        panelTitle.append(title);
        panelHeading.append(panelTitle);
        panel.append(panelHeading);
        panel.append(panelBody);
        acordeon.append(panel);
    });
}

function clickProducto(dato) {
    ocultarMsjError();
    idSeleccionada = dato.ID;
    valproducto = dato.Proceso;
    if (dato.Nivel === 99) {
        $('#codigoP').val(dato.TP);
        $('#picture').val(dato.Picture);
        var reqFact = dato.DC;
        if (reqFact === 'FA') {
            $('#siFA').prop('checked', true);
        } else {
            $('#noFA').prop('checked', true);
        }
    } else {
        $('#codigoP').val(dato.Cmds);
    }
    $('#txtConcepto').val(dato.Proceso);
    $("input[name='cbxProdc'][value='" + dato.DC + "']").prop("checked", true);
}

$("#btnEliminar").click(function () {
    if (idSeleccionada != null) {
        var codigoP = $('#codigoP').val();
        var nivel = $('#selectTipo').val();//nivel
        var tp = $('#tp').val();//tipo de proceso
        var parametros = {
            "codigo": codigoP,
            "nivel": nivel,
            "tp": tp
        };

        $.ajax({
            type: 'POST',
            url: '../controlador/inventario/catalogo_bodegaC.php?ListaEliminar=true',
            data: { parametros: parametros },
            success: function (data) {
                var responseData = JSON.parse(data);
                if (responseData['status'] == 200) {
                    var listaEliminar = responseData['datos'];
                    if (listaEliminar.length > 0) {
                        var textAreaContent;
                        if (nivel === '99') {
                            var textAreaContent = listaEliminar.map(function (registro) {
                                return registro['TP'] + ' - ' + registro['Proceso'];
                            }).join('\n');
                        } else {
                            var textAreaContent = listaEliminar.map(function (registro) {
                                return registro['Cmds'] + ' - ' + registro['Proceso'];
                            }).join('\n');
                        }
                        Swal.fire({
                            title: 'Está seguro que desea eliminar?',
                            html: 'Se borrará de forma permanente!<br>' +
                                '<textarea disabled id="selectEliminar" rows="2" style="overflow-y: auto; resize: none; margin-top:3px; width:300px" >' + textAreaContent + '</textarea>',
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
                                    url: '../controlador/inventario/catalogo_bodegaC.php?EliminarProducto=true',
                                    data: { parametros: listaEliminar },
                                    success: function (data) {
                                        var data = JSON.parse(data);
                                        if (data['status'] == 200) {
                                            Swal.fire({
                                                title: 'Éxito!, los datos se eliminaron correctamente.',
                                                type: 'success',
                                                timer: 1000,
                                                showConfirmButton: false
                                            });
                                            $('#codigoP').val('');
                                            $('#txtConcepto').val('');
                                            $("input[name='cbxProdc']").prop("checked", false);
                                            if (parametros.nivel === '99') {
                                                $('#picture').val('');
                                                $('#siFA').prop('checked', false);
                                                $('#noFA').prop('checked', false);
                                            }
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
                            } else {
                                idSeleccionada = null;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'No hay datos para eliminar',
                            type: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            },
            error: function (error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    } else {
        Swal.fire({
            title: 'Error, seleccione un producto',
            type: 'error',
            timer: 1000,
            showConfirmButton: false
        });
    }
});

function llenarListaTipoProcesosGenerales() {
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaTipo=true',
        data: {},
        success: function (data) {
            var responseData = JSON.parse(data);
            if (responseData['status'] == '200' && responseData['datos'].length > 0) {
                var datos = responseData['datos'];
                var select = $('#selectTipo');
                select.empty();
                datos.forEach(function (dato) {
                    select.append('<option value="' + dato.TP + '">' + dato.Proceso + '</option>');
                });
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

function tipoProceso() {
    //de selectTipo se obtiene la opcion seleccionada
    var tipoProceso = $('#selectTipo').val();
    //se verifica que tipo de TP es
    switch (tipoProceso) {
        //Tipo de Ingreso
        case '99':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('AR00');
            $('#pictureContainer').css('display', 'block');
            $('#reqFacturaContainer').css('display', 'block');
            $('#checkboxContainer').css('display', 'none');
            break;
        //Tipo de Categorias
        case '00':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('CATEGORI');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'block');
            break;
        //Tipo de Proveedor
        case '98':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('TIPOPROV');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        //Tipo de Empaque
        case '97':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('EMPAQUE');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        //Tipo de Estado de Transporte
        case '96':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('ESTTRANS');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        case '94':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('MOTIVOS');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        case '95':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('AREAEGRE');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        default:
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('CATEGORI');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
    };
    listarDatos();

}






