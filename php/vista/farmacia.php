<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

@session_start();
// print_r($_SESSION['INGRESO']['modulo_']);

$_SESSION['INGRESO']['modulo_']='28';
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

		//cambio de clave
		if ($_SESSION['INGRESO']['accion']=='ingresar_proveedor') 
			{
				require_once("farmacia/ingresar_proveedor.php");
			}
			if ($_SESSION['INGRESO']['accion']=='ingresar_paciente') 
			{
				require_once("farmacia/ingresar_paciente.php");
			}
			if ($_SESSION['INGRESO']['accion']=='ingresar_descargos') 
			{
				require_once("farmacia/ingreso_descargos.php");
			}
			if ($_SESSION['INGRESO']['accion']=='ingresar_factura') 
			{
				require_once("farmacia/ingresar_factura.php");
			}
			if ($_SESSION['INGRESO']['accion']=='articulos') 
			{
				require_once("farmacia/articulos.php");
			}
			if ($_SESSION['INGRESO']['accion']=='pacientes') 
			{
				require_once("farmacia/paciente.php");
			}
			if ($_SESSION['INGRESO']['accion']=='vis_descargos') 
			{
				require_once("farmacia/descargos.php");
			}
			if ($_SESSION['INGRESO']['accion']=='descargos_procesados') 
			{
				require_once("farmacia/reporte_descargos_procesados.php");
			}
			if ($_SESSION['INGRESO']['accion']=='facturacion_insumos') 
			{
				require_once("farmacia/facturacion_insumos.php");
			}
			if ($_SESSION['INGRESO']['accion']=='farmacia_interna') 
			{
				require_once("farmacia/farmacia_interna.php");
			}
			if ($_SESSION['INGRESO']['accion']=='devoluciones_insumos') 
			{
				require_once("farmacia/devoluciones_insumos.php");
			}
			if ($_SESSION['INGRESO']['accion']=='devoluciones_detalle') 
			{
				require_once("farmacia/devoluciones_detalle.php");
			}
			if ($_SESSION['INGRESO']['accion']=='devoluciones_departamento') 
			{
				require_once("farmacia/devoluciones_x_departamento.php");
			}
			if ($_SESSION['INGRESO']['accion']=='farmacia_interna_detalle') 
			{
				require_once("farmacia/farmacia_interna_detalle.php");
			}
			if ($_SESSION['INGRESO']['accion']=='prove_bodega') 
			{
				require_once("farmacia/proveedor_bodega.php");
			}
			if ($_SESSION['INGRESO']['accion']=='cliente_proveedor') 
			{
				require_once("farmacia/cliente_prove_bodega.php");
			}
			if ($_SESSION['INGRESO']['accion']=='descargos_bodega') 
			{
				require_once("farmacia/descargos_bodega.php");
			}
			if ($_SESSION['INGRESO']['accion']=='factura_bodega') 
			{
				require_once("farmacia/ingreso_factura_bodega.php");
			}
			if ($_SESSION['INGRESO']['accion']=='articulos_bodega') 
			{
				require_once("farmacia/articulos_bodega.php");
				// echo 'entro';
			}
						
	}else
	{
		echo "<div class='box-body'><img src='../../img/modulo_farmacia.png' width='100%'></div>";
	}

?>

    </section>
  </div>
<?php				
  require_once("../headers/footer.php");
?>	
		
	
