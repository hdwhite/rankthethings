<?php
// Doing this on its own file so it's easier to manually edit if necessary
$startdate = new DateTime('2021-10-04 12:01:00', new DateTimeZone('America/New_York'));
$curtime = new DateTime();
$week = floor(($curtime->diff($startdate)->days)/7);
$week--;
?>
