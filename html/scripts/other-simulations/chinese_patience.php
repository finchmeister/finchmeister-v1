<?php
// Last commit $Id$
// Version Location $HeadURL$

include "../poker-simulator/poker_simulator_core.php";

class ChinesePatience {

  public $deck;
  public $piles = [];
  public $burntPile;
  public $currentShownCards;
  public $cardGenerations;

  // Piles is an array containing the 4 card piles
  public function getCurrentShownCards() {
    foreach ($this->piles as $pile) {
      $currentShownCards[] = end($pile);
    }
    $this->currentShownCards = $currentShownCards;
  }

  public function getSameSuitCards() {
    // Extract the card suits, ensuring blanks are ignored
    $currentShownCardsSuits = CardFunctions::getHandSuitArray(array_filter($this->currentShownCards));
    $cardCount = array_count_values($currentShownCardsSuits);
    $sameSuitCards = [];
    foreach ($cardCount as $suit => $count) {
      if ($count > 1) {
        // We have matching suits
        $sameSuitCards[] = CardFunctions::getCardsFromSuit($this->currentShownCards, $suit);
      }
    }
    return $sameSuitCards;
  }





  // Will only return the lowest suited card one at a time
  public function getLowestSuitedCard() {
    $sameSuitCards = $this->getSameSuitCards();
    // Just look at the first set of suited cards
    $suitedCards = $sameSuitCards[0];
    if (!empty($suitedCards)) {
      $orderedSuitedCards = CardFunctions::orderHandNumericallyDesc($suitedCards);
      $lowestSuitedCard = end($orderedSuitedCards);
      return $lowestSuitedCard;
    }
    return FALSE;// There are none
  }

  public function addToBurnPile($card) {
    foreach ($this->piles as $position => $pile) {
      if (!is_array($pile)) {$pile = [$pile];}
      $pileKey = array_search($card, $pile);
      // It should always be the end card in the pile, but just to be sure.
      if ($pileKey !== FALSE && $card == end($pile)) {
        unset($this->piles[$position][$pileKey]);
        $this->getCurrentShownCards();
        $this->burntPile[] = $card;
        return;
      }
    }
  }

  // This will return the first ace it finds
  public function findAce() {
    $cardNumberArray = CardFunctions::getHandNumberArray($this->currentShownCards);
    foreach ($cardNumberArray as $card => $number) {
      if ($number == 14) {
        return $card;
      }
    }
    return;
  }

  public function getBottomOfPile() {
    foreach ($this->piles as $pile) {
      $bottomOfPile[] = $pile[0];
    }
    return $bottomOfPile;
  }

  // Will return the Ace and pile position
  public function canAceGoToBottomOfPile() {
    $emptyPilePosition = $this->returnEmptyPilePosition();
    if ($emptyPilePosition !== FALSE) {
      $ace = $this->findAce();
      if (!empty($ace)){
        $bottomOfPile = $this->getBottomOfPile();
        foreach ($bottomOfPile as $bottomCard) {
          if ($ace == $bottomCard) {
            return FALSE;
          }
        }
        return [$ace, $emptyPilePosition];
      }
    }
    return FALSE;
  }

  public function returnEmptyPilePosition() {
    foreach ($this->piles as $position => $pile) {
      if (empty($pile)) {
        return $position;
      }
    }
    return FALSE;
  }

  public function moveAceToBottomOfPile($ace, $emptyPilePosition) {
    $this->piles[$emptyPilePosition][] = $ace;
  }



  //
  public function run($noOfGames){

    for ($i = 0; $i < $noOfGames; $i++) {
      $this->burntPile = [];
      $this->piles = [];
      // Shuffle deck
      $this->deck = DeckFunctions::shuffleDeck();
      // Set up the batches of cards
      $batchedCards = array_chunk($this->deck, 4);
      $generation = 0;
      // Main loop
      foreach ($batchedCards as $round) {
        $generation++;
        foreach ($round as $position => $card) {
          $this->piles[$position][] = $card;
        }
        // Get current shown cards
        $this->getCurrentShownCards();
        $this->cardGenerations[$generation] = $this->currentShownCards;


        do {
          $lowestSuitedCard = $this->getLowestSuitedCard();
          list($ace, $emptyPilePosition) = $this->canAceGoToBottomOfPile();

          if ($lowestSuitedCard) {
            $this->addToBurnPile($lowestSuitedCard);
            $generation++;
            $this->cardGenerations[$generation] = $this->currentShownCards;
          }
          if ($ace) {
            $this->moveAceToBottomOfPile($ace, $emptyPilePosition);
            $generation++;

            $this->cardGenerations[$generation] = $this->currentShownCards;
          }

        } while ($lowestSuitedCard || $ace);
      }
      $cardsRemaining[] = 52 - count($this->burntPile);
    }
    $howManyGames = array_count_values($cardsRemaining);
    ksort($howManyGames);
    return $howManyGames;


  }





}

$cp = new ChinesePatience();
$stats = $cp->run(10000);

foreach ($stats as $noCards => $times) {
  echo "$noCards, $times, \n";
}


?>




Algorithm

Deal 4 cards
Check for same suit or Ace and empty space
 Lowest of same suit goes in pile
 Ace goes in empty pile
 Repeat until cannot carry on
Next 4 cards
