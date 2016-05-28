<!DOCTYPE html>
<!-- Last commit: $Id$ -->
<!-- Version Location: $HeadURL$ -->
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

</head>
<style>
  @import url(http://fonts.googleapis.com/css?family=Raleway:400,700);
  body {
    background: lightblue;
    position: relative;
    line-height: 1.5;
    font-family: 'Raleway', sans-serif;
  }

  h1,
  h2 {
    font-weight: 700;
  }

  h3 span {
    color: #286090;
  }

  .jumbotron {
    border-radius: 0px;
    background: #5fb3ce;
    margin-bottom: 0;
  }

  .jumbotron button {
    margin-top: 10px;
  }

  section {
    padding: 40px 0;
    border-bottom: 1px solid #c1e1ec;
  }

  #web-development,
  #mobile-development {
    padding-bottom: 0;
  }

  section:last-child {
    border-bottom: none;
  }

  .nav {
    background: #4baac8;
  }

  .nav a {
    color: black;
    font-style: italic;
  }

  .nav li a:hover,
  .nav li a:focus {
    background: #86c5da;
  }

  .nav .active {
    font-weight: bold;
    background: #72bcd4;
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
    border-top: 1px solid #c1e1ec;
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
    border-top: 1px solid #c1e1ec;
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
$stats = getStats();
list($sectionHTML, $navHTML) = createHTMLSectionsAndNav($resultsArray);

function createHTMLTable($tableArray) {
  $newStyle = $tableArray[0]['winnings'] != null ? 1 : 0;
  if ($newStyle) {
    $extraHeaders = <<<HTML
                        <th>Winnings</th>
                        <th>Rebuys</th>
                        <th>Bought In</th>
                        <th>Net</th>
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

function createNetTable($class='') {
  global $stats;
  foreach ($stats['net'] as &$row) $row['total'] = '£'.$row['total'];
  return createTableHTML($stats['net'], ['Name', 'Total'], $class);
}

?>


<body data-spy="scroll" data-target=".scrollspy">
<div class="jumbotron">
  <div class="container">
    <h1>Culver Road Poker Night Results</h1>
    <h3><span class="fa fa-pencil"></span> Places recorded since Dec '15</h3>
    <h3><span class="fa fa-pencil"></span> Cash recorded since April '15</h3>
  </div>
</div><!--end of .jumbotron-->

<div class="container">

  <div class="row">
    <div class="col-sm-12">
      <h2>Stats</h2>
    </div>
    <div class="col-sm-6">

      <h3>Winnings:</h3>
      <?php echo createWinningsTable(); ?>
      Total Won: <?php echo '£'.$stats['totalWinnings'][0]['total']; ?>


      <h3>Net:</h3>
      <?php echo createNetTable(); ?>

    </div>
    <div class="col-sm-6">
      <h3>Games Won:</h3>
      <?php echo createTableHTML($stats['gamesWon'], ['Name', 'Total'], ''); ?>
      Total No. Games: <?php echo $stats['noOfGames'][0]['total']; ?>

      <h3>Rebuys:</h3>
      <?php echo createTableHTML($stats['rebuys'], ['Name', 'Total'], ''); ?>
      Total No. Rebuys: <?php echo $stats['noOfRebuys'][0]['total']; ?>

    </div>
  </div><!--end of .row-->

  <div class="row">

    <div class="col-md-3 scrollspy">
      <?php echo $navHTML; ?>
    </div>

    <div class="col-md-9">
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



</body>
</html>