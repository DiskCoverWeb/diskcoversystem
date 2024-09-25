<script>

    // window.addEventListener('beforeunload', function (e) {
    //     // Mensaje personalizado (no será visible en la mayoría de los navegadores)
    //     const message = "¿Estás seguro de que deseas abandonar la página?";
    //     e.preventDefault();
    //     e.returnValue = message;
    //     return message;
    // });
    function eliminarTildes(cadena) {
        return cadena.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }   

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

        const today = new Date();
        const dayOfWeek = today.toLocaleDateString('es-Mx', { weekday: 'short' });
        const DiaActual =  eliminarTildes(dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1).toLowerCase());

        $('#diaEntr').val(DiaActual);

        if($('#diaEntr').val()!='')
        {
            // console.log(DiaActual);
            initPAge();
            beneficiario();

             const selectElement = document.getElementById('diaEntr');
            let previousValue = selectElement.value; // Guardar valor actual

            selectElement.addEventListener('change', function (e) {
                const confirmChange = confirm("¿Estás seguro de que deseas cambiar la opción es posible que los nuevos Beneficiario agregados se pierdan?");
                
                if (!confirmChange) {
                    selectElement.value = previousValue;
                } else {
                    previousValue = selectElement.value;
                    initPAge();
                    $('#beneficiario').empty();
                }
            });

        }




        // beneficiario();
        beneficiario_new();
        // tipoCompra();
        $('#beneficiario').on('select2:select', function (e) {
            var data = e.params.data;//Datos beneficiario seleccionado
            // console.log(data.data);
            tipoCompra(data.data)
            listaAsignacion();

        });

        autocoplet_pro();
        autocoplet_pro2();

        $('#ddl_producto').on('select2:select', function (e) {
            var data = e.params.data.data;
            // console.log(data);
            $('#grupProd').append($('<option>', { value: data[0].Codigo_Inv, text: data[0].Producto, selected: true }));
            $('#txt_referencia').val(data[0].Codigo_Inv);
        });

    });


    function initPAge()
    {
        var parametros = 
        {
            'dia':$('#diaEntr').val(),
        }
         $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?initPAge=true',
            type: 'POST',
            dataType: 'json',
            data: { parametros: parametros },
            success: function (data) {
               
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    //Metodos
    function beneficiario() {
        $('#beneficiario').select2({
            placeholder: 'Beneficiario',
            ajax: {
                url: '../controlador/inventario/asignacion_osC.php?Beneficiario=true',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        dia: $('#diaEntr').val(),
                    }
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

      function beneficiario_new() {
        $('#beneficiario_new').select2({
            placeholder: 'Beneficiario',
            ajax: {
                url: '../controlador/inventario/asignacion_osC.php?Beneficiario_new=true',
                dataType: 'json',
                dropdownParent: $('#modal_addBeneficiario'),
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                    }
                },
                processResults: function (data) {
                    return {
                        results: data
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
        tipo = $('#tipoCompra').val();
        url_ = '';
        if(tipo=='84.02')
        {
            // console.log('sss');
            let url_ = '../controlador/inventario/asignacion_osC.php?autocom_pro=true';
            // console.log(url_);
        }else
        {
            let url_ = '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true';
            // console.log(url_);

        }
        $('#ddl_producto').select2({
            placeholder: 'Seleccione una producto',
            ajax: {
                url: url_,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    console.log(url_);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function autocoplet_pro2() {
        tipo = $('#tipoCompra').val();
        var url_ = '';
        if(tipo=='84.02')
        {
            console.log('sss');
            url_ = '../controlador/inventario/asignacion_osC.php?autocom_pro=true';
            console.log(url_);
        }else
        {
            url_ = '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true';
            console.log(url_);
            
        }

        $('#grupProd').select2({
            placeholder: 'Seleccione una producto',
            ajax: {
                url: url_,
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
        if($('#beneficiario').val()=='' || $('#beneficiario').val()==null)
        {
            Swal.fire("","Seleccione Beneficiario","info").then(function(){
                return false;
            })
        }

        var stock = $('#stock').val();
        var cant =  $('#cant').val();
        console.log(cant)
        console.log(stock);
        if(cant=='' || cant==null || cant <= 0)
        { 
            Swal.fire("Cantidad no valida","","info");
            return false;
        }
        if(parseFloat(cant)> parseFloat(stock))
        { 
            Swal.fire("Cantidad supera al stock","","info")
            return false;
        }
        var datos = {
            'Codigo': $('#grupProd').val(),
            'Producto': $('#grupProd option:selected').text(),
            'Cantidad': $('#cant').val(),
            'Comentario': $('#comeAsig').val(),
            'beneficiarioCodigo':$('#beneficiario').val(), 
            'beneficiarioN':$('#beneficiario option:selected').text(),   
            'FechaAte':$('#fechAten').val(),   
            'asignacion':$('#tipoCompra').val(),
        };       

        if($('#tipoCompra').val()=='' || $('#tipoCompra').val()==null)
        {
            Swal.fire("Seleccione Tipo de asignacion","","info")
             return false;
        }


        $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?addAsignacion=true',
            type: 'POST',
            dataType: 'json',
            data: { param: datos },
            success: function (data) {
                if(data==1)
                {
                    listaAsignacion();
                    limpiar();
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function eliminar(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    function limpiar() {
        $("#grupProd").empty();
        $("#ddl_producto").empty();
        $("#txt_referencia").val("");
        $('#stock').val("");
        $('#cant').val("");
        $("#comeAsig").val("");
    }

    function removeOptionByValue(value) {
        var selectElement = document.getElementById('tipoCompra');
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === value) {
                selectElement.remove(i);
                break;
            }
        }
    }
    

    function onclicktipoCompra()
    {
        $('#modal_tipoCompra').modal('show');
    }

    function tipoCompra(benefi)
    {
         $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?tipo_asignacion=true',
            type: 'POST',
            dataType: 'json',
            // data: { param: datos },
            success: function (data) {

                console.log(data);

            var op = '';
            var option = '';
            data.forEach(function(item,i){
// console.log(item);
              option+= '<div class="col-md-6 col-sm-6">'+
                          '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+item.Picture+'.png" onclick="cambiar_empaque(\''+item.ID+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                          '<b>'+item.Proceso+'</b>'+
                        '</div>';

               op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
            })

            $('#tipoCompra').html(op); 
            $('#pnl_tipo_empaque').html(option);   

            llenarDatos(benefi);


               // llenarComboList(data,'tipoCompra');
               // console.log(data);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

function llenarDatos(datos) {
        // console.log(datos);
         // await tipoCompra();
        
       // $('#beneficiario').val(datos.Beneficiario);
        // $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
        $('#tipoEstado').val(datos.Estado);//Tipo de Estado
        $('#tipoEntrega').val(datos.TipoEntega);//Tipo de Entrega
        $('#horaEntrega').val(datos.Hora); //Hora de Entrega
        // $('#diaEntr').val(datos.Dia_Entrega.toUpperCase());//Dia de Entrega
        $('#frecuencia').val(datos.Frecuencia);//Frecuencia
        $('#tipoBenef').val(datos.TipoBene);//Tipo de Beneficiario
        $('#totalPersAten').val(datos.No_Soc);//Total, Personas Atendidas
        $('#tipoPobl').val(datos.Area);//Tipo de Poblacion
        $('#acciSoci').val(datos.AccionSocial);//Accion Social
        $('#vuln').val(datos.vulnerabilidad);//Vulnerabilidad
        $('#tipoAten').val(datos.TipoAtencion);//Tipo de Atencion
        $('#CantGlobSugDist').val(datos.Salario);//Cantidad global sugerida a distribuir
        $('#CantGlobDist').val(datos.Descuento);//Cantidad global a distribuir
        $('#infoNutr').val(datos.InfoNutri);
        const params = [datos.CodigoA, datos.CodigoACD, datos.Envio_No, datos.Beneficiario, datos.Area, datos.Acreditacion, datos.Tipo, datos.Cod_Fam];
        color = datos.Color.replace('Hex_','');
        $('#rowGeneral').css('background-color', '#' + color);
        $('#img_tipoBene').attr('src','../../img/png/'+datos.Picture+'.png')

         datos.asignaciones_hechas.forEach(function(item,i){
            removeOptionByValue(item.No_Hab)
         })

      //  datosExtras(params);


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


    function listaAsignacion() {
        var param = {
            'beneficiario':$('#beneficiario').val(),
        }
        $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?listaAsignacion=true',
            type: 'POST',
            dataType: 'json',
            data: { param: param },
            success: function (data) {
                $('#tbl_body').html(data.tabla);
                $('#CantGlobDist').val(data.cantidad);
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

    function eliminar_linea(id)
    {
         Swal.fire({
         title: 'Esta seguro?',
         text: "Esta usted seguro de que quiere borrar este registro!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
             if (result.value==true) {
                eliminarLinea(id)
             }  
       })   

    }

    function eliminarLinea(id)
    {
        var parametros = 
        {
            'id':id,
        }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/asignacion_osC.php?eliminarLinea=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                Swal.fire( '','Registro eliminado','success').then(function(){ listaAsignacion();});
              }

          }
        });
    }

    function buscar_producto(codigo)
    {
        var parametros = {
            'codigo':codigo,
        }
        $.ajax({
            type: "POST",
            url:   '../controlador/inventario/asignacion_osC.php?Codigo_Inv_stock=true',
            data:{parametros:parametros},
           dataType:'json',
            success: function(data)
            {
                if(data.respueta)
                {
                    $('#stock').val(data.datos.Stock);
                }
              console.log(data);
            }
        });
    }

    function llenarCamposPoblacion() {
        var Codigo = $('#beneficiario').val();
        if(Codigo=='' || Codigo==null)
        {
            Swal.fire("Seleccione un beneficiario","","info")
            return false;
        }
        $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?llenarCamposPoblacion=true',
            type: 'post',
            dataType: 'json',
            data: { valor: Codigo },
            success: function (datos) {
                $('#modalBtnGrupo').modal('show');
                $('#tbl_body_poblacion').html(datos);
               console.log(datos);
            }
        });
    }

    function asignar_beneficiario()
    {
        id = $('#beneficiario_new').val();
        parametros = {
            'cliente':id,
        }
         $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?asignar_beneficiario=true',
            type: 'post',
            dataType: 'json',
            data: { parametros: parametros },
            success: function (datos) {
                if(datos)
                {
                    Swal.fire('Beneficiario Agregado','','success').then(function(){
                        $('#modal_addBeneficiario').modal('hide');
                    })
                }
            }
        });
    }

    function eliminar_asignacion_beneficiario()
    {
        id = $('#beneficiario').val();
        if(id=='' || id== null)
        {
            Swal.fire("Seleccione un beneficiario","","info");
            return false;
        }
        var parametros = {
            'cliente':id,
        }
        Swal.fire({
                 title: 'Esta seguro?',
                 text: "Esta usted seguro de quitar este registro!",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!'
               }).then((result) => {
                 if (result.value==true) {
                    $.ajax({
                        url: '../controlador/inventario/asignacion_osC.php?eliminar_asignacion_beneficiario=true',
                        type: 'post',
                        dataType: 'json',
                        data: { parametros: parametros },
                        success: function (datos) {
                            if(datos)
                            {
                                Swal.fire('Beneficiario Eliminado','','success');
                                $('#beneficiario').empty();
                            }
                        }
                    });     
                 }
               })



       
                     
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
    .form-group input[type="text",type="datetime-local"] {
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
<script type="text/javascript">
    function guardar()
    {
        ben = $('#beneficiario').val();
        distribuir = $('#CantGlobDist').val();
        if(ben=='' || ben==null){Swal.fire("","Seleccione un Beneficiario","info");return false;}
        if(distribuir==0 || distribuir==''){ Swal.fire("","No se a agregado nigun grupo de producto","info");return false;}
        var parametros = {
            'beneficiario':ben,
            'fecha':$('#fechAten').val(),
        }
         $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?GuardarAsignacion=true',
            type: 'post',
            dataType: 'json',
            data: { parametros: parametros },
            success: function (datos) {
                if(datos==1)
                {
                    Swal.fire("Asignacion Guardada","","success").then(function(){
                        location.reload();
                    });
                }

            }
        });
    }

    function add_beneficiario(){
        $('#modal_addBeneficiario').modal('show');

    }
    // function eliminar_beneficiario(){
    //     beneficiario = $('#beneficiario').val();
    //     if(beneficiario=='' || beneficiario==null)
    //     {
    //         Swal.fire('Seleccione un beneficiario','','error');
    //         return false;
    //     }
    // }

</script>
<div class="row mb-2">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
        </div>
         <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button title="Guardar" class="btn btn-default" onclick="guardar()">
                <img src="../../img/png/grabar.png">
            </button>
        </div>
    </div>
       
</div>
<form id="form_asignacion">
    <div class="row" style="padding: 1vw; background-color: #fffacd; border: 1px solid;" id="rowGeneral">
        <div class="row">

            <div class="col-sm-2">
                <div class="row">                    
                    <div class="col-md-12 col-sm-6 col-xs-6" style="padding-right: 0px;">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Día Entrega</b>
                            </div>
                            <select class="form-control input-xs" id="diaEntr">
                                <option value="Lun">Lunes</option>
                                <option value="Mar">Martes</option>
                                <option value="Mie">Miercoles</option>
                                <option value="Jue">Jueves</option>
                                <option value="Vie">Viernes</option>
                                <option value="Sáb">Sabado</option>
                                <option value="Dom">Domingo</option>
                            </select>
                            <!-- <input type="text" name="diaEntr" id="diaEntr" class="form-control input-xs"> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="row">                   
                     <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Beneficiario/ Usuario:</b>
                            </div>
                             <select name="beneficiario" id="beneficiario" class="form-control input-xs" onchange="listaAsignacion()"></select>
                             <span class="input-group-btn">
                            <button type="button" class="" onclick="add_beneficiario()">
                                <img id="img_tipoCompra"  src="../../img/png/mostrar.png" style="width: 20px;" />
                            </button>
                        </span>
                        <span class="input-group-btn">
                            <button type="button" class="" onclick="eliminar_asignacion_beneficiario()">
                                <img id="img_tipoCompra"  src="../../img/png/close.png" style="width: 20px;" />
                            </button>
                        </span>
                        </div>
                    </div>
                </div>
            </div>           
            <div class="col-sm-3">
                <div class="row">                   
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">                   
                                <i class="fa fa-calendar"></i>
                                <b>Fecha de Atención:</b>
                            </div>
                            <input type="date" name="fechAten" id="fechAten" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-sm-2">
                 <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">                          
                        <span class="input-group-btn">
                            <button type="button" class="" onclick="onclicktipoCompra()">
                                <img id="img_tipoCompra"  src="../../img/png/TipoCompra.png" style="width: 20px;" />
                            </button>
                        </span>
                         <select name="tipoCompra" id="tipoCompra" class="form-control input-xs" onchange="autocoplet_pro2()">
                         </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row alineado">
            <div class="col-sm-3">
                <div class="row">                   
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">  
                                <b> Estado</b>
                            </div>
                        <input type="tipoEstado" name="tipoEstado" id="tipoEstado" class="form-control input-xs">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">  
                                <b> Tipo Entrega</b>
                            </div>
                        <input type="text" name="tipoEntrega" id="tipoEntrega" class="form-control input-xs">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">                    
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">  
                                <b><i class="fa fa-clock-o"></i> Hora de Entrega</b>
                            </div>
                        <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">  
                                <b>Frecuencia</b>
                            </div>
                        <input type="text" name="frecuencia" id="frecuencia" class="form-control input-xs">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row alineado">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b> Tipo de Beneficiario:</b>
                            </div>
                        <input type="text" name="tipoBenef" id="tipoBenef" class="form-control input-xs" readonly>
                        <span class="input-group-btn">
                            <button type="button" class="">
                                <img id="img_tipoBene"  src="../../img/png/cantidad_global.png" style="width: 20px;" />
                            </button>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Total, Personas Atendidas:</b>
                            </div>
                            <input type="text" name="totalPersAten" id="totalPersAten" class="form-control input-xs" readonly>
                            <span class="input-group-btn">
                            <button type="button" class="" onclick="llenarCamposPoblacion()">
                                <img id="img_tipoBene"  src="../../img/png/Personas_atendidas.png" style="width: 32px;" />
                            </button>
                        </span>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">                   
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Tipo de Población:</b>
                            </div>
                            <input type="text" name="tipoPobl" id="tipoPobl" class="form-control input-xs" readonly>
                        </div>
                    </div>
                </div> -->
                <div class="row">                    
                     <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>  Acción Social:</b>
                            </div>
                            <input type="text" name="acciSoci" id="acciSoci" class="form-control input-xs" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">                   
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b> Vulnerabalidad:</b>
                            </div>
                            <input type="text" name="vuln" id="vuln" class="form-control input-xs" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b> Tipo de Atención:</b>
                            </div>
                            <input type="text" name="tipoAten" id="tipoAten" class="form-control input-xs" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6"  style="font-size: 13px; ">
                        <div class="row">
                            <div class="col-sm-4">                        
                                <img  src="../../img/png/cantidad_global.png" style="width: 100%;" />
                            </div>  
                            <div class="col-sm-8" style="padding:0px">                        
                                <b>Cantidad global sugerida a distribuir</b>
                            </div>                     
                        </div> 
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="CantGlobSugDist" id="CantGlobSugDist" readonly style=""
                            class="form-control input-xs">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="CantGlobDist" style="font-size: 13px; white-space: nowrap;">
                            Cantidad global a distribuir
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="CantGlobDist" id="CantGlobDist" style=""
                            class="form-control input-xs" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6"  style="font-size: 13px; white-space: nowrap;">
                        <img  src="../../img/png/info_nutricional.png" style="width: 25%;" />
                        <b>Información Nutricional</b>
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
                        <textarea name="comeGeneAsig" id="comeGeneAsig" rows="5"
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
        
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-btn" style="padding-right: 10px;">
                     <button type="button" class="btn btn-default" onclick="show_producto();"><img
                    src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
                </span>
                <b>Grupo producto:</b>
                 <select name="grupProd" id="grupProd" class="form-control input-xs" onchange="buscar_producto(this.value)"></select>
            </div>
        </div>
        <div class="col-sm-1">
            <label for="stock">
                Stock
            </label>
            <input type="text" name="stock" id="stock" class="form-control input-xs" readonly>
        </div>
        <div class="col-sm-3">
            
            <div class="input-group">
                <span class="input-group-btn" style="padding-right: 10px;">
                     <button type="button" style="width: initial;" class="btn btn-default" onclick="show_cantidad()"
                    id="btn_cantidad">
                    <img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
                </button>
                </span>
                <b>Cantidad</b>
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
                        <select class="form-control" id="ddl_producto" name="ddl_producto" style="width: 100%;" onchange="buscar_producto(this.value)" >
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


    <div id="modalBtnGrupo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tipo de población</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div class="table-responsive">
                        <table class="table" id="tablaPoblacion">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Tipo de Población</th>
                                    <th scope="col">Hombres</th>
                                    <th scope="col">Mujeres</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body_poblacion">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarGrupo">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

<div id="modal_tipoCompra" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tipo empaque</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
            <div class="row text-center" id="pnl_tipo_empaque">
            </div>                       
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_empaque()">OK</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>
<div id="modal_addBeneficiario" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Agregar Beneficiario</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
            <div class="row">
                <div class="col-sm-12">
                    <b>Beneficiario / Usuario</b>
                    <br>
                    <select name="beneficiario_new" id="beneficiario_new" class="form-control input-xs" style="width:100%"></select>
                </div>
            </div>                       
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="asignar_beneficiario()">Asignar Beneficiario</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

