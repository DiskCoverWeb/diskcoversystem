
<?php
  include "../controlador/facturacion/divisasC.php";
  $divisas = new divisasC();

  // print_r($_SESSION);die();
?>
<style type="text/css">
  @media (max-width:600px) {
    .input-width{
      width: 80px;
    }
  }
</style>

<script type="text/javascript">
  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("").focus();
      e.preventDefault();
    }
  });
  $(document).ready(function () {    
     $("#total").blur(function () { $('#btn_cal').trigger( "focus" );});
     $("#cantidad").blur(function () { $('#btn_cal').trigger( "focus" );});
    limpiar_grid();
    cargar_grilla();
    catalogoLineas();
    autocomplete_cliente();
    provincias();
    $("#nombreCliente").hide();
    //enviar datos del cliente
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

  function addCliente(){
    $("#myModal").modal("show");
    var src ="../vista/modales.php?FCliente=true";
     $('#FCliente').attr('src',src).show();
  }

  function setPVP(){
    producto0 = $("#producto").val();
    producto = producto0.split("/");
    $("#preciounitario").val(producto[2]);
    var pro = producto[1].replace('Venta','');
    var pro = pro.replace('Compra','');
    $('#tipo_p').text(pro);
    $("#total").val(0);
    $("#cantidad").val(0);
  }

  function numeroFactura(){
    DCLinea = $("#DCLinea").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
      data: {
        'DCLinea' : DCLinea,
      }, 
      success: function(data)
      {
        datos = JSON.parse(data);
        labelFac = "("+datos.autorizacion+") No. "+datos.serie;
        document.querySelector('#numeroSerie').innerText = labelFac;
        $("#factura").val(datos.codigo);
      }
    });
  }

  function productos(){
    DCLinea = $("#DCLinea").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/divisasC.php?productos=true',
      data: {
        'DCLinea' : DCLinea,
      }, 
      success: function(data)
      {
        if (data) {
          datos = JSON.parse(data);
          llenarComboList(datos,'producto')
          setPVP();
        }else{
          console.log("No tiene datos");
        }  
      }
    });
  }

  function catalogoLineas(){
    fecha = $("#fecha").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/divisasC.php?catalogoLineas=true',
      data: {
        'fecha' : fecha,
      }, 
      success: function(data)
      {
        if (data) {
          datos = JSON.parse(data);
          llenarComboList(datos,'DCLinea')
          numeroFactura();
          productos();
        }else{
          console.log("No tiene datos");
        }  
      }
    });
  }

  function calcular()
  {
    var ti = $('#tipo_cal').val();
    var DCLinea = $("#DCLinea").val();
    var doc = DCLinea.split(' ');
    var doc = doc[0];
    if(ti == 'T')
    {
      calcular_totales(doc);
    }else
    {
      calcular_canti(doc);
    }
  }

  function calcular_canti(doc)
  {
    var TextVUnit = parseFloat($("#preciounitario").val());
    // var TextCant = parseFloat($("#cantidad").val());
    var TextVTotal = parseFloat($("#total").val());
    var producto = $("#producto").val();
    var producto = producto.split("/");
    var Div = producto[3];
    if (TextVUnit <= 0) {
      $("#preciounitario").val(1);
    }
    if(doc=='FA')
    {
     if (Div == "1") {
        TextCant = (TextVTotal / TextVUnit);
      }else{

        TextCant = (TextVTotal * TextVUnit);
      }
    }else
    {
      if (Div == "1") {
        TextCant = (TextVTotal * TextVUnit);
      }else{

        TextCant = (TextVTotal / TextVUnit);
      }

    }
    $("#cantidad").val(parseFloat(TextCant).toFixed(4));
  }

  function calcular_totales(doc){
    var TextVUnit = parseFloat($("#preciounitario").val());
    var TextCant = parseFloat($("#cantidad").val());
    // var TextVTotal = parseFloat($("#total").val());
    var producto = $("#producto").val();
    var producto = producto.split("/");
    var Div = producto[3];
    if (TextVUnit <= 0) {
      $("#preciounitario").val(1);
    }
    if(doc=='FA')
    {

    if (Div == "1") {
        TextVTotal = (TextCant * TextVUnit);
      }else{
        TextVTotal = (TextCant / TextVUnit);
      }
    }else
    {
      if (Div == "1") {
        TextVTotal = (TextCant / TextVUnit);
      }else{
        TextVTotal = (TextCant * TextVUnit);
      }

    }
      $("#total").val(parseFloat(TextVTotal).toFixed(4));
  
  }

   function calcular1(){
    TextVUnit = Number.parseFloat($("#preciounitario").val());
    TextCant = Number.parseFloat($("#cantidad").val());
    TextVTotal = Number.parseFloat($("#total").val());
    producto = $("#producto").val();
    producto = producto.split("/");
    Div = producto[3];
    if (TextVUnit <= 0) {
      $("#preciounitario").val(1);
    }
    if (TextVTotal > 0 && TextCant == 0) {
      if (Div == "1") {
        TextCant = (TextVTotal / TextVUnit);
      }else{
        TextCant = (TextVTotal * TextVUnit);
      }
      $("#cantidad").val(parseFloat(TextCant).toFixed(4));
    }else if(TextCant > 0 && TextVTotal == 0){
      if (Div == "1") {
        TextVTotal = (TextCant / TextVUnit);
      }else{
        TextVTotal = (TextCant * TextVUnit);
      }
      $("#total").val(parseFloat(TextVTotal).toFixed(4));
    }
  }

 

