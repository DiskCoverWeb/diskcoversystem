<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <div class="row">
        <div class="col-sm-8">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <span class="glyphicon glyphicon-folder-open text-success"></span> Pay by Credit Card
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">first</div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                <span class="glyphicon glyphicon-folder-open text-info"></span> Pay by PayPal
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">Pay Pal</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-sm-12">
                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                    print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                        <img src="../../img/png/salire.png">
                    </a>
                    <button class="btn btn-default" data-toggle="tooltip" title="Grabar" id="btnGuardar">
                        <img src="../../img/png/grabar.png">
                    </button>
                </div>
            </div>
            <form style="margin-top:20px" id="miFormulario">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="codigoP">Código del producto</label>
                        <input type="text" class="form-control" id="codigoP"
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
                        <input class="form-check-input" type="radio" name="cbxProdc" id="cbxDet" value='D'
                            checked>
                        <label class="form-check-label" for="cbxDet">
                            Detalle
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript para manipular la página -->
    <script src="../../dist/js/inventario/catalogo_bodega.js"></script>
</body>




</html>