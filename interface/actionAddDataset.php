<?php
  date_default_timezone_set('Europe/Warsaw');

  function own_execute($stmt, $conn) {
    $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
    if (!$result) {
      echo("Input data has failed");
      $e = oci_error($stmt);
      echo "Error: " . $e['message'];

      die();
    }
  }

  function run() {
    include("path.php");

    session_start();
    

    if (! isset($_SESSION['USER'])) {
      echo "Authentication required";
      return;
    }

    $val = 15321923* 124;

    // connect to database
    $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
    if (!$conn) echo "OCI conncection failed.\n";

    $date = date("Y-m-d");
    echo $date . "\n";
    echo $_POST['DSNAME'] . "  " . $_POST['DSDESC'] . "\n";

    // add row to Dataset
    $stmt = oci_parse($conn, "insert into Dataset(name,description,date_created,created_by) values (:nam, :des, to_date(:dat, 'yyyy-mm-dd'), :byy)");
    oci_bind_by_name($stmt, ':nam', $_POST['DSNAME'], -1);
    oci_bind_by_name($stmt, ':des', $_POST['DSDESC'], -1);
    oci_bind_by_name($stmt, ':dat', $date, -1);
    oci_bind_by_name($stmt, ':byy', $_SESSION['UID'], -1);

    own_execute($stmt, $conn);

    // find id of added dataset
    $stmt = oci_parse($conn, "select dataset_id from Dataset where name = :nam");
    oci_bind_by_name($stmt, ':nam', $_POST['DSNAME'], -1);
    own_execute($stmt, $conn);

    $ds_id = oci_fetch_array($stmt, OCI_BOTH)[0];

    // add explicitly given ids to the dataset
    for ($i = 1; !empty($_POST["IMGID${i}"]); $i++) {
      $stmt = oci_parse($conn, "insert into DatasetBelonging (image_id, dataset_id) values (:id1, :id2)");
      oci_bind_by_name($stmt, ':id1', $_POST["IMGID${i}"], -1);
      oci_bind_by_name($stmt, ':id2', $ds_id , -1);
      own_execute($stmt, $conn);
    }


    // iterate over privided names of labels
    for ($i = 1; !empty($_POST["LABEL${i}"]) && !empty($_POST["COUNT${i}"]); $i++) {
      // find id of given label
      $stmt = oci_parse($conn, "select label_id from label where name = :label");
      oci_bind_by_name($stmt, ':label', $_POST["LABEL${i}"], -1);
      own_execute($stmt, $conn);
      $label_id = oci_fetch_array($stmt, OCI_BOTH)[0];

      // select images that contain boxes classified this way
      $stmt = oci_parse($conn, "
        select image_id, count(area_id)
        from (ImageArea natural join Classification)
        where label_id = :lid
        group by image_id
      ");
      oci_bind_by_name($stmt, ':lid', $label_id, -1);
      own_execute($stmt, $conn);

      $num_added = 0;
      while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        if ($num_added >= $_POST["COUNT${i}"]) {
          break;
        }
        $image_id = $row[0];
        $count = $row[1];

        $stmt2 = oci_parse($conn, "insert into DatasetBelonging (image_id, dataset_id) values (:id1, :id2)");
        oci_bind_by_name($stmt2, ':id1', $image_id, -1);
        oci_bind_by_name($stmt2, ':id2', $ds_id , -1);
        own_execute($stmt2, $conn);

        $num_added = $count + $num_added;

      }

    }

    // copy images from other datasets
    for ($i = 1; !empty($_POST["DS${i}"]); $i++) {
      // find id of given label
      $stmt = oci_parse($conn, "select image_id from DatasetBelonging where dataset_id = :did");
      oci_bind_by_name($stmt, ':did', $_POST["DS${i}"], -1);
      own_execute($stmt, $conn);

      while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        $image_id = $row[0];

        $stmt2 = oci_parse($conn, "insert into DatasetBelonging (image_id, dataset_id) values (:id1, :id2)");
        oci_bind_by_name($stmt2, ':id1', $image_id, -1);
        oci_bind_by_name($stmt2, ':id2', $ds_id , -1);
        own_execute($stmt2, $conn);
      }
    }

    // delete duplicates in this dataset
    $stmt = oci_parse($conn, "
      delete DatasetBelonging
      where (
        rowid not in (
          select min(rowid)
          from   DatasetBelonging
          group  by image_id, dataset_id
        ) and (
          dataset_id = :did
        )
      )
    ");
    oci_bind_by_name($stmt, ':did', $ds_id , -1);
    own_execute($stmt, $conn);

    oci_commit($conn);

    header("Location: ".$path);
    die();

  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    run();
  } else {
    echo "Error\n";
  }

?>