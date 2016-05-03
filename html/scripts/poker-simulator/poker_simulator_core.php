<?php
/**
 *  Last commit $Id$
 *  Version Location $HeadURL$
 *
 *  Card, Hand, Deck and Poker Functions Core
 *
 */

function debug($debug) {
  global $DEBUG;
  if ($DEBUG) {
    if(is_array($debug)) {
      print_r($debug);
    } else {
      echo $debug . "\n\n";
    }
  }
}

class DeckFunctions {

  //Create an unshuffled deck
  public static function createUnshuffledDeck(){
    $deck = [];
    $suits = ['c', 'd', 'h', 's'];
    foreach($suits as $suit) {
      for($i = 2; $i <= 14; $i++) {
        $card = CardFunctions::returnValue($i) . $suit;
        $deck[] = $card;
      }
    }
    return $deck;
  }

  //Can pass in a deck or no deck, this will return a shuffled version
  public static function shuffleDeck($deck = FALSE){
    if(!$deck){$deck = self::createUnshuffledDeck();} //Create a deck in nothing is passed in
    //Pick a random array key 0-51
    //Put value in shuffled deck
    //Unset the value taken out
    //Reset the array keys 0-50
    //Pick a random array key 0-50
    //...
    $noOfCardsInDeck = count($deck);
    for($i = 0; $i < $noOfCardsInDeck; $i++){
      $randomArrayKey = mt_rand(0, $noOfCardsInDeck - $i - 1);
      $shuffledDeck[] = $deck[$randomArrayKey];
      unset($deck[$randomArrayKey]);
      $deck = array_values($deck);
    }
    return $shuffledDeck;
  }

  //Pass in the parameters below. Function will return an array of dealt cards, see
  //initialised array for structure. Can choose the deck you pass in (e.g., when cards need to be excluded)
  //and when to stop dealing.
  public static function deal($noOfPlayers, $deck = FALSE, $dealUpTo = 'river') {
    //Initialise dealt cards array
    $dealtCards = [
      'players' => [],                              //Array of each player's cards
      'flop' => [],                                 //First three cards turned
      'turn' => [],                                 //Four cards seen after the turn
      'river' => [],                                //Five cards seen after the river
    ];

    //If no deck is passed in, this will shuffle a standard deck, otherwise will only shuffle the cards passed in
    $shuffledDeck = self::shuffleDeck($deck);

    //Give each player two cards
    $noOfCardsToBeHandedOut = $noOfPlayers * 2; //We're to hand each player 2 cards
    for($i = 0; $i < $noOfCardsToBeHandedOut; $i++){
      $playerCards[($i%$noOfPlayers)][] = $shuffledDeck[$i]; //Using $i mod numberOfPlayers,
    }
    @$dealtCards['players'] = $playerCards;

    if($dealUpTo == 'players') {return $dealtCards;}

    //Now we burn a card
    $firstBurnCard = $shuffledDeck[$i];
    $i++;

    //Get the Flop
    for($j = $i; $j < $i + 3; $j++){
      $flop[] = $shuffledDeck[$j];
    }
    $dealtCards['flop'] = $flop;

    if($dealUpTo == 'flop') {return $dealtCards;}

    //Burn another card
    $secondBurnCard = $shuffledDeck[$j];
    $j++;

    //Get the Turn
    $turn = $shuffledDeck[$j];
    $flop[] = $turn;
    $turn = $flop;
    $dealtCards['turn'] = $turn;
    $j++;

    if($dealUpTo == 'turn') {return $dealtCards;}

    //Burn another card
    $thirdBurnCard = $shuffledDeck[$j];
    $j++;

    //Get the River
    $river = $shuffledDeck[$j];
    $turn[] = $river;
    $river = $turn;
    $dealtCards['river'] = $river;

    return $dealtCards;
  }

  public static function removeCardsFromDeck($cardsToRemove, $deck){
    $remainingCards = array_diff($deck, $cardsToRemove);
    $remainingCards = array_values($remainingCards);
    return $remainingCards;
  }

  public static function dealIndividualCard($shuffledDeck){

  }

} //End of deck functions.


class CardFunctions {

  //Number: the numerical value of the card where Ace is 14. i.e., 1, 2, 3, ..., 12, 13, 14.
//Value: is the name of the card ignoring suit. i.e., 1, 2, 3, ..., J, Q, K, A.
  public static function returnValue($i){
    switch($i) {
      case 11:
        return 'J';
      case 12:
        return 'Q';
      case 13:
        return 'K';
      case 14:
        return 'A';
      default:
        return $i;
    }
  }

  public static function returnNumber($value) {
    switch ($value) {
      case 'J':
        return 11;
      case 'Q':
        return 12;
      case 'K':
        return 13;
      case 'A':
        return 14;
      default:
        return $value;
    }
  }

  //Pass in a card, return the suit.
  public static function getSuit($card) {
    $cardLen = strlen($card);
    $suit = substr($card, $cardLen - 1);
    return $suit;
  }

  //Pass in a card, return the card value, i.e., 2,3,4,...,J,Q,K,A.
  public static function getValue($card) {
    $cardLen = strlen($card);
    $value = substr($card, 0, $cardLen - 1);
    return $value;
  }

  //Pass in a card, return the card number, i.e., 2,3,4,...,11,12,13,14.
  public static function getNumber($card) {
    $value = self::getValue($card);
    $number = self::returnNumber($value);
    return $number;
  }

