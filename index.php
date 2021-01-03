<!DOCTYPE html>
<html>

  <?php include("global.php"); ?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Main Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <!---<script src="front.js" defer></script>-->

  </head>
  <body>

    <?php include("loginPanel.php");?>

    <header class="frame">
      <div class="box">
        <h1>Dataset manager</h1>
        <a href=<?= $path."labels.php"?>>See tree of labels</a> &#8231;
        <a href=<?= $path."labels.php"?>>New dataset</a>
      </div>
    </header>
    <ul>
      <?php
        $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
        if (!$conn) {
          echo "oci_connect failed\n";
          $e = oci_error();
          echo $e['message'];
        } else {
          
        }

        $stmt = oci_parse($conn, "SELECT * FROM DATASET");
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        // OCI_BOTH sprawia, tablica jest zarowno asocjacyjna, jak i zwykla
        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
          $name = $row['NAME'];
          $desc = $row['DESCRIPTION'];
          $date = $row['DATE_CREATED'];
          $id   = $row['DATASET_ID'];
  
          echo "<li class=\"frame\">";
          echo "<p class=\"datasetDate\">".$date."</p>";
          echo "<a class=\"datasetName\" href=\"".$path."dataset.php?id=".$id."\">".$name."</a>";
          echo "<p class=\"datasetDesc\">".$desc."</p>";
          echo "</li>";
        }
        // Jesli modyfikujemy, to trzeba zrobic COMMIT:
        oci_commit($conn);
  


      ?>
    </ul>

  </body>
</html>