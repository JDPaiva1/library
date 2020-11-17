<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <title>Registro</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
  <link rel="stylesheet" type="text/css" href="../css/custom_icons.css">
</head>
<body>
  <form class="cd-form" method="POST" action="#">
    <legend>Ingresa tu información</legend>
      <div class="error-message" id="error-message">
        <p id="error"></p>
      </div>
      <div class="icon">
        <input class="m-user" type="text" name="m_user" id="m_user" placeholder="Nombre de usuario" required />
      </div>
      <div class="icon">
        <input class="m-pass" type="password" name="m_pass" placeholder="Contraseña" required />
      </div>
      <div class="icon">
        <input class="m-name" type="text" name="m_name" placeholder="Nombre completo" required />
      </div>
      <div class="icon">
        <input class="m-email" type="email" name="m_email" id="m_email" placeholder="Correo electrónico" required />
      </div>
      <br />
      <input type="submit" name="m_register" value="Registrarse" />
  </form>
</body>
<?php
  if(isset($_POST['m_register'])) {
    $user = $_POST['m_user'];
    $pass = sha1($_POST['m_pass']);
    $name = $_POST['m_name'];
    $email = $_POST['m_email'];
    $query = $connect->prepare("(SELECT username FROM member WHERE username = ?) UNION (SELECT username FROM pending_registrations WHERE username = ?);");
    $query->bind_param("ss", $user, $user);
    $query->execute();
    if(mysqli_num_rows($query->get_result()) != 0) {
      echo error_with_field("El nombre de usuario que ingresó ya está en uso", "m_user");
    } else {
      $query = $connect->prepare("(SELECT email FROM member WHERE email = ?) UNION (SELECT email FROM pending_registrations WHERE email = ?);");
      $query->bind_param("ss", $email, $email);
      $query->execute();
      if(mysqli_num_rows($query->get_result()) != 0) {
        echo error_with_field("Ya hay una cuenta registrada con ese correo electrónico", "m_email");
      } else {
        $query = $connect->prepare("INSERT INTO pending_registrations (username, password, name, email) VALUES (?, ?, ?, ?);");
        $query->bind_param("ssss", $user, $pass, $name, $email);
        if($query->execute()) {
          echo success("Detalles registrados. Se le notificará por correo electrónico cuando se hayan verificado sus datos");
        } else {
          echo error_without_field("No se pudieron registrar los detalles. Por favor, inténtelo de nuevo más tarde");
        }
      }
    }
  }
?>
</html>