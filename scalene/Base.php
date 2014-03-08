<?php

abstract class Core
{
	public function __construct(&$scalene)
	{
		foreach ($scalene as $k => $v)
			$this->$k = $v;
	}

}

abstract class Library
{
	public function __construct(&$scalene)
	{
		foreach ($scalene as $k => $v)
			$this->$k = $v;
	}

}

abstract class Model
{
	public function __construct(&$scalene)
	{
		foreach ($scalene as $k => $v)
			$this->$k = $v;
	}

}