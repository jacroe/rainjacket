<?php
require "scalene/Scalene.php";

$scalene->load->model("tropo");

$session = new Session();
$to = "+".$session->getParameters("numbertodial");

$tropo = new Tropo();

$tropo->call($to, array('network'=>'SMS'));
$tropo->say("It's a cold day with highs in the low 40s and light rain from 1 to 5p. Be sure to bring a RAIN JACKET. Get it? I need friends...");

$tropo->RenderJson();
?>
