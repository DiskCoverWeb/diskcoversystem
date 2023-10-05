<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <title>FORMULARIOS</title>
</head>

<body>

    <div class="row">
        <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">

            <!-- Menú colapsable para pantallas pequeñas -->
            <div class="panel-group hidden-lg hidden-md hidden-sm" id="collapsedMenu">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapseMenu" aria-expanded="false"
                                aria-controls="collapseMenu">
                                <span class="glyphicon glyphicon-menu-hamburger"></span>
                            </a>
                        </h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapseMenu">
                        <div class="panel-body">
                            <ul class="nav">
                                <li>
                                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']); ?>">
                                        Salir de módulo
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="" id="btnAgregarCollapse" onclick="event.preventDefault();">Ingresar nuevo</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones visibles en pantallas grandes -->
            <div class="hidden-xs">

                <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                    print_r($ruta[0] . '#'); ?>" title="Salir de módulo" class="btn btn-default">
                        <img src="../../img/png/salire.png" alt="Salir">
                    </a>
                </div>

                <?php
                function createButton($title, $imagePath, $onclickFunction, $id)
                {
                    echo '<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                            <button type="button" class="btn btn-default" id="' . $id . '" title="' . $title . '" onclick="' . $onclickFunction . '">
                                <img src="' . $imagePath . '" alt="' . $title . '">
                            </button>
                        </div>';
                }
                ?>

                <?php createButton("Ingresar nuevo", "../../img/png/add_articulo.png", "", "btnAgregar"); ?>
                <!-- otros botones -->
            </div>
        </div>
    </div>


    <div class="panel panel-primary" style="margin-top:20px">
        <div class="form-inline" style="margin:20px">
            <div class="">
                <label for="selectOption">Selecciona una categoría:</label>
                <select class="form-control " id="selectOption" name="selectOption">
                    <option value="INDIC_NUT">Indicador Nutricional</option>
                    <option value="CATEG_BPM">BPM Alergenos</option>
                    <option value="CATEG_BPMT">BPM Temperatura</option>
                </select>
                <!--a href="#" class="btn btn-info btn-md" id="btnBuscar">
                    <span class="glyphicon glyphicon-search"></span> Buscar
                </a-->
            </div>

            <!--div class="text-right">
                <a href="#" class="btn btn-info btn-md" id="btnAgregar">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Nuevo
                </a>
            </div-->

            <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                No se encontraron datos que mostrar.
            </div>

            <table class="table table-responsive table-bordered table-striped table-hover" style="margin-top: 20px;"
                id="idTabla" type="hidden">
            </table>
        </div>
    </div>

    <div class="modal" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar <strong><span id="selectedOptionLabelE"></strong> </h4>
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
                    <h4 class="modal-title">Agregar <strong><span id="selectedOptionLabel"></strong> </span> </h4>
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

    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->
    <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script-->
    <!--script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script-->
    <script src="../../dist/js/inventario/categorias.js"></script>
</body>

</html>