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
.padding-all{
  padding: 2px !important;
}
.table-responsive thead {
  position: sticky;
  top: 0;
}
.table-responsive {
  box-shadow: 5px 5px 6px rgba(0, 0, 0, 0.6);
}
</style>
<div class="row">
  <div class="col-sm-5 col-xs-12">
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
  <div class="col-sm-7 col-xs-12">
    <div class="row">
      <div class="form-group col-xs-12 col-md-6  padding-all margin-b-1">
        <div class="col-xs-12  text-center">
          <label for="inputEmail3" class="col control-label" style="font-size: 13px;">Periodo de Cierre</label>
        </div>
        <div class="col-xs-6">
          <input tabindex="43" type="date" name="MBFechaI" id="MBFechaI" class="form-control input-xs validateDate" onchange="">
        </div>
        <div class="col-xs-6">
          <input tabindex="44" type="date" name="MBFechaF" id="MBFechaF" class="form-control input-xs validateDate" onchange="">
        </div>
      </div>
      <div class="form-group col-xs-12 col-md-6  padding-all margin-b-1">
        <div class="col-xs-8 padding-all">
          <div class="col-xs-12">
            <label for="CheqCajero" class="col control-label" style="font-size: 13px;">
              <input style="margin-top: 0px;margin-right: 2px;" tabindex="47" type="checkbox" name="CheqCajero" id="CheqCajero"> Por Cajero
            </label>
          </div>
          <div class="col-xs-12">
            <select style="display:none" class="form-control input-xs" name="DCBenef" id="DCBenef" tabindex="46" onchange="" >
            </select>
          </div>
        </div>
        <div class="col-xs-4 padding-all">
          <label for="CheqOrdDep" class="col control-label" style="font-size: 13px;">
            <input style="margin-top: 0px;margin-right: 2px;" tabindex="48" type="checkbox" name="CheqOrdDep" id="CheqOrdDep"> Ordenar Por Depósito
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div id="resultado">
</div>
<h3 id="TextoImprimio"></h3> -->

