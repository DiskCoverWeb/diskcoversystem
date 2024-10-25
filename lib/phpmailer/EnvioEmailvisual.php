<?php 

header("Access-Control-Allow-Origin: *"); // Permite todas las orígenes, puedes restringir a uno específico
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


if(isset($_GET['EnviarVisual']))
{
	$controlor = new EnviarVisual();
	$parametros = $_POST;
	// $parametros = array(
	// 	'to'=>'javier.farinango92@gmail.com',
	// 	'body'=> 'hola nuevo email',
	// 	'subject'=> "prueba correo",
	// 	'HTML'=>1,
	// 	'Archivo'=> array('ruta'=>''),
	// 	);
	echo json_decode($controlor->EnvioEmailVisual($parametros));
}

class EnviarVisual 
{
	
	function __construct()
	{
		// code...
	}

	function EnvioEmailVisual($parametros)
	{

		$temp_file = 'ftp_folder_visual/';
		if($parametros['Archivo']!='')
		{
			$this->descargar_archivos_ftp($parametros['Archivo']);
		}

    	$to_correo = trim($parametros['to']);
    	$to_correo = str_replace(';',',',$to_correo);
    	$to = explode(',', $to_correo);
    	foreach ($to as $key => $value) 
    	{
    		$list_delete = array();
     		if ($value != '.' && $value != '') 
     		{
	        	$mail = new PHPMailer(true);
	        	$mail->SMTPOptions = array(
	          	'ssl' => array(
	            	'verify_peer' => false,
	            	'verify_peer_name' => false,
	            	'allow_self_signed' => true
		          	)
		        );

	        try {
		          //Server settings
		          // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
		            $mail->isSMTP(); //Send using SMTP
		            // $mail->Helo = 'smtp.diskcoversystem.com';    
				    $mail->Host = 'imap.diskcoversystem.com';
				    $mail->SMTPAuth = true;
				    $mail->Username = 'admin';
				    $mail->Password = 'Admin@2023';
				    $mail->SMTPSecure = false; // Dejar en blanco para 'tls'
				    $mail->SMTPAutoTLS = true; // Desactivar el inicio automático de TLS
				    $mail->SMTPSecure = 'tls';
				    $mail->Port = 587;
	         
			        $from = $parametros['from']; 
			        $mail->addAddress($value);  
			        $mail->setFrom($from, 'DiskCover System');
			        $mail->addReplyTo($from, 'Informacion');
			          //$mail->addCC('cc@example.com');
			          //$mail->addBCC('bcc@example.com');

			          // Attachments
			          if ($parametros['Archivo']!='') {

			          	  $archivos = explode(';',$parametros['Archivo']);
			              foreach ($archivos as $key => $value) {
			              	$list_delete[] = $temp_file.$value;
			                $mail->AddAttachment($temp_file.$value);              
			            }
			          }
			          //Content
			          if ($parametros['HTML']) {
			            $mail->isHTML(true);
			          } 
			          $mail->Subject = $parametros['subject'];
			          $mail->Body =  $parametros['body'];
			          if(strlen($from)>3)
			          {
			            if ($mail->send()) {
			              $res = 1;
			            }
			          }


		        } catch (Exception $e) {
		          // print_r($mail);
		          // print_r($e);
		          // die();
		          return -1;
		        }finally {

		        	foreach ($list_delete as $key => $value) {
		        		if (file_exists($value)) {
				        	unlink($value);
				    	}
		        	}
		        }
	     	}
    	}

    	return $res;
	}


	function descargar_archivos_ftp($archivos)
	{

		//proceso para envio de archivo por ftp 
		$ftp_host = "erp.diskcoversystem.com";
		$ftp_user = "ftpuser";
		$ftp_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites

		$remote_file = '/files/AddAttachment/';
		$temp_file = 'ftp_folder_visual/';
		$remote_path = '/';

		$ftp_conn = ftp_connect($ftp_host, $ftp_port) or die("No se pudo conectar al servidor FTP.");
		$login = ftp_login($ftp_conn, $ftp_user, $ftp_pass);

		// $archivos = ftp_nlist($ftp_conn, $remote_path);

		// if (ftp_chdir($ftp_conn, "files")) {
		//     echo "\nCambiado al directorio: $directorio\n";

		//     // Listar archivos en el nuevo directorio
		//     $archivos = ftp_nlist($ftp_conn, '.');
		//     if ($archivos) {
		//         foreach ($archivos as $archivo) {
		//             echo $archivo . "\n";
		//         }
		//     } else {
		//         echo "El directorio está vacío o no se pudo listar los archivos.\n";
		//     }
		// } 
		// print_r($archivos);die();

		$archi  = explode(';',$archivos);
		foreach ($archi as $key => $value) {
			ftp_get($ftp_conn, $temp_file.$value, $remote_file.$value, FTP_BINARY);			
		}
		ftp_close($ftp_conn);



	}
}
?>