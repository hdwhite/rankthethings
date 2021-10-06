<?php
require_once("data/$week/matchdata.inc");

// If this is their first time here, create a matchup list
if(!isset($_SESSION['ranks']))
	$_SESSION['ranks'] = array();
if(!isset($_SESSION['ranks'][$week]))
	require_once("generate_matchups.php");

// If they've voted the max number of times, go to rankings
if($_SESSION['ranks'][$week]->votes >= $maxvotes)
	header("Location: $_rootpath/rankings");

# The vote page for each matchup. Here we load the entrants and have the user vote on them.
$entry1 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][0];
$entry2 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][1];
?>

<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("rankthethings.css");
		</style>
		<title><?=$topic ?> - Rank All the Things!</title>
	</head>
	<body>
		<div id="wrapper">
			<h2><?=$topic ?></h2>
			<form method="post" action="action.php">
				<input type="hidden" name="from" value="<?=$week ?>">
				<input type="hidden" name="entry1" value="<?=$entry1 ?>">
				<input type="hidden" name="entry2" value="<?=$entry2 ?>">
				<div class="flexy">
					<div class="flexobject">
						<button class="entry" type="submit" name="button1"><img src="data/<?=$week ?>/<?=$entrants[$entry1]['code'] ?>.<?=$extension ?>" height="240" width="240"><br><?=$entrants[$entry1]['name'] ?></button>
						<?php if($haslinks == 1) { ?>
						<br><a href="<?=$entrants[$team1]['name'] ?>" target="_blank">More information</a>
						<?php } ?>
					</div>
					<div class="flexobject">
						<button class="entry" type="submit" name="button2"><img src="data/<?=$week ?>/<?=$entrants[$entry2]['code'] ?>.<?=$extension ?>" height="240" width="240"><br><?=$entrants[$entry2]['name'] ?></button>
						<?php if($haslinks == 1) { ?>
						<br><a href="<?=$entrants[$team2]['name'] ?>" target="_blank">More information</a>
						<?php } ?>
					</div>
				</div>
				<p><button class="unknown" type="submit" name="button3">I don't know enough about these things to make a decision</button></p>
			</form>
			<p class="patreon"><a href="<?=$_rootpath ?>/faq" target="_blank">Frequently Asked Questions</a></p>
			<p class="patreon"><a href="<?=$_rootpath ?>/rankings" target="_blank">Current rankings</a></p>
		</div>
	</body>
</html>
