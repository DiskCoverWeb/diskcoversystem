<?php 
//include(dirname(__DIR__).'/funciones/funciones.php');
include(dirname(__DIR__).'/modelo/saldo_fac_submoduloM.php');
/**
 * 
 */

if(isset($_GET['cargar']))
{
	$ctc = $_POST['select'];
	$controlador = new Saldo_fac_sub_M();
	echo json_encode($controlador->cargar_datos($ctc), JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['consultar']))
{
	$parametros = $_POST['parametros'];
	$controlador = new Saldo_fac_sub_M();
	$tabla = $controlador->cargar_consulta($parametros);
	$controlador->tabla_temporizado($tabla['datos'],$parametros);
	//print_r($tabla['datos']);
	echo json_decode($tabla['tabla']);
}
if(isset($_GET['consultar_totales']))
{
	$parametros = $_POST['parametros'];
	$controlador = new Saldo_fac_sub_M();
	$tabla = $controlador->cargar_consulta_totales($parametros);
	// print_r($tabla);die();
	echo json_encode($tabla);
}
if(isset($_GET['consultar_tempo']))
{
	$parametros = $_POST['parametros'];
	$controlador = new Saldo_fac_sub_M();	
    echo json_decode($controlador->consultar_tabla_temp($parametros['fechafin']));
}

class Saldo_fac_sub_M 
{
	private $modelo;
	
	function __construct()
	{
	   $this->modelo = new  Saldo_fac_sub_C();
	}

	function cargar_datos($datos)
	{
	    //$datos = $this->modelo->mensaje();
		$titulo = '';
		$cxc = array();
		$det = array();
		$beneficiario = array();
		
		if($datos == 'C')
		{
			$titulo = 'SALDO DE CUENTAS POR COBRAR' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}else if($datos == 'P')
		{
			$titulo = 'SALDO DE CUENTAS POR PAGAR' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}else if($datos == 'I')
		{
			$titulo = 'SALDO DE INGRESOS' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}else if($datos == 'G')
		{
			$titulo = 'SALDO DE EGRESOS' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);
			//echo $beneficiario;

		}else if($datos== 'CC')
		{
			$titulo = 'SALDO DE COSTOS' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}

