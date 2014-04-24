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
