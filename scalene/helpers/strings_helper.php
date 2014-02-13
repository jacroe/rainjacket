<?php

if (!function_exists("pluralize"))
{
	function pluralize($count, $singlular, $plural)
	{
		return ($count == 1) ? $singlular : $plural;
	}
}
