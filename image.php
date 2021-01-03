<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Image</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."popup.css"?>>
    <script src=<?= $path."image.js"?> defer></script>
    <script src=<?= $path."popup.js"?> defer></script>

  </head>
  <body>
    <?php include("loginPanel.php");?>

    <div class="frame">
      <?php
        $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
        if (!$conn) echo "OCI conncection failed.\n";

        $stmt = oci_parse($conn, "SELECT * FROM Image WHERE image_id = :id");
        oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        $res = oci_fetch_array($stmt, OCI_BOTH);
      ?>
      <script>
        const imgSrc = "<?=$res['CONTENT']?>";
      </script>


      <p class="price">user: ___ </p>
      <?php
        echo "<p class=\"datasetName\"> Image ".$_GET['id']."</p>";
      ?>
    </div>

    <div class="frame">
      <canvas id="canv1"> </canvas>

      <table id="imageBoxes" class="priceTable">
        <caption>Boxes</caption>
        <tr>
          <th>id</th>
          <th>x</th>
          <th>y</th>
          <th>width</th>
          <th>height</th>
        </tr>
        <?php
          $stmt = oci_parse($conn, "SELECT * FROM IMAGEAREA WHERE image_id = :id");
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);
          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id = $row['AREA_ID'];
    
            echo "<tr>";
            echo "<td><a href=\"".$path."imagearea.php?id=".$id."\">".$id."</a></td>";
            echo "<td>".$row['X']."</td>";
            echo "<td>".$row['Y']."</td>";
            echo "<td>".$row['WIDTH']."</td>";
            echo "<td>".$row['HEIGHT']."</td>";
            echo "</tr>";
          }

        ?>
      </table>

    </div>

    <div class="frame">
      <h2> Linked datasets </h2>
      <ul>
        <?php
          $stmt = oci_parse($conn, "SELECT * FROM (DatasetBelonging NATURAL JOIN Dataset) WHERE image_id = :id");
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);

          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $name = $row['NAME'];
            $id   = $row['DATASET_ID'];

            echo "<li>";
            echo "<form class='smallForm' action='".$path."actions.php' method='post'>";
            echo "<input type='hidden' name='ACTION' value='DELLINK'>";
            echo "<input type='hidden' name='IMGID' value='".$_GET['id']."'>";
            echo "<input type='hidden' name='DSID' value='".$id."'>";
            echo "<input class='deleteButton' type='submit' value='delete'>";
            echo "</form>";
            echo "<a href=\"".$path."dataset.php?id=".$id."\">".$name."</a>";
            echo "</li>";
          }
        ?>
      </ul>
    </div>

    <div class="frame">
      <h2> Delete Image </h2>
      <span> This operation cannot be reversed! 
        All relations with datasets and image areas will be delated as well. 
      </span>
      <form class='smallForm' action=<?=$path."actions.php"?> method='post'>
        <input type='hidden' name='ACTION' value='DELIMG'>
        <input type='hidden' name='IMGID' value=<?=$_GET['id']?>>
        <input class='deleteButton' type='submit' value='Delete'>
      </form>
    </div>

    <div class="frame">
      <h2>Add box</h2>
      <form action=<?= $path.'actions.php'?> method="post">
        <input type="hidden" name="ACTION" value="ADDAREA">
        <input type="hidden" name="IMGID" value=<?=$_GET['id']?>>

        <label for="imgx">X</label><br>
        <input id="imgx" type="number" name="IMGX"><br>

        <label for="imgy">Y</label><br>
        <input id="imgy" type="number" name="IMGY"><br>
        
        <label for="imgw">Width</label><br>
        <input id="imgw" type="number" name="IMGW"><br>

        <label for="imgh">Height</label><br>
        <input id="imgh" type="number" name="IMGH"><br>

        <input type="submit" value="Add box">
      </form>
      <a id="openPopup"> Experimental add window </a>
    </div>

    <nav class="frame">
      <a href=<?= $path?>>Back</a>
    </nav>

    <div class="message wide">
      <canvas id="imgadd"> </canvas>

      <form action=<?= $path.'actions.php'?> method="post">
        <h1>Add box</h1>
        <input type="hidden" name="ACTION" value="ADDAREA">
        <input type="hidden" name="IMGID" value=<?=$_GET['id']?>>

        <input id="imgx" type="text" name="IMGX" placeholder="X">
        <input id="imgy" type="text" name="IMGY" placeholder="Y">
        <input id="imgw" type="text" name="IMGW" placeholder="dX">
        <input id="imgh" type="text" name="IMGH" placeholder="dY"><br>
        <input type="submit" value="Add box">
        <a id="closePopup">close</a>
      </form>
    </div>
  </body>
</html>