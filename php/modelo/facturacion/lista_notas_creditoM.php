<?php
include_once(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start();





class lista_notas_creditoM
{
	private $conn;	
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function retenciones_emitidas_tabla($codigo,$desde=false,$hasta=false,$serie=false)
	{
		$sql ="SELECT TA.T,TC,Cliente,C.Codigo,C.CI_RUC,TA.Fecha,Serie_NC,TA.Clave_Acceso_NC,TA.Autorizacion_NC,Secuencial_NC,F.Factura,F.Serie,F.Autorizacion,F.Total_MN,F.Descuento,F.Descuento2 
			FROM Trans_Abonos TA 
			INNER JOIN Facturas F ON TA.Factura = F.Factura
			INNER JOIN Clientes C ON F.CodigoC = C.Codigo
			WHERE TA.Item = '".$_SESSION['INGRESO']['item']."' 
			AND TA.Periodo ='".$_SESSION['INGRESO']['periodo']."'
			AND TA.Item = F.Item
			AND TA.Periodo = F.Periodo 
			AND Secuencial_NC<>0";    
		if($codigo!='T')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND F.Codigo ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND Serie_NC ='".$serie."'";
		} 
        if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND TA.Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
	    }

       $sql.="ORDER BY Serie_NC,Secuencial_NC DESC "; 
		$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
	    // // print_r($_SESSION['INGRESO']);
		// print_r($sql);die();    
		return $this->db->datos($sql);

	       // return $datos;
	}

	function retenciones_buscar($serie,$numero,$fecha)
	{
        $sql = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Telefono,C.Email,TC.* 
        FROM Trans_Compras As TC, Clientes As C 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."' 
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.Serie_Retencion = ".$serie." 
        AND TC.SecRetencion = '".$numero."'         
        AND TC.Fecha = '".$fecha."' 
        AND LEN(TC.AutRetencion) = 13 
        AND TC.IdProv = C.Codigo 
        ORDER BY Serie_Retencion,SecRetencion ";
        // print_r($sql);die();
         $result = $this->db->datos($sql);
	     return $result;
	}

	function trans_documentos($clave)
	  {
	  	$sql = "SELECT * 
	  	FROM Trans_Documentos 
	  	WHERE Clave_Acceso = '".$clave."'";
		return $this->db->datos($sql);
	  }

}




?>