		$lista = array('titulo'=>$titulo,'cta'=>$cta,'det'=>$det,'beneficiario'=>$beneficiario);
		return $lista;
	}

	function cargar_consulta($parametros)
	{

		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$fechafin = str_replace("-","",$parametros['fechafin']);
			$Total = 0;$Saldo = 0;
		if($parametros['tipocuenta']=='C' || $parametros['tipocuenta']=='P')
		{
			// print_r($parametros);die();
			$datos_ = $this->modelo->consulta_c_p_datos(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']);
			foreach ($datos_ as $key => $value) {
				 $Total = $Total + $value["Total"];
                 $Saldo = $Saldo + $value["Saldo"];
			}
			$totales_  = array('Total'=>$Total,'Saldo'=>$Saldo);
		  return $valores = array('tabla'=>$this->modelo->consulta_c_p_tabla(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']),
		   'datos'=> $datos_,
		   'totales'=>$totales_);
		}else if($parametros['tipocuenta']=='I' || $parametros['tipocuenta']=='G')
		{
			$datos_=$this->modelo->consulta_ing_egre_datos(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']);
			foreach ($datos_ as $key => $value) {
				$Total = $Total + $value["Total"];
				$saldo = 0;
			}
			$totales_  = array('Total'=>$Total,'Saldo'=>$Saldo);
		   return $valores = array('tabla'=>$this->modelo->consulta_ing_egre_tabla(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']),
		   'datos'=> $datos_,
		   'totales'=>$totales_);

		}

	}

	function cargar_consulta_totales($parametros)
	{

		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$fechafin = str_replace("-","",$parametros['fechafin']);
			$Total = 0;$Saldo = 0;
		if($parametros['tipocuenta']=='C' || $parametros['tipocuenta']=='P')
		{
			// print_r($parametros);die();
			$datos_ = $this->modelo->consulta_c_p_datos(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']);
			foreach ($datos_ as $key => $value) {
				 $Total = $Total + $value["Total"];
                 $Saldo = $Saldo + $value["Saldo"];
			}
			$totales_  = array('Total'=>$Total,'Saldo'=>$Saldo);
		  return $totales_ ;
		}else if($parametros['tipocuenta']=='I' || $parametros['tipocuenta']=='G')
		{
			$datos_=$this->modelo->consulta_ing_egre_datos(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']);
			foreach ($datos_ as $key => $value) {
				$Total = $Total + $value["Total"];
				$saldo = 0;
			}
			$totales_  = array('Total'=>$Total,'Saldo'=>$Saldo);
		   return $totales_;

		}

	}


  function tabla_temporizado($datos,$parametros)
  {
  	    if($this->modelo->eliminar_saldo_diario()==1)
  	    {
  	    	if($parametros['tipocuenta']== 'C' || $parametros['tipocuenta']== 'P')
  	    	{
  	    		foreach ($datos as $key => $value) 
  	    		{  	    			

                    $date1 = new DateTime($parametros['fechaini']);
                    $date2 = new DateTime($parametros['fechafin']);
                    $fechaini = str_replace("-","",$parametros['fechaini']);
		            $fechafin = str_replace("-","",$parametros['fechafin']);		
		
  	    	        //$dias = $parametros['fechafin']-$parametros['fechaini'];
  	    	        $dias = $date1->diff($date2)->days;
  	    	        $dato[0]['campo']='Fecha_Venc';
			        $dato[0]['dato']=$fechafin;
			        $dato[1]['campo']='Numero';
			        $dato[1]['dato']=$value['Factura'];
			        $dato[2]['campo']='Comprobante';
			        $dato[2]['dato']=$value['Cliente'];
			        $dato[3]['campo']='T';
			        $dato[3]['dato']='N';
			        $dato[4]['campo']='Fecha';
			        $dato[4]['dato']=$fechaini;
			        $dato[5]['campo']='Dato_Aux1';
			        $dato[5]['dato']=$value['Cuenta'];
			        $dato[6]['campo']='Total';
			        $dato[6]['dato']=$value['Saldo'];
			        $dato[7]['campo']='Saldo_Actual';
			        $dato[7]['dato']=$value['Saldo'];
			        $dato[8]['campo']="Item";
			        $dato[8]['dato']=$_SESSION['INGRESO']['item'];
			        $dato[9]['campo']="CodigoU";
			        $dato[9]['dato']=$_SESSION['INGRESO']['CodigoU'];
			        $dato[10]['campo']="TP";
			        $dato[10]['dato']="CCXP";
			       // print_r($dias);
			        if($dias > 0 && $dias < 8)
			        {
				        $dato[11]['campo']='Ven_1_a_7';
			            $dato[11]['dato']=$value['Saldo'];

			        }else if($dias > 8 && $dias < 31)
			        {
				        $dato[11]['campo']='Ven_8_a_30';
			            $dato[11]['dato']=$value['Saldo'];

			        }else if($dias >30 && $dias < 61)
			        {
				        $dato[11]['campo']='Ven_31_a_60';
			            $dato[11]['dato']=$value['Saldo'];

			        }else if($dias >60 && $dias < 91)
			        {
				        $dato[11]['campo']='Ven_61_a_90';
			            $dato[11]['dato']=$value['Saldo'];

			        }else if($dias > 90 && $dias < 181)
			        {
				        $dato[11]['campo']='Ven_91_a_180';
			            $dato[11]['dato']=$value['Saldo'];

			        }else if($dias >180 && $dias < 361)
			        {
				        $dato[11]['campo']='Ven_181_a_360';
			            $dato[11]['dato']=$value['Saldo'];

			        }else if($dias > 360)
			        {
				        $dato[11]['campo']='Ven_mas_de_360';
			            $dato[11]['dato']=$value['Saldo'];
			        }

			        insert_generico("Saldo_Diarios",$dato);

  	    	    }
  	        }else
  	        {
  	        	$saldo=0;
  	        	if($datos=='')
  	        	{
  	        		$datos=array();
  	        	}  	        	
  	        	foreach ($datos as $key => $value) {
  	        		
  	        	

                    $anio = date("Y");
  	        	    $date1 = new DateTime($anio.'01-01');
                    $date2 = new DateTime($parametros['fechaini']);
                    $fechaini = str_replace("-","",$parametros['fechaini']);
		            $fechafin = str_replace("-","",$parametros['fechafin']);		
		
  	    	        //$dias = $parametros['fechafin']-$parametros['fechaini'];
  	    	        $dias = $date1->diff($date2)->days;
  	    	        $dato[0]['campo']='Fecha_Venc';
			        $dato[0]['dato']=$fechaini;			        
			        $dato[1]['campo']='Comprobante';
			        $dato[1]['dato']=$value['Sub_Modulos'];
			        $dato[2]['campo']='T';
			        $dato[2]['dato']='N';
			        $dato[3]['campo']='Fecha';
			        $dato[3]['dato']=$fechaini;
			        $dato[4]['campo']='Dato_Aux1';
			        $dato[4]['dato']=$value['Cuenta'];
			        $dato[5]['campo']='Total';
			        $dato[5]['dato']=$value['Total'];
			        $dato[6]['campo']='Saldo_Actual';
			        $dato[6]['dato']= $saldo+$value['Total'];
			        $dato[7]['campo']="Item";
			        $dato[7]['dato']=$_SESSION['INGRESO']['item'];
			        $dato[8]['campo']="CodigoU";
			        $dato[8]['dato']=$_SESSION['INGRESO']['CodigoU'];
			        $dato[9]['campo']="TP";
			        $dato[9]['dato']="CCXP";
			       // print_r($dias);
			        if($dias > 0 && $dias < 8)
			        {
				        $dato[10]['campo']='Ven_1_a_7';
			            $dato[10]['dato']=$value['Total']+$saldo;

			        }else if($dias > 8 && $dias < 31)
			        {
				        $dato[10]['campo']='Ven_8_a_30';
			            $dato[10]['dato']=$value['Total']+$saldo;

			        }else if($dias >30 && $dias < 61)
			        {
				        $dato[10]['campo']='Ven_31_a_60';
			            $dato[10]['dato']=$value['Total']+$saldo;

			        }else if($dias >60 && $dias < 91)
			        {
				        $dato[10]['campo']='Ven_61_a_90';
			            $dato[10]['dato']=$value['Total']+$saldo;

			        }else if($dias > 90 && $dias < 181)
			        {
				        $dato[10]['campo']='Ven_91_a_180';
			            $dato[10]['dato']=$value['Total']+$saldo;

			        }else if($dias >180 && $dias < 361)
			        {
				        $dato[10]['campo']='Ven_181_a_360';
			            $dato[10]['dato']=$value['Total']+$saldo;

			        }else if($dias > 360)
			        {
				        $dato[10]['campo']='Ven_mas_de_360';
			            $dato[10]['dato']=$value['Total']+$saldo;
			        }

			       insert_generico("Saldo_Diarios",$dato);

  	        }
  	      }
  	    						
  	    }
  }

  function consultar_tabla_temp($fechafin)
  {
  	return $this->modelo->tabla_temporizada($fechafin);
  }		
}

?>
