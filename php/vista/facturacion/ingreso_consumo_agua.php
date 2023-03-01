<!-- INICIO MODULO INGRESO CONSUMO DE AGUA -->
<style type="text/css">
  .check-group-xs{
    padding: 3px 6px !important;
    font-size: 5px !important;
  }
  .padding-3{
    padding: 3px !important;
  }

  .padding-l-5{
    padding-left: 5px !important;
  }

  #swal2-content{
    font-weight: 500;
    font-size: 1.3em;
  }
  .text-left{
    text-align: left !important;
  }
  .text-center{
    text-align: center !important;
  }
  .strong {
    font-weight: bold;
  }
</style>
  <div class="col">
    <a href="#" title="Ingresar Consumo de Agua"  class="btn btn-default" onclick="OpenModalIngresoConsumoAgua()">
      <img src="../../img/png/pipe_water.png" width="25" height="30">
    </a>
  </div>
</div>
<div id="myModalIngresoConsumoAgua" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ingreso de Consumo de Agua</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal"  id="FIngresoConsumoAgua" name="FIngresoConsumoAgua">
          <fieldset>
            <label>Digite Código del Usuario</label>
            <div class="form-group">
              <label for="ICAcodigoCliente" class="col-xs-2 control-label">Código</label>
              <div class="col-xs-10">
                <input type="text" class="form-control input-xs " name="ICAcodigoCliente" id="ICAcodigoCliente" placeholder="0" style="max-width: 150px;display: inline-block;">
              <label class="text-red">Con servicio y medidor</label>
              </div>
            </div>
            <div class="form-group">
              <label for="NameUsuario" class="col-xs-2 control-label">Usuario</label>
              <div class="col-xs-10">
                <input type="text" class="form-control input-xs " name="NameUsuario" id="NameUsuario" readonly>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <label for="textArea" class="col-xs-12 control-label text-red text-center">Ingresado lectura hasta Octubre/2022 ==>(7440)</label>
            </div>
            <div class="form-group">
              <label for="textArea" class="col-xs-2 control-label">Año</label>
              <div class="col-xs-4">
                <input type="text" class="form-control input-xs " name="Usuario" id="Usuario" readonly>
              </div>
              <label for="textArea" class="col-xs-2 control-label">Mes</label>
              <div class="col-xs-4">
                <input type="text" class="form-control input-xs " name="Usuario" id="Usuario" readonly>
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-7">
                <div class="radio">
                  <label>
                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                    Menos de 10.000 metros cúbicos.
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                    Más de 10.000 metros cúbicos.
                  </label>
                </div>
              </div>
              <div class="col-lg-5  text-right">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="optionsRadios" id="optionsRadios2" value="option2">
                    Medidor vuelve a 0.
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group" style=" margin-bottom: 2px;">
              <label for="inputPassword" class="col-xs-12 col-lg-6 control-label text-red text-left">Ingrese lectura de Noviembre/2022</label>
              <div class="col-xs-12 col-lg-6  strong text-right" >
                <input  style="max-width: 120px;display: inline-block;" type="text" class="form-control input-xs " name="Usuario" id="Usuario"  readonly> m<sup>3</sup>
              </div>
            </div>
            <div class="form-group" style=" margin-bottom: 2px;">
              <label for="inputPassword" class="col-lg-12 control-label text-red text-left">Promedio de Consumo: 67</label>
            </div>
            <div class="form-group" style=" margin-bottom: 2px;">
              <label for="inputPassword" class="col-lg-12 control-label text-red text-left">Consumo: 20 (47 Bajo el Promedio)</label>
            </div>
            <div class="form-group">
              <label for="inputPassword" class="col-xs-3 control-label ">Multas:</label>
              <div class="col-xs-4">
                <input type="text" class="form-control input-xs " name="Usuario" id="Usuario" placeholder="0.00">
              </div>
            </div>
          </fieldset>
        </form>

      </div>
      <div class="modal-footer">
          
        <button class="btn btn-success" title="Guardar Consumo" onclick="GuardarConsumoAgua()">
          <img  src="../../img/png/grabar.png" width="25" height="30">
        </button>
        <button  class="btn btn-default" title="Cancelar Consumo" onclick="CancelarConsumoAgua()">
          <img src="../../img/png/cancel.png" width="25" height="30">
        </button>
        <button class="btn btn-warning" id="btnSalirModuloPF" title="Salir del Modulo" data-dismiss="modal">
          <img  src="../../img/png/salire.png" width="25" height="30">
        </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $("#ICAcodigoCliente").on("blur", function(){
      let CodigoCliente = $("#ICAcodigoCliente").val();
      if(CodigoCliente!=""){
        $.ajax({
          type: "POST",                 
          url: '../controlador/facturacion/facturar_pensionC.php?BuscarClienteCodigo='+CodigoCliente,
          dataType:'json', 
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          {
            $('#myModal_espera').modal('hide');  
            if(response.rps){console.log(response.data.data.cliente)
              $("#NameUsuario").val(response.data.data.cliente);
            }else{
              Swal.fire('¡Oops!', response.mensaje, 'warning')
            }        
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
      }
    })
  });
  
  function OpenModalIngresoConsumoAgua(){
      $('.myModalNuevoCliente').modal('hide');
      $('#myModalIngresoConsumoAgua').modal('show');
  }


  function GuardarConsumoAgua() {
    let hayproductosMarcados = false
    for (var i = 1; i <= cantidadProductoPreFacturar; i++) {
      console.log($("#PFcheckProducto"+i).prop('checked'));
      if($("#PFcheckProducto"+i).prop('checked')){
        hayproductosMarcados = true
        break
      }
    }

    if(hayproductosMarcados){
      $('#myModal_espera').modal('show');

      $.ajax({
          type: "POST",                 
          url: '../controlador/facturacion/facturar_pensionC.php?GuardarInsPreFacturas=true',
          data: $("#FIngresoConsumoAgua").serialize(),
          dataType:'json', 
          beforeSend: function () {   
              $('#myModal_espera').modal('show');
          },    
          success: function(response)
          {
            $('#myModal_espera').modal('hide');  
            if(response.rps){
              if($('#persona').val()!=""){
                ClientePreseleccion($('#persona').val());
              }
              
              Swal.fire('¡Bien!', response.mensaje, 'success')
              $('#myModalPreFactura').modal('hide');
            }else{
              Swal.fire('¡Oops!', response.mensaje, 'warning')
            }        
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
    }else{
      Swal.fire('¡Oops!', "No ha seleccionado ningun producto.", 'info')
    }
  }

  function CancelarConsumoAgua() {
    Swal.fire({
      title: '¿ESTA SEGURO DE ELIMINAR LA PREFACTURACION DE ESTE CLIENTE?',
      text: "¡NO PODRA REVERSAR ESTE PROCESO!",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
      if (result.value) {
        $('#myModal_espera').modal('show');

        $.ajax({
          type: "POST",                 
          url: '../controlador/facturacion/facturar_pensionC.php?EliminarInsPreFacturas=true',
          data: $("#FIngresoConsumoAgua").serialize(),
          dataType:'json', 
          beforeSend: function () {   
              $('#myModal_espera').modal('show');
          },    
          success: function(response)
          {
            $('#myModal_espera').modal('hide');  
            if(response.rps){
              if(response.mensaje_extra){
                Swal.fire(response.mensaje, response.mensaje_extra, 'success')
              }else{
                Swal.fire('¡Bien!', response.mensaje, 'success')
              }
              $('#myModalPreFactura').modal('hide');
            }else{
              Swal.fire('¡Oops!', response.mensaje, 'warning')
            }        
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });

      }
    })
  }
</script>
<!-- FIN MODULO INGRESO CONSUMO DE AGUA -->

