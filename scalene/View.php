<?php

class View
{
	private $smarty;
	private $parent;

	public function __construct($parent)
	{
		$this->parent = $parent;

		$smarty = new Smarty;
		$smarty->left_delimiter = '{{';
		$smarty->right_delimiter = '}}';
		$smarty->setTemplateDir(DATA_PATH."views/");
		$smarty->setCompileDir(DATA_PATH."views_c/");
		$smarty->escape_html = true;
		$smarty->assign("ROOT_PATH", "//".$this->parent->rootpath);

		$this->smarty = $smarty;
	}

	public function display($file, $vars=null)
	{
		echo $this->fetch($file, $vars);
	}

	public function fetch($file, $vars=null)
	{
		if ($vars)
			$this->smarty->assign($vars);

		return $this->smarty->fetch("$file.tpl");
	}

	public function string($string, $vars=null)
	{
		if ($vars)
			$this->smarty->assign($vars);

		return $this->smarty->fetch("string:$string");
	}

	public function assign($key, $value)
	{
		$this->smarty->assign($key, $value);
	}
}