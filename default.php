<?php
# The main index page or if an invalid region is selected
# It just has buttons for each region's page
?>
<html>
	<head>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("lolranks.css");
		</style>
		<title>Crowdsourced League of Legends Power Rankings</title>
	</head>
	<body>
		<div id="wrapper">
			<h2>Select a region</h2>
			<form method="post" action="action.php">
				<input type="hidden" name="from" value="default">
				<table>
					<tr>
						<td style="text-align:left;"><button class="team" type="submit" name="button1"><img src="images/LCS.png" height="240" width="240"><br>North America (LCS)</button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="team" type="submit" name="button2" disabled><img src="images/LEC.png" height="240" width="240"><br>Europe (Coming Soon)</button></td>
					</tr>
						<tr>
						<td style="text-align:center;"><a href="https://lol.gamepedia.com/LCS/2021_Season/Lock_In" target="_blank">Gamepedia page</a></td>
						<td width="99%"></td>
						<td style="text-align:center;"><a href="https://lol.gamepedia.com/LEC/2021_Season/Spring_Season" target="_blank">Gamepedia page</a></td>
					</tr>
				</table>
			</form>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
