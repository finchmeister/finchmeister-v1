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

    var i = 10;

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
        "i":10,
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
          "i":i,
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
          "i":i,
          "p1c1":card1,
          "p1c2":card2
        },
        dataType: 'json',
        success: function (data) {
          console.log(data);
          $('#cop2pc1cc0response').text(data[0]['win']);
        }
      });

    }



    function cop2pc1cc0ChartFromJSON(json) {


    }

    // calculateodds, players=n, player cards=1, community cards=n
    function copnpc1ccn() {

    }

    //calculateodds, players=n, player cards=n, community cards=n
    function copnpcnccn() {

    }

    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Introduction_to_Object-Oriented_JavaScript




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
echo generateCardHTML('copnpc1ccnc1', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccnc2', 'copnpc1ccn');
// Community Cards
echo generateCardHTML('copnpc1ccncc1', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc2', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc3', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc4', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc5', 'copnpc1ccn');
// No of Players
echo generateNoOfPlayersHTML('copnpc1ccnp', 'copnpc1ccn');
?>
<p>copnpc1ccnresponse: <span id="copnpc1ccnresponse"></span></p>


<h2>calculateodds, players=n, player cards=n, community cards=n</h2>
<?php
//include "../scripts/poker-simulator/generate_card_html.php";
// P1 Cards
echo generateCardHTML('copnpc1ccnc1', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccnc2', 'copnpc1ccn');
// Community Cards
echo generateCardHTML('copnpc1ccncc1', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc2', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc3', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc4', 'copnpc1ccn');
echo generateCardHTML('copnpc1ccncc5', 'copnpc1ccn');
echo '<br>';

for($i=2; $i<=9; $i++) {
  for($j = 1; $j <=2; $j++) {
    $divId = "copnpc{$i}ccnc{$j}";
    echo generateCardHTML($divId, 'copnpcnccn');
  }
  echo "Player $i <br>";
}
?>
<p>copnpc1ccnresponse: <span id="copnpc1ccnresponse"></span></p>

</body>
</html>
