<?php
  session_start();
  $path = "/~wd417920/bd/";
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

?>

<footer>Witold Drzewakowski, DB Course, 2020-2021 Faculty of Mathematics, Informatics, and Mechanics, University of Warsaw </footer>