<?php

if (!function_exists("date_timeAgo"))
{
	function date_timeAgo($date)
	{
		$now = new DateTime();
		$ref = new DateTime($date);
		$diff = $now->diff($ref);
		if ($diff->y) $timeLeft = "{$diff->y} years, {$diff->m} months, {$diff->d} days";
		elseif ($diff->m) $timeLeft = "{$diff->m} months {$diff->d} days";
		elseif ($diff->d) $timeLeft = "{$diff->d} days {$diff->h} hours";
		elseif ($diff->h) $timeLeft = "{$diff->h} hours {$diff->i} minutes";
		else $timeLeft = "{$diff->i} minutes {$diff->s} seconds";

		if ($diff->y == 1) $timeLeft = str_replace("years", "year", $timeLeft);
		if ($diff->m == 1) $timeLeft = str_replace("months", "month", $timeLeft);
		if ($diff->d == 1) $timeLeft = str_replace("days", "day", $timeLeft);
		if ($diff->h == 1) $timeLeft = str_replace("hours", "hour", $timeLeft);
		if ($diff->i == 1) $timeLeft = str_replace("minutes", "minute", $timeLeft);
		if ($diff->s == 1) $timeLeft = str_replace("seconds", "second", $timeLeft);
		if(time() < strtotime($date))
			return $timeLeft." in the future";
		else
			return $timeLeft." ago";
	}
}

if (!function_exists("date_html5"))
{
	function date_html5($date)
	{
		return date("Y-m-d H:i:sO", strtotime($date));
	}
}

if (!function_exists("date_monthDay"))
{
	function date_monthDay($time = null)
	{
		if (!$time)
			$time = time();
		else
			$time = strtotime($time);

		return date("F j", $time);

	}
}

if (!function_exists("date_day"))
{
	function date_day($includeOrdinalSuffix = true, $time = null)
	{
		if (!$time)
			$time = time();
		else
			$time = strtotime($time);

		if ($includeOrdinalSuffix)
			return date("jS", $time);
		else
			return date("j", $time);
	}
}

if (!function_exists("date_timezoneConvert"))
{
	function date_timezoneConvert($time, $convertTo = "UTC", $format = "Hi")
	{
		$tz = new DateTimeZone($convertTo);
		$newDateTime = neW DateTime($time);
		$newDateTime->setTimeZone($tz);
		return $newDateTime->format($format);
	}
}