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
		$reply->textTelegram = "Yo!😊🖖🏻 \n\nChoose theme:";

		$reply->keyboard['inline_keyboard'] = [];

		foreach($themes as $i => $theme) {
			if ($i % 2 !== 0) {
				continue;
			}

			$button = [[
				'text' => $themes[$i]->name,
				'callback_data' => static::$themeCommand.$themes[$i]->theme_id,
			]];

			if (isset($themes[$i + 1])) {
				$button[] = [
					'text' => $themes[$i + 1]->name,
					'callback_data' => static::$themeCommand.$themes[$i + 1]->theme_id,
				];
			}
			$reply->keyboard['inline_keyboard'][] = $button;
		}

		$memory->theme_id = null;
		$memory->mode = null;
		$memory->word_id = 0;

		return [$reply];
	}

	public static function theme($message, &$memory) 
	{
		$reply = new Reply();
		$reply->textTelegram = "Choose mode:";

		$reply->keyboard['inline_keyboard'] = [
			[[
				'text' => "Learn",
				'callback_data' => static::$modeCommand."LEARN",
			]],
			[[
				'text' => "Translate (🇷🇺 to 🇬🇧)",
				'callback_data' => static::$modeCommand."TRANSLATE",
			]],
			[[
				'text' => "Reverse translate (🇬🇧 to 🇷🇺)",
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

		$reply = new Reply();
		$reply->textTelegram = "Let's start:";
		$reply->keyboard['keyboard'] = [
			['⬅️ Back'],
		];

		return [$reply, Learn::next("", $memory)[0]];
	}
}	
