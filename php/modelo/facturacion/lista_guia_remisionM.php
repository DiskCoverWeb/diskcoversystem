<?php
include_once(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start();





class lista_guia_remisionM
{
	private $conn;	
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function guia_remision_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$serie=false,$factura=false)
	{
		$sql ="SELECT * FROM Facturas_Auxiliares
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Item = '".$_SESSION['INGRESO']['item']."'";    
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND CodigoC ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND Serie_GR ='".$serie."'";
		} 
        if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND FechaGRE BETWEEN   '".$desde."' AND '".$hasta."' ";
	    }
	    if($factura)
	    {
	    	$sql.=" AND Factura = '".$factura."'";
	    }
	   $sql.=" ORDER BY Remision DESC"; 
		$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
	    // // print_r($_SESSION['INGRESO']);
		// print_r($sql);die();    
		return $this->db->datos($sql);

	       // return $datos;
	}


	function factura($factura=false,$serie=false,$Autorizacion=false)
	{
		$sql="SELECT * 
		    FROM Facturas 
		    WHERE Item = '".$_SESSION['INGRESO']['item']."'
		    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		    if($serie)
		    { 
		    	$sql.=" AND Serie='".$serie."'";
		    } 
		    if($factura)
		    {
		    	$sql.=" AND Factura='".$factura."'";
		    } 
		    if($Autorizacion)
		    {
		    	$sql.=" AND Autorizacion='".$Autorizacion."' ";
			}

		return $this->db->datos($sql);
		   
	}

	function lineas_nota_credito($serie,$numero)
	{
        $sql = "SELECT * FROM Detalle_Factura WHERE 
        Periodo = '".$_SESSION['INGRESO']['item']."'
		AND Item = '".$_SESSION['INGRESO']['periodo']."'
        AND Factura = '".$numero."' AND Serie = '".$serie."' ";
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