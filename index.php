<!DOCTYPE html>
<html>
  <head>
    <?php include("global.php"); ?>

    <title>Main Page</title>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>

  </head>
  <body>
    <!-- Login panel -->
    <?php include("loginPanel.php");?>

    <!-- Header -->
    <header class="frame">
      <div class="box">
        <h1>Dataset manager</h1>
        <a href=<?= $path."labels.php"?>>See tree of labels</a> &#8231;
        <a href=<?= $path."newDataset.php"?> id="newDataset">New dataset</a> &#8231;
        <a href=<?= $path."imageList.php"?>>Browse images</a>
      </div>
    </header>

    <!-- Display a frame for each dataset -->
    <ul class="specialList">
      <?php
        $stmt = oci_parse($conn, "SELECT * FROM DATASET");
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
          $name = $row['NAME'];
          $desc = $row['DESCRIPTION'];
          $date = $row['DATE_CREATED'];
          $id   = $row['DATASET_ID'];
  
          echo "
            <li class='frame'>
              <p class='datasetDate'>${date}</p>
              <a class='datasetName' href='${path}dataset.php?id=${id}'>${name}</a>
              <p class='datasetDesc'>${desc}</p>
            </li>";
        }

      ?>
    </ul>

  </body>
</html>