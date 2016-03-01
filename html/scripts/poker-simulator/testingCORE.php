<?php
// License: www.workbooks.com/mit_license
// Last commit $Id$
// Version Location $HeadURL$

include 'poker_simulator_core.php';

$allPlayersCards = [["Kh", "Qd"], ["4h","3h"] ];
$shownCardsInPlay = ["9c","Qh", "Qs", "6h", "3d"];

$rankings = PokerSimulation::rankPlayersHands($allPlayersCards, $shownCardsInPlay);
var_dump($rankings);