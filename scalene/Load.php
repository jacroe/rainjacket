<?php

class Load
{
	private $parent;

	public function __construct(&$parent)
	{
		$this->parent = $parent;
	}

	public function core($core)
	{
		$coreU = ucfirst($core);
		require_once SCALENE_PATH."core/{$coreU}_core.php";

		$this->parent->$core = new $coreU($this->parent);
	}

	public function library($lib)
	{
		$libU = ucfirst($lib);
		require_once SCALENE_PATH."lib/{$libU}_lib.php";

		$this->parent->$lib = new $libU($this->parent);
	}

	public function model($model)
	{
		$modelU = ucfirst($model);
		require_once DATA_PATH."models/{$modelU}_model.php";

		$this->parent->$model = new $modelU($this->parent);
	}

	public function helper($helper)
	{
		require_once SCALENE_PATH."helpers/{$helper}_helper.php";
	}

}