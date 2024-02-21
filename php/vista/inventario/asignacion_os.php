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
        padding-left: 1vw;
        padding-right: 1vw;
    }
</style>
<div class="row" style="padding: 1vw 1vw 0 1vw; background-color: rgb(254,252,172);">
    <form id="form_asignacion">
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12 form-group alineado">
                        <label for="fechaA">
                            Fecha de Atención
                        </label>
                        <input type="datetime-local" name="fechaA" id="fechaA" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-12 form-group alineado">
                        <label for="frecuencia">
                            Frecuencia
                        </label>
                        <select name="frecuencia" id="frecuencia" class="form-control">
                            <option value="sema">Semanal</option>
                            <option value="mens">Mensual</option>
                            <option value="quin">Quincenal</option>
                            <option value="ocas">Ocasional</option>
                            <!-- TODO:¿Hay que añadir mas o solo esas?-->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="row">
                    <div class="col-sm-12 form-group alineado">
                        <label for="estado">
                            ESTADO
                        </label>
                        <select name="estado" id="estado" class="form-control">
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
                    <div class="col-sm-12 form-group alineado">
                        <label for="tipoEntrega">
                            Tipo de Entrega
                        </label>
                        <select name="tipoEntrega" id="tipoEntrega" class="form-control">
                            <option value="Regu">Regular</option>
                            <option value="Espo">Esporádica</option>
                            <!-- TODO:¿Hay que añadir mas o solo esas?-->
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12 form-group alineado">
                        <label for="beneficiario">
                            Beneficiario/USUARIO:
                        </label>
                        <select name="beneficiario" id="beneficiario" class="form-control">
                            <option value=".">Seleccione un Beneficiario</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12 form-group alineado">
                        <label for="CantGlobSugDist" style="text-align: center; ">
                            Cantidad global sugerida a distribuir
                        </label>
                        <input type="number" name="CantGlobSugDist" id="CantGlobSugDist" value="200" readonly style=""
                            class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12 form-group alineado">
                        <label for="CantGlobDist" style="text-align: center;">
                            Cantidad global a distribuir
                        </label>
                        <input type="number" name="CantGlobDist" id="CantGlobDist" value="200" style=""
                            class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="tipoBenef" style="flex-basis: 50%;">
                                Tipo de Beneficiario:
                            </label>
                            <input type="text" name="tipoBenef" id="tipoBenef" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="totalPersAten" style="flex-basis: 50%;">
                                Total, Personas Atendidas:
                            </label>
                            <input type="text" name="totalPersAten" id="totalPersAten" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tipoPobl" style="flex-basis: 50%;">
                                Tipo de Población:
                            </label>
                            <input type="text" name="tipoPobl" id="tipoPobl" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="acciSoci" style="flex-basis: 50%;">
                                Acción Social:
                            </label>
                            <input type="text" name="acciSoci" id="acciSoci" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="vuln" style="flex-basis: 50%;">
                                Vulnerabalidad:
                            </label>
                            <input type="text" name="vuln" id="vuln" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tipoAten" style="flex-basis: 50%;">
                                Tipo de Atención:
                            </label>
                            <input type="text" name="tipoAten" id="tipoAten" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <label for="infoNutr">
                    Información Nutricional
                </label>
                <textarea name="infoNutr" id="infoNutr" rows="3" class="form-control">

                </textarea>
            </div>
            <div class="col-sm-4">
                <label for="comeGeneAsig">
                    Comentario General de Asignación
                </label>
                <div class="input-group">
                    <textarea name="comeGeneAsig" id="comeGeneAsig" rows="3" class="form-control"
                        placeholder="comentario general de clasificación...">
                </textarea>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-sm" onclick=""><i
                                class="fa fa-save"></i></button>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6 form-group" style="padding-left: 1vw;">
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
                    <div class="col-sm-6 form-group">
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
            <button type="button" class="btn btn-primary btn-sm"><b>Limpiar</b></button>
            <button type="button" class="btn btn-primary btn-sm" onclick=""><b>Agregar</b></button>
        </div>
        <hr>
    </form>
</div>

<div id="tbl-container">

</div>