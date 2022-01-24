<?php
require_once("data/$week/matchdata.inc");
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script language="javascript">
			const xhttp = new XMLHttpRequest();
			$(document).ready(function()
			{
				xhttp.onload = function()
				{
					if(this.responseText == "     ")
						location.href = "<?=$_rootpath ?>/rankings";
					else if(this.responseText != "")
						document.getElementById("currentVote").innerHTML = this.responseText;
				}
				xhttp.open("GET", "fetchvotes.php", true);
				xhttp.send();
			});

			function vote(winner, loser)
			{
				xhttp.open("GET", "sendvote.php?week=<?=$week ?>&winner=" + winner + "&loser=" + loser, true);
				xhttp.send();
				console.log(this.responseText);
				xhttp.open("GET", "fetchvotes.php", true);
				xhttp.send();
			}

			function skipvote()
			{
				xhttp.open("GET", "sendvote.php?week=<?=$week ?>&skip=1", true);
				xhttp.send();
				console.log(this.responseText);
				xhttp.open("GET", "fetchvotes.php", true);
				xhttp.send();
			}

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
			<?php if(isset($description)) { ?>
			<p><?=$description ?></p>
			<?php } ?>
			<div id="currentVote"></div>
			<p class="patreon"><a href="<?=$_rootpath ?>/faq" target="_blank">Frequently Asked Questions</a></p>
			<p class="patreon"><a href="<?=$_rootpath ?>/rankings" target="_blank">Current rankings</a></p>
		</div>
	</body>
</html>
