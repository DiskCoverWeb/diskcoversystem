<html>
<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    FECHA CREACION	: 16/02/2024
    FECHA MODIFICACION: --
    DESCIPCION :   Ventana Gestion Social Registro de Beneficiario
 -->

<head>
    <style>
        .card-header {
            background-color: #f3e5ab;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header h2 {
            font-weight: bold;
        }

        .card-body {
            background-color: #fffacd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-link {
            color: black;
        }
    </style>
</head>

<body>
    <div class="accordion" id="accordionExample" style="margin-left:30px; margin-right: 30px;">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        INFORMACION GENERAL
                    </button>
                </h2>
            </div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body" style="margin:1px; padding-top:5px; padding-bottom:5px;">
                    <div class="row" style="margin:10px; ">
                        <div class="col-sm-2">
                            <label for="ruc" style="display: block;">CI/RUC</label>
                            <input class="form-control input-xs" type="text" name="ruc" id="ruc" placeholder="0"
                                value=0>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/SRIlogo.png" width="80" height="50">
                        </div>

                        <div class="col-sm-3">
                            <label for="usuario" style="display: block;">Nombre del Beneficiario/Usuario</label>
                            <input class="form-control input-xs" type="text" name="usuario" id="usuario" placeholder="0"
                                value=0>
                        </div>
                        <div class="col-sm-4">
                            <label for="tipoBenef" style="display: block;">Tipo de Beneficiario</label>
                            <select class="form-control input-xs" name="tipoBenef" id="tipoBenef">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="estado" style="display: block;">Estado</label>
                            <select class="form-control input-xs" name="estado" id="estado">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                        <div class="col-sm-3">
                            <label for="nombreRepre" style="display: block;">NOMBRE REPRESENTANTE LEGAR</label>
                            <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                placeholder="Nombre Representante" value=0>
                        </div>
                        <div class="col-sm-3">
                            <label for="ciRepre" style="display: block;">CI REPRESENTANTE LEGAL</label>
                            <input class="form-control input-xs" type="text" name="ciRepre" id="ciRepre"
                                placeholder="CI Representante" value=0>
                        </div>
                        <div class="col-sm-3">
                            <label for="telfRepre" style="display: block;">TELEFONO REPRESENTANTE LEGAL</label>
                            <input class="form-control input-xs" type="text" name="telfRepre" id="telfRepre"
                                placeholder="xxxxxxxxxx" value=0>
                        </div>
                    </div>
                    <div class="row" style="margin:10px; ">
                        <div class="col-sm-2">
                            <label for="contacto" style="display: block;">CONTACTO/ENCARGADO</label>
                            <input class="form-control input-xs" type="text" name="contacto" id="contacto"
                                placeholder="xxxxxxxxxx" value=0>
                        </div>
                        <div class="col-sm-3">
                            <label for="cargo" style="display: block;">CARGO</label>
                            <select class="form-control input-xs" name="cargo" id="cargo">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/calendario2.png" width="60" height="60">
                        </div>
                        <div class="col-sm-3">
                            <label for="diaEntrega" style="display: block;">DIA ENTREGA A USUARIOS FINALES</label>
                            <select class="form-control input-xs" name="diaEntrega" id="diaEntrega">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/reloj.png" width="55" height="55">
                        </div>
                        <div class="col-sm-2">
                            <label for="horaEntrega" style="display: block;">HORA DE ENTREGA</label>
                            <select class="form-control input-xs" name="horaEntrega" id="horaEntrega">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                        <div class="col-sm-3">
                            <div class="row">
                                <label for="direccion" style="display: block;">DIRECCION</label>
                                <input class="form-control input-xs" type="text" name="direccion" id="direccion"
                                    placeholder="Direccion" value=0>
                            </div>
                            <div class="row">
                                <label for="email" style="display: block;">Email</label>
                                <input class="form-control input-xs" type="text" name="email" id="email"
                                    placeholder="Direccion" value=0>
                            </div>
                            <div class="row">
                                <label for="email2" style="display: block;">Email 2</label>
                                <input class="form-control input-xs" type="text" name="email2" id="email2"
                                    placeholder="Direccion" value=0>
                            </div>
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <div class="row">
                                <label for="referecia" style="display: block;">REFERENCIA</label>
                                <input class="form-control input-xs" type="text" name="referecia" id="referecia"
                                    placeholder="referencia" value=0>
                            </div>
                            <div class="row">
                                <label for="telefono" style="display: block;">TELEFONO</label>
                                <input class="form-control input-xs" type="text" name="telefono" id="telefono"
                                    placeholder="Telefono " value=0>
                            </div>
                            <div class="row">
                                <label for="telefono2" style="display: block;">TELEFONO 2</label>
                                <input class="form-control input-xs" type="text" name="telefono2" id="telefono2"
                                    placeholder="Telefono 2" value=0>
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
                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        INFORMACION ADICIONAL
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body" style="margin:1px; padding-top:5px; padding-bottom:5px;">
                    <div class="row" style="margin:10px; ">
                        <div class="col-sm-3">
                            <label for="tipoEntrega" style="display: block;">TIPO DE ENTREGA</label>
                            <select class="form-control input-xs" name="tipoEntrega" id="tipoEntrega">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/calendario2.png" width="60" height="60">
                        </div>
                        <div class="col-sm-2">
                            <label for="diaEntrega" style="display: block;">DIA DE ENTREGA</label>
                            <select class="form-control input-xs" name="diaEntrega" id="diaEntrega">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1" style="display: flex; justify-content: center;">
                            <img src="../../img/png/reloj.png" width="55" height="55">
                        </div>
                        <div class="col-sm-2">
                            <label for="horaEntrega" style="display: block;">HORA DE ENTREGA</label>
                            <select class="form-control input-xs" name="horaEntrega" id="horaEntrega">
                                <option value="">Seleccione</option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label for="frecuencia" style="display: block;">FRECUENCIA</label>
                            <select class="form-control input-xs" name="frecuencia" id="frecuencia">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin:10px; display: flex; justify-content: center;">
                        <div class="col-sm-2">
                            <label for="tipoEntrega" style="display: block;">TOTAL DE PERSONAS ASISTIDAS</label>
                            <select class="form-control input-xs" name="tipoEntrega" id="tipoEntrega">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="tipoPoblacion" style="display: block;">TIPO DE POBLACION</label>
                            <select class="form-control input-xs" name="tipoPoblacion" id="tipoPoblacion">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="accionSocial" style="display: block;">ACCION SOCIAL</label>
                            <select class="form-control input-xs" name="accionSocial" id="accionSocial">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="vulnerabilidad" style="display: block;">VULNERABILIDAD</label>
                            <select class="form-control input-xs" name="vulnerabilidad" id="vulnerabilidad">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="tipoAtencion" style="display: block;">TIPO DE ATENCION</label>
                            <select class="form-control input-xs" name="tipoAtencion" id="tipoAtencion">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin: 10px;">
                        <div class="col-sm-5"></div>
                        
                        <div class="col-sm-3">
                            <div class="row"  style="display: flex; justify-content: center;">
                            <img src="../../img/png/adjuntar-archivo.png" width="60" height="60">
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="archivoAdd">ARCHIVOS ADJUNTOS</label>
                                    <input type="file" class="form-control-file" id="archivoAdd">
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <label for="infoNut" style="display: block;">INFORMACION NUTRICIONAL</label>
                                <textarea class="form-control" id="infoNut" rows="4"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        var diasEntrega = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
        $.each(diasEntrega, function (index, dia) {
            $("#diaEntrega").append($("<option>", {
                value: dia,
                text: dia
            }));
        });

        var horasEntrega = ["9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM"];
        $.each(horasEntrega, function (index, hora) {
            $("#horaEntrega").append($("<option>", {
                value: hora,
                text: hora
            }));
        });
    });
</script>