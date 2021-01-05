<?php
  $f = fopen('php://memory', 'w'); 
  // loop over the input array
  //foreach ($array as $line) { 

  session_start();

  $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
  if (!$conn) {
    echo "OCI conncection failed.\n";
    $e = oci_error();
    echo $e['message'];
  }

  $stmt = null;

  if ($_GET['type'] == 'DS') {
    $stmt = oci_parse($conn, "select * from (Image natural join DatasetBelonging) where dataset_id = :id");
    oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
  } else if ($_GET['type'] == 'AR') {
    $stmt = oci_parse($conn, "select * from ((ImageArea natural join Image) natural join DatasetBelonging) where dataset_id = :id");
    oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
  } else if ($_GET['type'] == 'CL') {
    $stmt = oci_parse($conn, "select * from ((((ImageArea natural join Classification) natural join Label) natural join Image) natural join DatasetBelonging) where dataset_id = :id");
    oci_bind_by_name($stmt, ':id', $_GET['id'], -1);
  } else {
    echo "Error! ";
    die();
  }

  $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

  if ($result != true) {
    echo("Input data has failed");
    $e = oci_error($stmt);
    echo "Error: " . $e['message'];
    die();
  }

  $filename = '';
  if ($_GET['type'] == 'DS') {
    fputcsv($f, ["image_id", "url"], ';'); 
    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
      $id = $row['IMAGE_ID'];
      $src = $row['CONTENT'];
      fputcsv($f, [$id, $src], ';'); 
    }
    $filename = 'images.csv';
  } else if ($_GET['type'] == 'AR') {
    fputcsv($f, ["area_id", "image_id", "x", "y", "width", "height"], ';'); 
    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
      fputcsv($f, [$row['AREA_ID'], $row['IMAGE_ID'], $row['X'], $row['Y'], $row['WIDTH'], $row['HEIGHT']], ';'); 
    }
    $filename = 'boxes.csv';
  } else if ($_GET['type'] == 'CL') {
    fputcsv($f, ["label", "image_area"], ';'); 
    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
      fputcsv($f, [$row['NAME'], $row['AREA_ID']], ';'); 
    }
    $filename = 'classifications.csv';
  }



  fseek($f, 0);
  header('Content-Type: application/csv');
  header('Content-Disposition: attachment; filename="'.$filename.'";');
  fpassthru($f);
?>