<?php

    if(!isset($_SESSION))
	 		session_start();

?>
<?php
 //verificacion titulo accion
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti']))
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='BALANCE DE COMPROBACIÓN';
	}
?>
<script>
$(document).ready(function()
	{

		$('#imprimir_excel').click(function(){
			$tipo =$('#txt_tipo').val();
            var url = '../controlador/contabilidad/contabilidad_controller.php?balance_excel=true&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&TipoBa='+$('#TipoBenef').val()+'&TipoPre='+$('input:radio[name=optionsRadios]:checked').val()+'&Tipo='+$tipo+'&Imp=true&pdf=false';                 
      	   window.open(url, '_blank');


       });

		$('#imprimir_pdf').click(function(){
			$tipo =$('#txt_tipo').val();
			var url = '../controlador/contabilidad/contabilidad_controller.php?reporte_pdf=true&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&Tipo='+$tipo;
			window.open(url, '_blank');


       });
		agencias();

    });

// $('#myModal_espera').modal('show');




function bamup(tipo)
{

  $('#myModal_espera').modal('show');
	$('#txt_tipo').val(tipo);
	var parametros = 
 	{
 		'desde':$('#desde').val(),
 		'hasta':$('#hasta').val(),
 		'TipoBa':$('#TipoBenef').val(),
 		'TipoPre':$('input:radio[name=optionsRadios]:checked').val(),
 		'Tipo':tipo,
 		'Imp':false,
 		'Agencia':$('#rbl_agencia').prop('checked'),
 		'AgenciaVal':$('#lista_agencia').val(),
 		//'ES' o 'ER',
 	}
//parametros que se van a enviar al controlado  tanto para SP como para reporte

//el meto de envio de datos al controlador sera por GET y por POST;
//POST se van a enviar los parametros
//GET se va a enviar la variable "balance=true" en la url;

// cuando exclusivamente sea  grilla lo que vayamos a mostrar el   dataType: 'json' debe estar comentado
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/contabilidad_controller.php?balance=true',
      type:  'post',
     dataType: 'json',
         beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 // $('#myModal_espera').modal('show');
			},
        success:  function (response) {
        	//resultado que obtenemos de todo el proceso si es -1 fallo proceso 
        	if(response.respuesta == -1)
        	{        		
        		 $('#myModal_espera').modal('hide');
        	}else
        	{
        		//colocamos el resultado donde queramos
        		// console.log(response);
        		 $('#myModal_espera').modal('hide');
        		$('#tabla').html(response);
        	}
      },
       error: function(xhr, textStatus, error){
		    $('#myModal_espera').modal('hide');
		    Swal.fire('No se pudo cargar','Intente mas tarde','info');
		  }
    }); 
}

