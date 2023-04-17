<?php 
require_once(dirname(__DIR__,1)."/modelo/modalesM.php");



$controlador = new modalesC();
if(isset($_GET['buscar_cliente']))
{
	// print_r($_POST);die();
	$query = $_POST['search'];
	echo json_encode($controlador->busca_cliente($query));
}
if(isset($_GET['buscar_cliente_nom']))
{
	$query = $_POST['parametros'];
	echo json_encode($controlador->busca_cliente_nom($query));
}

if(isset($_GET['codigo']))
{
	$query = $_POST['ci'];
	echo json_encode($controlador->Codigo_CI($query));
}

if(isset($_GET['validar_sri']))
{
	$query = $_POST['ci'];
	echo json_encode($controlador->validar_sri($query));
}



if(isset($_GET['guardar_cliente']))
{
	// print_r($_POST);die();
	$query = $_POST;
	echo json_encode($controlador->guardar_cliente($query));
}

if(isset($_GET['DLCxCxP']))
{
	// print_r($_POST);die();
	$query = $_GET;
	echo json_encode($controlador->DLCxCxP($query));
}
if(isset($_GET['DLGasto']))
{
	// print_r($_POST);die();
	$query = $_GET;
	echo json_encode($controlador->DLGasto($query));
}
if(isset($_GET['DLSubModulo']))
{
	// print_r($_POST);die();
	$query = $_GET;
	echo json_encode($controlador->DLSubModulo($query));
}

if(isset($_GET['pdf_retencion']))
{
	$parametros = $_GET;
	echo json_encode($controlador->pdf_retenciones($parametros));
}

if(isset($_GET['AddMedidor']))
{
	echo json_encode($controlador->AddMedidor($_POST));
}

if(isset($_GET['DeleteMedidor']))
{
	echo json_encode($controlador->DeleteMedidor($_POST));
}

if(isset($_GET['ListarMedidores']))
{
	echo json_encode($controlador->Listar_Medidores($_POST["codigo"]));
	exit();
}
else
if(isset($_GET['FInfoErrorShow']))
{
   echo json_encode($controlador->FInfoErrorShow());
}
/**
 * 
 */
class modalesC
{
	private $modelo;	
	function __construct()
	{
		$this->modelo = new modalesM();
	}

	function busca_cliente($query)
	{
		$resp = $this->modelo->buscar_cliente($query);
		// print_r($resp);die();	
		if(count($resp)>0)
		{
			$veri = digito_verificador_nuevo($query);
			if($resp[0]['TD']==''){$resp[0]['TD'] = $veri['Tipo'];}
		}
		$datos = array();
		foreach ($resp as $key => $value) {
			$datos[] = array(
				'value'=>$value['ID'],
				'label'=>$value['id'],
				'nombre'=>$value['nombre'],
				'telefono'=>$value['Telefono'],
				'codigo'=>$value['Codigo'],
				'razon'=>$value['nombre'],
				'email'=>$value['email'],
			    'direccion'=>$value['Direccion'],
			    'vivienda'=>$value['DirNumero'],
			    'grupo'=>$value['Grupo'],
			    'nacionalidad'=>'',
			    'provincia'=>$value['Prov'],
			    'ciudad'=>$value['Ciudad'],
			    'FA'=>$value['FA'],
			    'TD'=>$value['TD'],
			);
		}	
		return $datos;
	}

	function busca_cliente_nom($query)
	{
		$resp = $this->modelo->buscar_cliente(false,$query['nombre']);
		return $resp;
	}
	function codigo_CI($ci)
	{
		$datos = codigo_verificador($ci);

		// print_r($datos);die();
		if($datos['Tipo']!= "R" && strlen($datos['CI'])== 13)
		{
			$res = file_get_contents("https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=".$ci);
			if($res==true)
			{
				$res2 = file_get_contents("https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa?accion=siguiente&ruc=".$ci);
				$res2 = explode('<table class="formulario">',$res2); //divide en tabla formulario que viene en el html
				$res2 = $res2[1]; //solo toma de tabla formulario para abajo
				$res2 = explode('</table>', $res2); //divide cuando lña tabla termina 
				$res2 = $res2[0]; //se selecciona solo la parte primera que seran los tr
				$res2 = str_replace('<tr>','', $res2); //remplazamos todos los tr

				$datos =  explode('</tr>', $res2);  //dividimos por el final del tr

				// if($datos[2])

				// // $res2 =json_encode($res2);
				// print_r($datos);die();


				$tipo = explode('\'">', $datos[11]);
				$tipo = str_replace(array('</a>','</td>'),'', $tipo[1]);

				return array('Codigo'=>substr($ci, 0,10),'Tipo'=>'R','Dig_ver'=>substr($ci, 10,1),'Ruc_Natu'=>trim($tipo),'CI'=> $ci);

			}else
			{
				print_r('No existe');die();
			}

			print_r($res);die();
		 // TipoSRI = consulta_RUC_SRI(URLInet, NumeroRUC)
		}

		return $datos;

	}
function li2Array($html,$elemento="li"){
 
  $a = array("/<".$elemento.">(.*?)</".$elemento.">/is");
  $b = array("$1 <explode>");
 
  $html  = preg_replace($a, $b, $html);
  $array = explode("<explode>",$html);
 
  return $array;
 
}

