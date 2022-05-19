<?php 
@session_start();
/**
 * 
 */
//phpinfo();
$d = new db();
$d->conexion();
class db
{
	private $usuario;
	private $password;  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	private $servidor;
	private $database;
	private $puerto;
	PRIVATE $tipobase;

	function __construct()
	{
		$this->default_conexion();
	   
	}

	function default_conexion()
	{
		// $this->usuario = "root";
	 //    $this->password = ''; 
	 //    $this->servidor = "localhost"; 
	 //    $this->database = "proyecto";
	 //    $this->puerto = '3306';	


	    $this->usuario = "diskcover";
	    $this->password = 'disk2017Cover'; 
	    $this->servidor = "mysql.diskcoversystem.com"; 
	    $this->database = "base_prueba";
	    $this->puerto = '3306';	

	}
    

	function conexion()
	{
	   $conn = new mysqli($this->servidor, $this->usuario, $this->password, $this->database,$this->puerto);
	   if (!$conn) 
	   {
		   return false;
	   }
	   return $conn;	
	}

	function existente($sql)
	{
		$conn = $this->conexion();
		$resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			mysqli_close($conn);
			return false;
		}else
		{
			if($resultado->num_rows==0)
			{	mysqli_close($conn);
				return false;
			}
			else
			{	mysqli_close($conn);
				return true;
			}
		}
 
	}
	function datos($sql)
	{
		$conn = $this->conexion(); 			
		$resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{

			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			mysqli_close($conn);
			return false;
		}
		$datos = array();
		while ($row = mysqli_fetch_assoc($resultado)) {
			$datos[] = $row;
		}
		mysqli_close($conn);
		return $datos;
	}
	function inserts($tabla,$datos)
	{
		$conn = $this->conexion();		
		$valores = '';
 		$campos = '';
 		$sql = 'INSERT INTO '.$tabla;

 		foreach ($datos as $key => $value) {
 			$campos.=$value['campo'].',';
 			if(is_numeric($value['dato']))
 			{
 			  $valores.=$value['dato'].',';
 			}else
 			{
 				$valores.='"'.$value['dato'].'",';
 			} 			 			
 		}
 		$campos = substr($campos, 0, -1);
 		$valores = substr($valores, 0, -1);
 		$sql.='('.$campos.')values('.$valores.');'; 		
		$resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		mysqli_close($conn);
			return -1;
		}
		mysqli_close($conn);
		return 1;
	}

	function update($tabla,$datos,$where)
	{
		$conn = $this->conexion();		
		$valores = '';
 		$campos = '';
 		$sql = 'UPDATE '.$tabla.' SET ';
 		foreach ($datos as $key => $value) {
 			if(is_numeric($value['dato']))
 			{
 			   $sql.=$value['campo'].'='.$value['dato'];
 			}else
 			{
 				$sql.=$value['campo'].'="'.$value['dato'].'"';
 			}
 			$sql.=','; 			 			
 		}
 		$sql = substr($sql, 0, -1);
 		$sql.=" WHERE ";

 		foreach ($where as $key => $value) {
 			if(is_numeric($value['dato']))
 			{
 			  $sql.=$value['campo'].'='.$value['dato'];
 			}else
 			{
 			    $sql.=$value['campo'].'="'.$value['dato'].'"';
 			//	$valores.='"'.$value['dato'].'",';
 			}
 			$sql.= " AND "; 			 			
 		} 		
 		$sql = substr($sql, 0, -5);	
		$resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		mysqli_close($conn);
			return -1;
		}
		mysqli_close($conn);
		return 1;	
	}


	function delete($tabla,$datos)
	{
		$conn = $this->conexion();
		$valores = '';
 		$campos = '';
 		$sql = 'DELETE FROM '.$tabla.' WHERE ';

 		foreach ($datos as $key => $value) {
 			$campos.=$value['campo'].',';
 			if(is_numeric($value['dato']))
 			{
 			  $sql.=$value['campo'].'='.$value['dato'];
 			}else
 			{
 			    $sql.=$value['campo'].'="'.$value['dato'].'"';
 			//	$valores.='"'.$value['dato'].'",';
 			}
 			$sql.= " AND ";
 			 			
 		}
 		$sql = substr($sql, 0, -5);
 		//print_r($sql);	die();	
		$resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		mysqli_close($conn);
			return -1;
		}
		mysqli_close($conn);
		return 1;
	}

	function sql_string($sql)
	{
	   $conn = $this->conexion();	   
       $resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		mysqli_close($conn);
			return -1;
		}
		mysqli_close($conn);
		return 1;		
	}

	function sql_string_cod_error($sql)
	{
		// print_r($sql);
	   $conn = $this->conexion();
   	   $resultado = mysqli_query($conn, $sql);
		if(!$resultado)
		{
		  return mysqli_errno($conn);			
		}
		mysqli_close($conn);
		return 1;

	  

	}
	
}
?>