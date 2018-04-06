<?php
namespace application;

class ConfigRegistry extends Registry
{
	protected static $instance;
	protected $db;
	
	protected function __construct($cashe) 
	{ 
		$this->db = $cashe['db'];
	}

	public function getDBConfiguration()
	{
		return $this->db;
	}
}