  //Ensure the card is valid
  public static function isCardValid($card) {
    $cardLen = strlen($card);
    //Cannot have a length shorter than 2 or greater than 3.
    if ($cardLen < 2 || $cardLen > 3) {
      //echo 'Invalid string length';
      return FALSE;
    }
    //Suit can only be c, d, h or s.
    $suit = self::getSuit($card);
    $validSuit = $suit == 'c' || $suit == 'd' || $suit == 'h' || $suit == 's';
    if (!$validSuit) {
      //echo 'Invalid suit';
      return FALSE;
    }
    //Cannot have a face other than J, Q, K, A.
    $number = self::getNumber($card);
    if (!is_numeric($number)) {
      //echo 'Not a number';
      return FALSE;
    }
    $value = self::getValue($card);
    //Is the value valid, i.e., does the card start with 2,3,4,...,J,Q,K,A.
    if(is_numeric($value)) { //Check numerical case
      if ($value < 2 || $value > 10) {
        echo 'Not a valid value between 2 and 10';
        return FALSE;
      }
    }
    elseif(!($value = 'J' || $value = 'Q' || $value = 'K' || $value = 'A')) {//Check picture case
      //echo 'Not a valid value of J, Q, K or A.';
      return FALSE;
    }
    return TRUE;
  }

  //Are hands valid?
  //Must be an array
  //If strictly5CardMust is not passed as 0, the hand must be of 5 cards
  //All cards must be valid
  //Two of the same card must not exist
  public static function areHandsValid($hands, $strictly5Card = 1) {
    if(!is_array($hands[0])) {//Deals with the single hand array case
      $hands = [$hands];
    }
    foreach ($hands as $hand) {
      if($strictly5Card) {
        $sizeOfHand = count($hand);
        if($sizeOfHand != 5) {
          //echo "Hand is not 5 card";
          return FALSE;
        }
      }
      foreach ($hand as $card) {
        if (!self::isCardValid($card)) {
          echo "{$card} is invalid";
          return FALSE;
        }
        $cards[] = $card;
      }
    }
    //Now check two cards which are the same do not exist
    $cardCount = array_count_values($cards);
    foreach ($cardCount as $card => $count) {
      if ($count > 1) {
        //echo "{$card} appears {$count} times";
        return FALSE;
      }
    }
    //All tests passed - hand is valid.
    return TRUE;
  }

  public static function getHandNumberArray($hand) {
    foreach($hand as $card){
      $handArray[$card] = self::getNumber($card);
    }
    return $handArray;
  }

  public static function getHandSuitArray($hand) {
    foreach($hand as $card){
      $handArray[$card] = self::getSuit($card);
    }
    return $handArray;
  }

  //Feed in the numerical value of the card and return an array of cards which match.
  //If the strict parameter is set to one, this function will only an exact match of cards specified in maxNumberReturned
  //E.g., when there are three of the same cards on the table, $maxNumberReturned is set to 2 and $strict is set to 1
  //this function will return FALSE.
  public static function getCardsFromNumber($hand, $number, $maxNumberReturned = 1, $strict = 0){
    foreach($hand as $card){
      $cardNumber = self::getNumber($card);
      if($cardNumber == $number) {
        $cards[] = $card;
      }
    }
    if(!empty($cards)) {
      $numberOfCardsFound = count($cards);
      if($strict && $numberOfCardsFound != $maxNumberReturned) {
        return FALSE;
      }
      for($i = 0; $i < $maxNumberReturned && $i < $numberOfCardsFound; $i++) {
        $foundCards[] = $cards[$i];
      }
      return $foundCards;
    }
    return FALSE;
  }


  //Very similar to the above except this accepts an array of numbers, not a single value
  public static function getCardsFromNumbers($hand, $numbers){
    $noOfCardsInHand = count($hand);
    foreach($numbers as $number) {
      $number = self::returnNumber($number);
      $card = self::getCardsFromNumber($hand, $number, $noOfCardsInHand);
      if(!empty($card)) {$setOfCards[] = $card;}
    }
    if(!empty($setOfCards)) {
      $cards = [];
      foreach($setOfCards as $set){
        if(is_array($set)) {$cards = array_merge($cards, $set);} //We're dealing with strings or arrays here
        else { $cards[] = $set;}
      }
      return $cards;
    }
    return FALSE;
  }

  //Specify a suit and return any cards from the hand which are of that suit
  public static function getCardsFromSuit($hand, $suit) {
    foreach($hand as $card){
      $cardSuit = self::getSuit($card);
      if($cardSuit == $suit) {
        $cards[] = $card;
      }
    }
    if(!empty($cards)) {
      return $cards;
    }
    return FALSE;
  }

  public static function returnFlushCards($hand){
    $handArray = CardFunctions::getHandSuitArray($hand);
    $cardCount = array_count_values($handArray);
    foreach($cardCount as $suit => $count){
      //There will only ever be at most 1 flush available in a hand, therefore
      //once we've found 5 or more cards of the same suit, we can return the cards.
      if($count >= 5){
        $cards = CardFunctions::getCardsFromSuit($hand, $suit);
        return $cards;
      }
    }
    return FALSE;
  }

  public static function orderHandNumericallyDesc($hand) {
    $handArray = self::getHandNumberArray($hand);
    arsort($handArray);
    foreach($handArray as $card => $number){
      $orderedHand[] = $card;
    }
    return $orderedHand;
  }

  public static function removeCardsFromHand($cards, $hand) {
    $remainingCards = DeckFunctions::removeCardsFromDeck($cards, $hand);
    return $remainingCards;
  }

