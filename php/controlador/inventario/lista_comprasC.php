<?php 

require_once(dirname(__DIR__,2).'/modelo/inventario/lista_comprasM.php');
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new lista_comprasC();


if(isset($_GET['pedidos_compra_contratista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_compra_contratista($parametros));
}


if(isset($_GET['pedidos_compra_solicitados']))
{
	$query = $_POST['parametros'];
	
	echo json_encode($controlador->pedidos_compra_solicitados($query));
}

if(isset($_GET['lineas_compras_solicitados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_compras_solicitados($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_pdf($orden));
}

/**
 * 
 */
class lista_comprasC
{
    private $modelo;	
	function __construct()
	{

        $this->modelo = new lista_comprasM();

	}

	function pedidos_compra_contratista($parametros)
	{
		$datos = $this->modelo->pedidos_compra_contratista(false,false,$parametros['fecha'],$parametros['query']);
		$tr = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td><a href="inicio.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=detalle_compra&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>					
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>
					<td>'.$value['Total'].'</td>					
					<td>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_pdf(\''.$value['Orden_No'].'\')" ><i class="fa fa-file-pdf-o"></i></butto>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_excel(\''.$value['Orden_No'].'\')" ><i class="fa fa-file-excel-o"></i></butto>
					</td>					
				</tr>';
				$total+=$value['Total'];
		}
		return $tr;
	}

	function pedidos_compra_solicitados($parametros)
	{
		$datos = $this->modelo->pedidos_compra_solicitados($parametros['orden']);
		return $datos;
		// print_r($datos);die();
	}
	function  lineas_compras_solicitados($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_compras_solicitados($parametros['orden']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td width="20px">'.$value['Cantidad'].'</td>
					<td>'.$value['Precio'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>					
					<td>'.$value['Total'].'</td>				
					<td>'.$value['proveedor'].'</td>
					
				</tr>';
		}
		return $tr;
	}

	function imprimir_pdf($orden)
	{


		$pdf = new cabecera_pdf();  
		$titulo = "O R D E N  D E   C O M P R A ";
		$mostrar = true;
		$sizetable =6;
		$tablaHTML = array();
		$datos_pedido = $this->modelo->pedidos_compra_x_proveedor($orden);
		$pos = 0;
		foreach ($datos_pedido as $key => $value) {

			$ali =array("L","L");
			$medi =array(40,130,40,100);
			$tablaHTML[$pos+0]['medidas']=$medi;
			$tablaHTML[$pos+0]['alineado']=$ali;
			$tablaHTML[$pos+0]['datos']= array('<b>Numero:',$value['Orden_No'],'<b>Dias desde la aprobacion',2);
			$tablaHTML[$pos+0]['newpag'] = 1;

			$tablaHTML[$pos+1]['medidas']=$medi;
			$tablaHTML[$pos+1]['alineado']=$ali;
			$tablaHTML[$pos+1]['datos']= array('<b>Contratista:',$value['Cliente'],'<b>Ahorro:','0.0');

			$tablaHTML[$pos+2]['medidas']=$medi;
			$tablaHTML[$pos+2]['alineado']=$ali;
			$tablaHTML[$pos+2]['datos']= array('<b>Precio Referencial Total:',$value['Total']);

			$tablaHTML[$pos+3]['medidas']=$medi;
			$tablaHTML[$pos+3]['alineado']=$ali;
			$tablaHTML[$pos+3]['datos']= array('<b>Proveedor:',$value['proveedor']);
			


			$datos_lineas = $this->modelo->lineas_compras_solicitados($orden,false,$value['CodigoC']);
			$tablaHTML[$pos+4]['medidas']=array(8,35,20,50,25,13,13,13,13,13,13,15,15,15,15);
			$tablaHTML[$pos+4]['alineado']=array('C','L','L','L','L','L','L','R','R','R','R','R','R','R');
			$tablaHTML[$pos+4]['datos']=array('ITEM','FAMILIA','CODIGO','ITEM','MARCAS','CANT','T0%','T5%','T15%','IVA 5%','IVA 15%','TOTAL','TOTAL GLOBAL','PRECIO REFERENCIAL','DIFERENCIA');
			$tablaHTML[$pos+4]['estilo']='B';
			$tablaHTML[$pos+4]['borde'] = '1';
			$tablaHTML[$pos+4]['size'] = 5;

			$cabecera_medi = $pos+4;
			// print_r($datos_lineas);die();

			$pos = $pos+5;
			foreach ($datos_lineas as $key => $value) {
				// print_r($value);die();
				$codigo = $value['Codigo_Inv'];
				$resp = true;
				while ($resp) {
					$posicion = strrpos($codigo, '.');
					// Verificar que el punto fue encontrado
					if ($posicion !== false) {
					    $códigoModificado = substr($codigo, 0, $posicion);
					    $fami = $this->modelo->buscar_familia($códigoModificado);
					    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
					}
				}
				
				// print_r($fami);die();
			  $tablaHTML[$pos]['medidas']=$tablaHTML[$cabecera_medi]['medidas'];
			  $tablaHTML[$pos]['alineado']=$tablaHTML[$cabecera_medi]['alineado'];
			  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Cantidad'],'','','','','',$value['Precio'],$value['Cantidad'],$value['Total'],"");
			  $tablaHTML[$pos]['estilo']='I';
			  $tablaHTML[$pos]['borde'] = '1';
			  $tablaHTML[$pos]['size'] = 5;
			  $pos = $pos+1;
			}
			
		}
		$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,15,'L',$download = true, $repetirCabecera=null, $mostrar_cero=1,$nuevaPagina=1);

	}



}

?>