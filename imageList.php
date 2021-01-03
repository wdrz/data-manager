<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Image List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."gridstyle.css"?>>
    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."popup.css"?>>
    <script src=<?= $path."popup.js"?> defer></script>

  </head>
  <body>
    <?php include("loginPanel.php");?>

    <div class="frame">

      <h1> Browse images </h1>

    </div>

    <div class="frame">
      <div id="container">
        <?php
          $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
          if (!$conn) echo "OCI conncection failed.\n";

          $stmt = oci_parse($conn, "SELECT * FROM IMAGE");
          oci_execute($stmt, OCI_NO_AUTO_COMMIT);

          while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
            $id = $row['IMAGE_ID'];
            $src = $row['CONTENT'];
    
            echo "<a title='Image id: ".$id."' href='".$path."image.php?id=".$id."'>";
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
      <h1>Add Image</h1>
      <form action=<?= $path.'actions.php'?> method="post">
        <input type="text" id="imageURL" name="URL" placeholder="Image URL">
        <input type="hidden" name="ACTION" value="ADDIMG">
        <input type="hidden" name="DSID" value=<?=$_GET['id']?>>
        <img id="preview"> </img>
        <input type="submit" value="Add image">
        <a id="closePopup">close</a>
      </form>

    </div>

    <script>
      const tfURL = document.getElementById("imageURL");

      tfURL.addEventListener('focusout', (e) => {
        document.getElementById("preview").src = tfURL.value;
      });

    </script>

  </body>
</html>