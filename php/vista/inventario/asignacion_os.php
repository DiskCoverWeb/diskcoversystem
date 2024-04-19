<script>

    let diccionarioTP =
        [
            { 'TP': 'BENEFICI', 'inputname': 'tipoBenef' },
            { 'TP': 'VULNERAB', 'inputname': 'vuln' },
            { 'TP': 'POBLACIO', 'inputname': 'tipoPobl' },
            { 'TP': 'ACCIONSO', 'inputname': 'acciSoci' },
            { 'TP': 'ATENCION', 'inputname': 'tipoAten' },
            { 'TP': 'ENTREGA', 'inputname': 'tipoEntrega' },
            { 'TP': 'ESTADO', 'inputname': 'tipoEstado' },
            { 'TP': 'FRECUENC', 'inputname': 'frecuencia' }
        ];

    $(document).ready(function () {
        beneficiario();

        $('#beneficiario').on('select2:select', function (e) {
            var data = e.params.data;//Datos beneficiario seleccionado
            llenarDatos(data);

        });

        autocoplet_pro();
        autocoplet_pro2();

        $('#ddl_producto').on('select2:select', function (e) {
            var data = e.params.data.data;
            $('#grupProd').append($('<option>', { value: data[0].Codigo_Inv, text: data[0].Producto, selected: true }));
            $('#txt_referencia').val(data[0].Codigo_Inv);
        });

    });

    //Metodos
    function beneficiario() {
        $('#beneficiario').select2({
            placeholder: 'Beneficiario',
            ajax: {
                url: '../controlador/inventario/asignacion_osC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        Beneficiario: true
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });
    }

    function cambiar_cantidad() {
        var can = $('#txt_cantidad2').val();
        $('#cant').val(can);
        $('#modal_cantidad').modal('hide');
        $('#cant').focus();
    }

    function autocoplet_pro() {
        $('#ddl_producto').select2({
            placeholder: 'Seleccione una producto',
            ajax: {
                url: '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function autocoplet_pro2() {
        $('#grupProd').select2({
            placeholder: 'Seleccione una producto',
            ajax: {
                url: '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function show_producto() {
        $('#modal_producto').modal('show');
    }

    function show_cantidad() {
        $('#modal_cantidad').modal('show');
    }

    function agregar() {
        var datos = {
            'Item': $('#tbl_body tr').length + 1,
            'Producto': $('#grupProd').val(),
            'Cantidad': $('#cant').val(),
            'Comentario': $('#comeAsig').val()
        };

        //validar datos
        if (datos.Producto == '') {
            swal.fire('Error', 'Debe seleccionar un producto', 'error');
            return;
        }
        if (datos.Cantidad == '' || datos.Cantidad <= 0) {
            swal.fire('Error', 'Debe ingresar una cantidad valida', 'error');
            return;
        }

        var fila = '<tr>' +
            '<td>' + datos.Item + '</td>' +
            '<td>' + datos.Producto + '</td>' +
            '<td>' + datos.Cantidad + '</td>' +
            '<td>' + datos.Comentario + '</td>' +
            '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar(this)"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';

        //agregar la fila
        $('#tbl_body').append(fila);
    }

    function eliminar(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    function limpiar() {
        //$('#form_asignacion').trigger('reset');
        $('#tbl_body').empty();
    }

    function llenarDatos(datos) {
        $('#beneficiario').val(datos.Beneficiario);
        $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
        $('#tipoEstado').val(datos.CodigoA);//Tipo de Estado
        $('#tipoEntrega').val(datos.CodigoACD);//Tipo de Entrega
        $('#horaEntrega').val(datos.Hora_Entrega); //Hora de Entrega
        var fecha = new Date(datos.Fecha_Atencion);
        const opciones = { weekday: 'long' };
        const diaEnLetras = new Intl.DateTimeFormat('es-ES', opciones).format(fecha);
        $('#diaEntr').val(diaEnLetras.toUpperCase());//Dia de Entrega
        $('#frecuencia').val(datos.Envio_No);//Frecuencia
        $('#tipoBenef').val(datos.Beneficiario);//Tipo de Beneficiario
        $('#totalPersAten').val(datos.No_Soc);//Total, Personas Atendidas
        $('#tipoPobl').val(datos.Area);//Tipo de Poblacion
        $('#acciSoci').val(datos.Acreditacion);//Accion Social
        $('#vuln').val(datos.Tipo);//Vulnerabilidad
        $('#tipoAten').val(datos.Cod_Fam);//Tipo de Atencion
        $('#CantGlobSugDist').val(datos.Salario);//Cantidad global sugerida a distribuir
        $('#CantGlobDist').val(datos.Descuento);//Cantidad global a distribuir
        const params = [datos.CodigoA, datos.CodigoACD, datos.Envio_No, datos.Beneficiario, datos.Area, datos.Acreditacion, datos.Tipo, datos.Cod_Fam];
        datosExtras(params);


    }

    function datosExtras(param) {
        $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?datosExtra=true',
            type: 'POST',
            dataType: 'json',
            data: { param: param },
            success: function (data) {
                if (data.result == 1) {
                    const tmp = relacionarListas(data.datos, diccionarioTP);
                    for (let i = 0; i < tmp.length; i++) {
                        $('#' + tmp[i].inputname).val(tmp[i].Proceso);
                        if (tmp[i].Color != '.') {
                            const color = tmp[i].Color.substring(4);
                            console.log(color);
                            $('#rowGeneral').css('background-color', '#' + color);
                        }
                    }
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    /**
     * Relaciona las listas de datos
     * @param {Array} lista1 los datos extras obtenidos de la tabla Catalogo_Procesos
     * @param {Array} lista2 la relacion que tienen cada Tipo de Proceso con el input que se muestra
     * @returns {Array}
     */
    function relacionarListas(lista1, lista2) {
        const relacion = {};
        lista1.forEach(element => {
            const tp = element.TP;
            relacion[tp] = { ...element };
        });

        lista2.forEach(element2 => {
            const tp = element2.TP;
            if (relacion[tp]) {
                relacion[tp] = { ...relacion[tp], ...element2 };
            }
        });

        return Object.values(relacion);
    }

</script>
<style>
    label {
        text-align: left;
    }

    input,
    select {
        width: 98%;
        max-width: 98%;
        text-align: left;
    }

    .form-group {
        padding: 0;
        display: flex;
        align-items: center;
        margin-bottom: 2px;
        /* Alinea los elementos verticalmente en el centro */
    }

    .form-group label {
        flex-basis: auto;
        flex-grow: 0;
        /* No permite que el label crezca */
        flex-shrink: 0;
        /* No permite que el label se encoja */
        margin-right: 10px;
        /* Añade un poco de espacio entre el label y el input */
    }

    /* Ajustar el ancho de los inputs si es necesario */
    .form-group input[type="text",
    type="datetime-local"] {
        flex-grow: 1;
        /* Permite que el input crezca para ocupar el espacio disponible */
    }

    .alineado {
        padding-top: 1vh;
    }

    @media(max-width: 768px) {
        #container-estado {
            padding: 0vw 3vw 0vw 3vw !important;
        }

        .centrar {
            text-align: center;
        }
    }
</style>
<form id="form_asignacion">
    <div class="row" style="padding: 1vw; background-color: rgb(254,252,172); border: 1px solid;" id="rowGeneral">
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="diaEntr">
                            Día de Entrega
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="diaEntr" id="diaEntr" class="form-control input-xs">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="beneficiario" style="flex-basis: 35%;">
                            Beneficiario/ USUARIO:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <select name="beneficiario" id="beneficiario" class="form-control input-xs">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="fechAten">
                            Fecha de Atención
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="date" name="fechAten" id="fechAten" class="form-control input-xs">
                    </div>
                </div>
            </div>
        </div>
        <div class="row alineado">
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoEstado">
                            Estado
                        </label>
                    </div>
                    <div class="col-sm-6" style="padding: 0" id="container-estado">
                        <input type="tipoEstado" name="tipoEstado" id="tipoEstado" class="form-control input-xs">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoEntrega">
                            Tipo de Entrega
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="tipoEntrega" id="tipoEntrega" class="form-control input-xs">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoEntrega">
                            Hora de Entrega
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="frecuencia">
                            Frecuencia
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="frecuencia" id="frecuencia" class="form-control input-xs">
                    </div>
                </div>
            </div>
        </div>
        <div class="row alineado">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoBenef">
                            Tipo de Beneficiario:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="tipoBenef" id="tipoBenef" class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="totalPersAten" style="font-size: 13px; white-space: nowrap;">
                            Total, Personas Atendidas:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="totalPersAten" id="totalPersAten" class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoPobl" style="flex-basis: 50%;">
                            Tipo de Población:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="tipoPobl" id="tipoPobl" class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="acciSoci" style="flex-basis: 50%;">
                            Acción Social:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="acciSoci" id="acciSoci" class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="vuln" style="flex-basis: 50%;">
                            Vulnerabalidad:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="vuln" id="vuln" class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoAten" style="flex-basis: 50%;">
                            Tipo de Atención:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="tipoAten" id="tipoAten" class="form-control input-xs">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-8 text-center">
                        <label for="CantGlobSugDist" style="font-size: 13px; white-space: nowrap;">
                            Cantidad global sugerida a distribuir
                        </label>
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="CantGlobSugDist" id="CantGlobSugDist" readonly style=""
                            class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 text-center">
                        <label for="CantGlobDist" style="font-size: 13px; white-space: nowrap;">
                            Cantidad global a distribuir
                        </label>
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="CantGlobDist" id="CantGlobDist" style=""
                            class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="infoNutr">
                            Información Nutricional
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <textarea name="infoNutr" id="infoNutr" rows="3" class="form-control input-xs">
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row centrar">
                    <label for="comeGeneAsig">
                        Comentario General de Asignación
                    </label>
                </div>
                <div class="row">
                    <div class="input-group">
                        <textarea name="comeGeneAsig" id="comeGeneAsig" rows="6"
                            placeholder="comentario general de clasificación..." class="form-control">
                            </textarea>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-sm" onclick=""><i class="fa fa-save"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 1rem">
        <div class="col-sm-5" style="display: flex; justify-content: center; align-items: center;">
            <div class="col-sm-5" style="">
                <button type="button" class="btn btn-default" onclick="show_producto();"><img
                        src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
            </div>
            <div class="col-sm-7">
                <select name="grupProd" id="grupProd" class="form-control input-xs"></select>
            </div>
        </div>
        <div class="col-sm-1">
            <label for="stock">
                Stock
            </label>
            <input type="text" name="stock" id="stock" class="form-control input-xs">
        </div>
        <div class="col-sm-3" style="display: flex; justify-content: center; align-items: center;">
            <div class="col-sm-5 text-right">
                <button type="button" style="width: initial;" class="btn btn-default" onclick="show_cantidad()"
                    id="btn_cantidad">
                    <img src="../../img/png/kilo.png" />
                    <br>
                    <b>Cantidad</b>
                </button>
            </div>
            <div class="col-sm-7 text-left">
                <input type="number" name="cant" id="cant" class="form-control input-xs">
            </div>
        </div>
        <div class="col-sm-3">
            <label for="comeAsig">
                Comentario de Asignación
            </label>
            <input type="text" name="comeAsig" id="comeAsig" class="form-control input-xs">
        </div>
    </div>
    <div class="row" style="text-align: right; padding-right: 1.5vw;">
        <button type="button" class="btn btn-primary btn-sm" onclick="agregar();"><b>Agregar</b></button>
        <button type="button" class="btn btn-primary btn-sm" onclick="limpiar();"><b>Limpiar</b></button>
    </div>
</form>

<div class="row" id="panel_add_productos"><!-- DEFINIR EL ID SEGUN SEA NECESARIO -->
    <div class="col-sm-12">
        <div class="box">
            <div class="card_body" style="background:antiquewhite;">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:7%;">ITEM</th>
                                    <th>PRODUCTO</th>
                                    <th>CANTIDAD</th>
                                    <th>COMENTARIO DE ASIGNACIÓN</th>
                                    <th>ELIMINAR</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="modal_producto" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Producto</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
                <div class="row">
                    <div class="col-md-3">
                        <b>Referencia:</b>
                        <input type="text" name="txt_referencia" id="txt_referencia" class="form-control input-sm"
                            readonly="">
                    </div>
                    <div class="col-sm-9">
                        <b>Producto:</b><br>
                        <select class="form-control" id="ddl_producto" name="ddl_producto" style="width: 100%;">
                            <option value="">Seleccione una producto</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="background-color:antiquewhite;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_cantidad" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cantidad</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
                <b>Cantidad</b>
                <input type="" name="txt_cantidad2" id="txt_cantidad2" class="form-control" placeholder="0"
                    onblur="cambiar_cantidad()">
            </div>
            <div class="modal-footer" style="background-color:antiquewhite;">
                <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>