  //
  public static function returnHighestMatchingNumberCards($hand, $howMany, $strict = 0) {
    $handArray = CardFunctions::getHandNumberArray($hand);
    $cardCount = array_count_values($handArray);
    foreach($cardCount as $number => $count){
      if($count == $howMany) {$matching[] = $number;}
    }
    if(empty($matching)){
      return FALSE;
    }
    //Get the maximum pair
    rsort($matching);
    $highestMatchingNumber = $matching[0];
    $cards = CardFunctions::getCardsFromNumber($hand, $highestMatchingNumber, $howMany, $strict);
    return $cards;
  }

  //Pass in a hand array and this function will return the decimal value of the cards.
  //It does not know what the hand is, it just computes the decimal value which is used
  //to identify how strong the hand against one of the same type.
  public static function calculateDecimalHandValue($hand) {
    $numberArray = self::getHandNumberArray($hand);
    $numberArray = array_unique($numberArray);
    $numberArray = array_values($numberArray);
    $size = count($numberArray);
    $decimalValue = 0;
    for($i = 0; $i < $size; $i++){
      $cardValue = $numberArray[$i]  * pow(10, (($i + 1) * -2));
      $decimalValue = $decimalValue + $cardValue;
    }
    return $decimalValue;
  }

  //
  public static function returnCardsInHand($cards, $hands) {
    $cardsInHand = array_intersect($cards, $hands);
    if(empty($cardsInHand)) {
      return FALSE;
    }
    return $cardsInHand;
  }


} //End of card functions

class HandEvaluation {

  public static function findHighCard($hand) {
    $handArray = CardFunctions::orderHandNumericallyDesc($hand);
    $highCard = $handArray[0];
    return $highCard;
  }

  public static function findHighestPair($hand) {
    $cards = CardFunctions::returnHighestMatchingNumberCards($hand, 2, 1);
    return $cards;
  }

  public static function findTwoPair($hand) { //TODO needs to be strictly 2 pair, i.e., exclude FH
    //Find pair, then remove the cards then find a pair again.
    $firstPair = self::findHighestPair($hand);
    if($firstPair) { //We've found a pair
      $remainingCards = CardFunctions::removeCardsFromHand($firstPair, $hand);
      $secondPair = self::findHighestPair($remainingCards); //TODO does this exclude full house!!
      if($secondPair) {
        $cards = array_merge($firstPair, $secondPair);
        return $cards;
      }
    }
    return FALSE;
  }

  public static function findThreeOfAKind($hand) {
    $cards = CardFunctions::returnHighestMatchingNumberCards($hand, 3);
    return $cards;
  }

  public static function findStraight($hand) {
    $handNumberArray = CardFunctions::getHandNumberArray($hand);
    $handNumberArray = array_unique($handNumberArray); //We need to remove duplicate numbers as they will mess with our series
    $handNumberArrayWithoutCards = array_values($handNumberArray); //Clear the array keys
    rsort($handNumberArrayWithoutCards); //Sort descending
    $numericallyOrderedHandNumberArrayWithoutCards = $handNumberArrayWithoutCards;
    //If an ace is in the hand, it will now be in the first array value so add a 1
    //to the hand to allow for an ace low straight
    if ($numericallyOrderedHandNumberArrayWithoutCards[0] == 14) {
      $numericallyOrderedHandNumberArrayWithoutCards[] = 1;
    }
    $numericallyOrderedHand = $numericallyOrderedHandNumberArrayWithoutCards; //just renaming the variable to something shorter
    $numberOfCardsInHand = count($numericallyOrderedHand);
    $maximumIterations = $numberOfCardsInHand - 4; //The number of starting points available
    for ($i = 0; $i < $maximumIterations; $i++) { //A 5 card straight cannot be made with less than 4 cards left in the sequence
      $j = 0; //Set the sequence counter
      while ($j < 4 && ($numericallyOrderedHand[($i + $j)] - 1 == $numericallyOrderedHand[($i + $j + 1)])) {
        $j++;
      }
      if ($j == 4) { //Only when j reaches 4 do we have a 5 card straight
        //At this point i + j is our end array key, therefore i + j - 4 is the start
        for ($k = 0; $k < 5; $k++) {
          $highestStraightNumbers[$k] = $numericallyOrderedHand[($i + $j - 4 + $k)];
        }
        if($highestStraightNumbers[4] == 1) {$highestStraightNumbers[4] = 14;} //Replace the 1 with a 14 to return an Ace from the number
        //We have the numerical value of each card in the straight, now lets get the card back from this value
        $highestStraight = CardFunctions::getCardsFromNumbers($hand, $highestStraightNumbers);
        //We only want the maximum straight here, so returning the first one we find is acceptable
        return $highestStraight;
      }
    }
    return FALSE;
  }

  //Will return the highest value flush from a hand
  public static function findFlush($hand) {
    $cards = CardFunctions::returnFlushCards($hand);
    if(!empty($cards)) {
      $cards = CardFunctions::orderHandNumericallyDesc($cards);
      for($i = 0; $i < 5 ; $i++){
        $highestFlush[$i] = $cards[$i];
      }
      return $highestFlush;
    }
    return FALSE;
  }

  //Full House
  public static function findFullHouse($hand) {
    $threeOfAKind = self::findThreeOfAKind($hand);
    if($threeOfAKind){//Need a three of a kind at minimum
      $remainingCards = CardFunctions::removeCardsFromHand($threeOfAKind, $hand);
      $pair = self::findHighestPair($remainingCards);
      if ($pair) {
        $cards = array_merge($threeOfAKind, $pair);
        return $cards;
      }
    }
    return FALSE;
  }

  //Four of a kind
  public static function findFourOfAKind($hand) {
    $cards = CardFunctions::returnHighestMatchingNumberCards($hand, 4);
    return $cards;
  }

