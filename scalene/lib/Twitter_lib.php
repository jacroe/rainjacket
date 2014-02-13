<?php

class Twitter
{
	private $scalene;
	private $tw;

	public function __construct($scalene)
	{
		$this->scalene = $scalene;

		require_once SCALENE_PATH."extlib/TwitterAPIExchange.php";
		$this->tw = new TwitterAPIExchange($scalene->config["twitter"]);
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