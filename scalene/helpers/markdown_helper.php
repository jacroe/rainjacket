<?php

if (!function_exists("markdown_parse"))
{
	function markdown_parse($text)
	{
		return Parsedown::instance()->parse($text);
	}
	
}