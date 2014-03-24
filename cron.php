<?php
require "scalene/Scalene.php";
$_->load->model("rainjacket");
$_->load->model("twilio");
$_->load->helper("strings");

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
$_->rainjacket->addZipsToDatabase();
echo "done!\n\n";
$users = $_->database->get("users", "dayTime = '$now' OR nightTime = '$now'");
if (!empty($users))
{
	echo "Starting to email ".count($users)." ".pluralize(count($users), "customer", "customers")."...\n";
	foreach ($users as $user)
	{
		date_default_timezone_set($user["timezone"]);
		$location = $_->database->get("zipcodes", "zipcode = '{$user["zipcode"]}'");
		$location = $location[0];
		echo "\tChecking Forecastio for their forecast...";
		if ($user["dayTime"] == $now)
		{
			$forecast = $_->rainjacket->getForecast($location["latitude"], $location["longitude"]);
			$data["isDay"] = true;
		}
		else
		{
			$forecast = $_->rainjacket->getForecast($location["lat"], $location["lng"], false);
			$data["isDay"] = false;
		}

		echo "done!\n";
		$data["forecast"] = $forecast;
		$data["city"] = $location["city"];
		$data["state"] = $location["state"];
		
		$body = $_->view->fetch("email", $data);
		echo "\tEmailing {$user["username"]} their forecast for {$location["city"]}, {$location["state"]}...";
		$_->email->send($user["username"], $user["email"], "Forecast for Today", $body);
		echo "done!\n";

		echo "\tTexting {$user["username"]} their forecast for {$location["city"]}, {$location["state"]}...";
		$_->twilio->sendText($user["phone"], $forecast);
		echo "done!\n";
	}
}

echo "\n";
echo "Done! The whole shebang took ".round($_->TimeSinceStart(), 2)."s\n";