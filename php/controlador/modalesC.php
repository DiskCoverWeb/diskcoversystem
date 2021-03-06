<?php 
require_once("../modelo/modalesM.php");



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
		$datos = digito_verificador_nuevo(trim($ci));
		return $datos;

	}

	function guardar_cliente($parametro)
	{
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
		$dato[8]['dato']=$parametro['TC'];
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
		if($_SESSION['INGRESO']['modulo_']=='02' || $_SESSION['INGRESO']['modulo_']=='16')
		{
			$dato[13]['campo']='FA';
			$dato[13]['dato']=1;
			if($parametro['rbl']=='false')
			{
				$dato[13]['campo']='FA';
				$dato[13]['dato']=0;
			}
		}

			// print_r($parametro);die();
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
		$url = "https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=".$ci;
		$url_sri = "https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa?accion=siguiente&ruc=".$ci;
		$res = $this->getRemoteFile($url);
		$r = array('res'=>2,'tbl'=>'');
		if($res=='true')
		{
			$r = array('res'=>2,'tbl'=>'');
			$datos = $this->getRemoteFile($url_sri);
			if($datos!= false)
			{
		    $tr='';
			$sp = '<table class="formulario">';
            $tbl = explode($sp, $datos); //tomo los datos de la tabla que envioa a pagina del sri
            $tbl =  explode('</table>',$tbl[1]); //quito el final de la tabla
            $html  = explode('</tr>',str_replace('<tr>','',$tbl[0]));  //tomo los elementos de cada tr
            foreach ($html as $key => $value) {

            // print_r($value);die();
            	//comparo si los tr estan vacios
            	if(trim($value)!='<td colspan="2" class="lineaSep" />' && trim($value)!='<td colspan="2">&nbsp;</td>')
            	{
            		$tr.="<tr>".$value."</tr>";
            	}
            }

            // print_r($tr);die();
			// $html  = str_replace('<tr>
			// 	<td colspan="2"></td>
			// </tr>','',$html);
			$html = str_replace('&oacute;','o',$tr);
			$html = str_replace('&nbsp;','',$html);

			// print_r($html);die();
			// $html = str_replace('U+FFFD','',$html);
            $tbl =strval('<table class="table">'.utf8_encode($html).'</table>');
            $r = array('res'=>1,'tbl'=>$tbl);
           }

		}
		// print_r($tbl);die();
		return $r;
	}

	function getRemoteFile($url, $timeout = 10) {
	  $ch = curl_init();
	  curl_setopt ($ch, CURLOPT_URL, $url);
	  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
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



}
?>