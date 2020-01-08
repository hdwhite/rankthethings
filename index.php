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
	require_once("vote.php");
}
?>
