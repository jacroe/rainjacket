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
	if (!validate_phone($_POST["phone"]))
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
			"sendBy"=>$_POST["sendBy"]
		), "username = '$user'");
		$data["errors"][] = array("title"=>"Done!", "body"=>"Those settings were updated like a boss.", "type"=>"success");
	}
}

$data["user"] = $_->users->getUser();
$data["user"]["dayTime"] = date_timezoneConvert($data["user"]["dayTime"]." UTC", $data["user"]["timezone"], "g:iA");
$data["user"]["nightTime"] = date_timezoneConvert($data["user"]["nightTime"]." UTC", $data["user"]["timezone"], "g:iA");
$_->view->display("settings", $data);