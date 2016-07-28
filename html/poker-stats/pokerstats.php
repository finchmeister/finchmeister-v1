<!DOCTYPE html>
<html>
<!--Credit: http://www.sitepoint.com/understanding-bootstraps-affix-scrollspy-plugins-->
<head>
  <title>Poker stats</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
  <script type="text/javascript" async
          src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML">
  </script>
</head>
<style>
  @import url(http://fonts.googleapis.com/css?family=Lora:400,700);
  body {
    position: relative;
    line-height: 1.5;
    font-family: 'Lora', sans-serif;
    background: rgb(245, 245, 245);
  }

  h1,
  h2 {
    font-weight: 700;
  }
  h2 {
    margin-bottom: -20px;
  }

  .jumbotron {
    border-radius: 0px;
    background: #4CAF50;
    margin-bottom: 0;
    color: #ffffff;
    margin-top: -20px;
  }

  .jumbotron button {
    margin-top: 10px;
  }

  .main_table {
    border-bottom: 1px solid #B6B6B6;
    padding-top: 20px;
    padding-bottom: 20px;
    /*padding: 40px 0;*/
  }
  
  @media (min-width:768px) {
    .row {
      border-bottom: 1px solid #B6B6B6;
    }
    .main_table {
      border-bottom: 0px
    }
  }

  section {
    padding: 40px 0;
    border-bottom: 1px solid #B6B6B6;
  }

  .results_table {
    padding: 40px 0;
  }

  #web-development,
  #mobile-development {
    padding-bottom: 0;
  }

  section:last-child {
    border-bottom: none;
  }

  .nav {
    background: #FF5252;
  }

  .nav a {
    color: #ffffff;
    font-style: italic;
  }

  .nav li a:hover,
  .nav li a:focus {
    background: #ff4143;
  }

  .nav .active {
    font-weight: bold;
    background: #ff2c2e;
  }

  .nav .nav {
    display: none;
  }

  .nav .active .nav {
    display: block;
  }

  .nav .nav a {
    font-weight: normal;
    font-size: .85em;
  }

  .nav .nav span {
    margin: 0 5px 0 2px;
  }

  .nav .nav .active a,
  .nav .nav .active:hover a,
  .nav .nav .active:focus a {
    font-weight: bold;
    padding-left: 30px;
    border-left: 5px solid black;
  }

  .nav .nav .active span,
  .nav .nav .active:hover span,
  .nav .nav .active:focus span {
    display: none;
  }

  .application {
    border-top: 1px solid #B6B6B6;
  }

  .affix-top {
    position: relative;
  }

  .affix {
    top: 20px;
  }

  .affix,
  .affix-bottom {
    width: 213px;
  }

  .affix-bottom {
    position: absolute;
  }

  footer {
    /*border-top: 1px solid #B6B6B6;*/
    height: 50px;
  }

  footer p {
    line-height: 50px;
    margin-bottom: 0;
  }

  @media (min-width:1200px) {
    .affix,
    .affix-bottom {
      width: 263px;
    }
  }

</style>

<script>
  $( document).ready( function() {
      $('#nav').affix({
        offset: {
          top: $('#nav').offset().top,
          bottom: ($('footer').outerHeight(true) + $('.application').outerHeight(true)) + 40
        }
      });
    }
  );
</script>

<body>
<!--https://www.sitepoint.com/understanding-bootstraps-affix-scrollspy-plugins/
http://codepen.io/SitePoint/pen/GgOzwX
-->
<?php
include "../scripts/poker-stats/poker_stats_core.php";
$resultsArray = getResultsInArray();
$pointsStats = createPointsStats($resultsArray);
$stats = getStats();
list($sectionHTML, $navHTML) = createHTMLSectionsAndNav($resultsArray);

