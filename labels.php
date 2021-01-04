<!DOCTYPE html>
<html>
  <head>

    <?php include("global.php"); ?>

    <title>Labels</title>

    <link rel="stylesheet" type="text/css" media="screen" href=<?= $path."styles.css"?>>

  </head>
  <body>
    <!-- Login panel -->
    <?php include("loginPanel.php");?>

    <!-- A frame which displays labels whose parent is equal to $_GET['par'] -->
    <div class="frame">
      <h2> Labels </h2>
      <ul>
      <?php
        $stmt = null;
        if (isset($_GET['par'])) {
          $stmt = oci_parse($conn, "SELECT * FROM LABEL WHERE PARENT_ID = :par");
          oci_bind_by_name($stmt, ':par', $_GET['par'], -1);

        } else {
          $stmt = oci_parse($conn, "SELECT * FROM LABEL WHERE PARENT_ID IS NULL");

        }

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
          $name = $row['NAME'];
          $id   = $row['LABEL_ID'];
  
          echo "
            <li>
              <form class='smallForm admin' action='${path}actions.php' method='post'>
                <input type='hidden' name='ACTION' value='DELLABEL'>
                <input type='hidden' name='LID' value='${id}'>
                <input class='deleteButton' type='submit' value='delete'>
              </form>
              <a href='${path}labels.php?par=${id}'>${name}</a>
            </li>";
        }
      ?>
      </ul>

    </div>

    <!-- A form to add a new label -->
    <div class="frame user">
      <h2> Add label</h2>
      <form class="smallForm" action=<?= $path.'actions.php'?> method="post">

        <input type="hidden" name="ACTION" value="NEWLABEL">
        <input type="hidden" name="PARID" value=<?=$_GET['par']?>>

        <input id="nlabel" type="text" name="NLABEL" placeholder="New label">
        <input type="submit" value="Add">
      </form>
    </div>

    <!-- Navigation -->
    <?php include("navigation.php");?>
  </body>
</html>