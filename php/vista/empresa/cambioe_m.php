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
						<a class="btn btn-default"  title="Mensaje masivo" href="#" data-placement="right"
						onclick='mmasivo();'	>
							<i ><img src="../../img/png/masivo.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
					<h4 class="box-title">
						<a class="btn btn-default"  title="Mensaje solo a entidad" href="#" data-placement="right"
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
						<a class="btn btn-default"  title="Guardar Masivo" href="#" data-placement="right"
						onclick='cambiarEmpresaMa();'>
							<i ><img src="../../img/png/guardarmasivo.png" class="user-image" alt="User Image"
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
