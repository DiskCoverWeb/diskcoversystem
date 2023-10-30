<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        .panel-body{
            margin-left: 15px;
        }
        .panel-body:hover {
            color: blue;
            cursor: pointer;            
        }

        .icono {
            margin-right: 5px;
        }

        #accordion {
        max-height: 400px; 
        overflow-y: auto; 
    }
    </style>
</head>

<body>
    <div class="row" style="margin:10px">
        <div class="col-sm-6 panel panel-info">
            <div class="panel-group" id="accordion"  style="margin-top:20px">>
                <!-- Los paneles del acordeón se llenarán aquí dinámicamente -->
            </div>

            <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                No se encontraron datos que mostrar.
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                    print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                        <img src="../../img/png/salire.png">
                    </a>
                    <button class="btn btn-default" data-toggle="tooltip" title="Grabar" id="btnGuardar">
                        <img src="../../img/png/grabar.png">
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" title="Eliminar" id="btnEliminar">
                        <img src="../../img/png/eliminar.png">
                    </button>
                </div>
            </div>
            <form style="margin-top:20px" id="miFormulario">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="codigoP">Código del producto</label>
                        <input type="text" class="form-control" maxlength="5" id="codigoP"
                            placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="txtConcepto">Concepto o detalle del producto</label>
                        <input type="text" class="form-control" id="txtConcepto">
                    </div>
                </div>
                <span style="margin-top:5px">Tipo de producto</span>
                <div class="row">
                    <div class="form-check col-sm-6">
                        <input class="form-check-input" type="radio" name="cbxProdc" id="cbxCat" value='C'>
                        <label class="form-check-label" for="cbxCat">
                            Categoría
                        </label>
                    </div>
                    <div class="form-check col-sm-6">
                        <input class="form-check-input" type="radio" name="cbxProdc" id="cbxDet" value='D' checked>
                        <label class="form-check-label" for="cbxDet">
                            Detalle
                        </label>
                    </div>
                </div>

                <div class="alert alert-light" id="alertUse" style="display: none; margin-top: 5px; padding: 2px;">
                    <span class="glyphicon glyphicon-exclamation-sign text-danger" aria-hidden="true"></span>
                </div>

            </form>

        </div>
    </div>

    <!-- Script JavaScript para manipular la página -->
    <script src="../../dist/js/inventario/catalogo_bodega.js"></script>
</body>




</html>