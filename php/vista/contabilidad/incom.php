<style>
   .btn_f {  background-color: #CFE9EF;  color: #444;  border-color: #ddd;}
   .input-group .input-group-addon 
    {
      background-color: #CFE9EF;  color: #444;  border-color: #ddd;  border-bottom-left-radius: 5px;  border-top-left-radius:  5px;}
    #select2-cuentar-results .select2-results__option {   white-space: pre;  }
</style>
  <!-- =========================================INICIO DE PROGRAMACION =================================== -->
<?php
if (!isset($_SESSION))
  session_start();
$T_No = 1;
$SC_No = 0;
$variables_mod = '';
$ModificarComp = 0;
$CopiarComp = 0;
$NuevoComp = 1;
$load = 0;
if (isset($_GET["modificar"])) {
  $variables_mod = $_GET["TP"] . '-' . $_GET["com"];
  $ModificarComp = 1;
}
if (isset($_GET["copiar"])) {
  $variables_mod = $_GET["TP"] . '-' . $_GET["com"];
  $CopiarComp = 1;
}
if (isset($_GET["num_load"])) {
  $load = 1;
}
?>



<script type="text/javascript">
var Trans_No = 1; var Ln_No = 1; var Ret_No = 1; var LnSC_No = 1;
function Form_Activate()
{ 
    var ModificarComp = '<?php echo $ModificarComp; ?>';
    var CopiarComp = '<?php echo $CopiarComp; ?>';
    var NuevoComp    = '<?php echo $NuevoComp; ?>';

  if(ModificarComp==1){
     // Control_Procesos Normal, "Modificar Comprobante de: " & Co.TP & " No. " & Co.Numero
    var comprobante = '<?php echo @$_GET["com"]; ?>';
    var tp = '<?php  echo @$_GET["TP"]; ?>';
     Listar_Comprobante_SP(comprobante,tp);
  }
  
  if(CopiarComp==1)
  {
     // Control_Procesos Normal, "Copiando Comprobante de: " & Co.TP & " No. " & Co.Numero
    var comprobante = '<?php echo @$_GET["com"]; ?>';
    var tp = '<?php  echo @$_GET["TP"]; ?>';
     Listar_Comprobante_SP(comprobante,tp);
     $('#NuevoComp').val(1);
  }

  if(NuevoComp==1)
  {
    // console.log('ingresa nuevo');
     var Numero = 0;
     ExistenMovimientos();
  }
  
  // TipoBusqueda = "%"
  
  Tipo_De_Comprobante_No();
  FormActivate();

 //  Llenar_Encabezado_Comprobante
 //  CalculosTotalAsientos AdoAsientos, LabelDebe, LabelHaber, LabelDiferencia
 //  Una_Vez = True
 // // 'Listamos lista de clientes para procesar comprobantes
  
 //  If UCaseStrg(Modulo) = "GASTOS" Then
 //     OpcTP(0).Visible = True
 //     OpcTP(1).Visible = False
 //     OpcTP(2).Visible = False
 //     OpcTP(3).Visible = False
 //     OpcTP(4).Visible = False
 //  End If
 //  If Bloquear_Control Then CmdGrabar.Enabled = False
 //  RatonNormal
 //  FComprobantes.WindowState = vbMaximized

 //  MBoxFecha.SetFocus
}


</script>



<script type="text/javascript">
    var cli = '<?php if (isset($_GET["cliente"])) {
      echo $_GET["cliente"];
    } ?>';    
    // console.log(cli);
    if(cli!='')
    {
      cargar_beneficiario(cli);
    }

   function ExistenMovimientos()
   {
     var CopiarComp = '<?php echo $CopiarComp; ?>';
      $.ajax({
        url:   '../controlador/contabilidad/incomC.php?ExistenMovimientos=true',
        type:  'post',
        data: {
          'Trans_No': Trans_No,
        },
        dataType: 'json',
        success:  function (response) {
         
         if(response ==1 && CopiarComp == 0){
               Swal.fire({
                 title: 'El Sistema se cerro de forma inesperada, existen movimientos en transito con su codigo de usuario. Desea recuperarlos? ',
                 text: "",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!',
                 allowOutsideClick: false
               }).then((result) => {
                 if (result.value!=true) {
                  borrar_asientos();
                 }
               })
                
             }

        }
      });

   }

  function Listar_Comprobante_SP(com,tp)
  {
     var modificar = '<?php echo $variables_mod; ?>';
      $('#NuevoComp').val(modificar);
      $.ajax({
        url:   '../controlador/contabilidad/incomC.php?CallListar_Comprobante_SP=true',
        type:  'post',
        data: {
          'NumeroComp': com,
          'TP': tp,
        },
        dataType: 'json',
        success:  function (response) {
          Tipo_De_Comprobante_No();
          if(cli=='')
          {
           Llenar_Encabezado_Comprobante();
          }
          FormActivate()
        }
      });
  }

  
  $(document).ready(function () {
    Form_Activate();
    cargar_cuenta();
    var modificar = '<?php echo $variables_mod; ?>';
    var load = '<?php echo $load; ?>';
    // if(modificar!='')
    // {
     
    // }else
    // {
    //   numero_comprobante();
    //   FormActivate()
    // }
    
     $("#btn_acep").blur(function () { if($('#modal_cuenta').hasClass('in')){if($('#txt_efectiv').is(':visible')){$('#txt_efectiv').trigger( "focus" );}else{$('#txt_moneda').trigger( "focus" );}}});


     window.addEventListener("message", function(event) {
        if (event.data === "closeModal") {
            $('#modal_subcuentas').modal('hide');
        }
    });
     window.addEventListener("message", function(event) {
        if (event.data === "closeModalG") {
            $('#modal_subcuentas').modal('hide');
            cargar_tablas_retenciones();
            cargar_tablas_contabilidad();
            cargar_totales_aseintos();
            $("#codigo").val('');            
            $("#cuentar").empty();
        }
    });

     //subcuenta
   window.addEventListener("message", function(event) {
        if (event.data === "closeModalSubCta") {
            $('#modal_subcuentas').modal('hide');
             cargar_tablas_contabilidad();
             cargar_totales_aseintos();
             cargar_tablas_sc();
            $("#codigo").val('');
            $("#cuentar").empty();
        }
    });

  });


function FormActivate() {
    $('#fecha1').focus();
    // numero_comprobante();
    cargar_totales_aseintos();
    autocoplet_bene();
    cargar_cuenta_efectivo();
    cargar_cuenta_banco();
    cargar_cuenta();
    cargar_tablas_contabilidad();
    // cargar_tablas_tab4();
    cargar_tablas_retenciones();
    // cargar_tablas_sc();
    ListarAsientoB();
}
   function autocoplet_bene(){
      $('#beneficiario1').select2({
        placeholder: 'Seleccione una beneficiario',
        width:'90%',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?beneficiario=true&q=.',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }

    function benefeciario_edit()
    {
      var bene = $("#beneficiario1").val();
      var parametros = 
      {
        'beneficiario' : bene,
      }
      $.ajax({
        data:  {parametros:parametros},
         url:   '../controlador/contabilidad/incomC.php?edit_beneficiario=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) {
            bene = bene.split('-');
            $('#ruc').val(bene[0]);
            $('#email').val(bene[1]);

        }
      });       
    }

    function cargar_beneficiario(ci)
    {
      var opcion = '';
      $.ajax({
      // data:  {parametros:parametros},
       url:   '../controlador/contabilidad/incomC.php?beneficiario_C=true&q='+ci+"#",
      type:  'get',
      dataType: 'json',
        success:  function (response) {
          console.log(response);
          var valor = response[0].id;
          var parte = valor.split('-');
           $('#ruc').val(parte[0]);
           $('#email').val(parte[1]);
           $('#beneficiario1').append($('<option>',{value:  response[0].id, text: response[0].text,selected: true }));
      }
    }); 
    }

    function mostrar_efectivo()
    {
      if($('#efec').prop('checked'))
      {
        $('#rbl_efec').css("background-color",'#286090');
        $('#rbl_efec').css("color",'#FFFFFF');
        $('#rbl_efec').css("border-radius",'5px');
        $('#rbl_efec').css("padding",'3px');
        $('#ineg1').css('display','block');
      }else
      {
        $('#rbl_efec').css("background-color",'');
        $('#rbl_efec').css("color",'black');
        $('#rbl_efec').css("border-radius",'');
        $('#rbl_efec').css("padding",'');
        $('#ineg1').css('display','none');
      }
    }

    function mostrar_banco()
    {
      if($('#ban').prop('checked'))
      {
        $('#rbl_banco').css("background-color",'#286090');
        $('#rbl_banco').css("color",'#FFFFFF');
        $('#rbl_banco').css("border-radius",'5px');
        $('#rbl_banco').css("padding",'3px');
        $('#ineg2').css('display','block');
        $('#ineg3').css('display','block');
      }else
      {
        $('#rbl_banco').css("background-color",'');
        $('#rbl_banco').css("color",'black');
        $('#rbl_banco').css("border-radius",'');
        $('#rbl_banco').css("padding",'');
        $('#ineg2').css('display','none');
        $('#ineg3').css('display','none');
      }
    }

    function cargar_cuenta_efectivo()
    {
      var opcion = '';
      $.ajax({
      // data:  {parametros:parametros},
       url:   '../controlador/contabilidad/incomC.php?cuentas_efectivo=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
          // console.log(response);
          $.each(response,function(i,item){
            if(i==0)
            {
              ini = item.id;
            }
            opcion+='<option value="'+item.id+'">'+item.text+'</option>';
          })
          $('#conceptoe').html(opcion);
          $('#conceptoe').val(ini);
                    // console.log(response);
      }
    }); 

      // $('#conceptoe').select2({
      //   placeholder: 'Seleccione cuenta efectivo',
      //   ajax: {
      //     url:   '../controlador/contabilidad/incomC.php?cuentas_efectivo=true',
      //     dataType: 'json',
      //     delay: 250,
      //     processResults: function (data) {
      //       console.log(data);
      //       return {
      //         results: data
      //       };
      //     },
      //     cache: true
      //   }
      // });
    }       

     function cargar_cuenta_banco()
    {
      var opcion = '';
      $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?cuentas_banco=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
          // console.log(response);
          $.each(response,function(i,item){
            if(i==0)
            {
              ini = item.id;
            }
            opcion+='<option value="'+item.id+'">'+item.text+'</option>';
          })
          $('#conceptob').html(opcion);
          $('#conceptob').val(ini);
                    // console.log(response);
      }
    }); 

      // $('#conceptob').select2({
      //   placeholder: 'Seleccione cuenta banco',
      //   ajax: {
      //     url:   '../controlador/contabilidad/incomC.php?cuentas_banco=true',
      //     dataType: 'json',
      //     delay: 250,
      //     processResults: function (data) {
      //       console.log(data);
      //       return {
      //         results: data
      //       };
      //     },
      //     cache: true
      //   }
      // });
    }

     function cargar_cuenta()
    {
      $('#cuentar').select2({
        placeholder: 'Seleccione cuenta',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?cuentasTodos=true',
          data: function (term, page) { return {q: term,  tip:$('#codigo').val()}},
          dataType: 'json',
          delay: 250,          
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }


    function reset_1(concepto,tipo)
    {
      // $('#fecha1').select();
      $('#fecha1').focus();
      var sel = $('#tipoc').val();
      $('#'+sel).removeClass("active");
      if (tipo=='CD') 
      {
        $('#ineg').css('display','none');
        $('#tipoc').val(tipo);
        numero_comprobante();

      }else if(tipo=='CI')
      { 
        $('#tipoc').val(tipo);
        $('#CI').addClass("active");
        $('#tipoc').val(tipo);
        $('#ineg').css('display','block');
        $('#no_cheque').css('display','none');
        $('#ingreso_val_banco').css('display','block');
        $('#deposito_no').css('display','block');
        numero_comprobante();

      }else if(tipo=='CE')
      {

      $('#myModal_espera').modal('show');
        $('#tipoc').val(tipo);
        $('#CE').addClass("active");
        $('#tipoc').val(tipo);
        $('#ineg').css('display','block');
        $('#no_cheque').css('display','block');
        $('#ingreso_val_banco').css('display','none');
        $('#deposito_no').css('display','none');
        numero_comprobante();
        eliminar_todo_asisntoB();

      }else if(tipo=='ND')
      {
        $('#tipoc').val(tipo);
        $('#ND').addClass("active");
        $('#tipoc').val(tipo);
        $('#ineg').css('display','none');
        numero_comprobante();

      }else if(tipo=='NC')
      {
        $('#tipoc').val(tipo);
        $('#NC').addClass("active");
        $('#tipoc').val(tipo);
        $('#ineg').css('display','none');
        numero_comprobante();

      }
    }


    function eliminar_todo_asisntoB()
    {
       $.ajax({
          //data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?EliAsientoBTodos=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
            
              ListarAsientoB();
            $('#myModal_espera').modal('hide');
            } 
          }
        });

    }


      function xml()
    {
      var parametros = 
      {
        'ruc':'1190068753',
        'numero':'9000147',
        'comp':'CD'
      }
       $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?generar_xml=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
            
            } 
          }
        });

    }

  function numero_comprobante()
    {
      var tip = $('#tipoc').val();
      var fecha = $('#fecha1').val();
      if(tip=='CD'){ tip = 'Diario';}
      else if(tip=='CI'){tip = 'Ingresos'}
      else if(tip=='CE'){tip = 'Egresos';}
      else if(tip=='ND'){tip = 'NotaDebito';}
      else if(tip=='NC'){tip= 'NotaCredito';}
      var parametros = 
       {      
         'tip': tip,
         'fecha': fecha,                    
       };
    $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/contabilidad/incomC.php?num_comprobante=true',
      type:  'post',
      dataType: 'json',
      // beforeSend: function () {
      //    $("#num_com").html("");
      // },
      success:  function (response) {
          $("#num_com").html("");
          $("#num_com").html('Comprobante de '+tip+' No. <?php echo date('Y');?>-'+response);
          // var valor = $("#subcuenta1").html(); 
      }
    });
    }

    function agregar_depo()
    {
      var banco = $('#conceptob').val();
      let nom_banco = $('#conceptob option:selected').text();
      nombre = nom_banco.replace(banco,'');
      // console.log(nombre);
      var parametros = 
      {
        'banco':banco,
        'bancoC':nombre,
        'cheque':$('#no_cheq').val(),
        'valor':$('#vab').val(),
        'fecha':$('#fecha1').val(),
        'T_no':$('#tipoc').val(),
      }
      if(banco =='')
      {
        Swal.fire({
        type: 'info',
        title: 'Oops...',
        text: 'Seleccione cuenta de banco!'
            });
        return false;
      }
       $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?asientoB=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
        //       Swal.fire({
        // type: 'success',
        // title: 'Agregado',
        // text: 'ingresado!'
        //     });           
              ListarAsientoB();
            }  else
            {
              Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: 'debe agregar beneficiario!'
            });
            }       
          }
        });
    }

    function validarc(cta,cheque)
    {     
      
      var parametros = 
      {
        'cta':cta,
        'cheque':cheque,
      }
      Swal.fire({
                 title: 'Esta seguro?',
                 text: "Esta usted seguro de que quiere borrar este registro!",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!'
               }).then((result) => {
                 if (result.value==true) {
                  Eliminar(parametros);
                 }else
                 {
                  document.getElementById(id).checked = false;
                 }
               })
    }

    function Eliminar(parametros)
    {
       $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?EliAsientoB=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
              Swal.fire({
                type: 'success',
                title: 'Eliminado',
                text: 'Registro eliminado!'
              });
              ListarAsientoB();
              //
            }  else
            {
              Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: 'No se pudo ejecutar la solicitud!'
            });
            }       
          }
        });
    }

    function abrir_modal_cuenta()
    {      
      $('#modal_cuenta').modal('show');
      var codigo = $('#cuentar').val();      
      $('#codigo').val(codigo);     
      tipo_cuenta(codigo);
    }

    function tipo_cuenta(codigo)
    {
      $.ajax({
          data:  {codigo:codigo},
          url:   '../controlador/contabilidad/incomC.php?TipoCuenta=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            $("#txt_cuenta").val(response.cuenta);
            $("#txt_codigo").val(response.codigo);
            $("#txt_tipocta").val(response.tipocta);
            $("#txt_subcta").val(response.subcta);
            $("#txt_tipopago").val(response.tipopago);
            $("#txt_moneda_cta").val(response.moneda);
            if(response.subcta =='BA')
            {
              $('#panel_banco').css('display','block');
               $('#modal_cuenta').on('shown.bs.modal', function (){
                  $('#txt_efectiv').select();
                });
            }else{
              $('#panel_banco').css('display','none');
               $('#modal_cuenta').on('shown.bs.modal', function (){
                  $('#txt_moneda').select();
                });
            }
      }
        });
    }
    function restingir(campo)
    {
      var valor = $('#'+campo).val();
      var cant = valor.length;
      if(cant>1)
      {
        var num = valor.substr(0,1);
        if(num<3 && num>0)
        {
           $('#'+campo).val(num);
        }else
        {
          $('#'+campo).val('');
        }
      }else
      {
        if(valor<3 && valor>0)
        {
          $('#'+campo).val(valor);
        }else
        {
          $('#'+campo).val('');
        }
      }
    }
    function cambia_foco()
    {
      // alert('ss');
      $('#modal_cuenta').modal('hide');
      $('#va').select();
    }

    function cargar_tablas_contabilidad()
    {
      
      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_contabilidad=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {    
            $('#contabilidad').html(response);      
          }
        });

    }
    function cargar_tablas_sc()
    {
      
      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_sc=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {    
            $('#subcuentas').html(response);      
          }
        });

    }

    function cargar_tablas_retenciones()
    {
      
      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_retencion=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {    
            $('#retenciones').html(response.b+response.r);
            // console.log(response.datos[0]);
            if (response.datos[0]) {
              $('#Autorizacion_R').val(response.datos[0].AutRetencion); 
              $('#Serie_R').val(response.datos[0].EstabRetencion+''+response.datos[0].PtoEmiRetencion); 
              $('#Retencion').val(response.datos[0].SecRetencion);      
            }
          }
        });

    }

    function cargar_tablas_tab4()
    {
      
      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_tab4=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {    
            $('#ac_av_ai_ae').html(response);      
          }
        });

    }

    function cargar_totales_aseintos()
    {
      
      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?totales_asientos=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 

            // console.log(response);     
            $('#txt_diferencia').val(response.diferencia.toFixed(2));  
            $('#txt_debe').val(response.debe.toFixed(2));  
            $('#txt_haber').val(response.haber.toFixed(2));  
            $('#txt_cta_modificar').val(response.Ctas_Modificar);  
          }
        });

    }

    function ingresar_asiento()
    {
      var partes = '';
    if($('#cuentar option:selected').text().length > 0){
      partes = $('#cuentar option:selected').text();
    }else{
      partes = $('#aux').val();
    }
    var bene = $('#beneficiario1').val();
    var partes = partes.split('-');
    var dconcepto1 = partes[1].trim();
    var codigo = $("#codigo").val();
    var efectivo_as = $("#txt_efectiv").val();
    var chq_as = $("#txt_cheq_dep").val();
    var moneda = $("#txt_moneda").val();
    var cotizacion = $("#cotizacion").val();
    var con = $("#con").val();
    var tipo_cue = $("#txt_tipo").val();
    var valor = $('#va').val();
    if(moneda==2)
    {
      Swal.fire({type: 'error', title: 'Oops...', text: 'No se puede agregar cotizacion vacia o cero!'});
    }

    var parametros = 
      {
        "va" : valor,
        "dconcepto1" : '.',
        "codigo" : codigo,
        "cuenta" : dconcepto1,
        "efectivo_as" : efectivo_as,
        "chq_as" : chq_as,
        "moneda" : moneda,
        "tipo_cue" : tipo_cue,
        "cotizacion" : cotizacion,
        "con" : con,
        "t_no" : '1',
        "bene":bene,
        "ajax_page": 'ing1',                        
      };
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?ing1=true',
      type:  'post',
      dataType: 'json',
      // beforeSend: function () {
      //    $("#tab1default").html("");
      // },
      success:  function (response) {

        // console.log(response.resp);
        if(response.resp==1)
        {
         cargar_tablas_contabilidad();
         cargar_totales_aseintos();
         $('#codigo').val('');      
         $('#cuentar').empty();
         $('#va').val('0.00');  
         $('#codigo').select();  
         $('#aux').val('');              
        }else if(response.resp==-2)
        {
          Swal.fire('Puede ser que ya exista un registro','','info');
        } else if(response.resp==-3)
        {
          Swal.fire(response.obs,'','warning');
        }                 
      }
    });
    }

    function subcuenta_frame()
    {
      var deha = $('#txt_tipo').val();
      var moneda = $('#txt_moneda').val();
      if(deha=='' || moneda=='')
      {
        return false;
      }

      var tipo = $('#txt_subcta').val();
      var cta = $('#txt_codigo').val();
      var tipoc = $('#tipoc').val();
      $('#modal_cuenta').modal('hide');
      if(tipo == 'C' || tipo =='P' || tipo == 'G' || tipo=='I' || tipo=='PM' || tipo=='CP')
      {
        titulos(tipo);
        var src ="../vista/modales.php?FSubCtas=true&mod=&tipo_subcta="+tipo+"&OpcDH="+deha+"&OpcTM="+moneda+"&cta="+cta+"&tipoc="+tipoc+"#";
        $('#modal_subcuentas').modal('show');
        $('#titulo_frame').text('Ingreso de sub cuenta por cobras');
        $('#frame').attr('src',src).show();
         adjustIframeHeight(300);
      }else if(tipo=="CC")
      {
        $('#modal_CC').modal('show');
        $('#titulo_frame_cc').text('Ingresar Subcuentas de Proceso');
        var tmp = $('#codigo').val();
        $('#titulo_aux').text('CENTRO DE COSTOS PARA: ' + tmp + " - " + $('#txt_cuenta').val());
         load_subcuentas();
      }else
      {
        cambia_foco();
      }
    }
     function titulos(tc)
    {
      switch(tc) {
        case 'C':
           $('#titulo_frame').text("Ingreso se Subcuenta por Cobrar");
          break;
        case 'P':
           $('#titulo_frame').text("Ingreso se Subcuenta por Pagar");
          break;
          case 'G':
           $('#titulo_frame').text("Ingreso se Subcuenta de Gastos");
          break;
          case 'I':
           $('#titulo_frame').text("Ingreso se Subcuenta de Ingreso");
          break;
          case 'CP':
           $('#titulo_frame').text("Ingreso se Subcuenta por Cobrar");
          break;
          case 'PM':
           $('#titulo_frame').text("Ingreso se Subcuenta de Ingreso");
           break;
    }
  }

  function load_subcuentas(){
    var tmp = $('#cuentar option:selected').text();
    $('#aux').val(tmp);
    if ($('#myTable tbody tr').length > 0) {
        // La tabla ya tiene datos, por lo que no hacemos nada
        return;
    }
    parametros = {
      'SubCtaGen':$('#codigo').val(),
      'SubCta':"CC",
      'OpcTM':$('#txt_moneda').val(),
      'OpcDH':$('#txt_tipo').val()
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?load_subcuentas=true',
      type:  'post',
      dataType: 'json',
      success:  function (data) {
         $('#tablaContenedor').html(data);
         table_lost_focus();
         get_cell_focus();
      }
    });
  }

  function table_lost_focus(){
    $('#myTable td[contenteditable="true"]').on('focusout', function(){
      var suma = 0;
      $('#myTable td[contenteditable="true"]').each(function() {
        var valor = parseFloat($(this).text()) || 0; // Convertir el texto a número y asegurar que sea 0 si no es numérico
        suma += valor;
      });
      $('#total_cc').val(suma.toFixed(2));
    });
  }

  function get_cell_focus(){
    $('#myTable').on('focus', 'td[contenteditable="true"]', function() {
        var $this = $(this);
        setTimeout(function() {
            var range = document.createRange();
            var selection = window.getSelection();
            range.selectNodeContents($this.get(0));
            selection.removeAllRanges();
            selection.addRange(range);
        }, 1);
    });

    // Mover el foco a la siguiente celda editable cuando se presiona Tab
    $('#myTable').on('keydown', 'td[contenteditable="true"]', function(e) {
        if (e.keyCode === 9) { // Tecla Tab
            e.preventDefault(); // Prevenir el comportamiento predeterminado
            var $next = $(this).next('td[contenteditable="true"]');
            if ($next.length) {
                $next.focus(); // Mover el foco a la siguiente celda editable
            } else {
                // Si es la última celda de la fila, moverse a la primera celda de la siguiente fila
                var $nextRowFirstCell = $(this).closest('tr').next().find('td[contenteditable="true"]').first();
                if ($nextRowFirstCell.length) {
                    $nextRowFirstCell.focus();
                } else {
                    // Si es la última celda de la última fila, volver a la primera celda de la tabla
                    $('#myTable td[contenteditable="true"]').first().focus();
                }
            }
        }
    });
  }

  function Commandl_Click(){
    parametros = {
      'SubCtaGen':$('#codigo').val(),
      'SubCta':"CC",
      'OpcTM':$('#txt_moneda').val(),
      'OpcDH':$('#txt_tipo').val()
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?Commandl_Click=true',
      type:  'post',
      dataType: 'json',
      success:  function (data) {
        $('#modal_CC').modal('hide');
        var SumatoriaSC = $('#total_cc').val();
        $('#va').val(SumatoriaSC);
        $('#cuentar').empty();
        console.log($('#aux').val());
      }
    });
  }

  

  function Command2_Click(){
    parametros = {
      'SubCtaGen':$('#codigo').val(),
      'SubCta':"CC",
      'OpcTM':$('#txt_moneda').val(),
      'OpcDH':$('#txt_tipo').val()
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?Command2_Click=true',
      type:  'post',
      dataType: 'json',
      success:  function (data) {
        $('#modal_CC').modal('hide');
        $('#cuentar').empty();
        console.log($('#aux').val());
      }
    });
  }



    function recarar()
    {

    cargar_tablas_contabilidad();
    cargar_tablas_tab4();
    cargar_tablas_retenciones();
    cargar_tablas_sc();

    }
    function cargar_modal()
    {
      var cod = $('#codigo').val();
      $('#tablaContenedor').html('');
      $('#total_cc').val('0.00');
      switch(cod) {
        case 'AC':
        case 'ac':
           var prv = '000000000';
           var ben = '.';
           if($('#beneficiario1').val()!='')
           {
             prv = $('#ruc').val();
             ben = $('#beneficiario1 option:selected').text();
           }else
           {
             Swal.fire('Seleccione beneficiario','','info');
             return false;
           }
            eliminar_ac();
             //borrar_asientos();
           $('#titulo_frame').text("COMPRAS");
          
           var fec = $('#fecha1').val();
           var opc_mult = $('#con').val();
           var src ="../vista/modales.php?FCompras=true&mod=&prv="+prv+"&ben="+ben+"&fec="+fec+"&opc_mult="+opc_mult+"&tipo=";
           $('#frame').attr('src',src).show();

           // $('#frame').css('height','100%').show();
           adjustIframeHeight();

           $('#modal_subcuentas').modal('show');
          break;
        case 'AV':
        case 'av':
            var prv = '000000000';
           var ben = '.';
           if($('#beneficiario1').val()!='')
           {
             prv = $('#ruc').val();
             ben = $('#beneficiario1 option:selected').text();
           }
           var fec = $('#fecha1').val();
           var src ="../vista/modales.php?FVentas=true&mod=&prv="+prv+"&ben="+ben+"&fec="+fec+"#";
           $('#frame').attr('src',src).show();

           // $('#frame').css('height','100%').show();
           adjustIframeHeight();

           $('#titulo_frame').text("VENTAS");
           $('#modal_subcuentas').modal('show');
          break;
          case 'AI':
          case 'ai':
           var src ="../vista/modales.php?FImportaciones#";
           $('#frame').attr('src',src).show();
           // $('#frame').css('height','450px').show();
            adjustIframeHeight(); 
           $('#titulo_frame').text("IMPORTACIONES");
           $('#modal_subcuentas').modal('show');
          break;
          case 'AE':
          case 'ae':
          var src ="../vista/modales.php?FExportaciones#";
           $('#frame').attr('src',src).show();
           // $('#frame').css('height','500px').show();

            adjustIframeHeight();
           $('#titulo_frame').text("EXPORTACIONES");
           $('#modal_subcuentas').modal('show');

          break;
          default:
             $('#cuentar').select2('open');
          break;
    }
  }

  function validar_comprobante()
  {
    var debe =$('#txt_debe').val();
    var haber = $('#txt_haber').val(); 
    var ben = $('#beneficiario1').val();
    var fecha = $('#fecha1').val();
    var tip = $('#tipoc').val();
    var ruc = $('#ruc').val();
    var concepto = $('#concepto').val();
    var haber = $('#txt_haber').val();
    var com = $('#num_com').text();
     var modificar = '<?php echo $NuevoComp; ?>';
    // var comprobante = com.split('.');
    if((debe != haber) || (debe==0 && haber==0) )
    {
      Swal.fire( 'Las transacciones no cuadran correctamente corrija los resultados de las cuentas','','info');
      return false;
    }
    if(ben =='')
    {      
      ben = '.';
    }
    var parametros = 
    {
      'ruc': ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
      'tip':tip,//tipo de cuenta contable cd, etc
      "fecha": fecha,// fecha actual 2020-09-21
      'concepto':concepto, //detalle de la transaccion realida
      'totalh': haber, //total del haber
      'num_com':com,
      'CodigoB':$('#ruc').val(),
      'Serie_R':$('#Serie_R').val(),
      'Retencion':$('#Retencion').val(),
      'Autorizacion_R':$('#Autorizacion_R').val(),
      'Autorizacion_LC':$('#Autorizacion_LC').val(),
      'TD':'C',
      'bene':$('select[name="beneficiario1"] option:selected').text(),
      'email':$('#email').val(),
      'Cta_modificar':$('#txt_cta_modificar').val(),
      'T':'N',
      'monto_total':$('#VT').val(),
      'Abono':$('#vae').val(),
      'TextCotiza':$("#cotizacion").val(),
      'NuevoComp':modificar,
    }

    // console.log(parametros);
    // return false;
    Swal.fire({
      title: "Esta seguro de Grabar el "+$('#num_com').text(),
      text: "con fecha: "+$('#fecha1').val(),
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si!'
    }).then((result) => {
      if (result.value==true) {
         grabar_comprobante(parametros);
      }else
      {
        // alert('cancelado');
      }
    })
  }

  function grabar_comprobante(parametros)
  {  

    if($('#beneficiario1').val()=='')
    {
      Swal.fire('seleccione un beneficiario','','info')
      return false;
    }    
        $('#myModal_espera').modal('show');  
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?generar_comprobante=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {
               $('#myModal_espera').modal('hide');
        if(response.respuesta == '3')
        {
          Swal.fire('Este documento electronico ya esta autorizado','','error');

          }else if(response.respuesta == 1)
          {
            // Swal.fire('Este documento electronico autorizado','','success');
             eliminar_ac();
             var texto ="";
             var tipo ="success";
             if(response.aut_res==1){texto = ' y Documento electronico Autorizado'; tipo ='success'; }
             if (response.aut_res==2) { tipo_error_sri(response.clave); texto = ' y Documento electronico No autorizado'; tipo = 'warning';
           }
             Swal.fire( ((parametros.NuevoComp==0)?'Comprobante Modificado '+texto: "Comprobante Generado "+texto),"",tipo).then(function(){ 
             eliminar_todo_asisntoB();
             cargar_tablas_contabilidad();
             cargar_tablas_tab4();
             cargar_tablas_retenciones();
             cargar_tablas_sc();
             numero_comprobante();
             url = "../controlador/contabilidad/comproC.php?reporte&comprobante="+response.NumCom+"&TP="+parametros['tip'];
             window.open(url,"_blank");

             });
                         
          }else if(response.respuesta == '2')
          {
            Swal.fire('XML devuelto','','info',);
            descargar_archivos(response.url,response.ar);

          }else if(response.respuesta == '-2')
          {
            Swal.fire('Falta de Ingresar datos.','','info',);
          }
          else
          {
            Swal.fire('Error por: '+response.respuesta,'','info');
          }

          // if(response.respuesta==1 || response==1)
          // {
          //    Swal.fire('Retencion ingresada','','success');
          //    eliminar_todo_asisntoB();
          //    cargar_tablas_contabilidad();
          //    cargar_tablas_tab4();
          //    cargar_tablas_retenciones();
          //    cargar_tablas_sc();
          //    numero_comprobante();
          //    url = "../controlador/contabilidad/comproC.php?reporte&comprobante=1000195&TP=CD";
          //    window.open(url,"_blank");
          // }           

          }
        });
  }
  function descargar_archivos(url,archivo)
    {

       // <a href="../comprobantes/entidades/entidad_001/CE_001/No_autorizados/1006202107179033180600110010011220000001234567815.xml" download="1006202107179033180600110010011220000001234567815.xml"><span class="info-box-text">
       //    Aplicacion PUCE para android</span>
       //    </a>
      var url1 = url+archivo;

      // console.log(url1);
            var link = document.createElement("a");
            link.download = archivo;
            link.href =url1;
            link.click();            
  }


  function eliminar_ac()
  {
    $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?eliminar_retenciones=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                
              }else
              {
                Swal.fire( 'No se pudo Eliminar','','error');
              }

          }
        });

  }

    function borrar_asientos()
  {
    $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?borrar_asientos=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                cargar_tablas_contabilidad();
                cargar_tablas_retenciones();
                cargar_tablas_sc();
                cargar_tablas_tab4();
                cargar_totales_aseintos();                
              }else
              {
                Swal.fire( 'No se pudo Eliminar','','error');
              }

          }
        });

  }



  function eliminar(codigo,tabla,ID)
  {
     var parametros = 
    {
      'tabla':tabla,
      'Codigo':codigo,
      'ID':ID,
    }

    Swal.fire({
      title: 'Esta seguro de eliminar este registro',
      text: "",
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'OK!'
    }).then((result) => {
      if (result.value==true) {
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?eliminarregistro=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                 cargar_tablas_contabilidad();
                 cargar_tablas_tab4();
                 cargar_tablas_retenciones();
                 cargar_tablas_sc();
                 cargar_totales_aseintos();
              }

          }
        });                    
      }
    });
  }

  function ListarAsientoB()
  {
    $('#div_tabla').empty();
     $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?ListarAsientoB=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 

             // console.log(response);     
            $('#div_tabla').html(response);  
          }
        });
  }

  function Llenar_Encabezado_Comprobante()
  {
     var parametros = $('#NuevoComp').val();
     $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?Llenar_Encabezado_Comprobante=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              // console.log(response);
              $('#beneficiario1').append($('<option>',{value: response.CodigoB, text:response.beneficiario,selected: true }));
              $('#ruc').val(response.RUC_CI);
              $('#concepto').val(response.Concepto);
              $('#email').val(response.email);
              $('#fecha1').val(response.fecha);

          }
        });

  }
  function Tipo_De_Comprobante_No()
  {
    var parametros = $('#NuevoComp').val();
     $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?Tipo_De_Comprobante_No=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 

            $("#num_com").html("");
            $("#num_com").html(response);
          }
        });
  }

  function listar_comprobante()
  {
    var parametros = $('#NuevoComp').val();
     $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?listar_comprobante=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 

             // console.log(response);     
            $('#div_tabla').html(response);  
          }
        });
  }



  function saltar()
  {
    valor = $('#txt_moneda').val();
    hd = $('#txt_tipo').val();
    if(valor=='')
    {
      $('#txt_moneda').select();
    }
    if(hd=='')
    {
      $('#txt_moneda').select();
    }

    if(valor!='' && hd!='')
    {
      subcuenta_frame();
    }
  }

  function adjustIframeHeight(medida=false) {
    var iframe = window.parent.document.getElementById('frame'); // Reemplaza 'miIframe' con el ID de tu iframe
    menos = 200;
    if(medida)
    {
       menos = medida;
    }
    if (iframe) {
      iframe.style.height = (document.documentElement.scrollHeight-menos) + 'px';
    }
  }

  function validar_fecha()
  {
    if($('#beneficiario1').val()=='')
    {
      $('#beneficiario1').select2('open');
    }

    var modificar = '<?php echo @$variables_mod; ?>';
    if(modificar=='')
    {
      numero_comprobante();
    }
  }

