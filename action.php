<?php
// This page is called when a user pushes a button.
require_once("dbnames.inc");
require_once($_dbconfig);
require_once("getweek.php");
session_start();
$sessionid = session_id();
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
	header("Location: $_rootpath");
	exit();
}

$from = $_POST['from'];

// Only accept votes if it's from the correct week
if($from == $week)
{
	require_once("data/$week/matchdata.inc");
	
	// If there are too many votes or the vote isn't there, get out of here
	if(!isset($_SESSION['ranks'][$week]) || $_SESSION['ranks'][$week]->votes >= $maxvotes)
	{
		header("Location: $_rootpath/vote");
		exit();
	}

	// If you skip, then just add one to the number of votes
	if(isset($_POST['button3']))
		$_SESSION['ranks'][$week]->votes++;
	else
	{
		// Depending on who the user has as a winner, insert the result into the database
		$stmt = $mysqli->prepare("INSERT INTO $_db (week, winner, loser, ip, sessionid) VALUES(?, ?, ?, ?, ?)");
		$stmt->bind_param("issss", $week, $winnercode, $losercode, $ip, $sessionid);
		$ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_POST['button1']))
		{
			$winner = $_POST['entry1'];
			$winnercode = $entrants[$winner]['code'];
			$loser = $_POST['entry2'];
			$losercode = $entrants[$loser]['code'];
			$stmt->execute();
			$_SESSION['ranks'][$week]->votes++;
		}
		elseif(isset($_POST['button2']))
		{
			$winner = $_POST['entry2'];
			$winnercode = $entrants[$winner]['code'];
			$loser = $_POST['entry1'];
			$losercode = $entrants[$loser]['code'];
			$stmt->execute();
			$_SESSION['ranks'][$week]->votes++;
		}
		$stmt->close();
	}
	echo($mysqli->error);
	// Now go back to where they (hopefully) came from
	header("Location: $_rootpath/$vote");
	exit();
}
header("Location: $_rootpath");
?>
