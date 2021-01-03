<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dataset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."gridstyle.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."popup.css"?>>
    <script src=<?= $path."popup.js"?> defer></script>
    
  </head>
  <body>
    <?php include("loginPanel.php");?>

    <div class="frame">
      <?php
        $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
        if (!$conn) echo "OCI conncection failed.\n";


        $stmt = oci_parse($conn, "SELECT * FROM DATASET WHERE dataset_id = :id");
        oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        $res = oci_fetch_array($stmt, OCI_BOTH);
      ?>
      <p class="datasetDate"><?=$res['DATE_CREATED']?></p>
      <p class="datasetName"><?=$res['NAME']?></p>
      <p class="datasetDesc"><?=$res['DESCRIPTION']?></p>

    </div>

    <div class="frame">
      <div id="container">
        <?php
          $img_dataset_query = "SELECT * FROM (DATASETBELONGING NATURAL JOIN IMAGE) WHERE dataset_id = :id";

          $stmt = oci_parse($conn, $img_dataset_query);
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);
          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id = $row['IMAGE_ID'];
            $src = $row['CONTENT'];
    
            echo "<a title='Image id: ".$id."' href='".$path."image.php?id=".$id."'>";
            //echo "<a href=\"".$path."image.php?id=".$id."\">".$id."</a>";
            //echo "<p>".$id."</p>";
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

    <nav class="frame">
      <a href=<?= $path?>>Back</a>
    </nav>

    <div class="message">
      <h1>Link Image to Dataset</h1>
      <form action=<?= $path.'actions.php'?> method="post">
        <input type="text" id="imageID" name="IMGID" placeholder="Image ID">
        <input type="hidden" name="ACTION" value="LINKIMG">
        <input type="hidden" name="DSID" value=<?=$_GET['id']?>>
        <!--<img id="preview"> </img>-->
        <input type="submit" value="Link image">
        <a id="closePopup">close</a>
      </form>
    </div>

  </body>
</html>