<?php
require_once("dbnames.inc");
require_once($_dbconfig);
session_start();
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
	header("Location: $_rootpath");
	exit();
}

$region = $_POST['from'];

if($region == "default")
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

require_once("teamlist.php");
if($region == "NA" || $region == "EU")
{
	if(!isset($_SESSION[$region][$week]) || $_SESSION[$region][$week]->votes >= $maxvotes)
	{
		header("Location: $_rootpath/$region");
		exit();
	}
	if(isset($_POST['button3']))
		$_SESSION[$region][$week]->votes++;
	else
	{
		$stmt = $mysqli->prepare("INSERT INTO $_db (region, week, winner, loser, ip) VALUES(?, ?, ?, ?, ?)");
		$stmt->bind_param("sisss", $region, $week, $winnercode, $losercode, $ip);
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
	header("Location: $_rootpath/$region");
	exit();
}
header("Location: $_rootpath");
