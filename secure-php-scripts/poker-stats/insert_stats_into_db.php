<?php
// Last commit $Id$
// Version Location $HeadURL$

include "../database_configuration.php";

date_default_timezone_set('UTC');

$DEPLOY = true; // Uncomment to deploy

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

$csv = array_map('str_getcsv', file("pokerstatsALL.csv"));
print_r($csv);
unset ($csv[0]);
if (isset($DEPLOY)) {
  $maxDateSQL = 'select max(date) as "date" from poker_rankings';
  $result = $mysqli->query($maxDateSQL);
  $row = $result->fetch_assoc();
  $insertFromDate = $row['date'];

  echo 'Inserting rows newer than '.$insertFromDate;

  foreach ($csv as $row) {
    foreach ($row as &$cell) $cell = empty($cell) ? '0' : $cell;
    $row[0] = date_format(date_create_from_format('d/m/Y', $row[0]), 'Y-m-d');
    // Only insert rows that are new
    if (strtotime($row[0]) > strtotime($insertFromDate)) {
      $sql = "insert into poker_rankings values (\"{$row[0]}\", \"{$row[1]}\", \"{$row[2]}\", \"{$row[3]}\", \"{$row[4]}\", \"{$row[5]}\");";
      //echo $sql ."\n";
      if ($mysqli->query($sql) === TRUE) {
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
  }
}

$mysqli->close();