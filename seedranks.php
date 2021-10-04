<?php
// This adds default rankings for a given week
if($argc < 2)
	exit();
require_once("dbnames.inc");
require_once($_dbconfig);
$week = $argv[1];
require_once("data/$week/matchdata.inc");

$runtime = date("Y-m-d H:i:s");
$currating = 5;
$stmt = $mysqli->prepare("INSERT INTO $_ratingdb (week, timestamp, entry, rating) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issd", $week, $runtime, $curcode, $currating);
foreach($entrants as $curentry)
{
	$curcode = $curentry['code'];
	$stmt->execute();
}
$stmt->close();
?>
