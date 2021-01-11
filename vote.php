<?php
# The vote page for each matchup. Here we load the teams and have trhe user vote on them.
$team1 = $_SESSION[$region][$week]->matchups[$_SESSION[$region][$week]->votes][0];
$team2 = $_SESSION[$region][$week]->matchups[$_SESSION[$region][$week]->votes][1];
?>

<html>
	<head>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("lolranks.css");
		</style>
		<title>League of Legends <?=$region ?> Power Rankings</title>
	</head>
	<body>
		<div id="wrapper">
			<h2>Select which team you think is better</h2>
			<form method="post" action="action.php">
				<input type="hidden" name="from" value="<?=$region ?>">
				<input type="hidden" name="team1" value="<?=$team1 ?>">
				<input type="hidden" name="team2" value="<?=$team2 ?>">
				<table>
					<tr>
						<td style="text-align:left;"><button class="team" type="submit" name="button1"><img src="images/<?=$teams[$team1]['code'] ?>.png" height="240" width="240"><br><?=$teams[$team1]['name'] ?></button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="team" type="submit" name="button2"><img src="images/<?=$teams[$team2]['code'] ?>.png" height="240" width="240"><br><?=$teams[$team2]['name'] ?></button></td>
					</tr>
					<tr>
						<td style="text-align:center;"><a href="https://lol.gamepedia.com/<?=$teams[$team1]['name'] ?>" target="_blank">Gamepedia page</a></td>
						<td width="99%"></td>
						<td style="text-align:center;"><a href="https://lol.gamepedia.com/<?=$teams[$team2]['name'] ?>" target="_blank">Gamepedia page</a></td>
					</tr>
				</table>
				<p><button class="unknown" type="submit" name="button3">I don't know enough about these teams to make a decision</button></p>
			</form>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
