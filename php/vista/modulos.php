<?php include('../headers/header.php'); //print_r($_SESSION['INGRESO']);die(); ?>
<!-- Content Wrapper. Contains page content -->

<head>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="../../dist/js/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="../../dist/css/sweetalert2.min.css">


</head>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $_SESSION['INGRESO']['item']; ?> -
			<?php echo $_SESSION['INGRESO']['noempr']; ?>
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
		if (!isset($_GET['mod'])) {
			$_SESSION['INGRESO']['modulo_'] = '';
			$_SESSION['INGRESO']['modulo'] = modulos_habiliatados();
			$todo = false;
			foreach ($_SESSION['INGRESO']['modulo'] as $key => $value) {
				if ($value['modulo'] == 'TO') {
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
				//$icon = "'info'";
				$css =
					'<style>
				body {
					font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif; 
				  }
					</style>';
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
				Swal.fire({
					title: "<strong>' . $titulo . '</strong>",
					html: "' . $mensaje_js . '",
					focusConfirm: false,
					icon: "error"
				}).then((result) => {
					if (result.isConfirmed) {
						Swal.fire(
							"ENVIO DE CORREO POR IMPLEMENTAR",
							"TESTTTTT",
							"success"
						).then(function() {
							logout();
						});
					}
				});';
				$continue =
					'Swal.fire(
					 "' . $titulo . '",
					 "' . $mensaje_js . '",
					 "info"
					 
				);';
				if ($rps == 'BLOQ' || $rps == 'MAS360' || $rps == 'VEN360' || $rps == 'noAuto' || $rps == 'noActivo') {
					echo
						'
					' . $css . '
					<script>
					' . $toLogin . '
					</script>';
				} else {
					echo
						'
					' . $css . '
					<script>
					' . $continue . '
					</script>';
				}
			}

			if ($todo == true) {
				if (!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_'] == "") {
					echo '<div class="row">' . contruir_todos_modulos() . '</div>';

				}


			} else {
				// print_r($_SESSION);die();
				if (!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_'] == "") {
					echo $l = '<div class="row">' . contruir_modulos($_SESSION['INGRESO']['modulo']) . '</div>';
				}

			}
		}

		?>




	</section>
	<!-- /.content -->
</div>
<?php include('../headers/footer.php'); ?>