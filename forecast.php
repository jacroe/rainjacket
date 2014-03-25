<?php
require "scalene/Scalene.php";

if (!array_key_exists("user", $_GET))
	header("Location: index.php");
if (!$_->database->numRows("users", "`username` = '{$_GET["user"]}'"))
	header("Location: index.php");

$_->load->model("rainjacket");

$rows = $_->database->get("forecasts", "`user` = '{$_GET["user"]}'");
$data = json_decode($rows[0]["processed"], true);
for ($i=0; $i < count($data["lookingAhead"]); $i++)
	$data["lookingAhead"][$i]["time"] = $_->rainjacket->prettyTime($data["lookingAhead"][$i]["time"]);
$data["lookingAhead"][0]["time"] = "now";

$_->view->assign("data", $data);

$_->view->display("forecast");