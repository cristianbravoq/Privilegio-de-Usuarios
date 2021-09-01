<?php
    require 'conexion.php';
    require 'funciones.php';

    ///////////// Registar usuario nuevo ////////////////

    $errors = array();

    if(!empty($_POST)){

        $nombre = $mysqli->real_escape_string($_POST['nombre']);
        $usuario = $mysqli->real_escape_string($_POST['usuario']);
        $password = $mysqli->real_escape_string($_POST['password']);
        $con_password = $mysqli->real_escape_string($_POST['con_password']);
        $email = $mysqli->real_escape_string($_POST['email']);

        $activo = 0;
        $tipo_usuario = 2;
        $secret = '';

        ///validacion desde el servidor///

        if (isNull ($nombre, $usuario, $password, $con_password, $email)){
            $errors[] ="Debe llenar todos los campos";
        }

        if (!isEmail ($email)){
            $errors[] ="Direccion de correo invalida";
        }

        if (!validaPassword ($password, $con_password)){
            $errors[] ="Las contraseñas no coinciden";
        }

        if (usuarioExiste ($usuario)){
            $errors[] ="Este usuario ya existe";
        }

        if (emailExiste ($email)){
            $errors[] ="Este correo ya existe";
        }

        /////////////Guardando datos en el servidor//////////////

        if(count($errors) == 0){

            $pass_hash = hashPassword($password);
            $token = generateToken();

            $registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);

            if ($registro > 0){
                $url = 'http://'.$_SERVER["SERVER_NAME"].'/login/activar.php?id='.$token;
                $asunto = 'Actvar cuenta - Sistema de Usuarios'
                $cuerpo = "Estimado $nombre: <br /><br />Para continuar con el proceso
                            de registro, es indispensable dar click en el siguiente link
                            <a href='$url'>Activar Cuenta</a>";

            } else {
                $errors[] = "Error al registrar"
            }

        }

    }

    ///////////////////////////////////////////////////////



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>I.E. El placer</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="assets/css/estilos.css"> -->
</head>
<body>

    <main>

        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>

                    <p>Inicia sesión para ingresar</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>


                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>

            <!--Formulario de Login y registro-->
            <div class="contenedor__login-register">
                <!--Login-->
                <form action="index.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" name="email_log" placeholder="Correo Electronico">
                    <input type="password" name="password_log" placeholder="Contraseña">
                    <button type="submit">Entrar</button>
                </form>

                <!--Register-->
                <form id="signupform" action="<?php $_SERVER['PHP_SELF']?>" method="POST" class="formulario__register">
                    <h2>Regístrarse</h2>
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre completo" required>
                    <input type="email" class="form-control" name="email" placeholder="Correo Electronico" required>
                    <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                    <input type="password" class="form-control" name="con_password" placeholder=" Confirmar contraseña" required>
                    <button id="btn-signup" class="btn btn-info" type="submit">Regístrarse</button>
                </form>
            </div>
        </div>

    </main>

<!-- <script src="assets/js/script.js"></script> -->

</body>
</html>