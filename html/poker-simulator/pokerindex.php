<!--
TODO:
 * Make JS tidy
 * Sidebar
 *  cohu1p

-->


<!DOCTYPE html>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script>

    var coI = 100;

    var playersCards = {
      p1:['c1', 'c2'],
      p2:['c1', 'c2'],
      p3:['c1', 'c2'],
      p4:['c1', 'c2'],
      p5:['c1', 'c2'],
      p6:['c1', 'c2'],
      p7:['c1', 'c2'],
      p8:['c1', 'c2']
    };

    // Pass in an array of cards, this will ensure they are unique and will return an error if not.
    function validateCards(cards) {

    }


    function hu1pc0() {
      var e = document.getElementById("hu1pc0c1"); //This can be simplified with jquery
      var card1 = e.options[e.selectedIndex].value;
      var e = document.getElementById("hu1pc0c2");
      var card2 = e.options[e.selectedIndex].value;

      var playerCardLength = card1.length + card2.length;
      console.log('card1:' + card1 + ', card2:' + card2);
      console.log(playerCardLength);
      if(playerCardLength >= 4) {
        if (card1 == card2) {
          console.log('cards dont differ');
          document.getElementById("jsresponse").innerHTML = "Please pick unique cards";
          return
        }
        console.log('cards differ');
        document.getElementById("jsresponse").innerHTML = "Two cards selected";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("jsonresponse").innerHTML = xmlhttp.responseText;
          }
        };
        var params = "t=co&p=2&i=100&p1c1="+card1+"&p1c2="+card2;
        xmlhttp.open("GET", "../scripts/poker-simulator/poker_simulator_interface.php?" + params, true);
        xmlhttp.send();

      }
    }

    function callPokerSim(urlParams) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          return xmlhttp.responseText;
        }
      };
      xmlhttp.open("GET", "../scripts/poker-simulator/poker_simulator_interface.php?" + urlParams, true);
      xmlhttp.send();
    }

    function cohu2pc0() {
      var card1 = $( "#zhu1pc0c1" ).val();
      var card2 = $( "#zhu1pc0c2" ).val();
      //var params = "?t=co&p=2&i=100&p1c1="+card1+"&p1c2="+card2;
      var params = {
        "t":"co",
        "p":2,
        "i":coI,
        "p1c1":card1,
        "p1c2":card2
      };
      console.log(params);

      $.ajax({
        type: 'POST',
        url: '../scripts/poker-simulator/poker_simulator_interface.php',
        data: {
          "t":"co",
          "p":2,
          "i":coI,
          "p1c1":card1,
          "p1c2":card2
        },
        dataType: 'json',
        success: function (data) {
          console.log(data);
          $('#zhu1pc0response').text(data[0]);
        }
      });

      /*$("#zhu1pc0response").load(pokerSimURL, params, function( response, status, xhr ) {
       console.log('response gone');
       if ( status == "error" ) {
       var msg = "Sorry but there was an error: ";
       $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
       }
       });*/
    }

    // calculateodds, players=2, player cards=1, community cards=0
    //
    function cop2pc1cc0() {
      //Get the card values
      var card1 = $( "#cop2pc1cc0c1" ).val();
      var card2 = $( "#cop2pc1cc0c2" ).val();

      //TODO validate cards before request

      $.ajax({
        type: 'POST',
        url: '../scripts/poker-simulator/poker_simulator_interface.php',
        data: {
          "t":"co",
          "p":2,
          "i":coI,
          "p1c1":card1,
          "p1c2":card2
        },
        dataType: 'json',
        success: function (data) {
          console.log(data);
          $('#cop2pc1cc0response').text(JSON.stringify(data));
        }
      });

    }



    function cop2pc1cc0ChartFromJSON(json) {



    }

    function validateUniqueCards(cards) {
      var counts = {};
      for(var i = 0; i< cards.length; i++) {
        var num = cards[i];
        if (num == null || 0 === num.length) { continue; }
        counts[num] = counts[num] ? counts[num]+1 : 1;
        if (counts[num] > 1) {
          return false;
        }
      }
      return true;
    }

    function isEmpty(str) {
      return (!str || 0 === str.length);
    }

    function validateCommunityCardsArray(ccArray) {
      var cardsAtPreFlop = isEmpty(ccArray[0]) && isEmpty(ccArray[1]) && isEmpty(ccArray[2]) && isEmpty(ccArray[3]) && isEmpty(ccArray[4]);
      var cardsAtFlop    = !isEmpty(ccArray[0]) && !isEmpty(ccArray[1]) && !isEmpty(ccArray[2]) && isEmpty(ccArray[3]) && isEmpty(ccArray[4]);
      var cardsAtTurn    = !isEmpty(ccArray[0]) && !isEmpty(ccArray[1]) && !isEmpty(ccArray[2]) && !isEmpty(ccArray[3]) && isEmpty(ccArray[4]);
      var cardsAtRiver   = !isEmpty(ccArray[0]) && !isEmpty(ccArray[1]) && !isEmpty(ccArray[2]) && !isEmpty(ccArray[3]) && !isEmpty(ccArray[4]);
      return (cardsAtPreFlop || cardsAtFlop || cardsAtTurn || cardsAtRiver);
    }

    function validateCommunityCards(ccObj) {
      var cardsAtPreFlop =  isEmpty(ccObj["cc1"]) &&  isEmpty(ccObj["cc2"]) &&  isEmpty(ccObj["cc3"]) &&  isEmpty(ccObj["cc4"]) &&  isEmpty(ccObj["cc5"]);
      var cardsAtFlop    = !isEmpty(ccObj["cc1"]) && !isEmpty(ccObj["cc2"]) && !isEmpty(ccObj["cc3"]) &&  isEmpty(ccObj["cc4"]) &&  isEmpty(ccObj["cc5"]);
      var cardsAtTurn    = !isEmpty(ccObj["cc1"]) && !isEmpty(ccObj["cc2"]) && !isEmpty(ccObj["cc3"]) && !isEmpty(ccObj["cc4"]) &&  isEmpty(ccObj["cc5"]);
      var cardsAtRiver   = !isEmpty(ccObj["cc1"]) && !isEmpty(ccObj["cc2"]) && !isEmpty(ccObj["cc3"]) && !isEmpty(ccObj["cc4"]) && !isEmpty(ccObj["cc5"]);
      return (cardsAtPreFlop || cardsAtFlop || cardsAtTurn || cardsAtRiver);
    }

    // calculateodds, players=n, player cards=1, community cards=n

    function copnp1ccnNEW() {
/*      //Get the card values
      var p1c1 = $("#copnp1c1").val();
      var p1c2 = $("#copnp1c2").val();
      var p1c = [p1c1, p1c2];*/
      var p1c = [$("#copnp1c1").val(), $("#copnp1c2").val()];
      var p1c = [p1c1, p1c2];
      // Community cards
      var cc1 = $("#copnp1cc1").val();
      var cc2 = $("#copnp1cc2").val();
      var cc3 = $("#copnp1cc3").val();
      var cc4 = $("#copnp1cc4").val();
      var cc5 = $("#copnp1cc5").val();
      var communityCards = [cc1, cc2, cc3, cc4, cc5];
      // Get the no. of players
      var p = $( "#copnp1ccnp" ).val();

    }

    function copnp1ccn() {
      //Get the card values
      var p1c1 = $( "#copnp1c1" ).val();
      var p1c2 = $( "#copnp1c2" ).val();
      var p1c = [p1c1, p1c2];
      // Community cards
      var cc1 = $( "#copnp1cc1" ).val();
      var cc2 = $( "#copnp1cc2" ).val();
      var cc3 = $( "#copnp1cc3" ).val();
      var cc4 = $( "#copnp1cc4" ).val();
      var cc5 = $( "#copnp1cc5" ).val();
      var communityCards = [cc1, cc2, cc3, cc4, cc5];
      // Validate player cards set
      if (isEmpty(p1c1) ||isEmpty(p1c2)) {
        console.log('Player cards must be set');
        return;
      }
      //Validate correct no. of cards select
      var ccCardsValid = validateCommunityCardsArray(communityCards);
      console.log(ccCardsValid);
      if (!(ccCardsValid)) {
        console.log('Community cards not valid');
        return;
        //TODO RETURN MESSAGE
      }
      // Validate uniqueness
      var allCards = communityCards.concat(p1c);
      if(!validateUniqueCards(allCards)) {
        console.log('Cards must be unique');
        return;
        //TODO RETURN MESSAGE
      }
      // Get the no. of players
      var p = $( "#copnp1ccnp" ).val();


      // Prepare the params
      var data = {
        t:"co",
        p:p,
        i:i,
        p1c1:p1c1,
        p1c2:p1c2,
        cc1:cc1,
        cc2:cc2,
        cc3:cc3,
        cc4:cc4,
        cc5:cc5
      };

      $.ajax({
        type: 'POST',
        url: '../scripts/poker-simulator/poker_simulator_interface.php',
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log(data);
          $('#copnp1ccnresponse').text(JSON.stringify(data));
        }
      });
    }

    function createPlayersCardsArray(commonDivId) {

      var playersCards = {};
      if ($('#elementId').length > 0) {
        // exists.
      }




      var p1c1 = $( "#copnp1c1" ).val();
      var p1c2 = $( "#copnp1c2" ).val();

    }

    function copnpnccn() {
      // Fetch an ensure valid no. of players cards are selected
      var playersCards = {};
      var cardsSelected = true;

      for (i = 1; i <= 9; i++) {
        for (j = 1; j <=2; j++) {
          var playerCardIndex = "p" + i + "c" + j;
          var divId = "copn" + playerCardIndex + "ccn";
          if (copnpnccnCardState[divId] || i == 1) {
            // Ensure the cards are not sat out, otherwise don't add them to the players cards obj
            // Don't do the check on the first set of cards, these are mandatory.
            playersCards[playerCardIndex] = $( "#" + divId ).val();
          }
        }
        var currentPlayerCards = playersCards["p" + i + "c1"] + playersCards["p" + i + "c2"];
        if (currentPlayerCards.length != 0 && currentPlayerCards.length < 4) {
          // Two cards not selected here
          cardsSelected = false;
          console.log("player " + i + " needs all cards selected");
        }
      }
      if (!cardsSelected) {
        console.log('All players must have cards selected');
        return;
        //TODO message
      }

      // P1 must have cards set to continue
      if (isEmpty(playersCards["p1c1"]) && isEmpty(playersCards["p1c2"])) {
        console.log('Player one must have cards selected');
        return;
        //TODO message
      }

      // Fetch and ensure the correct no. of community cards are select
      var communityCards = {};
      for (i = 1; i <=5; i++) {
        var communityCardIndex = "cc" + i;
        divId = "copnpn" + communityCardIndex;
        communityCards[communityCardIndex] = $( "#" + divId ).val();
      }
      console.log(communityCards);
      var ccValid = validateCommunityCards(communityCards);
      if (!ccValid) {
        console.log('Community cards not valid');
        return;
        //TODO RETURN MESSAGE
      }

      // Validate all cards for uniqueness
      var allCardsObj = $.extend({}, playersCards, communityCards);
      var allCardsArray = $.map(allCardsObj, function(value, index) { // Converts object to array
        return [value];
      });
      if (!validateUniqueCards(allCardsArray)) {
        // Cards not unique
        console.log('Cards must be unique');
        return;
        //TODO return message
      }

      // Now get the no of players
      var p = Object.keys(playersCards).length/2;
      if (p < 2) {
        console.log('There must be at least 2 players');
        return;
      }

      // Prepare the params
      var data = {
        t:"co",
        p:p,
        i:coI
      };

      $.extend(data, playersCards, communityCards);
      console.log('data');
      console.log(data)

      $.ajax({
        type: 'POST',
        url: '../scripts/poker-simulator/poker_simulator_interface.php',
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log(data);
          $('#copnpnccnresponse').text(JSON.stringify(data));
        }
      });

    }


    function calculateOdds(noOfPlayers, playersCards, communityCards) {
      var p1c1 = playersCards["p1c1"];
      var p1c2 = playersCards["p1c2"];




      $.ajax({
        type: 'POST',
        url: '../scripts/poker-simulator/poker_simulator_interface.php',
        data: data,
        dataType: 'json',
        success: function (data) {
          console.log(data);
          $('#copnp1ccnresponse').text(JSON.stringify(data));
        }
      });
    }

    //calculateodds, players=n, player cards=n, community cards=n

    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Introduction_to_Object-Oriented_JavaScript

    // Just blanks all the cards
    function resetcopnp1ccn() {
      var divs = ["copnp1c1", "copnp1c2", "copnp1cc1", "copnp1cc2", "copnp1cc3", "copnp1cc4", "copnp1cc5"];
      resetDivs(divs);
    }

    // Reloads the entire copnpnccn div
    function resetcopnpnccn() {
      $('#copnpnccn').load('pokerindex.php' +  ' #copnpnccn');
    }

    /**
     * Pass in a div id or an array of div ids, this will set the value to null
     * @param divs
     */
    function resetDivs(divs) {
      if (!Array.isArray(divs)) {
        var divs = [divs];
      }
      for(var i = 0; i< divs.length; i++) {
        var selector = "#" + divs[i];
        $(selector + " option:eq(1)").prop('selected', true);
      }
    }

    function disableDivs(divs) {
      if (!Array.isArray(divs)) {
        var divs = [divs];
      }
      for(var i = 0; i< divs.length; i++) {
        var selector = "#" + divs[i];
        $(selector).attr("disabled", true);
      }
    }

    function enableDivs(divs) {
      if (!Array.isArray(divs)) {
        var divs = [divs];
      }
      for(var i = 0; i< divs.length; i++) {
        var selector = "#" + divs[i];
        $(selector).attr("disabled", false);
      }
    }

    // Initialise the variable out of the scope of the function
    var copnpnccnCardState = {};

    /**
     * On click of the button will disable/enable the cards.
     * @param buttonDiv string of button div id
     * @param cardDivs array of card div ids
     */
    function changePlayerState(buttonDiv, cardDivs) {
     // var cardDivs = [cardDivs[0].replace('ccn', 'c1ccn'), cardDivs[1].replace('ccn', 'c2ccn')];
      var selector = "#" + buttonDiv;
      var newValue = "";
      var state = $(selector).text();
      if (state == 'Add Player') {
        enableDivs(cardDivs);
        newValue = "Sit Out";
        copnpnccnCardState[cardDivs[0]] = true;
        copnpnccnCardState[cardDivs[1]] = true;
      }
      else {
        resetDivs(cardDivs);
        disableDivs(cardDivs);
        newValue = "Add Player";
        copnpnccnCardState[cardDivs[0]] = false;
        copnpnccnCardState[cardDivs[1]] = false;
      }
      $(selector).text(newValue)
      copnpnccn();
    }






  </script>
