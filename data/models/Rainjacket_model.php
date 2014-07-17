<?php
/**
 * The Rainjacket class
 *
 * This class performs all necessary functions for Rainjacket to operate
 *
 * @author  Jacob Roeland
 */

class Rainjacket extends Model
{
	/**
	 * Gecodes the customers' zipcodes into useable data. Searches the database for zipcodes that have not been
	 * geocoded and then does it.
	 */
	public function addZipsToDatabase()
	{
		$this->load->library("googlelocation");
		$todoZips = $this->database->query("SELECT zipcode FROM `users` WHERE zipcode NOT IN (SELECT zipcode FROM `zipcodes` WHERE 1) GROUP BY zipcode");	// Selects all zips that aren't already in the cache
		foreach ($todoZips as $zip)
		{
			$location = $this->googlelocation->getByZip($zip["zipcode"]);
			$this->database->insert("zipcodes", $location);
		}
	}

	/**
	 * Returns a forecast string based on the latitude, longitude, whether or not it's daytime
	 * @param  float   $lat   Latitude
	 * @param  float   $long  Longitude
	 * @param  boolean $isDay Whether or not it's currently "daytime" for the customer
	 * @return string         The forecast
	 */
	public function getForecast($lat, $long, $isDay = true)
	{
		$data = exec("python ".BASE_PATH."/rainjacket/rainjacket.py $lat $long");
		while(strpos($data, "Could not get Forecast.io data.") !== false)
		{
			sleep(3);
			$data = exec("python ".BASE_PATH."/rainjacket/rainjacket.py $lat $long");
		}

		$dataJson = json_decode($data);


		$returnData["processed"]["lookingAhead"] = $dataJson->lookingAhead;
		$returnData["processed"]["wind"] = $this->_prettyWind($dataJson->wind);
		$returnData["processed"]["alerts"] = $this->_prettyAlerts($dataJson->alerts);
		$returnData["raw"] = json_encode($dataJson);

		if ($isDay)
			$temp = $dataJson->temp->hi->temp;
		else
			$temp = $dataJson->temp->lo->temp;

		$replace = array("tempAdj"=>$this->_prettyTempAdj($temp), "temp"=>$this->_prettyTemp($temp));

		if ($dataJson->precipitation)
		{
			$replace["topPrecipType"] = $this->_prettyPrecipType($dataJson->precipitation->top->type, $dataJson->precipitation->top->intensity);
			$replace["topPrecipTime"] = $this->prettyTime($dataJson->precipitation->top->time);
			$replace["topPrecipChance"] = $this->_prettyPrecipChance($dataJson->precipitation->top->chance);

			$replace["startPrecipType"] = $this->_prettyPrecipType($dataJson->precipitation->start->type, $dataJson->precipitation->start->intensity);
			$replace["startPrecipTime"] = $this->prettyTime($dataJson->precipitation->start->time);
			$replace["startPrecipChance"] = $this->_prettyPrecipChance($dataJson->precipitation->start->chance);

			$replace["endPrecipTime"] = $this->prettyTime($dataJson->precipitation->endTime);

			$template = $this->_getTemplate($isDay, true, (boolean)$dataJson->precipitation->endTime);
		}
		else
			$template = $this->_getTemplate($isDay, false, false);

		$returnData["processed"]["forecast"] = $this->view->string($template, $replace);

		return json_encode($returnData);

	}

	/**
	 * Returns a custom time format. For 8am we return "8". For 6:23pm we return "6:23p".
	 * @param  int $time    The unix epoch to format
	 * @return string       The formatted time
	 */
	public function prettyTime($time)
	{
		if (date("Hi", $time) == "0000")
			return "midnight";
		if (date("Hi", $time) == "1200")
			return "noon";

		$hour = date("g", $time);
		$minute = date("i", $time);
		$ampm = date("a", $time);

		if ($minute != "00")
			$minute = ":".$minute;
		else
			$minute = "";

		return $hour.$minute.$ampm;
	}

	/**
	 * Gets a list of templates from the database that matches the requirements, selects one at random, and
	 * returns it.
	 * @param  boolean $isDay      Is it day or not
	 * @param  boolean $isPrecip   Is it going to precipitate
	 * @param  boolean $isStopping Is it going to stop before the day ends
	 * @return string              The template from the
	 */
	private function _getTemplate($isDay, $isPrecip, $isStopping)
	{
		$templates = $this->database->get("templates",
			"`isDay` = ".(int)$isDay." and ".
			"`isPrecip` = ".(int)$isPrecip." and ".
			"`isStopping` = ".(int)$isStopping
		);
		return $templates[rand(0, count($templates)-1)]["template"];
	}

	/**
	 * For a given temperature, we English-ify it some making it look more like everyday speech
	 * @param  int $temp    The temperature
	 * @return string       The English'd temperature
	 */
	private function _prettyTemp($temp)
	{
		if ((int)substr($temp, -1) < 4)
			$loMidHi = "low ";
		elseif ((int)substr($temp, -1) < 7)
			$loMidHi = "";
		else
			$loMidHi = "high ";
		return $loMidHi.substr($temp, 0, -1)."0s";
	}

	/**
	 * Returns an adjective based on a given temperature. Another way to make it a bit more light-hearted
	 * @param  int $temp    The temperature
	 * @return string       The adjective describing the temperature.
	 */
	private function _prettyTempAdj($temp)
	{
		# Just like we do with the templates, we'll probabily have a dictionary of terms available for each range

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

	/**
	 * For a given precipitation type and precipitation intensity we return a word/phrase that describes it.
	 * Example: If it's going to rain with an intensity of 0.01, we return "drizzle".
	 * @param  string $type      Precipitation type
	 * @param  float $intensity  Precipitation intensity
	 * @return string            The corresponding word or phrase
	 */
	private function _prettyPrecipType($type, $intensity)
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

	/**
	 * This returns an appropriate phrase for the chance of precipitation to occur.
	 * Note that we only call this function if the chance is above a certain percentage
	 * determined in forecastCrunch.py. As of right now, this is >20%.
	 * @param  float $chance  The chance precipitation might occur (between 0 and 1)
	 * @return string         An English'd chance based on the Wikipedia article below
	 */
	private function _prettyPrecipChance($chance)
	{
		// https://en.wikipedia.org/wiki/Probability_of_precipitation

		if ($chance > .8)
			return "";
		elseif ($chance > .4)
			return " a chance of";
		else
			return " a slight chance of ";
	}

	private function _prettyWind($wind)
	{
		// Not even gonna lie, totally ripped this straight from Poncho

		if ($wind < 5)
			return "The perfect day ($wind MPH)";
		elseif ($wind < 11)
			return "Gone with the breeze ($wind MPH)";
		elseif ($wind < 14)
			return "Wind, wind, go away! ($wind MPH)";
		else
			return "We're not in Kansas anymore! ($wind MPH)";
	}

	private function _prettyAlerts($alerts)
	{
		if ($alerts)
		{
			foreach($alerts as $alert)
			{
				$alert->time = date('M jS \a\t g:iA', $alert->time);
				$alert->expires = date('M jS \a\t g:iA', $alert->expires);
				$formattedAlerts[] = $alert;
			}

			return $formattedAlerts;
		}
		else
			return null;

	}
}