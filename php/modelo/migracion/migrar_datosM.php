<?php 
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class migrar_datosM
{
	
	private $db;	
	function __construct()
	{
	    $this->db = new db();
	}

	function generarArchivos()
	{
			set_time_limit(0);
	    	ini_set('memory_limit', '1024M');
	    If(!file_exists('c:/DatosTbl/'))
	   	{
	   		mkdir('c:/DatosTbl/',0777,true);
	   	}
	   	If(!file_exists('c:/DatosTbl/TABLAS/'))
	   	{
	   		mkdir('c:/DatosTbl/TABLAS/',0777,true);
	   	}


	   	$respuesta = 1;
	    
	      $usuario = $_SESSION['INGRESO']['Usuario_DB'];
	      $password = $_SESSION['INGRESO']['Password_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	      $servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];     
	      $database = $_SESSION['INGRESO']['Base_Datos'];
	      $puerto = $_SESSION['INGRESO']['Puerto'];

		  $serverName =$servidor.','.$puerto;
		  $connectionOptions = array(
		      "Database" => $database,
		      "Uid" => $usuario,
		      "PWD" => $password, 
		  );


		  //busca las tablas que donforman la base de datos
	   		$sql = "SELECT TABLE_SCHEMA, TABLE_NAME
	           FROM INFORMATION_SCHEMA.TABLES
	           WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
	    		$tablas_base = $this->db->datos($sql);
	    		// print_r($tablas_base);die();



	    foreach ($tablas_base as $key => $value) 
	    {

	    		//buscamos las cabeceras de las tablas
	       		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
	                 FROM INFORMATION_SCHEMA.COLUMNS 
	                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
	        	$cabeceras_tabla = $this->db->datos($sql2);

		        $select_query = '';
		        foreach ($cabeceras_tabla as $key2 => $value2) 
		        {
		                $tipoCampo = $value2['DATA_TYPE'];
		                switch ($tipoCampo) {
		                    case 'char':
		                    case 'nvarchar':
		                    case 'varchar':
		                    case 'datetime':
		                        $select_query.="CONCAT('^',".$value2['COLUMN_NAME'].",'^') as ".$value2['COLUMN_NAME'].",";        
		                        break;
		                    default:
		                        $select_query.=$value2['COLUMN_NAME'].',';     
		                        break;
		                }
		        }

		        $select_query = substr($select_query,0,-1);
		        $query = 'SELECT '.$select_query.' FROM '.$value['TABLE_NAME'];

		        $query2 = "SELECT COUNT(*) AS NUM FROM " . $value['TABLE_NAME'];
		        $canti  = $this->db->datos($query2);

	        	$outputFile = "c:/DatosTbl/TABLAS/Z".$value['TABLE_NAME'].".txt";

	        	$command = "sqlcmd -S $serverName -d " . $connectionOptions['Database'] . " -U " . $connectionOptions['Uid'] . " -P " . $connectionOptions['PWD'] . " -Q \"$query\" -o \"$outputFile\" -s\",\" -W";
	        	// print_r($command);die();
	        	exec($command, $output, $returnVar);
		        if ($returnVar === 0) 
		        {
		             // Leer el archivo generado y reemplazar las comas por puntos y comas
		            $fileContent = file_get_contents($outputFile);
		            $fileContent = str_replace(',', ';', $fileContent);
		            file_put_contents($outputFile, $fileContent);


		          $ruta_archivo = $outputFile;
		          $cantidad = $canti[0]['NUM']+3;
		          $num_lineas_eliminar = [2,$cantidad,$cantidad+1,$cantidad+2,$cantidad+3];

						// Nombre del archivo temporal
						$archivo_temporal = 'temp.txt';
						$archivo_lectura = fopen($ruta_archivo, 'r');
						$archivo_escritura = fopen($archivo_temporal, 'w');
						$linea_actual = 1;

						// Copiar todas las líneas excepto las que deseas eliminar al archivo temporal
						while (($linea = fgets($archivo_lectura)) !== false) {
						    if (!in_array($linea_actual, $num_lineas_eliminar)) {
						        fwrite($archivo_escritura, $linea);
						    }
						    $linea_actual++;
						}

					// Cerrar los archivos
					fclose($archivo_lectura);
					fclose($archivo_escritura);

					// Reemplazar el archivo original con el archivo temporal
					rename($archivo_temporal, $ruta_archivo);

		        }else
		        {
		           $respuesta = -1;
		        } 

	    }

	    return $respuesta;


	}


	function generarSP()
	{
		set_time_limit(0);
	   	ini_set('memory_limit', '1024M');

	   	$resp = 1;
	   	if(!file_exists('c:/DatosTbl/'))
	   	{
	   		mkdir('c:/DatosTbl/',0777,true);
	   	}

	   	if(!file_exists('c:/DatosTbl/SP/'))
	   	{
	   		mkdir('c:/DatosTbl/SP/',0777,true);
	   	}
	    
	    $usuario = $_SESSION['INGRESO']['Usuario_DB'];
	    $password = $_SESSION['INGRESO']['Password_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];     
	    $database = $_SESSION['INGRESO']['Base_Datos'];
	    $puerto = $_SESSION['INGRESO']['Puerto'];

		$sql = "SELECT p.name AS sp,  m.definition AS Definition
				FROM  ".$database.".sys.procedures p
				INNER JOIN ".$database.".sys.sql_modules m ON p.object_id = m.object_id";
		$datosSP = $this->db->datos($sql);

		foreach ($datosSP as $key => $value) {
			$rutaArchivo = "c:/DatosTbl/SP/".$value['sp'].".txt";
			$contenido = $value['Definition'];

			$archivo = fopen($rutaArchivo, 'w');

			if ($archivo) {
			    // Escribir el contenido en el archivo
			    fwrite($archivo, $contenido);
			    fclose($archivo);

			} else {
				$resp = 0;
			}
		}

		return $resp;
	}

}

?>