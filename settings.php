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
	if (!$_POST["emailSendTime"])
		$data["errors"][] = array("title"=>"Time can't be empty", "body"=>"Please enter a time.");
	if (empty($data["errors"]))
	{
		$scalene->database->update("users", array("zipcode"=>$_POST["zipcode"], "email"=>$_POST["email"], "time"=>date("Hi", strtotime($_POST["emailSendTime"]))), "username = '$user'");
		$data["errors"][] = array("title"=>"Done!", "body"=>"Those settings were updated like a boss.", "type"=>"success");
	}
}

$data["user"] = $scalene->users->getUser();
$data["user"]["time"] = date("g:iA", strtotime($data["user"]["time"]));
$scalene->view->display("settings", $data);