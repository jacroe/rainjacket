<?php
require "scalene/Scalene.php";
$scalene->load->model("rainjacket");
$scalene->load->model("twilio");
$scalene->load->helper("strings");

date_default_timezone_set("UTC");
$now = date('Hi');
echo "
 ____       _      ___   _   _       _      _       ____   _  __  _____   _____ 
|  _ \     / \    |_ _| | \ | |     | |    / \     / ___| | |/ / | ____| |_   _|
| |_) |   / _ \    | |  |  \| |  _  | |   / _ \   | |     | ' /  |  _|     | |  
|  _ <   / ___ \   | |  | |\  | | |_| |  / ___ \  | |___  | . \  | |___    | |  
|_| \_\ /_/   \_\ |___| |_| \_|  \___/  /_/   \_\  \____| |_|\_\ |_____|   |_|


";

echo "Adding zips to database...";
$scalene->rainjacket->addZipsToDatabase();
echo "done!\n\n";
$users = $scalene->database->get("users", "dayTime = '$now' OR nightTime = '$now'");
if (!empty($users))
{
	echo "Starting to email ".count($users)." ".pluralize(count($users), "customer", "customers")."...\n";
	foreach ($users as $user)
	{
		date_default_timezone_set($user["timezone"]);
		$location = $scalene->database->get("zipcodes", "zipcode = '{$user["zipcode"]}'");
		$location = $location[0];
		echo "\tChecking Forecastio for their forecast...";
		if ($user["dayTime"] == $now)
		{
			$forecast = $scalene->rainjacket->getForecast($location["lat"], $location["lng"]);
			$data["isDay"] = true;
		}
		else
		{
			$forecast = $scalene->rainjacket->getForecast($location["lat"], $location["lng"], false);
			$data["isDay"] = false;
		}

		echo "done!\n";
		$data["forecast"] = $forecast;
		$data["city"] = $location["city"];
		$data["state"] = $location["state"];
		
		$body = $scalene->view->fetch("email", $data);
		echo "\tEmailing {$user["username"]} their forecast for {$location["city"]}, {$location["state"]}...";
		$scalene->email->send($user["username"], $user["email"], "Forecast for Today", $body);
		echo "done!\n";

		echo "\tTexting {$user["username"]} their forecast for {$location["city"]}, {$location["state"]}...";
		$scalene->twilio->sendText($user["phone"], $forecast);
		echo "done!\n";
	}
}

echo "\n";
echo "Done! The whole shebang took ".round($scalene->TimeSinceStart(), 2)."s\n";