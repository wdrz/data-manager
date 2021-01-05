<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">


<?php

  $path = "/~wd417920/bd/";

  $num_per_page = 2;

  session_start();
  if ($_SESSION['ROLE'] != 'a') {
    echo "<style>";
    echo ".admin {display: none !important;}";
    echo ".admin * {display: none !important;}";

    if ($_SESSION['ROLE'] != 'u') {
      echo ".user {display: none !important;}";
      echo ".user * {display: none !important;}";
    }

    echo "</style>";
  }

  $conn = oci_connect($_SESSION['LOGIN'],$_SESSION['PASS'],"//labora.mimuw.edu.pl/LABS");
  if (!$conn) {
    echo "OCI conncection failed.\n";
    $e = oci_error();
    echo $e['message'];
  }
?>

<footer>Witold Drzewakowski, DB Course, 2020-2021 Faculty of Mathematics, Informatics, and Mechanics, University of Warsaw </footer>