</script>

  <div class="box-body">
    
    <div width='100%'>
      <div style=" float: left;width:30%" align='left' width='30%'>
        <button type="button" class="btn btn-default btn-xs active" onclick="reset_1('comproba','CD');" 
        id='CD' style="width: 15%;" title='Comprobante diario'>Diario</button>
        <button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','CI');" 
        id='CI' style="width: 15%;" title='Comprobante de ingreso'>Ingreso</button>
        <button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','CE');" 
        id='CE' style="width: 15%;" title='Comprobante de egreso'>Egreso</button>
        <button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','ND');" 
        id='ND' style="width: 15%;" title='Comprobante nota de debito'>N/D</button>
        <button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','NC');" 
        id='NC' style="width: 15%;" title='Comprobante nota de credito'>N/C</button>
        <input id="tipoc" name="tipoc" type="hidden" value="CD">
        <input type="hidden" name="NuevoComp" id="NuevoComp">
        <input type="hidden" name="num_load" id="num_load" value="0">

      </div>                      
      <div align='' width='40%'  style="float: left;width:40%; ">
        <div align='top' style="float: top;">
          <h4 align='center' style="float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;" id='num_com'>
            Comprobante de Diario No. 0000-00000000
          </h4>
        </div>
      </div>
    
      <div class="checkbox" align='right' width='30%' style=" float: right;width:30%">
        <label>
          <input type="checkbox"> Imprimir copia
        </label>
      </div>
    </div>
    <div class="box table-responsive">
    
            <div class="box-header">      
        
                <form action="#" class="credit-card-div" id='formu1'>
                  <div class="panel panel-default" >
                    
                    <div class="panel-heading">
                      <div class="row " style="padding-bottom: 5px;">
                        
                        <div class="col-md-3 col-sm-2 col-xs-2">                          
                          <!-- <div class="form-group"> -->
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>FECHA:</b>
                                 </div>
                                 <input type="date" class="form-control input-xs" id="fecha1" placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>' maxlength='10' size='15' onblur="validar_fecha();fecha_valida(this)">
                               </div>
                          <!-- </div> -->
                        </div>
                        <div class="col-md-6 col-sm-7 col-xs-7">
                          <!-- <div class="form-group"> -->
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>BENEFICIARIO:</b>
                                 </div>                        
                              <select id="beneficiario1" name='beneficiario1' class='form-control' onchange="benefeciario_edit()">
                                <option value="">Seleccione beneficiario</option>                                
                              </select>
                              <input type="hidden" name="beneficiario2" id="beneficiario2" value='' />
                               </div>
                          <!-- </div> -->
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-xs-3">
                          <!-- <div class="form-group"> -->
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>R.U.C / C.I:</b>
                                 </div>
                                 <input type="text" class=" form-control input-xs" id="ruc" name='ruc' placeholder="R.U.C / C.I" value='000000000' maxlength='30' size='25' onblur="" onkeyup="solo_numeros(this)">
                               </div>
                          <!-- </div> -->
                        </div>
                        
                      </div>
                      <div class="row ">
                         <div class="col-md-4 col-sm-4 col-xs-4">
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>Email:</b>
                                 </div>
                                 <input type="email" class="form-control input-xs" id="email" name="email" placeholder="prueba@prueba.com" 
                            maxlength='255' size='100'/>
                               </div>
                        </div>        


                      <!--   <div class="col-md-6 col-sm-6 col-xs-6">
                          <div class="input-group">
                            <div class="btn_f input-xs col-sm-12 text-center">
                              <b>EMAIL:</b>
                            </div>
                                <input type="email" class="form-control input-xs" id="email" name="email" placeholder="prueba@prueba.com" 
                            maxlength='255' size='100'/>
                          </div>
                        </div> -->
                        <div class="col-md-2 col-sm-3 col-xs-3">
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>COTIZACION:</b>
                                 </div>
                                 <input type="text" class="form-control input-xs" id="cotizacion" name='cotizacion' placeholder="0.00" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)" style="text-align:right; width: 70px;" maxlength='20' />
                               </div>
                        </div>     

                      <!--   <div class="col-md-2 col-sm-2 col-xs-2">
                          <div class="input-group">
                            <div class="btn_f input-xs col-sm-12 text-center">
                              <b>COTIZACIÓN:</b>
                            </div>
                                <input type="text" class="form-control input-xs" id="cotizacion" name='cotizacion' placeholder="0.00" onKeyPress='return soloNumerosDecimales(event)' style="text-align:right;" maxlength='20' size='25' />
                          </div>
                        </div> -->

                        <div class="col-md-3 col-sm-3 col-xs-3">
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>Tipo de conversión  :</b>
                                 </div>
                                  <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(/)</span>
                                <input type="radio" checked="checked" name="con" id='con' value='/'>
                                <span class="checkmark"></span>
                              </label>        
                              <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(X)</span>
                                <input type="radio" name="con" id='con' value='X'>
                                <span class="checkmark"></span>
                              </label> 
                               </div>
                        </div>   

                    <!--     <div class="" style="float: left;position:relative;left:1%;width: 10%;margin-bottom: 1px;">
                            <label class="labeltext" style="margin-bottom: 1px;">Tipo de conversión</label><br>
                            <div class="">
                              <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(/)</span>
                                <input type="radio" checked="checked" name="con" id='con' value='/'>
                                <span class="checkmark"></span>
                              </label>        
                              <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(X)</span>
                                <input type="radio" name="con" id='con' value='X'>
                                <span class="checkmark"></span>
                              </label>
                            </div>
                        </div> -->

                         <div class="col-md-3 col-sm-3 col-xs-3">
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>VALOR TOTAL:</b>
                                 </div>
                                <input type="text" class="form-control input-xs" id="VT" name='VT' placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' maxlength='20' size='33' readonly="">
                               </div>
                         </div>     


                        <!-- <div class="col-md-2 col-sm-2 col-xs-2">
                          <div class="input-group">
                            <div class="btn_f input-xs col-sm-12 text-center">
                              <b>VALOR TOTAL:</b>
                            </div>
                                <input type="text" class="form-control input-xs" id="VT" name='VT' placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' maxlength='20' size='33' readonly="">
                          </div>
                        </div> -->
                      </div>
                      <div id='ineg' class="row" style="display: none;"> <br>
                        <div class="row">
                          <div class="col-sm-1" style="padding-right: 0px;">
                            <label class="label-inline" id="rbl_efec"><input type="checkbox" id='efec' name='efec'onclick="mostrar_efectivo()" /> Efectivo</label>
                          </div>
                          <div class="col-sm-10" id="ineg1" style="display: none;">
                            <div class="row">
                              <div class="col-sm-9">
                                <div class="input-group">
                                  <div class="input-group-addon input-xs">
                                    <b>CUENTA:</b>
                                  </div>
                                  <select class="form-control input-xs" name="conceptoe" id='conceptoe'>
                                   <option value="">Seleccione cuenta de efectivo</option>
                                  </select>
                                </div>                            
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                       <b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
                                     </div>
                                     <input type="text" class="form-control input-xs" id="vae" name='vae' placeholder="0.00" style="text-align:right;" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)" maxlength='20' size='13'>
                                   </div>                           
                              </div>                               
                            </div>                                                  
                          </div>                        
                        </div>
                        <div class="row">
                          <div class="col-sm-1" style="padding-right: 0px;">
                            <label class="label-inline" id="rbl_banco" style="background:rgb(40, 96, 144) ;color: #FFFFFF;padding:5px;border-radius: 5px;"><input type="checkbox" id='ban' name='ban'onclick="mostrar_banco()" checked="" /> Banco</label>
                          </div>
                          <div class="col-sm-10" id='ineg2'>
                            <div class="row">
                              <div class="col-md-9">
                              <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                         <b>CUENTA:</b>
                                     </div>
                                     <select class="form-control input-xs" name="conceptob" id='conceptob'>
                                         <option value="">Seleccione cuenta de banco</option>
                                    </select>
                                  </div>                            
                            </div>
                            <div class="col-md-3"  id="ingreso_val_banco">
                              <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                         <b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
                                     </div>
                                     <input type="text" class="form-control input-xs" id="vab" name='vab' placeholder="0.00" 
                                style="text-align:right;"  onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)"
                                maxlength='20' size='13' value='0.00'>
                                  </div>  
                            </div> 
                            <div class="col-md-3" id="no_cheque" style="display: none;">
                              <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                         <b>No. Cheq:</b>
                                     </div>
                                     <input type="text" class="form-control input-xs" id="no_cheq" name='no_cheq' placeholder="00000001" 
                                style="text-align:right;"  onKeyPress='return soloNumerosDecimales(event)' 
                                maxlength='20' size='13' value='00000001' onblur="agregar_depo()">
                                  </div>  
                            </div>
                            </div>
                            


                            
                          </div>                          
                        </div>
                        <div class="row" id='ineg3' >
                          <div class="col-md-8">
                            <div id="div_tabla">
                             
                            </div>
                            <input type="hidden" id='reg1' name='reg1'  value='' />
                          </div>
                          <div class="col-md-2">
                            <div class="input-group">
                                <div class="btn_f input-xs col-sm-12 text-center">
                                  <b>Efectivizar:</b>
                                </div>
                                <input type="date" class="form-control input-xs" id="efecti" name='efecti' placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>' onblur="fecha_valida(this)">
                              </div>                            
                          </div>
                          <div class="col-md-2">
                            <div class="input-group" id="deposito_no">
                                <div class="btn_f input-xs col-sm-12 text-center">
                                  <b>Deposito No:</b>
                                </div>
                                <input type="text" class="form-control input-xs" id="depos" onkeyup="solo_numeros(this)" name='depos' placeholder="12345" onblur="agregar_depo()">
                              </div>
                          </div>                          
                        </div>                      
                      </div>

                      <div class="row " style="padding-bottom: 5px;"><br> 
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <div class="input-group">
                                 <div class="input-group-addon input-xs">
                                   <b>CONCEPTO:</b>
                                 </div>
                                <input type="text" class="form-control input-xs" id="concepto" name="concepto" placeholder="concepto" maxlength='150'/>
                               </div>
                        </div>                        
                      </div>

                      <div class="row">
                        <div class="col-md-2 col-sm-1 col-xs-1">
                          <div class="input-group">
                            <div class="input-xs col-md-12 btn_f text-center">
                              <b>CODIGO:</b>
                            </div>
                             <input type="text" class="form-control input-xs" id="codigo" name='codigo' placeholder="codigo" maxlength='30' size='12' onblur="cargar_modal();" onkeyup="mayusculas('codigo',this.value)" />
                          </div>
                        </div>
                        <div class="col-md-8 col-sm-9 col-xs-9">
                               <div class="input-group" style="display: block;">
                                 <div class="btn_f input-xs col-md-12 text-center">
                                  <b>DIGITE LA CLAVE O SELECCIONE LA CUENTA:</b>
                                 </div>
                                 <select id="cuentar" class=" form-control input-xs" onchange="abrir_modal_cuenta()">
                                  <option value="">Seleccione una cuenta</option>   
                                 </select>
                                   <!--  <input type="text" class="xs" id="cuenta" name='cuenta' placeholder="cuenta" maxlength='70' size='153'/>
                                    <input type="hidden" id='codigo_cu' name='codigo_cu' value='' />-->
                                    <input type="hidden" id='aux' name='TC'  value='' />
                               </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                               <div class="input-group">
                                 <div class="btn_f input-xs col-md-12 text-center">
                                  <b>VALOR:</b>
                                 </div>
                                   <input type="text" class="form-control input-xs" id="va" name='va' 
                              placeholder="0.00" style="text-align:right;"  onkeyup="validar_numeros_decimal(this)" onblur="ingresar_asiento();validar_float(this,2)" value="0.00">
                               </div>
                        </div>
                      </div>
                      <div class="row">
                        <input type="hidden" name="txt_cuenta" id="txt_cuenta">
                        <input type="hidden" name="txt_codigo" id="txt_codigo">
                        <input type="hidden" name="txt_tipocta" id="txt_tipocta">
                        <input type="hidden" name="txt_subcta" id="txt_subcta">
                        <input type="hidden" name="txt_tipopago" id="txt_tipopago">
                        <input type="hidden" name="txt_moneda_cta" id="txt_moneda_cta">   
                        <input type="hidden" name="Serie_R" id="Serie_R" value=".">  
                        <input type="hidden" name="Retencion" id="Retencion" value="."> 
                        <input type="hidden" name="Autorizacion_R" id="Autorizacion_R" value=".">  
                        <input type="hidden" name="Autorizacion_LC" id="Autorizacion_LC" value="."> 
                        <input type="hidden" name="txt_cta_modificar" id="txt_cta_modificar" value="."> 
                      </div>
                      <div class="row">
                          <div class="col-xs-12 ">
                            <div class="panel-heading">
                              <ul class="nav nav-tabs">
                                <li class="active"><a href="#contabilidad" data-toggle="tab">4. Contabilización</a></li>
                                <li><a href="#subcuentas" data-toggle="tab" onclick="cargar_tablas_sc();">5. Subcuentas</a></li>
                                <li><a href="#retenciones" data-toggle="tab" onclick="cargar_tablas_retenciones();">6. Retenciones</a></li>
                                <li><a href="#ac_av_ai_ae" data-toggle="tab" onclick="cargar_tablas_tab4();">7. AC-AV-AI-AE</a></li>
                              </ul>
                            </div>
                            <div class="panel-body" style="padding-top: 2px;">
                              <div class="tab-content">
                                <div class="tab-pane fade in active" id="contabilidad">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>                          
                                </div>
                                <div class="tab-pane fade" id="subcuentas">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>  
                                </div>
                                <div class="tab-pane fade" id="retenciones">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>  
                                </div>
                                <div class="tab-pane fade" id="ac_av_ai_ae">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row ">
                          <div class="col-sm-6">
                             <button type="button"  class="btn btn-primary" id='grabar1' onclick="validar_comprobante()">Guardar</button>
                             <a  href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-danger">
                                Cancelar
                              </a>
                             <!-- 
                              <button type="button"  class="btn btn-danger" id='' onclick="xml()">xml</button>
                             -->                            
                          </div>
                          <div class="col-sm-6">
                            <div class="col-sm-4">
                              <b>Diferencia</b>
                                <input type="text" name="txt_diferencia" id="txt_diferencia" class="form-control input-xs text-right" readonly="" value="0">
                            </div>
                            <div class="col-sm-4">
                              <b>Totales</b>
                               <input type="text" name="txt_debe" id="txt_debe" class="form-control input-xs text-right" readonly="" value="0">
                            </div>
                            <div class="col-sm-4"><br>
                                <input type="text" name="txt_haber" id="txt_haber" class="form-control input-xs text-right" readonly="" value="0">
                            </div>
                          </div>
                        </div>                        
                    </div>
                                          
                      
                    </div>
                  </form>   
                    
                </div>
            </div>
  </div>