	function guardar_cliente($parametro)
	{
		// print_r($parametro);die();
		$resp = $this->modelo->buscar_cliente(trim($parametro['ruc']));		
		$dato[0]['campo']='T';
		$dato[0]['dato']='N';
		$dato[1]['campo']='Codigo';
		$dato[1]['dato']=$parametro['codigoc'];
		$dato[2]['campo']='Cliente';
		$dato[2]['dato']=$parametro['nombrec'];
		$dato[3]['campo']='CI_RUC';
		$dato[3]['dato']=$parametro['ruc'];
		$dato[4]['campo']='Direccion';
		$dato[4]['dato']=$parametro['direccion'];
		$dato[5]['campo']='Telefono';
		$dato[5]['dato']=$parametro['telefono'];
		$dato[6]['campo']='DirNumero';
		$dato[6]['dato']=$parametro['nv'];
		$dato[7]['campo']='Email';
		$dato[7]['dato']=$parametro['email'];
		$dato[8]['campo']='TD';
		$dato[8]['dato']=$parametro['TD'];
		$dato[9]['campo']='CodigoU';
		$dato[9]['dato']=$_SESSION['INGRESO']['CodigoU'];
		$dato[10]['campo']='Prov';
		$dato[10]['dato']=$parametro['prov'];
		$dato[11]['campo']='Pais';
		$dato[11]['dato']='593';
		$dato[12]['campo']='Grupo';
		$dato[12]['dato']=$parametro['grupo'];
		$dato[13]['campo']='Ciudad';
		$dato[13]['dato']=$parametro['ciu'];
		//facturacion
		$dato[14]['campo']='FA';
		$dato[14]['dato']=1;
		if($parametro['rbl']=='false')
		{
			$dato[14]['campo']='FA';
			$dato[14]['dato']=0;
		}
		 //print_r($parametro);die();
		if($parametro['txt_id']!='')
		{
			$campoWhere[0]['campo'] = 'ID';
			$campoWhere[0]['valor'] = $parametro['txt_id'];
			$re = update_generico($dato,'Clientes',$campoWhere);
		}else
		{
			// print_r($resp);die();
			if(count($resp)==0)
		      {
			    $re = insert_generico('Clientes',$dato); // optimizado pero falta 
			  }else{
			  	return 2;
			  }
		}

		if(isset($parametro['cxp']) && $parametro['cxp']==1)
		{
			$pro = $this->modelo->catalogo_Cxcxp($parametro['codigoc']);
			if(count($pro)==0)
			{
				// $cta = 'Cta_Proveedores';
				// $ctas = $this->modelo->buscar_cta($cta);
				// print_r($ctas);die();
				// $datos2 = $this->modelo->LeerCta($ctas[0]['Codigo']);

				// print_r($datos2);die();
				$datosCXP[0]['campo']='TC';
				$datosCXP[0]['dato']='P';
				$datosCXP[1]['campo']='Codigo';
				$datosCXP[1]['dato']=$parametro['codigoc'];
				$datosCXP[2]['campo']='Cta';
				$datosCXP[2]['dato']=$_SESSION['SETEOS']['Cta_Proveedores'];
				$datosCXP[3]['campo']='Item';
				$datosCXP[3]['dato']=$_SESSION['INGRESO']['item'];
				$datosCXP[4]['campo']='Periodo';
				$datosCXP[4]['dato']=$_SESSION['INGRESO']['periodo'];
				insert_generico('Catalogo_CxCxP',$datosCXP);
			}else
			{
				$datosCXP[0]['campo']='TC';
				$datosCXP[0]['dato']='P';
				$datosCXP[1]['campo']='Codigo';
				$datosCXP[1]['dato']=$parametro['codigoc'];
				$datosCXP[2]['campo']='Cta';
				$datosCXP[2]['dato']=$_SESSION['SETEOS']['Cta_Proveedores'];
				$datosCXP[3]['campo']='Item';
				$datosCXP[3]['dato']=$_SESSION['INGRESO']['item'];
				$datosCXP[4]['campo']='Periodo';
				$datosCXP[4]['dato']=$_SESSION['INGRESO']['periodo'];

				$where[0]['campo'] = 'Codigo';
				$where[0]['valor'] = $parametro['codigoc'];
				$where[0]['tipo'] = 'string';
				update_generico($datosCXP,'Catalogo_CxCxP',$where);
				// insert_generico('Catalogo_CxCxP',$datosCXP);
			}
		}

		if($re==1 || $re==null)
		{
			return 1;
		}else
		{
			return -1;
		}
	}

