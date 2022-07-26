<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../modelo/usuario_model.php");
$login = new login_controller();
if(isset($_GET['Entidad']))
{
	$entidad = $_POST['entidad'];
	$_SESSION['INGRESO']['Height_pantalla'] = $_GET['pantalla'];
	echo json_encode($login->validar_entidad($entidad));
}

if(isset($_GET['Cartera_Entidad']))
{
	$entidad = $_POST['entidad'];
	$_SESSION['INGRESO']['Height_pantalla'] = $_GET['pantalla'];
	echo json_encode($login->validar_entidad_cartera($entidad));
} 
if(isset($_GET['Usuario']))
{
	$parametro = $_POST['parametros'];
	echo json_encode($login->validar_usuario($parametro));
}
if(isset($_GET['Ingresar']))
{
	$parametro = $_POST['parametros'];
	echo json_encode($login->login($parametro));
}
if(isset($_GET['Ingresar_cartera']))
{
	$parametro = $_POST['parametros'];
	echo json_encode($login->login_cartera($parametro));
}
if(isset($_GET['logout']))
{
	echo json_encode($login->logout());
}

/**
 * 
 */
class login_controller
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new  usuario_model();
	}

	function validar_entidad($entidad)
	{
		$datos = $this->modelo->ValidarEntidad1($entidad);
		// $datos['cartera'] = 0;
		// if($datos['respuesta']==-1)
		// {
		// 	$datos = $this->modelo->empresa_cartera($entidad);
		// 	// print_r($datos);die();
		// 	if(count($datos)>0)
		// 	{
		// 		$datos['cartera'] = 1;
		// 		$datos['cartera_usu'] = 'Cartera';
		// 		$datos['cartera_pass'] = '999999';
		// 		$datos['respuesta'] = 1;
		// 		$datos['entidad'] = $datos[0]['ID_Empresa'];
		// 		$datos['Nombre'] = $datos[0]['Empresa'];
		// 		$datos['Item'] = $datos[0]['Item'];
		// 		$_SESSION['INGRESO']['CARTERA_ITEM'] = $datos[0]['Item'];
		// 	}else
		// 	{
		// 		//retorna -1 cuando no se encuentra la empresa 			
		// 		$datos['respuesta'] = -1;
		// 		$datos['entidad'] = '';
		// 		$datos['Nombre'] = '';
		// 	}
			
		// }
		return $datos;
		// print_r($datos);die();
	}

	function validar_entidad_cartera($entidad)
	{
		
		$datos['cartera'] = 0;
		$datos = $this->modelo->empresa_cartera($entidad);
			// print_r($datos);die();
			if(count($datos)>0)
			{
				$datos['cartera'] = 1;
				$datos['cartera_usu'] = 'Cartera';
				$datos['cartera_pass'] = '999999';
				$datos['respuesta'] = 1;
				$datos['entidad'] = $datos[0]['ID_Empresa'];
				$datos['Nombre'] = $datos[0]['Empresa'];
				$datos['Item'] = $datos[0]['Item'];
				$_SESSION['INGRESO']['CARTERA_ITEM'] = $datos[0]['Item'];
			}else
			{
				//retorna -1 cuando no se encuentra la empresa 			
				$datos['respuesta'] = -1;
				$datos['entidad'] = '';
				$datos['Nombre'] = '';
			}
			
		return $datos;
		// print_r($datos);die();
	}

	function validar_usuario($parametro)
	{
		$datos = $this->modelo->ValidarUser1($parametro['usuario'],$parametro['entidad']);
		return $datos;
	}
	function login($parametro)
	{
		$datos = $this->modelo->Ingresar($parametro['usuario'],$parametro['pass'],$parametro['entidad']);

		// print_r($parametro);
		// print_r($datos);
		// die();
		if($parametro['cartera']==1)
		{
			// validar cliente en cartera
			$empresa = $this->modelo->empresa_cartera($parametro['empresa'],$parametro['entidad']);
			// print_r($empresa);die();
			$cliente = $this->modelo->buscar_cliente_cartera($parametro['cartera_usu'],$parametro['cartera_pass'],$empresa);
			if(count($cliente)==0)
			{
				return -2;
			}else
			{
				$_SESSION['INGRESO']['CARTERA_USUARIO'] = $parametro['cartera_usu'];
				$_SESSION['INGRESO']['CARTERA_PASS'] = $parametro['cartera_pass'];
			}

			// print_r($cliente);die();
			// print_r($empresa);print_r($cliente);die();
		}


		return $datos;
	}
	function login_cartera($parametro)
	{
		$datos = $this->modelo->Ingresar($parametro['usuario'],$parametro['pass'],$parametro['entidad']);

		// print_r($parametro);
		// print_r($datos);
		// die();
		if($parametro['cartera']==1)
		{
			// validar cliente en cartera
			$empresa = $this->modelo->empresa_cartera($parametro['empresa'],$parametro['entidad']);
			// print_r($empresa);die();
			$cliente = $this->modelo->buscar_cliente_cartera($parametro['cartera_usu'],$parametro['cartera_pass'],$empresa);
			if(count($cliente)==0)
			{
				return -2;
			}else
			{
				$_SESSION['INGRESO']['CARTERA_USUARIO'] = $parametro['cartera_usu'];
				$_SESSION['INGRESO']['CARTERA_PASS'] = $parametro['cartera_pass'];
			}

			// print_r($cliente);die();
			// print_r($empresa);print_r($cliente);die();
		}


		return $datos;
	}

	function logout()
	{
		session_destroy(); 
		return 1;
	}

}


?>
