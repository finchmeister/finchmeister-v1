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
  <title>Poker Stats</title>
  <style>
    /*INSERT STLYE*/
  </style>
</head>
<body>





<div class="container">
  <h2>Culver Road Poker Finishes</h2>
  <p>Since December '15</p>
  <?php
include "../scripts/login/header.php";


$sql = "select * from poker_rankings where date='2016-03-31'";
$result = $mysqli->query($sql);
$htmlRows = '';
if ($result->num_rows > 0) {
  // output data of each row
  $htmlRows .= '';
  $htmlRows .= <<<EOF
<table class="table table-hover">
  <thead>
  <tr>
    <th>Date</th>
    <th>Position</th>
    <th>Name</th>
  </tr>
  </thead>
  <tbody>
EOF;


  while($row = $result->fetch_assoc()) {
    $htmlRows .= '<tr><td>';
    $htmlRows .= implode('</td><td>', $row) . '</td></tr>';
  }
  $htmlRows .= '</tbody></table>';
}
echo $htmlRows;

  function createTablePanel($tableCoreHTML, $heading) {
    return <<<EOF
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">$heading</div>

  <!-- Table -->
  <table class="table">
    {$tableCoreHTML}
  </table>
</div>
EOF;


  }



?>
</div>



</body>
</html>