	function validar_sri($ci)
	{
		$res = file_get_contents("https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=".$ci);
			if($res==true)
			{
				$res2 = file_get_contents("https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa?accion=siguiente&ruc=".$ci);
				$res2 = explode('<table class="formulario">',$res2); //divide en tabla formulario que viene en el html
				$res2 = $res2[1]; //solo toma de tabla formulario para abajo
				$res2 = explode('</table>', $res2); //divide cuando lña tabla termina 
				$res2 = $res2[0]; //se selecciona solo la parte primera que seran los tr

				$res2 = str_replace(array('<td colspan="2" class="lineaSep" />','th','<td colspan="2">&nbsp;</td>'),array('','td',''), $res2);

				// print_r($res2);die();
				



            $tbl =strval('<table class="table">'.utf8_encode($res2).'</table>');
            $r = array('res'=>1,'tbl'=>$tbl);
           }
		// print_r($tbl);die();
		return $r;
	}

	function getRemoteFile($url, $timeout = 10) {
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_POST      ,1);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	  curl_setopt ($ch, CURLOPT_URL, $url);
	  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
	 curl_setopt($ch, CURLOPT_HEADER      ,0);  
 
 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	  $file_contents = curl_exec($ch);
	  curl_close($ch);
	  return ($file_contents) ? $file_contents : FALSE;
	}

	function DLCxCxP($parametro)
	{
		$query = ''; if(isset($parametro['q'])){$query = $parametro['q'];}
		$datos = $this->modelo->DLCxCxP($parametro['SubCta'],$query);
		$op = array();
		foreach ($datos as $key => $value) {
			$op[]= array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cta']);
		}

		return $op;
		print_r($datos);die();
	}

	function DLGasto($parametro)
	{
		$query = ''; if(isset($parametro['q'])){$query = $parametro['q'];}
		$datos = $this->modelo->DLGasto($parametro['SubCta'],$query);
		foreach ($datos as $key => $value) {
			$op[]= array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cta']);
		}
		return $op;
	}

	function DLSubModulo($parametro)
	{
		$query = ''; if(isset($parametro['q'])){$query = $parametro['q'];}
		$datos = $this->modelo->DLSubModulo($parametro['SubCta'],$query);
		foreach ($datos as $key => $value) {
			$op[]= array('id'=>$value['Codigo'],'text'=>$value['Detalle']);
		}
		return $op;
	}

	function pdf_retenciones($numero,$TP,$retencion,$serie,$imp=1)
	{
		// $numero = '10000800';
		// $TP = 'CD';
		// $retencion = '603';
		// $serie = '001003';

		// print_r($parametros);die();
		$this->modelo->reporte_retencion($numero,$TP,$retencion,$serie,1);
		// $datos = array();
		// $detalle = array(); 
		// $cliente = array();
		// imprimirDocEle_ret($datos,$detalle,$cliente,$nombre,$sucursal,'factura',$imp=1);
		print_r($parametros);die();
	}

	function DeleteMedidor($parametros)
	{
		@$parametros['Cuenta_No'] = str_pad($parametros['Cuenta_No'], 6, "0", STR_PAD_LEFT);
		extract($parametros);
		$respuesta = $this->modelo->DeleteMedidor($parametros);
		if($respuesta){
			return array('rps'=>true, 'mensaje' => "Medidor No. {$Cuenta_No} eliminado correctamente.");
		}else{
			return array('rps'=>false, 'mensaje' => 'No se pudo eliminar el medidor No. '.$Cuenta_No);
		}
	}

	function AddMedidor($parametros)
	{
		@$parametros['Cuenta_No'] = str_pad($parametros['Cuenta_No'], 6, "0", STR_PAD_LEFT);
		extract($parametros);
		$respuesta = $this->modelo->GetMedidor($parametros);
		if(count($respuesta)<=0){
			$this->modelo->DeleteMedidor($parametros);
			$respuesta = $this->modelo->AddMedidor($parametros);
			if($respuesta){
				return array('rps'=>true, 'mensaje' => "Medidor No. {$Cuenta_No} creado correctamente.");
			}else{
				return array('rps'=>false, 'mensaje' => 'No se pudo crear el medidor No. '.$Cuenta_No);
			}
		}else{
			return array('rps'=>false, 'mensaje' => "El medidor No. {$Cuenta_No} ya esta asociado al cliente {$respuesta[0]['Codigo']}");
		}
	}

	function Listar_Medidores($codigo)
	{
		return $this->modelo->Listar_Medidores($codigo);
	}

	function FInfoErrorShow(){
		return $this->modelo->FInfoError();
	}
}
?>