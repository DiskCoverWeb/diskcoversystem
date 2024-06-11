<?php 
include('../headers/header.php');
// include('../db/db1.php');
// exportar_txt();
exportar_txt(1);


function exportar_txt()
{


    set_time_limit(0);
    ini_set('memory_limit', '1024M');
    
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


    $db2 = new db();

   $sql = "SELECT TABLE_SCHEMA, TABLE_NAME
           FROM INFORMATION_SCHEMA.TABLES
           WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
    $tablas_base = $db2->datos($sql);
    // print_r($tablas_base);die();
    foreach ($tablas_base as $key => $value) {
        $sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
                 FROM INFORMATION_SCHEMA.COLUMNS 
                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
        $cabeceras_tabla = $db2->datos($sql2);

        $select_query = '';
        foreach ($cabeceras_tabla as $key2 => $value2) {
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
        $canti  = $db2->datos($query2);

        $outputFile = "c:/DatosTbl/Z".$value['TABLE_NAME'].".txt";
        $command = "sqlcmd -S $serverName -d " . $connectionOptions['Database'] . " -U " . $connectionOptions['Uid'] . " -P " . $connectionOptions['PWD'] . " -Q \"$query\" -o \"$outputFile\" -s\",\" -W";
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
             // Leer el archivo generado y reemplazar las comas por puntos y comas
            $fileContent = file_get_contents($outputFile);
            $fileContent = str_replace(',', ';', $fileContent);
            file_put_contents($outputFile, $fileContent);


          $ruta_archivo = $outputFile;
          $cantidad = $canti[0]['NUM']+3;
          $num_lineas_eliminar = [2,$cantidad,$cantidad+1,$cantidad+2,$cantidad+3];

// Nombre del archivo temporal
$archivo_temporal = 'temp.txt';

// Abrir el archivo en modo lectura
$archivo_lectura = fopen($ruta_archivo, 'r');

// Abrir el archivo temporal en modo escritura
$archivo_escritura = fopen($archivo_temporal, 'w');

// Contador de líneas
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


            echo 'tabla'.($key+1);
        }else
        {
          echo "error";
        } 

    }





 
}




function exportar_txt2($grandes=false)
{
    $db2 = new db();
    set_time_limit(0);
    ini_set('memory_limit', '1024M');
    $ubicacion = dirname(__DIR__, 2) . "/TEMP/ZArchivos_".$_SESSION['INGRESO']['item'];
    
    if (!file_exists($ubicacion)) {
        mkdir($ubicacion, 0777, true);
    }

    $signo = '<=';
    if($grandes)
    {
        $signo = '>';
    }

    // Busca tablas de la base de datos
    // $sql = "SELECT TABLE_SCHEMA, TABLE_NAME
    //         FROM INFORMATION_SCHEMA.TABLES
    //         WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
    $sql = "SELECT t.NAME AS TABLE_NAME, p.[Rows] AS RowCounts
            FROM sys.tables t
            INNER JOIN sys.indexes i ON t.OBJECT_ID = i.object_id
            INNER JOIN sys.partitions p ON i.object_id = p.OBJECT_ID AND i.index_id = p.index_id
            WHERE i.index_id <= 1
            GROUP BY  t.NAME, p.[Rows]
            HAVING  p.[Rows] ".$signo." 15000
            ORDER BY p.[Rows] ASC;";
    $tablas_base = $db2->datos($sql);
// print_r($tablas_base);die();
    foreach ($tablas_base as $key => $value) {
        $patch = $ubicacion . '/Z' . $value['TABLE_NAME'] . '.txt';
        $cabeceras_tabla = array();
        $contenido = '';

        // Buscar estructura de tabla y generar cabeceras
        $sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
                 FROM INFORMATION_SCHEMA.COLUMNS 
                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
        $cabeceras_tabla = $db2->datos($sql2);
        $select_campos = '';

        foreach ($cabeceras_tabla as $key2 => $value2) {
            $contenido .= $value2['COLUMN_NAME'] . ';';        
            $select_campos .= $value2['COLUMN_NAME'] . ',';
        }

        $contenido = substr($contenido, 0, -1) . PHP_EOL;
        $select_campos = substr($select_campos, 0, -1);

        // Buscar los datos de la tabla
        $sql3 = "SELECT " . $select_campos . " FROM " . $value['TABLE_NAME'];
        $datos_tabla = $db2->datos($sql3);

        foreach ($datos_tabla as $key3 => $value3) {
            foreach ($cabeceras_tabla as $key4 => $value4) {
                $campo = $value4['COLUMN_NAME'];
                $tipoCampo = $value4['DATA_TYPE'];
                $valor = $value3[$campo];

                if (is_object($valor) && $valor instanceof DateTime) {
                    $valor = $valor->format('Y-m-d');
                }

                switch ($tipoCampo) {
                    case 'char':
                    case 'nvarchar':
                    case 'varchar':
                    case 'datetime':
                        $contenido .= '^' . $valor . '^;';
                        break;
                    default:
                        $contenido .= $valor . ';';
                        break;
                }
            }

            $contenido = substr($contenido, 0, -1) . PHP_EOL;
        }

        file_put_contents($patch, $contenido);
    }
}


?>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet" href="../../dist/css/arbol_bodega.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
<script>
  $(document).ready(function(){
   

  })

 
  </script>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">


    </div>
  </div>
  <?php include('../headers/footer.php');?>
 