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
		$reply->textTelegram = "First, choose theme:";

		$reply->keyboard['inline_keyboard'] = [];

		foreach ($themes as $theme) {
			$reply->keyboard['inline_keyboard'][] = [[
				'text' => "▫️".$theme->name,
				'callback_data' => static::$themeCommand.$theme->theme_id,
			]];
		}

		$memory->theme_id = null;
		$memory->mode = null;
		$memory->word_id = 0;

		return [$reply];
	}

	public static function theme($message, &$memory) 
	{
		$reply = new Reply();
		$reply->textTelegram = "Next, choose mode:";

		$reply->keyboard['inline_keyboard'] = [
			[[
				'text' => "Learn",
				'callback_data' => static::$modeCommand."LEARN",
			]],
			[[
				'text' => "Translate",
				'callback_data' => static::$modeCommand."TRANSLATE",
			]],
			[[
				'text' => "Reverse translate",
				'callback_data' => static::$modeCommand."REVERSE_TRANSLATE",
			]]
		];

		$memory->theme_id = str_replace(static::$themeCommand, "", $message);
		$memory->mode = null;
		$memory->word_id = 0;

		return [$reply];
	}

	public static function mode($message, &$memory) 
	{
		$memory->mode = str_replace(static::$modeCommand, "", $message);
		$memory->word_id = 0;

		return Learn::next("", $memory);
	}
}	