function agencias()
{
	$.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/contabilidad_controller.php?agencias=true',
      type:  'post',
     dataType: 'json',
         beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 // $('#myModal_espera').modal('show');
			},
        success:  function (response) {
        	//resultado que obtenemos de todo el proceso si es -1 fallo proceso 
        	llenarComboList(response,'lista_agencia');
        	console.log(response);
      },
       error: function(xhr, textStatus, error){
		  
		    Swal.fire('No se pudo cargar','Intente mas tarde','info');
		  }
    }); 
}
</script>
 <div class="row" id='submenu'>
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-body">
			  	<div class="row">

    				<div class="col-lg-4 col-sm-10 col-md-8 col-xs-12"> 
				  		<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
							<a class="btn btn-default"  title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
								<i><img src="../../img/png/salire.png"></i>
							</a>
						</div>
						<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
						       <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						        <img src="../../img/png/autorizar1.png">
						         <span class="caret"></span>
						      </button>
						    <ul class="dropdown-menu" role="menu" id="year">
						      <li><a href="#" onclick="bamup('ER')" >Estado de Resultado</a></li>
						      <li><a href="#" onclick="bamup('ES')" >Estado de Situacion</a></li>
					    	</ul>
					    </div>
						<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
							<a class="btn btn-default" title="Imprimir resultados" id="imprimir_pdf">
								<i ><img src="../../img/png/pdf.png"></i>
							</a>
						</div>
						<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
							<a id='imprimir_excel' class="btn btn-default" title="Exportar Excel" href="#">
								<i ><img src="../../img/png/table_excel.png"></i> 
							</a>
						</div>	
					</div>
					<div class="col-lg-8 col-sm-10 col-md-8 col-xs-12">
						<div class="row">
								<div class="col-sm-3">
			              			<input type="hidden" name="" id="txt_tipo">		
										<b>Desde: </b>	
										 <input type="date" class="form-control pull-right input-sm" id="desde" placeholder="01/01/2019"
										 name="fechai" onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>'>	
									
								</div>
								<div class="col-sm-3">
										<b>hasta: </b>
										<input type="date" class="form-control pull-right input-sm" id="hasta" placeholder="01/01/2019" name="fechaf" onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>'>						
								</div>
								<div class="col-sm-6">
									<b>Tipo Presentación cuentas: </b><br>
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios1" value="T" checked>
									  Todos
									</label>
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios2" value="G" onclick='modificar("G");'>
									  Grupo
									</label>
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios3" value='D' onclick='modificar("D");' >
									  Detalle
									</label>									
								</div>
						 </div>
					</div>				 
				</div>
				<div class="row">
					<div class="col-sm-4">
						<label><input type="checkbox" id="rbl_agencia"> <b> Nombre de Agencia</b></label>
						<select class="form-control input-xs" id="lista_agencia">
							<option value="">Seleccione agencia</option>
						</select>
					</div>
				</div>
				
		 </div>
	  </div>
	</div>
</div>

<?php
	//echo $_SESSION['INGRESO']['Opc'].' ------- '.$_SESSION['INGRESO']['Sucursal'];
	//llamamos spk
	if(isset($_GET['bm']))
	{
		sp_Reporte_Analitico_Mensual($_GET['fechai'],$_GET['fechaf'],$_GET['bm']);

		//verificamos periodo
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) )
		{
			if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
			{
				$periodo=getPeriodoActualSQL();
				//echo $periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
			}
			else
			{
				//mysql que se valide en controlador
				//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
				$periodo=getPeriodoActualSQL();
				//echo $periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
			}
		}
	}
			?>

<div class="row">
	<div class="col-sm-12" id="tabla">
		
	</div>
