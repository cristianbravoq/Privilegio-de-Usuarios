<?php

    function isNull($nombre, $user, $pass, $pass_con, $email) {
        if(strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || 
        strlen(trim($pass)) < 1 || strlen(trim($pass_con)) < 1 || 
        strlen(trim($email)) < 1) {
            return true;
        } else {
            return false;
        }
    }

    function isEmail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }
    }

    function validaPassword($var1, $var2){
        if (strcmp($var1, $var2) != 0){
            return false;
        } else {
            return true;
        }
    }

    function usuarioExiste($usuario){
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();

        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    function emailExiste($email){
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();

        if ($num > 0) {
            return true;
        } else {
            return false;
        }

    }

    function generateToken(){
        $gen = md5(uniqid(mt_rand(), false));
        return $gen;
    }

    function hashPassword($password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    function resultBlock($errors){
        if(count($errors) > 0){
            echo "<div id='error' class='alert alert-danger' role='alert'>
                <a href='#' onclick=\"showHide('error');\">[X]</a>
                <ul>";
            foreach($errors as $error){
                echo "<li>".$error."</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }

    function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario){

        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo)
                                VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssisi', $usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);

        if ($stmt->execute()) {
            return $mysqli->insert_id;
        } else {
            return 0;
        }
    }


    function enviarEmail($email, $nombre, $asunto, $cuerpo){
    
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

    function validaIdToken($id, $token){
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE id = ? AND token = ? LIMIT 1");
        $stmt->bind_param("is", $id, $token);
        $stmt->execute();
        $stmt->store_result();
        $rows = $stmt->num_rows;

        if($rows > 0) {
            $stmt->bind_result($activacion);
            $stmt->fetch();

            if($activacion == 1){
                $msg ="La cuenta ya se activo anteriormente";
            } else {
                if(activarUsuario($id)){
                    $msg ='Cuenta activada';
                } else {
                    $msg = 'Error al activar la cuenta';
                }
            }

        } else {
            $msg = 'No existe el registro para activar';
        }
        return $msg;
    }

    function activarUsuario($id){
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE usuarios SET activacion=1 WHERE id = ?");
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

?>