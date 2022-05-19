
<?php
$tc = '';
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
 ?>
<script type="text/javascript">
	$(document).ready(function () {
    limpiar_asiento_sc();
    var tc = '<?php echo $tc; ?>';
    titulos(tc);
		cargar_tablas_sc();
		carga_ddl();
    carga_ddl_aux();
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
            $('#subcuentas').html(response);      
          }
        });

    }

  function carga_ddl()
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
          // data:  {parametros:parametros},
          // type:  'post',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }

    function carga_ddl_aux()
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
          // data:  {parametros:parametros},
          // type:  'post',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }
    function agregar_sc()
    { var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var tipoc = '<?php echo $tipoc; ?>';
      var ben = $('#ddl_subcta  option:selected').text();
      var codigo = $('#ddl_subcta').val();
      var aux = $('#ddl_aux').val();
      var val = $('#txt_valor').val();
      var fac = $('#txt_factura').val();
      var mes = $('#txt_mes').val();
      var fec = $('#txt_fecha_ven').val();
      if(aux=='')
      {
        aux = '.';
      }
      if(val==0 || codigo =='')
      {
        Swal.fire('Sub cuenta no seleccionada o valor pendiente','','info')
        return false;
      }
      
      var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'ben':ben,
        'codigo':codigo,
        'aux':aux,
        'fec':fec,
        'val':val,
        'tipoc':tipoc,
        'fac':fac,
        'mes':mes,
      }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_generar_sc=true',
          type:  'post',
          dataType: 'json',
            success:  function (response){
            if(response.resp==1)
            {    
              cargar_tablas_sc();
            }  
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
                Swal.fire('Registrado','','success');
                parent.location.reload();
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
</script>
<div id="iframe">
<div class="row">
	<div class="col-sm-4">
		<b id="titulo">Sub cuenta por cobrar</b>
		<select class="form-control input-sm" id="ddl_subcta">
			<option value="">Seleccione una sub cuenta</option>
		</select>	
	</div>
	<div class="col-sm-3">
		<b>Fecha Venc</b>
		<input type="date" name="txt_fecha_ven" id="txt_fecha_ven" class="form-control input-sm" value="<?php echo date('Y-m-d');?>">
	</div>
	<div class="col-sm-2">
		<b>Factura No</b>
		<input type="text" name="txt_factura" id="txt_factura" class="form-control input-sm" value="0">
	</div>
	<div class="col-sm-1">
		<b>Meses</b>
		<input type="text" name="txt_mes" id="txt_mes" class="form-control input-sm" value="0">
	</div>
	<div class="col-sm-2">
		<b>Valor M/N</b>
		<input type="text" name="txt_valor" id="txt_valor" class="form-control input-sm" value="0" onblur="agregar_sc()">
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		
	</div>
	<div class="col-sm-8">
		<b>DETALLE AUXILIAR DE SUB MODULO</b>
		<select class="form-control input-sm" id="ddl_aux">
			<option value="">Seleccione detalle auxiliar de sub modulo</option>
		</select>
	</div>
</div>
<div class="row" style="overflow-x: scroll;">
  <div class="col-sm-12" id="subcuentas">
    
  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-primary" onclick="generar_asiento();">Continuar</button>
</div>
</div>	
