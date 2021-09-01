<?php

// $server = 'localhost:3306';
// $username = 'root';
// $password = '';
// $database = 'login';

  $mysqli = new mysqli("localhost","root","","login");

  if(mysqli_connect_errno()){
    echo 'Conexion Fallida : ', mysqli_connect_errno();
    exit();
  }

?>