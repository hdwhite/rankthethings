<?php
# Here we calculate the rankings
# They're done using a cronjob because I don't want to cause load issues
require_once("dbnames.inc");
require_once($_dbconfig);

# It ensures that 1) it's not done via browser, and 2) that a parameter was in fact passed
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

# Here we get the number of times each team beat another in the current region and week, and add it to the win matrix
$runtime = date("Y-m-d H:i:s");
$query = $mysqli->query("SELECT winner, loser, COUNT(*) AS num FROM $_db WHERE region='$region' AND week='$week' GROUP BY winner, loser");
while($currow = $query->fetch_assoc())
	$winmatrix[$currow['winner']][$currow['loser']] = $currow['num'];

# And now we get the total number of games
$gamesmatrix = $winmatrix;
foreach($winmatrix as $team1 => $t1array)
	foreach($t1array as $team2 => $t1wins)
		$gamesmatrix[$team2][$team1] += $t1wins;

# And the total number of wins per team
$teamwins = array();
foreach($winmatrix as $curteam => $curwins)
	$teamwins[$curteam] = array_sum($curwins);

# Initial seeds of ratings
$ratings = array();
foreach($winmatrix as $curteam => $temp)
	$ratings[$curteam] = 1;
$oldratings = $ratings;
$distance = 1000;

# We iterate until the ratings are close enough
# In theory this could run into issues if one team has 0 wins or losses, but in practice that tends to not happen
while($distance > pow(10, -10))
{
	foreach($oldratings as $curteam => $currating)
	{
		# We use Maximum Likelihood to iterate over a team's rating.
		$newrating = 0;
		foreach($oldratings as $oppteam => $opprating)
		{
			if($curteam == $oppteam)
				continue;
			$newrating += $gamesmatrix[$curteam][$oppteam]/($currating + $opprating);
		}
		$ratings[$curteam] = $teamwins[$curteam]/$newrating;
	}

	# And now we figure out how much the new iteration changes the ratings
	$distance = 0;
	foreach($ratings as $curteam => $currating)
		$distance += pow($currating - $oldratings[$curteam], 2);
	$oldratings = $ratings;
}

$product = 1;
$average = 0;

# And now we normalise the numbers
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

# Taking the log to make the numbers more human-readable
$scale = function($n) use ($multiplier) { return log($n * $multiplier) + 2; };
$ratings = array_map($scale, $ratings);
foreach($ratings as $i => $j)
	echo("$i: $j\n");

# And now we store the results
$stmt = $mysqli->prepare("INSERT INTO $_ratingdb (region, week, timestamp, team, rating) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sissd", $region, $week, $runtime, $curteam, $currating);
foreach($ratings as $curteam => $currating)
	$stmt->execute();
$stmt->close();
?>
