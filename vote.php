<?php
require_once("data/$week/matchdata.inc");

// If this is their first time here, create a matchup list
if(!isset($_SESSION['ranks']))
	$_SESSION['ranks'] = array();
if(!isset($_SESSION['ranks'][$week]))
	require_once("generate_matchups.php");

# The vote page for each matchup. Here we load the entrants and have the user vote on them.
$entry1 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][0];
$entry2 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][1];
?>

<html>
	<head>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("rankthethings.css");
		</style>
		<title>Rank All the Things!</title>
	</head>
	<body>
		<div id="wrapper">
			<h2>Select which thing you like best</h2>
			<form method="post" action="action.php">
				<input type="hidden" name="from" value="<?=$week ?>">
				<input type="hidden" name="entry1" value="<?=$team1 ?>">
				<input type="hidden" name="entry2" value="<?=$team2 ?>">
				<table>
					<tr>
						<td style="text-align:left;"><button class="entry" type="submit" name="button1"><img src="images/<?=$entries[$entry1]['code'] ?>.png" height="240" width="240"><br><?=$entries[$entry1]['name'] ?></button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="entry" type="submit" name="button2"><img src="images/<?=$entries[$entry2]['code'] ?>.png" height="240" width="240"><br><?=$entries[$entry2]['name'] ?></button></td>
					</tr>
					<?php if($haslinks == 1) { ?>
					<tr>
						<td style="text-align:center;"><a href="https://en.wikipedia.org/wiki/<?=$teams[$team1]['name'] ?>" target="_blank">Wikipedia page</a></td>
						<td width="99%"></td>
						<td style="text-align:center;"><a href="https://en.wikipedia.org/wiki/<?=$teams[$team2]['name'] ?>" target="_blank">Wikipedia page</a></td>
					</tr>
					<?php } ?>
				</table>
				<p><button class="unknown" type="submit" name="button3">I don't know enough about these things to make a decision</button></p>
			</form>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
