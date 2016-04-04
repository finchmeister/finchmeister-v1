<?php
// Last commit $Id$
// Version Location $HeadURL$

include "../database_configuration.php";

//$DEPLOY = true; // Uncomment to deploy

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

$csv = array_map('str_getcsv', file("../../html/poker-stats/pokerstatsApril4th2015.csv"));
print_r($csv);
unset ($csv[0]);
if (isset($DEPLOY)) {
  foreach ($csv as $row) {
    $sql = "insert into poker_rankings values (\"{$row[0]}\", \"{$row[1]}\", \"{$row[2]}\");";

    if ($mysqli->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}

$mysqli->close();