<?php 
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

require_once(dirname(__DIR__,2). '/modelo/migracion/migrar_datosM.php');
$controlador = new migrar_datosC();

if(isset($_GET['generarArchivos']))
{
	echo json_encode($controlador->generarArchivos());
}
if(isset($_GET['generarSP']))
{
	echo json_encode($controlador->generarSP());
}


class migrar_datosC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new migrar_datosM();
	}

	
	function generarArchivos()
	{
		$this->modelo->generarArchivos();
		$url = "c:/DatosTbl/TABLAS/";
		$this->generarZip("TABLAS",$url);
	}

	function generarSP()
	{
		$this->modelo->generarSP();
		$url = "c:/DatosTbl/SP/";
		$this->generarZip('SP',$url);
	}

	function generarZip($carpetaName,$ruta)
	{
		$zip = new ZipArchive();
		$filename = $carpetaName.".zip";


		if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
		    exit("No se pudo abrir el archivo <$filename>\n");
		}

		// Ruta de la carpeta que deseas añadir al archivo .zip
		$carpeta =  $ruta;

		// Añadir la carpeta y su contenido al archivo .zip
		$this->agregarCarpetaAlZip($zip, $carpeta, basename($carpeta));

		// Cerrar el archivo .zip
		$zip->close();

		// Ofrecer el archivo .zip para su descarga
		if (file_exists($filename)) {
		    header('Content-Type: application/zip');
		    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
		    header('Content-Length: ' . filesize($filename));
		    flush();
		    readfile($filename);
		    // Elimina el archivo .zip después de la descarga si no quieres guardarlo en el servidor
		    unlink($filename);
		    exit;
		} else {
		    exit("El archivo no existe.");
		}
	}

	function agregarCarpetaAlZip($zip, $carpeta, $carpetaDentroDelZip) {
    if (is_dir($carpeta)) {
        if ($dh = opendir($carpeta)) {
            // Añadir la carpeta dentro del archivo .zip
            $zip->addEmptyDir($carpetaDentroDelZip);
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($carpeta . '/' . $file)) {
                        // Añadir subcarpeta
                        $this->agregarCarpetaAlZip($zip, $carpeta . '/' . $file, $carpetaDentroDelZip . '/' . $file);
                    } else {
                        // Añadir archivo
                        $zip->addFile($carpeta . '/' . $file, $carpetaDentroDelZip . '/' . $file);
                    }
                }
            }
            closedir($dh);
        }
    }
}
}

?>