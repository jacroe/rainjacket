<?php

define("BASE_PATH", str_replace("scalene/", "", dirname(__FILE__)."/"));
define("SCALENE_PATH", BASE_PATH."scalene/");
define("DATA_PATH", BASE_PATH."data/");

require SCALENE_PATH."config.php";

error_reporting(E_ALL ^ E_NOTICE);

class Scalene
{
	private $timestart;
	public $config;
	public $rootpath;

	public function __construct($config)
	{
		require SCALENE_PATH."Load.php";
		require SCALENE_PATH."View.php";

		$this->config = $config;
		$this->timestart = microtime(true);
		$this->rootpath = str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]);


		$this->load = new Load($this);
		$this->view = new View($this);
		$this->model = new StdClass;

		if (array_key_exists("load", $this->config))
			foreach($this->config["load"]["core"] as $core)
				$this->load->core($core);
	}

	public function TimeSinceStart()
	{
		return microtime(true)-$this->timestart;
	}

}

$scalene = new Scalene($config);