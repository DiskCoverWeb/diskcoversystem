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
		$_SESSION['INGRESO']['ti']='MAYORIZACIÓN';
	}
?>
 <div class="row">
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
					<h4 class="box-title">
						<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="panel.php?sa=s">
							<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<i ><img src="../../img/png/pbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l2' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance mensual"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Procesar balance mensual&ti=BALANCE MENSUAL&Opcb=2&Opcen=1&b=1">
							<i ><img src="../../img/png/pbm.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l3' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance consolidado de varias sucursales">
							<i ><img src="../../img/png/pbcs.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l4' class="btn btn-default"  data-toggle="tooltip" title="Presenta balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<i ><img src="../../img/png/vbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de situación (general: activo, pasivo y patrimonio)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
						>
							<i ><img src="../../img/png/bc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l6' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de resultado (ingreso y egresos)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de resultado&ti=ESTADO RESULTADO&Opcb=6&Opcen=0&b=0">
							<i ><img src="../../img/png/up.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="Presenta balance mensual por semana">
							<i ><img src="../../img/png/pbms.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="SBS B11">
							<i ><img src="../../img/png/books.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="Imprimir resultados">
							<i ><img src="../../img/png/impresora.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						
						<a id='l7' class="btn btn-default"  data-toggle="tooltip" title="Exportar Excel"
						href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank">
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
