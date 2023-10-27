<?php include('../headers/header.php'); //print_r($_SESSION['INGRESO']);die(); ?>
<!-- Content Wrapper. Contains page content -->


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

			include('val_estado.php');
			$minutos = 6;
			val_estado($minutos);

			



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
<script>
	
</script>
<?php include('../headers/footer.php'); ?>