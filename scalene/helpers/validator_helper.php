<?php

if (!function_exists("validate_date"))
{
	function validate_date($date)
	{
		$dateArray  = explode('/', $date);
		return checkdate($dateArray[0], $dateArray[1], $dateArray[2]);
	}
}

if (!function_exists("validate_time"))
{
	function validate_time($time)
	{
		if (strtotime($time))
			return true;
		else
			return false;
	}
}

if (!function_exists("validate_email"))
{
	function validate_email($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}

if (!function_exists("validate_zipcode"))
{
	function validate_zipcode($zip)
	{
		return preg_match("/^\d{5}$/", $zip);
	}
}

if (!function_exists("validate_phone"))
{
	function validate_phone($phone)
	{
		$phone = explode('x', preg_replace('/[^\dxX]/', '', $phone));
		if (strlen($phone[0]) == 10 or strlen($phone[0]) == 11)
			return true;
		else
			return false;
	}
}