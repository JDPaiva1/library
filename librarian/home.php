<?php
  require "../db_connect.php";
  require "verify_librarian.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido Bibliotecario</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css" />
  <link rel="stylesheet" type="text/css" href="../css/index.css" />
</head>
<body>
  <div id="allTheThings">
    <a href="pending_registrations.php">
      <input type="button" value="Solicitudes de registro de usuario" />
    </a><br />
    <a href="pending_book_requests.php">
      <input type="button" value="Solicitudes de libros pendientes" />
    </a><br />
    <a href="insert_book.php">
      <input type="button" value="Agregar un nuevo libro" />
    </a><br />
    <a href="list_books.php">
      <input type="button" value="Actualizar un libro" />
    </a><br />
    <a href="due_handler.php">
      <input type="button" value="Recordatorios" />
    </a>
  </div>
</body>
</html>