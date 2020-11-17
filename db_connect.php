<?php
  // $host = 'localhost:8889';
  $host = 'localhost';
  $port = 8889;
  $user = 'root';
  $password = 'root';
  $db = 'library';

  // $connect = new mysqli($host, $user, $password, $db);
  // $connect = mysqli_connect($host, $user, $password, $db);
  $connect = mysqli_init();
  $success = mysqli_real_connect($connect, $host, $user, $password, $db, $port);
  if(!$connect) {
    die("ERROR: Couldn't connect to database " . mysqli_connect_error());
  }
?>