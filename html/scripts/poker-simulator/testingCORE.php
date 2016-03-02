<?php
// License: www.workbooks.com/mit_license
// Last commit $Id$
// Version Location $HeadURL$

include 'poker_simulator_core.php';

/*$allPlayersCards = [["Kh", "Qd"], ["4h","3h"] ];
$shownCardsInPlay = ["9c","Qh", "Qs", "6h", "3d"];

$rankings = PokerSimulation::rankPlayersHands($allPlayersCards, $shownCardsInPlay);
var_dump($rankings);*/

$ccEX1Valid = [
  'cc1'=>1,
  'cc2'=>2,
  'cc3'=>3,
  'cc4'=>4,
  'cc5'=>5,
];
$ccEX2Valid = [
  'cc1'=>1,
  'cc2'=>2,
  'cc3'=>3,
  'cc4'=>4,
  'cc5'=>NULL,
];
$ccEX3Valid = [
  'cc1'=>1,
  'cc2'=>2,
  'cc3'=>3,
  'cc4'=>NULL,
  'cc5'=>NULL,
];
$ccEX4Valid = [
  'cc1'=>NULL,
  'cc2'=>NULL,
  'cc3'=>NULL,
  'cc4'=>NULL,
  'cc5'=>NULL,
];
$ccExINV = [
  'cc1'=>1,
  'cc2'=>2,
  'cc3'=>3,
  'cc4'=>NULL,
  'cc5'=>5,
];
$ccExINV = [
  'cc1'=>1,
  'cc2'=>NULL,
  'cc3'=>3,
  'cc4'=>NULL,
  'cc5'=>5,
];

function test($cc) {
  $cardsAtPreFlop = $cc['cc1'] == NULL && $cc['cc2'] == NULL && $cc['cc3'] == NULL && $cc['cc4'] == NULL && $cc['cc5'] == NULL;
  $cardsAtFlop    = $cc['cc1'] != NULL && $cc['cc2'] != NULL && $cc['cc3'] != NULL && $cc['cc4'] == NULL && $cc['cc5'] == NULL;
  $cardsAtTurn    = $cc['cc1'] != NULL && $cc['cc2'] != NULL && $cc['cc3'] != NULL && $cc['cc4'] != NULL && $cc['cc5'] == NULL;
  $cardsAtRiver   = $cc['cc1'] != NULL && $cc['cc2'] != NULL && $cc['cc3'] != NULL && $cc['cc4'] != NULL && $cc['cc5'] != NULL;
  if ($cardsAtPreFlop || $cardsAtFlop || $cardsAtTurn || $cardsAtRiver) {
    return TRUE;
  }
  else {
    return FALSE;
  }
}

var_dump(test($ccExINV));