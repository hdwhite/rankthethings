<?php
// Here we display the rankings
// To prevent server load, the calulations are not done every time this page is loaded
require_once("data/$week/matchdata.inc");
$query = $mysqli->query("SELECT week, entry, rating FROM (SELECT * FROM $_ratingdb ORDER BY timestamp DESC LIMIT 1000000) a GROUP BY week, entry ORDER BY week DESC, rating DESC, entry ASC");
$ratings = array();
for($i = 0; $i <= $week; $i++)
	$ratings[$i] = array();
while($curteam = $query->fetch_assoc())
	$ratings[$curteam['week']][$curteam['entry']] = $curteam['rating'];
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
		<script language="javascript">
			function toggle(targetId)
			{
				target = document.getElementById(targetId);
				if (target.style.display == "none")
					target.style.display="";
				else
					target.style.display="none";
			}
			</script>
	</head>
	<body>
		<div id="wrapper">
			<h2><?=$topic ?></h2>
			<table>
				<?php
				$i = 0;
				foreach($ratings[$week] as $curentry => $currating)
				{
					foreach($entrants as $e)
						if($e['code'] ==  $curentry)
							$fullname = $e['name'];
					printf("<tr class=\"row%d\"><th>%d</th><td style=\"width: 200px; text-align:left\">%s</td><td>%.3f</td></tr>", ++$i, $i, $fullname, $currating);
				}
				?>
			</table>
			<?php if(!isset($_SESSION['ranks']) || !isset($_SESSION['ranks'][$week]) || $_SESSION['ranks'][$week]->votes < $maxvotes) { ?>
			<p><a href="<?=$_rootpath ?>/vote">Take me back to the voting</a></p>
			<?php } ?>
			<p class="patreon">Rankings are likely to fluctuate, especially early in the week, so be sure to check back Monday for final results and another series of votes.</p>
			<p class="patreon"><a href="<?=$_rootpath ?>/faq" target="_blank">Frequently Asked Questions</a></p>
			<h2>Previous Weeks</h2>
			<p>Click on a topic to show the full rankings.</p>
			<?php
			for($curweek = $week-1; $curweek >= 0; $curweek--)
			{
				require_once("data/$curweek/matchdata.inc");
				echo("<h4><a href=\"javascript:toggle('week$curweek')\" style=\"text-decoration:none\">$topic</a></h4><table><thead>");
				$i = 0;
				foreach($ratings[$curweek] as $curentry => $currating)
				{
					foreach($entrants as $e)
						if($e['code'] ==  $curentry)
							$fullname = $e['name'];
					if($i == 3)
						echo("</thead><tbody id=\"week$curweek\" style=\"display:none\">");
					printf("<tr class=\"row%d\"><th>%d</th><td style=\"width: 200px; text-align:left\">%s</td><td>%.3f</td></tr>", ++$i, $i, $fullname, $currating);
				}
				echo("</tbody></table>");
			}
			?>
			<p class="patreon"><a href="https://patreon.com/hdwhite" target="_blank">Support me on Patreon!</a></p>
		</div>
	</body>
</html>
