<?php
  date_default_timezone_set('Europe/Warsaw');

  function own_execute($stmt, $conn) {
    if (!$result) {
      oci_rollback($conn);
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

  
    $stmt = oci_parse($conn, "insert into Dataset(name,description,date_created,created_by) values (:nam, :des, to_date(:dat, 'yyyy-mm-dd'), :byy)");
    oci_bind_by_name($stmt, ':nam', $_POST['DSNAME'], -1);
    oci_bind_by_name($stmt, ':des', $_POST['DSDESC'], -1);
    oci_bind_by_name($stmt, ':dat', $date, -1);
    oci_bind_by_name($stmt, ':byy', $_SESSION['UID'], -1);

    $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
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