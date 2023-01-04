<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

date_default_timezone_set('America/Guayaquil');

@session_start(); 

$controlador = new autorizacion_sri();
if(isset($_GET['autorizar']))
{
	$parametros = $_POST['parametros'];
     echo json_encode($controlador->Autorizar($parametros));
}

/**
 * 
 */
class autorizacion_sri
{
	private $clave;
	//Metodo de encriptación
	private $method;
	private $iv;
	private $conn;
	private $db;
	// Puedes generar una diferente usando la funcion $getIV()
	
	function __construct()
	{
		$this->clave = 'Una cadena, muy, muy larga para mejorar la encriptacion';
		$this->method = 'aes-256-cbc';
		$this->iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
		// $this->conn = new Conectar();
		$this->db = new db();
	}
	function encriptar($dato)
	{
		return openssl_encrypt ($dato, $this->method, $this->clave, false, $this->iv);
	}
	function desencriptar($dato)
	{
		 return openssl_decrypt($dato, $this->method, $this->clave, false, $this->iv);
	}


	function Clave_acceso($fecha,$tipo_com,$serie,$numfac)
	{
		$ambiente = $_SESSION['INGRESO']['Ambiente'];
	    $Fecha1 = explode("-",$fecha);
		$fechaem=$Fecha1[2].'/'.$Fecha1[1].'/'.$Fecha1[0];
	    $fecha = str_replace('/','',$fechaem);
	    $ruc=$_SESSION['INGRESO']['RUC'];
	    $numfac=$this->generaCeros($numfac, '9');
	    $emi='1';
	    $nume='12345678';
	    $ambiente=$_SESSION['INGRESO']["Ambiente"];

	    $Clave = $fecha.$tipo_com.$ruc.$ambiente.$serie.$numfac.$nume.$emi;	
	    $dig=$this->digito_verificador($Clave);

	    // print_r($Clave.$dig);
	    return $Clave.$dig;
	}

	function Autorizar_factura_o_liquidacion($parametros)
	{
		// 1 para autorizados
	    //-1 para no autorizados y devueltas
	    // 2 para devueltas
	    // texto del erro en forma de matris
		$cabecera['ambiente']=$_SESSION['INGRESO']['Ambiente'];
	    $cabecera['ruta_ce']=$_SESSION['INGRESO']['Ruta_Certificado'];
	    $cabecera['clave_ce']=$_SESSION['INGRESO']['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($_SESSION['INGRESO']['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($_SESSION['INGRESO']['Razon_Social']);
	    $cabecera['ruc_principal']=$_SESSION['INGRESO']['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($_SESSION['INGRESO']['Direccion']);
	    $cabecera['Entidad'] = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
	   
	    if(isset($parametros['serie'])){
	    	$cabecera['serie']=$parametros['serie'];
	    	$cabecera['esta']=substr($parametros['serie'],0,3); 
	    	$cabecera['pto_e']=substr($parametros['serie'],3,5); 	    
	    }else if(isset($parametros['Serie']))
	    {	    	
	    	$parametros['serie'] = $parametros['Serie'];
	    	$cabecera['serie']=$parametros['Serie'];
	    	$cabecera['esta']=substr($parametros['Serie'],0,3); 
	   		$cabecera['pto_e']=substr($parametros['Serie'],3,5); 	    
	    }

	    if(isset($parametros['num_fac'])){
	    	$cabecera['factura']=$parametros['num_fac'];
	    }else if(isset($parametros['FacturaNo']))
	    {	    	
	    	$cabecera['factura']=$parametros['FacturaNo'];
	    }
	    else if(isset($parametros['Factura']))
	    {	    	
	    	$cabecera['factura']=$parametros['Factura'];
	    }	    
	    $cabecera['item']=$_SESSION['INGRESO']['item'];

	    if(isset($parametros['tc'])){
	    	$cabecera['tc']=$parametros['tc'];
	    }else if(isset($parametros['TC']))
	    {	    	
	    	$cabecera['tc']=$parametros['TC'];
	    }

	    if(isset($parametros['cod_doc'])){
	    	$cabecera['cod_doc']=$parametros['cod_doc'];
	    }

	    $cabecera['periodo']=$_SESSION['INGRESO']['periodo'];
		if($cabecera['tc']=='LC')
		{
			$cabecera['cod_doc']='03';
		}else if($cabecera['tc']=='FA')
		{
			$cabecera['cod_doc']='01';
		}

		//sucursal
		 $sucursal = $this->catalogo_lineas($cabecera['tc'],$cabecera['serie']);
		 if(count($sucursal)>0)
		 {
		 	$cabecera['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
		 	$cabecera['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
		 	$cabecera['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
		 	$cabecera['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
		 	$cabecera['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
		 	$cabecera['Placa_Vehiculo'] ='.';
		 	$cabecera['Cta_Establecimiento'] = '.';
		 	if(isset($sucursal[0]['Placa_Vehiculo']))
		 	{
		 		$cabecera['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
		 	}
		 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
		 		$cabecera['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
		 	}		 	
		 }
				//datos de factura
	    		$datos_fac = $this->datos_factura($cabecera['serie'],$cabecera['factura'],$cabecera['tc']);
	    		// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$datos_fac[0]['RUC_CI'];
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= $datos_fac[0]['Sin_IVA'];
				$cabecera['Descuento'] = $datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'];
				$cabecera['baseImponible'] = $datos_fac[0]['Sin_IVA']+$cabecera['Descuento'];
				$cabecera['Porc_IVA'] = $datos_fac[0]['Porc_IVA'];
				$cabecera['Con_IVA'] = $datos_fac[0]['Con_IVA'];
				$cabecera['Total_MN'] = $datos_fac[0]['Total_MN'];
				$cabecera['Observacion'] = $datos_fac[0]['Observacion'];
				$cabecera['Nota'] = $datos_fac[0]['Nota'];

				$cabecera['Nota'] = $datos_fac[0]['Nota'];
				if($datos_fac[0]['Tipo_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Tipo_Pago'];
				}
				$cabecera['Propina']=$datos_fac[0]['Propina'];
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva'] = $cabecera['Sin_IVA']-$datos_fac[0]['Desc_0'];
				$cabecera['baseImponibleConIva'] = $cabecera['Con_IVA']-$datos_fac[0]['Desc_X'];
				$cabecera['totalSinImpuestos'] = $cabecera['Sin_IVA']+$cabecera['Con_IVA'] - $cabecera['Descuento'];
				$cabecera['IVA'] = $datos_fac[0]['IVA'];
				$cabecera['descuentoAdicional']=0;
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';
				// print_r($cabecera);die();

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = $this->digito_verificadorf($datos_fac[0]['RUC_CI'],1);
			      	switch ($cod_veri) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'O':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }
			    $cabecera['codigoPorcentaje']=0;
			    if((floatval($cabecera['Porc_IVA'])*100)>12)
			    {
			       $cabecera['codigoPorcentaje']=3;
			    }else
			    {
			      $cabecera['codigoPorcentaje']=2;
			    }
			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {			    	
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $value['Cantidad'];
				    $detalle[$key]['Precio'] = $value['Precio'];
				    $detalle[$key]['descuento'] = $value['Total_Desc']+$value['Total_Desc2'];
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] = ($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = number_format($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $value['Porc_IVA'];
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));		

			    $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	    		$linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
			    // print_r($cabecera);print_r($detalle);die();
			    $cabecera['ClaveAcceso'] =$this->Clave_acceso($parametros['Fecha'],$cabecera['cod_doc'],$parametros['serie'],$parametros['FacturaNo']);
		
	            
	           $xml = $this->generar_xml($cabecera,$detalle);
	           // print_r('expression');
	           // die();

	           if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$cabecera['ClaveAcceso'],
	           	 	 generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$cabecera['ClaveAcceso'],
	           	 		$linkSriAutorizacion);
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$cabecera['ClaveAcceso'],
			   		 		$linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso'],$linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			// print('dd');
			   		 			$resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],$cabecera['Fecha']);
			   		 			return  $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// print_r('expressiondd');die();
			   		 	if($validar_autorizado==1)
			   		 	{
			   		 		 $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],$cabecera['Fecha']);
			   		 	}
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
	           	//RETORNA SI FALLA EL GENERAR EL XML o si ya esta en la carpeta de autorizados
	           	$this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],$cabecera['Fecha']);
	           	return $xml;
	           }

