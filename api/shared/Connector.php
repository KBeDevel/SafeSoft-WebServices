<?php

$dbhost	= "localhost";
$dbuser	= "root";
$dbpass	= base64_decode('TmluamFib3kyNDEwMTk5Ny8=');
$dbname	= "ssdb";
$connector = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die(mysql_error());

?>