</head>
<body>

<!--
<p><b>Heads Up Simulator (No Opponent)</b></p>
<form>
    Enter your cards pre flop: <input type="text" onkeyup="showHint(this.value)">
</form>
<p>Chance of winning: <span id="txtHint"></span></p>

<p><b>Heads Up Simulator (1 Opponent)</b></p>
<form>
    Enter your cards pre flop: <input type="text" onkeyup="showHint(this.value)">
</form>
<p>Chance of winning: <span id="txtHint"></span></p>
-->
<h1>Poker Sim</h1>

<h2>calculateodds, players=2, player cards=1, community cards=0</h2>
<?php
include "../scripts/poker-simulator/generate_card_html.php";
echo generateCardHTML('cop2pc1cc0c1', 'cop2pc1cc0');
echo generateCardHTML('cop2pc1cc0c2', 'cop2pc1cc0');
?>
<p>cop2pc1cc0response: <span id="cop2pc1cc0response"></span></p>


<h2>calculateodds, players=n, player cards=1, community cards=n</h2>
<?php
//include "../scripts/poker-simulator/generate_card_html.php";
// P1 Cards
echo generateCardHTML('copnp1c1', 'copnp1ccn');
echo generateCardHTML('copnp1c2', 'copnp1ccn');
// Community Cards
echo generateCardHTML('copnp1cc1', 'copnp1ccn');
echo generateCardHTML('copnp1cc2', 'copnp1ccn');
echo generateCardHTML('copnp1cc3', 'copnp1ccn');
echo generateCardHTML('copnp1cc4', 'copnp1ccn');
echo generateCardHTML('copnp1cc5', 'copnp1ccn');
// No of Players
echo generateNoOfPlayersHTML('copnp1ccnp', 'copnp1ccn');
?>
<button id="resetcopnp1ccn" type="button" onclick="resetcopnp1ccn()">Reset</button>

