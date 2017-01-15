<!--
TODO:
 * Tidy source/document
-->
<!DOCTYPE html>
<!-- Last commit: $Id$ -->
<!-- Version Location: $HeadURL$ -->
<html>
<head>
    <title>Poker Simulator</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.1.1/Chart.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Contrail+One">

    <!-- BOOTSTRAP SELECT  https://silviomoreto.github.io/bootstrap-select/examples/ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">


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
        #about    {
            background-color: #009688;
            /*text-align: justify;*/
        }

        .jumbotron {
            color: #ffffff;
            margin-bottom: 0px;
            background: url('../resources/images/poker-sim/pokerbanner.jpeg') no-repeat 50% 65%;
            background-size: cover;
            text-shadow: #444 0 1px 1px;
        }

        .navbar {
            margin-bottom: 0px;
        }

        .mainSection {
            height: auto;
            color: #fff;
            padding-bottom: 50px;
            padding-top: 30px;
            margin: -10px
        }

        .charts {
            text-align: center;
            align-self: center;
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

        #copnp1ccnpieDiv, #copnpnccnpieDiv {
            padding-top: 50px;
        }

        @media (min-width: 332px) {
            .mainSection {
                margin: 0px;
            }

        }
        @media (min-width: 768px) {
            .jumbotron {
                padding-top: 88px;
                padding-bottom: 88px;
            }
            #section3container, #section2container{
                display: flex;
                justify-content: center;
            }
        }
        @media (min-width: 992px) {
        }

        .hideRow {
            display:none;
        }

        .inputFeedback {
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        h1, h2, h3 {
            font-family: "Contrail One", Fallback, "sans-serif";
        }



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

        // Initialise the charts
        var cop2pc1cc0pieChart = null;
        var copnp1ccnpieChart = null;
        var copnpnccnpieChart = null;

        var loadingHTML = '<div class="alert alert-info inputFeedback" role="alert">' +
            '<i class="fa fa-spinner fa-spin fa-fw margin-bottom"></i> Loading... </div>';

        function generateWarningHTML(warnings) {
            console.log('warnings');
            console.log(warnings);
            var warningHTML = '<div class="alert alert-danger inputFeedback" role="alert" style="text-align: left">' +
                '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                '<b> Unable to return results:</b><ul>';
            //Add each error to the warnings array
            $.each( warnings, function( index, value ){
                warningHTML += '<li>' + value + '</li>';
            });
            warningHTML += '</ul> </div>';
            return warningHTML;
        }

        // calculateodds, players=2, player cards=1, community cards=0
        // Section 1
        function cop2pc1cc0() {
            //Get the card values
            var card1 = $( "#cop2pc1cc0c1" ).val();
            var card2 = $( "#cop2pc1cc0c2" ).val();
            if (isEmpty(card1) ||isEmpty(card2)) {
                console.log('Player cards must be set');
                return;
            }

            $('#cop2pc1cc0MainDiv').html('<canvas id="cop2pc1cc0pie" width="275" height="275"></canvas>');

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
        // Section 2
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
            var warnings = [];
            // Validate player cards set
            if (isEmpty(p1c1) ||isEmpty(p1c2)) {
                console.log('Your cards must be set');
                warnings.push('Your cards must be set');
            }
            //Validate correct no. of cards select
            var ccCardsValid = validateCommunityCardsArray(communityCards);
            console.log(ccCardsValid);
            if (!(ccCardsValid)) {
                console.log('Community cards are not valid');
                warnings.push('Community cards are not valid');
            }
            // Validate uniqueness
            var allCards = communityCards.concat(p1c);
            if(!validateUniqueCards(allCards)) {
                console.log('Cards must be unique');
                warnings.push('Cards must be unique');
            }
            // We have an error

            if (warnings.length > 0) {
                $('#copnp1ccnFeedback').html(generateWarningHTML(warnings));
                return;
            }
            else {
                // Good to go so show loading HTML
                $('#copnp1ccnFeedback').html(loadingHTML)
            }

            // Get the no. of players
            var p = $( "#copnp1ccnp" ).val();
            var pointInGame = findPointInGame(cc1, cc2, cc3, cc4, cc5);
            var opponents = ' Opponents';
            if (p == 2) {
                opponents = ' Opponent';
            }
            var title = '<h1>' + pointInGame + ', You vs ' + (p - 1) + opponents + '</h1>';
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
                    $('#copnp1ccnFeedback').html('');
                    $('#copnp1ccnpieMainDiv').html('<div id="copnp1ccnpieDiv"><canvas id="copnp1ccnpie" width="246" height="246"></canvas></div>');

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

        // Section 3
        // Calculate Odds
        function copnpnccn() {
            // Fetch an ensure valid no. of players cards are selected
            var playersCards = {};
            var cardsSelected = true;
            var warnings = [];

            for (i = 1; i <= 9; i++) {
                for (j = 1; j <=2; j++) {
                    var playerCardIndex = "p" + i + "c" + j;
                    var divId = "#copn" + playerCardIndex + "ccn";
                    var div = $(divId);
                    var parentDiv = div.parent();
                    if (parentDiv.hasClass("disabled") == false) { // Find if cards enabled by looking at class of parent div
                        playersCards[playerCardIndex] = div.val();
                    }
                }
                var currentPlayerCards = playersCards["p" + i + "c1"] + playersCards["p" + i + "c2"];
                if (currentPlayerCards.length != 0 && currentPlayerCards.length < 4) {
                    // Two cards not selected here
                    cardsSelected = false;
                    //console.log("player " + i + " needs all cards selected");
                    if (i > 1) { // Only show this warning for other players
                        warnings.push("P" + i + "'s cards must both be set");
                    }
                }
                if (currentPlayerCards.length >= 4) {
                    // Two cards are selected, add the corresponding row to the table
                    var tableRowId = '#copnpnccnTR' + "p" + i;
                    $(tableRowId).removeClass('hideRow');
                }
            }
            console.log('playerCards');
            console.log(playersCards);
            /*if (!cardsSelected) {
             console.log('All players must have cards selected');
             return;
             }*/

            // P1 must have cards set to continue
            if (isEmpty(playersCards["p1c1"]) || isEmpty(playersCards["p1c2"])) {
                console.log('You must have cards selected');
                warnings.unshift('Your cards must be set');
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
                console.log('Community cards are not valid');
                warnings.push('Community cards are not valid');
            }

            // Validate all cards for uniqueness
            var allCardsObj = $.extend({}, playersCards, communityCards);
            var allCardsArray = $.map(allCardsObj, function(value, index) { // Converts object to array
                return [value];
            });
            if (!validateUniqueCards(allCardsArray)) {
                // Cards not unique
                console.log('Cards must be unique');
                warnings.push('Cards must be unique');
            }

            // Now get the no of players
            var p = Object.keys(playersCards).length/2;
            /*if (p < 2) {
             console.log('There must be at least 2 players');
             return;
             }*/

            if (warnings.length > 0) {
                $('#copnpnccnFeedback').html(generateWarningHTML(warnings));
                return;
            }
            else {
                // Good to go so show loading HTML
                $('#copnpnccnFeedback').html(loadingHTML);
            }

            // Prepare the params
            var data = {
                t:"co",
                p:p,
                i:coI
            };

            $.extend(data, playersCards, communityCards);
            console.log('data');
            console.log(data);

            var pointInGame = findPointInGame(communityCards['cc1'], communityCards['cc2'], communityCards['cc3'], communityCards['cc4'], communityCards['cc5']);
            var opponents = ' Opponents';
            if (p == 2) {
                opponents = ' Opponent';
            }
            var title = '<h1>' + pointInGame + ', You vs ' + (p - 1) + opponents + '</h1>';
            $("#copnpnccnTitle").html(title);

            $('#copnpnccnpieMainDiv').html('<div id=copnpnccnpieDiv><canvas id="copnpnccnpie" width="246" height="246"></canvas></div>');

            $.ajax({
                type: 'POST',
                url: '../scripts/poker-simulator/poker_simulator_interface.php',
                data: data,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('#copnpnccnFeedback').html('');
                    var labels = [];
                    var win = [];
                    var lose = [];
                    var split = [];

                    // Get all the data we need
                    $.each(data, function (player, result) {

                        if(player == 'timeTaken') {
                            return true;
                        }

                        var winP = Math.round(result["winPercent"] * 100) + '%';
                        var loseP = Math.round((1 - result["winPercent"] - result["splitPercent"]) * 100) + '%';
                        var splitP = Math.round(result["splitPercent"] * 100) + '%';

                        $('#copnpnccn' + player + 'WinP').html(winP);
                        $('#copnpnccn' + player + 'LoseP').html(loseP);
                        $('#copnpnccn' + player + 'SplitP').html(splitP);

                        labels.push(player.toUpperCase());
                        win.push(Math.round(result["winPercent"] * 100));
                        lose.push(Math.round((1 - result["winPercent"] - result["splitPercent"]) * 100));
                        split.push(Math.round(result["splitPercent"] * 100));

                    }); // End of each

                    // Destory existing chart if set
                    if(copnpnccnpieChart!=null){
                        copnpnccnpieChart.destroy();
                    }

                    var copnpnccnpie = document.getElementById("copnpnccnpie").getContext("2d");

                    var ChartData = {
                        labels: labels,
                        datasets: [
                            {
                                label: "Win",
                                fillColor: "#46BFBD",
                                //strokeColor: "rgba(220,220,220,0.8)",
                                highlightFill: "#5AD3D1",
                                //highlightStroke: "rgba(220,220,220,1)",
                                data: win
                            },
                            {
                                label: "Lose",
                                fillColor: "#F7464A",
                                //strokeColor: "rgba(151,187,205,0.8)",
                                highlightFill: "#FF5A5E",
                                //highlightStroke: "rgba(151,187,205,1)",
                                data: lose
                            },
                            {
                                label: "Split",
                                fillColor: "#FDB45C",
                                //strokeColor: "rgba(151,187,205,0.8)",
                                highlightFill: "#FFC870",
                                //highlightStroke: "rgba(151,187,205,1)",
                                data: split
                            }
                        ]
                    };

                    var ChartOptions = {
                        scaleFontColor: "#ffffff",
                        scaleShowGridLines : false,
                        barShowStroke: false
                    };

                    copnpnccnpieChart = new Chart(copnpnccnpie).Bar(ChartData, ChartOptions);
                }
            });
        }

        // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Introduction_to_Object-Oriented_JavaScript

        // SECTION 2
        // Resets the cards, pie, table and title
        function resetcopnp1ccn() {
            var divs = ["copnp1c1", "copnp1c2", "copnp1cc1", "copnp1cc2", "copnp1cc3", "copnp1cc4", "copnp1cc5"];
            // Destory existing piechart if set
            if(copnp1ccnpieChart!=null){
                copnp1ccnpieChart.destroy();
            }
            $('#copnp1ccnWinP, #copnp1ccnLoseP, #copnp1ccnSplitP, #copnp1ccnFeedback, #copnp1ccnpieMainDiv').html('');
            $("#copnp1ccnTitle").html('<h1>Preflop, You vs 1 Opponent</h1>');


            $('#copnp1ccnp').selectpicker('val', '2'); // No of players
            resetDivs(divs);
        }

        // SECTION 3
        // Reloads the entire copnpnccn div
        function resetcopnpnccn() {

            // Community Cards reset
            var divs = ["copnpncc1", "copnpncc2", "copnpncc3", "copnpncc4", "copnpncc5"];
            var disablePlayerCardDivs = [];
            var resetPlayerButtonDivs = [];
            for (i = 1; i <= 9; i++) {
                divs.push("copnp" + i + "c1ccn", "copnp" + i + "c2ccn");
                // Deal with the players cards
                if (i > 2) {
                    disablePlayerCardDivs.push("copnp" + i + "c1ccn", "copnp" + i + "c2ccn");
                    resetPlayerButtonDivs.push("#disablecopnp" + i + "ccn");
                }
            }
            var buttonResetSelector = resetPlayerButtonDivs.join(resetPlayerButtonDivs);
            resetDivs(divs); // Reset all card divs
            disableDivs(disablePlayerCardDivs); // Reset the player card divs

            // Destroy existing piechart if set
            if(copnpnccnpieChart!=null){
                copnpnccnpieChart.destroy();
            }
            // Clear the table
            $('#copnpnccnp1WinP').html('');
            $('#copnpnccnp1LoseP').html('');
            $('#copnpnccnpieMainDiv').html('');
            $('#copnpnccnp1SplitP').html('');
            $("#copnpnccnTitle").html('<h1>Preflop, 2 Players</h1>');
            $('#copnpnccnp').selectpicker('val', '2'); // Reset No. of players

            // Reset the buttons
            var newValue = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
            var classAdd = "btn-success";
            var classRemove = "btn-danger";

            // Update buttons
            $(buttonResetSelector).html(newValue);
            $(buttonResetSelector).addClass(classAdd);
            $(buttonResetSelector).removeClass(classRemove);
        }

        /**
         * Pass in a div id or an array of div ids, this will set the value to null
         * @param divs
         */
        function resetDivs(divs) {
            if (!Array.isArray(divs)) {
                var divs = [divs];
            }
            for (var i = 0; i< divs.length; i++) {
                var selector = "#" + divs[i];
                $(selector).selectpicker('val', '');
                // Deal with table rows in copnpnccn (section 3)
                var copnpnccnDiv = /copnp[2-9]c[1-2]ccn/g.test(selector);
                if (copnpnccnDiv) {
                    var playerNo = /p[2-9]/.exec(selector);
                    var tableRowId = '#copnpnccnTR' + playerNo[0];
                    $(tableRowId).addClass('hideRow');
                }
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
                resizeButtonsForSmallScreens();
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
                classAdd = "btn-danger";
                classRemove = "btn-success";
            }
            else {
                resetDivs(cardDivs);
                disableDivs(cardDivs);
                newValue = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
                classAdd = "btn-success";
                classRemove = "btn-danger";
            }
            $(selector).html(newValue);
            $(selector).addClass(classAdd);
            $(selector).removeClass(classRemove);

            resizeButtonsForSmallScreens();
            copnpnccn();
        }

        function resizeButtonsForSmallScreens() {
            var $window = $(window);

            function resize() {
                var $cardButton = $('.cardButton');
                var width = '';
                if ($window.width() < 467) {
                    width = '65px';
                } else {
                    width = '115px';
                }
                $cardButton.css('width', width);
                $cardButton.attr('data-width', width);
            }
            $window
                .resize(resize)
                .trigger('resize');
        }

        $(document).ready(function(){
            resizeButtonsForSmallScreens();
        });

    </script>
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>-->
            <a class="navbar-brand" href="../index.html"><span class="glyphicon glyphicon-home"></span></a>
        </div>
        <div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <!--<li><a href="#section1">Preflop - You vs 1 Player</a></li>
                    <li><a href="#section2">You vs n Players</a></li>
                    <li><a href="#section3">You vs n Players</a></li>
                    <li><a href="#about">About</a></li>-->
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="jumbotron">
    <div class="container text-center">
        <h1>Poker Hand Simulator</h1>
    </div>
</div>

<div id="section1" class="mainSection">

    <div class="container">
        <!--<div class="row">-->
        <div class="col-xs-12">
            <!--<h1>calculateodds, players=2, player cards=1, community cards=0</h1>-->
            <h1>Preflop, Heads Up</h1>
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

        <div class="col-sm-6 charts" id="cop2pc1cc0MainDiv">
        </div>
        <!--</div>-->
    </div>

</div>

<!--new section-->

<div id="section2" class="mainSection">
    <div class="container" id="section2container">
        <div class="col-sm-8">
            <div id="copnp1ccnTitle"><h1>Preflop, You vs 1 Opponent</h1></div>
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
                        <select class="selectpicker" id="copnp1ccnp" data-width="234px" onchange="copnp1ccn()">
                            <option selected value="2">1</option>
                            <option value="3">2</option>
                            <option value="4">3</option>
                            <option value="5">4</option>
                            <option value="6">5</option>
                            <option value="7">6</option>
                            <option value="8">7</option>
                            <option value="9">8</option>
                        </select>
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
                <!--Alerts go here-->
                <div class="col-xs-12" id="copnp1ccnFeedback">
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
            </div>
        </div>
        <!--jquery draws the canvas-->
        <div class="col-sm-4 charts" id="copnp1ccnpieMainDiv">

        </div>
    </div>

</div>
<!--end of new section-->


<div id="section3" class="mainSection">
    <div class="container" id="section3container">
        <div class="col-sm-8">
            <!--<div id="copnpnccnTitle"><h1>calculateodds, players=n, player cards=n, community cards=n</h1></div>-->
            <div id="copnpnccnTitle"><h1>Preflop, 2 Players</h1></div>
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
                    P2:
                    <?php
                    echo generateCardHTML('copnp2c1ccn', 'copnpnccn');
                    echo generateCardHTML('copnp2c2ccn', 'copnpnccn');
                    ?>

                    <button id='disablecopnp2ccn' type='button' class='btn btn-default disabled btn-danger'><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                    <button id='resetcopnp2ccn' type='button' class='btn btn-default' onclick='resetDivs(["copnp2c1ccn","copnp2c2ccn"])'>Reset</button>

                </div>
                <!--<div class="col-xs-12">
          P3:
          <?php
                echo generateCardHTML('copnp3c1ccn', 'copnpnccn', 'disabled');
                echo generateCardHTML('copnp3c2ccn', 'copnpnccn', 'disabled');
                ?>
          <button id='disablecopnp3ccn' type='button' class='btn btn-default btn-success' onclick='changePlayerState("disablecopnp3ccn", ["copnp3c1ccn" , "copnp3c2ccn"])'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
          <button id='resetcopnp3ccn' type='button' class='btn btn-default' onclick='resetDivs(["copnp3c1ccn","copnp3c2ccn"])'>Reset</button>
        </div>-->
                <?php

                $html = '';
                for ($i=3; $i<=9; $i++) {
                    $html .= '<div class="col-xs-12">';
                    $html .= "P{$i}: ";
                    $html .= generateCardHTML("copnp{$i}c1ccn", 'copnpnccn', 'disabled');
                    $html .= generateCardHTML("copnp{$i}c2ccn", 'copnpnccn', 'disabled');
                    $html .= <<<HTML
          <button id='disablecopnp{$i}ccn' type='button' class='btn btn-default btn-success' onclick='changePlayerState("disablecopnp{$i}ccn", ["copnp{$i}c1ccn" , "copnp{$i}c2ccn"])'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
          <button id='resetcopnp{$i}ccn' type='button' class='btn btn-default' onclick='resetDivs(["copnp{$i}c1ccn","copnp{$i}c2ccn"])'>Reset</button>
          </div>
HTML;
                }
                echo $html;
                ?>


            </div>


            <div class="row">
                <!--Alerts go here-->
                <div class="col-xs-12" id="copnpnccnFeedback">

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
                                <th></th>
                                <th>Win</th>
                                <th>Lose</th>
                                <th>Split</th>
                            </tr>
                            </thead>
                            <tbody> <!--Yes naming convention got a bit nasty here-->
                            <?php
                            for ($i = 1; $i <= 9; $i++) {
                                $tableRowDivId = "copnpnccnTRp$i";
                                $tableCellDivIdPrefix = "copnpnccnp$i";
                                if ($i > 1) { $class = "hideRow"; }
                                echo <<<HTML
                <tr id="{$tableRowDivId}" class="{$class}">
                  <td>P{$i}</td>
                  <td id="{$tableCellDivIdPrefix}WinP"></td>
                  <td id="{$tableCellDivIdPrefix}LoseP"></td>
                  <td id="{$tableCellDivIdPrefix}SplitP"></td>
                </tr>
HTML;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="resetButtonDiv">
                        <button id="resetcopnpnccn" type="button" class="btn btn-default resetButton" onclick="resetcopnpnccn()">Reset</button>
                    </div>
                </div>
                <div class="col-md-2">

                </div>

            </div>


        </div>
        <div class="col-sm-4 charts" id="copnpnccnpieMainDiv">
        </div>


    </div>

</div>
<!--end of new section-->

<div id="about" class="mainSection">
    <div class="container">
        <div class="col-xs-12">
            <h1>About</h1>
            <p>
                The idea to make this came back in the summer of 15 long before I knew anything about poker - I still don't know anything by the way. Anyway Bodes threw me off a hand when I had top pair. I had K5 off-suit and hit the king on the flop but with serious kicker issues, all it took was a pretty small raise for me to fold. Even after hitting top pair, it didn’t feel right to my former self to keep betting into it. I became curious and wanted to know how many times K5 would win, I knew it wasn’t a great hand but mathematically how bad was it? I wanted a way to analyse statistically what my hands really meant and I didn't want to cheat. It's the perfect coding challenge.
            </p>
            <p>
                I wrote this poker simulator in PHP, a slow, interpreted language designed for web applications that wouldn't be the first choice for numerical simulations like this, but for me at the time, it's the language I knew best and I knew capable of getting the job done. Whilst not lightning fast - it goes through a hell of a lot of nested loops - on larger iterations the results seem pretty consistent with other tools out there.
            </p>
            <p>
                As it turns out, simulating a poker hand is quite involved. We have to work out all the possible 5 card combinations for every player given what cards are shown (or could be shown), go through every combination and determine the value of the hand, find the strongest, then compare this against every other player, <em>n</em> times. The combinatorics get out of hand. Quick. And the cost only increases as the number of players increases.
                I've found ways to make this more performant.
                <!--        increases. To get results in a reasonable time, I’ve capped the number of iterations for each simulation to 100. This is a relatively low number, so repeating the same simulation is likely to return slightly different results but you get the idea <i class="fa fa-smile-o" aria-hidden="true"></i>. I could cap it at 1000 iterations to return results with more precision but you'd be waiting 10 times as long!
                -->      </p>
            <p><b>Update 22nd Oct 2016:</b> How did I make it more performant you may ask?</p>
            <p>
                Comments and feedback welcome, if you spot an unusual result, leave a message below with the details and I’ll take a look.
            </p>

        </div>
    </div>
</div>
<div class="mainSection">
    <div class="container">
        <div class="col-xs-12">
            <div id="disqus_thread"></div>
            <script>
                /**
                 *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                 *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                 */
                /*
                 var disqus_config = function () {
                 this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                 this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                 };
                 */
                (function() {  // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');

                    s.src = '//finchmeister.disqus.com/embed.js';

                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
        </div>
    </div>
</div>

<!-- FOOTER -->

<footer class="container">
    <div class="col-xs-12">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2016 - finchmeister.co.uk</p>
    </div>
</footer>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-78908802-1', 'auto');
    ga('send', 'pageview');

</script>

</body>

</html>