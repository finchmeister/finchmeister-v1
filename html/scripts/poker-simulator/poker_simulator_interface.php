<?php
// Last commit $Id$
// Version Location $HeadURL$

/*
 * Heads up 1 player
 * Heads up 1 player & cards shown
 *
 *
 *
 *
 * No. of hands until royal flush (Probably needs reworking)
 * No. of hands until anything!
 * */

header('Content-Type: application/json');

include 'poker_simulator_core.php';

const NO_OF_ITERATIONS = 100;
$DEBUG = FALSE;

debug($_REQUEST);

// Calculate odds
if ($_REQUEST['t'] == 'co') {
  echo calculateOddsFromRequest();
}

function calculateOddsFromRequest(){
  $playersCards = [
    'p1' => ['c1', 'c2'],
    'p2' => ['c1', 'c2'],
    'p3' => ['c1', 'c2'],
    'p4' => ['c1', 'c2'],
    'p5' => ['c1', 'c2'],
    'p6' => ['c1', 'c2'],
    'p7' => ['c1', 'c2'],
    'p8' => ['c1', 'c2'],
  ];

// Convert player cards from the request into a format caculateOdds() will accept.
  foreach($playersCards as $player => $cards) {
    if(isset($_REQUEST[$player.$cards[0]]) && isset($_REQUEST[$player.$cards[1]])) {
      $hands[] = [$_REQUEST[$player.$cards[0]], $_REQUEST[$player.$cards[1]]];
    }
    else {
      break;
    }
  }

//Community cards
  $communityCards = ['cc1', 'cc2', 'cc3', 'cc4', 'cc5'];
  foreach ($communityCards as $communityCard) {
    if(isset($_REQUEST[$communityCard])) {
      $shownCards[] = $_REQUEST[$communityCard];
    }
  }

  $noOfPlayers = isset($_REQUEST['p']) ? $_REQUEST['p'] : FALSE;

  $iterations = isset($_REQUEST['i']) ? $_REQUEST['i'] : NO_OF_ITERATIONS;
  $results = PokerSimulation::calculateOdds($hands, $shownCards, $noOfPlayers, $iterations);
  return json_encode($results);
}

//http://localhost/scripts/poker-simulator/poker_simulator_interface.php?t=co&p1c1=Kh&p1c2=Qd&p2c1=4h&p2c2=3h&cc1=Qh&cc2=5s&cc3=6h&cc4=3d&i=1000
//http://localhost/scripts/poker-simulator/poker_simulator_interface.php?t=co&p1c1=Kh&p1c2=Qd&p2c1=4h&p2c2=3h&i=1000