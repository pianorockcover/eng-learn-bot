<?php
spl_autoload_register(function($class) {
	$class = str_replace('\\', '/', $class);
	// var_dump(strtolower($class));

	if (file_exists(strtolower($class).'.php'))
	{
		require(strtolower($class).'.php');
	}

	if (file_exists('../'.strtolower($class).'.php'))
	{
		require('../'.strtolower($class).'.php');
	}

	return;
});