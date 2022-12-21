<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/incomM.php');
include(dirname(__DIR__,2).'/comprobantes/SRI/autorizar_sri.php');
date_default_timezone_set('America/Guayaquil'); 
/**
 * 
 */
$controlador = new incomC();
if(isset($_GET['beneficiario']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario($query));
}
if(isset($_GET['beneficiario_C']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario_C($query));
}
if(isset($_GET['beneficiario_p']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario_pro($query));
}
if(isset($_GET['cuentas_efectivo']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cuentas_efectivo($query));
}
if(isset($_GET['ListarAsientoB']))
{
	echo json_encode($controlador->ListarAsientoB());
}
if(isset($_GET['cuentas_banco']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cuentas_banco($query));
}
if(isset($_GET['cuentasTodos']))
{
	$query = '';
    $ti='';
    $tipo = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
         if(count($query)>1)
        {
            $query = $query['term'];
            if($query =='*')
            {
                $query = '';
            }
        }else{$query ='';}

        $ti = $_GET['tip'];
	}
    if(isset($_GET['q1']))
    {
        $query = $_GET['q1'];
        $tipo = '1';
    }
	echo json_encode($controlador->cuentas_Todos($query,$tipo,$ti));
}

if(isset($_GET['asientoB']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->InsertarAsientoBanco($parametros));
}
if(isset($_GET['EliAsientoB']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_asientoB($parametros));
}
if(isset($_GET['EliAsientoBTodos']))
{
	echo json_encode($controlador->delete_asientoBTodos());
}
if(isset($_GET['tabs_contabilidad']))
{
	echo json_encode($controlador->cargar_tablas());
}
if(isset($_GET['tabs_sc']))
{
	echo json_encode($controlador->cargar_tablas_sc());
}
if(isset($_GET['tabs_sc_modal']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_tablas_sc_modal($parametros));
}

if(isset($_GET['tabs_retencion']))
{
	echo json_encode($controlador->cargar_tablas_retencion());
}
if(isset($_GET['tabs_tab4']))
{
	echo json_encode($controlador->cargar_tablas_tab4());
}
if(isset($_GET['subcuentas']))
{
	$parametros = $_POST['parametros'];
	echo json_decode($controlador->listar_subcuentas($parametros));
}
if(isset($_GET['TipoCuenta']))
{
	$codigo = $_POST['codigo'];
	echo json_encode($controlador->LeerCta($codigo));
}

if(isset($_GET['modal_generar_sc']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_generar_asiento_SC($parametros));
}
if(isset($_GET['modal_ingresar_asiento']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_ingresar_asiento($parametros));
}
if(isset($_GET['modal_limpiar_asiento']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_subcta_limpiar($parametros));
}
if(isset($_GET['eliminar_retenciones']))
{
    echo json_encode($controlador->eliminar_retenciones());
}
if(isset($_GET['modal_detalle_aux']))
{
	if(!isset($_GET['q'])){$_GET['q'] = '';}
	$parametros = array(
    		'tc'=>$_GET['tc'],
    		'OpcDH'=>$_GET['OpcDH'],
    		'OpcTM'=>$_GET['OpcTM'],
    		'cta'=>$_GET['cta'],
    		'query'=>$_GET['q']);
	echo json_encode($controlador->detalle_aux_submodulo($parametros));
}

