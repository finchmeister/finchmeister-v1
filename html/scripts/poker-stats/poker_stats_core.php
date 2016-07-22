<?php
// License: www.workbooks.com/mit_license
// Last commit $Id$
// Version Location $HeadURL$
include dirname(__FILE__)."/../login/header.php";

function getResultsInArray() {
  global $mysqli;
  // Col names "select 'date', 'position', 'name', 'winnings', 'buyIn', 'rebuys';
  $sql = "select * from poker_rankings order by date desc, position asc";
  $result = $mysqli->query($sql);
  $resultsArray = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

      $gameId = 'game_'.$row['date'];
      $resultsArray[$gameId][] = [
        'date' => $row['date'],
        'position' => $row['position'],
        'name' => $row['name'],
        'winnings' => $row['winnings'],
        'buyIn' => $row['buyIn'],
        'rebuys' => $row['rebuys'],
        'boughtIn' => $row['buyIn'] + $row['rebuys']*$row['buyIn'],
        'net' => $row['winnings'] - ($row['buyIn'] + $row['rebuys']*$row['buyIn']),
      ];
    }
  }

  $resultsArray = calculatePoints($resultsArray);

  return $resultsArray;
}

function createPointsStats($resultsArray) {

  $noOfGames = 0;
  foreach ($resultsArray as $result) {
    if (!empty($result[0]['points'])) {
      $noOfGames++;
      foreach ($result as $row) {
        if (!empty($row['points'])) {
          /*if (!isset($totalPoints[$row['name']])) {
            $totalPoints[$row['name']] = 0;
          }
          $totalPoints[$row['name']] += $row['points'];*/
        }
        $totalPoints[$row['name']][] = $row['points'];
      }
    }
  }
  $gamesCounted = floor($noOfGames/2);

  foreach ($totalPoints as $player => &$pointsArray) {
    $i = $gamesCounted;
    if (count($pointsArray) >= $gamesCounted) {
      arsort($pointsArray);
      $pointsArray = array_values($pointsArray);
      while (!empty($pointsArray[$i])) {
        unset($pointsArray[$i]);
        $i++;
      }
    }
    else {
      unset($totalPoints[$player]);
    }

  }

  $rawStats = $totalPoints;
  $totalPoints = array_map('array_sum', $rawStats);
  arsort($totalPoints);

  foreach ($totalPoints as $player => $points) {
    $sortedTotalPoints[] = [$player, $points];
  }

  return [
    'noOfGames' => $noOfGames,
    'gamesCounted' => $gamesCounted,
    'rawStats' => $rawStats,
    'totalPoints' => $sortedTotalPoints,
  ];
}

function getStats() {

  $queries = [
    'noOfGames' => 'select count(distinct date) as "total" from poker_rankings',
    'gamesWon' => 'select name, count(date) as "total" from poker_rankings where position=1 group by name order by total desc',
    'gamesWon2' => 'select name, sum(position=1) as "Games Won", count(date) as "Games Played", round(sum(position=1)/count(date)*100,2) as "Win Percent" from poker_rankings group by name order by sum(position=1) desc, count(date)',
    'winnings' => 'select name, sum(winnings) as "total" from poker_rankings where winnings!=0 group by name order by total desc',
    'net' => 'select name, sum(winnings)-sum(rebuys*buyIn+buyIn) as "total" from poker_rankings where date>="2016-04-14" group by name order by total desc',
    'rebuys' => 'select name, sum(rebuys) as "total" from poker_rankings where rebuys!=0 group by name order by total desc',
    'gamesPlayed' => 'select name, count(date) as "total" from poker_rankings group by name order by total desc',
    'totalWinnings' => 'select sum(winnings) as "total" from poker_rankings',
    'noOfRebuys' => 'select sum(rebuys) as "total" from poker_rankings',
    'winningsAndNet' => 'select name, sum(winnings) as "total",sum(rebuys*buyIn+buyIn) as "boughtIn",sum(winnings)-sum(rebuys*buyIn+buyIn) as  "net", sum(rebuys) as "rebuys" from poker_rankings where buyIn!=0 group by name order by net desc',
  ];

  foreach ($queries as $stat => $query) {
    $stats[$stat] = getArrayFromQuery($query);
  }
  return $stats;
}

function getArrayFromQuery($query) {
  global $mysqli;
  $result = $mysqli->query($query);

  $resultsArray = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      foreach ($row as $key => $value) {
        $resultsArrayRow[$key] = $value;
      }
      $resultsArray[] = $resultsArrayRow;
    }
  }
  return $resultsArray;
}

function calculatePoints($resultsArray) {
  // Calculate score http://forums.homepokertourney.com/index.php/topic,28150.0.html
  foreach ($resultsArray as $gameId => &$result) {
    $b = $result[0]['buyIn'];
    if ($b == 0) { continue;} // Before cash was recorded
    $n = count($result);
    foreach ($result as &$row) {
      $e = $row['boughtIn'];
      $f = $row['position'];
      //echo "n: $n, b: $b, e: $e, f: $f \n";
      $score = round(sqrt($n * $b * $b / $e)/($f + 1), 2);
      $row['points'] = $score;
    }
  }
  return $resultsArray;
}

?>