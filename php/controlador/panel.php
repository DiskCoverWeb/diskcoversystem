<?php
//Llamada al modelo
require_once("../modelo/usuario_model.php");
/**
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 * obse: 1.- esta controlador desde un inicio no fue una clase;
 *       2.- se usa el isset si se esta llamdo por ajax
 *       3.- las fuciones realizadas se estan llamando desde otro php (tener cuidado si se quiere borrar algo)
 * 
 * modificcado por javier farinango
 * 
 * 
 */

if(isset($_GET['pantalla']))
{
      $_SESSION['INGRESO']['Height_pantalla'] = $_GET['height'];
      $_SESSION['INGRESO']['width_pantalla'] = $_GET['width'];
}
if(isset($_GET['IngClaves']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametro);die();
	echo json_encode(IngClaves($parametros));
}

if(isset($_GET['IngClaves_MYSQL']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametro);die();
	echo json_encode(IngClaves_MYSQL($parametros));
}

if(isset($_GET['salir_empresa']))
{
      // print_r('expression');die();
    echo json_encode(eliminar_variables());
}

function IngClaves($parametros)
{
	// print_r($_SESSION['INGRESO']);die();
	$mensaje ='';
	$resultado = -1;
	$intentos = $parametros['intentos'];

	$per=new usuario_model();
	if($parametros['pass']=='')
	{
		$mensaje = 'Ingrese una clave valida';

	}else
	{
		$clave = $per->IngClave($parametros);
		// print_r($clave);
		// print_r($parametros);
		// die();
		if($parametros['pass']==$clave['clave'] and $parametros['intentos']<3)
		{
			$resultado = 1;
		}else if($parametros['intentos']>=3)
		{
			$mensaje = "Sr(a). ".$_SESSION['INGRESO']['Nombre'].": \n 
             Usted no está autorizado \n
             a ingresar a esta opción.";
      $intentos= $parametros['intentos']+1;

		}else
		{
			 $mensaje = "Sr(a). ".$_SESSION['INGRESO']['Nombre'].": \n  Clave incorrecta,";
			 $intentos= $parametros['intentos']+1;

		}

		return array('msj'=>$mensaje,'respuesta'=>$resultado,'intentos'=>$intentos);
	}
	
}


function datos_modulo($cod)
{
      $per=new usuario_model();      
      $datos = $per->detalle_modulos($cod);
      return $datos;
}

function IngClaves_MYSQL($parametros)
{
	// print_r($_SESSION['INGRESO']);die();

	// print_r($parametros);die();
	$mensaje ='';
	$resultado = -1;
	$intentos = $parametros['intentos'];

	$per=new usuario_model();
	if($parametros['pass']=='')
	{
		$mensaje = 'Ingrese una clave valida';

	}else
	{
		$clave = $per->IngClave_MYSQL($parametros);
		// print_r($clave);
		// print_r($parametros);
		// die();
		if($parametros['pass']==$clave['clave'] and $parametros['intentos']<3)
		{
			$resultado = 1;
		}else if($parametros['intentos']>=3)
		{
			$mensaje = "Sr(a). ".$_SESSION['INGRESO']['Nombre'].": \n 
             Usted no está autorizado \n
             a ingresar a esta opción.";
      $intentos= $parametros['intentos']+1;

		}else
		{
			 $mensaje = "Sr(a). ".$_SESSION['INGRESO']['Nombre'].": \n  Clave incorrecta,";
			 $intentos= $parametros['intentos']+1;

		}

		return array('msj'=>$mensaje,'respuesta'=>$resultado,'intentos'=>$intentos);
	}
	
}


