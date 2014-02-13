<?php
require "scalene/Scalene.php";

$scalene->load->core("users");

$data = array();

if ($_POST["username"])
	if (!$scalene->users->login($_POST["username"], $_POST["password"]))
		$data["errors"][] = array("title"=>"Login information incorrect", "body"=>"Sorry, we couldn't verify you. Try again?");

if ($user = $scalene->users->userLoggedIn())
	header("Location: settings.php");

$scalene->view->display("login", $data);