function createHTMLTable($tableArray) {
  $newStyle = $tableArray[0]['buyIn'] != 0 ? 1 : 0;
  if ($newStyle) {
    $extraHeaders = <<<HTML
                        <th>Winnings</th>
                        <th>Rebuys</th>
                        <th>Bought In</th>
                        <th>Net</th>
                        <th>Points</th>
HTML;
  }
  else {
    $extraHeaders = '';
  }
  $tableClass = 'table table-hover';
  $table = <<<HTML
                    <thead>
                    <tr>
                        <th>Position</th>
                        <th>Name</th>
                        {$extraHeaders}
                    </tr>
                    </thead>
                    <tbody>
HTML;
  foreach ($tableArray as $row) {
    if ($newStyle) {
      $extraCells = <<<HTML
                        <td>£{$row['winnings']}</td>
                        <td>{$row['rebuys']}</td>
                        <td>£{$row['boughtIn']}</td>
                        <td>£{$row['net']}</td>
                        <td>{$row['points']}</td>
HTML;
    }
    else {
      $extraCells = '';
    }
    $table .= <<<HTML
                    <tr>
                        <td>{$row['position']}</td>
                        <td>{$row['name']}</td>
                        {$extraCells}
                    </tr>
HTML;
  }
  $buyIn = $newStyle ? "Buy in: £{$tableArray[0]['buyIn']}" : '';

  $HTMLTable = <<<HTML
                <table class="{$tableClass}">
                {$table}
                    </tbody>
                </table>
                {$buyIn}
HTML;
  return $HTMLTable;
}

function createHTMLSectionsAndNav($resultsArray) {
  $sectionHTML = '';
  $navHTML = '<ul id="nav" class="nav hidden-xs hidden-sm" data-spy="affix">';
  foreach ($resultsArray as $gameId => $resultArray) {
    // Create the sections
    $date = date('jS M Y', strtotime($resultArray[0]['date']));
    $table = createHTMLTable($resultArray);
    $sectionHTML .= <<<HTML
    <section id="{$gameId}">
        <h3>$date</h3>
        {$table}
    </section><!--- end of {$gameId} --->
HTML;

    // Create the NAV
    $navHTML .= <<<HTML
    <li><a href="#{$gameId}">$date</a></li>
HTML;
  }
  $navHTML .= '</ul>';

  return [$sectionHTML, $navHTML];
}

function createTableHTML($tableArray, $columnHeaders=[], $class='') {
  $tableHTML = '<table class="table table-condensed '.$class.'">';
  if (count($columnHeaders) == count($tableArray[0])) {
    $headers = implode('</th><th>', $columnHeaders);
    $tableHTML .= <<<HTML
        <thead>
        <tr>
          <th>{$headers}</th>
        </tr>
        </thead>
HTML;
  }
  $tableHTML .= '<tbody>';
  foreach ($tableArray as $row) {
    $tableHTML .= '<tr><td>'.implode('</td><td>', $row).'</td></tr>';
  }
  $tableHTML .= '</tbody></table>';
  return $tableHTML;
}

function createWinningsTable($class='') {
  global $stats;
  foreach ($stats['winnings'] as &$row) $row['total'] = '£'.$row['total'];
  return createTableHTML($stats['winnings'], ['Name', 'Total'], $class);
}

function createWinningsAndNetTable($class='') {
  global $stats;
  foreach ($stats['winningsAndNet'] as &$row) list($row['total'], $row['boughtIn'], $row['net']) = ['£'.$row['total'],'£'.$row['boughtIn'],'£'.$row['net'],];
  return createTableHTML($stats['winningsAndNet'], ['Name', 'Total', 'Bought In', 'Net', 'Rebuys'], $class);
}

function createNetTable($class='') {
  global $stats;
  foreach ($stats['net'] as &$row) $row['total'] = '£'.$row['total'];
  return createTableHTML($stats['net'], ['Name', 'Total'], $class);
}
function createGamesPlayedTable($class='') {
  global $stats;
  foreach ($stats['gamesWon2'] as &$row) $row['Win Percent'] = $row['Win Percent']. '%';
  return createTableHTML($stats['gamesWon2'], ['Name', 'Games Won', 'Games Played', 'Win Percent'], $class);
}

function createPointsTable($class='') {
  global $pointsStats;
  return createTableHTML($pointsStats['totalPoints'], ['Name', 'Total'], $class);

}

?>

