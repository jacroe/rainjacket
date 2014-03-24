<?php

class Googlelocation extends Library
{
	public function getByLatLong($lat, $lng)
	{
		return $this->_lookup("$lat,$lng");
	}

	public function getByZip($zip)
	{
		return $this->_lookup($zip);
	}

	public function getByCitySt($city, $st)
	{
		return $this->_lookup("$city,$st");
	}

	private function _lookup($url)
	{
		$jsonGeocode = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$url&sensor=false"));

		foreach ($jsonGeocode->results[0]->address_components as $comp)
		{
			if (in_array("locality", $comp->types))
				$l["city"] = $comp->long_name;
			if (in_array("administrative_area_level_1", $comp->types))
				$l["state"] = $comp->short_name;
			if (in_array("postal_code", $comp->types))
				$l["zipcode"] = $comp->short_name;
		}
		$l["latitude"] = $jsonGeocode->results[0]->geometry->location->lat;
		$l["longitude"] = $jsonGeocode->results[0]->geometry->location->lng;

		$jsonTimezone = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/timezone/json?location={$l["latitude"]},{$l["longitude"]}&sensor=false&timestamp=".time()));

		$l["timezone"] = $jsonTimezone->timeZoneId;

		return $l;
	}
}