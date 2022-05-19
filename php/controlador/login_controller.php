<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../modelo/usuario_model.php");
$login = new login_controller();
if(isset($_GET['Entidad']))
{
	$entidad = $_POST['entidad'];
	echo json_encode($login->validar_entidad($entidad));
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
		return $datos;
	}

	function logout()
	{
		session_destroy(); 
		return 1;
	}

}


?>
