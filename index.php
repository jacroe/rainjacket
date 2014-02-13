<?php
require "scalene/Scalene.php";

$scalene->load->core("users");
$data["user"] = $scalene->users->userLoggedIn();

$scalene->view->display("index", $data);