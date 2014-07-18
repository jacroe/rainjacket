<?php

if (!function_exists("pluralize"))
{
	function pluralize($count, $singlular, $plural = null)
	{
		if ($plural)
			return ($count == 1) ? $singlular : $plural;
		else
			return ($count == 1) ? $singlular : $singlular."s";
	}
}

if (!function_exists("camelize"))
{
	function camelize($str)
	{
		return strtolower($str[0]).substr(str_replace(' ', '', ucwords(preg_replace('/[\s_]+/', ' ', $str))), 1);
	}
}