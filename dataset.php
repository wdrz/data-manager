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
    <!---<script src="front.js" defer></script>-->

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

        <a id="frameAdd">
          <div class="frameInsideAdd">
            <div class="horizontal"></div>
            <div class="vertical"></div>
          </div>
        </a>
      </div>
    </div>

    <!--<div class="frame">



    
      <table class="priceTable">
        <caption>Images in dataset</caption>
        <tr>
          <th>Price</th>
          <th>User</th>
          <th>Id</th>
        </tr>
        <?php/*
          $stmt = oci_parse($conn, $img_dataset_query);
          oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);
          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id = $row['IMAGE_ID'];
            //$desc = $row['DESCRIPTION'];
            //$date = $row['DATE_CREATED'];
    
            echo "<tr>";
            //echo "<td>".."</td>";
            //echo "<td>".."</td>";
            //echo "<td>".."</td>";
            echo "<td><a href=\"".$path."image.php?id=".$id."\">".$id."</a></td>";
            echo "</tr>";
          }*/

        ?>
      </table>
    </div>-->
    <nav class="frame">
      <a href=<?= $path?>>Back</a>
    </nav>

    <div class="message">
      <h1>Add Image</h1>
      <form action=<?= $path.'actions.php'?> method="post">
        <input type="text" id="imageURL" name="URL">
        <input type="hidden" name="ACTION" value="ADDIMG">
        <img id="preview"> </img>
        <input type="submit" value="Add image">

      <a id="closePopup">close</a>
    </div>

    <script>
      let komunikat = document.querySelector(".message");

      function message() {
          /* Adds shadow */
          const shadow = document.createElement("div");
          shadow.setAttribute("class", "shadow");
          document.body.appendChild(shadow);

          /* Displays message */
          //komunikat.querySelector("p").innerHTML = text;
          komunikat.style.display="inline";
      }

      /* Hides message & shadow */
      function closeP() {
          komunikat.style.display="none";
          const shadow = document.querySelector(".shadow");
          shadow.remove();
      }
      document.getElementById("closePopup").addEventListener("click", (e) => {
        closeP();
      });

      document.getElementById("frameAdd").addEventListener("click", (e) => {
        message();
      });

      const tfURL = document.getElementById("imageURL");

      tfURL.addEventListener('focusout', (e) => {
        document.getElementById("preview").src = tfURL.value;
      });

    </script>

  </body>
</html>