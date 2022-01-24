<?php
// This page is called when a user pushes a button.
require_once("dbnames.inc");
require_once($_dbconfig);
require_once("getweek.php");
session_start();
$sessionid = session_id();

$from = $_GET['week'];
// Only accept votes if it's from the correct week
if($from != $week)
{
	echo("Wrong week: $from !== $week");
	exit();
}
require_once("data/$week/matchdata.inc");

// If there are too many votes or the vote isn't there, get out of here
if(!isset($_SESSION['ranks'][$week]) || $_SESSION['ranks'][$week]->votes >= $maxvotes)
{
	echo("Too many votes");
	exit();
}

// If you skip, then just add one to the number of votes
if($_GET['skip'] == 1)
{
	$_SESSION['ranks'][$week]->votes++;
	exit();
}

// Making sure that theses are the votes that are supposed to happen
$entry1 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][0];
$entry2 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][1];
$winner = $_GET['winner'];
$loser = $_GET['loser'];
if(($entry1 != $winner || $entry2 != $loser) && ($entry2 != $winner || $entry1 != $loser))
{
	echo("Invalid winner/loser");
	exit();
}

// Depending on who the user has as a winner, insert the result into the database
$stmt = $mysqli->prepare("INSERT INTO $_db (week, winner, loser, ip, sessionid) VALUES(?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $week, $winnercode, $losercode, $ip, $sessionid);
$ip = $_SERVER['REMOTE_ADDR'];
$winnercode = $entrants[$winner]['code'];
$losercode = $entrants[$loser]['code'];
$stmt->execute();
$_SESSION['ranks'][$week]->votes++;
$stmt->close();
echo($mysqli->error);
?>
