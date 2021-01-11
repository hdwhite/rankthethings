<?php
# This page is called when a user pushes a button.
require_once("dbnames.inc");
require_once($_dbconfig);
session_start();
$sessionid = session_id();
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
	header("Location: $_rootpath");
	exit();
}

$region = $_POST['from'];

# If we're going to a region, we direct the user there. 
if($region == "default" || $region == "ranking")
{
	if(isset($_POST['button1']))
	{
		header("Location: $_rootpath/NA");
		exit();
	}
	if(isset($_POST['button2']))
	{
		header("Location: $_rootpath/EU");
		exit();
	}
}

# Otherwise it's a vote
require_once("teamlist.php");
if($region == "NA" || $region == "EU")
{
	# If there are too many votes or the vote isn't there, get out of here
	if(!isset($_SESSION[$region][$week]) || $_SESSION[$region][$week]->votes >= $maxvotes)
	{
		header("Location: $_rootpath/$region");
		exit();
	}

	# If you skip, then just add one to the number of votes
	if(isset($_POST['button3']))
		$_SESSION[$region][$week]->votes++;
	else
	{
		# Depending on who the user has as a winner, insert the result into the database
		$stmt = $mysqli->prepare("INSERT INTO $_db (region, week, winner, loser, ip, sessionid) VALUES(?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sissss", $region, $week, $winnercode, $losercode, $ip, $sessionid);
		$ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_POST['button1']))
		{
			$winner = $_POST['team1'];
			$winnercode = $teams[$winner]['code'];
			$loser = $_POST['team2'];
			$losercode = $teams[$loser]['code'];
			$stmt->execute();
			$_SESSION[$region][$week]->votes++;
		}
		elseif(isset($_POST['button2']))
		{
			$winner = $_POST['team2'];
			$winnercode = $teams[$winner]['code'];
			$loser = $_POST['team1'];
			$losercode = $teams[$loser]['code'];
			$stmt->execute();
			$_SESSION[$region][$week]->votes++;
		}
		$stmt->close();
	}
	# Now go back to where they (hopefully) came from
	header("Location: $_rootpath/$region");
	exit();
}
header("Location: $_rootpath");
?>
