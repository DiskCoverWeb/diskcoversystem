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

	function base_datos($Base_Datos)
	{
		$sql="SELECT ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto   FROM lista_empresas WHERE Base_Datos = '".$Base_Datos."'";
		$resp = $this->db->datos($sql,'MY SQL');
		// print_r($sql);die();
		  $datos=[];
		foreach ($resp as $key => $value) {
		
					$datos[]=['id'=>$value['ID'],'text'=>$value['Empresa'],'host'=>$value['IP_VPN_RUTA'],'usu'=>$value['Usuario_DB'],'pass'=>$value['Contrasena_DB'],'base'=>$value['Base_Datos'],'Puerto'=>$value['Puerto'],'Item'=>$value['Item']];				
		 }

	      return $datos;
	}

	function getDatabases($query)
    {
        $sql = "SELECT name FROM sys.databases ";
		
		if($query){
			$sql .= "WHERE name LIKE '%".$query."%' ";
		}

		$sql .= "ORDER BY name";
        $datos = $this->db->datos($sql);
        return $datos;
    }

	function generarArchivos($link, $Base_Datos)
	{
			set_time_limit(0);
	    	ini_set('memory_limit', '1024M');
	  	  $respuesta = 1;

		  $basedatos = $this->base_datos($Base_Datos);

	      $usuario = $basedatos[0]['usu'];
	      $password = $basedatos[0]['pass'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	      $servidor = $basedatos[0]['host'];     
	      $database = $basedatos[0]['base'];
	      $puerto = $basedatos[0]['Puerto'];
		$sql = "SELECT TABLE_SCHEMA, TABLE_NAME
	           FROM INFORMATION_SCHEMA.TABLES
	           WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
	    		$tablas_base = $this->db->consulta_datos_db_sql_terceros($sql, $servidor, $usuario, $password, $database, $puerto);
	    		// print_r($tablas_base);die();



	    $contenido = '';
	    foreach ($tablas_base as $key => $value) 
	    {
		    	$sql = 'SELECT Count(*) as total FROM '.$value['TABLE_NAME'];
		    	$datos =  $this->db->consulta_datos_db_sql_terceros($sql, $servidor, $usuario, $password, $database, $puerto);
		    	$query_select = '';
		    	$informe_cabe = '';
		    	if($datos[0]['total']>0)
		    	{
		    		$contenido.='REPLACE INTO `'.$value['TABLE_NAME'].'` (';
		    		//buscamos las cabeceras de las tablas
		       		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
		                 FROM INFORMATION_SCHEMA.COLUMNS 
		                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
		        	$cabeceras_tabla = $this->db->consulta_datos_db_sql_terceros($sql2, $servidor, $usuario, $password, $database, $puerto);
					
		        	//recore cabeceras
		        	foreach ($cabeceras_tabla as $key2 => $value2) {
		        		$contenido.="`".$value2['COLUMN_NAME']."`,";
		        		 $tipoCampo = $value2['DATA_TYPE'];
		                switch ($tipoCampo) {
		                    case 'char':
		                    case 'nvarchar':
		                    case 'varchar':
		                    case 'datetime':
		                        $query_select.="CONCAT('''',".$value2['COLUMN_NAME'].",'''') +',',";        
		                        break;
		                    default:
		                        $query_select.=$value2['COLUMN_NAME'].",',',";     
		                        break;
		                }
		                $informe_cabe.= '`'.$value2['COLUMN_NAME'].'`,';
		        	}
					//print_r($query_select);die();
		        	// $contenido = substr($contenido,0,-1);		        	
		        	$query_select = substr($query_select,0,-5); 	
		        	$informe_cabe = substr($informe_cabe,0,-1);
		        	// $contenido.=") VALUES". PHP_EOL;


		        	$sql2 = "SELECT CONCAT('(',".$query_select.", CASE WHEN (SELECT TOP 1 ID FROM ".$value['TABLE_NAME']." ORDER BY ID DESC) = ID THEN ');' ELSE '),' END ) as linea FROM ".$value['TABLE_NAME'];
					//print_r($sql2); die();
		        	$comando = 'sqlcmd -S '.$servidor.','.$puerto.' -U '.$usuario.' -P '.$password.' -d '.$database.' -Q "EXEC sp_helptext; SET NOCOUNT ON;SET QUOTED_IDENTIFIER OFF;'.$sql2.';" -o "'.$link.'Z'.$value['TABLE_NAME'].'.sql" -W -s"," -w 7000';
					//print_r($comando);die();
		        	exec($comando, $output, $return_var);

					if ($return_var === 0) {

						$archivoOriginal = $link.'Z' . $value['TABLE_NAME'] . '.sql';
					    // Ruta del nuevo archivo
					    $archivoNuevo = $link.'Z' . $database . '.sql';

					    // Texto a agregar al inicio del nuevo archivo
					    $textoNuevo = "REPLACE INTO `" . $value['TABLE_NAME'] . "` (" .$informe_cabe. ") VALUES\n";

					    // Leer el archivo original
					    $lineas = file($archivoOriginal);

					    // Eliminar las primeras 4 líneas
					    $lineasModificadas = array_slice($lineas, 4);

					    // Agregar el nuevo texto al inicio del contenido
					    $contenidoFinal = $textoNuevo . implode('', $lineasModificadas);

					    // Agregar el contenido final al archivo nuevo
					    file_put_contents($archivoNuevo, $contenidoFinal, FILE_APPEND | LOCK_EX);
					    unlink($archivoOriginal);

					    // die();
					} else {
					    echo "Hubo un problema al crear el archivo de respaldo.";
					    print_r($return_var);
					}

		        	// print_r($comando);die();
		        	// print_r($sql2);die();
		        }
		}

	}
	function generarEsquemas($link, $Base_Datos)
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$respuesta = 1;
		$archivo = $link."Z".$Base_Datos.".sql";

		// Crear un nuevo contenido o archivo (en este caso, un archivo de texto simple)
		$nuevoContenido = "USE `".$Base_Datos."`;\n";
		
		// Escribir el nuevo contenido en el archivo (reemplaza el contenido anterior)
		file_put_contents($archivo, $nuevoContenido);
		/*if (file_exists($archivo)) {
			// Si el archivo existe, lo reemplazamos
			echo "El archivo ya existe. Reemplazando con uno nuevo.";
			
		}*/

		$basedatos = $this->base_datos($Base_Datos);

		$usuario = $basedatos[0]['usu'];
		$password = $basedatos[0]['pass'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
		$servidor = $basedatos[0]['host'];     
		$database = $basedatos[0]['base'];
		$puerto = $basedatos[0]['Puerto'];

		$sql = "SELECT TABLE_SCHEMA, TABLE_NAME
	           FROM INFORMATION_SCHEMA.TABLES
	           WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
		$tablas_base = $this->db->consulta_datos_db_sql_terceros($sql, $servidor, $usuario, $password, $database, $puerto);
	    		// print_r($tablas_base);die();

	    $contenido = '';
		
	    foreach ($tablas_base as $key => $value) 
	    {
			$createsql = "DROP TABLE IF EXISTS `".$Base_Datos."`.`".$value['TABLE_NAME']."`;\n";
			/*$sql2 = "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH 
					FROM INFORMATION_SCHEMA.COLUMNS 
					WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; */
			$sql2 = "SELECT c.name AS ColumnName, t.name AS TableName, tp.name AS DataType, c.max_length AS MaxLength, c.is_nullable AS IsNullable, c.is_identity AS IsIdentity, c.is_computed AS IsComputed, i.is_primary_key AS IsPrimaryKey, fk.name AS ForeignKey, idx.name AS IndexName, idx.type_desc AS IndexType, idx.is_unique AS IsUniqueIndex
					FROM sys.columns c JOIN sys.tables t ON c.object_id = t.object_id
					JOIN sys.types tp ON c.user_type_id = tp.user_type_id
					LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
					LEFT JOIN sys.indexes idx ON ic.object_id = idx.object_id AND ic.index_id = idx.index_id
					LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id AND i.is_primary_key = 1
					LEFT JOIN sys.foreign_key_columns fkc ON c.object_id = fkc.parent_object_id AND c.column_id = fkc.parent_column_id
					LEFT JOIN sys.foreign_keys fk ON fkc.constraint_object_id = fk.object_id
					WHERE t.name = '".$value['TABLE_NAME']."'";
			/*$sql2 = "SELECT c.name AS ColumnName, t.name AS TableName, tp.name AS DataType, c.max_length AS MaxLength, c.is_nullable AS IsNullable, c.is_identity AS IsIdentity, c.is_computed AS IsComputed, i.is_primary_key AS IsPrimaryKey, fk.name AS ForeignKey
					FROM sys.columns c JOIN sys.tables t ON c.object_id = t.object_id
					JOIN sys.types tp ON c.user_type_id = tp.user_type_id
					LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
					LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id AND i.is_primary_key = 1
					LEFT JOIN sys.foreign_key_columns fkc ON c.object_id = fkc.parent_object_id AND c.column_id = fkc.parent_column_id
					LEFT JOIN sys.foreign_keys fk ON fkc.constraint_object_id = fk.object_id
					WHERE t.name = 'Accesos'";*/
			$cabeceras_tabla = $this->db->consulta_datos_db_sql_terceros($sql2, $servidor, $usuario, $password, $database, $puerto);
			$createsql .= "CREATE TABLE IF NOT EXISTS `".$Base_Datos."`.`".$value['TABLE_NAME']."`";
			$columnas = array();
			$pks = array();
			$indexes = array();
			$indexName = "";
			foreach($cabeceras_tabla as $key2 => $value2){
				$tipoCampo = strtoupper($value2['DataType']);
				$ncampo = $value2['MaxLength'];
				switch($tipoCampo){
					case 'NTEXT':
						$tipoCampo = "LONGTEXT";
						break;
					/*case 'bit':
						$tipoCampo = "bit(1)";*/
					case 'MONEY':
						$tipoCampo = "DECIMAL(19,4)";
						break;
					case 'SMALLMONEY':
						$tipoCampo = "DECIMAL(10,4)";
						break;
					case 'REAL':
						$tipoCampo = "FLOAT";
						break;
					case 'SMALLDATETIME':
						$tipoCampo = "DATETIME";
						break;
					case 'DATETIME2':
						$tipoCampo = "DATETIME(6)";
						break;
					case 'CHAR':
					case 'NCHAR':
					case 'VARCHAR':
					case 'NVARCHAR':
					case 'VARBINARY':
					{
						if($ncampo && $ncampo > 0){
							$tipoCampo = $tipoCampo."(".$ncampo.")";
						}else{
							if($tipoCampo == 'VARBINARY' || $tipoCampo == 'IMAGE'){
								$tipoCampo = "LONGBLOB";
							}else{
								$tipoCampo = "LONGTEXT";
							}
						}
					}
					break;
					case 'UNIQUEIDENTIFIER':
						$tipoCampo = "BINARY(16)";
						break;
					
				}
				$isnull = $value2['IsNullable'] == 1 ? " NULL" : " NOT NULL";
				$autoincrement = $value2['IsIdentity'] == 1 ? " AUTO INCREMENT" : "";
				$columna = "`".$value2['ColumnName']."` ".$tipoCampo.$isnull.$autoincrement;
				$columnas[] = $columna;
				if($value2['IsPrimaryKey']){
					$pks[] = "PRIMARY KEY (`".$value2['ColumnName']."`)";
				}
				if($value2['IndexName'] && !($value2['IsPrimaryKey'] == 1)){
					$indexes[] = "`".$value2['ColumnName']."` ASC";
					$indexName = $value2['IndexName'];
					//$indexes[$value2['IndexName']] = "PRIMARY KEY (".$value2['ColumnName'].")";
				}

			}

			$indparams = join(", ",$indexes);
			$indxsql = array(0 => "INDEX `".$indexName."` (".$indparams.") VISIBLE");
			$columnas = array_merge($columnas, $pks, $indxsql);
			$createparams = join(", \n\t",$columnas);
			$createsql .= "(\n\t".$createparams."\n);\n";
			//print_r($createsql);die();
			
			$archivoSQL = fopen($archivo, 'a');
			// Verificar si el archivo se abrió correctamente
			if ($archivoSQL) {
				// Escribir el nuevo contenido al final del archivo
				fwrite($archivoSQL, $createsql);

				// Cerrar el archivo
				fclose($archivoSQL);

				//echo "Contenido agregado correctamente.";
			} else {
				//echo "Error al abrir el archivo.";
			}
		    	/*$sql = 'SELECT Count(*) as total FROM '.$value['TABLE_NAME'];
		    	$datos =  $this->db->consulta_datos_db_sql_terceros($sql, $servidor, $usuario, $password, $database, $puerto);
		    	$query_select = '';
		    	$informe_cabe = '';
		    	if($datos[0]['total']>0)
		    	{
		    		$contenido.='REPLACE INTO `'.$value['TABLE_NAME'].'` (';
		    		//buscamos las cabeceras de las tablas
		       		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, 
		                 FROM INFORMATION_SCHEMA.COLUMNS 
		                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
		        	$cabeceras_tabla = $this->db->consulta_datos_db_sql_terceros($sql2, $servidor, $usuario, $password, $database, $puerto);
					
		        	//recore cabeceras
		        	foreach ($cabeceras_tabla as $key2 => $value2) {
		        		$contenido.="`".$value2['COLUMN_NAME']."`,";
		        		 $tipoCampo = $value2['DATA_TYPE'];
		                switch ($tipoCampo) {
		                    case 'char':
		                    case 'nvarchar':
		                    case 'varchar':
		                    case 'datetime':
		                        $query_select.="CONCAT('''',".$value2['COLUMN_NAME'].",'''') +',',";        
		                        break;
		                    default:
		                        $query_select.=$value2['COLUMN_NAME'].",',',";     
		                        break;
		                }
		                $informe_cabe.= '`'.$value2['COLUMN_NAME'].'`,';
		        	}
					//print_r($query_select);die();
		        	// $contenido = substr($contenido,0,-1);		        	
		        	$query_select = substr($query_select,0,-5); 	
		        	$informe_cabe = substr($informe_cabe,0,-1);
		        	// $contenido.=") VALUES". PHP_EOL;


		        	$sql2 = "SELECT CONCAT('(',".$query_select.", CASE WHEN (SELECT TOP 1 ID FROM ".$value['TABLE_NAME']." ORDER BY ID DESC) = ID THEN ');' ELSE '),' END ) as linea FROM ".$value['TABLE_NAME'];
					//print_r($sql2); die();
		        	$comando = 'sqlcmd -S '.$servidor.','.$puerto.' -U '.$usuario.' -P '.$password.' -d '.$database.' -Q "EXEC sp_helptext; SET NOCOUNT ON;SET QUOTED_IDENTIFIER OFF;'.$sql2.';" -o "'.$link.'Z'.$value['TABLE_NAME'].'.sql" -W -s"," -w 7000';
					//print_r($comando);die();
		        	exec($comando, $output, $return_var);

					if ($return_var === 0) {

						$archivoOriginal = $link.'Z' . $value['TABLE_NAME'] . '.sql';
					    // Ruta del nuevo archivo
					    $archivoNuevo = $link.'Z' . $database . '.sql';

					    // Texto a agregar al inicio del nuevo archivo
					    $textoNuevo = "REPLACE INTO `" . $value['TABLE_NAME'] . "` (" .$informe_cabe. ") VALUES\n";

					    // Leer el archivo original
					    $lineas = file($archivoOriginal);

					    // Eliminar las primeras 4 líneas
					    $lineasModificadas = array_slice($lineas, 4);

					    // Agregar el nuevo texto al inicio del contenido
					    $contenidoFinal = $textoNuevo . implode('', $lineasModificadas);

					    // Agregar el contenido final al archivo nuevo
					    file_put_contents($archivoNuevo, $contenidoFinal, FILE_APPEND | LOCK_EX);
					    unlink($archivoOriginal);

					    // die();
					} else {
					    echo "Hubo un problema al crear el archivo de respaldo.";
					    print_r($return_var);
					}

		        	// print_r($comando);die();
		        	// print_r($sql2);die();
		        }*/
		}

	}

	function generarArchivos2($link)
	{
			set_time_limit(0);
	    	ini_set('memory_limit', '1024M');
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



	    $contenido = '';
	    foreach ($tablas_base as $key => $value) 
	    {
		    	$sql = 'SELECT Count(*) as total FROM '.$value['TABLE_NAME'];
		    	$datos =  $this->db->datos($sql);
		    	$query_select = '';
		    	if($datos[0]['total']>0)
		    	{
		    		$contenido.='REPLACE INTO `'.$value['TABLE_NAME'].'` (';
		    		//buscamos las cabeceras de las tablas
		       		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
		                 FROM INFORMATION_SCHEMA.COLUMNS 
		                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
		        	$cabeceras_tabla = $this->db->datos($sql2);
		        	foreach ($cabeceras_tabla as $key2 => $value2) {
		        		$contenido.="`".$value2['COLUMN_NAME']."`,";
		        		 $tipoCampo = $value2['DATA_TYPE'];
		                switch ($tipoCampo) {
		                    case 'char':
		                    case 'nvarchar':
		                    case 'varchar':
		                    case 'datetime':
		                        $query_select.="CONCAT('''',".$value2['COLUMN_NAME'].",'''') +',' ,";        
		                        break;
		                    default:
		                        $query_select.=$value2['COLUMN_NAME'].',';     
		                        break;
		                }
		        	}
		        	$contenido = substr($contenido,0,-1);		        	
		        	$query_select = substr($query_select,0,-1);
		        	$contenido.=") VALUES". PHP_EOL;


		        	$sql2 = "SELECT CONCAT( ".$query_select." ) as linea FROM ".$value['TABLE_NAME'];
			        $datos2  = $this->db->datos($sql2);

			        foreach ($datos2 as $key3 => $value3) {
			        	$contenido.="(".$value3['linea']."),". PHP_EOL;;
			        }

			        $contenido = rtrim($contenido, "\r\n");
		        	$contenido = substr($contenido,0,-1);	
		        	$contenido = $contenido.';'. PHP_EOL;


		        	$contenido.= "-- Volcando datos para la tabla ".$_SESSION['INGRESO']['Base_Datos'].".".$value['TABLE_NAME']. PHP_EOL;

		    	}

	    }

	    $outputFile = $link."/Migracion_".$_SESSION['INGRESO']['item'].".sql";
		if (file_put_contents($outputFile, $contenido) !== false) {
		    echo "Archivo creado y datos escritos con éxito.";
		    die();
		} else {
		    echo "Error al escribir en el archivo.";
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