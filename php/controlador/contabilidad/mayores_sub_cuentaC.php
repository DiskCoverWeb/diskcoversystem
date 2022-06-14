<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/mayores_sub_cuentaM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new mayores_sub_cuentaC();
if(isset($_GET['consultar']))
{	
  echo json_encode($controlador->consultar_banco($_POST['parametros']));
}
if(isset($_GET['reporte_pdf']))
{
	$parametros = array(
			'tipoM'=>$_GET["rbl_subcta"],
			'DCCtas'=>$_GET["DCCtas"],
			'DLCtas'=>$_GET["DLCtas"],
			'estado'=>$_GET['rbl_estado'],
			'unoTodos'=>$_GET['rbl_opc'],
			'checkusu'=>isset($_GET["check_usu"]),
			'usuario'=>$_GET['DCUsuario'],
			'desde'=>$_GET['txt_desde'],
			'hasta'=>$_GET['txt_hasta'],	
			'checkagencia'=>isset($_GET['check_agencia']),
			'agencia'=>$_GET['DCAgencia']);
  echo json_encode($controlador->reporte_pdf($parametros));
}

if(isset($_GET['drop']))
{	
  echo json_encode($controlador->cargar_usuario_sucuarsal());
}
if(isset($_GET['DLCtas']))
{	
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->DLCtas($parametros));
}
if(isset($_GET['DCCtas']))
{	
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->DCCtas($parametros));
}
if(isset($_GET['Consultar_Un_Submodulo']))
{	
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->Consultar_Un_Submodulo($parametros));
}

class mayores_sub_cuentaC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new mayores_sub_cuentaM();
		$this->pdf = new cabecera_pdf();
	}

	function cargar_usuario_sucuarsal()
	{
		$usuario = $this->modelo->usuario();
		$sucursal = $this->modelo->sucursal();
		$datos = array('usuario'=>$usuario,'agencia'=>$sucursal);
		return $datos;

	}
	function DCCtas($parametros)
	{
		 $DCCtas = $this->modelo->Ctas_SubMod($parametros['tipoMod']);	
		 // print_r($DCCtas);die();
		 return $DCCtas;
	}
	function DLCtas($parametros)
	{
		// print_r($parametros);die();
		 $DLCtas = $this->modelo->lista_cuentas($parametros['tipoMod'],$parametros['DCCta']);
		 return $DLCtas;
	}

	function Consultar_Un_Submodulo($parametro)
	{
    return $this->modelo->Consultar_Un_Submodulo($parametro);
	}
	function reporte_pdf($parametros)
	{
			$datos = $this->modelo->Consultar_Un_Submodulo_datos($parametros);

		  $desde = str_replace('-','',$parametros['desde']);
			$hasta = str_replace('-','',$parametros['hasta']);	

			// print_r($datos);die();
  		//print_r($datos);
     

		$titulo = 'MODULOS DE SUBCUENTA DE BLOQUE';
		$sizetable =6.5;
		$mostrar = TRUE;
		$Fechaini = $parametros['desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		$pos = 0;
		$tipo = '';

		switch ($parametros['tipoM']) {
			case 'C':
				$tipo = 'Cta. por Cobrar';
				break;
			  case "P": 
			  $tipo = "Ctas. por Pagar";
			  break;
			  case "I": 
			  $tipo = "Ctas. de Ingresos";
			  break;
			  case "G": 
			  $tipo = "Ctas. de Gastos";
			  break;
			  case "PM":
			  $tipo =  "Valores de Primas";
			  break;
			}
		
		foreach ($datos['datos'] as $key => $value) {

			// print_r($value);die();
			$cta = $this->modelo->Ctas_SubMod($parametros['tipoM'],$value['Cta']);

			// print_r($cta);die();

				$tablaHTML[$pos]['medidas']=array(20,75,15,80);
				$tablaHTML[$pos]['alineado']=array('L','L','L','L');
				$tablaHTML[$pos]['datos']=array('<b>CUENTA:',$cta[0]['Nombre_Cta'],'<B>GRUPO:',$value['Codigo'].'-'.$value['Cliente']);
				$tablaHTML[$pos]['borde'] =array('LT','T','T','RT');
				$pos+=1;
				$tablaHTML[$pos]['medidas']=array(30,70,35,55);
				$tablaHTML[$pos]['alineado']=array('L','L','L','L');
				$tablaHTML[$pos]['datos']=array('Mayor de Submódulo:',$tipo,'Saldo Anterior S/','');
				$tablaHTML[$pos]['borde'] =array('LB','B','B','RB');
				$pos+=1;
				$tablaHTML[$pos]['medidas']=array(15,15,8,17,69,20,15,15,15);
				$tablaHTML[$pos]['alineado']=array('L','R','L','L','L','R','R','R','R');
				$tablaHTML[$pos]['datos']=array('FECHA','FACTURA','TD','NUMERO','CO N C E P T O','PARCIAL M/E','DEBITOS','CREDITOS','SALDO');
				$tablaHTML[$pos]['estilo']='BU';
				$tablaHTML[$pos]['borde'] =array('LTB','TB','TB','TB','TB','TB','TB','TB','RTB');
				$pos+=1;

				//CUERPO
				$tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
				$tablaHTML[$pos]['alineado']=$tablaHTML[$pos-1]['alineado'];
				$tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Factura'],$value['TP'],$value['Numero'],$value['Concepto'],$value['Parcial_ME'],$value['Debitos'],$value['Creditos'],$value['Saldo_MN']);
				$tablaHTML[$pos]['borde'] =1;
				$pos+=1;

				$tablaHTML[$pos]['medidas']=array(125,20,15,15,15);
				$tablaHTML[$pos]['alineado']=array('L','R','R','R','R');
				$tablaHTML[$pos]['datos']=array('','T o t a l e s',$value['Debitos'],'',$value['Saldo_MN']);
				$tablaHTML[$pos]['estilo']='B';
				$pos+=1;	
				$tablaHTML[$pos]['medidas']=array(190);
				$tablaHTML[$pos]['alineado']=array('L');
				$tablaHTML[$pos]['datos']=array('');
				$tablaHTML[$pos]['estilo']='BU';
				$pos+=1;	

		}
	
		
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,25,'P');
	}
}

?>