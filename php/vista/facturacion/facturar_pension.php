<?php
  include "../controlador/facturacion/facturar_pensionC.php";
  $facturar = new facturar_pensionC();
?>

<script type="text/javascript">
  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("DCLinea").focus();
      e.preventDefault();
    }
  });
  var total = 0;
  var total0 = 0;
  var total12 = 0;
  var iva12 = 0;
  var descuento = 0;
  $(document).ready(function () {
    autocomplete_cliente();
    catalogoLineas();
    totalRegistros();
    verificarTJ();
    cargarBancos();
    DCGrupo_No();

    $('.validateDate').on('keyup', function () {
        if($(this).val().length >= 10){
          let inputDate = $(this).val();
          const dateArray = inputDate.split("-"); // Separar la fecha en partes (año, mes, día)
          let year = dateArray[0];
          const month = dateArray[1];
          const day = dateArray[2];
          if(year.length>4){
            year = year.slice(0,4)
          }
          $(this).val(year+'-'+month+'-'+day);
       }
    });

    $("input").focusin(function() {
      $(this).select();
    });
    $("#factura").blur(function(){
      var currentTabIndex = parseInt($(this).attr("tabindex"));
      var nextTabIndex = currentTabIndex + 1;
      $("[tabindex='" + nextTabIndex + "']").focus();
    });

    $("#telefono").blur(function(){
      if($('.contenidoDepositoAutomatico').is(':visible') && $('.contenidoDepositoAutomatico').css("visibility") != "hidden"){
        $('#debito_automatica').focus()
      }
    });

    $(".btnDepositoAutomatico").on('click',function () {
      if($('.contenidoDepositoAutomatico').is(':visible') && $('.contenidoDepositoAutomatico').css("visibility") != "hidden"){
        $('.contenidoDepositoAutomatico').css('visibility', 'hidden')
      }else{
        $('.contenidoDepositoAutomatico').css('visibility', 'visible')
      }
    })

    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      // var dataM = e.params.data.dataMatricula;

      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#direccion1').val(data.direccion1);
      $('#telefono').val(data.telefono);
      $('#codigo').val(data.codigo);
      $('#ci_ruc').val(data.ci_ruc);
      $('#persona').val(data.cliente);
      $('#chequeNo').val(data.grupo);
      $('#codigoCliente').val(data.codigo);
      $('#tdCliente').val(data.tdCliente);
      $('#codigoB').val("Código del banco: "+data.ci_ruc);
      $("#total12").val(parseFloat(0.00).toFixed(2));
      $("#descuento").val(parseFloat(0.00).toFixed(2));
      $("#descuentop").val(parseFloat(0.00).toFixed(2));
      $("#efectivo").val(parseFloat(0.00).toFixed(2));
      $("#abono").val(parseFloat(0.00).toFixed(2));
      $("#iva12").val(parseFloat(0.00).toFixed(2));
      $("#total").val(parseFloat(0.00).toFixed(2));
      $("#total0").val(parseFloat(0.00).toFixed(2));
      $("#valorBanco").val(parseFloat(0.00).toFixed(2));
      $("#saldoTotal").val(parseFloat(0.00).toFixed(2));
      DCGrupo_NoPreseleccion(data.grupo)

      if(data.Archivo_Foto_url!=''){
        $("#img_estudiante").attr('src','../img/img_estudiantes/'+data.Archivo_Foto_Url)
      }else{
        $("#img_estudiante").attr('src','../img/img_estudiantes/SINFOTO.jpg')

      }
      //$("input[type=checkbox]").prop("checked", false);
      total = 0;
      total0 = 0;
      total12 = 0;
      iva12 = 0;
      descuento = 0;
      catalogoProductos(data.codigo);
      saldoFavor(data.codigo);
      saldoPendiente(data.codigo);
      clienteMatricula(data.codigo);

      //prefactura pension
      $('#PFcodigoCliente').val(data.codigo);
      $('#PFnombreCliente').text(data.cliente);
      $('#PFGrupoNo').val(data.grupo);
    });

    $("#DCGrupo_No").on('select2:select', function (e) {
      $.ajax({
        url:   '../controlador/facturacion/facturar_pensionC.php?DireccionByGrupo=true&grupo='+$("#DCGrupo_No").val()+'',
        dataType: 'json',
        success: function (data) {
          $('#direccion').val(data[0].Direccion)
        }
      })
    });
  });

  function usar_cliente(nombre, ruc, codigocliente, email, T, grupo) {
    $('#PFcodigoCliente').val(codigocliente);
    $('#PFnombreCliente').text(nombre);
    $('#PFGrupoNo').val(grupo);
    OpenModalPreFactura(cantidadProductoPreFacturar)
  }

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/facturar_pensionC.php?cliente=true',
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

  function catalogoLineas(){
    // $('#myModal_espera').modal('show');
    var cursos = $("#DCLinea");
    fechaEmision = $('#fechaEmision').val();
    fechaVencimiento = $('#fechaVencimiento').val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision},      
      dataType:'json', 
      success: function(data)             
      {
        if (data) {
          datos = data;
          // Limpiamos el select
          cursos.find('option').remove();
          for (var indice in datos) {
            cursos.append('<option value="' + datos[indice].id +" "+datos[indice].text+ ' ">' + datos[indice].text + '</option>');
          }
        }else{
          console.log("No tiene datos");
        }
        numeroFactura();            
      }
    });
    $('#myModal_espera').modal('hide');
  }

  function imprimir_ticket_fac(mesa,ci,fac,serie)
  {
    var html='<iframe style="width:100%; height:50vw;" src="../appr/controlador/imprimir_ticket.php?mesa='+mesa+'&tipo=FA&CI='+ci+'&fac='+fac+'&serie='+serie+'" frameborder="0" allowfullscreen></iframe>';
    $('#contenido').html(html); 
    $("#myModal").modal();
  }

  function catalogoProductos(codigoCliente){
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogoProducto=true',
      data: {'codigoCliente' : codigoCliente }, 
      dataType:'json', 
      success: function(data)
      {
        if (data) {
          datos = data;
          clave = 1;
          $("#cuerpo").empty();
          for (var indice in datos) {
            subtotal = (parseFloat(datos[indice].valor) + (parseFloat(datos[indice].valor) * parseFloat(datos[indice].iva) / 100)) - parseFloat(datos[indice].descuento) - parseFloat(datos[indice].descuento2);
            var tr = `<tr class="tr`+clave+`">
              <td><input style="border:0px;background:bottom" type="checkbox" id="checkbox`+clave+`" onclick="totalFactura('checkbox`+clave+`','`+subtotal+`','`+datos[indice].iva+`','`+datos[indice].descuento+`','`+datos.length+`','`+clave+`')" name="`+datos[indice].mes+`"></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Mes`+clave+`" value ="`+datos[indice].mes+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Codigo`+clave+`" value ="`+datos[indice].codigo+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Periodo`+clave+`" value ="`+datos[indice].periodo+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Producto`+clave+`" value ="`+datos[indice].producto+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom"  size="10px" type ="text" id="valor`+clave+`" value ="`+parseFloat(datos[indice].valor).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom"  size="10px" type ="text" id="descuento`+clave+`" value ="`+parseFloat(datos[indice].descuento).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom"  size="10px" type ="text" id="descuento2`+clave+`" value ="`+parseFloat(datos[indice].descuento2).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom" size="10px" type ="text" id="subtotal`+clave+`" value ="`+parseFloat(subtotal).toFixed(2)+`" disabled/></td>
              <input size="10px" type ="hidden" id="CodigoL`+clave+`" value ="`+datos[indice].CodigoL+`"/>
              <input size="10px" type ="hidden" id="Iva`+clave+`" value ="`+datos[indice].iva+`"/>
            </tr>`;
            $("#cuerpo").append(tr);
            clave++;
          }
          $("#efectivo").val(parseFloat(0.00).toFixed(2));
          $("#abono").val(parseFloat(0.00).toFixed(2));
          $("#descuentop").val(parseFloat(0.00).toFixed(2));
        }else{
          console.log("No tiene datos");
        }            
      }
    });
    $('#myModal_espera').modal('hide');
  }

  function historiaCliente(){
    codigoCliente = $('#codigoCliente').val();
    $('#myModal_espera').modal('show');
    
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?historiaCliente=true',
      data: {'codigoCliente' : codigoCliente }, 
      dataType:'json', 
      success: function(data)
      {
        $('#myModal_espera').modal('hide');
        $('#myModalHistoria').modal('show');
        if (data) {
          datos = data;
          clave = 0;
          $("#cuerpoHistoria").empty();
          for (var indice in datos) {
            var tr = `<tr>
              <td><input style="border:0px;background:bottom" size="1" type ="text" id="TD`+clave+`" value ="`+datos[indice].TD+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="7" type ="text" id="Fecha`+clave+`" value ="`+datos[indice].Fecha+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" type ="text" id="Serie`+clave+`" value ="`+datos[indice].Serie+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" type ="text" id="Factura`+clave+`" value ="`+datos[indice].Factura+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="70" type ="text" id="Detalle`+clave+`" value ="`+datos[indice].Detalle+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="2" class="text-right" type ="text" id="Anio`+clave+`" value ="`+datos[indice].Anio+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="10" type ="text" id="Mes`+clave+`" value ="`+datos[indice].Mes+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" class="text-right" size="10px" type ="text" id="Total`+clave+`" value ="`+parseFloat(datos[indice].Total).toFixed(2)+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" class="text-right" type ="text" id="Abonos`+clave+`" value ="`+parseFloat(datos[indice].Abonos).toFixed(2)+`" disabled/></td>
              <td><input size="2" class="text-right" style="border:0px;background:bottom"  type ="text" id="Mes_No`+clave+`" value ="`+datos[indice].Mes_No+`" disabled/></td>
              <td><input size="2" class="text-right" style="border:0px;background:bottom"  type ="text" id="No`+clave+`" value ="`+datos[indice].No+`" disabled/></td>
            </tr>`;
            $("#cuerpoHistoria").append(tr);
            clave++;
          }
        }else{
          console.log("No tiene datos");
        }            
      }
    });
  }

  function historiaClienteExcel(){
    codigoCliente = $('#codigoCliente').val();
    url = '../controlador/facturacion/facturar_pensionC.php?historiaClienteExcel=true&codigoCliente='+codigoCliente;
    window.open(url, '_blank');
  }

  function historiaClientePDF(){
    codigoCliente = $('#codigoCliente').val();
    if(codigoCliente=='')
    {
      codigoCliente = $('#codigo').val();
    }
    url = '../controlador/facturacion/facturar_pensionC.php?historiaClientePDF=true&codigoCliente='+codigoCliente;
    window.open(url,'_blank');
  }

    function DeudaPensionPDF(){
    var parametros=[];
    codigoCliente = $('#codigoCliente').val();
    var can = $('#txt_cant_datos').val();
    var j=0;
    for (var i = 1; i < can+1; i++) {
      if($('#checkbox'+i).prop('checked'))
      {
       parametros[j] = {
        'mes':$('#Mes'+i).val(),
        'cod':$('#Codigo'+i).val(),
        'ani':$('#Periodo'+i).val(),
        'pro':$('#Producto'+i).val(),
        'val':$('#valor'+i).val(),
        'des':$('#descuento'+i).val(),
        'p.p':$('#descuento2'+i).val(),
        'tot':$('#subtotal'+i).val(),
      }
      j= j+1;
    }

    }

    parametros = JSON.stringify(parametros);
    parametros = encodeURI(parametros);

    
    url = '../controlador/facturacion/facturar_pensionC.php?DeudaPensionPDF=true&codigoCliente='+codigoCliente+'&lineas='+parametros;
    // console.log(parametros);
    // return false;
    window.open(url, '_blank');
  }

  function enviarHistoriaCliente(){
    codigoCliente = $('#codigoCliente').val();
    email = $('#email').val();
    //url = '../controlador/facturacion/facturar_pensionC.php?enviarCorreo=true&codigoCliente='+codigoCliente+'&email='+email;
    //window.open(url, '_blank');
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?enviarCorreo=true&codigoCliente='+codigoCliente,
      data: {'email' : email }, 
      success: function(data)
      {
        $('#myModal_espera').modal('hide');
        Swal.fire({
          type: 'success',
          title: 'Correo enviado correctamente',
          text: ''
        });
      }
    });
  }

  function saldoFavor(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?saldoFavor=true',
      data: {'codigoCliente' : codigoCliente },
      dataType:'json', 
      success: function(data)
      {
        let valor = 0;
        if (data.length>0) {
          valor = data[0].Saldo_Pendiente;
        }
        $("#saldoFavor").val(parseFloat(valor).toFixed(2));
      }
    });
  }

  function saldoPendiente(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?saldoPendiente=true',
      data: {'codigoCliente' : codigoCliente }, 
      dataType:'json', 
      success: function(data)
      {
        let valor = 0;
        if (data.length>0) {
          valor = data[0].Saldo_Pend;
        }
        $("#saldoPendiente").val(parseFloat(valor).toFixed(2));
      }
    });
  }

  function totalFactura(id,valor,iva,descuento1,datos,clave){
    $('#txt_cant_datos').val(datos);
    $('.tr'+clave).toggleClass("filaSeleccionada");
    datosLineas = [];
    key = 0;
    for (var i = 1; i <= datos; i++) {
      datosId = 'checkbox'+i;
      if ($('#'+datosId).prop('checked')) {
        datosLineas[key] = {
          'Codigo' : $("#Codigo"+i).val(),
          'CodigoL' : $("#CodigoL"+i).val(),
          'Producto' : $("#Producto"+i).val(),
          'Precio' : $("#valor"+i).val(),
          'Total_Desc' : $("#descuento"+i).val(),
          'Total_Desc2' : $("#descuento2"+i).val(),
          'Iva' : $("#Iva"+i).val(),
          'Total' : $("#subtotal"+i).val(),
          'MiMes' : $("#Mes"+i).val(),
          'Periodo' : $("#Periodo"+i).val(),
        };
        key++;
      }
    }
    codigoCliente = $("#codigoCliente").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?guardarLineas=true',
      data: {
        'codigoCliente' : codigoCliente,
        'datos' : datosLineas,
      }, 
      success: function(data){}
    });
    var valor = 0; var descuento = 0; var descuentop = 0; var total = 0;var subtotal = 0;
    for(var i=1; i<datos+1; i++){
      checkbox = "checkbox"+i;
      if($('#'+checkbox).prop('checked'))
      {
        descuento+=parseFloat($('#descuento'+i).val());
        descuentop+=parseFloat($('#descuento2'+i).val());
        valor+=parseFloat($('#valor'+i).val());
        subtotal+=parseFloat($('#descuento2'+i).val());
        total+=parseFloat($('#subtotal'+i).val());
      }

    }

    //$("#total12").val(parseFloat(subtotal).toFixed(2));
    $("#descuentop").val(parseFloat(descuentop).toFixed(2));
    $("#descuento").val(parseFloat(descuento).toFixed(2));
    $("#iva12").val(parseFloat(iva12).toFixed(2));
    $("#total").val(parseFloat(total).toFixed(2));
    $("#total0").val(parseFloat(valor).toFixed(2));
    $("#valorBanco").val(parseFloat(total).toFixed(2));
   // $("#saldoTotal").val(parseFloat(0).toFixed(2));


  }

  function calcularDescuento(){
    $('#myModalDescuentoP').modal('hide');
    let ContDesc = 0
    let SubTotal_Desc2 = parseFloat($("#total0").val()) + parseFloat($("#total12").val()) - parseFloat($("#descuento").val())

    if (SubTotal_Desc2 > 0 ){
      let Valor_Desc2 = 0
      let Porc_Desc2 = $('#porcentaje').val();

      if( $.isNumeric(Porc_Desc2) ){
        var table = document.getElementById('tablaDetalle');
        var rowLength = table.rows.length;
        for(let i=1; i<rowLength; i+=1){
          if ($("#checkbox"+i).prop('checked')){
            ContDesc++
          }
        }

        if(ContDesc>0){ Valor_Desc2 = (((Porc_Desc2 / 100) * SubTotal_Desc2) / ContDesc).toFixed(2) }
        let S_Descuento2_Total = S_Descuento1_Total = 0;
        for(let i=1; i<rowLength; i+=1){
          if ($("#checkbox"+i).prop('checked')){
            let S_Valor = $("#valor"+i).val()
            let S_Descuento1 = $("#descuento"+i).val()
            let S_Descuento2 = Valor_Desc2
            let S_SubTotal = ((S_Valor) - (S_Descuento1) - (S_Descuento2)).toFixed(2)

            S_Descuento2_Total += parseFloat(S_Descuento2)
            S_Descuento1_Total += parseFloat(S_Descuento1)
            $("#descuento2"+i).val(S_Descuento2);
            $("#subtotal"+i).val(S_SubTotal);
            totalFactura("checkbox"+i,S_Valor,iva=0,S_Descuento1,rowLength);
          }
        }

        total0 = $("#total0").val();
        total = total0 - S_Descuento2_Total - S_Descuento1_Total;
        $("#descuentop").val(parseFloat(S_Descuento2_Total).toFixed(2));
        $("#total").val(parseFloat(total).toFixed(2));
        $("#valorBanco").val(parseFloat(total).toFixed(2));

        calcularSaldo()
      }
      else{
        Swal.fire({
          type: 'info',
          title: 'No tiene items a descontar',
          text: ''
        });
      }
    }
  }

  function calcularSaldo(){
    total = $("#total").val();
    efectivo = $("#efectivo").val();
    abono = $("#abono").val();
    banco = $("#valorBanco").val();
    saldoFavor = $("#saldoFavor").val();
    saldo = total - banco - efectivo - abono -saldoFavor;
    $("#saldoTotal").val(saldo.toFixed(2));
  }

  function numeroFactura(){
    DCLinea = $("#DCLinea").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
      data: {
        'DCLinea' : DCLinea,
      },       
      dataType:'json', 
      success: function(data)
      {
        datos = data;
        document.querySelector('#numeroSerie').innerText = datos.serie;
        $("#factura").val(datos.codigo);
      }
    });
  }

  function totalRegistros(){
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?cliente=true&total=true',
      data: {
        'q' : '',
      },       
      dataType:'json', 
      success: function(data)
      {
        datos = data;
        $("#registros").val(datos.registros);
      }
    });
  }

  function verificarTJ(){
    TC = $("#cuentaBanco").val();
    TC = TC.split("/");
    //console.log("entra");
    if (TC[1] == "TJ") {
      $("#divInteres").show();
    }else{
      $("#divInteres").hide();
    }
  }

  function guardarPension(){
    validarDatos = $("#total").val();
    saldoTotal = $("#saldoTotal").val();
    // if (saldoTotal > 0 ) {
    //   Swal.fire({
    //     type: 'info',
    //     title: 'Debe pagar la totalidad de la factura',
    //     text: ''
    //   });
    // }else 
    if (validarDatos <= 0 ) {
      Swal.fire({
        type: 'info',
        title: 'Ingrese los datos necesarios para guardar la factura',
        text: ''
      });
    }else{
      var update = false;
      //var update = confirm("¿Desea actualizar los datos del cliente?");
      Swal.fire({
        title: 'Esta seguro?',
        text: "¿Desea actualizar los datos del cliente?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
      }).then((result) => {
        if (result.value==true) {
          update = true;
        }else{
          update = false;
        }
        TextRepresentante = $("#persona").val();
        DCLinea = $("#DCLinea").val();
        TxtDireccion = $("#direccion").val();
        TxtTelefono = $("#telefono").val();
        TextFacturaNo = $("#factura").val();
        Grupo_No = $("#DCGrupo_No").val();
        TextCI = $("#ci_ruc").val();
        TD_Rep = $("#tdCliente").val();
        TxtEmail = $("#email").val().toUpperCase();
        TxtDirS = $("#direccion1").val().toUpperCase();
        TextCheque = $("#valorBanco").val();
        TextBanco = $("#TextBanco").val();
        DCBanco = $("#cuentaBanco").val();
        DCBanco = DCBanco.split("/");
        DCBanco = DCBanco[0];
        DCAnticipo = $("#DCAnticipo").val();
        chequeNo = $("#chequeNo").val();
        TxtEfectivo = $("#efectivo").val();
        TxtNC = $("#abono").val();
        DCNC = $("#cuentaNC").val();
        Fecha = $("#fechaEmision").val();
        Total = $("#total").val();
        Descuento = $("#descuento").val();
        Descuento2 = $("#descuentop").val();
        codigoCliente = $("#codigoCliente").val();
        saldoFavor = $('#saldoFavor').val();
        abono = $('#abono').val();
        debito_automatica = $('#debito_automatica').val();
        tipo_debito_automatico = $('#tipo_debito_automatico').val();
        numero_cuenta_debito_automatico = $('#numero_cuenta_debito_automatico').val();
        caducidad_debito_automatico = $('#caducidad_debito_automatico').val();
        TextInteres = $('#interesTarjeta').val();

        let por_deposito_debito_automatico ="0";
        if($('#por_deposito_debito_automatico').prop('checked')){
          por_deposito_debito_automatico = "1";
        }

        //var confirmar = confirm("Esta seguro que desea guardar \n La factura No."+TextFacturaNo);
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
            $('#myModal_espera').modal('show');
            $.ajax({
            type: "POST",
            url: '../controlador/facturacion/facturar_pensionC.php?guardarPension=true',
            data: {
              'update' : update,
              'DCLinea' : DCLinea,
              'Total' : Total,
              'Descuento' : Descuento,
              'Descuento2' : Descuento2,
              'TextRepresentante' : TextRepresentante,
              'TxtDireccion' : TxtDireccion,
              'TxtTelefono' : TxtTelefono,
              'TextFacturaNo' : TextFacturaNo,
              'Grupo_No' : Grupo_No,
              'chequeNo' : chequeNo,
              'TextCI' : TextCI,
              'TD_Rep' : TD_Rep,
              'TxtEmail' : TxtEmail,
              'TxtDirS' : TxtDirS,
              'codigoCliente' : codigoCliente,
              'TextCheque' : TextCheque,
              'TextBanco': TextBanco,
              'DCBanco' : DCBanco,
              'DCAnticipo' : DCAnticipo,
              'TxtEfectivo' : TxtEfectivo,
              'TxtNC' : TxtNC,
              'Fecha' : Fecha,
              'DCNC' : DCNC,
              'saldoTotal':saldoTotal,
              'saldoFavor':saldoFavor,
              'TxtNCVal':abono, 
              'DCDebito':debito_automatica, 
              'CTipoCta':tipo_debito_automatico, 
              'TxtCtaNo':numero_cuenta_debito_automatico, 
              'MBFecha':caducidad_debito_automatico, 
              'CheqPorDeposito':por_deposito_debito_automatico, 
              'TextInteres' : TextInteres
            },
            dataType:'json',  
            success: function(response)
            {
              
              $('#myModal_espera').modal('hide');
              if (response) {

                response = response;
                if(response.respuesta == '3')
                {
                  Swal.fire('Este documento electronico ya esta autorizado','','error');

                }else if(response.respuesta == '1')
                {
                    Swal.fire({
                      type: 'success',
                      title: 'Este documento electronico fue autorizado',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      //url = '../vista/appr/controlador/imprimir_ticket.php?mesa=0&tipo=FA&CI='+TextCI+'&fac='+TextFacturaNo+'&serie='+serie[1];
                      //window.open(url, '_blank');
                      var url = '../controlador/educativo/detalle_estudianteC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+codigoCliente;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
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
                      var url = '../controlador/detalle_estudianteC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });

                }else if(response.respuesta == '5')
                {
                    Swal.fire({
                      type: 'success',
                      title: 'Factura guardada correctamente',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      cambio = $("#cambio").val();
                      efectivo = $("#efectivo").val();
                      var url = '../controlador/detalle_estudianteC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
                  }else if(response.respuesta==4)
                  {
                     Swal.fire('SRI intermitente si el problema persiste por mas de 1 dia comuniquese con su proveedor','','info');
                     catalogoProductos(codigoCliente);
                  }
                  else
                  {
                    Swal.fire({
                       type: 'info',
                       title: 'Error por: '+response.text,
                       text: ''
                     });

                  }
              }else{
                Swal.fire({
                  type: 'info',
                  title: 'La factura ya se autorizo',
                  text: ''
                });
                catalogoProductos(codigoCliente);
              }

              if($('#persona').val()!=""){
                ClientePreseleccion($('#persona').val());
              }

            },
            error: function () {
              $('#myModal_espera').modal('hide');
              alert("Ocurrio un error inesperado, por favor contacte a soporte.");
            }
            });
          }
        })
      })
    }
  }

  function clienteMatricula(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?clienteMatricula=true&codigoCliente='+codigoCliente,
      dataType:'json', 
      success: function(data)
      {
        if (data[0]) {
          let Caducidad = new Date(data[0].Caducidad.date);
          let mesCaducidad = (Caducidad.getMonth()+1);
          if(mesCaducidad<10){
            mesCaducidad = '0'+mesCaducidad; 
          }
          cargarBancosPreseleccion(data[0].Cod_Banco)
          $("#tipo_debito_automatico").val(data[0].Tipo_Cta);
          $("#numero_cuenta_debito_automatico").val(data[0].Cta_Numero);
          $("#caducidad_debito_automatico").val(mesCaducidad+'/'+Caducidad.getFullYear());
          if(data[0].Por_Deposito=='1'){
            $("#por_deposito_debito_automatico").prop('checked', true);
          }else{
            $("#por_deposito_debito_automatico").prop('checked', false);
          }

          if (data[0].Cod_Banco!="<?php echo G_NINGUNO; ?>" && data[0].Cta_Numero!="<?php echo G_NINGUNO; ?>") {
            $('.contenidoDepositoAutomatico').css('visibility', 'visible')
          }else{
            $('.contenidoDepositoAutomatico').css('visibility', 'hidden')
          }
        }else{
          $('#debito_automatica').val(null).trigger('change');
          $("#tipo_debito_automatico").val('.');
          $("#numero_cuenta_debito_automatico").val('');
          $("#caducidad_debito_automatico").val('');
          $("#por_deposito_debito_automatico").prop('checked', false);
          $('.contenidoDepositoAutomatico').css('visibility', 'hidden')
        }            
      }
    });
  }

  function cargarBancos() {
    $('#debito_automatica').select2({
      width: '100%',
      placeholder: 'Seleccione un Banco',
      ajax: {
        url: '../controlador/facturacion/facturar_pensionC.php?cargarBancos=true&limit=true',
        dataType: 'json',
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: false
      }
    });
  }

  function cargarBancosPreseleccion(preseleccionado) {
    var debito = $('#debito_automatica');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '../controlador/facturacion/facturar_pensionC.php?cargarBancos=true&limit=true&id='+preseleccionado
    }).then(function (data) {
      if(data.length>0){
        var option = new Option(data[0].text, data[0].id, true, true);
        debito.append(option).trigger('change');
        debito.trigger({
            type: 'select2:select',
            params: {
                data: data[0]
            }
        });
      }else{
        $('#debito_automatica').val(null).trigger('change');
      }
    });
  }

  function DCGrupo_No()
  {
    $('#DCGrupo_No').select2({
      placeholder: 'Grupo',
      ajax: {
        url: '../controlador/facturacion/facturar_pensionC.php?DCGrupo_No=true',
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

   function DCGrupo_NoPreseleccion(preseleccionado) {
    var debito = $('#DCGrupo_No');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '../controlador/facturacion/facturar_pensionC.php?DCGrupo_No=true&q='+preseleccionado
    }).then(function (data) {
      if(data.length>0){
        var option = new Option(data[0].text, data[0].id, true, true);
        debito.append(option).trigger('change');
        debito.trigger({
            type: 'select2:select',
            params: {
                data: data[0]
            }
        });
      }else{
        $('#DCGrupo_No').val(null).trigger('change');
      }
    });
  }

  function ClientePreseleccion(preseleccionado) {
    var debito = $('#cliente');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '../controlador/facturacion/facturar_pensionC.php?cliente=true&q='+preseleccionado
    }).then(function (data) {
      if(data.length>0){
        var option = new Option(data[0].text, data[0].id, true, true);
        debito.append(option).trigger('change');
        debito.trigger({
            type: 'select2:select',
            params: {
                data: data[0]
            }
        });
      }else{
        $('#cliente').val(null).trigger('change');
      }
    });
  }

</script>
<style type="text/css"> 
 .contenedor_img{
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 150px;
    background: #e5e5e5;
    margin: 3px auto;
    min-height: 150px;
    border-radius: 10px;
}
tbody tr:nth-child(odd):hover {  background: #DDA !important;}
.filaSeleccionada{
  background: #ccccff !important;
 }

.visible{
  visibility: visible;
}
input:focus, select:focus, span:focus, button:focus, #guardar:focus, a:focus  {
  border: 2.3px solid #3c8cbb !important;
}
</style>
  <div class="row">
    <div class="col-lg-4 col-sm-10 col-md-7 col-xs-12">
      <div class="col-xs-2 col-sm-2">
        <a  href="./panel.php" title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/salire.png" width="25" height="30">
        </a>
      </div>

      <div class="col-xs-2 col-sm-2">
        <a title="Presenta la historia del cliente"  class="btn btn-default" onclick="historiaCliente()">
          <img src="../../img/png/document.png" width="25" height="30">
        </a>
      </div>
      
      <div class="col-xs-2 col-sm-2">
        <a href="#" title="Presenta la Deuda Pendiente"  class="btn btn-default" onclick="DeudaPensionPDF()">
          <img src="../../img/png/project.png" width="25" height="30">
        </a>
      </div>

      <?php include("prefactura.php") ?>
      
      <div class="col-xs-2 col-sm-2">
        <a href="#" title="Insertar nuevo Beneficiario/Cliente"  class="btn btn-default" onclick="addCliente(1)">
          <img src="../../img/png/group.png" width="25" height="30">
        </a>
      </div>
   
       
    </div>
  </div>
  <div class="row">
    <div class="panel panel-primary col-sm-12">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-2">
            <input type="hidden" id="Autorizacion">
            <input type="hidden" id="Cta_CxP">
            <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" onchange="numeroFactura();">
              
            </select>
          </div>
          <div class="col-xs-6 col-md-1 text-right no-padding">
            <b style="font-weight: 600;">Fecha emisión</b>
          </div>
          <div class="col-xs-6 col-md-2 no-padding">
            <input tabindex="2" type="date" name="fechaEmision" id="fechaEmision" class="form-control input-xs validateDate" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
          </div>
          <div class="col-xs-6 col-md-2 no-padding text-right">
            <label>Fecha vencimiento</label>
          </div>
          <div class="col-xs-6 col-md-2 no-padding">
            <input type="date" tabindex="3" name="fechaVencimiento" id="fechaVencimiento" class="form-control input-xs validateDate" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
          </div>
          <div class="col-xs-6 col-md-2 no-padding">
            <label class="red">Factura No.</label>
            <label id="numeroSerie" class="red"></label>
          </div>
           <div class="col-xs-6 col-md-1 no-padding">
            <input tabindex="4" type="input" class="form-control input-xs text-right" name="factura" id="factura"  >
          </div>
        </div>

        <div class="row">
          <div class="col-md-9">
            <div class="row">
              <div class="col-xs-3 text-right">
                <label class="text-right">Cliente/Alumno(C)</label>
              </div>
              <div class="col-xs-9">
                <select class="form-control" id="cliente" name="cliente" tabindex="5">
                  <option value="">Seleccione un cliente</option>
                </select>
                <input type="hidden" name="codigoCliente" id="codigoCliente">
              </div>       
            </div>
            <div class="row">
              <div class="col-xs-3 text-right">
                <select class="form-control input-xs" id="DCGrupo_No" name="grupo" tabindex="7">
                  <option value=".">Grupo</option>
                </select>
              </div>
              <div class="col-xs-9">
                <input tabindex="8" type="input" class="form-control input-xs" name="direccion" id="direccion">
              </div>
            </div>
            <div class="row bg-warning" style="margin-top: 10px;">
              <div class="col-xs-6 col-sm-3 text-right ">
                <label>Razón social</label>
              </div>
              <div class="col-xs-6 col-sm-6 ">
                <input tabindex="10" type="input" class="form-control input-xs" name="persona" id="persona">
              </div>
              <div class="col-xs-6 col-sm-1 text-right ">
                <label>CI/R.U.C</label>
              </div>
              <div class="col-xs-6 col-sm-2 text-right ">
                <input  type="input" class="form-control input-xs" name="tdCliente" id="tdCliente" readonly>
              </div>
            </div>
            <div class="row bg-warning">
              <div class="col-xs-6 col-sm-3 text-right ">
                <label>Dirección</label>
              </div>
              <div class="col-xs-6 col-sm-9 ">
                <input tabindex="11" type="input" class="form-control input-xs" style="text-transform: uppercase;" name="direccion1" id="direccion1">
              </div>
            </div>
            <div class="row bg-warning">
              <div class="col-xs-6 col-sm-3 text-right">
                <label>Email</label>
              </div>
              <div class="col-xs-6 col-sm-6">
                <input tabindex="12" type="input" class="form-control input-xs" style="text-transform: uppercase;" name="email" id="email">
              </div>

              <div class="col-xs-6 col-sm-1 text-right">
                <label>Telefono</label>
              </div>
              <div class="col-xs-6 col-sm-2">
                <input tabindex="13" type="input" class="form-control input-xs" name="telefono" id="telefono">
              </div>
            </div>
            <div class="row bg-info"  style="margin-top: 10px;">
              <div class="col-xs-12 text-center">
                <button style="margin: 8px 0px;" class="btn btn-block btn-info btn-xs btnDepositoAutomatico">Ingrese sus datos para el Debito Automatico</button>
              </div>
            </div>
            <div class="row bg-info contenidoDepositoAutomatico" style="visibility: hidden;">
              <div class="col-xs-6 col-sm-2 text-right">
                <label for="debito_automatica">Debito Automatico</label>
              </div>
              <div class="col-xs-6 col-sm-6">
                <select  class="form-control input-xs" name="debito_automatica" id="debito_automatica">
                  <option value="">Seleccione un Banco</option>
                </select>
              </div>

              <div class="col-xs-6 col-sm-1 text-right">
                <label>Tipo</label>
              </div>
              <div class="col-xs-6 col-sm-3">
                <select  type="input" class="form-control input-xs" name="tipo_debito_automatico" id="tipo_debito_automatico">
                  <option value=".">Seleccionar Tipo</option>
                  <option value="CORRIENTE">CORRIENTE</option>
                  <option value="AHORROS">AHORROS</option>
                  <option value="TARJETA">TARJETA</option>
                </select>
              </div>
            </div>
            <div class="row bg-info contenidoDepositoAutomatico" style="visibility: hidden;">
              <div class="col-xs-6 col-sm-2 text-right">
                <label>Numero de Cuenta</label>
              </div>
              <div class="col-xs-6 col-sm-3">
                <input  type="input" class="form-control input-xs" name="numero_cuenta_debito_automatico" id="numero_cuenta_debito_automatico">
              </div>

              <div class="col-xs-6 col-sm-1 text-right">
                <label>Caducidad</label>
              </div>
              <div class="col-xs-6 col-sm-2 contenedor_fecha_caducidad">
                <input  type="text" maxlength="7"  class="form-control input-xs fecha_caducidad" name="caducidad_debito_automatico" id="caducidad_debito_automatico" placeholder="MM/YYYY">
              </div>
              <div class="col-xs-6 col-sm-3 text-right">
                <label class="text-right" for="rbl_no">Depositar al Banco</label>
              </div>
              <div class="col-xs-6 col-sm-1 no-padding">
                <input style="margin-top: 0px;margin-right: 2px;" type="checkbox" name="por_deposito_debito_automatico" id="por_deposito_debito_automatico">
              </div>
            </div>


          </div>
          <div class="col-md-3">
            <div class="row">
              <div class="col-xs-12">
                <div class="col-xs-6 text-right">
                  <label class="text-right" for="rbl_no">Con mes</label>
                </div>
                <div class="col-xs-6 no-padding">
                  <input style="margin-top: 0px;margin-right: 2px;" tabindex="6" type="checkbox" name="rbl_radio" id="rbl_no" checked="">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <div class="col-xs-6 text-right">
                  <label>RUC</label>
                </div>
                <div class="col-xs-6 no-padding">
                  <input tabindex="9" type="input" class="form-control input-xs" name="ci" id="ci_ruc">   
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 text-center">
                <div class="contenedor_img">
                  <img src="../img/img_estudiantes/SINFOTO.jpg" id="img_estudiante" class="img-responsive img-thumbnail">
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="row"  style="margin-top: 10px;">
          <div class="col-sm-12">
            <!-- <div class="tab-content" style="background-color:#E7F5FF"> -->
            <div class="tab-content">
              <div id="home" class="tab-pane fade in active">
                <div class="table-responsive" style="overflow-y: scroll; height:250px; width: auto;">
                  <!-- <div class="sombra" style> -->
                    <table id="tablaDetalle" class="table-sm" style="width: -webkit-fill-available;">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Mes</th>
                          <th>Código</th>
                          <th>Año</th>
                          <th>Producto</th>
                          <th>Valor</th>
                          <th>Descuento</th>
                          <th>Desc. P. P.</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody id="cuerpo">
                      </tbody>
                    </table>          
                  <!-- </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2 text-left">
            <b>Total Tarifa 0%</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Total Tarifa 12%</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Descuentos</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Desc x P P</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>I.V.A. 12%</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Total Facturado</b>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <input type="text" style="color: coral;" name="total0" id="total0" class="form-control input-xs red text-right" readonly value="0.00">
          </div>
          <div class="col-sm-2">
            <input type="text" style="color: coral;" name="total12" id="total12" class="form-control input-xs red text-right" readonly value="0.00">
          </div>
          <div class="col-sm-2">
            <input type="text" style="color: coral;" name="descuento" id="descuento" class="form-control input-xs red text-right" readonly value="0.00">
          </div>
          <div class="col-sm-2">
            <div class="input-group input-group-xs">
                <input type="text" style="color: coral;"  name="descuentop" id="descuentop" class="form-control input-xs red text-right" readonly value="0.00">
                    <span class="input-group-btn">
                      <button style="border: 2px #b1b1b1 solid;padding: 2px;" tabindex="27" type="button" class="btn btn-xs" data-toggle="modal" data-target="#myModalDescuentoP">%</button>
                    </span>
              </div>
          </div>
          <div class="col-sm-2">
            <input type="text" style="color: coral;"  name="iva12" id="iva12" class="form-control input-xs red text-right" readonly value="0.00">
          </div>
          <div class="col-sm-2">
            <input type="text" style="color: coral;"  name="total" id="total" class="form-control input-xs red text-right" readonly value="0.00" onblur="$('#TextBanco').focus()">
          </div>
        </div>
        <div class="row" style="margin-top: 8px;">
          <div class="col-sm-2 text-right no-padding">
            <label>Detalle del pago</label>
          </div>
          <div class="col-sm-6 no-padding">
            <input type="text" name="TextBanco" id="TextBanco" class="form-control input-xs" value="." tabindex="17">
          </div>

          <div class="col-sm-3 text-right no-padding">
            <label id="saldo">Saldo pendiente</label>
          </div>
          <div class="col-sm-1  no-padding">
            <input type="input" id="saldoPendiente" class="form-control input-xs text-right blue saldo_input text-right" name="saldoPendiente">
          </div>

        </div>
        <div class="row">
          <div class="col-sm-2 text-right no-padding">
            <label>Bancos/Tarjetas</label>
          </div>
          <div class="col-sm-4 no-padding">
            <select class="form-control input-xs" name="cuentaBanco" id="cuentaBanco" tabindex="18" onchange="verificarTJ();" onblur="$('#valorBanco').focus()">
              <?php
                $cuentas = $facturar->getCatalogoCuentas();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>

          <div class="col-sm-2 text-right no-padding">
            <b>Cheque No.</b>
          </div>
          <div class="col-sm-2 no-padding">
            <input type="text" name="chequeNo" id="chequeNo" class="form-control input-xs text-right" tabindex="19"  onblur="$('#cuentaBanco').focus()">
          </div>

          <div class="col-sm-1 text-right no-padding">
            <label>USD</label>
          </div>
          <div class="col-sm-1 no-padding">
            <input tabindex="19" type="text" name="valorBanco" id="valorBanco" onkeyup="calcularSaldo();" class="form-control input-xs red text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right no-padding">
            <label>Anticipos</label>
          </div>
          <div class="col-sm-8 no-padding">
            <select class="form-control input-xs" name="DCAnticipo" id="DCAnticipo" tabindex="20">
              <?php
                $cuentas = $facturar->getAnticipos();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right no-padding">
            <label>USD</label>
          </div>
          <div class="col-sm-1 no-padding">
            <input title="Saldo a Favor" type="input" id="saldoFavor" class="form-control input-xs red text-right" name="saldoFavor" tabindex="24" onkeyup="calcularSaldo();" value="0.00" style="color:yellowgreen;">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right no-padding">
            <label>Notas de crédito</label>
          </div>
          <div class="col-sm-8 no-padding">
            <select class="form-control input-xs" name="cuentaNC" id="cuentaNC" tabindex="20">
              <?php
                $cuentas = $facturar->getNotasCredito();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right no-padding">
            <label>USD</label>
          </div>
          <div class="col-sm-1 no-padding">
            <input tabindex="21" type="text" name="abono" id="abono" onkeyup="calcularSaldo();" class="form-control input-xs red text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 text-center" no-padding>
            <input type="text" name="codigoB" class="form-control input-xs" id="codigoB" style="color: white; background: brown;" value="Código del banco: " readonly />
          </div>
          <div class="col-sm-2 col-sm-offset-5 text-right no-padding">
            <b>Efectivo USD</b>
          </div>
          <div class="col-sm-1 no-padding">
            <input tabindex="22" type="text" name="efectivo" id="efectivo" onkeyup="calcularSaldo();" class="form-control input-xs red text-right" value="0.00"  onblur="$('#saldoTotal').focus()">
          </div>
        </div>
        <div class="row" id="divInteres">
          <div class="col-sm-2 col-sm-offset-5 text-right no-padding">
            <b>Interés Tarjeta USD</b>
          </div>
          <div class="col-sm-1 no-padding">
            <input tabindex="20" type="text" name="interesTarjeta" id="interesTarjeta" class="form-control input-xs red text-right" >
          </div>
        </div>
        <div class="row">

          <div class="col-xs-12 col-sm-4">
            <div class="col-xs-6 text-right">
              <label>Código interno</label>
               <input type="hidden" name="txt_cant_datos" id="txt_cant_datos" readonly>
            </div>
            <div class="col-xs-6 no-padding">
              <input type="input" class="form-control input-xs" name="codigo" id="codigo" tabindex="26">
            </div>
          </div>

          <div class="col-sm-1 text-center justify-content-center align-items-center">
            <input style="width: 50px" type="text" id="registros" class="form-control input-xs text-center justify-content-center align-items-center" readonly>
          </div>
          <div class=" col-sm-4 ">
            <div class="col-sm-2 col-sm-offset-4">
              <a title="Guardar" class="btn btn-default" tabindex="24" id="guardar">
                <img src="../../img/png/grabar.png" width="25" height="30" onclick="guardarPension();">
              </a>
            </div>
            <div class="col-sm-2">
              <a title="Salir del panel" class="btn btn-default" tabindex="25" href="inicio.php?mod=02">
                <img src="../../img/png/salire.png" width="25" height="30" >
              </a>
            </div>
          </div>
          <div class="col-sm-2 text-right no-padding">
            <b>Saldo USD</b>
          </div>
          <div class="col-sm-1 no-padding">
            <input type="text" name="saldoTotal" id="saldoTotal" class="form-control input-xs red text-right" value="0.00" style="color:coral;" onblur="$('#guardar').focus()" tabindex="23" >
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

<!-- Modal porcentaje-->
<div id="myModalDescuentoP" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Porcentaje de descuento</h4>
      </div>
      <div class="modal-body">
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el porcentaje de descuento %">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="calcularDescuento();">Aceptar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal historia del cliente-->
<div id="myModalHistoria" class="modal fade modal-xl" role="dialog">
  <div class="modal-dialog modal-xl" style="width:1250px;height: 400px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Historia del cliente</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="tab-content" style="background-color:#E7F5FF">
              <div id="home" class="tab-pane fade in active">
                <div class="table-responsive" id="tabla_" style="overflow-y: scroll; height:450px; width: auto;">
                  <!-- <div class="sombra" style> -->
                    <table border class="table table-striped table-hover" id="tbl_style" tabindex="14" >
                      <thead>
                        <tr>
                          <th>TD</th>
                          <th>Fecha</th>
                          <th>Serie</th>
                          <th>Factura</th>
                          <th>Detalle</th>
                          <th>Año</th>
                          <th>Mes</th>
                          <th>Total</th>
                          <th>Abonos</th>
                          <th>Mes No</th>
                          <th>No</th>
                        </tr>
                      </thead>
                      <tbody id="cuerpoHistoria">
                      </tbody>
                    </table>
                  <!-- </div> -->
                </div>
              </div>
            </div>  
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xs-2 col-md-1 col-sm-1">
          <a type="button" href="#" class="btn btn-default" onclick="historiaClientePDF();">
            <img title="Generar PDF" src="../../img/png/impresora.png">
          </a>                           
        </div>      
        <div class="col-xs-2 col-md-1 col-sm-1">
          <a type="button" href="#" class="btn btn-default" onclick="historiaClienteExcel();">
            <img title="Generar EXCEL" src="../../img/png/table_excel.png">
          </a>                          
        </div>
        <div class="col-xs-2 col-md-1 col-sm-1">
          <a type="button" class="btn btn-default" onclick="enviarHistoriaCliente();">
            <img title="Enviar a correo" src="../../img/png/email.png">
          </a>                          
        </div>
        
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
