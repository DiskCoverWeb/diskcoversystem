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

    @media(max-width: 768px){
        #container-estado{
            padding: 0vw 3vw 0vw 3vw !important; 
        }

        .centrar{
            text-align: center;
        }
    }
</style>
<form id="form_asignacion">
    <div class="row" style="padding: 1vw; background-color: rgb(254,252,172); border: 1px solid;">
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="diaEntr">
                            Día de Entrega
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <select name="diaEntr" id="diaEntr">
                            <option value="LUN">LUNES</option>
                            <option value="MAR">MARTES</option>
                            <option value="MIE">MIERCOLES</option>
                            <option value="JUE">JUEVES</option>
                            <option value="VIE">VIERNES</option>
                            <option value="SAB">SABADO</option>
                            <option value="DOM">DOMINGO</option>
                        </select>
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
                        <select name="beneficiario" id="beneficiario">
                            <option value=".">Beneficiario</option>
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
                        <input type="date" name="fechAten" id="fechAten">
                    </div>
                </div>
            </div>
        </div>
        <div class="row alineado">
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="estado">
                            Estado
                        </label>
                    </div>
                    <div class="col-sm-6" style="padding: 0" id="container-estado">
                        <select name="estado" id="estado">
                            <option value="acti">Activa</option>
                            <option value="prev">Pre-Vinculación</option>
                            <option value="susp">Suspendida</option>
                            <option value="desv">Desvinculada</option>
                            <!-- TODO:¿Hay que añadir mas o solo esas?-->
                        </select>
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
                        <select name="tipoEntrega" id="tipoEntrega">
                            <option value="Regu">Regular</option>
                            <option value="Espo">Esporádica</option>
                            <!-- TODO:¿Hay que añadir mas o solo esas?-->
                        </select>
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
                        <input type="time" name="horaEntrega" id="horaEntrega">
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
                        <select name="frecuencia" id="frecuencia">
                            <option value="sema">Semanal</option>
                            <option value="mens">Mensual</option>
                            <option value="quin">Quincenal</option>
                            <option value="ocas">Ocasional</option>
                            <!-- TODO:¿Hay que añadir mas o solo esas?-->
                        </select>
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
                        <input type="text" name="tipoBenef" id="tipoBenef">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="totalPersAten" style="font-size: 13px;">
                            Total, Personas Atendidas:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="totalPersAten" id="totalPersAten">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoPobl" style="flex-basis: 50%;">
                            Tipo de Población:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="tipoPobl" id="tipoPobl">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="acciSoci" style="flex-basis: 50%;">
                            Acción Social:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="acciSoci" id="acciSoci">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="vuln" style="flex-basis: 50%;">
                            Vulnerabalidad:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="vuln" id="vuln">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="tipoAten" style="flex-basis: 50%;">
                            Tipo de Atención:
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="tipoAten" id="tipoAten">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label for="CantGlobSugDist">
                            Cantidad global sugerida a distribuir
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="CantGlobSugDist" id="CantGlobSugDist" value="200" readonly style="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label for="CantGlobDist" style="text-align: center;">
                            Cantidad global a distribuir
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="CantGlobDist" id="CantGlobDist" value="200" style="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-right centrar">
                        <label for="infoNutr">
                            Información Nutricional
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <textarea name="infoNutr" id="infoNutr" rows="3" class="form-control">
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
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6 form-group" style="padding-left: 1vw; padding-top:1.6vh;">
                    <button type="button" class="btn btn-default" onclick=""><img
                            src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
                    <input type="text" name="grupProd" id="grupProd" placeholder="" style="width: 47%; max-width:47%;"
                        class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="stock">
                        Stock
                    </label>
                    <input type="text" name="stock" id="stock" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6 form-group" style="padding-top:1.6vh;">
                    <button type="button" style="width: initial;" class="btn btn-default" onclick="show_cantidad()"
                        id="btn_cantidad">
                        <img src="../../img/png/kilo.png" />
                        <br>
                        <b>Cantidad</b>
                    </button>
                    <input type="number" name="cant" id="cant" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="comeAsig">
                        Comentario de Asignación
                    </label>
                    <input type="text" name="comeAsig" id="comeAsig" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="text-align: right; padding-right: 1.5vw;">
        <button type="button" class="btn btn-primary btn-sm"><b>Agregar</b></button>
        <button type="button" class="btn btn-primary btn-sm" onclick=""><b>Limpiar</b></button>
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
</div>