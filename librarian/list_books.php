<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "verify_librarian.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualización de Copias</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css" />
  <link rel="stylesheet" type="text/css" href="../css/form.css" />
  <link rel="stylesheet" type="text/css" href="../css/custom_radio_button.css">
</head>
<body>
  <?php
    $query = $connect->prepare("SELECT * FROM book, author, category WHERE author.id = book.author AND category.id = book.category ORDER BY title;");
    $query->execute();
    $result = $query->get_result();
    if(!$result)
      die("ERROR: No se pudieron recuperar los libros");
    $rows = mysqli_num_rows($result);
    if($rows == 0) {
      echo "<h2 align='center'>No books available</h2>";
    } else {
      echo "<form class='cd-form table content-wrap' method='POST' action='update_book.php'>";
      echo "<legend>Libros disponibles</legend>";
      echo "<div class='error-message' id='error-message'>
          <p id='error'></p>
        </div>";
      echo "<table width='90%' cellpadding=10 cellspacing=10>";
      echo "<tr>
          <th></th>
          <th>ISBN<hr></th>
          <th>Título<hr></th>
          <th>Autor<hr></th>
          <th>Categoría<hr></th>
        </tr>";
      for($i=0; $i<$rows; $i++) {
        $row = mysqli_fetch_array($result);
        echo "<tr>
            <td>
              <label class='control control--radio'>
                <input type='radio' name='rd_book' value=".$row[0]." />
              <div class='control__indicator'></div>
            </td>";
          echo "<td>".$row[1]."</td>";
          echo "<td>".$row[2]."</td>";
          echo "<td>".$row[7]."</td>";
          echo "<td>".$row[9]."</td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "<br /><input type='submit' name='go_edit' value='Editar libro' />";
      echo "</form>";
    }
  ?>
</body>
</html>