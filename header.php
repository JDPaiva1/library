<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700">
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
</head>
<body>
  <header>
    <div id="cd-logo">
      <a href="/index.php">
        <img src="../img/ic_logo.svg" alt="Logo" />
        <p>BIBLIOTECA</p>
      </a>
    </div>
    <?php
      if(empty($_SESSION['type']));
      else if(strcmp($_SESSION['type'], "librarian") == 0) {
        echo "
          <div class='dropdown'>
            <button class='dropbtn librarian'>
              <p id='librarian-name'>".$_SESSION['username']."</p>
            </button>
            <div class='dropdown-content'>
              <a href='/logout.php'>Cerrar Sesión</a>
            </div>
          </div>";
      } else if(strcmp($_SESSION['type'], "member") == 0) {
        echo "
          <div class='dropdown'>
            <button class='dropbtn member'>
              <p id='librarian-name'>".$_SESSION['username']."</p>
            </button>
            <div class='dropdown-content'>
              <a href='my_books.php'>Mis Libros</a>
              <a href='/logout.php'>Cerrar Sesión</a>
            </div>
          </div>";
      }
    ?>
  </header>
</body>
</html>