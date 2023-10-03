<?php @session_start();
date_default_timezone_set('America/Guayaquil'); //print_r($_SESSION['INGRESO']);die();
$cartera_usu = '';
$cartera_pass = '';
if (isset($_SESSION['INGRESO']['CARTERA_USUARIO'])) {
    $cartera_usu = $_SESSION['INGRESO']['CARTERA_USUARIO'];
    $cartera_pass = $_SESSION['INGRESO']['CARTERA_PASS'];
}
$tipo = '';

if (isset($_GET['tipo']) && $_GET['tipo'] == 2) {
    $tipo = 2;
}

?>
<link rel="stylesheet" href="../../dist/css/customTable.css">
<script type="text/javascript">

    $(document).ready(function () {
    });

    function open_email() {
        $('#myModal_email').modal('show');
    }

</script>

<div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar Pdf" onclick=""><img
                    src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar Excel" onclick=""><img
                    src="../../img/png/table_excel.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Enviar Email" onclick="open_email()"><img
                    src="../../img/png/email.png"></button>
        </div>
    </div>
</div>
<div class="row" style="margin-right: 5px">
    <form id="filtros" class="form-row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-2">
                    <label style="padding: 0px">ESTADO</label>
                    <select class="form-control input-xs" id="ddl_grupo" name="ddl_grupo" onchange="">
                        <option value="P">Pendientes</option>
                        <option value="C">Canceladas</option>
                        <option value="A">Anuladas</option>
                    </select>
                    <!-- <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm"> -->
                </div>
                <div class="col-sm-4">
                    <label style="padding: 0px">CI / RUC</label>
                    <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente" onchange="">
                        <option value="">Seleccione Cliente</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <label style="padding: 0px">Serie</label>
                    <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1"
                        style="padding-left:8px">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label style="padding: 0px">Desde</label>
                    <input type="date" name="txt_desde" id="txt_desde" class="form-control input-xs"
                        value="<?php echo date('Y-m-d') ?>">
                </div>
                <div class="col-sm-2">
                    <label style="padding: 0px">Hasta</label>
                    <input type="date" name="txt_hasta" id="txt_hasta" class="form-control input-xs"
                        value="<?php echo date('Y-m-d') ?>">
                </div>
                <div class="col-sm-1">
                    <br>
                    <button class="btn btn-primary" type="button" onclick="validar()"><i class="fa fa-search"></i>
                        Buscar
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
<br>
<div class="row panel panel-default" style="margin-left: 1px; margin-right: 1px">
    <div class="col-sm-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="row">
                    <div class="col-sm-6">
                        <h2 style="margin-top: 10px;">Listado de Facturas</h2>
                    </div>
                    <div class="col-sm-12" style="overflow-x: scroll;height: 500px;">
                        <table class="resp" style=" white-space: nowrap;">
                            <thead id="cabecera">
                                <th scope="col">T</th>
                                <th scope="col">Razon_Social</th>
                                <th scope="col">TC</th>
                                <th scope="col">Serie</th>
                                <th scope="col">Autorizacion</th>
                                <th scope="col">Factura</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">SubTotal</th>
                                <th scope="col">Con_IVA</th>
                                <th scope="col">IVA</th>
                                <th scope="col">Descuento</th>
                                <th scope="col">Total</th>
                                <th scope="col">Saldo</th>
                                <th scope="col">RUC_CI</th>
                                <th scope="col">TB</th>
                            </thead>
                            <tbody id="tbl_tabla">
                                <tr>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                    <td>TEST</td>
                                </tr>
                                <tr>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                    <td>TEST2</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<div id="modal_email" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enviar Email</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <p>Su nueva clave se enviara al correo:</p>
                    <h5 id="lbl_email">El usuario no tien un Email registrado contacte con la institucion</h5>
                    <input type="hidden" name="txt_email" id="txt_email">
                    <!-- <form enctype="multipart/form-data" id="form_img" method="post"> -->

                    <!-- </form>   -->
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-block" id="btn_email" onclick=""> Enviar
                        Email</button>
                    <button type="button" class="btn btn-default btn-sm btn-block" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar email</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="emails-input" name="emails-input" placeholder="añadir email"></div>
                        <input type="hidden" name="txt_fac" id="txt_fac">
                        <input type="hidden" name="txt_serie" id="txt_serie">
                        <input type="hidden" name="txt_codigoc" id="txt_codigoc">
                        <input type="hidden" name="txt_to" id="txt_to">
                    </div>
                    <div class="col-sm-12">
                        <input type="" id="txt_titulo" name="txt_titulo" class="form-control form-control-sm"
                            placeholder="titulo de correo" value="comprobantes">
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="3" style="resize:none" placeholder="Texto" id="txt_texto"
                            name="txt_texto"></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Factura</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="">Enviar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal_bloque" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Respuesta autorizacion en bloque</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" id="bloque_resp" style="height:350px; overflow-y: scroll;">

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>





<!--<script src="../../dist/js/utils.js"></script>
<script src="../../dist/js/emails-input.js"></script>
<script src="../../dist/js/multiple_email.js"></script>-->