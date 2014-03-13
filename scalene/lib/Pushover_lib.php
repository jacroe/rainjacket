<?php

class Pushover extends Library
{
	private $scalene;
	private $appID;
	private $userID;

	public function __construct()
	{
		foreach ($this->config["pushover"] as $var => $value)
			$this->{$var} = $value;
	}

	public function send($title, $message, $pri=0)
	{
		if ($pri > 1) $pri = 1;
		curl_setopt_array($ch = curl_init(), array(
		CURLOPT_URL => "https://api.pushover.net/1/messages.json",
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_POSTFIELDS => array(
		  "token" => $this->appID,
		  "user" => $this->userID,
		  "title" => $title,
		  "message" => $message,
		  "priority" => $pri )));
		$message = curl_exec($ch);
		curl_close($ch);
		if (json_decode($message)->status) return true;
		else return false;
	}
}