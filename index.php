<?php
// Index page. All the pages call this one.

require_once("getweek.php");

// Include database stuff.
require_once("dbnames.inc");
require_once($_dbconfig);
$urlarray = explode("/", $_SERVER['REQUEST_URI']);

// We need to figure out which page to bring up
switch ($urlarray[2])
{
	case "vote":
		$page = "vote";
		break;
	case "rankings":
		$page = "rankings";
		break;
	default:
		$page = "index";
}
session_set_cookie_params(365*24*60*60);
session_start();

# Now we figure out what page to display
if($page == "index")
	require_once("default.php");
elseif($page == "vote")
	require_once("vote.php");
else
	require_once("rankings.php");
?>