	           // print_r($respuesta);die();
	}

	function Autorizar($parametros)
	{

		// print_r($parametros);die();
		/*
			retorna entre 1 2 3 4
			1 Este documento electronico autorizado
			2 XML devuelto
			3 Este documento electronico ya esta autorizado
			4 sri intermitente

			en el caso de que la respuesta sea una "c" revisar el el firmado si el nombre del certidicado es
		*/		
		$cabecera['ambiente']=$_SESSION['INGRESO']['Ambiente'];
	    $cabecera['ruta_ce']=$_SESSION['INGRESO']['Ruta_Certificado'];
	    $cabecera['clave_ce']=$_SESSION['INGRESO']['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($_SESSION['INGRESO']['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($_SESSION['INGRESO']['Razon_Social']);
	    $cabecera['ruc_principal']=$_SESSION['INGRESO']['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($_SESSION['INGRESO']['Direccion']);
	    $cabecera['Entidad'] = $_SESSION['INGRESO']['IDEntidad'];
	    if(isset($parametros['serie'])){
	    	$cabecera['serie']=$parametros['serie'];
	    	$cabecera['esta']=substr($parametros['serie'],0,3); 
	    	$cabecera['pto_e']=substr($parametros['serie'],3,5); 	    
	    }else if(isset($parametros['Serie']))
	    {	    	
	    	$cabecera['serie']=$parametros['Serie'];
	    	$cabecera['esta']=substr($parametros['Serie'],0,3); 
	   		$cabecera['pto_e']=substr($parametros['Serie'],3,5); 	    
	    }

	    if(isset($parametros['num_fac'])){
	    	$cabecera['factura']=$parametros['num_fac'];
	    }else if(isset($parametros['FacturaNo']))
	    {	    	
	    	$cabecera['factura']=$parametros['FacturaNo'];
	    }
	    else if(isset($parametros['Factura']))
	    {	    	
	    	$cabecera['factura']=$parametros['Factura'];
	    }	    
	    $cabecera['item']=$_SESSION['INGRESO']['item'];

	    if(isset($parametros['tc'])){
	    	$cabecera['tc']=$parametros['tc'];
	    }else if(isset($parametros['TC']))
	    {	    	
	    	$cabecera['tc']=$parametros['TC'];
	    }

	    if(isset($parametros['cod_doc'])){   	

	    	$cabecera['cod_doc']=$parametros['cod_doc'];
	    }

	    // print_r($cabecera);die();

	    $cabecera['periodo']=$_SESSION['INGRESO']['periodo'];
		if($cabecera['tc']=='LC')
		{
			$cabecera['cod_doc']='03';
		}else if($cabecera['tc']=='FA')
		{
			$cabecera['cod_doc']='01';
		}

		// print_r($parametros);die();

	    if($cabecera['cod_doc']=='01')
	    {
	    	//datos de factura
	    	$datos_fac = $this->datos_factura($cabecera['serie'],$cabecera['factura'],$cabecera['tc']);
	    	// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$datos_fac[0]['RUC_CI'];
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= $datos_fac[0]['Sin_IVA'];
				$cabecera['Descuento'] = $datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'];
				$cabecera['baseImponible'] = $datos_fac[0]['Sin_IVA']+$cabecera['Descuento'];
				$cabecera['Porc_IVA'] = $datos_fac[0]['Porc_IVA'];
				$cabecera['Con_IVA'] = $datos_fac[0]['Con_IVA'];
				$cabecera['Total_MN'] = $datos_fac[0]['Total_MN'];
				if($datos_fac[0]['Forma_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Forma_Pago'];
				}
				$cabecera['Propina']=$datos_fac[0]['Propina'];
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva'] = $cabecera['Sin_IVA']-$datos_fac[0]['Desc_0'];
				$cabecera['baseImponibleConIva'] = $cabecera['Con_IVA']-$datos_fac[0]['Desc_X'];
				$cabecera['totalSinImpuestos'] = $cabecera['Sin_IVA']+$cabecera['Con_IVA'] - $cabecera['Descuento'];
				$cabecera['IVA'] = $datos_fac[0]['IVA'];
				$cabecera['descuentoAdicional']=0;
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';

				// print_r($cabecera);die();

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = $this->digito_verificadorf($datos_fac[0]['RUC_CI'],1);
			      	switch ($cod_veri) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'O':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }
			    $cabecera['codigoPorcentaje']=0;
			    if((floatval($cabecera['Porc_IVA'])*100)>12)
			    {
			       $cabecera['codigoPorcentaje']=3;
			    }else
			    {
			      $cabecera['codigoPorcentaje']=2;
			    }
			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {			    	
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $value['Cantidad'];
				    $detalle[$key]['Precio'] = $value['Precio'];
				    $detalle[$key]['descuento'] = $value['Total_Desc']+$value['Total_Desc2'];
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] = ($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = number_format($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $value['Porc_IVA'];
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));
			    // print_r($cabecera);print_r($detalle);die();
	            
	           $respuesta = $this->generar_xml($cabecera,$detalle);

	           $num_res = count($respuesta);
	           if($num_res>=2)
	           {
	           	// print_r($respuesta);die();
	           	if($num_res!=2)
	           	{
	           	 $estado = explode(' ', $respuesta[2]);
		           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO' || $estado[2]=='AUTORIZADO')
		           	 {
		           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion'],$cabecera['fechaem']);
		           	 	if($respuesta==1)
		           	 	{
		           	 	  return array('respuesta'=>1);
		           	 	}
		           	 }else
		           	 {

		           	   $compro = explode('FACTURA', $respuesta[2]);
			           $entidad= $_SESSION['INGRESO']['item'];
		           	   if(count($compro)>1)
		           	   {
			           	   $url_No_autorizados ='../../comprobantes/entidades/entidad_'.$entidad."/CE".$entidad.'/No_autorizados/';
			           	   $resp = array('respuesta'=>2,'ar'=>trim($compro[0]).'.xml','url'=>$url_No_autorizados);
			           	 	return $resp;
		           	  	}else
		           	  	{
		           	  		$compro = explode('null', $respuesta[2]);
		           	  		$url_No_autorizados ='../../comprobantes/entidades/entidad_'.$entidad."/CE".$entidad.'/No_autorizados/';
		           	    	$resp = array('respuesta'=>2,'ar'=>trim($compro[0]).'.xml','url'=>$url_No_autorizados);
		           	 		return $resp;	

		           	  	}
		           	 }
	           	}else
	           	{
	           	 $estado = explode(' ', $respuesta[1]);
	           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO' || $estado[2]=='AUTORIZADO')
	           	 {
	           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion'],$cabecera['fechaem']);
	           	 	if($respuesta==1)
	           	 	{
	           	 	  return array('respuesta'=>1);
	           	 	}
	           	 }
	           	 if($estado[1].' '.$estado[2].' '.$estado[3]=='FACTURA NO PROCESADOEl')
	           	 {
	           	 	 // El comprobante fue enviado, está pendiente de autorización 0110202101070216417900110010010000001631234567811 FACTURA NO PROCESADOEl archivo no tiene autorizaciones relacionadas =>mensaje que nos envia el .jar cuando no tiene conexion con el sri
	           	 	 return array('respuesta'=>4);
	           	 }

	           	}

	           }else
	           {
	           	if ($respuesta) {
	           		# code...
	           	}
	           	if($respuesta[1]=='Autorizado')
	           	{
	           		return array('respuesta'=>3);

	           	}else{
	           		$resp = utf8_encode($respuesta[1]);
	           		return $resp;
	           	}
	           }

	    }
	    if($cabecera['cod_doc']=='03')
	    {
	    	//datos de factura
	    	$datos_fac = $this->datos_factura($cabecera['serie'],$cabecera['factura'],$cabecera['tc']);
	    	// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$datos_fac[0]['RUC_CI'];
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= $datos_fac[0]['Sin_IVA'];
				$cabecera['Descuento'] = $datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'];
				$cabecera['baseImponible'] = $datos_fac[0]['Sin_IVA']+$cabecera['Descuento'];
				$cabecera['Porc_IVA'] = $datos_fac[0]['Porc_IVA'];
				$cabecera['Con_IVA'] = $datos_fac[0]['Con_IVA'];
				$cabecera['Total_MN'] = $datos_fac[0]['Total_MN'];
				if($datos_fac[0]['Forma_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Forma_Pago'];
				}
				$cabecera['Propina']=$datos_fac[0]['Propina'];
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva'] = $cabecera['Sin_IVA']-$datos_fac[0]['Desc_0'];
				$cabecera['baseImponibleConIva'] = $cabecera['Con_IVA']-$datos_fac[0]['Desc_X'];
				$cabecera['totalSinImpuestos'] = $cabecera['Sin_IVA']+$cabecera['Con_IVA'] - $cabecera['Descuento'];
				$cabecera['IVA'] = $datos_fac[0]['IVA'];
				$cabecera['descuentoAdicional']=0;
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';

				// print_r($cabecera);die();

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = $this->digito_verificadorf($datos_fac[0]['RUC_CI'],1);
			      	switch ($cod_veri) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'O':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }
			    $cabecera['codigoPorcentaje']=0;
			    if((floatval($cabecera['Porc_IVA'])*100)>12)
			    {
			       $cabecera['codigoPorcentaje']=3;
			    }else
			    {
			      $cabecera['codigoPorcentaje']=2;
			    }
			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {			    	
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $value['Cantidad'];
				    $detalle[$key]['Precio'] = $value['Precio'];
				    $detalle[$key]['descuento'] = $value['Total_Desc']+$value['Total_Desc2'];
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] = ($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = number_format($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $value['Porc_IVA'];
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));
			    // print_r($cabecera);print_r($detalle);die();
	            
	           $respuesta = $this->generar_xml($cabecera,$detalle);
	           // print_r($respuesta);die();

	           $num_res = count($respuesta);
	           if($num_res>=2)
	           {
	           	// print_r($respuesta);die();
	           	if($num_res!=2)
	           	{
	           	 $estado = explode(' ', $respuesta[2]);
		           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO' || $estado[2]=='AUTORIZADO')
		           	 {
		           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion'],$cabecera['fechaem']);
		           	 	if($respuesta==1)
		           	 	{
		           	 	  return array('respuesta'=>1);
		           	 	}
		           	 }else
		           	 {

		           	   $compro = explode('FACTURA', $respuesta[2]);
			           $entidad= $_SESSION['INGRESO']['item'];
		           	   if(count($compro)>1)
		           	   {
			           	   $url_No_autorizados ='../../comprobantes/entidades/entidad_'.$entidad."/CE".$entidad.'/No_autorizados/';
			           	   $resp = array('respuesta'=>2,'ar'=>trim($compro[0]).'.xml','url'=>$url_No_autorizados);
			           	 	return $resp;
		           	  	}else
		           	  	{
		           	  		$compro = explode('null', $respuesta[2]);
		           	  		$url_No_autorizados ='../../comprobantes/entidades/entidad_'.$entidad."/CE".$entidad.'/No_autorizados/';
		           	    	$resp = array('respuesta'=>2,'ar'=>trim($compro[0]).'.xml','url'=>$url_No_autorizados);
		           	 		return $resp;	

		           	  	}
		           	 }
	           	}else
	           	{
	           	 $estado = explode(' ', $respuesta[1]);
	           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO' || $estado[2]=='AUTORIZADO')
	           	 {
	           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion'],$cabecera['fechaem']);
	           	 	if($respuesta==1)
	           	 	{
	           	 	  return array('respuesta'=>1);
	           	 	}
	           	 }
	           	 if($estado[1].' '.$estado[2].' '.$estado[3]=='FACTURA NO PROCESADOEl')
	           	 {
	           	 	 // El comprobante fue enviado, está pendiente de autorización 0110202101070216417900110010010000001631234567811 FACTURA NO PROCESADOEl archivo no tiene autorizaciones relacionadas =>mensaje que nos envia el .jar cuando no tiene conexion con el sri
	           	 	 return array('respuesta'=>4);
	           	 }

	           	}

	           }else
	           {
	           	if ($respuesta) {
	           		# code...
	           	}
	           	if($respuesta[1]=='Autorizado')
	           	{
	           		return array('respuesta'=>3);

	           	}else{
	           		$resp = utf8_encode($respuesta[1]);
	           		return $resp;
	           	}
	           }

	    }
	}

	function datos_factura($serie,$fact,$tc)
	{
		// $con = $this->conn->conexion();
		$sql = "SELECT * From Facturas WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND TC = '".$tc."' 
		AND Serie = '".$serie."' 
		AND Factura = ".$fact." 
		AND LEN(Autorizacion) = 13 AND T <> 'A' ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function retencion_compras($numero,$tipoCom)
    {
    	$cid = $this->conn;
		$result = array();
        $sql = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Telefono,C.Email,TC.* 
        FROM Trans_Compras As TC, Clientes As C 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."' 
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.Numero = ".$numero." 
        AND TC.TP = '".$tipoCom."' 
        AND LEN(TC.AutRetencion) = 13 
        AND TC.IdProv = C.Codigo 
        ORDER BY Serie_Retencion,SecRetencion ";
        // print_r($sql);die();
         $result = $this->db->datos($sql);
	     return $result;
    }


	function datos_cliente($codigo=false,$ci_ruc = false)
	{

		// $con = $this->conn->conexion();
		$sql = "SELECT * From Clientes WHERE 1=1";
		if($codigo)
			{
				$sql.=" And Codigo = '".$codigo."'";
			}
			if($ci_ruc)
			{
				$sql.=" And CI_RUC = '".$ci_ruc."'";
			}
		// print_r($sql);die();
		// $stmt = sqlsrv_query($con, $sql);
	 //   if( $stmt === false)  
	 //   {  
		//  echo "Error en consulta PA.\n";  
		//  die( print_r( sqlsrv_errors(), true));  
	 //   }
	 //   $datos = array();
	 //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		// 	{
		// 		$datos[] = $row;
	 //        }
	 //        // print_r($datos);die();
	 //        return $datos;
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function datos_cliente_todo($codigo=false,$ci_ruc=false)
	{
		$sql = "SELECT * From Clientes WHERE 1=1 ";

		if($codigo)
		{
			$sql.=" AND Codigo = '".$codigo."'";
		}
		if($ci_ruc)
		{
			$sql.= " AND CI_RUC = '".$ci_ruc."'";
		}
		// print_r($sql);die();
		// $stmt = sqlsrv_query($con, $sql);
	 //   if( $stmt === false)  
	 //   {  
		//  echo "Error en consulta PA.\n";  
		//  die( print_r( sqlsrv_errors(), true));  
	 //   }
	 //   $datos = array();
	 //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		// 	{
		// 		$datos[] = $row;
	 //        }
	 //        // print_r($datos);die();
	 //        return $datos;
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function detalle_factura($serie,$factura,$autorizacion,$tc)
	{
		// $con = $this->conn->conexion();
		$sql="SELECT DF.*,CP.Reg_Sanitario,CP.Marca 
		FROM Detalle_Factura As DF, Catalogo_Productos As CP
		 WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
		    AND DF.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		    AND DF.TC = '".$tc."'
		    AND DF.Serie = '".$serie."' 
			AND DF.Autorizacion = '".$autorizacion."' 
			AND DF.Factura = '".$factura."' 
			AND LEN(DF.Autorizacion) >= 13 
			AND DF.T <> 'A' 
			AND DF.Item = CP.Item 
			AND DF.Periodo = CP.Periodo 
			AND DF.Codigo = CP.Codigo_Inv 
			ORDER BY DF.ID,DF.Codigo;";

			// print_r($sql);die();
			$datos = $this->db->datos($sql);
	        return $datos;
	}

	function datos_producto($codigo)
	{
		$sql="SELECT * from Catalogo_Productos WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo_Inv = '".$codigo."';";
		
		$datos = $this->db->datos($sql);
		return $datos;
	}

    function digito_verificadorf($ruc,$tipo=null,$pag=null,$idMen=null,$item=null)
    {
		$DigStr = "";
		$VecDig = "";
		$Dig3 = "";
		$sSQLRUC = "";
		$CodigoEmp = "";
		$Producto = "";
		$SumaDig = "";
		$NumDig = "";
		$ValDig = "";
		$TipoModulo = "";
		$CodigoRUC = "";
		$Residuo = "";
		//echo $ruc.' ';
		$Dig3 = substr($ruc, 2, 1);
		//echo $Dig3;
		//$Codigo_RUC_CI = substr($ruc, 0, 10);
		//echo $Dig3.' '.$Codigo_RUC_CI ;
		$Tipo_Beneficiario = "P";
		//$NumEmpresa='001';
		$NumEmpresa=$item;
		//echo $item.' dddvc '.$NumEmpresa;
		$Codigo_RUC_CI = $NumEmpresa . "0000001";
		$Digito_Verificador = "-";
		$RUC_CI = $ruc;
		$RUC_Natural = False;
		//echo $Codigo_RUC_CI;
		//die();
		if($ruc == "9999999999999" )
		{
			$Tipo_Beneficiario = "R";
			$Codigo_RUC_CI = substr($ruc, 0, 10);
			$Digito_Verificador = 9;
			$DigStr = "9";
			//echo ' ccc '.$Codigo_RUC_CI;
			//die();
		}
		else
		{
			$DigStr = $ruc;
			$TipoBenef = "P";
			$VecDig = "000000000";
			$TipoModulo = 1;
			If (is_numeric($ruc) And $ruc <= 0)
			{
				$Codigo_RUC_CI = $NumEmpresa & "0000001";
			}
			Else
			{
				//es cedula
				if(strlen($ruc)==10 and is_numeric($ruc))
				{
					$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
					$arr1 = str_split($ruc);
					$resu = array();
					$resu1=0;
					$coe1=0;
					$pro='';
					$ter='';
					$TipoModulo=10;
					//validador
					$ban=0;
					for($jj=0;$jj<(strlen($ruc));$jj++)
					{
						//echo $arr1[$jj].' -- '.$jj.' cc ';
						//validar los dos primeros registros
						if($jj==0 or $jj==1)
						{
							$pro=$pro.$arr1[$jj];
						}
						if($jj==2)
						{
							$ter=$arr1[$jj];
						}
						//operacion suma
						if($jj<=(strlen($ruc)-2))
						{
							$resu[$jj]=$coe[$jj]*$arr1[$jj];
							if($resu[$jj]>=10)
							{
								$resu[$jj]=$resu[$jj]-9;
							}
							//suma
							$resu1=$resu[$jj]+$resu1;
						}
						//ultimo digito
						if($jj==(strlen($ruc)-1))
						{
							//echo " entro ";
							$coe1=$arr1[$jj];
						}
						
					}
					//verificamos los dos primeros registros
					if($pro>=24)
					{
						//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
						$ban=1;
					}
					//verificamos el tercer registros
					if($ter>6)
					{
						//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
						$ban=1;
					}
					//partimos string
					$arr2 = str_split($resu1);
					for($jj=0;$jj<(strlen($resu1));$jj++)
					{
						if($jj==0)
						{
							$arr2[$jj]=$arr2[$jj]+1;
						}
					}
					//aumentamos a la siguiente decena
					$resu2=$arr2[0].'0';
					//resultado del ultimo coeficioente
					$resu3 = $resu2- $resu1;
					$Residuo = $resu1 % $TipoModulo;
					//echo ' dsdsd '.$Residuo;
					//die();
					If ($Residuo == 0)
					{
					  $Digito_Verificador = "0";
					}
					Else
					{
					   $Residuo = $TipoModulo - $Residuo;
					   $Digito_Verificador = $Residuo;
					}
					//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
					if($ban==0)
					{
						If ($Digito_Verificador == substr($ruc, 9, 1))
						{
							$Tipo_Beneficiario = "C";
						}	
					}					
				}
				else
				{
					//caso ruc
					if(strlen($ruc)==13 and is_numeric($ruc))
					{
						//caso ruc ecuatorianos de extrangeros
						$Tipo_Beneficiario='O';
						if ($Dig3 == 6 )
						{
							$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							$TipoModulo=10;
							//validador
							$ban=0;
							for($jj=0;$jj<(count($coe));$jj++)
							{
								//echo $arr1[$jj].' -- '.$jj.' cc ';
								//validar los dos primeros registros
								if($jj==0 or $jj==1)
								{
									$pro=$pro.$arr1[$jj];
								}
								if($jj==2)
								{
									$ter=$arr1[$jj];
								}
								//operacion suma
								if($jj<=(count($coe)-2))
								{
									$resu[$jj]=$coe[$jj]*$arr1[$jj];
									if($resu[$jj]>=10)
									{
										$resu[$jj]=$resu[$jj]-9;
									}
									//suma
									$resu1=$resu[$jj]+$resu1;
								}
								//ultimo digito
								if($jj==(count($coe)-1))
								{
									//echo " entro ";
									$coe1=$arr1[$jj];
								}
								
							}
							//verificamos los dos primeros registros
							if($pro>=24)
							{
								//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
								$ban=1;
							}
							//verificamos el tercer registros
							if($ter>6)
							{
								//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
								$ban=1;
							}
							//partimos string
							$arr2 = str_split($resu1);
							for($jj=0;$jj<(strlen($resu1));$jj++)
							{
								if($jj==0)
								{
									$arr2[$jj]=$arr2[$jj]+1;
								}
							}
							//aumentamos a la siguiente decena
							$resu2=$arr2[0].'0';
							//resultado del ultimo coeficioente
							$resu3 = $resu2- $resu1;
							$Residuo = $resu1 % $TipoModulo;
							//echo ' dsdsd '.$Residuo;
							//die();
							If ($Residuo == 0)
							{
							  $Digito_Verificador = "0";
							}
							Else
							{
							   $Residuo = $TipoModulo - $Residuo;
							   $Digito_Verificador = $Residuo;
							}
							//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
							if($ban==0)
							{
								If ($Digito_Verificador == substr($ruc, 9, 1))
								{
									$Tipo_Beneficiario = "R";
									$RUC_Natural = True;
								}	
							}	
						}
						if($Tipo_Beneficiario=='O')
						{
							$TipoModulo = 11;
							//echo $Dig3.' qmm ';
							if (($Dig3 <= 5) and ($Dig3 >= 0))
							{
								$TipoModulo = 10;
								$TipoModulo1=9;
								$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
								$VecDig = "212121212";
								//echo " aquiii 1 ";
							}
							else
							{
								if ($Dig3 == 6)
								{
									$coe = array("3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=8;
									$VecDig = "32765432";
									//echo " aquiii 2 ";
								}
								else
								{
									if($Dig3 == 9)
									{
										$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
										$TipoModulo1=9;
										$VecDig = "432765432";
										//echo " aquiii 3 ";/
									}
									else
									{
										$VecDig = "222222222";
										$TipoModulo1=9;
										//echo " aquiii 4 ";
										$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
									}
								}
							}
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							$ban=0;
							for($jj=0;$jj<($TipoModulo1);$jj++)
							{
								if($jj==0 or $jj==1)
								{
									$pro=$pro.$arr1[$jj];
								}
								if($jj==2)
								{
									$ter=$arr1[$jj];
								}
								if($jj<=(strlen($ruc)-2))
								{
									$resu[$jj]=$coe[$jj]*$arr1[$jj];
									If (0 <= $Dig3 And $Dig3 <= 5 And $resu[$jj] > 9)
									{
										$resu[$jj]=$resu[$jj]-9;
									}									
									//suma
									$resu1=$resu[$jj]+$resu1;
									
								}
								if($jj==(strlen($ruc)-1))
								{
									//echo " entro ";
									$coe1=$arr1[$jj];
								}
								
							}
							//partimos string
							$arr2 = str_split($resu1);
							for($jj=0;$jj<(strlen($resu1));$jj++)
							{
								if($jj==0)
								{
									$arr2[$jj]=$arr2[$jj]+1;
								}
							}
							//aumentamos a la siguiente decena
							$resu2=$arr2[0].'0';
							//resultado del ultimo coeficioente
							$resu3 = $resu2- $resu1;
							$Residuo = $resu1 % $TipoModulo;
							If ($Residuo == 0)
							{
							  $Digito_Verificador = "0";
							}
							Else
							{
							   $Residuo = $TipoModulo - $Residuo;
							   $Digito_Verificador = $Residuo;
							}
							//echo $Digito_Verificador.' '.$Dig3.' ';
							If ($Dig3 == 6) 
							{
								If ($Digito_Verificador = substr($ruc, 8, 1)) 
								{
									$Tipo_Beneficiario = "R";
								}
							} 
							Else
							{
								If ($Digito_Verificador == substr($ruc, 9, 1))
								{
									$Tipo_Beneficiario = "R";
								}							
							}
							If ($Dig3 < 6 )
							{
								$RUC_Natural = True;
							}
						}
					}
					else
					{
						if(strlen($ruc)==48 and is_numeric($ruc))
						{
							
						}
					}
				}
			}
			if(substr($ruc, 12, 1)!='1')
			{
				$Tipo_Beneficiario = 'O';
			}
			
		}
	    if($tipo==null OR $tipo==0)
	    {	
		   return $Digito_Verificador;
	    }
	    else
	    {
		   return $Tipo_Beneficiario;
	    }
    } 

    function generaCeros($numero, $tamaño=null)
    {
	   //obtengop el largo del numero
	   $largo_numero = strlen($numero);
	   //especifico el largo maximo de la cadena
	   if($tamaño==null)
	   {
		  $largo_maximo = 7;
	   }
	   else
	   {
		 $largo_maximo = $tamaño;
	   }
	   //tomo la cantidad de ceros a agregar
	   $agregar = $largo_maximo - $largo_numero;
	   //agrego los ceros
	   for($i =0; $i<$agregar; $i++){
	     $numero = "0".$numero;
	   }
	   //retorno el valor con ceros
	   return $numero;
    }

    function digito_verificador($cadena)
    {
	    $cadena=trim($cadena);
	    $baseMultiplicador=7;
	    $aux=new SplFixedArray(strlen($cadena));
	    $aux=$aux->toArray();
	    $multiplicador=2;
	    $total=0;
	    $verificador=0;
	    for($i=count($aux)-1;$i>=0;--$i)
	    {
		    $aux[$i] = substr($cadena,$i,1);
		    $aux[$i] *= $multiplicador;
		    $multiplicador++;
		    if($multiplicador > $baseMultiplicador)
		    {
			    $multiplicador=2;
		    }
			$total+=$aux[$i];
	    }
	    $verificador = $total % 11;
	    $verificador = 11 - $verificador;
	    if ($verificador == 10) {
	    	$verificador = 1;
	    }
	    if ($verificador == 11) {
	    	$verificador = 0;
	    }
	    /*if(($total==0)||($total==1)) $verificador=0;
	    else
	    {
		    $verificador=(11-($total%11)==11)?0:11-($total%11);
	    }
	    if($verificador==10)
	    {
		    $verificador=1;
	    }*/
	    return $verificador;
    }


    //parametros clave de acceso
    /*
    1 Fecha de Emisión Numérico             ddmmaaaa       8 Obligatorio <claveAcceso> 
    2 Tipo de Comprobante                   Tabla 3        2 
    3 Número de RUC                         1234567890001  13 
    4 Tipo de Ambiente                      Tabla 4        1 
    5 Serie                                 001001         6 
    6 Número del Comprobante (secuencial)   000000001      9 
    7 Código Numérico                       Numérico       8 
    8 Tipo de Emisión                       Tabla 2        1 
    9 Dígito Verificador (módulo 11 )       Numérico       1*/
function generar_xml($cabecera,$detalle)
{
		$RIMPE =  $this->datos_rimpe();
   	    $entidad=$cabecera['Entidad']; //cambiar por la entidad
	    $empresa=$cabecera['item'];
	    $numero=$this->generaCeros($cabecera['factura'], '9');
	    $ambiente=$cabecera['ambiente'];
	    $codDoc=$cabecera['cod_doc'];
	    $compro = $cabecera['ClaveAcceso'];

        //verificamos si existe una carpeta de la entidad si no existe las creamos
	    $carpeta_entidad = dirname(__DIR__)."/entidades/entidad_".$entidad;
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";
		if(file_exists($carpeta_entidad))
		{
			$carpeta_comprobantes = $carpeta_entidad.'/CE'.$empresa;
			if(file_exists($carpeta_comprobantes))
			{
			  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
			  $carpeta_generados = $carpeta_comprobantes."/Generados";
			  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
			  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Enviados";

				if(!file_exists($carpeta_autorizados))
				{
					mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
				}
				if(!file_exists($carpeta_generados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
				}
				if(!file_exists($carpeta_firmados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
				}
				if(!file_exists($carpeta_no_autori))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
				}
			}else
			{
				mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
				mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
			}
		}else
		{
			   mkdir($carpeta_entidad, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
			   mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);	  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
		}
		

		if(file_exists($carpeta_autorizados.'/'.$compro.'.xml'))
		{
			$respuesta = 'Documento ya autorizado';
			return $respuesta;
		}
	
		// "Create" the document.
		$xml = new DOMDocument( "1.0", "UTF-8" );
		$xml->formatOutput = true;
		$xml->preserveWhiteSpace = false; 

		// Create some elements.
		switch ($codDoc) {
			case '01':
				$xml_factura = $xml->createElement( "factura" );
				break;
			case '07':
				$xml_factura = $xml->createElement( "comprobanteRetencion" );
				break;
			case '03':
				$xml_factura = $xml->createElement( "liquidacionCompra" );
				break;
			case '04':
				$xml_factura = $xml->createElement( "notaCredito" );
				break;
			case '05':
				$xml_factura = $xml->createElement( "notaDebito" );
				break;
			case '06':
				$xml_factura = $xml->createElement( "guiaRemision" );
				break;
			
			
		}
		
		$xml_factura->setAttribute( "id", "comprobante" );
		$xml_factura->setAttribute( "version", "1.1.0" );
		$xml_infoTributaria = $xml->createElement( "infoTributaria" );
		$xml_ambiente = $xml->createElement( "ambiente",$ambiente );
		$xml_tipoEmision = $xml->createElement( "tipoEmision",'1' );
		$xml_razonSocial = $xml->createElement( "razonSocial",$cabecera['razon_social_principal']);
		$xml_nombreComercial = $xml->createElement( "nombreComercial",$cabecera['nom_comercial_principal'] );
		$xml_ruc = $xml->createElement( "ruc",$cabecera['ruc_principal'] );
		$xml_claveAcceso = $xml->createElement( "claveAcceso",$compro);
		$xml_codDoc = $xml->createElement( "codDoc",$codDoc );
		$xml_estab = $xml->createElement( "estab",$cabecera['esta'] );
		$xml_ptoEmi = $xml->createElement( "ptoEmi",$cabecera['pto_e'] );
		$xml_secuencial = $xml->createElement( "secuencial",$numero );
		$xml_dirMatriz = $xml->createElement( "dirMatriz",$cabecera['direccion_principal'] );
			
			
		$xml_infoTributaria->appendChild( $xml_ambiente );
		$xml_infoTributaria->appendChild( $xml_tipoEmision );
		$xml_infoTributaria->appendChild( $xml_razonSocial );
		$xml_infoTributaria->appendChild( $xml_nombreComercial );
		$xml_infoTributaria->appendChild( $xml_ruc );
		$xml_infoTributaria->appendChild( $xml_claveAcceso );
		$xml_infoTributaria->appendChild( $xml_codDoc );
		$xml_infoTributaria->appendChild( $xml_estab );
		$xml_infoTributaria->appendChild( $xml_ptoEmi );
		$xml_infoTributaria->appendChild( $xml_secuencial );
		$xml_infoTributaria->appendChild( $xml_dirMatriz );
		if(count($RIMPE)>0)
		{
			if($RIMPE['@micro']!='.' && $RIMPE['@micro']!='.' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['@micro']);
				$xml_infoTributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['@Agente']!='.' && $RIMPE['@Agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infoTributaria->appendChild( $xml_agenteRetencion);
			}
		}


		$xml_infoFactura = $xml->createElement( "infoFactura" );
		if($codDoc=='03')
		{
			$xml_infoFactura = $xml->createElement( "infoLiquidacionCompra" );
	    }

		$xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera['fechaem'] );

		$estable = $cabecera['esta'];
		$punto = $cabecera['pto_e'];
		if(isset($cabecera['Nombre_Establecimiento']) &&  strlen($cabecera['Nombre_Establecimiento'])>0 && $cabecera['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['Direccion_Establecimiento']);

		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['direccion_principal']);
		}
		
		
		$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",$_SESSION['INGRESO']['Obligado_Conta']);

		$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera['tipoIden'] );
		$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",$cabecera['Razon_Social'] );
		$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera['RUC_CI'] );
		$xml_totalSinImpuestos = $xml->createElement( "totalSinImpuestos",number_format(floatval($cabecera['totalSinImpuestos']),2,'.','') );
		$xml_totalDescuento = $xml->createElement( "totalDescuento",round($cabecera['Descuento'],2) );

		if($codDoc=='03')
		{
			$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionProveedor",$cabecera['tipoIden'] );
			$xml_razonSocialComprador = $xml->createElement( "razonSocialProveedor",$cabecera['Razon_Social'] );
			$xml_identificacionComprador = $xml->createElement( "identificacionProveedor",$cabecera['RUC_CI'] );		
			
		}

		$xml_infoFactura->appendChild( $xml_fechaEmision );
		$xml_infoFactura->appendChild( $xml_dirEstablecimiento );
		$xml_infoFactura->appendChild( $xml_obligadoContabilidad );
		$xml_infoFactura->appendChild( $xml_tipoIdentificacionComprador );
		$xml_infoFactura->appendChild( $xml_razonSocialComprador );
		$xml_infoFactura->appendChild( $xml_identificacionComprador );
		$xml_infoFactura->appendChild( $xml_totalSinImpuestos );
		$xml_infoFactura->appendChild( $xml_totalDescuento );

		$xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
		//sin iva
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
		$xml_codigo = $xml->createElement( "codigo",'2' );
		$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0'  );
		$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",round($cabecera['descuentoAdicional'],2) );
		$xml_baseImponible = $xml->createElement( "baseImponible",round($cabecera['baseImponibleSinIva'],2) );
		//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
		$xml_valor = $xml->createElement( "valor",'0.00' );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		//$xml_totalImpuesto->appendChild( $xml_tarifa );
		$xml_totalImpuesto->appendChild( $xml_valor );
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
		if(($cabecera['Con_IVA']) > 0)
		{
			$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
			$xml_codigo = $xml->createElement( "codigo",'2' );
			$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$cabecera['codigoPorcentaje'] );
			$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",round($cabecera['descuentoAdicional'],2) );
			$xml_baseImponible = $xml->createElement( "baseImponible",round($cabecera['baseImponibleConIva'],2) );
			$xml_tarifa = $xml->createElement( "tarifa",round(($cabecera['Porc_IVA']*100),2) );
			$xml_valor = $xml->createElement( "valor",round($cabecera['IVA'],2) );
			
			$xml_totalImpuesto->appendChild( $xml_codigo );
			$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
			$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
			$xml_totalImpuesto->appendChild( $xml_baseImponible );
			$xml_totalImpuesto->appendChild( $xml_tarifa );
			$xml_totalImpuesto->appendChild( $xml_valor );
			
			$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
		}
		$xml_infoFactura->appendChild( $xml_totalConImpuestos );
		if($codDoc=='01')
		{
			$xml_propina = $xml->createElement( "propina",round($cabecera['Propina'],2) );		
			$xml_infoFactura->appendChild( $xml_propina );
		}

		$xml_importeTotal = $xml->createElement( "importeTotal",round($cabecera['Total_MN'],2) );
		$xml_moneda = $xml->createElement( "moneda",$cabecera['moneda'] );

		$xml_pagos = $xml->createElement("pagos");
		$xml_pago = $xml->createElement("pago");
		   $xml_formapago = $xml->createElement( "formaPago",$cabecera['formaPago']);
		   $xml_total = $xml->createElement( "total",round($cabecera['Total_MN'],2));
		   $xml_pago->appendChild( $xml_formapago );
		   $xml_pago->appendChild($xml_total);

		   $xml_pagos->appendChild($xml_pago);


		$xml_infoFactura->appendChild( $xml_importeTotal );
		$xml_infoFactura->appendChild( $xml_moneda );
		$xml_infoFactura->appendChild( $xml_pagos );


		$xml_detalles = $xml->createElement( "detalles");
		foreach ($detalle as $key => $value) {
			if($value['Cod_Bar'] !='' or $value['Codigo']!='')
			{
				$xml_detalle = $xml->createElement( "detalle" );
				if($cabecera['SP']==true)
				{
					if(strlen($value['Cod_Bar'])>1)
					{
						$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Bar'] );
					}
					$xml_detalle->appendChild( $xml_codigoPrincipal );
					if(strlen($detalle[$i]['Cod_Aux'])>1)
					{
						$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Cod_Aux'] );
					}
					else
					{
						$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Codigo'] );
					}
					$xml_detalle->appendChild( $xml_codigoAuxiliar );

				}else
				{

					$cod_au = str_replace('.','', $value['Codigo']);
					$cod =explode('.', $value['Codigo']);
						$num_partes = count($cod);
						$val_cod = '';
						for ($i=0; $i <$num_partes-1 ; $i++) { 
							$val_cod.= $cod[$i].'.';
							$val_cod = substr($val_cod,0,-1);
						}

					if(strlen($value['Cod_Aux'])>1)
					{
						$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Aux'] );
					}
					else
					{					
						$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Codigo']);
					}
					$xml_detalle->appendChild( $xml_codigoPrincipal );
					// if(strlen($value['Cod_Bar'])>1)
					// {
						// $xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$val_cod);
						// $xml_detalle->appendChild( $xml_codigoAuxiliar );
					// }
				}

				$xml_descripcion = $xml->createElement( "descripcion",preg_replace("/[\r\n|\n|\r]+/", " ",$value['Producto']));
				$xml_unidadMedida = $xml->createElement( "unidadMedida",$cabecera['moneda'] );
				$xml_cantidad = $xml->createElement( "cantidad", number_format($value['Cantidad'],2,'.','') );
				$xml_precioUnitario = $xml->createElement( "precioUnitario",round($value['Precio'],6) );
				$xml_descuento = $xml->createElement( "descuento",round($value['descuento'],2) );
				$xml_precioTotalSinImpuesto = $xml->createElement( "precioTotalSinImpuesto",round($value['SubTotal'],2) );
				
				$xml_detalle->appendChild( $xml_codigoPrincipal );
				
				$xml_detalle->appendChild( $xml_descripcion );
				$xml_detalle->appendChild( $xml_unidadMedida );
				$xml_detalle->appendChild( $xml_cantidad );
				$xml_detalle->appendChild( $xml_precioUnitario );
				$xml_detalle->appendChild( $xml_descuento );
				$xml_detalle->appendChild( $xml_precioTotalSinImpuesto );
				if(strlen($value['Serie_No'])>1)
				{
					$detallesAdicionales = $xml->createElement( "detallesAdicionales" );
					$detAdicional = $xml->createElement( "detAdicional" );
					$detAdicional->setAttribute( "nombre", "Serie_No" );
					$detAdicional->setAttribute( "valor", $value['Serie_No'] );
					$detallesAdicionales->appendChild( $detAdicional );
					$xml_detalle->appendChild( $detallesAdicionales );
				}
				$xml_impuestos = $xml->createElement( "impuestos" );
				$xml_impuesto = $xml->createElement( "impuesto" );
				$xml_codigo = $xml->createElement( "codigo",'2' );

				if($value['Total_IVA'] == 0)
				{
					$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
					$xml_tarifa = $xml->createElement( "tarifa",'0' );
				}
				else
				{
					if(($value['Porc_IVA']*100) > 12)
					{
						$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
					}
					else
					{
						$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
					}
					$xml_tarifa = $xml->createElement( "tarifa",round(($value['Porc_IVA']*100),2) );
					
				}
				$xml_baseImponible = $xml->createElement( "baseImponible",round($value['SubTotal'],2) );
				$xml_valor = $xml->createElement( "valor",round($value['Total_IVA'],2)  );
				$xml_impuesto->appendChild( $xml_codigo );
				$xml_impuesto->appendChild( $xml_codigoPorcentaje );
				$xml_impuesto->appendChild( $xml_tarifa );
				$xml_impuesto->appendChild( $xml_baseImponible );
				$xml_impuesto->appendChild( $xml_valor );
			
				$xml_impuestos->appendChild( $xml_impuesto );
				$xml_detalle->appendChild( $xml_impuestos );
				$xml_detalles->appendChild( $xml_detalle );
			}
		}
		$xml_infoAdicional = $xml->createElement( "infoAdicional");
		//agregar informacion por default
			// $xml_campoAdicional = $xml->createElement( "campoAdicional",'.' );
			// $xml_campoAdicional->setAttribute( "nombre", "adi" );
			// $xml_infoAdicional->appendChild( $xml_campoAdicional );
		if($cabecera['Cliente']<>'.' AND $cabecera['Cliente']!=$cabecera['Razon_Social'])
		{
			if(strlen($cabecera['Cliente'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cliente']);
				$xml_campoAdicional->setAttribute( "nombre", "Beneficiario" );
				$xml_infoAdicional->appendChild($xml_campoAdicional );
			}
			if(strlen($cabecera['Grupo'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Grupo'] );
				$xml_campoAdicional->setAttribute( "nombre", "Ubicacion");
				$xml_infoAdicional->appendChild($xml_campoAdicional );
			}
		}
		if(strlen($cabecera['DireccionC'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['DireccionC'] );
			$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
			$xml_infoAdicional->appendChild($xml_campoAdicional );
		}
		if(strlen($cabecera['TelefonoC'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['TelefonoC'] );
			$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['EmailC'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailC'] );
			$xml_campoAdicional->setAttribute( "nombre", "Email" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['EmailR'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailR'] );
			$xml_campoAdicional->setAttribute( "nombre", "Email2" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Contacto'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Contacto'] );
			$xml_campoAdicional->setAttribute( "nombre", "Referencia" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Orden_Compra'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Orden_Compra'] );
			$xml_campoAdicional->setAttribute( "nombre", "ordenCompra" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Observacion'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Observacion'] );
			$xml_campoAdicional->setAttribute( "nombre", "Observacion" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Nota'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Nota'] );
			$xml_campoAdicional->setAttribute( "nombre", "Nota" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		$estable = $cabecera['esta'];
		$punto = $cabecera['pto_e'];

	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();
	if($estable=='001' && $punto!='001')
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional", $cabecera['esta'].$cabecera['pto_e']);
		$xml_campoAdicional->setAttribute( "nombre", "seriePuntoEmision" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );

		if($cabecera['Nombre_Establecimiento']!='.' && $cabecera['Nombre_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Nombre_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioRazonSocial" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Ruc_Establecimiento']!='' && $cabecera['Ruc_Establecimiento']!='.')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Ruc_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioRUC" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Direccion_Establecimiento']!='.' && $cabecera['Direccion_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Direccion_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioDireccion" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Telefono_Establecimiento']!='.' && $cabecera['Telefono_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Telefono_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioTelefono" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Email_Establecimiento']!='.' && $cabecera['Email_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Email_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioEmail" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Placa_Vehiculo']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Placa_Vehiculo'] );
			$xml_campoAdicional->setAttribute( "nombre", "PlacaVehiculo" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Cta_Establecimiento']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cta_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "CtaEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
	}

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

    if($estable!='001')
	{

		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['esta'].$cabecera['pto_e']);
		$xml_campoAdicional->setAttribute( "nombre", "serieEstablecimiento" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );

		if($cabecera['Nombre_Establecimiento']!='.' && $cabecera['Nombre_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Nombre_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "NombreEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Ruc_Establecimiento']!='' && $cabecera['Ruc_Establecimiento']!='.')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Ruc_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "RUCEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
       /*
       este dato ya viene al inicio del xml en direstablecimiento
		if($cabecera['Direccion_Establecimiento']!='.' && $cabecera['Direccion_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Direccion_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "direccionEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
       */
		if($cabecera['Telefono_Establecimiento']!='.' && $cabecera['Telefono_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Telefono_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "TelefonoEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Email_Establecimiento']!='.' && $cabecera['Email_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Email_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "EmailEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Placa_Vehiculo']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Placa_Vehiculo'] );
			$xml_campoAdicional->setAttribute( "nombre", "PlacaVehiculo" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Cta_Establecimiento']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cta_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "CtaEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}		
	}


		$xml_factura->appendChild( $xml_infoTributaria );
		$xml_factura->appendChild( $xml_infoFactura );
		$xml_factura->appendChild( $xml_detalles );
		$xml_factura->appendChild( $xml_infoAdicional );


		$xml->appendChild($xml_factura);

		$ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Generados';
		if($archivo = fopen($ruta_G.'/'.$compro.'.xml',"w+b"))
		  {
		  	fwrite($archivo,$xml->saveXML());
		  	 
		  	 return 1;
		  }else
		  {
		  	// print_r('sss')
		  	return -1;
		  }	
}


function Autorizar_retencion($parametros)
{
	$datos = $this->retencion_compras($parametros['Numero'],$parametros['TP']);
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

      $aut =  $this->Clave_acceso($TFA[0]['Fecha']->format('Y-m-d'),'07',$TFA[0]["Serie_R"],$TFA[0]["Retencion"]);
      $TFA[0]["ClaveAcceso"]  = $aut;


      $xml = $this->generar_xml_retencion($TFA,$datos=false);

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
	       }



}



function generar_xml_retencion($cabecera,$detalle=false)
{
	$entidad = $_SESSION['INGRESO']['IDEntidad'];
	$empresa = $_SESSION['INGRESO']['item'];
	$this->generar_carpetas($entidad,$empresa);
	$ambiente =$_SESSION['INGRESO']['Ambiente'];
	$RIMPE =  $this->datos_rimpe();
	$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".generaCeros($entidad,3).'/CE'.generaCeros($empresa,3)."/Autorizados";		  
	if(file_exists($carpeta_autorizados.'/'.$cabecera[0]['ClaveAcceso'].'.xml'))
	{
		$respuesta = array('1'=>'Autorizado');
		return $respuesta;
	}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "comprobanteRetencion" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "2.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$_SESSION['INGRESO']['Razon_Social']);
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$_SESSION['INGRESO']['Nombre_Comercial']);
	    $xml_ruc = $xml->createElement("ruc",$_SESSION['INGRESO']['RUC']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera[0]['ClaveAcceso']);
	    $xml_codDoc = $xml->createElement("codDoc",'07');
	    $xml_estab = $xml->createElement("estab",substr($cabecera[0]['Serie_R'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera[0]['Serie_R'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera[0]['Retencion'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$_SESSION['INGRESO']['Direccion']);

		if(count($RIMPE)>0)
		{
			if($RIMPE['@micro']!='.' && $RIMPE['@micro']!='.' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['@micro']);
				$xml_infoTributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['@Agente']!='.' && $RIMPE['@Agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infoTributaria->appendChild( $xml_agenteRetencion);
			}
		}



        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

        // $xml->appendChild($xml_infotributaria);

        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera


	    $xml_infoCompRetencion = $xml->createElement( "infoCompRetencion");
	    $xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera[0]['Fecha']->format('d/m/Y'));
	    $xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($_SESSION['INGRESO']['Direccion']));
	    
	    $xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",$_SESSION['INGRESO']['Obligado_Conta']);
	    switch ($cabecera[0]['TD']) {
	    	case 'R':
	    		if($cabecera[0]['CI_RUC']=="9999999999999"){$cabecera[0]['TD'] = '07';}else{$cabecera[0]['TD']='04';}
	    		break;
	    	case 'C':
	    		$cabecera[0]['TD'] = '05';
	    		break;
	    	case 'P':
	    		$cabecera[0]['TD'] = '06';
	    		break;
	    }
	    $xml_tipoIdentificacionSujetoRetenido = $xml->createElement( "tipoIdentificacionSujetoRetenido",$cabecera[0]['TD']);

        //-----
        $xml_tipoSujetoRetenido = $xml->createElement('tipoSujetoRetenido','01');
	    $xml_parterel  = $xml->createElement("parteRel",'NO');


//-----------
	    $xml_razonSocialSujetoRetenido = $xml->createElement( "razonSocialSujetoRetenido",$cabecera[0]['Cliente']);
	    $xml_identificacionSujetoRetenido = $xml->createElement( "identificacionSujetoRetenido",$cabecera[0]['CI_RUC']);
	    $xml_periodoFiscal = $xml->createElement( "periodoFiscal",$cabecera[0]['Fecha']->format('m/Y'));

    
	    $xml_infoCompRetencion->appendChild($xml_fechaEmision);
	    $xml_infoCompRetencion->appendChild($xml_dirEstablecimiento);
	    // ojo con esto de contribuyente	
	    // if(strlen($ContEspec)>1){
	    // 	$xml_contribuyenteEspecial = $xml->createElement( "contribuyenteEspecial",$ContEspec);
	    //     $xml_infoCompRetencion->appendChild($xml_contribuyenteEspecial);
	    // }
	    $xml_infoCompRetencion->appendChild($xml_obligadoContabilidad);
	    $xml_infoCompRetencion->appendChild($xml_tipoIdentificacionSujetoRetenido);	 
	    $xml_infoCompRetencion->appendChild($xml_tipoSujetoRetenido);   
	    $xml_infoCompRetencion->appendChild($xml_parterel);
	    $xml_infoCompRetencion->appendChild($xml_razonSocialSujetoRetenido);
	    $xml_infoCompRetencion->appendChild($xml_identificacionSujetoRetenido);
	    $xml_infoCompRetencion->appendChild($xml_periodoFiscal);

        $xml_inicio->appendChild($xml_infoCompRetencion);


        $xml_docsSustento = $xml->createElement("docsSustento");
        $xml_docSustento = $xml->createElement("docSustento");

        $xml_codsustento = $xml->createElement("CodSustento",'01');
        $xml_coddocsustento = $xml->createElement("CodDocSustento",'01');
        $xml_numdocsustento = $xml->createElement("numDodSustento",'0010010000009');
        $xml_fechaemisiondocsustento = $xml->createElement("fechaEmisionDocSustento",'30/11/2022');
        $xml_fecharegistrocontable = $xml->createElement("fechaRegistroContable",'30/11/2022');
        $xml_numautodocsustento = $xml->createElement("numAutDocSustento",'01');
        $xml_pagolocext = $xml->createElement("pagoLocExt",'01');
        $xml_totalsinimpuesto = $xml->createElement("totalSinImpuestos",'01');
        $xml_importetotal = $xml->createElement("importeTotal",'01');


         $xml_docSustento->appendChild($xml_codsustento);
         $xml_docSustento->appendChild($xml_coddocsustento);
         $xml_docSustento->appendChild($xml_numdocsustento);
         $xml_docSustento->appendChild($xml_fechaemisiondocsustento);
         $xml_docSustento->appendChild($xml_fecharegistrocontable);
         $xml_docSustento->appendChild($xml_numautodocsustento);
         $xml_docSustento->appendChild($xml_pagolocext);
         $xml_docSustento->appendChild($xml_totalsinimpuesto);
         $xml_docSustento->appendChild($xml_importetotal);




        $xml_impuestodocssustento =$xml->createElement("impuestosDocSustento");
        $xml_impuestodocsustento =$xml->createElement("impuestoDocSustento");

        
        $xml_codimpuestodocsustento = $xml->createElement("codImpuestoDocSustento",'2');
        $xml_codigoprocentaje = $xml->createElement("codigoPorcentaje",'2');
        $xml_baseimponible = $xml->createElement("baseImponible",'20.01');
        $xml_tarifa = $xml->createElement("tarifa",'12');
        $xml_valorimpuesto = $xml->createElement("valorImpuesto",'2.40');

        $xml_impuestodocsustento->appendChild($xml_codimpuestodocsustento);
        $xml_impuestodocsustento->appendChild($xml_codigoprocentaje);
        $xml_impuestodocsustento->appendChild($xml_baseimponible);
        $xml_impuestodocsustento->appendChild($xml_tarifa);
        $xml_impuestodocsustento->appendChild($xml_valorimpuesto);


        $xml_codimpuestodocsustento = $xml->createElement("codImpuestoDocSustento",'2');
        $xml_codigoprocentaje = $xml->createElement("codigoPorcentaje",'0');
        $xml_baseimponible = $xml->createElement("baseImponible",'10.00');
        $xml_tarifa = $xml->createElement("tarifa",'0');
        $xml_valorimpuesto = $xml->createElement("valorImpuesto",'0.00');

        $xml_impuestodocsustento->appendChild($xml_codimpuestodocsustento);
        $xml_impuestodocsustento->appendChild($xml_codigoprocentaje);
        $xml_impuestodocsustento->appendChild($xml_baseimponible);
        $xml_impuestodocsustento->appendChild($xml_tarifa);
        $xml_impuestodocsustento->appendChild($xml_valorimpuesto);

        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento);


        $xml_retenciones =$xml->createElement("Retenciones");
        $xml_retencion =$xml->createElement("Retencion");

        $xml_codigo = $xml->createElement("codigo",'1');
        $xml_codigoretencion = $xml->createElement("codigoRetencion",'312');
        $xml_baseimponible = $xml->createElement("baseImponible",'30.00');
        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",'0.00');
        $xml_valorretenido = $xml->createElement("valorRetenido",'0.00');

        $xml_retencion->appendChild($xml_codigo);
        $xml_retencion->appendChild($xml_codigoretencion);
        $xml_retencion->appendChild($xml_impuestodo);
        $xml_retencion->appendChild($xml_impuestodocsustento);
        $xml_retencion->appendChild($xml_impuestodocsustento);
	    




	    $xml_impuestos = $xml->createElement("impuestos");
	    if($detalle[0]['Porc_Bienes']>0)
	    {
	    	$xml_impuesto = $xml->createElement("impuesto");
	    	$xml_codigo = $xml->createElement("codigo",'2');
	    	switch ($detalle[0]['Porc_Bienes']) {
	    		case '10': $xml_codigoRetencion = $xml->createElement("codigoRetencion",'9');
	    			break;
	    		case '20': $xml_codigoRetencion = $xml->createElement("codigoRetencion",'10');
	    			break;
	    		case '30':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'1');
	    			break;
	    		case '50':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'11');
	    			break;
	    		case '70':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'2');
	    			break;
	    		case '100':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'3');
	    			break;
	    	}
	    	$Total = $detalle[0]["MontoIvaBienes"];
            $Retencion = intval($detalle[0]["Porc_Bienes"]);
            $Valor = number_format(($Total * ($Retencion / 100)), 2);

	    	$xml_baseImponible = $xml->createElement("baseImponible", number_format($Total,2 , '.', ''));
	    	$xml_porcentajeRetener = $xml->createElement("porcentajeRetener",$Retencion);
	    	$xml_valorRetenido = $xml->createElement("valorRetenido",number_format($Valor,2, '.', ''));
	    	$xml_codDocSustento = $xml->createElement("codDocSustento",$cabecera[0]['TipoComprobante']);
	    	$xml_numDocSustento = $xml->createElement("numDocSustento", $cabecera[0]['Serie'].$this->generaCeros($cabecera[0]['Factura'],9));
	    	$xml_fechaEmisionDocSustento = $xml->createElement("fechaEmisionDocSustento",$cabecera[0]['Fecha']->format('d/m/Y'));

	    	$xml_impuesto->appendChild($xml_codigo);
	    	$xml_impuesto->appendChild($xml_codigoRetencion);
	    	$xml_impuesto->appendChild($xml_baseImponible);
	    	$xml_impuesto->appendChild($xml_porcentajeRetener);
	    	$xml_impuesto->appendChild($xml_valorRetenido);
	    	$xml_impuesto->appendChild($xml_codDocSustento);
	    	$xml_impuesto->appendChild($xml_numDocSustento);
	    	$xml_impuesto->appendChild($xml_fechaEmisionDocSustento);


            $xml->appendChild($xml_impuesto);
            $xml_impuestos->appendChild($xml_impuesto);

	    }

	     if($detalle[0]['Porc_Servicios']>0)
	    {
	    	$xml_impuesto = $xml->createElement("impuesto");
	    	$xml_codigo = $xml->createElement("codigo",'2');
	    	switch ($detalle[0]['Porc_Servicios']) {	
	    		case '10': $xml_codigoRetencion = $xml->createElement("codigoRetencion",'9');
	    			break;
	    		case '20': $xml_codigoRetencion = $xml->createElement("codigoRetencion",'10');
	    			break;
	    		case '30':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'1');
	    			break;
	    		case '50':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'11');
	    			break;
	    		case '70':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'2');
	    			break;
	    		case '100':$xml_codigoRetencion = $xml->createElement("codigoRetencion",'3');
	    			break;
	    	}
	    	$Total = $detalle[0]["MontoIvaServicios"];
            $Retencion = intval($detalle[0]["Porc_Servicios"]);
            $Valor = number_format(($Total * ($Retencion / 100)), 2);

	    	$xml_baseImponible = $xml->createElement("baseImponible",number_format($Total,2, '.', ''));
	    	$xml_porcentajeRetener = $xml->createElement("porcentajeRetener",$Retencion);
	    	$xml_valorRetenido = $xml->createElement("valorRetenido",number_format($Valor,2, '.', ''));
	    	$xml_codDocSustento = $xml->createElement("codDocSustento",$cabecera[0]['TipoComprobante']);
	    	$xml_numDocSustento = $xml->createElement("numDocSustento", $cabecera[0]['Serie'].$this->generaCeros($cabecera[0]['Factura'],9));
	    	$xml_fechaEmisionDocSustento = $xml->createElement("fechaEmisionDocSustento",$cabecera[0]['Fecha']->format('d/m/Y'));

	    	$xml_impuesto->appendChild($xml_codigo);
	    	$xml_impuesto->appendChild($xml_codigoRetencion);
	    	$xml_impuesto->appendChild($xml_baseImponible);
	    	$xml_impuesto->appendChild($xml_porcentajeRetener);
	    	$xml_impuesto->appendChild($xml_valorRetenido);
	    	$xml_impuesto->appendChild($xml_codDocSustento);
	    	$xml_impuesto->appendChild($xml_numDocSustento);
	    	$xml_impuesto->appendChild($xml_fechaEmisionDocSustento);


            $xml->appendChild($xml_impuesto);
	        $xml_impuestos->appendChild($xml_impuesto);

	    }


			$con = $this->db->conexion();

			// print_r(expression)

            // 'RETENCIONES AIR
             $sql = "SELECT * 
                   FROM Trans_Air
                   WHERE Item = '".$_SESSION['INGRESO']['item']."'
                   AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                   AND Numero = ".$cabecera[0]['Numero']."
                   AND TP = '".$cabecera[0]['TP']."'
                   AND Tipo_Trans = 'C'
                   AND EstabRetencion = '".substr($cabecera[0]['Serie_R'] ,0, 3)."'
                   AND PtoEmiRetencion = '".substr($cabecera[0]['Serie_R'], 3, 3)."'
                   AND SecRetencion = '".$cabecera[0]['Retencion']."'
                   AND AutRetencion = '".$cabecera[0]['Autorizacion_R']."'
                   ORDER BY ID ";
                   // print_r($sql);die();
                   $result = array();
                   $stmt = sqlsrv_query($con, $sql);
                   if( $stmt === false)  
                   	{
                   		echo "Error en consulta PA.\n";  
                   		die( print_r( sqlsrv_errors(), true)); 
                   	}
                   	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
                   		{
                   			$result[] = $row;
                   	    }

                  foreach ($result as $key => $value) {
                  	// print_r($value);
                  	if(number_format($value['BaseImp'],2,'.','')>0)
                  	{
                  		// print_r($result);
                  	  $xml_impuesto = $xml->createElement("impuesto");
                  	  $xml_codigo = $xml->createElement("codigo",'1');
                  	  $xml_codigoRetencion = $xml->createElement("codigoRetencion",$value['CodRet']);

	    	          $xml_baseImponible = $xml->createElement("baseImponible",number_format($value['BaseImp'],2, '.', ''));
	    	          $xml_porcentajeRetener = $xml->createElement("porcentajeRetener",number_format(($value['Porcentaje']*100),2, '.', ''));
	    	          $xml_valorRetenido = $xml->createElement("valorRetenido",$value['ValRet']);
	    	          $xml_codDocSustento = $xml->createElement("codDocSustento",$cabecera[0]['TipoComprobante']);
	    	          $xml_numDocSustento = $xml->createElement("numDocSustento", $cabecera[0]['Serie'].$this->generaCeros($cabecera[0]['Factura'],9));
	    	          $xml_fechaEmisionDocSustento = $xml->createElement("fechaEmisionDocSustento",$cabecera[0]['Fecha']->format('d/m/Y'));
	    	          $xml_impuesto->appendChild($xml_codigo);
	    	          $xml_impuesto->appendChild($xml_codigoRetencion);
	    	          $xml_impuesto->appendChild($xml_baseImponible);
	    	          $xml_impuesto->appendChild($xml_porcentajeRetener);
	    	          $xml_impuesto->appendChild($xml_valorRetenido);
	    	          $xml_impuesto->appendChild($xml_codDocSustento);
	    	          $xml_impuesto->appendChild($xml_numDocSustento);
	    	          $xml_impuesto->appendChild($xml_fechaEmisionDocSustento);

	    	            $xml->appendChild($xml_impuesto);
	    	            $xml_impuestos->appendChild($xml_impuesto);
	    	        }
                  }

        $xml_inicio->appendChild($xml_impuestos);

        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");

       
        if (strlen($cabecera[0]['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if (strlen($cabecera[0]['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( strlen($cabecera[0]['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['TP'].'-'.$this->generaCeros($cabecera[0]['Numero'],9));
        	 $xml_campoAdicional->setAttribute( "nombre", "Comprobante No");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
         //     $AgenteRetencion ='ssss'; 
         // if ($AgenteRetencion<>'.'){ 
        	//  $xml_campoAdicional = $xml->createElement("campoAdicional",$AgenteRetencion);
        	//  $xml_campoAdicional->setAttribute( "nombre", "Agente de Retencion");
        	//  $xml_infoAdicional->appendChild($xml_campoAdicional);
        	// }
        	$MicroEmpresa = 's';
         if ($MicroEmpresa<>'.'){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",' ');
        	 $xml_campoAdicional->setAttribute( "nombre", "Contribuyente Regimen Microempresas");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

        	$xml_inicio->appendChild($xml_infoAdicional);
        	$xml->appendChild($xml_inicio);

		     $ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera[0]['ClaveAcceso'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	die();
			  	return 1;
			  }else
			  {
			  	return -1;
			  }
}



  function firmar_documento($nom_doc,$entidad,$empresa,$pass,$p12)
    {	

 	    $firmador = dirname(__DIR__).'/SRI/firmar/firmador.jar';
 	    $url_generados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Generados/';
 	    $url_firmados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Firmados/';
 	    $url_rechazado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Rechazados/';
 	    $certificado_1 = dirname(__DIR__).'/certificados/';
 	    if(file_exists($certificado_1.$p12))
	       {
	       	
	       	if(file_exists($url_generados.$nom_doc.".xml"))
	       	{
	       		// print_r("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass);die();

	        	exec("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass, $f);

	        	if(count($f)<6 && !empty($f))
		 		{
		 			return 1;		 		
		 		}else
		 		{		 			
		 			$respuesta = 'Error al generar XML o al firmar';
		 			return $respuesta;          
		        }
		    }else
		    {
		    	$respuesta = 'XML generado no encontrado';
	 			return $respuesta;
		    }
	 	   }else
	 	   {
	 	   		$respuesta = 'No se han encontrado Certificados';
	 			return $respuesta;
	 	   }

 		// $quijoteCliente =  dirname(__DIR__).'/SRI/firmar/QuijoteLuiClient-1.2.1.jar';
 	 //    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/No_autorizados/';
 	 //    $url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados/';

 	 //    $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	 //    $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
 	   
 		
    }

    //comprueba si el xml ya se envio al sri
    // 1 para autorizados
    //-1 para no autorizados
    // 2 para devueltas
    function comprobar_xml_sri($clave_acceso,$link_autorizacion)
    {
    	$entidad =  generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
    	$empresa = $_SESSION['INGRESO']['item'];
    	$comprobar_sri = dirname(__DIR__).'/SRI/firmar/sri_comprobar.jar';
    	$url_autorizado=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados/';
 	    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/No_autorizados/';

    	// print_r("java -jar ".$comprobar_sri." ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion);die();
   		 exec("java -jar ".$comprobar_sri." ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion,$f);   	
   		 // print_r($f);die();
   		 if(empty($f))
   		 {
   		 	return 2;
   		 }

   		 $resp = explode('-',$f[0]);

   		 // print_r($f);
   		 if(count($resp)>1)
   		 {
   		 	//cuando null NO PROCESADO es liquidacion de compras
	   		 if(isset($resp[1]) && $resp[1]=='FACTURA NO PROCESADO' || isset($resp[1]) && $resp[1]=='LIQUIDACION DE COMPRAS NO PROCESADO' || $resp[1] == 'COMPROBANTE DE RETENCION NO PROCESADO')
	   		 {
	   		 	return -1;
	   		 }else if(isset($resp[1]) && $resp[1]=='FACTURA AUTORIZADO' || isset($resp[1]) && $resp[1]=='LIQUIDACION DE COMPRAS AUTORIZADO' || $resp[1] == 'COMPROBANTE DE RETENCION AUTORIZADO')
	   		 {
	   		 	return 1;
	   		 }else
	   		 {
	   			return 'ERROR COMPROBACION -'.$f[0];
	   		 }
	   	}else
	   	{
	   		return 2;
	   	}
    }

    //envia el xml asia el sri
    function enviar_xml_sri($clave_acceso,$url_recepcion)
    {
    	$entidad =  generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
    	$empresa = $_SESSION['INGRESO']['item'];

    	$ruta_firmados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Firmados/';
    	$ruta_enviados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Enviados/';
 	    $ruta_rechazados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Rechazados/';
    	$enviar_sri = dirname(__DIR__).'/SRI/firmar/sri_enviar.jar';

    	if(!file_exists($ruta_firmados.$clave_acceso.'.xml'))
    	{
    		$respuesta = ' XML firmado no encontrado';
	 		return $respuesta;
    	}
    	 // print_r("java -jar ".$enviar_sri." ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviado." ".$ruta_rechazados." ".$url_recepcion);die();
   		 exec("java -jar ".$enviar_sri." ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion,$f);
   		 if(count($f)>0)
   		 {
	   		 $resp = explode('-',$f[0]);
	   		 if($resp[1]=='RECIBIDA')
	   		 {
	   		 	return 1;
	   		 }else if($resp[1]=='DEVUELTA')
	   		 {
	   		 	return 2;
	   		 }else if($resp[1]==null || $resp[1]=='' )
	   		 {
	   		 	//es devuelta
	   		 	return 2;
	   		 }else
	   		 {  
	   		 	return $f;
	   		 }
   		}else
   		{
   			// algo paso
   			return 2;
   		}
    }

    function actualizar_datos_CE($autorizacion,$tc,$serie,$factura,$entidad,$autorizacion_ant,$fecha_emi = false)
    {
    	   $fecha = date('Y-m-d');
    	   if($fecha_emi)
    	   {
    		 $fecha = date("Y-m-d", strtotime(str_replace('/','-',$fecha_emi)));
    	    }
			$con = $this->db->conexion();
			$sql ="UPDATE Facturas SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND TC = '".$tc."' 
			AND Serie = '".$serie."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) = 13 
			AND T <> 'A'; ";
			// print_r($sql);die();
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$sql="UPDATE Detalle_Factura SET Autorizacion='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			 AND TC = '".$tc."' 
			 AND Serie = '".$serie."' 
			AND Autorizacion = '".$autorizacion_ant."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			//echo $sql;
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//modificamos trans abonos
			$sql="UPDATE Trans_Abonos SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			 AND TP = '".$tc."' 
			 AND Serie = '".$serie."' 
			AND Autorizacion = '".$autorizacion_ant."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//creamos trans_documentos
			//echo $ban1[2];
			$url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$_SESSION['INGRESO']['item'].'/Autorizados/'.$autorizacion.'.xml';

			$archivo = fopen($url_autorizado,"rb");
			if( $archivo == false ) 
			{
				echo "Error al abrir el archivo";
			}
			else
			{
				rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
				$cadena2 = fread($archivo, filesize($url_autorizado));  // Leemos hasta el final del archivo
				if( $cadena2 == false )
					echo "Error al leer el archivo";
				else
				{
					//echo "<p>\$contenido1 es: [".$cadena1."]</p>";
					//echo "<p>\$contenido2 es: [".$cadena2."]</p>";
				}
			}
			// Cerrar el archivo:
			fclose($archivo);
			$sql="INSERT INTO Trans_Documentos
		    (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,Fecha,X)
			 VALUES
		    ('".$_SESSION['INGRESO']['item']."' 
		    ,'".$_SESSION['INGRESO']['periodo']."' 
		    ,'".$autorizacion."'
		    ,'".$cadena2."'
		    ,'".$tc."' 
		    ,'".$serie."' 
		    ,".$factura." 
		    ,'".$fecha."'
			,'.');";
			//echo $sql;
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			return 1;
    }
    function quitar_carac($query)
    {
    	$buscar = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','Ñ','ñ','/','?','�','-');
    	$remplaza = array('a','e','i','o','u','A','E','I','O','U','N','n','','','','');
    	$corregido = str_replace($buscar, $remplaza, $query);
    	 // print_r($corregido);
    	return trim($corregido);

    }

    function datos_rimpe()
    {
    	$tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);
    	// print_r($sql);die();
    	return $tipo_con;
    }

  function catalogo_lineas($TC,$SerieFactura)
  {
  	$sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND Autorizacion = '".$_SESSION['INGRESO']['RUC']."'
         AND TL <> 0
         ORDER BY Codigo ";
         // print_r($sql);die();
	  return $this->db->datos($sql);

  }

  function catalogo_lineas_sri($TC,$SerieFactura,$emision,$vencimiento,$electronico=false)
  {
  	$sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND CONVERT(DATE,Fecha) <= '".$emision."'
         AND CONVERT(DATE,Vencimiento) >= '".$vencimiento."'";
         if($electronico)
         {
           $sql.=" AND len(Autorizacion)=13";
         }
         $sql.=" ORDER BY Codigo ";
         // print_r($sql);die();
	  return $this->db->datos($sql);
  }

  function recuperar_cliente_xml_a_factura($documento,$autorizacion,$entidad,$empresa)
  {
  	 $ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados';
		     // print_r($ruta_G);die();
	if($archivo = fopen($ruta_G.'/'.$autorizacion.'.xml',"w+b"))
	  {
	  	fwrite($archivo,$documento);
	  }

  	$texto = file_get_contents($ruta_G.'/'.$autorizacion.'.xml');
  	$texto = str_replace('ï»¿','', $texto);
  	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="no"?>','', $texto);
  	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8"?>','', $texto);
  	$texto = str_replace(']]>','', $texto);
  	$xml = simplexml_load_string($texto);

	$objJsonDocument = json_encode($xml);
    $factura = json_decode($objJsonDocument, TRUE);
    // print_r($factura);die();
    $tributaria = $factura['comprobante']['factura']['infoTributaria'];
    $cabecera = $factura['comprobante']['factura']['infoFactura'];    
    $detalle = $factura['comprobante']['factura']['detalles'];

    // print_r($tributaria);die();



    $serie = $tributaria['estab'].''.$tributaria['ptoEmi'];
    $CI_RUC = $cabecera['identificacionComprador'];
    $codigoC = $this->datos_cliente($codigo=false,$CI_RUC);
    if(count($codigoC)>0)
    {
    	return $codigoC[0]['Codigo'];
    }else
    {
    	return -1;
    }
}


  function recuperar_xml_a_factura($documento,$autorizacion,$entidad,$empresa)
  {
  	$this->generar_carpetas($entidad,$empresa);
  	$respuesta = 1;
  	//busco el archivo xml
  	 $ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados';
		     // print_r($ruta_G);die();
	if($archivo = fopen($ruta_G.'/'.$autorizacion.'.xml',"w+b"))
	  {
	  	fwrite($archivo,$documento);
	  }

  	$texto = file_get_contents($ruta_G.'/'.$autorizacion.'.xml');
  	$texto = str_replace('EN PROCESO','nulo', $texto,$remplazado);
  	if($remplazado>0)
  	{
  		return -2;
  	}

  	$texto = str_replace('ï»¿','', $texto);
  	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="no"?>','', $texto);
  	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8"?>','', $texto);
  	$texto = str_replace(']]>','', $texto);

  	// print_r($texto);die();
  	$xml = simplexml_load_string($texto);


	$objJsonDocument = json_encode($xml);
    $factura = json_decode($objJsonDocument, TRUE);
    // print_r($factura);die();
    $tributaria = $factura['comprobante']['factura']['infoTributaria'];
    $cabecera = $factura['comprobante']['factura']['infoFactura'];    
    $detalle = $factura['comprobante']['factura']['detalles'];

    // print_r($tributaria);die();



    $serie = $tributaria['estab'].''.$tributaria['ptoEmi'];
    $CI_RUC = $cabecera['identificacionComprador'];
    $Fecha = date('Y-m-d',strtotime(str_replace('/','-',$cabecera['fechaEmision'])));
    $codigoC = $this->datos_cliente($codigo=false,$CI_RUC);
    if(count($codigoC)==0)
    {
    	return -1;
    }
    $cliente = Leer_Datos_Cliente_FA($codigoC[0]['Codigo']);
   

    // print_r($cliente);die();

    $CodigoL = '.';
	$CodigoL2 =  $this->catalogo_lineas_sri('FA',$serie,$Fecha,$Fecha,1);
	// print_r($CodigoL2);die();
	if(count($CodigoL2)>0)
	{
	   	$CodigoL = $CodigoL2[0]['Codigo'];
	}

    $A_No = 0;
    $Real3 = 0;
    $Real2 = 0;

    if(isset($detalle['detalle']['codigoPrincipal']))
    {

    	//no se hace nada
    }else
    {
    	$detalle = $detalle['detalle'];
    }
    foreach ($detalle as $key => $value) {   
    	// print_r($value);die();
    	$producto = Leer_Codigo_Inv($value['codigoPrincipal'],$Fecha,$CodBodega='',$CodMarca='');
    	$Real1 = number_format(floatval($value['cantidad']),2,'.','') * number_format($value['precioUnitario'],6,'.','');
    	if($producto['datos']['IVA']!=0){$Real3 = number_format(($Real1 - $Real2) * $_SESSION['INGRESO']['porc'], 2,'.','');}else{$Real3 = 0;}

    	// print_r($producto);
    	// print_r($value);
    	// die();
		$datos[0]['campo'] = "CODIGO"; 
		$datos[0]['dato']  = $value['codigoPrincipal'];
		$datos[1]['campo'] = "CODIGO_L"; 
		$datos[1]['dato']  = $CodigoL;
		$datos[2]['campo'] = "PRODUCTO"; 
		$datos[2]['dato'] = $value['descripcion'];
		$datos[3]['campo']  = "Tipo_Hab"; 
		$datos[3]['dato']  =  '.';
		$datos[4]['campo'] = "CANT"; 
		$datos[4]['dato']  = number_format(floatval($value['cantidad']),2,'.','');
		$datos[5]['campo'] = "PRECIO"; 
		$datos[5]['dato']  = number_format($value['precioUnitario'],6,'.','');
		$datos[6]['campo'] = "TOTAL"; 
		$datos[6]['dato']  = $value['precioTotalSinImpuesto'];
		$datos[7]['campo'] = "Total_IVA"; 
		$datos[7]['dato']  = $Real3;
		$datos[8]['campo'] = "Item"; 
		$datos[8]['dato']  = $empresa;
		$datos[9]['campo'] = "CodigoU"; 
		$datos[9]['dato']  = $_SESSION['INGRESO']['CodigoU'];
		$datos[10]['campo'] = "Codigo_Cliente"; 
		$datos[10]['dato']  = $cliente['CodigoC'];
		$datos[11]['campo'] = "A_No"; 
		$datos[11]['dato']  = $A_No+1;


		$datos[12]['campo'] = "CodBod"; 
		$datos[12]['dato']  ='.';            
		$datos[13]['campo'] = "COSTO";
		$datos[13]['dato']  = $producto['datos']['Costo'];
		if($producto['datos']['Costo']>0)
		{ 	            
		    $datos[14]['campo'] = "Cta_Inv"; 
		    $datos[14]['dato']  = $producto['datos']['Cta_Inventario'];
		    $datos[15]['campo'] = "Cta_Costo"; 
		    $datos[15]['dato']  = $producto['datos']['Cta_Costo_Venta'];
		}
	    if(insert_generico('Asiento_F',$datos)!=null)
    	{
    		$respuesta = -1;
    	};
	}

	return $respuesta;


  }

  function Actualizar_factura($CI_RUC,$FacturaNo,$serie)
  {
  	$digito = digito_verificador_nuevo($CI_RUC);
  	$cli = $this->datos_cliente_todo(false,$CI_RUC);

  	if(count($cli)>0)
  	{
	  	$datosC[0]['campo']='Codigo';
	  	$datosC[0]['dato']=$digito['Codigo'];
	  	$datosC[1]['campo']='TD';
	  	$datosC[1]['dato']=$digito['Tipo'];
	  	$datosC[2]['campo']='FA';
	  	$datosC[2]['dato']=1;

	  	$whereC[0]['campo']='CI_RUC';
	  	$whereC[0]['valor']=$CI_RUC;
	  	$whereC[0]['tipo']='string';
	  	if($cli[0]['TD']=='' || $cli[0]['TD']=='.' ||$cli[0]['Codigo']=='' || $cli[0]['Codigo']=='.')
	  	{
	  		update_generico($datosC,'Clientes',$whereC);
	  	}


	  	$cliente = Leer_Datos_Cliente_FA($digito['Codigo']);
	  	// print_r($cliente);die();
	  	$datosF[0]['campo']='CodigoC';
	  	$datosF[0]['dato']=$cliente['CodigoC'];
	  	$datosF[1]['campo']='TB';
	  	$datosF[1]['dato']=$cliente['TD'];
	  	$datosF[2]['campo']='Razon_Social';
	  	$datosF[2]['dato']=$cliente['Razon_Social'];  	
	  	$datosF[3]['campo']='Direccion_RS';
	  	$datosF[3]['dato']=$cliente['DireccionC'];  	
	  	$datosF[4]['campo']='Telefono_RS';
	  	$datosF[4]['dato']=$cliente['TelefonoC'];

	  	$whereF[0]['campo']='Serie';
	  	$whereF[0]['valor']=$serie;
	  	$whereF[1]['campo']='Factura';
	  	$whereF[1]['valor']=$FacturaNo;
	  	$whereF[2]['campo']='Item';
	  	$whereF[2]['valor']=$_SESSION['INGRESO']['item'];
	  	$whereF[2]['tipo']='string';
	  	$whereF[3]['campo']='Periodo';
	  	$whereF[3]['valor']=$_SESSION['INGRESO']['periodo'];
	  	$whereF[4]['campo']='TC';
	  	$whereF[4]['valor']='FA';

	  	// print_r($datosF);
	  	// print_r($whereF);
	  	// die();
	  	update_generico($datosF,'Facturas',$whereF);

	  	return 1;
	  }else
	  {
	  	return -1;
	  }
  }

  function generar_carpetas($entidad,$empresa)
  {
  	    if(strlen($entidad)<3){$entidad = generaCeros($entidad,3);}
  	    if(strlen($empresa)<3){$empresa = generaCeros($empresa,3);}

  	    $carpeta_entidad = dirname(__DIR__)."/entidades/entidad_".$entidad;
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";
		if(file_exists($carpeta_entidad))
		{
			$carpeta_comprobantes = $carpeta_entidad.'/CE'.$empresa;
			if(file_exists($carpeta_comprobantes))
			{
			  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
			  $carpeta_generados = $carpeta_comprobantes."/Generados";
			  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
			  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Enviados";

				if(!file_exists($carpeta_autorizados))
				{
					mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
				}
				if(!file_exists($carpeta_generados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
				}
				if(!file_exists($carpeta_firmados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
				}
				if(!file_exists($carpeta_no_autori))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
				}
			}else
			{
				mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
				mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
			}
		}else
		{
			   mkdir($carpeta_entidad, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
			   mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);	  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
		}
  }


}

?>