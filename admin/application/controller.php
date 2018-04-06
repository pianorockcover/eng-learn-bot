<?php
namespace application;

abstract class Controller {
	
	function __call($action, $params)
	{
		throw new \Exception("Bad Request 404", 404);
	}

	function render($view, $layout, $params = NULL)
	{
		ob_start();
		require dirname(__FILE__)."/../views/{$view}.html.php";
		$content = ob_get_clean();

		ob_start();
		require dirname(__FILE__)."/../views/layouts/{$layout}-layout.html.php";

		return ob_get_clean();
	}
}