  //Straight flush
  public static function findStraightFlush($hand) {
    //Note that if we pass in a 7 card hand, then we need to check all flush cards
    //not just the maximum flush, hence the use of the function below.
    $flushCards = CardFunctions::returnFlushCards($hand);
    if(!empty($flushCards)) {//We set of cards which are a flush, now check for a straight
      $straightFlushCards = HandEvaluation::findStraight($flushCards);
      if(!empty($straightFlushCards)) {
        return $straightFlushCards;
      }
    }
    return FALSE;
  }

  //Royal Flush
  public static function findRoyalFlush($hand) {
    $straightFlush = self::findStraightFlush($hand);
    if(!empty($straightFlush)){
      //If we have a straight flush and the first card in the hand is an ace, then we have a royal flush.
      $firstCardNumber = CardFunctions::getNumber($straightFlush[0]);
      if($firstCardNumber == 14){
        $royalFlush = $straightFlush;
        return $royalFlush;
      }
    }
    return FALSE;
  }



} //End of Hand Evaluation Class

class ReturnOrderedHandAndHandValue {

  public static function createHandAndHandValueArray($hand, $handValue, $name) {
    $handAndHandValue = [
      'hand' => $hand,
      'handValue' => $handValue,
      'handName' => $name,
    ];
    return $handAndHandValue;
  }

  //Once we know what hand we have, use these functions to order the cards and get the hand value.

  public static function highCard($hand) {
    $hand = CardFunctions::orderHandNumericallyDesc($hand);
    $handValue = CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'High Card');
    return $handAndHandValue;
  }

  public static function pair($hand) {
    $pair = HandEvaluation::findHighestPair($hand);
    $remainingCards = CardFunctions::removeCardsFromHand($pair, $hand);
    $remainingCards = CardFunctions::orderHandNumericallyDesc($remainingCards);
    $hand = array_merge($pair, $remainingCards);
    $handValue = 1 + CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Pair');
    return $handAndHandValue;
  }

  public static function twoPair($hand) {
    $twoPair = HandEvaluation::findTwoPair($hand);
    $remainingCard = CardFunctions::removeCardsFromHand($twoPair, $hand);
    $hand = array_merge($twoPair, $remainingCard);
    $handValue = 2 + CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Two Pair');
    return $handAndHandValue;
  }

  public static function threeOfAKind($hand) {
    $threeOfAKind = HandEvaluation::findThreeOfAKind($hand);
    $remainingCards = CardFunctions::removeCardsFromHand($threeOfAKind, $hand);
    $remainingCards = CardFunctions::orderHandNumericallyDesc($remainingCards);
    $hand = array_merge($threeOfAKind, $remainingCards);
    $handValue = 3 + CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Three of a Kind');
    return $handAndHandValue;
  }

  public static function straight($hand){
    $hand = HandEvaluation::findStraight($hand);
    $highCardOfStraight[0] = $hand[0];
    $handValue = 4 + CardFunctions::calculateDecimalHandValue($highCardOfStraight);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Straight');
    return $handAndHandValue;
  }

  public static function flush($hand) {
    $hand = HandEvaluation::findFlush($hand);
    $handValue = 5 + CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Flush');
    return $handAndHandValue;
  }

  public static function fullHouse($hand) {
    $hand = HandEvaluation::findFullHouse($hand);
    $handValue = 6 + CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Full House');
    return $handAndHandValue;
  }

  public static function fourOfAKind($hand) {
    $fourOfAKind = HandEvaluation::findFourOfAKind($hand);
    $remainingCard = CardFunctions::removeCardsFromHand($fourOfAKind, $hand);
    $hand = array_merge($fourOfAKind, $remainingCard);
    $handValue = 7 + CardFunctions::calculateDecimalHandValue($hand);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Four of a Kind');
    return $handAndHandValue;

  }

  public static function straightFlush($hand) {
    $hand = HandEvaluation::findStraightFlush($hand);
    $highCardOfStraightFlush[0] = $hand[0];
    $handValue = 8 + CardFunctions::calculateDecimalHandValue($highCardOfStraightFlush);
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Straight Flush');
    return $handAndHandValue;
  }

  public static function royalFlush($hand) {
    $hand = HandEvaluation::findroyalFlush($hand);
    $handValue = 9; //The Nuts.
    $handAndHandValue = self::createHandAndHandValueArray($hand, $handValue, 'Royal Flush');
    return $handAndHandValue;
  }


} //End of ReturnOrderedHandAndHandValue class.

class PokerSimulation {

