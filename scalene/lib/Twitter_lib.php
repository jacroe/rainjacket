<?php

class Twitter extends Library
{
	private $scalene;
	private $tw;

	public function __construct()
	{
		require_once SCALENE_PATH."extlib/TwitterAPIExchange.php";
		$this->tw = new TwitterAPIExchange($this->config["twitter"]);
	}

	public function tweet($tweet)
	{
		$array["status"] = $tweet;
		return $this->tw
					->setPostFields($array)
					->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
					->performRequest();
	}

	public function timeline()
	{
		return $this->tw
					->buildOauth("https://api.twitter.com/1.1/statuses/home_timeline.json", "GET")
					->performRequest();
	}
}