if(isset($_GET['modal_subcta_catalogo']))
{

	if(!isset($_GET['q']))
	{
		$_GET['q'] = '';
	}

	$parametros = array(
    		'tc'=>$_GET['tc'],
    		'OpcDH'=>$_GET['OpcDH'],
    		'OpcTM'=>$_GET['OpcTM'],
    		'cta'=>$_GET['cta'],
    		'query'=>$_GET['q']);
	echo json_encode($controlador->catalogo_subcta($parametros));
	
}
if(isset($_GET['totales_asientos']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_de_asientos());
}
if(isset($_GET['generar_comprobante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_comprobante($parametros));
}
if(isset($_GET['eliminarregistro']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_registro($parametros));
}

if(isset($_GET['num_comprobante']))
{    
    $parametros = $_POST['parametros'];

    // print_r($parametros);die();
    echo json_encode(numero_comprobante1($parametros['tip'],true,false,$parametros['fecha']));
}
if(isset($_GET['generar_xml']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->SRI_Crear_Clave_Acceso_Retenciones($parametros));
}

if(isset($_GET['borrar_asientos']))
{
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->borrar_asientos());
}
if(isset($_GET['listar_comprobante']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listar_comprobante($parametros));
}
if(isset($_GET['Tipo_De_Comprobante_No']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Tipo_De_Comprobante_No($parametros));
}
if(isset($_GET['Llenar_Encabezado_Comprobante']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Llenar_Encabezado_Comprobante($parametros));
}

if(isset($_GET['ing1']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ingresar_asiento($parametros));
}
if(isset($_GET['eliminar_asientos']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_asientos($parametros));
}



class incomC
{
	private $modelo;
    private $sri;	
	function __construct()
	{
		$this->modelo = new incomM();
        $this->sri = new autorizacion_sri();
	}

	function cargar_beneficiario($query)
	{
		$datos = $this->modelo->beneficiarios($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cargar_beneficiario_C($query)
	{
		$datos = $this->modelo->beneficiarios_c($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cargar_beneficiario_pro($query)
	{
		$datos = $this->modelo->beneficiarios_pro($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'].'-'.$value['TD'].'-'.$value['CI_RUC'].'-'.$value['Codigo'],'text'=>$value['nombre']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cuentas_efectivo($query)
	{
		$datos = $this->modelo->cuentas_efectivo($query);
		$cuenta = array();
		foreach ($datos as $key => $value) {
			$cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);//para produccion
		}
		return $cuenta;

	}



	function cuentas_banco($query)
	{
		$datos = $this->modelo->cuentas_banco($query);
		$cuenta = array();
		foreach ($datos as $key => $value) {
			$cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);//para produccion
		}
		return $cuenta;

	}

	function cuentas_Todos($query,$tipo,$tipoCta)
	{
		$datos = $this->modelo->cuentas_todos($query,$tipo,$tipoCta);
		$cuenta = array();
		foreach ($datos as $key => $value) {
            if($tipo=='')
            {
                $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cuenta']);
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cuenta']);//para produccion
            }else
            {                
                $cuenta[] = array('value'=>$value['Codigo'],'label'=>$value['Nombre_Cuenta']);
            }
		}
		return $cuenta;

	}

	function InsertarAsientoBanco($parametros)
	{
		// print_r($parametros);die();
		// $datos = $this->modelo->cargar_asientosB();
        $datos[0]['campo']= "ME";
        $datos[1]['campo']= "CTA_BANCO"; 
        $datos[2]['campo']= "BANCO";
        $datos[3]['campo']= "CHEQ_DEP";
        $datos[4]['campo']= "EFECTIVIZAR";
        $datos[5]['campo']= "VALOR";
        $datos[6]['campo']= "T_No"; 
        $datos[7]['campo']= "Item";
        $datos[8]['campo']= "CodigoU";
		
		$datos[0]['dato']= 0;
		$datos[1]['dato']= $parametros['banco']; 
		$datos[2]['dato']= $parametros['bancoC']; 
		$datos[3]['dato']= $parametros['cheque']; 
		$datos[4]['dato']= $parametros['fecha']; 
		$datos[5]['dato']= $parametros['valor']; 
		$datos[6]['dato']= 1;  
		$datos[7]['dato']= $_SESSION['INGRESO']['item'];
		$datos[8]['dato']= $_SESSION['INGRESO']['CodigoU'];

		$resp = $this->modelo->insertar_ingresos($datos);
        if($resp == '')
        {
    	    return 1;
        }else
        {
    	    return -1;
        }
	}

	function delete_asientoB($parametros)
	{
		$cta = $parametros['cta'];
		$cheq = $parametros['cheque'];
		$resp = $this->modelo->delete_asientoB($cta,$cheq);
		if($resp == 1)
		{
			return 1; 
		}else
		{
			return -1;
		}
	}
	function delete_asientoBTodos()
	{
		$resp = $this->modelo->delete_asientoBTodos();
		if($resp == 1)
		{
			return 1; 
		}else
		{
			return -1;
		}
	}

	function cargar_tablas()
	{
		$asiento= $this->modelo->DG_asientos();
		return $asiento;   
	}

	function cargar_tablas_sc()
	{
		$sc= $this->modelo->DG_asientos_SC();
		return $sc;   
	}

	function cargar_tablas_retencion()
	{
		$b= $this->modelo->DG_AC();
		$r= $this->modelo->DG_asientoR();		

		return array('b'=>$b,'r'=>$r['tbl'],'datos'=>$r['datos']);
	}
	function cargar_tablas_tab4()
	{
		// $AC= $this->modelo->DG_AC();
		$AV= $this->modelo->DG_AV();
		$AE= $this->modelo->DG_AE();
		$AI= $this->modelo->DG_AI();
		return $AV.$AE.$AI;   
	}

	function LeerCta($CodigoCta)
	{
		$Cuenta = '.';
        $Codigo = '.';
        $TipoCta = "G";
        $SubCta = "N";
        $TipoPago = "01";
        $Moneda_US = False;
		$datos= $this->modelo->LeerCta($CodigoCta);
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				$Codigo = $value["Codigo"];
				$Cuenta = $value["Cuenta"];
				$SubCta = $value["TC"];
				$Moneda_US = $value["ME"];
				$TipoCta = $value["DG"];
				$TipoPago = $value["Tipo_Pago"];
				if (strlen($TipoPago) <= 0){$TipoPago = "01";}
			}
		}
		return array('cuenta'=>$Cuenta,'codigo'=>$Codigo,'tipocta'=>$TipoCta,'subcta'=>$SubCta,'tipopago'=>$TipoPago,'moneda'=>$Moneda_US);
     }


     function catalogo_subcta($parametros)
     {
     	// print_r($parametros);die();
     	if($parametros['tc']=='C' ||  $parametros['tc']== "P" || $parametros['tc']=="CP" )
     	{
     		$datos = $this->modelo->Catalogo_CxCxP($parametros['tc'],$parametros['cta'],$parametros['query']);
     		$ddl =array();
     		foreach ($datos as $key => $value) {
     			$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['NomCuenta']);
     		}
     		return $ddl;
     	}else
     	{
     		$datos_tabla = $this->modelo->catalogo_subcta_grid($parametros['tc'],$parametros['cta'],$parametros['OpcDH'],$parametros['OpcTM']);
     	    $datos = $this->modelo->catalogo_subcta($parametros['tc']);
     	    foreach ($datos as $key => $value) {
     			$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['Detalle']);
     		}
     		return $ddl;     	

     	}
     }

     function detalle_aux_submodulo($parametros)
     {
     	$result = $this->modelo->detalle_aux_submodulo($parametros['query']);
     	return $result;
     }

     function modal_generar_asiento_SC($parametros)
     {
     	// print_r($parametros);die();
     	$parametros_sc = array(
            'be'=>$parametros['ben'],
            'ru'=> '',
            'co'=> $parametros['cta'],// codigo de cuenta cc
            'tip'=>$parametros['tipoc'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
            'tic'=> $parametros['dh'], //debito o credito (1 o 2);
            'sub'=> $parametros['codigo'], //Codigo se trae catalogo subcuenta o ruc del proveedor en caso de que se este ingresando
            'sub2'=>$parametros['ben'],//nombre del beneficiario
            'fecha_sc'=> $parametros['fec'], //fecha 
            'fac2'=>$parametros['fac'],
            'mes'=> $parametros['mes'],
            'valorn'=> round($parametros['val'],2),//valor de sub cuenta 
            'moneda'=> $parametros['tm'], /// moneda 1
            'Trans'=>$parametros['aux'],//detalle que se trae del asiento
            'T_N'=> $_SESSION['INGRESO']['modulo_'],
            't'=> $parametros['tc'],                        
        );

        $resp = ingresar_asientos_SC($parametros_sc);
        if($resp==null)
        {
        	return array('resp'=>1,'total'=>$parametros['val']);
        }else
        {
        	return array('resp'=>-1,'total'=>$parametros['val']);

        }
     }

     function modal_ingresar_asiento($parametros)
     {
     	$valor = $this->modelo->DG_asientos_SC_total($parametros['dh']);
     	$cuenta = $this->modelo->cuentas_todos($parametros['cta'],'',''); 
        $parametros_asiento = array(
				"va" => round($valor[0]['total'],2),
				"dconcepto1" => '.',
				"codigo" => $parametros['cta'],
				"cuenta" => $cuenta[0]['Cuenta'],
				"efectivo_as" => $parametros['fec'],
				"chq_as" =>0,
				"moneda" => $parametros['tm'],
				"tipo_cue" => $parametros['dh'],
				"cotizacion" => 0,
				"con" => 0,
				"t_no" => '1',
				"tc"=>$cuenta[0]['TC'],									
			);
         $resp = ingresar_asientos($parametros_asiento);
         if($resp==1)
         {
         	return 1;
         }else
         {
         	return -1;
         }
     }
     function cargar_tablas_sc_modal($parametros)
     {
     	$datos = $this->modelo->catalogo_subcta_grid($parametros['tc'],$parametros['cta'],$parametros['dh'],$parametros['tm']);
     	// print_r($datos);die();
     	return $datos;
     }
     function modal_subcta_limpiar($parametros)
     {
     	$this->modelo->limpiar_asiento_SC($parametros['tc'],$parametros['cta'],$parametros['dh'],$parametros['tm']);
     }
     function asientos_grabados()
     {
     	$asiento = $this->modelo->asiento();
     	$debe = 0;
     	$haber = 0;
     	foreach ($asiento as $key => $value) {
     		$debe+=$value['DEBE'];
     		$haber+=$value['HABER'];
     	}
     	if(($debe-$haber)<>0)
     	{
     		return 2;//Las transacciones no cuadran correctamente  "corrija los resultados de las cuentas"
     	}
     	$asiento_sc = $this->modelo->asiento_sc();
     }

     function datos_de_asientos()
     {
     	$asiento = $this->modelo->asientos();
     	$debe = 0;
     	$haber = 0;
     	$Ctas_Modificar = '';
     	foreach ($asiento as $key => $value) {
     		$debe+=$value['DEBE'];
     		$haber+=$value['HABER'];
     		$Ctas_Modificar.= $value['CODIGO'].',';
     	}
     	return array('debe'=>$debe,'haber'=>$haber,'diferencia'=>$debe-$haber,'Ctas_Modificar'=>$Ctas_Modificar);

     }
     function generar_comprobante($parametros)
     {
     	$Autorizacion_LC=''; //revisar
     	$T_No='01';
         if($parametros['tip']=='CD'){$tip = 'Diario';}
         else if($parametros['tip']=='CI'){$tip = 'Ingresos';}
         else if($parametros['tip']=='CE'){$tip = 'Egresos';}
         else if($parametros['tip']=='ND'){$tip = 'NotaDebito';}
         else if($parametros['tip']=='NC'){$tip= 'NotaCredito';}

         if($parametros['modificado']==0)
         {
         	$num_com = numero_comprobante1($tip,true,true,$parametros['fecha']);
         }else
         {
         	$num_com = explode('-',$parametros['num_com']);
         	$num_com = $num_com[1];
         }
         // $num_com = '123654789';

     	$parametro_comprobante = array(
            'ru'=> $parametros['ruc'], //codigo del cliente que sale co el ruc del beneficiario codigo
            'tip'=>$parametros['tip'],//tipo de cuenta contable cd, etc
            "fecha1"=> $parametros['fecha'],// fecha actual 2020-09-21
            'concepto'=>$parametros['concepto'], //detalle de la transaccion realida
            'totalh'=> $parametros['totalh'], //total del haber
            'num_com'=> '.'.date('Y', strtotime($parametros['fecha'])).'-'.$num_com, // codigo de comprobante de esta forma 2019-9000002
            );



				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();

            // $cod = explode('-',$parametros['num_com']);
            // print_r($cod);die();

          $Autorizacion_LC=  $parametros['Autorizacion_LC'];
          $TP = $parametros['tip'];
     	  $T = $parametros['T'];
     	  $Fecha = $parametros['fecha'];
     	  $Numero = $num_com;
          $ClaveAcceso = G_NINGUNO;
          $RUC_CI = $parametros['ruc'];
          $CodigoB = $parametros['CodigoB'];
          $Serie_R=  $parametros['Serie_R'];
          $Retencion=  $parametros['Retencion'];
          $Autorizacion_R=  $parametros['Autorizacion_R'];
          $Beneficiario = $parametros['bene'];
          $TD = $parametros['TD'];
          $Email = $parametros['email'];
          $Ctas_Modificar = substr($parametros['Cta_modificar'], 0 ,-1);

          // print_r($parametros);die();
     	 if (strlen($Autorizacion_LC) >= 13){$Autorizacion_LC = ReadSetDataNum("LC_SERIE_".$Serie_LC, True, True);}

     	 if(strlen($Autorizacion_R)>=13){
     	 $reg = $this->modelo->Asiento_Air_Com($Autorizacion_R,$T_No);

     	 // print_r($reg);die();
     	 $RetNueva =false;
     	 if(count($reg) >0 && $RetNueva && $RetSecuencial)
     	 {
     	 	$retencion = ReadSetDataNum("RE_SERIE_".$Serie_R, True, True);
     	 }
     	}
     	 

        
          // 'Actualizar las Ctas a mayoriazar
          $res= $this->modelo->Actualizar_Ctas_a_mayorizar($TP,$num_com);
          	 // print_r($res);die();

          if(count($res)>0)
          {
          	foreach ($res as $key => $value) {
          		$this->modelo->Actualiza_Procesado_Kardex($value["Codigo_Inv"]);
          	}
          }

          $this->modelo->EliminarComprobantes($TP,$Numero);
          
          // ' Por Bodegas
          $ConBodegas = False;
          if(count($this->modelo->Por_Bodegas())>1)
          {
          	$ConBodegas = True;
          }



          // ' Grabamos SubCtas
          $reg = $this->modelo->Grabamos_SubCtas($T_No);

          // print_r($reg);die();

          if(count($reg)>0)
          {
          	foreach ($reg as $key => $value) {
          		$Valor = $value["Valor"];
                $Valor_ME = $value["Valor_ME"];
                $Codigo = $value["Codigo"];
                $TipoCta = $value["TC"];
                $OpcDH = intval($value["DH"]);
                $Factura_No = $value["Factura"];
                $Fecha_Vence = $value["FECHA_V"];
                $Cta_Cobrar = trim($value["Cta"]);
                if($Valor <> 0 || $Valor_ME <> 0)
                {
                    $datos[0]['campo']="T";
                    $datos[0]['dato']=  $T;
                    $datos[1]['campo']="TP";
                    $datos[1]['dato']=  $TP;
                    $datos[2]['campo']="Numero";
                    $datos[2]['dato']=  $Numero;
                    $datos[3]['campo']="Fecha";
                    $datos[3]['dato']=  $Fecha;
                    $datos[4]['campo']="Item";
                    $datos[4]['dato']=  $_SESSION['INGRESO']['item'];
                    $datos[5]['campo']="TC";
                    $datos[5]['dato']=  $TipoCta;
                    $datos[6]['campo']="Cta";
                    $datos[6]['dato']=  $Cta_Cobrar;
                    $datos[7]['campo']="Codigo";
                    $datos[7]['dato']=  $Codigo;
                    $datos[8]['campo']="Fecha_V";
                    $datos[8]['dato']=  $Fecha_Vence->format('Y-m-d');
                    $datos[9]['campo']="Factura";
                    $datos[9]['dato']=  $Factura_No;
                    $datos[10]['campo']="Detalle_SubCta";
                    $datos[10]['dato']=  $value["Detalle_SubCta"];
                    $datos[11]['campo']="Prima";
                    $datos[11]['dato']=  $value["Prima"];
                    if($OpcDH == 1)
                    {
                    	$datos[12]['campo']="Debitos";
                    	$datos[12]['dato']= $Valor;
                    	$datos[13]['campo']="Parcial_ME";
                    	$datos[13]['dato']= $Valor_ME;
                    }else
                    {
                        $datos[12]['campo']="Creditos";
                        $datos[12]['dato']= $Valor;
                        $datos[13]['campo']="Parcial_ME";
                        $datos[13]['dato']= '-'.$Valor_ME;
                    }

                    $datos[14]['campo']="CodigoU";
                    $datos[14]['dato']= $_SESSION['INGRESO']['CodigoU'];
                    // $NumTrans = $NumTrans + 1;
                    //funcion para actualizar o ingresar aqui

                     // print_r($datos);die();
                }
          	 $resp = $this->modelo->insertar_ingresos_tabla("Trans_SubCtas",$datos);
          	}
          }


           // 'RETENCIONES COMPRAS
          $rc = $this->modelo->RETENCIONES_COMPRAS($T_No);

          // print_r($rc);die();

          if(count($rc)>0)
          {
          	foreach ($rc as $key => $value) {
          		// 'Generacion de la Retencion si es Electronica
                $FechaTexto = $value["FechaRegistro"]->format('Y-m-d');
                $CodSustento = generaCeros($value["CodSustento"],2);
                // SetAdoAddNew "Trans_Compras"
                $datosC[0]['campo']= "IdProv"; 
                $datosC[0]['dato']= $CodigoB;
                $datosC[1]['campo']= "DevIva"; 
                $datosC[1]['dato']= $value["DevIva"];
                $datosC[2]['campo']= "CodSustento"; 
                $datosC[2]['dato']= $value["CodSustento"];
                $datosC[3]['campo']= "TipoComprobante"; 
                $datosC[3]['dato']= $value["TipoComprobante"];
                $datosC[4]['campo']= "Establecimiento"; 
                $datosC[4]['dato']= $value["Establecimiento"];
                $datosC[5]['campo']= "PuntoEmision"; 
                $datosC[5]['dato']= $value["PuntoEmision"];
                $datosC[6]['campo']= "Secuencial"; 
                $datosC[6]['dato']= $value["Secuencial"];
                $datosC[7]['campo']= "Autorizacion"; 
                $datosC[7]['dato']= $value["Autorizacion"];
                $datosC[8]['campo']= "FechaEmision"; 
                $datosC[8]['dato']= $value["FechaEmision"]->format('Y-m-d');
                $datosC[9]['campo']= "FechaRegistro"; 
                $datosC[9]['dato']= $value["FechaRegistro"]->format('Y-m-d');;
                $datosC[10]['campo']= "FechaCaducidad"; 
                $datosC[10]['dato']= $value["FechaCaducidad"]->format('Y-m-d');;
                $datosC[11]['campo']= "BaseNoObjIVA"; 
                $datosC[11]['dato']= number_format($value["BaseNoObjIVA"],2,'.','');
                $datosC[12]['campo']= "BaseImponible"; 
                $datosC[12]['dato']= number_format($value["BaseImponible"],2,'.','');
                $datosC[13]['campo']= "BaseImpGrav"; 
                $datosC[13]['dato']= number_format($value["BaseImpGrav"],2,'.','');
                $datosC[14]['campo']= "PorcentajeIva"; 
                $datosC[14]['dato']= $value["PorcentajeIva"];
                $datosC[15]['campo']= "MontoIva"; 
                $datosC[15]['dato']= number_format($value["MontoIva"],2,'.','');
                $datosC[16]['campo']= "BaseImpIce"; 
                $datosC[16]['dato']= number_format($value["BaseImpIce"],2,'.','');
                $datosC[17]['campo']= "PorcentajeIce"; 
                $datosC[17]['dato']= $value["PorcentajeIce"];
                $datosC[18]['campo']= "MontoIce"; 
                $datosC[18]['dato']= number_format($value["MontoIce"],2,'.','');
                $datosC[19]['campo']= "MontoIvaBienes"; 
                $datosC[19]['dato']= number_format($value["MontoIvaBienes"],2,'.','');
                $datosC[20]['campo']= "PorRetBienes"; 
                $datosC[20]['dato']= number_format($value["PorRetBienes"],2,'.','');
                $datosC[21]['campo']= "ValorRetBienes"; 
                $datosC[21]['dato']= number_format($value["ValorRetBienes"],2,'.','');
                $datosC[22]['campo']= "MontoIvaServicios"; 
                $datosC[22]['dato']= number_format($value["MontoIvaServicios"],2,'.','');
                $datosC[23]['campo']= "PorRetServicios"; 
                $datosC[23]['dato']= $value["PorRetServicios"];
                $datosC[24]['campo']= "ValorRetServicios"; 
                $datosC[24]['dato']= $value["ValorRetServicios"];
                $datosC[25]['campo']= "Porc_Bienes"; 
                $datosC[25]['dato']= $value["Porc_Bienes"];
                $datosC[26]['campo']= "Porc_Servicios"; 
                $datosC[26]['dato']= $value["Porc_Servicios"];
                $datosC[27]['campo']= "Cta_Servicio"; 
                $datosC[27]['dato']= $value["Cta_Servicio"];
                $datosC[28]['campo']= "Cta_Bienes"; 
                $datosC[28]['dato']= $value["Cta_Bienes"];
                $datosC[29]['campo']= "Linea_SRI"; 
                $datosC[29]['dato']= 0;
                $datosC[30]['campo']= "DocModificado"; 
                $datosC[30]['dato']= $value["DocModificado"];
                $datosC[31]['campo']= "FechaEmiModificado"; 
                $datosC[31]['dato']= $value["FechaEmiModificado"]->format('Y-m-d');
                $datosC[32]['campo']= "EstabModificado"; 
                $datosC[32]['dato']= $value["EstabModificado"];
                $datosC[33]['campo']= "PtoEmiModificado"; 
                $datosC[33]['dato']= $value["PtoEmiModificado"];
                $datosC[34]['campo']= "SecModificado"; 
                $datosC[34]['dato']= $value["SecModificado"];
                $datosC[35]['campo']= "AutModificado"; 
                $datosC[35]['dato']= $value["AutModificado"];
                $datosC[36]['campo']= "ContratoPartidoPolitico"; 
                $datosC[36]['dato']= $value["ContratoPartidoPolitico"];
                $datosC[37]['campo']= "MontoTituloOneroso"; 
                $datosC[37]['dato']= number_format($value["MontoTituloOneroso"],2,'.','');
                $datosC[38]['campo']= "MontoTituloGratuito"; 
                $datosC[38]['dato']= number_format($value["MontoTituloGratuito"],2,'.','');
                $datosC[39]['campo']= "PagoLocExt"; 
                $datosC[39]['dato']= $value["PagoLocExt"];
                $datosC[40]['campo']= "PaisEfecPago"; 
                $datosC[40]['dato']= $value["PaisEfecPago"];
                $datosC[41]['campo']= "AplicConvDobTrib"; 
                $datosC[41]['dato']= $value["AplicConvDobTrib"];
                $datosC[42]['campo']= "PagExtSujRetNorLeg"; 
                $datosC[42]['dato']= $value["PagExtSujRetNorLeg"];
                $datosC[43]['campo']= "FormaPago"; 
                $datosC[43]['dato']= $value["FormaPago"];
                $datosC[44]['campo']= "Serie_Retencion"; 
                $datosC[44]['dato']= $Serie_R;
                $datosC[45]['campo']= "SecRetencion"; 
                $datosC[45]['dato']= $Retencion;
                $datosC[46]['campo']= "AutRetencion"; 
                $datosC[46]['dato']= $Autorizacion_R;
                $datosC[47]['campo']= "Clave_Acceso"; 
                $datosC[47]['dato']= G_NINGUNO;
                $datosC[48]['campo']= "T"; 
                $datosC[48]['dato']= G_NORMAL;
                $datosC[49]['campo']= "TP"; 
                $datosC[49]['dato']= $TP;
                $datosC[50]['campo']= "Numero"; 
                $datosC[50]['dato']= $Numero;
                $datosC[51]['campo']= "Fecha"; 
                $datosC[51]['dato']= $Fecha;
                $datosC[52]['campo']= "Item"; 
                $datosC[52]['dato']= $_SESSION['INGRESO']['item'];
                $datosC[53]['campo']= "CodigoU"; 
                $datosC[53]['dato']= $_SESSION['INGRESO']['CodigoU'];

                // print_r($datosC);die();
           $resp = $this->modelo->insertar_ingresos_tabla("Trans_Compras",$datosC);
                // SetAdoUpdate
          	}
          }

          // print_r($resp);die();

          // ' RETENCIONES VENTAS
           $rv = $this->modelo->RETENCIONES_VENTAS($T_No);    

                // print_r($rv);die();      
          if(count($rv)>0)
          {
          	foreach ($rv as $key => $value) {
          		$FechaTexto = $value["FechaRegistro"]->format('Y-m-d');
                // SetAdoAddNew "Trans_Ventas"
                $datosV[0]['campo']= "IdProv"; 
                $datosV[0]['dato']=$CodigoB;
                $datosV[1]['campo']= "TipoComprobante"; 
                $datosV[1]['dato']=$value["TipoComprobante"];
                $datosV[2]['campo']= "FechaRegistro"; 
                $datosV[2]['dato']=$value["FechaRegistro"];
                $datosV[3]['campo']= "FechaEmision"; 
                $datosV[3]['dato']=$value["FechaEmision"];
                $datosV[4]['campo']= "Establecimiento"; 
                $datosV[4]['dato']=$value["Establecimiento"];
                $datosV[5]['campo']= "PuntoEmision"; 
                $datosV[5]['dato']=$value["PuntoEmision"];
                $datosV[6]['campo']= "Secuencial"; 
                $datosV[6]['dato']=$value["Secuencial"];
                $datosV[7]['campo']= "NumeroComprobantes"; 
                $datosV[7]['dato']=$value["NumeroComprobantes"];
                $datosV[8]['campo']= "BaseImponible"; 
                $datosV[8]['dato']=$value["BaseImponible"];
                $datosV[9]['campo']= "IvaPresuntivo"; 
                $datosV[9]['dato']=$value["IvaPresuntivo"];
                $datosV[10]['campo']= "BaseImpGrav"; 
                $datosV[10]['dato']=$value["BaseImpGrav"];
                $datosV[11]['campo']= "PorcentajeIva"; 
                $datosV[11]['dato']=$value["PorcentajeIva"];
                $datosV[12]['campo']= "MontoIva"; 
                $datosV[12]['dato']=$value["MontoIva"];
                $datosV[13]['campo']= "BaseImpIce"; 
                $datosV[13]['dato']=$value["BaseImpIce"];
                $datosV[14]['campo']= "PorcentajeIce"; 
                $datosV[14]['dato']=$value["PorcentajeIce"];
                $datosV[15]['campo']= "MontoIce"; 
                $datosV[15]['dato']=$value["MontoIce"];
                $datosV[16]['campo']= "MontoIvaBienes"; 
                $datosV[16]['dato']=$value["MontoIvaBienes"];
                $datosV[17]['campo']= "PorRetBienes"; 
                $datosV[17]['dato']=$value["PorRetBienes"];
                $datosV[18]['campo']= "ValorRetBienes"; 
                $datosV[18]['dato']=$value["ValorRetBienes"];
                $datosV[19]['campo']= "MontoIvaServicios"; 
                $datosV[19]['dato']=$value["MontoIvaServicios"];
                $datosV[20]['campo']= "PorRetServicios"; 
                $datosV[20]['dato']=$value["PorRetServicios"];
                $datosV[21]['campo']= "ValorRetServicios"; 
                $datosV[21]['dato']=$value["ValorRetServicios"];
                $datosV[22]['campo']= "RetPresuntiva"; 
                $datosV[22]['dato']=$value["RetPresuntiva"];
                $datosV[23]['campo']= "Porc_Bienes"; 
                $datosV[23]['dato']=$value["Porc_Bienes"];
                $datosV[24]['campo']= "Porc_Servicios"; 
                $datosV[24]['dato']=$value["Porc_Servicios"];
                $datosV[25]['campo']= "Cta_Servicio"; 
                $datosV[25]['dato']=$value["Cta_Servicio"];
                $datosV[26]['campo']= "Cta_Bienes"; 
                $datosV[26]['dato']=$value["Cta_Bienes"];
                $datosV[27]['campo']= "Tipo_Pago"; 
                $datosV[27]['dato']=$value["Tipo_Pago"];
                $datosV[28]['campo']= "Linea_SRI"; 
                $datosV[28]['dato']=0;
                $datosV[29]['campo']= "T"; 
                $datosV[29]['dato']=G_NORMAL;
                $datosV[30]['campo']= "TP"; 
                $datosV[30]['dato']=$TP;
                $datosV[31]['campo']= "Numero"; 
                $datosV[31]['dato']=$Numero;
                $datosV[32]['campo']= "Fecha"; 
                $datosV[32]['dato']=$Fecha;
               // 'Razon Social
               // 'MsgBox C1.Beneficiario
                $datosV[33]['campo']= "RUC_CI"; 
                $datosV[33]['dato']=$RUC_CI;
                $datosV[34]['campo']= "IB"; 
                $datosV[34]['dato']=$TD;
                $datosV[35]['campo']= "Razon_Social"; 
                $datosV[35]['dato']=$Beneficiario;
                // SetAdoUpdate          	
          	}
          	 $resp = $this->modelo->insertar_ingresos_tabla("Trans_Ventas",$datosV);
          }

          // ' RETENCIONES EXPORTACION
          $re = $this->modelo->RETENCIONES_EXPORTACION($T_No);

                // print_r($re);die();  

          if(count($re)>0)
          {
          	foreach ($re as $key => $value) {
          		 // SetAdoAddNew "Trans_Exportaciones"
                 $datosE[0]['campo']= "Codigo"; 
                 $datosE[0]['dato']=$value["Codigo"];
                 $datosE[0]['campo']= "CtasxCobrar"; 
                 $datosE[0]['dato']=$value["CtasxCobrar"];
                 $datosE[0]['campo']= "ExportacionDe"; 
                 $datosE[0]['dato']=$value["ExportacionDe"];
                 $datosE[0]['campo']= "TipoComprobante"; 
                 $datosE[0]['dato']=$value["TipoComprobante"];
                 $datosE[0]['campo']= "FechaEmbarque"; 
                 $datosE[0]['dato']=$value["FechaEmbarque"];
                 $datosE[0]['campo']= "NumeroDctoTransporte"; 
                 $datosE[0]['dato']=$value["NumeroDctoTransporte"];
                 $datosE[0]['campo']= "IdFiscalProv"; 
                 $datosE[0]['dato']=$CodigoB;
                 $datosE[0]['campo']= "ValorFOB"; 
                 $datosE[0]['dato']=$value["ValorFOB"];
                 $datosE[0]['campo']= "DevIva"; 
                 $datosE[0]['dato']=$value["DevIva"];
                 $datosE[0]['campo']= "FacturaExportacion"; 
                 $datosE[0]['dato']=$value["FacturaExportacion"];
                 $datosE[0]['campo']= "ValorFOBComprobante"; 
                 $datosE[0]['dato']=$value["ValorFOBComprobante"];
                 $datosE[0]['campo']= "DistAduanero"; 
                 $datosE[0]['dato']=$value["DistAduanero"];
                 $datosE[0]['campo']= "Anio"; 
                 $datosE[0]['dato']=$value["Anio"];
                 $datosE[0]['campo']= "Regimen"; 
                 $datosE[0]['dato']=$value["Regimen"];
                 $datosE[0]['campo']= "Correlativo"; 
                 $datosE[0]['dato']=$value["Correlativo"];
                 $datosE[0]['campo']= "Verificador"; $datosE[0]['dato']=$value["Verificador"];
                 $datosE[0]['campo']= "Establecimiento"; 
                 $datosE[0]['dato']=$value["Establecimiento"];
                 $datosE[0]['campo']= "PuntoEmision"; 
                 $datosE[0]['dato']=$value["PuntoEmision"];
                 $datosE[0]['campo']= "Secuencial"; 
                 $datosE[0]['dato']=$value["Secuencial"];
                 $datosE[0]['campo']= "Autorizacion"; 
                 $datosE[0]['dato']=$value["Autorizacion"];
                 $datosE[0]['campo']= "FechaEmision"; 
                 $datosE[0]['dato']=$value["FechaEmision"];
                 $datosE[0]['campo']= "FechaRegistro"; 
                 $datosE[0]['dato']=$value["FechaRegistro"];
                 $datosE[0]['campo']= "Linea_SRI"; 
                 $datosE[0]['dato']=0;
                 $datosE[0]['campo']= "T"; 
                 $datosE[0]['dato']=G_NORMAL;
                 $datosE[0]['campo']= "TP"; 
                 $datosE[0]['dato']=$TP;
                 $datosE[0]['campo']= "Numero"; 
                 $datosE[0]['dato']=$Numero;
                 $datosE[0]['campo']= "Fecha"; 
                 $datosE[0]['dato']=$Fecha;
                 // SetAdoUpdate
          	}          	
          	 $resp = $this->modelo->insertar_ingresos_tabla("Trans_Exportaciones",$datosE);
          }

          // ' RETENCIONES IMPORTACIONES
           $ri = $this->modelo->RETENCIONES_IMPORTACIONES($T_No);

                // print_r($ri);die();  

          if(count($ri)>0)
          {
          	foreach ($ri as $key => $value) {
          		 $FechaTexto = $value["FechaLiquidacion"]->format('Y-m-d');
                 // SetAdoAddNew "Trans_Importaciones"
                 $datosI[0]['campo']= "CodSustento"; 
                 $datosI[0]['dato']= $value["CodSustento"];
                 $datosI[0]['campo']= "ImportacionDe"; 
                 $datosI[0]['dato']= $value["ImportacionDe"];
                 $datosI[0]['campo']= "FechaLiquidacion"; 
                 $datosI[0]['dato']= $value["FechaLiquidacion"];
                 $datosI[0]['campo']= "TipoComprobante"; 
                 $datosI[0]['dato']= $value["TipoComprobante"];
                 $datosI[0]['campo']= "DistAduanero"; 
                 $datosI[0]['dato']= $value["DistAduanero"];
                 $datosI[0]['campo']= "Anio"; 
                 $datosI[0]['dato']= $value["Anio"];
                 $datosI[0]['campo']= "Regimen"; 
                 $datosI[0]['dato']= $value["Regimen"];
                 $datosI[0]['campo']= "Correlativo"; 
                 $datosI[0]['dato']= $value["Correlativo"];
                 $datosI[0]['campo']= "Verificador"; 
                 $datosI[0]['dato']= $value["Verificador"];
                 $datosI[0]['campo']= "IdFiscalProv"; 
                 $datosI[0]['dato']= $CodigoB;
                 $datosI[0]['campo']= "ValorCIF"; 
                 $datosI[0]['dato']= $value["ValorCIF"];
                 $datosI[0]['campo']= "BaseImponible"; 
                 $datosI[0]['dato']= $value["BaseImponible"];
                 $datosI[0]['campo']= "BaseImpGrav"; 
                 $datosI[0]['dato']= $value["BaseImpGrav"];
                 $datosI[0]['campo']= "PorcentajeIva"; 
                 $datosI[0]['dato']= $value["PorcentajeIva"];
                 $datosI[0]['campo']= "MontoIva"; 
                 $datosI[0]['dato']= $value["MontoIva"];
                 $datosI[0]['campo']= "BaseImpIce"; 
                 $datosI[0]['dato']= $value["BaseImpIce"];
                 $datosI[0]['campo']= "PorcentajeIce"; 
                 $datosI[0]['dato']= $value["PorcentajeIce"];
                 $datosI[0]['campo']= "MontoIce"; 
                 $datosI[0]['dato']= $value["MontoIce"];
                 $datosI[0]['campo']= "Linea_SRI"; 
                 $datosI[0]['dato']= 0;
                 $datosI[0]['campo']= "T"; 
                 $datosI[0]['dato']= G_NORMAL;
                 $datosI[0]['campo']= "TP"; 
                 $datosI[0]['dato']= $TP;
                 $datosI[0]['campo']= "Numero"; 
                 $datosI[0]['dato']= $Numero;
                 $datosI[0]['campo']= "Fecha"; 
                 $datosI[0]['dato']= $Fecha;
                 // SetAdoUpdate
          	}

          	 $resp = $this->modelo->insertar_ingresos_tabla("Trans_Importaciones",$datosI);
          }

          // ' RETENCIONES AIR
           $ra = $this->modelo->RETENCIONES_AIR($T_No);   

                // print_r($ra);die();            
          if(count($ra)>0)
          {
          	foreach ($ra as $key => $value) {
          		  // SetAdoAddNew "Trans_Air"
                  $datosA[0]['campo']=  "CodRet"; 
                  $datosA[0]['dato']= $value["CodRet"];
                  $datosA[1]['campo']=  "BaseImp"; 
                  $datosA[1]['dato']= $value["BaseImp"];
                  $datosA[2]['campo']=  "Porcentaje"; 
                  $datosA[2]['dato']= number_format($value["Porcentaje"],2);
                  $datosA[3]['campo']=  "ValRet";
                  $datosA[3]['dato']= $value["ValRet"];
                  $datosA[4]['campo']=  "EstabRetencion"; 
                  $datosA[4]['dato']= $value["EstabRetencion"];
                  $datosA[5]['campo']=  "PtoEmiRetencion"; 
                  $datosA[5]['dato']= $value["PtoEmiRetencion"];
                  $datosA[6]['campo']=  "Tipo_Trans"; 
                  $datosA[6]['dato']= $value["Tipo_Trans"];
                  $datosA[7]['campo']=  "IdProv"; 
                  $datosA[7]['dato']= $CodigoB;
                  $datosA[8]['campo']=  "Cta_Retencion"; 
                  $datosA[8]['dato']= $value["Cta_Retencion"];
                  $datosA[9]['campo']=  "EstabFactura";
                  $datosA[9]['dato']= $value["EstabFactura"];
                  $datosA[10]['campo']=  "PuntoEmiFactura"; 
                  $datosA[10]['dato']= $value["PuntoEmiFactura"];
                  $datosA[11]['campo']=  "Factura_No"; 
                  $datosA[11]['dato']= $value["Factura_No"];
                  $datosA[12]['campo']=  "Linea_SRI"; 
                  $datosA[12]['dato']= 0;
                  $datosA[13]['campo']=  "T"; 
                  $datosA[13]['dato']= G_NORMAL;
                  $datosA[14]['campo']=  "TP"; 
                  $datosA[14]['dato']= $TP;
                  $datosA[15]['campo']=  "Numero"; 
                  $datosA[15]['dato']= $Numero;
                  $datosA[16]['campo']=  "Fecha"; 
                  $datosA[16]['dato']= $Fecha;
                  $datosA[17]['campo']=  "SecRetencion"; 
                  $datosA[17]['dato']= $Retencion;
                  $datosA[18]['campo']=  "AutRetencion"; 
                  $datosA[18]['dato']= $Autorizacion_R;                  
                  $datosA[19]['campo']=  "Item"; 
                  $datosA[19]['dato']= $_SESSION['INGRESO']['item'];
                  $datosA[20]['campo']=  "CodigoU"; 
                  $datosA[20]['dato']= $_SESSION['INGRESO']['CodigoU'];
                  // SetAdoUpdate
                  // NumTrans = NumTrans + 1
             $resp = $this->modelo->insertar_ingresos_tabla("Trans_Air",$datosA);

             // print_r($resp);die();

          	}

          }

          // ' Grabamos Retencion de Rol de Pagos
           $rp = $this->modelo->Retencion_Rol_Pagos($T_No);

                // print_r($rp);die();  

          if(count($rp['res'])>0)
          {
          	foreach ($rp['res'] as $key => $value) {
          		$count = 1;
          		foreach ($rp['smtp'] as $key1 => $value1) {
          			$datosP[$count]['campo']= "'".$value1['COLUMN_NAME']."'"; 
                    $datosP[$count]['dato']= "'".$value1['COLUMN_NAME']."'";           			
          		}
          		//buscar en  el array para remplazar en estos
                 $datosP[0]['campo']=  "CodigoU"; 
                 $datosP[0]['dato']= $CodigoUsuario;
                 $datosP[0]['campo']=  "Item"; 
                 $datosP[0]['dato']= $_SESSION['INGRESO']['item'];
                 $datosP[0]['campo']=  "Fecha"; 
                 $datosP[0]['dato']= $Fecha;
                 $datosP[0]['campo']=  "T"; 
                 $datosP[0]['dato']= $Normal;
                 $datosP[0]['campo']=  "TP"; 
                 $datosP[0]['dato']= $TP;
                 $datosP[0]['campo']=  "Numero"; 
                 $datosP[0]['dato']= $Numero;
                 $datosP[0]['campo']=  "Codigo"; 
                 $datosP[0]['dato']= $CodigoB;
                //  SetAdoUpdate
                // .MoveNext
          	}
          	 $resp = $this->modelo->insertar_ingresos_tabla("Trans_Rol_Pagos",$datosP);
          }


          // ' Grabamos Inventarios
          $Inv_Promedio =false;
           $gi = $this->modelo->Grabamos_Inventarios($T_No);   

                // print_r($gi);die();         
          if(count($gi)>0)
          {
          	foreach ($gi as $key => $value) {
          		// ' Asiento de Inventario
                // SetAdoAddNew "Trans_Kardex"
                $datosGI[0]['campo']=   "T"; 
                $datosGI[0]['dato']=  G_NORMAL;
                $datosGI[0]['campo']=   "TP"; 
                $datosGI[0]['dato']=  $TP;
                $datosGI[0]['campo']=   "Numero"; 
                $datosGI[0]['dato']=  $Numero;
                $datosGI[0]['campo']=   "Fecha"; 
                $datosGI[0]['dato']=  $Fecha;
                $datosGI[0]['campo']=   "Codigo_Dr"; 
                $datosGI[0]['dato']=  $value["Codigo_Dr"];// ' C1.CodigoDr
                $datosGI[0]['campo']=   "Codigo_Tra"; 
                $datosGI[0]['dato']=  $value["Codigo_Tra"]; // ' C1.CodigoDr
                $datosGI[0]['campo']=   "Codigo_Inv"; 
                $datosGI[0]['dato']=  $value["CODIGO_INV"];
                $datosGI[0]['campo']=   "Codigo_P"; 
                $datosGI[0]['dato']=  $value["Codigo_B"];
                $datosGI[0]['campo']=   "Descuento"; 
                $datosGI[0]['dato']=  $value["P_DESC"];
                $datosGI[0]['campo']=   "Descuento1"; 
                $datosGI[0]['dato']=  $value["P_DESC1"];
                $datosGI[0]['campo']=   "Valor_Total"; 
                $datosGI[0]['dato']=  $value["VALOR_TOTAL"];
                $datosGI[0]['campo']=   "Existencia"; 
                $datosGI[0]['dato']=  $value["CANTIDAD"];
                $datosGI[0]['campo']=   "Valor_Unitario"; 
                $datosGI[0]['dato']=  $value["VALOR_UNIT"];
                $datosGI[0]['campo']=   "Total"; 
                $datosGI[0]['dato']=  $value["SALDO"];
                $datosGI[0]['campo']=   "Cta_Inv"; 
                $datosGI[0]['dato']=  $value["CTA_INVENTARIO"];
                $datosGI[0]['campo']=   "Contra_Cta"; 
                $datosGI[0]['dato']=  $value["CONTRA_CTA"];
                $datosGI[0]['campo']=   "Orden_No"; 
                $datosGI[0]['dato']=  $value["ORDEN"];
                $datosGI[0]['campo']=   "CodBodega"; 
                $datosGI[0]['dato']=  $value["CodBod"];
                $datosGI[0]['campo']=   "CodMarca"; 
                $datosGI[0]['dato']=  $value["CodMar"];
                $datosGI[0]['campo']=   "Codigo_Barra"; 
                $datosGI[0]['dato']=  $value["COD_BAR"];
                $datosGI[0]['campo']=   "Costo"; 
                $datosGI[0]['dato']=  $value["VALOR_UNIT"];
                $datosGI[0]['campo']=   "PVP"; 
                $datosGI[0]['dato']=  $value["PVP"];
                $datosGI[0]['campo']=   "No_Refrendo"; 
                $datosGI[0]['dato']=  $value["No_Refrendo"];
                $datosGI[0]['campo']=   "Lote_No"; 
                $datosGI[0]['dato']=  $value["Lote_No"];
                $datosGI[0]['campo']=   "Fecha_Fab"; 
                $datosGI[0]['dato']=  $value["Fecha_Fab"];
                $datosGI[0]['campo']=   "Fecha_Exp"; 
                $datosGI[0]['dato']=  $value["Fecha_Exp"];
                $datosGI[0]['campo']=   "Modelo"; 
                $datosGI[0]['dato']=  $value["Modelo"];
                $datosGI[0]['campo']=   "Serie_No"; 
                $datosGI[0]['dato']=  $value["Serie_No"];
                $datosGI[0]['campo']=   "Procedencia"; 
                $datosGI[0]['dato']=  $value["Procedencia"];
                if($Inv_Promedio){
                   $Cantidad = $value["CANTIDAD"];
                   $Saldo = $value["SALDO"];
                   if($Cantidad <= 0){$Cantidad = 1;}
                   $datosGI[0]['campo']= "Costo";
                   $datosGI[0]['dato'] = number_format($Saldo / $Cantidad,2);
                }
                if($value["DH"] == 1){
                   $datosGI[0]['campo']=   "Entrada";
                   $datosGI[0]['dato']=   $value["CANT_ES"];
                }else{
                   $datosGI[0]['campo']=   "Salida";
                   $datosGI[0]['dato']=  $value["CANT_ES"];
                   $Si_No = False;
                }
                $datosGI[0]['campo']=   "CodigoU"; 
                $datosGI[0]['dato']= $_SESSION['INGRESO']['CodigoU'];
                $datosGI[0]['campo']=   "Item"; 
                $datosGI[0]['dato']=  $_SESSION['INGRESO']['item'];
                // SetAdoUpdate
                // AdoTemp.MoveNext
                
                $NumTrans = $NumTrans + 1;

          	}          	
          	 $resp = $this->modelo->insertar_ingresos_tabla("Trans_Kardex",$datosGI);
          }


          // ' Grabamos Prestamos
            $gp = $this->modelo->Grabamos_Prestamos($T_No);

                // print_r($gp);die();   

          if(count($gp)>0)
          {
          	 $TotalCapital = 0;
             $TotalInteres = 0;
          	foreach ($gp as $key => $value) {
          		if( $value["Cuotas"] > 0 ){
                    // SetAdoAddNew "Trans_Prestamos"
                    $datosCu[0]['campo']= "T"; 
                    $datosCu[0]['dato']="P";
                    $datosCu[0]['campo']= "Fecha"; 
                    $datosCu[0]['dato']=$value["Fecha"];
                    $datosCu[0]['campo']= "TP"; 
                    $datosCu[0]['dato']=$TP;
                    $datosCu[0]['campo']= "Credito_No"; 
                    $datosCu[0]['dato']=$_SESSION['INGRESO']['item'].''.generaCeros($Numero,7);
                    $datosCu[0]['campo']= "Cta"; 
                    $datosCu[0]['dato']=$Cta;
                    $datosCu[0]['campo']= "Cuenta_No"; 
                    $datosCu[0]['dato']=$CodigoB;
                    $datosCu[0]['campo']= "Cuota_No"; 
                    $datosCu[0]['dato']=$value["Cuotas"];
                    $datosCu[0]['campo']= "Interes"; 
                    $datosCu[0]['dato']=$value["Interes"];
                    $datosCu[0]['campo']= "Capital"; 
                    $datosCu[0]['dato']=$value["Capital"];
                    $datosCu[0]['campo']= "Pagos"; 
                    $datosCu[0]['dato']=$value["Pagos"];
                    $datosCu[0]['campo']= "Saldo"; 
                    $datosCu[0]['dato']=$value["Saldo"];
                    $datosCu[0]['campo']= "CodigoU"; 
                    $datosCu[0]['dato']=$value["CodigoU"];
                    $datosCu[0]['campo']= "Item"; 
                    $datosCu[0]['dato']=$_SESSION['INGRESO']['item'];
                    // SetAdoUpdate//actualiza
                    $resp = $this->modelo->insertar_ingresos_tabla("Trans_Prestamos",$datosCu);
                }
                $TotalCapital = $TotalCapital + $value["Capital"];
                $TotalInteres = $TotalInteres + $value["Interes"];
                $TotalAbonos = $value["Pagos"];
                $Cta = $value["Cta"];
                $NumMeses = $value["Cuotas"];
                // AdoTemp.MoveNext
          	}
          	 // SetAdoAddNew "Prestamos"
             $datosPr[0]['campo']= "T"; 
             $datosPr[0]['dato']="P";
             $datosPr[0]['campo']= "Fecha"; 
             $datosPr[0]['dato']=$Fecha;
             $datosPr[0]['campo']= "TP"; 
             $datosPr[0]['dato']=$TP;
             $datosPr[0]['campo']= "Credito_No"; 
             $datosPr[0]['dato']= $_SESSION['INGRESO']['item'].''.generaCeros($Numero,7);
             $datosPr[0]['campo']= "Cta"; 
             $datosPr[0]['dato']=$Cta;
             $datosPr[0]['campo']= "Cuenta_No"; 
             $datosPr[0]['dato']=$CodigoB;
             $datosPr[0]['campo']= "Meses"; 
             $datosPr[0]['dato']=NumMeses;
             $datosPr[0]['campo']= "Tasa"; 
             $datosPr[0]['dato']=number_format(($TotalInteres * 12) / ($TotalCapital * $NumMeses),4);
             $datosPr[0]['campo']= "Interes"; 
             $datosPr[0]['dato']=$TotalInteres;
             $datosPr[0]['campo']= "Capital"; 
             $datosPr[0]['dato']=$TotalCapital;
             $datosPr[0]['campo']= "Pagos"; 
             $datosPr[0]['dato']=$TotalAbonos;
             $datosPr[0]['campo']= "Saldo_Pendiente"; 
             $datosPr[0]['dato']=$TotalCapital;
             $datosPr[0]['campo']= "Item"; 
             $datosPr[0]['dato']=$_SESSION['INGRESO']['item'];

             $resp = $this->modelo->insertar_ingresos_tabla("Prestamos",$datosPr);
             // SetAdoUpdate ingresa creo

          }

           // ' Grabamos Comprobantes
        $resp = generar_comprobantes($parametro_comprobante);

        
          // ' Grabamos Transacciones
           $gt = $this->modelo->Grabamos_Transacciones($T_No);

                // print_r($gt);die();   

          if(count($gt)>0)
          {
          	foreach ($gt as $key => $value) {
          		$Moneda_US = $value["ME"];
                $Cta = trim($value["CODIGO"]);
                $Debe = number_format($value["DEBE"], 2);
                $Haber = number_format($value["HABER"], 2);
                $Parcial = number_format($value["PARCIAL_ME"], 2);
                $NoCheque = $value["CHEQ_DEP"];
                $CodigoCC = $value["CODIGO_CC"];
                $Fecha_Vence = $value["EFECTIVIZAR"];
                $DetalleComp = $value["DETALLE"];
                $CodigoP = $value["CODIGO_C"];
                if($CodigoP == '.'){ $CodigoP = $CodigoB;}
                // 'MsgBox C1.T_No & vbCrLf & C1.Concepto & vbCrLf & Debe & vbCrLf & Haber
                if (stristr($Ctas_Modificar, $Cta) === false) {  $Ctas_Modificar.= $Ctas_Modificar.''.$Cta.",";}
                // if (InStr(C1.Ctas_Modificar, $Cta) == 0){ C1.Ctas_Modificar == C1.Ctas_Modificar.''.$Cta.",";}
                if(($Debe + $Haber) > 0){
                   // SetAdoAddNew "Transacciones"

                   $datosGT[0]['campo']= "T"; 
                   $datosGT[0]['dato']=$T;
                   $datosGT[1]['campo']= "Fecha"; 
                   $datosGT[1]['dato']=$Fecha;
                   $datosGT[2]['campo']= "TP"; 
                   $datosGT[2]['dato']=$TP;
                   $datosGT[3]['campo']= "Numero"; 
                   $datosGT[3]['dato']=$Numero;
                   $datosGT[4]['campo']= "Cta"; 
                   $datosGT[4]['dato']=$Cta;
                   $datosGT[5]['campo']= "Parcial_ME"; 
                   $datosGT[5]['dato']=$Parcial;
                   $datosGT[6]['campo']= "Debe"; 
                   $datosGT[6]['dato']=$Debe;
                   $datosGT[7]['campo']= "Haber"; 
                   $datosGT[7]['dato']=$Haber;
                   // $datosGT[8]['campo']= "Parcial_ME"; 
                   // $datosGT[8]['dato']=$Parcial;
                   $datosGT[8]['campo']= "Cheq_Dep"; 
                   $datosGT[8]['dato']=$NoCheque;
                   $datosGT[9]['campo']= "Fecha_Efec"; 
                   $datosGT[9]['dato']=$Fecha_Vence->format('Y-m-d');
                   $datosGT[10]['campo']= "Detalle"; 
                   $datosGT[10]['dato']=$DetalleComp;
                   $datosGT[11]['campo']= "Codigo_C"; 
                   $datosGT[11]['dato']=$CodigoP;
                   $datosGT[12]['campo']= "C_Costo"; 
                   $datosGT[12]['dato']=$CodigoCC;
                   $datosGT[13]['campo']= "Item"; 
                   $datosGT[13]['dato']=$_SESSION['INGRESO']['item'];
                   // SetAdoFields "C", True
                   $datosGT[14]['campo']= "Procesado";
                   $datosGT[14]['dato']=0;
                   // $datosGT[16]['campo']= "Pagar";
                   // $datosGT[16]['dato']=0;
                  //  SetAdoUpdate
                  // NumTrans = NumTrans + 1

          	     // print_r($datosGT);die();
                }                
          	     $resp = $this->modelo->insertar_ingresos_tabla("Transacciones",$datosGT);
          	}
          }

          // print_r('expression');die();

           

          // 'Pasamos a colocar las cuentas que se tienen que mayorizar despuesde grabar el comprobante

        // print_r($Ctas_Modificar);die();
            if(strlen($Ctas_Modificar) > 1 ){
            	$Ctas = explode(',', $Ctas_Modificar);
            	foreach ($Ctas as $key => $value) {
            		 $this->modelo->cuentas_a_mayorizar($value);
            	}
            }


            // 'Actualiza el Email del beneficiario
            if(strlen($Email) > 3 ){
            	$this->modelo->actualizar_email($Email,$CodigoB);
            }

  


           // 'Pasamos a Autorizar la retencion si es electronica
            $parametros_xml = array();
            $parametros_xml['Autorizacion_R']=$Autorizacion_R;
            $parametros_xml['Retencion']=$Retencion;
            $parametros_xml['Serie_R']=$Serie_R;
            $parametros_xml['TP']=$TP;
            $parametros_xml['Fecha']=$Fecha;
            $parametros_xml['Numero']=$Numero;
            $parametros_xml['ruc']=$parametros['ruc'];
            // print_r($Autorizacion_R);
            // exit();
            // print_r($parametros);die();
            if(strlen($Autorizacion_R) >= 13){
            	$res = $this->SRI_Crear_Clave_Acceso_Retencines($parametros_xml); //function xml
            	// print_r($res);die();
				$aut = $this->sri->Clave_acceso($parametros['fecha'],'07',$Serie_R,generaCeros($parametros['Retencion'],9));
				$pdf = 'RE_'.$Serie_R.'-'.generaCeros($parametros['Retencion'],7); 
				$this->modelo->reporte_retencion($Numero,$TP,$Retencion,$Serie_R,$imp=1);

				// if($res==1)
				// {
					 $Trans_No = $T_No;
           			 $this->modelo->BorrarAsientos($Trans_No,true);
				// }
				return array('respuesta'=>$res,'pdf'=>$pdf,'text'=>$res,'clave'=>$aut);
            	  
				
            	// if(!is_null($res))
            	// {
            	//  return $res;
             //    }
            }else
            {
            	return 1;
            }


           // 'Eliminamos Asientos contables
           
            // Control_Procesos Normal, "Grabar Comprobante de: " & C1.TP & " No. " & C1.Numero
            //   if($this->ingresar_trans_Air($num_com,$parametros['tip'])==1){
            //     $resp = generar_comprobantes($parametro_comprobante);
            // // print_r($resp);die();
            //     if($resp==$num_com)
            //     {
            //         return 1;
            //     }else
            //     {
            //         return -1;
            //     }
            //   }else
            //   {
            //     echo " no se genero";
            //   }

            // return 1;
           

     }
     function ingresar_trans_Air($numero,$tipo)
     {
        $air = $this->modelo->DG_asientoR_datos();
        $fallo = false;
        if(count($air)>0)
        {
            foreach ($air as $key => $value) {
                $datos[0]['campo'] = 'CodRet';
                $datos[1]['campo'] = 'Detalle';
                $datos[2]['campo'] = 'BaseImp';
                $datos[3]['campo'] = 'Porcentaje';
                $datos[4]['campo'] = 'ValRet';
                $datos[5]['campo'] = 'EstabRetencion';
                $datos[6]['campo'] = 'PtoEmiRetencion';
                $datos[7]['campo'] = 'SecRetencion';
                $datos[8]['campo'] = 'AutRetencion';
                $datos[9]['campo'] = 'FechaEmiRet';
                $datos[10]['campo'] = 'Cta_Retencion';
                $datos[11]['campo'] = 'EstabFactura';
                $datos[12]['campo'] = 'PuntoEmiFactura';
                $datos[13]['campo'] = 'Factura_No';
                $datos[14]['campo'] = 'IdProv';
                $datos[15]['campo'] = 'Item';
                $datos[16]['campo'] = 'CodigoU';
                $datos[17]['campo'] = 'A_No';
                $datos[18]['campo'] = 'T_No';
                $datos[19]['campo'] = 'Tipo_Trans';
                $datos[20]['campo'] = 'T';
                $datos[21]['campo'] = 'Numero';
                $datos[22]['campo'] = 'TP';

                $datos[0]['dato'] =$value['CodRet'];
                $datos[1]['dato'] =$value['Detalle'];
                $datos[2]['dato'] =$value['BaseImp'];
                $datos[3]['dato'] =$value['Porcentaje'];
                $datos[4]['dato'] =$value['ValRet'];
                $datos[5]['dato'] =$value['EstabRetencion'];
                $datos[6]['dato'] =$value['PtoEmiRetencion'];
                $datos[7]['dato'] =$value['SecRetencion'];
                $datos[8]['dato'] =$value['AutRetencion'];
                $datos[9]['dato'] =$value['FechaEmiRet']->format('Y-m-d');
                $datos[10]['dato'] =$value['Cta_Retencion'];
                $datos[11]['dato'] =$value['EstabFactura'];
                $datos[12]['dato'] =$value['PuntoEmiFactura'];
                $datos[13]['dato'] =$value['Factura_No'];
                $datos[14]['dato'] =$value['IdProv'];
                $datos[15]['dato'] =$value['Item'];
                $datos[16]['dato'] =$value['CodigoU'];
                $datos[17]['dato'] =$value['A_No'];
                $datos[18]['dato'] =$value['T_No'];
                $datos[19]['dato'] =$value['Tipo_Trans'];
                $datos[20]['dato'] ='N';
                $datos[21]['dato'] =$numero;
                $datos[22]['dato'] =$tipo;
            }
           $resp = $this->modelo->insertar_ingresos_tabla('Trans_Air',$datos);
           if($resp !='')
           {
             $fallo = true;
           }
        }
        // print_r($air);die();
        if($fallo==false)
        {
            return 1;
        }
        else{
            return -1;
        }
     }
     function eliminar_retenciones()
     {
        return $this->modelo->eliminacion_retencion();
     }
     function eliminar_registro($parametros)
     {
        $Codigo = '';
        $tabla = '';
       switch ($parametros['tabla']) {
           case 'asiento':
             $Codigo = "CODIGO = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento';
               break;
            case 'asientoSC':
             $Codigo = "Codigo = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_SC';
               # code...
               break;
            case 'asientoB':
             $Codigo = "CTA_BANCO = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_B';
               # code...
               break;
            case 'inpor':
             $Codigo = "Cod_Sustento = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_Importaciones';
               # code...
               break;
            case 'expo':
             $Codigo = "Codigo = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Exportaciones';
               # code...
               break;
            case 'ventas':
             $Codigo = "IdProv = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Ventas';
               # code...
               break;
            case 'compras':
             $Codigo = "IdProv = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Compras';
               # code...
               break;
            case 'air':             
             $Codigo = "CodRet = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_Air';
               # code...
               break;
       }
       return $this->modelo->eliminar_registros($tabla,$Codigo);
     }

     function SRI_Crear_Clave_Acceso_Retencines($parametros)
     {

        $datos = $this->modelo->retencion_compras($parametros['Numero'],$parametros['TP']);
        // print_r($datos);die();
        if(count($datos)>0)
        {
          $TFA[0]["Serie_R"] = $datos[0]["Serie_Retencion"];
          $TFA[0]["Retencion"] = $datos[0]["SecRetencion"];
          $TFA[0]["Autorizacion_R"] = $datos[0]["AutRetencion"];
          $TFA[0]["Autorizacion"] = $datos[0]["Autorizacion"];
          $TFA[0]["Fecha"] = $datos[0]["FechaEmision"];
          $TFA[0]["Vencimiento"] = $datos[0]["FechaRegistro"];
          $TFA[0]["Serie"] = $datos[0]["Establecimiento"].$datos[0]["PuntoEmision"];
          $TFA[0]["Factura"] = $datos[0]["Secuencial"];
          $TFA[0]["Hora"] = date('H:m:s');
          $TFA[0]["Cliente"] = $datos[0]["Cliente"];
          $TFA[0]["CI_RUC"] = $datos[0]["CI_RUC"];
          $TFA[0]["TD"] = $datos[0]["TD"];
          $TFA[0]["DireccionC"] = $datos[0]["Direccion"];
          $TFA[0]["TelefonoC"] = $datos[0]["Telefono"];
          $TFA[0]["EmailC"] = $datos[0]["Email"];
          $CodSustento = $datos[0]["CodSustento"];

          $TFA[0]["Ruc"] = $datos[0]["CI_RUC"];
          $TFA[0]["TP"] = $parametros['TP'];
          $TFA[0]["Numero"] = $parametros['Numero'];
          $TFA[0]["TipoComprobante"] = '0'.$datos[0]["TipoComprobante"];

          // Validar_Porc_IVA $TFA[0]["Fecha"];
          
         // 'Algoritmo Modulo 11 para la clave de la retencion
         // '& Format$(TFA.Vencimiento, "ddmmyyyy")
          $len= strlen($TFA[0]["Retencion"]);
          // $rete = '';
          if($len<9)
          {
            $num_ce = 9-$len;
            $retencion = str_repeat('0',$num_ce);
            $rete = $retencion.$TFA[0]["Retencion"];
          }
          // print_r($rete);die();
          $dig = digito_verificador_nuevo($parametros['ruc']);
          // print_r($dig);die();

          //10062021
           $aut = $this->sri->Clave_acceso($TFA[0]['Fecha']->format('Y-m-d'),'07',$TFA[0]["Serie_R"],$rete);
           $TFA[0]["ClaveAcceso"]  = $aut;

           // print_r( $TFA[0]["ClaveAcceso"]);die();

          // $TFA[0]["ClaveAcceso"] = date("dmY", strtotime($TFA[0]['Fecha']->format('Y-m-d')))."07".$_SESSION['INGRESO']['RUC'].$_SESSION['INGRESO']['Ambiente'].$TFA[0]["Serie_R"].$rete."123456781";
          // $TFA[0]["ClaveAcceso"] = str_replace('.','1', $TFA[0]['ClaveAcceso']);

          // generamos el xmlo de la retencion
          $xml = $this->sri->generar_xml_retencion($TFA,$datos);
          $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	      $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
	           if($xml==1)
	           {
	           	 $firma = $this->sri->firmar_documento(
	           	 	$aut,
	           	 	generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->sri->comprobar_xml_sri(
	           	 		$aut,
	           	 		$linkSriAutorizacion);
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->sri->enviar_xml_sri(
			   		 		$aut,
			   		 		$linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->sri->comprobar_xml_sri($aut,$linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			$resp = $this->actualizar_datos_CER($aut,$parametros['TP'],$TFA[0]["Serie_R"],$rete,generaCeros($_SESSION['INGRESO']['IDEntidad'],3),$TFA[0]["Autorizacion_R"]);
			   		 			return  $resp;
			   		 		}else
			   		 		{
			   		 			return $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// $resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion']);
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML
	           	return $xml;
	           }



           // autorizar sri

          // print_r($respuesta);die();
         /* $num_res = count($respuesta);
          if($num_res>=2)
	           {
	           	// print_r($respuesta);die();
	           	if($num_res!=2)
	           	{
	           	 $estado = explode(' ', $respuesta[2]);
	           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO')
	           	 {
	           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion']);
	           	 	if($respuesta==1)
	           	 	{
	           	 	  return array('respuesta'=>1);
	           	 	}
	           	 }else
	           	 {

	           	   $compro = explode('COMPROBANTE', $respuesta[2]);
	           	   $entidad= $_SESSION['INGRESO']['item'];
	           	   $url_No_autorizados ='../comprobantes/entidades/entidad_'.$entidad."/CE".$entidad.'/No_autorizados/';
	           	   $resp = array('respuesta'=>2,'ar'=>trim($compro[0]).'.xml','url'=>$url_No_autorizados);
	           	 	return $resp;
	           	 }
	           	}else
	           	{
	           	 $estado = explode(' ', $respuesta[1]);
	           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO')
	           	 {
	           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion']);
	           	 	if($respuesta==1)
	           	 	{
	           	 	  return array('respuesta'=>1);
	           	 	}
	           	 }

	           	}

	           }else
	           {
	           	if($respuesta[1]=='Autorizado')
	           	{
	           		return array('respuesta'=>3);

	           	}else{
	           		$resp = utf8_encode($respuesta[1]);
	           		return $resp;
	           	}
	           }*/
        }

     }


     function actualizar_datos_CER($autorizacion,$tc,$serie,$retencion,$entidad,$autorizacion_ant)
     {

     	$res = $this->modelo->actualizar_trans_compras($tc,$retencion,$serie,$autorizacion,$autorizacion_ant);
     	$res2 = $this->modelo->atualizar_trans_air($tc,$retencion,$serie,$autorizacion,$autorizacion_ant);
		$url_autorizado =dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.$entidad."/CE".$_SESSION['INGRESO']['item'].'/Autorizados/'.$autorizacion.'.xml';
		$archivo = fopen($url_autorizado,"rb");
			if( $archivo != false ) 
			{			
				rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
				$cadena2 = fread($archivo, filesize($url_autorizado));  // Leemos hasta el final del archivo
				if( $cadena2 == false ){
					echo "Error al leer el archivo";
				}
			}
			// Cerrar el archivo:
			fclose($archivo);	

		$res3 = $this->modelo->guardar_documento($autorizacion,$cadena2,$serie,$retencion);	
			//echo $sql;
		if($res==1)
		{
			if($res2==1)
			{
				if($res3==1)
				{
					return 1;
				}else
				{
					return -3;
				}
			}else
			{
				return -2;
			}
		}else
		{
			return -1;
		}
			
			// return 1;

     }
     function ListarAsientoB()
     {
     	$Opcb = 1;
     	$tbl = $this->modelo->ListarAsientoTemSQL('',$Opcb,false,false);
     	return $tbl;
     }

     function borrar_asientos()
     {
     	return $this->modelo->BorrarAsientos('1',true);
     }

    function listar_comprobante($parametros)
    {

    $parametros = base64_decode($parametros);
    $parametros = unserialize($parametros);
    // print_r($parametros);die();

    $Trans_No = 0;
    $Ln_No = 0;
    $LnSC_No = 0;
    $Ret_No = 0;
    $C1_CodigoB = '';
    $C1_Beneficiario ='';
    $C1_Email ='';
    $C1_Concepto ='';
    $C1_Cotizacion ='';
    $C1_Monto_Total ='';
    $C1_Efectivo ='';
    $C1_RUC_CI ='';
    $C1_TD ='';
    $C1_Item = $parametros['Item'];
    $C1_Ctas_Modificar = '';


// ' Determinamos espacios de memoria para grabar
    if($Trans_No <= 0){	$Trans_No = 1;}
    if($Ln_No <= 0){$Ln_No = 1;}
    if($LnSC_No <= 0){$LnSC_No = 1;}
    if($Ret_No <= 0){$Ret_No = 1;}
    $ExisteComp = False;
//     Co.RetNueva = True;
//     // 'Encabezado del Comprobante
    $enca = $this->modelo->Encabezado_Comprobante($parametros);
    if(count($enca)>0)
    {
       $C1_CodigoB = $enca[0]["Codigo_B"];
       $C1_Beneficiario = $enca[0]["Cliente"];
       $C1_Email = $enca[0]["Email"];
       $C1_Concepto = $enca[0]["Concepto"];
       $C1_Cotizacion = $enca[0]["Cotizacion"];
       $C1_Monto_Total = $enca[0]["Monto_Total"];
       $C1_Efectivo = $enca[0]["Efectivo"];
       $C1_RUC_CI = $enca[0]["CI_RUC"];
       $C1_TD = $enca[0]["TD"];
       $ExisteComp = True;

    }else
    {
       $C1_CodigoB = G_NINGUNO;
       $C1_Beneficiario = G_NINGUNO;
       $C1_Email = Ninguno;
       $C1_Concepto = G_NINGUNO;
       $C1_Cotizacion = 0;
       $C1_Monto_Total = 0;
       $C1_Efectivo = 0;
       $C1_RUC_CI = G_NINGUNO;
       $C1_TD = G_NINGUNO;

    }
//  'Si existe el comprobante lo presentamos
    if($ExisteComp){
        // 'Llenar Cuentas de Transacciones
     	$AdoRegistros = $this->modelo->transacciones_comprobante($parametros['TP'],$parametros['Numero'],$parametros['Item']);
     	if(count($AdoRegistros)>0)
     	{
     		foreach ($AdoRegistros as $key => $value) {
     		 $Si_No = 0;
             if($value["Parcial_ME"] <> 0){$Si_No = 1;}
             $datos[0]['campo'] =  "CODIGO";
             $datos[0]['dato'] =  $value["Cta"];
             $datos[1]['campo'] =  "CUENTA";
             $datos[1]['dato'] =  $value["Cuenta"];
             $datos[2]['campo'] =  "PARCIAL_ME";
             $datos[2]['dato'] =  $value["Parcial_ME"];
             $datos[3]['campo'] =  "DEBE";
             $datos[3]['dato'] =  $value["Debe"];
             $datos[4]['campo'] =  "HABER";
             $datos[4]['dato'] =  $value["Haber"];
             $datos[5]['campo'] =  "ME";
             $datos[5]['dato'] =  $Si_No;
             $datos[6]['campo'] =  "CHEQ_DEP";
             $datos[6]['dato'] =  $value["Cheq_Dep"];
             $datos[7]['campo'] =  "EFECTIVIZAR";
             $datos[7]['dato'] =  $value["Fecha_Efec"]->format('Y-m-d');
             $datos[8]['campo'] =  "DETALLE";
             $datos[8]['dato'] =  str_replace(',','',$value["Detalle"]);
             $datos[9]['campo'] =  "CODIGO_C";
             $datos[9]['dato'] =  $value["Codigo_C"];
             $datos[10]['campo'] =  "T_No";
             $datos[10]['dato'] =  $Trans_No;
             $datos[11]['campo'] =  "Item";
             $datos[11]['dato'] =  $C1_Item;
             $datos[12]['campo'] =  "CodigoU";
             $datos[12]['dato'] =  $_SESSION['INGRESO']['CodigoU'];
             $datos[13]['campo'] =  "A_No";
             $datos[13]['dato'] =  $Ln_No;

             // print_r($datos);die();
             $resp = $this->modelo->insertar_ingresos_tabla('Asiento',$datos);
             $pos = strpos($C1_Ctas_Modificar, $value["Cta"]);
             if ($pos === false) {
             	 $C1_Ctas_Modificar = $C1_Ctas_Modificar.$value["Cta"].",";
             } 
             $Ln_No = $Ln_No + 1;
     		}

     	}
     	     
        //'Llenar Bancos
     	if(count($AdoRegistros)>0)
     	{
     		$datos = array();
     		foreach ($AdoRegistros as $key => $value) {
     			if($value["Cheq_Dep"] <> G_NINGUNO){
                    $Si_No = 0;
                    if($value["Parcial_ME"] <> 0){$Si_No = 1;}
                       $datos[0]['campo']= "CTA_BANCO";
                       $datos[0]['dato'] = $value["Cta"];
                       $datos[1]['campo']= "BANCO";
                       $datos[1]['dato'] = $value["Cuenta"];
                       $datos[2]['campo']= "CHEQ_DEP";
                       $datos[2]['dato'] = $value["Cheq_Dep"];
                       $datos[3]['campo']= "EFECTIVIZAR";
                       $datos[3]['dato'] = $value["Fecha_Efec"]->format('Y-m-d');
                       $datos[4]['campo']= "VALOR";
                       $datos[4]['dato'] = abs($value["Debe"]-$value["Haber"]);
                       $datos[5]['campo']= "ME"; 
                       $datos[5]['dato'] = $Si_No;
                       $datos[6]['campo']= "T_No"; 
                       $datos[6]['dato'] = $Trans_No;
                       $datos[7]['campo']= "Item"; 
                       $datos[7]['dato'] = $C1_Item;
                       $datos[8]['campo']= "CodigoU"; 
                       $datos[8]['dato'] = $_SESSION['INGRESO']['CodigoU'];

     	              // print_r($datos);die();
                      $resp = $this->modelo->insertar_ingresos_tabla('Asiento_B',$datos);
     			}    			
     		}
     	}


        //'Listar las Retenciones Air
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->retenciones_comprobantes($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        // print_r($AdoRegistros);
        if(count($AdoRegistros['respuesta'])>0)
        {       	
     		$datos = array();
        	foreach ($AdoRegistros['respuesta'] as $key => $value) {

             $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
             $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          			$datos[$count]['campo']= $value1['Name'];
          			if(is_object($value[$value1['Name']])) 
          			{
                    $datos[$count]['dato']= $value[$value1['Name']]->format('Y-m-d');  

          			}else
          			{
                    $datos[$count]['dato']= $value[$value1['Name']];  

          			}   
                    $count = $count+1; 
                    }     			
          		}

             $datos[$count]['campo']= "CodigoU";
             $datos[$count]['dato'] =  $_SESSION['INGRESO']['CodigoU'];
             $datos[$count+1]['campo']= "T_No";
             $datos[$count+1]['dato'] =  $Trans_No;
             $datos[$count+2]['campo']= "Item";
             $datos[$count+2]['dato'] =  $C1_Item;
             $datos[$count+3]['campo']= "A_No";
             $datos[$count+3]['dato'] =  $Ret_No;

     	// print_r($datos);die();
            
             $Ret_No = $Ret_No + 1;
             $resp = $this->modelo->insertar_ingresos_tabla('Asiento_Air',$datos);
        	}

        }
     
        //'Listar las Compras
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->Listar_Compras($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        if(count($AdoRegistros['respuesta'])>0)
        {
     		$datos = array();
        	foreach ($AdoRegistros['respuesta'] as $key => $value) {
        		if($value['SecRetencion']>0)
        		{
        			$Co_RetNueva = False;
                    $Co_Serie_R = $value["Serie_Retencion"];
                    $Co_Retencion = $value["SecRetencion"];
        		}
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          			$datos[$count]['campo']= $value1['Name'];
          			if(is_object($value[$value1['Name']])) 
          			{
                    $datos[$count]['dato']= $value[$value1['Name']]->format('Y-m-d');  

          			}else
          			{
                    $datos[$count]['dato']= $value[$value1['Name']];  

          			}   
                    $count = $count+1; 
                    }     			
          		}
        		 // For K = 0 To .Fields.Count - 1
            //     SetAdoFields .Fields(K).Name, .Fields(K)
            // Next K

                $datos[$count]['campo']= "CodigoU";
                $datos[$count]['dato'] =  $_SESSION['INGRESO']['CodigoU'];
                $datos[$count+1]['campo']= "T_No";
                $datos[$count+1]['dato'] =  $Trans_No;
                $datos[$count+2]['campo']= "Item";
                $datos[$count+2]['dato'] =  $C1_Item;
                $datos[$count+3]['campo']= "A_No";
                $datos[$count+3]['dato'] =  $Ret_No;
                // print_r($datos);die();
                $Ret_No = $Ret_No + 1;                
                $resp = $this->modelo->insertar_ingresos_tabla('Asiento_Compras',$datos);

        	}
        }
     
        //'Listar las Ventas
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->Listar_Ventas($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        if(count($AdoRegistros['respuesta'])>0)
        {
     		$datos = array();
        	foreach ($AdoRegistros['respuesta'] as $key => $value) {        		
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          			$datos[$count]['campo']= $value1['Name'];
          			if(is_object($value[$value1['Name']])) 
          			{
                    $datos[$count]['dato']= $value[$value1['Name']]->format('Y-m-d');  

          			}else
          			{
                    $datos[$count]['dato']= $value[$value1['Name']];  

          			}   
                    $count = $count+1; 
                    }     			
          		}
           //  For K = 0 To .Fields.Count - 1
           //    SetAdoFields .Fields(K).Name, .Fields(K)
           //  Next K
                $datos[$count]['campo']= "CodigoU";
                $datos[$count]['dato'] = $_SESSION['INGRESO']['CodigoU'];
                $datos[$count+1]['campo']= "T_No";
                $datos[$count+1]['dato'] = $Trans_No;
                $datos[$count+2]['campo']= "Item";
                $datos[$count+2]['dato'] = $C1_Item;
                $datos[$count+3]['campo']= "A_No";
                $datos[$count+3]['dato'] = $Ret_No;
                $Ret_No = $Ret_No + 1;
                $resp = $this->modelo->insertar_ingresos_tabla('Asiento_Ventas',$datos);
        	}

        }

     
        //'Listar las Importaciones
        $Ret_No = 1;
        $AdoRegistrosdo = $this->modelo->Listar_Importaciones($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        // print_r($AdoRegistros);die();
        if(count($AdoRegistros['respuesta'])>0)
        {
     		$datos = array();
        	foreach ($AdoRegistros as $key => $value) {
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          			$datos[$count]['campo']= $value1['Name'];
          			if(is_object($value[$value1['Name']])) 
          			{
                    $datos[$count]['dato']= $value[$value1['Name']]->format('Y-m-d');  

          			}else
          			{
                    $datos[$count]['dato']= $value[$value1['Name']];  

          			}   
                    $count = $count+1; 
                    }     			
          		}
        		$datos[$count]['campo']= "CodigoU";
        		$datos[$count]['dato'] = $_SESSION['INGRESO']['CodigoU'];
                $datos[$count+1]['campo']= "T_No";
                $datos[$count+1]['dato'] = $Trans_No;
                $datos[$count+2]['campo']= "Item";
                $datos[$count+2]['dato'] = $C1_Item;
                $datos[$count+3]['campo']= "A_No";
                $datos[$count+3]['dato'] = $Ret_No;
                $Ret_No = $Ret_No + 1;                
                $resp = $this->modelo->insertar_ingresos_tabla('Asiento_Importaciones',$datos);
        		
        	}
        }


        //'Listar las Compras
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->Listar_las_Compras($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        if(count($AdoRegistros['respuesta'])>0)
        {
        	foreach ($AdoRegistros as $key => $value) {
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          			$datos[$count]['campo']= $value1['Name'];
          			if(is_object($value[$value1['Name']])) 
          			{
                    $datos[$count]['dato']= $value[$value1['Name']]->format('Y-m-d');  

          			}else
          			{
                    $datos[$count]['dato']= $value[$value1['Name']];  

          			}   
                    $count = $count+1; 
                    }     			
          		}
        	    $datos[$count]['campo']= "CodigoU";
        	    $datos[$count]['dato'] = $_SESSION['INGRESO']['CodigoU'];
                $datos[$count+1]['campo']= "T_No";
                $datos[$count+1]['dato'] = $Trans_No;
                $datos[$count+2]['campo']= "Item";
                $datos[$count+2]['dato'] = $C1_Item;
                $datos[$count+3]['campo']= "A_No";
                $datos[$count+3]['dato'] = $Ret_No;
                $Ret_No = $Ret_No + 1;
                $resp = $this->modelo->insertar_ingresos_tabla('Asiento_Exportaciones',$datos);        		
        	}
        }
     
        // 'Llenar SubCuentas
        $AdoRegistros =  $this->modelo->Llenar_SubCuentas($parametros['TP'],$parametros['Numero'],$parametros['Item']);
       if(count($AdoRegistros)>0)
       {
    	 foreach ($AdoRegistros as $key => $value) {
                $datos[0]['campo']= "FECHA_V";
                $datos[0]['dato'] = $value["Fecha_V"]->format('Y-m-d');
                $datos[1]['campo']= "TC";
                $datos[1]['dato'] = $value["TC"];
                $datos[2]['campo']= "Codigo";
                $datos[2]['dato'] = $value["Codigo"];
                $datos[3]['campo']= "Beneficiario";
                $datos[3]['dato'] = $value["Detalle"];
                $datos[4]['campo']= "Factura";
                $datos[4]['dato'] = $value["Factura"];
                $datos[5]['campo']= "Prima";
                $datos[5]['dato'] = $value["Prima"];
                $datos[6]['campo']= "Valor";
                $datos[6]['dato'] = Abs($value["VALOR"]);
                $datos[7]['campo']= "Valor_Me";
                $datos[7]['dato'] = abs($value["Parcial_ME"]);
                $datos[8]['campo']= "Detalle_SubCta";
                $datos[8]['dato'] = $value["Detalle_SubCta"];
                $datos[9]['campo']= "Cta";
                $datos[9]['dato'] = $value["Cta"];
                $datos[10]['campo']= "TM";
                $datos[10]['dato'] = "1";
                $datos[11]['campo']= "DH";
                $datos[11]['dato'] = "1";
                if($value["Parcial_ME"] > 0){
                    $datos[12]['campo']= "TM";
                    $datos[12]['dato'] = "2";
                }
                if($value["VALOR"] < 0 ){                    
                	$datos[13]['campo']= "DH";
                	$datos[13]['dato'] = "2";
                }
                $datos[14]['campo']= "T_No";
                $datos[14]['dato'] = $Trans_No;
                $datos[15]['campo']= "Item";
                $datos[15]['dato'] = $C1_Item;
                $datos[16]['campo']= "SC_No";
                $datos[16]['dato'] = $LnSC_No;
                $datos[17]['campo']= "CodigoU";
                $datos[17]['dato'] = $_SESSION['INGRESO']['CodigoU'];
                $LnSC_No = $LnSC_No + 1;

                $resp = $this->modelo->insertar_ingresos_tabla('Asiento_SC',$datos);  
    		
    	    }
       }     

                // print_r($AdoRegistros);die();

        $AdoRegistros =  $this->modelo->Llenar_SubCuentas2($parametros['TP'],$parametros['Numero'],$parametros['Item']);
         // print_r($AdoRegistros);die();
       if(count($AdoRegistros)>0)
       {
    	foreach ($AdoRegistros as $key => $value) {
              $datos[0]['campo']= "FECHA_V";
              $datos[0]['dato'] =$value["Fecha_V"]->format('Y-m-d');
              $datos[1]['campo']= "TC";
              $datos[1]['dato'] =$value["TC"];
              $datos[2]['campo']= "Codigo";
              $datos[2]['dato'] =$value["Codigo"];
              $datos[3]['campo']= "Beneficiario";
              $datos[3]['dato'] =$value["Detalle"];
              $datos[4]['campo']= "Factura";
              $datos[4]['dato'] =$value["Factura"];
              $datos[5]['campo']= "Prima";
              $datos[5]['dato'] =$value["Prima"];
              $datos[6]['campo']= "Valor";
              $datos[6]['dato'] =abs($value["VALOR"]);
              $datos[7]['campo']= "Valor_Me";
              $datos[7]['dato'] =abs($value["Parcial_ME"]);
              $datos[8]['campo']= "Detalle_SubCta";
              $datos[8]['dato'] =$value["Detalle_SubCta"];
              $datos[9]['campo']= "Cta";
              $datos[9]['dato'] =$value["Cta"];             
              $datos[10]['campo']= "T_No";
              $datos[10]['dato'] = $Trans_No;
              $datos[11]['campo']= "Item";
              $datos[11]['dato'] = $C1_Item;
              $datos[12]['campo']= "SC_No";
              $datos[12]['dato'] = $LnSC_No;
              $datos[13]['campo']= "CodigoU";
              $datos[13]['dato'] = $_SESSION['INGRESO']['CodigoU'];
              if($value["Parcial_ME"] > 0){
                  $datos[14]['campo']= "TM";
                  $datos[14]['dato'] ="2";
              }else
              {

               $datos[14]['campo']= "TM";
               $datos[14]['dato'] ="1";
              }
              if($value["VALOR"] < 0){
                  $datos[15]['campo']= "DH";
                  $datos[15]['dato'] = "2";
              }else
              {
                $datos[15]['campo']= "DH";
                $datos[15]['dato'] ="1"; 
              }

              $LnSC_No = $LnSC_No + 1;
    		  $resp = $this->modelo->insertar_ingresos_tabla('Asiento_SC',$datos);  
    	}
       }
   }
}

function Tipo_De_Comprobante_No($parametros)
{
	$parametros = base64_decode($parametros);
    $parametros = unserialize($parametros);
    $y = explode('-',$parametros['fecha']);

	return $ret = 'Comprobante de '.$parametros['TP'].' No. '.$y[0].'-'.generaCeros($parametros['Numero'],8);
}
function Llenar_Encabezado_Comprobante($parametros)
{
	$parametros = base64_decode($parametros);
    $parametros = unserialize($parametros);
    $bene = $this->modelo->beneficiarios($parametros['beneficiario']);
    // print_r($bene);
    // print_r($parametros); die();

    return  array('beneficiario'=>$parametros['beneficiario'],'RUC_CI'=>$bene[0]['id'],'email'=>$bene[0]['email'],'Concepto'=>$parametros['Concepto'],'CodigoB'=>$parametros['CodigoB'],'fecha'=>$parametros['fecha']);  
}

function ingresar_asiento($parametros)
{
	// print_r($parametros);die();
	//ingresar asiento 
		$va = $parametros['va'];
		$dconcepto1 = $parametros['dconcepto1'];
		$codigo = $parametros['codigo'];
		$cuenta = $parametros['cuenta'];
		if(isset($parametros['t_no']))
		{
			$t_no = $parametros['t_no'];
		}else{
			$t_no = 1;
		}
		if(isset($parametros['efectivo_as']))
		{
			$efectivo_as = $parametros['efectivo_as'];
		}else{
			$efectivo_as = '';
		}
		if(isset($parametros['chq_as']))
		{
			$chq_as = $parametros['chq_as'];
		}else{
			$chq_as = '';
		}
		
		$moneda = $parametros['moneda'];
		$tipo_cue = $parametros['tipo_cue'];
		
		if($efectivo_as=='' || $efectivo_as==null)
		{
			$efectivo_as=$fecha;
		}
		if($chq_as=='' || $chq_as==null)
		{
			$chq_as='.';
		}
		$parcial = 0;
		if($moneda==2)
		{
			$cotizacion = $parametros['cotizacion'];
			$con = $parametros['con'];
			if($tipo_cue==1)
			{
				if($con=='/')
				{
					$debe=$va/$cotizacion;
				}else{
					$debe=$va*$cotizacion;
				}
				$parcial = $va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				if($con=='/')
				{
					$haber=$va/$cotizacion;
				}else{
					$haber=$va*$cotizacion;
				}
				$parcial = $va;
				$debe=0;
			}
		}else{
			if($tipo_cue==1)
			{
				$debe=$va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				$debe=0;
				$haber=$va;
			}
		}
		//verificar si ya existe en ese modulo ese registro
		  $stmt = $this->modelo->verificar_existente($codigo,$va);
		
		//print_r($sql);die();
		
		//para contar registro
		$i=0;
		$i=count($stmt);
		if($t_no == '60')
		{
			$i=0;
		}
		//echo $i.' -- '.$sql;
		//seleccionamos el valor siguiente

		$stmt = $this->modelo->valor_siguiente();
		
		$A_No=0;
		$ii=0;
		if(count($stmt)>0)
		{
			foreach ($stmt as $key => $value) {
				$A_No = $value['A_No'];
				$ii++;
			}
		}
		if($ii==0)
		{
			$A_No++;
		}else
		{
			$A_No++;
		}
		
		//si no existe guardamos
		if($i==0)
		{
			
			$res = $this->modelo->insertar_aseinto($codigo,$cuenta,$parcial,$debe,$haber,$chq_as,$dconcepto1,$efectivo_as,$t_no,$A_No);
			if($res==-1)  
			{  
				 return array('resp'=>-1,'tbl'=>'','totales'=>'','obs'=>'no se pudo insertar en asiento');  
			}
			else
			{
				$tbl = $this->modelo->listar_asientos($tabla=1);
				$totales = ''; // ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				 return array('resp'=>1,'tbl'=>$tbl,'totales'=>$totales,'obs'=>'');  			
			}
		}
		else
		{
			// 		grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
			// 		ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
			 return array('resp'=>-2,'tbl'=>'','totales'=>'','obs'=>'El asiento puede estar repetido');  
		}
		
	
}

function eliminar_asientos($parametros)
{
	$Trans_No = $parametros['T_No'];
	return $this->modelo->BorrarAsientos($Trans_No,true);
}


}
?>