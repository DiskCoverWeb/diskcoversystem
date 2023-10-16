<?php 
include(dirname(__DIR__,1)."/modelo/modalesM.php");



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
else
if(isset($_GET['FInfoErrorEliminarTablaTemporal']))
{
   echo json_encode($controlador->EliminarTablaTemporal());
   die();
}

if(isset($_GET['ActualizarDocumentoCliente']))
{
	echo json_encode($controlador->ActualizarDocumentoCliente($_POST));
	exit();
}
if(isset($_GET['ExcelFInfoError']))
{
	echo json_encode($controlador->GenerarExcelFInfoError());
	exit();
}
if(isset($_GET['tipo_proveedor']))
{
	echo json_encode($controlador->tipo_proveedor());
}
if(isset($_GET['sucursales']))
{
	// print_r($_POST);die();
	$query = $_POST['parametros'];
	echo json_encode($controlador->sucursales($query));
}

if(isset($_GET['add_sucursal']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_sucursal($parametros));
}
if(isset($_GET['delete_sucursal']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_sucursal($parametros));
}


/**
 * 
 */
class modalesC
{
	private $modelo;	
	private $sri;	
	function __construct()
	{
		$this->sri = new autorizacion_sri();
		$this->modelo = new modalesM();
	}

	function busca_cliente($query)
	{
		$resp = $this->modelo->buscar_cliente($query,false,false,1);
		// print_r($resp);die();	
		if(count($resp)>0)
		{
			$veri = Digito_verificador($query);
			if($resp[0]['TD']==''){$resp[0]['TD'] = $veri['Tipo_Beneficiario'];}
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
			    'Cod_Ejec'=>$value['Cod_Ejec'],
			    'Actividad'=>$value['Actividad'],
			);
		}	
		return $datos;
	}

	function busca_cliente_nom($query)
	{
		$resp = $this->modelo->buscar_cliente(false,$query['nombre']);
		return $resp;
	}

	function codigo_CI($CI_RUC)
	{
		 $CI_RUC = $this->sri->quitar_carac($CI_RUC);
		 $datos = Digito_verificador($CI_RUC);
		 return $datos;
	}

	function codigo_CI1($ci)
	{
		$datos = Digito_Verificador($ci);

		// print_r($datos);die();
		if($datos['Tipo_Beneficiario']!= "R" && strlen($datos['CI'])== 13)
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

		 $cli = $this->modelo->buscar_cliente($ci=false,false,$parametro['txt_id']);	
		 
		 SetAdoAddNew("Clientes");
	    SetAdoFields("T", G_NORMAL);
	    SetAdoFields("Cliente", $parametro['nombrec']);
	    SetAdoFields("CI_RUC", $this->sri->quitar_carac($parametro['ruc']));
	    SetAdoFields("Codigo",$parametro['codigoc']);
	    SetAdoFields("Direccion", $parametro['direccion']);
	    SetAdoFields("Telefono", $parametro['telefono']);
	    SetAdoFields("DirNumero", $parametro['nv']);
	    SetAdoFields("Email", $parametro['email']);
	    SetAdoFields("TD", $parametro['TD']);
	    SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
	    SetAdoFields("Prov", $parametro['prov']);
	    SetAdoFields("Pais", "593");
	    SetAdoFields("Grupo", $parametro['grupo']);
	    SetAdoFields("Ciudad", $parametro['ciu']);
	    SetAdoFields("Cod_Ejec", $parametro['txt_ejec']);
	    SetAdoFields("Actividad", $parametro['txt_actividad']);
	    if($parametro['rbl']=='false')
	    {
		    SetAdoFields("FA", 0);
	    }else
	    {
	    	SetAdoFields("FA", 1);    	
	    }    

		if($parametro['txt_id']!='')
		{
			$nom = $this->modelo->buscar_cliente($ci=false,$parametro['nombrec'],$id=false,1);	
			if(count($nom)==0)
			{
				// el nombre no existe y se podra editar el registro
				SetAdoFieldsWhere("ID", $parametro['txt_id']);
   			$re = SetAdoUpdateGeneric();  

			}else
			{
				// nombre si existe
				if($nom[0]['ID']==$parametro['txt_id'])
				{
					//entra aqui cuando solo se edita otras partes que no sea el nombre
					SetAdoFieldsWhere("ID", $parametro['txt_id']);
   				$re = SetAdoUpdateGeneric();  
				}else
				{
					// en el caso de que el nombre exista pero no es del registro ya establecido sino otros 
					return 3;
				}


			}			
		}else
		{			
			 $re = SetAdoUpdate();	
		}

		if(isset($parametro['cxp']) && $parametro['cxp']==1)
		{
			$pro = $this->modelo->catalogo_Cxcxp($parametro['codigoc']);
			  SetAdoAddNew("Catalogo_CxCxP");
			  SetAdoFields("TC",'P');
			  SetAdoFields("Codigo",$parametro['codigoc']);
			  SetAdoFields("Cta", $_SESSION['SETEOS']['Cta_Proveedores']);
			  SetAdoFields("Item", $_SESSION['INGRESO']['item']);
			  SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
			if(count($pro)==0)
			{			 			    
			  SetAdoUpdate();	
			}else
			{				
				
				SetAdoFieldsWhere("Codigo",$parametro['codigoc']);
   		  	SetAdoUpdateGeneric();
			}
		}

		if($re==1)
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
				print_r($res2);die();
				$res2 = $res2[1]; //solo toma de tabla formulario para abajo
				$res2 = explode('</table>', $res2); //divide cuando lña tabla termina 
				$res2 = $res2[0]; //se selecciona solo la parte primera que seran los tr

				$res2 = str_replace(array('<td colspan="2" class="lineaSep" />','th','<td colspan="2">&nbsp;</td>'),array('','td',''), $res2);

				// print_r($res2);die();
				



            $tbl =strval('<table class="table">'.mb_convert_encoding($res2, 'UTF-8').'</table>');
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

	function  tipo_proveedor()
	{
		$datos = $this->modelo-> tipo_proveedor();
		return $datos;
	}

	function FInfoErrorShow(){
		return $this->modelo->FInfoError();
	}

	function EliminarTablaTemporal(){
		return $this->modelo->EliminarTablaTemporal();
	}

	function ActualizarDocumentoCliente($parametros)
	{
		extract($parametros);
		return Procesar_Renumerar_CIRUC_JuntaAgua($CodigoC, $NewDocument);
	}

	function GenerarExcelFInfoError()
	{
		$sql = $this->modelo->FInfoError(false);
		return exportar_excel_generico_SQl("FORMULARIO DE INFORME DE ERRORES",$sql);
	}
	function sucursales($parametros)
	{
		$cli = $this->modelo->buscar_cliente($parametros['ruc'],$nombre=false,$id=false,1);
		$datos = array();
		if(count($cli)>0)
		{
			$datos = $this->modelo->sucursales(false,$cli[0]['Codigo']);
		}
		return $datos;
	}
	function add_sucursal($parametros)
	{
		$cli = $this->modelo->buscar_cliente($parametros['ruc'],$nombre=false,$id=false,1);
		$datos = array();
		if(count($cli)>0)
		{
			 SetAdoAddNew("Clientes_Datos_Extras");
		    SetAdoFields("Codigo",$cli[0]['Codigo']);
		    SetAdoFields("Direccion", $parametros['direccion']);
		    SetAdoFields("Tipo_Dato",'TIPO_PROV');
	 		return SetAdoUpdate();	
		}
	}
	function delete_sucursal($parametros)
	{
		return $this->modelo->delete_sucursal($parametros['id']);
	}
}
?>