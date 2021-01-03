<?php
  session_start();
  if (! isset($_SESSION['USER'])) {
    echo "Authentication required";
  } else if ($_POST['ACTION'] == 'ADDIMG') {
    session_start();
    $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
    if (!$conn) echo "OCI conncection failed.\n";


    $stmt = oci_parse($conn, "insert into IMAGE (content) values (:url)");
    oci_bind_by_name($stmt, ':url', $_POST['URL'], -1);

    $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

    oci_commit($conn);

    if($result) {
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      die();

    } else {
        echo("Input data has failed");
        $e = oci_error($stmt);
        echo "Error: " . $e['message'];
    }

/*
    $_SESSION['LOGIN'] = $_POST['LOGN'];
    $_SESSION['PASS'] = $_POST['PASW'];*/
  } else {
    echo "ERROR";
  }
?>