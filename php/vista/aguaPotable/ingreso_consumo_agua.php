<?php
  require_once "../modelo/facturacion/facturar_pensionM.php";
  $facturar = new facturar_pensionM();
  $periodo = $facturar->getPeriodoAbierto();
  if(count($periodo)>0){
    $dataperiodo = explode(" ", $periodo[0]['Detalle']);
  }
?>
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
        <h4>Ingreso de Consumo de Agua</h4>
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
                      <br><label>¿No tiene el código del medidor? <a class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModalBuscarMedidorCliente">Buscar por nombre de cliente <i class="fa fa-mouse-pointer"></i></a></label>
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
                      <?php if (count($periodo)<=0): ?>
                        <h4 style="color: red; text-align: center;">ACTIVE EL MES A PROCESAR</h4>
                      <?php endif ?>
                      <label class="col-xs-12 control-label text-red text-center labelUltimaLectura no-visible">Ultima lectura <span id="FechaUltimaLectura"></span>: (<span id="UltimaLectura"></span> m<sup>3</sup>) <label id="ConsumoActual" style="color: blue;"></label>
                    </div>
                    <div class="form-group">
                      <label class="col-xs-2 control-label">Año</label>
                      <div class="col-xs-4">
                        <input type="text" class="form-control input-xs " value="<?php echo @$dataperiodo[0] ?>" name="anio" id="anio" readonly>
                      </div>
                      <label class="col-xs-2 control-label">Mes</label>
                      <div class="col-xs-4">
                        <input type="text" class="form-control input-xs " value="<?php echo @$dataperiodo[1] ?>" name="mes" id="mes" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-7">
                        <div class="radio">
                          <label>
                            <input type="radio" name="optionrango"  value="menos15" checked="" >
                            Menos de 15.000 metros cúbicos.
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="optionrango"  value="mas15" >
                            Más de 15.000 metros cúbicos.
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-5  text-right">
                        <!-- <div class="checkbox">
                          <label>
                            <input type="checkbox" name="optionsRadios" id="optionsRadios2" value="option2">
                            Medidor vuelve a 0.
                          </label>
                        </div> -->
                      </div>
                    </div>
                    <div class="form-group" style=" margin-bottom: 2px;">
                      
                      <div class="col-xs-12 col-lg-6  strong text-right" >
                        <input onkeydown="if (event.keyCode === 13) GuardarConsumoAgua()"  style="max-width: 120px;display: inline-block;" type="tel" class="form-control input-xs " name="Lectura" id="Lectura" tabindex="2"> m<sup>3</sup>
                        <br><label id="ErrorLecturaExiste" class="control-label text-red text-left"></label>
                      </div>
                    </div>
                    <div class="form-group" style=" margin-bottom: 2px;">
                      <!-- <label for="inputPassword" class="col-lg-12 control-label text-red text-left">Promedio de Consumo: 67</label> -->
                    </div>
                    <div class="form-group" style=" margin-bottom: 2px;">
                      <!-- <label for="inputPassword" class="col-lg-12 control-label text-red text-left">Consumo: 20 (47 Bajo el Promedio)</label> -->
                    </div>
                    <!-- <div class="form-group">
                      <label for="inputPassword" class="col-xs-3 control-label ">Multas:</label>
                      <div class="col-xs-4">
                        <input type="tel" class="form-control input-xs " name="Multa" id="Multa" placeholder="0.00">
                      </div>
                    </div> -->
                  </fieldset>
                </form>
            </div>
        </div>

        <div class="row contenedor_item_center">
          <?php if (count($periodo)>0): ?>
            <button class="btn btn-success" title="Guardar Consumo" onclick="GuardarConsumoAgua()" id="GuardarConsumo">
              <img  src="../../img/png/grabar.png" width="25" height="30" tabindex="3">
            </button>
          <?php endif ?>
          </button>
          <a href="./inicio.php?mod=<?php echo @$_GET['mod']?>" class="btn btn-warning" id="btnSalirModuloPF" title="Salir del Modulo" data-dismiss="modal">
            <img  src="../../img/png/salire.png" width="25" height="30">
          </a>
        </div>
    </div>
