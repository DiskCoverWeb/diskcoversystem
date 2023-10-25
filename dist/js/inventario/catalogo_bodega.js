// Se ejecuta cuando el documento está listo
$(document).ready(function () {  

    $("#btnGuardar").click(function () {

        var codigoP = $("#codigoP").val();
        var txtConcepto = $("#txtConcepto").val();
        var tipoProducto = $("input[name='cbxProdc']:checked").attr('value');
        
        console.log("Código del producto: " + codigoP);
        console.log("Concepto: " + txtConcepto);
        console.log("Tipo de producto: " + tipoProducto);

        var parametros = {
            "codigo": codigoP,
            "concepto": txtConcepto,
            "tipo": tipoProducto,
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
                    url: '../controlador/inventario/catalogo_bodegaC.php?GuardarConcepto=true',
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
    
});