function  SeteosCtas()
{
	$modelo=new usuario_model();
// // ' Establecemos Espacios y seteos de impresion
  $Inv_Promedio = False;
  $PVP_Al_Inicio = False;
// // ' Cta_Ret = "0"
// //$_SESSION['SETEOS'][' ' Cta_Ret_IVA = "0"
  $_SESSION['SETEOS']['Cta_IVA'] = "0";
  $_SESSION['SETEOS']['Cta_IVA_Inventario'] = "0";
  $_SESSION['SETEOS']['Cta_CxP_Retenciones'] = "0";
  $_SESSION['SETEOS']['Cta_Desc'] = "0";
  $_SESSION['SETEOS']['Cta_Desc2'] = "0";
  $_SESSION['SETEOS']['Cta_CajaG'] = "0";
  $_SESSION['SETEOS']['Cta_General'] = "0";
  $_SESSION['SETEOS']['Cta_CajaGE'] = "0";
  $_SESSION['SETEOS']['Cta_CajaBA'] = "0";
  $_SESSION['SETEOS']['Cta_Gastos'] = "0";
  $_SESSION['SETEOS']['Cta_Diferencial'] = "0";
  $_SESSION['SETEOS']['Cta_Comision'] = "0";
  $_SESSION['SETEOS']['Cta_Mantenimiento'] = "0";
  $_SESSION['SETEOS']['Cta_Fondo_Mortuorio'] = "0";
  $_SESSION['SETEOS']['Cta_Tarjetas'] = "0";
  $_SESSION['SETEOS']['Cta_Del_Banco'] = "0";
  $_SESSION['SETEOS']['Cta_Seguro'] = "0";
  $_SESSION['SETEOS']['Cta_Seguro_I'] = "0";
  $_SESSION['SETEOS']['Cta_Proveedores'] = "0";
// 	// ' Consultamos las cuentas de la tabla
 	$datos = $modelo->SeteoCta();

 	if(count($datos)>0)
 	{
 		$Cadena = '';
 		foreach ($datos as $key => $value) {
 			 $Cadena.= $value["Detalle"];
 			switch ($value["Detalle"]) {
 			    	// case "Cta_Ret_IVA":
            // '''Cta_Ret_IVA = .Fields("Codigo")
 			    	// break;
            case "Cta_IVA":
                  $_SESSION['SETEOS']['Cta_IVA'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_IVA'] = $value['DC'];
 					  break;
            case "Cta_Descuentos":
                  $_SESSION['SETEOS']['Cta_Desc'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Desc'] = $value['DC'];
 					  break;
            case "Cta_Descuentos_Pronto_Pago":
                 $_SESSION['SETEOS']['Cta_Desc2'] = $value['Codigo'];
            break;
            case "Cta_Caja_General":
                  $_SESSION['SETEOS']['Cta_General'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_General'] = $value['DC'];
            break;
            case "Cta_Caja_GMN":
                  $_SESSION['SETEOS']['Cta_CajaG'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_CajaG'] = $value['DC'];
            break;
            case "Cta_Caja_GME":
                  $_SESSION['SETEOS']['Cta_CajaGE'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_CajaGE'] = $value['DC'];
            break;
            case "Cta_Caja_BAU":
                  $_SESSION['SETEOS']['Cta_CajaBA'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_CajaBA'] = $value['DC'];
            break;
            case "Cta_Gastos":
                  $_SESSION['SETEOS']['Cta_Gastos'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Gastos'] = $value['DC'];
            break;
            case "Cta_Diferencial_Cambiario":
                  $_SESSION['SETEOS']['Cta_Diferencial'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Diferencial'] = $value['DC'];
            break;
            case "Cta_SubTotal":
                  $_SESSION['SETEOS']['Cta_SubTotal'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_SubTotal'] = $value['DC'];
            break;
            case "Cta_Comision":
                  $_SESSION['SETEOS']['Cta_Comision'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Comision'] = $value['DC'];
            break;
            case "Cta_Faltantes":
                  $_SESSION['SETEOS']['Cta_Faltantes'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Faltantes'] = $value['DC'];
            break;
            case "Cta_Protestos":
                  $_SESSION['SETEOS']['Cta_Protestos'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Protestos'] = $value['DC'];
            break;
            case "Cta_Sobrantes":
                  $_SESSION['SETEOS']['Cta_Sobrantes'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Sobrantes'] = $value['DC'];
            break;
            case "Cta_Suspenso":
                  $_SESSION['SETEOS']['Cta_Suspenso'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Suspenso'] = $value['DC'];
            break;
            case "Cta_Libretas":
                  $_SESSION['SETEOS']['Cta_Libretas'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Libretas'] = $value['DC'];
            break;
            case "Cta_Certificado":
                  $_SESSION['SETEOS']['Cta_Certificado'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Certificado'] = $value['DC'];
            break;
            case "Cta_Certificado_Aportacion":
                  $_SESSION['SETEOS']['Cta_Certificado_Apor'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Certificado_Apor'] = $value['DC'];
            break;
            case "Cta_Apertura":
                  $_SESSION['SETEOS']['Cta_Apertura'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Apertura'] = $value['DC'];
            break;
            case "Cta_Transito":
                  $_SESSION['SETEOS']['Cta_Transito'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Transito'] = $value['DC'];
            break;
            case "Cta_Cheque_Transito":
                 $_SESSION['SETEOS']['Cta_Cheque_Transito'] = $value['Codigo'];
            break;
            case "Cta_IVA_Inventario":
                  $_SESSION['SETEOS']['Cta_IVA_Inventario'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_IVA_Inventario'] = $value['DC'];
            break;
            case "Cta_CxP_Retenciones":
                  $_SESSION['SETEOS']['Cta_CxP_Retenciones'] = $value['Codigo'];
            break;
            case "Cta_Inventario":
                  $_SESSION['SETEOS']['Cta_Inventario'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Inventario'] = $value['DC'];
            break;
            case "Cta_Mantenimiento":
                  $_SESSION['SETEOS']['Cta_Mantenimiento'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Mantenimiento'] = $value['DC'];
            break;
            case "Cta_Fondo_Mortuorio":
                  $_SESSION['SETEOS']['Cta_Fondo_Mortuorio'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Fondo_Mortuorio'] = $value['DC'];
            break;
            case "Cta_Servicios_Basicos":
                  $_SESSION['SETEOS']['Cta_Servicios_Basicos'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Servicios_Basicos'] = $value['DC'];
            break;
            case "Cta_Servicio":
                  $_SESSION['SETEOS']['Cta_Servicio'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Servicio'] = $value['DC'];
            break;
            case "Cta_Intereses":
                  $_SESSION['SETEOS']['Cta_Interes'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Interes'] = $value['DC'];
            break;
            case "Cta_Intereses1":
                  $_SESSION['SETEOS']['Cta_Interes1'] = $value['Codigo'];
                  $_SESSION['SETEOS']['DC_Interes1'] = $value['DC'];
            break;
            case "Cta_CxP_Tarjetas":
                 $_SESSION['SETEOS']['Cta_Tarjetas'] = $value['Codigo'];
            break;
            case "Cta_Caja_Vaucher":
                 $_SESSION['SETEOS']['Cta_Caja_Vaucher'] = $value['Codigo'];
            break;
            case "Cta_Banco":
                 $_SESSION['SETEOS']['Cta_Del_Banco'] = $value['Codigo'];
            break;
            case "Cta_Seguro_Desgravamen":
                 $_SESSION['SETEOS']['Cta_Seguro'] = $value['Codigo'];
            break;
            case "Cta_Impuesto_Renta_Empleado":
                 $_SESSION['SETEOS']['Cta_Impuesto_Renta_Empleado'] = $value['Codigo'];
            break;
            case "Cta_Seguro_Ingreso":
                 $_SESSION['SETEOS']['Cta_Seguro_I'] = $value['Codigo'];
            break;
             case "Cta_Proveedores":
                 $_SESSION['SETEOS']['Cta_Proveedores'] = $value['Codigo'];
            break;
            case "Inv_Promedio": 
                 If($value['Codigo']== "TRUE"){ $Inv_Promedio = True;}
            break;
            case "PVP_Al_Inicio": 
                 If($value['Codigo']== "TRUE"){ $PVP_Al_Inicio = True;}
            break;
 			}
 		}

 	}

  $SSQLSeteos = "";
 // 'If Cta_Ret = "0" Then SSQLSeteos = SSQLSeteos & "Cta_Ret_Ingreso" & vbCrLf
  if($_SESSION['SETEOS']['Cta_IVA']== "0"){ $SSQLSeteos.="Cta_IVA ,";}
  if($_SESSION['SETEOS']['Cta_Desc']== "0"){ $SSQLSeteos.="Cta_Descuentos ,";}
  if($_SESSION['SETEOS']['Cta_Desc2']== "0"){ $SSQLSeteos.="Cta_Descuentos_Pronto_Pago ,";}
  if($_SESSION['SETEOS']['Cta_CajaG']== "0"){ $SSQLSeteos.="Cta_Caja_GMN ,";}
  if($_SESSION['SETEOS']['Cta_General']== "0"){ $SSQLSeteos.="Cta_Caja_General ,";}
  if($_SESSION['SETEOS']['Cta_CajaGE']== "0"){ $SSQLSeteos.="Cta_Caja_GME ,";}
  if($_SESSION['SETEOS']['Cta_CajaBA']== "0"){ $SSQLSeteos.="Cta_Caja_VAU ,";}
  if($_SESSION['SETEOS']['Cta_Gastos']== "0"){ $SSQLSeteos.="Cta_Gastos ,";}
  if($_SESSION['SETEOS']['Cta_Diferencial']== "0"){ $SSQLSeteos.="Cta_Diferencial_Cambiario ,";}
  if($_SESSION['SETEOS']['Cta_IVA_Inventario']== "0"){ $SSQLSeteos.="Cta_IVA_Inventario ,";}
  if($_SESSION['SETEOS']['Cta_Proveedores']== "0"){ $SSQLSeteos.="Cta_Proveedores ,";}
  If($SSQLSeteos <> ""){
     $SSQLSeteos = "Verifique el codigo de:".$SSQLSeteos."La proxima vez que ejecute el sistema se crearan estas cuentas ";

     // print_r($SSQLSeteos);die();
     return $SSQLSeteos;
   // ' MsgBox SSQLSeteos
   // ' CtasSeteos AdoRec
  }
}


function variables_sistema($EmpresaEntidad,$NombreEmp,$ItemEmp)
{
	$_SESSION['INGRESO']['empresa']=$EmpresaEntidad;
	$_SESSION['INGRESO']['noempr']=$NombreEmp;
	$_SESSION['INGRESO']['item']=$ItemEmp;
	$_SESSION['INGRESO']['ninguno']='.';

	 $_SESSION['INGRESO']['LOCAL_SQLSERVER']='NO'; //quitar despues


	$cod = explode('-', $EmpresaEntidad);
	$empresa=getEmpresasId($cod[0]);
	// print_r($empresa);die();
	if(count($empresa)>0)
	{
		    $empresa[0]['Servicio'] = 0;
		    //datos base de mysql
	  $_SESSION['INGRESO']['RUCEnt'] =  $empresa[0]['RUC_CI_NIC']; //ruc de la entidad
	  $_SESSION['INGRESO']['Entidad'] = $empresa[0]['Nombre_Entidad'];
        $_SESSION['INGRESO']['IP_VPN_RUTA']=$empresa[0]['IP_VPN_RUTA'];
        $_SESSION['INGRESO']['Base_Datos']=$empresa[0]['Base_Datos'];
        $_SESSION['INGRESO']['Usuario_DB']=$empresa[0]['Usuario_DB'];
        $_SESSION['INGRESO']['Contraseña_DB']=$empresa[0]['Contrasena_DB'];
        $_SESSION['INGRESO']['Tipo_Base']=$empresa[0]['Tipo_Base'];
        $_SESSION['INGRESO']['Puerto']=$empresa[0]['Puerto'];
        $_SESSION['INGRESO']['Fecha']=$empresa[0]['Fecha'];
        $_SESSION['INGRESO']['Logo_Tipo']=$empresa[0]['Logo_Tipo'];
        $_SESSION['INGRESO']['periodo']='.';/////////
        $_SESSION['INGRESO']['Razon_Social']=$empresa[0]['Razon_Social'];
        $_SESSION['INGRESO']['Fecha_ce']=$empresa[0]['Fecha_CE'];
        $_SESSION['INGRESO']['Porc_Serv']= round($empresa[0]['Servicio'] / 100,2) ;

        //datos de empresa seleccionada
        $empresa = getEmpresasDE($_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['noempr']);
        SeteosCtas();

        // print_r($empresa);die();

  	

        $_SESSION['INGRESO']['Web_SRI_Autorizado']=$empresa[0]['Web_SRI_Autorizado'];
        $_SESSION['INGRESO']['Web_SRI_Recepcion']=$empresa[0]['Web_SRI_Recepcion'];
        $_SESSION['INGRESO']['Direccion']=$empresa[0]['Direccion'];
        $_SESSION['INGRESO']['Telefono1']=$empresa[0]['Telefono1'];
        $_SESSION['INGRESO']['FAX']=$empresa[0]['FAX'];
        $_SESSION['INGRESO']['Nombre_Comercial']=$empresa[0]['Nombre_Comercial'];
        $_SESSION['INGRESO']['Razon_Social']=$empresa[0]['Razon_Social'];
        $_SESSION['INGRESO']['Sucursal']=$empresa[0]['Sucursal'];
        $_SESSION['INGRESO']['Opc']=$empresa[0]['Opc'];
        $_SESSION['INGRESO']['noempr']=$empresa[0]['Empresa'];
        $_SESSION['INGRESO']['S_M']=$empresa[0]['S_M'];
        $_SESSION['INGRESO']['Num_CD']=$empresa[0]['Num_CD'];
        $_SESSION['INGRESO']['Num_CE']=$empresa[0]['Num_CE'];
        $_SESSION['INGRESO']['Num_CI']=$empresa[0]['Num_CI'];
        $_SESSION['INGRESO']['Num_ND']=$empresa[0]['Num_ND'];
        $_SESSION['INGRESO']['Num_NC']=$empresa[0]['Num_NC'];
        $_SESSION['INGRESO']['Email_Conexion_CE']=$empresa[0]['Email_Conexion_CE'];
        $_SESSION['INGRESO']['Formato_Cuentas']=$empresa[0]['Formato_Cuentas'];
        $_SESSION['INGRESO']['Formato_Inventario']=$empresa[0]['Formato_Inventario'];
        $_SESSION['INGRESO']['porc']=$empresa[0]['porc'];
        $_SESSION['INGRESO']['Ambiente']=$empresa[0]['Ambiente'];
        $_SESSION['INGRESO']['Obligado_Conta']=$empresa[0]['Obligado_Conta'];
        $_SESSION['INGRESO']['LeyendaFA']=$empresa[0]['LeyendaFA'];
        $_SESSION['INGRESO']['Email']=$empresa[0]['Email'];
        $_SESSION['INGRESO']['RUC']=$empresa[0]['RUC'];
        $_SESSION['INGRESO']['Gerente']=$empresa[0]['Gerente'];;
        $_SESSION['INGRESO']['Det_Comp']=$empresa[0]['Det_Comp'];
        $_SESSION['INGRESO']['Signo_Dec']=$empresa[0]['Signo_Dec'];
        $_SESSION['INGRESO']['Signo_Mil']=$empresa[0]['Signo_Mil'];
        $_SESSION['INGRESO']['RUC_Contador'] = $empresa[0]['RUC_Contador'];
        $_SESSION['INGRESO']['CI_Representante'] = $empresa[0]['CI_Representante'];
        $_SESSION['INGRESO']['Ruta_Certificado'] = $empresa[0]['Ruta_Certificado'];
        $_SESSION['INGRESO']['Clave_Certificado'] = $empresa[0]['Clave_Certificado'];
        $_SESSION['INGRESO']['Dec_PVP'] = $empresa[0]['Dec_PVP'];
        $_SESSION['INGRESO']['Dec_Costo'] = $empresa[0]['Dec_Costo'];
        $_SESSION['INGRESO']['Cotizacion'] = $empresa[0]['Cotizacion'];
        // print_r($empresa_d);die();
        $_SESSION['INGRESO']['Ciudad'] = $empresa[0]['Ciudad'];;       
        $_SESSION['INGRESO']['accesoe']='0';
        $_SESSION['INGRESO']['Email_Conexion']=$empresa[0]['Email_Conexion'];
        $_SESSION['INGRESO']['Impresora_Rodillo']=$empresa[0]['Impresora_Rodillo'];

	  $_SESSION['INGRESO']['Email_Contrasena']=$empresa[0]['Email_Contraseña'];
	  $_SESSION['INGRESO']['smtp_SSL']=$empresa[0]['smtp_SSL'];
	  $_SESSION['INGRESO']['smtp_UseAuntentificacion']=$empresa[0]['smtp_UseAuntentificacion'];
	  $_SESSION['INGRESO']['smtp_Puerto']=$empresa[0]['smtp_Puerto'];
	  $_SESSION['INGRESO']['smtp_Servidor']=$empresa[0]['smtp_Servidor'];

        $_SESSION['INGRESO']['paginacionIni']=0;
        $_SESSION['INGRESO']['paginacionFin']=100;
        $_SESSION['INGRESO']['base_actual']='';

	  if(isset($empresa[0]['smtp_Secure']))
	    {
	       $_SESSION['INGRESO']['smtp_Secure']=$empresa[0]['smtp_Secure'];
	    }
	
      $_SESSION['INGRESO']['Serie_FA'] = $empresa[0]['Serie_FA'];
	$_SESSION['INGRESO']['modulo']=modulos_habiliatados();
	//datos del periodo periodo

      //esto se debe sacar de la entidad ----------------------

	$periodo = getPeriodoActualSQL();
      if(count($periodo)>0)
      {
	   $_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial']->format('Y-m-d');
	   $_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final']->format('Y-m-d');
      }else
      {
         $_SESSION['INGRESO']['Fechai']=date('Y-m-d');
         $_SESSION['INGRESO']['Fechaf']=date('Y-m-d');
      }
      // ---------------------------------------
        $permiso=getAccesoEmpresas();
        
				//get usuario
				
  }else
  {
  	$modelo=new usuario_model();
	   $empresa=$modelo->getEmpresasId_sin_sqlserver($cod[0]);
	  // print_r($empresa);die();

        $_SESSION['INGRESO']['IP_VPN_RUTA']='mysql.diskcoversystem.com';
        $_SESSION['INGRESO']['Base_Datos']='diskcover_empresas';
        $_SESSION['INGRESO']['Usuario_DB']='diskcover';
        $_SESSION['INGRESO']['Contraseña_DB']='disk2017Cover';
        $_SESSION['INGRESO']['Tipo_Base']='My SQL';
        $_SESSION['INGRESO']['Puerto']=13306;
     //    $this->usuario = 'diskcover';
	    // $this->password =  'disk2017Cover';  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    // $this->servidor ='mysql.diskcoversystem.com';
	    // $this->database = 'diskcover_empresas';
	    // $this->puerto = 13306;	 


     $_SESSION['INGRESO']['Logo_Tipo']=$empresa[0]['Logo_Tipo'];
     $_SESSION['INGRESO']['Nombre_Comercial']=$empresa[0]['Empresa'];
     $_SESSION['INGRESO']['Razon_Social']=$empresa[0]['Razon_Social'];
     $_SESSION['INGRESO']['noempr']=$empresa[0]['Empresa'];
     $_SESSION['INGRESO']['RUC']=$empresa[0]['RUC_CI_NIC']; // ruc de la empresa
     $_SESSION['INGRESO']['Gerente']=$empresa[0]['Gerente'];
     $_SESSION['INGRESO']['Ciudad'] = $empresa[0]['Ciudad'];
     $_SESSION['INGRESO']['Direccion']='';
     $_SESSION['INGRESO']['Telefono1']='';
     $_SESSION['INGRESO']['FAX']='';
     $_SESSION['INGRESO']['Email']=''; 
     $_SESSION['INGRESO']['RUCEnt'] =  $empresa[0]['RUC_CI_NIC']; //ruc de la entidad
     $_SESSION['INGRESO']['Entidad'] = $empresa[0]['Nombre_Entidad'];

	  // print_r($_SESSION['INGRESO']);die();
  	
         
  }
}

function eliminar_variables()
{
	//destruimos la sesion
		unset( $_SESSION['INGRESO']['empresa'] ); 
		unset( $_SESSION['INGRESO']['noempr'] );  	
		unset( $_SESSION['INGRESO']['modulo_']);
		unset( $_SESSION['INGRESO']['accion']);
		unset($_SESSION['INGRESO']['IP_VPN_RUTA']);
		unset($_SESSION['INGRESO']['Base_Datos']);
		unset($_SESSION['INGRESO']['Usuario_DB']);
		unset($_SESSION['INGRESO']['Contraseña_DB']);
		unset($_SESSION['INGRESO']['Tipo_Base']);
		unset($_SESSION['INGRESO']['Puerto']);
		unset($_SESSION['INGRESO']['Fecha']);
		unset($_SESSION['INGRESO']['Fechai']);
		unset($_SESSION['INGRESO']['Fechaf']);
		unset($_SESSION['INGRESO']['Logo_Tipo']);
		unset($_SESSION['INGRESO']['Razon_Social']);
		unset($_SESSION['INGRESO']['Direccion']);
		unset($_SESSION['INGRESO']['Telefono1']);
		unset($_SESSION['INGRESO']['FAX']);
		unset($_SESSION['INGRESO']['Nombre_Comercial']);
		unset($_SESSION['INGRESO']['Razon_Social']);
		unset($_SESSION['INGRESO']['S_M']);
		unset($_SESSION['INGRESO']['porc']);
		//eliminar permisos
		unset($_SESSION['INGRESO']['accesoe']);
		unset($_SESSION['INGRESO']['modulo']);
            return 1;
}

//devuelve empresas asociadas al usuario  * modificado: javier fainango.
function getEmpresas($id_entidad,$cartera=false)
{
	$per=new usuario_model();
	$empresa=$per->getEmpresas($id_entidad,$cartera);
	// print_r($empresa);die();
	return $empresa;
}
//devuelve empresas seleccionada por el usuario ---* modificado: javier fainango.
function getEmpresasId($id_empresa)
{	
	$modelo = new usuario_model();
	$empresa=$modelo-> getEmpresasId($id_empresa);
	// print_r($empresa);die();
	// print_r($_SESSION); die();
	return $empresa;
}
//devuelve empresas seleccionada por el usuario de mysql sin credenciales sqlserver
function empresa_sin_creenciales_sqlserver($id_empresa)
{
	//echo ' dd '.$id_empresa;
	$per=new usuario_model();
	$empresa=$per->getEmpresasId_sin_sqlserver($id_empresa);
	// print_r($empresa);die();
	// print_r($_SESSION); die();
	return $empresa;
}
//devuelve inf del detalle de la empresa seleccionada por el usuario -------* modificado: javier fainango.
function getEmpresasDE($item,$nombre)
{
	$modelo =new usuario_model();
	$datos = $modelo->datos_empresa($item,$nombre);
	// print_r($datos);die();
	return $datos;
}
//perido actual funcion sql server --* modificado: javier fainango.
function getPeriodoActualSQL()
{
		$modulo=new usuario_model();
		$periodo=$modulo->get_periodo();
		return $periodo;
}

//obtener datos de usuario  
function getUsuario()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getUsuarioSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getUsuarioMYSQL();
	}
	
	return $empresa;
}


//verificar acceso usuario ------  * modificado: javier fainango.
 function getAccesoEmpresas()
 {
 	$modelo =new usuario_model();
 	$modelo->getAccesoEmpresasSQL();
 }


//consultar modulo
function getModulo()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getModuloSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getModuloMYSQL();
	}
	
	return $empresa;
} 

function modulos_habiliatados()
{
	$per=new usuario_model();
	$modulos=$per->modulos_registrados();
	return $modulos;
	
}

function contruir_modulos($modulos)
{
	$mod="";
	$color = array('1'=>'bg-green','2'=>'bg-yellow','3'=>'bg-red','4'=>'bg-aqua');

	$pos = 1;
	foreach ($modulos  as $key => $value) {
		// print_r($value);die();
		$link = '';
		if ($value['link'] == '.') {
			$link = 'onclick="no_modulo();"';
		}else{
			$link = 'href="'.$value['link'].'"';
		}
		$mod .= '<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box '.$color[$pos].'"  style="border-radius: 10px;">
            <div class="inner"><a '.$link.' style="color: #ffffff;">';
            if(strlen($value['apli'])<9)
            {
            	$mod.= '<h4><b>'.$value['apli'].'</b></h4>';
            }else
            {
            	$mod.= '<h4><b>'.$value['apli'].'</b></h4>';

            }
              $mod.='<p>Modulo</p>
              </a>
            </div>
            <div class="icon">';
            if($value['icono']!='.'){
              $mod.='<i class="ion ion" style="padding-right: 15px;"><img  class="style_prevu_kit" src="'.$value['icono'].'" class="icon" style="display:block;width:100%;margin-top: 35%;"></i>';
            }else
            {
            	$mod.='<i class="ion ion" style="padding-right: 15px;width: 80px;"></i>';
            }

            $mod.='</div>
            <a '.$link.' class="small-box-footer">Click para ingresar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>';        
        $pos+=1;
        if($pos==5)
        {
        	$pos = 1;
        }

	}
	return $mod;

}
function contruir_todos_modulos()
{

	$per=new usuario_model();
	$modulos=$per->modulos_todos();
	$mod="";
	$color = array('1'=>'bg-green','2'=>'bg-yellow','3'=>'bg-red','4'=>'bg-aqua');
	$pos =1;
	foreach ($modulos  as $key => $value) {
		$mod .= '<div class="col-lg-3 col-xs-6">
		<a href="'.$value['link'].'">
          <!-- small box -->
          <div class="small-box '.$color[$pos].'" style="border-radius: 10px;">
            <div class="inner"><a href="'.$value['link'].'" style="color: #ffffff;">';
            if(strlen($value['Aplicacion'])<9)
            {
            	$mod.= '<h3>'.$value['Aplicacion'].'</h3>';
            }else{
               $mod.= '<h4 style="font-size: 30px;"><b>'.$value['Aplicacion'].'</b></h4>';
            }

           $mod.='<p>Modulo</p>
           <a>
            </div>
            <div class="icon">';
            if($value['icono']!='.'){
              $mod.='<i class="ion ion-plus" style="padding-right: 15px;"><img src="'.$value['icono'].'" class="icon" style="display:block;width:85%;margin-top: 35%;"></i>';
            }else
            {
            	$mod.='<i class="ion ion-plus"></i>';
            }

            $mod.='</div>
          </a>
        </div>';        
        $pos= $pos+1;
        if($pos==5)
        {
        	$pos = 1;
        }
        
	}
	return $mod;

	//style="display:block; height:80%; width:100%;"

}


?>
