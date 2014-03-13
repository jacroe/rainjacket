<?php
require "scalene/Scalene.php";
$data = array();

if ($_POST["username"])
{
	$_->load->core("users");
	if ($_->users->register($_POST["username"], $_POST["email"], $_POST["password"], array("zipcode"=>39406, "timezone"=>"America/Chicago", "dayTime"=>"1400", "nightTime"=>"2300")))
	{
		$_->view->display("registerGood");
		die();
	}
	else
	{
		if ($_->users->errors)
			$data["errors"] = register_UnderstandErrors($_->users->errors);
		else
			$data["errors"][] = array("title"=>"Unknown error", "body"=>"Some unknown error occured and you were not able to be registered. You can try re-submitting or get in touch with <a href=\"mailto:jacob@jacroe.com\" class=\"alert-link\">Jacob</a>.");
		$data["submitted"] = array("username"=>$_POST["username"], "email"=>$_POST["email"]);
	}
}
$_->view->display("registerForm", $data);

function register_UnderstandErrors($errors)
{
	foreach($errors as $error)
	{
		switch ($error)
		{
			case "UsernameTaken":
				$cErrors[] = array("title"=>"Username taken", "body"=>"That username is already in use. Please pick another.");
				break;

			case "EmailTaken":
				$cErrors[] = array("title"=>"Email taken", "body"=>"That email address is already in use. Please pick another.");
				break;

			case "EmailBad":
				$cErrors[] = array("title"=>"Invalid email", "body"=>"Please use a valid email address.");
				break;

			case "PasswordEmpty":
				$cErrors[] = array("title"=>"Password empty", "body"=>"You cannot use an empty password.");
				break;
		}
	}
	return $cErrors;
}