</div>

<div class="modal fade" id="modal_cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="margin-right: 50px; margin-top: 200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
        <div id="panel_banco" style=" display: none">
        <div class="row">
          <div class="col-sm-6">
            <b>Efectiv.</b>
          </div>
          <div class="col-sm-6">
            <input type="date" name="txt_efectiv" id="txt_efectiv" class="form-control input-xs" value="<?php echo date('Y-m-d');?>">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <b> Cheq / Dep</b>
          </div>
          <div class="col-sm-6">
            <input type="text" name="txt_cheq_dep" id="txt_cheq_dep" class="form-control input-xs">
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
            <b>Valores</b>
          </div>
          <div class="col-sm-6">
            <b>M/N = 1 | M/E=2</b>
            <input type="text" name="txt_moneda" id="txt_moneda" class="form-control input-xs" onkeyup="restingir('txt_moneda')" value="1">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
            <b>Debe / Haber</b>
          </div>
          <div class="col-sm-6">
            <b>Debe = 1 | Haber=2</b>
            <input type="text" name="txt_tipo" id="txt_tipo" class="form-control input-xs" onkeyup="restingir('txt_tipo')" value="1" onblur="saltar()">
              <button type="button" class="btn btn-primary" onclick="subcuenta_frame();" id="btn_acep" style="background: white;border: 0px;">Aceptar</button>
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer"> -->
        
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button> -->
        <!-- </div> -->
    </div>
  </div>