</div>


<div id="myModalBuscarMedidorCliente" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Buscar medidores por nombre del cliente: <b><span id="PFnombreCliente"></span></b></h4>
      </div>
      <div class="modal-body">
        <form role="form" id="FInsPreFacturas" name="FInsPreFacturas">
          <div class="box-body">
            <div class="col-xs-12" style="margin-bottom: 5px;">
              <div class="col-xs-12 col-md-3   ">
                <label class="">Cliente </label>
              </div>
              <div class="col-xs-12 col-md-9 colCliente   ">
                <select class="form-control full-width" id="cliente" name="cliente">
                  <option value="">Seleccione un cliente</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="col-xs-12 col-md-3   ">
                <label class="">Medidor No.</label>
              </div>
              <div class="col-xs-12 col-md-9 colCliente   ">
                  <select class="form-control input-sm" id="SelectMedidor" name="SelectMedidor">
                    <option value="<?php echo G_NINGUNO ?>">NINGUNO</option>
                  </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" title="Cerrar modal" data-dismiss="modal">
          <img  src="../../img/png/salire.png" width="25" height="30">
        </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {

    $("#CMedidor").focus();

    $("#Lectura").on('blur',function () {
      if($("#Lectura").val()!=""){
        let LIMITE_MEDIDOR = "<?php echo LIMITE_MEDIDOR ?>";
        let $Lectura = parseFloat($("#Lectura").val());
        let $LecturaAnterior = parseFloat($("#UltimaLectura").text());
        if($Lectura<$LecturaAnterior){//si la lectura actual es menor que la anterior, se asume que el contador llego a 10000 y se reinicio
          $anterior = LIMITE_MEDIDOR-$LecturaAnterior;
          $consumoActual = $anterior+$Lectura;

        }else{
          $consumoActual = $Lectura-$LecturaAnterior; 
        }
        $("#ConsumoActual").html(`Consumo Actual: ${$consumoActual} m<sup>3</sup>`);
        if($consumoActual>15){
          $('input[name="optionrango"][value="mas15"]').prop('checked', true);
        }else{
          $('input[name="optionrango"][value="menos15"]').prop('checked', true);
        }
      }
    })

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
            if(response.rps){
              
              if(response.data.TD=="P" || response.data.TD=="" || response.data.TD=="."){mostrarModalActualizarDocumento(response.data.Codigo)}
              $("#NameUsuario").val(response.data.Cliente);
              $("#codigoCliente").val(response.data.Codigo);
              $("#FechaUltimaLectura").text(response.data.fechaUltimaMedida);
              $("#UltimaLectura").text(response.data.ultimaMedida);
              $("#Lectura").val("");
              $("#Lectura").focus();
              $(".labelUltimaLectura").removeClass('no-visible')
              $("#ConsumoActual").text("");
              if(response.data.fechaUltimaMedida=='<?php echo $dataperiodo[1] ?>/<?php echo $dataperiodo[0] ?>'){
                $("#Lectura").attr('disabled','disabled')
                $("#GuardarConsumo").attr('disabled','disabled')
                $("#ErrorLecturaExiste").text("Ya se registro la lectura del mes actual");
              }else{
                $("#Lectura").removeAttr('disabled')
                $("#GuardarConsumo").removeAttr('disabled')
                $("#ErrorLecturaExiste").text('');
              }
            }else{
              Swal.fire('¡Oops!', response.mensaje, 'warning')
              $("#ConsumoActual").text("");
            }
            $('#myModal_espera').modal('hide');         
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            $("#ConsumoActual").text("");
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
      }
    })

    //Cuando se cambia el cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      ListarMedidores(data.codigo)
    });

    $('#SelectMedidor').on('change', function (e) {
      let medidor = $('#SelectMedidor').val();
      medidor = (medidor=='' || medidor=='.')?'':medidor
      $("#CMedidor").val(medidor);
      if(medidor!=''){$("#myModalBuscarMedidorCliente").modal('hide');}
      $("#CMedidor").blur();
    })

    autocomplete_cliente()
  });
  
  function OpenModalIngresoConsumoAgua(){
      $('.myModalNuevoCliente').modal('hide');
      $('#myModalIngresoConsumoAgua').modal('show');
  }


  function GuardarConsumoAgua() {
    let medidor = $("#CMedidor").val();
    $("#CMedidor").focus();
    if(medidor!=""){
      $.ajax({
          type: "POST",                 
          url: '../controlador/facturacion/facturar_pensionC.php?GuardarConsumoAgua=true',
          data: $("#FIngresoConsumoAgua").serialize(),
          dataType:'json', 
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          {
            if(response.rps){
              $("#FIngresoConsumoAgua")[0].reset();
              Swal.fire('¡Bien!', response.mensaje, 'success');
              $("#FechaUltimaLectura").text('');
              $("#UltimaLectura").text('');
              $(".labelUltimaLectura").addClass('no-visible')
            }else{
              Swal.fire('¡Oops!', response.mensaje, 'warning')
            }  
            $('#myModal_espera').modal('hide');      
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
    }else{
      Swal.fire('¡Oops!', "No ha seleccionado ningun medidor.", 'info')
    }
  }

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/facturar_pensionC.php?clienteBasic=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function ListarMedidores(codigo)
  {
    if(codigo!="" && codigo!="."){
      $.ajax({
        url:   '../controlador/modalesC.php?ListarMedidores=true',      
        type:'POST',
        dataType:'json',
        data:{'codigo':codigo},
        success: function(response){
          // construye las opciones del select dinámicamente
          var select = $('#SelectMedidor');
          select.empty(); // limpia las opciones existentes
          $.each(response, function (i, opcion) {

            if(i==0){
              select.append($('<option>', {
                value: '.',
                text: (opcion.Cuenta_No!=".")?'Selecciona un Medidor':'NINGUNO'
              }));
            }

            if(opcion.Cuenta_No!="."){
              select.append($('<option>', {
                value: opcion.Cuenta_No,
                text: opcion.Cuenta_No
              }));
            }
          });
        }
      });
    }

  }

  function mostrarModalActualizarDocumento(CodigoC) {
    Swal.fire({
      title: 'Actualizar Documento del Cliente '+$("#NameUsuario").val(),
      showCancelButton: true,
      cancelButtonText: 'Cerrar',
      confirmButtonText: 'Actualizar',
      html:
        '<label for="NewDocument">Numero de documento:</label>' +
        '<input type="tel" id="NewDocument" class="swal2-input" required>' +
        '<span id="error1" style="color: red;"></span><br>',
      focusConfirm: false,
      preConfirm: () => {
        const NewDocument = document.getElementById('NewDocument').value;
        if(NewDocument!="" && NewDocument!="."){
          return [NewDocument];
        }else{
          Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar un valor para actualizar';
          return false
        }
        
      }
    }).then((result) => {
      if (result.value) {
        const [NewDocument] = result.value;
        if(NewDocument!="" && NewDocument!="."){
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../controlador/modalesC.php?ActualizarDocumentoCliente=true',
            data: {'NewDocument' : NewDocument , 'CodigoC' : CodigoC},
            beforeSend: function () {   
              $('#myModal_espera').modal('show');
            },    
            success: function(response)
            { 
              if(response.rps){
                Swal.fire('¡Bien!', response.mensaje, 'success')
                $("#codigoCliente").val(response.codigoCliente);
              }else{
                Swal.fire('¡Oops!', response.mensaje, 'warning')
              }
              $("#Lectura").focus();
              $('#myModal_espera').modal('hide');        
            },
            error: function () {
              $('#myModal_espera').modal('hide');
              alert("Ocurrio un error inesperado, por favor contacte a soporte.");
            }
          });
        }
      }
    });
  }
</script>