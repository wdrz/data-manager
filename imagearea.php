<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Image Area</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <!--<script src="image.js" defer></script>-->

  </head>
  <body>
    <?php include("loginPanel.php");?>

    <div class="frame">
      <?php
        $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
        if (!$conn) echo "OCI conncection failed.\n";

        $stmt = oci_parse($conn, "SELECT * FROM (ImageArea NATURAL JOIN Image) WHERE area_id = :id");
        oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        $res = oci_fetch_array($stmt, OCI_BOTH);
      ?>
      <script>
        const imgSrc = "<?=$res['CONTENT']?>";
      </script>


      <!--<p class="price">user: </p>-->
      <?php
        echo "<p class=\"datasetName\"> Image Area ".$_GET['id']."</p>";
      ?>
    </div>

    <div class="frame">


    <canvas id="canv1"> </canvas>

    <table id="table1" class="priceTable">
        <caption>Classifications</caption>
        <tr>
          <th>Id</th>
          <th>User</th>
          <th>Label</th>

          <th class='admin'> # </th>
        </tr>
        <?php
          $stmt = oci_parse($conn, 
            "SELECT * FROM 
            ((CLASSIFICATION JOIN USERS ON CREATED_BY = USER_ID)
              NATURAL JOIN LABEL) WHERE area_id = :id");
  
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);
          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id = $row['CLASS_ID'];
    
            echo "<tr>";
            echo "<td>".$id."</td>";
            echo "<td>".$row['USERNAME']."</td>";
            echo "<td>".$row['NAME']."</td>";

            echo "<td class='admin'><form class='smallForm' action='".$path."actions.php' method='post'>";
            echo "<input type='hidden' name='ACTION' value='DELCLASS'>";
            echo "<input type='hidden' name='CID' value='".$id."'>";
            echo "<input class='deleteButton' type='submit' value='delete'>";
            echo "</form></td>";

            echo "</tr>";
          }

        ?>

      </table>

    </div>

    <div class="frame user">
      <h2> Add classification</h2>
      <form class="smallForm" action=<?= $path.'actions.php'?> method="post">
        <input type="hidden" name="ACTION" value="ADDCLASS">
        <input type="hidden" name="AREAID" value=<?=$_GET['id']?>>

        <input id="ulabel" type="text" name="ULABEL" placeholder="Label">

        <input type="submit" value="Add">
      </form>
    </div>

    <nav class="frame">
      <a href=<?= $path?>>Back</a>
    </nav>
    <script>
      const width = 500;
      let ratio = 1;

      const img = new Image();
      img.src = imgSrc;

      const update = () => {
        console.log(img, <?= $res['X']?>, <?= $res['Y']?>, <?= $res['WIDTH']?>, <?= $res['HEIGHT']?>, 0, 0, width, c.height);
        ctx.drawImage(img, <?= $res['X']?>, <?= $res['Y']?>, <?= $res['WIDTH']?>, <?= $res['HEIGHT']?>, 0, 0, width, c.height);
      };

      const c = document.getElementById("canv1");
      const ctx = c.getContext("2d");
      c.width = width;

      img.onload = () => {
        ratio = width / <?= $res['WIDTH']?>;
        c.height = <?= $res['HEIGHT']?> * ratio;
        update();
      }

    </script>

  </body>
</html>