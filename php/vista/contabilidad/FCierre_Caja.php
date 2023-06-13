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
.table-sm td{
  white-space: nowrap;
  padding: 0px 5px;
}
#swal2-content{
    font-size: 13px;
    font-weight: 500;
}
input:focus, select:focus, span:focus, button:focus, #guardar:focus, a:focus  {
  border: 2px solid #3c8cbb !important;
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
        <a  href="javascript:void(0)" id="Grabar" title="Grabar Diario de Caja" class="btn btn-default"  onclick="Grabar_Cierre_DiarioV()">
          <img src="../../img/png/grabar.png" width="25" height="30">
        </a>
      </div>
    <?php endif ?>
      <div class="col">
        <a  href="javascript:void(0)" id="Reactivar" title="Reactivar" class="btn btn-default"  onclick="SolicitarReactivar()">
          <img src="../../img/png/folder-check.png" width="25" height="30">
        </a>
      </div>
      <div class="col">
        <a  href="javascript:void(0)" id="IESS" title="I.E.S.S" class="btn btn-default"  onclick="IESS_Cierre_DiarioV()">
          <img src="../../img/png/iess.png" width="25" height="30">
        </a>
      </div>
      <div class="col">
        <a  href="javascript:void(0)" id="Excel" title="Enviar a Excel los resultados" class="btn btn-default" onclick="GenerarExcelResultadoCierreCaja()">
          <img src="../../img/png/excel.png" width="25" height="30">
        </a>
      </div>
  </div>
  <div class="col-sm-7 col-xs-12">
    <div class="row">
      <div class="form-group col-xs-12 col-md-6  padding-all margin-b-1">
        <div class="col-xs-12  text-center">
          <label for="inputEmail3" class="col control-label" style="font-size: 13px;">Periodo de Cierre</label>
        </div>
        <div class="col-xs-6">
          <input tabindex="43" type="date" name="MBFechaI" id="MBFechaI" class="form-control input-xs validateDate" onchange="" title="Fecha Inicial">
        </div>
        <div class="col-xs-6">
          <input tabindex="44" type="date" name="MBFechaF" id="MBFechaF" class="form-control input-xs validateDate" onchange="" title="Fecha Final">
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
</div>-->

