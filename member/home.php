<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "verify_member.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
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
      echo "<form class='cd-form table content-wrap' method='POST' action='#'>";
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
          <th>Disponibilidad<hr></th>
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
        if($row[5] == true) {
          echo "<td>Disponible</td>";
        } else {
          echo "<td>No disponible</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
      echo "<br /><input type='submit' name='m_request' value='Solicitar libro' />";
      echo "</form>";
    }

    if(isset($_POST['m_request'])) {
      if(empty($_POST['rd_book'])) {
        echo error_without_field("Seleccione un libro para emitir");
      } else {
        $query = $connect->prepare("SELECT availability FROM book WHERE id = ?;");
        $query->bind_param("s", $_POST['rd_book']);
        $query->execute();
        $availability = mysqli_fetch_array($query->get_result())[0];
        if($availability == false) {
          echo error_without_field("No hay copias disponibles del libro seleccionado.");
        } else {
          $query = $connect->prepare("SELECT request_id FROM pending_book_requests WHERE member = ?;");
          $query->bind_param("s", $_SESSION['username']);
          $query->execute();
          if(mysqli_num_rows($query->get_result()) == 1) {
            echo error_without_field("Solo puedes solicitar un libro a la vez");
          } else {
            $query = $connect->prepare("SELECT book_id FROM book_issue_log WHERE member = ?;");
            $query->bind_param("s", $_SESSION['username']);
            $query->execute();
            $result = $query->get_result();
            if(mysqli_num_rows($result) >= 3) {
              echo error_without_field("No puedes emitir más de 3 libros a la vez");
            } else {
              $rows = mysqli_num_rows($result);
              for($i=0; $i<$rows; $i++)
                if(strcmp(mysqli_fetch_array($result)[0], $_POST['rd_book']) == 0)
                  break;
              if($i < $rows) {
                echo error_without_field("Ya ha solicitado una copia de este libro.");
              } else {
                $query = $connect->prepare("INSERT INTO pending_book_requests(member, book_id) VALUES(?, ?);");
                $query->bind_param("ss", $_SESSION['username'], $_POST['rd_book']);
                if($query->execute())
                  echo success("Libro solicitado con éxito.");
                else
                  echo error_without_field("Ha surgido un error, intentelo mas tarde");
              }
            }
          }
        }
      }
    }
  ?>
</body>
</html>