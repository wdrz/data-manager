<!DOCTYPE html>
<html>
  <?php include("global.php"); ?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
  </head>
  <body>
    <?php
      session_start();

      if ($_POST['ACTION'] == 'LOGOUT') {
        $_SESSION['USER'] = null;
        $_SESSION['ROLE'] = null;
        $_SESSION['UID'] = null;
        header('Location: '.$_SERVER['HTTP_REFERER']);
        die();
      }

      $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
      if (!$conn) echo "OCI conncection failed.\n";

      if (isset($_POST['LOGN']) && isset($_POST['PASW'])) {
        $stmt = oci_parse($conn, "SELECT * FROM Users WHERE username = :us AND password_hash = :pas");
        oci_bind_by_name($stmt, ':us', $_POST['LOGN'], -1);
        oci_bind_by_name($stmt, ':pas', $_POST['PASW'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
          $_SESSION['USER'] = $_POST['LOGN'];
          $_SESSION['ROLE'] = $row['ROLE'];
          $_SESSION['UID'] = $row['USER_ID'];
          header("Location: ".$path);
          die();
        } else {
          echo "<p class='redtext'>WRONG CREDENTIALS</p>";
        }

      }
    ?>
  
    <form class="frame" method="post">
      <label for="username">User</label><br>
      <input id="username" type="text" name="LOGN"><br>
      <label for="password">Password</label><br>
      <input id="password" type="password" name="PASW"><br>
      <input type="submit" value="Login">
    </form>
  </body>
</html>