<?php

    if(!isset($_SESSION)) 
	 		session_start();
	//datos para consultar
	//CI_NIC
	//echo $_SESSION['INGRESO']['Opc'].' '.$_SESSION['INGRESO']['Sucursal'].' '.$_SESSION['INGRESO']['item'].' '.$_SESSION['INGRESO']['periodo'].' ';

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
		$_SESSION['INGRESO']['ti']='ADMINISTRAR EMPRESA';
	}
?>



 <div class="row">
		 <div class="col-xs-12">
			 <div class="box">
			  <div class="box-header">
					<h4 class="box-title">
						<a class="btn btn-default"  title="Salir del modulo" href="panel.php?sa=s" data-placement="right">
							<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Mensaje masivo todas las empresas" href="#" data-placement="right"
						onclick='mmasivo();'	>
							<i ><img src="../../img/png/masivo.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Mensaje masivo a grupo seleccionado" href="#" data-placement="right"
						onclick='mgrupo();'	>
							<i ><img src="../../img/png/email_grupo.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Mensaje solo a empresa" href="#" data-placement="right"
						onclick='mindividual();'>
							<i ><img src="../../img/png/mensajei.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Guardar" href="#" data-placement="right"
						onclick='cambiarEmpresa();'>
							<i ><img src="../../img/png/grabar.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Guardar Masivo: Fechas de renovaciones" href="#" data-placement="right"
						onclick='cambiarEmpresaMa();'>
							<i ><img src="../../img/png/guardarmasivo.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Mostrar Vencimiento" href="#" data-placement="right"
						onclick='mostrarEmpresa();'>
							<i ><img src="../../img/png/reporte_1.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title" id='mostraEm' hidden="true">
						<a class="btn btn-default"  title="Mostrar Vencimiento" data-placement="right"
						onclick='mostrarEmpresa();' target="_blank" id='mostraEm_' href="descarga.php?mod=contabilidad&acc=mosEm&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&ex=1">
							<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
			  </div>
			 </div>
		 </div>
	  </div>

