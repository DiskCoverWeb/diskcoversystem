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
<script src="../../dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
  <script src="../../dist/js/js_globales.js"></script>
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
<div class="modal fade" id="myModal_sri_error" role="dialog" data-keyboard="false" data-backdrop="static">
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
        <button type="button" class="btn btn-default" onclick="location.reload();">Cerrar</button>
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


<div id="myModal" class="modal fade" role="dialog">
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
        <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button>
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
              <input type="password" name="txt_IngClave" id="txt_IngClave" class="form-control input-sm" placeholder="Clave" onfocusout="IngresoClave()" onkeypress="enter(event)"  autocomplete="new-password">
            </div>
            <div class="col-sm-3">
              <div class="btn-group">
                <!-- <button class="btn btn-default btn-sm">Aceptar</button> --> 
                <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_IngresoClave();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>     
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
              <input type="hidden" name="TipoSuper_MYSQL" id="TipoSuper_MYSQL"><br>
              <input type="hidden" name="intentos_MYSQL" id="intentos_MYSQL" value="1">
              <input type="password" name="txt_IngClave_MYSQL" id="txt_IngClave_MYSQL" class="form-control input-sm" placeholder="Clave" onfocusout="IngresoClave_MYSQL()" onkeypress="enter_MYSQL(event)"  autocomplete="new-password">
            </div>
            <div class="col-sm-3">
              <div class="btn-group">
                <!-- <button class="btn btn-default btn-sm">Aceptar</button> --> 
                <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_IngresoClave_MYSQL();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>     
              </div>              
            </div>            
          </div>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
  function datos_cliente()
  {
    
    var frame = document.getElementById('FCliente');
    var id = frame.contentWindow.document.getElementById('txt_id').value; 
    if(id=='')
    {
      Swal.fire('Seleccione un cliente valido','','info');
      return false;
    } 
    var ruc = frame.contentWindow.document.getElementById('ruc').value;
    var codigocliente = frame.contentWindow.document.getElementById('codigoc').value;  
    var email  = frame.contentWindow.document.getElementById('email').value;
    var nombre  = frame.contentWindow.document.getElementById('nombrec').value;
    var T  = 'N';
     // crear esta funcion donde se desee agregar estos datos de cliente
     usar_cliente(nombre,ruc,codigocliente,email,T);
  }



    function enter(e)
    {       
        if(e.which == 13) {
         IngresoClave()
        }
    }

    function IngresoClave()
    {
      var p = $('#txt_IngClave').val();
      if(p=='')
      {
        return false;
      }

      var parametros = 
      {
        'tipo':$('#TipoSuper').val(),
        'intentos':$('#intentos').val(),
        'pass':$('#txt_IngClave').val(),
      }
      var opcion = '';
      $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/panel.php?IngClaves=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
         console.log(response);
         if(response.respuesta==-1)
         {
           $('#intentos').val(response.intentos);
           Swal.fire(response.msj,'','info');           
           resp_clave_ingreso(response);
         }else
         {
          //esta funcion debe estar definida en la paginandonde se este llamando
           resp_clave_ingreso(response);
           $('#clave_contador').modal('hide');
         }
      }
    }); 

    }

    function limpiar_IngresoClave()
    {
      $('#intentos').val('1');
      $('#txt_IngClave').val('');
    }

    function enter_MYSQL(e)
    {       
        if(e.which == 13) {
          IngresoClave_MYSQL()
        }
    }

    function IngresoClave_MYSQL()
    {
      var p = $('#txt_IngClave_MYSQL').val();
      if(p=='')
      {
        return false;
      }

      var parametros = 
      {
        'tipo':$('#TipoSuper_MYSQL').val(),
        'intentos':$('#intentos_MYSQL').val(),
        'pass':$('#txt_IngClave_MYSQL').val(),
      }
      var opcion = '';
      $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/panel.php?IngClaves_MYSQL=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
         // console.log(response);
         limpiar_IngresoClave_MYSQL();
         if(response.respuesta==-1)
         {
           $('#intentos_MYSQL').val(response.intentos);
           Swal.fire(response.msj,'','info');           
           resp_clave_ingreso(response);
         }else
         {
          //esta funcion debe estar definida en la paginandonde se este llamando
           resp_clave_ingreso(response);
           $('#clave_contador').modal('hide');
         }
      }
    }); 

    }

    function limpiar_IngresoClave_MYSQL()
    {
      $('#intentos_MYSQL').val('1');
      $('#txt_IngClave_MYSQL').val('');
    }


</script>