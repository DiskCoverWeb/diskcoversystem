<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/comproM.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_comp.php');
/**
 * 
 */
$controlador = new comproC();
if(isset($_GET['reporte']))
{
	$parametros = $_GET['comprobante'];
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
	$comprobante= $parametro;
	
	$datos_com = $this->modelo->Listar_el_Comprobante($comprobante);
	if(count($datos_com)>0)
	{		
		//para ver cheuqes en tipo comprobante CE Y CI
		$datos_cheques = $this->modelo->cheques_debe($comprobante);
		$stmt8_count = count($datos_cheques);
		$datos_cheques_haber = $this->modelo->cheques_haber($comprobante);
		$stmt9_count = count($datos_cheques_haber);

		$TipoComp = $datos_com[0]['TP'];
		$Numero = $datos_com[0]['Numero'];
		$consulta = $this->modelo->comprobante_agrupado($Numero,$TipoComp);
		$concepto = 'Ninguno';
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

			// print_r('ex');die();
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
	// $i=0;
	// while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	// {
		// $i++;
		// //Listar el Comprobante
		// $sql="SELECT  Periodo, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total,".
		// " CodigoU, Autorizado, Item, Si_Existe, Hora, CEj, X, ID ". 
		//    "FROM Comprobantes ".
		//    "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		//    "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ".
		//    "AND Numero = '".$_POST['com']."' ";
		// $sql=$sql." ORDER BY Numero ";
		// //echo $sql;
		// //die();
		// $stmt7 = sqlsrv_query( $cid, $sql);
		// if( $stmt7 === false)  
		// {  
		// 	 echo "Error en consulta Listar.\n";  
		// 	 die( print_r( sqlsrv_errors(), true));  
		// }
		// $sql="";
		// //para ver cheuqes en tipo comprobante CE Y CI
		// $sql=" select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Haber) as monto
		// 	 from Transacciones as t, Catalogo_Cuentas as cc
		// 	 where t.Item='".$_SESSION['INGRESO']['item']."' and t.Periodo='".$_SESSION['INGRESO']['periodo']."' 
		// 	 and t.TP='CE' and t.Numero='".$_POST['com']."'
		// 	 and cc.TC IN ('BA','CJ')
		// 	 and SUBSTRING(t.Cta,1,1)='1'
		// 	 and t.Haber>0
		// 	 and t.Item=cc.Item
		// 	 and t.Periodo=cc.Periodo
		// 	 and t.Cta=cc.Codigo
		// 	 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
		// 	 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep";
		// $stmt8 = sqlsrv_query( $cid, $sql);
		// if( $stmt8 === false)  
		// {  
		// 	 echo "Error en consulta Listar.\n";  
		// 	 die( print_r( sqlsrv_errors(), true));  
		// }
		// $stmt8_count = contar_reg($stmt8);
			//vovlemos a generar consulta
		// $stmt8 = sqlsrv_query( $cid, $sql);
		
		// $sql=" select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Debe) as monto
		// 	 from Transacciones as t, Catalogo_Cuentas as cc
		// 	 where t.Item='".$_SESSION['INGRESO']['item']."' and t.Periodo='".$_SESSION['INGRESO']['periodo']."' 
		// 	 and t.TP='CI' and t.Numero='".$_POST['com']."'
		// 	 and cc.TC IN ('BA','CJ')
		// 	 and SUBSTRING(t.Cta,1,1)='1'
		// 	 and t.Debe>0
		// 	 and t.Item=cc.Item
		// 	 and t.Periodo=cc.Periodo
		// 	 and t.Cta=cc.Codigo
		// 	 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
		// 	 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep";
		// $stmt9 = sqlsrv_query( $cid, $sql);
		// if( $stmt9 === false)  
		// {  
		// 	 echo "Error en consulta Listar.\n";  
		// 	 die( print_r( sqlsrv_errors(), true));  
		// }
		// $stmt9_count = contar_reg($stmt9);
		// 	//vovlemos a generar consulta
		// $stmt9 = sqlsrv_query( $cid, $sql);
		//manda a realizar el comprobante
		//cabecera
		// $TipoComp = $row[2];
		// $Numero =$row[3];
		// Periodo, Item, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total, CodigoU, Autorizado, Si_Existe, Hora, CEj, X, ID
		/*$sql="SELECT C.Periodo, C.Item, C.T, C.TP, A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,Cl.Cliente,Cl.Ciudad ";
        $sql=$sql."FROM Comprobantes C, Accesos A, Clientes Cl WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' ";
        $sql=$sql."AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
        $sql=$sql."AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo ";*/
		// $sql="select  a.Periodo, a.Item, a.T, a.TP, a.Numero, a.Fecha, a.Codigo_B, a.Presupuesto, a.Concepto, 
		// a.Cotizacion, a.Efectivo, a.Monto_Total, a.CodigoU, a.Autorizado, a.Si_Existe, a.Hora, a.CEj, a.X, a.ID,
		// a.Efectivo, a.Nombre_Completo ,a.CI_RUC,a.Direccion,a.Email,a.Telefono,a.Celular,
		// a.Cliente,a.Ciudad from (
		// SELECT C.Periodo, C.Item, C.T, C.TP, C.Numero, C.Fecha, C.Codigo_B, C.Presupuesto, C.Concepto, 
		// C.Cotizacion, C.Efectivo, C.Monto_Total, C.CodigoU, C.Autorizado, C.Si_Existe, C.Hora, C.CEj, C.X, C.ID,
		// A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,
		// Cl.Cliente,Cl.Ciudad FROM Comprobantes C, Accesos A, Clientes Cl 
		// WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' 
		// AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		// AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo  ) a ";
		
		/*$sql="SELECT Comprobantes.T,Comprobantes.Fecha,Comprobantes.Codigo_B,Comprobantes.Concepto,Comprobantes.Efectivo,Accesos.Nombre_Completo,Clientes.CI_RUC,Clientes.Direccion,Clientes.Email,Clientes.Telefono,
			Clientes.Celular,Clientes.Cliente,Clientes.Ciudad FROM 
			Comprobantes 
			INNER JOIN Accesos  ON (Comprobantes.CodigoU = Accesos.Codigo)
			INNER JOIN Clientes ON (Comprobantes.Codigo_B = Clientes.Codigo) WHERE Comprobantes.Numero ='".$row[3]."' AND Comprobantes.TP = '".$row[2]."' ";
        $sql=$sql."AND Comprobantes.Item = '".$_SESSION['INGRESO']['item']."' AND Comprobantes.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";*/
        // $sql=$sql." ";
		//echo $sql;
		// $stmt1 = sqlsrv_query( $cid, $sql);
		// if( $stmt1 === false)  
		// {  
		// 	 echo "Error en consulta cabecera.\n";  
		// 	 die( print_r( sqlsrv_errors(), true));  
		// }
		// $i=0;
		// $concepto = 'Ninguno';
		// foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
		// 	foreach( $fieldMetadata as $name => $value) {
		// 		// echo "$name: $value<br />";
		// 	}
		// }
		
		// while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
		// {
		// 	$i++;
		// 	$t = $row1[2];
		// 	$Fecha = $row1[5]->format('Y-m-d');
		// 	$codigoB = $row1[6];
		// 	$beneficiario = $row1[25];
		// 	$concepto = $row1[8];
		// 	$efectivo = number_format($row1[10],2, ',', '.');
		// 	//$num = NumerosEnLetras::convertir(1988208.99);
		// 	//echo $num;
		// 	//die();
		// 	$est="Normal";
		// 	if($t == 'A')
		// 	{
		// 		$est="Anulado";
		// 	}
		// 	$usuario= $row1[19];
		// }
		// if($i!=0)
		// {
		// 	$sql="select  a.Periodo, a.Item, a.T, a.TP, a.Numero, a.Fecha, a.Codigo_B, a.Presupuesto, a.Concepto, 
		// 	a.Cotizacion, a.Efectivo, a.Monto_Total, a.CodigoU, a.Autorizado, a.Si_Existe, a.Hora, a.CEj, a.X, a.ID,
		// 	a.Efectivo, a.Nombre_Completo ,a.CI_RUC,a.Direccion,a.Email,a.Telefono,a.Celular,
		// 	a.Cliente,a.Ciudad from (
		// 	SELECT C.Periodo, C.Item, C.T, C.TP, C.Numero, C.Fecha, C.Codigo_B, C.Presupuesto, C.Concepto, 
		// 	C.Cotizacion, C.Efectivo, C.Monto_Total, C.CodigoU, C.Autorizado, C.Si_Existe, C.Hora, C.CEj, C.X, C.ID,
		// 	A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,
		// 	Cl.Cliente,Cl.Ciudad FROM Comprobantes C, Accesos A, Clientes Cl 
		// 	WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' 
		// 	AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		// 	AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo  ) a ";
		// 	//echo $sql;
		// 	//die();
		// 	$stmt1 = sqlsrv_query( $cid, $sql);
		// 	if( $stmt1 === false)  
		// 	{  
		// 		 echo "Error en consulta cabecera.\n";  
		// 		 die( print_r( sqlsrv_errors(), true));  
		// 	}
		// 	//existe comprobante
		// 	//Listar las Transacciones
		// 	$sql="SELECT T.Cta,Ca.Cuenta,T.Parcial_ME,T.Debe,T.Haber,T.Detalle,T.Cheq_Dep,T.Fecha_Efec,T.Codigo_C,Ca.Item,T.TP,T.Numero,T.Fecha,T.T ";
  //           $sql=$sql."FROM Transacciones As T, Catalogo_Cuentas As Ca ";
		// 	$sql=$sql."WHERE T.TP = '".$row[2]."' ";
		// 	$sql=$sql."AND T.Numero = ".$row[3]." ";
		// 	$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
		// 	$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		// 	$sql=$sql."AND T.Item = Ca.Item ";
		// 	$sql=$sql."AND T.Periodo = Ca.Periodo ";
		// 	$sql=$sql."AND T.Cta = Ca.Codigo ";
		// 	$sql=$sql."ORDER BY T.ID,Debe DESC,T.Cta ";
		// 	// echo $sql;
		// 	$stmt2 = sqlsrv_query( $cid, $sql);
		// 	if( $stmt2 === false)  
		// 	{  
		// 		 echo "Error en consulta Transacciones.\n";  
		// 		 die( print_r( sqlsrv_errors(), true));  
		// 	}
		// 	$stmt2_count = contar_reg($stmt2,5);
		// 	//vovlemos a generar consulta
		// 	$stmt2 = sqlsrv_query( $cid, $sql);
		// 	//Llenar Bancos
		// 	$sql="SELECT T.Cta,C.TC,C.Cuenta,Co.Fecha,Cl.Cliente,T.Cheq_Dep,T.Debe,T.Haber,T.Fecha_Efec ";
		// 	$sql=$sql."FROM Transacciones As T,Comprobantes As Co,Catalogo_Cuentas As C,Clientes As Cl ";
		// 	$sql=$sql."WHERE T.TP = '".$row[2]."' ";
		// 	$sql=$sql."AND T.Numero = ".$row[3]." ";
		// 	$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
		// 	$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		// 	$sql=$sql."AND T.Numero = Co.Numero ";
		// 	$sql=$sql."AND T.TP = Co.TP ";
		// 	$sql=$sql."AND T.Cta = C.Codigo ";
		// 	$sql=$sql."AND T.Item = C.Item ";
		// 	$sql=$sql."AND T.Item = Co.Item ";
		// 	$sql=$sql."AND T.Periodo = C.Periodo ";
		// 	$sql=$sql."AND T.Periodo = Co.Periodo ";
		// 	$sql=$sql."AND C.TC = 'BA' ";
		// 	$sql=$sql."AND Co.Codigo_B = Cl.Codigo ";
		// 	//echo $sql.'<br>';
		// 	$stmt3 = sqlsrv_query( $cid, $sql);
		// 	if( $stmt3 === false)  
		// 	{  
		// 		 echo "Error en consulta Bancos.\n";  
		// 		 die( print_r( sqlsrv_errors(), true));  
		// 	}
		// 	$stmt3_count = contar_reg($stmt3);
		// 	//echo " ffff ".$stmt3_count;
		// 	//vovlemos a generar consulta
		// 	$stmt3 = sqlsrv_query( $cid, $sql);
		// 	//Listar las Retenciones del IVA
		// 	$sql="SELECT * ";
		// 	$sql=$sql."FROM Trans_Compras ";
		// 	$sql=$sql."WHERE Numero = ".$row[3]." ";
		// 	$sql=$sql."AND TP = '".$row[2]."' ";
		// 	$sql=$sql."AND Item = '".$_SESSION['INGRESO']['item']."' ";
		// 	$sql=$sql."AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		// 	$sql=$sql."ORDER BY Cta_Servicio,Cta_Bienes ";
		// 	//echo $sql.'<br>';
		// 	$stmt4 = sqlsrv_query( $cid, $sql);
		// 	if( $stmt4 === false)  
		// 	{  
		// 		 echo "Error en consulta Retenciones.\n";  
		// 		 die( print_r( sqlsrv_errors(), true));  
		// 	}
		// 	$stmt4_count = contar_reg($stmt4);
		// 	//echo " ffff ".$stmt4_count;
		// 	//vovlemos a generar consulta
		// 	$stmt4 = sqlsrv_query( $cid, $sql);
		// 	//Listar las Retenciones de la Fuente
		// 	$sql="SELECT R.*,TIV.Concepto ";
		// 	$sql=$sql."FROM Trans_Air As R,Tipo_Concepto_Retencion As TIV ";
		// 	$sql=$sql."WHERE R.Numero = ".$row[3]." ";
		// 	$sql=$sql."AND R.TP = '".$row[2]."' ";
		// 	$sql=$sql."AND R.Item = '".$_SESSION['INGRESO']['item']."' ";
		// 	$sql=$sql."AND TIV.Fecha_Inicio <= '".$Fecha."' ";
		// 	$sql=$sql."AND TIV.Fecha_Final >= '".$Fecha."' ";
		// 	$sql=$sql."AND R.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		// 	$sql=$sql."AND R.Tipo_Trans IN ('C','I') ";
		// 	$sql=$sql."AND R.CodRet = TIV.Codigo ";
		// 	$sql=$sql."ORDER BY R.Cta_Retencion ";
		// 	//echo $sql.'<br>';
		// 	$stmt5 = sqlsrv_query( $cid, $sql);
		// 	if( $stmt5 === false)  
		// 	{  
		// 		 echo "Error en consulta Retenciones 1.\n";  
		// 		 die( print_r( sqlsrv_errors(), true));  
		// 	}
		// 	$stmt5_count = contar_reg($stmt5);
		// 	//echo " ffff ".$stmt5_count;
		// 	//vovlemos a generar consulta
		// 	$stmt5 = sqlsrv_query( $cid, $sql);
		// 	//Llenar SubCtas
		// 	$sql="SELECT T.Cta,T.TC,T.Factura,C.Cliente,T.Detalle_SubCta,T.Debitos,T.Creditos,T.Fecha_V,T.Codigo,T.Prima ";
		// 	$sql=$sql."FROM Trans_SubCtas As T,Clientes As C ";
		// 	$sql=$sql."WHERE T.TP = '".$row[2]."' ";
		// 	$sql=$sql."AND T.Numero = ".$row[3]." ";
		// 	$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
		// 	$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";	
		// 	$sql=$sql."AND T.TC IN ('C','P') ";
		// 	$sql=$sql."AND T.Codigo = C.Codigo ";
		// 	$sql=$sql."UNION ";
		// 	$sql=$sql."SELECT T.Cta,T.TC,T.Factura,C.Detalle As Cliente,T.Detalle_SubCta,T.Debitos,T.Creditos,T.Fecha_V,T.Codigo,T.Prima ";
		// 	$sql=$sql."FROM Trans_SubCtas As T,Catalogo_SubCtas As C ";
		// 	$sql=$sql."WHERE T.TP = '".$row[2]."' ";
		// 	$sql=$sql."AND T.Numero = ".$row[3]." ";
		// 	$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
		// 	$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		// 	$sql=$sql."AND T.TC = C.TC ";
		// 	$sql=$sql."AND T.Item = C.Item ";
		// 	$sql=$sql."AND T.Periodo = C.Periodo ";
		// 	$sql=$sql."AND T.Codigo = C.Codigo ";
		// 	$sql=$sql."ORDER BY T.Cta,T.Codigo,T.Fecha_V,T.Factura ";
		// 	//echo $sql.'<br>';
		// 	//die();
		// 	$stmt6 = sqlsrv_query( $cid, $sql);
		// 	if( $stmt6 === false)  
		// 	{  
		// 		 echo "Error en consulta SubCtas.\n";  
		// 		 die( print_r( sqlsrv_errors(), true));  
		// 	}
		// 	$stmt6_count = contar_reg($stmt6);
		// 	//echo " ffff ".$stmt6_count;
		// 	//vovlemos a generar consulta
		// 	$stmt6 = sqlsrv_query( $cid, $sql);
		// 	//llamamos a los pdf
		// 	if($TipoComp=='CD')
		// 	{
		// 		imprimirCD($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
		// 	}
		// 	if($TipoComp=='CI')
		// 	{
		// 		imprimirCI($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt9, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,
		// 		$stmt5_count,$stmt6_count,$stmt9_count);	
		// 	}
		// 	if($TipoComp=='CE')
		// 	{
		// 		imprimirCE($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt8, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,
		// 		$stmt5_count,$stmt6_count,$stmt8_count);
		// 	}
		// 	if($TipoComp=='ND')
		// 	{
		// 		imprimirND($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
		// 	}
		// 	if($TipoComp=='NC')
		// 	{
		// 		imprimirNC($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
		// 	}
			
		// }
		// else
		// {
		// 	echo "El Comprobante no exite.";
		// }
	
	// }
	
}

}
?>