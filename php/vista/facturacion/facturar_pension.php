<?php
  include "../controlador/facturacion/facturar_pensionC.php";
  $facturar = new facturar_pensionC();

  $mostrar_medidor = false;
  switch ($_SESSION['INGRESO']['modulo_']) {
    case '07': //AGUA POTABLE
      $mostrar_medidor =  true;
      break;    
    default:
      
      break;
  }
?>

<script type="text/javascript">
  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("factura").focus();
      e.preventDefault();
    }
  });
  var total = 0;
  var total0 = 0;
  var total12 = 0;
  var iva12 = 0;
  var descuento = 0;

  var tempRepresentante = "";
  var tempCI = "";
  var tempTD = "";
  var tempTelefono = "";
  var tempDirS = "";
  var tempDireccion = "";
  var tempEmail = "";
  var tempGrupo = "";
  var tempCtaNo = "";
  var tempTipoCta = "";
  var tempDocumento = "";
  var tempCaducidad = "";
  $(document).ready(function () {
    autocomplete_cliente();
    catalogoLineas();
    totalRegistros();
    verificarTJ();
    cargarBancos();
    DCGrupo_No();

    DCPorcenIva('fechaEmision', 'DCPorcenIVA');

    document.addEventListener('click', function(event) {
      let backdrop = document.querySelector('.modal-backdrop');
      if (backdrop === event.target) {
        backdrop.parentNode.removeChild(backdrop);
      }
    });

    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?getMBHistorico=true',
      dataType:'json', 
      success: function(data)             
      {
          $("#MBHistorico").val(data.MBHistorico)        
      }
    });

    $("#DCPorcenIVA").change(function() {
         alert('dd');
      });

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

      $(".btnDepositoAutomatico").blur(function(){
        if($('.contenidoDepositoAutomatico').is(':visible') && $('.contenidoDepositoAutomatico').css("display") != "none"){
          var currentTabIndex = parseInt($(this).attr("tabindex"));
          var nextTabIndex = currentTabIndex + 1;
          $("[tabindex='" + nextTabIndex + "']").focus();
        }else{
          $("#checkbox1").focus();
        }
      });

    $(".btnDepositoAutomatico").on('click',function () {
      if($('.contenidoDepositoAutomatico').is(':visible') && $('.contenidoDepositoAutomatico').css("display") != "none"){
        $('.contenidoDepositoAutomatico').css('display', 'none')
      }else{
        $('.contenidoDepositoAutomatico').css('display', 'block')
      }
    })

    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      // var dataM = e.params.data.dataMatricula;
      cambiarlabel()
      $('#email').val(data.EmailR);
      $('#direccion').val(data.direccion);
      $('#direccion1').val(data.direccion1);
      $('#telefono').val(data.Telefono_R);
      $('#codigo').val(data.codigo);
      $('#ci_ruc').val(data.ci_ruc);
      $('#persona').val(data.Representante);
      $('#chequeNo').val(data.grupo);
      $('#codigoCliente').val(data.codigo);
      $('#tdCliente').val(data.tdCliente);
      $('.spanNIC').text(data.TD_R);
      $('#TextCI').val(data.RUC_CI_Rep);
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
      ListarMedidoresHeader($("#CMedidorFiltro"),data.codigo, true)

      //Actualizar cliente
      tempRepresentante = $('#persona').val()
      tempCI  = $('#ci_ruc').val()
      tempTD  = $('#tdCliente').val()
      tempTelefono  = $('#telefono').val()
      tempDireccion  = $('#direccion').val()
      tempDirS  = $("#direccion1").val().toUpperCase()
      tempEmail  = $("#email").val().toUpperCase()
      tempGrupo  = $("#DCGrupo_No").val()
      tempCtaNo  = $('#numero_cuenta_debito_automatico').val()
      tempTipoCta  = $('#tipo_debito_automatico').val()
      tempDocumento  = $('#debito_automatica').val()
      tempCaducidad  = $('#caducidad_debito_automatico').val()

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

    $("#CMedidorFiltro").on('change', function () {
      catalogoProductos($('#codigo').val(), $("#CMedidorFiltro").val());
    })

    cambiarlabel()

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

  function catalogoProductos(codigoCliente, CMedidor="."){
    console.log(codigoCliente);
    console.log(CMedidor);
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogoProducto=true',
      data: {'codigoCliente' : codigoCliente, 'CMedidor' : CMedidor }, 
      dataType:'json', 
      success: function(data)
      {
        if (data) {
          datos = data;
          clave = 1;
          $("#cuerpo").empty();
          let totalItem = datos.length
          for (var indice in datos) {
            if(datos[indice].iva==1)
            {
              subtotal = (parseFloat(datos[indice].valor) + (parseFloat(datos[indice].valor) * parseFloat($('#DCPorcenIVA').val()) / 100)) - parseFloat(datos[indice].descuento) - parseFloat(datos[indice].descuento2);
            }else{
              subtotal = (parseFloat(datos[indice].valor) + (parseFloat(datos[indice].valor) * parseFloat(datos[indice].iva) / 100)) - parseFloat(datos[indice].descuento) - parseFloat(datos[indice].descuento2);
            }
            var tr = `<tr class="tr`+clave+`">
              <td><input ${((totalItem==clave)?`onblur="$('#TextBanco').focus()"`:'')} style="border:0px;background:bottom;" type="checkbox" id="checkbox`+clave+`" onclick="totalFactura('checkbox`+clave+`','`+subtotal+`','`+datos[indice].iva+`','`+datos[indice].descuento+`','`+datos.length+`','`+clave+`')" name="`+datos[indice].mes+`"></td>
              <td><input style="border:0px;background:bottom;max-width: 85px;" type ="text" id="Mes`+clave+`" value ="`+datos[indice].mes+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Codigo`+clave+`" value ="`+datos[indice].codigo+`" disabled/></td>
              <td><input style="border:0px;background:bottom;max-width: 50px;" type ="text" id="Periodo`+clave+`" value ="`+datos[indice].periodo+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Producto`+clave+`" value ="`+datos[indice].producto+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 75px;"  size="10px" type ="text" id="valor`+clave+`" value ="`+parseFloat(datos[indice].valor).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 85px;"  size="10px" type ="text" id="descuento`+clave+`" value ="`+parseFloat(datos[indice].descuento).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 85px;"  size="10px" type ="text" id="descuento2`+clave+`" value ="`+parseFloat(datos[indice].descuento2).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 85px;" size="10px" type ="text" id="subtotal`+clave+`" value ="`+parseFloat(subtotal).toFixed(2)+`" disabled/></td>
              
              <td <?php echo ($mostrar_medidor)?"":'style="display:none"'?>><input class="text-right" style="border:0px;background:bottom;max-width: 65px;" size="10px" type ="text" id="inputLectura`+clave+`" value ="`+datos[indice].Credito_No+`" disabled/></td>
              <td <?php echo ($mostrar_medidor)?"":'style="display:none"'?>><input class="text-right" style="border:0px;background:bottom;max-width: 65px;" size="10px" type ="text" id="inputMedidor`+clave+`"  value ="`+datos[indice].Codigo_Auto+`" disabled/></td>
              
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
      },
      complete: function (argument) {
        $('#myModal_espera').modal('hide');
      }
    });
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
    if(codigoCliente=='')
    {
      codigoCliente = $('#codigo').val();
    }

    if(codigoCliente!=''){
      url = '../controlador/facturacion/facturar_pensionC.php?historiaClienteExcel=true&codigoCliente='+codigoCliente;
      window.open(url, '_blank');
    }else{
      Swal.fire({
          type: 'warning',
          title: 'Seleccione un cliente',
          text: ''
        });
    }
  }

  function historiaClientePDF(){
    codigoCliente = $('#codigoCliente').val();
    if(codigoCliente=='')
    {
      codigoCliente = $('#codigo').val();
    }

    if(codigoCliente!=''){
      url = '../controlador/facturacion/facturar_pensionC.php?historiaClientePDF=true&codigoCliente='+codigoCliente;
      window.open(url,'_blank');
    }else{
      Swal.fire({
          type: 'warning',
          title: 'Seleccione un cliente',
          text: ''
        });
    }
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
    if(email!=""){
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
    }else{
      Swal.fire({
          type: 'warning',
          title: 'Seleccione un cliente',
          text: ''
        });
      
    }
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
        let adicionNombre = (($("#inputMedidor"+i).val()!="." && $("#inputMedidor"+i).val()!="")?" - "+$("#inputMedidor"+i).val():"");
        adicionNombre += (($("#inputLectura"+i).val()!="." && $("#inputLectura"+i).val()!="")?" - "+$("#inputLectura"+i).val():"");
        datosLineas[key] = {
          'Codigo' : $("#Codigo"+i).val(),
          'CodigoL' : $("#CodigoL"+i).val(),
          'Producto' : $("#Producto"+i).val()+adicionNombre,
          'Precio' : $("#valor"+i).val(),
          'Total_Desc' : $("#descuento"+i).val(),
          'Total_Desc2' : $("#descuento2"+i).val(),
          'Iva' : $('#DCPorcenIVA').val(),//$("#Iva"+i).val(),
          'Total' : $("#subtotal"+i).val(),
          'MiMes' : $("#Mes"+i).val(),
          'Periodo' : $("#Periodo"+i).val(),
          'CORTE' : $("#inputLectura"+i).val(),
          'Tipo_Hab' : $("#inputMedidor"+i).val(),
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
      success: function(data){calcularSaldo()}
    });
    // console.log('conti');
    var valor = 0; var descuento = 0; var descuentop = 0; var total = 0;var subtotal = 0;var iva12 = 0; var valor12 = 0;
    for(var i=1; i<datos+1; i++){
      checkbox = "checkbox"+i;
      if($('#'+checkbox).prop('checked'))
      {

        descuento+=parseFloat($('#descuento'+i).val());
        descuentop+=parseFloat($('#descuento2'+i).val());       
        iva = $('#Iva'+i).val()
        if(iva==1)
        {
          iva12+= parseFloat($('#valor'+i).val())*(parseFloat($('#DCPorcenIVA').val())/100) 
          valor12+=parseFloat($('#valor'+i).val());
        }else
        {
           valor+=parseFloat($('#valor'+i).val());
        }
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
    $("#total12").val(parseFloat(valor12).toFixed(2));
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
      }else{
        Swal.fire({
          type: 'info',
          title: 'Por favor indique un valor numerico',
          text: ''
        });
      }
    }else{
      Swal.fire({
        type: 'info',
        title: 'No tiene items a descontar',
        text: ''
      });
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
        DCLinea = $("#DCLinea option:selected").val();
        Cod_CxC = $("#DCLinea option:selected").text();
        TxtDireccion = $("#direccion").val();
        TxtTelefono = $("#telefono").val();
        TextFacturaNo = $("#factura").val();
        Grupo_No = $("#DCGrupo_No").val();
        TextCI = $("#TextCI").val();
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
              'Cod_CxC' : Cod_CxC,
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
              'Documento':debito_automatica, 
              'CTipoCta':tipo_debito_automatico, 
              'TxtCtaNo':numero_cuenta_debito_automatico, 
              'MBFecha':caducidad_debito_automatico, 
              'CheqPorDeposito':por_deposito_debito_automatico, 
              'TextInteres' : TextInteres
            },
            dataType:'json',  
            success: function(response)
            {
              recargarData = true;
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
                      var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+codigoCliente+'&per='+response.per+'&auto='+response.auto;
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
                      var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI+'&per='+response.per+'&auto='+response.auto;
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
                      var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI+'&per='+response.per+'&auto='+response.auto;
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
                       title: 'Error por: ',
                       html: `<div style="width: 100%; color:black;font-weight: 400;">${response.text}</div>`
                     });
                    if(response.respuesta==6){recargarData = false}
                  }
              }else{
                Swal.fire({
                  type: 'info',
                  title: 'La factura ya se autorizo',
                  text: ''
                });
                catalogoProductos(codigoCliente);
              }

              if(recargarData){
                if($('#persona').val()!=""){
                  ClientePreseleccion($('#persona').val());
                }
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
            $('.contenidoDepositoAutomatico').css('display', 'block')
          }else{
            $('.contenidoDepositoAutomatico').css('display', 'none')
          }
        }else{
          $('#debito_automatica').val(null).trigger('change');
          $("#tipo_debito_automatico").val('.');
          $("#numero_cuenta_debito_automatico").val('');
          $("#caducidad_debito_automatico").val('');
          $("#por_deposito_debito_automatico").prop('checked', false);
          $('.contenidoDepositoAutomatico').css('display', 'none')
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

  function Actualiza_Datos_Cliente() {
    if (tempRepresentante !== $('#persona').val() ||
    tempCI !== $('#TextCI').val() ||
    tempTD !== $('#tdCliente').val() ||
    tempTelefono !== $('#telefono').val() ||
    tempDireccion !== $('#direccion').val() ||
    tempDirS !== $("#direccion1").val().toUpperCase() ||
    tempEmail !== $("#email").val().toUpperCase() ||
    tempGrupo !== $("#DCGrupo_No").val() ||
    tempCtaNo !== $('#numero_cuenta_debito_automatico').val() ||
    tempTipoCta !== $('#tipo_debito_automatico').val() ||
    tempDocumento !== $('#debito_automatica').val() ||
    tempCaducidad !== $('#caducidad_debito_automatico').val()) {
      Swal.fire({
          title: 'DESEA ACTUALIZAR DATOS DEL REPRESENTANTE',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {

            let CheqPorDeposito ="0";
            if($('#por_deposito_debito_automatico').prop('checked')){
              CheqPorDeposito = "1";
            }

            $('#myModal_espera').modal('show');
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '../controlador/facturacion/facturar_pensionC.php?ActualizaDatosCliente=true',
              data: {
                "TextRepresentante" : $("#persona").val(),
                "TxtDireccion" : $("#direccion").val(),
                "TxtTelefono" : $("#telefono").val(),
                "Grupo_No" : $("#DCGrupo_No").val(),
                "TextCI" : $("#TextCI").val(),
                "TxtEmail" : $("#email").val().toUpperCase(),
                "TxtDirS" : $("#direccion1").val().toUpperCase(),
                "codigoCliente" : $("#codigoCliente").val(),
                "Documento" : $('#debito_automatica').val(),
                "CTipoCta" : $('#tipo_debito_automatico').val(),
                "TxtCtaNo" : $('#numero_cuenta_debito_automatico').val(),
                "MBFecha" : $('#caducidad_debito_automatico').val(),
                "Label18" : $("#tdCliente").val(),
                "CheqPorDeposito" : CheqPorDeposito
              }, 
              success: function(response)
              {
                $('#myModal_espera').modal('hide');  
                if(response.rps){
                  Swal.fire('¡Bien!', response.mensaje, 'success')
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
    }else{
      Swal.fire({type: 'info',title: 'NO SE ACTUALIZARA DATOS PORQUE USTED NO HA REALIZADO CAMBIOS DEL REPRESENTANTE',text: ''});
    }
  }


  function cambiar_iva(valor)
  {
    codigoCliente = $('#codigo').val();
    catalogoProductos(codigoCliente);
  }
  function cambiarlabel()
  {
     valiva = $("#DCPorcenIVA").val();
     $('#lbl_iva2').text(valiva);
     $('#lbl_iva').text(valiva);
  }

</script>
<style type="text/css"> 
 .contenedor_img{
    display: flex;
    justify-content: center;
    align-items: center;
    background: #e5e5e5;
    min-height: 150px;
    border-radius: 10px;
    max-width: 170px;
}

.mw115{
  max-width: 115px;
}
.centrado_margin{
  margin: 3px auto;
}

tbody tr:nth-child(odd):hover {  background: #DDA !important;}
.filaSeleccionada{
  background: #ccccff !important;
 }

.visible{
  visibility: visible;
}

.no-visible{
  visibility: hidden;
}
input:focus, select:focus, span:focus, button:focus, #guardar:focus, a:focus  {
  border: 2.3px solid #3c8cbb !important;
}
.col{
  display: inline-block;
}

.margin-b-1{
  margin-bottom: 1px !important;
}

.padding-all{
  padding: 2px !important;
}

.bg-amarillo{
  background: #bfc003;
}

.bg-amarillo-suave{
  background-color: #fffec5;
}


.max-width-110{
    max-width: 110px !important;
  }

  .min-width-150{
    min-width: 150px !important;
  }

.colDCLinea{
    width: 80% !important;
  }

@media (max-width: 1541px) {
 
  .colDCLinea{
    width: 92% !important;
  }
  
}

@media (max-width: 1332px) {
 
  .colDCLinea{
    width: 98% !important;
  }
  
}

@media (max-width: 1325px) {
 
  .min-width-150{
    min-width: 140px !important;
  }
  
}

@media (max-width: 1336px) {
 
  .div_fechas_emision, .div_fechas_vencimiento{
    width: 210px !important;
  }
  
   .div_fechas_dc{
    width: 105px !important;
  }
}

@media (max-width: 1286px) {
 
  .min-width-150{
    min-width: 130px !important;
  }
  .div_fechas_emision, .div_fechas_vencimiento{
    width: 180px !important;
  }
}

@media (max-width: 1243px) {
 
  .min-width-150{
    min-width: 125px !important;
  }
  
}

@media (max-width: 1223px) {
 
  .min-width-150{
    min-width: 115px !important;
  }
  
}

@media (max-width: 1178px) {
 
  .min-width-150{
    min-width: 105px !important;
  }
  
}

@media (max-width: 1121px) {
 
  .min-width-150{
    min-width: 90px !important;
  }
  
}

@media (max-width: 1033px) {
 
  .div_fechas_emision, .div_fechas_vencimiento{
    width: 140px !important;
  }
}

@media (max-width: 1029px) {
 
  .min-width-150{
    min-width: 80px !important;
  }
  
}

@media (max-width: 986px) {
  .panel-body .text-right{
    text-align: left !important;
  }
  .min-width-150{
    min-width: 110% !important;
  }
}

@media (max-width: 768px) {
  .min-width-150{
    min-width: 100% !important;
  }
  .colDCLinea{
    margin: 5px 0px;
  }

  .colCliente{
    padding: 3px 0px !important;
  }

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
        <a title="Presenta la historia del cliente" data-toggle="dropdown" class="btn btn-default" >
          <img src="../../img/png/document.png" width="25" height="30">
        </a>
        <ul class="dropdown-menu">
          <li><a href="#" onclick="historiaClientePDF();">En PDF</a></li>
          <li><a href="#" onclick="historiaClienteExcel();">En Excel</a></li>
          <li><a href="#" onclick="enviarHistoriaCliente();">Por Email</a></li>
        </ul>
      </div>
      
      <div class="col">
        <a href="#" title="Presenta la Deuda Pendiente"  class="btn btn-default" onclick="DeudaPensionPDF()">
          <img src="../../img/png/project.png" width="25" height="30">
        </a>
      </div>

      <?php include("prefactura.php") ?>
      
      <div class="col">
        <a href="#" title="Insertar nuevo Beneficiario/Cliente"  class="btn btn-default" onclick="addCliente(1)">
          <img src="../../img/png/group.png" width="25" height="30">
        </a>
      </div>
      
      <div class="col">
        <a href="#" title="Actualizar datos del Cliente"  class="btn btn-default" onclick="Actualiza_Datos_Cliente()">
          <img src="../../img/png/update_user.png" width="25" height="30">
        </a>
      </div>
    </div>
    <div class="col-sm-7 col-xs-12">
      <div class="row">
          <div class="form-group col-xs-6 padding-all margin-b-1">
            <label for="inputEmail3" class="col control-label">Inicio Resumen</label>
            <div class="col">
              <input type="date" class="form-control input-xs" id="MBHistorico" name="MBHistorico" readonly>
            </div>
          </div>

          <div class="form-group col-xs-6 padding-all margin-b-1">
            <label for="exampleInputEmail2"  class="col control-label">Factura No. <span id="numeroSerie" class="red"></span></label>

            <div class="col">
              <input style="  max-width: 110px;" type="input" class="form-control input-xs" tabindex="1" name="factura" id="factura">
            </div>
          </div>
        </div>
    </div>
  </div>

  <div class="row">

    <div class="panel panel-primary col-sm-12" style="  margin-bottom: 5px;">
      <div class="panel-body" style=" padding-top: 5px;">
        <div class="row">
          <div class="col-md-7 padding-all">
            <div class="row">
              
              <div class="form-group col-xs-6 col-md-3 margin-b-1 div_fechas_emision">
                <!-- <label for="inputEmail3" class="col control-label"> -->
                <b>Fecha Emision</b>
              <!-- </label> -->
                <!-- <div class="col"> -->
                  <input tabindex="2" type="date" name="fechaEmision" id="fechaEmision" class="form-control input-xs validateDate mw115" value="<?php echo date('Y-m-d'); ?>" onblur="catalogoLineas();DCPorcenIva('fechaEmision', 'DCPorcenIVA');">
                <!-- </div> -->
              </div>
              <div class="form-group col-xs-6 col-md-3  padding-all margin-b-1 div_fechas_vencimiento">
                <b>Fecha Vencimiento</b>
                <!-- <div class="col"> -->
                  <input type="date" tabindex="3" name="fechaVencimiento" id="fechaVencimiento" class="form-control input-xs validateDate mw115" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
                <!-- </div> -->
              </div>
              <div class="col-md-2 padding-all">
                 <b>I.V.A</b>
                 <select class="form-control input-xs" name="DCPorcenIVA" id="DCPorcenIVA"  tabindex="4" onchange="cambiar_iva(this.value);cambiarlabel()" onblur="cambiarlabel()"></select>
                
              </div>
              <div class="form-group col-xs-12 col-md-4  padding-all margin-b-1 div_fechas_dc">
                <label for="inputEmail3" class="labelDCLinea col control-label no-visible">.</label>
                <div class="col colDCLinea">
                  <br>
                  <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="5" onchange="numeroFactura();">
                  </select>
                </div>
                  <input type="hidden" id="Autorizacion">
                  <input type="hidden" id="Cta_CxP">
              </div>

            </div>

            <div class="row">
              
              <div class="col-xs-12 col-md-3 text-right  padding-all">
                <label class="text-right">Cliente/Alumno (<span class="spanNIC"></span>)</label>
              </div>
              <div class="col-xs-12  <?php echo ($mostrar_medidor)?'col-md-6':'col-md-9' ?> colCliente   padding-all">
                <select class="form-control" id="cliente" name="cliente" tabindex="6">
                  <option value="">Seleccione un cliente</option>
                </select>
                <input type="hidden" name="codigoCliente" id="codigoCliente">
              </div>
              <?php if ($mostrar_medidor): ?>
              <div class="col-xs-12  col-md-3    padding-all">
                <select class="form-control input-xs" id="CMedidorFiltro" name="CMedidorFiltro">  
                  <option value="<?php echo G_NINGUNO ?>">Medidores</option>
                </select>
              </div>
              <?php endif ?>
            </div>

            <div class="row">
              <div class="col-xs-12 col-md-3 text-right  padding-all">
                <select class="form-control input-xs" id="DCGrupo_No" name="grupo" tabindex="8">
                  <option value=".">Grupo</option>
                </select>
              </div>
              <div class="col-xs-12 col-md-9  padding-all">
                <input tabindex="9" type="input" class="form-control input-xs" name="direccion" id="direccion">
              </div>
            </div>

            <div class="row bg-amarillo-suave">
              <div class="col-xs-12 col-md-3 text-right padding-all bg-amarillo">
                <label>Razón social</label>
              </div>
              <div class="col-xs-12 col-md-5 padding-all bg-amarillo-suave">
                <input tabindex="10" type="input" class="form-control input-xs bg-amarillo-suave" name="persona" id="persona">
              </div>
              <div class="col-xs-12 col-md-2 text-right padding-all bg-amarillo">
                <label>CI/RUC(<span class="spanNIC"></span>) </label>
              </div>
              <div class="col-xs-12 col-md-2 text-right padding-all bg-amarillo-suave">
                <input  type="hidden" class="form-control input-xs" name="tdCliente" id="tdCliente" readonly>
                <input type="text" tabindex="11" name="TextCI" id="TextCI" class="form-control input-xs red text-right bg-amarillo-suave">
              </div>
            </div>

            <div class="row bg-amarillo-suave">
              <div class="col-xs-12 col-md-3 text-right padding-all bg-amarillo">
                <label>Dirección</label>
              </div>
              <div class="col-xs-12 col-md-9 padding-all bg-amarillo-suave">
                <input tabindex="12" type="input" class="form-control input-xs  bg-amarillo-suave" style="text-transform: uppercase;" name="direccion1" id="direccion1">
              </div>  
            </div>

            <div class="row bg-amarillo-suave">
              <div class="col-xs-12 col-md-3 text-right padding-all bg-amarillo">
                <label>Email</label>
              </div>
              <div class="col-xs-12 col-md-5 padding-all bg-amarillo-suave">
                <input tabindex="13" type="input" class="form-control input-xs bg-amarillo-suave"style="text-transform: uppercase;" name="email" id="email">
              </div>
              <div class="col-xs-12 col-md-2 text-right padding-all bg-amarillo">
                <label>Telefono </label>
              </div>
              <div class="col-xs-12 col-md-2 text-right padding-all bg-amarillo-suave">
                <input type="text" tabindex="14" name="telefono" id="telefono" class="form-control input-xs red text-right bg-amarillo-suave">
              </div>
            </div>

            <div class="row  bg-info">
              <div class="col-xs-12 text-center">
                <button tabindex="15" style="margin: 8px 0px;background-color: #c0c0fc !important; color: black;" class="btn btn-block btn-info btn-xs btnDepositoAutomatico strong">Ingrese sus datos para el Debito Automatico</button>
              </div>
            </div>
            <div class="row bg-info contenidoDepositoAutomatico" style="display: none;">
              <div class="col-xs-12 col-sm-2 text-right">
                <label for="debito_automatica">Debito Automatico</label>
              </div>
              <div class="col-xs-12 col-sm-6">
                <select tabindex="16"  class="form-control input-xs" name="debito_automatica" id="debito_automatica">
                  <option value="">Seleccione un Banco</option>
                </select>
              </div>

              <div class="col-xs-12 col-sm-1 text-right">
                <label>Tipo</label>
              </div>
              <div class="col-xs-12 col-sm-3">
                <select  tabindex="17" type="input" class="form-control input-xs" name="tipo_debito_automatico" id="tipo_debito_automatico">
                  <option value=".">Seleccionar Tipo</option>
                  <option value="CORRIENTE">CORRIENTE</option>
                  <option value="AHORROS">AHORROS</option>
                  <option value="TARJETA">TARJETA</option>
                </select>
              </div>
            </div>
            <div class="row bg-info contenidoDepositoAutomatico" style="display: none;">
              <div class="col-xs-12 col-sm-2 text-right">
                <label>Numero de Cuenta</label>
              </div>
              <div class="col-xs-12 col-sm-3">
                <input  tabindex="18" type="input" class="form-control input-xs" name="numero_cuenta_debito_automatico" id="numero_cuenta_debito_automatico">
              </div>

              <div class="col-xs-12 col-sm-1 text-right">
                <label>Caducidad</label>
              </div>
              <div class="col-xs-12 col-sm-2 contenedor_fecha_caducidad">
                <input  tabindex="19" type="text" maxlength="7"  class="form-control input-xs fecha_caducidad" name="caducidad_debito_automatico" id="caducidad_debito_automatico" placeholder="MM/YYYY">
              </div>
              <div class="col-xs-6 col-sm-3 text-right">
                <label class="text-right" for="rbl_no">Depositar al Banco</label>
              </div>
              <div class="col-xs-6 col-sm-1 padding-all">
                <input tabindex="20" style="margin-top: 0px;margin-right: 2px;" type="checkbox" name="por_deposito_debito_automatico" id="por_deposito_debito_automatico" onblur="$('#checkbox1').focus()">
              </div>
            </div>

          </div>
          <div class="col-md-2">
            
            <div class="row">
              <div class="col-xs-12">
                <input style="margin-top: 0px;margin-right: 2px;" tabindex="" type="checkbox" name="rbl_radio" id="rbl_no" checked=""> Con mes
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <div class="input-group  ">
                  <span class="input-group-addon strong input-xs">
                    NIC (<span class="spanNIC"></span>)
                  </span>
                  <input type="text" tabindex="7" name="ci" id="ci_ruc" class="form-control input-xs red text-right" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 text-center">
                <div class="contenedor_img centrado_margin">
                  <img src="../img/img_estudiantes/SINFOTO.jpg" id="img_estudiante" class="img-responsive img-thumbnail">
                </div>
              </div>
            </div>

          </div>
          <div class="col-md-3 ">
            <div class="row">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-6 control-label padding-all  max-width-110">Total Tarifa 0%</label>
                <div class="col-xs-6">
                  <input type="text" style="color: coral;" name="total0" id="total0" class="form-control input-xs red text-right min-width-150" readonly value="0.00">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-6 control-label padding-all  max-width-110">Total Tarifa <span id="lbl_iva">0</span>%</label>
                <div class="col-xs-6">
                  <input type="text" style="color: coral;" name="total12" id="total12" class="form-control input-xs red text-right min-width-150" readonly value="0.00">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-6 control-label padding-all  max-width-110">Descuentos</label>
                <div class="col-xs-6">
                  <input type="text" style="color: coral;" name="descuento" id="descuento" class="form-control input-xs red text-right min-width-150" readonly value="0.00">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-6 control-label padding-all  max-width-110">Desc x P P</label>
                <div class="col-xs-6">
                  <div class="input-group input-group-xs  min-width-150">
                    <span class="input-group-btn">
                      <button tabindex="41" style="border: 1px #b1b1b1 solid;border-right: 2px #b1b1b1 solid;padding: 2px;"  type="button" class="btn btn-xs" data-toggle="modal" data-target="#myModalDescuentoP">%</button>
                    </span>
                    <input type="text" style="color: coral;"  name="descuentop" id="descuentop" class="form-control input-xs red text-right" readonly value="0.00">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-6 control-label padding-all  max-width-110">I.V.A. <span id="lbl_iva2"></span>%</label>
                <div class="col-xs-6">
                  <input type="text" style="color: coral;"  name="iva12" id="iva12" class="form-control input-xs red text-right min-width-150" readonly value="0.00">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-6 control-label padding-all  max-width-110">Total Facturado</label>
                <div class="col-xs-6">
                  <input type="text" style="color: coral;"  name="total" id="total" class="form-control input-xs red text-right min-width-150" readonly value="0.00" onblur="$('#TextBanco').focus()">
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
                <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
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
                          <th <?php echo ($mostrar_medidor)?"":'style="display:none"'?>>Lectura</th>
                          <th <?php echo ($mostrar_medidor)?"":'style="display:none"'?>>Medidor</th>
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

        <div class="row" style="margin-top: 8px;">
          <div class="col-sm-2 text-right padding-all max-width-110">
            <label>Detalle del pago</label>
          </div>
          <div class="col-sm-6 padding-all">
            <input type="text" name="TextBanco" id="TextBanco" class="form-control input-xs" value="." tabindex="21">
          </div>

          <div class="col-sm-3 text-right padding-all bg-amarillo">
            <label id="saldo">Saldo pendiente</label>
          </div>
          <div class="col-sm-1  padding-all bg-amarillo-suave">
            <input type="input" id="saldoPendiente" class="form-control input-xs text-right blue saldo_input text-right bg-amarillo-suave min-width-150" name="saldoPendiente">
          </div>
        </div>

        <div class="row">
          <div class="col-sm-2 text-right padding-all max-width-110">
            <label>Bancos/Tarjetas</label>
          </div>
          <div class="col-sm-4 padding-all">
            <select class="form-control input-xs" name="cuentaBanco" id="cuentaBanco" tabindex="29" onchange="verificarTJ();" onblur="$('#chequeNo').focus()">
              <?php
                $cuentas = $facturar->getCatalogoCuentas();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>

          <div class="col-sm-2 text-right padding-all">
            <b>Cheque No.</b>
          </div>
          <div class="col-sm-2 padding-all">
            <input type="text" name="chequeNo" id="chequeNo" class="form-control input-xs text-right" tabindex="30"  >
          </div>

          <div class="col-sm-1 text-right padding-all">
            <label>USD</label>
          </div>
          <div class="col-sm-1 padding-all">
            <input  type="text" name="valorBanco" id="valorBanco" tabindex="31" onkeyup="calcularSaldo();" class="form-control input-xs red text-right min-width-150" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right padding-all max-width-110">
            <label>Anticipos</label>
          </div>
          <div class="col-sm-8 padding-all">
            <select class="form-control input-xs" name="DCAnticipo" id="DCAnticipo" tabindex="32">
              <?php
                $cuentas = $facturar->getAnticipos();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right padding-all">
            <label>USD</label>
          </div>
          <div class="col-sm-1 padding-all">
            <input title="Saldo a Favor" type="input" id="saldoFavor" class="form-control input-xs red text-right min-width-150" name="saldoFavor" tabindex="33" onkeyup="calcularSaldo();" value="0.00" style="color:yellowgreen;">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right padding-all max-width-110">
            <label>Notas de crédito</label>
          </div>
          <div class="col-sm-8 padding-all">
            <select class="form-control input-xs" name="cuentaNC" id="cuentaNC" tabindex="34">
              <?php
                $cuentas = $facturar->getNotasCredito();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right padding-all">
            <label>USD</label>
          </div>
          <div class="col-sm-1 padding-all">
            <input tabindex="35" type="text" name="abono" id="abono" onkeyup="calcularSaldo();" class="form-control input-xs red text-right min-width-150" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-center padding-all max-width-110" style="visibility: hidden;">
            <input type="text" name="codigoB" class="form-control input-xs" id="codigoB" style="color: white; background: brown;" value="Código del banco: " readonly />
          </div>
          <div class="col-sm-2 col-sm-offset-7 text-right padding-all">
            <b>Efectivo USD</b>
          </div>
          <div class="col-sm-1 padding-all">
            <input tabindex="36" type="text" name="efectivo" id="efectivo" onkeyup="calcularSaldo();" class="form-control input-xs red text-right min-width-150" value="0.00"  onblur="$('#saldoTotal').focus()">
          </div>
        </div>
        <div class="row" id="divInteres">
          <div class="col-sm-2 col-sm-offset-5 text-right padding-all">
            <b>Interés Tarjeta USD</b>
          </div>
          <div class="col-sm-1 padding-all">
            <input tabindex="37" type="text" name="interesTarjeta" id="interesTarjeta" class="form-control input-xs red text-right min-width-150" >
          </div>
        </div>
        <div class="row">

          <div class="col-xs-12 col-sm-4 padding-all">
            <div class="col-sm-4 text-right padding-all max-width-110">
              <label>Código interno</label>
               <input type="hidden" name="txt_cant_datos" id="txt_cant_datos" readonly>
            </div>
            <div class="col-xs-6 padding-all">
              <input type="input" class="form-control input-xs" name="codigo" id="codigo" tabindex="41">
            </div>
          </div>

          <div class="col-sm-1 text-center justify-content-center align-items-center no-visible">
            <input style="width: 50px" type="text" id="registros" class="form-control input-xs text-center justify-content-center align-items-center" readonly>
          </div>
          <div class=" col-sm-4 ">
            <div class="col-sm-2 col-sm-offset-2">
              <a title="Guardar" class="btn btn-default" tabindex="39" id="guardar">
                <img src="../../img/png/grabar.png" width="25" height="30" onclick="guardarPension();">
              </a>
            </div>
            <div class="col-sm-2">
              <a title="Salir del panel" class="btn btn-default" tabindex="40" href="inicio.php?mod=02">
                <img src="../../img/png/salire.png" width="25" height="30" >
              </a>
            </div>
          </div>
          <div class="col-sm-2 text-right padding-all max-width-110">
            <b>Saldo USD</b>
          </div>
          <div class="col-sm-1 padding-all">
            <input type="text" name="saldoTotal" id="saldoTotal" class="form-control input-xs red text-right min-width-150" value="0.00" style="color:coral;" onblur="$('#guardar').focus()" tabindex="38" >
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
        <button type="button" class="btn btn-success" onclick="calcularDescuento();">Aceptar</button>
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
