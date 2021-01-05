<div id="user">
<?php

  if (isset($_SESSION['USER'])) {
    echo 'User: ';
    echo $_SESSION['USER'];
    echo ' ('.$_SESSION['ROLE'].') ';
    echo "<form class='smallForm' action='".$path."loginPage.php' method='post'>";
    echo "<input type='hidden' name='ACTION' value='LOGOUT'>";
    echo "<input class='logout' type='submit' value='Logout'>";
    echo "</form>";

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