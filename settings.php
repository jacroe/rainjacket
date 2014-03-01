<?php
require "scalene/Scalene.php";
$scalene->load->core("users");
$scalene->load->helper("validator");
$scalene->load->helper("date");

if (!$user = $scalene->users->userLoggedIn())
	header("Location: login.php");

if ($_POST)
{
	if (!validate_email($_POST["email"]))
		$data["errors"][] = array("title"=>"Email invalid", "body"=>"Please enter a valid email.");
	if (!validate_zipcode($_POST["zipcode"]))
		$data["errors"][] = array("title"=>"Zipcode invalid", "body"=>"Please enter a valid US zipcode.");
	if (!$_POST["emailDaySendTime"] || !$_POST["emailNightSendTime"])
		$data["errors"][] = array("title"=>"Times can't be empty", "body"=>"Please enter a time for both fields.");
	if (empty($data["errors"]))
	{
		$scalene->database->update("users", array(
			"email"=>$_POST["email"],
			"zipcode"=>$_POST["zipcode"],
			"timezone"=>$_POST["timezone"],
			"dayTime"=>date_timezoneConvert($_POST["emailDaySendTime"]." ".$_POST["timezone"]),
			"nightTime"=>date_timezoneConvert($_POST["emailNightSendTime"]." ".$_POST["timezone"])
		), "username = '$user'");
		$data["errors"][] = array("title"=>"Done!", "body"=>"Those settings were updated like a boss.", "type"=>"success");
	}
}

$data["user"] = $scalene->users->getUser();
$data["user"]["dayTime"] = date_timezoneConvert($data["user"]["dayTime"]." UTC", $data["user"]["timezone"], "g:iA");
$data["user"]["nightTime"] = date_timezoneConvert($data["user"]["nightTime"]." UTC", $data["user"]["timezone"], "g:iA");
$scalene->view->display("settings", $data);