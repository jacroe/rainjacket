<?php
require "scalene/Scalene.php";
$_->load->core("users");
$_->load->helper("validator");
$_->load->helper("date");

if (!$user = $_->users->userLoggedIn())
	header("Location: login.php");

if ($_POST)
{
	if (!validate_email($_POST["email"]))
		$data["errors"][] = array("title"=>"Email invalid", "body"=>"Please enter a valid email.");
	if (!validate_zipcode($_POST["zipcode"]))
		$data["errors"][] = array("title"=>"Zipcode invalid", "body"=>"Please enter a valid US zipcode.");
	if (!$_POST["sendTimeDay"] || !$_POST["sendTimeNight"])
		$data["errors"][] = array("title"=>"Times can't be empty", "body"=>"Please enter a time for both fields.");
	if (!validate_time($_POST["sendTimeDay"]) || !validate_time($_POST["sendTimeNight"]))
		$data["errors"][] = array("title"=>"Times are invalid", "body"=>"Please enter a valid time for both fields.");
	if (!$_POST["phone"])
	{
		if ($_POST["sendBy"] == 2 or $_POST["sendBy"] == 3)
		{
			$_POST["sendBy"] = 1;
			$data["infos"][] = array("title"=>"No phone number entered", "body"=>"Since you didn't enter a phone number, we can only send your forecast by email.", "type"=>"info");
		}
	}
	elseif (!validate_phone($_POST["phone"]))
		$data["errors"][] = array("title"=>"Phone number invalid", "body"=>"Please enter a valid phone number.");
	if (empty($data["errors"]))
	{
		$_->database->update("users", array(
			"email"=>$_POST["email"],
			"phone"=>$_POST["phone"],
			"zipcode"=>$_POST["zipcode"],
			"timezone"=>$_POST["timezone"],
			"dayTime"=>date_timezoneConvert($_POST["sendTimeDay"]." ".$_POST["timezone"]),
			"nightTime"=>date_timezoneConvert($_POST["sendTimeNight"]." ".$_POST["timezone"]),
			"pollenForecast"=>(int)$_POST["pollenForecast"],
			"sendBy"=>$_POST["sendBy"]
		), "username = '$user'");
		$data["infos"][] = array("title"=>"Done!", "body"=>"Those settings were updated like a boss.", "type"=>"success");
	}
}

$data["user"] = $_->users->getUser();
$data["user"]["dayTime"] = date_timezoneConvert($data["user"]["dayTime"]." UTC", $data["user"]["timezone"], "g:iA");
$data["user"]["nightTime"] = date_timezoneConvert($data["user"]["nightTime"]." UTC", $data["user"]["timezone"], "g:iA");
$_->view->display("settings", $data);