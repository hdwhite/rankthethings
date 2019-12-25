<?php
require_once("dbnames.inc");
require_once($_dbconfig);
session_set_cookie_params(7*24*60*60);
session_start();
$teams = [["name" => "FunPlus Phoenix", "code" => "FPX", "region" => "CN"],
          ["name" => "Royal Never Give Up", "code" => "RNG", "region" => "CN"],
          ["name" => "Invictus Gaming", "code" => "IG", "region" => "CN"],
          ["name" => "SK Telecom T1", "code" => "SKT", "region" => "KR"],
          ["name" => "Griffin", "code" => "GRF", "region" => "KR"],
          ["name" => "DAMWON Gaming", "code" => "DWG", "region" => "KR"],
          ["name" => "G2 Esports", "code" => "G2", "region" => "EU"],
          ["name" => "Fnatic", "code" => "FNC", "region" => "EU"],
          ["name" => "Splyce", "code" => "SPY", "region" => "EU"],
          ["name" => "Team Liquid", "code" => "TL", "region" => "NA"],
          ["name" => "Cloud9", "code" => "C9", "region" => "NA"],
          ["name" => "Clutch Gaming", "code" => "CG", "region" => "NA"],
          ["name" => "J Team", "code" => "JT", "region" => "LMS"],
          ["name" => "ahq e-Sports Club", "code" => "AHQ", "region" => "LMS"],
          ["name" => "Hong Kong Attitude", "code" => "HKA", "region" => "LMS"],
          ["name" => "GAM Esports", "code" => "GAM", "region" => "VN"],
          ["name" => "Lowkey Esports.Vietnam", "code" => "LK", "region" => "VN"],
          ["name" => "Unicorns Of Love.CIS", "code" => "UOL", "region" => "CIS"],
          ["name" => "Isurus Gaming", "code" => "ISG", "region" => "LAT"],
          ["name" => "Royal Youth", "code" => "RY", "region" => "TR"],
          ["name" => "Flamengo eSports", "code" => "FLA", "region" => "BR"],
          ["name" => "DetonatioN FocusMe", "code" => "DFM", "region" => "JP"],
          ["name" => "MAMMOTH", "code" => "MMM", "region" => "OCE"],
          ["name" => "MEGA", "code" => "MEGA", "region" => "SEA"]];

if(!isset($_SESSION['votes']))
{
	$_SESSION['votes'] = 0;
	$leftorder = range(0, 23);
	shuffle($leftorder);
	$matchups = array();
	for($i = 0; $i < count($leftorder) - 1; $i++)
		$matchups[] = array($leftorder[$i], $leftorder[$i+1]);
	$matchups[] = array($leftorder[23], $leftorder[0]);
	shuffle($matchups);
	$_SESSION['matchups'] = $matchups;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['votes'] < 24)
{
	if(isset($_POST['button3']))
	{
		$_SESSION['votes']++;
	}
	else
	{
		$stmt = $mysqli->prepare("INSERT INTO $_db (winner, loser, ip) VALUES(?, ?, ?)");
		$stmt->bind_param("sss", $winnercode, $losercode, $ip);
		$ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_POST['button1']))
		{
			$winner = $_POST['team1'];
			$winnercode = $teams[$winner]['code'];
			$loser = $_POST['team2'];
			$losercode = $teams[$loser]['code'];
			$stmt->execute();
			$_SESSION['votes']++;
		}
		elseif(isset($_POST['button2']))
		{
			$winner = $_POST['team2'];
			$winnercode = $teams[$winner]['code'];
			$loser = $_POST['team1'];
			$losercode = $teams[$loser]['code'];
			$stmt->execute();
			$_SESSION['votes']++;
		}
		elseif(isset($_POST['button3']))
		{
			$_SESSION['votes']++;
		}
		$stmt->close();
	}
}
$team1 = $_SESSION['matchups'][$_SESSION['votes']][0];
$team2 = $_SESSION['matchups'][$_SESSION['votes']][1];
?>
<html>
	<head>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("lolworlds.css");
		</style>
		<title>2019 League of Legends World Championship: Pick Your Winners</title>
	</head>
	<body>
		<div id="wrapper">
			<h2>Select which team you believe would win</h2>
			<?php if($_SESSION['votes'] < 24) { ?>
			<form method="post" action="/lolworlds/">
				<input type="hidden" name="team1" value="<?=$team1 ?>">
				<input type="hidden" name="team2" value="<?=$team2 ?>">
				<table>
					<tr>
						<td style="text-align:left;"><button class="team" type="submit" name="button1"><img src="images/<?=$teams[$team1]['code'] ?>.png" height="240" width="240"><br><?=strtok($teams[$team1]['name'], ".") ?> (<?=$teams[$team1]['region'] ?>)</button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="team" type="submit" name="button2"><img src="images/<?=$teams[$team2]['code'] ?>.png" height="240" width="240"><br><?=strtok($teams[$team2]['name'], ".") ?> (<?=$teams[$team2]['region'] ?>)</button></td>
					</tr>
					<tr>
						<td style="text-align:center;"><a href="https://lol.gamepedia.com/<?=$teams[$team1]['name'] ?>" target="_blank">Gamepedia page</a></td>
						<td width="99%"></td>
						<td style="text-align:center;"><a href="https://lol.gamepedia.com/<?=$teams[$team2]['name'] ?>" target="_blank">Gamepedia page</a></td>
					</tr>
				</table>
				<p><button class="unknown" type="submit" name="button3">I don't know enough about these teams to make a decision</p>
			</form>
			<?php } else { ?>
			<h2>Thank you for your participation!</h2>
			<?php } ?>
		</div>
	</body>
</html>
