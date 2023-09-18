<?php  include('../headers/header.php');//print_r($_SESSION['INGRESO']);die(); ?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $_SESSION['INGRESO']['item'];?> -
        <?php echo $_SESSION['INGRESO']['noempr'];?>
        <small>Panel de control</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    	<?php //print_r($_SESSION['INGRESO']);?>
     <?php 
     // nuevo contruccion javier               
               if(!isset($_GET['mod']))
               { 
               	$_SESSION['INGRESO']['modulo_']='';
               	$_SESSION['INGRESO']['modulo']=modulos_habiliatados();
				$todo = false;
				foreach ($_SESSION['INGRESO']['modulo'] as  $key => $value) {
					if($value['modulo']=='TO')
					{
						$todo = true;
						break;
					}					
				}

				/**
			 * Analiza el estado de la empresa
			 */

			$response = validar_estado_all();

			if ($response['rps'] != 'ok') {
				$rps = $response['rps'];
				$mensaje = $response['mensaje'];
				$mensaje_js = str_replace("\n", "\\n", $mensaje);
				$titulo = $response['titulo'];
				$icon = "'info'";
				$toLogin =
					'
				function logout()
				{ 
				   $.ajax({
					url:   "../controlador/login_controller.php?logout=true",
					type:  "post",
					dataType: "json",
					  success:  function (response) { 
						console.log(response);
					  if(response == 1)
					  {
						location.href = "login.php";          
					  }     
					}
				  });
				}
				Swal.fire(
					 "' . $titulo . '",
					 "' . $mensaje_js . '",
					 ' . $icon . '
				).then(function() {
					logout();
				});';
				$continue =
					'Swal.fire(
					 "' . $titulo . '",
					 "' . $mensaje_js . '",
					 ' . $icon . '
					 
				);';
				if ($rps == 'BLOQ' || $rps == 'MAS360' || $rps == 'VEN360' || $rps == 'noAuto' || $rps == 'noActivo') {
					echo
						'
					<script>
					' . $toLogin . '
					</script>';
				} else {
					echo
					'
					<script>
					' . $continue . '
					</script>';
				}
			}
				

				if($todo == true)
				  {
				  	if(!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_']==""){
				  	echo '<div class="row">'.contruir_todos_modulos().'</div>';
				    }
					
				   }else
					{
						// print_r($_SESSION);die();
						if(!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_']==""){
				         echo $l ='<div class="row">'.contruir_modulos($_SESSION['INGRESO']['modulo']).'</div>';
				       }

					}
			}

     ?>
      

    </section>
    <!-- /.content -->
  </div>
<?php  include('../headers/footer.php');?>