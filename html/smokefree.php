<!DOCTYPE html>
<!-- Last commit: $Id$ -->
<!-- Version Location: $HeadURL$ -->
<html>
<head lang="en">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <title>Smokefree</title>
  <style>
    body {
      position: relative; background-color: #ffffff; color: #fff;
    }
    #section1 {
      padding-top:50px;padding-bottom:15px;color: #fff; background-color: #f09f34;
    }
    #counter {
      color: #ff160e; font-size: 80vmin; text-align: center;
    }

  </style>
</head>
<body>

<div id="section1" class="container-fluid">
  <h1>Days since last cigarette...</h1>
</div>

<?php
    $start = strtotime('2016-03-19');
    $days = floor((time() - $start)/(60*60*24));
    echo '<p id="counter">'. $days . '</p>';
    ?>

</body>

</html>

