<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "verify_member.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <title>Mis Libros</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/custom_checkbox.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
</head>
<body>
  <?php
    $query = $connect->prepare("SELECT book_id FROM book_issue_log WHERE member = ?;");
    $query->bind_param("s", $_SESSION['username']);
    $query->execute();
    $result = $query->get_result();
    $rows = mysqli_num_rows($result);
    if($rows == 0) {
      echo "<h2 align='center'>No hay libros solicitados actualmente</h2>";
    } else {
      echo "<form class='cd-form table' method='POST' action='#'>";
      echo "<legend>Mis Libros</legend>";
      echo "<div class='success-message' id='success-message'>
          <p id='success'></p>
        </div>";
      echo "<div class='error-message' id='error-message'>
          <p id='error'></p>
        </div>";
      echo"<table width='100%' cellpadding='10' cellspacing='10'>
          <tr>
            <th></th>
            <th>ISBN<hr></th>
            <th>Título<hr></th>
            <th>Autor<hr></th>
            <th>Categoría<hr></th>
            <th>Fecha de vencimiento<hr></th>
          </tr>";
      for($i=0; $i<$rows; $i++) {
        $id = mysqli_fetch_array($result)[0];
        if($id != NULL) {
          $query = $connect->prepare("SELECT * FROM book, author, category WHERE author.id = book.author AND category.id = book.category AND book.id = ?;");
          $query->bind_param("s", $id);
          $query->execute();
          $innerRow = mysqli_fetch_array($query->get_result());
          echo "<tr>
              <td>
                <label class='control control--checkbox'>
                  <input type='checkbox' name='cb_book".$i."' value='".$id."'>
                  <div class='control__indicator'></div>
                </label>
              </td>";
          echo "<td>".$innerRow[1]."</td>";
          echo "<td>".$innerRow[2]."</td>";
          echo "<td>".$innerRow[7]."</td>";
          echo "<td>".$innerRow[9]."</td>";
          $query = $connect->prepare("SELECT due_date FROM book_issue_log WHERE member = ? AND book_id = ?;");
          $query->bind_param("ss", $_SESSION['username'], $id);
          $query->execute();
          echo "<td>".mysqli_fetch_array($query->get_result())[0]."</td>";
          echo "</tr>";
        }
      }
      echo "</table><br />";
      echo "<input type='submit' name='b_return' value='Devolver los libros seleccionados' />";
      echo "</form>";
    }
    if(isset($_POST['b_return'])) {
      $books = 0;
      for($i=0; $i<$rows; $i++) {
        if(isset($_POST['cb_book'.$i])) {
          $query = $connect->prepare("SELECT due_date FROM book_issue_log WHERE member = ? AND book_id = ?;");
          $query->bind_param("ss", $_SESSION['username'], $_POST['cb_book'.$i]);
          $query->execute();
          $due_date = mysqli_fetch_array($query->get_result())[0];
          
          $query = $connect->prepare("SELECT DATEDIFF(CURRENT_DATE, ?);");
          $query->bind_param("s", $due_date);
          $query->execute();
          $days = (int)mysqli_fetch_array($query->get_result())[0];
          
          $query = $connect->prepare("DELETE FROM book_issue_log WHERE member = ? AND book_id = ?;");
          $query->bind_param("ss", $_SESSION['username'], $_POST['cb_book'.$i]);
          if(!$query->execute())
            die(error_without_field("ERROR: No pude devolver los libros"));
          $books++;
        }
      }
      if($books > 0) {
        echo '<script>
            document.getElementById("success").innerHTML = "Proceso de devolución exitoso '.$books.' libros";
            document.getElementById("success-message").style.display = "block";
          </script>';
      } else {
        echo error_without_field("Por favor seleccione el libro a devolver");
      }
    }
  ?>
</body>
</html>