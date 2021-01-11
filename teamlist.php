<?php
# Here we keep track of the current week as well as which teams are in which region.
$week = 1;
$active = 0;
if($region == "NA")
{
	$teams = [["name" => "100 Thieves",          "code" => "100"],
			  ["name" => "Cloud9",               "code" => "C9"],
			  ["name" => "Counter Logic Gaming", "code" => "CLG"],
			  ["name" => "Dignitas",             "code" => "DIG"],
			  ["name" => "Evil Geniuses",        "code" => "EG"],
			  ["name" => "FlyQuest",             "code" => "FQ"],
			  ["name" => "Golden Guardians",     "code" => "GG"],
			  ["name" => "Immortals",            "code" => "IMT"],
			  ["name" => "Team Liquid",          "code" => "TL"],
			  ["name" => "TSM",                  "code" => "TSM"]];
	$maxvotes = 10;
}
elseif($region == "EU")
{
	$teams = [["name" => "Astralis",       "code" => "AST"],
	          ["name" => "Excel Esports",  "code" => "XL"],
			  ["name" => "FC Schalke 04",  "code" => "S04"],
			  ["name" => "Fnatic",         "code" => "FNC"],
			  ["name" => "G2 Esports",     "code" => "G2"],
			  ["name" => "MAD Lions",      "code" => "MAD"],
			  ["name" => "Misfits Gaming", "code" => "MSF"],
			  ["name" => "Rogue",          "code" => "RGE"],
			  ["name" => "SK Gaming",      "code" => "SK"],
			  ["name" => "Team Vitality",  "code" => "VIT"]];
	$maxvotes = 10;
}
?>
