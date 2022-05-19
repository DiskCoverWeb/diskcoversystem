<?php 
/**
 * 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


class enviar_emails
{
	// private $mail;
	function __construct()
	{
		
	}


function enviar_email($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apoyo,$nombre,$HTML=false,$gmial=0)
{

    // print_r($_SESSION['INGRESO']);die();
    $to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
     	if($value!='.')
     	{
	         $mail = new PHPMailer();
	         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;        //respuesta del servidor              
	         $mail->isSMTP();    
	         $mail->Host       =  $_SESSION['INGRESO']['smtp_Servidor'];
	         $mail->SMTPAuth   = true; 
	         $mail->Username = $_SESSION['INGRESO']['Email_Conexion'];
	         $mail->Password =  $_SESSION['INGRESO']['Email_Contrasena'];
	         if($_SESSION['INGRESO']['smtp_Secure']==0)
	         {
	           $mail->SMTPSecure = 'ssl';       
	           $mail->Port     =465;
	         }else
	         {                                 
	           $mail->SMTPSecure ='tls';      
	           $mail->Port     =587;          
	         }
	         $mail->setFrom($correo_apoyo,$nombre);
	         $mail->addAddress($value);
	         $mail->Subject = $titulo_correo;
	         if($HTML)
	         {
	          $mail->isHTML(true);
	         }
		     $mail->Body = $cuerpo_correo; // Mensaje a enviar
	         if($archivos)
	         {

	          foreach ($archivos as $key => $value) {
	           if(file_exists(dirname(__DIR__,2).'/php/vista/TEMP/'.$value))
	            {                   
	              $mail->AddAttachment(dirname(__DIR__,2).'/php/vista/TEMP/'.$value);
	            }          
	          }         
	        }
	          if (!$mail->send()) 
	          {
	            $respuesta = false;
	          }
   		}
	}

 
 
}

	// function enviar_email($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA,$HTML=false,$gmial=0)
	// {

 //    // print_r($_SESSION['INGRESO']);die();
	// 	$to =explode(',', $to_correo);
 //     foreach ($to as $key => $value) {
 //  		 $mail = new PHPMailer();
 //         //Server settings
 //         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
 //         $mail->isSMTP();                                            //Send using SMTP
 //         $mail->Host       =  $_SESSION['INGRESO']['smtp_Servidor'];// 'mail.diskcoversystem.com';            //Set the SMTP server to send through
 //         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
 //         $mail->SMTPSecure = $_SESSION['INGRESO']['smtp_Secure'];        //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
 //         $mail->Port     =$_SESSION['INGRESO']['smtp_Puerto'];                                  //TCP port to connect to, use 465 for   $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA //  matriculas@diskcoversystem.com
	//      $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA  //Dlcjvl1210@Matric
	//      $mail->setFrom($correo_apooyo,$nombre);

 //         $mail->addAddress($value);
 //         $mail->Subject = $titulo_correo;
 //         if($HTML)
 //         {
 //          $mail->isHTML(true);
 //         }
 //         $mail->Body = $cuerpo_correo; // Mensaje a enviar


 //         if($archivos)
 //         {
 //          foreach ($archivos as $key => $value) {
 //           if(file_exists('../../php/vista/TEMP/'.$value))
 //            {
 //          //		print_r('../vista/TEMP/'.$value);
          
 //         	  $mail->AddAttachment('../../php/vista/TEMP/'.$value);
 //             }          
 //          }         
 //        }
 //          if (!$mail->send()) 
 //          {
 //          	$respuesta = false;
 //     	    }
 //    }

 
 
 //  }
/*
  function enviar_credenciales($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA,$HTML=false,$gmail=0)
  {
    $to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
       $mail = new PHPMailer();
       $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
       $mail->isSMTP();                                            //Send using SMTP
       $mail->CharSet = "UTF­8";
       if($gmail)
       {
         //Server settings
         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //respuesta del servidor
         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->Port       = 587;                          //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        }else
        {

         $mail->Host       = 'mail.diskcoversystem.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->Port       = 465;                          //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
       }
        $mail->setFrom($correo_apooyo,$nombre);
        $mail->addAddress($value);
        $mail->Subject = $titulo_correo;
        $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA
        $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
        


         if($HTML)
         {
          $mail->isHTML(true);
         }
         //$mail->Body = $cuerpo_correo; // Mensaje a enviar
         //$mail->Body = 'Prueba';

         if($archivos)
         {
          foreach ($archivos as $key => $value) {
           if(file_exists('../../php/vista/TEMP/'.$value))
            {
          //    print_r('../vista/TEMP/'.$value);
          
            $mail->AddAttachment('../../php/vista/TEMP/'.$value);
             }          
          }         
        }
          /*if (!$mail->send()) 
          {
            return -1;
          }else
          {
            return 1;
          }
          if(!$mail->send()) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          } else {
            echo "Mensaje enviado!!";
          }
    }
  }
*/

  function enviar_credenciales($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA,$HTML=false,$empresaGeneral)
  {

    // print_r($empresaGeneral)};die();
    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
        $mail->isSMTP();                                         //Send using SMTP
        $mail->Host       = $empresaGeneral[0]['smtp_Servidor'];    //Set the SMTP server to send through
        $mail->SMTPAuth   = true;           //Enable SMTP authentication
        $mail->Username   = $empresaGeneral[0]['Email_Conexion'];          //SMTP username
        $mail->Password   = $empresaGeneral[0]['Email_Contraseña'];                 //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = $empresaGeneral[0]['smtp_Puerto'];                                 //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->SMTPSecure = $empresaGeneral[0]['smtp_Secure'];
        //$mail->SMTPSecure='STARTTLS';
        //Recipients
        $mail->setFrom($empresaGeneral[0]['Email_Conexion'], 'DiskCover System');
        $mail->addAddress('infosistema@diskcoversystem.com', 'Informacion DiskCover System');     //Add a recipient
        //$mail->addAddress('jd-avalos@hotmail.com', 'Jonathan Avalos');     //Add a recipient
        $mail->addAddress($to_correo);     //Add a recipient
        $mail->addReplyTo($empresaGeneral[0]['Email_Conexion'], 'Informacion');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $titulo_correo;
        $mail->Body    = $cuerpo_correo;
        // print_r($mail);
        // die();

        $mail->send();
        return 1;
        
    } catch (Exception $e) {
      // print_r($mail);die();
        return -1;
    }
  }

  function enviar_historial($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$nombre)
  {
    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
        $mail->isSMTP();                                         //Send using SMTP
        $mail->Host       = 'mail.diskcoversystem.com';    //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                //Enable SMTP authentication
        $mail->Username   = 'info@diskcoversystem.com';          //SMTP username
        $mail->Password   = 'info2021DiskCover';                 //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                 //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        //$mail->SMTPSecure = 'tls';
        //$mail->SMTPSecure='STARTTLS';
        //Recipients
        $mail->setFrom('info@diskcoversystem.com', 'DiskCover System');
        $mail->addAddress('jdavalos450@gmail.com', 'Jonathan Avalos');     //Add a recipient
        $mail->addAddress('jd-avalos@hotmail.com', 'Jonathan Avalos');     //Add a recipient
        //$mail->addAddress('info@diskcoversystem.com', 'DiskCover');     //Add a recipient
        //$mail->addAddress('diskcover@msn.com', 'DiskCover MSN');     //Add a recipient
        //$mail->addAddress('ramiro_ron@hotmail.com', 'Ron Ramiro');     //Add a recipient
        //$mail->addAddress('diskcover.system@gmail.com', 'Ron Ramiro');     //Add a recipient
        $mail->addAddress($to_correo,$nombre);     //Add a recipient
        $mail->addReplyTo('info@diskcoversystem.com', 'Informacion');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        $mail->addAttachment($archivos[0]);         //Add attachments
        $mail->addAttachment($archivos[1]);         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $titulo_correo;
        $mail->Body    = $cuerpo_correo;
        $mail->send();
        return true ;
    } catch (Exception $e) {
        return false;
    }
  }

  function recuperar_clave($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA)
  {
    $to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
       $mail = new PHPMailer();
         //Server settings
         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
         $mail->isSMTP();                                            //Send using SMTP
         $mail->Host       = 'mail.diskcoversystem.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
         // $mail->Username   = 'matriculas@diskcoversystem.com';                     //SMTP username
         // $mail->Password   = 'DiskCover1210';                               //SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->SMTPSecure = 'ssl';
         $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
       $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA
       $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
       $mail->setFrom($correo_apooyo,$nombre);

         $mail->addAddress($value);
         $mail->Subject = $titulo_correo;
         $mail->Body = $cuerpo_correo; // Mensaje a enviar


         if($archivos)
         {
          foreach ($archivos as $key => $value) {
           if(file_exists('../../php/vista/TEMP/'.$value))
            {
          //    print_r('../vista/TEMP/'.$value);
          
            $mail->AddAttachment('../../php/vista/TEMP/'.$value);
             }          
          }         
        }
          if (!$mail->send()) 
          {
            return -1;
          }else
          {
            return 1;
          }
    }
  }


  

}
?>