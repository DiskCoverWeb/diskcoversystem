<?php
$GrabarEnabled =  true;
switch ($_SESSION['INGRESO']['modulo_']) {
    case '01': //CONTABILIDAD
    case '05': //CAJACREDITO
      $GrabarEnabled =  false;
      break;   
}
?>
<style type="text/css">
.col{
  display: inline-block;
}
</style>
<div class="row">
  <div class="col-lg-6 col-sm-12 col-md-9 col-xs-12">
    <div class="col">
      <a  href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
        <img src="../../img/png/salire.png" width="25" height="30">
      </a>
    </div>
    <div class="col">
      <a  href="javascript:void(0)" title="Diario de Caja" class="btn btn-default" onclick="Diario_Caja()">
        <img src="../../img/png/file2.png" width="25" height="30">
      </a>
    </div>
    <?php if ($GrabarEnabled): ?>
      <div class="col">
        <a  href="javascript:void(0)" id="Grabar" title="Grabar Diario de Caja" class="btn btn-default"  onclick="">
          <img src="../../img/png/grabar.png" width="25" height="30">
        </a>
      </div>
    <?php endif ?>
  </div>
</div>
<div id="resultado">
  aui
</div>
<div class="row">
  <div class="panel panel-primary col-sm-12" style="  margin-bottom: 5px;">
    <div class="panel-body" style=" padding-top: 5px;">
      <div class="row">
        <div class="form-group col-xs-12 col-md-4  padding-all margin-b-1">
          <div class="col-xs-12  text-center">
            <label for="inputEmail3" class="col control-label">Periodo de Cierre</label>
          </div>
          <div class="col-xs-6">
            <input tabindex="43" type="date" name="MBFechaI" id="MBFechaI" class="form-control input-xs validateDate" onchange="">
          </div>
          <div class="col-xs-6">
            <input tabindex="44" type="date" name="MBFechaF" id="MBFechaF" class="form-control input-xs validateDate" onchange="">
          </div>
        </div>

        <div class="form-group col-xs-12 col-md-8  padding-all margin-b-1">
          <div class="col-xs-6">
            <div class="col-xs-12">
              <label for="CheqCajero" class="col control-label">
                <input style="margin-top: 0px;margin-right: 2px;" tabindex="47" type="checkbox" name="CheqCajero" id="CheqCajero"> Por Cajero
              </label>
            </div>
            <div class="col-xs-12">
              <select style="display:none" class="form-control input-xs" name="DCBenef" id="DCBenef" tabindex="46" onchange="" >
              //TODO LS falta cargar data
              </select>
            </div>
          </div>
          <div class="col-xs-6">
            <label for="CheqOrdDep" class="col control-label">
              <input style="margin-top: 0px;margin-right: 2px;" tabindex="48" type="checkbox" name="CheqOrdDep" id="CheqOrdDep"> Ordenar Por Depósito
            </label>
          </div>
        </div>
      </div>
      <div class="col-sm-12">
        <ul class="nav nav-tabs">
           <li class="nav-item active">
             <a class="nav-link" data-toggle="tab" href="#ventas">1 VENTAS</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#abonos">2 ABONOS</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#inventario">3 INVENTARIO</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#contabilidad">4 CONTABILIDAD</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#anuladas">5 ANULADAS</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#reporte_auditoria">6 REPORTE DE AUDITORIA</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#reporte_banco">7 REPORTE DEL BANCO</a>
           </li>
         </ul>
         <div class="tab-content">
            <div class="tab-pane modal-body active" id="ventas">
              <div class="row">
                <div class="form-group col-xs-6 padding-all margin-b-1">
                  <label for="LabelAbonos" class="col control-label">TOTAL</label>
                  <div class="col">
                    <input type="tel" class="form-control input-xs" id="LabelAbonos" name="LabelAbonos">
                  </div>
                </div>
                <div class="form-group col-xs-6 padding-all margin-b-1">
                  <label for="LabelAbonos" class="col control-label">Ventas</label>
                  <div class="col">
                    <select style="min-width: 150px;" class="form-control input-xs" name="AdoVentas" id="AdoVentas"  onchange="" >
                    //TODO LS falta cargar data
                    </select>
                  </div>
                </div>
              </div>
              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
                <div class="sombra" style>
                  <table id="DGVentas" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Mes</th>
                        <th>Código</th>
                      </tr>
                    </thead>
                    <tbody id="DGVentasBody">
                    </tbody>
                  </table>          
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="abonos">
            </div>
            <div class="tab-pane modal-body" id="inventario">
            </div>
            <div class="tab-pane modal-body" id="contabilidad">


              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
                <div class="sombra" style>
                  <table id="DGAsiento" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>A_No</th>
                        <th>BENEFICIARIO</th>
                        <th>CUENTA</th>
                      </tr>
                    </thead>
                    <tbody id="DGAsientoBody">
                    </tbody>
                  </table>          
                </div>
              </div>

              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
                <div class="sombra" style>
                  <table id="DGAsiento1" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>A_No</th>
                        <th>BENEFICIARIO</th>
                        <th>CUENTA</th>
                      </tr>
                    </thead>
                    <tbody id="DGAsiento1Body">
                    </tbody>
                  </table>          
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="anuladas">
            </div>
            <div class="tab-pane modal-body" id="reporte_auditoria">
            </div>
            <div class="tab-pane modal-body" id="reporte_banco">
              <select style="min-width: 150px;" class="form-control input-xs" name="DCBanco" id="DCBanco"  onchange="" >
              //TODO LS falta cargar data
              </select>
            </div>
       </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    Form_Activate()
    $('#CheqCajero').click(function() {
      if ($(this).is(':checked')) {
        $('#DCBenef').show();
      } else {
        $('#DCBenef').hide();
      }
    });
  });

  function Form_Activate() {
    $.ajax({
      type: "POST",                 
      url: '../controlador/contabilidad/FCierre_CajaC.php?Form_Activate=true',
      dataType:'json', 
      success: function(data)             
      {
        construirTabla(data.AdoAsiento, "DGAsiento")  
        construirTabla(data.AdoAsiento1, "DGAsiento1") 

        var DCBanco = $("#DCBanco");
        for (var indice in data.AdoCtaBanco) {
          DCBanco.append('<option value="' + data.AdoCtaBanco[indice].NomCuenta+ ' ">' + data.AdoCtaBanco[indice].NomCuenta + '</option>');
        } 
      }
    });
  }

  // construye la tabla con los datos procesados
  function construirTabla(datos, tablaId) {
    // obtiene el encabezado de la tabla
    var encabezado = $("#" + tablaId + " thead tr th");

    // cuenta el número de columnas en el encabezado
    var numColumnas = encabezado.length;

    // crea las filas con los datos
    var tbody = $("#" + tablaId + " tbody");
    for (var i = 0; i < datos.length; i++) {
        var fila = $("<tr>");
        for (var j = 0; j < numColumnas; j++) {
            var nombreColumna = encabezado.eq(j).text();
            fila.append($("<td>").text(datos[i][nombreColumna]));
        }
        tbody.append(fila);
    }
  }

  function Diario_Caja() {
    $('#myModal_espera_progress').modal('show');
    $("#Bar_espera_progress").css('width','0%')
    $("#Bar_espera_progress .txt_progress").text('Procesando el Cierre de Caja...')

    $.ajax({
      type: "POST",                 
      url: '../controlador/contabilidad/FCierre_CajaC.php?Diario_CajaInicio=true',
      dataType:'json', 
      data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
      success: function(datos)             
      {
        construirTabla(datos.AdoAsiento, "DGAsiento")  
        construirTabla(datos.AdoAsiento1, "DGAsiento1") 
        $("#Bar_espera_progress").css('width','20%')
        $("#Bar_espera_progress .txt_progress").text('Actualizando Productos')
        $.ajax({
          type: "POST",                 
          url: '../controlador/contabilidad/FCierre_CajaC.php?Productos_Cierre_Caja=true',
          dataType:'json', 
          data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
          success: function(datos2)             
          {
            $("#Bar_espera_progress").css('width','40%')
            $("#Bar_espera_progress .txt_progress").text('Mayorizando Inventarios')
            $.ajax({
              type: "POST",                 
              url: '../controlador/contabilidad/FCierre_CajaC.php?Mayorizar_Inventario=true',
              dataType:'json', 
              // data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
              success: function(datos3)             
              {
                $("#Bar_espera_progress").css('width','60%')
                $("#Bar_espera_progress .txt_progress").text('Actualizando Abonos')
                $.ajax({
                  type: "POST",                 
                  url: '../controlador/contabilidad/FCierre_CajaC.php?Actualizar_Abonos_Facturas=true',
                  dataType:'json', 
                  data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                  success: function(datos3)             
                  {
                    $("#Bar_espera_progress").css('width','70%')
                    $("#Bar_espera_progress .txt_progress").text('Actualizando Clientes')
                    $.ajax({
                      type: "POST",                 
                      url: '../controlador/contabilidad/FCierre_CajaC.php?Actualizar_Datos_Representantes=true',
                      dataType:'json', 
                      data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                      success: function(datos3)             
                      {
                        $("#Bar_espera_progress").css('width','75%')
                        $("#Bar_espera_progress .txt_progress").text('Procesando Asientos Contables')
                        $.ajax({
                          type: "POST",                 
                          url: '../controlador/contabilidad/FCierre_CajaC.php?Grabar_Asientos_Facturacion=true',
                          dataType:'json', 
                          data: {
                            'MBFechaI' : $("#MBFechaI").val() ,
                            'MBFechaF' : $("#MBFechaF").val(),
                            'CheqCajero' : $("#CheqCajero").val() ,
                            'CheqOrdDep' : $("#CheqOrdDep").val(),
                            'DCBenef' : $("#DCBenef").val()  },
                          success: function(datos3)             
                          {
                            $("#Bar_espera_progress").css('width','85%')
                            $("#Bar_espera_progress .txt_progress").text('Verificando Errores')
                            $.ajax({
                              type: "POST",                 
                              url: '../controlador/contabilidad/FCierre_CajaC.php?VerificandoErrores=true',
                              dataType:'json', 
                              data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                              success: function(datos3)             
                              {
                                $("#Bar_espera_progress").css('width','90%')
                                $("#Bar_espera_progress .txt_progress").text('Fechas de Cierres')
                                $.ajax({
                                  type: "POST",                 
                                  url: '../controlador/contabilidad/FCierre_CajaC.php?FechasdeCierre=true',
                                  dataType:'json', 
                                  data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                                  success: function(datos3)             
                                  {
                                    $("#Bar_espera_progress").css('width','99%')
                                    $("#Bar_espera_progress .txt_progress").text('Finalizando Proceso')
                                    FInfoErrorShow()
                                  },
                                  error: function (e) {
                                    alert("error inesperado en Fechas de Cierres")
                                  }
                                });
                              },
                              error: function (e) {
                                alert("error inesperado al Verificar Errores")
                              }
                            });
                          },
                          error: function (e) {
                            alert("error inesperado al Procesar Asientos Contables")
                          }
                        });
                      },
                      error: function (e) {
                        alert("error inesperado al Actualizar Clientes")
                      }
                    });
                  },
                  error: function (e) {
                    alert("error inesperado al Actualizar Abonos")
                  }
                });
              },
              error: function (e) {
                alert("error inesperado al Mayorizar Inventario")
              }
            });
          },
          error: function (e) {
            alert("error inesperado al actualizar los productos")
          }
        });
      },
      error: function (e) {
        alert("error inesperado iniciar el proceso")
      }
    });
  }

  function FInfoErrorShow(){
    $.ajax({
      type: "POST",                 
      url: '../controlador/contabilidad/FCierre_CajaC.php?FInfoErrorShow=true',
      dataType:'json', 
      data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
      success: function(datos3)             
      {
         $('#myModal_espera_progress').modal('hide');
      },
      error: function (e) {
        alert("error inesperado en Fechas de Cierres")
      }
    });
  }
</script> 