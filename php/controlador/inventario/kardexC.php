<?php 
include(dirname(__DIR__,2).'/modelo/inventario/kardexM.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new kardexC();


if(isset($_GET['cambiarProducto']))
{
	$codigoProducto = $_POST['codigoProducto'];
  	$datos = $controlador->productos('P',$codigoProducto);
  	echo json_encode($datos);
}

if(isset($_GET['consulta_kardex_producto']))
{
  	echo json_encode($controlador->consulta_kardex_producto());
}

if(isset($_GET['consulta_kardex']))
{
  	echo json_encode($controlador->consulta_kardex());
}

if(isset($_GET['kardex_total']))
{
  	echo json_encode($controlador->kardex_total());
}

if(isset($_GET['generarPDF']))
{
  $controlador->generarPDF();
}

if(isset($_GET['generarExcel']))
{
  $controlador->generarExcel();
}

if(isset($_GET['funcion']))
{
  $controlador->funcionInicio();
}

class kardexC
{
	private $modelo;
	private $pdf;
	function __construct()
	{
		$this->modelo = new kardexM();
		$this->pdf = new cabecera_pdf();
	}

	public function productos($tipo,$codigoProducto){
		$datos = $this->modelo->productos($tipo,$codigoProducto);
		$productos = [];
    	$productos[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    	$i = 0;
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$productos[$i] = array('codigo'=>$value['Codigo_Inv']."/".$value['Minimo']."/".$value['Maximo']."/".$value['Unidad'],'nombre'=>utf8_encode($value['Codigo_Inv'])." ".utf8_encode($value['NomProd']));
      		$i++;
		}
		return $productos;
	}

	public function bodegas(){
		$datos = $this->modelo->bodegas();
		$productos = [];
    	$productos[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    	$i = 0;
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$productos[$i] = array('codigo'=>$value['CodBod'],'nombre'=>utf8_encode($value['CodBod'])." ".utf8_encode($value['Bodega']));
      		$i++;
		}
		return $productos;
	}

	public function consulta_kardex_producto(){
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$codigo = $_POST['productoP'];
		$tabla = $this->modelo->consulta_kardex_producto($desde,$hasta,$codigo);
		return $tabla;
	}

	public function consulta_kardex(){
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$codigo = $_POST['productoP'];
		$cbBodega = $_POST['cbBodega'];
		$bodega = $_POST['bodega'];
		$tabla = $this->modelo->consulta_kardex($desde,$hasta,$codigo,$cbBodega,$bodega);
		return $tabla;
	}

	public function kardex_total(){
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$codigo = $_POST['productoP'];
		$cbBodega = $_POST['cbBodega'];
		$bodega = $_POST['bodega'];
		$tabla = $this->modelo->kardex_total($desde,$hasta,$codigo,$cbBodega,$bodega);
		return $tabla;
	}

	public function funcionInicio(){
		$this->modelo->funcionInicio();
	}

	public function generarPDF(){
	    $desde = $_GET['desde'];
		$hasta = $_GET['hasta'];
		$codigo = $_GET['codigo'];
	    $datos = $this->modelo->consulta_kardex_producto($desde,$hasta,$codigo);
	    $titulo = 'Control de existencias';
	    $parametros['desde'] = false;
	    $parametros['hasta'] = false;
	    $sizetable = 8;
	    $mostrar = true;
	    $tablaHTML = array();


	    $tablaHTML[0]['medidas'] = array(15,18,10,16,80,20,20,20,20,20,20,20);
	    $tablaHTML[0]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L','L');
	    $tablaHTML[0]['datos'] = array('Bodega','Fecha','TP','Numero','Detalle','Entrada','Salida','Valor_Unit','Valor_Total','Stock Act.','Costo_Prom','Saldo Total');
	    $tablaHTML[0]['borde'] = 1;
	    $tablaHTML[0]['estilo'] = 'B';

	    $count = 1;
	    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
	      	$tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
	      	$tablaHTML[$count]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L','L');
	      	$tablaHTML[$count]['datos'] = array($value['Bodega'],$value['Fecha']->format('Y-m-d'),$value['TP'],$value['Comp_No'],$value['Detalle'], $value['Entrada'],$value['Salida'],$value['Valor_Unitario'],$value['Valor_Total'],$value['Stock'],$value['Costo'],$value['Saldo']);
	      	$tablaHTML[$count]['borde'] = $tablaHTML[0]['borde'];
	      	$count += 1;
	    }
	    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,25,$orientacion='L',true);
	}

	public function generarExcel($download = true){
	    $titulos = $_GET['array_titulo'];
	    $datos = explode(",", $_GET['array_datos']);
	    kardexExcel($titulos,$datos,$ti='ControlExistencias',$camne=null,$b=null,$base=null,$download);
	 }
}

?>