<?php
namespace controllers;

use \application\Controller;
use \models\Auth;

use \models\Send;

class mainController extends Controller
{
	/* Вход */
	function actionLogin($params)
	{
		$wrongLoginOrPass = false;
		if ($_POST) {
			$auth = new Auth();
			if ($auth->auth($_POST['login'], $_POST['password'])) {
				header('Location: index.php?r=main/index', true, 301);
			} else {
				$wrongLoginOrPass = true;
			}
		}

		/* Manually setting user pass */
		if (isset($_GET['setNewPass'])) {
			$auth = new Auth();
			$auth->setNewPass();
		}
		
		return $this->render('login','auth', [
				'wrongLoginOrPass' => $wrongLoginOrPass,
			]);
	}

	/* Выход */
	function actionLogout($params)
	{
		$auth = new Auth();
		$auth->out();
		header('Location: index.php?r=main/login', true, 301);

		return;	
	}

	/* Главная страница */
	function actionIndex($params)
	{

		if ($_POST) {
			$users = \QB::table('trainer_bot_bot')
					->get();

			$params = json_encode([
				'text' => $_POST['text'],
				'image' => $_POST['image'],
			], JSON_UNESCAPED_UNICODE);
			
			foreach ($users as $user) {
				Send::Telegram('/sp-mess'.$params, $user->user_id);
			}
		}

		return $this->render('index','main', [
				'param' => 100,
				//...
			]);
	}

	function actionSend($params)
	{
		$users = json_decode($_GET['users']);

		foreach ($users as $user) {
			Send::Telegram($_GET['command'], $user);
		}

		return 'true';
	}

}