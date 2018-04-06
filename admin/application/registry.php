<?php
namespace application;

abstract class Registry {
	protected static $instance = null;

	public static function init($cashe)
	{
		if (is_null(static::$instance)) {
			static::$instance = new static($cashe);
		} 

		return; 
	}

	public static function getInstance() 
	{
		if (!is_null(static::$instance)) {
			return static::$instance; 
		} 
		throw new Exception("Init registry before access", 1);
		
		return; 
	}
}