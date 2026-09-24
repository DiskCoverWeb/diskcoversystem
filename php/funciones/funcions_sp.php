<?php
/**
 * Funciones de procedimientos almacenados (Stored Procedures) del modulo Rol de Pagos.
 * Equivalente en PHP a los wrappers de C:\ASISTEMA\MODULOS\SubSQLServer.bas (sistema VB6).
 * Sigue la misma convencion usada en funciones.php (mayorizar_inventario_sp, etc.):
 * un wrapper por SP, parametros enlazados via SQLSRV_PARAM_IN/OUT y ejecutados
 * con la clase db::ejecutar_procesos_almacenados().
 */
if(!isset($_SESSION))
{
	@session_start();
}
require_once(dirname(__DIR__,1)."/db/db1.php");

// Equivale a Mayorizar_Cuentas_SP (SubSQLServer.bas). Usado por "Mayorizar Rol de Pagos".
function mayorizar_cuentas_sp($reIndexar = false)
{
	$conn = new db();
	$parametros = array(
		array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
		array(&$reIndexar, SQLSRV_PARAM_IN),
	);
	$sql = "EXEC sp_Mayorizar_Cuentas @Item=?, @Periodo=?, @ReIndexar=?";
	return $conn->ejecutar_procesos_almacenados($sql, $parametros);
}

// Equivale a Procesar_Rol_Pagos_del_Mes_SP (SubSQLServer.bas). Procesa el rol de pagos del mes/periodo indicado.
function procesar_rol_pagos_del_mes_sp($fechaIniRol, $fechaFinRol, $grupoRol, $dcCxP, $noCheque)
{
	$conn = new db();
	$parametros = array(
		array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['modulo_'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['CodigoU'], SQLSRV_PARAM_IN),
		array(&$fechaIniRol, SQLSRV_PARAM_IN),
		array(&$fechaFinRol, SQLSRV_PARAM_IN),
		array(&$grupoRol, SQLSRV_PARAM_IN),
		array(&$dcCxP, SQLSRV_PARAM_IN),
		array(&$noCheque, SQLSRV_PARAM_IN),
	);
	$sql = "EXEC sp_Procesar_Rol_Pagos_del_Mes @Item=?, @Periodo=?, @NumModulo=?, @CodigoUsuario=?, @FechaIniRol=?, @FechaFinRol=?, @GrupoRol=?, @DCCxP=?, @No_Cheque=?";
	return $conn->ejecutar_procesos_almacenados($sql, $parametros);
}

// Equivale a Procesar_Rol_Pagos_Asientos_SP (SubSQLServer.bas). Genera los asientos contables del rol ya procesado.
function procesar_rol_pagos_asientos_sp($fechaIniRol, $fechaFinRol)
{
	$conn = new db();
	$parametros = array(
		array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['modulo_'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['CodigoU'], SQLSRV_PARAM_IN),
		array(&$fechaIniRol, SQLSRV_PARAM_IN),
		array(&$fechaFinRol, SQLSRV_PARAM_IN),
	);
	$sql = "EXEC sp_Procesar_Rol_Pagos_Asientos @Item=?, @Periodo=?, @NumModulo=?, @CodigoUsuario=?, @FechaIniRol=?, @FechaFinRol=?";
	return $conn->ejecutar_procesos_almacenados($sql, $parametros);
}

// Equivale a Reporte_Rol_Pagos_Colectivo_SP (SubSQLServer.bas). Arma el reporte colectivo del rol
// (columnas dinamicas segun los rubros configurados: @ListaCampos y @SumatoriaCampos vienen por OUTPUT).
function reporte_rol_pagos_colectivo_sp($fechaIniRol, $fechaFinRol, $grupoRol = 'Todos', $ordenAlfabetico = true)
{
	$conn = new db();
	$listaCampos = '';
	$sumatoriaCampos = '';
	$parametros = array(
		array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
		array(&$_SESSION['INGRESO']['CodigoU'], SQLSRV_PARAM_IN),
		array(&$fechaIniRol, SQLSRV_PARAM_IN),
		array(&$fechaFinRol, SQLSRV_PARAM_IN),
		array(&$grupoRol, SQLSRV_PARAM_IN),
		array(&$ordenAlfabetico, SQLSRV_PARAM_IN),
		array(&$listaCampos, SQLSRV_PARAM_OUT),
		array(&$sumatoriaCampos, SQLSRV_PARAM_OUT),
	);
	$sql = "EXEC sp_Reporte_Rol_Pagos_Colectivo @Item=?, @Periodo=?, @CodigoUsuario=?, @FechaIniRol=?, @FechaFinRol=?, @GrupoRol=?, @OrdenAlfabetico=?, @ListaCampos=?, @SumatoriaCampos=?";
	$respuesta = $conn->ejecutar_procesos_almacenados($sql, $parametros);
	return array(
		'Respuesta' => $respuesta,
		'ListaCampos' => $listaCampos,
		'SumatoriaCampos' => $sumatoriaCampos,
	);
}
