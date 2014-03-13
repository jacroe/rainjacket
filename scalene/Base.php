<?php

abstract class Core
{
	public function __get($key)
	{
		return get_scalene()->$key;
	}

}

abstract class Library
{

	public function __get($key)
	{
		return get_scalene()->$key;
	}

}

abstract class Model
{
	public function __get($key)
	{
		return get_scalene()->$key;
	}

}