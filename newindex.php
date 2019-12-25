<?php
require_once("dbnames.inc");
require_once($_dbconfig);
session_set_cookie_params(7*24*60*60);
session_start();
if(!isset($_SESSION['numvotes']))
	$_SESSION['numvotes'] = 0;
$teams = [["name" => "KT Rolster", "code" => "KT", "region" => "KR"],
          ["name" => "Afreeca Freecs", "code" => "AFS", "region" => "KR"],
		  ["name" => "Gen.G", "code" => "GEN", "region" => "KR"],
		  ["name" => "Royal Never Give Up", "code" => "RNG", "region" => "CN"],
		  ["name" => "Invictus Gaming", "code" => "IG", "region" => "CN"],
		  ["name" => "EDward Gaming", "code" => "EDG", "region" => "CN"],
		  ["name" => "Fnatic", "code" => "FNC", "region" => "EU"],
		  ["name" => "Team Vitality", "code" => "VIT", "region" => "EU"],
		  ["name" => "G2 Esports", "code" => "G2", "region" => "EU"],
		  ["name" => "Team Liquid", "code" => "TL", "region" => "NA"],
		  ["name" => "100 Thieves", "code" => "100", "region" => "NA"],
		  ["name" => "Cloud9", "code" => "C9", "region" => "NA"],
		  ["name" => "Flash Wolves", "code" => "FW", "region" => "LMS"],
		  ["name" => "MAD Team", "code" => "MAD", "region" => "LMS"],
		  ["name" => "G-Rex", "code" => "GRX", "region" => "LMS"],
		  ["name" => "Phong Vũ Buffalo", "code" => "PVB", "region" => "VN"],
		  ["name" => "KaBuM! e-Sports", "code" => "KBM", "region" => "BR"],
		  ["name" => "Gambit Esports", "code" => "GMB", "region" => "RU"],
		  ["name" => "DetonatioN FocusMe", "code" => "DFM", "region" => "JP"],
		  ["name" => "Infinity eSports CR", "code" => "INF", "region" => "LAN"],
		  ["name" => "Kaos Latin Gamers", "code" => "KLG", "region" => "LAS"],
		  ["name" => "Dire Wolves", "code" => "DW", "region" => "OCE"],
		  ["name" => "Ascension Gaming", "code" => "ASC", "region" => "SEA"],
		  ["name" => "SuperMassive eSports", "code" => "SUP", "region" => "TR"]];

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['numvotes'] < 25)
{
	if(isset($_POST['button3']))
	{
		$_SESSION['numvotes']++;
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
			$_SESSION['numvotes']++;
			echo("Foo");
		}
		elseif(isset($_POST['button2']))
		{
			$winner = $_POST['team2'];
			$winnercode = $teams[$winner]['code'];
			$loser = $_POST['team1'];
			$losercode = $teams[$loser]['code'];
			$stmt->execute();
			echo("Bar");
			$_SESSION['numvotes']++;
		}
		$stmt->close();
	}
}

$team1 = rand(0,23);
$team2 = ($team1 + rand(1,23)) % 24;
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<style type="text/css">
			@import url("lolworlds.css");
		</style>
		<title>2018 League of Legends World Championship: Pick Your Winners</title>
	</head>
	<body>
		<div id="wrapper">
			<h2>Select which team you believe is better</h2>
			<?php if($_SESSION['numvotes'] < 25) { ?>
			<form method="post" action="/lolworlds/newindex.php">
				<input type="hidden" name="team1" value="<?=$team1 ?>">
				<input type="hidden" name="team2" value="<?=$team2 ?>">
				<table>
					<tr>
						<td style="text-align:left;"><button class="team" type="submit" name="button2"><img src="images/<?=$teams[$team1]['code'] ?>.png" height="240" width="240"><br><?=$teams[$team1]['name'] ?> (<?=$teams[$team1]['region'] ?>)</button></td>
						<td width="99%"></td>
						<td style="text-align:right;"><button class="team" type="submit" name="button2"><img src="images/<?=$teams[$team2]['code'] ?>.png" height="240" width="240"><br><?=$teams[$team2]['name'] ?> (<?=$teams[$team2]['region'] ?>)</button></td>
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
