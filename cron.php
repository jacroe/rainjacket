<?php
require "scalene/Scalene.php";
$_->load->model("rainjacket");
$_->load->model("pollen");
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

$users = $_->database->get("users", "(`dayTime` = '$now' OR `nightTime` = '$now') AND `sendBy` != 0");
if (!empty($users))
{
	echo "Starting to message ".count($users)." ".pluralize(count($users), "customer", "customers")."...\n";
	foreach ($users as $user)
	{
		date_default_timezone_set($user["timezone"]);
		$location = $_->database->get("zipcodes", "zipcode = '{$user["zipcode"]}'");
		$location = $location[0];
		echo "\tChecking Forecastio for their forecast...";
		if ($user["dayTime"] == $now)
		{
			$forecastData = $_->rainjacket->getForecast($location["latitude"], $location["longitude"]);
			$data["isDay"] = true;
		}
		else
		{
			$forecastData = $_->rainjacket->getForecast($location["latitude"], $location["longitude"], false);
			$data["isDay"] = false;
		}
		echo "done!\n";

		$forecastData = json_decode($forecastData, true);

		$data["user"] = $user["username"];
		$data["forecast"] = $forecastData["processed"]["forecast"];
		$data["lookingAhead"] = $forecastData["processed"]["lookingAhead"];
		$data["wind"] = $forecastData["processed"]["wind"];
		$data["alerts"] = $forecastData["processed"]["alerts"];
		$data["badHairDay"] = $forecastData["processed"]["badHairDay"];
		$data["city"] = $location["city"];
		$data["state"] = $location["state"];

		$data["includePollen"] = $user["pollenForecast"];
		$pollen = $_->pollen->getForZipcode($location["zipcode"]);
		$data["pollen"] = $pollen;
		if ($user["pollenForecast"])
			$forecastData["processed"]["pollen"] = $pollen;

		$data["includeBadHairDay"] = $user["badHairDay"];

		if ($user["sendBy"] == 1 or $user["sendBy"] == 3)
		{
			for ($i=1; $i < count($data["lookingAhead"]); $i++)
				$data["lookingAhead"][$i]["time"] = $_->rainjacket->prettyTime($data["lookingAhead"][$i]["time"]);
			$data["lookingAhead"][0]["time"] = "now";

			$body = $_->view->fetch("email", $data);
			echo "\tEmailing {$user["username"]} their forecast for {$location["city"]}, {$location["state"]}...";
			$_->email->send($user["username"], $user["email"], "Forecast for Today", $body);
			echo "done!\n";
		}

		if ($user["sendBy"] == 2 or $user["sendBy"] == 3)
		{
			echo "\tTexting {$user["username"]} their forecast for {$location["city"]}, {$location["state"]}...";
			$_->twilio->sendText($user["phone"], $forecastData["processed"]["forecast"]);
			echo "done!\n";
			if ($data["alerts"])
			{
				sleep(1); // Ensure that forecast arrives first
				echo "\tAlerts issued for customer. Sending second text warning them...";
				$_->twilio->sendText($user["phone"], "Oh, weather alerts have been issued for your area, as well. | therainjacket.com/forecast.php?user={$user["username"]}");
				echo "done!\n";
			}
			if ($data["includeBadHairDay"] and $data["badHairDay"])
			{
				echo "\tCustomer wanted hair forecast. Sending text...";
				$_->twilio->sendText($user["phone"], "Bad hair day! {$data["badHairDay"]} hair threatens you!");
				echo "done!\n";
			}
		}

		echo "\tStoring the data in the database...";
		$_->database->put("forecasts", array(
			"user"=>$user["username"],
			"raw"=>$forecastData["raw"],
			"processed"=>json_encode($forecastData["processed"])
		));
		echo "done!\n";
	}
}

echo "\n";
echo "Done! The whole shebang took ".round($_->TimeSinceStart(), 2)."s\n";