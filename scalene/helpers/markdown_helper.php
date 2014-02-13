<?php

require_once SCALENE_PATH."extlib/Parsedown/Parsedown.php";

if (!function_exists("markdown_parse"))
{
	function markdown_parse($text)
	{
		return Parsedown::instance()->parse($text);
	}
	
}