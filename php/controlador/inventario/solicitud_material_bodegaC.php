<?php
require_once (dirname(__DIR__,2).'/modelo/inventario/solicitud_material_bodegaM.php');
require_once(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
$_SESSION['INGRESO']['modulo_']='60';

$controlador = new inventario_onlineC();
if (isset($_GET['guardar'])) {
	
	echo json_encode($controlador->guardar_entrega($_POST['parametros']));
}
if (isset($_GET['entrega'])) {
	
	echo json_encode($controlador->lista_entrega());
}
if (isset($_GET['generar_comprobante'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->generar_factura($parametro));
}
if (isset($_GET['pedidos_contratista'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista($parametro));
}
if (isset($_GET['pedidos_contratista_detalle'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista_detalle($parametro));
}
if (isset($_GET['pedidos_contratista_detalle_check'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista_detalle_check($parametro));
}

if (isset($_GET['listar_rubro'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->listar_rubro($parametro));
}

if (isset($_GET['editarCCRubro'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->editarCCRubro($parametro));
}

if (isset($_GET['AprobarSolicitud'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->AprobarSolicitud($parametro));
}

if (isset($_GET['AprobarEntrega'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->AprobarEntrega($parametro));
}

if (isset($_GET['pedidos_contratistaCheck'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratistaCheck($parametro));
}


class inventario_onlineC
{
	private $modelo;
	private $pdf;
	private $ing_des;
	
	function __construct()
	{
		$this->modelo = new solicitud_material_bodegaM();		
		$this->pdf = new cabecera_pdf();	
		$this->ing_des = new ingreso_descargosM();
		// $this->pdftable = new PDF_MC_Table();			
	}

	function guardar_entrega($parametro)
	{
		// print_r($parametro);die();
		
		$id =count($this->lista_entrega())+1;
		if($parametro['id']=='')
		{
		   SetAdoAddNew("Asiento_K");
		   SetAdoFields('CODIGO_INV',$parametro['codigo']);
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']);
		   SetAdoFields('CANT_ES',$parametro['cant']);
		   SetAdoFields('CTA_INVENTARIO',$parametro['cta_pro']);
		   SetAdoFields('SUBCTA',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Procedencia',$parametro['observacion']);
		   SetAdoFields('A_No',$id);
		   SetAdoFields('Fecha_Fab',date('Y-m-d',strtotime($parametro['fecha'])));
		   SetAdoFields('Codigo_Dr',$parametro['bajas_por']);
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   SetAdoFields('CONTRA_CTA',$parametro['cc']);
	   	 SetAdoFields('CodMar',$parametro['codma']);
		   // print_r($datos);die();
		 		  $resp = SetAdoUpdate();

       //actualiza presupuesto
		  		 $presu =$this->modelo->actualizar_trans_presupuesto($parametro);
				   if(count($presu)>0){
						 $consu = $presu[0]['Total'];

						SetAdoAddNew("Trans_Presupuestos");
						SetAdoFields('Total',$consu+$parametro['cant']);

						SetAdoFieldsWhere('ID', $presu[0]['ID']);
						return SetAdoUpdateGeneric();
				   }	
		 	 return $resp;
	    }else
	    {
	    	// print_r($parametro);die();
	     SetAdoAddNew("Asiento_K");
	     SetAdoFields('CODIGO_INV',strval($parametro['codigo']));
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']);
		   SetAdoFields('CANT_ES',$parametro['cant']);
		   SetAdoFields('CTA_INVENTARIO',$parametro['cta_pro']);
		   SetAdoFields('SUBCTA',$parametro['rubro']);
		   SetAdoFields('Consumos',number_format($parametro['bajas'],2,'.',''));
		   SetAdoFields('Procedencia',$parametro['observacion']);
		   SetAdoFields('Fecha_Fab',date('Y-m-d'));
		   SetAdoFields('Codigo_Dr',$parametro['bajas_por']);
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('CONTRA_CTA',$parametro['cc']);

		   SetAdoFieldsWhere('CODIGO_INV',strval($parametro['ante']));
		   SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFieldsWhere('A_No',$id-1);
		   // print_r($datos);die();

		   SetAdoFieldsWhere('ID', $presu[0]['ID']);
			 return SetAdoUpdateGeneric();
	    }
	    // print_r($resp);die();
	}

	function lista_entrega()
	{
		$resp = $this->modelo->lista_entrega();
		// $resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}

	function generar_factura($parametro)
	{
		try
		{
			$resp = $this->modelo->lista_entrega();
			$codigo = ReadSetDataNum("PS_SERIE_001001", false, True);
			// print_r($codigo);die();
			$orden = 'PS_SERIE_001001_'.$codigo;
			// print_r($orden);die();

			foreach ($resp as $key => $value) {
				// print_r($value);die();
				SetAdoAddNew("Trans_Kardex");
				SetAdoFields('Orden_No',$orden);
				SetAdoFields('T','S');
				SetAdoFields('TP','CD');
			  	SetAdoFields('CodigoU', $value['CodigoU']);
		   		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   		SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   		SetAdoFields('Codigo_Inv',$value['CODIGO_INV']);
		   		SetAdoFields('Salida',$value['CANT_ES']);
		   		SetAdoFields('Valor_Unitario',$value['VALOR_UNIT']);
		   		SetAdoFields('Valor_Total',$value['VALOR_TOTAL']);
		   		SetAdoFields('Cta_Inv',$value['CTA_INVENTARIO']);
		   		SetAdoFields('Contra_Cta',$value['CONTRA_CTA']);		   		
		   		SetAdoFields('CodigoL',$value['SUBCTA']);
				SetAdoUpdate();

				$this->modelo->eliminarAsientoK('P',$orden,$value['CODIGO_INV']);
			}
			return 1;
		}catch(Exception $e)
		{
			return -1;
		}

	}

	function pedidos_contratista($parametro)
	{
		$datos = $this->modelo->pedidos_contratista($parametro['query'],$parametro['fecha']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td><a href="../vista/inicio.php?mod=03&acc=DetalleSolicitudesBodega&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>';
					if($value['TC']=='.')
					{
						$tr.='<span class="label label-danger">Para Revision</span>';
					}else
					{
						$tr.='<span class="label label-primary">Para Genera comprobante</span>';
					}
					$tr.='</td>
				</tr>';
		}

		return $tr;
		// print_r($parametro);die();

	}

	function pedidos_contratistaCheck($parametro)
	{
		$datos = $this->modelo->pedidos_contratista_check($parametro['query'],$parametro['fecha']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td><a href="../vista/inicio.php?mod=03&acc=DetalleSolicitudesBodegaCheck&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>';
					if($value['TC']=='.')
					{
						$tr.='<span class="label label-danger">Para Revision</span>';
					}else
					{
						$tr.='<span class="label label-warning">Para Checking</span>';
					}
					$tr.='</td>
				</tr>';
		}

		return $tr;
		// print_r($parametro);die();

	}

	function pedidos_contratista_detalle($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida(false,$parametro['order']);
		// print_r($datos);die();
		$tr = '';
		$estado = '.';
		foreach ($datos as $key => $value) {
			$estado =$datos[0]['TC'];
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</a></td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Salida'].'</td>
					<td>
						<select id="ddl_linea_cc_'.$value['ID'].'" class="form-control" onchange="cargar_rubro_linea('.$value['ID'].',this.value)">';
							$centroC = $this->modelo->listar_cc_info();

							foreach ($centroC as $key2 => $value2) {
								$select = '';
								if($value2['id'] == $value['Contra_Cta']){$select = 'selected';}
								$tr.='<option value="'.$value2['id'].'" '.$select.'>'.$value2['text'].'</option>';
							}
						$tr.='</select>
					</td>
					<td>
					<select class="form-control" id="ddl_linea_rubro_'.$value['ID'].'">';
							$rubro = $this->modelo->listar_rubro(false,$value['Contra_Cta']);
							foreach ($rubro as $key2 => $value2) {
								$select = '';
								if($value2['id'] == $value['CodigoL']){$select = 'selected';}
								$tr.='<option value="'.$value2['id'].'" '.$select.'>'.$value2['text'].'</option>';
							}
						$tr.='</select>
					</td>
					<td>
						<button class="btn btn-sm btn-primary" title="Editar linea" onclick="guardar_linea('.$value['ID'].')"><i class="fa fa-save"></i></button>
					</td>
				</tr>';
		}



		return array('tabla'=>$tr,'datos'=>$datos,'estado'=>$estado);
		// print_r($parametro);die();

	}

	function pedidos_contratista_detalle_check($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida_check(false,$parametro['order']);
		// print_r($datos);die();
		$tr = '';
		$estado = '.';
		foreach ($datos as $key => $value) {
			$estado =$datos[0]['TC'];
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</a></td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Salida'].'</td>
					<td>'.$value['Cuenta'].'</td>
					<td>'.$value['Detalle'].'</td>
					<td>
						<input type="checkbox" id="rbl_'.$value['ID'].'" name="rbl_'.$value['ID'].'">						
					</td>
				</tr>';
		}



		return array('tabla'=>$tr,'datos'=>$datos,'estado'=>$estado);
		// print_r($parametro);die();

	}


	function listar_rubro($parametros)
	{
		return $this->modelo->listar_rubro($query='',$parametros['cc']);
	}

	function editarCCRubro($parametro)
	{
		SetAdoAddNew("Trans_Kardex");
		SetAdoFields('CodigoL',$parametro['rubro']);
		SetAdoFields('CONTRA_CTA',$parametro['cc']);

		SetAdoFieldsWhere('ID', $parametro['ID']);
		return SetAdoUpdateGeneric();
	}

	function AprobarSolicitud($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida(false,$parametro['order']);
		foreach ($datos as $key => $value) {
			SetAdoAddNew("Trans_Kardex");
			SetAdoFields('TC','K');

			SetAdoFieldsWhere('ID', $value['ID']);
			SetAdoUpdateGeneric();		
		}
		return 1;
	}
	function AprobarEntrega($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida(false,$parametro['orden']);
		foreach ($datos as $key => $value) {
			SetAdoAddNew("Trans_Kardex");
			SetAdoFields('TC','GC');

			SetAdoFieldsWhere('ID', $value['ID']);
			SetAdoUpdateGeneric();		
		}
		return 1;
	}

}
?>