</div>

<div class="modal fade" id="modal_subcuentas" tabindex="-1" role="dialog" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_frame">SUB CUENTAS</h5>
      </div>
      <div class="modal-body" style="padding-top: 0px;">
        <!-- <div class="container-fluid"> -->
          <iframe  id="frame" width="100%" marginheight="0" frameborder="0"></iframe>
          
        <!-- </div> -->
        <!-- <iframe src="../vista/contabilidad/FSubCtas.php"></iframe> -->
        
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" onclick="cambia_foco();">Guardar</button> -->
          <button style="display: none;" id="btn_salir" id="btn_cerrar_sub" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- TODO: Modal CC-->

<div class="modal fade" id="modal_CC" data-backdrop="static" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 475px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_frame_cc"></h5>
      </div>
      <div class="modal-body">
        <h5 class="modal-title" id="titulo_aux" style="padding-top: 10px; padding-bottom: 10px;"></h5>
      <div class="row">
        <div class="col-sm-12" style="overflow-x: scroll;height: 300px; padding: 10px; ">
            <div id="tablaContenedor">

            </div>   
        </div>
      </div>                     
        <div class="row">
          <div class="col-sm-6" style="padding: 10px;">
            <button type="button" class="btn btn-primary" onclick="Commandl_Click()">Aceptar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="Command2_Click()">Cancelar</button>
          </div>
          <div class="col-sm-6">
            <div class="col-sm-6" style="padding: 10px;">
              <b>TOTAL</b>
            </div>
            <div class="col-sm-6" style="padding: 10px;">
              <input type="text" name="total_cc" id="total_cc" class="form-control input-xs text-right" readonly="" value="0.00" wfd-id="id35">
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" onclick="cambia_foco();">Guardar</button> -->
          <!-- <button style="display: none;" id="btn_salir_cc" id="btn_cerrar_sub_cc" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
      </div>
    </div>
  </div>
</div>

