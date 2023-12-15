  <!-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.18
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights
    reserved.
  </footer> -->

  <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <!-- jQuery UI 1.11.4 -->
  <!-- <script src="../../dist/js/jquery-ui.js"></script> -->
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
// $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="../../dist/js/pages/dashboard.js"></script> -->
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  <script src="../../dist/js/js_globales.js?<?php echo date('y') ?>"></script>
  <script src="../../dist/js/script_acordeon.js"></script>
  </body>

  </html>

  <!-- loader de esapera -->
  <div class="modal fade" id="myModal_espera" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-body text-center">
                  <img src="../../img/gif/loader4.1.gif" width="80%">
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="myModal_espera_progress" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-body text-center">
                  <img src="../../img/gif/loader4.1.gif" width="80%">
                    <div class="progress progress-striped active" style="height: 30px;">
                      <div style="margin-top: 10px;" id="Bar_espera_progress" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <span style="font-weight: 700;font-size: 14px;text-shadow: 1px 1px grey;" class="txt_progress text-black"></span>
                      </div>
                    </div>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="myModal_sri_error" role="dialog" data-keyboard="false" data-backdrop="static" style="z-index: 1600;">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-body">
                  <div class="row">
                      <div class="col-xs-2"><b>RUC Empresa</b> </div>
                      <div class="col-xs-10"><?php echo $_SESSION['INGRESO']['RUC']; ?></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-2"><b>Estado</b> </div>
                      <div class="col-xs-10" id="sri_estado"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-6"><b>Codigo de error</b> </div>
                      <div class="col-xs-6" id="sri_codigo"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-2"><b>Fecha</b></div>
                      <div class="col-xs-10" id="sri_fecha"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12"><b>Mensaje</b></div>
                      <div class="col-xs-12" id="sri_mensaje"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12"><b>Info Adicional</b></div>
                      <div class="col-xs-12" id="sri_adicional"></div>
                  </div>
              </div>
              <input type="hidden" id="txtclave" name="">

              <div class="modal-footer">
                  <!-- <a type="button" class="btn btn-primary" href="#" id="doc_xml">Descargar xml</button>         -->
                  <!-- <button type="button" class="btn btn-default" onclick="location.reload();">Cerrar</button> -->
                   <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>

  <!--   <div id="myModal_cliente" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cliente Nuevo</h4>
      </div>
      <div class="modal-body">
          <iframe  id="FCliente" width="100%" height="400px" marginheight="0" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 -->

  <!--inicia modal Guia de remision -->
  <!-- Modal cliente nuevo -->
  <div id="myModal_guia" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-md" style="width: 30%;">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Datos de guia de remision</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-sm-12">
                          <b class="col-sm-6 control-label" style="padding: 0px">Fecha de emision de guia</b>
                          <div class="col-sm-6" style="padding: 0px">
                              <input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus()">
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <b class="col-sm-6 control-label" style="padding: 0px">Guia de remision No.</b><br>
                          <div class="col-sm-9" style="padding: 0px">
                              <select class="form-control input-xs" id="DCSerieGR" name="DCSerieGR"
                                  onblur="DCSerieGR_LostFocus()">
                                  <option value="">No Existe</option>
                              </select>
                          </div>
                          <div class="col-sm-3" style="padding: 0px">
                              <input type="text" name="LblGuiaR" id="LblGuiaR" class="form-control input-xs"
                                  value="000000">
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <b>AUTORIZACION GUIA DE REMISION</b>
                          <input type="text" name="LblAutGuiaRem" id="LblAutGuiaRem" class="form-control input-xs"
                              value="0">
                      </div>
                      <div class="col-sm-12">
                          <b class="col-sm-6 control-label" style="padding: 0px">Iniciacion del traslados</b>
                          <div class="col-sm-6" style="padding: 0px">
                              <input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>">
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <b class="col-sm-3 control-label" style="padding: 0px">Ciudad</b>
                          <div class="col-sm-9" style="padding: 0px">
                              <select class="form-control input-xs" id="DCCiudadI" name="DCCiudadI" style="width:100%">
                                  <option value=""></option>
                              </select>
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <b class="col-sm-6 control-label" style="padding: 0px">Finalizacion del traslados</b>
                          <div class="col-sm-6" style="padding: 0px">
                              <input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>">
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <b class="col-sm-3 control-label" style="padding: 0px">ciudad</b>
                          <div class="col-sm-9" style="padding: 0px">
                              <select class="form-control input-xs" id="DCCiudadF" name="DCCiudadF" style="width:100%">
                                  <option value=""></option>
                              </select>
                          </div>
                      </div>
                      <div class="col-sm-12">
                          <b>Nombre o razon socila (Transportista)</b>
                          <select class="form-control input-xs" id="DCRazonSocial" name="DCRazonSocial" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-12">
                          <b>Empresa de Transporte</b>
                          <select class="form-control input-xs" id="DCEmpresaEntrega" name="DCEmpresaEntrega" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-4">
                          <b>Placa</b>
                          <input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control input-xs"
                              value="XXX-999">
                      </div>
                      <div class="col-sm-4">
                          <b>Pedido</b>
                          <input type="text" name="TxtPedido" id="TxtPedido" class="form-control input-xs">
                      </div>
                      <div class="col-sm-4">
                          <b>Zona</b>
                          <input type="text" name="TxtZona" id="TxtZona" class="form-control input-xs">
                      </div>
                      <div class="col-sm-12">
                          <b>Lugar entrega</b>
                          <input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control input-xs">
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-primary" onclick="Command8_Click();">Aceptar</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>

      </div>
  </div>
  <!--fin modal Guia de remision -->


  <div id="myModalInfoError" class="modal fade" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">FORMULARIO DE INFORME DE ERRORES</h4>
              </div>
              <div class="modal-body">
                  <iframe id="FInfoErrorFrame" width="100%" height="400px" marginheight="0" frameborder="0"></iframe>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>

      </div>
  </div>

  <div class="modal fade" id="clave_contador" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="titulo_clave">Modal title</h5>

              </div>
              <div class="modal-body text-center">
                  <div class="row">
                      <div class="col-sm-7">
                          <input type="hidden" name="TipoSuper" id="TipoSuper"><br>
                          <input type="hidden" name="intentos" id="intentos" value="1">
                          <input type="password" name="txt_IngClave" id="txt_IngClave" class="form-control input-sm"
                              placeholder="Clave" onfocusout="IngresoClave()" onkeypress="enter(event)"
                              autocomplete="new-password">
                      </div>
                      <div class="col-sm-3">
                          <div class="btn-group">
                              <!-- <button class="btn btn-default btn-sm">Aceptar</button> -->
                              <button class="btn btn-default" data-dismiss="modal" onclick="limpiar_IngresoClave();">
                                  <img src="../../img/png/bloqueo.png"><br> Cancelar</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="clave_supervisor" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="titulo_clave">Ingrese clave de supervisor</h5>

              </div>
              <div class="modal-body text-center">
                  <div class="row">
                      <div class="col-sm-7">
                          <input type="hidden" name="BuscarEn" id="BuscarEn" value="MYSQL">
                          <input type="hidden" name="TipoSuper_MYSQL" id="TipoSuper_MYSQL"><br>
                          <input type="hidden" name="intentos_MYSQL" id="intentos_MYSQL" value="1">
                          <input type="password" name="txt_IngClave_MYSQL" id="txt_IngClave_MYSQL"
                              class="form-control input-sm" placeholder="Clave" onfocusout="IngresoClave_MYSQL()"
                              onkeypress="enter_MYSQL(event)" autocomplete="new-password">
                      </div>
                      <div class="col-sm-3">
                          <div class="btn-group">
                              <!-- <button class="btn btn-default btn-sm">Aceptar</button> -->
                              <button class="btn btn-default" data-dismiss="modal"
                                  onclick="limpiar_IngresoClave_MYSQL();"> <img src="../../img/png/bloqueo.png"><br>
                                  Cancelar</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>


  <div id="myModal" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cliente Nuevo</h4>
            </div>
            <div class="modal-body">
                <iframe id="FCliente" width="100%" height="400px" marginheight="0" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>
  <div id="myModal_provedor" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Proveedor Nuevo</h4>
            </div>
            <div class="modal-body" id="contenido_prov" style="background: antiquewhite;">
                <iframe id="FProveedor" width="100%" height="390px" marginheight="0" frameborder="0" src="../vista/modales.php?FProveedores=true"></iframe>
            </div>
           <!--  <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div> -->
        </div>
    </div>
  </div>

  <div id="myModal_notificar" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Notificacion</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
              <input type="hidden" name="txt_id_noti" id="txt_id_noti">
                <div id="txt_mensaje"></div>
                <hr>
                <b>Responder</b>
                <textarea class="form-control" id="txt_respuesta" name="txt_respuesta">.</textarea>
                <div class="text-right">
                  <button type="button" class="btn btn-primary" onclick="cambiar_estado()">Responder</button>
                </div>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="solucionado()">Marcar como solucionando</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){ 
        window.addEventListener("message", function(event) {
            if (event.data === "closeModal") {
                $('#myModal_provedor').modal('hide');
            }
        });         
    })
