<?php
require_once("dbnames.inc");
require_once($_dbconfig);
$urlarray = explode("/", $_SERVER['REQUEST_URI']);
$regioninput = isset($_GET['region']) ? $_GET['region'] : "None";
switch ($regioninput)
{
	case "NA":
		$region = "NA";
		break;
	case "EU":
		$region = "EU";
		break;
	default:
		$region = "None";
}
session_set_cookie_params(365*24*60*60);
session_start();

if($region == "None")
	require_once("default.php");
else
{
	require_once("teamlist.php");
	if(!isset($_SESSION[$region]))
		$_SESSION[$region] = array();
	if(!isset($_SESSION[$region][$week]))
	{
		$leftorder = range(0, $maxvotes - 1);
		shuffle($leftorder);
		$matchups = array();
		for($i = 0; $i < count($leftorder) - 1; $i++)
			$matchups[] = array($leftorder[$i], $leftorder[$i + 1]);
		$matchups[] = array($leftorder[$maxvotes - 1], $leftorder[0]);
		shuffle($matchups);
		$_SESSION[$region][$week] = (object) array('votes' => 0, 'matchups' => $matchups, 'favorite' => "");
	}

	if($_SESSION[$region][$week]->votes < $maxvotes)
		require_once("vote.php");
	elseif($_SESSION[$region][$week]->favorite === "")
		require_once("favorite.php");
	else
		require_once("ranking.php");
}
?>
