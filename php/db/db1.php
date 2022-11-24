<?php 
@session_start();
/**
 * 
 */
 // $d = new db();
 // $d->conexion('MYSQL');
class db
{
	private $usuario;
	private $password;  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	private $servidor;
	private $database;
	private $puerto;
	private $ipconfig;
	
	function __construct()
	{

		// print_r(dirname(__DIR__));die();

		if(!file_exists(dirname(__DIR__).'/db/ipconfig.ini'))
		{
			mkdir(dirname(__DIR__).'/db/ipconfig.ini');
			//escribir local host adentro de ipconfig.ini
		}
    $p = file_get_contents(dirname(__DIR__).'/db/ipconfig.ini');
	  $this->ipconfig = $p;
	}

	function conexion($tipo='')
	{
		if($tipo=='MYSQL' || $tipo=='My SQL')
		{
		  return $this->MySQL();
		  // $this->MySQL2();

		}else
		{
		  return  $this->SQLServer();
		}

		
	}

	function SQLServer()
	{
		// print_r($_SESSION['INGRESO']);die();
			$this->usuario = $_SESSION['INGRESO']['Usuario_DB'];
	    $this->password = $_SESSION['INGRESO']['Contraseña_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $this->servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];
	    if($_SESSION['INGRESO']['IP_VPN_RUTA']=='tcp:mysql.diskcoversystem.com' &&  $this->ipconfig=='localhost')
	    {
	    	$this->servidor = $this->ipconfig;
	    }
	    $this->database = $_SESSION['INGRESO']['Base_Datos'];
	    $this->puerto = $_SESSION['INGRESO']['Puerto'];
		// print_r($_SESSION);die();


	    // print_r($this->servidor);die();

		$connectionInfo = array("Database"=>$this->database, "UID" => $this->usuario,"PWD" => $this->password,"CharacterSet" => "UTF-8");
		// print_r($connectionInfo);die();
		$cid = sqlsrv_connect($this->servidor.', '.$this->puerto, $connectionInfo); //returns false
		if( $cid === false )
		   {
				echo 'no se pudo conectar a la base de datos';
				die( print_r( sqlsrv_errors(), true));
		   }else{
		    $_SESSION['INGRESO']['base_actual'] = $this->servidor;
		   	  // print_r($this->servidor);die();
			}
		return $cid;
	}

	function MySQL()
	{
			$this->usuario = 'diskcover';
	    $this->password =  'disk2017Cover';  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $this->servidor = $this->ipconfig;
	    $this->database = 'diskcover_empresas';
	    $this->puerto = 13306;
		$conn =  new mysqli($this->servidor, $this->usuario, $this->password,$this->database,$this->puerto);
		$conn->set_charset("utf8");
		if (!$conn) 
		{
			echo  mysqli_connect_error();
			return false;
		}else
		{
			// echo 'conec 1';
			$_SESSION['INGRESO']['base_actual'] =   $this->servidor;
		}
		return $conn;
	}



	function datos($sql,$tipo=false)
	{
		// print_r($tipo);die();
		if($tipo=='MY SQL' || $tipo =='MYSQL' || $tipo=='My SQL' || $tipo=='My sql' || $tipo=='MySQL')
		{
			// print_r($sql);die();
			$conn = $this->MySQL();
			$resultado = mysqli_query($conn, $sql);
			if(!$resultado)
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				return false;
			}
			$datos = array();
			while ($row = mysqli_fetch_assoc($resultado)) {
				$datos[] = $row;
			}
			mysqli_close($conn);
			return $datos;

		}else
		{

			// print_r($sql);die();
			$conn = $this->SQLServer();	
			$stmt = sqlsrv_query($conn,$sql);
			// print_r($sql);die();
			$result = array();	
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC) ) 
	     	{
	     		$result[] = $row;
	     	}
	     	sqlsrv_close($conn);
	     	return $result;

		}
	
	}

	function existe_registro($sql,$tipo=false)
	{
		if($tipo=='MY SQL' || $tipo =='MYSQL' || $tipo=='My SQL' || $tipo=='My sql')
		{
			$conn = $this->MySQL();
			$resultado = mysqli_query($conn, $sql);
			if(!$resultado)
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				return false;
			}
			$datos = array();
			while ($row = mysqli_fetch_assoc($resultado)) {
				$datos[] = $row;
			}
			mysqli_close($conn);
			if(count($datos)>0)
	     	{
	     		return 1;
	     	}else
	     	{
	     		return 0;
	     	}

		}else
		{
			$conn = $this->SQLServer();	
			$stmt = sqlsrv_query($conn,$sql);
			// print_r($sql);die();
			$result = array();	
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC) ) 
	     	{
	     		$result[] = $row;
	     	}
	     	sqlsrv_close($conn);
	     	if(count($result)>0)
	     	{
	     		return 1;
	     	}else
	     	{
	     		return 0;
	     	}

		}
	
	}


	function String_Sql($sql,$tipo=false)
	{
		if($tipo=='MY SQL'|| $tipo =='MYSQL' || $tipo=='My SQL' || $tipo=='My sql')
		{
			$conn = $this->MySQL();
			$resultado = mysqli_query($conn, $sql);
		    if(!$resultado)
		    {
			  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			  return -1;
		    }

		    mysqli_close($conn);
		    // print_r('expression');die();
		    return 1;

		}else
		{
		   $conn = $this->SQLServer();
           $stmt = sqlsrv_query($conn, $sql);
		   if(!$stmt)
		   {
			   die( print_r( sqlsrv_errors(), true));
			   sqlsrv_close($conn);
			return -1;
		   }

		   sqlsrv_close($conn);
		   return 1;

		}

	}

	function ejecutar_procesos_almacenados($sql,$parametros,$retorna=false,$tipo=false)
	{
		if($tipo=='MY SQL' || $tipo =='MYSQL' || $tipo=='My SQL' || $tipo=='My sql')
		{
			// colocar funcion para ejecutar procesos almacenados en mysql

		}else
		{
		       $conn = $this->SQLServer();
		       // print_r('expression');die();
           $stmt = sqlsrv_prepare($conn, $sql, $parametros);
		       // print_r('expression');die();
           $res = sqlsrv_execute($stmt);
           if ($res === false) 
           {
           	echo "Error en consulta PA.\n";  
           	$respuesta = -1;
           	die( print_r( sqlsrv_errors(), true));  
           }else{
				   sqlsrv_close($conn);
				   // if($retorna)
				   // {
				   // 	$result = array();
				   // 	 while( $row = sqlsrv_fetch_array($res)) 
			   	// 		{
				 		// 	$result[] = $row;
			   	// 		}
				   // 	 return $result;
				   // }
				   return 1;
				 }
		}

	}

	function devolver_stmt($sql)
	{
			$conn = $this->SQLServer();	
			$stmt = sqlsrv_query($conn,$sql);
			if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}				
	   sqlsrv_close($conn);
			return $stmt;
	}

	// conexion a base SQL server de terceros
 function modulos_sql_server($host,$user,$pass,$base,$Puerto)
   {
   	if($host=='tcp:mysql.diskcoversystem.com' &&  $this->ipconfig=='localhost')
    {
    	$host = $this->ipconfig;
    }
   	 $server=''.$host.', '.$Puerto;
		$connectionInfo = array("Database"=>$base, "UID" => $user,"PWD" => $pass,"CharacterSet" => "UTF-8");
	    $cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
			{
				return -1;
			}
		return $cid;
	}


	function consulta_datos_db_sql_terceros($sql,$host,$user,$pass,$base,$Puerto)
	{
		if($host=='tcp:mysql.diskcoversystem.com' &&  $this->ipconfig=='localhost')
    {
    	$host = $this->ipconfig;
    }
		$server=''.$host.', '.$Puerto;
		$connectionInfo = array("Database"=>$base, "UID" => $user,"PWD" => $pass,"CharacterSet" => "UTF-8");
	  $cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			//devuelve -1 cuando la conexion a sql server o la base de dato no estan bien 
			return -1;
			// echo 'no se pudo conectar a la base de datos';
			// die( print_r( sqlsrv_errors(), true));
		}
		$stmt = sqlsrv_query($cid,$sql);
		// print_r($sql);die();
		$result = array();	
		if( $stmt === false) {
			// die( print_r( sqlsrv_errors(), true) );
			//revuelve -2 cuando el sql no esta bien realizado
			return -2;
		}
		while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC) ) 
   	{
   		$result[] = $row;
   	}
     	sqlsrv_close($cid);
     	return $result;
	}

	function ejecutar_sql_terceros($sql,$host,$user,$pass,$base,$Puerto)
	{
		if($host=='tcp:mysql.diskcoversystem.com' &&  $this->ipconfig=='localhost')
    {
    	$host = $this->ipconfig;
    }
		$server=''.$host.', '.$Puerto;
		$connectionInfo = array("Database"=>$base, "UID" => $user,"PWD" => $pass,"CharacterSet" => "UTF-8");
	  $cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			return -1;
			echo 'no se pudo conectar a la base de datos';
			die( print_r( sqlsrv_errors(), true));
		}
		// print_r($sql);die();
	    $stmt = sqlsrv_query($cid, $sql);
	   if(!$stmt)
	   {
		   // die( print_r( sqlsrv_errors(), true));
		   // sqlsrv_close($cid);
			return -1;
	   }

	   sqlsrv_close($cid);
	   return 1;
	
	}


	
}
?>