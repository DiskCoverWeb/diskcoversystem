<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class punto_ventaM
{
	private $db;

	public function __construct(){

      $this->db = new db();
  }

  function Listar_Clientes_PV($query)
  {
  	  $sql= "SELECT TOP 100 Cliente,Codigo,CI_RUC,TD,Grupo,Email,T 
        FROM Clientes 
        WHERE Cliente <> '.' 
        AND FA <> 0 
        AND Cliente LIKE '%".$query."%' 
        UNION 
        SELECT Cliente,Codigo,CI_RUC,TD,Grupo,Email,T 
        FROM Clientes 
        WHERE Codigo = '9999999999' 
        ORDER BY Cliente ";

     return $this->db->datos($sql);
  }

  function DCBodega()
  {
  	$sql = "SELECT *
        FROM Catalogo_Bodegas
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        ORDER BY CodBod ";
    return $this->db->datos($sql);
  }
  function DCBanco($query)
  {
  	 $sql= "SELECT Codigo +Space(2)+Cuenta As NomCuenta,Codigo 
       FROM Catalogo_Cuentas 
       WHERE TC IN ('BA','CJ','CP','C','P') 
       AND DG = 'D' 
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
       if($query)
       	{
       		$sql.=" AND Cuenta LIKE '%".$query."%'";
       	}
       	$sql.=" ORDER BY Codigo ";
    return $this->db->datos($sql);

  }

  function DCArticulos($Grupo_Inv,$TipoFactura,$query)
  {
  	 $sql = "SELECT Producto,Codigo_Inv,Codigo_Barra 
        FROM Catalogo_Productos 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TC = 'P' ";
	  if(strlen($Grupo_Inv) > 1){ $sql.="AND MidStrg(Codigo_Inv,1,2) = '".$Grupo_Inv."' ";}
	  // if($TipoFactura == "CP"){
	  //    $sql.=" AND Cta_Inventario = '0' ";
	  // }else{
	  //    $sql.=" AND LEN(Cta_Inventario) > 1 ";
	  // }
	  if($query)
	  {
	  	$sql.=" AND Producto like '%".$query."%'";
	  }
	  $sql.=" ORDER BY Producto,Codigo_Inv "; 

    // print_r($sql);die();
	  return $this->db->datos($sql);
  }

  function DGAsientoF($grilla=false)
  {
  	 $sql= "SELECT * 
       FROM Asiento_F 
       WHERE Item = '".$_SESSION['INGRESO']['item']."'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       ORDER BY A_No desc";
       $datos =  $this->db->datos($sql);
       $ln = count($datos);
       $tbl='';
       if($grilla)
       {
       	$botones[0] = array('boton'=>'Eliminar','icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'A_No,CODIGO');
       	$tbl = grilla_generica_new($sql,'Asiento_F',false,$titulo=false,$botones,$check=false,$imagen=false,1,1,1,270);
       }
       return array('datos'=>$datos,'tbl'=>$tbl,'ln'=>$ln);
  }

  function catalogo_lineas($TC,$SerieFactura)
  {
  	$sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND TL <> 0
         ORDER BY Codigo ";
         // print_r($sql);die();
	  return $this->db->datos($sql);

  }

  function ELIMINAR_ASIENTOF($codigo =false,$A_no=false)
  {
  	$sql= "DELETE
        FROM Asiento_F 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
        if($codigo)
        {
        	$sql.=" AND CODIGO ='".$codigo."'";
        }
        if($A_no)
        {
        	$sql.=" AND A_No ='".$A_no."'";
        }
        // print_r($sql);die();

        return $this->db->String_Sql($sql);
  }

  function delete_factura($TipoFactura,$Factura_No)
  {
  	 $sql = "DELETE
        FROM Detalle_Factura 
        WHERE Factura = ".$Factura_No." 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC = '".$TipoFactura."'; ";
    
     $sql.="DELETE
        FROM Facturas 
        WHERE Factura = ".$Factura_No." 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC = '".$TipoFactura."';";
     $this->db->String_Sql($sql);
  }

  function cargar_pedidos_factura($orden,$cliente=false)
  {
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT D.*,P.Producto 
     FROM Detalle_Factura  D ,Catalogo_Productos P
     WHERE D.Factura = '".$orden."' 
     AND D.CodigoC = '".$cliente."'
     AND D.Item = P.Item
     AND D.Periodo = P.Periodo
    AND D.Codigo = P.Codigo_Inv";
     
     $sql.=" ORDER BY D.ID DESC";
     // print_r($sql);die();

     return $this->db->datos($sql);
       
  }
  function lista_hijos_id($query)
  {
      // $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
      $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
        FROM Catalogo_Productos As CP, Trans_Kardex AS TK 
        WHERE CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND CP.Item = '".$_SESSION['INGRESO']['item']."'
        AND LEN(CP.Cta_Inventario)>3 
        AND CP.Codigo_Inv LIKE '".$query."' 
        AND TK.T<> 'A' 
        AND CP.Periodo = TK.Periodo 
        AND CP.Item = TK.Item 
        AND CP.Codigo_Inv = TK.Codigo_Inv 
        group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0
        order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";
   
     // print_r($sql);die();
     $datos1 =  $this->db->datos($sql);
     $datos = array();
     foreach ($datos1 as $key => $value) {
        $datos[]=array('id'=>$value['Codigo_Inv'].','.$value['Unidad'].','.$value['Stock_Actual'],'text'=>$value['Producto']);    
     }
       return $datos;
  }

  
}

?>