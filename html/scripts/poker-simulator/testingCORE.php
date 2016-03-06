<?php
// License: www.workbooks.com/mit_license
// Last commit $Id$
// Version Location $HeadURL$

include 'poker_simulator_core.php';

$allPlayersCards = [["Kh", "Qd"], ["4h","3h"] ];
$shownCardsInPlay = ["9c","Qh", "Qs", "", ""];

/*$rankings = PokerSimulation::rankPlayersHands($allPlayersCards, $shownCardsInPlay);
var_dump($rankings);*/

//print_r(PokerSimulation::calculateOdds([["Kh", "Qd"]], ["9c","Qh", "Qs", "2s","2d"], 2, 10));
//print_r(PokerSimulation::calculateOdds($player, $shown, 3, 1000));
