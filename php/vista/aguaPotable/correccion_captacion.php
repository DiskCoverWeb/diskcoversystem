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
  .contenedor_item_center{
    display: flex;
      justify-content: center;
      gap: 10px;"
  }
.no-visible{
  visibility: hidden;
}
.full-width + .select2-container {
  width: 100% !important;
}

</style>
<div class="box box-info">
    <div class="box-header">
      <h4>Corrección de Captación</h4>
    </div>
    <div class="box-body">    
        <div class="row">
            <div class="col-lg-offset-2 col-lg-8">
                <form class="form-horizontal"  id="FIngresoConsumoAgua" name="FIngresoConsumoAgua">
                  <fieldset>
                    <label>Digite Código del Medidor</label>
                    <div class="form-group">
                      <label for="CMedidor" class="col-xs-2 control-label">Código Medidor</label>
                      <div class="col-xs-10">
                        <input  onkeydown="if (event.keyCode === 13) $('#CMedidor').blur()" type="text" class="form-control input-xs " name="CMedidor" id="CMedidor" placeholder="0" style="max-width: 150px;display: inline-block;" tabindex="1">
                        <input type="hidden"  name="codigoCliente" id="codigoCliente">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="NameUsuario" class="col-xs-2 control-label">Usuario</label>
                      <div class="col-xs-10">
                        <input type="text" class="form-control input-xs " name="NameUsuario" id="NameUsuario" readonly>
                      </div>
                    </div>
                    <hr style="margin: 0px;">
                    <div class="form-group">
                      <label class="col-xs-12 control-label text-red text-center labelUltimaLectura no-visible">Ultima lectura <span id="FechaUltimaLectura"></span>: (<span id="UltimaLectura"></span> m<sup>3</sup>) <label id="ConsumoActual" style="color: blue;"></label>
                    </div>
                    
                    <div class="form-group">
                      <div class="col-lg-5  text-right">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="Encerar" id="Encerar" value="1"  tabindex="2">
                            Medidor vuelve a 0.
                          </label>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </form>
            </div>
        </div>

        <div class="row contenedor_item_center">
            <button class="btn btn-success" title="Guardar Cambios" onclick="GuardarCambios()">
              <img  src="../../img/png/grabar.png" width="25" height="30" tabindex="3">
            </button>
          </button>
          <a href="./inicio.php?mod=<?php echo @$_GET['mod']?>" class="btn btn-warning" id="btnSalirModuloPF" title="Salir del Modulo" data-dismiss="modal">
            <img  src="../../img/png/salire.png" width="25" height="30">
          </a>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function () {

    $("#CMedidor").focus();

    //Obtener datos del medidor
    $("#CMedidor").on("blur", function(){
      let medidor = $("#CMedidor").val();
      if(medidor!=""){
        $.ajax({
          type: "POST",                 
          url: '../controlador/facturacion/facturar_pensionC.php?BuscarClienteCodigoMedidor='+medidor,
          dataType:'json', 
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          {
            $('#myModal_espera').modal('hide');  
            if(response.rps){
              $("#NameUsuario").val(response.data.Cliente);
              $("#codigoCliente").val(response.data.Codigo);
              $("#FechaUltimaLectura").text(response.data.fechaUltimaMedida);
              $("#UltimaLectura").text(response.data.ultimaMedida);
              $(".labelUltimaLectura").removeClass('no-visible')
              //TODO LS falta obtner si esta encerado y marcar o no el check
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
  
  function GuardarCambios() {
    let medidor = $("#CMedidor").val();
    $("#CMedidor").focus();
    if(medidor!=""){
      $('#myModal_espera').modal('show');

      $.ajax({
          type: "POST",                 
          url: '../controlador/facturacion/facturar_pensionC.php?GuardarCambiosMedidorAgua=true',
          data: $("#FIngresoConsumoAgua").serialize(),
          dataType:'json', 
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          {alert('proceso no programado'); //TODO LS quitaar
            $('#myModal_espera').modal('hide');  
            if(response.rps){
              $("#FIngresoConsumoAgua")[0].reset();
              Swal.fire('¡Bien!', response.mensaje, 'success');
              $("#FechaUltimaLectura").text('');
              $("#UltimaLectura").text('');
              $(".labelUltimaLectura").addClass('no-visible')
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
      Swal.fire('¡Oops!', "No ha seleccionado indicado un medidor.", 'info')
    }
  }

</script>