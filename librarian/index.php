<?php
  require "../db_connect.php";
  require "../message_display.php";
  require "../verify_logged_out.php";
  require "../header.php";
  require "../footer.php";
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingreso Bibliotecario</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css">
  <link rel="stylesheet" type="text/css" href="../css/form.css">
  <link rel="stylesheet" type="text/css" href="../css/custom_icons.css">
</head>
<body>
  <form class="cd-form" method="POST" action="#">
  <legend>Ingreso Bibliotecario</legend>
    <div class="error-message" id="error-message">
      <p id="error"></p>
    </div>
    <div class="icon">
      <input class="l-user" type="text" name="l_user" placeholder="Usuario" required />
    </div>
    <div class="icon">
      <input class="l-pass" type="password" name="l_pass" placeholder="Contraseña" required />
    </div>
    <input type="submit" value="Ingresar" name="l_login"/>
  </form>
</body>

<?php
  if(isset($_POST['l_login'])) {
    $query = $connect->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
    $query->bind_param("ss", $_POST['l_user'], sha1($_POST['l_pass']));
    $query->execute();
    if(mysqli_num_rows($query->get_result()) != 1) {
      echo error_without_field("Combinacion de usuario y contraseña invalida");
    } else {
      $_SESSION['type'] = "librarian";
      $_SESSION['id'] = mysqli_fetch_array($result)[0];
      $_SESSION['username'] = $_POST['l_user'];
      header('Location: home.php');
    }
  }
?>
</html>