<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box table-responsive">
            <div class="box-header">
              <!--<h3 class="box-title">Striped Full Width Table</h3>-->
			  <table>				
			  </table>
			  <table id='mostraEm1' hidden="true">
				<tr>
					<td>
						<b>Desde:</b>
					</td>
					<td>
					   <!--<input type="date" id="desde" class="form-control"  value="<?php //echo date("d-m-Y");?>" >-->
					   <input type="date" class="form-control input-sm" id="desde" value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
					</td>
					<td>
						<b>Hasta:&nbsp;</b>
					</td>
					<td>
						<input type="date" id="hasta"  class="form-control"  value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);consultar_datos();">
					</td>
			  </table>
			<?php
					
					?>
					
					<?php
					$texto[0]=1;
					if(count($texto)>0)
							{
					?>			
								<div id='mostraE' style="display:none;">
									
									<div class="tab-content" style="background-color:#E7F5FF">
										<div id="home" class="tab-pane fade in active">									
										   <div class="table-responsive" id="tabla_" style="overflow-y: scroll; overflow-x: scroll; height:450px; width: auto;">
												<style type="text/css">
													#datos_t table {
													  border-collapse: collapse;
													}

													#datos_t table, th, td {
													  /*border: solid 1px black;*/
													  padding: 2px;
													}

													#datos_t tbody tr:nth-child(even) {
													  background:#fffff;
													}

													#datos_t tbody tr:nth-child(odd) {
													  background: #e2fbff;;
													}

													#datos_t tbody tr:nth-child(even):hover {
													  background: #DDB;
													}

													#datos_t tbody tr:nth-child(odd):hover {
													  background: #DDA;
													}

													.sombra {
													  width: 99%;
													  box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);
													}
												</style>
												<div id='lista'>
													<?php //$balance=ListarEmpresasSQL('Analisis de vencimiento',null,null,null,null,null,null); ?>	
												</div>
										   </div>
										</div>		  	    	  	    	
									</div>
									<!--<div class="box table-responsive">
										
									</div>-->
								</div>
								<!-- Modal -->
								<div class="col-md-3">
									<div class="form-group">
										<label for="Entidad">Entidad</label>
										<select class="form-control" name="entidad" id='entidad' onChange="return buscar('ciudad');">
											<option value=''>Seleccione Entidad</option>
										</select>
									</div>
								</div>
								<div id='ciudad1'>
									
								</div>
								<div id='entidad1'>
									
								</div>
								<div id='empresa1'>
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
									 $(document).ready(function()
                                       {
                                        // cargar_modulos();
                                        autocmpletar();
                                       });

								$('#myList a').on('click', function (e) {
								  e.preventDefault()
								  $(this).tab('show');
									});
									$(".loader1").hide();
									$("#mostraEm").hide();
									$("#0").hide();
									$(function() { 
										$("#myModal").modal();
										//$("#dialog").dialog(); 
									});

									 function autocmpletar(){
                                        $('#entidad').select2({
                                          placeholder: 'Seleccione una Empresa',
                                          ajax: {
                                            url: '../controlador/niveles_seguriC.php?entidades=true',
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
								
									function consultar_datos()
									{
										let desde= document.getElementById('desde');
										let hasta= document.getElementById('hasta');
										///alert(desde.value+' '+hasta.value);
										var parametros =
										{
											'desde':desde.value,
											'hasta':hasta.value			
										}
										$titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
										$.ajax({
											data:  {parametros:parametros},
											url:   '../controlador/contabilidad_controller.php?consultar=true',
											type:  'post',
											//dataType: 'json',
											beforeSend: function () {		
											  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
												 // $('#tabla_').html(spiner);
												 $('#myModal_espera').modal('show');
											},
												success:  function (response) {
													$('#lista').html(response);
													$('#myModal_espera').modal('hide');
													var mostraEm_=$('#mostraEm_').attr("href");  
													var mostraEm_=mostraEm_+'&desde='+desde.value+'&hasta='+hasta.value;
													//asignamos
													$("#mostraEm_").attr("href",mostraEm_);
												/*consultar_totales();
												
												 $('#tabla_').html(response);
												 var nFilas = $("#tabla_ tr").length;
												 // $('#num_r').html(nFilas-1);	
												 $('#myModal_espera').modal('hide');	
												 $('#tit').text($titulo);	*/	
											}
										});
										//document.getElementById('desde').value=desde;
									   // document.getElementById('hasta').value=hasta;
									}
									// function mostrarEmpresa()
									// {
									// 	//$(".loader1").show();
									// 	var x = document.getElementById('mostraE');
									// 	var x1 = document.getElementById('mostraEm');
										
									// 	if (x.style.display === 'none') 
									// 	{
									// 		x.style.display = 'block';
									// 		$("#mostraEm").show();
									// 		$("#mostraEm1").show();
									// 	} 
									// 	else 
									// 	{
									// 		x.style.display = 'none';
									// 		$("#mostraEm").hide();
									// 		$("#mostraEm1").hide();
									// 	}
									// }
									// function cambiarEmpresaMa()
									// {
									// 	var entidad = document.getElementById('entidad');
									// 	var empresa = document.getElementById('empresa');
									// 	var Estado = document.getElementById('Estado');
									// 	var Mensaje = document.getElementById('Mensaje');
									// 	var Fecha = document.getElementById('Fecha');
									// 	var Servidor = document.getElementById('Servidor');
									// 	var Base = document.getElementById('Base');
									// 	var Usuario = document.getElementById('Usuario');
									// 	var Clave = document.getElementById('Clave');
									// 	var Motor = document.getElementById('Motor');
									// 	var Puerto = document.getElementById('Puerto');
									// 	var FechaR = document.getElementById('FechaR');
									// 	var FechaV = document.getElementById('FechaV');
									
									// 	$.post('ajax/vista_ajax.php'
									// 	, {ajax_page: 'cambiarEmpresaMa', campo1: empresa.value, 
									// 	campo2: Estado.value, campo3: Mensaje.value, campo4: Fecha.value,
									// 	campo5: Servidor.value, campo6: Base.value, campo7: Usuario.value,
									// 	campo8: Clave.value, campo9: Motor.value, campo10: Puerto.value, 
									// 	campo11: FechaR.value, campo12: FechaV.value,campo13: entidad.value
									// 	})
									// 	.done(function( data, textStatus, jqXHR ) {
									// 			if ( console && console.log ) {
									// 				console.log( "La solicitud se ha completado correctamente." );
									// 				if(data.success)
									// 				{
									// 							Swal.fire({
									// 					  title: 'Entidad modificada!',
									// 					  text: 'entidad modificada con exito.',
														  
									// 					  animation: false
									// 					}).then((result) => {
									// 							  if (
									// 								result.value
									// 							  ) {
									// 								console.log('I was closed by the timer');
									// 								//location.href ="empresa.php?mod=empresa";
									// 							  }
									// 							});
									// 				}
									// 				else
									// 				{
									// 					 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
									// 				}
									// 			}
									// 		})
									// 		.fail(function( jqXHR, textStatus, errorThrown ) {
									// 			if ( console && console.log ) {
									// 				console.log( "Algo ha fallado: " +  textStatus);
									// 				 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
													 
									// 			}
									// 	});
									// }
									// function cambiarEmpresa()
									// {
									// 	var empresa = document.getElementById('empresa');
									// 	var Estado = document.getElementById('Estado');
									// 	var Mensaje = document.getElementById('Mensaje');
									// 	var Fecha = document.getElementById('Fecha');
									// 	var Servidor = document.getElementById('Servidor');
									// 	var Base = document.getElementById('Base');
									// 	var Usuario = document.getElementById('Usuario');
									// 	var Clave = document.getElementById('Clave');
									// 	var Motor = document.getElementById('Motor');
									// 	var Puerto = document.getElementById('Puerto');
									// 	var FechaR = document.getElementById('FechaR');
									// 	var FechaV = document.getElementById('FechaV');
									// 	var Fechadb = document.getElementById('FechaDB');
									// 	var Fechap12 = document.getElementById('FechaP12');
									
									// 	$.post('ajax/vista_ajax.php'
									// 	, {ajax_page: 'cambiarEmpresa', campo1: empresa.value, 
									// 	campo2: Estado.value, campo3: Mensaje.value, campo4: Fecha.value,
									// 	campo5: Servidor.value, campo6: Base.value, campo7: Usuario.value,
									// 	campo8: Clave.value, campo9: Motor.value, campo10: Puerto.value, 
									// 	campo11: FechaR.value, campo12: FechaV.value, campo13: Fechadb.value, campo14: Fechap12.value
									// 	})
									// 	.done(function( data, textStatus, jqXHR ) {
									// 			if ( console && console.log ) {
									// 				console.log( "La solicitud se ha completado correctamente." );
									// 				if(data.success)
									// 				{
									// 							Swal.fire({
									// 					  title: 'Empresa modificada!',
									// 					  text: 'empresa modificada con exito.',
														  
									// 					  animation: false
									// 					}).then((result) => {
									// 							  if (
									// 								result.value
									// 							  ) {
									// 								console.log('I was closed by the timer');
									// 								//location.href ="empresa.php?mod=empresa";
									// 							  }
									// 							});
									// 				}
									// 				else
									// 				{
									// 					 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
									// 				}
									// 			}
									// 		})
									// 		.fail(function( jqXHR, textStatus, errorThrown ) {
									// 			if ( console && console.log ) {
									// 				console.log( "Algo ha fallado: " +  textStatus);
									// 				 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
													 
									// 			}
									// 	});
									// }
									// function mindividual()
									// {
									// 	var entidad = document.getElementById('entidad');
									// 	var empresa = document.getElementById('empresa');
									// 	var Estado = document.getElementById('Estado');
									// 	var Mensaje = document.getElementById('Mensaje');
									// 	var Fecha = document.getElementById('Fecha');
									// 	var Servidor = document.getElementById('Servidor');
									// 	var Base = document.getElementById('Base');
									// 	var Usuario = document.getElementById('Usuario');
									// 	var Clave = document.getElementById('Clave');
									// 	var Motor = document.getElementById('Motor');
									// 	var Puerto = document.getElementById('Puerto');
									
									// 	$.post('ajax/vista_ajax.php'
									// 	, {ajax_page: 'mindividual', campo1: entidad.value, 
									// 	campo2: Estado.value, campo3: Mensaje.value, campo4: Fecha.value,
									// 	campo5: Servidor.value, campo6: Base.value, campo7: Usuario.value,
									// 	campo8: Clave.value, campo9: Motor.value, campo10: Puerto.value,campo11:empresa.value
									// 	})
									// 	.done(function( data, textStatus, jqXHR ) {
									// 			if ( console && console.log ) {
									// 				console.log( "La solicitud se ha completado correctamente." );
									// 				if(data.success)
									// 				{
									// 							Swal.fire({
									// 					  title: 'Mensaje modificado!',
									// 					  text: 'Mensaje a entidad modificado con exito.',
														  
									// 					  animation: false
									// 					}).then((result) => {
									// 							  if (
									// 								result.value
									// 							  ) {
									// 								console.log('I was closed by the timer');
									// 								//location.href ="empresa.php?mod=empresa";
									// 							  }
									// 							});
									// 				}
									// 				else
									// 				{
									// 					 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
									// 				}
									// 			}
									// 		})
									// 		.fail(function( jqXHR, textStatus, errorThrown ) {
									// 			if ( console && console.log ) {
									// 				console.log( "Algo ha fallado: " +  textStatus);
									// 				 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
													 
									// 			}
									// 	});
									// }

									// function mgrupo()
									// {
									// 	var entidad = document.getElementById('entidad');
									// 	var Ciudad = document.getElementById('ciudad');
									// 	var Estado = document.getElementById('Estado');
									// 	var Mensaje = document.getElementById('Mensaje');
									// 	var Fecha = document.getElementById('Fecha');
									// 	var Servidor = document.getElementById('Servidor');
									// 	var Base = document.getElementById('Base');
									// 	var Usuario = document.getElementById('Usuario');
									// 	var Clave = document.getElementById('Clave');
									// 	var Motor = document.getElementById('Motor');
									// 	var Puerto = document.getElementById('Puerto');
									
									// 	$.post('ajax/vista_ajax.php'
									// 	, {ajax_page: 'mgrupo', campo1: entidad.value, 
									// 	campo2: Estado.value, campo3: Mensaje.value, campo4: Fecha.value,
									// 	campo5: Servidor.value, campo6: Base.value, campo7: Usuario.value,
									// 	campo8: Clave.value, campo9: Motor.value, campo10: Puerto.value,campo11:Ciudad.value,
									// 	})
									// 	.done(function( data, textStatus, jqXHR ) {
									// 			if ( console && console.log ) {
									// 				console.log( "La solicitud se ha completado correctamente." );
									// 				if(data.success)
									// 				{
									// 							Swal.fire({
									// 					  title: 'mensaje modificado!',
									// 					  text: 'mensaje modificado en las entidades con exito.',
														  
									// 					  animation: false
									// 					}).then((result) => {
									// 							  if (
									// 								result.value
									// 							  ) {
									// 								console.log('I was closed by the timer');
									// 								//location.href ="empresa.php?mod=empresa";
									// 							  }
									// 							});
									// 				}
									// 				else
									// 				{
									// 					 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
									// 				}
									// 			}
									// 		})
									// 		.fail(function( jqXHR, textStatus, errorThrown ) {
									// 			if ( console && console.log ) {
									// 				console.log( "Algo ha fallado: " +  textStatus);
									// 				 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
													 
									// 			}
									// 	});
									// }

									// function mmasivo()
									// {
									// 	var empresa = document.getElementById('empresa');
									// 	var Estado = document.getElementById('Estado');
									// 	var Mensaje = document.getElementById('Mensaje');
									// 	var Fecha = document.getElementById('Fecha');
									// 	var Servidor = document.getElementById('Servidor');
									// 	var Base = document.getElementById('Base');
									// 	var Usuario = document.getElementById('Usuario');
									// 	var Clave = document.getElementById('Clave');
									// 	var Motor = document.getElementById('Motor');
									// 	var Puerto = document.getElementById('Puerto');
									
									// 	$.post('ajax/vista_ajax.php'
									// 	, {ajax_page: 'mmasivo', campo1: empresa.value, 
									// 	campo2: Estado.value, campo3: Mensaje.value, campo4: Fecha.value,
									// 	campo5: Servidor.value, campo6: Base.value, campo7: Usuario.value,
									// 	campo8: Clave.value, campo9: Motor.value, campo10: Puerto.value
									// 	})
									// 	.done(function( data, textStatus, jqXHR ) {
									// 			if ( console && console.log ) {
									// 				console.log( "La solicitud se ha completado correctamente." );
									// 				if(data.success)
									// 				{
									// 							Swal.fire({
									// 					  title: 'mensaje modificado!',
									// 					  text: 'mensaje modificado en las entidades con exito.',
														  
									// 					  animation: false
									// 					}).then((result) => {
									// 							  if (
									// 								result.value
									// 							  ) {
									// 								console.log('I was closed by the timer');
									// 								//location.href ="empresa.php?mod=empresa";
									// 							  }
									// 							});
									// 				}
									// 				else
									// 				{
									// 					 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
									// 				}
									// 			}
									// 		})
									// 		.fail(function( jqXHR, textStatus, errorThrown ) {
									// 			if ( console && console.log ) {
									// 				console.log( "Algo ha fallado: " +  textStatus);
									// 				 Swal.fire({
									// 					  type: 'error',
									// 					  title: 'Oops...',
									// 					  text: 'No se pudo modificar base de datos!'
									// 					});
													 
									// 			}
									// 	});
									// }
									function copiar()
									{
										var codigoACopiar = document.getElementById('texto');
										var seleccion = document.createRange();
										seleccion.selectNodeContents(codigoACopiar);
										window.getSelection().removeAllRanges();
										window.getSelection().addRange(seleccion);
										try {
											var res = document.execCommand('copy'); //Intento el copiado
											if (res)
												exito();
											else
												fracaso();

											mostrarAlerta();
										}
										catch(ex) {
											excepcion();
										}
										window.getSelection().removeRange(seleccion);
									}
									function interceptarPegado(ev) {
										alert('Has pegado el texto:' + ev.clipboardData.getData('text/plain'));
									}

								///////
								// Auxiliares para mostrar y ocultar mensajes
								///////
									var divAlerta = document.getElementById('alerta');
									
									function exito() {
										divAlerta.innerText = '¡¡Copiado con exito al portapapeles!!';
										divAlerta.classList.add('alert-success');
									}

									function fracaso() {
										divAlerta.innerText = '¡¡Ha fallado el copiado al portapapeles!!';
										divAlerta.classList.add('alert-warning');
									}

									function excepcion() {
										divAlerta.innerText = 'Se ha producido un error al copiar al portapaples';
										divAlerta.classList.add('alert-danger');
									}

									function mostrarAlerta() {
										divAlerta.classList.remove('invisible');
										divAlerta.classList.add('visible');
										setTimeout(ocultarAlerta, 1500);
									}

									function ocultarAlerta() {
										divAlerta.innerText = '';
										divAlerta.classList.remove('alert-success', 'alert-warning', 'alert-danger', 'visible');
										divAlerta.classList.add('invisible');
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
