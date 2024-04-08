<html>

<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION : 08/04/2024
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->

<head>
    <style>
        .table {
            width: 100%;
            table-layout: fixed;
        }

        .table th,
        .table td {
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #tabla-body {
            max-height: 300px;
            overflow-y: auto;
        }

        #modalCalendario table {
            text-align: center;
        }

        #modalCalendario th {
            font-weight: bold;
        }

        .card-header {
            background-color: #f3e5ab;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            background-color: #fffacd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-link {
            color: black;
        }

        #validarSRI {
            transition: transform 0.3s ease;
        }

        #validarSRI:hover {
            transform: translateY(-10px);
        }

        #btnMostrarModal {
            transition: transform 0.3s ease;
        }

        #btnMostrarModal:hover {
            transform: translateY(-10px);
        }

        #btnMostrarGrupo {
            transition: transform 0.3s ease;
        }

        #btnMostrarGrupo:hover {
            transform: translateY(-10px);
        }

        #btnMostrarDir {
            transition: transform 0.3s ease;
        }

        #btnMostrarDir:hover {
            transform: translateY(-10px);
        }

        #descargarArchivo img {
            transition: transform 0.3s ease;
        }

        #descargarArchivo img:hover {
            transform: translateX(10px);
        }

        .centered-img {
            display: block;
            margin: 0 auto;
        }

        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            text-align: top;
            color: black;
        }

        .modal-body {
            margin-top: 0;
            margin-bottom: 0;
        }

        .form-group.row {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>


<body>
    <div>
        <div class="row">
            <div class="col-sm-5" style="" id="btnsContainers">
                <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-default">
                    <img src="../../img/png/salire.png" width="35" height="35">
                </a>
                <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Guardar"
                    id="btnGuardarAsignacion" onclick="" >
                    <img src="../../img/png/disco.png" width="35" height="35">
                </button>
            </div>
        </div>

        <form id="miFormulario" style=" padding-bottom:30px">
            <div class="accordion" id="accordionExample" style="margin-top:px; margin-left:30px; margin-right: 30px;">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                style="font-weight: bold;" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                INFORMACION GENERAL
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body" style="margin: 1px; padding-top: 5px; padding-bottom: 5px;">
                            <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                                <div id="carouselBtnIma_93" class="carousel slide" data-ride="carousel"
                                    style="margin-right: 10px;">
                                    <div class="carousel-inner">
                                    </div>
                                </div>
                                <!--div style="margin-right: 10px;  display: flex; ">
                                    <a href="#" id="ingresarBeneficiario">
                                        <img src="../../img/png/grupo.png" width="60" height="60"
                                        onclick="abrirModal(93)" title="INGRESAR BENEFICIARIO">
                                    </a>
                                </div-->

                                <div style="flex: 1;  margin-right: 10px; ">
                                    <label for="input_93" style="display: block;">Tipo de Beneficiario</label>
                                    <input class="form-control input-xs" type="text" name="input_93" id="input_93"
                                        placeholder="Haz clic sobre la imagen" readonly>
                                </div>

                                <div id="carouselBtnImaDon" class="carousel slide" data-ride="carousel"
                                    style="margin-right: 10px;">
                                    <div class="carousel-inner">
                                    </div>
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="tipoDonacion" style="display: block;">Tipo de Donación</label>
                                    <input class="form-control input-xs" type="text" name="tipoDonacion"
                                        id="tipoDonacion" placeholder="Haz clic sobre la imagen" readonly>
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="ruc" style="display: block;">CI/RUC</label>
                                    <select class="form-control input-xs" name="ruc" id="ruc"
                                        style="width: 100%;"></select>
                                </div>
                                <div
                                    style="display: flex; justify-content: center; align-items: center;  margin-right: 10px;">
                                    <img src="../../img/png/SRIlogo.png" width="80" height="50"
                                        onclick="validar_sriC($('#ruc').val())" id="validarSRI" title="VALIDAR RUC">
                                </div>
                                <div style="flex: 1; margin-right: 10px;">
                                    <label for="cliente" style="display: block;">Nombre del Beneficiario/Usuario</label>
                                    <div class="input-group">
                                        <select class="form-control input-xs" name="cliente" id="cliente"
                                            style="width: 100%;"></select>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-xs btn-flat"
                                                id="btn_nuevo_cli" onclick="addCliente()" title="Nuevo cliente">
                                                <span class="fa fa-user-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="sexo" style="display: block;">Sexo</label>
                                    <select class="form-control input-xs" name="sexo" id="sexo"
                                        style="width: 100%;"></select>
                                </div>
                            </div>
                            <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                                <div id="carouselBtnIma_87" class="carousel slide" data-ride="carousel"
                                    style="margin-right: 10px;">
                                    <div class="carousel-inner">
                                    </div>
                                </div>
                                <!--div style="margin-right: 10px;  display: flex; ">
                                    <a href="#" id="ingresarEstado">
                                        <img src="../../img/png/estado.png" width="60" height="60"
                                        onclick="abrirModal(87)" title="INGRESAR ESTADO">
                                    </a>
                                </div-->
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="input_87" style="display: block;">Estado</label>
                                    <input class="form-control input-xs" type="text" name="input_87" id="input_87"
                                        placeholder="Haz clic sobre la imagen" readonly>
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="nombreRepre" style="display: block;">Nombre Representante Legal</label>
                                    <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                        placeholder="Nombre Representante">
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="ciRepre" style="display: block;">CI Representante Legal</label>
                                    <input class="form-control input-xs" type="text" name="ciRepre" id="ciRepre"
                                        placeholder="CI Representante">
                                </div>
                                <div style="flex: 1;">
                                    <label for="telfRepre" style="display: block;">Teléfono Representante Legal</label>
                                    <input class="form-control input-xs" type="text" name="telfRepre" id="telfRepre"
                                        placeholder="Representante legal">
                                </div>
                            </div>

                            <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="contacto" style="display: block;">Contacto/Encargado</label>
                                    <input class="form-control input-xs" type="text" name="contacto" id="contacto"
                                        placeholder="Contacto">
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="cargo" style="display: block;">Cargo</label>
                                    <input class="form-control input-xs" type="text" name="cargo" id="cargo"
                                        placeholder="Profesión">
                                </div>
                                <div style="margin-right: 10px;  display: flex; ">
                                    <img src="../../img/png/calendario2.png" width="60" height="60">
                                </div>
                                <div style="flex: 1; margin-right: 10px; ">
                                    <label for="diaEntrega" style="display: block;">Día Entrega a Usuarios
                                        Finales</label>
                                    <select class="form-control input-xs" name="diaEntrega" id="diaEntrega"></select>
                                </div>
                                <div style="margin-right: 10px;  display: flex; ">
                                    <img src="../../img/png/reloj.png" width="55" height="55">
                                </div>
                                <div style="flex: 1; ">
                                    <label for="horaEntrega" style="display: block;">Hora Entrega a Usuarios
                                        Finales</label>
                                    <input type="time" name="horaEntrega" id="horaEntrega"
                                        class="form-control input-xs">
                                </div>
                            </div>

                            <div class="row" style="margin: 10px; display: flex; justify-content: center;">

                                <div class="col-sm-1" style="margin-right:10px;">
                                    <div class="row" style="display: flex; justify-content: center;">
                                        <a href="#" id="btnMostrarDir">
                                            <img src="../../img/png/map.png" width="60" height="60"
                                                title="INGRESAR DIRECCIÓN">
                                        </a>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dir">Ingresar Dirección </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3" style="margin-right:10px;">
                                    <!--div class="row">
                                    <label for="direccion" style="display: block;">Dirección</label>
                                    <input class="form-control input-xs" type="text" name="direccion" id="direccion"
                                        placeholder="Direccion">
                                </div-->
                                    <div class="row">
                                        <label for="email" style="display: block;">Email</label>
                                        <input class="form-control input-xs" type="text" name="email" id="email"
                                            placeholder="Email">
                                    </div>
                                    <div class="row">
                                        <label for="email2" style="display: block;">Email 2</label>
                                        <input class="form-control input-xs" type="text" name="email2" id="email2"
                                            placeholder="Email2">
                                    </div>
                                </div>
                                <!--div class="col-sm-1"></div-->
                                <div class="col-sm-3" style="margin-right:10px;">
                                    <!--div class="row">
                                    <label for="referencia" style="display: block;">Referencia</label>
                                    <input class="form-control input-xs" type="text" name="referencia" id="referencia"
                                        placeholder="Referencia">
                                </div-->
                                    <div class="row">
                                        <label for="telefono" style="display: block;">Teléfono 1</label>
                                        <input class="form-control input-xs" type="text" name="telefono" id="telefono"
                                            placeholder="Teléfono ">
                                    </div>
                                    <div class="row">
                                        <label for="telefono2" style="display: block;">Teléfono 2</label>
                                        <input class="form-control input-xs" type="text" name="telefono2" id="telefono2"
                                            placeholder="Teléfono 2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"
                                id="botonInfoAdd" style="font-weight: bold;">
                                <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                INFORMACION ADICIONAL
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body" style="margin: 1px; padding-top: 5px; padding-bottom: 40px;">
                            <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="select_88" style="display: block;">Tipo de Entrega</label>
                                    <select class="form-control input-xs" name="select_88" id="select_88"
                                        style="width: 100%;"></select>
                                </div>
                                <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                    <img src="../../img/png/calendario2.png" width="60" height="60" id="btnMostrarModal"
                                        title="CALENDARIO ASIGNACION">
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="diaEntregac" style="display: block;">Día de Entrega</label>
                                    <select class="form-control input-xs" name="diaEntregac" id="diaEntregac"
                                        style="width: 100%;"></select>
                                </div>
                                <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                    <img src="../../img/png/reloj.png" width="55" height="55">
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="horaEntregac" style="display: block;">Hora de Entrega</label>
                                    <input type="time" name="horaEntregac" id="horaEntregac"
                                        class="form-control input-xs">
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="select_86" style="display: block;">Frecuencia</label>
                                    <select class="form-control input-xs" name="select_86" id="select_86"
                                        style="width: 100%;"></select>
                                </div>
                                <div id="comentariodiv"
                                    style="flex: 1; margin-right: 10px; margin-left: 10px; display: none;">
                                    <label for="comentario" style="display: block;">Comentario (máximo 85
                                        caracteres)</label>
                                    <textarea class="form-control" id="comentario" rows="2" style="resize: none"
                                        maxlength="85"></textarea>
                                </div>

                            </div>
                            <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                                <div style="margin-right: 10px; margin-lefth: 10px; display: flex; ">
                                    <img src="../../img/png/grupoEdad.png" width="60" height="60" id="btnMostrarGrupo"
                                        title="TIPO DE POBLACIÓN">
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="totalPersonas" style="display: block;">Total de Personas
                                        Atendidas</label>
                                    <input type="number" name="totalPersonas" id="totalPersonas"
                                        class="form-control input-xs" min="0" max="100">
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="select_91" style="display: block;">Tipo de Población</label>
                                    <select class="form-control input-xs" name="select_91" id="select_91"
                                        style="width: 100%;"></select>
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="select_92" style="display: block;">Acción Social</label>
                                    <select class="form-control input-xs" name="select_92" id="select_92"
                                        style="width: 100%;"></select>
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="select_90" style="display: block;">Vulnerabilidad</label>
                                    <select class="form-control input-xs" name="select_90" id="select_90"
                                        style="width: 100%;"></select>
                                </div>
                                <div style="flex: 1; margin-right: 10px; margin-lefth: 10px;">
                                    <label for="select_89" style="display: block;">Tipo de Atención</label>
                                    <select class="form-control input-xs" name="select_89" id="select_89"
                                        style="width: 100%;"></select>
                                </div>
                            </div>
                            <div class="row" style="margin: 10px;">
                                <div class="col-sm-5"></div>
                                <div class="col-sm-3">
                                    <div class="row" style="display: flex; justify-content: center;">
                                        <a href="#" id="descargarArchivo">
                                            <img src="../../img/png/adjuntar-archivo.png" width="60" height="60"
                                                title="DESCARGAR ARCHIVO">
                                        </a>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="archivoAdd">Archivos Adjuntos</label>
                                            <input type="file" class="form-control-file" id="archivoAdd" multiple
                                                onchange="checkFiles(this)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <label for="infoNut" style="display: block;">Información Nutricional</label>
                                        <textarea class="form-control" id="infoNut" rows="4"
                                            style="resize: none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="modalCalendario">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="background-color: white;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                style="color: white;">&times;</button>
                            <h4 class="modal-title">CALENDARIO DE ASIGNACION</h4>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div style="max-height: 300px; overflow-y: auto; overflow-x: auto;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <script>
                                                    var diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves',
                                                        'Viernes', 'Sabado', 'Domingo'];
                                                    for (var i = 0; i < diasSemana.length; i++) {
                                                        document.write('<th>' + diasSemana[i] + '</th>');
                                                    }
                                                </script>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalBtnDir" class="modal fade" role="dialog">
                <div class="modal-dialog" style="max-width: 400px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Registro de Ubicación</h4>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; max-height: 200px;">
                            <div class="form-group row">
                                <label for="Provincia" class="col-sm-3 col-form-label">Provincia</label>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="select_prov"
                                        onchange="ciudad(this.value)">
                                        <option value="">Seleccione provincia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Ciudad" class="col-sm-3 col-form-label">Ciudad</label>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="select_ciud">
                                        <option value="">Seleccione ciudad</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Canton" class="col-sm-3 col-form-label">Cantón</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="Canton" id="Canton"
                                        placeholder="Ingrese un cantón">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Parroquia" class="col-sm-3 col-form-label">Parroquia</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="Parroquia" id="Parroquia"
                                        placeholder="Ingrese una parroquia">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Barrio" class="col-sm-3 col-form-label">Barrio</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="Barrio" id="Barrio"
                                        placeholder="Ingrese un barrio">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CalleP" class="col-sm-3 col-form-label">Calle principal</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="CalleP" id="CalleP"
                                        placeholder="Ingrese calle principal">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CalleS" class="col-sm-3 col-form-label">Calle secundaria</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="CalleS" id="CalleS"
                                        placeholder="Ingrese calle secundaria">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Referencia" class="col-sm-3 col-form-label">Referencia</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="Referencia" id="Referencia"
                                        placeholder="Ingrese una referencia">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnGuardarDir">Aceptar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalBtnGrupo" class="modal fade" role="dialog">
                <div class="modal-dialog" style="width: 500px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Tipo de población</h4>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; max-height: 200px;">
                            <form>
                                <table class="table table-md table-dark" id="tablaPoblacion">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan="2">Tipo de Población</th>
                                            <th scope="col">Hombres</th>
                                            <th scope="col">Mujeres</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- filas -->
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalDescarga" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Descargar Archivos</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin: 10px; display: flex;">
                                <!-- Cambiar el id a modalDescContainer -->
                                <div id="modalDescContainer"
                                    style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalsBtnpAliado" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Productor</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin: 10px; display: flex;">
                                <div id="" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn btn-default btn-sm">
                                            <img src="../../img/png/industrial.png" style="width: 90%; height: 90%;"
                                                alt="Imagen">
                                        </button>
                                        <b>Industrial</b>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="button" class="btn btn-default btn-sm">
                                            <img src="../../img/png/animales.png" style="width: 90%; height: 90%;"
                                                alt="Imagen">
                                        </button>
                                        <b>Artesanal</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div id="modalsBtn87" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Estado</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin: 10px; display: flex;">
                                <div id="modal_87"
                                    style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalsBtn93" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Beneficiario</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin: 10px; display: flex;">
                                <div id="modal_93"
                                    style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalsBtnDon" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Donacion</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin: 10px; display: flex;">
                                <div id="modal_Don"
                                    style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
    $(document).ready(function () {
        Form_Activate();
    });

    function checkFiles(input) {
        const maxFiles = 3;
        const maxFileSize = 10;

        const files = input.files;
        if (files.length > maxFiles) {
            Swal.fire("", "Solo se permiten un máximo de " + maxFiles + " archivos.", "info");
            input.value = '';
        } else if (files.length > 0) {
            var fileNames = [];
            var fileSizeLimit = false;
            var contieneComa = false;

            for (var i = 0; i < files.length; i++) {
                var fileName = files[i].name;
                if (fileName.includes(',')) {
                    contieneComa = true;
                    break;
                }

                fileNames.push(fileName);

                if (files[i].size > maxFileSize * 1024 * 1024) {
                    fileSizeLimit = true;
                    break;
                }
            }

            if (contieneComa) {
                Swal.fire("", "Los nombres de los archivos no deben contener comas (,).", "info");
                input.value = '';
            } else if (fileSizeLimit) {
                Swal.fire("", "El tamaño máximo permitido por archivo es de " + maxFileSize + "MB.", "info");
                input.value = '';
            } else {
                var fileList = fileNames.join(', ');
                if (fileList.length > 90) {
                    Swal.fire("", "La longitud total de los nombres de archivo supera los 90 caracteres.", "info");
                } else {
                    Swal.fire("Archivos seleccionados:", fileList, "info");
                }
            }
        }
    }

    //direccion
    $('#btnMostrarDir').click(function () {
        provincias();
        $('#modalBtnDir').modal('show');
    });

    //direccion
    $('#btnGuardarDir').click(function () {
        $('#modalBtnDir').modal('hide');
        var provincia = $('#select_prov').val();
        var ciudad = $('#select_ciud').val();
        var canton = $('#Canton').val();
        var parroquia = $('#Parroquia').val();
        var barrio = $('#Barrio').val();
        var callep = $('#CalleP').val();
        var calles = $('#CalleS').val();
        var referencia = $('#Referencia').val();
    });

    //select provincias
    function provincias() {
        var option = "<option value='' disabled selected>Seleccione provincia</option>";
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?provincias=true',
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                $("#select_ciud").html("<option value='' disabled selected>Seleccione provincia</option>");
            },
            success: function (response) {
                response.forEach(function (data, index) {
                    option += "<option value='" + data.Codigo + "'>" + data.Descripcion_Rubro + "</option>";
                });
                $('#select_prov').html(option);
                if (prov != null) {
                    $('#select_prov').val(prov).trigger('change');
                    ciudad(prov);
                }
            }
        });
    }

    //select ciudad
    function ciudad(idpro) {
        var option = "<option value='' disabled selected>Seleccione ciudad</option>";
        if (idpro != '') {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?ciudad=true',
                type: 'post',
                dataType: 'json',
                data: { idpro: idpro },
                success: function (response) {
                    response.forEach(function (data, index) {
                        option += "<option value='" + data.Codigo + "'>" + data.Descripcion_Rubro + "</option>";
                    });
                    $('#select_ciud').html(option);
                    if (ciud != null) {
                        $('#select_ciud').val(ciud).trigger('change');
                    }
                }
            });
        }
    }

    //grupo
    $('#btnMostrarGrupo').click(function () {
        $('#modalBtnGrupo').modal('show');
        $.ajax({
            type: "GET",
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarTblPoblacion=true',
            dataType: 'json',
            success: function (datos) {
                $('#tablaPoblacion tbody').empty();

                $.each(datos, function (index, dato) {
                    $('#tablaPoblacion tbody').append(`
                    <tr>
                        <td colspan="2">${dato.Poblacion}</td>
                        <td>${dato.Hombres}</td>
                        <td>${dato.Mujeres}</td>
                        <td>${dato.Total}</td>
                    </tr>
                `);
                });
            }
        });
    });

    var datosArray = [];
    function llenarCarousels(valor, valor2) {
        $.ajax({
            type: "GET",
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true',
            data: { valor: valor, valor2: valor2 },
            dataType: 'json',
            success: function (res) {
                var val = res.val;
                var datos = res.respuesta;

                datos.forEach(function (item) {
                    datosArray.push(item);
                });

                if (val == 1) {
                    var carouselInner = $('#carouselBtnImaDon .carousel-inner');
                    if (datos.length > 0) {
                        carouselInner.empty();
                    }
                    datos.forEach(function (item, index) {
                        var carouselItem = $('<div class="item">');
                        if (index === 0) {
                            carouselItem.addClass('active');
                        }
                        var imgSrc = '../../img/png/' + item.picture + '.png';
                        var carouselContent = '<img src="' + imgSrc + '" alt="' + item.text + '" width="50" height="50">' +
                            '<div class="carousel-caption">' +
                            '</div>';
                        carouselItem.html(carouselContent);
                        carouselItem.click(function () {
                            abrirModal('Don');
                        });
                        carouselInner.append(carouselItem);
                    });
                    var option = '';
                    var opt = '<option value="">Estado</option>';
                    datos.forEach(function (item) {
                        option += '<div class="col-md-6 col-sm-6">' +
                            '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/' + item.picture + '.png" onclick="itemSelect(\'' + item.picture +
                            '\',\'' + item.text + '\', \'' + item.color + '\', \'' + item.id +
                            '\')" style="width: 60px;height: 60px;"></button><br>' +
                            '<b>' + item.text + '</b>' +
                            '</div>';
                        opt += '<option value="' + item.id + '">' + item.text + '</option>';
                    });
                    $('#modal_Don').html(option);

                } else {
                    var carouselInner = $('#carouselBtnIma_' + valor + ' .carousel-inner');
                    if (datos.length > 0) {
                        carouselInner.empty();
                    }
                    datos.forEach(function (item, index) {
                        var carouselItem = $('<div class="item">');
                        if (index === 0) {
                            carouselItem.addClass('active');
                        }
                        var imgSrc = '../../img/png/' + item.picture + '.png';
                        var carouselContent = '<img src="' + imgSrc + '" alt="' + item.text + '" width="60" height="60">' +
                            '<div class="carousel-caption">' +
                            '</div>';
                        carouselItem.html(carouselContent);
                        carouselItem.click(function () {
                            abrirModal(valor);
                        });
                        carouselInner.append(carouselItem);
                    });

                    var option = '';
                    if (valor == 87) {
                        var opt = '<option value="">Estado</option>';
                        datos.forEach(function (item) {
                            option += '<div class="col-md-6 col-sm-6">' +
                                '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/' +
                                item.picture + '.png" onclick="itemSelect(\'' + item.picture +
                                '\',\'' + item.text + '\', \'' + item.color + '\', \'' + item.id +
                                '\')" style="width: 60px;height: 60px;"></button><br>' +
                                '<b>' + item.text + '</b>' +
                                '</div>';
                            opt += '<option value="' + item.id + '">' + item.text + '</option>';
                        });
                        $('#modal_87').html(option);
                    }
                    if (valor == 93) {
                        var opt = '<option value="">Beneficiario</option>';
                        datos.forEach(function (item) {
                            option += '<div class="col-md-6 col-sm-6">' +
                                '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/' +
                                item.picture + '.png" onclick="itemSelect(\'' + item.picture +
                                '\',\'' + item.text + '\', \'' + item.color + '\', \'' + item.id +
                                '\')" style="width: 60px;height: 60px;"></button><br>' +
                                '<b>' + item.text + '</b>' +
                                '</div>';
                            opt += '<option value="' + item.id + '">' + item.text + '</option>';

                        });
                        $('#modal_93').html(option);
                    }

                }
            }
        });
    }

    function abrirModal(valor) {
        $('#modalsBtn' + valor).modal('show');
    };

    function itemSelect(picture, text, color, id) {
        if (id.length == 3) {
            var imagen = "../../img/png/" + picture + ".png";

            $("#carouselBtnImaDon .item.active img").attr("src", imagen);
            $("#carouselBtnImaDon").carousel("pause");
            $("#modalsBtnDon").modal("hide");

            $('#tipoDonacion').val(text);
            $('#tipoDonacion').attr('val', id);
        }

        var valor = id.substring(0, 2);
        var imagen = "../../img/png/" + picture + ".png";

        $("#carouselBtnIma_" + valor + " .item.active img").attr("src", imagen);
        $("#carouselBtnIma_" + valor).carousel("pause");
        $("#modalsBtn" + valor).modal("hide");

        $('#input_' + valor).val(text);
        $('#input_' + valor).attr('val', id);
        if (valor == 93) {
            actualizarEstilo(color);
        }
        if (id == 93.04) {
            abrirModal('pAliado');
        }
    }

    //btn icono RUC
    function validarRucYValidarSriC() {
        var ruc = $('#ruc').val();
        if (ruc) {
            validar_sriC(ruc);
        } else {
            Swal.fire('', 'Por favor ingrese un RUC válido.', 'error');
        }
    }

    //btn dentro de modal icono RUC
    function usar_cliente(nombre, ruc, codigo, email, td = 'N') {
        $('#cliente').val(ruc).trigger('change');
        $('#myModal').modal('hide');
    }

    var horaActual;
    function Form_Activate() {
        $('#comentariodiv').hide();
        LlenarSelectDiaEntrega();
        LlenarSelectSexo();
        LlenarSelectRucCliente();
        llenarCarousels(87);
        llenarCarousels(93);
        llenarCarousels("CXC", true);
        [86, 88, 89, 90, 91, 92].forEach(LlenarSelects_Val);

        horaActual = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        $('#horaEntregac').val(horaActual);
    }

    //textarea para tipo de frecuencia ocacional
    $('#select_86').change(function () {
        var selectedValue = $(this).val();
        if (selectedValue === '86.04') {
            $('#comentario').val(comen);
            $('#comentariodiv').show();
        } else {
            $('#comentario').val('.');
            $('#comentariodiv').hide();
        }
    });

    //calendario
    $('#btnMostrarModal').click(function () {
        var valorSeleccionado = $('#input_93').attr('val');
        if (valorSeleccionado && valorSeleccionado.length > 0) {
            LlenarCalendario(valorSeleccionado);
        } else {
            swal.fire('', 'Por favor, seleccione una organización', 'info');
        }
    });

    function LlenarCalendario(Actividad) {
        if (Actividad) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?LlenarCalendario=true',
                type: 'post',
                dataType: 'json',
                data: { valor: Actividad },
                success: function (datos) {
                    console.log(datos);
                    if (datos != 0) {
                        LlenarCalendarioC(datos);
                    } else {
                        $('#tabla-body').empty();
                        swal.fire('', 'No se encontraron datos de asignacion', '');
                    }
                }
            });
        }
    }

    //color para celdas del calendario
    var miColor;
    function ObtenerColor(valEnvio_No, callback) {
        if (valEnvio_No) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?ObtenerColor=true',
                type: 'post',
                dataType: 'json',
                data: { valor: valEnvio_No },
                success: function (data) {
                    if (data != 0) {
                        miColor = data.Color;
                        callback(miColor);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al obtener color:', error);
                }
            });
        }
    }

    function LlenarCalendarioC(data) {
        var horas = ['01:00 - 02:00', '02:00 - 03:00', '03:00 - 04:00',
            '04:00 - 05:00', '05:00 - 06:00', '06:00 - 07:00',
            '07:00 - 08:00', '08:00 - 09:00', '09:00 - 10:00',
            '10:00 - 11:00', '11:00 - 12:00', '12:00 - 13:00',
            '13:00 - 14:00', '14:00 - 15:00', '15:00 - 16:00',
            '16:00 - 17:00', '17:00 - 18:00', '18:00 - 19:00',
            '19:00 - 20:00', '20:00 - 21:00', '21:00 - 22:00',
            '22:00 - 23:00', '23:00 - 24:00'];
        var diasSemana = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        $('#tabla-body').empty();

        $.each(data, function (index, cliente) {
            var Actividad = cliente.Actividad || '';
            var Cliente = cliente.Cliente || '';
            var Envio_No = cliente.Envio_No || '';
            var Dia_Ent = cliente.Dia_Ent || '';
            var Hora_Ent = cliente.Hora_Ent || '';

            ObtenerColor(Envio_No, function (color) {
                var colorV = color.substring(4);
                var indiceColumna = diasSemana.indexOf(Dia_Ent);
                var indiceFila = -1;
                for (var i = 0; i < horas.length; i++) {
                    var horaInicio = horas[i].split(' - ')[0];
                    var horaFin = horas[i].split(' - ')[1];
                    if (Hora_Ent >= horaInicio && Hora_Ent < horaFin) {
                        indiceFila = i;
                        break;
                    }
                }

                var $fila = $('#tabla-body tr').filter(function () {
                    return $(this).find('td:first').text() === horas[indiceFila];
                });

                if ($fila.length === 0) {
                    $fila = $('<tr>');
                    $fila.append($('<td>').text(horas[indiceFila]));
                    $.each(diasSemana, function (indiceDia, dia) {
                        var $celda = $('<td>');
                        $fila.append($celda);
                    });
                    $('#tabla-body').append($fila);
                }

                var $celda = $fila.find('td').eq(indiceColumna + 1);
                var $div = $('<div>').text(Cliente).css('background-color', '#' + colorV);
                $celda.append($div);
            });
        });
        $('#modalCalendario').modal('show');
    }

    //selects Sexo
    function LlenarSelectSexo() {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectSexo=true',
            type: 'post',
            dataType: 'json',
            success: function (datos) {
                $('#sexo').append('<option value="" disabled selected>Seleccione una opción</option>');

                $.each(datos, function (index, opcion) {
                    $('#sexo').append('<option value="' + opcion['Codigo'] + '">' + opcion['Descripcion'] + '</option>');
                });
            }
        });
    }

    //selects Dia de Entrega
    function LlenarSelectDiaEntrega() {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectDiaEntrega=true',
            type: 'post',
            dataType: 'json',
            success: function (datos) {
                $('#diaEntregac').append('<option value="" disabled selected>Seleccione una opción</option>');
                $('#diaEntrega').append('<option value="" disabled selected>Seleccione una opción</option>');

                $.each(datos, function (index, opcion) {
                    $('#diaEntregac').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
                    $('#diaEntrega').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
                });
            }
        });
    }

    //select RUC y Cliente
    function LlenarSelectRucCliente() {
        $('#ruc').select2({
            placeholder: 'Seleccione una opción',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarSelectRucCliente: true
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.rucs
                    };
                },
                cache: true
            }
        });

        $('#cliente').select2({
            placeholder: 'Seleccione una opción',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarSelectRucCliente: true
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.clientes
                    };
                },
                cache: true
            }
        });
    }

    //todos los selects_num
    function LlenarSelects_Val(valor) {
        $('#select_' + valor).select2({
            placeholder: 'Seleccione una opción',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        valor: valor,
                        LlenarSelects_Val: true
                    };
                },
                processResults: function (data) {
                    var options = [];
                    if (data.respuesta === "No se encontraron datos para mostrar") {
                        options.push({
                            id: '',
                            text: data.respuesta
                        });
                    } else {
                        $.each(data.respuesta, function (index, opcion) {
                            var option = {
                                id: opcion.id,
                                text: opcion.text,
                                color: opcion.color
                            };
                            options.push(option);
                        });
                    }
                    return {
                        results: options
                    };
                },
                cache: true
            }
        });
    }

    //registro 
    $('#btnGuardarAsignacion').click(function () {
        var fileInput = $('#archivoAdd')[0];
        var formData = new FormData();

        for (var i = 0; i < fileInput.files.length; i++) {
            formData.append('Evidencias[]', fileInput.files[i]);
        }

        formData.append('Cliente', miCliente);
        formData.append('CI_RUC', miRuc);
        formData.append('Codigo', miCodigo);
        formData.append('Actividad', $('#input_93').attr('val'));
        formData.append('CodigoA', $('#input_87').attr('val'));
        formData.append('Calificacion', $('#tipoDonacion').attr('val'));
        formData.append('Representante', $('#nombreRepre').val());
        formData.append('CI_RUC_R', $('#ciRepre').val());
        formData.append('Telefono_R', $('#telfRepre').val());
        formData.append('Contacto', $('#contacto').val());
        formData.append('Profesion', $('#cargo').val());
        formData.append('Dia_Ent', $('#diaEntrega').val() || '.');
        formData.append('Hora_Ent', $('#horaEntrega').val());

        formData.append('Sexo', $('#sexo').val() || '.');
        formData.append('Email', $('#email').val());
        formData.append('Email2', $('#email2').val());
        formData.append('Telefono', $('#telefono').val());
        formData.append('TelefonoT', $('#telefono2').val());

        formData.append('Provincia', $('#select_prov').val() || '.');
        formData.append('Ciudad', $('#select_ciud').val() || '.');
        formData.append('Canton', $('#Canton').val() || '.');
        formData.append('Parroquia', $('#Parroquia').val() || '.');
        formData.append('Barrio', $('#Barrio').val() || '.');
        formData.append('CalleP', $('#CalleP').val() || '.');
        formData.append('CalleS', $('#CalleS').val() || '.');
        formData.append('Referencia', $('#Referencia').val() || '.');

        // Información adicional
        formData.append('CodigoA2', $('#select_88').val());
        formData.append('Dia_Ent2', $('#diaEntregac').val() || '.');
        formData.append('Hora_Registro', $('#horaEntregac').val());
        formData.append('Envio_No', $('#select_86').val());
        formData.append('Comentario', $('#comentario').val() || '.');
        formData.append('No_Soc', $('#totalPersonas').val());
        formData.append('Area', $('#select_91').val());
        formData.append('Acreditacion', $('#select_92').val());
        formData.append('Tipo_Dato', $('#select_90').val());
        formData.append('Cod_Fam', $('#select_89').val());
        formData.append('Observaciones', $('#infoNut').val());

        formData.forEach(function (value, key) {
            console.log(key + ': ' + value);
        });

        console.log("Added Evidences:");
        for (var [key, value] of formData.entries()) {
            if (key === 'Evidencias') {
                console.log(value.name);
            }
        }

        //validacion campos llenos
        var camposVacios = [];
        if (!miRuc) camposVacios.push('RUC');
        if (!$('#select_88').val()) camposVacios.push('Tipo Entrega');
        if (!$('#diaEntregac').val()) camposVacios.push('Fecha Entrega');
        if (!$('#horaEntregac').val()) camposVacios.push('Hora Entrega');
        if (!$('#select_86').val()) camposVacios.push('Frecuencia');
        if (!$('#totalPersonas').val()) camposVacios.push('Personas Atendidas');
        if (!$('#select_91').val()) camposVacios.push('Tipo poblacion');
        if (!$('#select_92').val()) camposVacios.push('Accion social');
        if (!$('#select_90').val()) camposVacios.push('Vulnerabilidad');
        if (!$('#select_89').val()) camposVacios.push('Tipo Atencion');
        if (!fileInput.files.length) camposVacios.push('Evidencias');
        if (!$('#infoNut').val()) camposVacios.push('Observaciones');

        if (camposVacios.length > 0) {
            var mensaje = 'Los siguientes campos están vacíos:\n';
            camposVacios.forEach(function (campo) {
                mensaje += campo + ',';
            });
            Swal.fire({
                title: 'Campos Vacíos',
                text: mensaje,
                type: 'warning',
                confirmButtonText: 'Aceptar'
            });
        } else {
            $('#myModal_espera').modal('show');
            $.ajax({
                type: 'post',
                url: '../controlador/inventario/registro_beneficiarioC.php?guardarAsignacion=true',
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('#myModal_espera').modal('hide');
                    if (response.res == '0') {
                        Swal.fire({
                            title: 'AVISO',
                            text: response.mensaje + (response.datos || ''),
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: response.mensaje,
                            type: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }
            });
        }
    });

    //limpieza
    function LimpiarSelectsInfoAdd() {
        $('#collapseTwo').collapse('hide');
        $('#select_92').val(null).trigger('change');
        $('#select_86').val(null).trigger('change');
        $('#select_88').val(null).trigger('change');
        $('#select_89').val(null).trigger('change');
        $('#select_90').val(null).trigger('change');
        $('#select_91').val(null).trigger('change');
        $('#archivoAdd').val('');
        $('#diaEntregac').val('');
        $('#horaEntregac').val('');
        $('#totalPersonas').val('');
        $('#infoNut').val('');
        $('#comentario').val('');
        comen = '';
        nombreArchivo = '';

    }

    //llenar campos del cliente segun nombre seleccionado
    var miRuc;
    var miCodigo = '';
    var miCliente;
    var nombreArchivo;
    $('#cliente').on('select2:select', function (e) {
        LimpiarSelectsInfoAdd();
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.CI_RUC;
        miCliente = data.text;
        if (data.id === '.') {
            swal.fire("", "No se encontró un RUC relacionado.", "error");
        } else {
            if ($('#ruc').find("option[value='" + data.id + "']").length) {
                $('#ruc').val(data.id).trigger('change');
            } else {
                var newOption = new Option(data.CI_RUC, data.id, true, true);
                $('#ruc').append(newOption).trigger('change');
            }
            var valorSeleccionado = $('#ruc').val();
            llenarCamposInfo(miCodigo);
        }
    });

    //llenar campos del cliente segun ruc seleccionado
    $('#ruc').on('select2:select', function (e) {
        LimpiarSelectsInfoAdd();
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.text;
        miCliente = data.Cliente;
        if (data.id === '.') {
            swal.fire("", "No se encontró un Cliente relacionado.", "error");
        } else {
            if ($('#cliente').find("option[value='" + data.id + "']").length) {
                $('#cliente').val(data.id).trigger('change');
            } else {
                var newOption = new Option(data.Cliente, data.id, true, true);
                $('#cliente').append(newOption).trigger('change');
            }
            var valorSeleccionado = $('#cliente').val();
            llenarCamposInfo(miCodigo);
        }
    });

    //llenar campos del panel informacion
    var prov;
    var ciud;
    function llenarCamposInfo(Codigo) {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposInfo=true',
            type: 'post',
            dataType: 'json',
            data: { valor: Codigo },
            success: function (datos) {
                if (datos != 0) {
                    $('#nombreRepre').val(datos.Representante);
                    $('#ciRepre').val(datos.CI_RUC_R);
                    $('#telfRepre').val(datos.Telefono_R);
                    $('#contacto').val(datos.Contacto);
                    $('#cargo').val(datos.Profesion);
                    $('#email').val(datos.Email);
                    $('#email2').val(datos.Email2);
                    $('#telefono').val(datos.Telefono);
                    $('#telefono2').val(datos.TelefonoT);
                    if (datos.Sexo == '.') {
                        $('#sexo').val($('#sexo option:first').val());
                    } else {
                        $('#sexo').val(datos.Sexo);
                    }

                    prov = (datos.Prov !== '.') ? datos.Prov : null;
                    ciud = (datos.Ciudad !== '.') ? datos.Ciudad : null;

                    $('#Canton').val(datos.Canton);
                    $('#Parroquia').val(datos.Parroquia);
                    $('#Barrio').val(datos.Barrio);
                    $('#CalleP').val(datos.Direccion);
                    $('#CalleS').val(datos.DireccionT);
                    $('#Referencia').val(datos.Referencia);

                    datosArray.forEach(function (item) {
                        if (item.id === datos.Actividad || item.id === datos.CodigoA || item.id === datos.Calificacion) {
                            itemSelect(item.picture, item.text, item.color, item.id);
                        }
                    });
                    if (datos.Dia_Ent == '.') {
                        $('#diaEntrega').val($('#diaEntrega option:first').val());
                    } else {
                        $('#diaEntrega').val(datos.Dia_Ent);
                    }
                    if (/^\d{2}:\d{2}$/.test(datos.Hora_Ent)) {
                        $('#horaEntrega').val(datos.Hora_Ent);
                    } else {
                        $('#horaEntrega').val(datos);
                    }
                }
            }
        });
    }

    //llenar campos de panel informacion adicional
    var comen;
    $('#botonInfoAdd').click(function () {
        console.log(miCodigo);
        if (miCodigo) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposInfoAdd=true',
                type: 'post',
                dataType: 'json',
                data: { valor: miCodigo },
                success: function (datos) {
                    if (datos != 0) {
                        $('#diaEntregac').val(datos.Dia_Ent2);
                        $('#horaEntregac').val(datos.Hora_Ent2);
                        $('#totalPersonas').val(datos.No_Soc);
                        $('#comentario').val(datos.Etapa_Procesal);
                        comen = datos.Etapa_Procesal;
                        $('#infoNut').val(datos.Observaciones);
                        nombreArchivo = datos.Evidencias;
                        llenarPreSelects(datos.CodigoA2);
                        llenarPreSelects(datos.Envio_No);
                        llenarPreSelects(datos.Area);
                        llenarPreSelects(datos.Acreditacion);
                        llenarPreSelects(datos.Tipo_Dato);
                        llenarPreSelects(datos.Cod_Fam);
                    } else {
                        swal.fire('', 'No se encontraron datos adicionales', 'info');
                    }

                }
            });
        } else {
            swal.fire('', 'No se selecciono un Cliente', 'info');
        }
    });

    //llenar selects preseleccionados
    function llenarPreSelects(valor) {
        if (valor != ".") {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true',
                type: 'GET',
                dataType: 'json',
                data: { valor: valor },
                success: function (res) {
                    console.log(res);
                    var val = res.val;
                    var datos = res.respuesta;
                    if (!res.error) {
                        datos.forEach(function (item) {
                            valorp = item.id.slice(0, 2);
                            if ($('#select_' + valorp).find("option[value='" + item.id + "']").length) {
                                $('#select_' + valorp).val(item.id).trigger('change');
                            } else {
                                var newOption = new Option(item.text, item.id, true, true);
                                $('#select_' + valorp).append(newOption).trigger('change');
                            }
                        });
                    }
                }
            });
        }
    }

    //estilos de panel
    function actualizarEstilo(colorValor) {
        if (colorValor) {
            var hexColor = colorValor.substring(4);
            var darkerColor = darkenColor(hexColor, 20);
            $('.card-body').css('background-color', '#' + hexColor);
            $('.card-header, .modal-header').css('background-color', darkerColor);
        } else {
            $('.card-body').css('background-color', '#fffacd');
            $('.card-header').css('background-color', '#f3e5ab');
        }
    }

    //conversion color y tono mas oscuro para encabezado del panel
    function darkenColor(color, percent) {
        var num = parseInt(color, 16),
            amt = Math.round(2.55 * percent),
            R = (num >> 16) - amt,
            G = (num >> 8 & 0x00FF) - amt,
            B = (num & 0x0000FF) - amt;

        R = (R < 255 ? (R < 1 ? 0 : R) : 255);
        G = (G < 255 ? (G < 1 ? 0 : G) : 255);
        B = (B < 255 ? (B < 1 ? 0 : B) : 255);

        return "#" + ((1 << 24) + (R << 16) + (G << 8) + B).toString(16).slice(1);
    }

    //descarga del archivo adjunto
    function descargarArchivo(url, nombre) {
        var ruta = "../../" + url + nombre;
        var enlaceTemporal = $('<a></a>')
            .attr('href', ruta)
            .attr('download', nombre)
            .appendTo('body');
        enlaceTemporal[0].click();
        enlaceTemporal.remove();
    }

    $('#descargarArchivo').click(function () {
        if (nombreArchivo) {
            console.log(nombreArchivo);
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?descargarArchivo=true',
                type: 'post',
                dataType: 'json',
                data: { valor: nombreArchivo },
                success: function (data) {
                    //console.log(data);
                    if (data.response === 1) {
                        $('#modalDescContainer').empty();
                        if (data.archivos.length > 0) {
                            data.archivos.forEach(function (archivo) {
                                var extension = archivo.split('.').pop().toLowerCase();
                                console.log(extension);
                                var iconSrc;
                                switch (extension) {
                                    case 'pdf':
                                        iconSrc = '../../img/png/pdf_icon.png';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        iconSrc = '../../img/png/doc_icon.png';
                                        break;
                                    case 'png':
                                    case 'jpg':
                                        iconSrc = '../../img/png/jpg_icon.png';
                                        break;
                                    default:
                                        iconSrc = '../../img/png/file_icon.png';
                                        break;
                                }
                                var buttonHTML = '<div class="col-md-6 col-sm-6">' +
                                    '<button type="button" class="btn btn-default btn-sm"' +
                                    'onclick="descargarArchivo(\'' + data.dir + '\', \'' + archivo + '\')">' +
                                    '<img src="' + iconSrc + '" style="width: 60px;height: 60px;">' +
                                    '</button><br>' +
                                    '<b>' + archivo + '</b>' +
                                    '</div>';
                                $('#modalDescContainer').append(buttonHTML);
                            });
                        }
                        $('#modalDescContainer').append('<hr>');

                        if (data.archivosNo.length > 0) {
                            var archivosNoEncontradosHTML = '<span style="margin-top:20px" class="text-danger">Archivos no encontrados:<br>';
                            data.archivosNo.forEach(function (archivoNoEncontrado) {
                                archivosNoEncontradosHTML += archivoNoEncontrado + '<br>';
                            });
                            archivosNoEncontradosHTML += '</span>';
                            $('#modalDescContainer').append(archivosNoEncontradosHTML);
                        }

                        $('#modalDescarga').modal('show');
                    }
                    else {
                        swal.fire('', 'El archivo no se encuentra en la base de datos', 'error');
                    }
                },
                error: function () {
                    swal.fire('', 'Error al intentar descargar el archivo', 'error');
                }
            });
        } else {
            if (miCliente) {
                swal.fire('', "No se encontró un archivo adjunto para el beneficiario " + miCliente, 'error');
            } else {
                swal.fire('', 'Seleccione un nombre de Beneficiario/Usuario o CI/RUC', 'error')
            }
        }
    });

</script>