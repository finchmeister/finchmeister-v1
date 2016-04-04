<?php
include "scripts/login/header.php";
include "scripts/smoke-free/smokefreedb.php";


//setLastSmokeDate(1, '15/04/2017', $mysqli)
echo 'about to login';


var_dump(login('thefinchmeister@gmail.com', 'Workbooks123', $mysqli));

?>