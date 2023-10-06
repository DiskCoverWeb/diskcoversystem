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
        <form class="row" style="margin-top: 20px;">
            <div class="col-xs-12">
                <div class="col-xs-10 form-inline">
                    <label for="selectOption">Selecciona una opción:</label>
                    <select class="form-control" id="selectOption" name="selectOption">
                        <option value="CAT_GFN">Categoría GFN</option>
                        <option value="CATEG_BPM">Categoría BPM_ALERGENOS</option>
                        <option value="CATEG_BPMT">Categoría BPM TEMPERATURA</option>
                    </select>
                    <a href="#" class="btn btn-info btn-md" id="btnBuscar">
                        <span class="glyphicon glyphicon-search"></span> Buscar
                    </a>
                </div>

                <div class="col-xs-2 text-right">
                    <a href="#" class="btn btn-info btn-md" id="btnAgregar">
                        <span class="glyphicon glyphicon-plus"></span> Agregar Nuevo
                    </a>
                </div>
            </div>
        </form>


        <table class="table table-responsive table-bordered table-striped" style="margin-top: 20px;" id="idTabla">
        </table>
    </div>

    <div class="modal" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Fila</h4>

                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="ID">ID:</label>
                        <input type="text" class="form-control" id="ID">
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
                    <h4 class="modal-title">Agregar Categoría</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="ID">ID:</label>
                        <input type="text" class="form-control" id="ID">
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