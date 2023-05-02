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
	    $this->password = $_SESSION['INGRESO']['Password_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
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
	    $this->password =  'disk2017@Cover';  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
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
		set_time_limit(0);
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
			$conn = $this->MySQL();
			$select = '';
			$variables = '';
			if(count($parametros)>1)
			{
				 foreach ($parametros as $key => $value) {
				 		if($value[1]=='OUT' || $value[1]=='out')
				 		{
				 			 $select.='@'.$value[0].',';
				 			 $variables.= '@'.$value[0].',';
				 		}else{
				 		$variables.= '"'.$value[0].'",'; 
				 	}
				 }
				 $variables = substr($variables,0,-1);
				 $select = substr($select,0,-1);
			}

			$sql = $sql.'('.$variables.')';


		if (!$conn->query($sql)) {
		   return  "Falló CALL: (" . $conn->errno . ") " . $conn->error;
		}

		if (!($resultado = $conn->query("SELECT ".$select))) {
		    return "Falló la obtención: (" . $conn->errno . ") " . $conn->error;
		}

		$fila = $resultado->fetch_assoc();
		return $fila;


		}else
		{
		       $conn = $this->SQLServer();
		       // print_r('expression');die();
           $stmt = sqlsrv_prepare($conn, $sql, $parametros);
		       // print_r('expression');die();
           $res = sqlsrv_execute($stmt);
           if ($res === false) 
           {
           	// echo "<script type='text/javascript'>alert('Estructura procesco almacenado')</script>";
           	// die();
           	// echo "Error en consulta PA.\n";  
           	// $respuesta = -1;
           	die( print_r("<script type='text/javascript'>alert('Estructura procesco almacenado')</script>", true));  
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

function control_procesos($TipoTrans,$Tarea,$opcional_proceso='')
{  
  // print_r($_SESSION['INGRESO']);die();
  $conn = new db();
  $TMail_Credito_No = G_NINGUNO;
  $NumEmpresa = $_SESSION['INGRESO']['item'];
  $TMail = '';
  $Modulo = $_SESSION['INGRESO']['modulo_'];
  if($NumEmpresa=="")
  {
    $NumEmpresa = G_NINGUNO;
  }
  if($TMail == "")
  {
    $TMail = G_NINGUNO;
  }
  if($Modulo <> G_NINGUNO AND $TipoTrans<>G_NINGUNO AND $NumEmpresa<>G_NINGUNO)
  {
    try {
      $sSQL = "SELECT Aplicacion " .
        "FROM modulos " .
        "WHERE modulo = '" . $Modulo . "' ";
      $rps = $conn->datos($sSQL,'MYSQL');
      $ModuloName = $rps[0]['Aplicacion'] ;
    } catch (Exception $e) {
      $ModuloName = $Modulo;
    }
    
    if($Tarea == G_NINGUNO)
    {
      $Tarea = "Inicio de Sección";
    }else
    {
      $Tarea = substr($Tarea,0,60);
    }
    $proceso = substr($opcional_proceso,0,60);
    $NombreUsuario1 = substr($_SESSION['INGRESO']['Nombre'], 0, 60);
    $TipoTrans = $TipoTrans;
    $Mifecha1 = date("Y-m-d");
    $MiHora1 = date("H:i:s");
    $CodigoUsuario= $_SESSION['INGRESO']['CodigoU'];
    if($Tarea == "")
    {
      $Tarea = G_NINGUNO;
    }
    if($opcional_proceso=="")
    {
      $opcional_proceso = G_NINGUNO;
    }

    $ip= ip();
    $sql = "INSERT INTO acceso_pcs (IP_Acceso,CodigoU,Item,Aplicacion,RUC,Fecha,Hora,
             ES,Tarea,Proceso,Credito_No,Periodo)VALUES('".$ip."','".$CodigoUsuario."','".$NumEmpresa."',
             '".$ModuloName."','".$_SESSION['INGRESO']['Id']."','".$Mifecha1."','".$MiHora1."','".$TipoTrans."','".$Tarea."','".$proceso."','".$TMail_Credito_No."','".$_SESSION['INGRESO']['periodo']."');";
    $conn->String_Sql($sql,'MYSQL');

  }
}
?>