<nav class="navbar navbar-inverse navbar-static-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="../index.html"><span class="glyphicon glyphicon-home"></span></a>
    </div>

  </div>
</nav>

<body data-spy="scroll" data-target=".scrollspy">
<div class="jumbotron">
  <div class="container">
    <h1>Culver Road Poker Night Results</h1>
    <h3><span class="fa fa-pencil"></span> Places recorded since December '15</h3>
    <h3><span class="fa fa-pencil"></span> Cash recorded since April '16</h3>
    <h3><span class="fa fa-pencil"></span> Last ever game 7th July 2016</h3>
  </div>
</div><!--end of .jumbotron-->

<div class="container">

  <div class="row">
    <div class="col-md-12">
      <h2>Stats</h2>
    </div>
    <div class="col-md-6 main_table">
      <h3>All Games Played:</h3>
      <?php echo createGamesPlayedTable(); ?>
      Total No. Games: <?php echo $stats['noOfGames'][0]['total']; ?>
    </div>
    <!--<div class="col-md-6 main_table">
      <h3>Winnings:</h3>
      <?php /*echo createWinningsTable(); */?>
      Total Won: <?php /*echo '£'.$stats['totalWinnings'][0]['total']; */?>
    </div>-->
    <div class="col-md-6 main_table">
      <h3>Cash Winnings:</h3>
      <?php echo createWinningsAndNetTable(); ?>
      Total Won: <?php echo '£'.$stats['totalWinnings'][0]['total']; ?> out of <?php echo $pointsStats['noOfGames']; ?> cash recorded games.<br>
      Total No. Rebuys: <?php echo $stats['noOfRebuys'][0]['total']; ?>
    </div>



  </div><!--end of .row-->

  <!--<div class="row">
    <div class="col-md-6 main_table">
      <h3>Net:</h3>
      <?php /*echo createNetTable(); */?>
    </div>
    <div class="col-md-6 main_table">
      <h3>Rebuys:</h3>
      <?php /*echo createTableHTML($stats['rebuys'], ['Name', 'Total'], ''); */?>
      Total No. Rebuys: <?php /*echo $stats['noOfRebuys'][0]['total']; */?>
    </div>
  </div>-->

  <div class="row">

    <div class="col-md-12">
      <h3>Points:</h3>
    </div>
    <div class="col-md-6">
      Points for each player are calculated using <a href="http://forums.homepokertourney.com/index.php/topic,28150.0.html" target="_blank">Dr. Neau's Tournament Formula</a>:
      $$\text{points} = \dfrac{\sqrt{n \times b \times b  / e}}{f + 1.0} $$
      where:<br>
      \(n\) = no. of players <br>
      \(b\) = buy-in cost  <br>
      \(e\) = individual player's expense (buy-in + rebuys) <br>
      \(f\) = individual player's finish. <br><br>
      <p>
        Players must have played at least half of the cash recorded games (<?php echo $pointsStats['gamesCounted']; ?>) to be counted and the top <?php echo $pointsStats['gamesCounted']; ?> scores for each player are added up to get the total.
      </p>


    </div>

    <div class="col-md-6">

      <?php echo createPointsTable(); ?>
    </div>





    <!--
$$\text{score} = \dfrac{\sqrt{n \times b \times b  / e}}{f + 1.0} \\

\begin{align}
&
\text{where:} \\

&n = \text{no. of players} \\
&b = \text{buy-in cost} \\
&e = \text{individual player's expense (buy-in + rebuys)} \\
&f = \text{individual player's finish}
\end{align}
 $$
-->



  </div>

  <div class="row results_table">

    <div class="col-md-3 scrollspy">
      <?php echo $navHTML; ?>
    </div>

    <div class="col-md-9">
      <h2>Results</h2>
      <?php echo $sectionHTML; ?>
    </div>

  </div><!--end of .row-->
</div><!--end of .container-->


<footer class="container">
  <div class="col-xs-12">
    <p class="pull-right"><a href="#">Back to top</a></p>
    <p>&copy; 2016 - finchmeister.co.uk</p>
  </div>
</footer>
</body>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-78908802-1', 'auto');
  ga('send', 'pageview');

</script>
</html>