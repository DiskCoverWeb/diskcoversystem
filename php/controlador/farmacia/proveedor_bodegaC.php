<?php 
require(dirname(__DIR__,2).'/modelo/farmacia/proveedor_bodegaM.php');
/**
 * 
 */
$controlador = new proveedor_bodegaC();
if(isset($_GET['lista_clientes']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_clientes($parametros));
}
if(isset($_GET['add_clientes']))
{
	$parametros = $_POST['parametros'];
	$respuesta = $controlador->add_clientes($parametros);
	echo json_encode($respuesta);
}
if(isset($_GET['delete']))
{
	$id =$_POST['id'] ;
	echo json_encode($controlador->delete_clientes($id));
}

if(isset($_GET['buscar_edi']))
{
	$id =$_POST['id'] ;
	echo json_encode($controlador->buscar_edi($id));
}
if(isset($_GET['guardar_cuentas']))
{
	$parametros =$_POST ;
	echo json_encode($controlador->guardar_cuentas($parametros));
}
class proveedor_bodegaC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new proveedor_bodegaM();
	}

	function lista_clientes($parametros)
	{
		$datos = $this->modelo->lista_clientes($parametros['query']);
		$tr="";
		foreach ($datos as $key => $value) {
			$tr.='<tr>
			<td>'.$value['Cliente'].'</td>
			<td>'.$value['CI_RUC'].'</td>
			<td>'.$value['Telefono'].'</td>
			<td>'.$value['Email'].'</td>
			<td>'.$value['Direccion'].'</td>
			<td>
			<button class="btn btn-sm btn-primary" onclick="buscar_cliente('.$value['ID'].')"><i class="fa fa-pencil"></i></button>
			<button class="btn btn-sm btn-danger" onclick="eliminar('.$value['ID'].')"><i class="fa fa-trash"></i></button>
			</td>
			</tr>';
		}
		return $tr;
		// print_r($datos);die();
	}
	function add_clientes($parametros)
	{
		$datos[0]['campo']='Cliente';
		$datos[0]['dato']=$parametros['nombre'];
		$datos[1]['campo']='CI_RUC';
		$datos[1]['dato']=$parametros['ci'];
		$datos[2]['campo']='Telefono';
		$datos[2]['dato']=$parametros['telefono'];
		$datos[3]['campo']='Email';
		$datos[3]['dato']=$parametros['email'];
				
		$codig = digito_verificador_nuevo($parametros['ci']);
		// print_r($codig);die();
		if($codig['Tipo']!='C')
		{
			return -3;
		}
		$datos[4]['campo'] = 'T';
		$datos[4]['dato']='N';
		$datos[5]['campo'] = 'Codigo';
		$datos[5]['dato']=$codig['Codigo'];
		$datos[6]['campo'] = 'TD';
		$datos[6]['dato']=$codig['Tipo'];
		$datos[7]['campo'] = 'Direccion';
		$datos[7]['dato']=$parametros['direccion'];

		if($parametros['id']=='')
		{
			 $val = $this->modelo->lista_clientes($query=false,$ci=$parametros['ci']);
			 if(count($val)>0)
			 {
			 	return -2;
			 }else
			 {
				$res = $this->modelo->add($tabla='Clientes',$datos);
				if($res==null)
				{
					return 1;
				}else
				{
					return -1;
				}
			 }
		}else
		{
			$where[0]['campo'] = 'ID';
			$where[0]['valor'] = $parametros['id'];
			return  $this->modelo->update($tabla='Clientes',$datos,$where);
		}

	}

	function delete_clientes($id)
	{
		return $this->modelo->delete_clientes($id);
	}

	function buscar_edi($id)
	{
		$val = $this->modelo->lista_clientes($query=false,$ci=false,$id);
		return $val;
	}

	function guardar_cuentas($parametros)
	{

		// print_r($parametros);die();
		$codigoCliente = $parametros['txt_ci_cuenta'];
		$SubCta = $parametros['SubCta'];
		$Cta_Aux =$parametros['DLCxCxP'];
		$SubmoduloGastoCosto = G_NINGUNO;
		$SubmoduloGastoCosto = G_NINGUNO;
		if($parametros['TxtRetIVAB']=='' || $parametros['TxtRetIVAB']=='.'){$parametros['TxtRetIVAB']=0;}
		if($parametros['TxtRetIVAS']=='' || $parametros['TxtRetIVAS']=='.'){$parametros['TxtRetIVAS']=0;}
		
		if(isset($parametros['DLSubModulo']) && $parametros['DLSubModulo']!='')
		{
			$SubmoduloGastoCosto = $parametros['DLSubModulo'];
		}
		  $CtaGastoCosto = '.';		  
		  if(isset($parametros['cbx_cuenta_g'])){$CtaGastoCosto = $parametros['DLGasto']; }else{$Cta1 = G_NINGUNO;}
		  $encontrado = $this->modelo->Catalogo_CxCxP($Cta_Aux,$codigoCliente,$SubCta);


		  // print_r($encontrado);die();
		 
		  	$datos[0]['campo'] = 'Item';		  	
		  	$datos[0]['dato'] = $_SESSION['INGRESO']['item'];
		  	$datos[1]['campo'] = 'Periodo';		  	
		  	$datos[1]['dato'] = $_SESSION['INGRESO']['periodo'];
		  	$datos[2]['campo'] = 'Codigo';		  	
		  	$datos[2]['dato'] = $codigoCliente;
		  	$datos[3]['campo'] = 'Cta';		  	
		  	$datos[3]['dato'] = $Cta_Aux;
		  	$datos[4]['campo'] = 'TC';		  	
		  	$datos[4]['dato'] = $parametros['SubCta'];
		  	$datos[5]['campo'] = 'Importaciones';		  	
		  	$datos[5]['dato'] = 0;

		  	$datos[6]['campo'] = 'Cta_Gasto';		  	
		  	$datos[6]['dato'] = $CtaGastoCosto;
		  	$datos[7]['campo'] = 'SubModulo';		  	
		  	$datos[7]['dato'] = $SubmoduloGastoCosto;
		  	$datos[8]['campo'] = 'Porc_IVAB';		  	
		  	$datos[8]['dato'] = ($parametros['TxtRetIVAB']/100);
		  	$datos[9]['campo'] = 'Porc_IVAS';		  	
		  	$datos[9]['dato'] = ($parametros['TxtRetIVAS']/100);
		  	$datos[10]['campo'] = 'Cod_Ret';		  	
		  	$datos[10]['dato'] = $parametros['TxtCodRet'];	  		  

		  if(count($encontrado)<=0)
		  {
		  	 return $this->modelo->add($table='Catalogo_CxCxP',$datos);
		  }else
		  {
		  	$where[0]['campo'] = 'ID';
		  	$where[0]['valor'] = $encontrado[0]['ID'];
		  	 return $this->modelo->update($table='Catalogo_CxCxP',$datos,$where);
		  }

		  // If AdoCxCxP.Recordset.RecordCount <= 0 Then
		  //    SetAddNew AdoCxCxP
		  //    SetFields AdoCxCxP, "Item", NumEmpresa
		  //    SetFields AdoCxCxP, "Periodo", Periodo_Contable
		  //    SetFields AdoCxCxP, "Codigo", CodigoCliente
		  //    SetFields AdoCxCxP, "Cta", Cta_Aux
		  //    SetFields AdoCxCxP, "TC", SubCta
		  //    SetFields AdoCxCxP, "Importaciones", 0
		  // Else
		  //    SetFields AdoCxCxP, "Cta_Gasto", CtaGastoCosto
		  //    SetFields AdoCxCxP, "SubModulo", SubmoduloGastoCosto
		  //    SetFields AdoCxCxP, "Porc_IVAB", Val(TxtRetIVAB) / 100
		  //    SetFields AdoCxCxP, "Porc_IVAS", Val(TxtRetIVAS) / 100
		  //    SetFields AdoCxCxP, "Cod_Ret", TxtCodRet
		  // End If
		  // SetUpdate AdoCxCxP
		  // Unload FCxCxP
	}
}

?>