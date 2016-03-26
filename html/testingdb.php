<?php
include "scripts/login/header.php";
include "scripts/smoke-free/smokefreedb.php";


//setLastSmokeDate(1, '15/04/2017', $mysqli)

echo getNoOfDaysSinceLastSmoke(1, $mysqli);
setLastSmokeDate(5, '23/03/2016', $mysqli);
echo getNoOfDaysSinceLastSmoke(5, $mysqli);

?>