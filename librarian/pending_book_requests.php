<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "verify_librarian.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <title>Pending Book Requests</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/custom_checkbox.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
</head>
<body>
  <?php
    $query = $connect->prepare("SELECT * FROM pending_book_requests, book WHERE book.id = pending_book_requests.book_id;");
    $query->execute();
    $result = $query->get_result();
    $rows = mysqli_num_rows($result);
    if($rows == 0) {
      echo "<h2 align='center'>Sin solicitudes pendientes</h2>";
    } else {
      echo "<form class='cd-form table' method='POST' action='#'>";
      echo "<legend>Solicitudes de Libros Pendientes</legend>";
      echo "<div class='error-message' id='error-message'>
          <p id='error'></p>
        </div>";
      echo "<table width='100%' cellpadding=10 cellspacing=10>
          <tr>
            <th></th>
            <th>Usuario<hr></th>
            <th>Libro<hr></th>
            <th>Fecha<hr></th>
          </tr>";
      for($i=0; $i<$rows; $i++) {
        $row = mysqli_fetch_array($result);
        echo "<tr>";
        echo "<td>
            <label class='control control--checkbox'>
              <input type='checkbox' name='cb_".$i."' value='".$row[0]."' />
              <div class='control__indicator'></div>
            </label>
          </td>";
        echo "<td>".$row[1]."</td>";
        echo "<td>".$row[6]."</td>";
        echo "<td>".$row[3]."</td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "<br /><div style='float: right;'>";
      echo "<input type='submit' value='Rechazar selección' name='l_reject' />&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<input type='submit' value='Aceptar selección' name='l_grant'/>";
      echo "</div>";
      echo "</form>";
    }

    if(isset($_POST['l_grant'])) {
      $requests = 0;
      for($i=0; $i<$rows; $i++) {
        if(isset($_POST['cb_'.$i])) {
          $request_id =  $_POST['cb_'.$i];
          $query = $connect->prepare("SELECT member, book_id time FROM pending_book_requests WHERE request_id = ?;");
          $query->bind_param("d", $request_id);
          $query->execute();
          $resultRow = mysqli_fetch_array($query->get_result());
          $member = $resultRow[0];
          $id = $resultRow[1];
          $query = $connect->prepare("INSERT INTO book_issue_log (member, book_id) VALUES (?, ?);");
          $query->bind_param("sd", $member, $id);
          if(!$query->execute()) {
            die(error_without_field("ERROR: No se pudo emitir el libro"));
          }
          $requests++;
        }
      }
      if($requests > 0) {
        echo success("Libro aceptado exitósamente ".$requests." solicitud");
      } else {
        echo error_without_field("Ninguna solicitud seleccionada");
      }
    }

    if(isset($_POST['l_reject'])) {
      $requests = 0;
      for($i=0; $i<$rows; $i++) {
        if(isset($_POST['cb_'.$i])) {
          $requests++;
          $request_id =  $_POST['cb_'.$i];
          
          $query = $connect->prepare("SELECT member, book_id FROM pending_book_requests WHERE request_id = ?;");
          $query->bind_param("d", $request_id);
          $query->execute();
          $resultRow = mysqli_fetch_array($query->get_result());
          $member = $resultRow[0];
          $id = $resultRow[1];
          $query = $connect->prepare("DELETE FROM pending_book_requests WHERE request_id = ?");
          $query->bind_param("d", $request_id);
          if(!$query->execute()) {
            die(error_without_field("ERROR: No se pudieron eliminar los valores"));
          }
        }
      }
      if($requests > 0) {
        echo success("Exitósame eliminado ".$requests." registro");
      } else {
        echo error_without_field("Ninguna solicitud seleccionada");
      }
    }
  ?>
</body>
</html>