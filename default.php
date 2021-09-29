<?php
// The main index page or if an invalid page is selected
// It just has buttons for the latest vote and the rankings
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
<!--			<h2>Put stuff here?</h2> -->
			<form method="post" action="action.php">
				<input type="hidden" name="from" value="default">
				<table>
					<tr>
						<td style="text-align:left;"><button class="entry" type="submit" name="button1"><img src="" height="240" width="240"><br>Latest Vote</button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="entry" type="submit" name="button2"><img src="" height="240" width="240"><br>Previous Rankings</button></td>
					</tr>
				</table>
			</form>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
