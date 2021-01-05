<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <title>Dataset</title>

    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."gridstyle.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."popup.css"?>>
    <script src=<?= $path."popup.js"?> defer></script>

  </head>
  <body>
    <!-- Login panel -->
    <?php include("loginPanel.php");?>

    <!-- Header -->
    <div class="frame">
      <?php

        $stmt = oci_parse($conn, "SELECT * FROM DATASET WHERE dataset_id = :id");
        oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        $res = oci_fetch_array($stmt, OCI_BOTH);

        $stmt = oci_parse($conn, "SELECT count(*) FROM DATASETBELONGING WHERE dataset_id = :id");
        oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        $num_images = oci_fetch_array($stmt, OCI_BOTH)[0];
        $num_pages = ceil($num_images / $num_per_page);

        $page = 0;
        if (isset($_GET['page'])) {
          $page = $_GET['page'];
        }

        $from = $page * $num_per_page + 1;
        $to = ($page + 1) * $num_per_page;

      ?>
      <p class="datasetDate"><?=$res['DATE_CREATED']?></p>
      <p class="datasetName"><?=$res['NAME']?></p>
      <p class="datasetDesc"><?=$res['DESCRIPTION']?></p>

    </div>

    <!-- Navigate subpages, pagination -->
    <div class="frame">
      <?php
        echo "<p>";
        echo "Displaying ".min($num_per_page, $num_images)." images out of ".$num_images.". ";
        echo "This is page ".($page + 1)." out of ".$num_pages.". (";
        for ($x = 0; $x < $num_pages; $x++) {
          echo "<a href='".$path."dataset.php?id=".$_GET['id']."&page=".$x."'>".($x + 1)."</a> ";
        }
        echo ")</p>";
      ?>
    </div>

    <!-- list of images -->
    <div class="frame">
      <div id="container">
        <?php
          $stmt = oci_parse($conn, "
            WITH imgs AS (
              SELECT content, image_id, RANK() OVER(ORDER BY image_id DESC) AS my_rank
              FROM (DATASETBELONGING NATURAL JOIN IMAGE) 
              WHERE dataset_id = :id
            ) SELECT content, image_id FROM imgs WHERE (my_rank <= :vto and my_rank >= :vfrom)");

          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_bind_by_name($stmt, ':vto', $to, -1);
          oci_bind_by_name($stmt, ':vfrom', $from, -1);

          $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

          if ($result != true) {
              echo("Input data has failed");
              $e = oci_error($stmt);
              echo "Error: " . $e['message'];
          }

          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id = $row['IMAGE_ID'];
            $src = $row['CONTENT'];
    
            echo "<a title='Image id: ".$id."' href='".$path."image.php?id=".$id."'>";
            //echo "<a href=\"".$path."image.php?id=".$id."\">".$id."</a>";
            //echo "<p>".$row['MY_RANK']."</p>";
            echo "<img src='".$src."' alt='Image loading failure'>";
            echo "</a>";
          }
        ?>

        <a class="frameAdd user" id="openPopup">
          <div class="frameInsideAdd">
            <div class="horizontal"></div>
            <div class="vertical"></div>
          </div>
        </a>
      </div>
    </div>

    <!-- Download dataset -->
    <div class="frame">
      <h2> Download data from this dataset </h2>
      <ul>
        <li><a href=<?= $path."download.php?id=".$_GET['id']."&type=DS"?>> Download urls of images (CSV) <a></li>
        <li><a href=<?= $path."download.php?id=".$_GET['id']."&type=AR"?>> Download boxes (CSV) <a></li>
        <li><a href=<?= $path."download.php?id=".$_GET['id']."&type=CL"?>> Download classifications (CSV) <a></li>
      </ul>
    </div>

    <!-- Navigation -->
    <?php include("navigation.php");?>

    <!-- Add image hidden popup -->
    <div class="message">
      <h1>Link Image to Dataset</h1>
      <form action=<?= $path.'actions.php'?> method="post">
        <input type="text" id="imageID" name="IMGID" placeholder="Image ID">
        <input type="hidden" name="ACTION" value="LINKIMG">
        <input type="hidden" name="DSID" value=<?=$_GET['id']?>>
        <input type="submit" value="Link image">
        <a id="closePopup">close</a>
      </form>
    </div>

  </body>
</html>