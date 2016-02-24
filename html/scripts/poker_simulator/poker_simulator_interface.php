<?php
// Last commit $Id$
// Version Location $HeadURL$

/*
 * Heads up 1 player
 * Heads up 1 player & cards shown
 *
 *
 *
 * */

include 'poker_simulator_core.php';

$cards = [
  'p1c1' => $_REQUEST['p1c1'],
];
$args = ['p1c1', 'p1c2', 'p2c1', 'p2c2', 'p3c1', 'p3c2', 'p4c1', 'p4c2', 'p5c1', 'p5c2', 'p6c1', 'p6c2', 'p7c1', 'p7c2', 'p8c1', 'p8c2', 'cc1', 'cc2', 'cc3', ];

//$hand = [$_REQUEST["p1c1"], $_REQUEST["p1c2"]];
$hand = [substr($_REQUEST["q"], 0, 2), substr($_REQUEST["q"], 2, 4)];

$results = PokerSimulation::calculateOdds([$hand], FALSE, 2, 100);

echo "Win {$results[0]['winPercent']} %";
//print_r($results);


