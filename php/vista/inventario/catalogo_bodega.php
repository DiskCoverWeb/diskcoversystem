<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Metadatos y configuraciones iniciales -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FORMULARIOS</title>

    <style>
        @media (max-width: 600px) {
            .responsive-table thead {
                display: none;
            }

            .responsive-table tbody td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }

            .responsive-table tbody td::before {
                content: attr(data-label);
                font-weight: bold;
                display: inline-block;
                width: 50%;
            }

            .text-center {
                text-align: left;
            }

            .btn-container {
                text-align: right;
            }
        }
    </style>
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
                                    <a href="" id="btnAgregarCollapse" onclick="event.preventDefault();">Asignar
                                        Categoría</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones visibles en pantallas grandes -->
            <div class="hidden-xs">

                <div class="row">
                    <div class="col-sm-12">
                        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                        print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                            <img src="../../img/png/salire.png">
                        </a>
                        <?php
                        function createButton($title, $imagePath, $onclickFunction, $id)
                        {
                            echo '
                            <button type="button" class="btn btn-default" data-toggle="tooltip" id="' . $id . '" title="' . $title . '" onclick="' . $onclickFunction . '">
                                <img src="' . $imagePath . '" alt="' . $title . '">
                            </button>';
                        }
                        ?>
                        <?php createButton("Asignar Categoría", "../../img/png/add_articulo.png", "", "btnAgregar"); ?>
                        <!-- otros botones -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario y Tabla -->
    <div class="panel panel-primary" style="margin-top:20px">
        <div class="form-inline" style="margin:20px">

            <!-- Mensaje de alerta si no hay datos -->
            <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                No se encontraron datos que mostrar.
            </div>

            <!-- Tabla para mostrar datos -->
            <table class="table responsive-table table-bordered table-striped table-hover" style="margin-top: 20px;"
                id="idTabla" type="hidden"></table>
        </div>
    </div>

    <!-- Ventana Modal para Editar -->
    <div class="modal" id="modalEditar">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Encabezado de la ventana modal para editar -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Categoría</h4>
                </div>
                <!-- Contenido de la ventana modal para editar -->
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="idE" maxlength="10" style="display: none;">

                        <label for="tipoE">Tipo de dato:</label>
                        <input type="text" class="form-control" id="tipoE" maxlength="10" readonly>                        

                        <label for="procesoE">Detalle:</label>
                        <input type="text" class="form-control" id="procesoE" maxlength="60">

                        <label for="cmdsE">Tipo Detalle:</label>
                        <input type="text" class="form-control" id="cmdsE" maxlength="4">
                    </div>
                </div>
                <!-- Pie de la ventana modal para editar -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarEditar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana Modal para Agregar -->
    <div class="modal" id="modalAgregar">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Encabezado de la ventana modal para agregar -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar <strong><span id="selectedOptionLabel"></strong> </span> </h4>
                </div>
                <!-- Contenido de la ventana modal para agregar -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tipoA">Tipo:</label>
                        <input type="text" class="form-control" id="tipoA" maxlength="4" readonly>

                        <label for="procesoA">Detalle:</label>
                        <input type="text" class="form-control" id="procesoA" maxlength="60">

                        <label for="cmdsA">Tipo Detalle:</label>
                        <input type="text" class="form-control" id="cmdsA" maxlength="4">
                    </div>
                </div>
                <!-- Pie de la ventana modal para agregar -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarAgregar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript para manipular la página -->
    <script src="../../dist/js/inventario/catalogo_bodega.js"></script>
</body>

</html>