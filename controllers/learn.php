<?php
namespace controllers;

use app\Controller;
use app\Memory;
use app\Reply;
use app\DataMapper;

use models\Root;
use models\Photo;
use controllers\Error;
use controllers\Main;

class Learn extends Controller
{
	private static $learnCommand = "/learn";
	private static $translateCommand = "/translate";
	private static $reverseTranslateCommand = "/reverse";

	private static $keyboard = [
		"next" => [[
				"text" => "Next",
				"callback_data" => static::$learnCommand,
			]],
		"translate" => [[
				"text" => "Translate",
				"callback_data" => static::$translateCommand,
			]],
		"reverse" => [[
			"text" => "Reverse translate",
				"callback_data" => static::$reverseCommand,
			]]
	];

	public static function next($message, &$memory) {
		$theme = DataMapper::get("theme", $memory->theme_id);
		$words = json_decode($theme->words);

		if (!isset($words[$memory->word_id])) {
			return Main::menu("", $memory);
		}

		$word = $words[$memory->memory_id];

		$reply = new Reply();
		$reply->textTelegram = $word[0]." - ".$word[1]." - ".$word[2];

		$reply->keyboard['inline_keyboard'] = [$keyboard["next"],$keyboard["translate"],$keyboard["reverse"]];

		if ($memory->mode_id == 2) {
			$reply->keyboard['inline_keyboard'] = [$keyboard["next"],$keyboard["translate"],];
		}

		if ($memory->mode_id == 3) {
			$reply->keyboard['inline_keyboard'] = [$keyboard["next"],$keyboard["reverse"],];
		} 

		$memory->word_id = ($memory->word_id == 0 ? -1 : $memory->word_id) + 1;

		return [$reply];
	}

	public static function translate($message, &$memory) {
		$theme = DataMapper::get("theme", $memory->theme_id);
		$words = json_decode($theme->words);

		if (!isset($words[$memory->word_id])) {
			return Main::menu("", $memory);
		}

		$word = $words[$memory->memory_id];

		$reply = new Reply();
		$reply->textTelegram = $word[0]." - ".$word[1]." - ".$word[2];

		if ($message == $reverseTranslateCommand) {
			$reply->textTelegram = $word[2]." - ".$word[1]." - ".$word[0];			
		}

		$reply->keyboard['inline_keyboard'] = [$keyboard["next"]];

		return [$reply];
	}
}	
