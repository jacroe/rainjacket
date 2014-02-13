<?php
require "scalene/Scalene.php";
$scalene->load->core("users");

if (!$user = $scalene->users->userLoggedIn())
	header("Location: login.php");

if ($_POST)
	$data["errors"][] = array("title"=>"Changing information disabled", "body"=>"Right now, we're not allowing user details to be edited. If you need your information updated, email me at <a href=\"mailto:jacob@jacroe.com\" class=\"alert-link\">jacob@jacroe.com</a>.");

$user = $scalene->users->getUser();
$data["user"] = $user[0];
$data["user"]["time"] = date("g:iA", strtotime($data["user"]["time"]));
$scalene->view->display("settings", $data);