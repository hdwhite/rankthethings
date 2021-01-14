<?php
# The user gets to (optionally) select a team they support.
# This is mainly done for analysis purposes.
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
			<h2>What team would you want to win the Lock In Tournament?</h2>
			<form method="post" action="favoriteaction.php">
				<input type="hidden" name="from" value="<?=$region ?>">
				<div style="margin:-50px 0">
				<table style="border-spacing:0 50px">
					<?php for($i = 0; $i < $maxvotes; $i += 2) { ?>
					<tr>
						<td style="text-align:left;"><button class="team" type="submit" name="<?=$teams[$i]['code'] ?>"><img src="images/<?=$teams[$i]['code'] ?>.png" height="240" width="240"><br><?=$teams[$i]['name'] ?></button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="team" type="submit" name="<?=$teams[$i+1]['code'] ?>"><img src="images/<?=$teams[$i+1]['code'] ?>.png" height="240" width="240"><br><?=$teams[$i+1]['name'] ?></button></td>
					</tr>
					<?php } ?>
				</table>
				</div>
				<p><button class="unknown" type="submit" name="button3">I don't have a favorite team in particular</button></p>
			</form>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
