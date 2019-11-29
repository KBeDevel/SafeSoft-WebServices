<?php

require "../config/db.class.php";

$dbclass = new DBClass();
$dbclass::init();

$connector = $dbclass->link;

?>