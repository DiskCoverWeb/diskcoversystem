
<?php
  include "../controlador/facturacion/divisasC.php";
  $divisas = new divisasC();

  // print_r($_SESSION);die();
?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#MBoxFechaGRE').select();
    autocomplete_cliente()
      DCCiudadI()
      DCCiudadF()
      autocomplete_cliente();
      AdoPersonas()
      DCEmpresaEntrega()
      cargar_grilla()
      productos()

 $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#telefono').val(data.telefono);
      $('#ci_ruc').val(data.ci_ruc);
      $('#codigoCliente').val(data.codigo);
      $('#celular').val(data.celular);
      console.log(data);
    });
  });

   function calcular_totales(){
    var TextVUnit = parseFloat($("#preciounitario").val());
    var TextCant = parseFloat($("#cantidad").val());
    var producto = $("#producto").val();
    if (TextVUnit <= 0) {
      $("#preciounitario").val(1);
    }
    var TextVTotal = TextVUnit*TextCant;
     
    $("#total").val(parseFloat(TextVTotal).toFixed(4));
  }

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/divisasC.php?cliente=true',
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

  function AdoPersonas() {
    $('#DCRazonSocial').select2({
        placeholder: 'Seleccione un Grupo',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?AdoPersonas=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function DCEmpresaEntrega() {
    $('#DCEmpresaEntrega').select2({
        placeholder: 'Seleccione la Empresa',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCEmpresaEntrega=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}




  function DCCiudadI() {
    $('#DCCiudadI').select2({
        placeholder: 'Seleccione la ciudad',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadI=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function DCCiudadF() {
    $('#DCCiudadF').select2({
        placeholder: 'Seleccione la ciudad',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadF=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}


  function MBoxFechaGRE_LostFocus() {
    var parametros = {
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?MBoxFechaGRE_LostFocus=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                llenarComboList(data, 'DCSerieGR');
            }

        }
    })
}
  
  function DCSerieGR_LostFocus() {
    var DCserie = $('#DCSerieGR').val();
    serie = DCserie.split('_');
    var parametros = {
        'DCSerieGR': serie[1],
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCSerieGR_LostFocus=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
           
                //llenarComboList(data, 'DCSerieGR');
                $('#LblGuiaR_').val(data['Guia']);
                $('#LblAutGuiaRem').val(data['Auto']);
           
        }
        // success: function(response) {
        //     console.log(response);
        //     $('#LblGuiaR_').val(response[0]['Guia']);
        // }
    })

}



function aceptar(){
    producto = $("#producto").val();
    productoDes = $("#producto option:selected").text();
    cliente = $("#cliente").val();
    if(cliente=='')
    {
      Swal.fire('Seleccione un cliente','','info');
      return false;
    }

     
    pvp = $("#preciounitario").val();
    total = $("#total").val();
    cantidad = $("#cantidad").val();
    if(cantidad==0 || cantidad=='')
    {
      Swal.fire('Cantidad no valida','','info');
      return false;
    }
    var year = new Date().getFullYear();
    $('#myModal_espera').modal('show');
    var datosLineas = 
    {
        'Codigo' : producto,
        'CodigoL' : producto[0],
        'Producto' : productoDes,
        'Precio' :pvp,
        'Total_Desc' : 0,
        'Total_Desc2' : 0,
        'Iva' : 0,
        'Total':total,
        'MiMes': '',
        'Periodo' :year,
        'Cantidad' :cantidad,
    }
    codigoCliente = $("#codigoCliente").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/lista_guia_remisionC.php?guardarLineas=true',
      data: {
        'codigoCliente' : codigoCliente,
        'datos' : datosLineas,
      }, 
      success: function(data)
      {
        cargar_grilla();
      }
    });
  }


  function cargar_grilla()
  {
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/divisasC.php?cargarLineas=true',
      dataType: 'json',
      success: function(data)
      {
        $('#tbl_divisas').html(data.tbl);   
        $("#total0").val(parseFloat(data.total).toFixed(2));
        $("#totalFac").val(parseFloat(data.total).toFixed(2));
        $("#efectivo").val(parseFloat(data.total).toFixed(2));
        $('#myModal_espera').modal('hide');
      }
    });
  }

  function productos(){
    $('#producto').select2({
        placeholder: 'Seleccione un Producto',
        ajax: {
             url: '../controlador/facturacion/divisasC.php?productos2=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
  }


  function Articulo_Seleccionado() {
    var parametros = {
        'codigo': $('#producto').val(),
        'fecha': $('#fecha').val(),
        'CodBod': '1',
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?ArtSelec=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if (data.respueta == true) {
                if (data.datos.Stock < 0) {
                    Swal.fire(data.datos.Producto + ' ES UN PRODUCTO SIN EXISTENCIA', '', 'info').then(
                        function() {
                            $('#producto').empty();
                            // $('#LabelStock').val(0);
                        });

                } else {

                    $('#stock').val(data.datos.Stock);
                    $('#preciounitario').val(data.datos.PVP);
                    $('#LabelStock').focus();

                    // $('#TxtDetalle').val(data.datos.Producto);
                    // $('#').val(data.datos.);


                    // $('#cambiar_nombre').on('shown.bs.modal', function() {
                    //     $('#TxtDetalle').focus();
                    // })

                    // $('#cambiar_nombre').modal('show', function() {
                    //     $('#TxtDetalle').focus();
                    // })

                }

            }
        }
    });

}

</script>
<div class="row">
  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
      <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>"
          title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/salire.png">
      </a>
  </div>
</div>

 <div class="row">
  <div class="col-sm-2">
    <b>Fecha de emision de guia</b>
    <input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus()">
  </div>
    <div class="col-sm-5 col-xs-12">
    <b>Cliente</b>
   <div class="input-group">
      <select class="form-control input-xs" id="cliente" name="cliente">
        <option value="">Seleccione un cliente</option>
      </select>
      <span class="input-group-btn">
        <button type="button" class="btn btn-success btn-xs btn-flat" onclick="addCliente()" title="Nuevo cliente">
          <span class="fa fa-user-plus"></span>
        </button>   
      </span> 
    </div>
    <input type="hidden" name="codigoCliente" id="codigoCliente">
    <input type="hidden" name="direccion" id="direccion">
    <input type="hidden" name="ci" id="ci_ruc">
    <input type="hidden" name="fechaEmision" id="fechaEmision" value="<?php echo date('Y-m-d'); ?>">
  </div>
  <div class="col-sm-3">
    <b>Email:</b>
    <input type="text" class="form-control input-xs" placeholder="Email" name="email" id="email" onblur="validador_correo()">            
  </div>
  <div class="col-sm-2">
    <b>Telefono:</b>
    <input type="text" class="form-control input-xs" placeholder="Telefono" name="telefono" id="telefono">            
  </div> 
  <div class="col-sm-4">
	<b>Guia de remision No.</b><br>
    <select class="form-control input-xs" id="DCSerieGR" name="DCSerieGR" onblur="DCSerieGR_LostFocus()">
       	<option value="">No Existe</option>
  	</select>
  </div>
  <div class="col-sm-2" style="padding: 0px">
  	<b>Numero</b>
    <input type="text" name="LblGuiaR_" id="LblGuiaR_" class="form-control input-xs"  value="000000">
  </div>
    <div class="col-sm-6">
	  <b>AUTORIZACION GUIA DE REMISION</b>
	  <input type="text" name="LblAutGuiaRem_" id="LblAutGuiaRem_" class="form-control input-xs" value="0">
	</div>
  <div class="col-sm-6">
      <b class="col-sm-6 control-label" style="padding: 0px">Iniciacion del traslados</b>
      <div class="col-sm-6" style="padding: 0px">
          <input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>">
      </div>
  </div>
  <div class="col-sm-6">
      <b class="col-sm-3 control-label" style="padding: 0px">Ciudad</b>
      <div class="col-sm-9" style="padding: 0px">
          <select class="form-control input-xs" id="DCCiudadI" name="DCCiudadI" style="width:100%">
              <option value=""></option>
          </select>
      </div>
  </div>
  <div class="col-sm-6">
      <b class="col-sm-6 control-label" style="padding: 0px">Finalizacion del traslados</b>
      <div class="col-sm-6" style="padding: 0px">
          <input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control input-xs"
              value="<?php echo date('Y-m-d'); ?>">
      </div>
  </div>
  <div class="col-sm-6">
      <b class="col-sm-3 control-label" style="padding: 0px">ciudad</b>
      <div class="col-sm-9" style="padding: 0px">
          <select class="form-control input-xs" id="DCCiudadF" name="DCCiudadF" style="width:100%">
              <option value=""></option>
          </select>
      </div>
  </div>
  <div class="col-sm-6">
      <b>Nombre o razon socila (Transportista)</b>
      <select class="form-control input-xs" id="DCRazonSocial" name="DCRazonSocial" style="width:100%">
          <option value=""></option>
      </select>
  </div>
  <div class="col-sm-6">
      <b>Empresa de Transporte</b>
      <select class="form-control input-xs" id="DCEmpresaEntrega" name="DCEmpresaEntrega" style="width:100%">
          <option value=""></option>
      </select>
  </div>
  <div class="col-sm-2">
      <b>Placa</b>
      <input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control input-xs"
          value="XXX-999">
  </div>
  <div class="col-sm-2">
      <b>Pedido</b>
      <input type="text" name="TxtPedido" id="TxtPedido" class="form-control input-xs">
  </div>
  <div class="col-sm-2">
      <b>Zona</b>
      <input type="text" name="TxtZona_" id="TxtZona_" class="form-control input-xs">
  </div>
  <div class="col-sm-6">
      <b>Lugar entrega</b>
      <input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control input-xs">
  </div>
</div>


<!--
<div class="row">
  <div class="col-sm-2">            
    <label>Fecha</label>
    <input type="date" class="form-control input-xs" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" onchange="numeroFactura();catalogoLineas();">
  </div>

</div>


<div class="row">
  <div class="col-sm-2 col-sm-offset-1">
    <label class="text-right">TIPO DE PROCESO</label>
  </div>
  <div class="col-sm-4">
    <select class="form-control input-xs" name="DCLinea" id="DCLinea" onchange="numeroFactura();productos();limpiar_grid(); cargar_grilla();">
    </select>
  </div>
  <div class="col-sm-3">
    <label id="numeroSerie" class="red">() No.</label>
  </div>
  <div class="col-sm-2">
    <input type="text" name="factura" id="factura" value="1" class="form-control input-xs text-right">
  </div>
</div>

-->
<div class="row">
  <div class="col-sm-6">
    <label>PRODUCTO</label>
    <select class="form-control input-xs" id="producto" onchange="Articulo_Seleccionado();">
    </select>
  </div>
  <div class="col-sm-1">
    <label>Stock</label>
    <input type="text" name="stock" id="stock" value="0.00" class="form-control input-xs text-right" readonly>
  </div>
  <div class="col-sm-1">
    <label>Cant</label>
    <input type="text" name="cantidad" id="cantidad" value="0.00" class="form-control input-xs text-right" onblur="calcular_totales()">
  </div>
  <div class="col-sm-1">
    <label>PVP</label>
    <input type="text" name="preciounitario" id="preciounitario" value="0.00" class="form-control input-xs text-right" onblur="calcular_totales()">
  </div>
  <div class="col-sm-1">
    <label>Total</label>
    <input type="text" name="total" id="total" value="0.00" class="form-control input-xs text-right" readonly onblur="aceptar()">
  </div>
   <!-- <div class=" col-sm-1 text-right">     <br>
      <a title="Aprobar" class="btn btn-default btn-block"  onclick="calcular_totales();aceptar();">
        <img src="../../img/png/mostrar.png" width="25">
      </a>     
  </div> -->
</div>

<br>
<div class="row">         
  <div class="col-sm-9" id="tbl_divisas" style="height:300px">
   
  </div>
  <div class="col-sm-3">
    <div class="row">
      <div class="col-sm-6">
        <label>Total Tarifa 0%</label>
      </div>
      <div class="col-sm-6">
        <input type="text" name="total0" id="total0" class="form-control input-xs red text-right" value="0.00" readonly>
      </div>              
    </div>
    <div class="row">
       <div class="col-sm-6">
          <label>Total Tarifa 12%</label>
        </div>
        <div class="col-sm-6">
          <input type="text" name="total12" id="total12" class="form-control input-xs red text-right" value="0.00" readonly>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>I.V.A. 12%</label>
      </div>
      <div class="col-sm-6">
        <input type="text" name="iva12" id="iva12" class="form-control input-xs red text-right" value="0.00" readonly>
      </div>                  
    </div>
    <div class="row">
      <div class="col-sm-6">
        <label>Total Fact. (ME)</label>
      </div>
      <div class="col-sm-6">
        <input type="text" name="totalFacMe" id="totalFacMe" class="form-control input-xs red text-right" value="0.00" readonly>
      </div>    
    </div>
    <div class="row">
       <div class="col-sm-6">
        <label>Total Factura</label>
      </div>
      <div class="col-sm-6">
        <input type="text" name="totalFac" id="totalFac" class="form-control input-xs red text-right" value="0.00" readonly>
      </div>              
    </div>
    <div class="row">
       <div class="col-sm-6">
          <label>EFECTIVO</label>
        </div>
        <div class="col-sm-6">
          <input type="text" name="efectivo" id="efectivo" class="form-control input-xs red text-right" value="0.00" onkeyup="calcularSaldo();" readonly>
        </div>              
    </div>
     <div class="row">
        <div class="col-sm-6">
          <label>Cambio</label>
        </div>
        <div class="col-sm-6">
          <input type="text" name="cambio" id="cambio" class="form-control input-xs red text-right" value="0.00">
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <a title="Guardar" class="btn btn-default btn-block">
            <img src="../../img/png/save.png" width="25" height="30" onclick="guardarFactura();">
          </a>
        </div>
      </div>
  </div>
</div>
        <br>
      

  <div class="row">
    <input type="hidden" name="" id="txt_pag" value="0">
    <div id="tbl_pag"></div>    
  </div>
</div>

<!-- Modal cliente nuevo -->
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 <!-- Fin Modal cliente nuevo-->

 <!-- buscar y reimprimir -->
<div id="reimprimir" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comprobantes procesados</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-sm-8">
             <b>Nombre Cliente / CI / RUC</b>
             <input type="text" name="txt_buscar" id="txt_buscar" class="form-control input-xs" placeholder="Nombre - CI - RUC " onkeyup="cargar_facs()">
           </div>
            <div class="col-sm-4">
             <b>Numero de comprobante</b>
             <input type="text" name="txt_fac" id="txt_fac" class="form-control input-xs" placeholder="Numero comprobante" onkeyup="cargar_facs()">
           </div>
         </div>
           <br>
         <div class="row">
           <div class="col-sm-12">
            <div id="re_frame" style="display: none;">
              
            </div>
            <div id="tbl_fac" style="overflow-y:auto;height: 300px;">
              
            </div>
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 <!-- Fin Modal cliente nuevo-->