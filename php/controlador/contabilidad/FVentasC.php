<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/FVentasM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */


$controlador = new FVentasC();
if(isset($_GET['DCRetIBienes']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetIBienes());
}
if(isset($_GET['DCRetISer']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetISer());
}
if(isset($_GET['DCPorcenIvaV']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIva($parametros));
}
if(isset($_GET['DCPorcenIceV']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIce($parametros));
}
if(isset($_GET['DCRetFuente']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetFuente());
}
if(isset($_GET['DCConceptoRet']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCConceptoRet($parametros));
}
if(isset($_GET['Cargar_DataGrid']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Cargar_DataGrid($parametros['Trans_No']));
}

if(isset($_GET['DCTipoPago']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCTipoPago());
}
if(isset($_GET['grabacion']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->grabacion($parametros));
}
if(isset($_GET['eliminar_air']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->delete_asiento_air($parametros['Trans_No']));
}

class FVentasC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  FVentasM();	   
	   $this->pdf = new cabecera_pdf();
	}
	 function DCRetIBienes()
  {
    $datos = $this->modelo->DCRetIBienes();
    // print_r($datos);die();
    return $datos;
  }
  function DCRetISer()
  {
    $datos = $this->modelo->DCRetISer();
     // print_r($datos);die();
    return $datos;
  }
     function DCPorcenIva($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCPorcenIva($fecha);
     // print_r($datos);die();
    return $datos;
  }
  function DCPorcenIce($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCPorcenIce($fecha);
     // print_r($datos);die();
    return $datos;
  }
  function DCRetFuente()
  {
    $datos = $this->modelo->DCRetFuente();
     // print_r($datos);die();
    return $datos;
  }
  function DCConceptoRet($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCConceptoRet($fecha);
     // print_r($datos);die();
    return $datos;
  }
     function DCTipoPago()
  {
    $datos = $this->modelo->DCTipoPago();
     // print_r($datos);die();
    return $datos;
  }
   function Cargar_DataGrid($Trans_No)
  {
    return $datos = $this->modelo->Cargar_DataGrid($Trans_No);
    // $html = '';
    // foreach ($datos as $key => $value) {
    //   $html.='<tr>
    //             <td>'.$value["CodRet"].'</td>
    //             <td>'.$value["Detalle"].'</td>
    //             <td>'.round($value["BaseImp"],2, PHP_ROUND_HALF_ODD).'</td>
    //             <td>'.round($value["Porcentaje"],2, PHP_ROUND_HALF_ODD).'</td>
    //             <td>'.round($value["ValRet"],2, PHP_ROUND_HALF_ODD).'</td>
    //             <td>'.$value["EstabRetencion"].'</td>
    //             <td>'.$value["PtoEmiRetencion"].'</td>
    //             <td>'.$value["SecRetencion"].'</td>
    //             <td>'.$value["AutRetencion"].'</td>
    //             <td>'.$value["FechaEmiRet"]->format('Y-m-d').'</td>
    //             <td>'.$value["Cta_Retencion"].'</td>
    //             <td>'.$value["EstabFactura"].'</td>
    //             <td>'.$value["PuntoEmiFactura"].'</td>
    //             <td>'.$value["Factura_No"].'</td>
    //             <td>'.$value["IdProv"].'</td>
    //             <td>'.$value["Item"].'</td>
    //             <td>'.$value["CodigoU"].'</td>
    //             <td>'.$value["A_No"].'</td>
    //             <td>'.$value["T_No"].'</td>
    //             <td>'.$value["Tipo_Trans"].'</td>              
    //           </tr>';
    // }
    // return $html;
  }

   function grabacion($parametros)
  {
  	$T_No = 1;
  	$this->delete_asiento($T_No);
  	// print_r($_SESSION['INGRESO']);die();
  	// print_r($parametros);die();
    $datos[0]['campo']="IdProv";
    $datos[1]['campo']="TipoComprobante"; 
    $datos[2]['campo']="FechaRegistro"; ;
    $datos[3]['campo']="Establecimiento"; 
    $datos[4]['campo']="PuntoEmision"; 
    $datos[5]['campo']="Secuencial"; //CTNumero
    $datos[6]['campo']="NumeroComprobante"; 
    $datos[7]['campo']="FechaEmision"; 
    $datos[8]['campo']="BaseImponible"; //CTNumero 2 decimales
    $datos[9]['campo']="IvaPresuntivo"; //CTNumero 2 decimales
    $datos[10]['campo']="BaseImpGrav"; //CTNumero 2 decimales
    $datos[11]['campo']="PorcentajeIva"; 
    $datos[12]['campo']="MontoIva"; //CTNumero 2 decimales
    $datos[13]['campo']="BaseImpIce"; //CTNumero 2 decimales
    $datos[14]['campo']="PorcentajeIce";
    $datos[15]['campo']="MontoIce"; //CTNumero 2 decimales
    $datos[16]['campo']="Porc_Bienes";
    $datos[17]['campo']="MontoIvaBienes"; //CTNumero 2 decimales
    $datos[18]['campo']="PorRetBienes";                  //ojo la varable puedee cambiar
    $datos[19]['campo']="ValorRetBienes"; //CTNumero 2 decimales
    $datos[20]['campo']="Porc_Servicios";
    $datos[21]['campo']="MontoIvaServicios"; //CTNumero 2 decimales
    $datos[22]['campo']="PorRetServicios";                //ojo la varable puedee cambiar
    $datos[23]['campo']="ValorRetServicios";    
    $datos[24]['campo']="RetPresuntiva"; //CTNumero 2 decimales
    $datos[25]['campo']= "Cta_Bienes";
    $datos[26]['campo']= "Cta_Servicios";

    $datos[27]['campo']= "Tipo_Pago";
    $datos[28]['campo']= "A_No";
    $datos[29]['campo']= "T_No";
    $datos[30]['campo']= "CodigoU";
    $datos[31]['campo']= "Item";



    if($parametros["IdProv"]=='')
    {
    	$parametros["IdProv"] = '.';
    }
    $datos[0]['dato']=$parametros["IdProv"];
    $datos[1]['dato']=$parametros["TipoComprobante"]; 
    $datos[2]['dato']=$parametros["FechaRegistro" ];
    $datos[3]['dato']=$parametros["Establecimiento"]; 
    $datos[4]['dato']=$parametros["PuntoEmision"]; 
    $datos[5]['campo']=$parametros["Secuencial"]; //CTNumero
    $datos[6]['dato']=$parametros["NumeroComprobantes"]; //CTNumero
    $datos[7]['dato']=$parametros["FechaEmision"];  
    $datos[8]['dato']=  round($parametros["BaseImponible"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[9]['dato']=$parametros["IvaPresuntivo"];     
    $datos[10]['dato']=  round($parametros["BaseImpGrav"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[11]['dato']=$parametros["PorcentajeIva"]; 
    $datos[12]['dato']=  round($parametros["MontoIva"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[13]['dato']=  round($parametros["BaseImpIce"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[14]['dato']=$parametros["PorcentajeIce"];
    $datos[15]['dato']=  round($parametros["MontoIce"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales    
    $datos[16]['dato']=$parametros["Porc_Bienes"];
    $datos[17]['dato']=  round($parametros["MontoIvaBienes"] ,2, PHP_ROUND_HALF_ODD);//CTNumero 2 decimales
    $datos[18]['dato']=$parametros["PorRetBienes"];                  //ojo la varable puedee cambiar 
    $datos[19]['dato']=  round($parametros["ValorRetBienes"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[20]['dato']=$parametros["Porc_Servicios"];
    $datos[21]['dato']=  round($parametros["MontoIvaServicios"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[22]['dato']=$parametros["PorRetServicios"]; 
    $datos[23]['dato']=$parametros["ValorRetServicios"];
    $datos[24]['dato']=$parametros["RetPresuntivo"]; 
    if($parametros['ChRetB']==true)
    {
    	$datos[25]['dato']=$parametros["Bienes"]; 
    }else
    {
    	$datos[25]['dato']='.'; 

    }
    if($parametros['ChRetS']==true)
    {
    	$datos[26]['dato']=$parametros["Servicio"]; 
    }else
    {
    	$datos[26]['dato']='.';

    }   

    $datos[27]['dato']= $parametros['Tipo_pago'];
    $datos[28]['dato']= "1";
    $datos[29]['dato']= $T_No;
    $datos[30]['dato']= $_SESSION['INGRESO']['CodigoU'];
    $datos[31]['dato']= $_SESSION['INGRESO']['item'];


  	// print_r($datos);die();

    if(insert_generico("Asiento_Ventas",$datos)==null)
    {
     // if($this->grabar_asiento_compras($parametros)==1)
            // {
             return 1;
            // }

    }
  }
  function delete_asiento($T_No)
  {
  	 return $this->modelo->delete_asiento_venta($T_No);
  }
  function delete_asiento_air($T_No)
  {
  	// print_r($T_No);die();
  	return $this->modelo->delete_asiento_air($T_No);
  }
}
?>