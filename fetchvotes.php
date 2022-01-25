<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once("dbnames.inc");
require_once("getweek.php");
require_once("data/$week/matchdata.inc");

// If this is their first time here, create a matchup list
if(!isset($_SESSION['ranks']))
	$_SESSION['ranks'] = array();
if(!isset($_SESSION['ranks'][$week]))
	require_once("generate_matchups.php");

// If they've voted the max number of times, go to rankings
if($_SESSION['ranks'][$week]->votes >= $maxvotes)
{
	echo("     ");
	exit;
}

# The vote page for each matchup. Here we load the entrants and have the user vote on them.
$entry1 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][0];
$entry2 = $_SESSION['ranks'][$week]->matchups[$_SESSION['ranks'][$week]->votes][1];
?>

<input type="hidden" name="week" value="<?=$week ?>">
<div class="flexy">
	<div class="flexobject">
		<button class="entry" type="submit" name="button1" onclick="vote(<?=$entry1 ?>, <?=$entry2 ?>)">
			<img src="data/<?=$week ?>/<?=$entrants[$entry1]['code'] ?>.<?=$extension ?>" height="240" width="240">
			<br><?=$entrants[$entry1]['name'] ?>
		</button>
		<?php if($haslinks == 1) { ?>
		<br><a href="<?=$entrants[$entry1]['link'] ?>" target="_blank">More information</a>
		<?php } elseif($haslinks == 2) { ?>
		<br><a href="javascript:toggle('info1')">More information</a>
		<div id="info1" style="display:none"><br>
			<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="280" height="210" type="text/html" src="<?=$entrants[$entry1]["embed"] ?>"></iframe>
		</div>
		<?php } ?>
	</div>
	<div class="flexobject">
		<button class="entry" type="submit" name="button2" onclick="vote(<?=$entry2 ?>, <?=$entry1 ?>)">
			<img src="data/<?=$week ?>/<?=$entrants[$entry2]['code'] ?>.<?=$extension ?>" height="240" width="240">
			<br><?=$entrants[$entry2]['name'] ?>
		</button>
		<?php if($haslinks == 1) { ?>
		<br><a href="<?=$entrants[$entry2]['link'] ?>" target="_blank">More information</a>
		<?php } elseif($haslinks == 2) { ?>
		<br><a href="javascript:toggle('info2')">More information</a>
		<div id="info2" style="display:none"><br>
			<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="280" height="210" type="text/html" src="<?=$entrants[$entry2]["embed"] ?>"></iframe>
		</div>
		<?php } ?>
	</div>
</div>
<p><button class="unknown" type="submit" name="button3" onclick="skipvote()">I don't know enough about these things to make a decision</button></p>
<div class="progressbar">
	<span style="width: <?=round(($_SESSION['ranks'][$week]->votes + 1)*100/$maxvotes, 0) ?>%"></span>
</div>