function datos_cliente() {
    var frame = document.getElementById('FCliente');
    var ruc = frame.contentWindow.document.getElementById('ruc').value;
    var codigocliente = frame.contentWindow.document.getElementById('codigoc').value;
    var email = frame.contentWindow.document.getElementById('email').value;
    var nombre = frame.contentWindow.document.getElementById('nombrec').value;
    var grupo = frame.contentWindow.document.getElementById('grupo').value;
    var T = frame.contentWindow.document.getElementById('TD').value;
    // crear esta funcion donde se desee agregar estos datos de cliente
    usar_cliente(nombre, ruc, codigocliente, email, T,grupo);
}



function enter(e) {
    if (e.which == 13) {
        IngresoClave()
    }
}

function IngresoClave() {
    var p = $('#txt_IngClave').val();
    if (p == '') {
        return false;
    }

    var parametros = {
        'tipo': $('#TipoSuper').val(),
        'intentos': $('#intentos').val(),
        'pass': $('#txt_IngClave').val(),
    }
    var opcion = '';
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/panel.php?IngClaves=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.respuesta == -1) {
                $('#intentos').val(response.intentos);
                Swal.fire(response.msj, '', 'info');
                resp_clave_ingreso(response);
            } else {
                //esta funcion debe estar definida en la paginandonde se este llamando
                resp_clave_ingreso(response);
                $('#clave_contador').modal('hide');
            }
        }
    });

}

