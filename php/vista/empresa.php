<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

@session_start();
// print_r($_SESSION['INGRESO']['modulo_']);

$_SESSION['INGRESO']['modulo_']='99';
// chequea que esten con sesion
require_once("../db/chequear_seguridad.php");
//llamo la cabecera
require_once("../headers/header.php");

// chequea si hay una base de datos asignada
if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) && $_SESSION['INGRESO']['Tipo_Base'] =='SQL SERVER') 
{
	$permiso=getAccesoEmpresas();
}else
{
	echo "<script>
			Swal.fire({
			  type: 'error',
			  title: 'Comuniquese con el Administrador del Sistema, Para Activar el acceso a su base de dato de la nube',
			  text: 'Asegurese de tener credeciales de SQLSERVER',
			  allowOutsideClick:false,
			}).then((result) => {
			  if (result.value) {
				location.href='modulos.php';
			  } 
			});
		</script>";
}
?>

  <div class="content-wrapper">
    <!-- <section class="content-header">
      <h1>
        <small>Panel de control</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section> -->

<?php 
    //llamamos a los parciales
	if (isset($_SESSION['INGRESO']['accion'])) 
	{
		// echo '<section class="content-header">
		// <h1>
  //       <small></small>
  //     </h1>
  //     <ol class="breadcrumb">
  //       <li><a href="#"><i class="fa fa-dashboard"></i>'.$_GET['mod'].'</a></li>
  //       <li class="active">'.$_SESSION['INGRESO']['accion1'].'</li>
  //     </ol>
  //   </section>';

    echo '<section class="content">';

		//cambio de empresa
	if ($_SESSION['INGRESO']['accion']=='cambioe') 
	{
		require_once("empresa/cambioe.php");
	}
	//adminis. usuario
	if ($_SESSION['INGRESO']['accion']=='cambiou') 
	{
		require_once("empresa/cambiou.php");
	}
	if ($_SESSION['INGRESO']['accion']=='niveles_seguri') 
	{
		require_once("empresa/niveles_seguri.php");
	}
	if ($_SESSION['INGRESO']['accion']=='mostrar_venci') 
	{
		require_once("empresa/mostrar_venci.php");
	}
						
	}else
	{
		echo "<div class='box-body'><img src='../../img/modulo_empresa.png' width='100%' heigth='500px'></div>";
					
	}

?>

    </section>
  </div>
<?php				
  require_once("../headers/footer.php");
?>	
		
	

