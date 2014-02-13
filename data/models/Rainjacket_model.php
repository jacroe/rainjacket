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

	public function GetForecast($lat, $long)
	{
		return exec("python ".BASE_PATH."/rainjacket/rainjacket.py $lat $long");
	}
}