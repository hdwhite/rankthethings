<?php
# Here we display the rankings
# To prevent server load, the calulations are not done every time this page is loaded
$query = $mysqli->query("SELECT team, rating FROM $_ratingdb WHERE region='$region' AND week='$week' ORDER BY timestamp DESC, rating DESC LIMIT 10");
$ratings = array();
while($curteam = $query->fetch_assoc())
	$ratings[$curteam['team']] = $curteam['rating'];
?>
<html>
	<head>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("lolranks.css");
			table { margin-left: auto; margin-right: auto; width: auto; }
			td,th { padding: 2px 10px; }
			th { text-align: right; }
			.row1 { color: gold; font-weight: bold; }
			.row2 { color: silver; font-weight: bold; }
			.row3 { color: #8C7853; font-weight: bold; }
		</style>
		<title>Crowdsourced League of Legends Power Rankings</title>
	</head>
	<body>
		<div id="wrapper">
			<h2>Current Power Rankings</h2>
			<p class="patreon">(Note: Rankings will not be accurate for the first hour or so. Please check back later for numbers that make more sense.)</p>
			<table>
				<?php
				$i = 0;
				foreach($ratings as $curteam => $currating)
				{
					foreach($teams as $t)
						if($t['code'] ==  $curteam)
							$fullname = $t['name'];
					printf("<tr class=\"row%d\"><th>%d</th><td style=\"width: 200px\">%s</td><td>%.3f</td></tr>", ++$i, $i, $fullname, $currating);
				}
				?>
			</table>
			<h2>Thank you for participating!</h2>
			<form method="post" action="action.php">
				<input type="hidden" name="from" value="ranking">
				<?php if($region == "EU") { ?>
					<button class="team" type="submit" name="button1"><img src="images/LCS.png" height="240" width="240"><br>Switch to NA</button></td>
				<?php } elseif($region == "NA") { ?>
					<button class="team" type="submit" name="button2"><img src="images/LEC.png" height="240" width="240"><br>Switch to EU</button></td>
				<?php } ?>
			</form>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
