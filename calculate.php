<?php
# Here we calculate the rankings
# They're done using a cronjob because I don't want to cause load issues
require_once("dbnames.inc");
require_once($_dbconfig);

// This ensures that it's not done via browser
if($argc < 1)
	exit();
require_once("getweek.php");
require_once("data/$week/matchdata.inc");

$winmatrix = array();
foreach($entrants as $curentry)
{
	$temp = array();
	foreach($entrants as $secondentry)
		$temp[$secondentry["code"]] = 0;
	$winmatrix[$curentry["code"]] = $temp;
}

// Here we get the number of times each entry beat another in the current week, and add it to the win matrix
$runtime = date("Y-m-d H:i:s");
$query = $mysqli->query("SELECT winner, loser, COUNT(*) AS num FROM $_db WHERE week='$week' GROUP BY winner, loser");
while($currow = $query->fetch_assoc())
	$winmatrix[$currow['winner']][$currow['loser']] = $currow['num'];

// And now we get the total number of games
$gamesmatrix = $winmatrix;
foreach($winmatrix as $entry1 => $e1array)
	foreach($e1array as $entry2 => $e1wins)
		$gamesmatrix[$entry2][$entry1] += $e1wins;

# And the total number of wins per entry
$entrywins = array();
foreach($winmatrix as $curentry => $curwins)
	$entrywins[$curentry] = array_sum($curwins);

# Initial seeds of ratings
$ratings = array();
foreach($winmatrix as $curentry => $temp)
	$ratings[$curentry] = 1;
$oldratings = $ratings;
$distance = 1000;

# We iterate until the ratings are close enough
# In theory this could run into issues if one entry has 0 wins or losses, but in practice that tends to not happen
while($distance > pow(10, -10))
{
	foreach($oldratings as $curentry => $currating)
	{
		# We use Maximum Likelihood to iterate over a entry's rating.
		$newrating = 0;
		foreach($oldratings as $oppentry => $opprating)
		{
			if($curentry == $oppentry)
				continue;
			$newrating += $gamesmatrix[$curentry][$oppentry]/($currating + $opprating);
		}
		$ratings[$curentry] = $entrywins[$curentry]/$newrating;
	}

	# And now we figure out how much the new iteration changes the ratings
	$distance = 0;
	foreach($ratings as $curentry => $currating)
		$distance += pow($currating - $oldratings[$curentry], 2);
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
$scale = function($n) use ($multiplier) { return log($n * $multiplier) + 5; };
$ratings = array_map($scale, $ratings);

echo("--- New run at $runtime ---");
print_r($winmatrix);
print_r($gamesmatrix);

arsort($ratings);
foreach($ratings as $i => $j)
	echo("$i: $j\n");

# And now we store the results
$stmt = $mysqli->prepare("INSERT INTO $_ratingdb (week, timestamp, entry, rating) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issd", $week, $runtime, $curentry, $currating);
foreach($ratings as $curentry => $currating)
	$stmt->execute();
$stmt->close();
?>
