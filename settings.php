<?php
require "scalene/Scalene.php";
$scalene->load->core("users");
$scalene->load->helper("validator");

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
			"zipcode"=>$_POST["zipcode"],
			"email"=>$_POST["email"],
			"dayTime"=>date("Hi", strtotime($_POST["emailDaySendTime"])),
			"nightTime"=> date("Hi", strtotime($_POST["emailNightSendTime"]))
		), "username = '$user'");
		$data["errors"][] = array("title"=>"Done!", "body"=>"Those settings were updated like a boss.", "type"=>"success");
	}
}

$data["user"] = $scalene->users->getUser();
$data["user"]["dayTime"] = date("g:iA", strtotime($data["user"]["dayTime"]));
$data["user"]["nightTime"] = date("g:iA", strtotime($data["user"]["nightTime"]));
$scalene->view->display("settings", $data);