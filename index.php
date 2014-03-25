<?php
require "scalene/Scalene.php";

$_->load->core("users");
if ($user = $_->users->userLoggedIn())
	header("Location: settings.php");

$_->view->display("index");