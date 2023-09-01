<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/alimentos_recibidosM.php");


$controlador = new alimentos_recibidosC();
if(isset($_GET['proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->proveedores($query));
}
if(isset($_GET['guardar']))
{
	$parametros = $_POST;
	echo json_decode($controlador->guardar($parametros));
}
if(isset($_GET['guardar2']))
{
	$parametros = $_POST;
	echo json_decode($controlador->guardar2($parametros));
}
if(isset($_GET['alimentos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cta_procesos($query));
}
if(isset($_GET['detalle_ingreso']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->detalle_ingreso($query));
}
if(isset($_GET['datos_ingreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->datos_ingreso($id));
}

if(isset($_GET['autoincrementable']))
{
	$parametros = $_POST['parametros'];
	$num = ReadSetDataNum('Ingresos_Recibidos',false,false,$parametros['fecha']);
	$num = generaCeros($num,4);
	echo json_encode($num);
}
if(isset($_GET['search']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->buscar($query));

}
/**
 * 
 */
class alimentos_recibidosC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new alimentos_recibidosM();
	}

	function guardar($parametros)
	{
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','N');
		SetAdoFields('Mensaje',$parametros['txt_comentario']);
		SetAdoFields('Fecha_P',$parametros['txt_fecha']);
		SetAdoFields('CodigoP',$parametros['ddl_ingreso']);
		SetAdoFields('Cod_C',$parametros['ddl_alimento']);
		SetAdoFields('TOTAL',$parametros['txt_cant']);
		SetAdoFields('Envio_No',substr($parametros['txt_codigo'],0,-4).generaCeros(ReadSetDataNum('Ingresos_Recibidos',false,true,$parametros['txt_fecha']),4));
		return SetAdoUpdate();

	}

	function guardar2($parametros)
	{
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','N');		
		SetAdoFields('Llamadas',$parametros['txt_comentario2']);
		if(isset($parametros['rbl_recibido']))
		{
			SetAdoFields('C',1);
		}else
		{
			SetAdoFields('C',0);
		}
		SetAdoFields('SucIng',$parametros['cbx_evaluacion']);
		SetAdoFieldsWhere('ID',$parametros['txt_id']);
		return SetAdoUpdateGeneric();

	}
	function cta_procesos($query)
	{
		$datos = $this->modelo->cta_procesos($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['TP'],'text'=>$value['Proceso']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;

	}

	function detalle_ingreso($query)
	{
		$datos = $this->modelo->detalle_ingreso();
		// $bene = array();
		// foreach ($datos as $key => $value) {
		// 	$bene[] = array('id'=>$value['ID'],'text'=>$value['Cliente']);
		// 	// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		// }
		return $datos;
	}
	function datos_ingreso($cod)
	{
		// print_r($id);die();
		$datos = $this->modelo->detalle_ingreso($cod);
		return $datos[0];
	}
	function buscar($cod)
	{
		$datos = $this->modelo->buscar_transCorreos($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("value"=>$value['ID'],"label"=>$value['Envio_No'],'Fecha'=>$value['Fecha_P']->format('Y-m-d'),'mensaje'=>$value['Mensaje'],'Codigo_P'=>$value['CodigoP'],'Total'=>$value['TOTAL'],'Cod_C'=>$value['Cod_C'],'CI_RUC'=>$value['CI_RUC'],'Cod_Ejec'=>$value['Cod_Ejec'],'Cliente'=>$value['Cliente'],'Proceso'=>$value['Proceso']);
		}
		return $result;
	}

	function proveedores($query)
	{
		$datos = $this->modelo->buscar_transCorreos($query);
		if($datos!=-1)
		{
		     $prov = array();
		     foreach ($datos as $key => $value) {
			  // print_r($value);die();
			  $prov[] = array('id'=>$value['ID'],'text'=>$value['Envio_No']);
		     }
		     return $prov;
		 }else
		 {
		 	return -1;
		 }
	}
	  


}

?>