<?php
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

require_once("teamlist.php");
if($region == "NA" || $region == "EU")
{
	if(!isset($_SESSION[$region][$week]) || $_SESSION[$region][$week]->favorite !== "")
	{
		header("Location: $_rootpath/$region");
		exit();
	}
	$stmt = $mysqli->prepare("INSERT INTO $_favoritedb (region, week, team, ip, sessionid) VALUES(?, ?, ?, ?, ?)");
	$stmt->bind_param("sisss", $region, $week, $favoriteteam, $ip, $sessionid);
	$ip = $_SERVER['REMOTE_ADDR'];

	for($i = 0; $i < $maxvotes; $i++)
	{
		$curcode = $teams[$i]['code'];
		if(isset($_POST[$curcode]))
		{
			$favoriteteam = $curcode;
			$stmt->execute();
			$_SESSION[$region][$week]->favorite = $favoriteteam;
			break;
		}
	}
	$_SESSION[$region][$week]->favorite = "None";

	$stmt->close();
	header("Location: $_rootpath/$region");
	exit();
}
header("Location: $_rootpath");
?>
