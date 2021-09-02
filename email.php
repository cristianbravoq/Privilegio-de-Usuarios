<?php

    require 'conexion.php';

    function enviarEmail($email, $nombre){

    $url = 'http://'.$_SERVER["SERVER_NAME"].'/login/activar.php?id='.$token;
    $asunto = "Actvar cuenta - Sistema de Usuarios";
    $cuerpo = "Estimado $nombre: <br /><br />Para continuar con el proceso de registro, 
        es indispensable dar click en el siguiente link<a href='$url'>Activar Cuenta</a>";

	$mail = new PHPMailer\PHPMailer\PHPMailer();
    //Set mailer to use smtp
	$mail->isSMTP();
    //Define smtp host
	$mail->Host = "smtp.gmail.com";
    //Enable smtp authentication
	$mail->SMTPAuth = true;
    //Set smtp encryption type (ssl/tls)
	$mail->SMTPSecure = "tls";
    //Port to connect smtp
	$mail->Port = "587";
    //Set gmail username
	$mail->Username = "cbq.alim@gmail.com";
    //Set gmail password
	$mail->Password = "31416BRAVO";
    //Email subject
	$mail->Subject = $asunto;
    //Set sender email
	$mail->setFrom("cbq.alim@gmail.com","Cristian Bravo");
    //Enable HTML
	$mail->isHTML(true);
    //Attachment
	$mail->addAttachment('img/attachment.png');
    //Email body
	$mail->Body = $cuerpo;
    //Add recipient
	$mail->addAddress($email);
    //Finally send email

    if ( $mail->send() ) {
        echo '<script language="javascript">alert("Correo enviado con exito!");</script>';
        echo '<script language="javascript">alert("Para terminar el proceso de registro siga las instrucciones que le hemos enviado al correo");</script>';
	}else{
		echo '<script language="javascript">alert("El correo de activacion no pudo ser enviado");</script>';;
	}
    //Closing smtp connection
	$mail->smtpClose();

}