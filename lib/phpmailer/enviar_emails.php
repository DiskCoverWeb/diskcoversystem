<?php 
/**
 * 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
if(!class_exists('db'))
{
  include(dirname(__DIR__,2).'/php/db/db1.php');
}

class enviar_emails
{
	// private $mail;
	function __construct()
	{
		 $this->db  = new db();
	}


// funcion de envios enviando datos por correo (funciona)
function enviar_email($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$HTML=false)
{
  $empresaGeneral = $this->Empresa_data();
    // print_r($_SESSION['INGRESO']);die();
    $to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
     	if($value!='.' && $value!='')
     	{
	          $mail = new PHPMailer(true);
            try {
                //Server settings
                 //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
                $mail->isSMTP();                                         //Send using SMTP
                $mail->Host       = $empresaGeneral[0]['smtp_Servidor'];    //Set the SMTP server to send through
                $mail->SMTPAuth   = true;           //Enable SMTP authentication
                $mail->Username   = $empresaGeneral[0]['Email_Conexion'];          //SMTP username
                $mail->Password   = $empresaGeneral[0]['Email_Contraseña'];                 //SMTP password
                if($empresaGeneral[0]['smtp_SSL']==1)
                   {
                     $mail->SMTPSecure = 'ssl';       
                     $mail->Port     =465;
                   }else
                   {                                 
                     $mail->SMTPSecure ='tls';      
                     $mail->Port     =587;          
                   }

                $mail->setFrom($empresaGeneral[0]['Email_Conexion'], 'DiskCover System');
                $mail->addAddress($value);     //Add a recipient
                $mail->addReplyTo($empresaGeneral[0]['Email_Conexion'], 'Informacion');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');

                //Attachments
                // print_r($archivos);die();
                 if($archivos)
                   {

                    foreach ($archivos as $key => $value) {
                     if(file_exists(dirname(__DIR__,2).'/TEMP/'.$value))
                      {                   
                        $mail->AddAttachment(dirname(__DIR__,2).'/TEMP/'.$value);
                      }  
                      if(file_exists(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$value))
                      {                   
                        $mail->AddAttachment(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$value);                      
                      }     

                      if(file_exists(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Generados/'.$value))
                      {                   
                        $mail->AddAttachment(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Generados/'.$value);                       
                      }

                       if(file_exists(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$value))
                      {                   
                       
                        $mail->AddAttachment(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$value);
                      }     


                    }         
                  }

                //Content
                if($HTML)
                {
                  $mail->isHTML(true);   
                }                               //Set email format to HTML
                $mail->Subject = $titulo_correo;
                $mail->Body    = $cuerpo_correo;
                // print_r($mail);
                // die();

                // print_r('host:'.$mail->Host.'//Username:'.$mail->Username.'//pass:'.$mail->Password.'//Puerto:'.$mail->Port.'//Secure:'.$mail->SMTPSecure);die();

                if($mail->send())
                {
                  return 1;
                }
                
            } catch (Exception $e) {
              print_r($mail);
              print_r($e);
              die();
                return -1;
            }
	        
   		}
	}

 
 
}
// funcion de envios enviando datos por correo (funciona)
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
        if($empresaGeneral[0]['smtp_SSL']==1)
           {
             $mail->SMTPSecure = 'ssl';       
             $mail->Port     =465;
           }else
           {                                 
             $mail->SMTPSecure ='tls';      
             $mail->Port     =587;          
           }

        $mail->setFrom($empresaGeneral[0]['Email_Conexion'], 'DiskCover System');
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

        // print_r('host:'.$mail->Host.'//Username:'.$mail->Username.'//pass:'.$mail->Password.'//Puerto:'.$mail->Port.'//Secure:'.$mail->SMTPSecure);die();

        if($mail->send())
        {
          return 1;
        }
        
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

  function Empresa_data()
   {        
     $sql = "SELECT * FROM Empresas where Item='".$_SESSION['INGRESO']['item']."'";
     $datos = $this->db->datos($sql);
     return $datos;
   }

  

}
?>