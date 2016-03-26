<?php
// Last commit $Id$
// Version Location $HeadURL$
//
function setLastSmokeDate($userId, $date, $mysqli) {
  date_default_timezone_set('UTC');
  $date = DateTime::createFromFormat('d/m/Y', $date);
  $date = $date->format("Y-m-d H:i:s");

  $stmt = $mysqli->prepare("INSERT INTO smokefree (user_id, time) VALUES (?, ?) ");
    $stmt->bind_param('is', $userId, $date);
    $stmt->execute();   // Execute the prepared query.
}

function getNoOfDaysSinceLastSmoke($userId, $mysqli) {
  $stmt = $mysqli->prepare("SELECT datediff(CURDATE(), time) FROM smokefree WHERE user_id=? ORDER BY time DESC limit 1;");
  $stmt->bind_param('i', $userId);
  $stmt->execute();
  $stmt->store_result();
  $daysSinceLastSmoke = '';
  if ($stmt->num_rows == 1) {
    $stmt->bind_result($daysSinceLastSmoke);
    $stmt->fetch();
  }
  return $daysSinceLastSmoke;
}