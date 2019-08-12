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
	private static $keyboard = [
		'next' => [
			'text' => 'Next ➡',
			'callback_data' => "/next",
		],
		'translate' => [
			'text' => 'Translate',
			'callback_data' => "/translate",
		],
		'reverse' => [
			'text' => 'Reverse translate',
			'callback_data' => "/reverse",
		],
	];

	private static $translatorLink = "https://translate.google.com/?uact=5&um=1&ie=UTF-8&hl=ru&client=tw-ob#en/ru/";

	public static function next($message, &$memory) {
		$theme = DataMapper::get("theme", $memory->theme_id);
		$words = json_decode($theme->words);

		if (!isset($words[$memory->word_id])) {
			return Main::menu("", $memory);
		}

		$word = $words[$memory->word_id];

		$reply = new Reply();

		if ($memory->mode == "LEARN") {
			$reply->textTelegram = "*$word[0]* - \[$word[1]] — $word[2]";

			$reply->keyboard['inline_keyboard'] = [
				[[
					'text' => '🔊 listen',
					'url' => static::$translatorLink.$word[0],
				],
				static::$keyboard["next"],]
			];

			$memory->word_id = $memory->word_id + 1;
		}

		if ($memory->mode == "TRANSLATE") {
			$reply->textTelegram = $word[0];
			$reply->keyboard['inline_keyboard'] = [[static::$keyboard["translate"]]];
		}

		if ($memory->mode == "REVERSE_TRANSLATE") {
			$reply->textTelegram = $word[2];
			$reply->keyboard['inline_keyboard'] = [[static::$keyboard["reverse"]]];
		} 

		return [$reply];
	}

	public static function translate($message, &$memory) {
		$theme = DataMapper::get("theme", $memory->theme_id);
		$words = json_decode($theme->words);

		if (!isset($words[$memory->word_id])) {
			return Main::menu("", $memory);
		}

		$word = $words[$memory->word_id];

		$reply = new Reply();

		$reply->textTelegram = $word[2];
		$reply->keyboard['inline_keyboard'] = [[static::$keyboard["next"],]];

		if ($memory->mode == "REVERSE_TRANSLATE") {
			$reply->textTelegram = $word[0];
		}


		$memory->word_id = $memory->word_id + 1;

		return [$reply];
	}
}	
