<?php
require "scalene/Scalene.php";

$_->load->core("users");

$_->users->logout();
header("Location: index.php");