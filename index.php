<?php
require "scalene/Scalene.php";

$_->load->core("users");
if ($user = $_->users->userLoggedIn())
	$data["user"] = $_->users->getUser();

$_->view->display("index", $data);