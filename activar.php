<?php

    require 'conexion.php';
    require 'funciones.php';

    if(isset($_GET["id"]) AND isset($_GET['val'])){

        $idUsuario = $_GET['id'];
        $token = $_GET['val'];

        $mensaje = validaIdToken($idUsuario, $token);
    }

?>

<html>
<head>

    <title>ACTIVACION CUENTA</title>

</head>
<body>

    <div class="container">
        <div class="jumbotron">

            <h1> <?php echo $mensaje ; ?> </h1>

            <br>
            <p><a class="" href="index.php" role="button">
                Iniciar Sesion
            </a></p>

        </div>

</body>
</html>