  //Pass in a 5 card hand array, this will return what the hand is with its value.
  public static function evaluate5CardHand($hand) {
    $validHand = CardFunctions::areHandsValid($hand);
    if(!$validHand) {
      //echo "Hand invalid";
      exit(1); //Hand invalid
    }

    $pair = HandEvaluation::findHighestPair($hand);
    if($pair) {
      $twoPair = HandEvaluation::findTwoPair($hand);
      if($twoPair) {
        //Hand is a Two Pair
        //echo "Hand is a Two Pair";
        $handAndValue = ReturnOrderedHandAndHandValue::twoPair($hand);
        return $handAndValue;
      }
      else {
        $fullHouse = HandEvaluation::findFullHouse($hand);
        if($fullHouse) {
          //Hand is a Full House
          //echo "Hand is a Full House";
          $handAndValue = ReturnOrderedHandAndHandValue::fullHouse($hand);
          return $handAndValue;
        }
        else {
          //Hand is a Pair
          //echo "Hand is a Pair";
          $handAndValue = ReturnOrderedHandAndHandValue::pair($hand);
          return $handAndValue;
        }
      }
    }
    else {
      $threeOfAKind = HandEvaluation::findThreeOfAKind($hand);
      if($threeOfAKind) {
        //Hand is a three of a kind
        //echo "Hand is a three of a kind";
        $handAndValue = ReturnOrderedHandAndHandValue::threeOfAKind($hand);
        return $handAndValue;
      }
      else {
        $straight = HandEvaluation::findStraight($hand);
        if($straight) {
          $straightFlush = HandEvaluation::findStraightFlush($hand);
          if($straightFlush){
            $royalFlush = HandEvaluation::findRoyalFlush($hand);
            if($royalFlush) {
              //Hand is a Royal Flush
              //echo "Hand is a Royal Flush";
              $handAndValue = ReturnOrderedHandAndHandValue::royalFlush($hand);
              return $handAndValue;
            }
            else {
              //Hand is a Straight Flush
              //echo "Hand is a Straight Flush";
              $handAndValue = ReturnOrderedHandAndHandValue::straightFlush($hand);
              return $handAndValue;
            }
          }
          //Hand is a Straight
          //echo "Hand is a Straight";
          $handAndValue = ReturnOrderedHandAndHandValue::straight($hand);
          return $handAndValue;
        }
        else {
          $flush = HandEvaluation::findFlush($hand);
          if($flush) {
            //Hand is a Flush
            //echo "Hand is a Flush";
            $handAndValue = ReturnOrderedHandAndHandValue::flush($hand);
            return $handAndValue;
          }
          else {
            $fourOfAKind = HandEvaluation::findFourOfAKind($hand);
            if($fourOfAKind) {
              //Hand is Four of a Kind
              //echo "Hand is Four of a Kind";
              $handAndValue = ReturnOrderedHandAndHandValue::fourOfAKind($hand);
              return $handAndValue;
            }
            else {
              //Hand is high card
              //echo "Hand is high card";
              $handAndValue = ReturnOrderedHandAndHandValue::highCard($hand);
              return $handAndValue;
            }
          }
        }
      }
    }
  }

  //Pass in 5 card hands, this function will order them based on ranking.
  public static function rankHands($hands) {
    @$singleHand = !is_array($hands[0]); //True if this is a single hand
    $singleHand = !is_array(current($hands));
    if($singleHand) {
      $hands = [$hands];
    }
    foreach($hands as $handKey => $hand){
      $handAndHandValue = self::evaluate5CardHand($hand);
      $handsInfo[] = [
        'handRank' => '',
        'hand' => $handAndHandValue['hand'],
        'handValue' => $handAndHandValue['handValue'],
        'handName' => $handAndHandValue['handName'],
        'originalHandKey' => $handKey,
      ];
      $handValues[] = $handAndHandValue['handValue']; //Create an array of hand values
    }
    if($singleHand){//In the case of 1 hand, we do not need to do any ordering, so return the hands info array now.
      return $handsInfo;
    }
    $handValues = array_unique($handValues); //We must eliminate duplicate hand values otherwise it will mess with our loop later.
    arsort($handValues); //Now sort the values in descending order, i.e., strongest to weakest.
    $descendingHandValues = $handValues;
    $handRank = 1; //Set the rank counter
    //For every hand value, lets fetch the corresponding hand info, this time in descending order.
    foreach($descendingHandValues as $handValue){
      //Loop through the hands info array to find the corresponding hand info of given hand value.
      foreach($handsInfo as $handInfo){
        if($handValue == $handInfo['handValue']) {
          $orderedHandsInfo[] = [
            'handRank' => $handRank,
            'hand' => $handInfo['hand'],
            'handValue' => $handInfo['handValue'],
            'handName' => $handInfo['handName'],
            'originalHandKey' => $handInfo['originalHandKey'],
          ];
        } //If the same value appears more than once - i.e., split hand - this will give the same hand rank.
      }
      $handRank++;
    }
    //Deal with split hands
    $i = 0;
    while($orderedHandsInfo[$i]['handRank'] == @$orderedHandsInfo[($i + 1)]['handRank']){
      $i++;
    }
    $split = $i;
    $orderedHandsInfo['split'] = $split;
    return $orderedHandsInfo;
  }

  //Pass in two cards in your hand, and other cards which will be ignored, then this function will return
  //all other possible card two card combinations. This all the combinations of cards your opponents could possibly have.
  public static function getAllOtherPossibleTwoCardCombinations($twoCards, $otherCards) {//Todo test this properly
    //Give it an un shuffled deck array
    $allCards = DeckFunctions::createUnshuffledDeck();
    //Eliminate the cards which cannot be used.
    $cardsToRemoveFromDeck = array_merge($twoCards, $otherCards);
    foreach($cardsToRemoveFromDeck as $card){
      $deckKey = array_search($card, $allCards);
      unset($allCards[$deckKey]);
    }
    $remainingCards = array_values($allCards); //Reset array keys
    //Start at 1st card in the remaining cards array
    //Loop through every other remaining card and create arrays where the first entry is the 1st card
    //and the second entry is a different, unused card, until all the possible two card combinations including the first card are exhausted.
    //Then increment the counter and start at the next card. Repeat until all possible other two card combinations have been discovered.
    $noOfRemainingCards = count($remainingCards);
    for($i = 0; $i < $noOfRemainingCards; $i++){
      for($j = 0; $j < $noOfRemainingCards - $i; $j++){
        $twoCardCombinations[] = [$remainingCards[$i], $remainingCards[($i + $j)]];
      }
    }
    return $twoCardCombinations;
  }

