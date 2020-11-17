<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "verify_librarian.php";
  require "../header.php";
  require "../footer.php";

  $rows = ['','','','',''];
  if(isset($_POST['go_edit']) && !empty($_POST['rd_book'])) {
    $query = "SELECT book.id, book.isbn, book.title, author.name, category.id FROM book, author, category WHERE author.id = book.author AND category.id = book.category AND book.id = ?;";
    $query = $connect->prepare($query);
    $query->bind_param("s", $_POST['rd_book']);
    $query->execute();
    $result = $query->get_result();
    $rows = mysqli_fetch_array($result);
  }
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      <input style="display:none" id="b_id" type="number" name="b_id" value="<?php echo $rows[0]; ?>"/>
      <div class="icon">
        <input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN" value="<?php echo $rows[1]; ?>"/>
      </div>
      <div class="icon">
        <input class="b-title" type="text" name="b_title" placeholder="Título" value="<?php echo $rows[2]; ?>"/>
      </div>
      <div class="icon">
        <input class="b-author" type="text" name="b_author" placeholder="Autor" value="<?php echo $rows[3]; ?>"/>
      </div>
      <div>
      <h4>Categoría</h4>
        <p class="cd-select icon">
          <select class="b-category" name="b_category">
            <option value="1" <?php if($rows[4] == 1) echo "selected";?>>Ficción</option>
            <option value="2" <?php if($rows[4] == 2) echo "selected";?>>No Ficción</option>
            <option value="3" <?php if($rows[4] == 3) echo "selected";?>>Educación</option>
          </select>
        </p>
      </div>
      <br />
      <input class="b-isbn" type="submit" name="b_edit" value="Editar" />
      <input class="b-isbn" type="submit" name="b_back" value="Volver" />
  </form>
</body>
<?php
  if(isset($_POST['b_edit'])) {
    $query = $connect->prepare("SELECT isbn FROM book WHERE isbn = ?;");
    $query->bind_param("s", $_POST['b_isbn']);
    $query->execute();
    if(mysqli_num_rows($query->get_result()) != 1)
      echo error_with_field("ISBN inválido", "b_isbn");
    else {
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

      $query = $connect->prepare("UPDATE book SET isbn = ?, title = ?, author = ?, category = ? WHERE id = ?;");
      $query->bind_param("ssddd", $_POST['b_isbn'], $_POST['b_title'], $author, $_POST['b_category'], $_POST['b_id']);
      if(!$query->execute()) {
        die(error_without_field("ERROR: No se pudo actualizar el libro"));
      } else {
        echo success("Libro actualizado con éxito");
      }
    }
  } else if(empty($_POST['rd_book']) || isset($_POST['b_back'])) {
    header("Location: ../librarian/list_books.php");
  }
?>
</html>