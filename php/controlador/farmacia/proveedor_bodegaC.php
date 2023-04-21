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

if(isset($_GET['cliente_proveedor']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cliente_proveedor($query));
}

if(isset($_GET['historial_direcciones']))
{
	$codigo = $_POST['txtcodigo'];
	echo json_encode($controlador->historial_direcciones($codigo));
}

if(isset($_GET['guardar_datos']))
{
	$datos = $_POST;
	echo json_encode($controlador->guardar_datos($datos));
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
				
		$codig = Digito_Verificador($parametros['ci']);
		// print_r($codig);die();
		if($codig['Tipo_Beneficiario']!='C')
		{
			return -3;
		}
		$datos[4]['campo'] = 'T';
		$datos[4]['dato']='N';
		$datos[5]['campo'] = 'Codigo';
		$datos[5]['dato']=$codig['Codigo_RUC_CI'];
		$datos[6]['campo'] = 'TD';
		$datos[6]['dato']=$codig['Tipo_Beneficiario'];
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

	function cliente_proveedor($query)
	{
		// print_r($query);die();
		// print_r($_SESSION['INGRESO']);die();
		$datos = $this->modelo->cliente_proveedor($query);
		$cli = array();
		foreach ($datos as $key => $value) {
			$cli[] = array('id'=>$value['CI_RUC'],'text'=>$value['Cliente'],'data'=>$value);
		}
		return $cli;
	}

	function historial_direcciones($codigo)
	{
		$datos= $this->modelo->historial_direcciones($codigo);
		$texto = '';
		foreach ($datos as $key => $value) {
			$texto.= $value["Fecha_Registro"]->format('Y-m-d').": ".$value["Ciudad"].", ".$value["Direccion"].". ".$value["Telefono"].'  <br>';
		}
		return $texto;
		// print_r($datos);die();
	}

	function guardar_datos($parametros)
	{
		// print_r($parametros);die();
		$datosC[0]["dato"] = ''; // 1756051494
	    $datosC[0]["campo"] = '';
	    $datosC[1]["dato"] = $parametros["txt_ci_ruc"]; // 1756051494
	    $datosC[1]["campo"] = 'CI_RUC';
	    $datosC[2]["dato"] = $parametros["txt_fax"]; // .
	    $datosC[2]["campo"] = 'FAX';
	    $datosC[3]["dato"] = $parametros["txt_telefono"]; // 02282950
	    $datosC[3]["campo"] = 'Telefono';
	    $datosC[4]["dato"] = $parametros["txt_celular"]; // .
	    $datosC[4]["campo"] = 'Celular';
	    $datosC[5]["dato"] = $parametros["txt_grupo"]; // .
	    $datosC[5]["campo"] = 'Grupo';
	    $datosC[6]["dato"] = $parametros["txt_contactos"]; // .
	    $datosC[6]["campo"] = 'Contacto';
	    $datosC[7]["dato"] = $parametros["txt_descuento"]; // 0
	    $datosC[7]["campo"] = 'Descuento';
	    $datosC[8]["dato"] = $parametros["txt_cliente"]; // ABADIANO SANCHEZ ARIEL SEBASTIAN
	    $datosC[8]["campo"] = 'Cliente';
	    $datosC[9]["dato"] = $parametros["CTipoProv"]; // 01
	    $datosC[9]["campo"] = 'Tipo_Pasaporte';
	    $datosC[10]["dato"] = $parametros["CParteR"]; // NO
	    $datosC[10]["campo"] = 'Parte_Relacionada';
	    $datosC[11]["dato"] = $parametros["rbl_sexo"]; // on
	    $datosC[11]["campo"] = 'Sexo';
	    $datosC[12]["dato"] = $parametros["txt_direccion"]; // .
	    $datosC[12]["campo"] = 'Direccion';
	    $datosC[13]["dato"] = $parametros["txt_numero"]; // .
	    $datosC[13]["campo"] = 'DirNumero';
	    $datosC[14]["dato"] = $parametros["txt_email"]; // comprobantes@clinicasantabarbara.com.ec
	    $datosC[14]["campo"] = 'Email';
	    $datosC[15]["dato"] = $parametros["ddl_naciones"]; // 593
	    $datosC[15]["campo"] = 'Pais';
	    $datosC[16]["dato"] = $parametros["prov"]; // 17
	    $datosC[16]["campo"] = 'Prov';
	    $datosC[17]["dato"] = $parametros["ddl_ciudad"]; // 21701
	    $datosC[17]["campo"] = 'Ciudad';
	    $datosC[18]["dato"] = $parametros["MBFecha"]; // 2022-02-14
	    $datosC[18]["campo"] = 'Fecha';
	    $datosC[19]["dato"] = $parametros["MBFechaN"]; // 2022-02-14
	    $datosC[19]["campo"] = 'Fecha_N';
	    $datosC[20]["dato"] = $parametros["txt_representante"]; // .
	    $datosC[20]["campo"] = 'Representante';
	    $datosC[21]["dato"] = $parametros["ddl_estado_civil"]; // S
	    $datosC[21]["campo"] = 'Est_Civil';
	    $datosC[22]["dato"] = $parametros["txt_no_dep"]; // 0
	    $datosC[22]["campo"] = 'No_Dep';
	    $datosC[23]["dato"] = $parametros["txt_casilla"]; // .
	    $datosC[23]["campo"] = 'Casilla';
	    $datosC[24]["dato"] = $parametros["txt_comision"]; // 0
	    $datosC[24]["campo"] = 'Comision';
	    $datosC[25]["dato"] = $parametros["ddl_medidor"]; // Seleccione
	    $datosC[25]["campo"] = 'Medidor'; //ojo no hay
	    $datosC[26]["dato"] = $parametros["txt_Email2"]; // .
	    $datosC[26]["campo"] = 'Email2';
	    $datosC[27]["dato"] = $parametros["txt_afiliacion"]; // .
	    $datosC[27]["campo"] = 'Plan_Afiliado';
	    $datosC[28]["dato"] = $parametros["txt_actividad"]; // .
	    $datosC[28]["campo"] = 'Actividad';
	    $datosC[29]["dato"] = $parametros["txt_credito"]; // 0
	    $datosC[29]["campo"] = 'Credito';
	    $datosC[30]["dato"] = $parametros["txt_profesion"]; // .
	    $datosC[30]["campo"] = 'Profesion';
	    $datosC[31]["dato"] = $parametros["txt_lugar_trabajo"]; // .
	    $datosC[31]["campo"] = 'Lugar_Trabajo';
	    $datosC[32]["dato"] = $parametros["txt_direccion_tra"]; // .
	    $datosC[32]["campo"] = 'DireccionT';
	    $datosC[33]["dato"] = $parametros["txt_califica"]; // .
	    $datosC[33]["campo"] = 'Calificacion';
	    $datosC[34]["dato"] = 0;
		$datosC[34]["campo"] = 'Especial';
		$datosC[35]["dato"] = 0;
		$datosC[35]["campo"] = 'RISE';
		$datosC[36]["dato"] = 0;
		$datosC[36]["campo"] = 'Asignar_Dr';

		$datosC[37]["dato"] = 'Codigo';
		$datosC[37]["campo"] = $parametros['TD'];
		$datosC[38]["dato"] = 'TD';
		$datosC[38]["campo"] = $parametros['txt_codigo'];


	    if(isset($parametros['cbx_ContEsp']))
	    {
	    	$datosC[34]["dato"] = 1;
		    $datosC[34]["campo"] = 'Especial';
	    }
	    if(isset($parametros['cbx_rise']))
	    {
	    	$datosC[35]["dato"] = 1;
		    $datosC[35]["campo"] = 'RISE';
	    }
	    if(isset($parametros['cbx_dr']))
	    {
	    	$datosC[36]["dato"] = 1;
		    $datosC[36]["campo"] = 'Asignar_Dr';
	    }

	    $this->guardar_historial($parametros);

	    if($parametros['txt_id']!='')
	    {
	    	$where[0]['campo'] = 'ID'; 
	    	$where[0]['valor'] = $parametros['txt_id'];
	    	return update_generico($datosC,'Clientes',$where);
	    }else
	    {
	    	$resp = $this->modelo->buscar_cliente(trim($parametros['txt_ci_ruc']),false,true);
		    if(count($resp)>0){return -2;}
		
	    	$r = insert_generico('Clientes',$datosC);
	    	if($r==null)
	    	{
	    		return 1;
	    	}else
	    	{
	    		return -1;
	    	}
	    }



	    // $datosC[34]["dato"] = $parametros["txt_historial_dir"]; // 
	    // $datosC[0]["campo"] = '';
	    // $datosC[34]["dato"] = $parametros["txt_productos_rela"]; // 
	    // $datosC[0]["campo"] = '';
	    // $datosC[0]["campo"] = '';
	    

	}


	function guardar_historial($parametros)
	{
		 // 'Ingresamos el historial de direcciones

		// print_r($parametros);die();
		$Si_No = False;
  		$datos = $this->modelo->buscar_historial($parametros['txt_codigo']);
  		// print_r($datos);
  		// print_r($parametros);die();
  		if(count($datos)>0)
  		{
  		   if($datos[0]["Lugar_Trabajo"] <> $parametros["txt_lugar_trabajo"]) { $Si_No = True;}
	       if($datos[0]["Direccion"] <> $parametros["txt_direccion"] ) { $Si_No = True;}
	       if($datos[0]["DireccionT"] <> $parametros["txt_direccion_tra"] ) { $Si_No = True;}
	       if($datos[0]["TelefonoT"] <> $parametros["txt_telefono2"] ) { $Si_No = True;}
	       if($datos[0]["Telefono"] <> $parametros["txt_telefono"] ) { $Si_No = True;}
	       if($datos[0]["Celular"] <> $parametros["txt_celular"] ) { $Si_No = True;}
	       if($datos[0]["FAX"] <> $parametros["txt_fax"] ) { $Si_No = True;}
	       if($datos[0]["Ciudad"] <> $parametros["ddl_ciudad"] ) { $Si_No = True;}
	       if($datos[0]["Descuento"] <> $parametros["txt_descuento"] ) { $Si_No = True;}

  		}else
  		{
  			 $Si_No = True;
  		}

  		// print_r($Si_No);die();

	  if($Si_No){
	  	// print_r($datos);
  		// print_r($parametros);die();
	     $datosH[0]['campo'] =  "Fecha_Registro"; 
	     $datosH[0]['dato'] =  date('Y-m-d');
	     $datosH[1]['campo'] =  "Codigo"; 
	     $datosH[1]['dato'] = $parametros["txt_codigo"];
	     $datosH[2]['campo'] =  "Lugar_Trabajo"; 
	     $datosH[2]['dato'] = $parametros["txt_lugar_trabajo"];
	     $datosH[3]['campo'] =  "Direccion"; 
	     $datosH[3]['dato'] = $parametros["txt_direccion"];
	     $datosH[4]['campo'] =  "DireccionT"; 
	     $datosH[4]['dato'] = $parametros["txt_direccion_tra"];
	     $datosH[5]['campo'] =  "TelefonoT"; 
	     $datosH[5]['dato'] = $parametros["txt_telefono2"];
	     $datosH[6]['campo'] =  "Telefono"; 
	     $datosH[6]['dato'] = $parametros["txt_telefono"] ;
	     $datosH[7]['campo'] =  "Celular"; 
	     $datosH[7]['dato'] = $parametros["txt_celular"];
	     $datosH[8]['campo'] =  "FAX"; 
	     $datosH[8]['dato'] = $parametros["txt_fax"];
	     $datosH[9]['campo'] =  "Ciudad"; 
	     $datosH[9]['dato'] = $parametros["ddl_ciudad"];
	     $datosH[10]['campo'] =  "Prov"; 
	     $datosH[10]['dato'] = $parametros["prov"];
	     $datosH[11]['campo'] =  "Pais"; 
	     $datosH[11]['dato'] = $parametros["ddl_naciones"];
	     $datosH[12]['campo'] =  "CodigoU"; 
	     $datosH[12]['dato'] = $_SESSION['INGRESO']['CodigoU'];
	     $datosH[13]['campo'] =  "Descuento"; 
	     $datosH[13]['dato'] = $parametros["txt_descuento"];
	     $datosH[14]['campo'] =  "Item"; 
	     $datosH[14]['dato'] = $_SESSION['INGRESO']['item'];
	     $datosH[15]['campo'] =  "Tipo_Dato"; 
	     $datosH[15]['dato'] = "DIRECCION1";

  		// print_r($datosH);die();	     // 

	     $resp = insert_generico('Clientes_Datos_Extras',$datosH);
	  }

	
	}
}
?>