
<?php
  date_default_timezone_set('America/Guayaquil');
?>
<script type="text/javascript">
  $(document).ready(function() {
    limpiar_grid();
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
      $('#txt_tc').text(data.tdCliente);
      console.log(data);
    });
  });

   function calcular_totales(){
    var TextVUnit = parseFloat($("#preciounitario").val());
    var TextCant = parseFloat($("#cantidad").val());
    if(TextCant==0 || TextCant =='')
    {
      $("#cantidad").val(1);
    }
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
            DCSerieGR_LostFocus();
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
                $('#LblAutGuiaRem_').val(data['Auto']);
           
        }
        // success: function(response) {
        //     console.log(response);
        //     $('#LblGuiaR_').val(response[0]['Guia']);
        // }
    })

}


function validar_datos()
{
    producto = $("#producto").val();
    if(producto=='')
    {
      Swal.fire('Seleccione un producto','','info').then(function()
        {
           $('#producto').select2('focus');
        });
      return false;
    }
    cliente = $("#cliente").val();
    if(cliente=='')
    {
      Swal.fire('Seleccione un cliente','','info').then(function()
        {
          $('#cliente').select2('focus');
        });
      return false;
    }
    ciudad1 = $('#DCCiudadI').val();
    if(ciudad1=='')
    {
       Swal.fire('Seleccione Ciudad de inicio','','info').then(function()
        {
            $('#DCCiudadI').select2('focus');
        });
       return false;
    }   
    ciudad2 = $('#DCCiudadF').val();
    if(ciudad2=='')
    {
       Swal.fire('Seleccione Ciudad de Fin','','info').then(function()
        {
            $('#DCCiudadF').select2('focus');
        });
       return false;
    }   

    DCRazonSocial = $('#DCRazonSocial').val();
    if(DCRazonSocial=='')
    {
       Swal.fire('Seleccione Transportista','','info').then(function()
        {
           // $('#DCRazonSocial').select();
            $('#DCRazonSocial').select2('focus');
        });
       return false;
    }   
    DCEmpresaEntrega = $('#DCEmpresaEntrega').val();
    if(DCEmpresaEntrega=='')
    {
       Swal.fire('Seleccione empresa Transportista','','info').then(function()
        {
            $('#DCEmpresaEntrega').select2('focus');
           // $('#DCEmpresaEntrega').select();
        });
       return false;
    }   
    TxtPlaca = $('#TxtPlaca').val();
    if(TxtPlaca=='')
    {
       Swal.fire('Ingrese un numero de placa','','info').then(function()
        {
           $('#TxtPlaca').select();
        });
       return false;
    }   
    TxtPedido = $('#TxtPedido').val();
    if(TxtPedido=='')
    {
       Swal.fire('Ingrese un numero de pedido','','info').then(function()
        {
           $('#TxtPedido').select();
        });
       return false;
    }   
    TxtZona_ = $('#TxtZona_').val();
    if(TxtZona_=='')
    {
       Swal.fire('Ingrese una Zona','','info').then(function()
        {
           $('#TxtZona_').select();
        });
       return false;
    }   
    TxtLugarEntrega = $('#TxtLugarEntrega').val();
    if(TxtLugarEntrega=='')
    {
       Swal.fire('Ingrese un lugar de entrega','','info').then(function()
        {
           $('#TxtLugarEntrega').select();
        });
       return false;
    }   
    aceptar();
}



