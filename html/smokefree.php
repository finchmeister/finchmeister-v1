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
    #section2 {
      padding:50px; max-width: 500px;
    }
    #counter {
      color: #ff160e; font-size: 40vmax; text-align: center;
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

if (isset($_POST['logout'])) {
  destroySession();
}

$registering = isset($_POST['email'], $_POST['p']); // TRUE if a user is registering
if ($registering) {
  $email = $_POST['email'];
  $password = $_POST['p'];

  if (login($email, $password, $mysqli) == true) {
    $validSession = true;
  } else {
    // Login failed
    echo 'Nah m8';
  }
}
 if (isset($_POST['lastsmokedate'])) {
   setLastSmokeDate($_SESSION['user_id'], $_POST['lastsmokedate'], $mysqli);
 }

if (login_check($mysqli) == true) {
  $validSession = true;
}
else {
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
}

if ($validSession) {
  // Get the last smoke date
  $daysSinceLastSmoke = getNoOfDaysSinceLastSmoke($_SESSION['user_id'], $mysqli);
  if (empty($daysSinceLastSmoke)) {
    // Set the last smoke date with the picker
    echo <<<EOF
<form class="form-inline" role="form" method="post" action="smokefree.php">
  <div class="form-group">
    <p>Date of last smoke: <input type="text" id="datepicker" name="lastsmokedate"></p>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-default">Submit</button>
  </div>
</form>
EOF;


  } else {
    // Display the no of days
    echo '<div id="section2" class="container-fluid"><p id="counter">'. $daysSinceLastSmoke . '</p></div>';
  }
}
?>
<form class="form-inline" role="form" method="post">
  <div class="form-group">
    <button type="submit" name="logout" class="btn btn-default">Destroy session</button>
  </div>
</form>

</body>

</html>