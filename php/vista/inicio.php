<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */
@session_start();
// chequea que esten con sesion

// cambiaria dependiendo el modulo


$_SESSION['INGRESO']['modulo_']=$_GET['mod'];
require_once("../db/chequear_seguridad.php");
require_once("../headers/header.php");

$modulo_header = '';
if(isset($_GET['mod']))
{
  $cod = $_GET['mod'];
  $detalle_modulo = datos_modulo($cod);
  $modulo_header = $detalle_modulo[0]['aplicacion'];
  $modulo_logo='../../img/modulos/diskcover.png';
  if(file_exists($detalle_modulo[0]['icono']))
  {
  	$modulo_logo = $detalle_modulo[0]['icono'];
  }
}


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
<?php 
    //llamamos a los parciales
	if (isset($_SESSION['INGRESO']['accion'])) 
	{
		echo '<section class="content">';


		//cambio de clave
		if ($_SESSION['INGRESO']['accion']=='cambioc') 
		{
			require_once("contabilidad/cambioc.php");
		}
		//ingreso catalogo de cuenta
		if ($_SESSION['INGRESO']['accion']=='incc') 
		{
			require_once("contabilidad/inccu.php");
		}
		//Mayorización
		if ($_SESSION['INGRESO']['accion']=='macom') 
		{
			require_once("contabilidad/macom.php");
		}
		//Balance de Comprobacion/Situación/General
		if ($_SESSION['INGRESO']['accion']=='bacsg') 
		{
			require_once("contabilidad/bacsg1.php");
		}
		//herramientas conexion oracle
		if ($_SESSION['INGRESO']['accion']=='hco') 
		{
			require_once("contabilidad/hco.php");
		}
		//comprobantes procesados
		if ($_SESSION['INGRESO']['accion']=='compro') 
		{
			require_once("contabilidad/compro.php");
		}
		//cambio de periodo
		if ($_SESSION['INGRESO']['accion']=='campe') 
		{
			require_once("contabilidad/campe.php");
		}
		//Ingresar Comprobantes (Crtl+f5)
		if ($_SESSION['INGRESO']['accion']=='incom') 
		{
			require_once("contabilidad/incom.php");
		}
		//saldo de factura submodulo
		if ($_SESSION['INGRESO']['accion']=='saldo_fac_submodulo') 
		{
			require_once("contabilidad/saldo_fac_submodulo.php");
		}
		if ($_SESSION['INGRESO']['accion']=='catalogo_cuentas') 
		{
			include("contabilidad/catalogoCta.php");
		}
		if ($_SESSION['INGRESO']['accion']=='diario_general') 
		{

			include("contabilidad/diario_general.php");
		}
		if ($_SESSION['INGRESO']['accion']=='mayor_auxiliar') 
		{			
			require_once("contabilidad/mayor_auxiliar.php");
		}
		if ($_SESSION['INGRESO']['accion']=='libro_banco') 
		{
			require_once("contabilidad/libro_banco.php");
		}
		if ($_SESSION['INGRESO']['accion']=='ctaOperaciones') 
		{
			require_once("contabilidad/ctaOperaciones.php");
		}
		if ($_SESSION['INGRESO']['accion']=='anexos_trans') 
		{
			require_once("contabilidad/anexos_trans.php");
		}
		if ($_SESSION['INGRESO']['accion']=='bamup') 
		{
			require_once("contabilidad/bamup.php");
		}
		if ($_SESSION['INGRESO']['accion']=='reportes') 
		{
			require_once("contabilidad/resumen_retenciones.php");
		}
		if ($_SESSION['INGRESO']['accion']=='Clientes') 
		{
			include("contabilidad/FCliente.php");
		}
		if ($_SESSION['INGRESO']['accion']=='subcta_proyectos') 
		{
			require_once("contabilidad/Subcta_proyectos.php");
		}
		if ($_SESSION['INGRESO']['accion']=='cierre_mes') 
		{
			require_once("contabilidad/cierre_mes.php");
		}
		if ($_SESSION['INGRESO']['accion']=='MayoresSubCta') 
		{
			require_once("contabilidad/mayores_sub_cuenta.php");
		}
//facturacion

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
			if ($_SESSION['INGRESO']['accion']=='factura_elec') 
			{
				require_once("facturacion/facturacion_elec.php");
			}
			if ($_SESSION['INGRESO']['accion']=='catalogoPro') 
			{
				require_once("inventario/catalogo_producto.php");
			}

			if ($_SESSION['INGRESO']['accion']=='lineas_cxc') 
			{
				require_once("facturacion/lineas_cxc.php");
			}
//empresa

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
//farmacia
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
		//educativo
			if ($_SESSION['INGRESO']['accion']=='detalle_estudiante') 
			{
				require_once("educativo/detalle_estudiante.php");
			}
		//cliente proveedor
			if ($_SESSION['INGRESO']['accion']=='facturarLista') 
			{
				require_once("facturacion/lista_facturas.php");
			}

		//inventario
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
		switch ($_SESSION['INGRESO']['modulo_']) {
			case '01':
				echo "<div class='box-body'><img src='../../img/modulo_contable.gif' width='100%'></div>";
				break;
			case '28':
				echo "<div class='box-body'><img src='../../img/modulo_farmacia.png' width='100%'></div>";
				break;
			case '02':
				echo "<div class='box-body'><img src='../../img/modulo_facturacion.png' width='100%'></div>";
				break;
			case '09':
				echo "<div class='box-body'><img src='../../img/modulo_electronicos.png' width='100%'></div>";
				break;
			case '10':
				echo "<div class='box-body'><img src='../../img/modulo_comprobantes.png' width='100%'></div>";
				break;
			case '99':
				echo "<div class='box-body'><img src='../../img/modulo_empresa.png' width='100%' heigth='500px'></div>";
				break;			
			case '10':
				echo "<div class='box-body'><img src='../../img/modulo_cliente_pro.png' width='100%'></div>";
				break;
			case '11':
				echo "<div class='box-body'><img src='../../img/modulo_educativo.png' width='100%' heigth='500px'></div>";
				break;
			case '03':
				echo "<div class='box-body'><img src='../../img/modulo_inventario1.gif' width='100%'></div>";
				break;
			
			default:
				$titulo = "<div class='box-body' style='position:absolute'>
				<img src='../../img/fondo.png' width='100%'>
				<div style='position:absolute;top:15%;left:70%;width:25%'>
					<img src='".$modulo_logo."' width='100%'>
				</div>
				<div style='position:absolute;top:20%;left:8%;font-size:97px'>
				<b>MÓDULO</b>
				</div>";
				if(isset($_GET['mod']))
				{					
					$text = explode(' ',$modulo_header);
					$salto = 3;
					foreach ($text as $key => $value) {
						$titulo.= "<div style='position:absolute;top:".$salto."2%;left:8%;font-size:97px'>";
						$titulo.='<b>'.$value.'</b>';
						$titulo.="</div>";
						$salto = $salto+1;
					}	
				}
				$titulo.='</div>';				
				echo $titulo;
				break;
		}
	}

?>

    </section>
  </div>
<?php	require_once("../headers/footer.php"); ?>	
		
	
