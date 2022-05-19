<?php

    if(!isset($_SESSION)) 
	 		session_start();
	//datos para consultar
	//CI_NIC
	//echo $_SESSION['INGRESO']['Opc'].' '.$_SESSION['INGRESO']['Sucursal'].' '.$_SESSION['INGRESO']['item'].' '.$_SESSION['INGRESO']['periodo'].' ';
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti'])) 
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='Administración de usuario';
	}
?>

<!--<h2>Balance de Comprobacion/Situación/General</h2>-->

<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box table-responsive">
            <div class="box-header">
              <!--<h3 class="box-title">Striped Full Width Table</h3>-->
			  <table>
				<tr>
					<td>
						<div class="loader1"></div>
					</td>
				</tr>
			  </table>
			<?php
					
					?>
					
					<?php
					$texto[0]=1;
					if(count($texto)>0)
							{
					?>	
								<!-- Modal -->
								<div class="col-md-6">
									<div class="form-group">
										<input type="checkbox" name='entidadch' id='entidadch'>
										<label for="Entidad">Entidad</label>
										<select class="form-control" name="entidad_u" id='entidad_u' onChange="return buscar('entidad_u');">
											<option value='0'>Seleccione Entidad</option>
											<?php select_option_mysql('entidad','ID_Empresa','Nombre_Entidad',' 1=1 ORDER BY Nombre_Entidad '); ?>
										</select>
									</div>
								</div>
								<div id='entidad_u1'>
									<div class="col-md-6">
										<div class="form-group">
											<input type="checkbox" name='empresach' id='empresach'>
											<label for="Empresa">Empresa</label>
											
											<select class="form-control" name="empresa" id='empresa' >
												<option value='0'>Seleccione Empresa</option>
												<?php select_option_mysql('lista_empresas','Item','Empresa',' 1=1 ORDER BY Empresa '); ?>
											</select>
										</div>
									</div>
								
									<div class="col-md-6">
										<div class="form-group">
											<input type="checkbox" name='usuarioch' id='usuarioch'>
											<label for="Empresa">Usuario</label>
											
											<select class="form-control" name="usuario" id='usuario' onChange="return buscar('usuario');">
												<option value='0'>Seleccione Usuario</option>
												<?php select_option_mysql('acceso_usuarios','ID','Nombre_Usuario',' 1=1 group by Nombre_Usuario ORDER BY Nombre_Usuario'); ?>
											</select>
										</div>
									</div>
								</div>
								<div id='usuario1'>
									
								</div>
								<div class="col-md-2">
										<div class="form-group">
										  <label for="FechaR">Fecha Inicio(dia-mes-año)</label>
										   
										  <input type="date" class="form-control" id="FechaI" placeholder="Fecha Inicio" 
										  value='<?php echo date('Y-m-d'); ?>' 
										  onKeyPress="return soloNumeros(event)"  maxlength="10" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
										  <input type="hidden" id='usuario' name='usuario'  value='<?php echo date('Y-m-d'); ?>' />
										  <label for="Fecha">Fecha Fin(dia-mes-año)</label>
										   
										  <input type="date" class="form-control" id="FechaF" placeholder="Fecha Fin" 
										  value='<?php echo date('Y-m-d'); ?>' onKeyPress="return soloNumeros(event)" 
										  maxlength="10" >
										</div>
									</div>
								<div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
									<div class="form-group">
										<button type="button"  class="btn btn-primary" id='buscarusu' onclick="return buscar('buscarusu');">Buscar</button>
										<a href='descarga.php?mod=empresa&acc=cambiou&acc1=Administrar Usuario&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
							&Opcb=6&Opcen=0&b=0&ex=1' class="btn btn-primary" target='_blank' id='exportar' onclick='valicarcu();'>
											Exportar
										</a>
									</div>
								</div>
								<div id='buscarusu1'>
								</div>
								<!--<div class="form-group">
									<div class="col-md-12">
										<div id="alerta" class="alert invisible"></div>
										<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
											En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:
											+593-2-321-0051 / +593-9-8035-5483</p>
									</div>	
									<div class="col-md-9">
										<button id="btnCopiar" class="btn btn-primary" onclick='cambiarEmpresa();'>Cambiar</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									</div>
								</div>
								<div class="modal fade" id="myModal" role="dialog" >
									<div class="modal-dialog" >
									
									
									  <div class="modal-content" >
										<div class="modal-header" style="background-color: #367fa9;color: #fff;">
										  <button type="button" class="close" data-dismiss="modal" 
										  style="color: #fff;">&times;</button>
										  <h4 class="modal-title">Modificar empresa</h4>
										</div>
										<div class="modal-body" style="height:250px;overflow-y: scroll;">
											<div class="box-body">
												<div class="form-group">
												    <label for="Entidad">Entidad</label>
												    <select class="form-control" name="entidad" id='entidad' onChange="return buscar('entidad');">
														<option value='0'>Seleccione Entidad</option>
														<?php select_option_mysql('entidad','ID_Empresa','Nombre_Entidad',''); ?>
													</select>
												</div>
												
												<div id='entidad1'>
													
												</div>
												<div id='empresa1'>
												</div>
												
											</div>
											
											<div class="form-group">
												<div class="row">
													
												  <div class="col-4">
													
													<div class="list-group" id="myList" role="tablist">
														
													</div>
												  </div>
												 
												  </div>
												</div>
												
											</div>
										</div>
										<div class="modal-footer" style="background-color: #fff;">
											<div id="alerta" class="alert invisible"></div>
											<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
											En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:<br> 
											+593-2-321-0051 / +593-9-8035-5483</p>
											
											<button id="btnCopiar" class="btn btn-primary" onclick='cambiarEmpresa();'>Cambiar</button>
										    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									  </div>
									  
									</div>-->
								</div>
								
								<script>
								$('#myList a').on('click', function (e) {
								  e.preventDefault()
								  $(this).tab('show');
									});
									$(".loader1").hide();
									$(function() { 
										$("#myModal").modal();
										//$("#dialog").dialog(); 
									});
									function cambiarEmpresa()
									{
										var empresa = document.getElementById('empresa');
										var Estado = document.getElementById('Estado');
										var Mensaje = document.getElementById('Mensaje');
										var Fecha = document.getElementById('Fecha');
										var Servidor = document.getElementById('Servidor');
										var Base = document.getElementById('Base');
										var Usuario = document.getElementById('Usuario');
										var Clave = document.getElementById('Clave');
										var Motor = document.getElementById('Motor');
										var Puerto = document.getElementById('Puerto');
										var FechaR = document.getElementById('FechaR');
										var FechaV = document.getElementById('FechaV');
									
										$.post('ajax/vista_ajax.php'
										, {ajax_page: 'cambiarEmpresa', campo1: empresa.value, 
										campo2: Estado.value, campo3: Mensaje.value, campo4: Fecha.value,
										campo5: Servidor.value, campo6: Base.value, campo7: Usuario.value,
										campo8: Clave.value, campo9: Motor.value, campo10: Puerto.value, 
										campo11: FechaR.value, campo12: FechaV.value
										})
										.done(function( data, textStatus, jqXHR ) {
												if ( console && console.log ) {
													console.log( "La solicitud se ha completado correctamente." );
													if(data.success)
													{
																Swal.fire({
														  title: 'Empresa modificada!',
														  text: 'empresa modificada con exito.',
														  
														  animation: false
														}).then((result) => {
																  if (
																	result.value
																  ) {
																	console.log('I was closed by the timer');
																	//location.href ="empresa.php?mod=empresa";
																  }
																});
													}
													else
													{
														 Swal.fire({
														  type: 'error',
														  title: 'Oops...',
														  text: 'No se pudo modificar base de datos!'
														});
													}
												}
											})
											.fail(function( jqXHR, textStatus, errorThrown ) {
												if ( console && console.log ) {
													console.log( "Algo ha fallado: " +  textStatus);
													 Swal.fire({
														  type: 'error',
														  title: 'Oops...',
														  text: 'No se pudo modificar base de datos!'
														});
													 
												}
										});
									}
									function valicarcu() 
									{
										var value1 = document.getElementById('entidad_u').value;
										var ch1 = '0';
										var isChecked = document.getElementById('entidadch').checked;
										if(isChecked)
										{
											ch1 = '1';
										}
										var value3 = document.getElementById('usuario').value;
										var ch2 = '0';
										var isChecked = document.getElementById('usuarioch').checked;
										if(isChecked)
										{
											ch2 = '1';
										}
										var ch3 = '0';
										var isChecked = document.getElementById('empresach').checked;
										if(isChecked)
										{
											ch3 = '1';
										}
										var value7 = document.getElementById('empresa').value;
										var value5 = document.getElementById('FechaI').value;
										var value6 = document.getElementById('FechaF').value;
										
										var exportar=$('#exportar').attr("href");
										//agregar fechas				
										//var l1=l1+'&OpcDG='+texto;
										var exportar=exportar+'&ch1='+ch1+'&value1='+value1+'&ch2='+ch2+'&value3='+value3+'&ch3='+ch3+'&value7='+value7+'&value5='+value5+'&value6='+value6+'';
										//asignamos
										$("#exportar").attr("href",exportar);
									}
								</script>
								<!-- /.modal -->
					<?php
							}
							else
							{
			?>
								<script>
									/*
										let timerInterval
										Swal.fire({
										  title: 'Mayorizando!',
										  html: 'quedan <strong></strong> segundos.',
										  timer: 4000,
										  onBeforeOpen: () => {
											Swal.showLoading()
											timerInterval = setInterval(() => {
											  Swal.getContent().querySelector('strong')
												.textContent = Swal.getTimerLeft()
											}, 100)
										  },
										  onClose: () => {
											clearInterval(timerInterval)
										  }
										}).then((result) => {
										  if (
											// Read more about handling dismissals
											result.dismiss === Swal.DismissReason.timer
										  ) {
											console.log('I was closed by the timer');
											 //location.href ="contabilidad.php?mod=contabilidad";
										  }
										});*/
								  $(".loader1").hide();
								  <?php
										//
										//die();
									?>
								
								 // $(".loader2").show();
								 Swal.fire({
								  title: 'Terminado!',
								  text: 'Error al cargar formulario.',
								  
								  animation: false
								}).then((result) => {
										  if (
											result.value
										  ) {
											console.log('I was closed by the timer');
											location.href ="contabilidad.php?mod=contabilidad&er=1";
										  }
										});
							</script>
			<?php
							}
			?>
            </div>
			
				
            </div>
				
            <!-- /.box-body -->
          </div>
	</div>
</div>
<script>
	


	//Date picker
    $('#desde').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });
	$('#hasta').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });
	//modificar url
	function modificar(texto){
		var l1=$('#l1').attr("href");  
		var l1=l1+'&OpcDG='+texto;
		//asignamos
		$("#l1").attr("href",l1);
		
		var l2=$('#l2').attr("href");  
		var l2=l2+'&OpcDG='+texto;
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
