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
  <title>Recordatorios</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/custom_checkbox.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
</head>
<body>

<?php
  $query = "SELECT book_issue_log.issue_id, member.name, member.email, book.title, book_issue_log.due_date, book_issue_log.last_reminded 
    FROM book_issue_log, book, member WHERE book.id = book_issue_log.book_id AND member.username = book_issue_log.member ORDER BY due_date ASC;";
  $query = $connect->prepare($query);
  $query->execute();
  $result = $query->get_result();
  $rows = mysqli_num_rows($result);

  if($rows > 0) {
    echo "<form class='cd-form table' method='POST' action='#'>";
    echo "<legend>Solicitudes de Libros Pendientes</legend>";
    echo "<div class='error-message' id='error-message'><p id='error'></p></div>";
    echo "<table width='100%' cellpadding=10 cellspacing=10>
        <tr>
          <th></th>
          <th>Usuario<hr></th>
          <th>Email del usuario<hr></th>
          <th>Libro<hr></th>
          <th>Fecha de entrega<hr></th>
          <th>Ultima notificación<hr></th>
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
      echo "<td>".$row[2]."</td>";
      echo "<td>".$row[3]."</td>";
      echo "<td>".$row[4]."</td>";
      if($row[5] == null) {
        echo "<td>No notificado</td>";
      } else {
        echo "<td>".$row[5]."</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
    echo "<br /><div style='float: right;'>";
    echo "<input type='submit' value='Actualizar notificados' name='reminded'/>";
    echo "</div>";
    echo "</form>";
  } else {
    echo "<h2 align='center'>No hay recordatorios pendientes</h2>";
  }

  if(isset($_POST['reminded'])) {
    $requests = 0;
    for($i=0; $i<$rows; $i++) {
      if(isset($_POST['cb_'.$i])) {
        $query = $connect->prepare("UPDATE book_issue_log SET last_reminded = NOW() WHERE issue_id = ?;");
        $query->bind_param("d", $_POST['cb_'.$i]);
        if(!$query->execute()) {
          die(error_without_field("ERROR: No se pudo actualizar"));
        }
        $requests++;
      }
    }
    if($requests > 0) {
      echo success("Solicitud actualiza exitósamente ".$requests." solicitud");
      header("Refresh:0");
    } else {
      echo error_without_field("Ninguna solicitud seleccionada");
    }
  }
?>
</body>
</html>