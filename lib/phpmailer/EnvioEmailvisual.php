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
    	$to_correo = trim($parametros['to']);
    	$to_correo = str_replace(';',',',$to_correo);
    	$to = explode(',', $to_correo);
    	foreach ($to as $key => $value) 
    	{
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

			          	  $archivos = explode(';',$parametros['Archivo'])
			              foreach ($archivos as $key => $value) {
			                $mail->AddAttachment($value);              
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
		        }

	     	}
    	}

    	return $res;
	}
}
?>