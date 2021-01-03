
<!DOCTYPE html>
<html>
  <?php include("global.php"); ?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DB login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
  </head>

  <body>
    <?php
      if (isset($_POST['LOGN']) && isset($_POST['PASW'])) {
        session_start();
        $_SESSION['LOGIN'] = $_POST['LOGN'];
        $_SESSION['PASS'] = $_POST['PASW'];
        header("Location: ".$path);
        die();
      }
    ?>

    <h1> Set Oracle DB credentials </h1>

    <form class="frame" action=<?= $path.'databaseLogin.php'?> method="post">
      <label for="username">DB User</label><br>
      <input id="username" type="text" name="LOGN"><br>
      <label for="password">DB Password</label><br>
      <input id="password" type="password" name="PASW"><br>
      <input type="submit" value="Login">
    </form>

  </body>
</html>