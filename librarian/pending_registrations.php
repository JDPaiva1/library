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
  <title>Solicitudes de Registro Pendientes</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/custom_checkbox.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
</head>
<body>
  <?php
    $query = $connect->prepare("SELECT username, name, email FROM pending_registrations");
    $query->execute();
    $result = $query->get_result();
    $rows = mysqli_num_rows($result);
    if($rows == 0) {
      echo "<h2 align='center'>Sin solicitudes de registro pendientes</h2>";
    } else {
      echo "<form class='cd-form table' method='POST' action='#'>";
      echo "<legend>Solicitudes de Registro Pendientes</legend>";
      echo "<div class='error-message' id='error-message'>
          <p id='error'></p>
        </div>";
      echo "<table width='100%' cellpadding=10 cellspacing=10>
          <tr>
            <th></th>
            <th>Usuario<hr></th>
            <th>Nombre<hr></th>
            <th>Correo<hr></th>
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
        $j;
        for($j=0; $j<3; $j++) {
          echo "<td>".$row[$j]."</td>";
        }
        echo "</tr>";
      }
      echo "</table><br />";
      echo "<div style='float: right;'>";
      echo "<input type='submit' value='Eliminar Selección' name='l_delete' />&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<input type='submit' value='Confirmar Selección' name='l_confirm' />";
      echo "</div>";
      echo "</form>";
    }

    if(isset($_POST['l_confirm'])) {
      $members = 0;
      for($i=0; $i<$rows; $i++) {
        if(isset($_POST['cb_'.$i])) {
          $username =  $_POST['cb_'.$i];
          $query = $connect->prepare("SELECT * FROM pending_registrations WHERE username = ?;");
          $query->bind_param("s", $username);
          $query->execute();
          $row = mysqli_fetch_array($query->get_result());
          
          $query = $connect->prepare("INSERT INTO member (username, password, name, email) VALUES (?, ?, ?, ?);");
          $query->bind_param("ssss", $username, $row[1], $row[2], $row[3]);
          if(!$query->execute())
            die(error_without_field("ERROR: No se pudieron insertar valores"));
          $members++;
        }
      }
      if($members > 0) {
        echo success("Exitosamente agregado ".$members." miembro");
      } else {
        echo error_without_field("Ningún registro seleccionado");
      }
    }

    if(isset($_POST['l_delete'])) {
      $requests = 0;
      for($i=0; $i<$rows; $i++) {
        if(isset($_POST['cb_'.$i])) {
          $username =  $_POST['cb_'.$i];
          $query = $connect->prepare("SELECT email FROM pending_registrations WHERE username = ?;");
          $query->bind_param("s", $username);
          $query->execute();
          $email = mysqli_fetch_array($query->get_result())[0];
          
          $query = $connect->prepare("DELETE FROM pending_registrations WHERE username = ?;");
          $query->bind_param("s", $username);
          if(!$query->execute())
            die(error_without_field("ERROR: No se pudieron eliminar los valores"));
          $requests++;
        }
      }
      if($requests > 0)
        echo success("Eliminado Exitosamente ".$requests." Registro");
      else
        echo error_without_field("No se seleccionó ningún registro");
    }
  ?>
</body>
</html>