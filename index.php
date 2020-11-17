<?php
  require "db_connect.php";
  require "header.php";
  require "footer.php";
  session_start();

  if(empty($_SESSION['type']));
  else if(strcmp($_SESSION['type'], "librarian") == 0)
    header("Location: librarian/home.php");
  else if(strcmp($_SESSION['type'], "member") == 0)
    header("Location: member/home.php");
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tu Biblioteca</title>
  <link rel="stylesheet" type="text/css" href="../css/global.css" />
  <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>
  <div id="allTheThings" class="content-wrap">
    <div id="member">
      <a href="member">
        <img src="img/ic_member_index.svg" height="auto"/><br />
        &nbsp;Miembro
      </a>
    </div>
    <div id="verticalLine">
      <div id="librarian">
        <a id="librarian-link" href="librarian">
          <img src="img/ic_librarian_index.svg" height="auto" /><br />
          &nbsp;&nbsp;&nbsp;Bibliotecario
        </a>
      </div>
    </div>
  </div>
</body>
</html>