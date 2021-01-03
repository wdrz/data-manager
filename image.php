<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Image</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <script src="image.js" defer></script>

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


        <!--<tr>
          <td>50</td>
          <td>50</td>
          <td>100</td>
          <td>100</td>
        </tr>
        <tr>
          <td>70</td>
          <td>100</td>
          <td>100</td>
          <td>40</td>
        </tr><tr>
          <td>150</td>
          <td>200</td>
          <td>50</td>
          <td>50</td>
        </tr>-->
      </table>

    </div>

    <div class="frame">

    <table id="sth" class="priceTable">
        <caption>Boxes</caption>
        <tr>
          <th>a</th>
          <th>b</th>
          <th>c</th>
          <th>d</th>
        </tr>
        <tr>
          <td><input type="text" value="30"/></td>
          <td><input type="text" value="70"/></td>
          <td><input type="text" value="15"/></td>
          <td><input type="text" value="100"/></td>
        </tr>
        <tr>
          <td><input type="button" value="Dodaj"/></td>
        </tr>
      </table>
    </div>
    <nav class="frame">
      <a href=<?= $path?>>Back</a>
    </nav>
  </body>
</html>