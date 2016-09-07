<?php
// License: www.workbooks.com/mit_license
// Last commit $Id$
// Version Location $HeadURL$

/*$start = microtime(1);
$j = 0;
for ($i = 0; $i < 10000000; $i++) {
  if ($i % 3) {
    $j++;
  }
}
$end = microtime(1);
$timeTaken = round($end - $start, 3);
echo "Time taken for $i iterations = $timeTaken";
exit(0);*/


include 'poker_simulator_core.php';

const BASE_URL = 'https://secure.workbooks.com/process/=kDM2gDN/poker_sim';
$DEBUG = FALSE;


// Calculate odds
function pokerSimTest($params) {
  global $MASSIVELY_PARALLEL, $OUTSOURCED;
  $start = microtime(1);

  if ($params['t'] == 'co') {
    if ($OUTSOURCED) {
      // Construct the URL from params and create a get request to outsourced server
      $urlParams = http_build_query($params);
      $url = BASE_URL . '?' . $urlParams;
      //$url = 'https://secure.workbooks.com/process/=kDM2gDN/poker_sim?t=co&p1c1=Ks&p1c2=5d&p=2&i=100';

      if ($MASSIVELY_PARALLEL > 1) {
        $mh = curl_multi_init();
        $conn = [];
        // Prepare the parallel requests
        for ($i = 0; $i < $MASSIVELY_PARALLEL; $i++) {
          $conn[$i] = curl_init($url);
          curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($conn[$i], CURLOPT_HEADER, 0);
          curl_multi_add_handle($mh, $conn[$i]);
        }
        $active = null;
        // Execute
        do {
          $status = curl_multi_exec($mh, $active);
          $info = curl_multi_info_read($mh);
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

        $multiResponse = [];
        for ($i = 0; $i < $MASSIVELY_PARALLEL; $i++) {
          $http_code = curl_getinfo($conn[$i], CURLINFO_HTTP_CODE);
          if ($http_code == 200) { // If OK add the response to the
            $multiResponse[$i] = json_decode(curl_multi_getcontent($conn[$i]), 1);
          }
          curl_close($conn[$i]);
        }
        curl_multi_close($mh);
        $aggregateResponse = aggregateParallelData($multiResponse);
        $response = json_encode($aggregateResponse);
      }
      // Run simple parallel
      else {
        $response = file_get_contents($url);
      }
    } else { // Run locally
      $response = calculateOddsFromRequest($params);
    }
    $end = microtime(1);
    $timeTaken = round($end - $start, 3);
    $response = json_decode($response, 1);
    $response['timeTaken'] = $timeTaken;
    return $response;
  }

}


function aggregateParallelData($parallelData) {
  foreach ($parallelData as $data) {
    foreach ($data as $player => $results) {
      foreach ($results as $outcome => $value) {
        if (!isset($result[$player][$outcome])) {
          $result[$player][$outcome] = 0;
        }
        $result[$player][$outcome] += $value;
      }
    }
  }
  // Correct the aggregate % data
  foreach ($result as $player => $results) {
    $total = $result[$player]['win'] + $result[$player]['split'] +$result[$player]['lose'];
    $result[$player]['winPercent'] = $result[$player]['win']/$total;
    $result[$player]['splitPercent'] = $result[$player]['split']/$total;
  }
  return $result;
}

function calculateOddsFromRequest($bob){
  $playersCards = [
    'p1' => ['c1', 'c2'],
    'p2' => ['c1', 'c2'],
    'p3' => ['c1', 'c2'],
    'p4' => ['c1', 'c2'],
    'p5' => ['c1', 'c2'],
    'p6' => ['c1', 'c2'],
    'p7' => ['c1', 'c2'],
    'p8' => ['c1', 'c2'],
    'p9' => ['c1', 'c2'],
  ];

// Convert player cards from the request into a format caculateOdds() will accept.
  foreach($playersCards as $player => $cards) {
    if(!empty($bob[$player.$cards[0]]) && !empty($bob[$player.$cards[1]])) {
      $hands[$player] = [$bob[$player.$cards[0]], $bob[$player.$cards[1]]];
    }
  }

//Community cards
  $communityCards = ['cc1', 'cc2', 'cc3', 'cc4', 'cc5'];
  foreach ($communityCards as $communityCard) {
    if(!empty($bob[$communityCard])) {
      $shownCards[] = $bob[$communityCard];
    }
  }

  $noOfPlayers = isset($bob['p']) ? $bob['p'] : FALSE;

  $iterations = isset($bob['i']) ? $bob['i'] : NO_OF_ITERATIONS;
  $results = PokerSimulation::calculateOdds($hands, $shownCards, $noOfPlayers, $iterations);
  return json_encode($results);
}
/*
echo date_default_timezone_get();
exit(0);*/
//----ACTUAL BENCHMARK
const NO_OF_ITERATIONS = 100;
const TEST_WB = false;
// Benchmarking the poker sim locally
$urlParams = 't=co&p=5&p1c1=Ad&p1c2=As&p2c1=&p2c2=&p3c1=&p3c2=&p4c1=&p4c2=&p5c1=&p5c2=&cc1=2c&cc2=3c&cc3=4c&cc4=&cc5=';
$bob = [];
parse_str($urlParams, $bob);
$OUTSOURCED = false;
$noOfSims = 5;
for ($i=0; $i<$noOfSims; $i++) {
  $response[] = pokerSimTest($bob);
}
echo 'Local times ';
$responseTimes = array_column($response, 'timeTaken');
$stats['avg'] = StatisticsFunctions::mean($responseTimes);
$stats['sd'] = StatisticsFunctions::sd($responseTimes);
$responseTimes['stats'] = $stats;

print_r($responseTimes);
unset($response, $stats);

// Benchmark WB
if (TEST_WB) {
  $OUTSOURCED = true;
  $MASSIVELY_PARALLEL = 5;
  $bob['i'] = NO_OF_ITERATIONS/$MASSIVELY_PARALLEL;
  for ($i=0; $i<$noOfSims; $i++) {
    $response[] = pokerSimTest($bob);
  }
  echo 'Remote times';
  $responseTimes = array_column($response, 'timeTaken');
  $stats['avg'] = StatisticsFunctions::mean($responseTimes);
  $stats['sd'] = StatisticsFunctions::sd($responseTimes);
  $responseTimes['stats'] = $stats;
  print_r($responseTimes);
}
