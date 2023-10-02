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
        <form class="form-inline" style="margin-top: 20px;">
            <div class="form-group">
                <label for="selectOption">Selecciona una opción:</label>
                <select class="form-control" id="selectOption" name="selectOption">
                    <option value="CAT_GFN">Categoría GFN</option>
                    <option value="CATEG_BPM">Categoría BPM_ALERGENOS</option>
                    <option value="CATEG_BPMT">Categoría BPM TEMPERATURA</option>
                </select>
            </div>
            <button type="button" class="btn btn-info btn-sm" onclick="Buscar()">BUSCAR</button>
        </form>

        <table class="table table-responsive table-bordered table-striped" style="margin-top: 20px;" id="idTabla">
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="daya.js"></script>
</body>

</html>