<p>copnp1ccnresponse: <span id="copnp1ccnresponse"></span></p>


<h2>calculateodds, players=n, player cards=n, community cards=n</h2>
<!--copnpnccn-->
<div id="copnpnccn">
  <?php
  //include "../scripts/poker-simulator/generate_card_html.php";
  // P1 Cards
  echo generateCardHTML('copnp1c1ccn', 'copnpnccn');
  echo generateCardHTML('copnp1c2ccn', 'copnpnccn');
  // Community Cards
  echo generateCardHTML('copnpncc1', 'copnpnccn');
  echo generateCardHTML('copnpncc2', 'copnpnccn');
  echo generateCardHTML('copnpncc3', 'copnpnccn');
  echo generateCardHTML('copnpncc4', 'copnpnccn');
  echo generateCardHTML('copnpncc5', 'copnpnccn');
  echo '<br>';

  // Create the card dropdowns and buttons for players p2<=
  for($i=2; $i<=9; $i++) {
    $pcDivIds = [];
    for($j = 1; $j <=2; $j++) {
      $divId = "copnp{$i}c{$j}ccn";
      echo generateCardHTML($divId, 'copnpnccn', 'disabled="true"');
      $pcDivIds[] = $divId;
    }
    $pcDivIds = '["' . implode('" , "', $pcDivIds) . '"]';
    $emptySeatDivId = "disablecopnp{$i}ccn";
    echo "<button id='{$emptySeatDivId}' type='button' onclick='changePlayerState(\"{$emptySeatDivId}\", {$pcDivIds})'>Add Player</button>";
    echo "<button id='resetcopnp{$i}ccn' type='button' onclick='resetDivs({$pcDivIds})'>Reset</button>";
    unset ($pcDivIds);
    echo "Player $i <br>";

  }
  ?>
  <p>copnpnccnresponse: <span id="copnpnccnresponse"></span></p>

  <button id="resetcopnpnccn" type="button" onclick="resetcopnpnccn()">Reset</button>


</div>

</body>
</html>
