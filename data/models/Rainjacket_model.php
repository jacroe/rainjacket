<?php
class Rainjacket
{
	private $scalene;

	public function __construct($scalene)
	{
		$this->scalene = $scalene;
	}

	public function LocationLookup($zip)
	{
		$json = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$zip&sensor=false"));
		foreach ($json->results[0]->address_components as $comp)
		{
			if (in_array("locality", $comp->types))
				$loc["city"] = $comp->long_name;
			if (in_array("administrative_area_level_1", $comp->types))
				$loc["state"] = $comp->short_name;
		}

		$loc["lat"] = $json->results[0]->geometry->location->lat;
		$loc["lng"] = $json->results[0]->geometry->location->lng;
		$loc["zipcode"] = $zip;
		return $loc;
	}

	public function AddZipsToDatabase()
	{
		$scalene = $this->scalene;

		$todoZips = $scalene->database->query("SELECT zipcode FROM `users` WHERE zipcode NOT IN (SELECT zipcode FROM `zipcodes` WHERE 1) GROUP BY zipcode");
		foreach ($todoZips as $zip)
		{
			$location = $this->LocationLookup($zip["zipcode"]);
			$scalene->database->insert("zipcodes", $location);
		}
	}

	public function GetForecast($lat, $long, $isDay)
	{
		$dataJson = json_decode(exec("python ".BASE_PATH."/rainjacket/rainjacket.py $lat $long"));

		if ($isDay)
			$temp = $dataJson->temp->hi->temp;
		else
			$temp = $dataJson->temp->lo->temp;

		$replace = array("tempAdj"=>$this->prettyTempAdj($temp), "temp"=>$this->prettyTemp($temp));

		if ($dataJson->precipitation)
		{
			$replace["topPrecipType"] = $this->prettyPrecipType($dataJson->precipitation->top->type, $dataJson->precipitation->top->intensity);
			$replace["topPrecipTime"] = $this->prettyTime($dataJson->precipitation->top->time);
			$replace["topPrecipChance"] = $this->prettyPrecipChance($dataJson->precipitation->top->chance);

			$replace["startPrecipType"] = $this->prettyPrecipType($dataJson->precipitation->start->type, $dataJson->precipitation->start->intensity);
			$replace["startPrecipTime"] = $this->prettyTime($dataJson->precipitation->start->time);
			$replace["startPrecipChance"] = $this->prettyPrecipChance($dataJson->precipitation->start->chance);

			$replace["endPrecipTime"] = $this->prettyTime($dataJson->precipitation->endTime);

			$template = $this->getTemplate($isDay, (boolean)$dataJson->precipitation->start->chance, (boolean)$dataJson->precipitation->endTime);
		}
		else
			$template = $this->getTemplate($isDay, false, false);

		return $this->scalene->view->string($template, $replace);

	}

	private function getTemplate($isDay, $isPrecip, $isStopping)
	{
		$templates = $this->scalene->database->get("templates",
			"`isDay` = ".(int)$isDay." and ".
			"`isPrecip` = ".(int)$isPrecip." and ".
			"`isStopping` = ".(int)$isStopping
		);
		return $templates[rand(0, count($templates)-1)]["template"];
	}

	private function prettyTemp($temp)
	{
		if ((int)substr($temp, -1) < 4)
			$loMidHi = "low ";
		elseif ((int)substr($temp, -1) < 7)
			$loMidHi = "";
		else
			$loMidHi = "high ";
		return $loMidHi.substr($temp, 0, -1)."0s";
	}

	private function prettyTime($time)
	{
		$hour = date("g", $time);
		$minute = date("i", $time);
		$ampm = date("A", $time);

		if ($ampm == "AM")
			$ampm = "";
		else
			$ampm = "p";

		if ($minute != "00")
			$minute = ":".$minute;
		else
			$minute = "";

		return $hour.$minute.$ampm;
	}

	private function prettyTempAdj($temp)
	{
		if ($temp >= 90)
			return "bloody hot";
		elseif ($temp >= 80)
			return "warm";
		elseif ($temp >= 70)
			return "nice and cozy";
		elseif ($temp >= 60)
			return "cool";
		elseif ($temp >= 50)
			return "chilly";
		elseif ($temp >= 40)
			return "cold";
		elseif ($temp >= 30)
			return "freezing";
		else
			return "freakin' cold";
	}

	private function prettyPrecipType($type, $intensity)
	{
		$types = array(
			"rain"=>array("drizzle", "light rain", "rain", "heavy rain"),
			"snow"=>array("snow flurries", "snow", "snow", "heavy snow"),
			"sleet"=>array("sleet", "sleet", "sleet", "sleet"),
			"hail"=>array("hail", "hail", "hail", "heavy hail")
		);

		if ($intensity >= 0.4)
			return $types[$type][3];
		elseif ($intensity >= 0.1)
			return $types[$type][2];
		elseif ($intensity >= 0.017)
			return $types[$type][1];
		else
			return $types[$type][0];
	}

	private function prettyPrecipChance($chance)
	{
		// https://en.wikipedia.org/wiki/Probability_of_precipitation

		if ($chance > .8)
			return "";
		elseif ($chance > .4)
			return " a chance of";
		else
			return " a slight chance of ";
	}
}