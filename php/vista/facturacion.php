<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

@session_start();
// print_r($_SESSION['INGRESO']['modulo_']);

$_SESSION['INGRESO']['modulo_']='02';
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
    <section class="content">

<?php 
    //llamamos a los parciales
	if (isset($_SESSION['INGRESO']['accion'])) 
		{
			echo '<section class="content-header">
		<h1>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>'.$_GET['mod'].'</a></li>
        <li class="active">';if(isset($_SESSION['INGRESO']['accion1'])){echo $_SESSION['INGRESO']['accion1']; } echo '</li>
      </ol>
    </section>';

    echo '<section class="content">';
    
			//facturar pension
			if ($_SESSION['INGRESO']['accion']=='facturarPension') 
			{
				require_once("facturacion/facturar_pension.php");
			}
			//compra / venta divisas
			if ($_SESSION['INGRESO']['accion']=='divisas') 
			{
				require_once("facturacion/divisas.php");
			}
			//listar y anular facturas 
			if ($_SESSION['INGRESO']['accion']=='listarFactura') 
			{
				require_once("facturacion/listar_facturas.php");
			}
			if ($_SESSION['INGRESO']['accion']=='facturarLista') 
			{
				require_once("facturacion/lista_facturas.php");
			}
			if ($_SESSION['INGRESO']['accion']=='facturar') 
			{
				require_once("facturacion/facturar.php");
			}
			if ($_SESSION['INGRESO']['accion']=='punto_venta') 
			{
				require_once("facturacion/punto_venta.php");
			}
			if ($_SESSION['INGRESO']['accion']=='catalogoPro') 
			{
				require_once("inventario/catalogo_producto.php");
			}
		}else
		{
			echo "<div class='box-body'><img src='../../img/modulo_facturacion.png' width='100%'></div>";
		}

?>

    </section>
  </div>
<?php				
  require_once("../headers/footer.php");
?>	
		
	