function aceptar(){
   
    var year = new Date().getFullYear();
    producto = $("#producto").val();
    productoDes = $("#producto option:selected").text();
    cliente = $("#cliente").val();
    codigoCliente = $("#codigoCliente").val();
    // $('#myModal_espera').modal('show');
    parametros = $('#form_guia').serialize();
    var lineas = 
    {
        'Producto' : productoDes,
        'productoCod':producto,
        'Precio' :$('#preciounitario').val(),
        'Total_Desc' : 0,
        'Total_Desc2' : 0,
        'Iva' : 0,
        'Total':$('#total').val(),
        'MiMes': '',
        'Periodo' :year,
        'Cantidad' :$('#cantidad').val(),
        'codigoCliente':codigoCliente,
    }
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/lista_guia_remisionC.php?guardarLineas=true&'+parametros+'&T='+$('#txt_tc').text(),
      data: {lineas:lineas}, 
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
      url: '../controlador/facturacion/lista_guia_remisionC.php?cargarLineas=true',
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
        'fecha': $('#MBoxFechaGRE').val(),
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
                }

            }
        }
    });

}
function Eliminar(cod)
{
     $.ajax({
      url:'../controlador/facturacion/lista_guia_remisionC.php?Eliminar=true',
      type:'post',
      dataType:'json',
      data:{cod:cod},
      success: function(response){
        cargar_grilla()  
       }
    });
}
  function limpiar_grid()
  {   
     $.ajax({
      url:'../controlador/facturacion/lista_guia_remisionC.php?limpiar_grid=true',
      type:'post',
      dataType:'json',
      // data:{idpro:idpro},
      success: function(response){
        cargar_grilla(); 
     
    }
    });
  }

 function guardarFactura(){


     producto = $("#producto").val();
    if(producto=='')
    {
      Swal.fire('Seleccione un producto','','info').then(function()
        {
           $('#producto').select2('focus');
        });
      return false;
    }
    cliente = $("#cliente").val();
    if(cliente=='')
    {
      Swal.fire('Seleccione un cliente','','info').then(function()
        {
          $('#cliente').select2('focus');
        });
      return false;
    }
    ciudad1 = $('#DCCiudadI').val();
    if(ciudad1=='')
    {
       Swal.fire('Seleccione Ciudad de inicio','','info').then(function()
        {
            $('#DCCiudadI').select2('focus');
        });
       return false;
    }   
    ciudad2 = $('#DCCiudadF').val();
    if(ciudad2=='')
    {
       Swal.fire('Seleccione Ciudad de Fin','','info').then(function()
        {
            $('#DCCiudadF').select2('focus');
        });
       return false;
    }   

    DCRazonSocial = $('#DCRazonSocial').val();
    if(DCRazonSocial=='')
    {
       Swal.fire('Seleccione Transportista','','info').then(function()
        {
           // $('#DCRazonSocial').select();
            $('#DCRazonSocial').select2('focus');
        });
       return false;
    }   
    DCEmpresaEntrega = $('#DCEmpresaEntrega').val();
    if(DCEmpresaEntrega=='')
    {
       Swal.fire('Seleccione empresa Transportista','','info').then(function()
        {
            $('#DCEmpresaEntrega').select2('focus');
           // $('#DCEmpresaEntrega').select();
        });
       return false;
    }   
    TxtPlaca = $('#TxtPlaca').val();
    if(TxtPlaca=='')
    {
       Swal.fire('Ingrese un numero de placa','','info').then(function()
        {
           $('#TxtPlaca').select();
        });
       return false;
    }   
    TxtPedido = $('#TxtPedido').val();
    if(TxtPedido=='')
    {
       Swal.fire('Ingrese un numero de pedido','','info').then(function()
        {
           $('#TxtPedido').select();
        });
       return false;
    }   
    TxtZona_ = $('#TxtZona_').val();
    if(TxtZona_=='')
    {
       Swal.fire('Ingrese una Zona','','info').then(function()
        {
           $('#TxtZona_').select();
        });
       return false;
    }   
    TxtLugarEntrega = $('#TxtLugarEntrega').val();
    if(TxtLugarEntrega=='')
    {
       Swal.fire('Ingrese un lugar de entrega','','info').then(function()
        {
           $('#TxtLugarEntrega').select();
        });
       return false;
    } 

    // $('#myModal_espera').modal('show');

    parametros = $('#form_guia').serialize();
    parametros = parametros+'&Comercial='+$('#DCRazonSocial option:selected').text()+'&Entrega='+$('#DCEmpresaEntrega option:selected').text();
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/lista_guia_remisionC.php?guardarFactura=true',
        dataType: 'json',
        data: parametros, 
        success: function(response)
        {
          console.log(response);
          // $('#myModal_espera').modal('hide');
          cargar_grilla();
            if(response == 1)
              {
                 Swal.fire({
                  type: 'success',
                  title: 'Documento electronico autorizado',
                  allowOutsideClick: false,
                }).then(function(){
                 //window.open(url,'_blank');                      
                  location.reload();
                  // imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                });
              }else if(response==2)
              {
                Swal.fire({
                  type: 'info',
                  title: 'XML devuelto',
                  allowOutsideClick: false,
                }).then(() => {
                  serie = DCLinea.split(" ");
                  cambio = $("#cambio").val();
                  efectivo = $("#efectivo").val();
                  var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                  window.open(url,'_blank');
                  location.reload();
                  //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                });
                //descargar_archivos(response.url,response.ar);

              }else if(response == 4)
              {
                 Swal.fire({
                  type: 'success',
                  title: 'Factura guardada',
                  allowOutsideClick: false,
                }).then(() => {
                  serie = DCLinea.split(" ");
                  cambio = $("#cambio").val();
                  efectivo = $("#efectivo").val();
                  var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                  window.open(url,'_blank');
                  location.reload();
                  //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                });
              }else if(response==5)
              {
                Swal.fire({
                  type: 'error',
                  title: 'Numero de documento repetido se recargara la pagina para colocar el numero correcto',
                  // text:''
                  allowOutsideClick: false,
                }).then(function(){
                  location.reload();
                })
              }
              else
              {
                Swal.fire({
                  type: 'error',
                  title: 'XML NO AUTORIZADO',
                  allowOutsideClick: false,
                })
              }              
        }
    });
  }

</script>
<style>
    .select2-container *:focus {
        outline: solid 1px !important;
    }
  </style>
<div class="row">
  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
      <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>"
          title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/salire.png">
      </a>
  </div>
</div>

 <div class="row">
  <form id="form_guia">
  <div class="col-sm-2">
    <b>Fecha de emision de guia</b>
    <input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus();">
  </div>
    <div class="col-sm-5 col-xs-12">
    <b>Cliente</b>
   <div class="input-group">
      <select class="form-control input-xs" id="cliente" name="cliente">
        <option value="">Seleccione un cliente</option>
      </select>
      <span class="input-group-btn">
        <button type="button" class="btn btn-default btn-xs btn-flat" disabled >
           <span class="fa" id="txt_tc" style="color:coral;" name="txt_tc">-</span>
        </button>
      </span>
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
    <input type="text" class="form-control input-xs" placeholder="Email" name="email" id="email" readonly>            
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
</form>
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
    <input type="text" name="total" id="total" value="0.00" class="form-control input-xs text-right" readonly onblur="validar_datos()">
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