  //Pass in an array to $base, $n is the size of the combination required.
  public static function getCombinations($base, $n){
//Function by Kemal Dağ - http://stackoverflow.com/questions/4279722/php-recursion-to-get-all-possibilities-of-strings/8880362#8880362
    $baselen = count($base);
    if($baselen == 0){
      return;
    }
    if($n == 1) {
      $return = array();
      foreach($base as $b){
        $return[] = array($b);
      }
      return $return;
    }
    else {
      //get one level lower combinations
      $oneLevelLower = self::getCombinations($base, $n-1);
      //for every one level lower combinations add one element to them that the last element of a combination is preceeded by the element which follows it in base array if there is none, does not add
      $newCombs = array();

      foreach($oneLevelLower as $oll){
        $lastEl = $oll[$n-2];
        $found = false;
        foreach($base as  $key => $b){
          if($b == $lastEl){
            $found = true;
            continue;
            //last element found
          }
          if($found == true){
            //add to combinations with last element
            if($key < $baselen){
              $tmp = $oll;
              $newCombination = array_slice($tmp,0);
              $newCombination[] = $b;
              $newCombs[] = array_slice($newCombination,0);
            }
          }
        }
      }
    }
    return $newCombs;
  }

  //Pass in an array of cards, return an array with every 5 card combination
  public static function findHandCombinations($cards){
    $combinations = self::getCombinations($cards, 5);
    return $combinations;
  }

  //Pass in an array of hand ranking values
  //e.g. ['bob' => 5.14, 'alice' => 5.14, 'steve' => 3, 'david' => 2.07, 'mike' => 2]
  //and return an ordered position array
  //[1 => ['bob' => 5.14, 'alice' => 5.14], 2 => ['steve' => 3], 3 => ['david' => 2.07], 4 => ['mike' => 2]]
  public static function orderArrayByHandValue($maxPlayerHandValues){
    arsort($maxPlayerHandValues);
    $handValuePrevious = 0;
    $position = 0;
    foreach($maxPlayerHandValues as $player => $handValue){
      if($handValue == $handValuePrevious) {
        $position--;
      }
      $position++;
      $handValuePrevious = $handValue;
      $orderedPlayerHandValues[$position][$player] = $handValue;
    }
    return $orderedPlayerHandValues;
  }

  //Input an array of two player cards and the shared cards, this function will return an array
  //ordered by position revealing
  public static function rankPlayersHands($playersTwoCards, $sharedCards){
    //Firstly validate the cards being passed into the function.
    //For each player, fetch the array of every possible hand combination.
    //Pass that array into the rank hands function, this will find the max player hand value.
    //With each players max hand ranking, we can order each player against each other.
    //Use the order array by hand value function

    $allCards = $sharedCards;
    foreach($playersTwoCards as $playerTwoCards){
      $allCards = array_merge($playerTwoCards, $allCards);
    }
    $valid = CardFunctions::areHandsValid($allCards, 0);
    if(!$valid) {
      return ['status' => 'Invalid Cards'];
    }

    foreach($playersTwoCards as $playerKey => $playerTwoCards){
      $availableCards = array_merge($playerTwoCards, $sharedCards);
      $availableCardCombinations = self::findHandCombinations($availableCards);
      $handRankings = self::rankHands($availableCardCombinations);
      //Todo, there can be a split at this point. BUT do we need to account for them here?
      $playersMaxHandValue[$playerKey] = $handRankings[0]['handValue'];
    }
    $orderedPlayerHandValues = self::orderArrayByHandValue($playersMaxHandValue);
    return $orderedPlayerHandValues;
  }

  //Pass in the player's cards, the total no. of players and the iterations you wish to run,
  //this function will return stats based on the chance of the player winning with that hand preflop (i.e., no shown cards).
  public static function simulatePreFlopSinglePlayerCards($playerCards, $noOfPlayers, $iterations=1){
    //It works by randomly dealing the other players and river. For each deal, we'll assess
    //where the player's hand would have ranked. Either win, split win, or lose.
    //Repeat this for the number of iterations
    list($win, $split, $lose) = [0, 0, 0];

    $unshuffledDeck = DeckFunctions::createUnshuffledDeck();
    $remainingDeck = DeckFunctions::removeCardsFromDeck($playerCards, $unshuffledDeck);

    for($i = 0; $i < $iterations; $i++){
      //We only need to deal for the remaining players, i.e., total no. of players - 1.
      $dealtCards = DeckFunctions::deal(($noOfPlayers - 1), $remainingDeck);
      $allPlayersCards = array_merge(['player' => $playerCards], $dealtCards['players']);
      $rankings = self::rankPlayersHands($allPlayersCards, $dealtCards['river']);
      print_r($rankings);
      $winner = $rankings[1];
      if(array_key_exists('player', $winner)) {
        if(count($winner) > 1){
          $split++;
        }
        else {
          $win++;
        }
      }
      else {
        $lose++;
      }
    }
    $winPercent = $win/($win + $split + $lose);
    $splitPercent = $split/($win + $split + $lose);

    $stats = [
      'win' => $win,
      'splitWin' => $split,
      'lose' => $lose,
      'winPercent' => $winPercent,
      'splitPercent' => $splitPercent,
    ];
    return $stats;
  }

  //TODO This function needs to:
  // * pass in a multi-dimensional array of player cards
  // * Pass in any shown cards (from here we need to simulate the remaining cards)
  // * Return an array of the same dimension as the original player card array, but also with all the stats from the above func.
  //Main loop is each iteration
  //Then loop through each player card and rank accordingly
  //Find a way to robustly determine which player hand returns the best score.abstract

