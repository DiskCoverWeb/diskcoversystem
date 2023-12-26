
<?php
include_once(dirname(__DIR__,2).'/modelo/contabilidad/incomM.php');
$tc = '';
$cta = '';
$Opcion_Mulp = 'false';

if(isset($_GET['tipo_subcta']))
{
	$tc = $_GET['tipo_subcta'];
}
if(isset($_GET['OpcTM']))
{
	$OpcTM = $_GET['OpcTM'];
}
if(isset($_GET['OpcDH']))
{
	$OpcDH = $_GET['OpcDH'];
}
if(isset($_GET['cta']))
{
	$cta = $_GET['cta'];
}
if(isset($_GET['tipoc']))
{
  $tipoc = $_GET['tipoc'];
}
if(isset($_GET['Opcion_Mulp']))
{
  $Opcion_Mulp = $_GET['Opcion_Mulp'];
}
if(isset($_GET['Cuenta']))
{
  $Cuenta = $_GET['Cuenta'];
}

$_SESSION['PorCtasCostos'] = false;
$_SESSION['AgruparSubMod'] = Leer_Campo_Empresa('Det_SubMod');
if(!isset($_SESSION['Trans_No'])){
  $_SESSION['Trans_No'] = 1;
}

if (substr($cta, 0, 1) == "1") {
  $modelo = new incomM();
  switch ($tc) {
      case "G":
      case "CC":
          $sSQL = "SELECT Cta " .
              "FROM Trans_Presupuestos " .
              "WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' " .
              "AND Item = '".$_SESSION['INGRESO']['item']."' " .
              "AND MesNo = 0 " .
              "GROUP BY Cta ";
          $AdoAux = $modelo->SelectDB_List($sSQL);
          if (count($AdoAux) > 0) {
              $_SESSION['PorCtasCostos'] = true;
          }
          break;
  }
}

