<!--
TODO:
 * Make JS tidy
 * Sidebar
 *  cohu1p
 http://www.chartjs.org/docs/#getting-started-include-chart.js
-->


<!DOCTYPE html>
<!-- Last commit: $Id$ -->
<!-- Version Location: $HeadURL$ -->
<html>
<head>
  <title>Poker Simulator</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.1.1/Chart.min.js"></script>

  <!-- BOOTSTRAP SELECT  https://silviomoreto.github.io/bootstrap-select/examples/ -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

  <style>
    body {
      position: relative;
    }
    #section1 {
      background-color: #1E88E5;
    }
    #section2 {
      background-color: #9921bb;
    }
    #section3 {
      background-color: #ff9800;
    }
    #about    {padding-top:50px;height:500px;color: #fff; background-color: #009688;}

    .mainSection {
      height: auto;
      color: #fff;
      padding-bottom: 50px;
      padding-top: 30px;
    }

    .charts {
      text-align: center;
    }

    th {
      text-align: center;
    }

    .tableDiv {
      padding-right: 10%;
      padding-left: 10%;
    }


    #headsUpCards {
      text-align: center;
      margin-bottom: 20px;
    }

    .cardButton {
      margin-left: 2px;
      margin-right: 4px;
    }

    .cards {
      padding-top: 10px;
      padding-bottom: 10px;
      /*text-align: center;*/
    }

    .resetButton {
      width: 115px;
    }
    .resetButtonDiv {
      text-align: center;
    }

    #copnp1ccnpieDiv {
      padding-top: 50px;
    }

    @media (min-width: 768px) {
      #copnp1ccnpieDiv {
        padding-top: 253px;
      }
    }
    @media (min-width: 992px) {
      #copnp1ccnpieDiv {
        padding-top: 220px;

      }
    }

    /* Make a media query
    .addPlayerResetButtons {
      text-align: center;
    }
*/




  </style>
  <script>

    function drawCanvas(canvasId) {
      console.log("#" + canvasId);
      var c = $("#" + canvasId),

        ctx = c[0].getContext('2d');


      $(function(){
        // set width and height
        ctx.canvas.height = 250;
        ctx.canvas.width = 250;
        // draw
        //draw();

      });

    }

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

    var cop2pc1cc0pieChart = null;
    var copnp1ccnpieChart = null;

    // calculateodds, players=2, player cards=1, community cards=0
    function cop2pc1cc0() {
      //Get the card values
      var card1 = $( "#cop2pc1cc0c1" ).val();
      var card2 = $( "#cop2pc1cc0c2" ).val();
      if (isEmpty(card1) ||isEmpty(card2)) {
        console.log('Player cards must be set');
        return;
      }


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

          var winP = Math.round(data["p1"]["winPercent"] * 100) + '%';
          var loseP = Math.round((1 - data["p1"]["winPercent"] - data["p1"]["splitPercent"]) * 100) + '%';
          var splitP = Math.round(data["p1"]["splitPercent"] * 100) + '%';


          $('#cop2pc1cc0WinP').html(winP);
          $('#cop2pc1cc0LoseP').html(loseP);
          $('#cop2pc1cc0SplitP').html(splitP);

          // pie chart data
          var pieData = [
            {
              value: data.p1.win,
              color: "#46BFBD",
              highlight: "#5AD3D1",
              label: "Win"
            },
            {
              value: data.p1.lose,
              color:"#F7464A",
              highlight: "#FF5A5E",
              label: "Lose"
            },

            {
              value: data.p1.split,
              color: "#FDB45C",
              highlight: "#FFC870",
              label: "Split"
            }
          ];
          // pie chart options
          var pieOptions = {
            segmentShowStroke : false,
            animateScale : true
          };
          // get pie chart canvas
          var cop2pc1cc0pie= document.getElementById("cop2pc1cc0pie").getContext("2d");


          // Destory existing piechart if set
          if(cop2pc1cc0pieChart!=null){
            console.log('piechart != NULL');
            cop2pc1cc0pieChart.destroy();
          }

          /*if(cop2pc1cc0pie!=null){
            console.log('pie != NULL');
            cop2pc1cc0pieChart.destroy();
          }*/

          // draw pie chart
          cop2pc1cc0pieChart = new Chart(cop2pc1cc0pie).Pie(pieData, pieOptions);
        }
      });

    }


    function validateUniqueCards(cards) {
      var counts = {};
      for(var i = 0; i < cards.length; i++) {
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

    function findPointInGame(cc1, cc2, cc3, cc4, cc5) {
      if (cc1.length > 0 && cc2.length > 0 && cc3.length > 0) {
        if (cc4.length > 0) {
          if (cc5.length > 0) {
            return 'River';
          }
          return 'Turn';
        }
        return 'Flop';
      }
      return 'Preflop';
    }

    // calculateodds, players=n, player cards=1, community cards=n


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

      //

      var pointInGame = findPointInGame(cc1, cc2, cc3, cc4, cc5);
      var opponents = ' Opponents';
      if (p == 2) {
        opponents = ' Opponent';
      }

      console.log(pointInGame);
      var title = '<h1>' + pointInGame + ' You vs ' + (p - 1) + opponents + '</h1>';
      console.log(title);


      $("#copnp1ccnTitle").html(title);

      // Prepare the params
      var data = {
        t:"co",
        p:p,
        i:coI,
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

          var winP = Math.round(data["p1"]["winPercent"] * 100) + '%';
          var loseP = Math.round((1 - data["p1"]["winPercent"] - data["p1"]["splitPercent"]) * 100) + '%';
          var splitP = Math.round(data["p1"]["splitPercent"] * 100) + '%';


          $('#copnp1ccnWinP').html(winP);
          $('#copnp1ccnLoseP').html(loseP);
          $('#copnp1ccnSplitP').html(splitP);

          // pie chart data
          var pieData = [
            {
              value: data.p1.win,
              color: "#46BFBD",
              highlight: "#5AD3D1",
              label: "Win"
            },
            {
              value: data.p1.lose,
              color:"#F7464A",
              highlight: "#FF5A5E",
              label: "Lose"
            },

            {
              value: data.p1.split,
              color: "#FDB45C",
              highlight: "#FFC870",
              label: "Split"
            }
          ];
          // pie chart options
          var pieOptions = {
            segmentShowStroke : false,
            animateScale : true
          };
          // get pie chart canvas
          var copnp1ccnpie= document.getElementById("copnp1ccnpie").getContext("2d");


          // Destory existing piechart if set
          if(copnp1ccnpieChart!=null){
            copnp1ccnpieChart.destroy();
          }

          // draw pie chart
          copnp1ccnpieChart = new Chart(copnp1ccnpie).Pie(pieData, pieOptions);
        }
      });
    }

    // Final JS function
    // Calculate Odds
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



    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Introduction_to_Object-Oriented_JavaScript

    // Resets the cards, pie, table and title
    function resetcopnp1ccn() {
      var divs = ["copnp1c1", "copnp1c2", "copnp1cc1", "copnp1cc2", "copnp1cc3", "copnp1cc4", "copnp1cc5"];
      // Destory existing piechart if set
      if(copnp1ccnpieChart!=null){
        copnp1ccnpieChart.destroy();
      }
      $('#copnp1ccnWinP').html('');
      $('#copnp1ccnLoseP').html('');
      $('#copnp1ccnSplitP').html('');
      $("#copnp1ccnTitle").html('<h1>Preflop You vs 1 Opponent</h1>');

      $('#copnp1ccnp').selectpicker('val', '2');
      resetDivs(divs);
    }

    // Reloads the entire copnpnccn div
    function resetcopnpnccn() {
      //$('#copnpnccn').load('pokerindex.php' +  ' #copnpnccn');
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
        $(selector).selectpicker('val', '');
      }
    }

    function disableDivs(divs) {
      if (!Array.isArray(divs)) {
        var divs = [divs];
      }
      for(var i = 0; i< divs.length; i++) {
        var selector = "#" + divs[i];
        $(selector).prop('disabled', true);
        $(selector).selectpicker('refresh');
      }
    }

    function enableDivs(divs) {
      if (!Array.isArray(divs)) {
        var divs = [divs];
      }
      for(var i = 0; i< divs.length; i++) {
        var selector = "#" + divs[i];
        $(selector).prop('disabled', false);
        $(selector).selectpicker('refresh');
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
      var classAdd = "";
      var classRemove = "";
      var state = $(selector).html();
      if (state == '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>') {
        enableDivs(cardDivs);
        newValue = '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>';
        copnpnccnCardState[cardDivs[0]] = true;
        copnpnccnCardState[cardDivs[1]] = true;
        var classAdd = "btn-danger";
        var classRemove = "btn-success";
      }
      else {
        resetDivs(cardDivs);
        disableDivs(cardDivs);
        newValue = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
        copnpnccnCardState[cardDivs[0]] = false;
        copnpnccnCardState[cardDivs[1]] = false;
        var classAdd = "btn-success";
        var classRemove = "btn-danger";
      }
      $(selector).html(newValue);
      $(selector).addClass(classAdd);
      $(selector).removeClass(classRemove);
      copnpnccn();
    }







  </script>
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="50">

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

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="../index.html"><span class="glyphicon glyphicon-home"></span></a>
    </div>
    <div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1">Preflop - You vs 1 Player</a></li>
          <li><a href="#section2">You vs n Players</a></li>
          <li><a href="#section3">You vs n Players</a></li>
          <li><a href="#about">About</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="jumbotron" style="padding-top: 98px;"> <!--TODO make consistent-->
  <div class="container text-center">
    <h1>Poker Hand Simulator</h1>
  </div>
</div>

<div id="section1" class="mainSection">

  <div class="container">
    <!--<div class="row">-->
      <div class="col-xs-12">
        <!--<h1>calculateodds, players=2, player cards=1, community cards=0</h1>-->
        <h1>Preflop Heads Up</h1>
      </div>
     <!-- </div>-->
      <!--<div class="row">-->

      <div class="col-sm-6">
        <div id="headsUpSelect">
          <p class="lead">Select your cards:</p>
          <div class="col-xs-12">
            <div id="headsUpCards">
              <?php
              include "../scripts/poker-simulator/generate_card_html.php";
              echo generateCardHTML('cop2pc1cc0c1', 'cop2pc1cc0');
              echo generateCardHTML('cop2pc1cc0c2', 'cop2pc1cc0');
              ?>
            </div>
          </div>
        </div>

        <p class="lead">Results:</p>
        <div class="col-xs-12 ">
          <div class="tableDiv">
            <table class="table text-center">
              <thead>
              <tr>
                <th>Win</th>
                <th>Lose</th>
                <th>Split</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td id="cop2pc1cc0WinP"></td>
                <td id="cop2pc1cc0LoseP"></td>
                <td id="cop2pc1cc0SplitP"></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-sm-6 charts">
        <canvas id="cop2pc1cc0pie" width="275" height="275"></canvas>
      </div>
    <!--</div>-->
  </div>

</div>

<!--new section-->

<div id="section2" class="mainSection">
  <div class="container">
    <div class="col-sm-8">
      <div id="copnp1ccnTitle"><h1>Preflop You vs 1 Opponent</h1></div>
      <div class="row">
        <div class="col-lg-7">
          <div id="copnp1ccnCards" class="cards">
            <p class="lead">Select your cards:</p>
            <?php
            // P1 Cards
            echo generateCardHTML('copnp1c1', 'copnp1ccn');
            echo generateCardHTML('copnp1c2', 'copnp1ccn');
            ?>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="cards">


          <p class="lead">Select no. of opponents:</p>
          <?php
          echo generateNoOfPlayersHTML('copnp1ccnp', 'copnp1ccn');
          ?>
          </div>
        </div>
      </div>

      <div class="row">


          <div class="col-lg-7 cards flopCards">
            <p class="lead">Select the flop cards:</p>
            <?php
            // Community Cards
            echo generateCardHTML('copnp1cc1', 'copnp1ccn');
            echo generateCardHTML('copnp1cc2', 'copnp1ccn');
            echo generateCardHTML('copnp1cc3', 'copnp1ccn');
            ?>
          </div>
          <div class="col-lg-5 cards">
            <p class="lead">Select the turn & river cards:</p>
            <div class="turnriver">
              <?php
              echo generateCardHTML('copnp1cc4', 'copnp1ccn');
              echo generateCardHTML('copnp1cc5', 'copnp1ccn');
              ?>
            </div>
          </div>

      </div>

      <div class="row">
        <div class="col-lg-12 cards">

          <p class="lead">Results:</p>


        </div>


      </div>

      <div class="row">

        <!--<div class="col-md-2">

        </div>-->
        <div class="col-md-8 col-md-offset-2">
          <div class="tableDiv">
            <table class="table text-center">
              <thead>
              <tr>
                <th>Win</th>
                <th>Lose</th>
                <th>Split</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td id="copnp1ccnWinP"></td>
                <td id="copnp1ccnLoseP"></td>
                <td id="copnp1ccnSplitP"></td>
              </tr>
              </tbody>
            </table>
          </div>
          <div class="resetButtonDiv">
            <button id="resetcopnp1ccn" type="button" class="btn btn-default resetButton" onclick="resetcopnp1ccn()">Reset</button>
          </div>
        </div>
        <!--<div class="col-md-2">

        </div>-->

      </div>
      <!--<div class="row">
        <div class="col-lg-12">
          <div class="alert alert-danger" role="alert">Warnibng</div>

        </div>
      </div>
-->

    </div>
    <div class="col-sm-4 charts" id="copnp1ccnpieDiv">
      <canvas id="copnp1ccnpie" width="246" height="246"></canvas>
    </div>


  </div>

</div>
<!--end of new section-->


<div id="section3" class="mainSection">
  <div class="container">
    <div class="col-sm-8">
      <div id="copnpnccnTitle"><h1>calculateodds, players=n, player cards=n, community cards=n</h1></div>
      <div class="row">
        <div class="col-lg-7">
          <div id="copnpnccnCards" class="cards">
            <p class="lead">Select your cards:</p>
            <?php
            // P1 Cards
            echo generateCardHTML('copnp1c1ccn', 'copnpnccn');
            echo generateCardHTML('copnp1c2ccn', 'copnpnccn');
            ?>
          </div>
        </div>
        <div class="col-lg-5">

        </div>
      </div>

      <div class="row">


          <div class="col-lg-7 cards flopCards">
            <p class="lead">Select the flop cards:</p>
            <?php
            // Community Cards
            echo generateCardHTML('copnpncc1', 'copnpnccn');
            echo generateCardHTML('copnpncc2', 'copnpnccn');
            echo generateCardHTML('copnpncc3', 'copnpnccn');
            ?>
          </div>
          <div class="col-lg-5 cards">
            <p class="lead">Select the turn & river cards:</p>
            <div class="turnriver">
              <?php
              echo generateCardHTML('copnpncc4', 'copnpnccn');
              echo generateCardHTML('copnpncc5', 'copnpnccn');
              ?>
            </div>
          </div>

      </div>

      <div class="row">
        <div class="col-xs-12">
          <p class="lead">Select players' cards:</p>
          Player 2:
          <?php
          echo generateCardHTML('copnp2c1ccn', 'copnpnccn');
          echo generateCardHTML('copnp2c2ccn', 'copnpnccn');
          ?>

            <button id='disablecopnp2ccn' type='button' class='btn btn-default btn-danger' onclick='changePlayerState("disablecopnp2ccn", ["copnp2c1ccn" , "copnp2c2ccn"])'><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
            <button id='resetcopnp2ccn' type='button' class='btn btn-default' onclick='resetDivs(["copnp2c1ccn","copnp2c2ccn"])'>Reset</button>

          </div>
        <div class="col-xs-12">
          Player 3:
          <?php
          echo generateCardHTML('copnp3c1ccn', 'copnpnccn', 'disabled');
          echo generateCardHTML('copnp3c2ccn', 'copnpnccn', 'disabled');
          ?>

          <button id='disablecopnp3ccn' type='button' class='btn btn-default btn-success' onclick='changePlayerState("disablecopnp3ccn", ["copnp3c1ccn" , "copnp3c2ccn"])'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
          <button id='resetcopnp3ccn' type='button' class='btn btn-default' onclick='resetDivs(["copnp3c1ccn","copnp3c2ccn"])'>Reset</button>

        </div>
        <div class="col-xs-12">
          <p>TODO add players</p>
        </div>

      </div>



      <div class="row">
        <div class="col-lg-12 cards">

          <p class="lead">Results:</p>
        </div>
      </div>

      <div class="row">

        <div class="col-md-2">

        </div>
        <div class="col-md-8">
          <div class="tableDiv">
            <table class="table text-center">
              <thead>
              <tr>
                <th>Win</th>
                <th>Lose</th>
                <th>Split</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td id="copnp1ccnWinP"></td>
                <td id="copnp1ccnLoseP"></td>
                <td id="copnp1ccnSplitP"></td>
              </tr>
              </tbody>
            </table>
          </div>
          <div class="resetButtonDiv">
            <button id="resetcopnp1ccn" type="button" class="btn btn-default resetButton" onclick="resetcopnp1ccn()">Reset</button>
          </div>
        </div>
        <div class="col-md-2">

        </div>

      </div>


    </div>
    <div class="col-sm-4 charts" >
      <canvas id="copnp1ccnpie" width="246" height="246"></canvas>
    </div>


  </div>

</div>
<!--end of new section-->



<div id="section6" class="container-fluid">
<h1>calculateodds, players=n, player cards=n, community cards=n</h1>
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
      echo "<button id='{$emptySeatDivId}' type='button' class='btn btn-default' onclick='changePlayerState(\"{$emptySeatDivId}\", {$pcDivIds})'>Add Player</button>"; //TODO Active/Disabled Buttons BS
      echo "<button id='resetcopnp{$i}ccn' type='button' class='btn btn-default' onclick='resetDivs({$pcDivIds})'>Reset</button>";
      unset ($pcDivIds);
      echo "Player $i <br>";

    }
    ?>
    <p>copnpnccnresponse: <span id="copnpnccnresponse"></span></p>

    <button id="resetcopnpnccn" type="button" onclick="resetcopnpnccn()">Reset</button>


  </div>
</div>

<div id="about" class="container-fluid">
  <h1>About</h1>
  <p>
    The idea to make this came one poker night after I was thrown off a hand with top pair. I had K5 off-suit and hit the king on the flop, but with serious kicker issues, all it took was a pretty small raise for me to fold. Even after hitting the top pair, what was I expecting to achieve with that hand? I was curious to know how many times K5 would have won in that situation or even just pre-flop, I knew it wasn’t a great hand but statistically how bad was it?
  </p>
  <p>
    So rather than use one of the existing tools to discover this knowledge, I decided to create my own. I wrote this poker simulator in PHP, a language not really suited for heavy numerical simulations, but on larger iterations it certainly works - the results are consistent with other tools out there.
  </p>
  <p>
    There’s quite a lot involved in simulating a poker hand. Working out all the possible 5 card combinations for every player, then calculating what every possible hand is and its value, many, many times is computationally expensive. That, and running this program on budget hardware isn’t the best combination, meaning I’ve had to limit the number of simulations on this demo to something relatively low. The results will vary a bit due to random sampling but you get the idea.
  </p>
</div>

</body>
</html>
