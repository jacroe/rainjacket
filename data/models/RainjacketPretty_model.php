<?php
class RainjacketPretty
{
	public function prettyOutput($dataJson, $isDay)
	{
		$replace = array("{tempAdj}"=>$dataJson->temp->adj, "{temp}"=>$dataJson->temp->temp);

		if ($dataJson->precipitation)
		{
			$replace["{topPrecipType}"] = $this->prettyPrecipType($dataJson->precipitation->top->type, $dataJson->precipitation->top->intensity);
			$replace["{topPrecipTime}"] = $this->prettyTime($dataJson->precipitation->top->time);
			$replace["{topPrecipChance}"] = $this->prettyPrecipChance($dataJson->precipitation->top->chance);

			$replace["{startPrecipType}"] = $this->prettyPrecipType($dataJson->precipitation->start->type, $dataJson->precipitation->start->intensity);
			$replace["{startPrecipTime}"] = $this->prettyTime($dataJson->precipitation->start->time);
			$replace["{startPrecipChance}"] = $this->prettyPrecipChance($dataJson->precipitation->start->chance);

			$replace["{endPrecipTime}"] = $this->prettyTime($dataJson->precipitation->endTime);

			$template = $this->template($isDay, (boolean)$dataJson->precipitation->start->chance, (boolean)$dataJson->precipitation->endTime);
		}
		else
			$template = $this->template($isDay, false, false);

		#print_r($replace);
		#die();
		$template = str_replace(array_keys($replace), array_values($replace), $template);
		return $template;
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

	private function template($isDay, $isPrecip, $isStopping)
	{
		if ($isDay)
			if ($isPrecip)
				if ($isStopping)
					return "It's a {tempAdj} day with highs in the {temp} and {topPrecipType} from {startPrecipTime} to {endPrecipTime}. Be sure to bring a RAIN JACKET. Get it? I need friends...";
				else
					return "It's a {tempAdj} day with highs in the {temp} and {topPrecipType} starting at {startPrecipTime} and continuing throughout the day. Be sure to bring a RAIN JACKET. Get it? I need friends...";
			else
				return "It's a {tempAdj} day with highs in the {temp}. Something something, sweater weather.";
		else
			if ($isPrecip)
				if ($isStopping)
					return "Lows tonight will be in the {temp}. {topPrecipType} from {startPrecipTime} to {endPrecipTime}. Sounds like perfect cuddling weather.";
				else
					return "Lows tonight will be in the {temp}. {topPrecipType} from {startPrecipTime} and continuing throughout the night. Sounds like great cuddling weather.";
			else
				"Lows tonight will be in the {temp}. Hot chocolate, anyone?";

	}
}
