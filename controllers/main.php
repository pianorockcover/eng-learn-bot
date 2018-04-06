<?php
namespace controllers;

use app\Controller;
use app\Memory;
use app\Reply;

use models\Root;
use models\Photo;
use controllers\Error;

class Main extends Controller
{
	private static $defaultTelegramKeyboard = [];

	public static function sayHello($message, &$memory) 
	{
		$reply = new Reply();
		$reply->textTelegram = "Привет, {$memory->name}! Я бот *КАЗИНО* \n\n Хочешь испытать удачу?💵💎💰\n\n*Дерни за рычаг*!\n👇🏻👇🏻👇🏻👇🏻👇🏻👇🏻👇🏻👇🏻";

		$reply->keyboard['keyboard'] = [
			['📍 Дернуть за рычаг!',]
		];

		return [$reply];
	}

	public static function result($message, &$memory)
	{
		$reply = new Reply();
		$reply->textTelegram = "Макака🐵 дернула за банан...\n\n";

		$reply->textTelegram .= static::generateLine()."\n";
		$reply->textTelegram .= static::generateLine()."\n";
		$reply->textTelegram .= static::generateLine()."\n";
		$reply->textTelegram .= static::generateLine()."\n";

		$reply->textTelegram .= "\nИ ты все проиграл!👮🖕🏼";		

		$reply->keyboard['keyboard'] = [
			['📍 Дернуть за рычаг!',]
		];

		return [$reply];
	}

	private static function generateLine()
	{
		$fruts = ['🍏', '🍌', '🍋', '🍒'];

		$line = '';

		for ($i = 0; $i < 6; $i++) 
		{
			$line .= $fruts[round(static::f_rand(0, 3))];
		}
		

		return $line;
	}

	private static function f_rand($min = 0, $max = 1, $mul = 1000000)
	{
        if ($min > $max) return false;
    
        return mt_rand($min * $mul, $max * $mul) / $mul;
    }

}	
