<?php
namespace models;

require_once '../models/email/mailer.php';

class Auth {
    private $userID = 1;
 
 	public function __construct() {
 		if (session_status() == PHP_SESSION_NONE) {
			session_start();
 		}
 	}

    public function isAuth() {
        if (isset($_SESSION["is_auth"])) { 
            return $_SESSION["is_auth"];
        }

        else return false;
    }
     
    public function auth($login, $password) {
    	$user = \QB::table('trainer_bot_admin_user')
					->where('admin_user_id', '=', $this->userID)
					->get();

		$user = $user[0];

		if ($user->login == $login && $user->password == md5($password)) { 
            $_SESSION["is_auth"] = true; 
            $_SESSION["login"] = $login; 

            return true;
        }
        else { 
            $_SESSION["is_auth"] = false;

            return false; 
        }
    }
     
    public function getLogin() {
        if ($this->isAuth()) { 
            return $_SESSION["login"]; 
        }
    }

   public function setNewPass() {
   		$pass = $this->generateRandomString(6);
  		// $pass = 'admin';

   		$user = \QB::table('trainer_bot_admin_user')
					->where('admin_user_id', '=', $this->userID)
					->get();

		$user = $user[0];

   		(\QB::table('trainer_bot_admin_user')
					->where('admin_user_id', '=', $this->userID)
					->update([
							'password' => md5($pass),
						]));

   		\models\email\Mailer::sendEmail('Новый пароль от бота', $pass, $user->login);

   		return;
   }

   	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
		    $randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
     
     
    public function out() {
        $_SESSION = array();
        session_destroy();
    }
}