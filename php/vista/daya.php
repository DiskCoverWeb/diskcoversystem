<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>FORMULARIOS</title>
</head>

<body>
    <div class="container">
        <div class="form-inline" style="margin:20px">
            <div class="">
                <label for="selectOption">Selecciona una categoría:</label>
                <select class="form-control" id="selectOption" name="selectOption">
                    <option value="INDIC_NUT">Indicador Nutricional</option>
                    <option value="CATEG_BPM">BPM Alergenos</option>
                    <option value="CATEG_BPMT">BPM Temperatura</option>
                </select>
                <!--a href="#" class="btn btn-info btn-md" id="btnBuscar">
                    <span class="glyphicon glyphicon-search"></span> Buscar
                </a-->
            </div>

            <div class="text-right">
                <a href="#" class="btn btn-info btn-md" id="btnAgregar">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Nuevo
                </a>
            </div>

            <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                No se encontraron datos que mostrar.
            </div>

            <table class="table table-responsive table-bordered table-striped table-hover" style="margin-top: 20px;" id="idTabla"
                type="hidden">
            </table>
        </div>
    </div>

    <div class="modal" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar <span id="selectedOptionLabelE"> </span> </h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">                                                
                        <input type="text" class="form-control" id="idE" maxlength="10" style="display: none;">

                        <label for="codigoE">Tipo de dato:</label>
                        <input type="text" class="form-control" id="tipoE" maxlength="10" readonly>

                        <label for="codigoE">Código:</label>
                        <input type="text" class="form-control" id="codigoE" maxlength="10">

                        <label for="beneficiarioE">Beneficiario:</label>
                        <input type="text" class="form-control" id="beneficiarioE" maxlength="60">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarEditar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="modalAgregar">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar <span id="selectedOptionLabel"> </span> </h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="codigoA">Código:</label>
                        <input type="text" class="form-control" id="codigoA" maxlength="10">

                        <label for="beneficiarioA">Beneficiario:</label>
                        <input type="text" class="form-control" id="beneficiarioA" maxlength="60">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarAgregar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </div>
        </div>
    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="daya.js"></script>
</body>

</html>