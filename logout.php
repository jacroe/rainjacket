<?php
require "scalene/Scalene.php";

$scalene->load->core("users");

$scalene->users->logout();
header("Location: index.php");