<div class="row">
  <div class="panel panel-primary col-sm-12" style="  margin-bottom: 5px;">
    <div class="panel-body" style=" padding-top: 5px;">
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
                    </select>
                  </div>
                </div>
              </div>
              <div class="table-responsive DGVentas-container" style="overflow-y: scroll; min-height: 50px;width: auto;">
                <div class="sombra" style>
                  <table id="DGVentas" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>TC</th>
                        <th>Fecha</th>
                        <th>Cta_CxP</th>
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Total_MN</th>
                      </tr>
                    </thead>
                    <tbody id="DGVentasBody">
                    </tbody>
                  </table>          
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="abonos">

              <div class="row">
                <div class="form-group col-xs-6 padding-all margin-b-1">
                  <label for="LabelCheque" class="col control-label">TOTAL</label>
                  <div class="col">
                    <input type="tel" class="form-control input-xs" id="LabelCheque" name="LabelCheque">
                  </div>
                </div>
                <div class="form-group col-xs-6 padding-all margin-b-1">
                  <label for="AdoCxC" class="col control-label">CxC</label>
                  <div class="col">
                    <select style="min-width: 150px;" class="form-control input-xs" name="AdoCxC" id="AdoCxC"  onchange="" >
                    </select>
                  </div>
                </div>
              </div>

              <div class="table-responsive " style="overflow-y: scroll; min-height: 50px;max-height: 200px; width: auto;margin-bottom: 10px;">
                <div class="sombra" style>
                  <table id="DGCxC" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>TP</th>
                        <th>Fecha</th>
                        <th>Orden_No</th>
                        <th>COD_BANCO</th>
                        <th>Cliente</th>
                        <th>Serie</th>
                        <th>Autorizacion</th>
                        <th>Factura</th>
                        <th>Banco</th>
                        <th>Cheque</th>
                        <th>Abono</th>
                        <th>Comprobante</th>
                        <th>Cta</th>
                        <th>Cta_CxP</th>
                        <th>CodigoC</th>
                        <th>Ciudad</th>
                        <th>Sectorizacion</th>
                        <th>Ejecutivo</th>
                      </tr>
                    </thead>
                    <tbody id="DGCxCBody">
                    </tbody>
                  </table>          
                </div>
              </div>

              <div class="table-responsive" style="overflow-y: scroll; min-height: 80px;max-height:200px; width: auto;">
                <div class="sombra" style>
                  <table id="DGAnticipos" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>TP</th>
                        <th>Fecha</th>
                        <th>Cuenta</th>
                        <th>Cliente</th>
                        <th>Numero</th>
                        <th>Creditos</th>
                        <th>Contra_Cta</th>
                        <th>Cta</th>
                      </tr>
                    </thead>
                    <tbody id="DGAnticiposBody">
                    </tbody>
                  </table>          
                </div>
              </div>

            </div>
            <div class="tab-pane modal-body" id="inventario">
              <div class="col-md-2">
                <div class="table-responsive DGCierres-container" style="overflow-y: scroll; min-height: 50px; width: auto;">
                  <div class="sombra" style>
                    <table id="DGCierres" class="table-sm tablaHeight" style="width: -webkit-fill-available;">
                      <thead>
                        <tr>
                          <th>Dias Cierres</th>
                        </tr>
                        <tr>
                          <th>Fecha</th>
                        </tr>
                      </thead>
                      <tbody id="DGCierresBody">
                      </tbody>
                    </table>          
                  </div>
                </div>
              </div>
              <div class="col-md-10">
                <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;margin-bottom: 15px;">
                  <div class="sombra" style>
                    <table id="DGInv" class="table-sm" style="width: -webkit-fill-available;">
                      <thead>
                        <tr>
                          <th>Codigo_Inv</th>
                          <th>Producto</th>
                          <th>Entradas</th>
                        </tr>
                      </thead>
                      <tbody id="DGInvBody">
                      </tbody>
                    </table>          
                  </div>
                </div>
                <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
                  <div class="sombra" style>
                    <table id="DGProductos" class="table-sm" style="width: -webkit-fill-available;">
                      <thead>
                        <tr>
                          <th>Codigo</th>
                          <th>Producto</th>
                          <th>CANTIDADES</th>
                          <th>SUBTOTALES</th>
                          <th>SUBTOTAL_IVA</th>
                        </tr>
                      </thead>
                      <tbody id="DGProductosBody">
                      </tbody>
                    </table>          
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="contabilidad">

              <input class="form-control" id="LblConcepto"></input>
              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;margin-bottom: 1px;">
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

              <div class="text-right" style="margin-bottom: 15px;">
                <input id="LblDiferencia"></input>
                <input id="LabelDebe"></input>
                <input id="LabelHaber"></input>
              </div>
              <input class="form-control" id="LblConcepto1"></input>
              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;margin-bottom: 10px;">
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

              <div class="text-right">
                <input id="LblDiferencia1"></input>
                <input id="LabelDebe1"></input>
                <input id="LabelHaber1"></input>
              </div>

            </div>
            <div class="tab-pane modal-body" id="anuladas">

              <div class="table-responsive DGFactAnul-container" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
                <div class="sombra" style>
                  <table id="DGFactAnul" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>T</th>
                        <th>TC</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Factura</th>
                        <th>Total_IVA</th>
                        <th>Total_MN</th>
                        <th>Cta_CxP</th>
                      </tr>
                    </thead>
                    <tbody id="DGFactAnulBody">
                    </tbody>
                  </table>          
                </div>
              </div>

            </div>
            <div class="tab-pane modal-body" id="reporte_auditoria">
              <select style="min-width: 150px;" class="form-control input-xs" name="AdoSRI" id="AdoSRI"  onchange="" >
              //TODO LS falta cargar data
              </select>

              <label id="DGSRICaption"></label>
              <div class="table-responsive" style="overflow-y: scroll; min-height: 80px;max-height:285px; width: auto;margin-bottom: 15px;">
                <div class="sombra" style>
                  <table id="DGSRI" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>TC</th>
                        <th>T</th>
                        <th>RUC_CI</th>
                        <th>TB</th>
                        <th>Razon_Social</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Autorizacion</th>
                        <th>Serie</th>
                        <th>Secuencial</th>
                      </tr>
                    </thead>
                    <tbody id="DGSRIBody">
                    </tbody>
                  </table>          
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6 col-md-2">
                  <label>CON I.V.A.</label>
                  <input id="LblConIVA"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>SIN I.V.A.</label>
                  <input id="LblSinIVA"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>DESCUENTO</label>
                  <input id="LblDescuento"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>TOTAL  I.V.A.</label>
                  <input id="LblIVA"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>TOTAL  SERVICIO</label>
                  <input id="LblServicio"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label> T O T A L</label>
                  <input id="LblTotalFacturado"></input>
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="reporte_banco">
              <select style="min-width: 150px;" class="form-control input-xs" name="DCBanco" id="DCBanco"  onchange="" >
              </select>

              <div class="table-responsive DGBanco-container" style="overflow-y: scroll; min-height: 50px; width: auto;">
                <div class="sombra" style>
                  <!-- //TODO LS cuando se llena esta tabla -->
                  <table id="DGBanco" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>T</th>
                      </tr>
                    </thead>
                    <tbody id="DGBancoBody">
                    </tbody>
                  </table>          
                </div>
              </div>
            </div>
       </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    Form_Activate()
    ajustarAlturaTabla()
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

        var DCBenef = $("#DCBenef"); ////TODO LS carga a carga con ajax
        for (var indice in data.AdoClientes) {
          DCBenef.append('<option value="' + data.AdoClientes[indice].NomCuenta+ ' ">' + data.AdoClientes[indice].NomCuenta + '</option>');
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
            let valor = datos[i][nombreColumna];           
            if(typeof valor === 'object'){
              valor = valor.date;
              if (valor.endsWith(".000000")) {
                valor = valor.slice(0, -7); // Obtiene los primeros 6 caracteres del final
              }
            }
            fila.append($("<td>").text(valor));
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
                  success: function(datos4)             
                  {
                    $("#Bar_espera_progress").css('width','70%')
                    $("#Bar_espera_progress .txt_progress").text('Actualizando Clientes')
                    $.ajax({
                      type: "POST",                 
                      url: '../controlador/contabilidad/FCierre_CajaC.php?Actualizar_Datos_Representantes=true',
                      dataType:'json', 
                      data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                      success: function(datos5)             
                      {
                        $("#Bar_espera_progress").css('width','70%')
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
                          success: function(datos6)             
                          {
                            if(datos6.error){
                              $('#myModal_espera_progress').modal('hide');
                              Swal.fire({
                                type: 'warning',
                                title: '',
                                text: datos6.mensaje
                              });
                            }
                            else{
                              CargarDataResponseGrabar_Asientos_Facturacion(datos6);
                              $("#Bar_espera_progress").css('width','90%')
                              $("#Bar_espera_progress .txt_progress").text('Verificando Errores')
                              $.ajax({
                                type: "POST",                 
                                url: '../controlador/contabilidad/FCierre_CajaC.php?VerificandoErrores=true',
                                dataType:'json', 
                                data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                                success: function(datos7)             
                                {
                                  construirTabla(datos7.AdoAsiento, "DGAsiento")  
                                  construirTabla(datos7.AdoAsiento1, "DGAsiento1")

                                  $("#Bar_espera_progress").css('width','95%')
                                  $("#Bar_espera_progress .txt_progress").text('Fechas de Cierres')
                                  $.ajax({
                                    type: "POST",                 
                                    url: '../controlador/contabilidad/FCierre_CajaC.php?FechasdeCierre=true',
                                    dataType:'json', 
                                    data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
                                    success: function(datos8)             
                                    {
                                      construirTabla(datos8.AdoCierres, "DGCierres")  
                                      construirTabla(datos8.AdoAnticipos, "DGAnticipos")

                                      $("#Bar_espera_progress").css('width','99%')
                                      $("#Bar_espera_progress .txt_progress").text('Finalizando Proceso')
                                      if (redondear(datos6.LabelDebe - datos6.LabelHaber, 2) !== 0) {
                                        $('#myModal_espera_progress').modal('hide');
                                        Swal.fire({
                                          type: 'warning',
                                          text: '',
                                          title: "Las Transacciones no cuadran, verifique las facturas emitidas o los abonos del día."
                                        });
                                      }

                                      ShowFInfoErrorShowView()
                                    },
                                    error: function (e) {
                                      $('#myModal_espera_progress').modal('hide');
                                      alert("error inesperado en Fechas de Cierres")
                                    }
                                  });
                                },
                                error: function (e) {
                                  $('#myModal_espera_progress').modal('hide');
                                  alert("error inesperado al Verificar Errores")
                                }
                              });
                            }
                          },
                          error: function (e) {
                            $('#myModal_espera_progress').modal('hide');
                            alert("error inesperado al Procesar Asientos Contables")
                          }
                        });
                      },
                      error: function (e) {
                        $('#myModal_espera_progress').modal('hide');
                        alert("error inesperado al Actualizar Clientes")
                      }
                    });
                  },
                  error: function (e) {
                    $('#myModal_espera_progress').modal('hide');
                    alert("error inesperado al Actualizar Abonos")
                  }
                });
              },
              error: function (e) {
                $('#myModal_espera_progress').modal('hide');
                alert("error inesperado al Mayorizar Inventario")
              }
            });
          },
          error: function (e) {
            $('#myModal_espera_progress').modal('hide');
            alert("error inesperado al actualizar los productos")
          }
        });
      },
      error: function (e) {
        $('#myModal_espera_progress').modal('hide');
        alert("error inesperado iniciar el proceso")
      }
    });
  }

  function CargarDataResponseGrabar_Asientos_Facturacion(datos) {
    construirTabla(datos.AdoCxC, "DGCxC")

    var AdoCxC = $("#AdoCxC");
        for (var indice in datos.AdoCxC) { //TODO LS que valor se asigna al select??
          AdoCxC.append('<option value="' + datos.AdoCxC[indice].Orden_No+ ' ">' + datos.AdoCxC[indice].Orden_No + '</option>');
        } 

    construirTabla(datos.AdoAsiento, "DGAsiento")  
    construirTabla(datos.AdoAsiento1, "DGAsiento1")
    construirTabla(datos.AdoFactAnul, "DGFactAnul")
    construirTabla(datos.AdoInv, "DGInv")
    construirTabla(datos.AdoProductos, "DGProductos")
    construirTabla(datos.AdoVentas, "DGVentas")

    var AdoVentas = $("#AdoVentas");
        for (var indice in datos.AdoVentas) { //TODO LS que valor se asigna al select??
          AdoVentas.append('<option value="' + datos.AdoVentas[indice].Factura+ ' ">' + datos.AdoVentas[indice].Factura + ' - '+datos.AdoVentas[indice].Total_MN+'</option>');
        }

    $("#DGSRICaption").text(datos.DGSRI);
    construirTabla(datos.AdoSRI, "DGSRI")

    var AdoSRI = $("#AdoSRI");
        for (var indice in datos.AdoSRI) { //TODO LS que valor se asigna al select??
          AdoSRI.append('<option value="' + datos.AdoSRI[indice].RUC_CI+ ' ">' + datos.AdoSRI[indice].RUC_CI + ' - '+datos.AdoSRI[indice].Razon_Social+'</option>');
        }

    $("#LabelAbonos").val(datos.LabelAbonos)
    $("#LabelCheque").val(datos.LabelCheque)
    $("#LabelDebe").val(datos.LabelDebe)
    $("#LabelDebe1").val(datos.LabelDebe1)
    $("#LabelHaber").val(datos.LabelHaber)
    $("#LabelHaber1").val(datos.LabelHaber1)
    $("#LblConIVA").val(datos.LblConIVA) //TODO LS 
    $("#LblConcepto").val(datos.LblConcepto)
    $("#LblConcepto1").val(datos.LblConcepto1)
    $("#LblDescuento").val(datos.LblDescuento) //TODO LS 
    $("#LblDiferencia").val(datos.LblDiferencia)
    $("#LblDiferencia1").val(datos.LblDiferencia1)
    $("#LblIVA").val(datos.LblIVA) //TODO LS 
    $("#LblServicio").val(datos.LblServicio) //TODO LS 
    $("#LblSinIVA").val(datos.LblSinIVA) //TODO LS 
    $("#LblTotalFacturado").val(datos.LblTotalFacturado) //TODO LS 
    $("#TextoImprimio").text(datos.TextoImprimio) //TODO LS definir uso o posicion
  }

  function redondear(valor, decimales) {
    if (decimales <= 0) decimales = 0;
    if (decimales >= 6) decimales = 6;
    valor_redondeo = parseFloat(valor).toFixed(6);
    valor_redondeo = parseFloat(valor_redondeo).toFixed(decimales);
    return valor_redondeo;
  }

  function ajustarAlturaTabla(tabla) {
    var posicionEncabezado = document.querySelector("#DGVentas thead").getBoundingClientRect().top;
    var alturaDisponible = window.innerHeight - posicionEncabezado;
    $(".DGVentas-container").height(alturaDisponible - 55);
    $(".DGCierres-container").height(alturaDisponible);
    $(".DGFactAnul-container").height(alturaDisponible);
    $(".DGBanco-container").height(alturaDisponible - 40);

  }
</script> 