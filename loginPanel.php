<div id="user">
<?php
  
  session_start();

  if (isset($_SESSION['USER'])) {
    echo 'User: ';
    echo $_SESSION['USER'];
    echo ' ('.$_SESSION['ROLE'].')';
  } else {
    echo "<a href=".$path."loginPage.php>login</a>";
  }


  if (isset($_SESSION['LOGIN']) && isset($_SESSION['PASS'])) {
    echo ', Oracle: ';
    echo $_SESSION['LOGIN'];
  } else {
    echo 'You have no access to oracle db. <a href='.$path.'databaseLogin.php>Oracle login</a>';
  }
?>
</div>