<?php 
    //Aqui iria el resto del codigo para envio del correo
    /*
    require_once('../../../lib/phpmailer/enviar_emails.php');
    $email = new enviar_emails();
    $email->prueba_email_tm();
    */
    /*sleep(3);
    $binario = rand(0, 1);
    if($binario == 1){
        echo "success";
    }else{
        echo "error";
    }*/
    if(isset($_POST['data'])){
        require_once("../../db/variables_globales.php");
        require_once('../../../lib/phpmailer/enviar_emails.php');
        
        $TMail = new stdClass();
        $TMail->Subject = $_POST['data']['subject'];
        $TMail->de = $_POST['data']['de'];
        $TMail->Mensaje = $_POST['data']['mensaje'];
        $TMail->Adjunto = $_POST['data']['adjunto'];
        $TMail->Credito_No = $_POST['data']['credito_no'];
        $TMail->TipoDeEnvio = $_POST['data']['tipoDeEnvio'];
        $TMail->ListaMail = $_POST['data']['listaMail'];
        $TMail->para = $_POST['data']['para'];
        //$TMail->para = Insertar_Mail($TMail->para, "tedalemorvel@gmail.com");
        $email = new enviar_emails();
        $rps = $email->FEnviarCorreos($TMail, null, "");
        
        
        if($rps){
            $rps = $rps[0];
            if(isset($rps['error'])){
                echo $rps['mensaje'];
            }else if(isset($rps['rps'])){
                if($rps['rps'] == 1){
                    echo "success";
                }else{
                    echo "Ocurrio un problema en el envio del correo";
                }
            }
        }else{
            echo "Ocurrio un error al recibir datos de la funcion";
        }
    }
?>