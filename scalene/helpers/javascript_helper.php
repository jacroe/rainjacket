<?php

if (!function_exists("javascript_library"))
{
	function javascript_library($lib, $ver = null)
	{
		switch ($lib)
		{
			case "angularjs":
			case "angular":
				if ($ver)
					$js = "angularjs/$ver/angular.min.js";
				else
					$js = "angularjs/1.2.10/angular.min.js";
				break;

			case "chrome-frame":
			case "chromeframe":
			case "chrome":
				if ($ver)
					$js = "chrome-frame/$ver/CFInstall.min.js";
				else
					$js = "chrome-frame/1.0.3/CFInstall.min.js";
				break;

			case "dojo":
				if ($ver)
					$js = "dojo/$ver/dojo/dojo.js";
				else
					$js = "dojo/1.9.2/dojo/dojo.js";
				break;

			case "ext-core":
			case "extcore":
			case "ext":
				if ($ver)
					$js = "ext-core/$ver/ext-core.js";
				else
					$js = "ext-core/3.1.0/ext-core.js";
				break;

			case "jquery":
				if ($ver)
					$js = "jquery/$ver/jquery.min.js";
				else
					$js = "jquery/1.10.2/jquery.min.js";
				break;

			case "jquery-ui":
			case "jqueryui":
				if ($ver)
					$js = "jqueryui/$ver/jquery-ui.min.js";
				else
					$js = "jqueryui/1.10.3/jquery-ui.min.js";
				break;

			case "mootools":
			case "moo":
				if ($ver)
					$js = "mootools/$ver/mootools-yui-compressed.js";
				else
					$js = "mootools/1.4.5/mootools-yui-compressed.js";
				break;

			case "prototype":
				if ($ver)
					$js = "prototype/$ver/prototype.js";
				else
					$js = "prototype/1.7.1.0/prototype.js";
				break;

			case "scriptaculous":
			case "script.aculo.us":
				if ($ver)
					$js = "scriptaculous/1.9.0/scriptaculous.js";
				else
					$js = "scriptaculous/1.9.0/scriptaculous.js";
				break;

			case "swfobject":
				if ($ver)
					$js = "swfobject/$ver/swfobject.js";
				else
					$js = "swfobject/2.2/swfobject.js";
				break;

			case "webfontloader":
			case "webfont":
				if ($ver)
					$js = "webfont/$ver/webfont.js";
				else
					$js = "webfont/1.5.0/webfont.js";
				break;

			default:
				return false;
				break;
		}
		return "<script src=\"//ajax.googleapis.com/ajax/libs/$js\"></script>\n";
	}
}