</div>
<script>
	//Date picker
    /*$('#desde').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });
	$('#hasta').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });*/
	//modificar url
	function modificar(texto){
		if(texto=='NI')
		{
			texto='';
		}
		var fechai = document.getElementById('desde');
		var fechaf = document.getElementById('hasta');
		//alert(fechai.value+' '+fechaf.value);
	 /*
		//cambiamos formato
		var ca1 = fechai.split('/');
		alert(ca1.length);
		var ca2 = fechaf.split('/');
		alert(ca2.length);
		for(var i=0;i < ca.length;i++) {

		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) {
		  return decodeURIComponent( c.substring(nameEQ.length,c.length) );
		}

	  }*/
		var l1=$('#l1').attr("href");
		var l1=l1+'&OpcDG='+texto+'&fechai='+fechai.value+'&fechaf='+fechaf.value;
		//asignamos
		$("#l1").attr("href",l1);

		var l2=$('#l2').attr("href");
		var l2=l2+'&OpcDG='+texto+'&fechai='+fechai.value+'&fechaf='+fechaf.value;
		//asignamos
		$("#l2").attr("href",l2);

		var l4=$('#l4').attr("href");
		var l4=l4+'&OpcDG='+texto;
		//asignamos
		$("#l4").attr("href",l4);

		var l5=$('#l5').attr("href");
		var l5=l5+'&OpcDG='+texto;
		//asignamos
		$("#l5").attr("href",l5);

		var l6=$('#l6').attr("href");
		var l6=l6+'&OpcDG='+texto;
		//asignamos
		$("#l6").attr("href",l6);
		//var ti=getParameterByName('ti');
		//alert(ti);
	  //document.getElementById("mienlace").innerHTML = texto;
	  //document.getElementById("mienlace").href = url;
	  //document.getElementById("mienlace").target = destino;
	}
	//balance nomenclatura nacional o internacional
		//modificar url
	function modificarb(id){
		texto='0';
		if (document.getElementById(id).checked)
		{
			//alert('Seleccionado');
			texto='1';
		}

		var l1=$('#l1').attr("href");
		var l1=l1+'&OpcCE='+texto;
		//asignamos
		$("#l1").attr("href",l1);

		var l2=$('#l2').attr("href");
		var l2=l2+'&OpcCE='+texto;
		//asignamos
		$("#l2").attr("href",l2);

		var l4=$('#l4').attr("href");
		var l4=l4+'&OpcCE='+texto;
		//asignamos
		$("#l4").attr("href",l4);

		var l5=$('#l5').attr("href");
		var l5=l5+'&OpcCE='+texto;
		//asignamos
		$("#l5").attr("href",l5);

		var l6=$('#l6').attr("href");
		var l6=l6+'&OpcCE='+texto;
		//asignamos
		$("#l6").attr("href",l6);
		//var ti=getParameterByName('ti');
		//alert(ti);
	  //document.getElementById("mienlace").innerHTML = texto;
	  //document.getElementById("mienlace").href = url;
	  //document.getElementById("mienlace").target = destino;
	}
	function modificarb1(id)
	{
		texto='';
		if (document.getElementById(id).checked)
		{
			//alert('Seleccionado');
			document.getElementById("ineg").style.display = "block";
		}
		else
		{
			document.getElementById("ineg").style.display = "none";
			//alert('ppp');
			var l1=$('#l1').attr("href");
			var l1=l1+'&OpcBs='+texto;
			//asignamos
			$("#l1").attr("href",l1);

			var l2=$('#l2').attr("href");
			var l2=l2+'&OpcBs='+texto;
			//asignamos
			$("#l2").attr("href",l2);

			var l4=$('#l4').attr("href");
			var l4=l4+'&OpcBs='+texto;
			//asignamos
			$("#l4").attr("href",l4);

			var l5=$('#l5').attr("href");
			var l5=l5+'&OpcBs='+texto;
			//asignamos
			$("#l5").attr("href",l5);

			var l6=$('#l6').attr("href");
			var l6=l6+'&OpcBs='+texto;
			//asignamos
			$("#l6").attr("href",l6);
		}
	}
	function agregarSu(id)
	{
		var select = document.getElementById(id); //El <select>
		texto = select.value;

		var l1=$('#l1').attr("href");
		var l1=l1+'&OpcBs='+texto;
		//asignamos
		$("#l1").attr("href",l1);

		var l2=$('#l2').attr("href");
		var l2=l2+'&OpcBs='+texto;
		//asignamos
		$("#l2").attr("href",l2);

		var l4=$('#l4').attr("href");
		var l4=l4+'&OpcBs='+texto;
		//asignamos
		$("#l4").attr("href",l4);

		var l5=$('#l5').attr("href");
		var l5=l5+'&OpcBs='+texto;
		//asignamos
		$("#l5").attr("href",l5);

		var l6=$('#l6').attr("href");
		var l6=l6+'&OpcBs='+texto;
		//asignamos
		$("#l6").attr("href",l6);
		//var ti=getParameterByName('ti');
		//alert(ti);
	  //document.getElementById("mienlace").innerHTML = texto;
	  //document.getElementById("mienlace").href = url;
	  //document.getElementById("mienlace").target = destino;
	}
	function modificar1()
	{
		var ti=getParameterByName('ti');
		//alert(ti);
		if( ti=='BALANCE DE COMPROBACIÓN')
		{
			var l1=$('#l1').attr("href");
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='BALANCE MENSUAL')
		{
			var l1=$('#l2').attr("href");
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='ESTADO SITUACIÓN')
		{
			var l1=$('#l5').attr("href");
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='ESTADO RESULTADO')
		{
			var l1=$('#l6').attr("href");
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}

	}
</script>
