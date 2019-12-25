<?php
require_once("dbnames.inc");
require_once($_dbconfig);
$rawvotes = array();
$query = $mysqli->query("SELECT winner, loser, COUNT(*) AS num FROM $lockdb GROUP BY winner, loser");

?>
