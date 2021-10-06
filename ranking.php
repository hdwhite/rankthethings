<?php
// Here we display the rankings
// To prevent server load, the calulations are not done every time this page is loaded
require_once("data/$week/matchdata.inc");
$query = $mysqli->query("SELECT entry, rating FROM $_ratingdb WHERE week='$week' ORDER BY timestamp DESC, rating DESC LIMIT " . count($entrants));
$ratings = array();
while($curteam = $query->fetch_assoc())
	$ratings[$curteam['entry']] = $curteam['rating'];
?>
<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("rankthethings.css");
			table { margin-left: auto; margin-right: auto; width: auto; }
			td,th { padding: 2px 10px; }
			th { text-align: right; }
			.row1 { color: gold; font-weight: bold; }
			.row2 { color: silver; font-weight: bold; }
			.row3 { color: #8C7853; font-weight: bold; }
		</style>
		<title>Rankings of the Things!</title>
	</head>
	<body>
		<div id="wrapper">
			<h2><?=$topic ?></h2>
			<table>
				<?php
				$i = 0;
				foreach($ratings as $curentry => $currating)
				{
					foreach($entrants as $e)
						if($e['code'] ==  $curentry)
							$fullname = $e['name'];
					printf("<tr class=\"row%d\"><th>%d</th><td style=\"width: 200px\">%s</td><td>%.3f</td></tr>", ++$i, $i, $fullname, $currating);
				}
				?>
			</table>
			<?php if(!isset($_SESSION['ranks']) || !isset($_SESSION['ranks'][$week]) || $_SESSION['ranks'][$week]->votes < $maxvotes) { ?>
			<p><a href="<?=$_rootpath ?>/vote">Take me back to the voting</a></p>
			<?php } ?>
			<p class="patreon">Rankings are likely to fluctuate, especially early in the week, so be sure to check back Monday for final results and another series of votes.</p>
			<p class="patreon"><a href="<?=$_rootpath ?>/faq" target="_blank">Frequently Asked Questions</a></p>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
