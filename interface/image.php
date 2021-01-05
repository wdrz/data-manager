<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <title>Image</title>

    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."popup.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."boxes.css"?>>
  
    <script src=<?= $path."image.js"?> defer></script>
    <script src=<?= $path."popup.js"?> defer></script>

  </head>
  <body>
    <!-- Login panel -->
    <?php include("loginPanel.php");?>

    <!-- Header -->
    <div class="frame">
      <?php
        $stmt = oci_parse($conn, "SELECT * FROM Image WHERE image_id = :id");
        oci_bind_by_name($stmt, ':id', $_GET['id'], -1);

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        $res = oci_fetch_array($stmt, OCI_BOTH);
      ?>
      <script>
        const imgSrc = "<?=$res['CONTENT']?>";
      </script>

      <?php
        echo "<p class=\"datasetName\"> Image ".$_GET['id']."</p>";
      ?>
    </div>

    <!-- List of boxes -->
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
          <th class='admin'> # </th>
        </tr>
        <?php

          $stmt = oci_parse($conn, "SELECT * FROM IMAGEAREA WHERE image_id = :id");
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);

          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id     = $row['AREA_ID'];
            $x      = $row['X'];
            $y      = $row['Y'];
            $width  = $row['WIDTH'];
            $height = $row['HEIGHT'];
    
            echo "
              <tr>
                <td><a href='${path}imagearea.php?id=${id}'>${id}</a></td>
                <td>${x}</td>
                <td>${y}</td>
                <td>${width}</td>
                <td>${height}</td>

                <td class='admin'>
                  <form class='smallForm' action='${path}actions.php' method='post'>
                    <input type='hidden' name='ACTION' value='DELAREA'>
                    <input type='hidden' name='AREAID' value='${id}'>
                    <input class='deleteButton' type='submit' value='delete'>
                  </form>
                </td>
              </tr>";
          }

        ?>
      </table>

      <!-- open popup -->
      <button class="admin" id="openPopup" style="width: auto;"> New box </button>

    </div>

    <!-- list of datasets -->
    <div class="frame">
      <h2> Linked datasets </h2>
      <ul>
        <?php
          $stmt = oci_parse($conn, "SELECT * FROM (DatasetBelonging NATURAL JOIN Dataset) WHERE image_id = :id");
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);

          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $name   = $row['NAME'];
            $id     = $row['DATASET_ID'];
            $img_id = $_GET['id'];

            echo "
              <li>
                <form class='smallForm admin' action='${path}actions.php' method='post'>
                  <input type='hidden' name='ACTION' value='DELLINK'>
                  <input type='hidden' name='IMGID' value='${img_id}'>
                  <input type='hidden' name='DSID' value='${id}'>
                  <input class='deleteButton' type='submit' value='delete'>
                </form>
                <a href='${path}dataset.php?id=${id}'>${name}</a>
              </li>";
          }
        ?>
      </ul>
    </div>

    <!-- delete image -->
    <div class="frame admin">
      <h2> Delete Image </h2>
      <span> 
        This operation cannot be reversed! 
        All relations with datasets and image areas will be delated as well. 
      </span>
      <form class='smallForm' action=<?=$path."actions.php"?> method='post'>
        <input type='hidden' name='ACTION' value='DELIMG'>
        <input type='hidden' name='IMGID' value=<?=$_GET['id']?>>
        <input class='deleteButton' type='submit' value='Delete'>
      </form>
    </div>

    <!-- add box OLD -->
    <div class="frame user">
      <h2> Manually add a box </h2>

      <p> A better tool can be found above. </p> 

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
    </div>

    <!-- Navigation -->
    <?php include("navigation.php");?>

    <!-- add box tool, popup -->
    <div class="message wide">
      <div class="imgcnt">
        <img id="imgadd">
        <div id="selbox" class="box"></div>
        <div class="dot" id="dot1"></div>
        <div class="dot" id="dot2"></div>
      </div>

      <form action=<?= $path.'actions.php'?> method="post">
        <h1>Add box</h1>
        <p> Click on the image to select an area! </p>
        <input type="hidden" name="ACTION" value="ADDAREA">
        <input type="hidden" name="IMGID" value=<?=$_GET['id']?>>

        <input id="imgx_ex" type="text" name="IMGX" placeholder="X" readonly>
        <input id="imgy_ex" type="text" name="IMGY" placeholder="Y" readonly>
        <input id="imgw_ex" type="text" name="IMGW" placeholder="dX" readonly>
        <input id="imgh_ex" type="text" name="IMGH" placeholder="dY" readonly><br>
        <input type="submit" value="Add box">
        <a id="closePopup">close</a>
      </form>
    </div>
  </body>
</html>