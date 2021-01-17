<?php
# Index page. All the pages call this one.

# Include database stuff.
require_once("dbnames.inc");
require_once($_dbconfig);
$urlarray = explode("/", $_SERVER['REQUEST_URI']);

# /NA/ is actually ?region=NA, so get the region name if it exists
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

# Now we figure out what page to display
if($region == "None")
	require_once("default.php");
else
{
	# Here we pre-generate a list of 10 matchups for each user
	# Essentially we shuffle up the list of teams and have each one match up with the two adjacent ones
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

	# Now we need to figure out how what page to show depending on where they are in the process
	if($active === 0)
		require_once("ranking.php");
	elseif($_SESSION[$region][$week]->votes < $maxvotes)
		require_once("vote.php");
	elseif($_SESSION[$region][$week]->favorite === "")
		require_once("favorite.php");
	else
		require_once("ranking.php");
}
?>