?>
<script type="text/javascript">
  var SubCta = '<?php echo $tc; ?>';
  var SubCtaGen = '<?php echo $cta; ?>';
  var OpcTM = '<?php echo $OpcTM; ?>';
  var Cuenta = '<?php echo $Cuenta; ?>';
	$(document).ready(function () {
    $("#LabelCta").text(Cuenta)
    if ($("#ddl_subcta").is(":visible")) {
        $("#ddl_subcta").focus();
    } else {
        $("#DLSubCta").focus();
    }
    var tc = '<?php echo $tc; ?>';
    $('#Label6').css('visibility','visible');
    $('#Label2').text("MESES");
    switch(tc) {
      case 'G':
       $('#titulo').text("SUBCUENTAS DE GASTOS");
       $("#Label4").css('visibility','hidden');
       $("#txt_fecha_ven").hide();
       $("#txt_factura").show()
       $("#DCFactura").hide()
       $('#Label6').text("VALOR");
       $('#Label2').text("CANT.");
       $("#DCFactura").hide()
      break;
      case 'PM':
       $('#titulo').text("SUBCUENTAS DE PRIMAS");
       $("#Label4").css('visibility','visible');
       $("#txt_fecha_ven").hide();
       $('#Label4').text("FACTURA No.");
       $("#DCFactura").show()
       $("#txt_factura").hide()
       $('#TxtPrima').show();
       $('#Label6').text("PRIMA");
      break;

    default:
       $('#Label4').css('visibility','visible');
       $('#txt_fecha_ven').show();
       $('#Label6').text("Factura No.");
       $("#DCFactura").show()
       $("#txt_factura").hide()
      break;
    }
    limpiar_asiento_sc();
    titulos(tc)
    cargar_tablas_sc();
    cargaDCDetalle();
    cargaDCSubCta()
    ListarSubCtaModulo();
    FacturasPendientesSC()
	});

	function cargar_tablas_sc()
    {
       var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var val = $('#txt_total').val();
      var fec = $('#txt_fecha_ven').val();
       var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'fec':fec,
        'val':val,
      }          	
    	$.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_sc_modal=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) {    
            $('#subcuentas').html(response.DGSubCta);      
            $('#LabelTotalSCMN').val(formatearNumero(response.SumaSubCta));      
            $('#LabelTotalSCME').val(formatearNumero(response.SumaSubCta_ME));      
          }
        });

    }

  function cargaDCSubCta()
  {
    	var tc = '<?php echo $tc; ?>';
    	var OpcDH = '<?php echo $OpcDH; ?>';
    	var OpcTM = '<?php echo $OpcTM; ?>';
    	var cta = '<?php echo $cta; ?>';
      $('#ddl_subcta').select2({
        placeholder: 'Seleccione cuenta efectivo',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?modal_subcta_catalogo=true&tc='+tc+'&OpcDH='+OpcDH+'&OpcTM='+OpcTM+'&cta='+cta,
          dataType: 'json',
          processResults: function (data) {
            if(data.length>0){
              $("#ddl_subcta").show();
              $("#titulo").show();
              $("#div_DCSubCta").css('visibility','visible')
            }
            return {
              results: data
            };
          },
          cache: true
        }
      });
      $('#ddl_subcta').select2('open').select2('close');
    }

    function cargaDCDetalle()
    {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      $('#ddl_aux').select2({
        placeholder: 'Seleccione cuenta efectivo',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?modal_detalle_aux=true&tc='+tc+'&OpcDH='+OpcDH+'&OpcTM='+OpcTM+'&cta='+cta,
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
      $('#ddl_aux').select2('open').select2('close');
      $('#ddl_aux').on('select2:close', event => agregar_sc());
    }

    function agregar_sc()
    {
      if($('#txt_valor').val()==0 || $('#DLSubCta').val() =='')
      {
        Swal.fire('Sub cuenta no seleccionada o valor pendiente','','info')
        return false;
      }
      var parametros = 
      {
        'SubCtaGen':'<?php echo $cta; ?>',
        'SubCta':'<?php echo $tc; ?>',
        'OpcTM':'<?php echo $OpcTM; ?>',
        'OpcDH':'<?php echo $OpcDH; ?>',
        'Beneficiario':$('#DLSubCta  option:selected').text(),
        'Codigo':$('#DLSubCta').val(),
        'DCDetalle':$('#ddl_aux').val(),
        'MBoxFechaV':$('#txt_fecha_ven').val(),
        'TextValor':$('#txt_valor').val(),
        'tipoc':'<?php echo $tipoc; ?>',
        'TxtFactura':$('#txt_factura').val(),
        'TxtMeses':$('#txt_mes').val(),
        'TxtPrima':$('#TxtPrima').val(),
        'DLSubCta':$('#DLSubCta').val(),
        "DCFactura":$('#DCFactura').val(),
        "Opcion_Mulp": '<?php echo $Opcion_Mulp; ?>',
      }
      $('#myModal_espera').modal('show');
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_generar_sc=true',
          type:  'post',
          dataType: 'json',
          success:  function (response){
            if(response.resp==1)
            {    
              cargar_tablas_sc();
              $('#myModal_espera').modal('hide');
            }  
          },
          error: function (e) {
            $('#myModal_espera').modal('hide');
            alert("error inesperado en agregar_asiento")
            reject(e);
          }
        });
    }

    function generar_asiento()
    {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var val = $('#txt_total').val();
      var fec = $('#txt_fecha_ven').val();
       var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'fec':fec,
        'val':val,
      }      
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_ingresar_asiento=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                // Swal.fire('Registrado','','success');                
                 window.parent.postMessage('closeModalSubCta', '*');
                // parent.location.reload();
                $('#iframe').css('display','none');
              }
          }
        });

    }


    function limpiar_asiento_sc()
    {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var val = $('#txt_total').val();
      var fec = $('#txt_fecha_ven').val();
       var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'fec':fec,
        'val':val,
      }      
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_limpiar_asiento=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              Swal.fire('Registrado','','success');
              $('#iframe').css('display','none');
            }
          },
          error: function (e) {
            $('#myModal_espera').modal('hide');
            alert("error inesperado en limpiar_asiento")
            reject(e);
          }
        });

    }

    function titulos(tc)
    {
      switch(tc) {
        case 'C':
           $('#titulo').text("SUBCUENTAS POR COBRAR");
          break;

        case 'P':
           $('#titulo').text("SUBCUENTAS POR PAGAR");
          break;

          case 'G':
           $('#titulo').text("SUBCUENTAS DE GASTOS");
          break;

          case 'I':
           $('#titulo').text("SUBCUENTAS DE INGRESO");
          break;

          case 'CP':
           $('#titulo').text("SUBCUENTAS POR COBRAR PRESTAMOS");
          break;

          case 'PM':
           $('#titulo').text("SUBCUENTAS DE PRIMAS");
          break;
      }
    }

  function ListarSubCtaModulo(Nivel_No='null', DCSubCta='.')
  {
    var tc = '<?php echo $tc; ?>';
    var OpcDH = '<?php echo $OpcDH; ?>';
    var OpcTM = '<?php echo $OpcTM; ?>';
    var cta = '<?php echo $cta; ?>';
    $('#myModal_espera').modal('show');
    $('#DLSubCta').select2({
      placeholder: '.',
      ajax: {
        url:   '../controlador/contabilidad/incomC.php?ListarSubCtaModulo=true&tc='+tc+'&OpcDH='+OpcDH+'&OpcTM='+OpcTM+'&cta='+cta+'&Nivel_No='+Nivel_No+'&DCSubCta='+DCSubCta,
        dataType: 'json',
        processResults: function (data) {
          if(data.DCSubCtaMostrar){
            $("#ddl_subcta").show();
            $("#titulo").show();
            $("#div_DCSubCta").css('visibility','visible')
          }
          $('#myModal_espera').modal('hide');

          if (data.DLSubCta.length>0) {
            switch (tc) {
                case "G":
                case "I":
                case "PM":
                    if ($("#ddl_subcta").is(":visible")) {
                        $("#ddl_subcta").focus();
                    } else {
                        $("#DLSubCta").focus();
                    }
                    break;
                case "C":
                case "P":
                case "CP":
                    $("#DLSubCta").focus();
                    break;
                default:
                    break;
            }
          }else{
            Swal.fire({
              title: "No existe Datos Asignados para procesar",
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Aceptar'
            }).then((result) => {
              cerrarModal()
            })
          }
          return {
            results: data.DLSubCta
          };
        },
      }
    });
    $('#DLSubCta').select2('open').select2('close');
  }

  function DCSubCta_LostFocus() {
    switch (SubCta) {
      case "G":
      case "I":
      case "PM":
          Nivel_No = "00"
          DCSubCta = $('#ddl_subcta').val();
          ListarSubCtaModulo(Nivel_No, DCSubCta)
        break;
        
    }
  }

  function FacturasPendientesSC() {
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/incomC.php?FacturasPendientesSC=true',
      dataType: 'json',
      data: { 
        'FechaTexto': $("#txt_fecha_ven").val(),
        'Codigo': $("#DLSubCta").val(), 
        'SubCta': '<?php echo $tc; ?>', 
        'SubCtaGen': '<?php echo $cta; ?>'
      },
      success: function (datos) {
        let FACTS ='<option value="0">Seleccione</option>';
        $.each(response.DCFactura, function(i, item){
          FACTS+='<option value="'+response.DCFactura[i].Factura+'">'+response.DCFactura[i].Factura+'</option>';
        });         
        $('#DCFactura').html(FACTS);

        $('#myModal_espera').modal('hide');
      },
      error: function (e) {
        $('#myModal_espera').modal('hide');
        alert("error inesperado en FacturasPendientesSC")
      }
    });
  }

  function InsertarCxP($SubCtaGen, $CodigoCliente, $SubCta)
  {
    return new Promise(function (resolve, reject) {
      $('#myModal_espera').modal('show');
      $.ajax({
        url:   '../controlador/contabilidad/incomC.php?InsertarCxP=true',
        type:  'post',
        data: {
          'SubCtaGen':$SubCtaGen,
          'CodigoCliente':$CodigoCliente,
          'SubCta':$SubCta
        },
        dataType: 'json',
        success:  function (response) { 
          $('#myModal_espera').modal('hide');
          resolve();
        },
        error: function (e) {
          $('#myModal_espera').modal('hide');
          alert("error inesperado en Insertar_CxP")
          reject(e);
        }
      });
    });
  }

  function DCClienteLostFocus(){
    let CodigoCliente = $("#DCCliente").val()
    if(CodigoCliente!='' && CodigoCliente!='.'){
      InsertarCxP(SubCtaGen, CodigoCliente, SubCta)
      .then(function () {
        return ListarSubCtaModulo(Nivel_No = 'null', $('#ddl_subcta').val());
      })
      .catch(function (error) {
        console.error(error);
      });
    }
    $("#DCCliente").hide();
    $("#DLSubCta").focus()
  }

