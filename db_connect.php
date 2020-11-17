<?php
  $host = 'localhost';
  $port = 8889;
  $user = 'root';
  $password = 'root';
  $db = 'library';

  $connect = mysqli_init();
  $success = mysqli_real_connect($connect, $host, $user, $password, $db, $port);
  mysqli_set_charset($connect, 'utf8');
  if(!$connect) {
    die("ERROR: No se pudo conectar a la base de datos" . mysqli_connect_error());
  }
?>