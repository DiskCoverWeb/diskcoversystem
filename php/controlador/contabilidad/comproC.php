<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/comproM.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_comp.php');
/**
 * 
 */
$controlador = new comproC();
if(isset($_GET['reporte']))
{
	$parametros = $_GET;
	echo json_encode($controlador->reporte_com($parametros));
}
class comproC 
{
	private $modelo;
	private $pdf;
	function __construct()
	{
		$this->modelo = new comproM();
		$this->pdf = new PDF();
	}

function reporte_com($parametro)
{
	$comprobante= $parametro['comprobante'];
	$tp= $parametro['TP'];
	// print_r($comprobante);die();
	$datos_com = $this->modelo->Listar_el_Comprobante($comprobante,$tp);
	if(count($datos_com)>0)
	{		
		//para ver cheuqes en tipo comprobante CE Y CI
		$datos_cheques = $this->modelo->cheques_debe($comprobante);
		$stmt8_count = count($datos_cheques);
		$datos_cheques_haber = $this->modelo->cheques_haber($comprobante);
		$stmt9_count = count($datos_cheques_haber);

		$TipoComp = $datos_com[0]['TP'];
		$Numero = $datos_com[0]['Numero'];
		// print_r($datos_com);die();
		$consulta = $this->modelo->comprobante_agrupado($Numero,$TipoComp);
		$concepto = 'Ninguno';
		// print_r($consulta);die();
		if(count($consulta)>0)
		{
			$t = $consulta[0]['T'];
			$Fecha = $consulta[0]['Fecha']->format('Y-m-d');
			$codigoB = $consulta[0]['Codigo_B'];
			$beneficiario = $consulta[0]['Cliente'];
			$concepto = $consulta[0]['Concepto'];
			$efectivo = number_format($consulta[0]['Efectivo'],2, ',', '.');
			//$num = NumerosEnLetras::convertir(1988208.99);
			//echo $num;
			//die();
			$est="Normal";
			if($t == 'A')
			{
				$est="Anulado";
			}
			$usuario= $consulta[0]['Nombre_Completo'];


			//Listar las Transacciones
			$transacciones = $this->modelo->Listar_las_Transacciones($Numero,$TipoComp);
			$stmt2_count = count($transacciones);
			

			//Llenar Bancos
			$llenar_ban = $this->modelo->llenar_banco($Numero,$TipoComp);			
			$stmt3_count = count($llenar_ban);
			
			//Listar las Retenciones del IVA
			$retencion = $this->modelo->Retenciones_IVA($Numero,$TipoComp);			
			$stmt4_count = count($retencion);


			//Listar las Retenciones de la Fuente
			$retencion_fuen = $this->modelo->Retenciones_Fuente($Numero,$TipoComp,$Fecha);			
			$stmt5_count = count($retencion_fuen);
			
			//Llenar SubCtas
			$subcta = $this->modelo->llenar_SubCta($Numero,$TipoComp);	
			$stmt6_count = count($subcta);

			// print_r($parametro);die();
			//llamamos a los pdf
			if($TipoComp=='CD')
			{
				imprimirCD($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
			}
			if($TipoComp=='CI')
			{
				imprimirCI($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $datos_cheques_haber, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,
				$stmt5_count,$stmt6_count,$stmt9_count);	
			}
			if($TipoComp=='CE')
			{
				imprimirCE($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $datos_cheques, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,
				$stmt5_count,$stmt6_count,$stmt8_count);
			}
			if($TipoComp=='ND')
			{
				imprimirND($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
			}
			if($TipoComp=='NC')
			{
				imprimirNC($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
			}
			

		}


	}else
	{
			echo "No existen datos";
	}
	
}

}
?>