function limpiar_IngresoClave() {
    $('#intentos').val('1');
    $('#txt_IngClave').val('');
}

function enter_MYSQL(e) {
    if (e.which == 13) {
        IngresoClave_MYSQL()
    }
}

function IngresoClave_MYSQL() {
    var p = $('#txt_IngClave_MYSQL').val();
    if (p == '') {
        return false;
    }

    var parametros = {
        'tipo': $('#TipoSuper_MYSQL').val(),
        'intentos': $('#intentos_MYSQL').val(),
        'pass': $('#txt_IngClave_MYSQL').val(),
        'buscaren': $('#BuscarEn').val(),
    }
    var opcion = '';
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/panel.php?IngClaves_MYSQL=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            limpiar_IngresoClave_MYSQL();
            if (response.respuesta == -1) {
                $('#intentos_MYSQL').val(response.intentos);
                Swal.fire(response.msj, '', 'info');
                resp_clave_ingreso(response);
            } else {
                //esta funcion debe estar definida en la paginandonde se este llamando
                resp_clave_ingreso(response);
                $('#clave_contador').modal('hide');
            }
        }
    });

}

function limpiar_IngresoClave_MYSQL() {
    $('#intentos_MYSQL').val('1');
    $('#txt_IngClave_MYSQL').val('');
}
  </script>

<?php
/*
if(isset($_SESSION['INGRESO']['Mail']) && isset($_SESSION['INGRESO']['Clave']) && isset($_SESSION['INGRESO']['noempr']) && isset($_SESSION['INGRESO']['empresa']) && !empty($_SESSION['INGRESO']['noempr']) && !empty($_SESSION['INGRESO']['empresa'])){
  //INICIO VALIDAMOS SI EL USUARIO TIENE PERMISO DE ACCESO AL SISTEMA
    $resp =  validacionAcceso($_SESSION['INGRESO']['noempr'], $_SESSION['INGRESO']['Mail'], $_SESSION['INGRESO']['Clave']);
    if($resp['rps'])
    {
      if (isset($resp["mensaje"]) && $resp["mensaje"]!="" && $_SESSION['INGRESO']['msjMora']) {
        $_SESSION['INGRESO']['msjMora'] = false; //indica que ya se mostro el msj en esta sesion
        echo '<script>$(document).ready(function(){ 

          Swal.fire({
            type: "warning",
            html: `<div style="width: 100%; color:black;font-weight: 400;">
            '.$resp['mensaje'].'</div>`
            })
        })
        </script>';
      }
    }else if(!isset($_SESSION['INGRESO']['modulo_']) || empty($_SESSION['INGRESO']['modulo_'])){
      echo '
          <script>$(document).ready(function(){ 
            Swal.fire({
              type: "warning",
              title: "Acceso no permitido",
              html: `<div style="width: 100%; color:black;font-weight: 400;">'.$resp['mensaje'].'</div>`
            }).then(() => {
              logout()
            });
          })
        </script>';
    }
  //FIN VALIDAMOS SI EL USUARIO TIENE PERMISO DE ACCESO AL SISTEMA
}
*/
?>