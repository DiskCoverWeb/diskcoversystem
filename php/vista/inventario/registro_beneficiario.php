<html>

<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION : 08/05/2024
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->

<head>
    <style>
        #tablaIntegrantes {
            text-align: center;
            white-space: nowrap;
        }

        #tablaPoblacion {
            table-layout: fixed;
            word-wrap: break-word;
        }

        #tablaPoblacion th,
        #tablaPoblacion td {
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        .btnsDD {
            transition: transform 0.3s ease;
        }

        .btnsDD:hover {
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

        .icon {
            transition: transform 0.3s ease;
        }

        .icon:hover {
            transform: translateY(-5px);
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

        .text-danger:hover {
            color: orangered;
            cursor: pointer;
        }

        #calendar {
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js'></script>
</head>

<body>
    <div>
        <div class="row">
            <div class="col-sm-5" id="btnsContainers">
                <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-default">
                    <img src="../../img/png/salire.png" width="35" height="35" alt="Salir">
                </a>
                <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Guardar"
                    id="btnGuardarAsignacion">
                    <img src="../../img/png/disco.png" width="35" height="35" alt="Guardar">
                </button>
                <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Autorizar"
                    id="btnAutorizarCambios">
                    <img src="../../img/png/admin.png" width="35" height="35" alt="Autorizar">
                </button>
            </div>
        </div>

        <div class="accordion" id="accordionExample" style="margin-top:0px; margin-left:30px; margin-right: 30px;">
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

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="select_93" style="display: block;">Tipo de Beneficiario</label>
                                <select class="form-control input-xs" name="select_93" id="select_93"
                                    style="width: 100%;"></select>
                            </div>

                            <div id="carouselBtnImaDon" class="carousel slide" data-ride="carousel"
                                style="margin-right: 10px;">
                                <div class="carousel-inner">
                                </div>
                            </div>

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="select_CxC" style="display: block;">Tipo de Donación</label>
                                <select class="form-control input-xs" name="select_CxC" id="select_CxC"
                                    style="width: 100%;"></select>
                            </div>

                            <div class="campoSocial" style="flex: 1; margin-right: 10px; ">
                                <label for="ruc" style="display: block;">CI/RUC</label>
                                <select class="form-control input-xs" name="ruc" id="ruc" style="width: 100%;"></select>
                            </div>

                            <div class="campoSocial"
                                style="display: flex; justify-content: center; align-items: center;  margin-right: 10px;">
                                <img src="../../img/png/SRIlogo.png" width="80" height="50"
                                    onclick="validarRucYValidarSriC()" id="validarSRI" title="VALIDAR RUC">
                            </div>

                            <div class="row campoSocial" style="margin-right: 10px;">
                                <div class="col-sm-6" style="width:100%">
                                    <label for="cliente" style="display: block;">Nombre del
                                        Beneficiario/Usuario</label>
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
                            </div>

                            <div class="campoFamilia" style="margin-right: 10px;" style="width: 100%;">
                                <label for="fechaIngreso" style="display: block;">Fecha de ingreso</label>
                                <input type="date" id="fechaIngreso">
                            </div>

                            <div id="carouselBtnIma_87" class="carousel slide" data-ride="carousel"
                                style="margin-right: 10px;">
                                <div class="carousel-inner">
                                </div>
                            </div>

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="select_87" style="display: block;">Estado</label>
                                <select class="form-control input-xs" name="select_87" id="select_87"
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="row campoSocial" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; ">
                                <div class="col" style="width:100%">
                                    <label for="nombreRepre" style="display: block;">Nombre Representante
                                        Legal</label>
                                    <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                        placeholder="Nombre Representante">
                                </div>
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

                        <div class="row campoSocial" style="margin: 10px; display: flex; flex-wrap: wrap;">
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
                                <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
                            </div>
                        </div>

                        <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                            <div class="col-sm-3 campoFamilia" style="margin-right:10px;">
                                <div class="row" style="display: flex;">
                                    <div id="carouselBtnIma_93" class="carousel slide" data-ride="carousel"
                                        style="margin-right: 10px; display: inline-block;">
                                        <div class="carousel-inner"></div>
                                    </div>
                                    <div
                                        style="flex: 1; margin-right: 10px; margin-left: 10px; display: inline-block; width: 100%;">
                                        <label for="programa" style="display: block;">Programa</label>
                                        <select class="form-control input-xs" name="programa" id="programa"
                                            style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                        <label for="grupo" style="display: block;">Grupo</label>
                                        <select class="form-control input-xs" name="grupo" id="grupo"
                                            style="width: 100%;"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3 campoFamilia" style="margin-right:10px;">
                                <div class="row">
                                    <label for="nombres" style="display: block;">Nombres</label>
                                    <input class="form-control input-xs" type="text" name="nombres" id="nombres"
                                        placeholder="Nombres">
                                </div>
                                <div class="row">
                                    <label for="apellidos" style="display: block;">Apellidos</label>
                                    <input class="form-control input-xs" type="text" name="apellidos" id="apellidos"
                                        placeholder="Apellidos">
                                </div>

                                <div class="row">
                                    <label for="cedula" style="display: block;">Cédula de identidad</label>
                                    <input class="form-control input-xs" type="text" name="cedula" id="cedula"
                                        placeholder="Cédula de identidad">
                                </div>

                                <div class="row">
                                    <label for="nivelEscolar" style="display: block;">Nivel escolar</label>
                                    <input class="form-control input-xs" type="text" name="nivelEscolar"
                                        id="nivelEscolar" placeholder="Cédula de identidad">
                                </div>

                                <div class="row">
                                    <label for="estadoCivil" style="display: block;">Estado civil</label>
                                    <select class="form-control input-xs" name="estadoCivil" id="estadoCivil"
                                        style="width: 100%;"></select>
                                </div>
                            </div>

                            <div class="col-sm-3 campoFamilia" style="margin-right:10px;">
                                <div class="row">
                                    <label for="edad" style="display: block;">Edad</label>
                                    <input class="form-control input-xs" type="number" name="edad" id="edad"
                                        placeholder="Edad">
                                </div>

                                <div class="row">
                                    <label for="ocupacion" style="display: block;">Ocupación</label>
                                    <input class="form-control input-xs" type="text" name="ocupacion" id="ocupacion"
                                        placeholder="Ocupación">
                                </div>

                                <div class="row">
                                    <label for="telefonoFam" style="display: block;">Teléfono</label>
                                    <input class="form-control input-xs" type="text" name="telefonoFam" id="telefonoFam"
                                        placeholder="Teléfono">
                                </div>

                                <div class="row">
                                    <label for="pregunta" style="display: block;">¿Cómo se enteró del BAQ?</label>
                                    <input class="form-control input-xs" type="text" name="pregunta" id="pregunta"
                                        placeholder="¿Cómo se enteró del BAQ?">
                                </div>
                            </div>

                            <div class="col-sm-2" style="margin-right:10px; text-align: center; padding: 10px;">
                                <div class="row" id="btnMostrarDir">
                                    <img src="../../img/png/map.png" width="60" height="60" title="INGRESAR DIRECCIÓN"
                                        class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label>Ingresar Dirección</label>
                                    </div>
                                </div>

                                <div class="row campoFamilia" id="btnInfoUser">
                                    <img src="../../img/png/infoUser.png" width="60" height="60"
                                        title="INFORMACIÓN DEL USUARIO" class="icon">
                                </div>
                                <div class="row campoFamilia">
                                    <div class="form-group">
                                        <label>Información del usuario</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3 campoSocial" style="margin-right:10px;">
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

                            <div class="col-sm-3 campoSocial" style="margin-right:10px;">
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
                    <div id="mostrarOrgSocialAdd" class="card-body"
                        style="margin: 1px; padding-top: 5px; padding-bottom: 40px;  display: none;">
                        <div class="row" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="select_88" style="display: block;">Tipo de Entrega</label>
                                <select class="form-control input-xs" name="select_88" id="select_88"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="margin-right: 10px; margin-left: 10px; display: flex; ">
                                <img src="../../img/png/calendario2.png" width="60" height="60" id="btnMostrarModal"
                                    title="CALENDARIO ASIGNACION">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="diaEntregac" style="display: block;">Día de Entrega</label>
                                <select class="form-control input-xs" name="diaEntregac" id="diaEntregac"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="margin-right: 10px; margin-left: 10px; display: flex; ">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="horaEntregac" style="display: block;">Hora de Entrega</label>
                                <input type="time" name="horaEntregac" id="horaEntregac" class="form-control input-xs">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
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
                            <div style="margin-right: 10px; margin-left: 10px; display: flex; ">
                                <img src="../../img/png/grupoEdad.png" width="60" height="60" id="btnMostrarGrupo"
                                    title="TIPO DE POBLACIÓN">
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="totalPersonas" style="display: block;">Total de Personas
                                    Atendidas</label>
                                <input type="number" name="totalPersonas" id="totalPersonas"
                                    class="form-control input-xs" min="0" max="100" readonly>
                            </div>

                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="select_92" style="display: block;">Acción Social</label>
                                <select class="form-control input-xs" name="select_92" id="select_92"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="select_90" style="display: block;">Vulnerabilidad</label>
                                <select class="form-control input-xs" name="select_90" id="select_90"
                                    style="width: 100%;"></select>
                            </div>
                            <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                <label for="select_89" style="display: block;">Tipo de Atención</label>
                                <select class="form-control input-xs" name="select_89" id="select_89"
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="row" style="margin: 10px;">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-1" style="margin-right:10px;">
                                <div class="row" style="display: flex; justify-content: center;">
                                    <a href="#" id="descargarArchivo">
                                        <img src="../../img/png/adjuntar-archivo.png" width="60" height="60"
                                            title="DESCARGAR ARCHIVO">
                                    </a>
                                </div>
                                <div class="row">
                                    <label for="archivoAdd">Archivos Adjuntos</label>
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
                    <div id="mostrarFamiliasAdd" class="card-body"
                        style="margin: 1px; padding-top: 5px; padding-bottom: 40px;  display: none;">
                        <div class="row"
                            style="margin: 10px; display: flex; justify-content: center; align-items: center;">
                            <div class="col-sm-2" style="margin-right:10px; text-align: center; padding: 10px;"
                                id="modalEstrFam">
                                <div class="row">
                                    <img src="../../img/png/estructura_familiar.png" width="80" height="80"
                                        title="ESTRUCTURA FAMILIAR" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="">Estructura familiar</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2" style="margin-right:10px; text-align: center; padding: 10px;"
                                id="modalVulnFam">
                                <div class="row">
                                    <img src="../../img/png/vulnerabilidades.png" width="80" height="80"
                                        title="VULNERABILIDADES" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="">Vulnerabilidades</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="margin-right:10px; text-align: center; padding: 10px;"
                                id="modalSituFam">
                                <div class="row">
                                    <img src="../../img/png/situacion_economica.png" width="80" height="80"
                                        title="SITUACIÓN ECONÓMICA" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="">Situación Económica</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="margin-right:10px; text-align: center; padding: 10px;"
                                id="modalViviFam">
                                <div class="row">
                                    <img src="../../img/png/vivienda_servicios.png" width="80" height="80"
                                        title="VIVIENDA Y SERVICIOS BÁSICOS" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="">Vivienda y Servisiós Básicos</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="margin-right:10px; text-align: center; padding: 10px;"
                                id="modalEvalFam">
                                <div class="row">
                                    <img src="../../img/png/evaluacion.png" width="80" height="80" title="EVALUACIÓN"
                                        class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="">Evaluación</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="estructuraFam" class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Estructura familiar</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div style="margin: 10px; overflow-x: auto;">
                        <table class="table" id="tablaIntegrantes">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>Parentesco</th>
                                    <th>Rango de edad</th>
                                    <th>Ocupación</th>
                                    <th>Estado Civil</th>
                                    <th>Nivel de Escolaridad</th>
                                    <th>Nombre de la Institución</th>
                                    <th>Tipo de Institución</th>
                                    <th>Vulnerabilidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="filaAgregar">
                                    <td><input type="text" class="form-control imput-xs" id="nuevoNombre"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoGenero">
                                            <option value="">Seleccione</option>
                                            <option value="masculino">Masculino</option>
                                            <option value="femenino">Femenino</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="nuevoParentesco"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoRangoEdad">
                                            <option value="">Seleccione</option>
                                            <option value="0-5">0-5 años</option>
                                            <option value="6-12">6-12 años</option>
                                            <option value="13-18">13-18 años</option>
                                            <option value="19-64">19-64 años</option>
                                            <option value="65+">65 años o más</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="nuevaOcupacion"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoEstadoCivil">
                                            <option value="">Seleccione</option>
                                            <option value="soltero">Soltero/a</option>
                                            <option value="casado">Casado/a</option>
                                            <option value="divorciado">Divorciado/a</option>
                                            <option value="viudo">Viudo/a</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoNivelEscolaridad">
                                            <option value="">Seleccione</option>
                                            <option value="ninguna">Ninguna</option>
                                            <option value="primaria">Primaria</option>
                                            <option value="secundaria">Secundaria</option>
                                            <option value="bachillerato">Bachillerato</option>
                                            <option value="tecnico">Técnico</option>
                                            <option value="universidad">Universidad</option>
                                            <option value="posgrado">Posgrado</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="nuevoNombreInstitucion">
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoTipoInstitucion">
                                            <option value="">Seleccione</option>
                                            <option value="fiscal">Fiscal</option>
                                            <option value="fiscomisional">Fiscomisional</option>
                                            <option value="particular">Particular</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevaVulnerabilidad">
                                            <option value="">Seleccione</option>
                                            <option value="discapacidad">Discapacidad</option>
                                            <option value="enfermedad">Enfermedad</option>
                                            <option value="ninguna">Ninguna</option>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-primary"
                                            id="agregarIntegrante">Agregar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnTablaIntegrantes">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="vulnerabilidadFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Vulnerabilidades</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div style="margin: 10px; overflow-x: auto;">
                        <table class="table" id="tablaFamVulnerable">
                            <thead>
                                <tr>
                                    <th>Nombre persona</th>
                                    <th>Nombre de la discapacidad</th>
                                    <th>Tipo de discapacidad</th>
                                    <th>% discapacidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnTblFamVulnerable">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="situacionFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Situación Económica</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="viviendaFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Vivienda y Servicios Básicos</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="evaluacionFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Evaluación</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="infoUserFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Información del Usuario</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div class="campoFamilia" style="margin-right: 10px;">
                        <div class="row form-group form-group-xs">
                            <div class="col-sm-6">
                                <div>
                                    <label for="trabajaSelect">¿Trabaja?</label>
                                    <div class="d-flex">
                                        <select class="form-control input-xs" id="trabajaSelect">
                                            <option value="0" selected>Sí</option>
                                            <option value="1">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="trabajaAct" style="display: none;">
                                    <label for="comentarioAct">Actividad:</label>
                                    <textarea class="form-control input-xs" id="comentarioAct" rows="2"
                                        style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="trabajaAct">
                                    <label for="modalidadSelect">Modalidad</label>
                                    <select class="form-control input-xs" id="modalidadSelect">
                                        <option value="" selected>Seleccione una opción</option>
                                        <option value="0">Dependiente</option>
                                        <option value="1">Independiente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group form-group-xs">
                            <div class="col-sm-6">
                                <div>
                                    <label for="conyugeSelect">¿Cónyuge trabaja?</label>
                                    <div class="d-flex">
                                        <select class="form-control input-xs" id="conyugeSelect">
                                            <option value="0" selected>Sí</option>
                                            <option value="1">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="conyugeAct" style="display: none;">
                                    <label for="comentarioConyugeAct">Actividad:</label>
                                    <textarea class="form-control input-xs" id="comentarioConyugeAct" rows="2"
                                        style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="conyugeAct">
                                    <label for="modalidadConyugeSelect">Modalidad</label>
                                    <select class="form-control input-xs" id="modalidadConyugeSelect">
                                        <option value="" disabled selected>Seleccione una opción</option>
                                        <option value="0">Dependiente</option>
                                        <option value="1">Independiente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group form-group-xs">
                            <div class="col-sm-6">
                                <div>
                                    <label for="numHijos">Número de hijos</label>
                                    <input class="form-control input-xs" type="number" id="numHijos" name="numHijos"
                                        min="0">
                                </div>
                                <div>
                                    <label for="numPersonas">Número de personas que viven en la casa</label>
                                    <input class="form-control input-xs" type="number" id="numPersonas"
                                        name="numPersonas" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mycalendar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: white; ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">CALENDARIO DE ASIGNACION</h4>
                </div>
                <div class="modal-body">
                    <div id="calendar" style="overflow-y: auto; max-height: 400px;"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarCale">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalBtnDir" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Registro de Ubicación</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto;">
                    <div class="form-group row">
                        <label for="Provincia" class="col-sm-3 col-form-label">Provincia</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm" id="select_prov" onchange="ciudad(this.value)">
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
                            <tbody>
                                <!-- filas -->
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

    <div id="modalDescarga" data-backdrop="static" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Gestionar Archivos</h4>
                </div>
                <div class="modal-body" style="margin:10px">
                    <div class="row-sm-12">
                        <div id="cargarArchivo" class="form-group" style="display: flex;">
                            <label for="archivoAdd">Adjuntar Archivos: (máximo 3 archivos) </label>
                            <input type="file" style="margin-left: 10px" class="form-control-file" id="archivoAdd"
                                multiple onchange="checkFiles(this)">
                        </div>
                    </div>
                    <div class="row-sm-12" style="width: 100%; margin-right:10px; margin-left:10px;">
                        <div class="form-group" style="display: flex; justify-content: center;">
                            <div id="modalDescContainer" class="d-flex justify-content-center flex-wrap">
                            </div>
                        </div>
                    </div>
                    <div class="row-sm-12">
                        <div class="col" id="divNoFile" style="display:flex">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:none">
                    <div class="row" style="margin: 10px;">
                        <div class="col-xs-4">

                        </div>
                        <div class="col-xs-4">
                            <button id="btnDescargar" type="button" class="btn btn-default btn-block"
                                onclick="descargarArchivo(ruta, nombre)">
                                <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Descargar
                            </button>
                        </div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-danger btn-block"
                                onclick="eliminarArchivo(ruta, nombre)">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
                            </button>
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
                                    <img src="../../img/png/animales.png" style="width: 90%; height: 90%;" alt="Imagen">
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
                        <div id="modal_87" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
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
                        <div id="modal_93" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
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

</body>

<script>
    $(document).ready(function () {
        //$("#btnUsarCli").hide();        
        Form_Activate();
        $('.campoSocial').hide();
        $('.campoFamilia').hide();


        var fechaActual = new Date().toISOString().split('T')[0];
        $('#fechaIngreso').val(fechaActual);
    });

    $("#btnTablaIntegrantes").click(function () {
        console.log(integrantes);
        $('#estructuraFam').modal('hide');
    });
    var integrantes = [];

    $("#agregarIntegrante").click(function () {
        console.log('click para nuevo integrante');
        var integrante = {
            nombre: $("#nuevoNombre").val(),
            genero: $("#nuevoGenero").val(),
            parentesco: $("#nuevoParentesco").val(),
            rangoEdad: $("#nuevoRangoEdad").val(),
            ocupacion: $("#nuevaOcupacion").val(),
            estadoCivil: $("#nuevoEstadoCivil").val(),
            nivelEscolaridad: $("#nuevoNivelEscolaridad").val(),
            nombreInstitucion: $("#nuevoNombreInstitucion").val(),
            tipoInstitucion: $("#nuevoTipoInstitucion").val(),
            vulnerabilidad: $("#nuevaVulnerabilidad").val()
        };
        integrantes.push(integrante);
        actualizarTabla();
        limpiarCampos();
    });

    function actualizarTabla() {
        var tablaBody = $("#tablaIntegrantes tbody");
        tablaBody.children(':not(:first)').remove();

        for (var i = 0; i < integrantes.length; i++) {
            var integrante = integrantes[i];
            var fila = $("<tr></tr>");
            fila.append($("<td></td>").text(integrante.nombre));
            fila.append($("<td></td>").text(integrante.genero));
            fila.append($("<td></td>").text(integrante.parentesco));
            fila.append($("<td></td>").text(integrante.rangoEdad));
            fila.append($("<td></td>").text(integrante.ocupacion));
            fila.append($("<td></td>").text(integrante.estadoCivil));
            fila.append($("<td></td>").text(integrante.nivelEscolaridad));
            fila.append($("<td></td>").text(integrante.nombreInstitucion));
            fila.append($("<td></td>").text(integrante.tipoInstitucion));
            fila.append($("<td></td>").text(integrante.vulnerabilidad));
            fila.append($("<td><button type='button' class='btn btn-danger btn-eliminar'>Eliminar</button> <button type='button' class='btn btn-warning btn-editar'>Editar</button></td>"));
            tablaBody.append(fila);
        }
    }

    function limpiarCampos() {
        $("#nuevoNombre").val("");
        $("#nuevoGenero").val("");
        $("#nuevoParentesco").val("");
        $("#nuevoRangoEdad").val("");
        $("#nuevaOcupacion").val("");
        $("#nuevoEstadoCivil").val("");
        $("#nuevoNivelEscolaridad").val("");
        $("#nuevoNombreInstitucion").val("");
        $("#nuevoTipoInstitucion").val("");
        $("#nuevaVulnerabilidad").val("");
    }

    $("#tablaIntegrantes").on("click", ".btn-eliminar", function () {
        var fila = $(this).closest("tr");
        var index = fila.index();
        integrantes.splice(index - 1, 1);
        actualizarTabla();
    });

    $("#tablaIntegrantes").on("click", ".btn-editar", function () {
        var fila = $(this).closest("tr");
        var index = fila.index();
        var integrante = integrantes[index - 1];
        $("#nuevoNombre").val(integrante.nombre);
        $("#nuevoGenero").val(integrante.genero);
        $("#nuevoParentesco").val(integrante.parentesco);
        $("#nuevoRangoEdad").val(integrante.rangoEdad);
        $("#nuevaOcupacion").val(integrante.ocupacion);
        $("#nuevoEstadoCivil").val(integrante.estadoCivil);
        $("#nuevoNivelEscolaridad").val(integrante.nivelEscolaridad);
        $("#nuevoNombreInstitucion").val(integrante.nombreInstitucion);
        $("#nuevoTipoInstitucion").val(integrante.tipoInstitucion);
        $("#nuevaVulnerabilidad").val(integrante.vulnerabilidad);
        integrantes.splice(index - 1, 1);
        actualizarTabla();
    });

    $('#btnInfoUser').click(function () {
        $('#infoUserFam').modal('show');
        if ($('#trabajaSelect').val() === '0') {
            $('.trabajaAct').show();
        }
        if ($('#conyugeSelect').val() === '0') {
            $('.conyugeAct').show();
        }
    });

    $('#trabajaSelect').change(function () {
        var valorSeleccionado = $(this).val();
        if (valorSeleccionado === '0') {
            $('.trabajaAct').show();
        } else {
            $('.trabajaAct').hide();
            $('#comentarioAct').val('');
            $('#modalidadSelect').val('');

        }
    });

    $('#conyugeSelect').change(function () {
        var valorSeleccionado = $(this).val();
        if (valorSeleccionado === '0') {
            $('.conyugeAct').show();
        } else {
            $('.conyugeAct').hide();
            $('#comentarioConyugeAct').val('');
            $('#modalidadConyugeSelect').val('');
        }
    });

    $('#modalEstrFam').click(function () {
        $('#estructuraFam').modal('show');
    });

    var integrantesDiscapacitados = [];
    $("#btnTblFamVulnerable").click(function () {
        console.log('hola1');
        $("#tablaFamVulnerable tbody tr").each(function () {
            var $row = $(this);
            var nombre = $row.find("td:first").text();
            var nombreDiscapacidad = $row.find("#nombreDiscapacidad").val();
            var tipoDiscapacidad = $row.find("#tipoDiscapacidad").val();
            var porcentajeDiscapacidad = $row.find("#xDiscapacidad").val();

            if (nombreDiscapacidad && tipoDiscapacidad && porcentajeDiscapacidad) {
                integrantesDiscapacitados.push({
                    nombre: nombre,
                    nombreDiscapacidad: nombreDiscapacidad,
                    tipoDiscapacidad: tipoDiscapacidad,
                    porcentajeDiscapacidad: porcentajeDiscapacidad
                });
            }
        });

        console.log(integrantesDiscapacitados);
        $('#vulnerabilidadFam').modal('hide');
    });

    $('#modalVulnFam').click(function () {
        if (integrantes.length > 0) {
            $("#tablaFamVulnerable tbody").empty();
            var tablaBody = $("#tablaFamVulnerable tbody");
            if (integrantesDiscapacitados.length > 0) {
                for (var i = 0; i < integrantesDiscapacitados.length; i++) {
                    var integrante = integrantesDiscapacitados[i];
                    var fila = $("<tr></tr>");
                    fila.append($("<td></td>").text(integrante.nombre));
                    fila.append($("<td></td>").text(integrante.nombreDiscapacidad));
                    fila.append($("<td></td>").text(integrante.tipoDiscapacidad));
                    fila.append($("<td></td>").text(integrante.porcentajeDiscapacidad));
                    tablaBody.append(fila);
                }
            } else {
                for (var i = 0; i < integrantes.length; i++) {
                    var integrante = integrantes[i];
                    if (integrante.vulnerabilidad == 'discapacidad')
                        var fila = $("<tr></tr>");
                    fila.append($("<td></td>").text(integrante.nombre));
                    fila.append($("<td><input type='text' class='form-control imput-xs' id='nombreDiscapacidad'></td>"));
                    fila.append($("<td><select class='form-control imput-xs' id='tipoDiscapacidad'> " +
                        "<option value=''>Seleccione</option>" +
                        "<option value='fisica'>Física</option>" +
                        "<option value='mental'>Mental</option>" +
                        "<option value='social'>Social</option></select></td>"));
                    fila.append($("<td><input type='text' class='form-control imput-xs' id='xDiscapacidad'></td>"));
                    tablaBody.append(fila);
                }
            }

            $('#vulnerabilidadFam').modal('show');
        } else {
            nombreSol = $('#nombres').val();
            swal.fire('', 'No hay integrantes para el Sr.(a)' + nombreSol, 'info');
        }

    });

    $('#modalSituFam').click(function () {
        $('#situacionFam').modal('show');
    });

    $('#modalViviFam').click(function () {
        $('#viviendaFam').modal('show');
    });

    $('#modalEvalFam').click(function () {
        $('#evaluacionFam').modal('show');
    });



    function Calendario(datos) {
        return new Promise((resolve, reject) => {
            const promesas = datos.map(async (cliente) => {
                var Actividad = cliente.Actividad || '';
                var Cliente = cliente.Cliente || '';
                var Envio_No = cliente.Envio_No || '';
                var Dia_Ent = cliente.Dia_Ent || '';
                var Hora_Ent = cliente.Hora_Ent || '';
                var colorV = await ObtenerColor(Envio_No);
                var fechaActual = new Date();
                var diaSemana = fechaActual.getDay();
                var fechaEvento;

                switch (Dia_Ent) {
                    case 'Lun':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 1);
                        break;
                    case 'Mar':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 2);
                        break;
                    case 'Mie':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 3);
                        break;
                    case 'Jue':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 4);
                        break;
                    case 'Vie':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 5);
                        break;
                    case 'Sab':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 6);
                        break;
                    case 'Dom':
                        fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 0);
                        break;
                    default:
                        fechaEvento = fechaActual;
                }

                const fechaInicio = new Date(fechaEvento.getFullYear(), fechaEvento.getMonth(), fechaEvento.getDate(), Hora_Ent.split(':')[0], Hora_Ent.split(':')[1]);
                const fechaFin = new Date(fechaInicio.getTime() + 30 * 60000);

                return {
                    title: Cliente,
                    start: fechaInicio,
                    end: fechaFin,
                    backgroundColor: colorV,
                    textColor: 'black',
                };
            });

            Promise.all(promesas)
                .then((events) => {
                    resolve(events);
                    inicializarCalendario(events);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }

    var eventosEliminados = [];
    var eventosEditados = [];
    var eventosCreados = [];
    function inicializarCalendario(events) {
        //console.log(events);
        $('#mycalendar').modal('show');
        var calendarEl = $("#calendar")[0];
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                //left: 'prev,next today',
                //center: 'title',
                right: 'timeGridWeek,listWeek'
            },
            locale: 'es',
            views: {
                timeGridWeek: {
                    buttonText: 'Semana'
                },
                listWeek: {
                    buttonText: 'Lista'
                }
            },
            allDaySlot: false,
            weekends: false,
            navLinks: true,
            selectable: true,
            selectMirror: true,
            slotMinTime: '09:30:00',
            slotMaxTime: '16:30:00',
            slotDuration: '00:15:00',
            select: function (arg) {
                var eventoExistente = calendar.getEvents().find(function (evento) {
                    return evento.title === miCliente;
                });

                if (!eventoExistente) {
                    var endDate = new Date(arg.start.getTime() + 30 * 60000);
                    var nuevoEvento = {
                        title: miCliente,
                        start: arg.start,
                        end: endDate,
                        allDay: arg.allDay
                    };
                    calendar.addEvent(nuevoEvento);
                    eventosCreados.push(nuevoEvento);
                } else {
                    swal.fire("", "El usuario ya tiene una asignación en el Calendario", "error");
                }
                calendar.unselect();
            },
            eventClick: function (arg) {
                if (arg.event.title === miCliente) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción eliminará el evento',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            arg.event.remove();
                            eventosEliminados.push({
                                title: arg.event.title,
                                start: arg.event.start,
                                end: arg.event.end
                            });
                        }
                    });
                }
            },
            editable: true,
            dayMaxEvents: true,
            events: events.map(event => {
                console.log(`Evento: ${event.title}, Día: ${event.start.getDay()}`);
                return event;
            }).concat([
                {
                    daysOfWeek: [1, 2, 3, 4, 5],
                    startTime: '12:30',
                    endTime: '13:30',
                    display: 'background',
                    rendering: 'background'
                }
            ]),
            eventChange: function (info) {
                if (info.event.title === miCliente) {
                    var index = eventosEditados.findIndex(function (item) {
                        return item.title === miCliente;
                    });
                    if (index !== -1) {
                        eventosEditados[index] = {
                            title: info.event.title,
                            start: info.event.start,
                            end: info.event.end
                        };
                    } else {
                        eventosEditados.push({
                            title: info.event.title,
                            start: info.event.start,
                            end: info.event.end
                        });
                    }
                }
            }
        });

        calendar.render();
    }

    $('#btnGuardarCale').click(function () {
        $('#mycalendar').modal('hide');
        console.log('editados');
        console.log(eventosEditados);
        console.log('eliminados');
        console.log(eventosEliminados);
        console.log('creados');
        console.log(eventosCreados);

        if (eventosEditados.length > 0) {
            eventosEditados.forEach(function (evento) {
                var startDate = new Date(evento.start);
                var dayName = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'][startDate.getDay()];
                var startTime = startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                $('#diaEntregac').val(dayName.substring(0, 3));

                $('#horaEntregac').val(startTime);
            });
        } else {
            eventosCreados.forEach(function (evento) {
                var startDate = new Date(evento.start);
                var dayName = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'][startDate.getDay()];
                var startTime = startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                $('#diaEntregac').val(dayName.substring(0, 3));
                $('#horaEntregac').val(startTime);
            });
        }

        if (eventosEliminados.length > 0) {
            $('#diaEntregac').val('');
            $('#horaEntregac').val('');

        }
    });

    function checkFiles(input) {
        console.log(contador);
        var maxFiles = 3;
        var max = 3;
        const maxFileSize = 10;

        const files = input.files;
        if (contador != 0) {
            max = maxFiles - contador;
            if (files.length > max) {
                Swal.fire({
                    title: 'Solo se permiten un máximo de ' + maxFiles + ' archivos.',
                    text: 'Ya cargó ' + contador + ' archivo (s). Puede eliminar algunos si es necesario.',
                    type: 'error'
                });
                input.value = '';
                return;
            }
        }
        if (files.length > maxFiles) {
            Swal.fire({
                title: 'Solo se permiten un máximo de ' + maxFiles + ' archivos.',
                text: 'Intentó cargar ' + files.length + ' archivo (s).',
                type: 'error'
            });
            input.value = '';
        } else if (files.length > 0) {
            var fileNames = [];
            var fileSizeLimit = false;
            var contieneSpecialChar = false;
            var specialChar = /[!@#$%^&*()+\-=\[\]{};':"\\|,<>\/?]/;

            for (var i = 0; i < files.length; i++) {
                var fileName = files[i].name.toLowerCase();
                console.log(fileName);
                if (specialChar.test(fileName)) {
                    contieneSpecialChar = true;
                    break;
                }

                if (fileName.includes(' ')) {
                    fileName = fileName.replace(/ /g, "_");
                }
                console.log(fileName);
                fileNames.push(fileName);

                if (files[i].size > maxFileSize * 1024 * 1024) {
                    fileSizeLimit = true;
                    break;
                }
            }

            if (contieneSpecialChar) {
                Swal.fire({
                    title: 'Los nombres de los archivos no deben contener caracteres especiales',
                    text: specialChar,
                    type: 'error'
                });
                input.value = '';
            } else if (fileSizeLimit) {
                Swal.fire({
                    title: 'El tamaño máximo permitido por archivo es de ' + maxFileSize + 'MB.',
                    text: '',
                    type: 'error'
                });
                input.value = '';
            } else {
                if (contador != 0) {
                    fileNames.push(nombreArchivo);
                }
                var fileList = fileNames.join(',');
                console.log(fileList);
                if (fileList.length > 50) {
                    Swal.fire({
                        title: 'La longitud total de los nombres de archivo supera el máximo de caracteres.',
                        text: '',
                        type: 'error'
                    });
                    input.value = '';
                } else {
                    $('#modalDescarga').modal('hide');
                    Swal.fire({
                        title: 'Archivos cargados con éxito',
                        text: 'Archivos seleccionados: ' + fileList,
                        type: 'success'
                    });
                    $('#modalDescarga .modal-footer').hide();
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
        agregarFila();
    });

    function agregarFila() {
        console.log(valoresFilas);
        var tbody = $('#tablaPoblacion tbody');
        tbody.empty();
        datosArray.forEach(function (item) {
            var valor = item.id.substring(0, 2);
            //console.log(item);
            if (valor == 91) {
                var fila = $('<tr>', { valueData: item.id });
                var celda1 = $('<td>', { colspan: 2, text: item.text });
                var celda2 = $('<td>').append($('<input>', { type: 'number', class: 'form-control hombres', name: 'hombres', value: 0 }));
                var celda3 = $('<td>').append($('<input>', { type: 'number', class: 'form-control mujeres', name: 'mujeres', value: 0 }));
                var celda4 = $('<td>').append($('<input>', { type: 'number', class: 'form-control total', name: 'total', readonly: true, value: 0 }));
                fila.append(celda1, celda2, celda3, celda4);
                tbody.append(fila);

                var valorFila = valoresFilas.find(f => f.valueData === item.id);

                if (valorFila) {
                    fila.find('.hombres').val(valorFila.hombres);
                    fila.find('.mujeres').val(valorFila.mujeres);
                    fila.find('.total').val(valorFila.total);
                }

                fila.find('.hombres, .mujeres').on('change', function () {
                    var hombres = parseInt(fila.find('.hombres').val()) || 0;
                    var mujeres = parseInt(fila.find('.mujeres').val()) || 0;
                    var total = hombres + mujeres;
                    fila.find('.total').val(total);
                });
            }
        });
    }

    var valoresFilas = [];
    $('#btnGuardarGrupo').click(function () {
        var filas = $('#tablaPoblacion tbody tr');
        valoresFilas = [];
        var totalSum = 0;
        filas.each(function () {
            var hombres = parseInt($(this).find('.hombres').val()) || 0;
            var mujeres = parseInt($(this).find('.mujeres').val()) || 0;
            var total = parseInt($(this).find('.total').val()) || 0;
            var textoFila = $(this).find('td:first-child').text();
            var valueData = $(this).attr('valueData');

            if (hombres > 0 || mujeres > 0 || total > 0) {
                console.log('Fila:', textoFila);
                console.log('Hombres:', hombres);
                console.log('Mujeres:', mujeres);
                console.log('Total:', total);
                console.log('-------------------');
                totalSum += total;
                valoresFilas.push({ hombres, mujeres, total, valueData });
            }
        });
        console.log(totalSum);
        $('#totalPersonas').val(totalSum);
        $('#modalBtnGrupo').modal('hide');
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
                if (valor != 91) {
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
                                    '<button type="button" class="btn btn-default btn-sm" onclick="' +
                                    (item.id === '93.04' ? "itemSelect('" + item.picture + "','" + item.text +
                                        "','" + item.color + "','" + item.id + "'); abrirModal('pAliado');" :
                                        "itemSelect('" + item.picture + "','" + item.text + "','" + item.color +
                                        "','" + item.id + "');") +
                                    '">' +
                                    '<img src="../../img/png/' + item.picture + '.png" style="width: 60px;height: 60px;"></button><br>' +
                                    '<b>' + item.text + '</b>' +
                                    '</div>';
                                opt += '<option value="' + item.id + '">' + item.text + '</option>';
                            });
                            $('#modal_93').html(option);
                        }
                    }
                }

            }
        });
    }

    function handleSelectEvent(e, valor) {
        var data = e.params.data;
        var id = data.id;
        datosArray.forEach(function (item) {
            if (item.id == id) {
                var imagen = "../../img/png/" + item.picture + ".png";
                if (valor == 93) {
                    var val = id.substring(0, 2);
                    $("#carouselBtnIma_" + val + " .item.active img").attr("src", imagen);
                    $("#carouselBtnIma_" + val).carousel("pause");
                    actualizarEstilo(item.color);
                    if (id == "93.04") abrirModal('pAliado');
                }
                if (valor == 87) {
                    var val = id.substring(0, 2);
                    $("#carouselBtnIma_" + val + " .item.active img").attr("src", imagen);
                    $("#carouselBtnIma_" + val).carousel("pause");
                }
                if (valor == 0) {
                    $("#carouselBtnImaDon .item.active img").attr("src", imagen);
                    $("#carouselBtnImaDon").carousel("pause");
                }
            }
        });
    }

    $('#select_93').on('select2:select', function (e) {
        handleSelectEvent(e, 93);
    });
    $('#select_87').on('select2:select', function (e) {
        handleSelectEvent(e, 87);
    });
    $('#select_CxC').on('select2:select', function (e) {
        handleSelectEvent(e, 0);
    });

    function abrirModal(valor) {
        $('#modalsBtn' + valor).modal('show');
    };

    function itemSelect(picture, text, color, id) {
        console.log(id);
        if (id.length == 3) {
            var imagen = "../../img/png/" + picture + ".png";

            $("#carouselBtnImaDon .item.active img").attr("src", imagen);
            $("#carouselBtnImaDon").carousel("pause");
            $("#modalsBtnDon").modal("hide");
            var newOption = new Option(text, id, true, true);
            $('#select_CxC').append(newOption).trigger('change');
        } else {
            var valor = id.substring(0, 2);
            var imagen = "../../img/png/" + picture + ".png";

            $("#carouselBtnIma_" + valor + " .item.active img").attr("src", imagen);
            $("#carouselBtnIma_" + valor).carousel("pause");
            $("#modalsBtn" + valor).modal("hide");

            if (valor == 93) {
                actualizarEstilo(color);
            }

            var newOption = new Option(text, id, true, true);
            $('#select_' + valor).append(newOption).trigger('change');
        }
    }

    //btn icono RUC
    function validarRucYValidarSriC() {
        var ruc = $('#ruc').val();
        console.log(ruc);
        if (ruc) {
            validar_sriC(ruc);
        } else {
            Swal.fire({
                title: 'Por favor, seleccione un RUC',
                text: '',
                type: 'error'
            });
        }
    }

    //btn dentro de modal icono RUC
    function usar_cliente(nombre, ruc, codigo, email, td = 'N') {
        LimpiarPanelOrgSocialAdd();

        var newOption = new Option(nombre, codigo, true, true);
        $('#cliente').append(newOption).trigger('change');

        var newOption = new Option(ruc, codigo, true, true);
        $('#ruc').append(newOption).trigger('change');

        miCliente = nombre;
        miRuc = ruc;
        miCodigo = codigo;

        $('#myModal').modal('hide');
        llenarCamposInfo(codigo);
    }

    var horaActual;
    function Form_Activate() {
        $('#comentariodiv').hide();
        LlenarSelectDiaEntrega();
        LlenarSelectSexo();
        LlenarSelectRucCliente();
        llenarCarousels(87);
        llenarCarousels(93);
        llenarCarousels(91);
        llenarCarousels("CxC", true);
        LlenarSelects_Val("CxC", true);
        LlenarSelects_Val(86);
        LlenarSelects_Val(87);
        LlenarSelects_Val(88);
        LlenarSelects_Val(89);
        LlenarSelects_Val(90);
        LlenarSelects_Val(91);
        LlenarSelects_Val(92);
        LlenarSelects_Val(93);
        //[86, 87, 88, 89, 90, 91, 92, 93].forEach(LlenarSelects_Val);

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
        var valorSeleccionado = $('#select_93').val();
        if (valorSeleccionado !== null && valorSeleccionado !== undefined) {
            LlenarCalendario(valorSeleccionado);
        } else {
            Swal.fire({
                title: 'Por favor, seleccione una organización',
                text: '',
                type: 'info'
            });
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
                    //console.log(datos);
                    if (datos != 0 && datos[0].Envio_No != null) {
                        //LlenarCalendarioC(datos);
                        Calendario(datos);
                    } else {
                        $('#tabla-body').empty();
                        Swal.fire({
                            title: 'No se encontraron datos de asignación',
                            text: '',
                            type: 'info'
                        });
                    }
                }
            });
        }
    }

    //color para celdas del calendario
    function ObtenerColor(valEnvio_No) {
        return new Promise((resolve) => {
            if (valEnvio_No) {
                $.ajax({
                    url: '../controlador/inventario/registro_beneficiarioC.php?ObtenerColor=true',
                    type: 'post',
                    dataType: 'json',
                    data: { valor: valEnvio_No },
                    success: function (data) {
                        var color = "#" + data.Color.substring(4);
                        resolve(color);
                    }
                });
            } else {
                resolve('#000000');
            }
        });
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

    //llenar select Donacion
    function LlenarTipoDonacion() {
        $('#select_CxC').select2({
            placeholder: 'Seleccione una opcion',
            ajax: {
                url: '../controlador/inventario/registro_beneficiarioC.php?',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        LlenarTipoDonacion: true
                    }
                },
                processResults: function (data) {
                    var options = [];
                    if (data.respuesta === "No se encontraron datos para mostrar") {
                        options.push({
                            id: '',
                            text: data.respuesta
                        });
                    } else {
                        $.each(data.respuesta, function (index, item) {
                            var idDigits = item.id.slice(-3);
                            options.push({
                                id: idDigits,
                                text: item.text
                            });
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

    //todos los selects_num
    function LlenarSelects_Val(valor, valor2) {
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
                        valor2: valor2,
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

    function autorizarCambios() {
        Swal.fire({
            title: "Se requiere autorización para modificar el beneficiario: " + miCliente,
            text: "¿Desea proceder ingresando su contraseña?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.value) {
                $('#TipoSuper_MYSQL').val('Actualizar');
                $('#titulo_clave').text('Autorizar Cambios');
                $('#clave_supervisor').modal('show');
            } else {
                $('#collapseTwo').collapse('hide');
            }
        });
    }

    $('#btnAutorizarCambios').click(function () {
        //console.log('hola');
        //console.log(miCliente);
        if (miCliente != undefined) {
            autorizarCambios();
        } else {
            Swal.fire({
                title: 'No se seleccionó un Cliente',
                text: '',
                type: 'warning',
            });
        }
    });

    function resp_clave_ingreso(response) {
        if (response.respuesta == 1) {
            $('#clave_supervisor').modal('hide');
            $('#collapseTwo').collapse('show');
            userAuth = true;
        } else {
            console.log('no se ingreso pass');
            $('#collapseTwo').collapse('hide');
        }
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
        formData.append('Actividad', $('#select_93').val() || '.');
        formData.append('CodigoA', $('#select_87').val() || '.');
        formData.append('Calificacion', $('#select_CxC').val() || '.');
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
        //formData.append('Area', $('#select_91').val());
        formData.append('Acreditacion', $('#select_92').val());
        formData.append('Tipo_Dato', $('#select_90').val());
        formData.append('Cod_Fam', $('#select_89').val());
        formData.append('Observaciones', $('#infoNut').val());

        console.log(valoresFilas);
        formData.append('TipoPoblacion', JSON.stringify(valoresFilas));

        console.log("Tipo Poblacion:");
        formData.forEach(function (value, key) {
            if (key == 'TipoPoblacion[]') {
                console.log(value);
            }
        });

        console.log("Added Evidences:");
        for (var [key, value] of formData.entries()) {
            if (key === 'Evidencias[]') {
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
        //if (!$('#select_91').val()) camposVacios.push('Tipo poblacion');
        if (valoresFilas.length == 0) {
            camposVacios.push('Tipo Poblacion');
        }
        if (!$('#select_92').val()) camposVacios.push('Accion social');
        if (!$('#select_90').val()) camposVacios.push('Vulnerabilidad');
        if (!$('#select_89').val()) camposVacios.push('Tipo Atencion');
        if (!fileInput.files.length && nombreArchivo == "") camposVacios.push('Evidencias');
        if (!$('#infoNut').val()) camposVacios.push('Observaciones');

        if (userNew == false && userAuth == false) {
            Swal.fire({
                title: '',
                text: "Usted no está autorizado.",
                type: 'error',
                confirmButtonText: 'Aceptar'
            });
        } else if (camposVacios.length > 0) {
            var mensaje = 'Los siguientes campos están vacíos:\n';
            camposVacios.forEach(function (campo) {
                mensaje += campo + ',';
            });
            Swal.fire({
                title: 'Campos Vacíos',
                text: mensaje,
                type: 'error',
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
                        nombreArchivo = response.datos.result;
                        console.log(nombreArchivo);
                    }
                }
            });
        }
    });

    //limpieza
    function LimpiarPanelOrgSocialAdd() {
        eventosEliminados = [];
        eventosEditados = [];
        eventosCreados = [];
        userAuth = false;
        userNew = false;
        $('#collapseTwo').collapse('hide');
        $('#select_92').val(null).trigger('change');
        $('#select_86').val(null).trigger('change');
        $('#select_88').val(null).trigger('change');
        $('#select_89').val(null).trigger('change');
        $('#select_90').val(null).trigger('change');
        //$('#select_91').val(null).trigger('change');
        $('#archivoAdd').val('');
        $('#diaEntregac').val('');
        $('#horaEntregac').val('');
        $('#totalPersonas').val('');
        $('#infoNut').val('');
        $('#comentario').val('');
        comen = '';
        nombreArchivo = '';
        ruta = '';
        nombre = '';
        valoresFilas = [];
        $('#modalDescarga .modal-footer').hide();
    }

    //llenar campos del cliente segun nombre seleccionado
    var miRuc;
    var miCodigo = '';
    var miCliente;
    var nombreArchivo;
    $('#cliente').on('select2:select', function (e) {
        LimpiarPanelOrgSocialAdd();
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.CI_RUC;
        miCliente = data.text;
        if (data.id === '.') {
            Swal.fire("", "No se encontró un RUC relacionado.", "error");
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
        LimpiarPanelOrgSocialAdd();
        var data = e.params.data;
        miCodigo = data.id;
        miRuc = data.text;
        miCliente = data.Cliente;
        if (data.id === '.') {
            Swal.fire("No se encontró un Cliente relacionado.", "", "error");
        } else {
            if ($('#cliente').find("option[value='" + data.id + "']").length) {
                $('#cliente').val(data.id).trigger('change');
            } else {
                var newOption = new Option(data.Cliente, data.id, true, true);
                $('#cliente').append(newOption).trigger('change');
            }
            //var valorSeleccionado = $('#cliente').val();
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
                    actualizarEstilo();

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

                    if (datos.CodigoA == '.') {
                        $('#select_87').val(null).trigger('change');
                        $('#carouselBtnIma_87').carousel("cycle");
                    }
                    if (datos.Actividad == '.') {
                        $('#select_93').val(null).trigger('change');
                        $('#carouselBtnIma_93').carousel("cycle");
                    }
                    if (datos.Calificacion == '.') {
                        $('#select_CxC').val(null).trigger('change');
                        $('#carouselBtnImaDon').carousel("cycle");
                    }


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

    $('#select_93').change(function () {
        LimpiarPanelOrgSocialAdd();
    });

    $('#select_93').change(function () {
        var valorSeleccionado = $('#select_93').val();
        switch (valorSeleccionado) {
            case '93.01':
                console.log('social93');
                $('.campoSocial').show();
                $('.campoFamilia').hide();
                break;
            case '93.02':
                console.log('familia93');
                $('.campoSocial').hide();
                $('.campoFamilia').show();
                break;
            case '93.03':
                console.log('operativo93');
                $('.campoSocial').hide();
                $('.campoFamilia').hide();
                break;
            case '93.04':
                console.log('productor93');
                $('.campoSocial').hide();
                $('.campoFamilia').hide();
                break;
        }
    });


    //llenar campos panel org.social
    function CamposPanelOrgSocial() {
        $("#mostrarFamiliasAdd").css("display", "none");
        if (miCodigo) {
            $("#mostrarOrgSocialAdd").css("display", "block");
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
                        if (userNew == false && userAuth == false) {
                            autorizarCambios();
                        }
                    } else {
                        userNew = true;
                        Swal.fire({
                            title: 'No se encontraron datos adicionales',
                            text: '',
                            type: 'info'
                        });
                    }
                }
            });
            llenarCamposPoblacion(miCodigo);
        }
        else {
            $('#collapseTwo').collapse('hide');
            Swal.fire({
                title: 'No se seleccionó un Beneficiario/Usuario',
                text: '',
                type: 'warning',
            });
        }
    }

    //llenar campos panel familias
    function CamposPanelFamilias() {
        $("#mostrarFamiliasAdd").css("display", "block");
        $("#mostrarOrgSocialAdd").css("display", "none");

    }

    //llenar campos de panel informacion adicional
    var comen;
    var userNew = false;
    var userAuth = false;
    $('#botonInfoAdd').click(function () {
        var valorSeleccionado = $('#select_93').val();
        switch (valorSeleccionado) {
            case '93.01':
                console.log('social');
                CamposPanelOrgSocial();
                break;
            case '93.02':
                console.log('familia');
                CamposPanelFamilias();
                break;
            case '93.03':
                console.log('operativo');
                break;
            case '93.04':
                console.log('productor');
                break;
            default:
        }
    });

    function llenarCamposPoblacion(Codigo) {
        //console.log('hola');
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposPoblacion=true',
            type: 'post',
            dataType: 'json',
            data: { valor: Codigo },
            success: function (datos) {
                if (datos != 0) {
                    console.log(datos);
                    datos.forEach(function (registro) {
                        var hombres = registro.Hombres;
                        var mujeres = registro.Mujeres;
                        var total = registro.Total;
                        var valueData = registro.Cmds;
                        valoresFilas.push({ hombres, mujeres, total, valueData });
                    });
                }
            }
        });
    }

    //llenar selects preseleccionados
    function llenarPreSelects(valor) {
        if (valor != ".") {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true',
                type: 'GET',
                dataType: 'json',
                data: { valor: valor },
                success: function (res) {
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
            $('.card-header, .modal-header').css('background-color', '#f3e5ab');
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

    var ruta;
    var nombre;
    function DownloadOrDelete(archivo, noDescarga) {
        nombre = archivo;
        //console.log(nombre);
        if (noDescarga == true) {
            $('#btnDescargar').hide();
            //console.log('hola1');
        }
        else { $('#btnDescargar').show(); }
        $('#modalDescarga .modal-footer').show();
    }

    //descarga del archivo adjunto
    function descargarArchivo(url, nombre) {
        $('#modalDescarga').modal('hide');
        $('#modalDescarga .modal-footer').hide();
        Swal.fire({
            title: '',
            text: "Archivo descargado con éxito",
            type: 'success',
        });
        var ruta = "../../" + url + nombre;
        var enlaceTemporal = $('<a></a>')
            .attr('href', ruta)
            .attr('download', nombre)
            .appendTo('body');
        enlaceTemporal[0].click();
        enlaceTemporal.remove();
    }

    function eliminarArchivo(url, nombre) {
        $('#modalDescarga').modal('hide');
        $('#modalDescarga .modal-footer').hide();
        var parametros = {
            'nombre': nombre,
            'ruta': ruta,
            'codigo': miCodigo,
        };
        Swal.fire({
            title: 'Formulario de confirmación',
            text: "(SI) Eliminar el archivo: " + nombre + "\n(NO) Cancelar",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.value) {
                console.log(result.value);
                $.ajax({
                    type: "POST",
                    url: '../controlador/inventario/registro_beneficiarioC.php?EliminaArchivosTemporales=true',
                    dataType: 'json',
                    data: { 'parametros': parametros },
                    success: function (data) {
                        console.log(data.res2);
                        if (data.res == 0) {
                            Swal.fire({
                                title: 'Archivo eliminado con éxito',
                                text: '',
                                type: 'success',
                            });
                            nombreArchivo = data.res2;
                            $('#modalDescarga .modal-footer').hide();
                        }
                    }
                });
            }
        });
    }

    var contador = 0;
    $('#descargarArchivo').click(function () {
        if (miCliente) {
            $('#modalDescarga').modal('show');
            console.log(nombreArchivo);
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?descargarArchivo=true',
                type: 'post',
                dataType: 'json',
                data: { valor: nombreArchivo },
                success: function (data) {
                    ruta = data.dir;
                    $('#modalDescContainer').empty();
                    $('#divNoFile').empty();
                    var archivosEncontrados = data.archivos.length;
                    var archivosNoEncontrados = data.archivosNo.length;
                    contador = archivosEncontrados + archivosNoEncontrados;
                    if (data.archivos.length > 0) {
                        var buttonsHTML = '';
                        data.archivos.forEach(function (archivo) {
                            var extension = archivo.split('.').pop().toLowerCase();
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
                            var maxLength = 12;

                            var truncatedFileName = archivo.length > maxLength ? archivo.substr(0, maxLength) + '...' : archivo;
                            var buttonHTML = '<div class="col-md-4 col-sm-4">' +
                                '<button style="margin-right:50px" title="clic para descargar o eliminar" type="button" class="btn btn-default btn-sm btnsDD"' +
                                'onclick="DownloadOrDelete(\'' + archivo + '\')">' +
                                '<img src="' + iconSrc + '" style="width: 60px;height: 60px;">' +
                                '</button><br>' +
                                '<b title="' + archivo + '">' + truncatedFileName + '</b>' +
                                '</div>';
                            buttonsHTML += buttonHTML;
                        });
                        $('#modalDescContainer').html('<div class="row">' + buttonsHTML + '</div>');
                        if (archivos.length < 3) {
                            $('#modalDescContainer').addClass('justify-content-center');
                        } else {
                            $('#modalDescContainer').removeClass('justify-content-center');
                        }
                    }
                    if (data.archivosNo.length > 0) {
                        var archivosNoEncontradosHTML = '<ul class="list-unstyled"><b>Archivos no encontrados en el directorio:</b>';
                        data.archivosNo.forEach(function (archivoNoEncontrado) {
                            archivosNoEncontradosHTML += '<li><span class="text-danger">' + archivoNoEncontrado + '</span></li>';
                        });
                        archivosNoEncontradosHTML += '</ul>';
                        $('#divNoFile').append(archivosNoEncontradosHTML);
                    }
                }
            });
        } else {
            Swal.fire({
                title: 'Seleccione un nombre de Beneficiario/Usuario o CI/RUC',
                text: '',
                type: 'error',
            });
        }
    });


    $('#divNoFile').on('click', 'span.text-danger', function () {
        var archivoClic = $(this).text();
        console.log('Se hizo clic en el archivo:', archivoClic);
        DownloadOrDelete(archivoClic, true);
    });
</script>