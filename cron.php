<?php
require "scalene/Scalene.php";
$scalene->load->model("rainjacket");
$scalene->load->helper("strings");

echo "
 ____       _      ___   _   _       _      _       ____   _  __  _____   _____ 
|  _ \     / \    |_ _| | \ | |     | |    / \     / ___| | |/ / | ____| |_   _|
| |_) |   / _ \    | |  |  \| |  _  | |   / _ \   | |     | ' /  |  _|     | |  
|  _ <   / ___ \   | |  | |\  | | |_| |  / ___ \  | |___  | . \  | |___    | |  
|_| \_\ /_/   \_\ |___| |_| \_|  \___/  /_/   \_\  \____| |_|\_\ |_____|   |_|


";

echo "Adding zips to database...";
$scalene->rainjacket->AddZipsToDatabase();
echo "done!\n\n";
$users = $scalene->database->get("users", "time = '".date('Hi')."'");
if (!empty($users))
{
	echo "Starting to email ".count($users)." ".pluralize(count($users), "customer", "customers")."...\n";
	foreach ($users as $user)
	{
		$location = $scalene->database->get("zipcodes", "zipcode = '{$user["zipcode"]}'");
		$forecast = $scalene->rainjacket->GetForecast($location[0]["lat"], $location[0]["lng"]);
		echo "\tEmailing {$user["username"]} their forecast for {$location[0]["city"]}, {$location[0]["state"]}...";
		$scalene->email->send($user["username"], $user["email"], "Forecast for Today", $forecast);
		echo "done!\n";
	}
}

echo "\n";
echo "Done! The whole shebang took ".round($scalene->TimeSinceStart(), 2)."s\n";