<?php
require "scalene/Scalene.php";
$data = array();

if ($_POST["username"])
{
	$scalene->load->core("users");
	if ($scalene->users->register($_POST["username"], $_POST["email"], $_POST["password"], array("zipcode"=>39402, "time"=>"0800")))
	{
		$scalene->view->display("registerGood");
		die();
	}
	else
	{
		if ($scalene->users->errors)
			$data["errors"] = register_UnderstandErrors($scalene->users->errors);
		$data["submitted"] = array("username"=>$_POST["username"], "email"=>$_POST["email"]);
	}
}
$scalene->view->display("registerForm", $data);

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