<div class="row">
  <div class="panel panel-primary col-sm-12" style="  margin-bottom: 5px;">
    <div class="panel-body" style=" padding-top: 5px;">
      <div class="col-sm-12">
        <ul class="nav nav-tabs">
           <li class="nav-item active">
             <a class="nav-link" data-toggle="tab" href="#AdoVentasT">1 VENTAS</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#AdoCxCT">2 ABONOS</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#AdoInv">3 INVENTARIO</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#AdoAsientoT">4 CONTABILIDAD</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#AdoFactAnul">5 ANULADAS</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#AdoSRIT">6 REPORTE DE AUDITORIA</a>
           </li>
           <li class="nav-item ">
             <a class="nav-link" data-toggle="tab" href="#AdoBanco">7 REPORTE DEL BANCO</a>
           </li>
         </ul>
         <div class="tab-content">
            <div class="tab-pane modal-body active" id="AdoVentasT">
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
                        <th>Cliente</th>
                        <th>Serie</th>
                        <th>Autorizacion</th>
                        <th>Factura</th>
                        <th>Total_IVA</th>
                        <th>Descuento</th>
                        <th>Descuento2</th>
                        <th>Servicio</th>
                        <th>Propina</th>
                        <th>Total_MN</th>
                        <th>Saldo_MN</th>
                        <th>Cta_CxP</th>
                        <th></th>
                      </tr>
                    </thead> 
                  </table>          
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="AdoCxCT">

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
                        <th>COD_BANCO</th>
                        <th>Cliente</th>
                        <th>Serie</th>
                        <th>Autorizacion</th>
                        <th>Factura</th>
                        <th>Banco</th>
                        <th>Cheque</th>
                        <th>Abono</th>
                        <th>Comprobante</th>
                        <th>Orden_No</th>
                        <th>Cta</th>
                        <th>Cta_CxP</th>
                        <th>CodigoC</th>
                        <th>Ciudad</th>
                        <th>Ejecutivo</th>
                      </tr>
                    </thead>
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
                  </table>          
                </div>
              </div>

            </div>
            <div class="tab-pane modal-body" id="AdoInv">
              <div class="col-md-2">
                <table id="DGCierres" class="table-sm tablaHeight" style="width: -webkit-fill-available;">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                        </tr>
                      </thead>
                    </table>
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
                          <th>Cta_Venta</th>
                        </tr>
                      </thead>
                    </table>          
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="AdoAsientoT">

              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;margin-bottom: 1px;">
                <div class="sombra" style>
                  <table id="DGAsiento" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>CODIGO</th>
                        <th>CUENTA</th>
                        <th>PARCIAL_ME</th>
                        <th>DEBE</th>
                        <th>HABER</th>
                        <th>CHEQ_DEP</th>
                        <th>DETALLE</th>
                      </tr>
                    </thead>
                  </table>          
                </div>
              </div>

              <div class="text-right" style="margin-bottom: 15px;">
                <label for="LblDiferencia">Diferencia</label>
                <input id="LblDiferencia"></input>
                <label>TOTALES</label>
                <input id="LabelDebe"></input>
                <input id="LabelHaber"></input>
              </div>
              <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;margin-bottom: 10px;">
                <div class="sombra" style>
                  <table id="DGAsiento1" class="table-sm" style="width: -webkit-fill-available;">
                    <thead>
                      <tr>
                        <th>CODIGO</th>
                        <th>CUENTA</th>
                        <th>PARCIAL_ME</th>
                        <th>DEBE</th>
                        <th>HABER</th>
                        <th>CHEQ_DEP</th>
                        <th>DETALLE</th>
                      </tr>
                    </thead>
                  </table>          
                </div>
              </div>

              <div class="text-right">
                <label for="LblDiferencia">Diferencia</label>
                <input id="LblDiferencia1"></input>
                <label>TOTALES</label>
                <input id="LabelDebe1"></input>
                <input id="LabelHaber1"></input>
              </div>

            </div>
            <div class="tab-pane modal-body" id="AdoFactAnul">

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
                  </table>          
                </div>
              </div>

            </div>
            <div class="tab-pane modal-body" id="AdoSRIT">
              <select style="min-width: 150px;" class="form-control input-xs" name="AdoSRI" id="AdoSRI"  onchange="" >
              </select>
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
                        <th>Base_12</th>
                        <th>Base_0</th>
                        <th>Descuento</th>
                        <th>Descuento2</th>
                        <th>TOTAL</th>
                      </tr>
                    </thead>
                  </table>          
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6 col-md-2">
                  <label>CON I.V.A.</label><br>
                  <input id="LblConIVA"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>SIN I.V.A.</label><br>
                  <input id="LblSinIVA"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>DESCUENTO</label><br>
                  <input id="LblDescuento"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>TOTAL  I.V.A.</label><br>
                  <input id="LblIVA"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label>TOTAL  SERVICIO</label><br>
                  <input id="LblServicio"></input>
                </div>
                <div class="col-xs-6 col-md-2">
                  <label> T O T A L</label><br>
                  <input id="LblTotalFacturado"></input>
                </div>
              </div>
            </div>
            <div class="tab-pane modal-body" id="AdoBanco">
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

    $('#MBFechaI').blur(function() {
      let fechaI = $(this).val();
      FechaValidaJs(fechaI)
      $('#MBFechaF').val(fechaI);
    });

    $('#MBFechaF').blur(function() {
      let fechaF = $(this).val();
      FechaValidaJs(fechaF);
    });

    $('#MBFechaF').keydown(function(event) {
      let keyCode = event.which;
      let shift = event.shiftKey;
      if (shift && keyCode === 77) { // 77 es el código para la letra "M"
        let fechaI = $('#MBFechaI').val();
        let fechaF = UltimoDiaMes(fechaI);
        $(this).val(fechaF);
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
        // construirTabla(data.AdoAsiento, "DGAsiento")  
        // construirTabla(data.AdoAsiento1, "DGAsiento1") 

        var DCBanco = $("#DCBanco");
        for (var indice in data.AdoCtaBanco) {
          DCBanco.append('<option value="' + data.AdoCtaBanco[indice].NomCuenta+ ' ">' + data.AdoCtaBanco[indice].NomCuenta + '</option>');
        } 

        var DCBenef = $("#DCBenef"); ////TODO LS carga a carga con ajax
        for (var indice in data.AdoClientes) {
          DCBenef.append('<option value="' + data.AdoClientes[indice].Codigo+ ' ">' + data.AdoClientes[indice].Cajero + '</option>');
        }
      }
    });
  }

  // construye la tabla con los datos procesados
  function construirTabla(datos, tablaId) {
    $('#'+tablaId).html(datos);
    // obtiene el encabezado de la tabla
    // var encabezado = $("#" + tablaId + " thead tr th");

    // // cuenta el número de columnas en el encabezado
    // var numColumnas = encabezado.length;

    // // crea las filas con los datos
    // var tbody = $("#" + tablaId + " tbody");
    // tbody.empty();
    // for (var i = 0; i < datos.length; i++) {
    //     var fila = $("<tr>");
    //     for (var j = 0; j < numColumnas; j++) {
    //         var nombreColumna = encabezado.eq(j).text();
    //         let valor = datos[i][nombreColumna];           
    //         if(valor !== null && typeof valor === 'object'){
    //           valor = valor.date;
    //           if (valor.endsWith(".000000")) {
    //             valor = valor.slice(0, -7); // Obtiene los primeros 6 caracteres del final
    //           }
    //         }
    //         fila.append($("<td>").text(valor));
    //     }
    //     tbody.append(fila);
    // }
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
        // construirTabla(datos.AdoAsiento, "DGAsiento")  
        // construirTabla(datos.AdoAsiento1, "DGAsiento1") 
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
                            'CheqCajero' : ($('#CheqCajero').prop('checked'))?1:0,
                            'CheqOrdDep' : ($('#CheqOrdDep').prop('checked'))?1:0,
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
                                      if (redondear(datos6.LabelDebe1 - datos6.LabelHaber1, 2) !== 0) {
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
    construirTabla(datos.DGCxC, "DGCxC")

    var AdoCxC = $("#AdoCxC");
        for (var indice in datos.AdoCxC) { //TODO LS que valor se asigna al select??
          AdoCxC.append('<option value="' + datos.AdoCxC[indice].Orden_No+ ' ">' + datos.AdoCxC[indice].Orden_No + '</option>');
        } 

    construirTabla(datos.AdoAsiento, "DGAsiento")  
    construirTabla(datos.AdoAsiento1, "DGAsiento1")
    construirTabla(datos.DGFactAnul, "DGFactAnul")
    construirTabla(datos.DGInv, "DGInv")
    construirTabla(datos.DGProductos, "DGProductos")
    construirTabla(datos.DGVentas, "DGVentas")

    var AdoVentas = $("#AdoVentas");
        for (var indice in datos.AdoVentas) { //TODO LS que valor se asigna al select??
          AdoVentas.append('<option value="' + datos.AdoVentas[indice].Factura+ ' ">' + datos.AdoVentas[indice].Factura + ' - '+datos.AdoVentas[indice].Total_MN+'</option>');
        }

    construirTabla(datos.DGSRI, "DGSRI")

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
    $("#LblConIVA").val(datos.LblConIVA) 
    $("#LblDescuento").val(datos.LblDescuento) 
    $("#LblDiferencia").val(datos.LblDiferencia)
    $("#LblDiferencia1").val(datos.LblDiferencia1)
    $("#LblIVA").val(datos.LblIVA) 
    $("#LblServicio").val(datos.LblServicio) 
    $("#LblSinIVA").val(datos.LblSinIVA) 
    $("#LblTotalFacturado").val(datos.LblTotalFacturado) 
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

  function Grabar_Cierre_DiarioV() {

    Swal.fire({
          title: 'Esta seguro?',
          text: "¿Está seguro de grabar el Cierre de Caja?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {
            $('#myModal_espera').modal('show');
            $.ajax({
              type: "POST",                 
              url: '../controlador/contabilidad/FCierre_CajaC.php?Grabar_Cierre_Diario=true',
              dataType:'json', 
              data: {'MBFechaI' : $("#MBFechaI").val() ,
                    'MBFechaF' : $("#MBFechaF").val(),
                    'CheqCajero' : ($('#CheqCajero').prop('checked'))?1:0,
                    'CheqOrdDep' : ($('#CheqOrdDep').prop('checked'))?1:0,
                    'DCBenef' : $("#DCBenef").val() },
              success: function(datos)             
              {
                if(datos.error){
                  Swal.fire({
                    type: 'warning',
                    title: datos.mensaje,
                    text: ''
                  });
                }
                else{
                  Swal.fire({
                    type: 'success',
                    title: 'Cierre del día '+((datos.dataCierre.MBFechaI)?datos.dataCierre.MBFechaI:"")+((datos.dataCierre.Factura)?"("+datos.dataCierre.Factura+")":""),
                  });

                  if(datos.dataCierre.MBFechaI){
                    $("#MBFechaI").val(datos.dataCierre.MBFechaI)
                    $("#MBFechaF").val(datos.dataCierre.MBFechaI)
                  }
                }

                $('#myModal_espera').modal('hide');
              },
              error: function (e) {
                $('#myModal_espera').modal('hide');
                alert("error inesperado en Grabar_Cierre_Diario")
              }
            });
          }
        })
  }

  function FechaValidaJs(fecha) {
    $.ajax({
      type: "POST",                 
      url: '../controlador/contabilidad/FCierre_CajaC.php?FechaValida=true',
      dataType:'json', 
      data: {'fecha' : fecha },
      success: function(datos)             
      {
        if(datos.ErrorFecha){
          Swal.fire({
            type: 'warning',
            title: datos.MsgBox,
            text: fecha
          });
        }
      }
    });
  }

  function IESS_Cierre_DiarioV(){
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/contabilidad/FCierre_CajaC.php?IESS_Cierre_Diario=true',
      dataType:'json', 
      data: {'MBFechaI' : $("#MBFechaI").val() ,
            'MBFechaF' : $("#MBFechaF").val()},
      success: function(datos)             
      {
        if(datos.rps){
          Swal.fire({
            type: 'success',
            title: datos.mensaje,
            html: "<a class='btn btn-xs btn-warning' onclick=\"descargarArchivo('"+datos.nombre_archivo+"', '../.."+datos.ruta+"')\"><i class='fa fa-download' aria-hidden='true'></i> Descargar Archivo</a>"
          });
        }else{
          Swal.fire({
            type: 'warning',
            title: datos.mensaje
          });
        }
        $('#myModal_espera').modal('hide');
      },
      error: function (e) {
        $('#myModal_espera').modal('hide');
        alert("error inesperado en IESS_Cierre_DiarioV")
      }
    });
  }

  function SolicitarReactivar()
  {
    if($("#MBFechaI").val() !="" && $("#MBFechaF").val()!="")
    {
      $('#clave_contador').modal('show');
      $('#titulo_clave').text('Contador General');
      $('#TipoSuper').val('Contador');
    }else
    {
      Swal.fire('Seleccione las fechas','','info');
    }
  }

  // funcion de respuesta para la clave
   function resp_clave_ingreso(response)
   {
     if(response['respuesta']==1)
     {
       ReactivarV()
     }else{
        Swal.fire({
          type: 'warning',
          title: response['msj']
        });
     }
   }

  function ReactivarV() {
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/contabilidad/FCierre_CajaC.php?Reactivar=true',
      dataType:'json', 
      data: {'MBFechaI' : $("#MBFechaI").val() ,
            'MBFechaF' : $("#MBFechaF").val()},
      success: function(datos)             
      {
        Swal.fire({
          type: (datos.rps)?'success':'warning',
          title: datos.mensaje
        });

        if(datos.rps){
          if(datos.CierreDelDia && datos.CierreDelDia.MBFechaI){
            $("#MBFechaI").val(datos.CierreDelDia.MBFechaI)
            $("#MBFechaF").val(datos.CierreDelDia.MBFechaF)
          }

          construirTabla(datos.AdoAsiento, "DGAsiento")  
          construirTabla(datos.AdoAsiento1, "DGAsiento1")
          
        }
        $("#LabelDebe").val('0')
        $("#LabelHaber").val('0')
        $('#myModal_espera').modal('hide');
      },
      error: function (e) {
        $('#myModal_espera').modal('hide');
        alert("error inesperado en IESS_Cierre_DiarioV")
      }
    });
  }

  function GenerarExcelResultadoCierreCaja() {
    var activeTabHref = $('.nav-tabs .nav-item.active a').attr('href');
    var activeTabTitle = $('.nav-tabs .nav-item.active a').text();
    var activeTabName = activeTabHref.substring(1);
    var url, tabName, secondTabUrl;
    var Titulo;

    switch (activeTabName) {
      case "AdoCxCT":
        secondTabUrl = "AdoAnticipos&Titulo=Anticipos";
        Titulo = "Anticipos";
        break;

      case "AdoInv":
        secondTabUrl = "AdoProductos&Titulo=Productos";
        Titulo = "Productos";
        break;

      case "AdoAsientoT":
        secondTabUrl = "AdoAsiento1T&Titulo=Caja de CxC";
        Titulo = "Caja de CxC";
        break;
    }

    url = `../controlador/contabilidad/FCierre_CajaC.php?ExcelResultadoCierreCaja=true&Tabs=${activeTabName}&Titulo=${activeTabTitle}`;
    console.log(url);
    window.open(url, '_blank');

    if (secondTabUrl) {
        url = `../controlador/contabilidad/FCierre_CajaC.php?ExcelResultadoCierreCaja=true&Tabs=${secondTabUrl}`;
        console.log(url);
        
        $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
          responseType: 'blob' // Especificamos que la respuesta será un Blob
        },
        success: function(response) {console.log(response)
          // Crear un enlace para descargar el archivo
          const downloadLink = document.createElement('a');
          downloadLink.href = URL.createObjectURL(response);
          downloadLink.download = 'Cierre de Caja '+Titulo+' .xlsx'; // Nombre del archivo a descargar
          downloadLink.click();
        },
        error: function(xhr, status, error) {
          console.error('Error al descargar el archivo:', error);
        }
      });
    }
  }
</script> 