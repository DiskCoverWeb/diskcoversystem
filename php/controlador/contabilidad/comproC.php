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
if(isset($_GET['anular_comprobante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular_comprobante($parametros));
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

	function anular_comprobante($parametros)
	{
		$datos_com = $this->modelo->Listar_el_Comprobante($parametros['numero'],$parametros['TP']);
		$Co = $datos_com[0];
		// print_r($Co);die();
		// var $ClaveSupervisor = $parametros[''];
		// if($ClaveSupervisor)
		// {
		   $CompCierre = false;
		  // 'Co = ObtenerNumeroDeComp
		   $FechaInicial = $Co['Fecha'];
		   $FechaFinal = $Co['Fecha'];
		   if(strpos($Co['Concepto'], "Cierre de Caja de") !== false){ $CompCierre = True; }
		   if($CompCierre){
		      // If InStr(Co.Concepto, "Cierre de Caja de Cuentas por Cobrar") > 0 Then EsCxC = True Else EsCxC = False
		      // UnaFecha = True
		      // For IdF = 1 To Len(Co.Concepto)
		      //     Mifecha = MidStrg(Co.Concepto, IdF, 10)
		      //     If IsDate(Mifecha) Then
		      //        If Year(Mifecha) >= 1900 Then
		      //           If UnaFecha Then
		      //              FechaInicial = Mifecha
		      //              FechaFinal = Mifecha
		      //              UnaFecha = False
		      //              IdF = IdF + 10
		      //           Else
		      //              FechaFinal = Mifecha
		      //              IdF = IdF + 10
		      //           End If
		      //        End If
		      //     End If
		      // Next IdF
			}
		    $FechaIni = BuscarFecha($FechaInicial);
		    $FechaFin = BuscarFecha($FechaFinal);
		   	$AnularComprobanteDe = "WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		                       AND Item = '".$Co['Item']."' 
		                       AND TP = '".$Co['TP']."' 
		                       AND Numero = ".$Co['Numero']." ";

		                       // print_r($datos_com);die();
		 
		//       Control_Procesos "A", "Anulo Comprobante de: " & Co.TP & " No. " & Co.Numero
		      if(strpos($Co['Concepto'], "(ANULADO)") !== false){
		         $Contra_Cta = $Co['Concepto'];
		      }else{
		         $MotivoAnulacion =  strtoupper("FORMULARIO DE ANULACION");
		         $Contra_Cta = "(ANULADO) ";
		         if($MotivoAnulacion <> ""){
		         	$Contra_Cta = $Contra_Cta." [MOTIVO: ".$MotivoAnulacion."], ";
		         	$Contra_Cta = $Contra_Cta.$Co['Concepto'];
		         }
		      }
		        $Contra_Cta = substr($Contra_Cta, 1, 120);
				//'Actualizamos Comprobante
		      	$this->modelo->Actualizamos_Comprobante($Contra_Cta,$AnularComprobanteDe);
		     	// 'Actualizar Transacciones
		        $this->modelo->Actualizar_Transacciones($AnularComprobanteDe);		     
		     	// 'Actualizar Trans_SubCtas
		     	$this->modelo->Actualizar_Trans_SubCtas($AnularComprobanteDe);

		     	// 'Actualizar Retencion
		     	$this->modelo->Actualizar_Retencion($AnularComprobanteDe);		     
		     
				//'Eliminamos el Rol de Pagos
		   		$this->modelo->Rol_de_Pagos($AnularComprobanteDe);
				//'Actualizar Kardex
			    if($CompCierre){
			          $this->modelo->Trans_Kardex_update_cierre($AnularComprobanteDe,$FechaIni,$FechaFin);
			        // 'MsgBox "CompCierre" & vbCrLf & sSQL
			    }else{
			      	 $datos = $this->modelo->Trans_Kardex($AnularComprobanteDe);
			      	 foreach ($datos as $key => $value) {
			      	 	$Codigo = $value["Codigo_Inv"];
			            $this->modelo->Trans_Kardex_update($Co['Item'],$Codigo);
			      	 }			        		         
			        $this->modelo->Trans_Kardex_delete($AnularComprobanteDe);
			    }
		     
				//'Actualizar las Ctas a mayoriazar
		     	$datos = $this->modelo->Transacciones($AnularComprobanteDe);
		     	foreach ($datos as $key => $value) {
		     		// 'Determinamos que la cuenta ya fue mayorizada
		              $SubCta = $value["Cta"];
		             $this->modelo->Transacciones_update($SubCta);	
		     	}

	}

}
?>