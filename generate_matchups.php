<?php
	// Randomly generate a series of matchups and store it in session data
	// The goal is to have approximate parity in terms of how often each matchup shows up
	
	// We start by shuffling a list of size equal to the number of entries
	$numentries = count($entrants);
	$remaining = $maxvotes;
	$leftorder = range(0, $numentries);
	shuffle($leftorder);
	$matchups = array();

	// We pair off the 1st and 2nd entries, 3rd and 4th, and so on
	for($i = 0; $i < floor($numentries/2); $i++)
	{
		$matchups[] = array($leftorder[2*$i], $leftorder[2*$i+1]);
		$remaining--;
		if($remaining == 0) break;
	}

	// Next we'll put in the last and first (also useful if there's an odd number)
	if($remaining > 0)
	{
		$matchups[] = array($leftorder[$numentries - 1], $leftorder[0]);
		$remaining--;
	}

	// Now we'll go back and do 2nd and 3rd, 4th and 5th, etc.
	for($i = 0; $i < $numentries/2 - 1; $i++)
	{
		if($remaining == 0) break;
		$matchups[] = array($leftorder[2*$i+1], $leftorder[2*$i+2]);
		$remaining--;
	}

	// At this point, each entry will have matchups with the ones ahead and behind them on the list.
	// From now on, each entry will get paird with the one 2 entries over (than 3, than 4 if necessary)
	// This ensures there will be no repeat matchups
	
	for($gap = 2; $gap <= floor($numentries/2); $gap++)
	{
		for($i = 0; $i < $numentries; $i++)
		{
			if($remaining == 0) break 2;
			$matchups[] = array($leftorder[$i], $leftorder[($i + $gap) % $numentries]);
			$remaining--;
		}
	}

	// And now we shuffle and store them
	shuffle($matchups);
	$_SESSION['ranks'][$week] = (object) array('votes' => 0, 'matchups' => $matchups);
?>
