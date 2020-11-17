<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "verify_librarian.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <title>Agregar Libro</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css" />
  <link rel="stylesheet" type="text/css" href="../css/form.css" />
  <link rel="stylesheet" type="text/css" href="../css/custom_icons.css">
</head>
<body>
  <form class="cd-form" method="POST" action="#">
    <legend>Ingresa toda la información del libro</legend>
      <div class="error-message" id="error-message">
        <p id="error"></p>
      </div>
      <div class="icon">
        <input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN" required />
      </div>
      <div class="icon">
        <input class="b-title" type="text" name="b_title" placeholder="Título" required />
      </div>
      <div class="icon">
        <input class="b-author" type="text" name="b_author" placeholder="Autor" required />
      </div>
      <div>
      <h4>Categoría</h4>
        <p class="cd-select icon">
          <select class="b-category" name="b_category">
            <option value="1">Ficción</option>
            <option value="2">No Ficción</option>
            <option value="3">Educación</option>
          </select>
        </p>
      </div>
      <br />
      <input class="b-isbn" type="submit" name="b_add" value="Agregar" />
  </form>
<body>

<?php
  if(isset($_POST['b_add'])) {
    $author = $_POST['b_author'];
    $query = $connect->prepare("SELECT id FROM author WHERE name = ?;");
    $query->bind_param("s", $author);
    $query->execute();
    $result = $query->get_result();
    if(mysqli_num_rows($result) != 0) {
      $author = mysqli_fetch_array($result)[0];
    } else {
      $query = $connect->prepare("INSERT INTO author (name) VALUES (?);");
      $query->bind_param("s", $author);
      $query->execute();
      $author = $query->insert_id;
    }

    $query = $connect->prepare("SELECT isbn FROM book WHERE isbn = ?;");
    $query->bind_param("s", $_POST['b_isbn']);
    $query->execute();
    if(mysqli_num_rows($query->get_result()) != 0) {
      echo error_with_field("Ya existe un libro con ese ISBN", "b_isbn");
    } else {
      $query = $connect->prepare("INSERT INTO book (isbn, title, author, category) VALUES (?, ?, ?, ?);");
      $query->bind_param("ssss", $_POST['b_isbn'], $_POST['b_title'], $author, $_POST['b_category']);

      if(!$query->execute()) {
        die(error_without_field("ERROR: No se pudo agregar el libro"));
      }
      echo success("Libro Agregado Satisfactoriamente");
    }
  }
?>
</html>