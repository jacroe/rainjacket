<?php
require "scalene/Scalene.php";

$scalene->load->core("users");
if ($user = $scalene->users->userLoggedIn())
	$data["user"] = $scalene->users->getUser();

$scalene->view->display("index", $data);