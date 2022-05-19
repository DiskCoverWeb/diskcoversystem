<?php 
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class comproM
{
	private $db;
	
	function __construct()
	{
		// se incluye la conexion en el documeno de funciones php
		$this->db = new db();
		
	}

	function Listar_el_Comprobante($comprobante){
	$sql="SELECT  Periodo, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total,CodigoU, Autorizado, Item, Si_Existe, Hora, CEj, X, ID 
       FROM Comprobantes
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND Numero = '".$comprobante."'
       ORDER BY Numero ";
       return $this->db->datos($sql);
	}

	function cheques_debe($comprobante)
	{
		$sql=" select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Haber) as monto
			 from Transacciones as t, Catalogo_Cuentas as cc
			 where t.Item='".$_SESSION['INGRESO']['item']."' and t.Periodo='".$_SESSION['INGRESO']['periodo']."' 
			 and t.TP='CE' and t.Numero='".$comprobante."'
			 and cc.TC IN ('BA','CJ')
			 and SUBSTRING(t.Cta,1,1)='1'
			 and t.Haber>0
			 and t.Item=cc.Item
			 and t.Periodo=cc.Periodo
			 and t.Cta=cc.Codigo
			 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep";
       return $this->db->datos($sql);
	}

	function cheques_haber($comprobante)
	{
		$sql=" select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Debe) as monto
			 from Transacciones as t, Catalogo_Cuentas as cc
			 where t.Item='".$_SESSION['INGRESO']['item']."' and t.Periodo='".$_SESSION['INGRESO']['periodo']."' 
			 and t.TP='CI' and t.Numero='".$comprobante."'
			 and cc.TC IN ('BA','CJ')
			 and SUBSTRING(t.Cta,1,1)='1'
			 and t.Debe>0
			 and t.Item=cc.Item
			 and t.Periodo=cc.Periodo
			 and t.Cta=cc.Codigo
			 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep";
		return $this->db->datos($sql);		
	}

	function comprobante_agrupado($numero,$tp)
	{
		$sql="select  a.Periodo, a.Item, a.T, a.TP, a.Numero, a.Fecha, a.Codigo_B, a.Presupuesto, a.Concepto, 
		a.Cotizacion, a.Efectivo, a.Monto_Total, a.CodigoU, a.Autorizado, a.Si_Existe, a.Hora, a.CEj, a.X, a.ID,
		a.Efectivo, a.Nombre_Completo ,a.CI_RUC,a.Direccion,a.Email,a.Telefono,a.Celular,
		a.Cliente,a.Ciudad from (
		SELECT C.Periodo, C.Item, C.T, C.TP, C.Numero, C.Fecha, C.Codigo_B, C.Presupuesto, C.Concepto, 
		C.Cotizacion, C.Efectivo, C.Monto_Total, C.CodigoU, C.Autorizado, C.Si_Existe, C.Hora, C.CEj, C.X, C.ID,
		A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,
		Cl.Cliente,Cl.Ciudad FROM Comprobantes C, Accesos A, Clientes Cl 
		WHERE C.Numero ='".$numero."' AND C.TP = '".$tp."' 
		AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo  ) a ";
		return $this->db->datos($sql);		
	}

	function Listar_las_Transacciones($numero,$tp)
	{
		$sql="SELECT T.Cta,Ca.Cuenta,T.Parcial_ME,T.Debe,T.Haber,T.Detalle,T.Cheq_Dep,T.Fecha_Efec,T.Codigo_C,Ca.Item,T.TP,T.Numero,T.Fecha,T.T ";
            $sql=$sql."FROM Transacciones As T, Catalogo_Cuentas As Ca ";
			$sql=$sql."WHERE T.TP = '".$tp."' ";
			$sql=$sql."AND T.Numero = ".$numero." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND T.Item = Ca.Item ";
			$sql=$sql."AND T.Periodo = Ca.Periodo ";
			$sql=$sql."AND T.Cta = Ca.Codigo ";
			$sql=$sql."ORDER BY T.ID,Debe DESC,T.Cta ";
		return $this->db->datos($sql);		
	}

	function llenar_banco($numero,$tp)
	{
		$sql="SELECT T.Cta,C.TC,C.Cuenta,Co.Fecha,Cl.Cliente,T.Cheq_Dep,T.Debe,T.Haber,T.Fecha_Efec ";
			$sql=$sql."FROM Transacciones As T,Comprobantes As Co,Catalogo_Cuentas As C,Clientes As Cl ";
			$sql=$sql."WHERE T.TP = '".$tp."' ";
			$sql=$sql."AND T.Numero = ".$numero." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND T.Numero = Co.Numero ";
			$sql=$sql."AND T.TP = Co.TP ";
			$sql=$sql."AND T.Cta = C.Codigo ";
			$sql=$sql."AND T.Item = C.Item ";
			$sql=$sql."AND T.Item = Co.Item ";
			$sql=$sql."AND T.Periodo = C.Periodo ";
			$sql=$sql."AND T.Periodo = Co.Periodo ";
			$sql=$sql."AND C.TC = 'BA' ";
			$sql=$sql."AND Co.Codigo_B = Cl.Codigo ";
			// print_r($sql);die();
		return $this->db->datos($sql);		
	}

	function  Retenciones_IVA($numero,$tp)
	{
		$sql="SELECT * ";
		$sql=$sql."FROM Trans_Compras ";
		$sql=$sql."WHERE Numero = ".$numero." ";
		$sql=$sql."AND TP = '".$tp."' ";
		$sql=$sql."AND Item = '".$_SESSION['INGRESO']['item']."' ";
		$sql=$sql."AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		$sql=$sql."ORDER BY Cta_Servicio,Cta_Bienes ";
		return $this->db->datos($sql);		
	}
	function Retenciones_Fuente($numero,$tp,$Fecha)
	{
		$sql="SELECT R.*,TIV.Concepto ";
			$sql=$sql."FROM Trans_Air As R,Tipo_Concepto_Retencion As TIV ";
			$sql=$sql."WHERE R.Numero = ".$numero." ";
			$sql=$sql."AND R.TP = '".$tp."' ";
			$sql=$sql."AND R.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND TIV.Fecha_Inicio <= '".$Fecha."' ";
			$sql=$sql."AND TIV.Fecha_Final >= '".$Fecha."' ";
			$sql=$sql."AND R.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND R.Tipo_Trans IN ('C','I') ";
			$sql=$sql."AND R.CodRet = TIV.Codigo ";
			$sql=$sql."ORDER BY R.Cta_Retencion ";
		return $this->db->datos($sql);		
	}
	function llenar_SubCta($numero,$tp)
	{
		$sql="SELECT T.Cta,T.TC,T.Factura,C.Cliente,T.Detalle_SubCta,T.Debitos,T.Creditos,T.Fecha_V,T.Codigo,T.Prima ";
			$sql=$sql."FROM Trans_SubCtas As T,Clientes As C ";
			$sql=$sql."WHERE T.TP = '".$tp."' ";
			$sql=$sql."AND T.Numero = ".$numero." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";	
			$sql=$sql."AND T.TC IN ('C','P') ";
			$sql=$sql."AND T.Codigo = C.Codigo ";
			$sql=$sql."UNION ";
			$sql=$sql."SELECT T.Cta,T.TC,T.Factura,C.Detalle As Cliente,T.Detalle_SubCta,T.Debitos,T.Creditos,T.Fecha_V,T.Codigo,T.Prima ";
			$sql=$sql."FROM Trans_SubCtas As T,Catalogo_SubCtas As C ";
			$sql=$sql."WHERE T.TP = '".$tp."' ";
			$sql=$sql."AND T.Numero = ".$numero." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND T.TC = C.TC ";
			$sql=$sql."AND T.Item = C.Item ";
			$sql=$sql."AND T.Periodo = C.Periodo ";
			$sql=$sql."AND T.Codigo = C.Codigo ";
			$sql=$sql."ORDER BY T.Cta,T.Codigo,T.Fecha_V,T.Factura ";
		return $this->db->datos($sql);		
	}
}
?>