  //An adaptation of the above function, pass in an array of player cards, and optionally any shown cards.
  //Player card array must include an index even if blank, e.g., [['Ks', '5h']] FIXME??
  //The total number of players, if left undefined will default to number of players in the hand.
  //Will out put an array for each player with their win %, split %
  /**
   * @param $playersCards
   * @param bool $shownCards
   * @param bool $noOfPlayers
   * @param int $iterations
   * @return mixed
   */
  public static function calculateOdds($playersCards, $shownCards = FALSE, $noOfPlayers = FALSE, $iterations = 1) {

    $noOfPlayersPassed = count($playersCards);

    if(!$noOfPlayers) { //If the number of players is undefined, then we'll only evaluate the hands we've got
      $remainingPlayers = 0;
    } elseif($noOfPlayers < $noOfPlayersPassed) {
      return ['status' => 'Fewer number of players defined than those with hands'];
    } else {
      $remainingPlayers = $noOfPlayers - $noOfPlayersPassed;
    }

    //Find the remaining available cards
    $shownCards = $shownCards ? $shownCards : [];
    $noOfShownCards = count($shownCards);
    $cardsInPlay = $shownCards; //Initialise the array
    foreach($playersCards as $playerCards){
      $cardsInPlay = array_merge($cardsInPlay, $playerCards);
    }
    $unshuffledDeck = DeckFunctions::createUnshuffledDeck();
    $remainingDeck = DeckFunctions::removeCardsFromDeck($cardsInPlay, $unshuffledDeck);

    //Initialise the player stats array
    foreach($playersCards as $player => $cards){
      $playerStats[$player] = [
        'win' => 0,
        'split' => 0,
        'lose' => 0,
      ];
    }

    for($i = 0; $i < $iterations; $i++){

      //We only need to deal for the remaining players, i.e., total no. of players - 1.
      $dealtCards = DeckFunctions::deal($remainingPlayers, $remainingDeck); //Empty players array if blank

      // Prepare the shown cards in play array, take cards from the deal up until the necessary point
      $shownCardsInPlay = [];
      for($j = 0; $j < 5-$noOfShownCards; $j++) {
        $shownCardsInPlay[] = $dealtCards['river'][$j];
      }
      $shownCardsInPlay = array_merge($shownCardsInPlay, $shownCards);

      //If we have any extra players we must include them in all players cards, otherwise $allPlayersCards is all we need.
      if(isset($dealtCards['players'])) {
        $allPlayersCards = array_merge($playersCards, $dealtCards['players']); //Here we're going to match up the paired indices
      }
      else {
        $allPlayersCards = $playersCards;
      }

      $rankings = self::rankPlayersHands($allPlayersCards, $shownCardsInPlay);
      unset($shownCardsInPlay);
      $winner = $rankings[1];
      /*echo 'rankings ';
      print_r($rankings);
      foreach($winner as $winningPlayer => $rank){
        //Foreach again through the players as we don't care about the stats for the dealt cards
        //i.e., at this point we are disregarding non passed players
        foreach($playersCards as $player => $cards){
          if($winningPlayer === $player){
            $winningPlayers[] = $player;
          }
        }
      }
      echo 'winning player ';
      @print_r($winningPlayers);*/

      foreach($playersCards as $player => $cards){
        if(array_key_exists($player, $winner)) {
          if(count($winner) > 1){
            $playerStats[$player]['split']++;
          }
          else {
            $playerStats[$player]['win']++;
          }
        }
        else {
          $playerStats[$player]['lose']++;
        }

      }

      //Now we identified which players in our set of players have won.
      //Let's calculate the stats.
      //If count(winnings players) > 2, then there's a split between our players

      //If count(winning players) = 1, we have a winning player, everyone else loses

      //If count(winning players) = 0, all our players have lost.



      //At this point we need to know the size of the winning array, and what array key(s) won
      //If the array key value is the number or players or higher, we know the dealt players won
      //Otherwise, one of our players won, and the rest of the players need their stats to be updated


    }

    //Update the player stats array to include percentages
    foreach($playersCards as $player => $cards){
      $playerStats[$player]['winPercent'] = $playerStats[$player]['win']/($playerStats[$player]['win'] + $playerStats[$player]['split'] + $playerStats[$player]['lose']);
      $playerStats[$player]['splitPercent'] = $playerStats[$player]['split']/($playerStats[$player]['win'] + $playerStats[$player]['split'] + $playerStats[$player]['lose']);
    }
    return $playerStats;
  }


} //End of Poker Simulation Class

class TestingFunctions {

  public static function checkDealDistribution($noOfIterations){
    $start = microtime_float();
    $river = [];
    for($i = 0; $i < $noOfIterations; $i++) {
      $dealtCards = DeckFunctions::deal(4);
      $riverNew = $dealtCards['river'];
      $river = array_merge($river, $riverNew);
    }
    $distribution = array_count_values($river);
    /*foreach($distribution as $card => $count){

    }*/
    $sum = array_sum($distribution);
    $avg = $sum/52;
    $end = self::microtime_float();
    $time = $end - $start;
    list($distribution['sum'], $distribution['avg'], $distribution['time']) = [$sum, $avg, $time];
    return $distribution;
  }