function DCFactura_LostFocus(event) {
  let Factura_No = $(event.target).val();
  $("#txt_valor").val("0.00")
  if (SubCta === "C" || SubCta === "P") {
    $('#myModal_espera').modal('show');
    $.ajax({
      url:   '../controlador/contabilidad/incomC.php?DCFacturaLostFocus=true',
      type:  'post',
      data: {
        'Factura_No':Factura_No,
        'OpcTM':OpcTM,
      },
      dataType: 'json',
      success:  function (response) { 
        $('#myModal_espera').modal('hide');
        $("#txt_valor").val(response.TextValor)
      },
      error: function (e) {
        $('#myModal_espera').modal('hide');
        alert("error inesperado en DCFacturaLostFocus")
      }
    });
  }
}

</script>

<style type="text/css">
  .select2-container--default.select2-container--focus, .select2-selection.select2-container--focus, .select2-container--default:focus, .select2-selection:focus, .select2-container--default:active, .select2-selection:active{
    border: 1px solid blue;
  }
</style>
<div class="col-xs-12 text-rigth" style="
    text-align: center;
    font-size: 14px;
    color: red;
    font-weight: bold;">
    <p id="LabelCta"></p>
</div>
<div class="row">
	<div class="col-sm-4" id="div_DCSubCta"  style="visibility: hidden;">
		<b id="titulo" style="display:none;">Sub cuenta por cobrar</b>
		<select class="form-control input-sm" id="ddl_subcta" style="display:none;" onchange="DCSubCta_LostFocus()"tabindex="1">
			<option value="">Seleccione una sub cuenta</option>
		</select>	
	</div>
	<div class="col-sm-3">
		<b id="Label4">FECHA VEN.</b>
    <input type="date" name="txt_fecha_ven" id="txt_fecha_ven" class="form-control input-sm" value="<?php echo date('Y-m-d');?>" onblur="FacturasPendientesSC()" tabindex="3">
		<input type="text" name="TxtPrima" id="TxtPrima" class="form-control input-sm" onblur="FacturasPendientesSC()" style="display:none" tabindex="3">
	</div>
	<div class="col-sm-2">
		<b id="Label6">Factura No</b>
    <input type="text" name="txt_factura" id="txt_factura" class="form-control input-sm" onkeyup="solo_numeros(this)" value="0" tabindex="5">
    <select class="form-control input-sm" id="DCFactura" style="display:none" onblur="DCFactura_LostFocus(event)" tabindex="5">
      <option value="">.</option>
    </select> 
	</div>
	<div class="col-sm-1">
		<b id="Label2">Meses</b>
		<input type="text" name="txt_mes" id="txt_mes" class="form-control input-sm" onkeyup="solo_numeros(this)" value="0"  tabindex="6">
	</div>
	<div class="col-sm-2">
		<b>Valor M/N</b>
		<input type="text" name="txt_valor" id="txt_valor" class="form-control input-sm" value="0"onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)" tabindex="12">
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<b id="tituloDLSubCta">Beneficiario Cuentas</b>
    <select class="form-control input-sm" id="DLSubCta" name="DLSubCta" tabindex="2">
      <option value="">.</option>
    </select> 
	</div>
	<div class="col-sm-8">
		<b>DETALLE AUXILIAR DE SUB MODULO</b>
		<select class="form-control input-sm" id="ddl_aux"  tabindex="13">
			<option value="">.</option>
		</select>
	</div>
