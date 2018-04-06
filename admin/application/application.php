<?php
namespace application;

class Application
{
	private $router;

	function __construct($config)
	{
		# Проверить запрос на ниличие инъекций

		ConfigRegistry::init($config);

		return;
	}

	public function run()
	{
		try {
			/* Init DB Engine */
			$dbConfig = [
	            'driver'    => 'mysql', 
	            'host'      => ConfigRegistry::getInstance()->getDBConfiguration()['host'],
	            'database'  => ConfigRegistry::getInstance()->getDBConfiguration()['name'],
	            'username'  => ConfigRegistry::getInstance()->getDBConfiguration()['user'],
	            'password'  => ConfigRegistry::getInstance()->getDBConfiguration()['password'],
	            'charset'   => 'utf8mb4',
	        ];

			new \Pixie\Connection('mysql', $dbConfig, 'QB');

			/* Get Router */
			$this->router = new Router($_GET);

			/* Init Controller and Action */
			$controller = $this->router->getController();
			$action = $this->router->getAction();
			$params = $this->router->getParams();

			echo $controller->$action($params);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		return;
	}
}