<?php

class Pollen extends Model
{
	public function getForZipcode($zipcode)
	{
		if ($this->database->numRows("pollen", "`zipcode` = '$zipcode'"))
		{
			$rows = $this->database->get("pollen", "`zipcode` = '$zipcode'");
			$row = $rows[0];

			if (time() > strtotime($row["expires"]))
			{
				$data = $this->_doLookup($zipcode);
				$this->database->update(
					"pollen",
					array("data"=>json_encode($data),
						  "expires"=>date("Y-m-d H:i:s\n", strtotime("tomorrow"))
					),
					"`zipcode` = '$zipcode'"
				);
			}
			else
				$data = json_decode($row["data"], true);
		}
		else
		{
			$data = $this->_doLookup($zipcode);
			$this->database->insert(
				"pollen",
				array("zipcode"=>$zipcode,
					  "data"=>json_encode($data),
					  "expires"=>date("Y-m-d H:i:s\n", strtotime("tomorrow"))
				)
			);
		}

		return $data;
	}

	private function _doLookup($zipcode)
	{
		$raw = file_get_contents("http://www.claritin.com/weatherpollenservice/weatherpollenservice.svc/getforecast/$zipcode");
		$json = json_decode(json_decode($raw));

		$days = array();
		foreach ($json->pollenForecast->forecast as $x)
		{
			$day = array();
			$day["level"] = $x;
			$day["word"] = $this->_getWord($x);
			$days[] = $day;
		}

		return $days;
	}

	private function _getWord($x)
	{
		if ($x > 8.5)
			return "high";
		elseif ($x > 3.6)
			return "med";
		else
			return "low";
	}
}