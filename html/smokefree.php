<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<!-- Last commit: $Id$ -->
<!-- Version Location: $HeadURL$ -->
<html>
<head lang="en">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!--  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->  <link rel="stylesheet" href="resources/css/datepicker.css">
  <link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <title>Smokefree</title>
  <style>
    body {
      position: relative; background-color: #ffffff;
    }
    h1 {
      color: #fff;
    }
    #section1 {
      padding-top:50px;padding-bottom:15px;color: #fff; background-color: #f09f34;
    }
    #counter {
      color: #ff160e; font-size: 80vmin; text-align: center;
    }

  </style>
  <script>
    $(function() {
      $( "#datepicker" ).datepicker({
        dateFormat:"dd/mm/yy"
      });
    });
  </script>
</head>
<body>

<div id="section1" class="container-fluid">
  <h1>Days since last cigarette...</h1>
</div>

<?php
include "scripts/login/header.php";
include "scripts/smoke-free/smokefreedb.php";
var_dump($_SESSION);



$registering = isset($_POST['email'], $_POST['p']); // TRUE if a user is registering
if ($registering) {
  $email = $_POST['email'];
  $password = $_POST['p'];

  if (login($email, $password, $mysqli) == true) {
    // Login success
  } else {
    // Login failed
    echo 'Nah m8';
  }
}

if (login_check($mysqli) == true) {

  // Get the last smoke date
  $daysSinceLastSmoke = getNoOfDaysSinceLastSmoke($_SESSION['user_id'], $mysqli);
  if (empty($daysSinceLastSmoke)) {
    // Set the last smoke date with the picker
    echo <<<EOF
<form class="form-inline" role="form" method="post" action="smokefree.php">
  <div class="form-group">
    <p>Date of last smoke: <input type="text" id="datepicker" name="lastsmoke"></p>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-default">Submit</button>
  </div>
</form>
EOF;
  } else {
    // Display the no of days
    echo '<p id="counter">'. $daysSinceLastSmoke . '</p>';
  }
} else {
  // Display login form
  echo <<<EOF
<form class="form-inline" role="form" method="post" action="smokefree.php">
  <div class="form-group">
    <label class="sr-only" for="email">Email:</label>
    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
  </div>
  <div class="form-group">
    <label class="sr-only" for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="p">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
EOF;

  if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p'];

    if (login($email, $password, $mysqli) == true) {
      // Login success
      echo 'LOGGED IN m8';
    } else {
      // Login failed
      echo 'Nah m8';
    }
  } else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
  }
}


if (isset($_POST['destroy'])) {
  destroySession();
}

?>
<form class="form-inline" role="form" method="post">
  <div class="form-group">
    <button type="submit" name="destroy" class="btn btn-default">DESTROY</button>
  </div>
</form>

</body>

</html>
TODO
https://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/
Real simple login screen
Create a menu to store the quit date
Use cookies to store info, login
http://www.w3schools.com/php/php_cookies.asp
http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL