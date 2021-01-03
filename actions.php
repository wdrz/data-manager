<?php
  function run() {
    $flags_user = array("ADDIMG", "LINKIMG", "ADDAREA", "DELLINK", "ADDCLASS", "NEWLABEL");
    $flags_admin = array("DELIMG");

    session_start();

    if (! isset($_SESSION['USER'])) {
      echo "Authentication required";
    } else if ((! in_array($_POST['ACTION'], $flags_user)) && (! in_array($_POST['ACTION'], $flags_admin))) {
      echo "ERROR: flag not recognised - ".$_POST['ACTION'];
    } else if ( in_array($_POST['ACTION'], $flags_admin) && $_SESSION['ROLE'] != 'a' ) {
      echo "ERROR: this operation can be performed only by an admin - ".$_POST['ACTION'];
    } else {
      // connect to database
      $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
      if (!$conn) echo "OCI conncection failed.\n";

      $stmt = null;

      switch ($_POST['ACTION']) {
        case 'ADDIMG':

          
            break;
        case 1:
            echo "i equals 1";
            break;
        case 2:
            echo "i equals 2";
            break;
    }


      if ($_POST['ACTION'] == 'ADDIMG') {
        $stmt = oci_parse($conn, "insert into IMAGE (content) values (:url)");
        oci_bind_by_name($stmt, ':url', $_POST['URL'], -1);

      } else if ($_POST['ACTION'] == 'LINKIMG') {
        $stmt = oci_parse($conn, "insert into DatasetBelonging (image_id, dataset_id) values (:id2, :id1)");
        oci_bind_by_name($stmt, ':id1', $_POST['DSID'], -1);
        oci_bind_by_name($stmt, ':id2', $_POST['IMGID'], -1);
      } else if ($_POST['ACTION'] == 'ADDAREA') {
        $stmt = oci_parse($conn, "insert into ImageArea(x, y, width, height, image_id) values (:x, :y, :w, :h, :id)");
        oci_bind_by_name($stmt, ':x', $_POST['IMGX'], -1);
        oci_bind_by_name($stmt, ':y', $_POST['IMGY'], -1);
        oci_bind_by_name($stmt, ':w', $_POST['IMGW'], -1);
        oci_bind_by_name($stmt, ':h', $_POST['IMGH'], -1);
        oci_bind_by_name($stmt, ':id', $_POST['IMGID'], -1);
      } else if ($_POST['ACTION'] == 'DELLINK') {
        $stmt = oci_parse($conn, "delete from DatasetBelonging where (image_id = :id2 and dataset_id = :id1)");
        oci_bind_by_name($stmt, ':id1', $_POST['DSID'], -1);
        oci_bind_by_name($stmt, ':id2', $_POST['IMGID'], -1);
      } else if ($_POST['ACTION'] == 'DELIMG') {
        $stmt = oci_parse($conn, "delete from Image where image_id = :id ");
        oci_bind_by_name($stmt, ':id', $_POST['IMGID'], -1);
      } else if ($_POST['ACTION'] == 'ADDCLASS') {
        $stmt = oci_parse($conn, "select * from label where name = :label");
        oci_bind_by_name($stmt, ':label', $_POST['ULABEL'], -1);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        $res = oci_fetch_array($stmt, OCI_BOTH);
        if ($res == false) {
          echo "ERROR: ".$_POST['ULABEL']." is not recognised as a label name. Try adding it to database first.";
          return;
        }
        $stmt = oci_parse($conn, "insert into Classification (area_id, created_by, label_id) values (:aid, :us, :lab)");
        oci_bind_by_name($stmt, ':aid', $_POST['AREAID'], -1);
        oci_bind_by_name($stmt, ':us', $_SESSION['UID'], -1);
        oci_bind_by_name($stmt, ':lab', $res['LABEL_ID'], -1);
      } else if ($_POST['ACTION'] == 'NEWLABEL') {

      }

      $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
      oci_commit($conn);

      if ($result) {
        header('Location: '.$_SERVER['HTTP_REFERER']);
        die();

      } else {
          echo("Input data has failed");
          $e = oci_error($stmt);
          echo "Error: " . $e['message'];
      }
    }
  }

  run();
?>