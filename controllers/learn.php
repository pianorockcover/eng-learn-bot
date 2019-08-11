<?php
namespace controllers;

use app\Controller;
use app\Memory;
use app\Reply;
use app\DataMapper;

use models\Root;
use models\Photo;
use controllers\Error;
use controllers\Learn;

class Main extends Controller
{
	private static $themeCommand = "/theme";
	private static $modeCommand = "/mode";

	public static function menu($message, &$memory) 
	{
		$themes = DataMapper::get("theme");

		$reply = new Reply();
		$reply->textTelegram = "Choose theme:";

		$reply->keyboard['inline_keyboard'] = [];

		foreach ($themes as $theme) {
			$reply->keyboard['inline_keyboard'][] = [[
				'text' => $theme->name,
				'callback_data' => static::$themeCommand.$theme->theme_id,
			]];
		}

		$memory->theme_id = 0;
		$memory->mode_id = 0;
		$memory->word_id = 0;

		return [$reply];
	}

	public static function theme($message, &$memory) 
	{
		$reply = new Reply();
		$reply->textTelegram = "Choose mode:";

		$reply->keyboard['inline_keyboard'] = [];

		$reply->keyboard['inline_keyboard'][] = [[
			'text' => "Изучение",
			'callback_data' => static::$modeCommand."1",
		]];

		$reply->keyboard['inline_keyboard'][] = [[
			'text' => "Перевод",
			'callback_data' => static::$modeCommand."2",
		]];

		$reply->keyboard['inline_keyboard'][] = [[
			'text' => "Обратный перевод",
			'callback_data' => static::$modeCommand."3",
		]];

		$memory->theme_id = str_replace(static::$themeCommand, "", $message);
		$memory->mode_id = 0;
		$memory->word_id = 0;

		return [$reply];
	}

	public static function mode($message, &$memory) 
	{
		$memory->mode_id = str_replace(static::$modeCommand, "", $message);
		$memory->word_id = 0;

		$method = "mode".$memory->mode_id;

		return Learn::$method("", $memory);
	}
}	
