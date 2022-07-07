<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

@session_start();
// print_r($_SESSION['INGRESO']['modulo_']);

$_SESSION['INGRESO']['modulo_']='03';
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


if(isset($_GET['cuenta']))
{
	if($_GET['cuenta']=='-1')
	{
		echo '<script type="text/javascript">$(document).ready(function(){ Swal.fire(Cuenta Cta_Desperdicio no encontrada","","info"); });</script>';

	}else if($_GET['cuenta']== '-2')
	{
		echo '<script type="text/javascript">$(document).ready(function(){ Swal.fire("Asegurese que la cuenta sea de detalle","","info"); });</script>';
	}
}

?>

  <div class="content-wrapper">
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
  //       <li class="active">';if(isset($_SESSION['INGRESO']['accion1'])){echo $_SESSION['INGRESO']['accion1']; } echo '</li>
  //     </ol>
  //   </section>';

    echo '<section class="content">';

		//cambio de clave
		if ($_SESSION['INGRESO']['accion']=='inventario_online') 
		{
			require_once("inventario/inventario_online.php");
		}
		if ($_SESSION['INGRESO']['accion']=='registro_es') 
		{
			require_once("inventario/registro_es.php");
		}
		if ($_SESSION['INGRESO']['accion']=='articulos') 
		{
			require_once("farmacia/articulos.php");
		}
		//kardex
		if ($_SESSION['INGRESO']['accion']=='kardex') 
		{
			require_once("inventario/kardex.php");
		}
		//ingreso de presusupuestos
		if ($_SESSION['INGRESO']['accion']=='ingreso_presupuesto') 
		{
			require_once("inventario/ingreso_presupuesto.php");
		}
		if ($_SESSION['INGRESO']['accion']=='catalogoPro') 
		{
			require_once("inventario/catalogo_producto.php");
		}
		
	}else
	{
		echo "<div class='box-body'><img src='../../img/modulo_inventario1.gif' width='100%'></div>";
	}

?>

    </section>
  </div>
<?php				
  require_once("../headers/footer.php");
?>	
		
	
