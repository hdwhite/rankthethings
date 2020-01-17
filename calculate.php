<?php
require_once("dbnames.inc");
require_once($_dbconfig);
if($argc < 2)
	exit();
$region = $argv[1];
require_once("teamlist.php");

$winmatrix = array();
foreach($teams as $curteam)
{
	$temp = array();
	foreach($teams as $secondteam)
		$temp[$secondteam["code"]] = 0;
	$teams[$curteam["code"]] = $temp;
}

$query = $mysqli->query("SELECT winner, loser, COUNT(*) AS num FROM $_db WHERE region='$region' AND week='$week' GROUP BY winner, loser");
while($currow = $query->fetch_assoc())
	$winmatrix[$currow['winner']][$currow['loser']] = $currow['num'];

$gamesmatrix = $winmatrix;
foreach($winmatrix as $team1 => $t1array)
	foreach($t1array as $team2 => $t1wins)
		$gamesmatrix[$team2][$team1] += $t1wins;
$teamwins = array();
foreach($winmatrix as $curteam => $curwins)
	$teamwins[$curteam] = array_sum($curwins);

$ratings = array();
foreach($winmatrix as $curteam => $temp)
	$ratings[$curteam] = 1;
$oldratings = $ratings;
$distance = 1000;

while($distance > pow(10, -10))
{
	foreach($oldratings as $curteam => $currating)
	{
		$newrating = 0;
		foreach($oldratings as $oppteam => $opprating)
		{
			if($curteam == $oppteam)
				continue;
			$newrating += $gamesmatrix[$curteam][$oppteam]/($currating + $opprating);
		}
		$ratings[$curteam] = $teamwins[$curteam]/$newrating;
	}
	$distance = 0;
	foreach($ratings as $curteam => $currating)
		$distance += pow($currating - $oldratings[$curteam], 2);
	$oldratings = $ratings;
}

$product = 1;
$average = 0;

foreach($ratings as $currating)
	$product *= $currating;
$multiplier = pow($product, -1/$maxvotes);

foreach($ratings as $currating)
	$average += 1/(1+$currating * $multiplier) - 1/2;

while(abs($average) > pow(10, -10))
{
	$aprime = 0;
	foreach($ratings as $currating)
		$aprime -= $currating/pow(1+$currating*$multiplier, 2);
	$multiplier = $multiplier - $average/$aprime;
	$average = 0;
	foreach($ratings as $currating)
		$average += 1/(1+$currating * $multiplier) - 1/2;
}

$scale = function($n) use ($multiplier) { return log($n * $multiplier) + 2; };
$ratings = array_map($scale, $ratings);
foreach($ratings as $i => $j)
	echo("$i: $j\n");
?>
