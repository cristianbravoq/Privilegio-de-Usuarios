<?php
  session_start();

  require 'databases.php';

  if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

  }
?>

<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome to you WebApp</title>
  </head>

  <body>

      <br> Bienvenido. = <?= $results['email']; ?>
      <br>Ha iniciado sesión correctamente
      <a href="logout.php">Salir</a>

  </body>
</html>