</div>
<div class="row">
  <select class="form-control input-sm" id="DCCliente" onblur="DCClienteLostFocus()" style="display:none">
    <option value="">Seleccione beneficiario</option>
  </select>
</div>
<div class="row" style="overflow-x: scroll;">
  <div class="col-sm-12" id="subcuentas">
    
  </div>
</div>

<div class="modal-footer">
  <div class="col-sm-offset-5 col-sm-4">
    <div class="input-group">
     <div class="input-group-addon input-xs">
       <b>TOTAL M/N:</b>
     </div>
     <input type="text" class="form-control input-xs" id="LabelTotalSCMN" >
   </div>
    <div class="input-group">
     <div class="input-group-addon input-xs">
       <b> TOTAL M/E:</b>
     </div>
     <input type="text" class="form-control input-xs" id="LabelTotalSCME" >
   </div>
  </div>
  <div class="col-sm-3">
    <button type="button" class="btn btn-primary" onclick="generar_asiento();">Continuar</button>
    <button type="button" class="btn btn-default" onclick="cerrarModal();">Salir</button>
  </div>
</div>

<script type="text/javascript">
  function cerrarModal() {
       // window.parent.document.getElementById('modal_subcuentas').style.display = 'none';
       window.parent.document.getElementById('modal_subcuentas').click();
 
    // $('#modal_subcuentas').hide();
  }
</script>