  public static function preFlopSimulation($playerCards, $noOfPlayers, $sampleSize = 4){
    for($i = 1; $i <= $sampleSize; $i++){
      $iterations = pow(10, $i);
      $iterations = 500 + $i;
      $start = self::microtime_float();
      $results = PokerSimulation::simulatePreFlopSinglePlayerCards($playerCards, $noOfPlayers, $iterations);
      $end = self::microtime_float();
      $time = $end - $start;
      $timePerI = $time / $iterations;
      $simulation[$iterations] = ['winPercent' => $results['winPercent'], 'splitPercent' => $results['splitPercent'], 'time' => $time, 'timePerI' => $timePerI];
    }

    foreach($simulation as $test) {
      $winPercents[] = $test['winPercent'];
      $splitPercents[] = $test['splitPercent'];
    }
    $simulation['stats'] = [
      'winPercentMean' => StatisticsFunctions::mean($winPercents),
      'winPercentSd' => StatisticsFunctions::sd($winPercents),
    ];

    return $simulation;
  }

  public static function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }

}

class StatisticsFunctions{

  //Find the mean value
  public static function mean($x) {
    return array_sum($x)/count($x);
  }

  // Function to calculate square of value - mean
  public static function sd_square($x, $mean) {
    return pow($x - $mean, 2);
  }

  // Function to calculate standard deviation (uses sd_square)
  public static function sd($array) {
    // square root of sum of squares divided by N-1
    return sqrt(array_sum(array_map(["StatisticsFunctions", "sd_square"], $array, array_fill(0, count($array), self::mean($array)))) / (count($array)-1));
  }

} //End of statistics class

//Loop through every combination of hands with the current hand and sort the best hand


#$sharedCards = ['Qs','Kh', '7c', '5c'  ];
#$playerCards = ['Luke' => ['6h', '6s'],  'steve' => ['Ks', '5d'], 'alice' =>['Kd', '5s'],];
#$playerCards = [ 'alice' =>['Qd', '2s'],];
#print_r(PokerSimulation::rankPlayersHands($playerCards, $sharedCards));
#print_r(PokerSimulation::simulatePreFlopSinglePlayerCards(['Ah', 'As'], 5, 50));
#print_r(TestingFunctions::preFlopSimulation(['Kh', '5s'], 6));

#$hands = [['Qd', '2s', 'Qs', 'Kh', '7c' ], ['Qh', 'Ad', 'Qd', 'Ks', '7d' ]];
#$hand = ['10d', '4s', '3s', '10h', '7c' ];
#print_r(PokerSimulation::rankHands($hands));





#print_r(checkDealDistribution(400));


#simulatePlayerCards(['Ks', '5h'], 3);

class UnusedFunctions {


  //This got completely out of hand!! It is not far from finished, but the used 'getCombinations' solution is far more elegant!
  public static function findHandCombinations($playerCards, $shownCards){//Maybe just accept hand array...
    $availableCards = array_merge($playerCards, $shownCards);
    $availableCards = array_values($availableCards); //Todo may not need to do this.
    $totalNoOfCards = count($availableCards);
    //Get first case of first 5 cards.
    for($i = 0; $i < 5; $i++){
      $handCombinations[0][] = $availableCards[$i];
    }
//More elegant solution http://stackoverflow.com/questions/3742506/php-array-combinations


    $noOfCardCountIterations = $totalNoOfCards - 5;
    for($z = 0; $z < $noOfCardCountIterations; $z++){
      if($z = 0) {
        for($i = 0; $i < $totalNoOfCards - 5; $i++){ //This represents the 6 & 7.
          for($j = 0; $j < 5; $j++){ //This is looping through the set //TODO this needs to be more generic.
            $combinationCounter = $i + $j + 1;
            #echo "i: {$i}, j: {$j}, permcalcu {$combinationCounter} \n";
            $handCombinations[$combinationCounter] = $handCombinations[0];
            unset($handCombinations[$combinationCounter][(5 - ($j + 1))]);
            $handCombinations[$combinationCounter][(5 + $i)] = $availableCards[(5 + $i)];
            print_r($handCombinations[$combinationCounter]);
            $unsetKey = (5 - ($j + 1));
            $movingCard = (5 + $i);
            echo "i: {$i}, \n j: {$j}, \n permcount: {$combinationCounter}, \n unset key {$unsetKey}, \n moving key {$movingCard} \n \n";
            //TODO sort combination counter

          }
        }
      }
      elseif($z = 1) { //Combinations where two cards are moved
        $combinationNOO = 11;

        $diff = 1; //Difference between the first starting point and the second.
        //update array permutate from before + $k mod 5 (where 5 is the size of the loop)
        for($noOfIterationsRequired = 4; $noOfIterationsRequired > 0; $noOfIterationsRequired--) { //
          $zz=0;
          echo "no of its required.... {$noOfIterationsRequired} \n";
          for ($h = 0; $h < 2; $h++) {//This is for the 6 & 7.
            $targetKey = 5 + $h; //The array key we will be moving a value to
            //for ($k = 0; $k < 4; $k++) {//To get starting point
            //$startingKey = 4 - ($k + 1);
            for($m = 0; $m < $noOfIterationsRequired; $m++){//Loop from the starting point to the end of the iterations required, this will decrease.
              $startingKey = $noOfIterationsRequired - $m - 1 + ($h * $diff); //When
              //TODO move starting key to target key.
              $mod = $zz%$noOfIterationsRequired;
              $combinationNew = $combinationNOO + $mod;

              echo "deep loop:  {$startingKey} => {$targetKey}, h: {$h}, diff: {$diff}, z: {$zz}, zmod IT: {$mod}, permunew {$combinationNew}, \n";
              $zz++;
            }
            //}
          }
          $combinationNOO = $combinationNew + 1;
          $diff++;
        }
      }
      else {
        //This function is not generalised for more cases!!
        return FALSE;
      }
    }
    $noOfCombinations = count($handCombinations);
    echo "no of combinations {$noOfCombinations}";
    return $handCombinations;
  }


}