function aceptar(){
    producto = $("#producto").val();
    cliente = $("#cliente").val();
    if(cliente=='')
    {
      Swal.fire('Seleccione un cliente','','info');
      return false;
    }
    pvp = $("#preciounitario").val();
    total = $("#total").val();
    cantidad = $("#cantidad").val();
    producto = producto.split("/");
    var year = new Date().getFullYear();
    $('#myModal_espera').modal('show');
    var datosLineas = 
    {
        'Codigo' : producto[0],
        'CodigoL' : producto[0],
        'Producto' : producto[1],
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
      url: '../controlador/facturacion/divisasC.php?guardarLineas=true',
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

  function calcularTotal(){
    var table = document.getElementById('customers');
    var rowLength = table.rows.length;
    total = 0;
    for(var i=1; i<rowLength; i++){
      total += parseFloat($("#total"+i).val());
    }
    $("#total0").val(parseFloat(total).toFixed(4));
    $("#totalFac").val(parseFloat(total).toFixed(4));
    $("#efectivo").val(parseFloat(total).toFixed(4));
  }

  function guardarFactura(){
    
    
    validarDatos = $("#total").val();
    totalFac = $("#totalFac").val();
    codigoCliente = $("#codigoCliente").val();
    DCLinea = $("#DCLinea").val();
    TextCI = $("#ci_ruc").val();
    tipoFactura = DCLinea.split(" ");
    console.log(tipoFactura[0]);
    if (tipoFactura[0] == 'LC' && TextCI == '9999999999999') {
      Swal.fire({
        type: 'info',
        title: 'En liquidación de compras no puede elegir consumidor final',
        text: ''
      });
    }else if (codigoCliente == '' ) {
      Swal.fire({
        type: 'info',
        title: 'Ingrese el cliente para la factura',
        text: ''
      });
    }else if (totalFac <= 0 ) {
      Swal.fire({
        type: 'info',
        title: 'Ingrese una o más lineas para generar la factura',
        text: ''
      });
    }else{
        TextRepresentante = $("#cliente").val();;
        TxtDireccion = $("#direccion").val();
        TxtTelefono = null;
        TextFacturaNo = $("#factura").val();
        TxtGrupo = null;
        TD_Rep = $("#ci_ruc").val();
        TxtEmail = $("#email").val();
        TxtDirS = $("#direccion").val();
        TextCheque = null;
        DCBanco = null;
        chequeNo = $("#chequeNo").val();
        TxtEfectivo = $("#efectivo").val();
        TxtNC = null;
        DCNC = null;
        Fecha = $("#fechaEmision").val();
        Total = $("#total").val();
        
        //var confirmar = confirm("Esta seguro que desea guardar \n La factura No."+TextFacturaNo);
        if(validador_correo()==false)
          {
            return false;
            }


        Swal.fire({
          title: 'Esta seguro?',
          text: "Esta seguro que desea guardar \n La factura No."+TextFacturaNo,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {

            guardar_datoscliente();
            $('#myModal_espera').modal('show');
            $.ajax({
            type: "POST",
            url: '../controlador/facturacion/divisasC.php?guardarFactura=true',
            data: {
              'DCLinea' : DCLinea,
              'Total' : Total,
              'TextRepresentante' : TextRepresentante,
              'TxtDireccion' : TxtDireccion,
              'TxtTelefono' : TxtTelefono,
              'TextFacturaNo' : TextFacturaNo,
              'TxtGrupo' : TxtGrupo,
              'chequeNo' : chequeNo,
              'TextCI' : TextCI,
              'TD_Rep' : TD_Rep,
              'TxtEmail' : TxtEmail,
              'TxtDirS' : TxtDirS,
              'codigoCliente' : codigoCliente,
              'TextCheque' : TextCheque,
              'DCBanco' : DCBanco,
              'TxtEfectivo' : TxtEfectivo,
              'TxtNC' : TxtNC,
              'Fecha' : Fecha,
              'DCNC' : DCNC, 
            }, 
            success: function(response)
            {
              
              $('#myModal_espera').modal('hide');
              cargar_grilla();
              if (response) {

                response = JSON.parse(response);
                if(response.respuesta == '3')
                {
                  Swal.fire({
                       type: 'error',
                       title: 'Este documento electronico ya esta autorizado',
                       text: ''
                     });

                  }else if(response.respuesta == '1')
                  {
                    limpiar_grid();
                     serie = DCLinea.split(" ");
                     cambio = $("#cambio").val();
                     efectivo = $("#efectivo").val();  
                     var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                     imprimir(url); 

                    Swal.fire({
                      type: 'success',
                      title: 'Este documento electronico fue autorizado',
                      text: ''
                    }).then(() => {
                                      
                      // var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                      window.open(url,'_blank');                      
                      location.reload();
                      // imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
                  }else if(response.respuesta == '2')
                  {
                    Swal.fire({
                       type: 'info',
                       title: 'XML devuelto',
                       text: ''
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

                  }else if(response.respuesta == '4')
                  {
                    Swal.fire({
                      type: 'success',
                      title: 'Factura guardada correctamente',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      cambio = $("#cambio").val();
                      efectivo = $("#efectivo").val();
                      var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
                  }
                  else
                  {
                    Swal.fire({
                       type: 'info',
                       title: 'Error por: '+response,
                       text: ''
                     });

                  }
              }else{
                Swal.fire({
                  type: 'info',
                  title: 'La factura ya se autorizo',
                  text: ''
                });
              }
            }
            });
          }
        })
    }
    
  }


  function guardar_datoscliente()
  {
    var tel = $('#telefono').val();
    var ema = $('#email').val();
    var cod = $('#codigoCliente').val();
    var parametros = 
    {
      'tel':tel,
      'ema':ema,
      'cod':cod,
    }
    $.ajax({
      url: '../controlador/facturacion/divisasC.php?guardar_datoC=true',
      type:'post',
      dataType:'json',
      data:{parametros:parametros},     
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#select_provincias').html(option);
    }
    });

  }

  function calcularSaldo(){
    efectivo = $("#efectivo").val();
    total = $("#totalFac").val();
    saldo = efectivo - total;
    $("#cambio").val(parseFloat(saldo).toFixed(4));
  }

  function provincias()
  {
   var option ="<option value=''>Seleccione provincia</option>"; 
     $.ajax({
      url: '../controlador/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#select_provincias').html(option);
    }
    });

  }

  function ciudad(idpro)
  {
    var option ="<option value=''>Seleccione ciudad</option>"; 
    //var idpro = $('#select_provincias').val();
    if(idpro !='')
    {
     $.ajax({
      url: '../controlador/detalle_estudianteC.php?ciudad=true',
      type:'post',
      dataType:'json',
      data:{idpro:idpro},
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#select_ciudad').html(option);
    }
    });
   } 

  }

  function limpiar_grid()
  {   
     $.ajax({
      url:'../controlador/facturacion/divisasC.php?limpiar_grid=true',
      type:'post',
      dataType:'json',
      // data:{idpro:idpro},
      success: function(response){
        cargar_grilla(); 
     
    }
    });
  }

  function Eliminar(cod)
  {
     $.ajax({
      url:'../controlador/facturacion/divisasC.php?Eliminar=true',
      type:'post',
      dataType:'json',
      data:{cod:cod},
      success: function(response){
        cargar_grilla()  
       }
    });
  }

  function imprimir(url)
  {
    // var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac=116&serie=001001&CI=1717098667&TC=FA&efectivo=0.0000&saldo=0.00&pdf=no';
     var html='<iframe style="width:100%; height:50vw;" src="'+url+'&pdf=no" frameborder="0" allowfullscreen id="ticket"></iframe>';
    $('#contenido').html(html);
    document.getElementById('ticket').contentWindow.print();
                     
  }

  function modal_reimprimir()
  {
    $('#reimprimir').modal('show');
    cargar_facs();
   
  }
  function cargar_facs()
  {
     var parametros = 
    {
      'factura':$('#txt_fac').val(),
      'query':$('#txt_buscar').val(),
    }
    $.ajax({
      url:'../controlador/facturacion/divisasC.php?buscar_facturas=true',
      type:'post',
      dataType:'json',
      data:{parametros:parametros},
      success: function(response){
        $('#tbl_fac').html(response);
        // console.log(response);
       }
    });

  }

  function Re_imprimir(fac,serie,ci,tc)
  {
 var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+fac+'&serie='+serie+'&CI='+ci+'&TC='+tc+'&efectivo=0.0000&saldo=0.00&pdf=no';
     var html='<iframe style="width:100%; height:50vw;" src="'+url+'&pdf=no" frameborder="0" allowfullscreen id="re_ticket"></iframe>';
    $('#re_frame').html(html);
    document.getElementById('re_ticket').contentWindow.print();
                     
  }

function validador_correo()
{
    var campo = $('#email').val();   
    var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    //Se muestra un texto a modo de ejemplo, luego va a ser un icono
    if (emailRegex.test(campo)) {
      // alert("válido");
      return true;

    } else {
      Swal.fire('Email incorrecto','','info');
      console.log(campo);
      return false;
    }
}


</script>
<div class="container" id="container1">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-2">
            <div id="contenido" style="display: none;">
              
            </div>
            <label>Fecha</label>
            <input type="date" class="form-control input-sm" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" onchange="numeroFactura();catalogoLineas();">
          </div>
          <div class="col-sm-5 col-xs-12">
            <label class="text-right">Cliente</label>
            <a title="Agregar nuevo cliente" style="padding-left: 20px" onclick="addCliente();">
              <img src="../../img/png/mostrar.png" width="20" height="20">
            </a>
            <select class="form-control input-sm" id="cliente" name="cliente">
              <option value="">Seleccione un cliente</option>
            </select>
            <input type="hidden" name="codigoCliente" id="codigoCliente">
            <input type="text" class="form-control input-sm" placeholder="Ingrese nombre del nuevo cliente" name="nombreCliente" id="nombreCliente" autocomplete="off">
            <input type="hidden" name="direccion" id="direccion">
            <input type="hidden" name="ci" id="ci_ruc">
            <input type="hidden" name="fechaEmision" id="fechaEmision" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-3">
            <b>Email:</b>
            <input type="text" class="form-control input-sm" placeholder="Email" name="email" id="email" onblur="validador_correo()">            
          </div>
           <div class="col-sm-2">
            <b>Telefono:</b>
            <input type="text" class="form-control input-sm" placeholder="Telefono" name="telefono" id="telefono">            
          </div>
        </div><br>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label class="text-right">TIPO DE PROCESO</label>
          </div>
          <div class="col-sm-4">
            <select class="form-control input-sm" name="DCLinea" id="DCLinea" onchange="numeroFactura();productos();limpiar_grid(); cargar_grilla();">
            </select>
          </div>
          <div class="col-sm-3">
            <label id="numeroSerie" class="red">() No.</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="factura" id="factura" value="1" class="form-control input-sm text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-sm-offset-1">
            <label>PRODUCTO</label>
            <select class="form-control input-sm" id="producto" onchange="setPVP();">
            </select>
          </div>
          <div class="col-sm-2">
            <label>Precio Unitario</label>
            <input type="text" name="preciounitario" id="preciounitario" value="101.7900" class="form-control input-sm text-right">
          </div>
        </div>
        <div class="row">          
          <input type="hidden" name="" id="tipo_cal" value="T">
          <div class="col-sm-4 col-sm-offset-1">
            <label>TOTAL EN DOLARES S/.</label>
            <input type="text" name="total" id="total" value="0.00" class="form-control input-sm text-right" onblur="$('#tipo_cal').val('C')">
          </div>
          <div class="col-sm-4">
            <label>Cantidad <label id="tipo_p"></label> </label>
            <input type="text" name="cantidad" id="cantidad" value="0.00" class="form-control input-sm text-right" onblur="$('#tipo_cal').val('T')">
          </div>
          <div class=" col-sm-2">
              <a title="Calcular" class="btn btn-default" tabindex="22" id="btn_cal">
                <img src="../../img/png/calculadora.png" width="25" height="30" onclick="calcular();">
              </a>
              <a title="Aprobar" class="btn btn-default" tabindex="23" onclick="calcular();aceptar();">
                <img src="../../img/png/aprobar.png" width="25" height="30">
              </a>
              <a title="Buscar y re imprimir" class="btn btn-default" tabindex="23" onclick="modal_reimprimir()">
                <img src="../../img/png/re_print.png" width="25" height="30">
              </a>
          </div>
        </div>
        <div class="row"><br>
         
          <div class="col-sm-12" id="tbl_divisas" style="height:300px">
           
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Total Tarifa 0%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total0" id="total0" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <label>Total Factura</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="totalFac" id="totalFac" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <a title="Guardar" class="btn btn-default" tabindex="22">
              <img src="../../img/png/save.png" width="25" height="30" onclick="guardarFactura();">
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Total Tarifa 12%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total12" id="total12" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <label>Total Fact. (ME)</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="totalFacMe" id="totalFacMe" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <a title="Guardar" class="btn btn-default" tabindex="22" title="Salir del panel" href="facturacion.php?mod=facturacion">
              <img src="../../img/png/salire.png" width="25" height="30" >
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>I.V.A. 12%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="iva12" id="iva12" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <label>EFECTIVO</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="efectivo" id="efectivo" class="form-control input-sm red text-right" value="0.00" onkeyup="calcularSaldo();">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-5">
            <label>Cambio</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cambio" id="cambio" class="form-control input-sm red text-right" value="0.00">
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
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
             <input type="text" name="txt_buscar" id="txt_buscar" class="form-control input-sm" placeholder="Nombre - CI - RUC " onkeyup="cargar_facs()">
           </div>
            <div class="col-sm-4">
             <b>Numero de comprobante</b>
             <input type="text" name="txt_fac" id="txt_fac" class="form-control input-sm" placeholder="Numero comprobante" onkeyup="cargar_facs()">
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