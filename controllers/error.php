<?php
namespace controllers;

use app\Controller;
use app\Memory;
use app\Reply;

class Error extends Controller
{
	/**
	  * Returns an error message then a bot can't identify message 
	  * @param $message string
	  * @param $memory object
	  *
	  */

	public static function cantIdentify($message, $memory) 
	{
		$reply = new Reply();
		$reply->textTelegram = "Ваше сообщение не удалось распознать!😞 Попробуйте еще раз!";
		$reply->textViber = $reply->textTelegram;
		$reply->textVk = $reply->textTelegram;

		return [$reply];
	}

	/**
	  * Returns an error
	  * @param $message string
	  * @param $memory object
	  *
	  */

	public static function unidentifiedError($message, $memory)
	{
		$reply = new Reply();
		$reply->textTelegram = "There is an undifined error! Please, contact a bot developers! :-(";
		$reply->textViber = $reply->textTelegram;
		$reply->textVk = $reply->textTelegram;

		return [$reply];
	}
}