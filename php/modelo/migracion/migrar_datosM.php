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

	function generarArchivos($link)
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

	        	$outputFile = $link."/Z".$value['TABLE_NAME'].".txt";

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



	function generarSP($link)
	{
		set_time_limit(0);
	   	ini_set('memory_limit', '1024M');
	   	$resp = 1;	   	
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
			$rutaArchivo = $link.$value['sp'].".txt";
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
	function Enviar_ftp($link,$archivo)
	{

		// $this->leer_ftp($link,'142.txt');
		// die();
		$ftp_server = "db.diskcoversystem.com";
		$ftp_user_name = "ftpuser";
		$ftp_user_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites


		$link_remoto = '/files/';
		$file_local_upload = $link; // Ruta local del archivo a subir
		$file_remote_upload = $link_remoto .$archivo; // Nombre del archivo remoto


		// print_r($file_local_upload.'--'.$file_remote_upload);die();

		// Conectar al servidor FTP en el puerto especificado
		$conn_id = ftp_connect($ftp_server, $ftp_port);

		// Autenticarse con el usuario y la contraseña
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

		// Comprobar la conexión
		if ((!$conn_id) || (!$login_result)) {
		    die("No se pudo conectar al servidor FTP con los detalles proporcionados.");
		}
		echo "Conectado a $ftp_server en el puerto $ftp_port, como $ftp_user_name\n";

		// Subir el archivo
		if (ftp_put($conn_id, $file_remote_upload, $file_local_upload, FTP_BINARY)) {
		    echo "El archivo $file_local_upload se ha subido satisfactoriamente como $file_remote_upload.\n";
		} else {
		    echo "Hubo un problema al subir el archivo $file_local_upload.\n";
		}

		// Cerrar la conexión FTP
		ftp_close($conn_id);
	}

	function leer_ftp($link,$archivo)
	{
// Detalles del servidor FTP
$ftp_server = "db.diskcoversystem.com";
$ftp_user_name = "ftpuser";
$ftp_user_pass = "ftp2023User";
$ftp_port = 21; // Cambia al puerto que necesites

$link_remoto = '/home/ftpuser/ftp/files/';


$link_remoto = '/home/ftpuser/ftp/files/';
$file_remote_download = $link_remoto . '142.txt'; // Nombre del archivo remoto
$file_local_download = 'C:\\Users\\usuario\\Desktop\\Payload\\142.txt'; // Nombre del archivo remoto

// Conectar al servidor FTP en el puerto especificado
$conn_id = ftp_connect($ftp_server, $ftp_port);

// Autenticarse con el usuario y la contraseña
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// Comprobar la conexión
if ((!$conn_id) || (!$login_result)) {
    die("No se pudo conectar al servidor FTP con los detalles proporcionados.");
}
echo "Conectado a $ftp_server en el puerto $ftp_port, como $ftp_user_name\n";

// Verificar si la ruta local es escribible
if (!is_writable(dirname($file_local_download))) {
    die("El directorio local no es escribible: " . dirname($file_local_download));
}

// Descargar el archivo
if (ftp_get($conn_id, $file_local_download, $file_remote_download, FTP_BINARY)) {
    echo "El archivo $file_remote_download se ha descargado satisfactoriamente como $file_local_download.\n";
} else {
    echo "Hubo un problema al descargar el archivo $file_remote_download.\n";
}

// Cerrar la conexión FTP
ftp_close($conn_id);



	}

}

?>