<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Labels</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <!---<script src="front.js" defer></script>-->

  </head>
  <body>
    <?php include("loginPanel.php");?>

    <div class="frame">

      <ul>
      <?php
        $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
        if (!$conn) echo "OCI conncection failed.\n";

        $stmt;
        if (isset($_GET['par'])) {
          $stmt = oci_parse($conn, "SELECT * FROM LABEL WHERE PARENT_ID = :par");
          oci_bind_by_name($stmt, ':par', $_GET['par'], -1);


        } else {
          $stmt = oci_parse($conn, "SELECT * FROM LABEL WHERE PARENT_ID IS NULL");

        }

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
          $name = $row['NAME'];
          $id   = $row['LABEL_ID'];
  
          echo "<li>";
          echo "<a href=\"".$path."labels.php?par=".$id."\">".$name."</a>";
          echo "</li>";
        }
/*
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
          $name = $row['NAME'];
          $id   = $row['LABEL_ID'];
  
          echo "<li>";
          echo "<a href=\"".$path."labels.php?par=".$id."\">".$name."</a>";
          echo "</li>";
        }*/
      ?>
      </ul>

    </div>
    <nav class="frame">
      <a href=<?= $path?>>Back</a>
    </nav>
  </body>
</html>